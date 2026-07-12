<?php
/** @var array<int, array<string, mixed>> $applications */
$title = 'My Applications';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">My Applications</h1>
        <p class="page-desc">Track the status of all your scholarship applications</p>
    </div>

    <?php if (!empty($applications)): ?>
    <!-- Status Summary -->
    <?php
    $counts = [];
    foreach ($applications as $a) {
        $st = $a['status'] ?? 'unknown';
        $counts[$st] = ($counts[$st] ?? 0) + 1;
    }
    ?>
    <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 mb-6">
        <?php foreach (['submitted','screening','shortlisted','interview','approved'] as $st): ?>
        <?php if (isset($counts[$st])): ?>
        <div class="stat-card">
            <div class="stat-value text-lg" style="color: <?= match($st) {'approved'=>'var(--color-success-600)','screening'=>'var(--color-warning-600)','shortlisted'=>'var(--color-info-600)','interview'=>'var(--color-warning-700)',default=>'var(--color-primary-600)'} ?>"><?= $counts[$st] ?></div>
            <div class="stat-label"><?= e(ucfirst(str_replace('_', ' ', $st))) ?></div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Application No.</th>
                    <th>Scholarship</th>
                    <th>Provider</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td class="text-xs font-medium text-surface-600"><?= e($app['application_number'] ?? 'APP-' . $app['id']) ?></td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-700);">
                                <?= e(substr($app['scholarship_name'] ?? 'S', 0, 1)) ?>
                            </div>
                            <span class="font-medium text-surface-800"><?= e($app['scholarship_name'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="text-surface-500"><?= e($app['company_name'] ?? '') ?></td>
                    <td>
                        <?php $s = $app['status'] ?? '';
                        $sc = match($s) {
                            'approved' => 'badge-success', 'rejected' => 'badge-danger',
                            'screening','shortlisted','interview' => 'badge-warning',
                            'document_required' => 'badge-info', 'submitted' => 'badge-info',
                            'withdrawn','expired' => 'badge-surface', 'pending' => 'badge-warning',
                            default => 'badge-surface'
                        }; ?>
                        <span class="badge <?= $sc ?>"><?= e(ucfirst(str_replace('_', ' ', $s))) ?></span>
                    </td>
                    <td class="text-surface-500 text-xs"><?= date('d M Y', strtotime($app['created_at'])) ?></td>
                    <td><a href="<?= url('/applications/' . e($app['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
        <p class="empty-title">No applications yet</p>
        <p class="empty-desc">Start exploring scholarships and submit your first application.</p>
        <div class="empty-action">
            <a href="<?= url('/scholarships') ?>" class="btn btn-primary">Browse Scholarships</a>
        </div>
    </div>
    <?php endif; ?>
</div>
