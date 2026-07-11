<?php $title = 'Subscription Detail'; ?>
<div class="animate-fade-in">
    <a href="<?= url('/admin/subscriptions') ?>" class="btn btn-ghost btn-sm mb-4">&larr; Back</a>
    <div class="card-hover p-6">
        <h1 class="page-title mb-4"><?= e($subscription['provider_name']??'') ?> - <?= e($subscription['plan_name']??'') ?></h1>
        <dl class="grid gap-3 sm:grid-cols-2 text-sm mb-6">
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Status</dt><dd><span class="badge <?= ($subscription['status']??'')==='active'?'badge-green':'badge-red' ?>"><?= e($subscription['status']??'') ?></span></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Amount</dt><dd class="font-medium">RM<?= e(number_format($subscription['plan_price']??$subscription['amount']??0)) ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Start Date</dt><dd class="font-medium"><?= date('d M Y',strtotime($subscription['start_date'])) ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">End Date</dt><dd class="font-medium"><?= date('d M Y',strtotime($subscription['end_date'])) ?></dd></div>
        </dl>
    </div>
</div>