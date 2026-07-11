<?php $title = 'Feedback'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">User Feedback</h1><p class="page-desc">Feedback from users</p></div>
    <div class="grid gap-3 grid-cols-3 mb-6">
        <div class="stat-card"><div class="stat-value"><?= e($totalFeedback??0) ?></div><div class="stat-label">Total</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($pendingCount??0) ?></div><div class="stat-label">Pending</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($reviewedCount??0) ?></div><div class="stat-label">Reviewed</div></div>
    </div>
    <div class="table-wrap"><table><thead><tr><th>User</th><th>Rating</th><th>Comment</th><th>Status</th><th>Date</th></tr></thead>
    <tbody><?php if (empty($feedback)): ?><tr><td colspan="5" class="text-center text-surface-400 py-8">No feedback yet.</td></tr><?php endif; ?>
    <?php foreach ($feedback as $f): ?><tr>
        <td class="font-medium text-sm"><?= e($f['user_name']??'') ?></td>
        <td class="whitespace-nowrap">
            <?php $r = (int)($f['rating']??0); for ($i=1;$i<=5;$i++): ?>
            <svg class="w-3.5 h-3.5 inline <?= $i<=$r?'text-yellow-400':'text-surface-200' ?>" fill="currentColor" viewBox="0 0 24 24"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5z"/></svg>
            <?php endfor; ?>
        </td>
        <td class="text-sm text-surface-600 max-w-xs truncate"><?= e($f['message']??'') ?></td>
        <td><span class="badge <?= ($f['status']??'')==='pending'?'badge-yellow':(($f['status']??'')==='reviewed'?'badge-green':'badge-surface') ?>"><?= e($f['status']??'pending') ?></span></td>
        <td class="text-xs text-surface-500"><?= isset($f['created_at'])?date('d M Y',strtotime($f['created_at'])):'-' ?></td>
    </tr><?php endforeach; ?></tbody></table></div>
</div>