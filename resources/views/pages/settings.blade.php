<x-app-shell title="Pengaturan User" description="Ubah profil dan password akun.">
    <style>
    /* Dedicated Self-Contained Styles for Settings Page */
    .settings-grid {
        display: grid;
        grid-template-columns: 320px minmax(0, 1fr);
        gap: 28px;
        align-items: start;
    }

    .profile-card-sticky {
        position: sticky;
        top: calc(var(--topbar-height) + 32px);
        overflow: hidden;
        padding: 0;
        border: 1px solid var(--panel-border);
        border-radius: 16px;
        background: var(--panel-bg);
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
    }

    .profile-card-cover {
        height: 96px;
        background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);
        position: relative;
    }

    .profile-card-body {
        padding: 0 24px 24px;
        text-align: center;
        position: relative;
    }

    .profile-avatar-wrapper {
        margin: -48px auto 14px;
        position: relative;
        display: inline-block;
    }

    .profile-avatar-large {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 1.6rem;
        font-weight: 800;
        color: #ffffff;
        border: 4px solid var(--panel-bg);
        box-shadow: 0 8px 24px -4px rgba(13, 148, 136, 0.35);
        background: linear-gradient(135deg, #0d9488 0%, #0369a1 100%);
        transition: transform 0.3s ease;
    }

    .profile-avatar-large:hover {
        transform: scale(1.05);
    }

    .profile-avatar-large.role-bendahara {
        background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
        box-shadow: 0 8px 24px -4px rgba(124, 58, 237, 0.35);
    }

    .profile-avatar-large.role-reviewer {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
    }

    .profile-name-display {
        margin: 0;
        font-size: 1.18rem;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: -0.02em;
    }

    .profile-email-display {
        margin: 4px 0 16px;
        font-size: 0.8rem;
        color: var(--muted);
        word-break: break-all;
    }

    .profile-meta-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 18px;
        padding-top: 18px;
        border-top: 1px solid var(--line);
        text-align: left;
    }

    .profile-meta-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
    }

    .profile-meta-label {
        color: var(--muted);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .profile-meta-value {
        color: var(--ink);
        font-weight: 700;
    }

    .settings-section-card {
        padding: 28px;
        margin-bottom: 24px;
        border: 1px solid var(--panel-border);
        border-radius: 16px;
        background: var(--panel-bg);
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
        animation: fadeInUp 0.4s ease-out both;
    }

    .settings-section-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding-bottom: 18px;
        margin-bottom: 22px;
        border-bottom: 1px solid var(--line);
    }

    .settings-icon-badge {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .password-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .password-input-wrapper input {
        width: 100%;
        padding-right: 44px !important;
    }

    .toggle-password-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: 0;
        padding: 6px;
        color: var(--muted);
        display: grid;
        place-items: center;
        border-radius: 6px;
        cursor: pointer;
        transition: color 0.2s ease, transform 0.15s ease;
    }

    .toggle-password-btn:hover {
        color: var(--ink);
        transform: translateY(-50%) scale(1.1);
    }

    /* Password Strength Indicator */
    .password-meter-wrap {
        margin-top: 8px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .password-meter-bars {
        display: flex;
        gap: 5px;
        height: 6px;
    }

    .password-meter-bar {
        flex: 1;
        height: 100%;
        border-radius: 9999px;
        background: var(--line);
        transition: background-color 0.25s ease;
    }

    .password-meter-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--muted);
    }

    .strength-weak .password-meter-bar:nth-child(1) { background: #f43f5e; }
    .strength-fair .password-meter-bar:nth-child(-n+2) { background: #f59e0b; }
    .strength-good .password-meter-bar:nth-child(-n+3) { background: #3b82f6; }
    .strength-strong .password-meter-bar:nth-child(-n+4) { background: #10b981; }

    .password-match-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    @media (max-width: 900px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
        .profile-card-sticky {
            position: static;
        }
    }
    </style>

    <div class="page-head">
        <div>
            <p class="eyebrow">Akun Anda</p>
            <h1>Pengaturan User</h1>
            <p class="head-copy">Kelola identitas profil, perbarui email, dan ganti password untuk memastikan keamanan akun Anda.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="form-success" style="display:flex;align-items:center;gap:8px">
            <svg style="width:16px;height:16px;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="form-error" style="display:flex;align-items:center;gap:8px">
            <svg style="width:16px;height:16px;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @php
        $user = auth()->user();
        $initials = collect(explode(' ', $user->name))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
        $roleTitle = match($user->role) {
            'bendahara' => 'Bendahara Pengeluaran',
            'kepala_dinas' => 'Kepala Dinas',
            'visitor1' => 'Reviewer - Visitor 1',
            'visitor2' => 'Reviewer - Visitor 2',
            default => 'Pengusul / Pembuat SPJ',
        };
        $avatarClass = $user->isBendahara() ? 'role-bendahara' : ($user->isReviewer() ? 'role-reviewer' : 'role-user');
        $badgeClass = $user->isBendahara() ? 'badge-bendahara' : ($user->isReviewer() ? 'badge-reviewer' : 'badge-user');
    @endphp

    <div class="settings-grid">
        {{-- Left Column: Profile Card Summary --}}
        <div class="panel profile-card-sticky">
            <div class="profile-card-cover"></div>
            <div class="profile-card-body">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-large {{ $avatarClass }}" id="preview-avatar">
                        {{ $initials }}
                    </div>
                </div>

                <h2 class="profile-name-display" id="preview-name">{{ $user->name }}</h2>
                <p class="profile-email-display" id="preview-email">{{ $user->email }}</p>

                <span class="role-badge {{ $badgeClass }}" style="font-size:0.72rem;padding:5px 12px">
                    {{ $roleTitle }}
                </span>

                <div class="profile-meta-list">
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            Role Sistem
                        </span>
                        <span class="profile-meta-value">{{ strtoupper($user->role) }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Status Akun
                        </span>
                        <span class="profile-meta-value" style="color:#0d9488;display:inline-flex;align-items:center;gap:4px">
                            <span style="width:6px;height:6px;border-radius:50%;background:#0d9488;display:inline-block"></span>
                            Aktif
                        </span>
                    </div>
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Bergabung
                        </span>
                        <span class="profile-meta-value">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '2026' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Interactive Settings Forms --}}
        <form method="POST" action="{{ route('settings.update') }}" id="settings-form">
            @csrf
            @method('PUT')

            {{-- Section 1: Profil Pengguna --}}
            <div class="settings-section-card">
                <div class="settings-section-header">
                    <div>
                        <h2 class="panel-title" style="font-size:1.05rem;display:flex;align-items:center;gap:8px">
                            <span>Informasi Profil</span>
                        </h2>
                        <p class="panel-subtitle">Perbarui nama lengkap dan alamat email yang terhubung dengan akun Anda</p>
                    </div>
                    <div class="settings-icon-badge" style="background:var(--mint);color:var(--teal-dark)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:18px">
                    <div class="form-group">
                        <label for="name">
                            <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Nama Lengkap
                        </label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" placeholder="Nama lengkap Anda">
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            Alamat Email
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email" placeholder="email@dinkes.go.id">
                    </div>
                </div>
            </div>

            {{-- Section 2: Keamanan & Password --}}
            <div class="settings-section-card">
                <div class="settings-section-header">
                    <div>
                        <h2 class="panel-title" style="font-size:1.05rem;display:flex;align-items:center;gap:8px">
                            <span>Keamanan & Password</span>
                        </h2>
                        <p class="panel-subtitle">Kosongkan kolom password di bawah jika Anda tidak berniat mengganti password.</p>
                    </div>
                    <div class="settings-icon-badge" style="background:var(--blue-tint);color:var(--blue)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:16px">
                    <label for="current_password">
                        <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Password Saat Ini
                    </label>
                    <div class="password-input-wrapper">
                        <input id="current_password" name="current_password" type="password" placeholder="Masukkan password saat ini untuk verifikasi" autocomplete="current-password">
                        <button type="button" class="toggle-password-btn" data-target="current_password" title="Lihat/Sembunyikan password" aria-label="Toggle password">
                            <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg class="eye-closed" style="display:none" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:18px">
                    <div class="form-group">
                        <label for="password">
                            <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Password Baru
                        </label>
                        <div class="password-input-wrapper">
                            <input id="password" name="password" type="password" minlength="8" placeholder="Minimal 8 karakter" autocomplete="new-password">
                            <button type="button" class="toggle-password-btn" data-target="password" title="Lihat/Sembunyikan password" aria-label="Toggle password">
                                <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="eye-closed" style="display:none" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>

                        {{-- Interactive Password Strength Meter --}}
                        <div class="password-meter-wrap" id="password-meter-container" style="display:none">
                            <div class="password-meter-bars" id="password-meter-bars">
                                <span class="password-meter-bar"></span>
                                <span class="password-meter-bar"></span>
                                <span class="password-meter-bar"></span>
                                <span class="password-meter-bar"></span>
                            </div>
                            <span class="password-meter-label" id="password-meter-label">Kekuatan password: Lemah</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">
                            <svg style="width:13px;height:13px;display:inline;margin-right:4px;vertical-align:-1px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
                            Konfirmasi Password Baru
                        </label>
                        <div class="password-input-wrapper">
                            <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" placeholder="Ulangi password baru" autocomplete="new-password">
                            <button type="button" class="toggle-password-btn" data-target="password_confirmation" title="Lihat/Sembunyikan password" aria-label="Toggle password">
                                <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="eye-closed" style="display:none" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                        <div id="password-match-badge" class="password-match-badge" style="display:none"></div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:12px">
                <button class="primary-button" type="submit" id="submit-btn" style="min-width:180px">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Interactive Javascript --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Live Profile Preview Sync
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const previewName = document.getElementById('preview-name');
        const previewEmail = document.getElementById('preview-email');
        const previewAvatar = document.getElementById('preview-avatar');

        if (nameInput && previewName && previewAvatar) {
            nameInput.addEventListener('input', (e) => {
                const val = e.target.value.trim();
                previewName.textContent = val || 'Nama Pengguna';
                
                // Update initials
                if (val) {
                    const initials = val.split(' ')
                        .filter(Boolean)
                        .slice(0, 2)
                        .map(word => word[0].toUpperCase())
                        .join('');
                    previewAvatar.textContent = initials || 'U';
                }
            });
        }

        if (emailInput && previewEmail) {
            emailInput.addEventListener('input', (e) => {
                previewEmail.textContent = e.target.value.trim() || 'email@dinkes.go.id';
            });
        }

        // 2. Toggle Password Visibility
        const toggleButtons = document.querySelectorAll('.toggle-password-btn');
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.dataset.target;
                const input = document.getElementById(targetId);
                const eyeOpen = btn.querySelector('.eye-open');
                const eyeClosed = btn.querySelector('.eye-closed');

                if (input.type === 'password') {
                    input.type = 'text';
                    if (eyeOpen) eyeOpen.style.display = 'none';
                    if (eyeClosed) eyeClosed.style.display = 'block';
                } else {
                    input.type = 'password';
                    if (eyeOpen) eyeOpen.style.display = 'block';
                    if (eyeClosed) eyeClosed.style.display = 'none';
                }
            });
        });

        // 3. Password Strength Meter
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const meterContainer = document.getElementById('password-meter-container');
        const meterBars = document.getElementById('password-meter-bars');
        const meterLabel = document.getElementById('password-meter-label');
        const matchBadge = document.getElementById('password-match-badge');

        function checkStrength(pass) {
            let score = 0;
            if (pass.length >= 8) score++;
            if (/[A-Z]/.test(pass) && /[a-z]/.test(pass)) score++;
            if (/[0-9]/.test(pass)) score++;
            if (/[^A-Za-z0-9]/.test(pass)) score++;
            return score;
        }

        function checkMatch() {
            if (!confirmInput.value) {
                matchBadge.style.display = 'none';
                return;
            }

            matchBadge.style.display = 'inline-flex';
            if (passwordInput.value === confirmInput.value) {
                matchBadge.style.color = '#10b981';
                matchBadge.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Password cocok';
            } else {
                matchBadge.style.color = '#f43f5e';
                matchBadge.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Password tidak cocok';
            }
        }

        if (passwordInput && meterContainer) {
            passwordInput.addEventListener('input', (e) => {
                const pass = e.target.value;
                if (!pass) {
                    meterContainer.style.display = 'none';
                    checkMatch();
                    return;
                }

                meterContainer.style.display = 'flex';
                const score = checkStrength(pass);
                
                meterBars.className = 'password-meter-bars';

                if (score <= 1) {
                    meterBars.classList.add('strength-weak');
                    meterLabel.textContent = 'Kekuatan: Lemah (gunakan kombinasi huruf, angka, & simbol)';
                    meterLabel.style.color = '#f43f5e';
                } else if (score === 2) {
                    meterBars.classList.add('strength-fair');
                    meterLabel.textContent = 'Kekuatan: Cukup';
                    meterLabel.style.color = '#f59e0b';
                } else if (score === 3) {
                    meterBars.classList.add('strength-good');
                    meterLabel.textContent = 'Kekuatan: Bagus';
                    meterLabel.style.color = '#3b82f6';
                } else {
                    meterBars.classList.add('strength-strong');
                    meterLabel.textContent = 'Kekuatan: Sangat Kuat & Aman';
                    meterLabel.style.color = '#10b981';
                }

                checkMatch();
            });
        }

        if (confirmInput) {
            confirmInput.addEventListener('input', checkMatch);
        }
    });
    </script>
</x-app-shell>
