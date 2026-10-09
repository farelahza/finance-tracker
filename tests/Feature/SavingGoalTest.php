<?php

namespace Tests\Feature;

use App\Models\SavingsContribution;
use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * PERHATIAN: RefreshDatabase mengosongkan database yang dipakai saat test.
 * Pastikan phpunit.xml memakai database khusus test (mis. sqlite ::memory:),
 * BUKAN database aplikasi yang berisi data aslimu.
 */
class SavingsGoalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function goalFor(User $user, int $target = 1000000, array $extra = []): SavingsGoal
    {
        return SavingsGoal::forceCreate(array_merge([
            'user_id'       => $user->id,
            'name'          => 'Laptop Baru',
            'target_amount' => $target,
        ], $extra));
    }

    private function contributionFor(SavingsGoal $goal, int $amount): SavingsContribution
    {
        return $goal->contributions()->create([
            'amount'   => $amount,
            'saved_at' => now()->toDateString(),
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('savings-goals.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_create_goal_and_progress_starts_at_zero(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($user)->post(route('savings-goals.store'), [
            'name'          => 'Motor',
            'target_amount' => 20000000,
            'user_id'       => $other->id,   // harus diabaikan
        ])->assertSessionHasNoErrors();

        $goal = SavingsGoal::firstOrFail();
        $this->assertSame($user->id, $goal->user_id);
        $this->assertSame(0, $goal->saved_amount);
        $this->assertSame(0, $goal->progress_percent);
        $this->assertSame('Berjalan', $goal->status_label);
    }

    public function test_goal_validation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('savings-goals.store'), ['target_amount' => 100])
            ->assertSessionHasErrors('name');

        foreach ([0, -5, 'abc', '1.5'] as $bad) {
            $this->actingAs($user)->post(route('savings-goals.store'), [
                'name' => 'X', 'target_amount' => $bad,
            ])->assertSessionHasErrors('target_amount');
        }

        $this->assertSame(0, SavingsGoal::count());
    }

    public function test_pages_render_for_owner(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user);
        $this->contributionFor($goal, 250000);

        $this->actingAs($user)->get(route('savings-goals.index'))->assertOk()->assertSee('Laptop Baru');
        $this->actingAs($user)->get(route('savings-goals.create'))->assertOk();
        $this->actingAs($user)->get(route('savings-goals.show', $goal->id))->assertOk()->assertSee('250.000');
        $this->actingAs($user)->get(route('savings-goals.edit', $goal->id))->assertOk();
    }

    public function test_adding_contribution_updates_totals(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user, 1000000);

        $this->actingAs($user)->post(route('savings-goals.contributions.store', $goal->id), [
            'amount' => 250000, 'saved_at' => now()->toDateString(), 'note' => 'gaji',
        ])->assertSessionHasNoErrors();

        $goal = $goal->fresh();
        $this->assertSame(250000, $goal->saved_amount);
        $this->assertSame(750000, $goal->remaining_amount);
        $this->assertSame(25, $goal->progress_percent);
        $this->assertFalse($goal->is_achieved);
    }

    public function test_contribution_must_be_positive(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user);

        foreach ([0, -100, 'abc'] as $bad) {
            $this->actingAs($user)->post(route('savings-goals.contributions.store', $goal->id), [
                'amount' => $bad, 'saved_at' => now()->toDateString(),
            ])->assertSessionHasErrorsIn('contribution', 'amount');
        }

        $this->assertSame(0, SavingsContribution::count());
    }

    public function test_exceeding_target_is_achieved_with_capped_percent(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user, 1000000);
        $this->contributionFor($goal, 1200000);

        $goal = $goal->fresh();
        $this->assertTrue($goal->is_achieved);
        $this->assertSame('Tercapai', $goal->status_label);
        $this->assertSame(100, $goal->progress_percent);
        $this->assertSame(0, $goal->remaining_amount);
        $this->assertSame(200000, $goal->surplus_amount);
    }

    public function test_deleting_contribution_recalculates_total(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user, 1000000);
        $this->contributionFor($goal, 400000);
        $second = $this->contributionFor($goal, 100000);

        $this->actingAs($user)
            ->delete(route('savings-goals.contributions.destroy', [$goal->id, $second->id]))
            ->assertRedirect();

        $this->assertSame(400000, $goal->fresh()->saved_amount);
    }

    public function test_deleting_goal_removes_its_contributions(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user);
        $this->contributionFor($goal, 1000);

        $this->actingAs($user)->delete(route('savings-goals.destroy', $goal->id))->assertRedirect();

        $this->assertSame(0, SavingsGoal::count());
        $this->assertSame(0, SavingsContribution::count());
    }

    public function test_user_cannot_access_other_users_goal(): void
    {
        $owner    = User::factory()->create();
        $intruder = User::factory()->create();
        $goal     = $this->goalFor($owner);
        $entry    = $this->contributionFor($goal, 5000);

        $this->actingAs($intruder);

        $this->get(route('savings-goals.show', $goal->id))->assertNotFound();
        $this->get(route('savings-goals.edit', $goal->id))->assertNotFound();
        $this->put(route('savings-goals.update', $goal->id), ['name' => 'Hack', 'target_amount' => 1])->assertNotFound();
        $this->delete(route('savings-goals.destroy', $goal->id))->assertNotFound();
        $this->post(route('savings-goals.contributions.store', $goal->id), [
            'amount' => 1000, 'saved_at' => now()->toDateString(),
        ])->assertNotFound();
        $this->delete(route('savings-goals.contributions.destroy', [$goal->id, $entry->id]))->assertNotFound();

        $this->assertSame('Laptop Baru', $goal->fresh()->name);
        $this->assertSame(5000, $goal->fresh()->saved_amount);
    }

    public function test_index_only_lists_own_goals(): void
    {
        $me    = User::factory()->create();
        $other = User::factory()->create();
        $this->goalFor($me, 1000, ['name' => 'Punya Saya']);
        $this->goalFor($other, 1000, ['name' => 'Punya Orang Lain']);

        $this->actingAs($me)->get(route('savings-goals.index'))
            ->assertSee('Punya Saya')
            ->assertDontSee('Punya Orang Lain');
    }

    public function test_cannot_delete_contribution_of_another_goal_via_own_goal_url(): void
    {
        $user      = User::factory()->create();
        $goalA     = $this->goalFor($user, 1000, ['name' => 'A']);
        $goalB     = $this->goalFor($user, 1000, ['name' => 'B']);
        $entryOfB  = $this->contributionFor($goalB, 500);

        $this->actingAs($user)
            ->delete(route('savings-goals.contributions.destroy', [$goalA->id, $entryOfB->id]))
            ->assertNotFound();

        $this->assertSame(500, $goalB->fresh()->saved_amount);
    }

    public function test_savings_do_not_touch_transactions_table(): void
    {
        if (! Schema::hasTable('transactions')) {
            $this->markTestSkipped('Tabel transactions tidak ada di database test.');
        }

        $user   = User::factory()->create();
        $goal   = $this->goalFor($user);
        $before = DB::table('transactions')->count();

        $this->actingAs($user)->post(route('savings-goals.contributions.store', $goal->id), [
            'amount' => 300000, 'saved_at' => now()->toDateString(),
        ])->assertSessionHasNoErrors();

        $this->assertSame($before, DB::table('transactions')->count());
    }
}