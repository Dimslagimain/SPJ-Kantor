<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }} — SPJ Sehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" aria-label="Navigasi utama">
        <div class="brand">
            <div class="brand-mark">S</div>
            <div class="brand-copy"><span class="brand-title">SPJ Sehat</span><span class="brand-subtitle">Dinas Kesehatan</span></div>
        </div>
        <nav class="nav">
            <p class="nav-label">Ruang kerja</p>
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="nav-icon">◫</span><span>Dashboard</span></a>
            <a class="nav-link {{ request()->routeIs('review.queue') ? 'active' : '' }}" href="{{ route('review.queue') }}"><span class="nav-icon">▣</span><span>{{ auth()->user()->isBendahara() ? 'Antrean Review' : 'Pantau SPJ' }}</span></a>
            @if (auth()->user()->isBendahara())
                <a class="nav-link {{ request()->routeIs('spj.index') ? 'active' : '' }}" href="{{ route('spj.index') }}"><span class="nav-icon">▤</span><span>Semua SPJ</span></a>
                <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}"><span class="nav-icon">◈</span><span>Laporan</span></a>
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span class="nav-icon">♙</span><span>Kelola User</span></a>
            @else
                <a class="nav-link {{ request()->routeIs('spj.create') ? 'active' : '' }}" href="{{ route('spj.create') }}"><span class="nav-icon">＋</span><span>Ajukan SPJ</span></a>
            @endif
            <p class="nav-label">Akun</p>
            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}"><span class="nav-icon">⚙</span><span>Pengaturan User</span></a>
        </nav>
        <div class="sidebar-footer">Tahun Anggaran 2026<br>Versi internal 1.0</div>
    </aside>
    <main class="main">
        <header class="topbar">
            <div class="breadcrumb">Dinas Kesehatan &nbsp;/&nbsp; <strong>{{ $title }}</strong></div>
            <div class="user-box">
                <div class="user-avatar">{{ collect(explode(' ', auth()->user()->name))->map(fn ($part) => substr($part, 0, 1))->take(2)->implode('') }}</div>
                <div class="user-info"><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->isBendahara() ? 'Bendahara Pengeluaran' : 'Pengusul SPJ' }}</span></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-button" type="submit">Keluar</button>
                </form>
            </div>
        </header>
        <section class="content">
            {{ $slot }}
        </section>
    </main>
</div>
</body>
</html>
