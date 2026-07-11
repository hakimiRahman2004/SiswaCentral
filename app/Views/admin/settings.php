<?php $title = 'Settings'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Platform Settings</h1><p class="page-desc">Global configuration</p></div>
    <form method="POST" action="<?= url('/admin/settings') ?>" class="max-w-2xl space-y-6">
        <?= csrfField() ?>
        <div class="card-hover p-5 space-y-4">
            <h2 class="font-semibold text-surface-800">General</h2>
            <div><label class="input-label">Platform Name</label><input type="text" name="platform_name" value="<?= e($settings['platform_name']??'SISWACentral') ?>" class="input"></div>
            <div><label class="input-label">Support Email</label><input type="email" name="support_email" value="<?= e($settings['support_email']??'') ?>" class="input"></div>
            <div><label class="input-label">Maintenance Mode</label><label class="flex items-center gap-2"><input type="checkbox" name="maintenance_mode" value="1" <?= ($settings['maintenance_mode']??false)?'checked':'' ?> class="rounded border-surface-300 text-brand-600 focus:ring-brand-500"><span class="text-sm text-surface-600">Enable maintenance</span></label></div>
        </div>
        <div class="card-hover p-5 space-y-4">
            <h2 class="font-semibold text-surface-800">Registration</h2>
            <div><label class="input-label">Allow Student Registration</label><label class="flex items-center gap-2"><input type="checkbox" name="allow_student_registration" value="1" <?= ($settings['allow_student_registration']??true)?'checked':'' ?> class="input border-surface-300 text-brand-600 focus:ring-brand-500"><span class="text-sm text-surface-600">Enabled</span></label></div>
            <div><label class="input-label">Allow Provider Registration</label><label class="flex items-center gap-2"><input type="checkbox" name="allow_provider_registration" value="1" <?= ($settings['allow_provider_registration']??true)?'checked':'' ?> class="input border-surface-300 text-brand-600 focus:ring-brand-500"><span class="text-sm text-surface-600">Enabled</span></label></div>
        </div>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>