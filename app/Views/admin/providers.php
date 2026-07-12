<?php
/** @var int $totalProviders */
/** @var int $verifiedCount */
/** @var int $unverifiedCount */
/** @var string $page */
/** @var array<int, array<string, mixed>> $providers */
$page ??= 'all';
$headings = ['all' => ['Provider Management', 'Manage provider accounts and verification'], 'verified' => ['Verified Providers', 'Providers with verified accounts'], 'unverified' => ['Unverified Providers', 'Providers pending verification']];
[$title, $desc] = $headings[$page] ?? $headings['all'];
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title"><?= e($title) ?></h1>
        <p class="page-desc"><?= e($desc) ?></p>
    </div>

    <div class="flex items-center gap-2 mb-6">
        <a href="<?= url('/admin/providers') ?>" class="btn btn-sm <?= $page === 'all' ? 'btn-primary' : 'btn-ghost' ?>">All</a>
        <a href="<?= url('/admin/providers/verified') ?>" class="btn btn-sm <?= $page === 'verified' ? 'btn-primary' : 'btn-ghost' ?>">Verified (<?= e($verifiedCount ?? 0) ?>)</a>
        <a href="<?= url('/admin/providers/unverified') ?>" class="btn btn-sm <?= $page === 'unverified' ? 'btn-primary' : 'btn-ghost' ?>">Unverified (<?= e($unverifiedCount ?? 0) ?>)</a>
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
                                <?= e(strtoupper(substr($p['company_name'] ?? ($p['owner_name'] ?? 'P'), 0, 1))) ?>
                            </div>
                            <span class="font-medium text-surface-800"><?= e($p['company_name'] ?? ($p['owner_name'] ?? '')) ?></span>
                        </div>
                    </td>
                    <td class="text-xs text-surface-500"><?= e($p['owner_email'] ?? '') ?></td>
                    <td><span class="badge <?= ($p['status']??'')==='active'?'badge-success':'badge-danger' ?>"><?= e($p['status']??'active') ?></span></td>
                    <td><?= ($p['is_verified']??false) ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-warning">No</span>' ?></td>
                    <td class="text-xs text-surface-500"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                    <td class="flex gap-1">
                        <a href="<?= url('/admin/providers/' . e($p['id'])) ?>" class="btn btn-ghost btn-sm">View</a>
                        <?php if (!($p['is_verified'] ?? false)): ?>
                        <form method="POST" action="<?= url('/admin/providers/' . e($p['id']) . '/verify') ?>" style="display:inline" onsubmit="return confirm('Verify this provider?')"><?= csrfField() ?><button type="submit" class="btn btn-sm" style="background: var(--color-success-500); color: white;">Verify</button></form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
