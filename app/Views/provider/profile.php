<?php
/** @var array<string, mixed> $provider */
$title = 'Provider Profile';
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Organization Profile</h1>
        <p class="page-desc">Update your organization information</p>
    </div>

    <form method="POST" action="<?= url('/provider/profile') ?>" class="card p-6">
        <?= csrfField() ?>

        <!-- Organization -->
        <h2 class="section-title mb-4">Organization Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="input-label">Company Name</label>
                <input type="text" name="company_name" value="<?= e(old('company_name', $provider['company_name'] ?? '')) ?>" class="input" required>
                <?php if ($err = error('company_name')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="input-label">Registration No.</label>
                <input type="text" name="registration_no" value="<?= e(old('registration_no', $provider['registration_no'] ?? '')) ?>" class="input" placeholder="Company registration number">
            </div>
            <div>
                <label class="input-label">Phone</label>
                <input type="text" name="phone" value="<?= e(old('phone', $provider['phone'] ?? '')) ?>" class="input" placeholder="+60 12-345 6789">
                <?php if ($err = error('phone')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
            </div>
            <div class="sm:col-span-2">
                <label class="input-label">Address</label>
                <textarea name="address" rows="2" class="input" placeholder="Your organization address"><?= e(old('address', $provider['address'] ?? '')) ?></textarea>
            </div>
        </div>

        <!-- Account Owner -->
        <h2 class="section-title mb-4 mt-8">Account Owner</h2>
        <div class="flex items-center gap-3 p-4 rounded-xl" style="background: var(--color-surface-50);">
            <div class="avatar" style="background: var(--color-primary-100); color: var(--color-primary-700);"><?= e(strtoupper(substr($user['name'] ?? 'O', 0, 1))) ?></div>
            <div class="text-sm">
                <p class="font-medium text-surface-800"><?= e($user['name'] ?? '') ?></p>
                <p class="text-surface-500"><?= e($user['email'] ?? '') ?></p>
            </div>
            <span class="badge badge-primary ml-auto">Owner</span>
        </div>

        <button type="submit" class="btn btn-primary btn-lg mt-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4.5 12.75 6 6 9-13.5"/></svg>
            Save Profile
        </button>
    </form>
</div>
