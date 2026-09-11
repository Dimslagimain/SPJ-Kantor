<x-app-shell title="Laporan" description="Laporan pertanggungjawaban SPJ.">
    <div class="page-head"><div><p class="eyebrow">Laporan aktual</p><h1>Laporan SPJ</h1><p class="head-copy">Ringkasan berdasarkan data pengajuan yang tersimpan.</p></div></div>
    <div class="kpi-grid">
        <article class="kpi-card" style="--accent:#0b8f87;--tint:#dff7f1"><div class="kpi-meta"><span>Nilai disetujui</span><span class="kpi-icon">Rp</span></div><div class="kpi-value">Rp{{ number_format((float) $approvedAmount, 0, ',', '.') }}</div><div class="kpi-caption">{{ $approvedCount }} SPJ approved</div></article>
        <article class="kpi-card" style="--accent:#348fc2;--tint:#e7f4fb"><div class="kpi-meta"><span>Total nilai diajukan</span><span class="kpi-icon">Rp</span></div><div class="kpi-value">Rp{{ number_format((float) $totalAmount, 0, ',', '.') }}</div><div class="kpi-caption">Seluruh pengajuan user</div></article>
        <article class="kpi-card" style="--accent:#d75462;--tint:#ffedf0"><div class="kpi-meta"><span>Perlu revisi</span><span class="kpi-icon">!</span></div><div class="kpi-value">{{ $revisionCount }}</div><div class="kpi-caption">Menunggu perbaikan</div></article>
    </div>
</x-app-shell>
