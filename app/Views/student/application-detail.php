<?php
/** @var array<string, mixed> $app Application data */
/** @var array<int, array<string, mixed>>|null $logs Status timeline logs */
$title = 'Application Detail';
$appDetails = $app['details'] ?? (is_string($app['details'] ?? null) ? json_decode($app['details'], true) : []);
$appNumber = $app['application_number'] ?? 'APP-' . ($app['id'] ?? '');
?>
<div class="animate-fade-in">
    <a href="<?= url('/applications') ?>" class="inline-flex items-center gap-1.5 text-sm text-surface-500 hover:text-primary-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
        Back to Applications
    </a>

    <div class="card p-6 mb-6">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xs text-surface-400 mb-1"><?= e($appNumber) ?></p>
                <h1 class="page-title mb-1"><?= e($app['scholarship_name'] ?? '') ?></h1>
                <p class="text-sm text-surface-500"><?= e($app['company_name'] ?? '') ?></p>
            </div>
            <?php $s = $app['status'] ?? '';
            $sc = match($s) {
                'approved' => 'badge-success', 'rejected' => 'badge-danger',
                'screening','shortlisted','interview' => 'badge-warning',
                'document_required' => 'badge-info', 'submitted' => 'badge-info',
                'withdrawn','expired' => 'badge-surface', 'pending' => 'badge-warning',
                default => 'badge-surface'
            }; ?>
            <span class="badge <?= $sc ?> badge-lg"><?= e(ucfirst(str_replace('_', ' ', $s))) ?></span>
        </div>

        <div class="detail-grid">
            <div class="detail-row">
                <div class="detail-label">Applied</div>
                <div class="detail-value"><?= date('d M Y', strtotime($app['created_at'])) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Last Updated</div>
                <div class="detail-value"><?= date('d M Y', strtotime($app['updated_at'] ?? $app['created_at'])) ?></div>
            </div>
            <?php if (!empty($app['amount'])): ?>
            <div class="detail-row">
                <div class="detail-label">Award Amount</div>
                <div class="detail-value">RM<?= e(number_format((float)$app['amount'])) ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($appDetails['cgpa'])): ?>
            <div class="detail-row">
                <div class="detail-label">CGPA</div>
                <div class="detail-value"><?= e($appDetails['cgpa']) ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($appDetails['course'])): ?>
            <div class="detail-row">
                <div class="detail-label">Course</div>
                <div class="detail-value"><?= e($appDetails['course']) ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($appDetails['university'])): ?>
            <div class="detail-row">
                <div class="detail-label">University</div>
                <div class="detail-value"><?= e($appDetails['university']) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Motivation Letter -->
        <?php if (!empty($appDetails['motivation_letter'])): ?>
        <div class="mt-6">
            <h3 class="section-title mb-2">Motivation Letter</h3>
            <div class="p-4 rounded-xl text-sm text-surface-700 leading-relaxed whitespace-pre-wrap" style="background: var(--color-surface-50);"><?= e($appDetails['motivation_letter']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Achievements -->
        <?php if (!empty($appDetails['achievements'])): ?>
        <div class="mt-6">
            <h3 class="section-title mb-2">Achievements</h3>
            <div class="p-4 rounded-xl text-sm text-surface-700 leading-relaxed whitespace-pre-wrap" style="background: var(--color-surface-50);"><?= e($appDetails['achievements']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Status Timeline -->
        <?php if (!empty($logs)): ?>
        <div class="mt-8">
            <h3 class="section-title mb-4">Status Timeline</h3>
            <div class="timeline">
                <?php foreach (array_reverse($logs) as $log): ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= match($log['new_status'] ?? '') { 'approved' => 'success', 'rejected' => 'accent', 'screening','shortlisted','interview' => 'warning', 'document_required' => 'warning', default => 'primary' } ?>"></div>
                    <div>
                        <p class="text-sm text-surface-700">
                            <?php if ($log['old_status'] ?? null): ?>
                                <span class="font-medium"><?= e(ucfirst(str_replace('_', ' ', $log['new_status'] ?? ''))) ?></span>
                            <?php else: ?>
                                Application <span class="font-medium">submitted</span>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($log['notes'])): ?>
                        <p class="text-xs text-surface-500 mt-0.5"><?= e($log['notes']) ?></p>
                        <?php endif; ?>
                        <p class="text-xs text-surface-400 mt-0.5"><?= date('d M Y, h:i A', strtotime($log['created_at'])) ?> &middot; <?= e($log['changed_by_name'] ?? 'System') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($interview)): ?>
    <section class="card p-6 mb-6 border border-primary-200">
        <h2 class="section-title mb-2">Interview Invitation</h2>
        <p class="text-sm text-surface-600">Your interview is scheduled for <strong><?= e(date('d M Y, h:i A', strtotime($interview['scheduled_at']))) ?></strong>.</p>
        <?php if (!empty($interview['instructions'])): ?><p class="text-sm text-surface-600 mt-2 whitespace-pre-wrap"><?= e($interview['instructions']) ?></p><?php endif; ?>
        <a href="<?= e($interview['meeting_url']) ?>" target="_blank" rel="noopener" class="btn btn-primary mt-4">Join Meeting</a>
    </section>
    <?php endif; ?>

    <?php if (!empty($essayRequest)): ?>
    <section class="card p-6 mb-6">
        <h2 class="section-title mb-2">Essay Requested</h2>
        <p class="text-sm text-surface-700 whitespace-pre-wrap mb-2"><?= e($essayRequest['prompt']) ?></p>
        <?php if (!empty($essayRequest['due_at'])): ?><p class="text-xs text-surface-500 mb-4">Due <?= e(date('d M Y, h:i A', strtotime($essayRequest['due_at']))) ?></p><?php endif; ?>
        <form method="POST" action="<?= url('/applications/' . e($app['id']) . '/essay') ?>">
            <?= csrfField() ?>
            <label for="essay" class="input-label">Your Response</label>
            <textarea id="essay" name="essay" rows="8" maxlength="10000" class="input w-full resize-y" required <?= !empty($essayRequest['submitted_at']) ? 'readonly' : '' ?>><?= e($essayRequest['submitted_essay'] ?? '') ?></textarea>
            <?php if (!empty($essayRequest['submitted_at'])): ?>
                <p class="text-sm text-success-700 mt-3">Submitted <?= e(date('d M Y, h:i A', strtotime($essayRequest['submitted_at']))) ?></p>
            <?php else: ?>
                <button type="submit" class="btn btn-primary mt-3">Submit Essay</button>
            <?php endif; ?>
        </form>
    </section>
    <?php endif; ?>

    <?php if (!empty($app['communication_url'])): ?>
    <section class="card p-6 mb-6 border border-primary-200">
        <h2 class="section-title mb-2 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
            Provider Communication Link
        </h2>
        <p class="text-sm text-surface-600">Your provider has shared a communication channel for updates and coordination.</p>
        <a href="<?= e($app['communication_url']) ?>" target="_blank" rel="noopener" class="btn btn-primary mt-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
            Join Communication Group
        </a>
    </section>
    <?php endif; ?>

    <!-- Provider Notes -->
    <?php if (($app['notes'] ?? '') && !in_array($app['status'] ?? '', ['submitted', 'pending'])): ?>
    <div class="card p-6 mb-6">
        <h2 class="section-title mb-3">Notes from Provider</h2>
        <div class="p-4 rounded-xl text-sm text-surface-700 leading-relaxed" style="background: var(--color-surface-50);"><?= nl2br(e($app['notes'])) ?></div>
    </div>
    <?php endif; ?>

    <!-- Withdraw / Cancel -->
    <?php if (in_array($app['status'] ?? '', ['pending', 'submitted', 'screening'])): ?>
    <div class="card p-6">
        <h2 class="section-title mb-3"><?= $app['status'] === 'submitted' ? 'Cancel' : 'Withdraw' ?> Application</h2>
        <p class="text-sm text-surface-500 mb-4">Once <?= $app['status'] === 'submitted' ? 'cancelled' : 'withdrawn' ?>, this action cannot be undone.</p>
        <form method="POST" action="<?= url('/applications/' . e($app['id']) . '/' . ($app['status'] === 'submitted' ? 'cancel' : 'withdraw')) ?>" onsubmit="return confirm('Are you sure you want to <?= $app['status'] === 'submitted' ? 'cancel' : 'withdraw' ?> this application?')">
            <?= csrfField() ?>
            <button type="submit" class="btn btn-outline" style="color: var(--color-danger-600); border-color: var(--color-danger-300);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
                <?= $app['status'] === 'submitted' ? 'Cancel' : 'Withdraw' ?> Application
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>
