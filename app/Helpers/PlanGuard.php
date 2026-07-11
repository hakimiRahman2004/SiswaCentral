<?php

namespace App\Helpers;

use App\Core\Auth;
use App\Core\Session;
use App\Core\Response;
use App\Repositories\StudentSubscriptionRepository;

class PlanGuard
{
    private static ?StudentSubscriptionRepository $repo = null;

    private static function repo(): StudentSubscriptionRepository
    {
        if (self::$repo === null) {
            self::$repo = new StudentSubscriptionRepository();
        }
        return self::$repo;
    }

    /** Get the user's active subscription, defaulting to Basic if none. */
    public static function getPlan(): array
    {
        $sub = self::repo()->getActiveSubscription(Auth::id());
        if ($sub) return $sub;

        // Auto-mark expired subs so they don't linger as "active"
        self::repo()->expireStale(Auth::id());

        $default = self::repo()->getDefaultPlan();
        return $default ? [
            'plan_name' => $default['name'],
            'plan_id' => $default['id'],
            'max_applications' => $default['max_applications'],
            'max_documents' => $default['max_documents'],
            'has_smart_matching' => $default['has_smart_matching'],
            'has_priority_support' => $default['has_priority_support'],
            'price' => $default['price'],
        ] : [
            'plan_name' => 'Basic',
            'max_applications' => 3,
            'max_documents' => 5,
            'has_smart_matching' => false,
            'has_priority_support' => false,
        ];
    }

    /** Has the user paid for a plan (any plan with price > 0)? */
    public static function isPremium(): bool
    {
        $plan = self::getPlan();
        return ($plan['price'] ?? 0) > 0;
    }

    /** Is the user on the free Basic plan? */
    public static function isBasic(): bool
    {
        return !self::isPremium();
    }

    // ─── Feature checks ───

    /** Check if user can apply to more scholarships this month. Redirects on failure. */
    public static function canApply(): bool
    {
        $plan = self::getPlan();
        $used = self::repo()->getApplicationCountThisMonth(Auth::id());

        if ($used >= ($plan['max_applications'] ?? 3)) {
            Session::flash('error', 'You have reached your monthly application limit. <a href="/subscription" class="underline font-semibold">Upgrade to Premium</a> to continue.');
            Response::redirect('/subscription');
        }
        return true;
    }

    /** Check if user can upload more documents. Redirects on failure. */
    public static function canUploadDocument(): bool
    {
        $plan = self::getPlan();
        $used = self::repo()->getDocumentCount(Auth::id());

        if ($used >= ($plan['max_documents'] ?? 5)) {
            Session::flash('error', 'You have reached your document upload limit. <a href="/subscription" class="underline font-semibold">Upgrade to Premium</a> for unlimited uploads.');
            Response::redirect('/subscription');
        }
        return true;
    }

    /** Check if user has smart matching access. Returns bool (no redirect — caller decides). */
    public static function hasMatching(): bool
    {
        $plan = self::getPlan();
        return (bool) ($plan['has_smart_matching'] ?? false);
    }

    /** Redirect if user doesn't have Premium, used for guard middleware. */
    public static function requirePremium(): void
    {
        if (!self::isPremium()) {
            Session::flash('error', 'This feature requires Premium plan. <a href="/subscription" class="underline font-semibold">Upgrade now</a>.');
            Response::redirect('/subscription');
        }
    }

    // ─── Stats ───

    /** Get usage vs limit summary for the dashboard. */
    public static function usage(): array
    {
        $plan = self::getPlan();
        return [
            'plan' => $plan['plan_name'],
            'isPremium' => self::isPremium(),
            'applications' => [
                'used' => self::repo()->getApplicationCountThisMonth(Auth::id()),
                'max' => $plan['max_applications'] ?? 3,
            ],
            'documents' => [
                'used' => self::repo()->getDocumentCount(Auth::id()),
                'max' => $plan['max_documents'] ?? 5,
            ],
            'hasMatching' => (bool) ($plan['has_smart_matching'] ?? false),
        ];
    }
}
