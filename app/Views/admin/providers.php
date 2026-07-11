<?php
/** @var int $totalProviders */
/** @var int $verifiedProviders */
/** @var int $pendingVerification */
/** @var array<int, array<string, mixed>> $providers */
$title = 'Provider Management';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Provider Management</h1>
        <p class="page-desc">Manage provider accounts and verification</p>
    </div>

    <div style="display: grid; gap: 1rem; grid-template-columns: repeat(3, 1fr); margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
            </div>
            <div class="stat-value"><?= e($totalProviders ?? 0) ?></div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-success-50); color: var(--color-success-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
            </div>
            <div class="stat-value"><?= e($verifiedProviders ?? 0) ?></div>
            <div class="stat-label">Verified</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-warning-50); color: var(--color-warning-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
            <div class="stat-value" style="color: var(--color-warning-600);"><?= e($pendingVerification ?? 0) ?></div>
            <div class="stat-label">Pending</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Verified</th><th>Joined</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($providers as $p): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-700);">
                                <?= e(strtoupper(substr($p['name'] ?? 'P', 0, 1))) ?>
                            </div>
                            <span class="font-medium text-surface-800"><?= e($p['name'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="text-xs text-surface-500"><?= e($p['email'] ?? '') ?></td>
                    <td><span class="badge <?= ($p['status']??'')==='active'?'badge-success':'badge-danger' ?>"><?= e($p['status']??'active') ?></span></td>
                    <td><?= ($p['verified']??false) ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-warning">No</span>' ?></td>
                    <td class="text-xs text-surface-500"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                    <td><a href="<?= url('/admin/users/' . e($p['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
