<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['email'], $data['password'], $data['role']]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE users SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC")->fetchAll();
    }

    public function getAllByRole(string $role): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = ? ORDER BY created_at DESC");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    public function search(string $query, ?string $role = null, ?string $status = null): array
    {
        $sql = "SELECT id, name, email, role, status, created_at FROM users WHERE (name LIKE ? OR email LIKE ?)";
        $params = ["%$query%", "%$query%"];
        if ($role) { $sql .= " AND role = ?"; $params[] = $role; }
        if ($status) { $sql .= " AND status = ?"; $params[] = $status; }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getRecent(int $limit = 10): array
    {
        return $this->db->query("SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function countByRole(string $role): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE status = ?");
        $stmt->execute([$status]);
        return (int) $stmt->fetchColumn();
    }

    public function getRegistrationTrend(int $months = 6): array
    {
        return $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM users WHERE role = 'student' AND created_at >= DATE_SUB(CURDATE(), INTERVAL $months MONTH) GROUP BY month ORDER BY month")->fetchAll();
    }

    public function getUserGrowth(): array
    {
        return $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY month ORDER BY month")->fetchAll();
    }
}
