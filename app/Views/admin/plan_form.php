<?php $title = 'Plan Form'; $isEdit = isset($plan['id']); ?>
<div class="animate-fade-in">
    <a href="<?= url('/admin/plans') ?>" class="btn btn-ghost btn-sm mb-4">&larr; Plans</a>
    <div class="card-hover p-6 max-w-2xl">
        <h1 class="page-title mb-6"><?= $isEdit ? 'Edit' : 'Create' ?> Plan</h1>
        <form method="POST" action="<?= $isEdit ? '/admin/plans/'.e($plan['id']).'/edit' : '/admin/plans/create' ?>" class="space-y-4">
            <?= csrfField() ?>
            <div><label class="input-label">Name</label><input type="text" name="name" value="<?= e($plan['name']??'') ?>" class="input" required></div>
            <div><label class="input-label">Price (RM)</label><input type="number" name="price" value="<?= e($plan['price']??'') ?>" class="input" step="0.01" required></div>
            <div><label class="input-label">Interval</label><select name="interval" class="input"><option value="month" <?= ($plan['interval']??'')==='month'?'selected':'' ?>>Monthly</option><option value="year" <?= ($plan['interval']??'')==='year'?'selected':'' ?>>Yearly</option></select></div>
            <div><label class="input-label">Description</label><textarea name="description" class="input" rows="3"><?= e($plan['description']??'') ?></textarea></div>
            <div><label class="input-label">Max Scholarships</label><input type="number" name="max_scholarships" value="<?= e($plan['max_scholarships']??'') ?>" class="input" placeholder="0 = unlimited"></div>
            <div><label class="input-label">Max Staff Accounts</label><input type="number" name="max_staff" value="<?= e($plan['max_staff']??'') ?>" class="input" placeholder="0 = unlimited"></div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Save Changes' : 'Create Plan' ?></button>
        </form>
    </div>
</div>