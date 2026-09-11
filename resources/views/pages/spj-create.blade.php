<x-app-shell title="Ajukan SPJ" description="Form pengajuan SPJ baru.">
    <div class="page-head"><div><p class="eyebrow">Pengajuan baru</p><h1>Ajukan SPJ</h1><p class="head-copy">Lengkapi data berikut untuk dikirim ke bendahara.</p></div></div>
    <form method="POST" action="{{ route('spj.store') }}" class="panel login-form" style="max-width:780px;padding:24px">
        @csrf
        <label for="title">Uraian kegiatan</label><input id="title" name="title" value="{{ old('title') }}" required>
        <label for="type">Jenis SPJ</label><input id="type" name="type" value="{{ old('type') }}" placeholder="Perjalanan Dinas / Belanja Barang" required>
        <label for="unit_name">Unit kerja</label><input id="unit_name" name="unit_name" value="{{ old('unit_name') }}" required>
        <label for="submitter_nip">NIP</label><input id="submitter_nip" name="submitter_nip" value="{{ old('submitter_nip') }}">
        <label for="submitted_amount">Nilai pengajuan</label><input id="submitted_amount" name="submitted_amount" type="number" min="0" step="0.01" value="{{ old('submitted_amount') }}" required>
        <label for="activity_date">Tanggal kegiatan</label><input id="activity_date" name="activity_date" type="date" value="{{ old('activity_date') }}" required>
        <label for="due_date">Batas pertanggungjawaban</label><input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}" required>
        <label for="description">Deskripsi</label><textarea id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
        <button class="primary-button" type="submit">Kirim pengajuan</button>
    </form>
</x-app-shell>