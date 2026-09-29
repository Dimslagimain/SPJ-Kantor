<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpjWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_spj_moves_through_each_approval_level(): void
    {
        $submitter = User::factory()->create(['role' => 'user']);
        $visitor1 = User::factory()->create(['role' => 'visitor1']);
        $visitor2 = User::factory()->create(['role' => 'visitor2']);
        $kepala = User::factory()->create(['role' => 'kepala_dinas']);
        $bendahara = User::factory()->create(['role' => 'bendahara']);
        $spj = Spj::create([
            'user_id' => $submitter->id,
            'number' => 'SPJ-WORKFLOW-001',
            'title' => 'Pengajuan berjenjang',
            'type' => 'Perjalanan Dinas',
            'unit_name' => 'Sekretariat',
            'submitter_name' => $submitter->name,
            'description' => 'Pengujian alur persetujuan.',
            'submitted_amount' => 250000,
            'activity_date' => '2026-09-11',
            'due_date' => '2026-09-12',
            'status' => 'submitted',
            'current_role' => 'visitor1',
        ]);

        $this->actingAs($visitor1)->put(route('spj.review.update', $spj), ['decision' => 'approved'])
            ->assertRedirect();
        $this->assertDatabaseHas('spjs', ['id' => $spj->id, 'status' => 'visitor2_review', 'current_role' => 'visitor2']);

        $this->actingAs($visitor2)->put(route('spj.review.update', $spj), ['decision' => 'approved'])
            ->assertRedirect();
        $this->actingAs($kepala)->put(route('spj.review.update', $spj), ['decision' => 'approved'])
            ->assertRedirect();
        $this->assertDatabaseHas('spjs', ['id' => $spj->id, 'status' => 'bendahara_review', 'current_role' => 'bendahara']);

        $this->actingAs($bendahara)->put(route('spj.review.update', $spj), ['decision' => 'approved'])
            ->assertRedirect();

        $this->assertDatabaseHas('spjs', ['id' => $spj->id, 'status' => 'completed']);
        $this->assertDatabaseCount('approval_histories', 4);
    }

    public function test_visitor_revision_returns_to_queue_without_forbidden_error(): void
    {
        $submitter = User::factory()->create(['role' => 'user']);
        $visitor = User::factory()->create(['role' => 'visitor1']);
        $spj = Spj::create([
            'user_id' => $submitter->id,
            'number' => 'SPJ-REVISION-001',
            'title' => 'Pengajuan perlu revisi',
            'type' => 'Belanja Barang',
            'unit_name' => 'Sekretariat',
            'submitter_name' => $submitter->name,
            'description' => 'Pengujian request revision.',
            'submitted_amount' => 100000,
            'activity_date' => '2026-09-11',
            'due_date' => '2026-09-12',
            'status' => 'submitted',
            'current_role' => 'visitor1',
        ]);

        $this->actingAs($visitor)->put(route('spj.review.update', $spj), [
            'decision' => 'revision',
            'note' => 'Mohon lengkapi bukti pembayaran.',
        ])->assertRedirect(route('review.queue'));

        $this->assertDatabaseHas('spjs', [
            'id' => $spj->id,
            'status' => 'revision_visitor1',
            'current_role' => null,
            'review_note' => 'Mohon lengkapi bukti pembayaran.',
        ]);
        $this->assertDatabaseHas('revision_histories', [
            'spj_id' => $spj->id,
            'revision_from_role' => 'visitor1',
        ]);
        $this->actingAs($submitter)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Revisi Visitor 1')
            ->assertSee('Mohon lengkapi bukti pembayaran.');

        $this->actingAs($submitter)->put(route('spj.update', $spj), [
            'title' => 'Pengajuan sudah diperbaiki',
            'type' => 'Belanja Barang',
            'unit_name' => 'Sekretariat',
            'description' => 'Bukti pembayaran sudah dilengkapi.',
            'submitted_amount' => 125000,
            'activity_date' => '2026-09-11',
            'due_date' => '2026-09-12',
        ])->assertRedirect(route('spj.show', $spj));

        $this->assertDatabaseHas('spjs', [
            'id' => $spj->id,
            'title' => 'Pengajuan sudah diperbaiki',
            'submitted_amount' => 125000,
        ]);
    }
}
