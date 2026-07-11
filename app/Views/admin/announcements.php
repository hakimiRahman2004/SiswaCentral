<?php $title = 'Announcements'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Announcements</h1><p class="page-desc">Platform-wide announcements</p></div>
    <div class="flex gap-3 mb-6"><a href="<?= url('/admin/announcements/create') ?>" class="btn btn-primary btn-sm">+ New Announcement</a></div>
    <div class="space-y-4">
        <?php foreach ($announcements as $a): ?>
        <div class="card-hover p-5">
            <div class="flex items-start justify-between gap-4">
                <div><h3 class="font-semibold text-surface-800"><?= e($a['title']??'') ?></h3><p class="text-sm text-surface-600 mt-1"><?= e(Str::limit($a['content']??'', 200)) ?></p></div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="badge <?= (($a['published']??false) || ($a['status']??'') === 'published') ? 'badge-green' : 'badge-surface' ?>"><?= ($a['published']??false) ? 'Published' : 'Draft' ?></span>
                    <a href="<?= url('/admin/announcements/' . e($a['id']) . '//edit') ?>" class="btn btn-ghost btn-sm">Edit</a>
                </div>
            </div>
            <p class="text-xs text-surface-400 mt-2"><?= date('d M Y',strtotime($a['created_at'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div>