<?php

namespace App\Http\Controllers;

use App\Models\Spj;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SpjReviewController extends Controller
{
    public function show(Spj $spj): View
    {
        $reviewRole = $spj->current_role ?? 'bendahara';
        abort_unless(auth()->user()->hasRole($reviewRole), 403);

        return view('review.show', [
            'spj' => $spj->load(['user', 'approvalHistories.approver', 'revisionHistories']),
        ]);
    }

    public function update(Request $request, Spj $spj): RedirectResponse
    {
        $reviewRole = $spj->current_role ?? 'bendahara';
        abort_unless(auth()->user()->hasRole($reviewRole), 403);

        $data = $request->validate([
            'decision' => ['required', 'in:revision,approved'],
            'note' => ['required_if:decision,revision', 'nullable', 'string', 'max:1000'],
        ]);

        $role = $reviewRole;
        if ($spj->current_role === null && $role === 'bendahara' && $data['decision'] === 'approved') {
            $nextStatus = 'approved';
            $nextRole = null;
        } else {
            [$nextStatus, $nextRole] = match ([$role, $data['decision']]) {
                ['visitor1', 'approved'] => ['visitor2_review', 'visitor2'],
                ['visitor2', 'approved'] => ['kepala_review', 'kepala_dinas'],
                ['kepala_dinas', 'approved'] => ['bendahara_review', 'bendahara'],
                ['bendahara', 'approved'] => ['completed', null],
                [$role, 'revision'] => ['revision_'.$role, null],
                default => abort(422, 'Keputusan tidak valid untuk tahap ini.'),
            };
        }

        DB::transaction(function () use ($data, $nextStatus, $nextRole, $role, $spj): void {
            $spj->update([
                'status' => $nextStatus,
                'current_role' => $nextRole,
                'review_note' => $data['note'] ?? null,
                'reviewed_at' => now(),
            ]);
            $spj->approvalHistories()->create([
                'approver_id' => auth()->id(),
                'role' => $role,
                'status' => $data['decision'],
                'comment' => $data['note'] ?? null,
                'approved_at' => now(),
            ]);

            if ($data['decision'] === 'revision') {
                $spj->revisionHistories()->create([
                    'revision_from_role' => $role,
                    'description' => $data['note'],
                    'revision_date' => now(),
                ]);
            }
        });

        $message = $data['decision'] === 'approved'
            ? 'SPJ berhasil diproses ke tahap berikutnya.'
            : 'SPJ dikembalikan kepada pengusul untuk direvisi.';

        return redirect()->route('review.queue')->with('status', $message);
    }
}
