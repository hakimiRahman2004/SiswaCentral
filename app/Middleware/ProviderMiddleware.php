<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Repositories\ProviderRepository;

class ProviderMiddleware
{
    public function handle(Request $request, callable $next): void
    {
        if (!Auth::check() || Auth::role() !== 'provider') {
            header('Location: /login');
            exit;
        }

        $repo = new ProviderRepository();
        $provider = $repo->findByUserId(Auth::id());

        if ($provider && $provider['status'] !== 'active') {
            http_response_code(403);

            // Check if provider has completed onboarding
            $needsOnboarding = empty($provider['registration_no']) || empty($provider['phone']) || empty($provider['address']);
            $heading = $needsOnboarding ? 'Complete Your Profile' : 'Account Pending Approval';
            $message = $needsOnboarding
                ? 'Please complete your organization profile and upload your verification document before an admin can review your account.'
                : 'Your provider account is awaiting verification by an administrator. You will be notified once your documents have been reviewed.';
            $actionLink = $needsOnboarding ? '/provider/onboarding' : '/logout';
            $actionText = $needsOnboarding ? 'Complete Profile' : 'Sign Out';
            $actionClass = $needsOnboarding ? 'text-white bg-primary-600 hover:bg-primary-700' : 'text-gray-700 bg-gray-100 hover:bg-gray-200';

            $content = '<div class="flex items-center justify-center min-h-screen bg-gray-50"><div class="max-w-md p-8 text-center bg-white rounded-xl shadow-sm"><div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100"><svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><h1 class="mt-4 text-xl font-bold text-gray-900">' . $heading . '</h1><p class="mt-2 text-sm text-gray-500">' . $message . '</p><a href="' . $actionLink . '" class="inline-block mt-6 px-4 py-2 text-sm font-medium rounded-lg ' . $actionClass . '">' . $actionText . '</a></div></div>';
            require __DIR__ . '/../Views/layouts/clean.php';
            exit;
        }

        $next();
    }
}
