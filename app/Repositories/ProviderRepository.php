<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ProviderRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM providers WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT p.*, u.name as owner_name, u.email as owner_email FROM providers p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $cols = implode(', ', array_keys($data));
        $phs = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO providers ({$cols}) VALUES ({$phs})");
        $stmt->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE providers SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT p.*, u.name as owner_name, u.email as owner_email FROM providers p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC")->fetchAll();
    }

    public function getVerified(): array
    {
        return $this->db->query("SELECT p.*, u.name as owner_name, u.email as owner_email FROM providers p JOIN users u ON p.user_id = u.id WHERE p.is_verified = 1 ORDER BY p.created_at DESC")->fetchAll();
    }

    public function getUnverified(): array
    {
        return $this->db->query("SELECT p.*, u.name as owner_name, u.email as owner_email FROM providers p JOIN users u ON p.user_id = u.id WHERE p.is_verified = 0 ORDER BY p.created_at DESC")->fetchAll();
    }

    public function getStaff(int $providerId): array
    {
        $stmt = $this->db->prepare("SELECT s.*, u.name, u.email FROM staff s JOIN users u ON s.user_id = u.id WHERE s.provider_id = ?");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM providers")->fetchColumn();
    }

    public function countVerified(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM providers WHERE is_verified = 1")->fetchColumn();
    }
}
