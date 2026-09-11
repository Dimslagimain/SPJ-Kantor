<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SPJ Sehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">
    <main class="login-card">
        <div class="brand-mark">S</div>
        <p class="eyebrow">SPJ Sehat · Dinas Kesehatan</p>
        <h1>Masuk ke ruang kerja</h1>
        <p class="login-copy">Kelola pengajuan dan proses persetujuan SPJ dengan aman.</p>
        @if ($errors->any())
            <div class="form-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('login.store') }}" class="login-form">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" required>
            <label class="remember"><input name="remember" type="checkbox" value="1"> Ingat saya</label>
            <button class="primary-button" type="submit">Masuk</button>
        </form>
    </main>
</body>
</html>