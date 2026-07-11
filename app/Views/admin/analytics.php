<?php $title = 'Analytics'; ?>
<div class="animate-fade-in">
    <div class="page-header"><h1 class="page-title">Analytics</h1><p class="page-desc">Platform metrics and insights</p></div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="stat-card"><div class="stat-value"><?= e($totalUsers??0) ?></div><div class="stat-label">Users</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($totalScholarships??0) ?></div><div class="stat-label">Scholarships</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($totalApplications??0) ?></div><div class="stat-label">Applications</div></div>
        <div class="stat-card"><div class="stat-value"><?= e($totalRevenue ?? 0) ?>%</div><div class="stat-label">Conversion Rate</div></div>
    </div>
    <?php if (!empty($userGrowth)): ?>
    <div class="card-hover p-5 mb-6"><h2 class="font-semibold text-surface-800 text-sm mb-4">User Growth</h2><canvas id="userGrowthChart" height="100"></canvas></div>
    <?php endif; ?>
    <?php if (!empty($applicationGrowth)): ?>
    <div class="card-hover p-5 mb-6"><h2 class="font-semibold text-surface-800 text-sm mb-4">Application Trends</h2><canvas id="appGrowthChart" height="100"></canvas></div>
    <?php endif; ?>
</div>
<?php if (!empty($userGrowth) || !empty($applicationGrowth)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($userGrowth)): ?>new Chart(document.getElementById('userGrowthChart'),{type:'line',data:{labels:<?= json_encode(array_column($userGrowth,'month')) ?>,datasets:[{label:'Users',data:<?= json_encode(array_column($userGrowth,'count')) ?>,borderColor:'#0d9488',backgroundColor:'rgba(13,148,136,0.1)',fill:true,tension:0.3}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});<?php endif; ?>
<?php if (!empty($applicationGrowth)): ?>new Chart(document.getElementById('appGrowthChart'),{type:'line',data:{labels:<?= json_encode(array_column($applicationGrowth,'month')) ?>,datasets:[{label:'Applications',data:<?= json_encode(array_column($applicationGrowth,'count')) ?>,borderColor:'#d97706',backgroundColor:'rgba(245,158,11,0.1)',fill:true,tension:0.3}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});<?php endif; ?>
</script>
<?php endif; ?>