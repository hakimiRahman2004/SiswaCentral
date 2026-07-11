<?php

namespace App\Helpers;

use App\Core\Auth;
use App\Core\Session;
use App\Core\Response;
use App\Repositories\ProviderRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\ScholarshipRepository;
use App\Repositories\StaffRepository;

class ProviderGuard
{
    private static function repo(): SubscriptionRepository
    {
        return new SubscriptionRepository();
    }

    private static function provider(): array
    {
        return (new ProviderRepository())->findByUserId(Auth::id());
    }

    public static function getPlan(): array
    {
        self::expireStale();
        $provider = self::provider();
        $sub = self::repo()->findActiveByProvider($provider['id']);
        if (!$sub) {
            $plans = self::repo()->getAllPlans();
            $free = array_filter($plans, fn($p) => $p['price'] <= 0);
            $free = reset($free);
            return [
                'plan' => $free ?: ['id' => 0, 'name' => 'None', 'price' => 0, 'max_scholarships' => 0, 'max_staff' => 0],
                'subscription' => null,
                'is_active' => false,
            ];
        }
        $plan = self::repo()->findPlanById($sub['plan_id']);
        return [
            'plan' => $plan,
            'subscription' => $sub,
            'is_active' => $sub['status'] === 'active' && (!$sub['end_date'] || $sub['end_date'] >= date('Y-m-d')),
        ];
    }

    public static function usage(): array
    {
        $planInfo = self::getPlan();
        $plan = $planInfo['plan'];
        $provider = self::provider();
        $scholarshipCount = (new ScholarshipRepository())->countByProvider($provider['id']);
        $staffCount = count((new StaffRepository())->findByProvider($provider['id']));

        return [
            'plan' => $plan,
            'subscription' => $planInfo['subscription'],
            'is_active' => $planInfo['is_active'],
            'scholarships_used' => $scholarshipCount,
            'scholarships_max' => $plan['max_scholarships'] ?? 0,
            'staff_used' => $staffCount,
            'staff_max' => $plan['max_staff'] ?? 0,
            'can_create_scholarship' => $plan['max_scholarships'] <= 0 || $scholarshipCount < $plan['max_scholarships'],
            'can_invite_staff' => $plan['max_staff'] <= 0 || $staffCount < $plan['max_staff'],
        ];
    }

    public static function requireActiveSub(): void
    {
        $planInfo = self::getPlan();
        if (!$planInfo['is_active']) {
            Session::flash('error', 'Your subscription has expired. Please renew to continue.');
            Response::redirect('/provider/subscription');
            exit;
        }
    }

    public static function canCreateScholarship(): bool
    {
        $u = self::usage();
        if (!$u['can_create_scholarship']) {
            Session::flash('error', 'You have reached the maximum number of scholarships allowed by your plan. Upgrade to create more.');
            Response::back();
            exit;
        }
        return true;
    }

    public static function canInviteStaff(): bool
    {
        $u = self::usage();
        if (!$u['can_invite_staff']) {
            Session::flash('error', 'You have reached the maximum number of staff allowed by your plan. Upgrade to add more.');
            Response::back();
            exit;
        }
        return true;
    }

    public static function isPremium(): bool
    {
        $planInfo = self::getPlan();
        return $planInfo['is_active'] && ($planInfo['plan']['price'] ?? 0) > 0;
    }

    private static function expireStale(): void
    {
        $subRepo = self::repo();
        $expired = $subRepo->getExpiredSubscriptions();
        foreach ($expired as $sub) {
            $subRepo->update($sub['id'], ['status' => 'expired']);
        }
    }
}
