<?php
$isEdit = !empty($scholarship);
$title = $isEdit ? 'Edit Scholarship' : 'Create Scholarship';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title"><?= $isEdit ? 'Edit Scholarship' : 'Create Scholarship' ?></h1>
        <p class="page-desc"><?= $isEdit ? 'Update your scholarship listing' : 'List a new scholarship opportunity' ?></p>
    </div>

    <?php if ($flash = flash('success')): ?>
        <div class="flash-success"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><?= e($flash) ?></div>
    <?php endif; ?>
    <?php if ($flash = flash('error')): ?>
        <div class="flash-error"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><?= e($flash) ?></div>
    <?php endif; ?>

    <?php if (!$isEdit && isset($usage)): ?>
    <div class="grid gap-3 grid-cols-2 mb-6">
        <div class="stat-card"><div class="stat-label">Scholarships</div><div class="stat-value text-lg"><?= ($usage['scholarships_used'] ?? 0) ?> / <?= ($usage['scholarships_max'] ?? '∞') ?></div></div>
        <div class="stat-card"><div class="stat-label">Staff</div><div class="stat-value text-lg"><?= ($usage['staff_used'] ?? 0) ?> / <?= ($usage['staff_max'] ?? '∞') ?></div></div>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= $isEdit ? '/provider/scholarships/'.e($scholarship['id']).'/edit' : '/provider/scholarships/create' ?>" class="card-hover p-6">
        <?= csrfField() ?>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="input-label">Scholarship Name</label>
                <input type="text" name="name" value="<?= e(old('name', $scholarship['name'] ?? '')) ?>" class="input" required>
            </div>
            <div>
                <label class="input-label">Amount (RM)</label>
                <input type="number" name="amount" value="<?= e(old('amount', $scholarship['amount'] ?? '')) ?>" class="input">
            </div>
            <div>
                <label class="input-label">Min. CGPA</label>
                <input type="text" name="min_cgpa" value="<?= e(old('min_cgpa', $scholarship['min_cgpa'] ?? '')) ?>" class="input" placeholder="e.g. 3.00">
            </div>
            <div>
                <label class="input-label">Max Income (RM)</label>
                <input type="text" name="max_income" value="<?= e(old('max_income', $scholarship['max_income'] ?? '')) ?>" class="input" placeholder="e.g. 5000">
            </div>
            <div>
                <label class="input-label">Deadline</label>
                <input type="date" name="deadline" value="<?= e(old('deadline', $scholarship['deadline'] ?? '')) ?>" class="input" required>
            </div>
            <div class="sm:col-span-2">
                <label class="input-label">Eligible Courses (comma separated)</label>
                <input type="text" name="eligible_courses" value="<?= e(old('eligible_courses', $scholarship['eligible_courses'] ?? '')) ?>" class="input" placeholder="e.g. Computer Science, Engineering, Business">
            </div>
            <div class="sm:col-span-2">
                <label class="input-label">Requirements</label>
                <textarea name="requirements" rows="3" class="input" placeholder="List the requirements for applicants..."><?= e(old('requirements', $scholarship['requirements'] ?? '')) ?></textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="input-label">Description</label>
                <textarea name="description" rows="5" class="input" placeholder="Describe the scholarship opportunity in detail..."><?= e(old('description', $scholarship['description'] ?? '')) ?></textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Scholarship' : 'Create Scholarship' ?></button>
            <a href="<?= url('/provider/scholarships') ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>