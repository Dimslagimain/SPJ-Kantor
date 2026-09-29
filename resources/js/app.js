// Application JS - Sidebar toggle, theme switcher & animations
document.addEventListener('DOMContentLoaded', () => {
    // Theme Switcher
    const themeToggleBtn = document.getElementById('theme-toggle');
    const updateTheme = (theme) => {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('spj_theme', theme);
    };

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
            updateTheme(nextTheme);
        });
    }

    // Sidebar & Shell Layout
    const appShell = document.getElementById('app-shell');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const overlay = document.getElementById('sidebar-overlay');

    if (!appShell || !toggleBtn) return;

    // Load saved desktop sidebar state
    const isCollapsedSaved = localStorage.getItem('spj_sidebar_collapsed') === 'true';
    if (isCollapsedSaved && window.innerWidth >= 1024) {
        appShell.classList.add('sidebar-collapsed');
    }

    // Toggle button click handler
    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (window.innerWidth < 1024) {
            // Mobile behavior: open/close drawer overlay
            appShell.classList.toggle('sidebar-mobile-open');
        } else {
            // Desktop behavior: toggle collapse state
            const isCollapsed = appShell.classList.toggle('sidebar-collapsed');
            localStorage.setItem('spj_sidebar_collapsed', isCollapsed);
        }
    });

    // Close mobile sidebar when clicking backdrop overlay
    if (overlay) {
        overlay.addEventListener('click', () => {
            appShell.classList.remove('sidebar-mobile-open');
        });
    }

    // Close mobile sidebar on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && appShell.classList.contains('sidebar-mobile-open')) {
            appShell.classList.remove('sidebar-mobile-open');
        }
    });

    // Handle responsive window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            appShell.classList.remove('sidebar-mobile-open');
        }
    });
});
