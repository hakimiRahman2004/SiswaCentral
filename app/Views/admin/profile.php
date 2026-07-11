<?php $title = 'My Profile'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">My Profile</h1><p class="page-desc">Manage your admin profile</p></div>
    <div class="card-hover p-6 max-w-2xl">
        <form method="POST" action="<?= url('/admin/profile') ?>" class="space-y-4">
            <?= csrfField() ?>
            <div><label class="input-label">Name</label><input type="text" name="name" value="<?= e($user['name']??'') ?>" class="input" required></div>
            <div><label class="input-label">Email</label><input type="email" value="<?= e($user['email']??'') ?>" class="input" disabled></div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
        <hr class="my-6 border-surface-200">
        <h2 class="font-semibold text-surface-800 mb-4">Change Password</h2>
        <form method="POST" action="<?= url('/admin/profile/password') ?>" class="space-y-4">
            <?= csrfField() ?>
            <div><label class="input-label">Current Password</label><input type="password" name="current_password" class="input" required></div>
            <div><label class="input-label">New Password</label><input type="password" name="new_password" class="input" required></div>
            <div><label class="input-label">Confirm Password</label><input type="password" name="confirm_password" class="input" required></div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</div>