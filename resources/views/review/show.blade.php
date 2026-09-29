<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Review SPJ oleh bendahara.">
    <title>Review {{ $spj->number }} — SPJ Sehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<main class="content" style="max-width:1100px">
    <a class="text-button" href="{{ route('review.queue') }}">← Kembali ke antrean</a>
    <div class="page-head" style="margin-top:24px">
        <div><p class="eyebrow">{{ ($isOwnerView ?? false) ? 'Detail SPJ' : 'Review '.str_replace('_', ' ', ucfirst(auth()->user()->role)) }} · {{ $spj->number }}</p><h1>{{ $spj->title }}</h1><p class="head-copy">Diajukan oleh {{ $spj->submitter_name }} · Posisi: {{ $spj->statusLabel() }}</p></div>
        <span class="status {{ $spj->statusClass() }}">{{ $spj->statusLabel() }}</span>
    </div>
    @if (session('status'))<div class="form-success">{{ session('status') }}</div>@endif
    @if ($errors->any())<div class="form-error">{{ $errors->first() }}</div>@endif
    <section class="panel" style="padding:24px">
        <div class="dashboard-grid" style="margin-top:0">
            <div><span class="panel-subtitle">Jenis SPJ</span><strong>{{ $spj->type }}</strong></div>
            <div><span class="panel-subtitle">Unit kerja</span><strong>{{ $spj->unit_name }}</strong></div>
            <div><span class="panel-subtitle">Tanggal kegiatan</span><strong>{{ $spj->activity_date->format('d F Y') }}</strong></div>
            <div><span class="panel-subtitle">Nilai diajukan</span><strong>Rp{{ number_format((float) $spj->submitted_amount, 0, ',', '.') }}</strong></div>
            <div><span class="panel-subtitle">NIP</span><strong>{{ $spj->submitter_nip ?: '-' }}</strong></div>
            <div><span class="panel-subtitle">Batas pertanggungjawaban</span><strong>{{ $spj->due_date->format('d F Y') }}</strong></div>
        </div>
        <div style="margin-top:20px"><span class="panel-subtitle">Deskripsi</span><p>{{ $spj->description }}</p></div>
    </section>
    @if (($isOwnerView ?? false) && str_starts_with($spj->status, 'revision_'))
        <details class="panel" style="margin-top:18px;padding:20px">
            <summary style="cursor:pointer;color:var(--teal);font-weight:800">Edit data revisi</summary>
            <form method="POST" action="{{ route('spj.update', $spj) }}" class="login-form" style="margin-top:18px">
                @csrf
                @method('PUT')
                <label for="title">Uraian kegiatan</label><input id="title" name="title" value="{{ old('title', $spj->title) }}" required>
                <label for="type">Jenis SPJ</label><input id="type" name="type" value="{{ old('type', $spj->type) }}" required>
                <label for="unit_name">Unit kerja</label><input id="unit_name" name="unit_name" value="{{ old('unit_name', $spj->unit_name) }}" required>
                <label for="submitter_nip">NIP</label><input id="submitter_nip" name="submitter_nip" value="{{ old('submitter_nip', $spj->submitter_nip) }}">
                <label for="submitted_amount">Nilai pengajuan</label><input id="submitted_amount" name="submitted_amount" type="number" min="0" step="0.01" value="{{ old('submitted_amount', $spj->submitted_amount) }}" required>
                <label for="activity_date">Tanggal kegiatan</label><input id="activity_date" name="activity_date" type="date" value="{{ old('activity_date', $spj->activity_date?->format('Y-m-d')) }}" required>
                <label for="due_date">Batas pertanggungjawaban</label><input id="due_date" name="due_date" type="date" value="{{ old('due_date', $spj->due_date?->format('Y-m-d')) }}" required>
                <label for="description">Deskripsi</label><textarea id="description" name="description" rows="5" required>{{ old('description', $spj->description) }}</textarea>
                <button class="primary-button" type="submit">Simpan perubahan</button>
            </form>
        </details>
        <form method="POST" action="{{ route('spj.resubmit', $spj) }}" style="margin-top:12px">
            @csrf
            <button class="primary-button" type="submit">Kirim ulang setelah revisi</button>
        </form>
    @endif
    @if (! ($isOwnerView ?? false) && ($spj->isAwaitingReview() || $spj->status === 'submitted'))
        <form method="POST" action="{{ route('spj.review.update', $spj) }}" class="panel login-form" style="padding:24px;margin-top:18px">
            @csrf
            @method('PUT')
            <label for="note">Catatan review atau revisi</label><textarea id="note" name="note" rows="4" placeholder="Wajib diisi saat meminta revisi">{{ old('note') }}</textarea>
            <div style="display:flex;gap:10px"><button class="review-button" type="submit" name="decision" value="revision" onclick="document.getElementById('note').required = true">Minta revisi</button><button class="primary-button" type="submit" name="decision" value="approved" onclick="document.getElementById('note').required = false">Setujui SPJ</button></div>
        </form>
    @elseif ($spj->review_note)
        <section class="panel" style="padding:24px;margin-top:18px"><span class="panel-subtitle">Catatan review</span><p>{{ $spj->review_note }}</p></section>
    @endif
    <div class="dashboard-grid" style="margin-top:18px">
        <section class="panel" style="padding:24px"><h2 class="panel-title">Approval history</h2>@forelse ($spj->approvalHistories as $history)<p style="border-bottom:1px solid var(--line);padding:11px 0;margin:0"><strong>{{ ucfirst(str_replace('_', ' ', $history->role)) }}</strong> · {{ ucfirst($history->status) }}<br><span class="panel-subtitle">{{ $history->comment ?: 'Tidak ada catatan' }} · {{ $history->created_at->format('d/m/Y H:i') }}</span></p>@empty<p class="panel-subtitle">Belum ada histori approval.</p>@endforelse</section>
        <section class="panel" style="padding:24px"><h2 class="panel-title">Revision history</h2>@forelse ($spj->revisionHistories as $revision)<p style="border-bottom:1px solid var(--line);padding:11px 0;margin:0"><strong>{{ ucfirst(str_replace('_', ' ', $revision->revision_from_role)) }}</strong><br><span class="panel-subtitle">{{ $revision->description }} · {{ $revision->revision_date->format('d/m/Y H:i') }}</span></p>@empty<p class="panel-subtitle">Belum ada histori revisi.</p>@endforelse</section>
    </div>
</main>
</body>
</html>
