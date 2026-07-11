<?php
$title = 'Subscription';
$starter = null;
$professional = null;
$enterprise = null;
foreach ($plans as $p) {
    if (((int)($p['price'] ?? 999)) === 0) $starter = $p;
    elseif ((int)($p['price'] ?? 0) < 200) $professional = $p;
    else $enterprise = $p;
}
$activePrice = $currentSub['plan_price'] ?? 0;
$isPaid = $activePrice > 0;
?>
<div class="animate-fade-in max-w-4xl mx-auto">
    <div class="page-header">
        <h1 class="page-title">Subscription</h1>
        <p class="page-desc">Choose a plan that fits your organization</p>
    </div>

    <?php if ($flash = flash('success')): ?>
        <div class="alert alert-success" role="alert"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span><?= e($flash) ?></span></div>
    <?php endif; ?>
    <?php if ($flash = flash('error')): ?>
        <div class="alert alert-error" role="alert"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span><?= e($flash) ?></span></div>
    <?php endif; ?>

    <?php if ($currentSub): ?>
    <div class="card p-5 mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-surface-500">Current Plan</p>
                    <p class="font-semibold text-surface-800 text-lg"><?= e($currentSub['plan_name'] ?? 'Starter') ?></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge <?= ($currentSub['status'] ?? '') === 'active' ? 'badge-success' : 'badge-surface' ?> badge-lg">
                    <?= e(ucfirst($currentSub['status'] ?? 'active')) ?>
                </span>
                <?php if ($isPaid): ?>
                <form method="POST" action="<?= url('/provider/subscription/cancel') ?>" onsubmit="return confirm('Cancel your subscription? You will be reverted to the Starter plan.');">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-ghost btn-sm text-danger-600 hover:bg-danger-50">Cancel Plan</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($isPaid && !empty($currentSub['end_date'])): ?>
        <div class="mt-3 pt-3 border-t border-surface-100 flex items-center gap-2 text-sm text-surface-500">
            <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4.5 12.75 6 6 9-13.5"/></svg>
            <span>Your plan is active until <strong><?= date('d M Y', strtotime($currentSub['end_date'])) ?></strong></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Plans Grid -->
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3 mb-10 items-stretch">
        <?php foreach ([$starter, $professional, $enterprise] as $i => $plan): if (!$plan) continue;
            $isPlanPremium = ($plan['price'] ?? 0) > 0;
            $isCurrentPlan = ($currentSub['plan_id'] ?? null) == $plan['id'];
            $isRecommended = $i === 1;
        ?>
        <div class="card-hover flex flex-col relative <?= $isRecommended ? 'ring-2 ring-primary-600 shadow-lg' : '' ?>">
            <?php if ($isRecommended): ?>
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary-800 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-1 rounded-full shadow whitespace-nowrap z-10">Most Popular</div>
            <?php endif; ?>

            <!-- Header -->
            <div class="p-6 pb-0 text-center <?= $isRecommended ? 'pt-9' : '' ?>">
                <div class="mx-auto w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                    <?php if ($i === 0): ?>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                    <?php elseif ($i === 1): ?>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75z"/></svg>
                    <?php else: ?>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
                    <?php endif; ?>
                </div>

                <h3 class="text-lg font-bold font-heading text-surface-900"><?= e($plan['name'] ?? '') ?></h3>
                <div class="mt-3 flex items-baseline justify-center gap-1">
                    <span class="text-4xl font-extrabold font-heading tracking-tight text-surface-900">
                        <?php if ($plan['price'] > 0): ?>RM<?= e(number_format($plan['price'], 2)) ?><?php else: ?>Free<?php endif; ?>
                    </span>
                    <?php if ($plan['price'] > 0): ?><span class="text-sm text-surface-400 font-medium">/month</span><?php endif; ?>
                </div>
                <p class="text-xs text-surface-400 mt-1"><?= $plan['price'] > 0 ? 'Billed monthly · Cancel anytime' : 'Free forever · No card needed' ?></p>
            </div>

            <!-- Divider -->
            <div class="mx-6 mt-5 border-t border-surface-100"></div>

            <!-- Key metrics -->
            <div class="grid grid-cols-2 gap-2 mx-6 mt-5">
                <div class="text-center p-3 rounded-xl bg-surface-50">
                    <div class="text-xl font-bold font-heading text-surface-800"><?= (int)($plan['max_scholarships'] ?? 0) >= 999 ? '∞' : e($plan['max_scholarships'] ?? '0') ?></div>
                    <div class="text-xs text-surface-500 font-medium mt-0.5">Scholarships</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-surface-50">
                    <div class="text-xl font-bold font-heading text-surface-800"><?= (int)($plan['max_staff'] ?? 0) >= 20 ? '∞' : e($plan['max_staff'] ?? '0') ?></div>
                    <div class="text-xs text-surface-500 font-medium mt-0.5">Staff</div>
                </div>
            </div>

            <!-- Features -->
            <ul class="space-y-2.5 mx-6 mt-5 flex-1">
                <?php $features = array_map('trim', explode(',', $plan['features'] ?? '')); ?>
                <?php foreach ($features as $feat): if (!$feat) continue; ?>
                <li class="flex items-start gap-3 text-sm">
                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    <span class="text-surface-600"><?= e($feat) ?></span>
                </li>
                <?php endforeach; ?>
            </ul>

            <!-- Action -->
            <div class="p-6 pt-5 mt-5 border-t border-surface-100">
                <?php if ($isCurrentPlan): ?>
                    <div class="btn btn-outline w-full text-center cursor-default opacity-60">Current Plan</div>
                <?php elseif ($isPlanPremium): ?>
                    <form method="POST" action="<?= url('/provider/subscription/subscribe') ?>">
                        <?= csrfField() ?>
                        <input type="hidden" name="plan_id" value="<?= e($plan['id']) ?>">
                        <button type="submit" class="btn btn-primary w-full">
                            Subscribe — RM<?= e(number_format($plan['price'], 2)) ?>/mo
                        </button>
                    </form>
                <?php else: ?>
                    <form method="POST" action="<?= url('/provider/subscription/subscribe') ?>">
                        <?= csrfField() ?>
                        <input type="hidden" name="plan_id" value="<?= e($plan['id']) ?>">
                        <button type="submit" class="btn btn-outline w-full">Get Started Free</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Feature Comparison Table -->
    <?php if ($starter && $professional && $enterprise):
        $allPlans = [$starter, $professional, $enterprise];
        $planLabels = array_map(fn($p) => $p['name'] ?? 'Plan', $allPlans);
        $numericRows = [
            ['Monthly Price', fn($p) => $p['price'] > 0 ? 'RM'.number_format($p['price'], 2).'/mo' : 'Free'],
            ['Duration', fn($p) => ($p['duration_days'] ?? 30).' days'],
            ['Max Scholarships', fn($p) => ($p['max_scholarships'] ?? 0) >= 999 ? 'Unlimited' : ($p['max_scholarships'] ?? 0)],
            ['Staff Accounts', fn($p) => ($p['max_staff'] ?? 0) >= 20 ? 'Unlimited' : ($p['max_staff'] ?? 0)],
        ];
    ?>
    <div class="card p-5 mb-8">
        <h2 class="section-title mb-4">Compare Plans</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:24%">Feature</th>
                        <?php foreach ($planLabels as $i => $label): ?>
                        <th style="width:<?= round(76/3) ?>%" class="text-center"><?= $label ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($numericRows as $r): ?>
                    <tr>
                        <td class="font-medium text-surface-800"><?= e($r[0]) ?></td>
                        <?php foreach ($allPlans as $p): ?>
                        <td class="text-center text-sm text-surface-700"><?= $r[1]($p) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="font-medium text-surface-800 pt-4 border-t border-surface-100">Included Features</td>
                        <?php foreach ($allPlans as $p):
                            $feats = array_map('trim', explode(',', $p['features'] ?? ''));
                        ?>
                        <td class="pt-4 border-t border-surface-100">
                            <ul class="space-y-1.5">
                                <?php foreach ($feats as $f): if (!$f) continue; ?>
                                <li class="flex items-center gap-1.5 text-xs text-surface-600">
                                    <svg class="w-3 h-3 shrink-0 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    <?= e($f) ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- History -->
    <?php if (!empty($history)): ?>
    <div class="card p-5">
        <h2 class="section-title mb-4">Subscription History</h2>
        <div class="table-wrap">
            <table><thead><tr><th>Plan</th><th>Status</th><th>Start</th><th>End</th></tr></thead>
            <tbody>
                <?php foreach ($history as $h): ?>
                <tr>
                    <td class="font-medium text-surface-800"><?= e($h['plan_name'] ?? '') ?></td>
                    <td><?php $hs = $h['status'] ?? ''; $hsc = match($hs) {'active'=> 'badge-success','expired'=> 'badge-surface','cancelled'=>'badge-danger', default=>'badge-info' }; ?>
                        <span class="badge <?= $hsc ?>"><?= e(ucfirst($hs)) ?></span>
                    </td>
                    <td class="text-surface-500 text-xs"><?= date('d M Y', strtotime($h['start_date'])) ?></td>
                    <td class="text-surface-500 text-xs"><?= $h['end_date'] ? date('d M Y', strtotime($h['end_date'])) : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody></table>
        </div>
    </div>
    <?php endif; ?>
</div>
