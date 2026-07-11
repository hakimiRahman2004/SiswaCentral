<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;

class AdminMiddleware
{
    public function handle(Request $request, callable $next): void
    {
        if (!Auth::check() || !Auth::is('admin')) {
            http_response_code(403);
            $title = 'Forbidden';
            $content = '<div class="text-center py-16"><h1 class="text-6xl font-bold text-gray-300">403</h1><p class="mt-4 text-lg text-gray-600">Access denied. Admin only.</p><a href="/" class="mt-6 inline-block text-blue-600 hover:underline">Go Home</a></div>';
            include __DIR__ . '/../Views/layouts/main.php';
            exit;
        }
        $next();
    }
}
