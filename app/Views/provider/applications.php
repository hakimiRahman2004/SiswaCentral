<?php
/** @var array<int, array<string, mixed>> $applications */
$title = 'Applications';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">All Applications</h1>
        <p class="page-desc">Review applications across all your scholarships</p>
    </div>

    <?php if (!empty($applications)): ?>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Student</th><th>Scholarship</th><th>Status</th><th>Applied</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($applications as $a): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-info-50); color: var(--color-info-600);">
                                <?= e(strtoupper(substr($a['student_name'] ?? 'U', 0, 1))) ?>
                            </div>
                            <span class="font-medium text-surface-800"><?= e($a['student_name'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="text-surface-500"><?= e($a['scholarship_name'] ?? '') ?></td>
                    <td><span class="badge <?= match($a['status'] ?? '') {'approved'=>'badge-success','rejected'=>'badge-danger','screening'=>'badge-warning','shortlisted'=>'badge-primary','interview'=>'badge-info','submitted'=>'badge-surface', default=>'badge-surface'} ?>"><?= e(ucfirst($a['status'] ?? '')) ?></span></td>
                    <td class="text-xs text-surface-500"><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                    <td><a href="<?= url('/provider/applications/' . e($a['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
        <p class="empty-title">No applications received</p>
        <p class="empty-desc">Applications from students will appear here once they start applying.</p>
    </div>
    <?php endif; ?>
</div>
