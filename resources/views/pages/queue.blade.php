<x-app-shell title="{{ auth()->user()->isBendahara() ? 'Antrean Review' : 'Pantau SPJ' }}" description="Pantau status pengajuan SPJ.">
    <div class="page-head">
        <div>
            <p class="eyebrow">{{ auth()->user()->isReviewer() ? 'Ruang kerja reviewer' : 'Ruang kerja pengusul' }}</p>
            <h1>{{ auth()->user()->isReviewer() ? 'Antrean review' : 'Pantau pengajuan SPJ' }}</h1>
            <p class="head-copy">{{ auth()->user()->isReviewer() ? 'Tinjau SPJ yang sedang berada pada tahap Anda.' : 'Pantau posisi SPJ Anda dalam alur persetujuan.' }}</p>
        </div>
        @if (auth()->user()->hasRole('user'))
            <a class="review-button" href="{{ route('spj.create') }}">+ Ajukan SPJ</a>
        @endif
    </div>
    <section class="panel queue">
        <div class="panel-header"><div><h2 class="panel-title">{{ $spjs->count() }} pengajuan</h2><p class="panel-subtitle">Status dan posisi proses diperbarui setiap ada keputusan.</p></div></div>
        <div class="table-wrap"><table class="queue-table"><thead><tr><th>Nomor SPJ</th><th>Uraian</th><th>Pengusul</th><th>Nilai</th><th>Status</th>@if (auth()->user()->isReviewer())<th>Aksi</th>@endif</tr></thead><tbody>
            @forelse ($spjs as $spj)
                <tr><td><a class="text-button" href="{{ route('spj.show', $spj) }}">{{ $spj->number }}</a></td><td>{{ $spj->title }}</td><td>{{ $spj->submitter_name }}</td><td class="amount">Rp{{ number_format((float) $spj->submitted_amount, 0, ',', '.') }}</td><td><span class="status {{ $spj->statusClass() }}">{{ $spj->statusLabel() }}</span></td>@if (auth()->user()->isReviewer())<td><a class="review-button" href="{{ route('spj.review.show', $spj->id) }}">{{ auth()->user()->role === 'bendahara' ? 'Lihat detail' : 'Mulai review' }}</a></td>@else @if (str_starts_with($spj->status, 'revision_'))<td><form method="POST" action="{{ route('spj.resubmit', $spj) }}">@csrf<button class="review-button" type="submit">Kirim ulang</button></form></td>@endif @endif</tr>
            @empty
                <tr><td colspan="{{ auth()->user()->isReviewer() ? 6 : 5 }}">Belum ada SPJ untuk ditampilkan.</td></tr>
            @endforelse
        </tbody></table></div>
    </section>
</x-app-shell>
