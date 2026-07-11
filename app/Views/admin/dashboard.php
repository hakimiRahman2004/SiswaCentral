<?php
/** @var int $totalUsers */
/** @var int $activeScholarships */
/** @var int $totalApplications */
/** @var float $totalRevenue */
/** @var array|null $recentUsers */
/** @var array|null $recentApplications */
/** @var array|null $registrationTrend */
/** @var array|null $applicationTrend */
/** @var array|null $revenueTrend */
/** @var array|null $topScholarships */
$title = 'Admin Dashboard';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <p class="text-sm font-medium text-primary-600 mb-1">Admin</p>
        <h1 class="page-title">Platform Overview</h1>
        <p class="page-desc">Complete overview of your scholarship platform</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0z"/></svg>
            </div>
            <div class="stat-value"><?= e($totalUsers ?? 0) ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-info-50); color: var(--color-info-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
            </div>
            <div class="stat-value"><?= e($activeScholarships ?? 0) ?></div>
            <div class="stat-label">Active Scholarships</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-success-50); color: var(--color-success-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
            </div>
            <div class="stat-value"><?= e($totalApplications ?? 0) ?></div>
            <div class="stat-label">Total Applications</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-warning-50); color: var(--color-warning-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
            </div>
            <div class="stat-value">RM<?= e(number_format($totalRevenue ?? 0)) ?></div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-decoration" style="background: var(--color-warning-500);"></div>
        </div>
    </div>

    <!-- Recent Activity Grid -->
    <div class="grid gap-6 lg:grid-cols-2 mb-8">
        <div class="card p-5">
            <h2 class="section-title mb-4">Recent Users</h2>
            <div class="divide-y divide-surface-100">
                <?php foreach (array_slice($recentUsers ?? [], 0, 5) as $u): ?>
                <div class="flex items-center gap-3 py-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: <?= $u['role'] === 'admin' ? 'var(--color-danger-100)' : ($u['role'] === 'provider' ? 'var(--color-primary-100)' : 'var(--color-info-100)') ?>; color: <?= $u['role'] === 'admin' ? 'var(--color-danger-600)' : ($u['role'] === 'provider' ? 'var(--color-primary-600)' : 'var(--color-info-600)') ?>;">
                        <?= e(strtoupper(substr($u['name'] ?? 'U', 0, 1))) ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-surface-800 truncate"><?= e($u['name'] ?? '') ?></p>
                        <p class="text-xs text-surface-500"><?= e($u['role']) ?></p>
                    </div>
                    <span class="text-xs text-surface-400"><?= date('d M', strtotime($u['created_at'])) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card p-5">
            <h2 class="section-title mb-4">Recent Applications</h2>
            <div class="divide-y divide-surface-100">
                <?php foreach (array_slice($recentApplications ?? [], 0, 5) as $a): ?>
                <div class="py-2.5">
                    <div class="flex items-center justify-between">
                        <a href="<?= url('/admin/applications/' . $a['id']) ?>" class="text-sm font-medium text-primary-600 hover:text-primary-800 hover:underline truncate">
                            <?= e($a['student_name'] ?? '') ?>
                        </a>
                        <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-full shrink-0 ml-2 <?= match($a['status'] ?? 'submitted') {
                            'approved' => 'bg-green-50 text-green-700',
                            'rejected' => 'bg-red-50 text-red-700',
                            'screening' => 'bg-blue-50 text-blue-700',
                            'shortlisted' => 'bg-purple-50 text-purple-700',
                            'interview' => 'bg-indigo-50 text-indigo-700',
                            default => 'bg-yellow-50 text-yellow-700',
                        } ?>"><?= ucfirst(str_replace('_', ' ', $a['status'] ?? 'submitted')) ?></span>
                    </div>
                    <p class="text-xs text-surface-500 mt-0.5">
                        <?= e($a['scholarship_name'] ?? '') ?>
                        <span class="text-surface-300 mx-1">·</span>
                        <span class="text-surface-400"><?= isset($a['created_at']) ? date('d M Y', strtotime($a['created_at'])) : '-' ?></span>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <?php if (!empty($registrationTrend)): ?>
    <div class="card p-5 mb-6">
        <h2 class="section-title mb-4">User Growth</h2>
        <canvas id="registrationChart" height="80"></canvas>
    </div>
    <?php endif; ?>

    <?php if (!empty($applicationTrend)): ?>
    <div class="card p-5 mb-6">
        <h2 class="section-title mb-4">Applications Over Time</h2>
        <canvas id="applicationChart" height="80"></canvas>
    </div>
    <?php endif; ?>

    <?php if (!empty($revenueTrend)): ?>
    <div class="card p-5 mb-6">
        <h2 class="section-title mb-4">Revenue</h2>
        <canvas id="revenueChart" height="80"></canvas>
    </div>
    <?php endif; ?>

    <!-- Top Scholarships -->
    <?php if (!empty($topScholarships)): ?>
    <div class="card p-5">
        <h2 class="section-title mb-4">Most Applied Scholarships</h2>
        <div class="space-y-3">
            <?php $maxS = max(array_column($topScholarships, 'application_count') ?: [1]) ?>
            <?php foreach ($topScholarships as $s): ?>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-surface-800"><?= e($s['name'] ?? '') ?></span>
                    <span class="text-surface-500"><?= e($s['application_count'] ?? 0) ?></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-bar-fill primary" style="width: <?= ($s['application_count'] ?? 0) / $maxS * 100 ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if (!empty($registrationTrend) || !empty($applicationTrend) || !empty($revenueTrend)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($registrationTrend)): ?>
new Chart(document.getElementById('registrationChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($registrationTrend, 'month')) ?>,
        datasets: [{
            label: 'Registrations',
            data: <?= json_encode(array_column($registrationTrend, 'count')) ?>,
            backgroundColor: 'rgba(99, 102, 241, 0.4)',
            borderColor: '#4f46e5',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
<?php endif; ?>
<?php if (!empty($applicationTrend)): ?>
new Chart(document.getElementById('applicationChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($applicationTrend, 'month')) ?>,
        datasets: [{
            label: 'Applications',
            data: <?= json_encode(array_column($applicationTrend, 'count')) ?>,
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            fill: true,
            tension: 0.3,
            pointRadius: 3,
            pointBackgroundColor: '#4f46e5'
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
<?php endif; ?>
<?php if (!empty($revenueTrend)): ?>
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($revenueTrend, 'month')) ?>,
        datasets: [{
            label: 'Revenue (RM)',
            data: <?= json_encode(array_column($revenueTrend, 'total')) ?>,
            backgroundColor: 'rgba(245, 158, 11, 0.4)',
            borderColor: '#f59e0b',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
<?php endif; ?>
</script>
<?php endif; ?>
