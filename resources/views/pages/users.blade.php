<x-app-shell title="Kelola User" description="Kelola akun user aplikasi SPJ.">
    <div class="page-head"><div><p class="eyebrow">Administrasi akun</p><h1>Kelola user</h1><p class="head-copy">Tambahkan user yang dapat mengajukan dan memantau SPJ.</p></div></div>
    @if (session('status'))<div class="form-success">{{ session('status') }}</div>@endif
    @if ($errors->any())<div class="form-error">{{ $errors->first() }}</div>@endif
    <div class="dashboard-grid">
        <form method="POST" action="{{ route('users.store') }}" class="panel login-form" style="padding:24px">
            @csrf
            <h2 class="panel-title">Tambah user</h2>
            <label for="name">Nama</label><input id="name" name="name" value="{{ old('name') }}" required>
            <label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required>
            <label for="password">Password</label><input id="password" name="password" type="password" minlength="8" required>
            <label for="password_confirmation">Konfirmasi password</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required>
            <label for="role">Role</label><select id="role" name="role" required><option value="user" @selected(old('role', 'user') === 'user')>User / Pengaju SPJ</option><option value="visitor1" @selected(old('role') === 'visitor1')>Visitor 1</option><option value="visitor2" @selected(old('role') === 'visitor2')>Visitor 2</option><option value="kepala_dinas" @selected(old('role') === 'kepala_dinas')>Kepala Dinas</option><option value="bendahara" @selected(old('role') === 'bendahara')>Bendahara</option></select>
            <button class="primary-button" type="submit">Tambah user</button>
        </form>
        <section class="panel queue"><div class="panel-header"><div><h2 class="panel-title">Daftar akun</h2><p class="panel-subtitle">{{ $users->count() }} akun terdaftar</p></div></div><div class="table-wrap"><table class="queue-table"><thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead><tbody>@forelse ($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ ['user' => 'User / Pengaju SPJ', 'visitor1' => 'Visitor 1', 'visitor2' => 'Visitor 2', 'kepala_dinas' => 'Kepala Dinas', 'bendahara' => 'Bendahara'][$user->role] ?? $user->role }}</td><td>@if (! $user->isBendahara())<form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')<button class="text-button" type="submit">Hapus</button></form>@else<span class="panel-subtitle">Dilindungi</span>@endif</td></tr>@empty<tr><td colspan="4">Belum ada user.</td></tr>@endforelse</tbody></table></div></section>
    </div>
</x-app-shell>