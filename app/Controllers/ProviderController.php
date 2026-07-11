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
use App\Repositories\SubscriptionRepository;

class ProviderController extends Controller
{
    private ProviderRepository $providerRepo;
    private ScholarshipRepository $scholarshipRepo;
    private ApplicationRepository $applicationRepo;
    private StaffRepository $staffRepo;
    private UserRepository $userRepo;
    private NotificationRepository $notificationRepo;
    private SubscriptionRepository $subRepo;

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
        $this->scholarshipRepo->create([
            'provider_id' => $provider['id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'min_cgpa' => $data['min_cgpa'],
            'max_income' => $data['max_income'],
            'eligible_courses' => $request->input('eligible_courses'),
            'requirements' => $request->input('requirements'),
            'deadline' => $data['deadline'],
            'status' => 'draft',
        ]);

        Session::flash('success', 'Scholarship created');
        Response::redirect('/provider/scholarships');
    }

    public function editScholarship(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $scholarship = $this->scholarshipRepo->findById($id);
        if (!$scholarship || $scholarship['provider_id'] != $provider['id']) {
            Response::redirect('/provider/scholarships');
        }
        $usage = ProviderGuard::usage();
        $this->view('provider/scholarship-form', [
            'title' => 'Edit Scholarship',
            'scholarship' => $scholarship,
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

        Session::flash('success', 'Scholarship updated');
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

        $this->view('provider/application-detail', [
            'title' => 'Application Detail',
            'app' => $app,
            'scholarship' => $scholarship,
            'logs' => $logs,
            'documents' => $attachedDocs,
            'studentDocs' => $studentDocs,
        ]);
    }

    public function updateApplicationStatus(Request $request, int $id): void
    {
        $provider = $this->getProvider();
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['provider_id'] != $provider['id']) {
            Response::redirect('/provider/applications');
        }

        $status = $request->input('status');
        $notes = $request->input('notes');
        $oldStatus = $app['status'];

        $this->applicationRepo->updateStatus($id, $status, $notes);
        $this->applicationRepo->logStatusChange($id, $oldStatus, $status, Auth::id(), $notes);

        // Notify the student
        $this->notificationRepo->create([
            'user_id' => $app['student_id'],
            'type' => 'application_status',
            'title' => 'Application ' . ucfirst($status),
            'message' => "Your application for \"{$app['scholarship_name']}\" has been updated to: " . ucfirst($status),
        ]);

        Session::flash('success', 'Application status updated');
        Response::back();
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

    // ─── SUPPORT TICKETS ─────────────────────────────────────────────────

    public function supportTickets(): void
    {
        $tickets = \App\Core\Database::connect()->prepare(
            "SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC"
        );
        $tickets->execute([Auth::id()]);
        $this->view('provider/support-tickets', [
            'title' => 'Support Tickets',
            'tickets' => $tickets->fetchAll(),
        ]);
    }

    public function createTicket(Request $request): void
    {
        $data = $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);

        $stmt = \App\Core\Database::connect()->prepare(
            "INSERT INTO support_tickets (user_id, subject, message) VALUES (?, ?, ?)"
        );
        $stmt->execute([Auth::id(), $data['subject'], $data['message']]);

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
            'subject' => 'Rating: ' . $data['rating'] . '/5',
            'message' => $data['message'],
            'rating' => (int) $data['rating'],
        ]);

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
}
