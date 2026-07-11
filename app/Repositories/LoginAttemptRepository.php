<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class LoginAttemptRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(string $email, bool $success): int
    {
        $stmt = $this->db->prepare("INSERT INTO login_attempts (email, success, ip_address, user_agent) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $success ? 1 : 0, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null]);
        return (int) $this->db->lastInsertId();
    }

    public function getFailedAttempts(int $limit = 50): array
    {
        return $this->db->query("SELECT * FROM login_attempts WHERE success = 0 ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    }

    public function getAll(int $limit = 100): array
    {
        return $this->db->query("SELECT * FROM login_attempts ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    }

    public function countFailedByEmail(string $email, int $minutes = 15): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM login_attempts WHERE email = ? AND success = 0 AND created_at >= DATE_SUB(NOW(), INTERVAL ? MINUTE)");
        $stmt->execute([$email, $minutes]);
        return (int) $stmt->fetchColumn();
    }
}
