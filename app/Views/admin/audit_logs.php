<?php $title = 'Audit Logs'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Audit Logs</h1><p class="page-desc">Detailed system activity</p></div>
    <form method="GET" action="<?= url('/admin/audit-logs') ?>" class="filter-bar">
        <input type="text" name="search" value="<?= e($_GET['search']??'') ?>" class="input" placeholder="Search..." style="max-width:240px">
        <select name="action" class="input" style="width:auto" onchange="this.form.submit()">
            <option value="">All Actions</option><option value="create" <?= ($_GET['action']??'')==='create'?'selected':''?>>Create</option><option value="update" <?= ($_GET['action']??'')==='update'?'selected':''?>>Update</option><option value="delete" <?= ($_GET['action']??'')==='delete'?'selected':''?>>Delete</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>
    <div class="table-wrap"><table><thead><tr><th>User</th><th>Action</th><th>Entity</th><th>Description</th><th>Time</th></tr></thead>
    <tbody><?php foreach ($logs as $l): ?><tr><td class="font-medium text-sm"><?= e($l['user_name']??'') ?></td><td><span class="badge <?= match($l['action']??''){'create'=>'badge-green','update'=>'badge-blue','delete'=>'badge-red',default=>'badge-surface'} ?>"><?= e($l['action']??'') ?></span></td><td class="text-sm text-surface-600"><?= e($l['entity_type']??'') ?> #<?= e($l['entity_id']??'') ?></td><td class="text-xs text-surface-500 max-w-xs truncate"><?= e($l['description']??'') ?></td><td class="text-xs text-surface-500"><?= date('d M Y H:i',strtotime($l['created_at']))??'' ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>