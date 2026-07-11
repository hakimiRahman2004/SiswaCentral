<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request, callable $next): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        $next();
    }
}
