<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ApplicationRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByStudent(int $studentId): array
    {
        $stmt = $this->db->prepare("SELECT a.*, s.name as scholarship_name, s.amount, p.company_name FROM applications a JOIN scholarships s ON a.scholarship_id = s.id JOIN providers p ON a.provider_id = p.id WHERE a.student_id = ? ORDER BY a.created_at DESC");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function findByScholarship(int $scholarshipId): array
    {
        $stmt = $this->db->prepare("SELECT a.*, u.name as student_name, u.email as student_email, sp.cgpa, sp.course, sp.university, sp.phone as student_phone, sp.income as student_income, sp.gender, sp.date_of_birth, sp.address as student_address, sp.about as student_about FROM applications a JOIN users u ON a.student_id = u.id LEFT JOIN student_profiles sp ON a.student_id = sp.user_id WHERE a.scholarship_id = ? ORDER BY a.created_at DESC");
        $stmt->execute([$scholarshipId]);
        return $stmt->fetchAll();
    }

    public function findByProvider(int $providerId): array
    {
        $stmt = $this->db->prepare("SELECT a.*, s.name as scholarship_name, u.name as student_name FROM applications a JOIN scholarships s ON a.scholarship_id = s.id JOIN users u ON a.student_id = u.id WHERE a.provider_id = ? ORDER BY a.created_at DESC");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll();
    }

    public function findByStudentAndScholarship(int $studentId, int $scholarshipId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM applications WHERE student_id = ? AND scholarship_id = ?");
        $stmt->execute([$studentId, $scholarshipId]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO applications (student_id, scholarship_id, provider_id) VALUES (?, ?, ?)");
        $stmt->execute([$data['student_id'], $data['scholarship_id'], $data['provider_id']]);
        return (int) $this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): bool
    {
        if ($notes) {
            $stmt = $this->db->prepare("UPDATE applications SET status = ?, notes = ? WHERE id = ?");
            return $stmt->execute([$status, $notes, $id]);
        }
        $stmt = $this->db->prepare("UPDATE applications SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT a.*, u.name as student_name, u.email as student_email, s.name as scholarship_name, p.company_name FROM applications a JOIN users u ON a.student_id = u.id JOIN scholarships s ON a.scholarship_id = s.id JOIN providers p ON a.provider_id = p.id ORDER BY a.created_at DESC")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT a.*, u.name as student_name, u.email as student_email, u.created_at as student_registered, sp.cgpa, sp.course, sp.university, sp.phone as student_phone, sp.income as student_income, sp.gender, sp.date_of_birth, sp.address as student_address, sp.about as student_about, s.name as scholarship_name, s.amount, p.company_name FROM applications a JOIN users u ON a.student_id = u.id LEFT JOIN student_profiles sp ON a.student_id = sp.user_id JOIN scholarships s ON a.scholarship_id = s.id JOIN providers p ON a.provider_id = p.id WHERE a.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function filter(?string $status = null, ?int $scholarshipId = null): array
    {
        $sql = "SELECT a.*, u.name as student_name, u.email as student_email, s.name as scholarship_name, p.company_name FROM applications a JOIN users u ON a.student_id = u.id JOIN scholarships s ON a.scholarship_id = s.id JOIN providers p ON a.provider_id = p.id WHERE 1=1";
        $params = [];
        if ($status) { $sql .= " AND a.status = ?"; $params[] = $status; }
        if ($scholarshipId) { $sql .= " AND a.scholarship_id = ?"; $params[] = $scholarshipId; }
        $sql .= " ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM applications")->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE status = ?");
        $stmt->execute([$status]);
        return (int) $stmt->fetchColumn();
    }

    public function countByProvider(int $providerId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE provider_id = ?");
        $stmt->execute([$providerId]);
        return (int) $stmt->fetchColumn();
    }

    public function countByProviderAndStatus(int $providerId, string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE provider_id = ? AND status = ?");
        $stmt->execute([$providerId, $status]);
        return (int) $stmt->fetchColumn();
    }

    public function getApplicationTrend(int $months = 6): array
    {
        return $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM applications WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL $months MONTH) GROUP BY month ORDER BY month")->fetchAll();
    }

    public function getTopScholarships(int $limit = 10): array
    {
        return $this->db->query("SELECT s.id, s.name, s.amount, p.company_name, COUNT(a.id) as application_count FROM scholarships s JOIN providers p ON s.provider_id = p.id LEFT JOIN applications a ON a.scholarship_id = s.id GROUP BY s.id, s.name, s.amount, p.company_name ORDER BY application_count DESC LIMIT $limit")->fetchAll();
    }

    public function countByStudent(int $studentId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return (int) $stmt->fetchColumn();
    }

    public function countByStudentAndStatus(int $studentId, string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ? AND status = ?");
        $stmt->execute([$studentId, $status]);
        return (int) $stmt->fetchColumn();
    }

    public function cancel(int $id, int $studentId): bool
    {
        $stmt = $this->db->prepare("UPDATE applications SET status = 'rejected', notes = 'Cancelled by student' WHERE id = ? AND student_id = ? AND status = 'submitted'");
        return $stmt->execute([$id, $studentId]);
    }

    public function logStatusChange(int $applicationId, ?string $oldStatus, string $newStatus, ?int $changedBy = null, ?string $notes = null): void
    {
        $stmt = $this->db->prepare("INSERT INTO application_status_logs (application_id, old_status, new_status, changed_by, notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$applicationId, $oldStatus, $newStatus, $changedBy, $notes]);
    }

    public function getStatusLogs(int $applicationId): array
    {
        $stmt = $this->db->prepare("SELECT l.*, u.name as changed_by_name FROM application_status_logs l LEFT JOIN users u ON l.changed_by = u.id WHERE l.application_id = ? ORDER BY l.created_at DESC");
        $stmt->execute([$applicationId]);
        return $stmt->fetchAll();
    }
}
