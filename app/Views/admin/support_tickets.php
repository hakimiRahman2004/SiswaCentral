<?php $title = 'Support Tickets'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Support Tickets</h1><p class="page-desc">User support requests</p></div>
    <div class="grid gap-3 grid-cols-3 mb-6">
        <div class="stat-card"><div class="stat-value"><?= e($openTickets??0) ?></div><div class="stat-label">Open</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($inProgressTickets??0) ?></div><div class="stat-label">In Progress</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($resolvedTickets??0) ?></div><div class="stat-label">Resolved</div></div>
    </div>
    <form method="GET" action="<?= url('/admin/support-tickets') ?>" class="filter-bar">
        <input type="text" name="search" value="<?= e($_GET['search']??'') ?>" class="input" placeholder="Search..." style="max-width:240px">
        <select name="status" class="input" style="width:auto" onchange="this.form.submit()">
            <option value="">All</option><option value="open" <?= ($_GET['status']??'')==='open'?'selected':''?>>Open</option><option value="in_progress" <?= ($_GET['status']??'')==='in_progress'?'selected':''?>>In Progress</option><option value="resolved" <?= ($_GET['status']??'')==='resolved'?'selected':''?>>Resolved</option><option value="closed" <?= ($_GET['status']??'')==='closed'?'selected':''?>>Closed</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>
    <div class="table-wrap"><table><thead><tr><th>Subject</th><th>User</th><th>Status</th><th>Priority</th><th>Created</th><th></th></tr></thead>
    <tbody><?php foreach ($tickets as $t): ?><tr><td class="font-medium"><?= e($t['subject']??'') ?></td><td class="text-sm text-surface-600"><?= e($t['user_name']??'') ?></td><td><span class="badge <?= match($t['status']??''){'open'=>'badge-red','in_progress'=>'badge-blue','resolved'=>'badge-green','closed'=>'badge-surface',default=>'badge-surface'} ?>"><?= e(str_replace('_',' ',($t['status']??''))) ?></span></td><td><span class="badge <?= match($t['priority']??''){'high'=>'badge-red','medium'=>'badge-brand','low'=>'badge-surface',default=>'badge-surface'} ?>"><?= e(ucfirst($t['priority']??'normal')) ?></span></td><td class="text-xs text-surface-500"><?= date('d M Y',strtotime($t['created_at'])) ?></td><td><a href="<?= url('/admin/support-tickets/' . e($t['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td></tr><?php endforeach; ?></tbody></table></div>
</div>