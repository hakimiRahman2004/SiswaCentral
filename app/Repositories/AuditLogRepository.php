<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class AuditLogRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO audit_logs (user_id, action, entity_type, entity_id, description, old_values, new_values, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['user_id'] ?? null, $data['action'], $data['entity_type'],
            $data['entity_id'] ?? null, $data['description'] ?? null,
            isset($data['old_values']) ? json_encode($data['old_values']) : null,
            isset($data['new_values']) ? json_encode($data['new_values']) : null,
            $data['ip_address'] ?? ($_SERVER['REMOTE_ADDR'] ?? null),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getAll(int $limit = 100): array
    {
        return $this->db->query("SELECT al.*, u.name as user_name, u.email as user_email FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT $limit")->fetchAll();
    }

    public function getByAction(string $action, int $limit = 50): array
    {
        $stmt = $this->db->prepare("SELECT al.*, u.name as user_name, u.email as user_email FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id WHERE al.action = ? ORDER BY al.created_at DESC LIMIT $limit");
        $stmt->execute([$action]);
        return $stmt->fetchAll();
    }

    public function getByUser(int $userId, int $limit = 50): array
    {
        $stmt = $this->db->prepare("SELECT al.*, u.name as user_name, u.email as user_email FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id WHERE al.user_id = ? ORDER BY al.created_at DESC LIMIT $limit");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getFailedLogins(int $limit = 50): array
    {
        return $this->db->query("SELECT al.*, u.name as user_name FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id WHERE al.action = 'login_failed' ORDER BY al.created_at DESC LIMIT $limit")->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn();
    }
}
