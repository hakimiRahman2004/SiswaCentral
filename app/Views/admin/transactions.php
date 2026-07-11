<?php $title = 'Transactions'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Transactions</h1><p class="page-desc">Payment history</p></div>
    <div class="grid gap-3 grid-cols-3 mb-6">
        <div class="stat-card"><div class="stat-value">RM<?= e(number_format($totalRevenue??0)) ?></div><div class="stat-label">Total Revenue</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($totalTransactions ?? 0) ?></div><div class="stat-label">Transactions</div></div>
        <?php $successful = array_filter($payments ?? [], fn($p) => ($p['status']??'') === 'success'); ?>
        <div class="stat-card"><div class="stat-value"><?= e(count($successful)) ?></div><div class="stat-label">Successful</div></div>
    </div>
    <div class="table-wrap"><table><thead><tr><th>Ref</th><th>Provider</th><th>Amount</th><th>Plan</th><th>Status</th><th>Date</th></tr></thead>
    <tbody><?php if (empty($payments)): ?><tr><td colspan="6" class="text-center text-surface-400 py-8">No transactions found.</td></tr><?php endif; ?>
    <?php foreach ($payments as $t): ?><tr><td class="font-mono text-xs"><?= e($t['bill_code']??$t['reference']??'') ?></td><td class="text-sm"><?= e($t['company_name']??'') ?></td><td class="font-medium">RM<?= e(number_format($t['amount']??0)) ?></td><td class="text-sm text-surface-600"><?= e($t['plan_name']??'') ?></td><td><span class="badge <?= ($t['status']??'')==='success'?'badge-green':'badge-red' ?>"><?= e($t['status']??'') ?></span></td><td class="text-xs text-surface-500"><?= isset($t['created_at'])?date('d M Y',strtotime($t['created_at'])):'-' ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>