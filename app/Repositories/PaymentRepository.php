<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class PaymentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO payments (subscription_id, provider_id, bill_code, toyyib_bill_code, amount, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['subscription_id'], $data['provider_id'], $data['bill_code'], $data['toyyib_bill_code'] ?? null, $data['amount'], $data['status']]);
        return (int) $this->db->lastInsertId();
    }

    public function findByBillCode(string $billCode): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE bill_code = ?");
        $stmt->execute([$billCode]);
        return $stmt->fetch() ?: null;
    }

    public function findByToyyibBillCode(string $toyyibBillCode): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE toyyib_bill_code = ?");
        $stmt->execute([$toyyibBillCode]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE payments SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT pay.*, p.company_name, pl.name as plan_name FROM payments pay JOIN providers p ON pay.provider_id = p.id JOIN subscriptions s ON pay.subscription_id = s.id JOIN subscription_plans pl ON s.plan_id = pl.id ORDER BY pay.created_at DESC")->fetchAll();
    }

    public function getByStatus(string $status): array
    {
        $stmt = $this->db->prepare("SELECT pay.*, p.company_name, pl.name as plan_name FROM payments pay JOIN providers p ON pay.provider_id = p.id JOIN subscriptions s ON pay.subscription_id = s.id JOIN subscription_plans pl ON s.plan_id = pl.id WHERE pay.status = ? ORDER BY pay.created_at DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public function totalRevenue(?string $startDate = null, ?string $endDate = null): float
    {
        if ($startDate && $endDate) {
            $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'success' AND paid_at >= ? AND paid_at <= ?");
            $stmt->execute([$startDate, $endDate]);
            return (float) $stmt->fetchColumn();
        }
        return (float) $this->db->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'success'")->fetchColumn();
    }

    public function getRevenueTrend(?string $startDate = null, ?string $endDate = null): array
    {
        if ($startDate && $endDate) {
            $stmt = $this->db->prepare("SELECT DATE_FORMAT(paid_at, '%Y-%m') as month, COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'success' AND paid_at >= ? AND paid_at <= ? GROUP BY month ORDER BY month");
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetchAll();
        }
        return $this->db->query("SELECT DATE_FORMAT(paid_at, '%Y-%m') as month, COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'success' AND paid_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY month ORDER BY month")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT pay.*, p.company_name, pl.name as plan_name FROM payments pay JOIN providers p ON pay.provider_id = p.id JOIN subscriptions s ON pay.subscription_id = s.id JOIN subscription_plans pl ON s.plan_id = pl.id WHERE pay.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
