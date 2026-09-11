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
        $data['submitted_at'] = now();

        Spj::create($data);

        return redirect()->route('spj.index')->with('status', 'SPJ berhasil diajukan untuk diperiksa bendahara.');
    }
}
