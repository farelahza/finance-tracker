<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Catatan: RefreshDatabase memakai database test (phpunit.xml: sqlite :memory:),
 * bukan database aplikasimu. Storage disk 'public' di-fake sehingga tidak ada file asli yang dibuat.
 */
class ProfilePhotoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    /** PNG 1x1 yang valid, jadi tidak butuh ekstensi GD. */
    private function pngFile(string $name = 'foto.png'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $name,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==')
        );
    }

    private function payload(User $user, array $extra = []): array
    {
        return array_merge(['name' => $user->name, 'email' => $user->email], $extra);
    }

    public function test_profile_page_renders(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Profil Saya');
    }

    public function test_user_can_upload_photo(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('profile.update'), $this->payload($user, ['photo' => $this->pngFile()]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'));

        $path = $user->fresh()->profile_photo_path;
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
        $this->assertStringContainsString('storage/' . $path, $user->fresh()->profile_photo_url);
    }

    public function test_replacing_photo_deletes_the_old_file(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, ['photo' => $this->pngFile('a.png')]));
        $old = $user->fresh()->profile_photo_path;

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, ['photo' => $this->pngFile('b.png')]));
        $new = $user->fresh()->profile_photo_path;

        $this->assertNotSame($old, $new);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($new);
    }

    public function test_user_can_remove_photo(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, ['photo' => $this->pngFile()]));
        $path = $user->fresh()->profile_photo_path;

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, ['remove_photo' => 1]))
            ->assertSessionHasNoErrors();

        $this->assertNull($user->fresh()->profile_photo_path);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_updating_name_without_photo_keeps_existing_photo(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, ['photo' => $this->pngFile()]));
        $path = $user->fresh()->profile_photo_path;

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Nama Baru', 'email' => $user->email, 'remove_photo' => 0,
        ])->assertSessionHasNoErrors();

        $this->assertSame('Nama Baru', $user->fresh()->name);
        $this->assertSame($path, $user->fresh()->profile_photo_path);
    }

    public function test_rejects_non_image_and_oversized_files(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, [
            'photo' => UploadedFile::fake()->createWithContent('doc.pdf', '%PDF-1.4 bukan gambar'),
        ]))->assertSessionHasErrors('photo');

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, [
            'photo' => UploadedFile::fake()->create('besar.png', 3000, 'image/png'),
        ]))->assertSessionHasErrors('photo');

        $this->assertNull($user->fresh()->profile_photo_path);
    }

    public function test_cannot_set_photo_path_by_posting_it_directly(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, [
            'profile_photo_path' => 'profile-photos/milik-orang-lain.png',
        ]));

        $this->assertNull($user->fresh()->profile_photo_path);
    }

    public function test_deleting_account_removes_photo_file(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), $this->payload($user, ['photo' => $this->pngFile()]));
        $path = $user->fresh()->profile_photo_path;

        $this->actingAs($user)->delete(route('profile.destroy'), ['password' => 'password'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        Storage::disk('public')->assertMissing($path);
    }
}