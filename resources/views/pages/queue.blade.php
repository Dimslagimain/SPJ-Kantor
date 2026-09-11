<x-app-shell title="{{ auth()->user()->isBendahara() ? 'Antrean Review' : 'Pantau SPJ' }}" description="Pantau status pengajuan SPJ.">
    <div class="page-head">
        <div>
            <p class="eyebrow">{{ auth()->user()->isBendahara() ? 'Ruang kerja bendahara' : 'Ruang kerja pengusul' }}</p>
            <h1>{{ auth()->user()->isBendahara() ? 'Antrean review' : 'Pantau pengajuan SPJ' }}</h1>
            <p class="head-copy">{{ auth()->user()->isBendahara() ? 'Tinjau SPJ yang menunggu persetujuan.' : 'Pantau apakah SPJ Anda sudah disetujui atau masih menunggu pemeriksaan.' }}</p>
        </div>
        @if (! auth()->user()->isBendahara())
            <a class="review-button" href="{{ route('spj.create') }}">+ Ajukan SPJ</a>
        @endif
    </div>
    <section class="panel queue">
        <div class="panel-header"><div><h2 class="panel-title">{{ $spjs->count() }} pengajuan</h2><p class="panel-subtitle">Status diperbarui setelah pemeriksaan bendahara.</p></div></div>
        <div class="table-wrap"><table class="queue-table"><thead><tr><th>Nomor SPJ</th><th>Uraian</th><th>Pengusul</th><th>Nilai</th><th>Status</th>@if (auth()->user()->isBendahara())<th>Aksi</th>@endif</tr></thead><tbody>
            @forelse ($spjs as $spj)
                <tr><td>{{ $spj->number }}</td><td>{{ $spj->title }}</td><td>{{ $spj->submitter_name }}</td><td class="amount">Rp{{ number_format((float) $spj->submitted_amount, 0, ',', '.') }}</td><td><span class="status {{ $spj->status === 'approved' ? 'ready' : ($spj->status === 'revision' ? 'revision' : 'review') }}">{{ $spj->status === 'approved' ? 'Disetujui' : ($spj->status === 'revision' ? 'Perlu revisi' : 'Menunggu review') }}</span></td>@if (auth()->user()->isBendahara())<td><a class="review-button" href="{{ route('spj.review.show', $spj->id) }}">Mulai review</a></td>@endif</tr>
            @empty
                <tr><td colspan="{{ auth()->user()->isBendahara() ? 6 : 5 }}">Belum ada SPJ untuk ditampilkan.</td></tr>
            @endforelse
        </tbody></table></div>
    </section>
</x-app-shell>
