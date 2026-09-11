<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_user_can_login_and_cannot_access_bendahara_features(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/');
        $this->get('/antrean-review')->assertOk();
        $this->get('/laporan')->assertForbidden();
    }

    public function test_bendahara_can_access_review_and_reports(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara']);

        $this->actingAs($bendahara)->get('/antrean-review')->assertOk();
        $this->actingAs($bendahara)->get('/laporan')->assertOk();
    }

    public function test_user_can_submit_spj(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/spj', [
            'title' => 'Perjalanan dinas',
            'type' => 'Perjalanan Dinas',
            'unit_name' => 'Sekretariat',
            'description' => 'Kegiatan koordinasi.',
            'submitted_amount' => 1500000,
            'activity_date' => '2026-09-11',
            'due_date' => '2026-09-12',
        ])->assertRedirect('/spj');

        $this->assertDatabaseHas('spjs', ['user_id' => $user->id, 'status' => 'submitted']);
    }

    public function test_bendahara_approval_updates_spj_status(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $bendahara = User::factory()->create(['role' => 'bendahara']);
        $spj = Spj::create([
            'user_id' => $user->id,
            'number' => 'SPJ-TEST-001',
            'title' => 'Pengajuan test approval',
            'type' => 'Belanja Barang',
            'unit_name' => 'Unit Test',
            'submitter_name' => $user->name,
            'description' => 'Data untuk pengujian approval.',
            'submitted_amount' => 100000,
            'activity_date' => '2026-09-11',
            'due_date' => '2026-09-12',
            'status' => 'submitted',
        ]);

        $this->actingAs($bendahara)->put(route('spj.review.update', $spj), [
            'decision' => 'approved',
            'note' => 'Berkas lengkap.',
        ])->assertRedirect(route('spj.review.show', $spj));

        $this->assertDatabaseHas('spjs', [
            'id' => $spj->id,
            'status' => 'approved',
            'review_note' => 'Berkas lengkap.',
        ]);
    }

    public function test_only_bendahara_can_manage_users(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $bendahara = User::factory()->create(['role' => 'bendahara']);

        $this->actingAs($user)->get(route('users.index'))->assertForbidden();

        $this->actingAs($bendahara)->post(route('users.store'), [
            'name' => 'User Baru',
            'email' => 'baru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('users.index'));

        $managedUser = User::where('email', 'baru@example.com')->firstOrFail();
        $this->assertSame('user', $managedUser->role);

        $this->actingAs($bendahara)->delete(route('users.destroy', $managedUser))
            ->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $managedUser->id]);
    }
}
