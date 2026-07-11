<?php $title = 'Scholarship Detail'; ?>
<div class="animate-fade-in">
    <a href="<?= url('/admin/scholarships') ?>" class="btn btn-ghost btn-sm mb-4">&larr; Back</a>
    <div class="card-hover p-6">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div><h1 class="page-title mb-1"><?= e($scholarship['name']??'') ?></h1><p class="text-sm text-surface-500">by <?= e($scholarship['provider_name']??'') ?></p></div>
            <span class="badge <?= match($scholarship['status']??''){'active'=>'badge-green','draft'=>'badge-surface','closed'=>'badge-red',default=>'badge-surface'} ?>"><?= e($scholarship['status']??'') ?></span>
        </div>
        <dl class="grid gap-3 sm:grid-cols-2 text-sm mb-6">
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Deadline</dt><dd class="font-medium"><?= e($scholarship['deadline']??'') ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Total Applications</dt><dd class="font-medium"><?= e($scholarship['application_count']??0) ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Amount</dt><dd class="font-medium">RM<?= e(number_format($scholarship['amount']??0)) ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Category</dt><dd class="font-medium"><?= e($scholarship['category']??'') ?></dd></div>
        </dl>
    </div>
</div>