<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= url('/admin/applications') ?>" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 class="text-2xl font-bold text-gray-900">Application #<?= $app['id'] ?></h1>
            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full <?= ($app['status'] ?? '') === 'approved' ? 'bg-green-50 text-green-700' : (($app['status'] ?? '') === 'rejected' ? 'bg-red-50 text-red-700' : (($app['status'] ?? '') === 'screening' ? 'bg-blue-50 text-blue-700' : (($app['status'] ?? '') === 'shortlisted' ? 'bg-purple-50 text-purple-700' : (($app['status'] ?? '') === 'interview' ? 'bg-indigo-50 text-indigo-700' : 'bg-yellow-50 text-yellow-700')))) ?>"><?= ucfirst(str_replace('_', ' ', $app['status'] ?? 'submitted')) ?></span>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="<?= url('/admin/applications/' . $app['id'] . '/force-status') ?>" class="inline">
                <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg" onchange="this.form.submit()">
                    <option value="">Change status...</option>
                    <option value="screening">Screening</option>
                    <option value="shortlisted">Shortlisted</option>
                    <option value="interview">Interview</option>
                    <option value="approved">Approve</option>
                    <option value="rejected">Reject</option>
                </select>
            </form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-900">Application Details</h3></div>
            <dl class="p-6 space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Student</dt><dd class="font-medium text-gray-900"><?= e($app['student_name'] ?? '') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900"><?= e($app['student_email'] ?? '') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Scholarship</dt><dd class="font-medium text-gray-900"><?= e($app['scholarship_name'] ?? '') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Provider</dt><dd class="font-medium text-gray-900"><?= e($app['company_name'] ?? '-') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd class="font-medium"><?= ucfirst(str_replace('_', ' ', $app['status'] ?? 'submitted')) ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Submitted</dt><dd class="font-medium text-gray-900"><?= isset($app['created_at']) ? date('d F Y, h:i A', strtotime($app['created_at'])) : '-' ?></dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-900">Scholarship Details</h3></div>
            <dl class="p-6 space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Name</dt><dd class="font-medium text-gray-900"><?= e($app['scholarship_name'] ?? '') ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Amount</dt><dd class="font-medium text-gray-900">RM <?= number_format($app['amount'] ?? 0, 2) ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Provider</dt><dd class="font-medium text-gray-900"><?= e($app['company_name'] ?? '-') ?></dd></div>
            </dl>
        </div>
    </div>

    <?php if (!empty($app['notes'])): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-900">Admin Notes</h3></div>
        <div class="p-6 text-sm text-gray-700"><?= nl2br(e($app['notes'])) ?></div>
    </div>
    <?php endif; ?>
</div>
