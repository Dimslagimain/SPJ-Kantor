<x-app-shell title="Dashboard Reviewer" description="Pantau perjalanan SPJ pada setiap tahap approval.">
    <div class="page-head">
        <div>
            <p class="eyebrow">Ruang kerja reviewer</p>
            <h1>Dashboard {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</h1>
            <p class="head-copy">Pantau SPJ yang sedang berjalan dan tindak lanjuti pengajuan yang berada pada tahap Anda.</p>
        </div>
        <a class="review-button" href="{{ route('review.queue') }}">Buka antrean review</a>
    </div>
    <div class="kpi-grid">
        <article class="kpi-card" style="--accent:#0b8f87;--tint:#dff7f1"><div class="kpi-meta"><span>SPJ sedang berjalan</span><span class="kpi-icon">◫</span></div><div class="kpi-value">{{ $totalInProgress }}</div><div class="kpi-caption">Belum selesai diproses</div></article>
        <article class="kpi-card" style="--accent:#e8932f;--tint:#fff3df"><div class="kpi-meta"><span>Menunggu Anda</span><span class="kpi-icon">◷</span></div><div class="kpi-value">{{ $waitingForReviewer }}</div><div class="kpi-caption">SPJ pada tahap {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div></article>
        <article class="kpi-card" style="--accent:#d75462;--tint:#ffedf0"><div class="kpi-meta"><span>Perlu revisi</span><span class="kpi-icon">!</span></div><div class="kpi-value">{{ $revisionSpjs }}</div><div class="kpi-caption">Menunggu perbaikan pengusul</div></article>
    </div>
    <section class="panel queue">
        <div class="panel-header"><div><h2 class="panel-title">SPJ yang sedang berjalan</h2><p class="panel-subtitle">Pantauan status terbaru dari seluruh alur approval.</p></div></div>
        <div class="table-wrap"><table class="queue-table"><thead><tr><th>Nomor SPJ</th><th>Uraian</th><th>Pengusul</th><th>Nilai</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            @forelse ($recentSpjs as $spj)
                <tr><td>{{ $spj->number }}</td><td>{{ $spj->title }}</td><td>{{ $spj->submitter_name }}</td><td class="amount">Rp{{ number_format((float) $spj->submitted_amount, 0, ',', '.') }}</td><td><span class="status {{ $spj->statusClass() }}">{{ $spj->statusLabel() }}</span></td><td>@if ($spj->current_role === auth()->user()->role)<a class="review-button" href="{{ route('spj.review.show', $spj) }}">Review</a>@else<span class="panel-subtitle">Dipantau</span>@endif</td></tr>
            @empty
                <tr><td colspan="6">Belum ada SPJ yang sedang berjalan.</td></tr>
            @endforelse
        </tbody></table></div>
    </section>
</x-app-shell>