<?php $user = \App\Core\Auth::user(); ?>
<?php $role = \App\Core\Auth::role(); ?>
<?php $notifRepo = new \App\Repositories\NotificationRepository(); ?>
<?php $unreadCount = $user ? $notifRepo->countUnread($user['id']) : 0; ?>
<?php $notifUrl = match($role) { 'provider' => '/provider/notifications', 'admin' => null, default => '/notifications' }; ?>
<nav class="fixed top-0 left-0 right-0 z-40 h-16 bg-white border-b border-surface-200">
    <div class="content-section h-full">
        <div class="flex items-center justify-between h-full gap-4">
            <div class="flex items-center gap-3">
                <button id="sidebarToggle" class="lg:hidden p-2 -ml-2 rounded-lg text-surface-400 hover:bg-surface-100 hover:text-surface-700 transition-colors" aria-label="Toggle sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <a href="<?= url('/') ?>" class="text-lg font-bold font-heading text-primary-700 tracking-tight flex items-center gap-2">
                    <svg class="w-7 h-7" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="8" fill="url(#nb-gradient)"/>
                        <path d="M16 7c-1.5 3-4.5 5-8 5v1c3.5 0 6.5 2 8 5 1.5-3 4.5-5 8-5v-1c-3.5 0-6.5-2-8-5z" fill="white" opacity="0.9"/>
                        <path d="M16 14c-1 2-3 3.5-5.5 3.5v.5c2.5 0 4.5 1.5 5.5 3.5 1-2 3-3.5 5.5-3.5v-.5c-2.5 0-4.5-1.5-5.5-3.5z" fill="white" opacity="0.6"/>
                        <defs><linearGradient id="nb-gradient" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse"><stop stop-color="#292929"/><stop offset="1" stop-color="#0e0e0e"/></linearGradient></defs>
                    </svg>
                    <span class="hidden sm:inline">SISWACentral</span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <?php if ($user): ?>
                    <span class="hidden sm:flex items-center gap-2 text-sm text-surface-500 mr-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
                        <?= e($user['name']) ?>
                    </span>
                    <?php if ($notifUrl): ?>
                    <a href="<?= $notifUrl ?>" class="btn-icon btn-ghost relative" aria-label="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <?php if ($unreadCount > 0): ?>
                        <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-danger-500 text-white text-[10px] font-bold leading-none"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>
                    <div class="dropdown" id="userDropdown">
                        <button class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-surface-100 transition-colors" onclick="toggleDropdown(event, 'userMenu')" aria-haspopup="true" aria-expanded="false">
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
</script>
