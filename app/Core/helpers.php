<?php

if (!function_exists('config')) {
function config(string $key, mixed $default = null): mixed
{
    $parts = explode('.', $key, 2);
    $file = $parts[0];
    $path = __DIR__ . "/../../config/{$file}.php";
    if (!file_exists($path)) return $default;
    $cfg = require $path;
    if (!isset($parts[1])) return $cfg;
    foreach (explode('.', $parts[1]) as $k) {
        if (!is_array($cfg) || !array_key_exists($k, $cfg)) return $default;
        $cfg = $cfg[$k];
    }
    return $cfg;
}
}

if (!function_exists('view')) {
function view(string $view, array $data = [], string $layout = 'main'): void
{
    $c = new class extends \App\Core\Controller {};
    $c->view($view, $data, $layout);
}
}

if (!function_exists('asset')) {
function asset(string $path): string
{
    return rtrim(config('app.url'), '/') . '/' . ltrim($path, '/');
}
}

if (!function_exists('url')) {
function url(string $path): string
{
    $base = rtrim(config('app.url'), '/');
    return $base . '/' . ltrim($path, '/');
}
}

if (!function_exists('old')) {
function old(string $key, mixed $default = null): mixed
{
    return \App\Core\Session::old($key, $default);
}
}

if (!function_exists('error')) {
function error(string $field): ?string
{
    $errors = \App\Core\Session::errors();
    return $errors[$field][0] ?? null;
}
}

if (!function_exists('flash')) {
function flash(?string $key = null, ?string $value = null): mixed
{
    return \App\Core\Session::flash($key, $value);
}
}

if (!function_exists('csrf')) {
function csrf(): string
{
    if (!\App\Core\Session::has('_token')) {
        \App\Core\Session::set('_token', bin2hex(random_bytes(32)));
    }
    return '<input type="hidden" name="_token" value="' . \App\Core\Session::get('_token') . '">';
}
}

if (!function_exists('csrfField')) {
function csrfField(): string
{
    return csrf();
}
}

if (!function_exists('e')) {
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
}
