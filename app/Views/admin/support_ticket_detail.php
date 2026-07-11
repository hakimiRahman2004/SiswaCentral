<?php $title = 'Ticket Detail'; ?>
<div class="animate-fade-in">
    <a href="<?= url('/admin/support-tickets') ?>" class="btn btn-ghost btn-sm mb-4">&larr; Tickets</a>
    <div class="card-hover p-6 mb-6">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div><h1 class="page-title mb-1"><?= e($ticket['subject']??'') ?></h1><p class="text-sm text-surface-500">by <?= e($ticket['user_name']??'') ?></p></div>
            <div class="flex items-center gap-2">
                <span class="badge <?= match($ticket['status']??''){'open'=>'badge-red','in_progress'=>'badge-blue','resolved'=>'badge-green','closed'=>'badge-surface',default=>'badge-surface'} ?>"><?= e(str_replace('_',' ',($ticket['status']??''))) ?></span>
                <span class="badge <?= match($ticket['priority']??''){'high'=>'badge-red','medium'=>'badge-brand','low'=>'badge-surface',default=>'badge-surface'} ?>"><?= e(ucfirst($ticket['priority']??'normal')) ?></span>
            </div>
        </div>
        <p class="text-sm text-surface-700 mb-6"><?= e($ticket['description']??'') ?></p>
        <form method="POST" action="<?= url('/admin/support-tickets/' . e($ticket['id']) . '//update-status') ?>" class="flex items-center gap-3"><?= csrfField() ?><select name="status" class="input" style="width:auto"><option value="open" <?= ($ticket['status']??'')==='open'?'selected':''?>>Open</option><option value="in_progress" <?= ($ticket['status']??'')==='in_progress'?'selected':''?>>In Progress</option><option value="resolved" <?= ($ticket['status']??'')==='resolved'?'selected':''?>>Resolved</option><option value="closed" <?= ($ticket['status']??'')==='closed'?'selected':''?>>Closed</option></select><button type="submit" class="btn btn-primary btn-sm">Update</button></form>
    </div>
    <div class="card-hover p-6">
        <h2 class="font-semibold text-surface-800 text-sm mb-4">Replies</h2>
        <div class="space-y-4 mb-6">
            <?php foreach ($replies ?? [] as $r): ?>
            <div class="p-3 bg-surface-50 rounded-xl"><div class="flex justify-between mb-1"><span class="text-sm font-medium text-surface-800"><?= e($r['user_name']??'') ?></span><span class="text-xs text-surface-400"><?= date('d M Y H:i',strtotime($r['created_at'])) ?></span></div><p class="text-sm text-surface-600"><?= e($r['message']??'') ?></p></div>
            <?php endforeach; ?>
        </div>
        <form method="POST" action="<?= url('/admin/support-tickets/' . e($ticket['id']) . '//reply') ?>"><?= csrfField() ?><textarea name="message" class="input mb-3" rows="3" placeholder="Write a reply..." required></textarea><button type="submit" class="btn btn-primary btn-sm">Send Reply</button></form>
    </div>
</div>