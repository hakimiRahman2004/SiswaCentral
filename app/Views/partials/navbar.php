<?php $user = \App\Core\Auth::user(); ?>
<?php $role = \App\Core\Auth::role(); ?>
<?php $notifRepo = new \App\Repositories\NotificationRepository(); ?>
<?php $unreadCount = $user ? $notifRepo->countUnread($user['id']) : 0; ?>
<?php $notifUrl = match($role) { 'provider' => '/provider/notifications', 'admin' => '/admin/notifications', default => '/notifications' }; ?>
<nav class="fixed top-0 left-0 right-0 z-40 h-16 bg-white border-b border-surface-200">
    <div class="flex items-center justify-between h-full px-4 sm:px-6 lg:pl-64 lg:pr-6">
        <div class="flex items-center gap-2">
            <button id="sidebarToggle" class="lg:hidden p-2 -ml-2 rounded-lg text-surface-400 hover:bg-surface-100 hover:text-surface-700 transition-colors" aria-label="Toggle sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
            <a href="<?= url('/') ?>" class="flex items-center gap-2">
                <svg class="w-8 h-8" viewBox="0 0 32 32" fill="none">
                    <rect width="32" height="32" rx="8" fill="url(#nb-gradient)"/>
                    <path d="M16 7c-1.5 3-4.5 5-8 5v1c3.5 0 6.5 2 8 5 1.5-3 4.5-5 8-5v-1c-3.5 0-6.5-2-8-5z" fill="white" opacity="0.9"/>
                    <path d="M16 14c-1 2-3 3.5-5.5 3.5v.5c2.5 0 4.5 1.5 5.5 3.5 1-2 3-3.5 5.5-3.5v-.5c-2.5 0-4.5-1.5-5.5-3.5z" fill="white" opacity="0.6"/>
                    <defs><linearGradient id="nb-gradient" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse"><stop stop-color="#292929"/><stop offset="1" stop-color="#0e0e0e"/></linearGradient></defs>
                </svg>
                <span class="text-lg font-bold font-heading text-surface-900 hidden sm:inline">SISWACentral</span>
            </a>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($user): ?>
                <span class="hidden md:flex items-center gap-2 text-sm text-surface-600">
                    <span class="w-2 h-2 rounded-full bg-success-500"></span>
                    <?= e($user['name']) ?>
                </span>
                <?php if ($notifUrl): ?>
                <a href="<?= $notifUrl ?>" class="relative p-2 rounded-lg text-surface-500 hover:bg-surface-100 hover:text-surface-700 transition-colors" aria-label="Notifications">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    <?php if ($unreadCount > 0): ?>
                    <span id="notifBadge" class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-danger-500 text-white text-[10px] font-bold leading-none"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
                    <?php else: ?>
                    <span id="notifBadge" style="display:none" class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-danger-500 text-white text-[10px] font-bold leading-none"></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                <div class="dropdown" id="userDropdown">
                    <button class="flex items-center gap-2 p-1 rounded-lg hover:bg-surface-100 transition-colors" onclick="toggleDropdown(event, 'userMenu')" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar avatar-sm"><?= e(substr($user['name'] ?? 'U', 0, 1)) ?></div>
                        <svg class="w-4 h-4 text-surface-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                    </button>
                        <div id="userMenu" class="dropdown-menu hidden" role="menu">
                            <div class="px-3 py-2 text-xs text-surface-400 font-medium border-b border-surface-100 mb-1">
                                Signed in as<br><span class="text-surface-700 font-semibold text-sm"><?= e($user['email'] ?? '') ?></span>
                            </div>
                            <?php if ($role === 'student'): ?>
                                <a href="<?= url('/profile') ?>" class="dropdown-item" role="menuitem">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                                <a href="<?= url('/settings') ?>" class="dropdown-item" role="menuitem">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Settings
                                </a>
                            <?php elseif ($role === 'provider'): ?>
                                <a href="<?= url('/provider/profile') ?>" class="dropdown-item" role="menuitem">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                                <a href="<?= url('/provider/settings') ?>" class="dropdown-item" role="menuitem">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Settings
                                </a>
                            <?php elseif ($role === 'admin'): ?>
                                <a href="<?= url('/admin/profile') ?>" class="dropdown-item" role="menuitem">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                                <a href="<?= url('/admin/settings') ?>" class="dropdown-item" role="menuitem">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Settings
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?= url('/logout') ?>" class="dropdown-item danger" role="menuitem">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                Sign out
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="btn btn-ghost btn-sm">Sign in</a>
                    <a href="<?= url('/register') ?>" class="btn btn-primary btn-sm">Get started</a>
                <?php endif; ?>
        </div>
    </div>
</nav>

<script>
function toggleDropdown(event, menuId) {
    event.stopPropagation();
    const menu = document.getElementById(menuId);
    const isHidden = menu.classList.contains('hidden');
    // Close all dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
    if (isHidden) menu.classList.remove('hidden');
}

document.addEventListener('click', function(e) {
    document.querySelectorAll('.dropdown-menu:not(.hidden)').forEach(menu => {
        if (!menu.parentElement.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
});

// Real-time notification badge polling
(function() {
    const badge = document.getElementById('notifBadge');
    if (!badge) return;
    const notifUrl = <?= json_encode($notifUrl) ?>;
    const apiBase = notifUrl.includes('provider') ? '/provider' : (notifUrl.includes('admin') ? '/admin' : '');

    setInterval(async function() {
        try {
            const resp = await fetch(apiBase + '/notifications/unread-count', {
                headers: { 'Accept': 'application/json' }
            });
            if (!resp.ok) return;
            const data = await resp.json();
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        } catch (e) { /* silent */ }
    }, 30000);
})();
</script>
