<?php

namespace App\Services;

use App\Repositories\NotificationRepository;

class NotificationService
{
    private NotificationRepository $notifRepo;
    private EmailService $emailService;

    public function __construct()
    {
        $this->notifRepo = new NotificationRepository();
        $this->emailService = new EmailService();
    }

    /**
     * Notify student when application status changes.
     */
    public function applicationStatusChanged(array $application, array $student, array $scholarship, string $oldStatus, string $newStatus, ?string $notes = null, ?string $rejectionReason = null): void
    {
        $appNumber = $application['application_number'] ?? 'APP-' . $application['id'];
        $statusLabel = $this->statusLabel($newStatus);
        $scholarshipName = $scholarship['name'] ?? 'Unknown Scholarship';

        // In-app notification
        $message = "Your application {$appNumber} for \"{$scholarshipName}\" is now {$statusLabel}.";
        if ($notes) $message .= " Note: {$notes}";
        if ($rejectionReason && $newStatus === 'rejected') $message .= " Reason: {$rejectionReason}";

        $this->notifRepo->create([
            'user_id' => $student['id'],
            'type' => "application_{$newStatus}",
            'title' => "Application {$statusLabel}",
            'message' => $message,
        ]);

        // Email notification
        $this->emailService->applicationStatusChanged($student, $scholarship, $appNumber, $newStatus, $notes, $rejectionReason);
    }

    /**
     * Notify provider when a new application is received.
     */
    public function newApplication(array $application, array $student, array $scholarship, int $providerUserId): void
    {
        $appNumber = $application['application_number'] ?? 'APP-' . $application['id'];
        $studentName = $student['name'] ?? 'A student';
        $scholarshipName = $scholarship['name'] ?? 'Unknown Scholarship';

        $this->notifRepo->create([
            'user_id' => $providerUserId,
            'type' => 'new_application',
            'title' => 'New Application Received',
            'message' => "{$studentName} has applied for \"{$scholarshipName}\" ({$appNumber}).",
        ]);
    }

    /**
     * Notify student about interview schedule.
     */
    public function interviewScheduled(array $application, array $student, array $scholarship, array $interview): void
    {
        $appNumber = $application['application_number'] ?? 'APP-' . $application['id'];
        $scholarshipName = $scholarship['name'] ?? 'Unknown Scholarship';
        $date = date('d M Y, h:i A', strtotime($interview['scheduled_at']));

        $this->notifRepo->create([
            'user_id' => $student['id'],
            'type' => 'interview_scheduled',
            'title' => 'Interview Scheduled',
            'message' => "Interview for \"{$scholarshipName}\" ({$appNumber}) is scheduled for {$date}.",
        ]);

        $this->emailService->interviewScheduled($student, $scholarship, $appNumber, $interview);
    }

    /**
     * Notify student about document request.
     */
    public function documentRequested(array $application, array $student, array $scholarship, string $docType, ?string $deadline): void
    {
        $appNumber = $application['application_number'] ?? 'APP-' . $application['id'];
        $scholarshipName = $scholarship['name'] ?? 'Unknown Scholarship';

        $this->notifRepo->create([
            'user_id' => $student['id'],
            'type' => 'document_request',
            'title' => 'Documents Required',
            'message' => "Additional documents ({$docType}) requested for \"{$scholarshipName}\" ({$appNumber}).",
        ]);

        $this->emailService->documentRequest($student, $scholarship, $appNumber, $docType, $deadline);
    }

    /**
     * Notify student when application is submitted.
     */
    public function applicationSubmitted(array $application, array $student, array $scholarship): void
    {
        $appNumber = $application['application_number'] ?? 'APP-' . $application['id'];
        $scholarshipName = $scholarship['name'] ?? 'Unknown Scholarship';

        $this->notifRepo->create([
            'user_id' => $student['id'],
            'type' => 'application_submitted',
            'title' => 'Application Submitted',
            'message' => "Your application {$appNumber} for \"{$scholarshipName}\" has been submitted successfully.",
        ]);

        $this->emailService->applicationSubmitted($student, $scholarship, $appNumber);
    }

    /**
     * Notify admin about new application.
     */
    public function adminNotify(string $type, string $title, string $message): void
    {
        $userRepo = new \App\Repositories\UserRepository();
        $admins = $userRepo->getAllByRole('admin');
        foreach ($admins as $admin) {
            $this->notifRepo->create([
                'user_id' => $admin['id'],
                'type' => $type,
                'title' => $title,
                'message' => $message,
            ]);
        }
    }

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
}
