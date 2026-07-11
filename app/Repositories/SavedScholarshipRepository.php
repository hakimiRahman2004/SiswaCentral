<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SavedScholarshipRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByStudent(int $studentId): array
    {
        $stmt = $this->db->prepare("SELECT ss.*, s.name, s.amount, s.deadline, s.min_cgpa, s.max_income, s.status, p.company_name FROM saved_scholarships ss JOIN scholarships s ON ss.scholarship_id = s.id JOIN providers p ON s.provider_id = p.id WHERE ss.student_id = ? ORDER BY ss.created_at DESC");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function isSaved(int $studentId, int $scholarshipId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM saved_scholarships WHERE student_id = ? AND scholarship_id = ?");
        $stmt->execute([$studentId, $scholarshipId]);
        return (bool) $stmt->fetch();
    }

    public function save(int $studentId, int $scholarshipId): bool
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO saved_scholarships (student_id, scholarship_id) VALUES (?, ?)");
        return $stmt->execute([$studentId, $scholarshipId]);
    }

    public function unsave(int $studentId, int $scholarshipId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM saved_scholarships WHERE student_id = ? AND scholarship_id = ?");
        return $stmt->execute([$studentId, $scholarshipId]);
    }

    public function countByStudent(int $studentId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM saved_scholarships WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return (int) $stmt->fetchColumn();
    }
}
