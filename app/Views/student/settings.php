<?php
/** @var array|null $subscription */
$title = 'Settings';
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Account Settings</h1>
        <p class="page-desc">Manage your account settings and password</p>
    </div>

    <!-- Subscription Card -->
    <div class="card p-5 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: <?= ($subscription['has_smart_matching'] ?? false) ? 'var(--color-primary-100)' : 'var(--color-surface-100)' ?>;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: <?= ($subscription['has_smart_matching'] ?? false) ? 'var(--color-primary-600)' : 'var(--color-surface-400)' ?>"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5z"/></svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-surface-800"><?= e($subscription['plan_name'] ?? 'Basic') ?> Plan</p>
                <p class="text-xs text-surface-500 mt-0.5"><?= ($subscription['has_smart_matching'] ?? false) ? 'Smart matching enabled' : 'Upgrade for smart matching' ?></p>
            </div>
            <a href="<?= url('/subscription') ?>" class="btn btn-outline btn-sm">Manage</a>
        </div>
    </div>

    <!-- Account Info -->
    <div class="card p-6 mb-6">
        <h2 class="section-title mb-4">Account Information</h2>
        <form method="POST" action="<?= url('/settings') ?>">
            <?= csrfField() ?>
            <div class="grid gap-4 sm:grid-cols-2 max-w-lg">
                <div>
                    <label class="input-label">Full Name</label>
                    <input type="text" name="name" value="<?= e(old('name', $user['name'] ?? '')) ?>" class="input">
                    <?php if ($err = error('name')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="input-label">Email</label>
                    <input type="email" value="<?= e($user['email'] ?? '') ?>" class="input bg-surface-50" disabled>
                    <p class="input-hint">Email cannot be changed</p>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="card p-6">
        <h2 class="section-title mb-4">Change Password</h2>
        <form method="POST" action="<?= url('/settings/password') ?>">
            <?= csrfField() ?>
            <div class="grid gap-4 sm:grid-cols-3 max-w-2xl">
                <div>
                    <label class="input-label">Current Password</label>
                    <input type="password" name="current_password" class="input" required placeholder="Enter current password">
                    <?php if ($err = error('current_password')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="input-label">New Password</label>
                    <input type="password" name="new_password" class="input" required placeholder="Min. 8 characters">
                    <?php if ($err = error('new_password')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="input-label">Confirm New</label>
                    <input type="password" name="confirm_password" class="input" required placeholder="Re-enter new password">
                    <?php if ($err = error('confirm_password')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Password</button>
        </form>
    </div>
</div>
