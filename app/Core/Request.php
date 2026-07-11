<?php

namespace App\Core;

class Request
{
    private array $query;
    private array $body;
    private array $files;
    private array $server;

    public function __construct()
    {
        $this->query = $_GET;
        $this->body = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    public static function capture(): self
    {
        return new self();
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    public function uri(): string
    {
        $uri = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
        // ponytail: detect base path — handles both .htaccess (script in /public/)
        // and root index.php (script at project root)
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $base = basename($scriptDir) === 'public' ? dirname($scriptDir) : $scriptDir;
        $base = rtrim($base, '/\\');
        if ($base !== '' && $base !== '/' && $base !== '.' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        return rtrim($uri, '/') ?: '/';
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function validate(array $rules): array
    {
        $validated = [];
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $value = $this->input($field);
            $ruleList = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field][] = "$field is required";
                    continue 2;
                }
                if (str_starts_with($rule, 'min:') && strlen($value) < (int) substr($rule, 4)) {
                    $errors[$field][] = "$field must be at least " . substr($rule, 4);
                    continue 2;
                }
                if (str_starts_with($rule, 'max:') && strlen($value) > (int) substr($rule, 4)) {
                    $errors[$field][] = "$field must not exceed " . substr($rule, 4);
                    continue 2;
                }
                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "$field must be a valid email";
                    continue 2;
                }
            }

            $validated[$field] = $value;
        }

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $this->all();

            $referer = $this->server['HTTP_REFERER'] ?? '/';
            header("Location: $referer");
            exit;
        }

        return $validated;
    }
}
