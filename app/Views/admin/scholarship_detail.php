<?php $title = 'Scholarship Detail'; ?>
<div class="animate-fade-in">
    <a href="<?= url('/admin/scholarships') ?>" class="btn btn-ghost btn-sm mb-4">&larr; Back</a>
    <div class="card-hover p-6">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div><h1 class="page-title mb-1"><?= e($s['name']??'') ?></h1><p class="text-sm text-surface-500">by <?= e($s['company_name']??'') ?> (Provider #<?= e($s['provider_id']??'') ?>)</p></div>
            <span class="badge <?= match($s['status']??''){'open'=>'badge-success','pending'=>'badge-warning','draft'=>'badge-warning','closed'=>'badge-danger',default=>'badge-surface'} ?>"><?= e(ucfirst($s['status']??'')) ?></span>
        </div>
        <dl class="grid gap-3 sm:grid-cols-2 text-sm mb-6">
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Deadline</dt><dd class="font-medium"><?= e($s['deadline']??'') ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Amount</dt><dd class="font-medium">RM<?= e(number_format((float)($s['amount']??0))) ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Min. CGPA</dt><dd class="font-medium"><?= e($s['min_cgpa']??'N/A') ?></dd></div>
            <div class="p-3 bg-surface-50 rounded-xl"><dt class="text-surface-500 text-xs">Max. Income</dt><dd class="font-medium">RM<?= e(number_format((float)($s['max_income']??0))) ?></dd></div>
        </dl>

        <!-- Provider Info -->
        <?php if (!empty($provider)): ?>
        <div class="mb-4 p-4 rounded-xl bg-surface-50 border border-surface-100">
            <h3 class="text-sm font-semibold text-surface-700 mb-2">Provider Information</h3>
            <div class="grid gap-2 sm:grid-cols-2 text-sm">
                <div><span class="text-surface-500">Company:</span> <span class="font-medium text-surface-700"><?= e($provider['company_name'] ?? '') ?></span></div>
                <div><span class="text-surface-500">Status:</span> <span class="badge <?= ($provider['is_verified'] ?? false) ? 'badge-success' : 'badge-warning' ?> text-xs"><?= ($provider['is_verified'] ?? false) ? 'Verified' : 'Unverified' ?></span></div>
                <?php if (!empty($provider['registration_no'])): ?>
                <div><span class="text-surface-500">Registration No:</span> <span class="font-medium text-surface-700"><?= e($provider['registration_no']) ?></span></div>
                <?php endif; ?>
                <?php if (!empty($provider['phone'])): ?>
                <div><span class="text-surface-500">Phone:</span> <span class="font-medium text-surface-700"><?= e($provider['phone']) ?></span></div>
                <?php endif; ?>
            </div>
            <?php if (!empty($provider['document'])): ?>
            <div class="mt-3 pt-3 border-t border-surface-200">
                <span class="text-surface-500 text-sm">Verification Document:</span>
                <a href="<?= url('/' . e($provider['document'])) ?>" target="_blank" class="inline-flex items-center gap-1 ml-2 text-sm text-primary-600 hover:text-primary-700 font-medium">
                    View Document
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Description -->
        <?php if (!empty($s['description'])): ?>
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-surface-700 mb-1">Description</h3>
            <p class="text-sm text-surface-600"><?= e($s['description']) ?></p>
        </div>
        <?php endif; ?>

        <!-- Requirements -->
        <?php if (!empty($s['requirements'])): ?>
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-surface-700 mb-1">Requirements</h3>
            <p class="text-sm text-surface-600"><?= nl2br(e($s['requirements'])) ?></p>
        </div>
        <?php endif; ?>

        <!-- Eligible Courses -->
        <?php if (!empty($s['eligible_courses'])): ?>
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-surface-700 mb-1">Eligible Courses</h3>
            <p class="text-sm text-surface-600"><?= e($s['eligible_courses']) ?></p>
        </div>
        <?php endif; ?>

        <!-- Supporting Documents -->
        <?php if (!empty($documents)): ?>
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-surface-700 mb-1">Supporting Documents</h3>
            <div class="space-y-2">
                <?php foreach ($documents as $doc): ?>
                <a href="<?= url('/uploads/scholarship_docs/' . e($doc['stored_name'])) ?>" target="_blank" class="flex items-center gap-2 p-2 rounded-lg bg-surface-50 hover:bg-surface-100 transition-colors group">
                    <svg class="w-4 h-4 text-surface-400 shrink-0 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    <span class="text-sm text-surface-700 group-hover:text-primary-600"><?= e($doc['original_name']) ?></span>
                    <span class="text-xs text-surface-400">(<?= e(round($doc['file_size'] / 1024)) ?> KB)</span>
                    <svg class="w-4 h-4 text-surface-300 ml-auto shrink-0 group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-surface-700 mb-1">Supporting Documents</h3>
            <p class="text-sm text-surface-400 italic">No documents uploaded</p>
        </div>
        <?php endif; ?>

        <!-- Approve / Reject actions for scholarships awaiting review -->
        <?php if (in_array($s['status']??'', ['pending', 'draft'])): ?>
        <div class="flex gap-3 mt-4 pt-4 border-t border-surface-200">
            <form method="POST" action="<?= url('/admin/scholarships/' . e($s['id']) . '/toggle-status') ?>">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    Approve &amp; Publish
                </button>
            </form>
            <form method="POST" action="<?= url('/admin/scholarships/' . e($s['id']) . '/toggle-status') ?>">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="reject">
                <button type="submit" class="btn btn-outline" style="color: var(--color-danger-600); border-color: var(--color-danger-300);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
                    Reject
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Toggle status (admin override) for non-pending -->
        <?php if (($s['status']??'') !== 'pending'): ?>
        <div class="mt-4 pt-4 border-t border-surface-200">
            <form method="POST" action="<?= url('/admin/scholarships/' . e($s['id']) . '/toggle-status') ?>" style="display:inline">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-outline btn-sm">Toggle Status</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>