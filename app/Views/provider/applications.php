<?php
/** @var array<int, array<string, mixed>> $applications */
$statusFilter = $_GET['status'] ?? '';
$title = 'All Applications';
?>
<div class="animate-fade-in">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-surface-800">All Applications</h1>
            <p class="text-surface-500 text-sm mt-1">Review and manage applications across all your scholarships</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('/provider/applications/export' . ($statusFilter ? "?status={$statusFilter}" : '')) ?>" class="btn btn-outline btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex items-center gap-1 mb-6 bg-surface-100 p-1 rounded-xl w-fit">
        <?php
        $filters = ['' => 'All', 'submitted' => 'Submitted', 'screening' => 'Screening', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview', 'document_required' => 'Docs Required', 'approved' => 'Approved', 'rejected' => 'Rejected'];
        foreach ($filters as $val => $label):
        ?>
        <a href="<?= url('/provider/applications' . ($val ? "?status={$val}" : '')) ?>"
           class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $statusFilter === $val ? 'bg-white shadow-sm text-surface-800' : 'text-surface-500 hover:text-surface-700' ?>">
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($applications)): ?>
    <div class="card">
        <!-- Bulk Actions Bar -->
        <div id="bulkBar" class="hidden px-6 py-3 bg-surface-50 border-b border-surface-200">
        <div class="flex items-center gap-3">
            <span id="selectedCount" class="text-sm text-surface-600"></span>
            <div class="flex items-center gap-2 ml-auto">
                <form method="POST" action="<?= url('/provider/applications/bulk') ?>" id="bulkForm" class="flex items-center gap-2">
                    <?= csrfField() ?>
                    <input type="hidden" name="bulk_action" id="bulkActionInput" value="">
                    <input type="hidden" name="application_ids[]" id="bulkIdsInput" value="">
                    <button type="button" onclick="setBulkAction('shortlist')" class="btn btn-primary btn-sm">Shortlist Selected</button>
                    <button type="button" onclick="setBulkAction('reject')" class="btn btn-danger btn-sm">Reject Selected</button>
                </form>
            </div>
        </div></div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-200">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-surface-300" onchange="toggleSelectAll(this)">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Application</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Scholarship</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">CGPA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Applied</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-surface-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    <?php foreach ($applications as $a):
                        $details = json_decode($a['details'] ?? '{}', true) ?: [];
                    ?>
                    <tr class="hover:bg-surface-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="app-checkbox rounded border-surface-300" value="<?= e($a['id']) ?>" onchange="updateBulkBar()">
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-surface-500"><?= e($a['application_number'] ?? 'APP-' . $a['id']) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-info-50); color: var(--color-info-600);">
                                    <?= e(strtoupper(substr($a['student_name'] ?? 'U', 0, 1))) ?>
                                </div>
                                <div>
                                    <div class="font-medium text-surface-800 text-sm"><?= e($a['student_name'] ?? '') ?></div>
                                    <div class="text-xs text-surface-400"><?= e($a['student_email'] ?? '') ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-surface-600"><?= e($a['scholarship_name'] ?? '') ?></td>
                        <td class="px-6 py-4 text-sm font-medium text-surface-700"><?= e($details['cgpa'] ?? 'N/A') ?></td>
                        <td class="px-6 py-4">
                            <?php
                            $statusClasses = [
                                'approved' => 'badge-success', 'rejected' => 'badge-danger', 'screening' => 'badge-warning',
                                'shortlisted' => 'badge-primary', 'interview' => 'badge-info', 'submitted' => 'badge-surface',
                                'document_required' => 'badge-warning', 'withdrawn' => 'badge-surface', 'expired' => 'badge-surface',
                            ];
                            $cls = $statusClasses[$a['status'] ?? ''] ?? 'badge-surface';
                            ?>
                            <span class="badge <?= $cls ?>"><?= e(ucfirst(str_replace('_', ' ', $a['status'] ?? ''))) ?></span>
                        </td>
                        <td class="px-6 py-4 text-xs text-surface-500"><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= url('/provider/applications/' . e($a['id'])) ?>" class="btn btn-ghost btn-xs">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (count($applications) > 20): ?>
        <div class="px-6 py-4 border-t border-surface-200 text-sm text-surface-500">
            Showing <?= count($applications) ?> applications
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
        <p class="empty-title">No applications received</p>
        <p class="empty-desc">Applications will appear here once students start applying to your scholarships.</p>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleSelectAll(el) {
    document.querySelectorAll('.app-checkbox').forEach(cb => cb.checked = el.checked);
    updateBulkBar();
}

function updateBulkBar() {
    const checked = document.querySelectorAll('.app-checkbox:checked');
    const bar = document.getElementById('bulkBar');
    const countEl = document.getElementById('selectedCount');
    if (checked.length > 0) {
        bar.classList.remove('hidden');
        countEl.textContent = checked.length + ' application' + (checked.length > 1 ? 's' : '') + ' selected';
    } else {
        bar.classList.add('hidden');
    }
}

function setBulkAction(action) {
    const checked = document.querySelectorAll('.app-checkbox:checked');
    const ids = Array.from(checked).map(cb => cb.value);
    document.getElementById('bulkActionInput').value = action;
    document.getElementById('bulkIdsInput').value = ids.join(',');
    // Convert comma-separated to individual inputs
    const form = document.getElementById('bulkForm');
    const existing = form.querySelectorAll('input[name="application_ids[]"]');
    existing.forEach(el => el.remove());
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'application_ids[]';
        input.value = id;
        form.appendChild(input);
    });
    document.getElementById('bulkIdsInput').remove();
    if (confirm(action === 'reject' ? 'Reject selected applications?' : 'Shortlist selected applications?')) {
        form.submit();
    }
}
</script>
