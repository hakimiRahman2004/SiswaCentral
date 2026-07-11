<?php $title = $isEdit ? 'Edit Announcement' : 'New Announcement'; ?>
<div class="animate-fade-in">
    <a href="<?= url('/admin/announcements') ?>" class="btn btn-ghost btn-sm mb-4">&larr; Announcements</a>
    <div class="card-hover p-6 max-w-2xl">
        <h1 class="page-title mb-6"><?= $isEdit ? 'Edit' : 'New' ?> Announcement</h1>
        <form method="POST" action="<?= $isEdit ? '/admin/announcements/'.e($announcement['id']).'/edit' : '/admin/announcements/create' ?>" class="space-y-4">
            <?= csrfField() ?>
            <div><label class="input-label">Title</label><input type="text" name="title" value="<?= e($announcement['title']??'') ?>" class="input" required></div>
            <div><label class="input-label">Content</label><textarea name="content" class="input" rows="6" required><?= e($announcement['content']??'') ?></textarea></div>
            <div><label class="input-label">Target Audience</label><select name="audience" class="input"><option value="all" <?= ($announcement['audience']??'')==='all'?'selected':'' ?>>All Users</option><option value="student" <?= ($announcement['audience']??'')==='student'?'selected':'' ?>>Students</option><option value="provider" <?= ($announcement['audience']??'')==='provider'?'selected':'' ?>>Providers</option></select></div>
            <div><label class="flex items-center gap-2"><input type="checkbox" name="publish" value="1" <?= (($announcement['published']??false)||($announcement['status']??'')==='published')?'checked':'' ?> class="rounded border-surface-300 text-brand-600 focus:ring-brand-500"><span class="text-sm text-surface-600">Publish immediately</span></label></div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Save Changes' : 'Create Announcement' ?></button>
        </form>
    </div>
</div>