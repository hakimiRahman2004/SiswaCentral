<?php

if (!function_exists('env')) {
function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}
}

if (!function_exists('loadEnv')) {
function loadEnv(string $path): void
{
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) continue;

        $key = trim($parts[0]);
        $value = trim($parts[1]);

        $value = preg_replace('/^["\'](.*)["\']$/', '$1', $value);

        $_ENV[$key] = match (strtolower($value)) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => $value,
        };

        putenv($key . '=' . $_ENV[$key]);
    }
}
}
