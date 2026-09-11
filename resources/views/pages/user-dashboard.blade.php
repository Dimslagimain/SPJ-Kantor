<x-app-shell title="Dashboard Pengusul" description="Pantau pengajuan SPJ Anda.">
    <div class="page-head">
        <div><p class="eyebrow">Ruang kerja pengusul</p><h1>Halo, {{ auth()->user()->name }}.</h1><p class="head-copy">Buat pengajuan baru dan pantau proses persetujuan SPJ Anda.</p></div>
        <a class="review-button" href="{{ route('spj.create') }}">+ Ajukan SPJ</a>
    </div>
    <div class="kpi-grid">
        <article class="kpi-card" style="--accent:#0b8f87;--tint:#dff7f1"><div class="kpi-meta"><span>Pengajuan saya</span><span class="kpi-icon">▤</span></div><div class="kpi-value">{{ auth()->user()->spjs()->count() }}</div><div class="kpi-caption">Seluruh pengajuan Anda</div></article>
        <article class="kpi-card" style="--accent:#e8932f;--tint:#fff3df"><div class="kpi-meta"><span>Menunggu review</span><span class="kpi-icon">◷</span></div><div class="kpi-value">{{ auth()->user()->spjs()->where('status', 'submitted')->count() }}</div><div class="kpi-caption">Sedang diperiksa bendahara</div></article>
        <article class="kpi-card" style="--accent:#348fc2;--tint:#e7f4fb"><div class="kpi-meta"><span>Disetujui</span><span class="kpi-icon">✓</span></div><div class="kpi-value">{{ auth()->user()->spjs()->where('status', 'approved')->count() }}</div><div class="kpi-caption">SPJ telah disetujui</div></article>
    </div>
    <section class="panel queue"><div class="panel-header"><div><h2 class="panel-title">Status pengajuan terbaru</h2><p class="panel-subtitle">Pantau keputusan bendahara pada setiap SPJ.</p></div><a class="text-button" href="{{ route('spj.index') }}">Lihat semua →</a></div></section>
</x-app-shell>