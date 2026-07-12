<?php $title = 'User Detail'; ?>
<div class="animate-fade-in">
    <a href="<?= url('/admin/users') ?>" class="btn btn-ghost btn-sm mb-4">&larr; Back</a>
    <div class="card-hover p-6">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="page-title mb-1"><?= e($user['name'] ?? '') ?></h1>
                <p class="text-sm text-surface-500"><?= e($user['email'] ?? '') ?></p>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge <?= match($user['role']??''){'admin'=>'badge-red','provider'=>'badge-brand','student'=>'badge-blue',default=>'badge-surface'} ?>"><?= e(ucfirst($user['role']??'')) ?></span>
                <span class="badge <?= ($user['status']??'')==='active'?'badge-green':'badge-red' ?>"><?= e($user['status']??'active') ?></span>
            </div>
        </div>
        <dl class="grid gap-3 sm:grid-cols-2 text-sm mb-6">
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Name</dt><dd class="font-medium text-surface-800"><?= e($user['name']??'') ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Email</dt><dd class="font-medium text-surface-800"><?= e($user['email']??'') ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Role</dt><dd class="font-medium text-surface-800"><?= e(ucfirst($user['role']??'')) ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Joined</dt><dd class="font-medium text-surface-800"><?= date('d M Y',strtotime($user['created_at'])) ?></dd></div>
        </dl>
        <form method="POST" action="<?= url('/admin/users/' . e($user['id']) . '/toggle-status') ?>"><?= csrfField() ?><button type="submit" class="btn <?= ($user['status']??'')==='active' ? 'btn-outline text-red-500 border-red-300' : 'btn-primary' ?>" onclick="return confirm('Toggle user status?')"><?= ($user['status']??'')==='active' ? 'Suspend User' : 'Activate User' ?></button></form>
    </div>
</div>