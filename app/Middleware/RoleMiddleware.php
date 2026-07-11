<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;

class RoleMiddleware
{
    private string $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function handle(Request $request, callable $next): void
    {
        if (!Auth::check() || !Auth::is($this->role)) {
            http_response_code(403);
            include __DIR__ . '/../Views/errors/403.php';
            exit;
        }
        $next();
    }
}
