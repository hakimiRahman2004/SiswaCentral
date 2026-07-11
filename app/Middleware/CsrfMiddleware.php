<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request, callable $next): void
    {
        if ($request->method() !== 'GET') {
            $token = $request->post('_token');
            $sessionToken = Session::get('_token');

            if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
                http_response_code(419);
                die('CSRF token mismatch');
            }
        }
        $next();
    }
}
