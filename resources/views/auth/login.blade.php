<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SPJ Sehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">
    <div class="login-container">
        <main class="login-card">
            <div class="login-header">
                <div class="brand-mark" title="SPJ Sehat">S</div>
                <p class="eyebrow">SPJ Sehat · Dinas Kesehatan</p>
                <h1>Masuk ke ruang kerja</h1>
                <p class="login-copy">Kelola pengajuan dan proses persetujuan SPJ dengan aman.</p>
            </div>

            @if ($errors->any())
                <div class="form-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@dinkes.go.id" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Kata sandi</label>
                    <input id="password" name="password" type="password" placeholder="••••••••" required>
                </div>

                <div class="form-actions">
                    <label class="remember">
                        <input name="remember" type="checkbox" value="1">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button class="primary-button login-btn" type="submit">
                    <span>Masuk</span>
                    <svg style="width:16px;height:16px;display:inline-block;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>

            <div class="login-footer">
                Tahun Anggaran 2026 — Sistem Internal SPJ Sehat
            </div>
        </main>
    </div>
</body>
</html>