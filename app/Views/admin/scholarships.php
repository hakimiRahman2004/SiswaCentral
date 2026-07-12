<?php
/** @var int $totalScholarships */
/** @var int $activeScholarships */
/** @var int $pendingCount */
/** @var int $draftScholarships */
/** @var int $closedScholarships */
/** @var array<int, array<string, mixed>> $scholarships */
$title = 'Scholarship Management';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Scholarship Management</h1>
        <p class="page-desc">All scholarships across providers</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
            </div>
            <div class="stat-value"><?= e($totalScholarships ?? 0) ?></div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-success-50); color: var(--color-success-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
            </div>
            <div class="stat-value" style="color: var(--color-success-600);"><?= e($activeScholarships ?? 0) ?></div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-warning-50); color: var(--color-warning-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
            </div>
            <div class="stat-value" style="color: var(--color-warning-600);"><?= e($draftScholarships ?? 0) ?></div>
            <div class="stat-label">Draft</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-warning-50); color: var(--color-warning-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div class="stat-value" style="color: var(--color-warning-600);"><?= e($pendingCount ?? 0) ?></div>
            <div class="stat-label">Pending Review</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--color-danger-50); color: var(--color-danger-600);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div class="stat-value" style="color: var(--color-danger-600);"><?= e($closedScholarships ?? 0) ?></div>
            <div class="stat-label">Closed</div>
        </div>
    </div>

    <form method="GET" action="<?= url('/admin/scholarships') ?>" class="filter-bar">
        <div class="search-bar">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/></svg>
            <input type="text" name="search" value="<?= e($_GET['search']??'') ?>" class="input search-input" placeholder="Search scholarships...">
        </div>
        <select name="status" class="input" style="width:auto" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="open" <?= ($_GET['status']??'')==='open'?'selected':''?>>Open</option>
            <option value="pending" <?= ($_GET['status']??'')==='pending'?'selected':''?>>Pending</option>
            <option value="draft" <?= ($_GET['status']??'')==='draft'?'selected':''?>>Draft</option>
            <option value="closed" <?= ($_GET['status']??'')==='closed'?'selected':''?>>Closed</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Title</th><th>Provider</th><th>Status</th><th>Applications</th><th>Deadline</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($scholarships as $s): ?>
                <tr>
                    <td class="font-medium text-surface-800"><?= e($s['name']??'') ?></td>
                    <td class="text-sm text-surface-600"><?= e($s['provider_name']??'') ?></td>
                    <td><span class="badge <?= match($s['status']??''){'open'=>'badge-success','pending'=>'badge-warning','draft'=>'badge-warning','closed'=>'badge-danger',default=>'badge-surface'} ?>"><?= e($s['status']??'') ?></span></td>
                    <td class="font-medium"><?= e($s['application_count']??0) ?></td>
                    <td class="text-xs text-surface-500"><?= e($s['deadline']??'') ?></td>
                    <td><a href="<?= url('/admin/scholarships/' . e($s['id'])) ?>" class="btn btn-ghost btn-sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
