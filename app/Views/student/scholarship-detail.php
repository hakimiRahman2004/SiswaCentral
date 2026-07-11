<?php
/** @var array<string, mixed> $scholarship */
/** @var array|null $profile */
/** @var array|null $documents */
/** @var bool $isSaved */
/** @var bool $hasApplied */
/** @var bool $showMatching */
/** @var int $matchScore */
/** @var string $matchLabel */
$title = e($scholarship['name'] ?? 'Scholarship Detail');
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <a href="<?= url('/scholarships') ?>" class="inline-flex items-center gap-1.5 text-sm text-surface-500 hover:text-primary-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
        Back to Scholarships
    </a>

    <!-- Header -->
    <div class="card p-6 mb-6">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-bold shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-700);">
                    <?= e(substr($scholarship['name'] ?? 'S', 0, 1)) ?>
                </div>
                <div>
                    <h1 class="text-2xl font-bold font-heading text-surface-900 tracking-tight"><?= e($scholarship['name']) ?></h1>
                    <p class="text-sm text-surface-500 mt-1"><?= e($scholarship['provider_name'] ?? '') ?></p>
                </div>
            </div>
            <form method="POST" action="<?= url('/scholarships/' . e($scholarship['id']) . '/' . ($isSaved ? 'unsave' : 'save')) ?>">
                <?= csrfField() ?>
                <button type="submit" class="btn <?= $isSaved ? 'btn-ghost' : 'btn-soft' ?> btn-sm">
                    <svg class="w-4 h-4 <?= $isSaved ? 'fill-current text-accent-500' : '' ?>" fill="<?= $isSaved ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0z"/></svg>
                    <?= $isSaved ? 'Saved' : 'Save' ?>
                </button>
            </form>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <?php if ($scholarship['amount']): ?>
            <div class="stat-card p-4">
                <div class="stat-label text-xs">Award Amount</div>
                <div class="text-xl font-bold font-heading text-surface-900 mt-1">RM<?= e(number_format($scholarship['amount'])) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($scholarship['min_cgpa']): ?>
            <div class="stat-card p-4">
                <div class="stat-label text-xs">Minimum CGPA</div>
                <div class="text-xl font-bold font-heading text-surface-900 mt-1"><?= e($scholarship['min_cgpa']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($scholarship['max_income']): ?>
            <div class="stat-card p-4">
                <div class="stat-label text-xs">Max Income</div>
                <div class="text-xl font-bold font-heading text-surface-900 mt-1">RM<?= e(number_format($scholarship['max_income'])) ?></div>
            </div>
            <?php endif; ?>
            <div class="stat-card p-4">
                <div class="stat-label text-xs">Deadline</div>
                <div class="text-xl font-bold font-heading text-surface-900 mt-1"><?= date('d M Y', strtotime($scholarship['deadline'])) ?></div>
            </div>
        </div>

        <!-- Description -->
        <?php if ($scholarship['description']): ?>
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-surface-800 mb-2">About this Scholarship</h3>
            <div class="text-sm text-surface-600 leading-relaxed"><?= nl2br(e($scholarship['description'])) ?></div>
        </div>
        <?php endif; ?>

        <!-- Eligible Courses -->
        <?php if (!empty($scholarship['eligible_courses'])): ?>
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-surface-800 mb-2">Eligible Courses</h3>
            <div class="flex flex-wrap gap-1.5">
                <?php foreach (explode(',', $scholarship['eligible_courses']) as $c): ?>
                <span class="badge badge-surface"><?= e(trim($c)) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Requirements -->
        <?php if (!empty($scholarship['requirements'])): ?>
        <div>
            <h3 class="text-sm font-semibold text-surface-800 mb-2">Requirements</h3>
            <div class="text-sm text-surface-600 leading-relaxed"><?= nl2br(e($scholarship['requirements'])) ?></div>
        </div>
        <?php endif; ?>

        <!-- Match Score -->
        <?php if ($showMatching && $profile): ?>
        <div class="mt-6 p-4 rounded-xl border" style="background: <?= $matchScore >= 70 ? 'var(--color-success-50)' : ($matchScore >= 40 ? 'var(--color-warning-50)' : 'var(--color-surface-100)') ?>; border-color: <?= $matchScore >= 70 ? 'var(--color-success-100)' : ($matchScore >= 40 ? 'var(--color-warning-100)' : 'var(--color-surface-200)') ?>">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: <?= $matchScore >= 70 ? 'var(--color-success-100)' : ($matchScore >= 40 ? 'var(--color-warning-100)' : 'var(--color-surface-200)') ?>; color: <?= $matchScore >= 70 ? 'var(--color-success-600)' : ($matchScore >= 40 ? 'var(--color-warning-600)' : 'var(--color-surface-500)') ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold" style="color: var(--color-surface-800);">Smart Match: <span style="color: <?= $matchScore >= 70 ? 'var(--color-success-600)' : ($matchScore >= 40 ? 'var(--color-warning-600)' : 'var(--color-surface-500)') ?>"><?= e($matchLabel) ?></span></p>
                    <p class="text-xs" style="color: var(--color-surface-500);">Based on your CGPA, course, and income profile</p>
                </div>
            </div>
        </div>
        <?php elseif (!$showMatching): ?>
        <div class="mt-6 p-4 rounded-xl border" style="background: var(--color-warning-50); border-color: var(--color-warning-100);">
            <p class="text-sm" style="color: var(--color-warning-700);">Upgrade to <a href="<?= url('/subscription') ?>" class="font-semibold underline">Premium</a> for smart match scoring on every scholarship.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Apply Section -->
    <div class="card p-6">
        <h2 class="text-lg font-bold font-heading text-surface-900 mb-4">Apply for this Scholarship</h2>
        <?php if ($hasApplied): ?>
            <div class="alert alert-success" role="alert">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                <span>You have already applied for this scholarship.</span>
            </div>
        <?php elseif ($scholarship['status'] !== 'open'): ?>
            <div class="alert alert-error" role="alert">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                <span>This scholarship is currently closed for applications.</span>
            </div>
        <?php else:
            $eligible = true;
            if ($profile && $scholarship['min_cgpa'] && (floatval($profile['cgpa'] ?? 0) < floatval($scholarship['min_cgpa']))):
                $eligible = false; ?>
                <div class="alert alert-error" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <span>Your CGPA (<?= e($profile['cgpa']) ?>) does not meet the minimum requirement (<?= e($scholarship['min_cgpa']) ?>).</span>
                </div>
            <?php endif;
            if ($profile && $scholarship['max_income'] && (intval($profile['income'] ?? 999999) > intval($scholarship['max_income']))):
                $eligible = false; ?>
                <div class="alert alert-error" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <span>Your household income exceeds the maximum allowed for this scholarship.</span>
                </div>
            <?php endif;
            if ($eligible && empty($documents)): ?>
                <div class="alert alert-warning" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                    <span>Upload required documents first in your <a href="<?= url('/documents') ?>" class="font-semibold underline">documents page</a> before applying.</span>
                </div>
            <?php endif;
            if ($eligible && !empty($documents)): ?>
                <form method="POST" action="<?= url('/scholarships/' . e($scholarship['id']) . '/apply') ?>">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-primary btn-lg">
                        Submit Application
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </button>
                </form>
            <?php endif;
        endif; ?>
    </div>
</div>
