<?php
$title = 'Settings';
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Settings</h1>
        <p class="page-desc">Manage your account settings</p>
    </div>

    <?php if ($flash = flash('success')): ?>
        <div class="flash-success"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><?= e($flash) ?></div>
    <?php endif; ?>
    <?php if ($flash = flash('error')): ?>
        <div class="flash-error"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><?= e($flash) ?></div>
    <?php endif; ?>

    <div class="card-hover p-5 mb-6 flex items-center gap-4">
        <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
        <div class="flex-1"><p class="font-semibold text-surface-800"><?= e($plan['name'] ?? '') ?> Plan</p></div>
        <a href="<?= url('/provider/subscription') ?>" class="btn btn-outline btn-sm">Manage</a>
    </div>

    <form method="POST" action="<?= url('/provider/settings') ?>" class="card-hover p-6 mb-6">
        <?= csrfField() ?>
        <h2 class="font-semibold text-surface-800 text-sm mb-4">Account Information</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div><label class="input-label">Name</label><input type="text" name="name" value="<?= e(old('name', $user['name'] ?? '')) ?>" class="input"></div>
            <div><label class="input-label">Email</label><input type="email" value="<?= e($user['email'] ?? '') ?>" class="input bg-surface-50" disabled></div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
    </form>

    <form method="POST" action="<?= url('/provider/settings/password') ?>" class="card-hover p-6">
        <?= csrfField() ?>
        <h2 class="font-semibold text-surface-800 text-sm mb-4">Change Password</h2>
        <div class="grid gap-4 sm:grid-cols-3">
            <div><label class="input-label">Current</label><input type="password" name="current_password" class="input" required></div>
            <div><label class="input-label">New Password</label><input type="password" name="new_password" class="input" required></div>
            <div><label class="input-label">Confirm</label><input type="password" name="confirm_password" class="input" required></div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Update Password</button>
    </form>
</div>