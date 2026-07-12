<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= url('/admin/providers') ?>" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 class="text-2xl font-bold text-gray-900"><?= e($p['company_name'] ?? e($p['owner_name'] ?? '')) ?></h1>
            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full <?= ($p['is_verified'] ?? 0) ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' ?>"><?= ($p['is_verified'] ?? 0) ? 'Verified' : 'Unverified' ?></span>
        </div>
        <div class="flex gap-2">
            <?php if (!($p['is_verified'] ?? 0)): ?>
            <form method="POST" action="<?= url('/admin/providers/' . $p['id'] . '/verify') ?>" class="inline" onsubmit="return confirm('Verify this provider? They will get full access.')"><?= csrfField() ?><button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Approve &amp; Verify</button></form>
            <?php endif; ?>
            <?php if (($p['is_verified'] ?? 0)): ?>
            <form method="POST" action="<?= url('/admin/providers/' . $p['id'] . '/verify') ?>" class="inline" onsubmit="return confirm('Revoke verification for this provider?')"><?= csrfField() ?><button type="submit" class="px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 rounded-lg">Revoke Verification</button></form>
            <?php endif; ?>
            <form method="POST" action="<?= url('/admin/providers/' . $p['id'] . '/suspend') ?>" class="inline"><?= csrfField() ?><button type="submit" class="px-4 py-2 text-sm font-medium <?= ($p['status'] ?? 'active') === 'active' ? 'text-red-700 bg-red-50 hover:bg-red-100' : 'text-green-700 bg-green-50 hover:bg-green-100' ?> rounded-lg" onclick="return confirm('<?= ($p['status'] ?? 'active') === 'active' ? 'Suspend' : 'Activate' ?> this provider?')"><?= ($p['status'] ?? 'active') === 'active' ? 'Suspend' : 'Activate' ?></button></form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-900">Details</h3></div>
            <dl class="p-6 space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Organization</dt><dd class="font-medium text-gray-900"><?= e($p['company_name'] ?? e($p['owner_name'] ?? '-')) ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Registration No.</dt><dd class="font-medium text-gray-900"><?= e($p['registration_no'] ?? '-') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900"><?= e($p['owner_email'] ?? '') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd class="font-medium text-gray-900"><?= e($p['phone'] ?? '-') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Address</dt><dd class="font-medium text-gray-900 text-right max-w-[200px]"><?= e($p['address'] ?? '-') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full <?= ($p['status']??'') === 'active' ? 'bg-green-50 text-green-700' : (($p['status']??'') === 'suspended' ? 'bg-red-50 text-red-600' : 'bg-yellow-50 text-yellow-700') ?>"><?= ucfirst($p['status'] ?? 'pending') ?></span></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Joined</dt><dd class="font-medium text-gray-900"><?= isset($p['created_at']) ? date('d F Y', strtotime($p['created_at'])) : '-' ?></dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-900">Verification Document</h3></div>
            <div class="p-6">
                <?php if (!empty($p['document'])): ?>
                <a href="<?= url('/' . e($p['document'])) ?>" target="_blank" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <svg class="w-8 h-8 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    <div class="text-sm"><p class="font-medium text-gray-900"><?= e(basename($p['document'])) ?></p><p class="text-xs text-gray-400">Click to view document</p></div>
                    <svg class="w-4 h-4 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.625A1.125 1.125 0 0 0 4.5 7.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                </a>
                <?php else: ?>
                <p class="text-sm text-gray-400 text-center py-4">No document uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-900">Staff Members</h3></div>
            <div class="divide-y divide-gray-100">
                <?php if (empty($staff)): ?><p class="p-6 text-center text-gray-400 text-sm">No staff members.</p><?php endif; ?>
                <?php foreach ($staff as $s): ?>
                <div class="px-6 py-3 flex justify-between items-center">
                    <div><p class="text-sm font-medium text-gray-900"><?= e($s['name'] ?? '') ?></p><p class="text-xs text-gray-400"><?= e($s['email'] ?? '') ?></p></div>
                    <span class="text-xs text-gray-400"><?= ucfirst($s['role'] ?? 'staff') ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-900">Scholarships (<?= count($scholarships) ?>)</h3></div>
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 uppercase border-b border-gray-100"><th class="px-6 py-3 font-medium">Name</th><th class="px-6 py-3 font-medium">Value</th><th class="px-6 py-3 font-medium">Status</th><th class="px-6 py-3 font-medium">Applications</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($scholarships)): ?><tr><td colspan="4" class="px-6 py-4 text-center text-gray-400">No scholarships yet.</td></tr><?php endif; ?>
                <?php foreach ($scholarships as $s): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-900"><?= e($s['name'] ?? '') ?></td>
                    <td class="px-6 py-3 text-gray-500">RM <?= number_format($s['amount'] ?? 0, 2) ?></td>
                    <td class="px-6 py-3"><span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full <?= ($s['status'] ?? '') === 'open' ? 'bg-green-50 text-green-700' : (($s['status'] ?? '') === 'closed' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-600') ?>"><?= ucfirst($s['status'] ?? 'draft') ?></span></td>
                    <td class="px-6 py-3 text-gray-500"><?= $s['application_count'] ?? 0 ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
