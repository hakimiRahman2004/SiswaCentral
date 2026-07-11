<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SubscriptionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findActiveByProvider(int $providerId): ?array
    {
        $stmt = $this->db->prepare("SELECT sub.*, pl.name as plan_name, pl.price as plan_price, pl.max_staff, pl.max_scholarships, pl.features FROM subscriptions sub JOIN subscription_plans pl ON sub.plan_id = pl.id WHERE sub.provider_id = ? AND sub.status = 'active' AND (sub.end_date IS NULL OR sub.end_date >= CURDATE()) ORDER BY sub.end_date DESC LIMIT 1");
        $stmt->execute([$providerId]);
        return $stmt->fetch() ?: null;
    }

    public function findByProvider(int $providerId): array
    {
        $stmt = $this->db->prepare("SELECT sub.*, pl.name as plan_name, pl.price as plan_price FROM subscriptions sub JOIN subscription_plans pl ON sub.plan_id = pl.id WHERE sub.provider_id = ? ORDER BY sub.created_at DESC");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO subscriptions (provider_id, plan_id, status, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['provider_id'], $data['plan_id'], $data['status'], $data['start_date'], $data['end_date']]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE subscriptions SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function getExpiredSubscriptions(): array
    {
        return $this->db->query("SELECT * FROM subscriptions WHERE status = 'active' AND end_date IS NOT NULL AND end_date < CURDATE()")->fetchAll();
    }

    public function getAllPlans(): array
    {
        return $this->db->query("SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY price ASC")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT sub.*, p.company_name as provider_name, pl.name as plan_name, pl.price as plan_price FROM subscriptions sub JOIN providers p ON sub.provider_id = p.id JOIN subscription_plans pl ON sub.plan_id = pl.id WHERE sub.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT sub.*, p.company_name as provider_name, pl.name as plan_name, pl.price as plan_price FROM subscriptions sub JOIN providers p ON sub.provider_id = p.id JOIN subscription_plans pl ON sub.plan_id = pl.id ORDER BY sub.created_at DESC")->fetchAll();
    }

    public function findPlanById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM subscription_plans WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function createPlan(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO subscription_plans (name, price, duration_days, max_staff, max_scholarships, features) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['price'], $data['duration_days'], $data['max_staff'], $data['max_scholarships'], $data['features'] ?? null]);
        return (int) $this->db->lastInsertId();
    }

    public function updatePlan(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE subscription_plans SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function deletePlan(int $id): bool
    {
        return $this->db->prepare("DELETE FROM subscription_plans WHERE id = ?")->execute([$id]);
    }

    public function getAllPlansAdmin(): array
    {
        return $this->db->query("SELECT * FROM subscription_plans ORDER BY price ASC")->fetchAll();
    }
}
