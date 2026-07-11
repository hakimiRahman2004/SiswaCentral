<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SettingsRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE `key` = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetchColumn();
        return $row !== false ? $row : $default;
    }

    public function set(string $key, string $value): void
    {
        $stmt = $this->db->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
        $stmt->execute([$key, $value, $value]);
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT `key`, value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
