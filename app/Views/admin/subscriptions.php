<?php $title = 'Subscriptions'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Subscriptions</h1><p class="page-desc">Provider &amp; student subscription plans</p></div>

    <!-- Stats -->
    <div class="grid gap-3 grid-cols-4 mb-6">
        <div class="stat-card"><div class="stat-value"><?= e($activeSubscriptions ?? 0) ?></div><div class="stat-label">Active Provider</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($expiredSubscriptions ?? 0) ?></div><div class="stat-label">Expired Provider</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($activeStudentSubscriptions ?? 0) ?></div><div class="stat-label">Active Student</div></div>
        <div class="stat-card"><div class="stat-value">RM<?= e(number_format($monthlyRevenue ?? 0)) ?></div><div class="stat-label">Revenue</div></div>
    </div>

    <!-- Provider Subscriptions -->
    <div class="card p-5 mb-8">
        <h2 class="section-title mb-4">Provider Subscriptions</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Provider</th><th>Plan</th><th>Status</th><th>Start</th><th>End</th><th></th></tr></thead>
                <tbody>
                    <?php if (empty($subscriptions)): ?>
                    <tr><td colspan="6" class="text-center text-surface-400 py-8">No subscriptions found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($subscriptions as $s): ?>
                    <tr>
                        <td class="font-medium"><?= e($s['provider_name']??'') ?></td>
                        <td class="text-sm"><?= e($s['plan_name']??'') ?></td>
                        <td><span class="badge <?= ($s['status']??'')==='active'?'badge-green':'badge-red' ?>"><?= e($s['status']??'') ?></span></td>
                        <td class="text-xs text-surface-500"><?= isset($s['start_date'])?date('d M Y',strtotime($s['start_date'])):'-' ?></td>
                        <td class="text-xs text-surface-500"><?= isset($s['end_date'])?date('d M Y',strtotime($s['end_date'])):'-' ?></td>
                        <td><a href="<?= url('/admin/subscriptions/' . e($s['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Student Subscriptions -->
    <div class="card p-5">
        <h2 class="section-title mb-4">Student Subscriptions</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Student</th><th>Email</th><th>Plan</th><th>Status</th><th>Start</th><th>End</th></tr></thead>
                <tbody>
                    <?php if (empty($studentSubscriptions)): ?>
                    <tr><td colspan="6" class="text-center text-surface-400 py-8">No student subscriptions found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($studentSubscriptions as $s): ?>
                    <tr>
                        <td class="font-medium"><?= e($s['student_name']??'') ?></td>
                        <td class="text-xs text-surface-500"><?= e($s['student_email']??'') ?></td>
                        <td class="text-sm"><?= e($s['plan_name']??'') ?></td>
                        <td><span class="badge <?= ($s['status']??'')==='active'?'badge-green':'badge-red' ?>"><?= e($s['status']??'') ?></span></td>
                        <td class="text-xs text-surface-500"><?= isset($s['start_date'])?date('d M Y',strtotime($s['start_date'])):'-' ?></td>
                        <td class="text-xs text-surface-500"><?= isset($s['end_date'])?date('d M Y',strtotime($s['end_date'])):'-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>