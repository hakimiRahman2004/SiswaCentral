<?php $title = 'Analytics'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Analytics</h1><p class="page-desc">Platform metrics and insights</p></div>

    <form method="GET" action="<?= url('/admin/analytics') ?>" class="filter-bar">
        <label class="text-sm text-surface-600">From:</label>
        <input type="date" name="start_date" value="<?= e($startDate ?? '') ?>" class="input" style="width:auto">
        <label class="text-sm text-surface-600">To:</label>
        <input type="date" name="end_date" value="<?= e($endDate ?? '') ?>" class="input" style="width:auto">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <?php if ($startDate || $endDate): ?>
        <a href="<?= url('/admin/analytics') ?>" class="btn btn-ghost btn-sm">Clear</a>
        <?php endif; ?>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="stat-card"><div class="stat-value"><?= e($totalUsers??0) ?></div><div class="stat-label">Users</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($totalScholarships??0) ?></div><div class="stat-label">Scholarships</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($totalApplications??0) ?></div><div class="stat-label">Applications</div></div>
        <div class="stat-card"><div class="stat-value">RM<?= e(number_format($totalRevenue??0, 0)) ?></div><div class="stat-label">Revenue</div></div>
    </div>

    <?php if (!empty($userGrowth)): ?>
    <div class="card-hover p-5 mb-6"><h2 class="font-semibold text-surface-800 text-sm mb-4">User Growth</h2><canvas id="userGrowthChart" height="100"></canvas></div>
    <?php endif; ?>
    <?php if (!empty($applicationGrowth)): ?>
    <div class="card-hover p-5 mb-6"><h2 class="font-semibold text-surface-800 text-sm mb-4">Application Trends</h2><canvas id="appGrowthChart" height="100"></canvas></div>
    <?php endif; ?>
    <?php if (!empty($revenueTrend)): ?>
    <div class="card-hover p-5 mb-6"><h2 class="font-semibold text-surface-800 text-sm mb-4">Revenue Trend</h2><canvas id="revenueChart" height="100"></canvas></div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($userGrowth)): ?>new Chart(document.getElementById('userGrowthChart'),{type:'line',data:{labels:<?= json_encode(array_column($userGrowth,'month')) ?>,datasets:[{label:'Users',data:<?= json_encode(array_column($userGrowth,'total')) ?>,borderColor:'#0d9488',backgroundColor:'rgba(13,148,136,0.1)',fill:true,tension:0.3}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});<?php endif; ?>
<?php if (!empty($applicationGrowth)): ?>new Chart(document.getElementById('appGrowthChart'),{type:'line',data:{labels:<?= json_encode(array_column($applicationGrowth,'month')) ?>,datasets:[{label:'Applications',data:<?= json_encode(array_column($applicationGrowth,'total')) ?>,borderColor:'#d97706',backgroundColor:'rgba(245,158,11,0.1)',fill:true,tension:0.3}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});<?php endif; ?>
<?php if (!empty($revenueTrend)): ?>new Chart(document.getElementById('revenueChart'),{type:'line',data:{labels:<?= json_encode(array_column($revenueTrend,'month')) ?>,datasets:[{label:'Revenue',data:<?= json_encode(array_column($revenueTrend,'total')) ?>,borderColor:'#059669',backgroundColor:'rgba(5,150,105,0.1)',fill:true,tension:0.3}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{callback:function(v){return'RM'+v.toLocaleString()}}}}}});<?php endif; ?>
</script>