<?php

namespace App\Http\Controllers;

use App\Models\Spj;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpjController extends Controller
{
    public function create(): View
    {
        return view('pages.spj-create');
    }

    public function show(Request $request, Spj $spj): View
    {
        abort_unless($request->user()->isBendahara() || $spj->user_id === $request->user()->id, 403);

        return view('review.show', [
            'spj' => $spj->load(['user', 'approvalHistories.approver', 'revisionHistories']),
            'isOwnerView' => true,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'unit_name' => ['required', 'string', 'max:255'],
            'submitter_nip' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'submitted_amount' => ['required', 'numeric', 'min:0'],
            'activity_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:activity_date'],
        ]);

        $data['user_id'] = $request->user()->id;
        $data['number'] = 'SPJ-'.now()->format('YmdHis').'-'.random_int(100, 999);
        $data['submitter_name'] = $request->user()->name;
        $data['status'] = 'submitted';
        $data['current_role'] = 'visitor1';
        $data['submitted_at'] = now();

        $spj = Spj::create($data);
        $spj->approvalHistories()->create([
            'approver_id' => $request->user()->id,
            'role' => 'user',
            'status' => 'submitted',
            'approved_at' => now(),
        ]);

        return redirect()->route('spj.index')->with('status', 'SPJ berhasil diajukan untuk diperiksa Visitor 1.');
    }

    public function update(Request $request, Spj $spj): RedirectResponse
    {
        abort_unless($spj->user_id === $request->user()->id, 403);
        abort_unless(str_starts_with($spj->status, 'revision_'), 422);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'unit_name' => ['required', 'string', 'max:255'],
            'submitter_nip' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'submitted_amount' => ['required', 'numeric', 'min:0'],
            'activity_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:activity_date'],
        ]);

        $spj->update($data);

        return redirect()->route('spj.show', $spj)->with('status', 'Perubahan SPJ berhasil disimpan.');
    }

    public function resubmit(Request $request, Spj $spj): RedirectResponse
    {
        abort_unless($spj->user_id === $request->user()->id, 403);
        abort_unless(str_starts_with($spj->status, 'revision_'), 422);

        $revision = $spj->revisionHistories()->whereNull('resolved_date')->latest('revision_date')->first();
        $role = $revision?->revision_from_role ?? 'visitor1';

        $spj->update([
            'status' => $role.'_review',
            'current_role' => $role,
            'submitted_at' => now(),
            'review_note' => null,
        ]);
        $revision?->update(['resolved_date' => now()]);
        $spj->approvalHistories()->create([
            'approver_id' => $request->user()->id,
            'role' => 'user',
            'status' => 'resubmitted',
            'approved_at' => now(),
        ]);

        return redirect()->route('spj.index')->with('status', 'SPJ berhasil dikirim ulang untuk diperiksa.');
    }
}
