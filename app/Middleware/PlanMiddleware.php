<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Helpers\PlanGuard;

class PlanMiddleware
{
    private string $feature;

    public function __construct(string $feature = 'premium')
    {
        $this->feature = $feature;
    }

    public function handle(Request $request, callable $next): void
    {
        if (!Auth::check() || Auth::role() !== 'student') {
            $next();
            return;
        }

        match ($this->feature) {
            'premium' => PlanGuard::requirePremium(),
            default => null,
        };

        $next();
    }
}
