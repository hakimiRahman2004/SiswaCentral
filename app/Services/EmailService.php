<?php

namespace App\Services;

use App\Core\Database;
use PDO;

class EmailService
{
    private PDO $db;
    private string $appName;
    private string $supportEmail;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->appName = config('app.name') ?? 'SISWACentral';
        $this->supportEmail = env('SUPPORT_EMAIL', 'support@siswacentral.com');
    }

    /**
     * Send an email and log it to the email_logs table.
     */
    public function send(string $to, string $subject, string $htmlBody, string $type = 'general', ?int $userId = null): bool
    {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            "From: {$this->appName} <{$this->supportEmail}>",
            'X-Mailer: PHP/' . phpversion(),
        ];

        $sent = mail($to, $subject, $htmlBody, implode("\r\n", $headers));

        $this->logEmail($userId, $to, $subject, $htmlBody, $type, $sent ? 'sent' : 'failed');

        return $sent;
    }

    /**
     * Log an email to the database.
     */
    public function logEmail(?int $userId, string $email, string $subject, string $body, string $type, string $status = 'pending'): void
    {
        $stmt = $this->db->prepare("INSERT INTO email_logs (user_id, recipient_email, subject, body, type, status, sent_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$userId, $email, $subject, $body, $type, $status]);
    }

    /**
     * Wrap content in the branded email template.
     */
    private function wrap(string $title, string $content): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f4f0;font-family:Inter,Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:40px 20px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
  <tr><td style="background:#0e0e0e;padding:24px 32px;">
    <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;">{$this->appName}</h1>
  </td></tr>
  <tr><td style="padding:32px;">
    <h2 style="margin:0 0 16px;color:#1a1a1a;font-size:18px;font-weight:600;">{$title}</h2>
    {$content}
  </td></tr>
  <tr><td style="background:#f9f9f6;padding:20px 32px;border-top:1px solid #eee;">
    <p style="margin:0;font-size:12px;color:#888;text-align:center;">
      This is an automated message from {$this->appName}. Do not reply to this email.<br>
      Need help? Contact us at <a href="mailto:{$this->supportEmail}" style="color:#0e0e0e;">{$this->supportEmail}</a>
    </p>
  </td></tr>
</table>
</td></tr></table>
</body></html>
HTML;
    }

    // ─── APPLICATION STATUS EMAILS ────────────────────────────────────────

    public function applicationSubmitted(array $student, array $scholarship, string $appNumber): void
    {
        $name = htmlspecialchars($student['name']);
        $scholarshipName = htmlspecialchars($scholarship['name']);
        $providerName = htmlspecialchars($scholarship['company_name'] ?? 'the provider');

        $content = <<<HTML
<p style="color:#555;line-height:1.6;margin:0 0 16px;">Hi {$name},</p>
<p style="color:#555;line-height:1.6;margin:0 0 16px;">Your application has been successfully submitted.</p>
<table width="100%" cellpadding="8" style="background:#f9f9f6;border-radius:8px;margin:0 0 16px;">
  <tr><td style="color:#888;font-size:13px;">Application No.</td><td style="color:#1a1a1a;font-weight:600;">{$appNumber}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Scholarship</td><td style="color:#1a1a1a;font-weight:600;">{$scholarshipName}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Provider</td><td style="color:#1a1a1a;font-weight:600;">{$providerName}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Status</td><td style="color:#0d9488;font-weight:600;">Submitted</td></tr>
</table>
<p style="color:#555;line-height:1.6;margin:0 0 16px;">You will be notified when the provider reviews your application.</p>
HTML;

        $this->send($student['email'], "Application Submitted - {$scholarshipName}", $this->wrap("Application Submitted", $content), 'application_submitted', $student['id']);
    }

    public function applicationStatusChanged(array $student, array $scholarship, string $appNumber, string $newStatus, ?string $notes = null, ?string $rejectionReason = null): void
    {
        $name = htmlspecialchars($student['name']);
        $scholarshipName = htmlspecialchars($scholarship['name']);
        $statusLabel = $this->statusLabel($newStatus);
        $statusColor = $this->statusColor($newStatus);

        $content = <<<HTML
<p style="color:#555;line-height:1.6;margin:0 0 16px;">Hi {$name},</p>
<p style="color:#555;line-height:1.6;margin:0 0 16px;">Your application status has been updated.</p>
<table width="100%" cellpadding="8" style="background:#f9f9f6;border-radius:8px;margin:0 0 16px;">
  <tr><td style="color:#888;font-size:13px;">Application No.</td><td style="color:#1a1a1a;font-weight:600;">{$appNumber}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Scholarship</td><td style="color:#1a1a1a;font-weight:600;">{$scholarshipName}</td></tr>
  <tr><td style="color:#888;font-size:13px;">New Status</td><td style="color:{$statusColor};font-weight:600;">{$statusLabel}</td></tr>
</table>
HTML;

        if ($notes) {
            $escapedNotes = htmlspecialchars($notes);
            $content .= "<p style=\"color:#555;line-height:1.6;margin:0 0 16px;\"><strong>Notes:</strong> {$escapedNotes}</p>";
        }

        if ($rejectionReason && $newStatus === 'rejected') {
            $escapedReason = htmlspecialchars($rejectionReason);
            $content .= "<p style=\"color:#555;line-height:1.6;margin:0 0 16px;\"><strong>Reason:</strong> {$escapedReason}</p>";
        }

        if ($newStatus === 'approved') {
            $content .= '<p style="color:#059669;font-weight:600;line-height:1.6;margin:0 0 16px;">Congratulations! Your application has been approved.</p>';
        }

        $content .= '<p style="color:#555;line-height:1.6;margin:0;">Log in to your dashboard to view details.</p>';

        $this->send($student['email'], "Application {$statusLabel} - {$scholarshipName}", $this->wrap("Application {$statusLabel}", $content), "application_{$newStatus}", $student['id']);
    }

    public function interviewScheduled(array $student, array $scholarship, string $appNumber, array $interview): void
    {
        $name = htmlspecialchars($student['name']);
        $scholarshipName = htmlspecialchars($scholarship['name']);
        $date = date('d M Y, h:i A', strtotime($interview['scheduled_at']));
        $meetingUrl = htmlspecialchars($interview['meeting_url'] ?? '');
        $instructions = htmlspecialchars($interview['instructions'] ?? '');

        $content = <<<HTML
<p style="color:#555;line-height:1.6;margin:0 0 16px;">Hi {$name},</p>
<p style="color:#555;line-height:1.6;margin:0 0 16px;">An interview has been scheduled for your application.</p>
<table width="100%" cellpadding="8" style="background:#f9f9f6;border-radius:8px;margin:0 0 16px;">
  <tr><td style="color:#888;font-size:13px;">Application No.</td><td style="color:#1a1a1a;font-weight:600;">{$appNumber}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Scholarship</td><td style="color:#1a1a1a;font-weight:600;">{$scholarshipName}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Date & Time</td><td style="color:#1a1a1a;font-weight:600;">{$date}</td></tr>
</table>
HTML;

        if ($meetingUrl) {
            $content .= '<p style="margin:0 0 16px;"><a href="' . $meetingUrl . '" style="display:inline-block;padding:12px 24px;background:#0e0e0e;color:#fff;text-decoration:none;border-radius:8px;font-weight:600;">Join Meeting</a></p>';
        }

        if ($instructions) {
            $content .= "<p style=\"color:#555;line-height:1.6;margin:0 0 16px;\"><strong>Instructions:</strong> {$instructions}</p>";
        }

        $this->send($student['email'], "Interview Scheduled - {$scholarshipName}", $this->wrap("Interview Scheduled", $content), 'interview_scheduled', $student['id']);
    }

    public function documentRequest(array $student, array $scholarship, string $appNumber, string $docType, ?string $deadline): void
    {
        $name = htmlspecialchars($student['name']);
        $scholarshipName = htmlspecialchars($scholarship['name']);
        $docTypeEsc = htmlspecialchars($docType);
        $deadlineText = $deadline ? date('d M Y', strtotime($deadline)) : 'No deadline set';

        $content = <<<HTML
<p style="color:#555;line-height:1.6;margin:0 0 16px;">Hi {$name},</p>
<p style="color:#555;line-height:1.6;margin:0 0 16px;">Additional documents have been requested for your application.</p>
<table width="100%" cellpadding="8" style="background:#f9f9f6;border-radius:8px;margin:0 0 16px;">
  <tr><td style="color:#888;font-size:13px;">Application No.</td><td style="color:#1a1a1a;font-weight:600;">{$appNumber}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Scholarship</td><td style="color:#1a1a1a;font-weight:600;">{$scholarshipName}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Document Required</td><td style="color:#1a1a1a;font-weight:600;">{$docTypeEsc}</td></tr>
  <tr><td style="color:#888;font-size:13px;">Deadline</td><td style="color:#d97706;font-weight:600;">{$deadlineText}</td></tr>
</table>
<p style="color:#555;line-height:1.6;margin:0;">Log in to your dashboard to upload the required document.</p>
HTML;

        $this->send($student['email'], "Documents Required - {$scholarshipName}", $this->wrap("Documents Required", $content), 'document_request', $student['id']);
    }

    // ─── HELPERS ──────────────────────────────────────────────────────────

    private function statusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'Pending',
            'submitted' => 'Submitted',
            'screening' => 'Under Review',
            'shortlisted' => 'Shortlisted',
            'interview' => 'Interview',
            'document_required' => 'Documents Required',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn',
            'expired' => 'Expired',
            default => ucfirst($status),
        };
    }

    private function statusColor(string $status): string
    {
        return match($status) {
            'pending' => '#d97706',
            'submitted' => '#2563eb',
            'screening' => '#7c3aed',
            'shortlisted' => '#0891b2',
            'interview' => '#ea580c',
            'document_required' => '#d97706',
            'approved' => '#059669',
            'rejected' => '#dc2626',
            'withdrawn' => '#6b7280',
            'expired' => '#6b7280',
            default => '#6b7280',
        };
    }
}
