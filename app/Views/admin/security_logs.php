<?php $title = 'Security Logs'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Security Logs</h1><p class="page-desc">Authentication and security events</p></div>
    <form method="GET" action="<?= url('/admin/security-logs') ?>" class="filter-bar">
        <input type="text" name="search" value="<?= e($_GET['search']??'') ?>" class="input" placeholder="Search..." style="max-width:240px">
        <select name="event" class="input" style="width:auto" onchange="this.form.submit()">
            <option value="">All Events</option><option value="login" <?= ($_GET['event']??'')==='login'?'selected':''?>>Login</option><option value="logout" <?= ($_GET['event']??'')==='logout'?'selected':''?>>Logout</option><option value="failed_login" <?= ($_GET['event']??'')==='failed_login'?'selected':''?>>Failed Login</option><option value="password_reset" <?= ($_GET['event']??'')==='password_reset'?'selected':''?>>Password Reset</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>
    <div class="table-wrap"><table><thead><tr><th>User</th><th>Event</th><th>IP</th><th>Time</th></tr></thead>
    <tbody><?php foreach ($logs as $l): ?><tr><td class="font-medium text-sm"><?= e($l['user_name']??'') ?></td><td><span class="badge <?= match($l['event']??''){'login'=>'badge-green','logout'=>'badge-surface','failed_login'=>'badge-red','password_reset'=>'badge-blue',default=>'badge-surface'} ?>"><?= e($l['event']??'') ?></span></td><td class="font-mono text-xs text-surface-500"><?= e($l['ip_address']??'') ?></td><td class="text-xs text-surface-500"><?= date('d M Y H:i',strtotime($l['created_at'])) ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>