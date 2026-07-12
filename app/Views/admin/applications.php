<?php $title = 'Application Monitoring'; ?>
<div class="animate-fade-in">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-surface-800">Application Monitoring</h1>
            <p class="text-surface-500 text-sm mt-1">Monitor all scholarship applications across the platform</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('/admin/applications/export') ?>" class="btn btn-outline btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid gap-4 sm:grid-cols-4 mb-6">
        <div class="card p-4">
            <p class="text-xs text-surface-500 mb-1">Total Applications</p>
            <p class="text-2xl font-bold text-surface-800"><?= $totalApplications ?></p>
        </div>
        <div class="card p-4">
            <p class="text-xs text-surface-500 mb-1">Submitted</p>
            <p class="text-2xl font-bold text-warning-600"><?= $pendingCount ?></p>
        </div>
        <div class="card p-4">
            <p class="text-xs text-surface-500 mb-1">Approved</p>
            <p class="text-2xl font-bold text-success-600"><?= $approvedCount ?></p>
        </div>
        <div class="card p-4">
            <p class="text-xs text-surface-500 mb-1">Rejected</p>
            <p class="text-2xl font-bold text-danger-600"><?= $rejectedCount ?></p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="card p-4 mb-6">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="input-label">Status</label>
                <select name="status" class="input w-full">
                    <option value="">All Status</option>
                    <option value="submitted" <?= ($_GET['status'] ?? '') === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                    <option value="screening" <?= ($_GET['status'] ?? '') === 'screening' ? 'selected' : '' ?>>Screening</option>
                    <option value="shortlisted" <?= ($_GET['status'] ?? '') === 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                    <option value="interview" <?= ($_GET['status'] ?? '') === 'interview' ? 'selected' : '' ?>>Interview</option>
                    <option value="document_required" <?= ($_GET['status'] ?? '') === 'document_required' ? 'selected' : '' ?>>Document Required</option>
                    <option value="approved" <?= ($_GET['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= ($_GET['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="input-label">Scholarship</label>
                <select name="scholarship_id" class="input w-full">
                    <option value="">All Scholarships</option>
                    <?php foreach ($scholarships as $s): ?>
                    <option value="<?= e($s['id']) ?>" <?= ($_GET['scholarship_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="<?= url('/admin/applications') ?>" class="btn btn-ghost btn-sm">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">App #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Scholarship</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Provider</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Applied</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-surface-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    <?php if (empty($applications)): ?>
                    <tr><td colspan="7" class="px-6 py-12 text-center text-surface-400">No applications found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($applications as $a): ?>
                    <tr class="hover:bg-surface-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-mono text-surface-500"><?= e($a['application_number'] ?? 'APP-' . $a['id']) ?></td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-surface-800 text-sm"><?= e($a['student_name'] ?? '') ?></div>
                            <div class="text-xs text-surface-400"><?= e($a['student_email'] ?? '') ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-surface-600"><?= e($a['scholarship_name'] ?? '') ?></td>
                        <td class="px-6 py-4 text-sm text-surface-600"><?= e($a['provider_name'] ?? '-') ?></td>
                        <td class="px-6 py-4">
                            <?php
                            $statusClasses = [
                                'approved' => 'badge-success', 'rejected' => 'badge-danger', 'screening' => 'badge-warning',
                                'shortlisted' => 'badge-primary', 'interview' => 'badge-info', 'submitted' => 'badge-surface',
                                'document_required' => 'badge-warning',
                            ];
                            $cls = $statusClasses[$a['status'] ?? ''] ?? 'badge-surface';
                            ?>
                            <span class="badge <?= $cls ?>"><?= e(ucfirst(str_replace('_', ' ', $a['status'] ?? ''))) ?></span>
                        </td>
                        <td class="px-6 py-4 text-xs text-surface-500"><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= url('/admin/applications/' . e($a['id'])) ?>" class="btn btn-ghost btn-xs">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
