<?php
$title = 'Complete Your Profile';
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary-50 mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
            </div>
            <h1 class="page-title">Complete Your Organization Profile</h1>
            <p class="page-desc">Fill in your company details so we can verify your account. You'll be able to start once approved.</p>
        </div>

        <form method="POST" action="<?= url('/provider/onboarding') ?>" class="card p-6" enctype="multipart/form-data">
            <?= csrfField() ?>

            <h2 class="section-title mb-4">Organization Details</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="input-label">Company Name <span class="text-danger-500">*</span></label>
                    <input type="text" name="company_name" value="<?= e(old('company_name', $provider['company_name'] ?? '')) ?>" class="input" required placeholder="e.g. Yayasan Pendidikan Malaysia">
                    <?php if ($err = error('company_name')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="input-label">Registration No. <span class="text-danger-500">*</span></label>
                    <input type="text" name="registration_no" value="<?= e(old('registration_no', $provider['registration_no'] ?? '')) ?>" class="input" required placeholder="e.g. 1234567-A">
                    <?php if ($err = error('registration_no')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="input-label">Phone <span class="text-danger-500">*</span></label>
                    <input type="text" name="phone" value="<?= e(old('phone', $provider['phone'] ?? '')) ?>" class="input" required placeholder="+60 12-345 6789">
                    <?php if ($err = error('phone')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div class="sm:col-span-2">
                    <label class="input-label">Address <span class="text-danger-500">*</span></label>
                    <textarea name="address" rows="3" class="input" required placeholder="Your organization's registered address"><?= e(old('address', $provider['address'] ?? '')) ?></textarea>
                    <?php if ($err = error('address')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
            </div>

            <h2 class="section-title mb-4 mt-8">Verification Document</h2>
            <p class="text-sm text-surface-500 mb-3">Upload your business registration certificate or any official document for verification (PDF, JPG, or PNG).</p>
            <div class="border-2 border-dashed border-surface-300 rounded-xl p-4 text-center hover:border-primary-400 transition-colors">
                <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                <?php if (!empty($provider['document'])): ?>
                <p class="text-xs text-surface-400 mt-2">Already uploaded: <?= e(basename($provider['document'])) ?></p>
                <?php endif; ?>
            </div>

            <h2 class="section-title mb-4 mt-8">Account Owner</h2>
            <div class="flex items-center gap-3 p-4 rounded-xl" style="background: var(--color-surface-50);">
                <div class="avatar" style="background: var(--color-primary-100); color: var(--color-primary-700);"><?= e(strtoupper(substr($user['name'] ?? 'O', 0, 1))) ?></div>
                <div class="text-sm">
                    <p class="font-medium text-surface-800"><?= e($user['name'] ?? '') ?></p>
                    <p class="text-surface-500"><?= e($user['email'] ?? '') ?></p>
                </div>
                <span class="badge badge-primary ml-auto">Owner</span>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit" class="btn btn-primary btn-lg flex-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    Submit for Verification
                </button>
                <a href="/logout" class="btn btn-outline btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>
