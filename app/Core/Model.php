<?php

namespace App\Core;

use PDO;

abstract class Model
{
    protected static PDO $db;
    protected static string $table;

    public static function init(): void
    {
        self::$db = Database::connect();
    }

    public static function all(): array
    {
        $stmt = self::$db->query("SELECT * FROM " . static::$table . " ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function where(string $column, mixed $value): array
    {
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $stmt = self::$db->prepare("INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));
        return (int) self::$db->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
        $stmt = self::$db->prepare("UPDATE " . static::$table . " SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public static function delete(int $id): bool
    {
        $stmt = self::$db->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
