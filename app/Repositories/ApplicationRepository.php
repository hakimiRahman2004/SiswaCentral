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
        if ($notes !== null) {
            $stmt = $this->db->prepare("UPDATE applications SET status = ?, notes = ? WHERE id = ?");
            return $stmt->execute([$status, $notes, $id]);
        }
        $stmt = $this->db->prepare("UPDATE applications SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function updateCommunicationUrl(int $id, ?string $url): bool
    {
        $stmt = $this->db->prepare("UPDATE applications SET communication_url = ? WHERE id = ?");
        return $stmt->execute([$url ?: null, $id]);
    }

    public function update(int $id, array $data): bool
    {
        if (empty($data)) return false;
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE applications SET {$sets}, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function saveInterview(int $applicationId, string $scheduledAt, string $meetingUrl, ?string $instructions, int $createdBy): bool
    {
        $stmt = $this->db->prepare("INSERT INTO application_interviews (application_id, scheduled_at, meeting_url, instructions, created_by) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE scheduled_at = VALUES(scheduled_at), meeting_url = VALUES(meeting_url), instructions = VALUES(instructions), created_by = VALUES(created_by)");
        return $stmt->execute([$applicationId, $scheduledAt, $meetingUrl, $instructions ?: null, $createdBy]);
    }

    public function getInterview(int $applicationId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM application_interviews WHERE application_id = ?");
        $stmt->execute([$applicationId]);
        return $stmt->fetch() ?: null;
    }

    public function saveEssayRequest(int $applicationId, string $prompt, ?string $dueAt, int $createdBy): bool
    {
        $stmt = $this->db->prepare("INSERT INTO application_essay_requests (application_id, prompt, due_at, created_by) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE prompt = VALUES(prompt), due_at = VALUES(due_at), created_by = VALUES(created_by)");
        return $stmt->execute([$applicationId, $prompt, $dueAt ?: null, $createdBy]);
    }

    public function getEssayRequest(int $applicationId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM application_essay_requests WHERE application_id = ?");
        $stmt->execute([$applicationId]);
        return $stmt->fetch() ?: null;
    }

    public function submitEssay(int $applicationId, int $studentId, string $essay): bool
    {
        $stmt = $this->db->prepare("UPDATE application_essay_requests er JOIN applications a ON a.id = er.application_id SET er.submitted_essay = ?, er.submitted_at = NOW() WHERE er.application_id = ? AND a.student_id = ? AND er.submitted_at IS NULL AND (er.due_at IS NULL OR er.due_at >= NOW())");
        return $stmt->execute([$essay, $applicationId, $studentId]);
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

    public function count(?string $startDate = null, ?string $endDate = null): int
    {
        if ($startDate && $endDate) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE created_at >= ? AND created_at <= ?");
            $stmt->execute([$startDate, $endDate]);
            return (int) $stmt->fetchColumn();
        }
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

    public function getApplicationTrend(int $months = 6, ?string $startDate = null, ?string $endDate = null): array
    {
        if ($startDate && $endDate) {
            $stmt = $this->db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM applications WHERE created_at >= ? AND created_at <= ? GROUP BY month ORDER BY month");
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetchAll();
        }
        return $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM applications WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL $months MONTH) GROUP BY month ORDER BY month")->fetchAll();
    }

    public function getTopScholarships(int $limit = 10, ?string $startDate = null, ?string $endDate = null): array
    {
        $dateFilter = '';
        $params = [];
        if ($startDate && $endDate) {
            $dateFilter = ' AND a.created_at >= ? AND a.created_at <= ?';
            $params = [$startDate, $endDate];
        }
        $sql = "SELECT s.id, s.name, s.amount, p.company_name, COALESCE(app_count, 0) as application_count
                FROM scholarships s
                JOIN providers p ON s.provider_id = p.id
                LEFT JOIN (SELECT scholarship_id, COUNT(id) as app_count FROM applications a WHERE 1=1$dateFilter GROUP BY scholarship_id) a ON a.scholarship_id = s.id
                ORDER BY application_count DESC LIMIT $limit";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
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

    // ─── ENHANCED APPLICATION METHODS ─────────────────────────────────────

    public function generateApplicationNumber(): string
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE YEAR(created_at) = ?");
        $stmt->execute([$year]);
        $count = (int) $stmt->fetchColumn() + 1;
        return "APP-{$year}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function createFull(array $data): int
    {
        $appNumber = $this->generateApplicationNumber();
        $details = json_encode($data['details'] ?? []);

        $stmt = $this->db->prepare("INSERT INTO applications (student_id, scholarship_id, provider_id, status, application_number, details) VALUES (?, ?, ?, 'submitted', ?, ?)");
        $stmt->execute([$data['student_id'], $data['scholarship_id'], $data['provider_id'], $appNumber, $details]);
        return (int) $this->db->lastInsertId();
    }

    public function saveDraft(array $data): int
    {
        $appNumber = $data['application_number'] ?? $this->generateApplicationNumber();
        $details = json_encode($data['details'] ?? []);

        if (!empty($data['id'])) {
            $stmt = $this->db->prepare("UPDATE applications SET details = ?, application_number = ? WHERE id = ?");
            $stmt->execute([$details, $appNumber, $data['id']]);
            return (int) $data['id'];
        }

        $stmt = $this->db->prepare("INSERT INTO applications (student_id, scholarship_id, provider_id, status, application_number, details) VALUES (?, ?, ?, 'pending', ?, ?)");
        $stmt->execute([$data['student_id'], $data['scholarship_id'], $data['provider_id'], $appNumber, $details]);
        return (int) $this->db->lastInsertId();
    }

    public function markReviewed(int $id, int $reviewedBy): void
    {
        $stmt = $this->db->prepare("UPDATE applications SET reviewed_by = ?, reviewed_at = NOW() WHERE id = ? AND reviewed_by IS NULL");
        $stmt->execute([$reviewedBy, $id]);
    }

    public function withdraw(int $id, int $studentId): bool
    {
        $stmt = $this->db->prepare("UPDATE applications SET status = 'withdrawn', notes = 'Withdrawn by student' WHERE id = ? AND student_id = ? AND status IN ('pending','submitted','screening')");
        return $stmt->execute([$id, $studentId]);
    }

    public function getDetails(int $applicationId): ?array
    {
        $stmt = $this->db->prepare("SELECT details FROM applications WHERE id = ?");
        $stmt->execute([$applicationId]);
        $row = $stmt->fetch();
        if (!$row || !$row['details']) return null;
        return json_decode($row['details'], true);
    }

    public function getTimeline(int $applicationId): array
    {
        $stmt = $this->db->prepare("SELECT l.*, u.name as changed_by_name, u.role as changed_by_role FROM application_status_logs l LEFT JOIN users u ON l.changed_by = u.id WHERE l.application_id = ? ORDER BY l.created_at ASC");
        $stmt->execute([$applicationId]);
        return $stmt->fetchAll();
    }

    public function getNotes(int $applicationId): array
    {
        $stmt = $this->db->prepare("SELECT n.*, u.name as author_name FROM application_notes n JOIN users u ON n.user_id = u.id WHERE n.application_id = ? ORDER BY n.created_at DESC");
        $stmt->execute([$applicationId]);
        return $stmt->fetchAll();
    }

    public function addNote(int $applicationId, int $userId, string $note, bool $isInternal = true): void
    {
        $stmt = $this->db->prepare("INSERT INTO application_notes (application_id, user_id, note, is_internal) VALUES (?, ?, ?, ?)");
        $stmt->execute([$applicationId, $userId, $note, $isInternal ? 1 : 0]);
    }

    // ─── DOCUMENT REQUESTS ────────────────────────────────────────────────

    public function createDocumentRequest(int $applicationId, string $docType, ?string $description, ?string $deadline, int $createdBy): int
    {
        $stmt = $this->db->prepare("INSERT INTO document_requests (application_id, document_type, description, deadline, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$applicationId, $docType, $description, $deadline, $createdBy]);
        return (int) $this->db->lastInsertId();
    }

    public function getDocumentRequests(int $applicationId): array
    {
        $stmt = $this->db->prepare("SELECT dr.*, u.name as requested_by_name FROM document_requests dr LEFT JOIN users u ON dr.created_by = u.id WHERE dr.application_id = ? ORDER BY dr.created_at DESC");
        $stmt->execute([$applicationId]);
        return $stmt->fetchAll();
    }

    // ─── BULK OPERATIONS ──────────────────────────────────────────────────

    public function bulkUpdateStatus(array $ids, string $status, ?int $changedBy = null, ?string $notes = null): void
    {
        if (empty($ids)) return;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("UPDATE applications SET status = ? WHERE id IN ($placeholders)");
        $params = array_merge([$status], $ids);
        $stmt->execute($params);

        foreach ($ids as $id) {
            $this->logStatusChange($id, null, $status, $changedBy, $notes);
        }
    }

    public function getExportData(?string $status = null, ?int $providerId = null, ?int $scholarshipId = null): array
    {
        $sql = "SELECT a.id, a.application_number, a.status, a.created_at, a.updated_at,
                       u.name as student_name, u.email as student_email,
                       s.name as scholarship_name, s.amount,
                       p.company_name as provider_name,
                       JSON_EXTRACT(a.details, '$.cgpa') as cgpa,
                       JSON_EXTRACT(a.details, '$.course') as course,
                       JSON_EXTRACT(a.details, '$.university') as university,
                       JSON_EXTRACT(a.details, '$.income') as income
                FROM applications a
                JOIN users u ON a.student_id = u.id
                JOIN scholarships s ON a.scholarship_id = s.id
                JOIN providers p ON a.provider_id = p.id
                WHERE 1=1";

        $params = [];
        if ($status) { $sql .= " AND a.status = ?"; $params[] = $status; }
        if ($providerId) { $sql .= " AND a.provider_id = ?"; $params[] = $providerId; }
        if ($scholarshipId) { $sql .= " AND a.scholarship_id = ?"; $params[] = $scholarshipId; }
        $sql .= " ORDER BY a.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getEmailLogs(): array
    {
        $stmt = $this->db->query(
            "SELECT el.*, u.name as recipient_name, u.email as recipient_email
             FROM email_logs el
             LEFT JOIN users u ON el.user_id = u.id
             ORDER BY el.created_at DESC
             LIMIT 100"
        );
        return $stmt->fetchAll();
    }
}
