<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SupportTicketRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT t.*, u.name as user_name, u.email as user_email FROM support_tickets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT t.*, u.name as user_name, u.email as user_email FROM support_tickets t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function reply(int $id, string $reply, int $repliedBy): bool
    {
        $stmt = $this->db->prepare("UPDATE support_tickets SET admin_reply = ?, replied_by = ?, replied_at = NOW(), status = 'resolved' WHERE id = ?");
        return $stmt->execute([$reply, $repliedBy, $id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE support_tickets SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM support_tickets WHERE status != 'closed'")->fetchColumn();
    }

    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare("SELECT t.*, u.name as user_name, u.email as user_email FROM support_tickets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC LIMIT ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO support_tickets (user_id, subject, message) VALUES (?, ?, ?)");
        $stmt->execute([$data['user_id'], $data['subject'], $data['message']]);
        return (int) $this->db->lastInsertId();
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
