<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Helpers\ProviderGuard;
use App\Repositories\ProviderRepository;
use App\Repositories\ScholarshipRepository;
use App\Repositories\ApplicationRepository;
use App\Repositories\StaffRepository;
use App\Repositories\UserRepository;
use App\Repositories\NotificationRepository;
use App\Services\NotificationService;
use App\Repositories\SubscriptionRepository;
use App\Repositories\SupportTicketRepository;

class ProviderController extends Controller
{
    private ProviderRepository $providerRepo;
    private ScholarshipRepository $scholarshipRepo;
    private ApplicationRepository $applicationRepo;
    private StaffRepository $staffRepo;
    private UserRepository $userRepo;
    private NotificationRepository $notificationRepo;
    private SubscriptionRepository $subRepo;
    private SupportTicketRepository $ticketRepo;

    public function __construct()
    {
        parent::__construct();
        $this->providerRepo = new ProviderRepository();
        $this->scholarshipRepo = new ScholarshipRepository();
        $this->applicationRepo = new ApplicationRepository();
        $this->staffRepo = new StaffRepository();
        $this->userRepo = new UserRepository();
        $this->notificationRepo = new NotificationRepository();
        $this->subRepo = new SubscriptionRepository();
        $this->ticketRepo = new SupportTicketRepository();
    }

    private function getProvider(): array
    {
        $provider = $this->providerRepo->findByUserId(Auth::id());
        if (!$provider) {
            $providerId = $this->providerRepo->create(['user_id' => Auth::id(), 'company_name' => Auth::user()['name'] ?? 'My Company']);
            $provider = $this->providerRepo->findById($providerId);
            $this->staffRepo->create(['provider_id' => $providerId, 'user_id' => Auth::id(), 'role' => 'owner']);
        }
        return $provider;
    }

    public function dashboard(): void
    {
        $provider = $this->getProvider();
        $scholarships = $this->scholarshipRepo->findByProvider($provider['id']);
        $totalApplicants = $this->applicationRepo->countByProvider($provider['id']);
        $usage = ProviderGuard::usage();
        $appStatuses = [
            'submitted' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'submitted'),
            'screening' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'screening'),
            'shortlisted' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'shortlisted'),
            'interview' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'interview'),
            'approved' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'approved'),
            'rejected' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'rejected'),
        ];
        $recentApplicants = [];
        if (!empty($scholarships)) {
            $recentApplicants = $this->applicationRepo->findByProvider($provider['id']);
            $recentApplicants = array_slice($recentApplicants, 0, 5);
        }
        $unreadNotifications = $this->notificationRepo->countUnread(Auth::id());
        $scholarshipStatuses = [
            'open' => $this->scholarshipRepo->countByProviderAndStatus($provider['id'], 'open'),
            'pending' => $this->scholarshipRepo->countByProviderAndStatus($provider['id'], 'pending'),
            'draft' => $this->scholarshipRepo->countByProviderAndStatus($provider['id'], 'draft'),
            'closed' => $this->scholarshipRepo->countByProviderAndStatus($provider['id'], 'closed'),
        ];

        $this->view('provider/dashboard', [
            'title' => 'Provider Dashboard',
            'provider' => $provider,
            'scholarships' => $scholarships,
            'totalApplicants' => $totalApplicants,
            'usage' => $usage,
            'appStatuses' => $appStatuses,
            'scholarshipStatuses' => $scholarshipStatuses,
            'recentApplicants' => $recentApplicants,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }

    public function analytics(): void
    {
        $provider = $this->getProvider();
        $applications = $this->applicationRepo->findByProvider($provider['id']);
        $totalApps = count($applications);

        $statusCounts = [
            'submitted' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'submitted'),
            'screening' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'screening'),
            'shortlisted' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'shortlisted'),
            'interview' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'interview'),
            'approved' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'approved'),
            'rejected' => $this->applicationRepo->countByProviderAndStatus($provider['id'], 'rejected'),
        ];

        $monthlyData = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-{$i} months"));
            $months[] = $m;
            $monthlyData[$m] = 0;
        }
        foreach ($applications as $app) {
            $m = date('Y-m', strtotime($app['created_at']));
            if (isset($monthlyData[$m])) {
                $monthlyData[$m]++;
            }
        }

        $scholarships = $this->scholarshipRepo->findByProvider($provider['id']);
        $topScholarships = [];
        foreach ($scholarships as $s) {
            $count = $this->applicationRepo->countByProviderAndStatus($provider['id'], 'submitted')
                + $this->applicationRepo->countByProviderAndStatus($provider['id'], 'screening');
            // Get per-scholarship count
            $apps = $this->applicationRepo->findByScholarship($s['id']);
            $topScholarships[] = [
                'name' => $s['name'],
                'applicants' => count($apps),
                'amount' => $s['amount'],
            ];
        }
        usort($topScholarships, fn($a, $b) => $b['applicants'] - $a['applicants']);

        $this->view('provider/analytics', [
            'title' => 'Analytics',
            'totalApps' => $totalApps,
            'statusCounts' => $statusCounts,
            'monthlyData' => $monthlyData,
            'months' => $months,
            'topScholarships' => array_slice($topScholarships, 0, 10),
            'totalScholarships' => count($scholarships),
        ]);
    }

    // ─── ONBOARDING (pre-verification) ────────────────────────────────────

    public function onboarding(): void
    {
        $provider = $this->getProvider();

        // If provider is already active (verified), redirect to dashboard
        if ($provider['status'] === 'active') {
            Response::redirect('/provider/dashboard');
            return;
        }

        $this->view('provider/onboarding', [
            'title' => 'Complete Your Profile',
            'provider' => $provider,
        ]);
    }

    public function saveOnboarding(Request $request): void
    {
        $data = $request->validate([
            'company_name' => 'required|min:2',
            'registration_no' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $provider = $this->getProvider();

        // Handle document upload
        $documentPath = $provider['document'] ?? null;
        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
                $dir = 'public/uploads/documents';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $filename = 'provider_doc_' . bin2hex(random_bytes(8)) . '.' . $ext;
                move_uploaded_file($_FILES['document']['tmp_name'], "$dir/$filename");
                $documentPath = "uploads/documents/$filename";
            }
        }

        $this->providerRepo->update($provider['id'], [
            'company_name' => $data['company_name'],
            'registration_no' => $data['registration_no'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'document' => $documentPath,
        ]);

        // Notify admins
        $admins = $this->userRepo->getAllByRole('admin');
        foreach ($admins as $admin) {
            $this->notificationRepo->create([
                'user_id' => $admin['id'],
                'type' => 'provider_onboarding',
                'title' => 'Provider Awaiting Verification',
                'message' => "{$data['company_name']} has completed onboarding and is ready for review.",
            ]);
        }

        Session::flash('success', 'Your profile has been submitted for verification. You will be notified once approved.');
        Response::redirect('/provider/dashboard');
    }

    public function scholarships(): void
    {
        $provider = $this->getProvider();
        $scholarships = $this->scholarshipRepo->findByProvider($provider['id']);
        $usage = ProviderGuard::usage();
        $this->view('provider/scholarships', [
            'title' => 'My Scholarships',
            'scholarships' => $scholarships,
            'usage' => $usage,
        ]);
    }

    public function createScholarship(): void
    {
        ProviderGuard::requireActiveSub();
        ProviderGuard::canCreateScholarship();
        $usage = ProviderGuard::usage();
        $this->view('provider/scholarship-form', [
            'title' => 'Create Scholarship',
            'scholarship' => null,
            'usage' => $usage,
        ]);
    }

    public function storeScholarship(Request $request): void
    {
        ProviderGuard::requireActiveSub();
        ProviderGuard::canCreateScholarship();

        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'min_cgpa' => 'required',
            'max_income' => 'required',
            'deadline' => 'required',
        ]);

        $provider = $this->getProvider();
        $scholarshipId = $this->scholarshipRepo->create([
            'provider_id' => $provider['id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'min_cgpa' => $data['min_cgpa'],
            'max_income' => $data['max_income'],
            'eligible_courses' => $request->input('eligible_courses'),
            'requirements' => $request->input('requirements'),
            'deadline' => $data['deadline'],
            'status' => 'pending',
        ]);

        // Handle document uploads
        $this->handleDocumentUploads($request, $scholarshipId);

        // Notify the provider that the scholarship was submitted
        $this->notificationRepo->create([
            'user_id' => Auth::id(),
            'type' => 'scholarship_submitted',
            'title' => 'Scholarship Submitted for Review',
            'message' => "Your scholarship \"{$data['name']}\" has been submitted for admin review. You'll be notified once it's approved.",
        ]);

        // Notify all admins
        $this->notifyAdminsNewScholarship($data['name'], $provider['company_name'] ?? 'A provider');

        Session::flash('success', 'Scholarship submitted for admin review. You will be notified once approved.');
        Response::redirect('/provider/scholarships');
    }

    public function editScholarship(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $scholarship = $this->scholarshipRepo->findById($id);
        if (!$scholarship || $scholarship['provider_id'] != $provider['id']) {
            Response::redirect('/provider/scholarships');
        }
        $documents = $this->scholarshipRepo->getDocuments($id);
        $usage = ProviderGuard::usage();
        $this->view('provider/scholarship-form', [
            'title' => 'Edit Scholarship',
            'scholarship' => $scholarship,
            'documents' => $documents,
            'usage' => $usage,
        ]);
    }

    public function updateScholarship(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $scholarship = $this->scholarshipRepo->findById($id);
        if (!$scholarship || $scholarship['provider_id'] != $provider['id']) {
            Response::redirect('/provider/scholarships');
        }

        $this->scholarshipRepo->update($id, [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'min_cgpa' => $request->input('min_cgpa'),
            'max_income' => $request->input('max_income'),
            'eligible_courses' => $request->input('eligible_courses'),
            'requirements' => $request->input('requirements'),
            'deadline' => $request->input('deadline'),
        ]);

        // Handle document uploads
        $this->handleDocumentUploads($request, $id);

        // If scholarship was rejected (closed), re-submit for review
        if (in_array($scholarship['status'], ['draft', 'closed'])) {
            $this->scholarshipRepo->update($id, ['status' => 'pending']);
            $this->notifyAdminsNewScholarship($scholarship['name'], $provider['company_name'] ?? 'A provider');
            Session::flash('success', 'Scholarship submitted for admin review.');
        } else {
            Session::flash('success', 'Scholarship updated');
        }
        Response::redirect('/provider/scholarships');
    }

    public function deleteScholarship(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $scholarship = $this->scholarshipRepo->findById($id);
        if ($scholarship && $scholarship['provider_id'] == $provider['id']) {
            $this->scholarshipRepo->delete($id);
        }
        Session::flash('success', 'Scholarship deleted');
        Response::back();
    }

    public function toggleScholarship(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $scholarship = $this->scholarshipRepo->findById($id);
        if ($scholarship && $scholarship['provider_id'] == $provider['id']) {
            // Don't toggle pending scholarships — they need admin approval first
            if ($scholarship['status'] === 'pending') {
                Session::flash('error', 'This scholarship is pending admin review and cannot be toggled.');
                Response::back();
            }
            $newStatus = $scholarship['status'] === 'open' ? 'closed' : 'open';
            $this->scholarshipRepo->update($id, ['status' => $newStatus]);
        }
        Session::flash('success', 'Scholarship updated');
        Response::back();
    }

    public function applicants(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $scholarship = $this->scholarshipRepo->findById($id);
        if (!$scholarship || $scholarship['provider_id'] != $provider['id']) {
            Response::redirect('/provider/scholarships');
        }
        $applicants = $this->applicationRepo->findByScholarship($id);
        $this->view('provider/applicants', [
            'title' => 'Applicants - ' . $scholarship['name'],
            'scholarship' => $scholarship,
            'applicants' => $applicants,
        ]);
    }

    public function applicationDetail(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['provider_id'] != $provider['id']) {
            Response::redirect('/provider/applications');
        }
        $logs = $this->applicationRepo->getStatusLogs($id);
        $interview = $this->applicationRepo->getInterview($id);
        $essayRequest = $this->applicationRepo->getEssayRequest($id);
        $scholarship = $this->scholarshipRepo->findById($app['scholarship_id']);

        // Get documents attached to this application
        $docStmt = \App\Core\Database::connect()->prepare(
            "SELECT d.* FROM documents d
             JOIN application_documents ad ON d.id = ad.document_id
             WHERE ad.application_id = ?"
        );
        $docStmt->execute([$id]);
        $attachedDocs = $docStmt->fetchAll();

        // Get ALL documents the student has uploaded (document vault)
        $vaultStmt = \App\Core\Database::connect()->prepare(
            "SELECT * FROM documents WHERE user_id = ? ORDER BY created_at DESC"
        );
        $vaultStmt->execute([$app['student_id']]);
        $studentDocs = $vaultStmt->fetchAll();

        // Internal notes
        $notes = $this->applicationRepo->getNotes($id);

        // Document requests
        $docRequests = $this->applicationRepo->getDocumentRequests($id);

        $this->view('provider/application-detail', [
            'title' => 'Application Detail',
            'app' => $app,
            'scholarship' => $scholarship,
            'logs' => $logs,
            'documents' => $attachedDocs,
            'studentDocs' => $studentDocs,
            'interview' => $interview,
            'essayRequest' => $essayRequest,
            'notes' => $notes,
            'docRequests' => $docRequests,
        ]);
    }

    public function updateApplicationStatus(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['provider_id'] != $provider['id']) {
            Response::redirect('/provider/applications');
        }

        $status = (string) $request->input('status');
        $notes = trim((string) $request->input('notes'));
        $communicationUrl = trim((string) $request->input('communication_url'));
        $rejectionReason = trim((string) $request->input('rejection_reason'));
        $allowedStatuses = ['screening', 'shortlisted', 'interview', 'document_required', 'approved', 'rejected'];
        if (!in_array($status, $allowedStatuses, true)) {
            Session::flash('error', 'Choose a valid application status.');
            Response::back();
        }
        if ($communicationUrl && !filter_var($communicationUrl, FILTER_VALIDATE_URL)) {
            Session::flash('error', 'Enter a valid URL.');
            Response::back();
        }
        $oldStatus = $app['status'];

        $updateData = ['status' => $status];
        if ($notes) $updateData['notes'] = $notes;
        if ($communicationUrl) $updateData['communication_url'] = $communicationUrl;
        if ($rejectionReason && $status === 'rejected') $updateData['rejection_reason'] = $rejectionReason;
        if ($status === 'approved') $updateData['awarded_at'] = date('Y-m-d H:i:s');

        $this->applicationRepo->update($id, $updateData);
        $this->applicationRepo->logStatusChange($id, $oldStatus, $status, Auth::id(), $notes ?: $rejectionReason);

        // Use NotificationService for combined in-app + email
        $notifService = new NotificationService();
        $student = (new \App\Repositories\UserRepository())->findById($app['student_id']);
        $scholarship = $this->scholarshipRepo->findById($app['scholarship_id']);

        if ($student && $scholarship) {
            $notifService->applicationStatusChanged($app, $student, $scholarship, $oldStatus, $status, $notes, $rejectionReason);
        }

        Session::flash('success', 'Application status updated to ' . ucfirst(str_replace('_', ' ', $status)));
        Response::back();
    }

    public function scheduleInterview(Request $request, int $id): void
    {
        $app = $this->providerApplicationOrRedirect($id);
        $scheduledAt = (string) $request->input('scheduled_at');
        $meetingUrl = trim((string) $request->input('meeting_url'));
        $instructions = trim((string) $request->input('instructions'));
        $date = \DateTime::createFromFormat('Y-m-d\\TH:i', $scheduledAt);
        $dateErrors = \DateTime::getLastErrors();
        $validDate = $date && (!$dateErrors || ($dateErrors['warning_count'] === 0 && $dateErrors['error_count'] === 0));
        if (!$validDate || !$meetingUrl || !filter_var($meetingUrl, FILTER_VALIDATE_URL)) {
            Session::flash('error', 'Provide the interview date, time, and a valid meeting link.');
            Response::back();
        }
        $this->applicationRepo->saveInterview($id, $date->format('Y-m-d H:i:s'), $meetingUrl, $instructions, Auth::id());
        $this->applicationRepo->update($id, ['status' => 'interview']);
        $this->applicationRepo->logStatusChange($id, $app['status'], 'interview', Auth::id(), 'Interview scheduled');

        // Use NotificationService
        $notifService = new NotificationService();
        $student = (new \App\Repositories\UserRepository())->findById($app['student_id']);
        $scholarship = $this->scholarshipRepo->findById($app['scholarship_id']);
        $interview = $this->applicationRepo->getInterview($id);

        if ($student && $scholarship && $interview) {
            $notifService->interviewScheduled($app, $student, $scholarship, $interview);
        }

        Session::flash('success', 'Interview saved and the student has been notified.');
        Response::back();
    }

    public function requestEssay(Request $request, int $id): void
    {
        $app = $this->providerApplicationOrRedirect($id);
        $prompt = trim((string) $request->input('prompt'));
        $dueAt = (string) $request->input('due_at');
        if ($prompt === '') {
            Session::flash('error', 'Add the essay question or instructions.');
            Response::back();
        }
        $existingRequest = $this->applicationRepo->getEssayRequest($id);
        if (!empty($existingRequest['submitted_at'])) {
            Session::flash('error', 'This essay has already been submitted and cannot be changed.');
            Response::back();
        }
        if ($dueAt && !\DateTime::createFromFormat('Y-m-d\\TH:i', $dueAt)) {
            Session::flash('error', 'Enter a valid essay due date.');
            Response::back();
        }
        $this->applicationRepo->saveEssayRequest($id, $prompt, $dueAt ? date('Y-m-d H:i:s', strtotime($dueAt)) : null, Auth::id());
        $message = 'An essay has been requested for "' . $app['scholarship_name'] . '". Open your application to read the prompt and submit your response.';
        $this->notificationRepo->create(['user_id' => $app['student_id'], 'type' => 'essay_request', 'title' => 'Essay requested', 'message' => $message]);
        Session::flash('success', 'Essay request sent to the student.');
        Response::back();
    }

    private function providerApplicationOrRedirect(int $id): array
    {
        $app = $this->applicationRepo->findById($id);
        $provider = $this->getProvider();
        if (!$app || $app['provider_id'] != $provider['id']) {
            Response::redirect('/provider/applications');
        }
        return $app;
    }

    private function moveAndNotify(array $app, string $status, string $message): void
    {
        if ($app['status'] !== $status) {
            $this->applicationRepo->updateStatus($app['id'], $status);
            $this->applicationRepo->logStatusChange($app['id'], $app['status'], $status, Auth::id());
        }
        $this->notificationRepo->create(['user_id' => $app['student_id'], 'type' => 'application_status', 'title' => 'Application ' . ucfirst($status), 'message' => $message]);
    }

    private function statusMessage(string $scholarshipName, string $status, string $notes, string $communicationUrl): string
    {
        $messages = [
            'shortlisted' => 'Congratulations! You have been shortlisted for',
            'rejected' => 'Your application was not selected for',
            'approved' => 'Great news! Your application has been approved for',
            'interview' => 'You have been invited to interview for',
            'screening' => 'Your application is now being reviewed for',
        ];
        $message = ($messages[$status] ?? 'Your application has been updated for') . ' "' . $scholarshipName . '".';
        if ($notes !== '') $message .= ' Note: ' . $notes;
        if ($status === 'approved' && $communicationUrl !== '') $message .= ' Join the provider group here: ' . $communicationUrl;
        return $message;
    }

    public function allApplications(): void
    {
        $provider = $this->getProvider();
        $applications = $this->applicationRepo->findByProvider($provider['id']);
        $this->view('provider/applications', [
            'title' => 'All Applications',
            'applications' => $applications,
        ]);
    }

    public function staff(): void
    {
        $provider = $this->getProvider();
        $staff = $this->staffRepo->findByProvider($provider['id']);
        $usage = ProviderGuard::usage();
        $this->view('provider/staff', [
            'title' => 'Staff Management',
            'staff' => $staff,
            'usage' => $usage,
        ]);
    }

    public function inviteStaff(Request $request): void
    {
        ProviderGuard::requireActiveSub();
        ProviderGuard::canInviteStaff();

        $provider = $this->getProvider();
        $data = $request->validate(['email' => 'required|email', 'role' => 'required']);
        $user = $this->userRepo->findByEmail($data['email']);

        if (!$user) {
            Session::flash('error', 'User not found. They must register first.');
            Response::back();
        }

        $this->staffRepo->create([
            'provider_id' => $provider['id'],
            'user_id' => $user['id'],
            'role' => $data['role'],
        ]);

        Session::flash('success', 'Staff invited successfully');
        Response::back();
    }

    public function removeStaff(Request $request, int $id): void
    {
        $this->staffRepo->delete($id);
        Session::flash('success', 'Staff removed');
        Response::back();
    }

    public function profile(): void
    {
        $provider = $this->getProvider();
        $user = Auth::user();
        $this->view('provider/profile', [
            'title' => 'Provider Profile',
            'provider' => $provider,
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request): void
    {
        $provider = $this->getProvider();
        $data = $request->validate([
            'company_name' => 'required',
            'registration_no' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $this->providerRepo->update($provider['id'], [
            'company_name' => $data['company_name'],
            'registration_no' => $data['registration_no'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

        Session::flash('success', 'Profile updated');
        Response::redirect('/provider/profile');
    }

    // ─── NOTIFICATIONS ───────────────────────────────────────────────────

    public function notifications(): void
    {
        $notifications = $this->notificationRepo->findByUser(Auth::id());
        $this->view('provider/notifications', [
            'title' => 'Notifications',
            'notifications' => $notifications,
        ]);
    }

    public function markNotificationRead(Request $request, int $id): void
    {
        $this->notificationRepo->markAsRead($id, Auth::id());
        Response::back();
    }

    public function unreadCount(): void
    {
        $count = $this->notificationRepo->countUnread(Auth::id());
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }

    public function markAllRead(): void
    {
        $this->notificationRepo->markAllAsRead(Auth::id());
        Session::flash('success', 'All notifications marked as read');
        Response::back();
    }

    // ─── SUPPORT TICKETS ─────────────────────────────────────────────────

    public function supportTickets(): void
    {
        $tickets = $this->ticketRepo->findByUser(Auth::id());
        $this->view('provider/support-tickets', [
            'title' => 'Support Tickets',
            'tickets' => $tickets,
        ]);
    }

    public function createTicket(Request $request): void
    {
        $data = $request->validate([
            'subject' => 'required|min:3',
            'message' => 'required|min:10',
        ]);

        $ticketId = $this->ticketRepo->create([
            'user_id' => Auth::id(),
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        $userName = Auth::user()['name'] ?? 'Provider';

        $this->notificationRepo->create([
            'user_id' => Auth::id(),
            'type' => 'ticket_confirmation',
            'title' => 'Support Ticket Submitted',
            'message' => "Your support ticket '{$data['subject']}' has been submitted. We'll get back to you soon.",
        ]);

        $admins = $this->userRepo->getAllByRole('admin');
        foreach ($admins as $admin) {
            $this->notificationRepo->create([
                'user_id' => $admin['id'],
                'type' => 'new_ticket',
                'title' => 'New Support Ticket',
                'message' => "New ticket from {$userName}: {$data['subject']}",
            ]);
        }

        Session::flash('success', 'Support ticket created');
        Response::redirect('/provider/support-tickets');
    }

    public function feedback(): void
    {
        $this->view('provider/feedback', ['title' => 'Submit Feedback']);
    }

    public function submitFeedback(Request $request): void
    {
        $data = $request->validate([
            'rating' => 'required',
            'message' => 'required|min:5',
        ]);

        $repo = new \App\Repositories\FeedbackRepository();
        $repo->create([
            'user_id' => Auth::id(),
            'type' => 'general',
            'message' => $data['message'],
            'rating' => (int) $data['rating'],
        ]);

        $userName = Auth::user()['name'] ?? 'Provider';

        $this->notificationRepo->create([
            'user_id' => Auth::id(),
            'type' => 'feedback_confirmation',
            'title' => 'Feedback Received',
            'message' => 'Thank you for your feedback! We appreciate your input.',
        ]);

        $admins = $this->userRepo->getAllByRole('admin');
        foreach ($admins as $admin) {
            $this->notificationRepo->create([
                'user_id' => $admin['id'],
                'type' => 'new_feedback',
                'title' => 'New Feedback Submitted',
                'message' => "New feedback from {$userName} (Rating: {$data['rating']}/5)",
            ]);
        }

        Session::flash('success', 'Thank you for your feedback!');
        Response::redirect('/provider/feedback');
    }

    // ─── SETTINGS ────────────────────────────────────────────────────────

    public function settings(): void
    {
        $user = Auth::user();
        $usage = ProviderGuard::usage();
        $this->view('provider/settings', [
            'title' => 'Settings',
            'user' => $user,
            'usage' => $usage,
        ]);
    }

    public function updateSettings(Request $request): void
    {
        $user = Auth::user();
        $name = $request->input('name');

        if ($name) {
            $this->userRepo->update($user['id'], ['name' => $name]);
        }

        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('confirm_password');

        if ($currentPassword && $newPassword && $confirmPassword) {
            if (!password_verify($currentPassword, $user['password'])) {
                Session::flash('error', 'Current password is incorrect');
                Response::redirect('/provider/settings');
            }
            if ($newPassword !== $confirmPassword) {
                Session::flash('error', 'New passwords do not match');
                Response::redirect('/provider/settings');
            }
            $this->userRepo->update($user['id'], ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);
        }

        Session::flash('success', 'Settings updated');
        Response::redirect('/provider/settings');
    }

    // ─── SCHOLARSHIP DOCUMENTS ─────────────────────────────────────────────

    public function deleteScholarshipDoc(Request $request, int $id): void
    {
        $doc = $this->scholarshipRepo->findDocumentById($id);
        if (!$doc) { Response::back(); return; }

        $scholarship = $this->scholarshipRepo->findById($doc['scholarship_id']);
        $provider = $this->getProvider();
        if (!$scholarship || $scholarship['provider_id'] != $provider['id']) {
            Response::redirect('/provider/scholarships');
        }

        $filePath = __DIR__ . '/../../public/uploads/scholarship_docs/' . $doc['stored_name'];
        if (file_exists($filePath)) unlink($filePath);
        $this->scholarshipRepo->deleteDocument($id);
        Session::flash('success', 'Document removed');
        Response::back();
    }

    // ─── HELPERS ───────────────────────────────────────────────────────────

    private function handleDocumentUploads(Request $request, int $scholarshipId): void
    {
        $files = $request->file('documents');
        if (!$files || !is_array($files['name']) || empty(array_filter($files['name']))) return;

        $dir = __DIR__ . '/../../public/uploads/scholarship_docs';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        foreach ($files['name'] as $i => $name) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK || empty($name)) continue;

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $stored = uniqid('schdoc_') . '.' . $ext;
            move_uploaded_file($files['tmp_name'][$i], "$dir/$stored");

            $this->scholarshipRepo->addDocument(
                $scholarshipId,
                $name,
                $stored,
                $files['size'][$i],
                $files['type'][$i]
            );
        }
    }

    private function notifyAdminsNewScholarship(string $scholarshipName, string $companyName): void
    {
        $admins = $this->userRepo->getAllByRole('admin');
        foreach ($admins as $admin) {
            $this->notificationRepo->create([
                'user_id' => $admin['id'],
                'type' => 'scholarship_review',
                'title' => 'New Scholarship Pending Review',
                'message' => "{$companyName} has submitted \"{$scholarshipName}\" for approval.",
            ]);
        }
    }

    // ─── BULK ACTIONS & EXPORT ────────────────────────────────────────────

    public function bulkAction(Request $request): void
    {
        $provider = $this->getProvider();
        $action = $request->input('bulk_action');
        $ids = $request->input('application_ids', []);

        if (empty($ids) || !is_array($ids)) {
            Session::flash('error', 'No applications selected');
            Response::back();
        }

        // Verify ownership
        $validIds = [];
        foreach ($ids as $id) {
            $app = $this->applicationRepo->findById((int) $id);
            if ($app && $app['provider_id'] == $provider['id']) {
                $validIds[] = (int) $id;
            }
        }

        if ($action === 'shortlist') {
            $this->applicationRepo->bulkUpdateStatus($validIds, 'shortlisted', Auth::id(), 'Bulk shortlisted');
            Session::flash('success', count($validIds) . ' applications shortlisted');
        } elseif ($action === 'reject') {
            $this->applicationRepo->bulkUpdateStatus($validIds, 'rejected', Auth::id(), 'Bulk rejected');
            Session::flash('success', count($validIds) . ' applications rejected');
        }

        Response::back();
    }

    public function exportApplications(Request $request): void
    {
        $provider = $this->getProvider();
        $status = $request->get('status');
        $data = $this->applicationRepo->getExportData($status, $provider['id']);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=applications_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
        fputcsv($output, ['Application No', 'Student Name', 'Email', 'Scholarship', 'Amount', 'CGPA', 'Course', 'University', 'Status', 'Applied Date']);

        foreach ($data as $row) {
            fputcsv($output, [
                $row['application_number'] ?? 'APP-' . $row['id'],
                $row['student_name'],
                $row['student_email'],
                $row['scholarship_name'],
                $row['amount'],
                json_decode($row['cgpa'] ?? 'null', true) ?? '',
                json_decode($row['course'] ?? 'null', true) ?? '',
                json_decode($row['university'] ?? 'null', true) ?? '',
                $row['status'],
                date('d M Y', strtotime($row['created_at'])),
            ]);
        }

        fclose($output);
        exit;
    }

    public function addNote(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['provider_id'] != $provider['id']) {
            Response::redirect('/provider/applications');
        }

        $note = trim((string) $request->input('note'));
        if ($note === '') {
            Session::flash('error', 'Note cannot be empty');
            Response::back();
        }

        $this->applicationRepo->addNote($id, Auth::id(), $note);
        Session::flash('success', 'Note added');
        Response::back();
    }

    public function requestDocuments(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['provider_id'] != $provider['id']) {
            Response::redirect('/provider/applications');
        }

        $docType = trim((string) $request->input('document_type'));
        $description = trim((string) $request->input('description'));
        $deadline = $request->input('deadline') ?: null;

        if ($docType === '') {
            Session::flash('error', 'Document type is required');
            Response::back();
        }

        $this->applicationRepo->createDocumentRequest($id, $docType, $description, $deadline, Auth::id());
        $this->applicationRepo->update($id, ['status' => 'document_required']);
        $this->applicationRepo->logStatusChange($id, $app['status'], 'document_required', Auth::id(), "Document requested: {$docType}");

        // Notify student
        $notifService = new NotificationService();
        $student = $this->userRepo->findById($app['student_id']);
        $scholarship = $this->scholarshipRepo->findById($app['scholarship_id']);
        if ($student && $scholarship) {
            $notifService->documentRequested($app, $student, $scholarship, $docType, $deadline);
        }

        Session::flash('success', 'Document request sent to student');
        Response::back();
    }
}
