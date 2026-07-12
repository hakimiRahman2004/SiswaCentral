<?php
/** @var bool $showMatching */
/** @var int $openScholarships */
/** @var int $approvedCount */
/** @var int $savedCount */
/** @var int $totalApplications */
/** @var int $profilePct */
/** @var array|null $usage */
/** @var array|null $subscription */
/** @var array|null $recommended */
/** @var array|null $recentApps */
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <!-- Profile Completion Banner -->
    <?php if (isset($profilePct) && $profilePct < 100): ?>
        <div class="alert alert-warning" role="alert">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>
                Complete your <a href="<?= url('/profile') ?>" class="font-semibold underline">profile</a> to unlock scholarship matching
                <span class="ml-2 inline-flex items-center gap-1.5">
                    <span class="text-sm font-medium"><?= $profilePct ?>%</span>
                    <span class="progress-bar inline-block w-16">
                        <span class="progress-bar-fill warning block" style="width: <?= $profilePct ?>%"></span>
                    </span>
                </span>
            </span>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header">
        <p class="text-sm font-medium text-primary-600 mb-1">Welcome back</p>
        <h1 class="page-title">Hello, <?= e(explode(' ', $user['name'] ?? 'Student')[0]) ?> 👋</h1>
        <p class="page-desc">Here's an overview of your scholarship activity</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/></svg>
            </div>
            <div class="stat-value"><?= e($openScholarships ?? 0) ?></div>
            <div class="stat-label">Open Scholarships</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-info-50); color: var(--color-info-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
            </div>
            <div class="stat-value"><?= e($totalApplications ?? 0) ?></div>
            <div class="stat-label">Total Applications</div>
            <div class="stat-decoration" style="background: var(--color-info-500);"></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-success-50); color: var(--color-success-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
            </div>
            <div class="stat-value"><?= e($approvedCount ?? 0) ?></div>
            <div class="stat-label">Approved</div>
            <?php if (($approvedCount ?? 0) > 0): ?>
            <span class="stat-change up">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 15.75 7.5-7.5 7.5 7.5"/></svg>
                Active
            </span>
            <?php endif; ?>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-accent-50); color: var(--color-accent-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0z"/></svg>
            </div>
            <div class="stat-value"><?= e($savedCount ?? 0) ?></div>
            <div class="stat-label">Saved Scholarships</div>
            <div class="stat-decoration" style="background: var(--color-accent-500);"></div>
        </div>
    </div>

    <!-- Main Grid: Usage + Quick Actions + Recent -->
    <div class="grid gap-6 lg:grid-cols-3 mb-8">
        <!-- Usage -->
        <div class="card p-5 lg:col-span-2">
            <div class="section-header">
                <h2 class="section-title">Usage Overview</h2>
                <?php if (isset($subscription) && ($subscription['plan_name'] ?? '') !== 'Premium'): ?>
                    <a href="<?= url('/subscription') ?>" class="btn btn-soft btn-sm">Upgrade</a>
                <?php endif; ?>
            </div>
            <?php $usage = $usage ?? []; ?>
            <div class="space-y-5">
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-surface-600">Monthly Applications</span>
                        <span class="font-semibold text-surface-700"><?= ($usage['applications']['used'] ?? 0) ?> / <?= ($usage['applications']['max'] ?? '∞') ?></span>
                    </div>
                    <div class="progress-bar">
                        <?php $appPct = ($usage['applications']['max'] ?? 0) > 0 ? min(100, round(($usage['applications']['used'] ?? 0) / $usage['applications']['max'] * 100)) : 0; ?>
                        <div class="progress-bar-fill <?= $appPct >= 80 ? 'warning' : ($appPct >= 50 ? 'accent' : 'primary') ?>" style="width: <?= $appPct ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-surface-600">Documents Stored</span>
                        <span class="font-semibold text-surface-700"><?= ($usage['documents']['used'] ?? 0) ?> / <?= ($usage['documents']['max'] ?? '∞') ?></span>
                    </div>
                    <div class="progress-bar">
                        <?php $docPct = ($usage['documents']['max'] ?? 0) > 0 ? min(100, round(($usage['documents']['used'] ?? 0) / $usage['documents']['max'] * 100)) : 0; ?>
                        <div class="progress-bar-fill <?= $docPct >= 80 ? 'warning' : ($docPct >= 50 ? 'accent' : 'primary') ?>" style="width: <?= $docPct ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card p-5">
            <h2 class="section-title mb-4">Quick Actions</h2>
            <div class="space-y-1">
                <a href="<?= url('/scholarships') ?>" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-700">Browse Scholarships</p>
                        <p class="text-xs text-surface-400">Find matching opportunities</p>
                    </div>
                </a>
                <a href="<?= url('/applications') ?>" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: var(--color-info-50); color: var(--color-info-600);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-700">My Applications</p>
                        <p class="text-xs text-surface-400">Track your progress</p>
                    </div>
                </a>
                <a href="<?= url('/profile') ?>" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: var(--color-accent-50); color: var(--color-accent-600);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-700">Edit Profile</p>
                        <p class="text-xs text-surface-400">Update your details</p>
                    </div>
                </a>
                <a href="<?= url('/documents') ?>" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: var(--color-warning-50); color: var(--color-warning-600);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-700">Upload Documents</p>
                        <p class="text-xs text-surface-400">Store your files securely</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recommended Scholarships -->
    <?php if (!empty($recommended)): ?>
        <div class="section-header mt-6">
            <h2 class="section-title">Recommended for You</h2>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            <?php foreach ($recommended as $s): ?>
                <a href="<?= url('/scholarships/' . e($s['id'])) ?>" class="card-hover p-5 block group">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-semibold text-surface-800 text-sm group-hover:text-primary-600 transition-colors"><?= e($s['name']) ?></h3>
                        <?php if ($showMatching && isset($s['match_score'])): ?>
                            <span class="badge <?= $s['match_score'] >= 70 ? 'badge-success' : ($s['match_score'] >= 40 ? 'badge-warning' : 'badge-surface') ?> text-xs"><?= e($s['match_score']) ?>%</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-surface-500"><?= e($s['provider_name'] ?? '') ?></p>
                    <div class="flex items-center gap-4 mt-3 pt-3 border-t border-surface-100 text-xs">
                        <?php if ($s['min_cgpa']): ?>
                            <span class="text-surface-500">CGPA <?= e($s['min_cgpa']) ?>+</span>
                        <?php endif; ?>
                        <?php if ($s['amount']): ?>
                            <span class="font-semibold text-surface-700">RM<?= e(number_format($s['amount'])) ?></span>
                        <?php endif; ?>
                        <?php if ($s['deadline'] ?? ''): ?>
                            <span class="ml-auto text-surface-400"><?= date('d M', strtotime($s['deadline'])) ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Recent Applications -->
    <?php if (!empty($recentApps)): ?>
        <div class="card p-5">
            <div class="section-header">
                <h2 class="section-title">Recent Applications</h2>
                <a href="<?= url('/applications') ?>" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">View all</a>
            </div>
            <div class="divide-y divide-surface-100">
                <?php foreach ($recentApps as $app): ?>
                    <a href="<?= url('/applications/' . e($app['id'])) ?>"
                       class="flex items-center justify-between py-3.5 -mx-1 px-3 rounded-lg hover:bg-surface-50 transition-colors gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-surface-800 truncate"><?= e($app['scholarship_name'] ?? '') ?></p>
                                <p class="text-xs text-surface-400 mt-0.5"><?= date('d M Y', strtotime($app['created_at'])) ?></p>
                            </div>
                        </div>
                        <?php
                        $status = $app['status'] ?? '';
                        $sc = match($status) {
                            'approved' => 'badge-success', 'rejected' => 'badge-danger',
                            'screening','shortlisted','interview' => 'badge-warning',
                            'document_required' => 'badge-info', 'submitted' => 'badge-info',
                            'withdrawn','expired' => 'badge-surface', 'pending' => 'badge-warning',
                            default => 'badge-surface'
                        };
                        ?>
                        <span class="badge <?= $sc ?> shrink-0"><?= e(ucfirst(str_replace('_', ' ', $status))) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
