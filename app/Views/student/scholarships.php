<?php
/** @var array<int, array<string, mixed>> $scholarships */
/** @var array<int> $savedIds */
/** @var array<int> $appliedIds */
/** @var string $q */
/** @var string $course */
/** @var string $cgpa */
/** @var string $income */
/** @var bool $showMatching */
$title = 'Browse Scholarships';
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Browse Scholarships</h1>
        <p class="page-desc">Discover funding opportunities that match your profile</p>
    </div>

    <!-- Search & Filter -->
    <div class="card p-4 mb-8">
        <form method="GET" action="<?= url('/scholarships') ?>">
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-56 search-bar">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/></svg>
                    <input type="text" name="q" value="<?= e($q ?? '') ?>" class="input" placeholder="Search scholarships...">
                </div>
                <div>
                    <input type="text" name="course" value="<?= e($course ?? '') ?>" class="input" placeholder="Course, e.g. Engineering">
                </div>
                <div>
                    <input type="text" name="cgpa" value="<?= e($cgpa ?? '') ?>" class="input" placeholder="Min CGPA" style="width:90px">
                </div>
                <div>
                    <input type="text" name="income" value="<?= e($income ?? '') ?>" class="input" placeholder="Max income" style="width:100px">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if (!empty($q) || !empty($course) || !empty($cgpa) || !empty($income)): ?>
                    <a href="<?= url('/scholarships') ?>" class="btn btn-ghost">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Results -->
    <?php if (!empty($scholarships)): ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($scholarships as $s): ?>
            <a href="<?= url('/scholarships/' . e($s['id'])) ?>" class="card-hover p-5 block group relative">
                <!-- Save button -->
                <?php $isSaved = isset($savedIds[$s['id']]); ?>
                <form method="POST" action="<?= url('/scholarships/' . e($s['id']) . '/' . ($isSaved ? 'unsave' : 'save')) ?>"
                      class="absolute top-3 right-3 z-10" onclick="event.stopPropagation()">
                    <?= csrfField() ?>
                    <button type="submit" class="p-1.5 rounded-lg hover:bg-surface-100 transition-colors" aria-label="<?= $isSaved ? 'Unsave' : 'Save' ?>">
                        <?php if ($isSaved): ?>
                            <svg class="w-4 h-4 text-accent-500 fill-current" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        <?php else: ?>
                            <svg class="w-4 h-4 text-surface-400 group-hover:text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0z"/></svg>
                        <?php endif; ?>
                    </button>
                </form>

                <div class="flex items-start gap-2 mb-3 pr-8">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-sm font-bold" style="background: var(--color-primary-50); color: var(--color-primary-700);">
                        <?= e(substr($s['name'] ?? 'S', 0, 1)) ?>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-surface-800 text-sm leading-snug group-hover:text-primary-600 transition-colors truncate"><?= e($s['name']) ?></h3>
                        <p class="text-xs text-surface-500 mt-0.5"><?= e($s['provider_name'] ?? '') ?></p>
                    </div>
                </div>

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

                <?php if ($showMatching && isset($s['match_score'])): ?>
                    <div class="flex items-center gap-1.5 mt-3 pt-3 border-t border-surface-100">
                        <span class="badge <?= $s['match_score'] >= 70 ? 'badge-success' : ($s['match_score'] >= 40 ? 'badge-warning' : 'badge-surface') ?> text-xs"><?= e($s['match_score']) ?>% Match</span>
                    </div>
                <?php endif; ?>

                <!-- Apply button -->
                <?php $alreadyApplied = isset($appliedIds[$s['id']]); ?>
                <?php if ($s['status'] === 'open' && !$alreadyApplied): ?>
                <div class="mt-3 pt-3 border-t border-surface-100" onclick="event.stopPropagation()">
                    <form method="POST" action="<?= url('/scholarships/' . e($s['id']) . '/apply') ?>">
                        <?= csrfField() ?>
                        <button type="submit" class="w-full text-center py-2 text-sm font-semibold text-white rounded-lg transition-colors" style="background: var(--color-primary-600);" onmouseover="this.style.background='var(--color-primary-700)'" onmouseout="this.style.background='var(--color-primary-600)'">
                            Apply Now
                        </button>
                    </form>
                </div>
                <?php elseif ($alreadyApplied): ?>
                <div class="mt-3 pt-3 border-t border-surface-100">
                    <span class="block w-full text-center py-2 text-sm font-medium text-success-700 rounded-lg" style="background: var(--color-success-50);">
                        <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        Applied
                    </span>
                </div>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/></svg>
            <p class="empty-title">No scholarships found</p>
            <p class="empty-desc">Try adjusting your search or filter criteria.</p>
            <div class="empty-action">
                <a href="<?= url('/scholarships') ?>" class="btn btn-primary btn-sm">Reset filters</a>
            </div>
        </div>
    <?php endif; ?>
</div>
