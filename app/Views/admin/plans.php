<?php $title = 'Subscription Plans'; $tab = $_GET['tab'] ?? 'provider'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Subscription Plans</h1><p class="page-desc">Manage provider and student plans</p></div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 p-1 bg-surface-100 rounded-xl w-fit">
        <a href="?tab=provider" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors <?= $tab === 'provider' ? 'bg-white text-surface-900 shadow-sm' : 'text-surface-500 hover:text-surface-700' ?>">Provider Plans</a>
        <a href="?tab=student" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors <?= $tab === 'student' ? 'bg-white text-surface-900 shadow-sm' : 'text-surface-500 hover:text-surface-700' ?>">Student Plans</a>
    </div>

    <?php if ($tab === 'provider'): ?>
    <div class="flex gap-3 mb-6">
        <a href="<?= url('/admin/plans/create') ?>" class="btn btn-primary btn-sm">+ New Provider Plan</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php if (empty($providerPlans)): ?><p class="text-surface-400 col-span-full text-center py-12">No provider plans found.</p><?php endif; ?>
        <?php foreach ($providerPlans as $p): ?>
        <div class="card-hover p-5">
            <h3 class="font-semibold text-surface-800 text-lg mb-1"><?= e($p['name']??'') ?></h3>
            <p class="text-2xl font-bold text-brand-600 mb-3">
                <?= ($p['price']??0) > 0 ? 'RM'.e(number_format($p['price']??0)) : 'Free' ?>
                <?php if (($p['price']??0) > 0): ?><span class="text-sm font-normal text-surface-500">/<?= e($p['interval']??'month') ?></span><?php endif; ?>
            </p>
            <p class="text-xs text-surface-500 mb-3">
                Max scholarships: <?= e($p['max_scholarships']??'∞') ?> | Max staff: <?= e($p['max_staff']??'∞') ?>
            </p>
            <div class="flex flex-wrap gap-1.5 mb-4">
                <span class="badge <?= ($p['is_active']??1) ? 'badge-green' : 'badge-red' ?> text-xs"><?= ($p['is_active']??1) ? 'Active' : 'Inactive' ?></span>
            </div>
            <?php if (!empty($p['features'])): $feats = explode(',', $p['features']); ?>
            <ul class="text-xs text-surface-500 space-y-1 mb-4">
                <?php foreach ($feats as $f): $f = trim($f); if ($f): ?><li class="flex items-center gap-1.5"><span class="text-green-500">✓</span> <?= e($f) ?></li><?php endif; endforeach; ?>
            </ul>
            <?php endif; ?>
            <div class="flex gap-2 mt-4 pt-4 border-t border-surface-100">
                <a href="<?= url('/admin/plans/' . e($p['id']) . '//edit') ?>" class="btn btn-outline btn-sm">Edit</a>
                <form method="POST" action="<?= url('/admin/plans/' . e($p['id']) . '//toggle') ?>"><?= csrfField() ?><button type="submit" class="btn btn-ghost btn-sm <?= ($p['is_active']??1) ? 'text-red-500' : 'text-green-500' ?>" onclick="return confirm('Toggle plan?')"><?= ($p['is_active']??1) ? 'Deactivate' : 'Activate' ?></button></form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($tab === 'student'): ?>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php if (empty($studentPlans)): ?><p class="text-surface-400 col-span-full text-center py-12">No student plans found.</p><?php endif; ?>
        <?php foreach ($studentPlans as $p): ?>
        <div class="card-hover p-5">
            <h3 class="font-semibold text-surface-800 text-lg mb-1"><?= e($p['name']??'') ?></h3>
            <p class="text-2xl font-bold text-brand-600 mb-3">
                <?= ($p['price']??0) > 0 ? 'RM'.e(number_format($p['price']??0)) : 'Free' ?>
                <?php if (($p['price']??0) > 0): ?><span class="text-sm font-normal text-surface-500">/month</span><?php endif; ?>
            </p>
            <p class="text-xs text-surface-500 mb-3">
                Max applications: <?= e($p['max_applications']??'∞') ?> | Max documents: <?= e($p['max_documents']??'∞') ?>
            </p>
            <div class="flex flex-wrap gap-1.5 mb-4">
                <?php if ($p['has_smart_matching']??0): ?><span class="badge badge-green text-xs">Smart matching</span><?php endif; ?>
                <?php if ($p['has_priority_support']??0): ?><span class="badge badge-blue text-xs">Priority support</span><?php endif; ?>
                <span class="badge <?= ($p['is_active']??1) ? 'badge-green' : 'badge-red' ?> text-xs"><?= ($p['is_active']??1) ? 'Active' : 'Inactive' ?></span>
            </div>
            <?php if (!empty($p['features'])): $feats = json_decode($p['features'], true); if (is_array($feats)): ?>
            <ul class="text-xs text-surface-500 space-y-1 mb-4">
                <?php foreach ($feats as $f): ?><li class="flex items-center gap-1.5"><span class="text-green-500">✓</span> <?= e($f) ?></li><?php endforeach; ?>
            </ul>
            <?php endif; endif; ?>
            <div class="flex gap-2 mt-4 pt-4 border-t border-surface-100">
                <form method="POST" action="<?= url('/admin/plans/student/' . e($p['id']) . '//toggle') ?>"><?= csrfField() ?><button type="submit" class="btn btn-ghost btn-sm <?= ($p['is_active']??1) ? 'text-red-500' : 'text-green-500' ?>" onclick="return confirm('Toggle plan?')"><?= ($p['is_active']??1) ? 'Deactivate' : 'Activate' ?></button></form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>