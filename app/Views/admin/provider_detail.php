<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= url('/admin/providers') ?>" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 class="text-2xl font-bold text-gray-900"><?= e($p['company_name'] ?? e($p['owner_name'] ?? '')) ?></h1>
            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full <?= ($p['is_verified'] ?? 0) ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' ?>"><?= ($p['is_verified'] ?? 0) ? 'Verified' : 'Unverified' ?></span>
        </div>
        <div class="flex gap-2">
            <?php if (!($p['is_verified'] ?? 0)): ?>
            <form method="POST" action="<?= url('/admin/providers/' . $p['id'] . '/verify') ?>" class="inline"><button type="submit" class="px-4 py-2 text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg">Verify</button></form>
            <?php endif; ?>
            <form method="POST" action="<?= url('/admin/providers/' . $p['id'] . '/suspend') ?>" class="inline"><button type="submit" class="px-4 py-2 text-sm font-medium <?= ($p['status'] ?? 'active') === 'active' ? 'text-red-700 bg-red-50 hover:bg-red-100' : 'text-green-700 bg-green-50 hover:bg-green-100' ?> rounded-lg"><?= ($p['status'] ?? 'active') === 'active' ? 'Suspend' : 'Activate' ?></button></form>
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
                <div class="flex justify-between"><dt class="text-gray-500">Joined</dt><dd class="font-medium text-gray-900"><?= isset($p['created_at']) ? date('d F Y', strtotime($p['created_at'])) : '-' ?></dd></div>
            </dl>
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
