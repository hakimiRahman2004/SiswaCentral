<?php

namespace App\Core;

class Response
{
    public static function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function redirect(string $url, int $status = 302): void
    {
        // ponytail: prepend base path so redirects work from any subdirectory
        if (str_starts_with($url, '/')) {
            $base = rtrim(config('app.url'), '/');
            $url = $base . $url;
        }
        header("Location: $url", true, $status);
        exit;
    }

    public static function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirect($referer);
    }

    public static function setLayout(string $layout): void
    {
        $GLOBALS['_layout'] = $layout;
    }
}
