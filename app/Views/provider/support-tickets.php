<?php
$title = 'Support Tickets';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Support Tickets</h1>
        <p class="page-desc">Get help from our support team</p>
    </div>

    <?php if ($flash = flash('success')): ?>
        <div class="flash-success"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><?= e($flash) ?></div>
    <?php endif; ?>
    <?php if ($flash = flash('error')): ?>
        <div class="flash-error"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><?= e($flash) ?></div>
    <?php endif; ?>

    <div class="card-hover p-5 mb-6">
        <h2 class="font-semibold text-surface-800 text-sm mb-4">Open a New Ticket</h2>
        <form method="POST" action="<?= url('/provider/support-tickets') ?>">
            <?= csrfField() ?>
            <div class="space-y-4">
                <div><label class="input-label">Subject</label><input type="text" name="subject" class="input" placeholder="Brief summary..." required></div>
                <div><label class="input-label">Message</label><textarea name="message" rows="4" class="input" placeholder="Describe your issue..." required></textarea></div>
                <button type="submit" class="btn btn-primary">Submit Ticket</button>
            </div>
        </form>
    </div>

    <?php if (!empty($tickets)): ?>
    <div class="divide-y divide-surface-100 bg-white rounded-2xl border border-surface-200 shadow-card overflow-hidden">
        <?php foreach ($tickets as $t): ?>
        <div class="p-5">
            <div class="flex items-start justify-between gap-3 mb-2">
                <h3 class="font-semibold text-surface-800 text-sm"><?= e($t['subject'] ?? '') ?></h3>
                <span class="badge <?= match($t['status']??''){'open'=>'badge-blue','resolved'=>'badge-green','closed'=>'badge-surface', default=>'badge-surface'} ?>"><?= e(ucfirst($t['status'] ?? '')) ?></span>
            </div>
            <p class="text-sm text-surface-600"><?= nl2br(e($t['message'] ?? '')) ?></p>
            <?php if ($t['reply'] ?? ''): ?>
            <div class="mt-3 p-3 bg-brand-50 rounded-xl border border-brand-100"><p class="text-xs font-semibold text-brand-700 mb-1">Staff Response</p><p class="text-sm text-brand-800"><?= nl2br(e($t['reply'])) ?></p></div>
            <?php endif; ?>
            <p class="text-xs text-surface-400 mt-2"><?= date('d M Y, h:i A', strtotime($t['created_at'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197"/></svg><p>No tickets yet.</p></div>
    <?php endif; ?>
</div>