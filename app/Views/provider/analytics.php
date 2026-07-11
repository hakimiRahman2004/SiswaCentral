<?php
$title = 'Analytics';
$total = array_sum($statusCounts);
$approved = $statusCounts['approved'] ?? 0;
$approvalRate = $total > 0 ? round($approved / $total * 100) : 0;

// Prepare monthly chart data
$chartMonths = [];
$chartValues = [];
foreach ($months as $m) {
    $chartMonths[] = date('M', strtotime($m . '-01'));
    $chartValues[] = $monthlyData[$m] ?? 0;
}
$maxChart = max($chartValues) ?: 1;
?>
<div class="animate-fade-in max-w-6xl mx-auto">
    <div class="page-header">
        <h1 class="page-title">Analytics</h1>
        <p class="page-desc">Track your scholarship performance</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid gap-4 grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-primary-50 text-primary-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <div class="text-xs text-surface-500">Total Applications</div>
                    <div class="text-2xl font-bold font-heading text-surface-900"><?= e($total) ?></div>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-teal-50 text-teal-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <div>
                    <div class="text-xs text-surface-500">Total Scholarships</div>
                    <div class="text-2xl font-bold font-heading text-surface-900"><?= e($totalScholarships ?? 0) ?></div>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-success-50 text-success-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-xs text-success-600 font-medium">Approval Rate</div>
                    <div class="text-2xl font-bold font-heading text-surface-900"><?= e($approvalRate) ?>%</div>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <div class="text-xs text-surface-500">Rejected</div>
                    <div class="text-2xl font-bold font-heading text-surface-900"><?= e($statusCounts['rejected'] ?? 0) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart + Status Breakdown -->
    <div class="grid gap-6 lg:grid-cols-5 mb-6">
        <!-- Monthly Chart -->
        <div class="card p-5 lg:col-span-3">
            <h2 class="text-sm font-semibold text-surface-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Monthly Applications
            </h2>
            <div class="flex items-end gap-2 h-40 pt-2">
                <?php foreach ($chartMonths as $i => $label): $val = $chartValues[$i]; ?>
                <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                    <span class="text-[11px] font-semibold text-surface-600"><?= e($val ?: '') ?></span>
                    <div class="w-full rounded-lg transition-all duration-300 hover:opacity-80 cursor-pointer" style="height: <?= max(4, $val / $maxChart * 100) ?>%; background: linear-gradient(180deg, var(--color-primary-400), var(--color-primary-600)); min-height: 4px;"></div>
                    <span class="text-[10px] text-surface-400 font-medium"><?= e($label) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="card p-5 lg:col-span-2">
            <h2 class="text-sm font-semibold text-surface-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                Status Breakdown
            </h2>
            <div class="space-y-3">
                <?php
                $statusColors = [
                    'submitted' => ['bar' => 'bg-primary-400', 'dot' => 'bg-primary-400'],
                    'screening' => ['bar' => 'bg-warning-400', 'dot' => 'bg-warning-400'],
                    'shortlisted' => ['bar' => 'bg-accent-400', 'dot' => 'bg-accent-400'],
                    'interview' => ['bar' => 'bg-info-500', 'dot' => 'bg-info-500'],
                    'approved' => ['bar' => 'bg-success-500', 'dot' => 'bg-success-500'],
                    'rejected' => ['bar' => 'bg-danger-500', 'dot' => 'bg-danger-500'],
                ];
                $maxStatus = max($statusCounts) ?: 1;
                ?>
                <?php foreach ($statusCounts as $k => $v): $c = $statusColors[$k] ?? ['bar' => 'bg-surface-300', 'dot' => 'bg-surface-300']; ?>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full <?= $c['dot'] ?>"></span>
                            <span class="text-surface-600"><?= e(ucfirst($k)) ?></span>
                        </div>
                        <span class="font-semibold text-surface-800"><?= e($v) ?></span>
                    </div>
                    <div class="h-2 rounded-full bg-surface-100 overflow-hidden">
                        <div class="h-full rounded-full transition-all <?= $c['bar'] ?>" style="width: <?= $v / $maxStatus * 100 ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Top Scholarships -->
    <?php if (!empty($topScholarships)): ?>
    <div class="card p-5">
        <h2 class="text-sm font-semibold text-surface-800 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
            Top Scholarships
        </h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:48px">#</th>
                        <th>Scholarship</th>
                        <th class="text-center">Applicants</th>
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topScholarships as $i => $s): if ($i >= 5) break; ?>
                    <tr>
                        <td>
                            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold <?= match($i) { 0=>'bg-amber-100 text-amber-700', 1=>'bg-surface-150 text-surface-600', 2=>'bg-amber-50 text-amber-600', default=>'bg-surface-100 text-surface-500' } ?>"><?= $i + 1 ?></span>
                        </td>
                        <td class="font-medium text-surface-800"><?= e($s['name'] ?? '') ?></td>
                        <td class="text-center font-semibold text-surface-800"><?= e($s['applicants'] ?? 0) ?></td>
                        <td class="text-center text-surface-500 text-sm"><?= $s['amount'] ? 'RM' . e(number_format((float)$s['amount'])) : '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
