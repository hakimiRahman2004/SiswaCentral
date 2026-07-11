<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Applications</h1>
        <span class="text-sm text-gray-500"><?= $totalApplications ?> total</span>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm"><p class="text-xs text-gray-500">Pending</p><p class="text-xl font-bold text-yellow-600"><?= $pendingCount ?></p></div>
        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm"><p class="text-xs text-gray-500">Approved</p><p class="text-xl font-bold text-green-600"><?= $approvedCount ?></p></div>
        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm"><p class="text-xs text-gray-500">Rejected</p><p class="text-xl font-bold text-red-600"><?= $rejectedCount ?></p></div>
    </div>

    <form method="GET" class="flex gap-2">
        <input type="text" name="search" placeholder="Search by student or scholarship..." value="<?= e($_GET['search'] ?? '') ?>" class="w-full max-w-md px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg"><option value="">All Status</option><option value="submitted" <?= ($_GET['status'] ?? '') === 'submitted' ? 'selected' : '' ?>>Submitted</option><option value="screening" <?= ($_GET['status'] ?? '') === 'screening' ? 'selected' : '' ?>>Screening</option><option value="shortlisted" <?= ($_GET['status'] ?? '') === 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option><option value="interview" <?= ($_GET['status'] ?? '') === 'interview' ? 'selected' : '' ?>>Interview</option><option value="approved" <?= ($_GET['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option><option value="rejected" <?= ($_GET['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option></select>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Filter</button>
        <a href="<?= url('/admin/applications') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Reset</a>
    </form>

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 uppercase border-b border-gray-100"><th class="px-6 py-3 font-medium">Student</th><th class="px-6 py-3 font-medium">Scholarship</th><th class="px-6 py-3 font-medium">Provider</th><th class="px-6 py-3 font-medium">Status</th><th class="px-6 py-3 font-medium">Applied</th><th class="px-6 py-3 font-medium"></th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($applications)): ?><tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No applications found.</td></tr><?php endif; ?>
                <?php foreach ($applications as $a): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-900"><?= e($a['student_name'] ?? '') ?></td>
                    <td class="px-6 py-3 text-gray-500"><?= e($a['scholarship_name'] ?? '') ?></td>
                    <td class="px-6 py-3 text-gray-500"><?= e($a['provider_name'] ?? '-') ?></td>
                    <td class="px-6 py-3"><span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full <?= ($a['status'] ?? '') === 'approved' ? 'bg-green-50 text-green-700' : (($a['status'] ?? '') === 'rejected' ? 'bg-red-50 text-red-700' : (($a['status'] ?? '') === 'screening' ? 'bg-blue-50 text-blue-700' : (($a['status'] ?? '') === 'shortlisted' ? 'bg-purple-50 text-purple-700' : (($a['status'] ?? '') === 'interview' ? 'bg-indigo-50 text-indigo-700' : 'bg-yellow-50 text-yellow-700')))) ?>"><?= ucfirst(str_replace('_', ' ', $a['status'] ?? 'submitted')) ?></span></td>
                    <td class="px-6 py-3 text-xs text-gray-400"><?= isset($a['created_at']) ? date('d M Y', strtotime($a['created_at'])) : '-' ?></td>
                    <td class="px-6 py-3"><a href="<?= url('/admin/applications/' . $a['id']) ?>" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
