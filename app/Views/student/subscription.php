<?php
/** @var array<string, mixed>|null $subscription */
/** @var array<int, array<string, mixed>> $plans */
/** @var array<int, array<string, mixed>>|null $history */
/** @var bool|null $expired */
$title = 'Subscription';
$basic = null;
$premium = null;
foreach ($plans as $p) {
    if (($p['price'] ?? 999) == 0) $basic = $p;
    else $premium = $p;
}
$isPremium = ($subscription['plan_name'] ?? '') === 'Pro' || ($subscription['plan_name'] ?? '') === 'Premium';
?>
<div class="animate-fade-in max-w-4xl mx-auto">
    <div class="page-header">
        <h1 class="page-title">Subscription</h1>
        <p class="page-desc">Choose the plan that fits your needs</p>
    </div>

    <?php if (isset($expired) && $expired && !$subscription): ?>
        <div class="alert alert-warning" role="alert">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            <span>Your subscription has expired. Choose a plan below to continue.</span>
        </div>
    <?php endif; ?>

    <?php if ($subscription): ?>
    <div class="card p-5 mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-surface-500">Current Plan</p>
                    <p class="font-semibold text-surface-800 text-lg"><?= e($subscription['plan_name'] ?? 'Basic') ?></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge <?= $isPremium ? 'badge-primary' : 'badge-surface' ?> badge-lg">
                    <?= $isPremium ? 'Premium' : 'Basic' ?>
                </span>
                <?php if ($isPremium): ?>
                <form method="POST" action="<?= url('/subscription/cancel') ?>" onsubmit="return confirm('Cancel your Premium subscription? You will be reverted to the Basic plan.');">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-ghost btn-sm text-danger-600 hover:bg-danger-50">Cancel Premium</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($isPremium): ?>
        <div class="mt-3 pt-3 border-t border-surface-100 flex items-center gap-2 text-sm text-surface-500">
            <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4.5 12.75 6 6 9-13.5"/></svg>
            <span>Your Premium features are active until <strong><?= date('d M Y', strtotime($subscription['end_date'])) ?></strong></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Plans Grid: Basic vs Premium -->
    <div class="grid gap-6 sm:grid-cols-2 mb-10">
        <?php foreach ([$basic, $premium] as $plan): if (!$plan) continue;
            $features = json_decode($plan['features'] ?? '[]', true) ?? [];
            $isPlanPremium = ($plan['price'] ?? 0) > 0;
            $isCurrentPlan = ($subscription['plan_id'] ?? null) == $plan['id'];
        ?>
        <div class="pricing-card flex flex-col <?= $isPlanPremium ? 'featured' : '' ?> relative">
            <?php if ($isPlanPremium): ?>
                <div class="pricing-badge">Most Popular</div>
            <?php endif; ?>

            <!-- Plan header -->
            <div class="mb-5 text-center">
                <h3 class="text-xl font-bold font-heading text-surface-900"><?= e($plan['name'] ?? '') ?></h3>
                <div class="mt-4 flex items-baseline justify-center gap-1">
                    <span class="text-4xl font-extrabold font-heading text-surface-900 tracking-tight">
                        <?php if ($plan['price'] > 0): ?>RM<?= e(number_format($plan['price'], 2)) ?><?php else: ?>Free<?php endif; ?>
                    </span>
                    <?php if ($plan['price'] > 0): ?><span class="text-sm text-surface-400">/month</span><?php endif; ?>
                </div>
                <p class="text-xs text-surface-500 mt-1"><?= $plan['price'] > 0 ? 'Billed monthly' : 'Free forever' ?></p>
            </div>

            <!-- Key metrics -->
            <div class="grid grid-cols-2 gap-3 mb-5 px-2">
                <div class="text-center p-2 rounded-lg bg-surface-50">
                    <div class="text-lg font-bold font-heading text-surface-800"><?= (int)($plan['max_applications'] ?? 0) >= 999 ? '∞' : e($plan['max_applications'] ?? '0') ?></div>
                    <div class="text-xs text-surface-500">Applications</div>
                </div>
                <div class="text-center p-2 rounded-lg bg-surface-50">
                    <div class="text-lg font-bold font-heading text-surface-800"><?= (int)($plan['max_documents'] ?? 0) >= 99 ? '∞' : e($plan['max_documents'] ?? '0') ?></div>
                    <div class="text-xs text-surface-500">Documents</div>
                </div>
            </div>

            <!-- Features list -->
            <ul class="space-y-2.5 flex-1 mb-6">
                <?php foreach ($features as $feat): ?>
                <li class="flex items-start gap-2.5 text-sm">
                    <?php if (is_string($feat)): ?>
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        <span class="text-surface-700"><?= e($feat) ?></span>
                    <?php elseif ($feat['enabled'] ?? false): ?>
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        <span class="text-surface-700"><?= e($feat['name'] ?? '') ?></span>
                    <?php else: ?>
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18 18 6M6 6l12 12"/></svg>
                        <span class="text-surface-400"><?= e($feat['name'] ?? '') ?></span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>

                <!-- Extra premium-only items visible only on Basic card -->
                <?php if (!$isPlanPremium && $premium): $premFeats = json_decode($premium['features'] ?? '[]', true) ?? []; ?>
                    <?php foreach ($premFeats as $pf): $pfName = is_string($pf) ? $pf : ($pf['name'] ?? ''); if (!$pfName) continue; ?>
                    <?php
                        // Skip if already listed in basic
                        $alreadyListed = false;
                        foreach ($features as $bf) { if ((is_string($bf) ? $bf : ($bf['name'] ?? '')) === $pfName) { $alreadyListed = true; break; } }
                        if ($alreadyListed) continue;
                    ?>
                    <li class="flex items-start gap-2.5 text-sm">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18 18 6M6 6l12 12"/></svg>
                        <span class="text-surface-400"><?= e($pfName) ?></span>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <!-- Action button -->
            <?php if ($isCurrentPlan): ?>
                <div class="text-center">
                    <span class="btn btn-outline w-full text-center cursor-default opacity-60">Current Plan</span>
                </div>
            <?php elseif ($isPlanPremium): ?>
                <form method="POST" action="<?= url('/subscription/subscribe') ?>">
                    <?= csrfField() ?>
                    <input type="hidden" name="plan_id" value="<?= e($plan['id']) ?>">
                    <button type="submit" class="btn btn-primary w-full btn-lg text-base font-bold shadow-lg shadow-primary-500/20 hover:shadow-xl hover:shadow-primary-500/25 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5z"/></svg>
                        Subscribe Now — RM<?= e(number_format($plan['price'], 2)) ?>/mo
                    </button>
                </form>
            <?php else: ?>
                <form method="POST" action="<?= url('/subscription/subscribe') ?>">
                    <?= csrfField() ?>
                    <input type="hidden" name="plan_id" value="<?= e($plan['id']) ?>">
                    <button type="submit" class="btn btn-outline w-full btn-lg">Get Started Free</button>
                </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Feature Comparison Table -->
    <?php if ($basic && $premium):
        $stuRows = [
            ['Monthly Applications', fn($p) => ($p['max_applications'] ?? 0) >= 999 ? 'Unlimited' : ($p['max_applications'] ?? 0)],
            ['Document Uploads', fn($p) => ($p['max_documents'] ?? 0) >= 99 ? 'Unlimited' : ($p['max_documents'] ?? 0)],
            ['Smart Matching', fn($p) => ($p['has_smart_matching'] ?? 0) ? true : false],
            ['Priority Support', fn($p) => ($p['has_priority_support'] ?? 0) ? true : false],
        ];
        $both = [$basic, $premium];
        // Collect all unique features from both plans (from JSON features array)
        $allStuFeats = [];
        foreach ($both as $p) {
            $feats = json_decode($p['features'] ?? '[]', true) ?? [];
            foreach ($feats as $f) {
                $name = is_string($f) ? $f : ($f['name'] ?? '');
                if ($name && !in_array($name, $allStuFeats)) $allStuFeats[] = $name;
            }
        }
        $basicFeats = json_decode($basic['features'] ?? '[]', true) ?? [];
        $basicFeatNames = array_map(fn($f) => is_string($f) ? $f : ($f['name'] ?? ''), $basicFeats);
        $premFeats = json_decode($premium['features'] ?? '[]', true) ?? [];
        $premFeatNames = array_map(fn($f) => is_string($f) ? $f : ($f['name'] ?? ''), $premFeats);
    ?>
    <div class="card p-5 mb-8">
        <h2 class="section-title mb-4">Compare Plans</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40%">Feature</th>
                        <th style="width:30%" class="text-center"><?= e($basic['name'] ?? 'Basic') ?></th>
                        <th style="width:30%" class="text-center"><?= e($premium['name'] ?? 'Premium') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stuRows as $r): ?>
                    <tr>
                        <td class="font-medium text-surface-800"><?= e($r[0]) ?></td>
                        <td class="text-center text-sm text-surface-700"><?= renderCheckStu($r[1]($basic)) ?></td>
                        <td class="text-center text-sm text-surface-700"><?= renderCheckStu($r[1]($premium)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- Features from DB -->
                    <tr>
                        <td class="font-medium text-surface-800 pt-4 border-t border-surface-100">Included Features</td>
                        <?php foreach ([$basicFeatNames, $premFeatNames] as $featNames): ?>
                        <td class="pt-4 border-t border-surface-100">
                            <ul class="space-y-1.5">
                                <?php foreach ($featNames as $fn): if (!$fn) continue; ?>
                                <li class="flex items-center gap-1.5 text-xs text-surface-600">
                                    <svg class="w-3 h-3 shrink-0 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    <?= e($fn) ?>
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

    <!-- Subscription History -->
    <?php if (!empty($history)): ?>
    <div class="card p-5">
        <h2 class="section-title mb-4">Subscription History</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Plan</th><th>Status</th><th>Start</th><th>End</th></tr></thead>
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
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
function renderCheckStu($val): string {
    if ($val === true) {
        return '<svg class="w-5 h-5 mx-auto text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/></svg>';
    }
    if ($val === false || $val === '' || $val === null) {
        return '<svg class="w-5 h-5 mx-auto text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18 18 6M6 6l12 12"/></svg>';
    }
    return '<span class="text-sm text-surface-700">' . e((string)$val) . '</span>';
}
?>
