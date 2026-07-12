<?php $title = 'Announcements'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Announcements</h1><p class="page-desc">Platform-wide announcements</p></div>

    <!-- New Announcement Form -->
    <div class="card p-5 mb-6">
        <h2 class="font-semibold text-surface-800 text-sm mb-3">New Announcement</h2>
        <form method="POST" action="<?= url('/admin/announcements') ?>">
            <?= csrfField() ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="input-label">Title</label>
                    <input type="text" name="title" class="input" required placeholder="e.g. System Maintenance Notice">
                </div>
                <div class="sm:col-span-2">
                    <label class="input-label">Message</label>
                    <textarea name="message" rows="3" class="input" required placeholder="Write your announcement here..."></textarea>
                </div>
                <div>
                    <label class="input-label">Type</label>
                    <select name="type" class="input">
                        <option value="info">Info</option>
                        <option value="warning">Warning</option>
                        <option value="alert">Alert</option>
                    </select>
                </div>
                <div>
                    <label class="input-label">Target</label>
                    <select name="target_role" class="input">
                        <option value="all">All Users</option>
                        <option value="student">Students</option>
                        <option value="provider">Providers</option>
                        <option value="admin">Admins</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm mt-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Publish Announcement
            </button>
        </form>
    </div>

    <!-- Announcement History -->
    <h2 class="font-semibold text-surface-800 text-sm mb-3">History</h2>
    <?php if (!empty($announcements)): ?>
    <div class="space-y-4">
        <?php foreach ($announcements as $a): ?>
        <div class="card-hover p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-semibold text-surface-800"><?= e($a['title']??'') ?></h3>
                        <span class="badge <?= match($a['type']??'info'){'warning'=>'badge-warning','alert'=>'badge-danger',default=>'badge-surface'} ?>"><?= e(ucfirst($a['type']??'info')) ?></span>
                        <span class="badge badge-surface"><?= e($a['target_role']??'all') ?></span>
                    </div>
                    <p class="text-sm text-surface-600 mt-1"><?= e($a['message']??'') ?></p>
                </div>
                <form method="POST" action="<?= url('/admin/announcements/' . e($a['id']) . '/delete') ?>" style="display:inline" onsubmit="return confirm('Delete this announcement?')">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--color-danger-500);">Delete</button>
                </form>
            </div>
            <p class="text-xs text-surface-400 mt-2">By <?= e($a['author_name']??'Admin') ?> &middot; <?= date('d M Y, g:i A', strtotime($a['created_at'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <p class="empty-title">No announcements yet</p>
        <p class="empty-desc">Create your first announcement above to notify users.</p>
    </div>
    <?php endif; ?>
</div>