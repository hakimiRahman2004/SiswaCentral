<?php

namespace App\Core;

class Auth
{
    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function id(): ?int
    {
        return Session::get('user')['id'] ?? null;
    }

    public static function role(): ?string
    {
        return Session::get('user')['role'] ?? null;
    }

    public static function check(): bool
    {
        return Session::has('user');
    }

    public static function is(string $role): bool
    {
        return self::role() === $role;
    }

    public static function login(array $user): void
    {
        Session::set('user', $user);
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}
