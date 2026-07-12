<?php
/** @var array<int, array<string, mixed>> $notifications */
$title = 'Notifications';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Notifications</h1>
        <p class="page-desc">Stay updated on your scholarship activity</p>
        <?php if (!empty($notifications) && in_array(false, array_column($notifications, 'is_read'))): ?>
        <form method="POST" action="<?= url('/notifications/mark-all-read') ?>" class="mt-2">
            <?= csrfField() ?>
            <button type="submit" class="btn btn-ghost btn-sm text-xs">Mark all as read</button>
        </form>
        <?php endif; ?>
    </div>

    <?php if (!empty($notifications)): ?>
    <div class="card overflow-hidden divide-y divide-surface-100">
        <?php foreach ($notifications as $n): ?>
        <div class="flex items-start gap-3 px-5 py-4 transition-colors <?= $n['is_read'] ? '' : 'bg-primary-50/40' ?>">
            <div class="w-5 shrink-0 mt-1">
                <?php if (!$n['is_read']): ?>
                    <span class="w-2 h-2 rounded-full bg-primary-500 block"></span>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <?php if ($n['title'] ?? ''): ?>
                            <p class="text-sm font-medium text-surface-800"><?= e($n['title']) ?></p>
                        <?php endif; ?>
                        <p class="text-sm <?= $n['is_read'] ? 'text-surface-600' : 'text-surface-700' ?>"><?= nl2br(e($n['message'] ?? '')) ?></p>
                        <p class="text-xs text-surface-400 mt-1"><?= date('d M Y, h:i A', strtotime($n['created_at'])) ?></p>
                    </div>
                    <?php if (!$n['is_read']): ?>
                    <form method="POST" action="<?= url('/notifications/' . e($n['id']) . '/read') ?>" class="shrink-0">
                        <?= csrfField() ?>
                        <button type="submit" class="btn btn-ghost btn-sm text-xs">Mark read</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75v-.7V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
        <p class="empty-title">No notifications</p>
        <p class="empty-desc">You'll see updates here when providers respond to your applications or when new scholarships are available.</p>
    </div>
    <?php endif; ?>
</div>
