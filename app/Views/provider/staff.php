<?php
/** @var array $usage */
/** @var array<int, array<string, mixed>> $staff */
$title = 'Staff Management';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Staff Management</h1>
        <p class="page-desc">Invite and manage team members</p>
    </div>

    <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr);">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772"/></svg>
            </div>
            <div class="stat-value"><?= ($usage['staff_used'] ?? 0) ?> / <?= ($usage['staff_max'] ?? '∞') ?></div>
            <div class="stat-label">Staff Used</div>
        </div>
        <?php if (!($usage['can_invite_staff'] ?? true)): ?>
        <div class="stat-card" style="background: var(--color-warning-50); border-color: var(--color-warning-200);">
            <div class="stat-icon" style="background: var(--color-warning-100); color: var(--color-warning-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
            <div class="stat-value" style="color: var(--color-warning-700);">Limit reached</div>
            <div class="stat-label">Upgrade to add more</div>
            <a href="<?= url('/provider/subscription') ?>" class="btn btn-soft btn-sm mt-2">Upgrade Plan</a>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($usage['can_invite_staff'] ?? true): ?>
    <div class="card p-6 mb-6">
        <h2 class="section-title mb-4">Invite Staff Member</h2>
        <form method="POST" action="<?= url('/provider/staff/invite') ?>">
            <?= csrfField() ?>
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-56">
                    <label class="input-label">Email address</label>
                    <input type="email" name="email" class="input" placeholder="colleague@organization.com" required>
                    <?php if ($err = error('email')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="input-label">Role</label>
                    <select name="role" class="input">
                        <option value="admin">Admin</option>
                        <option value="reviewer">Reviewer</option>
                        <option value="finance">Finance</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Invite
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($staff)): ?>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($staff as $s): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-700);">
                                <?= e(strtoupper(substr($s['name'] ?? 'S', 0, 1))) ?>
                            </div>
                            <span class="font-medium text-surface-800"><?= e($s['name'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="text-surface-500 text-xs"><?= e($s['email'] ?? '') ?></td>
                    <td><span class="badge <?= match($s['role'] ?? '') {'owner'=>'badge-primary','admin'=>'badge-info','reviewer'=>'badge-warning','finance'=>'badge-accent','viewer'=>'badge-surface', default=>'badge-surface'} ?>"><?= e(ucfirst($s['role'] ?? '')) ?></span></td>
                    <td><?php if (($s['role'] ?? '') !== 'owner'): ?>
                        <form method="POST" action="<?= url('/provider/staff/' . e($s['id']) . '//remove') ?>" onsubmit="return confirm('Remove this staff member?')"><?= csrfField() ?><button type="submit" class="btn btn-ghost btn-sm" style="color: var(--color-danger-500);">Remove</button></form>
                    <?php endif; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
