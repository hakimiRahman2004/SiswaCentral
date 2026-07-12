<?php
$title = 'Application Detail';
$badgeMap = fn($s) => match($s) {
    'approved' => 'badge-success',
    'rejected' => 'badge-danger',
    'screening' => 'badge-warning',
    'shortlisted' => 'badge-primary',
    'interview' => 'badge-info',
    'document_required' => 'badge-warning',
    default => 'badge-surface',
};
$details = json_decode($app['details'] ?? '{}', true) ?: [];
?>
<div class="animate-fade-in max-w-5xl mx-auto">
    <!-- Top bar -->
    <div class="flex items-center justify-between mb-6">
        <a href="<?= url('/admin/applications') ?>" class="btn btn-ghost btn-sm">&larr; Back to Applications</a>
        <span class="text-xs text-surface-400">Application <?= e($app['application_number'] ?? 'APP-' . $app['id']) ?></span>
    </div>

    <!-- Student Header Card -->
    <div class="card p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold font-heading shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                    <?= e(strtoupper(substr($app['student_name'] ?? 'U', 0, 1))) ?>
                </div>
                <div>
                    <h1 class="text-2xl font-bold font-heading text-surface-900"><?= e($app['student_name'] ?? '') ?></h1>
                    <p class="text-surface-500 text-sm">Applied for <span class="font-semibold text-surface-700"><?= e($app['scholarship_name'] ?? '') ?></span></p>
                    <div class="flex items-center gap-3 mt-1.5 text-xs text-surface-400">
                        <span><?= e($app['student_email'] ?? '') ?></span>
                        <?php if ($app['student_phone'] ?? ''): ?>
                        <span class="w-1 h-1 rounded-full bg-surface-300"></span>
                        <span><?= e($app['student_phone']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <span class="badge <?= $badgeMap($app['status'] ?? '') ?> badge-lg text-sm"><?= e(ucfirst(str_replace('_', ' ', $app['status'] ?? ''))) ?></span>
        </div>
    </div>

    <!-- Admin Force Status -->
    <div class="card p-5 mb-6">
        <h2 class="text-sm font-semibold text-surface-800 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            Admin Override
        </h2>
        <p class="text-sm text-surface-500 mb-3">Force change the application status. This will be logged in audit logs.</p>
        <form method="POST" action="<?= url('/admin/applications/' . e($app['id']) . '/force-status') ?>" class="flex items-end gap-3">
            <?= csrfField() ?>
            <div class="flex-1 max-w-xs">
                <label class="input-label">New Status</label>
                <select name="status" class="input w-full" required>
                    <option value="submitted" <?= ($app['status']??'')==='submitted'?'selected':'' ?>>Submitted</option>
                    <option value="screening" <?= ($app['status']??'')==='screening'?'selected':'' ?>>Screening</option>
                    <option value="shortlisted" <?= ($app['status']??'')==='shortlisted'?'selected':'' ?>>Shortlisted</option>
                    <option value="interview" <?= ($app['status']??'')==='interview'?'selected':'' ?>>Interview</option>
                    <option value="document_required" <?= ($app['status']??'')==='document_required'?'selected':'' ?>>Document Required</option>
                    <option value="approved" <?= ($app['status']??'')==='approved'?'selected':'' ?>>Approved</option>
                    <option value="rejected" <?= ($app['status']??'')==='rejected'?'selected':'' ?>>Rejected</option>
                </select>
            </div>
            <button type="submit" onclick="return confirm('Force change this application status?')" class="btn btn-danger btn-sm">Force Status</button>
        </form>
    </div>

    <!-- Application Details Grid -->
    <div class="grid gap-6 lg:grid-cols-2 mb-6">
        <!-- Academic Info -->
        <div class="card p-5">
            <h2 class="text-sm font-semibold text-surface-800 mb-4">Academic Details</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-xs text-surface-400 block">CGPA</span>
                    <span class="font-medium text-surface-700"><?= e($details['cgpa'] ?? $app['cgpa'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-xs text-surface-400 block">Course</span>
                    <span class="font-medium text-surface-700"><?= e($details['course'] ?? $app['course'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-xs text-surface-400 block">University</span>
                    <span class="font-medium text-surface-700"><?= e($details['university'] ?? $app['university'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-xs text-surface-400 block">Year</span>
                    <span class="font-medium text-surface-700"><?= e($details['year_of_study'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-xs text-surface-400 block">Gender</span>
                    <span class="font-medium text-surface-700 capitalize"><?= e($details['gender'] ?? $app['gender'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-xs text-surface-400 block">Monthly Income</span>
                    <span class="font-medium text-surface-700"><?= ($details['student_income'] ?? $app['student_income'] ?? '') ? 'RM' . e(number_format((float)($details['student_income'] ?? $app['student_income'] ?? 0))) : '-' ?></span>
                </div>
            </div>
        </div>

        <!-- Scholarship Info -->
        <div class="card p-5">
            <h2 class="text-sm font-semibold text-surface-800 mb-4">Scholarship Info</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-xs text-surface-400 block">Scholarship</span>
                    <span class="font-medium text-surface-700"><?= e($app['scholarship_name'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-xs text-surface-400 block">Amount</span>
                    <span class="font-medium text-surface-700"><?= $app['amount'] ? 'RM' . e(number_format((float)$app['amount'])) : '-' ?></span>
                </div>
                <div>
                    <span class="text-xs text-surface-400 block">Provider</span>
                    <span class="font-medium text-surface-700"><?= e($app['provider_name'] ?? $app['company_name'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-xs text-surface-400 block">Applied</span>
                    <span class="font-medium text-surface-700"><?= date('d M Y', strtotime($app['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Motivation Letter -->
    <?php if ($details['motivation_letter'] ?? $app['motivation_letter'] ?? ''): ?>
    <div class="card p-5 mb-6">
        <h2 class="text-sm font-semibold text-surface-800 mb-3">Motivation Letter</h2>
        <div class="p-4 rounded-xl bg-surface-50 text-sm leading-relaxed text-surface-700 whitespace-pre-wrap"><?= e($details['motivation_letter'] ?? $app['motivation_letter'] ?? '') ?></div>
    </div>
    <?php endif; ?>

    <!-- Achievements -->
    <?php if ($details['achievements'] ?? $app['achievements'] ?? ''): ?>
    <div class="card p-5 mb-6">
        <h2 class="text-sm font-semibold text-surface-800 mb-3">Achievements</h2>
        <div class="p-4 rounded-xl bg-surface-50 text-sm leading-relaxed text-surface-700 whitespace-pre-wrap"><?= e($details['achievements'] ?? $app['achievements'] ?? '') ?></div>
    </div>
    <?php endif; ?>

    <!-- Status Timeline -->
    <?php
    $logStmt = \App\Core\Database::connect()->prepare(
        "SELECT l.*, u.name AS changed_by_name FROM application_status_logs l LEFT JOIN users u ON l.changed_by = u.id WHERE l.application_id = ? ORDER BY l.created_at DESC"
    );
    $logStmt->execute([$app['id']]);
    $logs = $logStmt->fetchAll();
    ?>
    <?php if (!empty($logs)): ?>
    <div class="card p-5 mb-6">
        <h2 class="text-sm font-semibold text-surface-800 mb-4">Status Timeline</h2>
        <div class="relative pl-8 space-y-0">
            <div class="absolute left-3.5 top-2 bottom-2 w-0.5 bg-surface-200"></div>
            <?php foreach ($logs as $i => $log): $isFirst = $i === 0; ?>
            <div class="relative pb-6">
                <div class="absolute -left-4.5 top-1 w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center <?= $isFirst ? 'border-primary-500 bg-primary-50' : 'border-surface-300 bg-white' ?>">
                    <div class="w-1.5 h-1.5 rounded-full <?= $isFirst ? 'bg-primary-500' : 'bg-surface-300' ?>"></div>
                </div>
                <div>
                    <p class="text-sm <?= $isFirst ? 'text-surface-800 font-medium' : 'text-surface-600' ?>">
                        <?php if ($log['old_status'] ?? null): ?>
                            <span class="font-medium"><?= e(ucfirst(str_replace('_', ' ', $log['old_status']))) ?></span>
                            <svg class="w-3.5 h-3.5 inline mx-1 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            <span class="font-medium"><?= e(ucfirst(str_replace('_', ' ', $log['new_status']))) ?></span>
                        <?php else: ?>
                            Application submitted
                        <?php endif; ?>
                    </p>
                    <p class="text-xs text-surface-400 mt-0.5"><?= date('d M Y, h:i A', strtotime($log['created_at'])) ?> &middot; <?= e($log['changed_by_name'] ?? 'System') ?></p>
                    <?php if ($log['notes'] ?? ''): ?>
                    <p class="text-xs text-surface-500 mt-1 italic">"<?= e($log['notes']) ?>"</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
