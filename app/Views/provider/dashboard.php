<?php
/** @var int $unreadNotifications */
/** @var array $usage */
/** @var array $scholarshipStatuses */
/** @var array $appStatuses */
/** @var array|null $recentApplicants */
$title = 'Provider Dashboard';
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <div class="page-header">
        <p class="text-sm font-medium text-primary-600 mb-1">Provider Dashboard</p>
        <h1 class="page-title">Good day, <?= e($user['name'] ?? 'Provider') ?> 👋</h1>
        <p class="page-desc">Overview of your scholarship programs</p>
    </div>

    <!-- Alerts -->
    <?php if (isset($unreadNotifications) && $unreadNotifications > 0): ?>
    <div class="alert alert-info" role="alert">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75v-.7V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
        <span>You have <a href="<?= url('/provider/notifications') ?>" class="font-semibold underline"><?= e($unreadNotifications) ?> unread notification<?= $unreadNotifications > 1 ? 's' : '' ?></a></span>
    </div>
    <?php endif; ?>

    <?php if (isset($usage['is_active']) && !$usage['is_active'] && $usage['subscription']): ?>
        <div class="alert alert-warning" role="alert">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            <span>Your subscription has expired. <a href="<?= url('/provider/subscription') ?>" class="font-semibold underline">Renew now</a> to continue posting scholarships.</span>
        </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
            </div>
            <div class="stat-value"><?= e($scholarshipStatuses['open'] ?? 0) ?></div>
            <div class="stat-label">Open Scholarships</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-info-50); color: var(--color-info-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
            </div>
            <div class="stat-value"><?= e($totalApplicants ?? 0) ?></div>
            <div class="stat-label">Total Applicants</div>
            <div class="stat-decoration" style="background: var(--color-info-500);"></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-success-50); color: var(--color-success-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
            </div>
            <div class="stat-value"><?= e($appStatuses['approved'] ?? 0) ?></div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-warning-50); color: var(--color-warning-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/></svg>
            </div>
            <div class="stat-value"><?= e($appStatuses['screening'] ?? 0) ?></div>
            <div class="stat-label">In Review</div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid gap-6 lg:grid-cols-2 mb-8">
        <!-- Plan Usage -->
        <div class="card p-5">
            <div class="section-header">
                <h2 class="section-title">Plan Usage</h2>
                <a href="<?= url('/provider/subscription') ?>" class="btn btn-soft btn-sm">Manage Plan</a>
            </div>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-surface-600">Scholarships</span>
                        <span class="font-semibold text-surface-700"><?= ($usage['scholarships_used'] ?? 0) ?> / <?= ($usage['scholarships_max'] ?? '∞') ?></span>
                    </div>
                    <div class="progress-bar">
                        <?php $sp = ($usage['scholarships_max'] ?? 0) > 0 ? min(100, round(($usage['scholarships_used'] ?? 0) / $usage['scholarships_max'] * 100)) : 0; ?>
                        <div class="progress-bar-fill <?= $sp >= 80 ? 'warning' : ($sp >= 50 ? 'accent' : 'primary') ?>" style="width:<?= $sp ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-surface-600">Staff Members</span>
                        <span class="font-semibold text-surface-700"><?= ($usage['staff_used'] ?? 0) ?> / <?= ($usage['staff_max'] ?? '∞') ?></span>
                    </div>
                    <div class="progress-bar">
                        <?php $stp = ($usage['staff_max'] ?? 0) > 0 ? min(100, round(($usage['staff_used'] ?? 0) / $usage['staff_max'] * 100)) : 0; ?>
                        <div class="progress-bar-fill <?= $stp >= 80 ? 'warning' : ($stp >= 50 ? 'accent' : 'primary') ?>" style="width:<?= $stp ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card p-5">
            <h2 class="section-title mb-4">Quick Actions</h2>
            <div class="space-y-1">
                <a href="<?= url('/provider/scholarships/create') ?>" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-700">New Scholarship</p>
                        <p class="text-xs text-surface-400">Create a new listing</p>
                    </div>
                </a>
                <a href="<?= url('/provider/applications') ?>" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: var(--color-info-50); color: var(--color-info-600);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-700">Review Applications</p>
                        <p class="text-xs text-surface-400">Screen and shortlist</p>
                    </div>
                </a>
                <a href="<?= url('/provider/staff') ?>" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: var(--color-accent-50); color: var(--color-accent-600);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-700">Manage Staff</p>
                        <p class="text-xs text-surface-400">Add or remove team members</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Status Grid -->
    <div class="grid gap-6 lg:grid-cols-2 mb-8">
        <div class="card p-5">
            <h2 class="section-title mb-4">Scholarship Status</h2>
            <div class="grid grid-cols-4 gap-3">
                <div class="p-4 rounded-xl text-center" style="background: var(--color-success-50);">
                    <p class="text-2xl font-bold font-heading" style="color: var(--color-success-700);"><?= e($scholarshipStatuses['open'] ?? 0) ?></p>
                    <p class="text-xs font-medium mt-1" style="color: var(--color-success-600);">Open</p>
                </div>
                <div class="p-4 rounded-xl text-center" style="background: var(--color-warning-50);">
                    <p class="text-2xl font-bold font-heading" style="color: var(--color-warning-700);"><?= e($scholarshipStatuses['pending'] ?? 0) ?></p>
                    <p class="text-xs font-medium mt-1" style="color: var(--color-warning-600);">Pending</p>
                </div>
                <div class="p-4 rounded-xl text-center" style="background: var(--color-surface-100);">
                    <p class="text-2xl font-bold font-heading" style="color: var(--color-surface-700);"><?= e($scholarshipStatuses['draft'] ?? 0) ?></p>
                    <p class="text-xs font-medium mt-1" style="color: var(--color-surface-600);">Draft</p>
                </div>
                <div class="p-4 rounded-xl text-center" style="background: var(--color-surface-100);">
                    <p class="text-2xl font-bold font-heading" style="color: var(--color-surface-700);"><?= e($scholarshipStatuses['closed'] ?? 0) ?></p>
                    <p class="text-xs font-medium mt-1" style="color: var(--color-surface-600);">Closed</p>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <h2 class="section-title mb-4">Applications by Status</h2>
            <div class="grid grid-cols-3 gap-3">
                <?php $statusColors = ['submitted' => 'info', 'screening' => 'warning', 'shortlisted' => 'warning', 'interview' => 'accent', 'approved' => 'success', 'rejected' => 'danger']; ?>
                <?php foreach ($appStatuses as $k => $v): ?>
                <div class="p-4 rounded-xl text-center" style="background: var(--color-<?= $statusColors[$k] ?? 'surface' ?>-50);">
                    <p class="text-2xl font-bold font-heading" style="color: var(--color-<?= $statusColors[$k] ?? 'surface' ?>-700);"><?= e($v) ?></p>
                    <p class="text-xs font-medium mt-1" style="color: var(--color-<?= $statusColors[$k] ?? 'surface' ?>-600);"><?= e(ucfirst($k)) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Recent Applicants -->
    <?php if (!empty($recentApplicants)): ?>
    <div class="card p-5">
        <div class="section-header">
            <h2 class="section-title">Recent Applicants</h2>
            <a href="<?= url('/provider/applications') ?>" class="text-sm font-medium text-primary-600 hover:text-primary-700">View all</a>
        </div>
        <div class="divide-y divide-surface-100">
            <?php foreach ($recentApplicants as $a): ?>
            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="text-sm font-medium text-surface-800"><?= e($a['student_name'] ?? '') ?></p>
                    <p class="text-xs text-surface-500"><?= e($a['scholarship_name'] ?? '') ?></p>
                </div>
                <?php $asc = match($a['status'] ?? '') {'approved'=>'badge-success','rejected'=>'badge-danger','screening'=>'badge-warning','submitted'=>'badge-info', default=>'badge-surface'}; ?>
                <span class="badge <?= $asc ?>"><?= e(ucfirst($a['status'] ?? '')) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
