<x-app-shell title="Pengaturan User" description="Ubah profil dan password akun.">
    <div class="page-head"><div><p class="eyebrow">Akun Anda</p><h1>Pengaturan user</h1><p class="head-copy">Perbarui informasi profil dan password untuk menjaga akun tetap aman.</p></div></div>
    @if (session('status'))<div class="form-success">{{ session('status') }}</div>@endif
    @if ($errors->any())<div class="form-error">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('settings.update') }}" class="panel login-form" style="max-width:780px;padding:24px">
        @csrf
        @method('PUT')
        <h2 class="panel-title">Profil</h2>
        <label for="name">Nama</label><input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
        <label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required>
        <h2 class="panel-title" style="margin-top:14px">Ubah password</h2>
        <label for="current_password">Password saat ini</label><input id="current_password" name="current_password" type="password">
        <label for="password">Password baru</label><input id="password" name="password" type="password" minlength="8">
        <label for="password_confirmation">Konfirmasi password baru</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="8">
        <button class="primary-button" type="submit">Simpan perubahan</button>
    </form>
</x-app-shell>
