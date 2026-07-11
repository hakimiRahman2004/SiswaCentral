<?php
/** @var array<int, array<string, mixed>> $saved */
$title = 'Saved Scholarships';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Saved Scholarships</h1>
        <p class="page-desc">Scholarships you've bookmarked for later</p>
    </div>

    <?php if (!empty($saved)): ?>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($saved as $s):
            $scholarshipId = $s['scholarship_id'] ?? $s['id'];
        ?>
        <div class="card-hover p-5 relative group">
            <!-- Top: icon + info + unsave -->
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg font-bold shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-700);">
                        <?= e(substr($s['name'] ?? 'S', 0, 1)) ?>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-surface-800 text-sm leading-snug truncate"><?= e($s['name']) ?></h3>
                        <p class="text-xs text-surface-500 mt-0.5 truncate"><?= e($s['company_name'] ?? $s['provider_name'] ?? '') ?></p>
                    </div>
                </div>
                <form method="POST" action="<?= url('/scholarships/' . e($scholarshipId) . '/unsave') ?>">
                    <?= csrfField() ?>
                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 hover:text-red-500 transition-colors" aria-label="Unsave" onclick="return confirm('Remove from saved?')">
                        <svg class="w-4 h-4" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" fill="currentColor"/></svg>
                    </button>
                </form>
            </div>

            <!-- Badges -->
            <div class="flex flex-wrap gap-1.5">
                <?php if ($s['amount']): ?>
                    <span class="badge badge-primary">RM<?= e(number_format($s['amount'])) ?></span>
                <?php endif; ?>
                <?php if ($s['min_cgpa']): ?>
                    <span class="badge badge-surface">CGPA <?= e($s['min_cgpa']) ?>+</span>
                <?php endif; ?>
                <?php if ($s['deadline'] ?? ''): ?>
                    <span class="badge badge-outline">Due <?= date('d M', strtotime($s['deadline'])) ?></span>
                <?php endif; ?>
            </div>

            <!-- Saved date -->
            <p class="text-xs text-surface-400 mt-3">
                Saved <?= isset($s['created_at']) ? date('d M Y', strtotime($s['created_at'])) : '' ?>
            </p>

            <!-- Actions -->
            <div class="flex gap-2 mt-4 pt-3 border-t border-surface-100">
                <a href="<?= url('/scholarships/' . e($scholarshipId)) ?>" class="btn btn-ghost btn-sm flex-1">View Details</a>
                <?php if (($s['status'] ?? '') === 'open'): ?>
                <a href="<?= url('/scholarships/' . e($scholarshipId) . '/apply') ?>" class="btn btn-primary btn-sm flex-1">Apply Now</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0z"/></svg>
        <p class="empty-title">No saved scholarships</p>
        <p class="empty-desc">Start browsing and bookmark scholarships that interest you.</p>
        <div class="empty-action">
            <a href="<?= url('/scholarships') ?>" class="btn btn-primary">Browse Scholarships</a>
        </div>
    </div>
    <?php endif; ?>
</div>