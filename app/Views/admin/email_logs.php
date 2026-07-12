<?php $title = 'Email Logs'; ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Email Logs</h1>
        <p class="page-desc">Track all emails sent through the platform</p>
    </div>

    <?php if (!empty($logs)): ?>
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Recipient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 uppercase tracking-wider">Sent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    <?php foreach ($logs as $log): ?>
                    <tr class="hover:bg-surface-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-surface-800"><?= e($log['recipient_name'] ?? 'Unknown') ?></div>
                            <div class="text-xs text-surface-400"><?= e($log['recipient_email'] ?? '') ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-surface-600"><?= e($log['subject'] ?? '') ?></td>
                        <td class="px-6 py-4">
                            <span class="badge badge-surface text-xs"><?= e($log['type'] ?? 'general') ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php $status = $log['status'] ?? 'sent'; ?>
                            <span class="badge <?= $status === 'sent' ? 'badge-success' : ($status === 'failed' ? 'badge-danger' : 'badge-warning') ?> text-xs"><?= e(ucfirst($status)) ?></span>
                        </td>
                        <td class="px-6 py-4 text-xs text-surface-500"><?= date('d M Y, h:i A', strtotime($log['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
        <p class="empty-title">No email logs</p>
        <p class="empty-desc">Emails sent through the platform will appear here.</p>
    </div>
    <?php endif; ?>
</div>
