<?php $title = 'Students'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Students</h1><p class="page-desc">All registered students</p></div>
    <div class="grid gap-3 grid-cols-3 mb-6">
        <div class="stat-card"><div class="stat-value"><?= e($totalStudents ?? 0) ?></div><div class="stat-label">Total</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($activeUsers ?? 0) ?></div><div class="stat-label">Active</div></div>
        <div class="stat-card border-red-200"><div class="stat-value text-red-500"><?= e($bannedUsers ?? 0) ?></div><div class="stat-label">Banned</div></div>
    </div>
    <form method="GET" action="<?= url('/admin/students') ?>" class="filter-bar">
        <input type="text" name="search" value="<?= e($_GET['search'] ?? '') ?>" class="input" placeholder="Search..." style="max-width:240px">
        <select name="status" class="input" style="width:auto" onchange="this.form.submit()">
            <option value="">All</option><option value="active" <?= ($_GET['status']??'')==='active'?'selected':'' ?>>Active</option><option value="banned" <?= ($_GET['status']??'')==='banned'?'selected':'' ?>>Banned</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </form>
    <div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Joined</th><th></th></tr></thead>
    <tbody><?php foreach ($users as $u): ?><tr><td class="font-medium"><?= e($u['name']??'') ?></td><td class="text-xs text-surface-500"><?= e($u['email']??'') ?></td><td><span class="badge <?= ($u['status']??'')==='active'?'badge-green':'badge-red' ?>"><?= e($u['status']??'active') ?></span></td><td class="text-xs text-surface-500"><?= date('d M Y',strtotime($u['created_at'])) ?></td><td><a href="<?= url('/admin/users/' . e($u['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td></tr><?php endforeach; ?></tbody></table></div>
</div>