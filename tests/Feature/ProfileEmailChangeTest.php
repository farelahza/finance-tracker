<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Database test = sqlite :memory: (phpunit.xml), bukan database aplikasi. */
class ProfileEmailChangeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_changing_email_requires_current_password(): void
    {
        $user = User::factory()->create(['email' => 'lama@example.com']);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name, 'email' => 'baru@example.com',
        ])->assertSessionHasErrors('current_password');

        $this->assertSame('lama@example.com', $user->fresh()->email);
    }

    public function test_wrong_password_does_not_change_email(): void
    {
        $user = User::factory()->create(['email' => 'lama@example.com']);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name, 'email' => 'baru@example.com', 'current_password' => 'salah-banget',
        ])->assertSessionHasErrors('current_password');

        $this->assertSame('lama@example.com', $user->fresh()->email);
    }

    public function test_correct_password_changes_email_and_resets_verification(): void
    {
        $user = User::factory()->create(['email' => 'lama@example.com']);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name, 'email' => 'baru@example.com', 'current_password' => 'password',
        ])->assertSessionHasNoErrors()->assertRedirect(route('profile.edit'));

        $fresh = $user->fresh();
        $this->assertSame('baru@example.com', $fresh->email);
        $this->assertNull($fresh->email_verified_at);
    }

    public function test_changing_only_the_name_needs_no_password(): void
    {
        $user = User::factory()->create(['email' => 'lama@example.com']);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Nama Baru', 'email' => 'lama@example.com',
        ])->assertSessionHasNoErrors();

        $this->assertSame('Nama Baru', $user->fresh()->name);
    }
}