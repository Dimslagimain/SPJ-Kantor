<x-app-shell title="Kelola User" description="Kelola akun user aplikasi SPJ.">
    <div class="page-head">
        <div>
            <p class="eyebrow">Administrasi akun</p>
            <h1>Kelola User</h1>
            <p class="head-copy">Tambahkan, pantau, dan hapus akun yang dapat mengajukan atau mereview SPJ.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="form-success">
            <svg style="width:15px;height:15px;display:inline;margin-right:6px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="form-error">
            <svg style="width:15px;height:15px;display:inline;margin-right:6px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="users-layout">
        {{-- Form Panel (sticky) --}}
        <form method="POST" action="{{ route('users.store') }}" class="user-form-panel" id="add-user-form">
            @csrf
            <div class="panel-header" style="padding:0 0 20px; border-bottom:1px solid var(--line); margin-bottom:24px;">
                <div>
                    <h2 class="panel-title" style="margin:0">Tambah User Baru</h2>
                    <p class="panel-subtitle">Isi semua kolom untuk menambah akun</p>
                </div>
                <span style="display:grid;place-items:center;width:36px;height:36px;border-radius:10px;background:var(--mint);color:var(--teal-dark)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                </span>
            </div>

            <div class="form-group">
                <label for="name">
                    <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Nama Lengkap
                </label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="cth. Budi Santoso" autocomplete="name" required>
            </div>

            <div class="form-group">
                <label for="email">
                    <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Email
                </label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="cth. budi@dinkes.go.id" autocomplete="email" required>
            </div>

            <div class="form-group">
                <label for="password">
                    <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Password
                </label>
                <input id="password" name="password" type="password" placeholder="Min. 8 karakter" minlength="8" autocomplete="new-password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password" minlength="8" autocomplete="new-password" required>
            </div>

            <div class="form-group">
                <label for="role">
                    <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    Role / Jabatan
                </label>
                <select id="role" name="role" required>
                    <option value="user"        @selected(old('role', 'user') === 'user')>User / Pengaju SPJ</option>
                    <option value="visitor1"    @selected(old('role') === 'visitor1')>Visitor 1</option>
                    <option value="visitor2"    @selected(old('role') === 'visitor2')>Visitor 2</option>
                    <option value="kepala_dinas" @selected(old('role') === 'kepala_dinas')>Kepala Dinas</option>
                    <option value="bendahara"   @selected(old('role') === 'bendahara')>Bendahara</option>
                </select>
            </div>

            <button class="primary-button" type="submit" style="width:100%;margin-top:8px">
                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah User
            </button>
        </form>

        {{-- User List Panel --}}
        <div class="panel user-list-panel">
            <div class="user-list-header">
                <div>
                    <h2 class="panel-title">Daftar Akun</h2>
                    <p class="panel-subtitle">Semua user yang terdaftar pada sistem</p>
                </div>
                <span class="user-count-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    {{ $users->count() }} akun
                </span>
            </div>

            {{-- Search box --}}
            <div class="user-search-box">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="user-search" placeholder="Cari nama atau email user…" autocomplete="off">
            </div>

            {{-- Role filter tabs --}}
            <div style="display:flex;gap:8px;padding:0 24px 16px;flex-wrap:wrap">
                <button class="role-filter-btn active" data-role="all" type="button">Semua</button>
                <button class="role-filter-btn" data-role="user" type="button">Pengaju SPJ</button>
                <button class="role-filter-btn" data-role="reviewer" type="button">Reviewer</button>
                <button class="role-filter-btn" data-role="bendahara" type="button">Bendahara</button>
            </div>

            <div class="user-card-list" id="user-list">
                @forelse ($users as $index => $user)
                    @php
                        $roleLabel = [
                            'user'         => 'Pengaju SPJ',
                            'visitor1'     => 'Visitor 1',
                            'visitor2'     => 'Visitor 2',
                            'kepala_dinas' => 'Kepala Dinas',
                            'bendahara'    => 'Bendahara',
                        ][$user->role] ?? $user->role;

                        $avatarClass  = $user->isBendahara() ? 'role-bendahara' : ($user->isReviewer() ? 'role-reviewer' : 'role-user');
                        $badgeClass   = $user->isBendahara() ? 'badge-bendahara' : ($user->isReviewer() ? 'badge-reviewer' : 'badge-user');
                        $roleGroup    = $user->isBendahara() ? 'bendahara' : ($user->isReviewer() ? 'reviewer' : 'user');
                        $initials     = collect(explode(' ', $user->name))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
                    @endphp
                    <div class="user-card"
                         style="animation-delay:{{ $index * 0.04 }}s"
                         data-name="{{ strtolower($user->name) }}"
                         data-email="{{ strtolower($user->email) }}"
                         data-role-group="{{ $roleGroup }}">
                        <div class="user-card-avatar {{ $avatarClass }}">{{ $initials }}</div>
                        <div class="user-card-details">
                            <span class="user-card-name">{{ $user->name }}</span>
                            <span class="user-card-email">{{ $user->email }}</span>
                        </div>
                        <div class="user-card-actions">
                            <span class="role-badge {{ $badgeClass }}">{{ $roleLabel }}</span>
                            @if (! $user->isBendahara())
                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-btn" type="submit" title="Hapus user ini">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4h6v2"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <span class="protected-badge">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                    Dilindungi
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="user-card-empty">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:block;margin:0 auto 10px"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        <p>Belum ada user terdaftar.</p>
                    </div>
                @endforelse

                {{-- Empty search state (hidden by default) --}}
                <div class="user-card-empty" id="no-results" style="display:none">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:block;margin:0 auto 10px"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <p>Tidak ada user yang cocok dengan pencarian.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput  = document.getElementById('user-search');
        const cards        = document.querySelectorAll('#user-list .user-card');
        const noResults    = document.getElementById('no-results');
        const filterBtns   = document.querySelectorAll('.role-filter-btn');
        let activeRole     = 'all';

        function filterCards() {
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            let visible = 0;

            cards.forEach(card => {
                const name      = card.dataset.name  || '';
                const email     = card.dataset.email || '';
                const roleGroup = card.dataset.roleGroup || '';

                const matchSearch = !query || name.includes(query) || email.includes(query);
                const matchRole   = activeRole === 'all' || roleGroup === activeRole;

                if (matchSearch && matchRole) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noResults) {
                noResults.style.display = visible === 0 ? '' : 'none';
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterCards);
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeRole = btn.dataset.role;
                filterCards();
            });
        });
    });
    </script>
</x-app-shell>