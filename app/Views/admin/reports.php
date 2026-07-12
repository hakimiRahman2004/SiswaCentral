<?php $title = 'Reports'; ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Reports</h1>
        <p class="page-desc">Platform-wide statistics and insights</p>
    </div>

    <!-- Application Stats -->
    <div class="card p-6 mb-6">
        <h2 class="text-lg font-semibold text-surface-800 mb-4">Application Overview</h2>
        <div class="grid gap-4 sm:grid-cols-4">
            <div class="text-center p-4 rounded-xl bg-surface-50 border border-surface-100">
                <div class="text-3xl font-bold font-heading text-surface-900"><?= $totalApplications ?></div>
                <div class="text-xs text-surface-500 mt-1">Total Applications</div>
            </div>
            <div class="text-center p-4 rounded-xl bg-success-50 border border-success-200">
                <div class="text-3xl font-bold font-heading text-success-600"><?= $approvedCount ?></div>
                <div class="text-xs text-surface-500 mt-1">Approved</div>
            </div>
            <div class="text-center p-4 rounded-xl bg-danger-50 border border-danger-200">
                <div class="text-3xl font-bold font-heading text-danger-600"><?= $rejectedCount ?></div>
                <div class="text-xs text-surface-500 mt-1">Rejected</div>
            </div>
            <div class="text-center p-4 rounded-xl bg-warning-50 border border-warning-200">
                <div class="text-3xl font-bold font-heading text-warning-600"><?= $pendingCount ?></div>
                <div class="text-xs text-surface-500 mt-1">Pending Review</div>
            </div>
        </div>
    </div>

    <!-- Approval Rate -->
    <div class="card p-6 mb-6">
        <h2 class="text-lg font-semibold text-surface-800 mb-4">Approval Rate</h2>
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="h-4 bg-surface-100 rounded-full overflow-hidden">
                    <div class="h-full bg-success-500 rounded-full transition-all" style="width: <?= $approvalRate ?>%"></div>
                </div>
            </div>
            <span class="text-2xl font-bold text-surface-800"><?= $approvalRate ?>%</span>
        </div>
        <p class="text-sm text-surface-500 mt-2"><?= $approvedCount ?> of <?= $totalApplications ?> applications approved</p>
    </div>

    <!-- Scholarship Stats -->
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-surface-800 mb-4">Scholarship Overview</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="p-4 rounded-xl bg-surface-50 border border-surface-100">
                <div class="text-2xl font-bold font-heading text-surface-900"><?= $totalScholarships ?></div>
                <div class="text-xs text-surface-500 mt-1">Total Scholarships</div>
            </div>
            <div class="p-4 rounded-xl bg-primary-50 border border-primary-200">
                <div class="text-2xl font-bold font-heading text-primary-600"><?= $activeScholarships ?></div>
                <div class="text-xs text-surface-500 mt-1">Active / Published</div>
            </div>
        </div>
    </div>
</div>
