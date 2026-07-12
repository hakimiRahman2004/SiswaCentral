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

    private function dateWhere(?string $start, ?string $end): string
    {
        if ($start && $end) return " AND created_at >= ? AND created_at <= ?";
        if ($start) return " AND created_at >= ?";
        if ($end) return " AND created_at <= ?";
        return '';
    }

    public function count(?string $startDate = null, ?string $endDate = null): int
    {
        $where = $this->dateWhere($startDate, $endDate);
        $sql = "SELECT COUNT(*) FROM users" . ($where ? " WHERE 1=1$where" : "");
        if ($where) { $stmt = $this->db->prepare($sql); $stmt->execute(array_filter([$startDate, $endDate])); return (int) $stmt->fetchColumn(); }
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function countByRole(string $role, ?string $startDate = null, ?string $endDate = null): int
    {
        $where = $this->dateWhere($startDate, $endDate);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = ?$where");
        $stmt->execute(array_filter([$role, $startDate, $endDate]));
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

    public function getUserGrowth(int $months = 12, ?string $startDate = null, ?string $endDate = null): array
    {
        if ($startDate && $endDate) {
            $stmt = $this->db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM users WHERE created_at >= ? AND created_at <= ? GROUP BY month ORDER BY month");
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetchAll();
        }
        return $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL $months MONTH) GROUP BY month ORDER BY month")->fetchAll();
    }
}
