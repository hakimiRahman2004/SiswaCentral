<?php
$title = 'Application Detail';
$badgeMap = fn($s) => match($s) {
    'approved' => 'badge-success',
    'rejected' => 'badge-danger',
    'screening' => 'badge-warning',
    'shortlisted' => 'badge-primary',
    'interview' => 'badge-info',
    default => 'badge-surface',
};
$statusColor = fn($s) => match($s) {
    'approved' => '#059669',
    'rejected' => '#dc2626',
    'screening' => '#d97706',
    'shortlisted' => '#4f46e5',
    'interview' => '#0284c7',
    default => '#737373',
};
$docIcons = [
    'ic' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>',
    'transcript' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>',
    'income' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'resume' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>',
    'certificate' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>',
    'passport' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3"/></svg>',
];
$docColor = fn($t) => match($t) {
    'ic' => 'text-blue-600 bg-blue-50',
    'transcript' => 'text-emerald-600 bg-emerald-50',
    'income' => 'text-amber-600 bg-amber-50',
    'resume' => 'text-purple-600 bg-purple-50',
    'certificate' => 'text-rose-600 bg-rose-50',
    'passport' => 'text-cyan-600 bg-cyan-50',
    default => 'text-surface-600 bg-surface-100',
};
?>
<div class="animate-fade-in max-w-5xl mx-auto">
    <!-- Top bar -->
    <div class="flex items-center justify-between mb-6">
        <a href="<?= url('/provider/applications') ?>" class="btn btn-ghost btn-sm">&larr; Back to Applications</a>
        <span class="text-xs text-surface-400">Applied <?= date('d M Y', strtotime($app['created_at'])) ?></span>
    </div>

    <!-- Student Header Card -->
    <div class="card p-6 mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-bl from-primary-500/5 to-transparent rounded-full -mr-16 -mt-16"></div>
        <div class="flex flex-wrap items-start justify-between gap-4 relative">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold font-heading shrink-0 shadow-sm" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                    <?= e(strtoupper(substr($app['student_name'] ?? 'U', 0, 1))) ?>
                </div>
                <div>
                    <h1 class="text-2xl font-bold font-heading text-surface-900"><?= e($app['student_name'] ?? '') ?></h1>
                    <p class="text-surface-500 text-sm">Applied for <span class="font-semibold text-surface-700"><?= e($app['scholarship_name'] ?? '') ?></span></p>
                    <div class="flex items-center gap-3 mt-1.5 text-xs text-surface-400">
                        <span><?= e($app['student_email'] ?? '') ?></span>
                        <?php if ($app['student_phone'] ?? ''): ?>
                        <span class="w-1 h-1 rounded-full bg-surface-300"></span>
                        <span><?= e($app['student_phone']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <span class="badge <?= $badgeMap($app['status'] ?? '') ?> badge-lg text-sm"><?= e(ucfirst($app['status'] ?? '')) ?></span>
        </div>
    </div>

    <!-- Status Update + Profile side by side -->
    <div class="grid gap-6 lg:grid-cols-3 mb-6">
        <!-- Status Update Form -->
        <div class="lg:col-span-1">
            <div class="card p-5 h-full">
                <h2 class="text-sm font-semibold text-surface-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Update Status
                </h2>
                <form method="POST" action="<?= url('/provider/applications/' . e($app['id']) . '/status') ?>">
                    <?= csrfField() ?>
                    <div class="space-y-3">
                        <div>
                            <label class="input-label">Status</label>
                            <select name="status" class="input w-full">
                                <option value="screening" <?= ($app['status']??'')==='screening'?'selected':'' ?>>Screening</option>
                                <option value="shortlisted" <?= ($app['status']??'')==='shortlisted'?'selected':'' ?>>Shortlisted</option>
                                <option value="interview" <?= ($app['status']??'')==='interview'?'selected':'' ?>>Interview</option>
                                <option value="approved" <?= ($app['status']??'')==='approved'?'selected':'' ?>>Approved</option>
                                <option value="rejected" <?= ($app['status']??'')==='rejected'?'selected':'' ?>>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="input-label">Notes</label>
                            <textarea name="notes" rows="3" class="input w-full resize-none"><?= e($app['notes'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label for="communication_url" class="input-label">WhatsApp / Telegram group link</label>
                            <input id="communication_url" name="communication_url" type="url" class="input w-full" value="<?= e($app['communication_url'] ?? '') ?>" placeholder="https://chat.whatsapp.com/...">
                            <p class="input-hint">Students will see this link on their application page.</p>
                        </div>
                        <button type="submit" class="btn btn-primary w-full justify-center">Update Status</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Academic Profile Summary -->
        <div class="lg:col-span-2">
            <div class="card p-5 h-full">
                <h2 class="text-sm font-semibold text-surface-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342"/></svg>
                    Academic Profile
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="text-center p-3 rounded-xl bg-surface-50 border border-surface-100">
                        <div class="text-2xl font-bold font-heading text-surface-900"><?= e($app['cgpa'] ?? '-') ?></div>
                        <div class="text-xs text-surface-500 mt-0.5">CGPA</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-surface-50 border border-surface-100">
                        <div class="text-2xl font-bold font-heading text-surface-900 truncate" title="<?= e($app['course'] ?? '') ?>"><?= e(explode(' ', $app['course'] ?? '-')[0]) ?></div>
                        <div class="text-xs text-surface-500 mt-0.5 truncate"><?= e($app['course'] ?? 'Course') ?></div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-surface-50 border border-surface-100 sm:col-span-1 col-span-2">
                        <div class="text-sm font-bold font-heading text-surface-900 truncate" title="<?= e($app['university'] ?? '') ?>"><?= e($app['university'] ?? '-') ?></div>
                        <div class="text-xs text-surface-500 mt-0.5">University</div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                    <div>
                        <span class="text-xs text-surface-400 block">Gender</span>
                        <span class="font-medium text-surface-700 capitalize"><?= e($app['gender'] ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="text-xs text-surface-400 block">DOB</span>
                        <span class="font-medium text-surface-700"><?= $app['date_of_birth'] ? date('d M Y', strtotime($app['date_of_birth'])) : '-' ?></span>
                    </div>
                    <div>
                        <span class="text-xs text-surface-400 block">Monthly Income</span>
                        <span class="font-medium text-surface-700"><?= $app['student_income'] ? 'RM' . e(number_format((float)$app['student_income'])) : '-' ?></span>
                    </div>
                    <div>
                        <span class="text-xs text-surface-400 block">Scholarship</span>
                        <span class="font-medium text-surface-700"><?= $app['amount'] ? 'RM' . e(number_format((float)$app['amount'])) : '-' ?></span>
                    </div>
                </div>
                <?php if ($app['student_address'] ?? ''): ?>
                <div class="mt-3 pt-3 border-t border-surface-100">
                    <span class="text-xs text-surface-400 block mb-0.5">Address</span>
                    <span class="text-sm text-surface-700"><?= nl2br(e($app['student_address'])) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($app['student_about'] ?? ''): ?>
                <div class="mt-3 pt-3 border-t border-surface-100">
                    <span class="text-xs text-surface-400 block mb-0.5">About</span>
                    <span class="text-sm text-surface-600"><?= nl2br(e($app['student_about'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Next steps -->
    <div class="grid gap-6 lg:grid-cols-2 mb-6">
        <section class="card p-5">
            <h2 class="text-sm font-semibold text-surface-800 mb-1">Schedule interview</h2>
            <p class="text-sm text-surface-500 mb-4">Saving this moves the application to Interview and sends the meeting details to the student.</p>
            <form method="POST" action="<?= url('/provider/applications/' . e($app['id']) . '/interview') ?>" class="space-y-3">
                <?= csrfField() ?>
                <div><label for="scheduled_at" class="input-label">Date and time</label><input id="scheduled_at" type="datetime-local" name="scheduled_at" class="input w-full" value="<?= !empty($interview['scheduled_at']) ? e(date('Y-m-d\\TH:i', strtotime($interview['scheduled_at']))) : '' ?>" required></div>
                <div><label for="meeting_url" class="input-label">Meeting link</label><input id="meeting_url" type="url" name="meeting_url" class="input w-full" value="<?= e($interview['meeting_url'] ?? '') ?>" placeholder="https://meet.google.com/..." required></div>
                <div><label for="interview_instructions" class="input-label">Instructions <span class="text-surface-400 font-normal">(optional)</span></label><textarea id="interview_instructions" name="instructions" rows="2" class="input w-full resize-none"><?= e($interview['instructions'] ?? '') ?></textarea></div>
                <button type="submit" class="btn btn-outline w-full justify-center">Save interview & notify</button>
            </form>
        </section>

        <section class="card p-5">
            <h2 class="text-sm font-semibold text-surface-800 mb-1">Request an essay</h2>
            <p class="text-sm text-surface-500 mb-4">The student can submit their answer directly from their application page.</p>
            <form method="POST" action="<?= url('/provider/applications/' . e($app['id']) . '/essay-request') ?>" class="space-y-3">
                <?= csrfField() ?>
                <div><label for="essay_prompt" class="input-label">Essay question or instructions</label><textarea id="essay_prompt" name="prompt" rows="4" class="input w-full resize-none" required><?= e($essayRequest['prompt'] ?? '') ?></textarea></div>
                <div><label for="essay_due_at" class="input-label">Due date <span class="text-surface-400 font-normal">(optional)</span></label><input id="essay_due_at" type="datetime-local" name="due_at" class="input w-full" value="<?= !empty($essayRequest['due_at']) ? e(date('Y-m-d\\TH:i', strtotime($essayRequest['due_at']))) : '' ?>"></div>
                <?php if (!empty($essayRequest['submitted_at'])): ?><p class="text-sm text-success-700">Submitted <?= e(date('d M Y, h:i A', strtotime($essayRequest['submitted_at']))) ?></p><?php endif; ?>
                <button type="submit" class="btn btn-outline w-full justify-center">Send essay request</button>
            </form>
        </section>
    </div>

    <?php if (!empty($essayRequest['submitted_essay'])): ?>
    <section class="card p-5 mb-6">
        <h2 class="text-sm font-semibold text-surface-800 mb-3">Student essay</h2>
        <div class="p-4 rounded-xl bg-surface-50 text-sm leading-relaxed text-surface-700 whitespace-pre-wrap"><?= e($essayRequest['submitted_essay']) ?></div>
    </section>
    <?php endif; ?>

    <!-- Student's Document Vault -->
    <div class="card p-5 mb-6">
        <h2 class="text-sm font-semibold text-surface-800 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            Student's Document Vault
        </h2>
        <?php if (!empty($studentDocs)): ?>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($studentDocs as $d): ?>
            <a href="<?= url('/' . e($d['file_path'] ?? '')) ?>" target="_blank" class="block p-3 rounded-xl border border-surface-200 bg-surface-50 hover:bg-white hover:border-primary-200 hover:shadow-sm transition-all group">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 <?= $docColor($d['type'] ?? '') ?>">
                        <?= $docIcons[$d['type'] ?? ''] ?? $docIcons['certificate'] ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-surface-800 truncate group-hover:text-primary-600 transition-colors" title="<?= e($d['file_name'] ?? '') ?>"><?= e($d['file_name'] ?? '') ?></p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wider text-surface-400"><?= e(str_replace('_', ' ', $d['type'] ?? '')) ?></span>
                            <span class="w-1 h-1 rounded-full bg-surface-300"></span>
                            <span class="text-[10px] text-surface-400"><?= $d['file_size'] ? round($d['file_size'] / 1024, 1) . ' KB' : '' ?></span>
                            <?php if ($d['is_verified'] ?? false): ?>
                            <span class="w-1 h-1 rounded-full bg-surface-300"></span>
                            <span class="text-[10px] text-success-600 font-medium">Verified</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-surface-300 group-hover:text-primary-400 shrink-0 mt-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-8 text-surface-400">
            <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            <p class="text-sm">No documents uploaded yet.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Status Timeline -->
    <?php if (!empty($logs)): ?>
    <div class="card p-5 mb-6">
        <h2 class="text-sm font-semibold text-surface-800 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Status Timeline
        </h2>
        <div class="relative pl-8 space-y-0">
            <div class="absolute left-3.5 top-2 bottom-2 w-0.5 bg-surface-200"></div>
            <?php foreach ($logs as $i => $log): $isFirst = $i === 0; ?>
            <div class="relative pb-6">
                <div class="absolute -left-4.5 top-1 w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center <?= $isFirst ? 'border-primary-500 bg-primary-50' : 'border-surface-300 bg-white' ?>">
                    <div class="w-1.5 h-1.5 rounded-full <?= $isFirst ? 'bg-primary-500' : 'bg-surface-300' ?>"></div>
                </div>
                <div>
                    <p class="text-sm <?= $isFirst ? 'text-surface-800 font-medium' : 'text-surface-600' ?>">
                        <?php if ($log['old_status'] ?? null): ?>
                            <span class="font-medium"><?= e(ucfirst(str_replace('_', ' ', $log['old_status']))) ?></span>
                            <svg class="w-3.5 h-3.5 inline mx-1 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            <span class="font-medium"><?= e(ucfirst(str_replace('_', ' ', $log['new_status']))) ?></span>
                        <?php else: ?>
                            Application submitted
                        <?php endif; ?>
                    </p>
                    <p class="text-xs text-surface-400 mt-0.5"><?= date('d M Y, h:i A', strtotime($log['created_at'])) ?> &middot; <?= e($log['changed_by_name'] ?? 'System') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Internal Notes & Document Request -->
    <div class="grid gap-6 lg:grid-cols-2 mb-6">
        <!-- Internal Notes -->
        <div class="card p-5">
            <h2 class="text-sm font-semibold text-surface-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                Internal Notes
            </h2>
            <form method="POST" action="<?= url('/provider/applications/' . e($app['id']) . '/notes') ?>" class="mb-4">
                <?= csrfField() ?>
                <textarea name="note" rows="3" class="input w-full resize-none" placeholder="Add a note about this application..." required></textarea>
                <button type="submit" class="btn btn-primary btn-sm mt-2">Add Note</button>
            </form>
            <?php if (!empty($notes)): ?>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                <?php foreach ($notes as $note): ?>
                <div class="p-3 rounded-lg bg-surface-50 border border-surface-100">
                    <p class="text-sm text-surface-700 whitespace-pre-wrap"><?= e($note['note']) ?></p>
                    <p class="text-xs text-surface-400 mt-1"><?= e($note['author_name'] ?? 'Staff') ?> &middot; <?= date('d M Y, h:i A', strtotime($note['created_at'])) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-sm text-surface-400 text-center py-4">No notes yet</p>
            <?php endif; ?>
        </div>

        <!-- Document Request -->
        <div class="card p-5">
            <h2 class="text-sm font-semibold text-surface-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9.75m-3.75 0H6m3.75 0h3m-3.75 0v3m0-3v-3"/></svg>
                Request Documents
            </h2>
            <form method="POST" action="<?= url('/provider/applications/' . e($app['id']) . '/document-request') ?>" class="space-y-3">
                <?= csrfField() ?>
                <div>
                    <label class="input-label">Document Type</label>
                    <input type="text" name="document_type" class="input w-full" placeholder="e.g. Bank Statement, Recommendation Letter" required>
                </div>
                <div>
                    <label class="input-label">Description</label>
                    <textarea name="description" rows="2" class="input w-full resize-none" placeholder="Explain what you need and why..."></textarea>
                </div>
                <div>
                    <label class="input-label">Deadline (optional)</label>
                    <input type="date" name="deadline" class="input w-full">
                </div>
                <button type="submit" class="btn btn-outline w-full justify-center">Request Document</button>
            </form>
            <?php if (!empty($docRequests)): ?>
            <div class="mt-4 space-y-2">
                <p class="text-xs font-medium text-surface-500 uppercase tracking-wider">Past Requests</p>
                <?php foreach ($docRequests as $dr): ?>
                <div class="flex items-center justify-between p-3 rounded-lg bg-surface-50 border border-surface-100">
                    <div>
                        <p class="text-sm font-medium text-surface-700"><?= e($dr['document_type']) ?></p>
                        <p class="text-xs text-surface-400">Requested <?= date('d M Y', strtotime($dr['created_at'])) ?><?= $dr['deadline'] ? ' &middot; Due ' . date('d M Y', strtotime($dr['deadline'])) : '' ?></p>
                    </div>
                    <span class="badge <?= ($dr['status'] ?? '') === 'uploaded' ? 'badge-success' : 'badge-warning' ?> text-xs"><?= e(ucfirst($dr['status'] ?? 'pending')) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
