<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class FeedbackRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT f.*, u.name as user_name, u.email as user_email FROM feedback f JOIN users u ON f.user_id = u.id ORDER BY f.created_at DESC")->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE feedback SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO feedback (user_id, subject, message, rating, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$data['user_id'], $data['subject'] ?? '', $data['message'], $data['rating'] ?? 0]);
        return (int) $this->db->lastInsertId();
    }
}
