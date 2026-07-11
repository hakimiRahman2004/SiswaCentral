<?php
/** @var int $totalUsers */
/** @var int $totalStudents */
/** @var int $totalProviders */
/** @var int $bannedUsers */
/** @var array<int, array<string, mixed>> $users */
$title = 'User Management';
$students = array_filter($users ?? [], fn($u) => ($u['role'] ?? '') === 'student');
$providers = array_filter($users ?? [], fn($u) => ($u['role'] ?? '') === 'provider');
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">User Management</h1>
        <p class="page-desc">Manage all platform users</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0z"/></svg>
            </div>
            <div class="stat-value"><?= e($totalUsers ?? 0) ?></div>
            <div class="stat-label">All Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-info-50); color: var(--color-info-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342"/></svg>
            </div>
            <div class="stat-value"><?= e($totalStudents ?? 0) ?></div>
            <div class="stat-label">Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-accent-50); color: var(--color-accent-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
            </div>
            <div class="stat-value"><?= e($totalProviders ?? 0) ?></div>
            <div class="stat-label">Providers</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-danger-50); color: var(--color-danger-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div class="stat-value" style="color: var(--color-danger-600);"><?= e($bannedUsers ?? 0) ?></div>
            <div class="stat-label">Banned</div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card p-5 mb-8">
        <h2 class="section-title mb-4">Students (<?= count($students) ?>)</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Joined</th><th></th></tr></thead>
                <tbody>
                    <?php if (empty($students)): ?>
                    <tr><td colspan="5" class="text-center text-surface-400 py-8">No students found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($students as $u): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-info-100); color: var(--color-info-600);">
                                    <?= e(strtoupper(substr($u['name'] ?? 'U', 0, 1))) ?>
                                </div>
                                <span class="font-medium text-surface-800"><?= e($u['name'] ?? '') ?></span>
                            </div>
                        </td>
                        <td class="text-xs text-surface-500"><?= e($u['email'] ?? '') ?></td>
                        <td><span class="badge <?= ($u['status']??'') === 'active' ? 'badge-success' : 'badge-danger' ?>"><?= e($u['status'] ?? 'active') ?></span></td>
                        <td class="text-xs text-surface-500"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                        <td><a href="<?= url('/admin/users/' . e($u['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Providers Table -->
    <div class="card p-5">
        <h2 class="section-title mb-4">Providers (<?= count($providers) ?>)</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Company</th><th>Email</th><th>Status</th><th>Joined</th><th></th></tr></thead>
                <tbody>
                    <?php if (empty($providers)): ?>
                    <tr><td colspan="5" class="text-center text-surface-400 py-8">No providers found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($providers as $u): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-primary-100); color: var(--color-primary-600);">
                                    <?= e(strtoupper(substr($u['name'] ?? 'U', 0, 1))) ?>
                                </div>
                                <span class="font-medium text-surface-800"><?= e($u['name'] ?? '') ?></span>
                            </div>
                        </td>
                        <td class="text-xs text-surface-500"><?= e($u['email'] ?? '') ?></td>
                        <td><span class="badge <?= ($u['status']??'') === 'active' ? 'badge-success' : 'badge-danger' ?>"><?= e($u['status'] ?? 'active') ?></span></td>
                        <td class="text-xs text-surface-500"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                        <td><a href="<?= url('/admin/users/' . e($u['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
