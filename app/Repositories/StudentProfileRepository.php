<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class StudentProfileRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM student_profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function upsert(int $userId, array $data): bool
    {
        $existing = $this->findByUserId($userId);
        if ($existing) {
            $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
            $stmt = $this->db->prepare("UPDATE student_profiles SET {$sets} WHERE user_id = ?");
            return $stmt->execute([...array_values($data), $userId]);
        }
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO student_profiles ({$columns}) VALUES ({$placeholders})");
        return $stmt->execute(array_values($data));
    }
}
