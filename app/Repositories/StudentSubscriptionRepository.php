<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class StudentSubscriptionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAllPlans(): array
    {
        return $this->db->query("SELECT * FROM student_plans ORDER BY price ASC")->fetchAll();
    }

    public function getPlans(): array
    {
        return $this->db->query("SELECT * FROM student_plans WHERE is_active = 1 ORDER BY price ASC")->fetchAll();
    }

    public function findPlanById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM student_plans WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function togglePlanStatus(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE student_plans SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getActiveSubscription(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT ss.*, sp.name as plan_name, sp.price, sp.max_applications, sp.max_documents, sp.has_smart_matching, sp.has_priority_support, sp.features FROM student_subscriptions ss JOIN student_plans sp ON ss.plan_id = sp.id WHERE ss.user_id = ? AND ss.status = 'active' AND ss.end_date >= CURDATE() ORDER BY ss.end_date DESC LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function findSubscriptionByUser(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT ss.*, sp.name as plan_name, sp.price, sp.max_applications, sp.max_documents, sp.has_smart_matching, sp.has_priority_support, sp.features FROM student_subscriptions ss JOIN student_plans sp ON ss.plan_id = sp.id WHERE ss.user_id = ? ORDER BY ss.created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function getHistory(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT ss.*, sp.name as plan_name, sp.price FROM student_subscriptions ss JOIN student_plans sp ON ss.plan_id = sp.id WHERE ss.user_id = ? ORDER BY ss.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO student_subscriptions (user_id, plan_id, status, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['user_id'], $data['plan_id'], $data['status'], $data['start_date'], $data['end_date']]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE student_subscriptions SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT ss.*, u.name as student_name, u.email as student_email, sp.name as plan_name, sp.price as plan_price FROM student_subscriptions ss JOIN users u ON ss.user_id = u.id JOIN student_plans sp ON ss.plan_id = sp.id ORDER BY ss.created_at DESC")->fetchAll();
    }

    public function getDefaultPlan(): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM student_plans WHERE price = 0 AND is_active = 1 LIMIT 1");
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function getApplicationCountThisMonth(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ? AND created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function getDocumentCount(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM documents WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    /** Mark expired subscriptions as 'expired' for a user. */
    public function expireStale(int $userId): void
    {
        $stmt = $this->db->prepare("UPDATE student_subscriptions SET status = 'expired' WHERE user_id = ? AND status = 'active' AND end_date < CURDATE()");
        $stmt->execute([$userId]);
    }

    /** Get the most recently expired subscription for a user, if any. */
    public function getRecentlyExpired(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT ss.*, sp.name as plan_name, sp.price FROM student_subscriptions ss JOIN student_plans sp ON ss.plan_id = sp.id WHERE ss.user_id = ? AND ss.status = 'expired' ORDER BY ss.end_date DESC LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }
}
