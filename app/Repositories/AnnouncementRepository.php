<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class AnnouncementRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO announcements (title, message, type, target_role, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['title'], $data['message'], $data['type'] ?? 'info', $data['target_role'] ?? 'all', $data['created_by']]);
        return (int) $this->db->lastInsertId();
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT a.*, u.name as author_name FROM announcements a LEFT JOIN users u ON a.created_by = u.id ORDER BY a.created_at DESC")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM announcements WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE announcements SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM announcements WHERE id = ?")->execute([$id]);
    }
}
