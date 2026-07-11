<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class StaffRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByProvider(int $providerId): array
    {
        $stmt = $this->db->prepare("SELECT st.*, u.name, u.email FROM staff st JOIN users u ON st.user_id = u.id WHERE st.provider_id = ?");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO staff (provider_id, user_id, role) VALUES (?, ?, ?)");
        $stmt->execute([$data['provider_id'], $data['user_id'], $data['role']]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM staff WHERE id = ?")->execute([$id]);
    }
}
