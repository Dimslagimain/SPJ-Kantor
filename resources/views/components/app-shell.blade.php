<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }} — SPJ Sehat</title>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('spj_theme');
            const theme = savedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell" id="app-shell">
    <!-- Fixed Header / Topbar — always full-width, above everything -->
    <header class="topbar" id="topbar">
        <div class="topbar-left">
            <!-- Hamburger 3-Line Toggle Button -->
            <button type="button" class="sidebar-toggle-btn" id="sidebar-toggle" aria-label="Buka atau sembunyikan navigasi sidebar" title="Toggle Sidebar">
                <svg class="hamburger-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6" class="line-top"></line>
                    <line x1="3" y1="12" x2="21" y2="12" class="line-mid"></line>
                    <line x1="3" y1="18" x2="21" y2="18" class="line-bot"></line>
                </svg>
            </button>

            <div class="breadcrumb">
                <span class="breadcrumb-root">Dinas Kesehatan</span>
                <span class="breadcrumb-sep">/</span>
                <strong class="breadcrumb-active">{{ $title }}</strong>
            </div>
        </div>

        <div class="topbar-right">
            <!-- Theme Toggle Button (Light / Dark) -->
            <button type="button" class="theme-toggle-btn" id="theme-toggle" aria-label="Ganti tema terang atau gelap" title="Ganti Tema">
                <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
                <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>

            <div class="user-box">
                <div class="user-avatar" title="{{ auth()->user()->name }}">
                    {{ collect(explode(' ', auth()->user()->name))->map(fn ($part) => substr($part, 0, 1))->take(2)->implode('') }}
                </div>
                <div class="user-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->isReviewer() ? ucfirst(str_replace('_', ' ', auth()->user()->role)) : 'Pengusul SPJ' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button class="logout-button" type="submit" title="Keluar dari akun">
                        <svg class="logout-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="logout-text">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Body area below the fixed topbar -->
    <div class="app-body">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>

        <!-- Sidebar — sits below the topbar -->
        <aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
            <div class="brand">
                <div class="brand-mark" title="SPJ Sehat">S</div>
                <div class="brand-copy">
                    <span class="brand-title">SPJ Sehat</span>
                    <span class="brand-subtitle">Dinas Kesehatan</span>
                </div>
            </div>

            <nav class="nav">
                <p class="nav-label">Ruang kerja</p>

                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" title="Dashboard">
                    <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                        <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                        <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                        <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                    </svg>
                    <span class="nav-text">Dashboard</span>
                </a>

                <a class="nav-link {{ request()->routeIs('review.queue') ? 'active' : '' }}" href="{{ route('review.queue') }}" title="{{ auth()->user()->isReviewer() ? 'Antrean Review' : 'Pantau SPJ' }}">
                    <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    <span class="nav-text">{{ auth()->user()->isReviewer() ? 'Antrean Review' : 'Pantau SPJ' }}</span>
                </a>

                @if (auth()->user()->isBendahara())
                    <a class="nav-link {{ request()->routeIs('spj.index') ? 'active' : '' }}" href="{{ route('spj.index') }}" title="Semua SPJ">
                        <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        <span class="nav-text">Semua SPJ</span>
                    </a>

                    <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}" title="Laporan">
                        <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        <span class="nav-text">Laporan</span>
                    </a>

                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}" title="Kelola User">
                        <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="nav-text">Kelola User</span>
                    </a>
                @elseif (auth()->user()->hasRole('user'))
                    <a class="nav-link {{ request()->routeIs('spj.create') ? 'active' : '' }}" href="{{ route('spj.create') }}" title="Ajukan SPJ">
                        <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="6" y1="12" x2="18" y2="12"></line>
                        </svg>
                        <span class="nav-text">Ajukan SPJ</span>
                    </a>
                @endif

                <p class="nav-label">Akun</p>
                <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}" title="Pengaturan User">
                    <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span class="nav-text">Pengaturan User</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <span class="footer-line">Tahun Anggaran 2026</span>
                <span class="footer-line">Versi internal 1.0</span>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <section class="content">
                {{ $slot }}
            </section>
        </main>
    </div>
</div>
</body>
</html>
