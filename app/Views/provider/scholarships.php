<?php
/** @var array<int, array<string, mixed>> $scholarships */
$title = 'My Scholarships';
?>
<div class="animate-fade-in">
    <div class="page-header flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="page-title">My Scholarships</h1>
            <p class="page-desc">Manage all your scholarship listings</p>
        </div>
        <a href="<?= url('/provider/scholarships/create') ?>" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Create Scholarship
        </a>
    </div>

    <?php if (!empty($scholarships)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Name</th><th>Amount</th><th>Status</th><th>Deadline</th><th>Applicants</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($scholarships as $s): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-700);">
                                <?= e(substr($s['name'] ?? 'S', 0, 1)) ?>
                            </div>
                            <span class="font-medium text-surface-800"><?= e($s['name'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="font-medium text-surface-700"><?= $s['amount'] ? 'RM'.e(number_format($s['amount'])) : '-' ?></td>
                    <td>
                        <span class="badge <?= match($s['status'] ?? '') {'open'=>'badge-success','pending'=>'badge-warning','draft'=>'badge-surface','closed'=>'badge-surface', default=>'badge-surface'} ?>">
                            <?= e(ucfirst(str_replace('_', ' ', $s['status'] ?? ''))) ?>
                        </span>
                    </td>
                    <td class="text-xs text-surface-500"><?= date('d M Y', strtotime($s['deadline'])) ?></td>
                    <td class="font-medium"><?= e($s['applicant_count'] ?? 0) ?></td>
                    <td>
                        <div class="flex gap-1">
                            <form method="POST" action="<?= url('/provider/scholarships/' . e($s['id']) . '/toggle') ?>" style="display:inline">
                                <?= csrfField() ?>
                                <?php if (($s['status'] ?? '') !== 'pending'): ?>
                                <button type="submit" class="btn btn-ghost btn-sm"><?= ($s['status'] ?? '') === 'open' ? 'Close' : 'Open' ?></button>
                                <?php endif; ?>
                            </form>
                            <a href="<?= url('/provider/scholarships/' . e($s['id']) . '/edit') ?>" class="btn btn-ghost btn-sm">Edit</a>
                            <a href="<?= url('/provider/scholarships/' . e($s['id']) . '/applicants') ?>" class="btn btn-ghost btn-sm">Applicants</a>
                            <form method="POST" action="<?= url('/provider/scholarships/' . e($s['id']) . '/delete') ?>" style="display:inline" onsubmit="return confirm('Delete this scholarship?')">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--color-danger-500);">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
        <p class="empty-title">No scholarships yet</p>
        <p class="empty-desc">Create your first scholarship listing to start receiving applications.</p>
        <div class="empty-action">
            <a href="<?= url('/provider/scholarships/create') ?>" class="btn btn-primary">Create Your First Scholarship</a>
        </div>
    </div>
    <?php endif; ?>
</div>
