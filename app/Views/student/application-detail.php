<?php
/** @var array<string, mixed> $app Application data */
/** @var array<int, array<string, mixed>>|null $logs Status timeline logs */
$title = 'Application Detail';
?>
<div class="animate-fade-in">
    <a href="<?= url('/applications') ?>" class="inline-flex items-center gap-1.5 text-sm text-surface-500 hover:text-primary-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
        Back to Applications
    </a>

    <div class="card p-6 mb-6">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="page-title mb-1"><?= e($app['scholarship_name'] ?? '') ?></h1>
                <p class="text-sm text-surface-500"><?= e($app['provider_name'] ?? '') ?></p>
            </div>
            <?php $s = $app['status'] ?? '';
            $sc = match($s) { 'approved' => 'badge-success', 'rejected' => 'badge-danger', 'screening','shortlisted','interview' => 'badge-warning', 'submitted' => 'badge-info', default => 'badge-surface' }; ?>
            <span class="badge <?= $sc ?> badge-lg"><?= e(ucfirst(str_replace('_', ' ', $s))) ?></span>
        </div>

        <div class="detail-grid">
            <div class="detail-row">
                <div class="detail-label">Submitted</div>
                <div class="detail-value"><?= date('d M Y', strtotime($app['created_at'])) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Last Updated</div>
                <div class="detail-value"><?= date('d M Y', strtotime($app['updated_at'])) ?></div>
            </div>
            <?php if ($app['amount']): ?>
            <div class="detail-row">
                <div class="detail-label">Award Amount</div>
                <div class="detail-value">RM<?= e(number_format($app['amount'])) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Status Timeline -->
        <?php if (!empty($logs)): ?>
        <div class="mt-8">
            <h3 class="section-title mb-4">Status Timeline</h3>
            <div class="timeline">
                <?php foreach ($logs as $log): ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= match($log['new_status'] ?? '') { 'approved' => 'success', 'rejected' => 'accent', 'screening','shortlisted','interview' => 'warning', default => 'primary' } ?>"></div>
                    <div>
                        <p class="text-sm text-surface-700">
                            <?php if ($log['old_status'] ?? null): ?>
                                Status changed from <span class="font-medium"><?= e(ucfirst(str_replace('_', ' ', $log['old_status']))) ?></span> to <span class="font-medium"><?= e(ucfirst(str_replace('_', ' ', $log['new_status']))) ?></span>
                            <?php else: ?>
                                Application <span class="font-medium">submitted</span>
                            <?php endif; ?>
                        </p>
                        <p class="text-xs text-surface-400 mt-0.5"><?= date('d M Y, h:i A', strtotime($log['created_at'])) ?> by <?= e($log['changed_by_name'] ?? 'System') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Cancel -->
    <?php if ($app['status'] === 'submitted'): ?>
    <div class="card p-6">
        <h2 class="section-title mb-3">Cancel Application</h2>
        <p class="text-sm text-surface-500 mb-4">Once cancelled, this action cannot be undone. Only cancel if you're certain you no longer wish to apply.</p>
        <form method="POST" action="<?= url('/applications/' . e($app['id']) . '//cancel') ?>" onsubmit="return confirm('Are you sure you want to cancel this application?')">
            <?= csrfField() ?>
            <button type="submit" class="btn btn-outline" style="color: var(--color-danger-600); border-color: var(--color-danger-300);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
                Cancel Application
            </button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Admin Notes -->
    <?php if (($app['notes'] ?? '') && $app['status'] !== 'submitted'): ?>
    <div class="card p-6 mt-6">
        <h2 class="section-title mb-3">Notes from Provider</h2>
        <div class="p-4 rounded-xl text-sm text-surface-700 leading-relaxed" style="background: var(--color-surface-50);"><?= nl2br(e($app['notes'])) ?></div>
    </div>
    <?php endif; ?>
</div>
