<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ScholarshipRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findOpen(): array
    {
        return $this->db->query("SELECT s.*, p.company_name, p.id as provider_id FROM scholarships s JOIN providers p ON s.provider_id = p.id WHERE s.status = 'open' ORDER BY s.created_at DESC")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT s.*, p.company_name, p.logo FROM scholarships s JOIN providers p ON s.provider_id = p.id WHERE s.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByProvider(int $providerId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM scholarships WHERE provider_id = ? ORDER BY created_at DESC");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $cols = implode(', ', array_keys($data));
        $phs = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO scholarships ({$cols}) VALUES ({$phs})");
        $stmt->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE scholarships SET {$sets} WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM scholarships WHERE id = ?")->execute([$id]);
    }

    public function countByProvider(int $providerId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM scholarships WHERE provider_id = ?");
        $stmt->execute([$providerId]);
        return (int) $stmt->fetchColumn();
    }

    public function countByProviderAndStatus(int $providerId, string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM scholarships WHERE provider_id = ? AND status = ?");
        $stmt->execute([$providerId, $status]);
        return (int) $stmt->fetchColumn();
    }

    public function count(?string $startDate = null, ?string $endDate = null): int
    {
        if ($startDate && $endDate) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM scholarships WHERE created_at >= ? AND created_at <= ?");
            $stmt->execute([$startDate, $endDate]);
            return (int) $stmt->fetchColumn();
        }
        return (int) $this->db->query("SELECT COUNT(*) FROM scholarships")->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM scholarships WHERE status = ?");
        $stmt->execute([$status]);
        return (int) $stmt->fetchColumn();
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT s.*, p.company_name FROM scholarships s JOIN providers p ON s.provider_id = p.id ORDER BY s.created_at DESC")->fetchAll();
    }

    public function getByStatus(string $status): array
    {
        $stmt = $this->db->prepare("SELECT s.*, p.company_name FROM scholarships s JOIN providers p ON s.provider_id = p.id WHERE s.status = ? ORDER BY s.created_at DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public function getTrend(): array
    {
        return $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM scholarships WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY month ORDER BY month")->fetchAll();
    }

    public function countOpen(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM scholarships WHERE status = 'open'")->fetchColumn();
    }

    // ─── SCHOLARSHIP DOCUMENTS ─────────────────────────────────────────────

    public function getDocuments(int $scholarshipId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM scholarship_documents WHERE scholarship_id = ? ORDER BY created_at ASC");
        $stmt->execute([$scholarshipId]);
        return $stmt->fetchAll();
    }

    public function addDocument(int $scholarshipId, string $originalName, string $storedName, int $fileSize, string $mimeType): int
    {
        $stmt = $this->db->prepare("INSERT INTO scholarship_documents (scholarship_id, original_name, stored_name, file_size, mime_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$scholarshipId, $originalName, $storedName, $fileSize, $mimeType]);
        return (int) $this->db->lastInsertId();
    }

    public function deleteDocument(int $docId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM scholarship_documents WHERE id = ?");
        return $stmt->execute([$docId]);
    }

    public function findDocumentById(int $docId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM scholarship_documents WHERE id = ?");
        $stmt->execute([$docId]);
        return $stmt->fetch() ?: null;
    }

    public function search(?string $q = null, ?string $course = null, ?string $university = null, ?float $cgpa = null, ?float $income = null): array
    {
        $sql = "SELECT s.*, p.company_name, p.logo FROM scholarships s JOIN providers p ON s.provider_id = p.id WHERE s.status = 'open'";
        $params = [];

        if ($q) {
            $sql .= " AND (s.name LIKE ? OR s.description LIKE ? OR p.company_name LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }

        if ($course) {
            $sql .= " AND (s.eligible_courses IS NULL OR s.eligible_courses = '' OR s.eligible_courses LIKE ?)";
            $params[] = "%$course%";
        }

        if ($cgpa !== null) {
            $sql .= " AND (s.min_cgpa IS NULL OR s.min_cgpa <= ?)";
            $params[] = $cgpa;
        }

        if ($income !== null) {
            $sql .= " AND (s.max_income IS NULL OR s.max_income >= ?)";
            $params[] = $income;
        }

        $sql .= " ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
