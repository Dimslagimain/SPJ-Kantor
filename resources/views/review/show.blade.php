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
        <div><p class="eyebrow">Review bendahara · {{ $spj->status }}</p><h1>{{ $spj->title }}</h1><p class="head-copy">{{ $spj->number }} · Diajukan oleh {{ $spj->submitter_name }}</p></div>
        <span class="status {{ $spj->status === 'approved' ? 'ready' : ($spj->status === 'revision' ? 'revision' : 'review') }}">{{ $spj->status === 'approved' ? 'Approved' : ($spj->status === 'revision' ? 'Perlu revisi' : 'Menunggu review') }}</span>
    </div>
    @if (session('status'))<div class="form-success">{{ session('status') }}</div>@endif
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
    @if ($spj->status === 'submitted')
        <form method="POST" action="{{ route('spj.review.update', $spj) }}" class="panel login-form" style="padding:24px;margin-top:18px">
            @csrf
            @method('PUT')
            <label for="note">Catatan review</label><textarea id="note" name="note" rows="4" placeholder="Tambahkan catatan jika diperlukan">{{ old('note') }}</textarea>
            <div style="display:flex;gap:10px"><button class="review-button" type="submit" name="decision" value="revision">Minta revisi</button><button class="primary-button" type="submit" name="decision" value="approved">Setujui SPJ</button></div>
        </form>
    @elseif ($spj->review_note)
        <section class="panel" style="padding:24px;margin-top:18px"><span class="panel-subtitle">Catatan review</span><p>{{ $spj->review_note }}</p></section>
    @endif
</main>
</body>
</html>
