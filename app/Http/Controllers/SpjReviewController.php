<?php

namespace App\Http\Controllers;

use App\Models\Spj;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpjReviewController extends Controller
{
    public function show(Spj $spj): View
    {
        return view('review.show', compact('spj'));
    }

    public function update(Request $request, Spj $spj): RedirectResponse
    {
        $data = $request->validate([
            'decision' => ['required', 'in:revision,approved'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $spj->update([
            'status' => $data['decision'],
            'review_note' => $data['note'] ?? null,
            'reviewed_at' => now(),
        ]);

        $message = $data['decision'] === 'approved'
            ? 'SPJ berhasil disetujui.'
            : 'SPJ dikembalikan kepada pengusul untuk direvisi.';

        return redirect()->route('spj.review.show', $spj)->with('status', $message);
    }
}
