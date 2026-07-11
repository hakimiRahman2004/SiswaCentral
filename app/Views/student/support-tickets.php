<?php
/** @var array<int, array<string, mixed>> $tickets */
$title = 'Support Tickets';
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Support Tickets</h1>
        <p class="page-desc">Get help from our support team</p>
    </div>

    <!-- New Ticket Form -->
    <div class="card p-6 mb-6">
        <h2 class="section-title mb-4">Open a New Ticket</h2>
        <form method="POST" action="<?= url('/support-tickets/create') ?>">
            <?= csrfField() ?>
            <div class="space-y-4 max-w-lg">
                <div>
                    <label class="input-label">Subject</label>
                    <input type="text" name="subject" class="input" placeholder="Brief summary of your issue" required>
                    <?php if ($err = error('subject')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="input-label">Message</label>
                    <textarea name="message" rows="4" class="input" placeholder="Describe your issue in detail..." required></textarea>
                    <?php if ($err = error('message')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12zm0 0h7.5"/></svg>
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>

    <!-- Tickets List -->
    <?php if (!empty($tickets)): ?>
    <div class="card overflow-hidden divide-y divide-surface-100">
        <?php foreach ($tickets as $t): ?>
        <div class="p-5">
            <div class="flex items-start justify-between gap-3 mb-2">
                <h3 class="font-semibold text-surface-800 text-sm"><?= e($t['subject'] ?? '') ?></h3>
                <?php $ts = $t['status'] ?? ''; $tc = match($ts) {'open'=>'badge-info','resolved'=>'badge-success','closed'=>'badge-surface', default=>'badge-surface' }; ?>
                <span class="badge <?= $tc ?> shrink-0"><?= e(ucfirst($ts)) ?></span>
            </div>
            <p class="text-sm text-surface-600 leading-relaxed"><?= nl2br(e($t['message'] ?? '')) ?></p>
            <?php if ($t['admin_reply'] ?? ''): ?>
                <div class="mt-3 p-4 rounded-xl border" style="background: var(--color-primary-50); border-color: var(--color-primary-100);">
                    <p class="text-xs font-semibold" style="color: var(--color-primary-700);">Staff Response</p>
                    <p class="text-sm mt-1" style="color: var(--color-primary-800);"><?= nl2br(e($t['admin_reply'])) ?></p>
                </div>
            <?php endif; ?>
            <p class="text-xs text-surface-400 mt-2">Opened <?= date('d M Y, h:i A', strtotime($t['created_at'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
        <p class="empty-title">No tickets yet</p>
        <p class="empty-desc">Submit a ticket above and our support team will get back to you.</p>
    </div>
    <?php endif; ?>
</div>
