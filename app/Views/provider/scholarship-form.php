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

    <form method="POST" action="<?= $isEdit ? '/provider/scholarships/'.e($scholarship['id']).'/edit' : '/provider/scholarships/create' ?>" class="card-hover p-6" enctype="multipart/form-data">
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

            <!-- Supporting Documents -->
            <div class="sm:col-span-2">
                <div class="section-header">
                    <h2 class="section-title">Supporting Documents</h2>
                </div>
                <p class="text-sm text-surface-500 mb-2">Upload documents for admin review (e.g., company registration, scholarship criteria, T&amp;Cs).</p>

                <?php if ($isEdit && !empty($documents)): ?>
                <div class="mb-3 space-y-2">
                    <?php foreach ($documents as $doc): ?>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-surface-50">
                        <span class="text-sm text-surface-700"><?= e($doc['original_name']) ?></span>
                        <form method="POST" action="<?= url('/provider/scholarships/doc/' . e($doc['id']) . '/delete') ?>" style="display:inline" onsubmit="return confirm('Remove this document?')">
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--color-danger-500)">Remove</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="border-2 border-dashed border-surface-300 rounded-xl p-4 text-center hover:border-primary-400 transition-colors">
                    <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    <p class="text-xs text-surface-400 mt-1">PDF, DOC, or images. Max 5MB each.</p>
                </div>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Scholarship' : 'Submit for Review' ?></button>
            <a href="<?= url('/provider/scholarships') ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>