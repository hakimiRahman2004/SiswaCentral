document.addEventListener('DOMContentLoaded', () => {
    // ── Sidebar Toggle ──────────────────────────────────────
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    if (toggle && sidebar) {
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('hidden');
        });
    }

    // ── Close sidebar on outside click (mobile) ─────────────
    document.addEventListener('click', (e) => {
        if (sidebar && !sidebar.classList.contains('hidden') && window.innerWidth < 1024) {
            if (!sidebar.contains(e.target) && e.target !== toggle && !toggle?.contains(e.target)) {
                sidebar.classList.add('hidden');
            }
        }
    });

    // ── Close sidebar on Escape key ─────────────────────────
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('hidden') && window.innerWidth < 1024) {
            sidebar.classList.add('hidden');
        }
    });

    // ── Dropdown toggle (delegated) ─────────────────────────
    document.addEventListener('click', (e) => {
        const toggleBtn = e.target.closest('[onclick*="toggleDropdown"]');
        if (toggleBtn) return; // handled inline
    });

    document.querySelectorAll('[data-toggle="dropdown"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const target = document.getElementById(this.dataset.target);
            if (target) {
                const isHidden = target.classList.contains('hidden');
                closeAllDropdowns();
                if (isHidden) target.classList.remove('hidden');
            }
        });
    });

    // ── Close dropdowns on outside click ────────────────────
    document.addEventListener('click', () => {
        closeAllDropdowns();
    });

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu:not(.hidden)').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    // ── Flash auto-dismiss ──────────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        if (!alert.classList.contains('alert-ghost')) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }
    });

    // ── Table row click → navigate ──────────────────────────
    document.querySelectorAll('[data-clickable]').forEach(row => {
        row.addEventListener('click', function() {
            const url = this.dataset.href;
            if (url) window.location.href = url;
        });
        row.style.cursor = 'pointer';
    });

    // ── Smooth scroll for anchor links ──────────────────────
    const navHeight = 64;
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const id = link.getAttribute('href').slice(1);
            const el = document.getElementById(id);
            if (el) {
                e.preventDefault();
                window.scrollTo({ top: el.offsetTop - navHeight, behavior: 'smooth' });
            }
        });
    });

    // ── Confirm dialogs ─────────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', (e) => {
            if (!confirm(el.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });

    // ── Active sidebar link scroll into view ────────────────
    const activeLink = document.querySelector('.sidebar-link.active');
    if (activeLink && sidebar) {
        activeLink.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }
});
