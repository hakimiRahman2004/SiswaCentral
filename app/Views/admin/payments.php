<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Payment History</h1>
        <span class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg font-medium"><?= count($payments) ?> payments</span>
    </div>

    <?php if (empty($payments)): ?>
        <div class="p-12 text-center bg-white rounded-xl shadow-sm border border-gray-100">
            <p class="text-gray-500">No payments yet.</p>
        </div>
    <?php else: ?>
        <div class="overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">All Transactions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Provider</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Plan</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($payments as $pay): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= e($pay['company_name']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= e($pay['plan_name'] ?? '-') ?></td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">RM <?= number_format($pay['amount'], 2) ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-semibold rounded-full <?= $pay['status'] === 'success' ? 'bg-green-50 text-green-700' : ($pay['status'] === 'pending' ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $pay['status'] === 'success' ? 'bg-green-500' : ($pay['status'] === 'pending' ? 'bg-yellow-500' : 'bg-red-500') ?>"></span>
                                        <?= ucfirst($pay['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= date('d M Y', strtotime($pay['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
