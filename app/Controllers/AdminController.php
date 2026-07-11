<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Helpers\AuditLog;
use App\Repositories\UserRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\ScholarshipRepository;
use App\Repositories\ApplicationRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\AuditLogRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\AnnouncementRepository;
use App\Repositories\SupportTicketRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\LoginAttemptRepository;
use App\Repositories\StudentSubscriptionRepository;

class AdminController extends Controller
{
    private UserRepository $userRepo;
    private ProviderRepository $providerRepo;
    private ScholarshipRepository $scholarshipRepo;
    private ApplicationRepository $applicationRepo;
    private PaymentRepository $paymentRepo;
    private SubscriptionRepository $subRepo;
    private AuditLogRepository $auditRepo;
    private SettingsRepository $settingsRepo;
    private AnnouncementRepository $announcementRepo;
    private SupportTicketRepository $ticketRepo;
    private FeedbackRepository $feedbackRepo;
    private LoginAttemptRepository $loginAttemptRepo;
    private StudentSubscriptionRepository $studentSubRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
        $this->providerRepo = new ProviderRepository();
        $this->scholarshipRepo = new ScholarshipRepository();
        $this->applicationRepo = new ApplicationRepository();
        $this->paymentRepo = new PaymentRepository();
        $this->subRepo = new SubscriptionRepository();
        $this->auditRepo = new AuditLogRepository();
        $this->settingsRepo = new SettingsRepository();
        $this->announcementRepo = new AnnouncementRepository();
        $this->ticketRepo = new SupportTicketRepository();
        $this->feedbackRepo = new FeedbackRepository();
        $this->loginAttemptRepo = new LoginAttemptRepository();
        $this->studentSubRepo = new StudentSubscriptionRepository();
    }

    // ─── 1. DASHBOARD ───────────────────────────────────────────────────────

    public function dashboard(): void
    {
        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'totalUsers' => $this->userRepo->count(),
            'totalStudents' => $this->userRepo->countByRole('student'),
            'totalProviders' => $this->providerRepo->count(),
            'verifiedProviders' => $this->providerRepo->countVerified(),
            'totalScholarships' => $this->scholarshipRepo->count(),
            'activeScholarships' => $this->scholarshipRepo->countByStatus('open'),
            'totalApplications' => $this->applicationRepo->count(),
            'totalRevenue' => $this->paymentRepo->totalRevenue(),
            'recentUsers' => $this->userRepo->getRecent(5),
            'recentApplications' => $this->applicationRepo->getAll(),
            'registrationTrend' => $this->userRepo->getRegistrationTrend(6),
            'applicationTrend' => $this->applicationRepo->getApplicationTrend(6),
            'topScholarships' => $this->applicationRepo->getTopScholarships(5),
            'revenueTrend' => $this->paymentRepo->getRevenueTrend(),
            'bannedUsers' => $this->userRepo->countByStatus('banned'),
            'openTickets' => $this->ticketRepo->count(),
        ]);
    }

    // ─── 2. USER MANAGEMENT ─────────────────────────────────────────────────

    public function users(): void
    {
        $role = $this->input('role');
        $status = $this->input('status');
        $search = $this->input('search');

        if ($search) {
            $allUsers = $this->userRepo->search($search, $role, $status);
        } elseif ($role) {
            $allUsers = $this->userRepo->getAllByRole($role);
        } else {
            $allUsers = $this->userRepo->getAll();
        }

        if ($status && !$search) {
            $allUsers = array_filter($allUsers, fn($u) => $u['status'] === $status);
        }

        $this->view('admin/users', [
            'title' => 'User Management',
            'users' => $allUsers,
            'totalUsers' => $this->userRepo->count(),
            'totalStudents' => $this->userRepo->countByRole('student'),
            'totalProviders' => $this->userRepo->countByRole('provider'),
            'totalAdmins' => $this->userRepo->countByRole('admin'),
            'activeUsers' => $this->userRepo->countByStatus('active'),
            'bannedUsers' => $this->userRepo->countByStatus('banned'),
        ]);
    }

    public function students(): void
    {
        $search = $this->input('search');
        $status = $this->input('status');

        if ($search) {
            $allUsers = $this->userRepo->search($search, 'student', $status);
        } else {
            $allUsers = $this->userRepo->getAllByRole('student');
        }

        if ($status && !$search) {
            $allUsers = array_filter($allUsers, fn($u) => $u['status'] === $status);
        }

        $this->view('admin/students', [
            'title' => 'Students',
            'users' => $allUsers,
            'totalStudents' => $this->userRepo->countByRole('student'),
            'activeUsers' => $this->userRepo->countByStatus('active'),
            'bannedUsers' => $this->userRepo->countByStatus('banned'),
        ]);
    }

    public function userProviders(): void
    {
        $search = $this->input('search');
        $status = $this->input('status');

        if ($search) {
            $allUsers = $this->userRepo->search($search, 'provider', $status);
        } else {
            $allUsers = $this->userRepo->getAllByRole('provider');
        }

        if ($status && !$search) {
            $allUsers = array_filter($allUsers, fn($u) => $u['status'] === $status);
        }

        $this->view('admin/user_providers', [
            'title' => 'Provider Accounts',
            'users' => $allUsers,
            'totalProviders' => $this->userRepo->countByRole('provider'),
            'activeUsers' => $this->userRepo->countByStatus('active'),
            'bannedUsers' => $this->userRepo->countByStatus('banned'),
        ]);
    }

    public function userDetail(Request $request, int $id): void
    {
        $user = $this->userRepo->findById($id);
        if (!$user) { $this->notFound(); return; }

        $this->view('admin/user_detail', [
            'title' => "User: {$user['name']}",
            'user' => $user,
            'activities' => $this->auditRepo->getByUser($id, 20),
        ]);
    }

    public function userToggleStatus(Request $request, int $id): void
    {
        $user = $this->userRepo->findById($id);
        if ($user) {
            $newStatus = match ($user['status']) {
                'active' => 'inactive',
                'inactive' => 'active',
                default => 'active',
            };
            $this->userRepo->update($id, ['status' => $newStatus]);
            AuditLog::log('user_status_toggle', 'user', $id, "User {$user['name']} → {$newStatus}", ['status' => $user['status']], ['status' => $newStatus]);
            Session::flash('success', "User {$user['name']} is now {$newStatus}");
        }
        Response::back();
    }

    public function userBan(Request $request, int $id): void
    {
        $user = $this->userRepo->findById($id);
        if ($user && $user['role'] !== 'admin') {
            $this->userRepo->update($id, ['status' => 'banned']);
            AuditLog::log('user_banned', 'user', $id, "User {$user['name']} banned");
            Session::flash('success', "User {$user['name']} has been banned");
        }
        Response::back();
    }

    public function userDelete(Request $request, int $id): void
    {
        $user = $this->userRepo->findById($id);
        if ($user && $user['role'] !== 'admin') {
            AuditLog::log('user_deleted', 'user', $id, "User {$user['name']} deleted", $user, null);
            $this->userRepo->delete($id);
            Session::flash('success', "User {$user['name']} deleted");
        }
        Response::back();
    }

    public function userResetPassword(Request $request, int $id): void
    {
        $user = $this->userRepo->findById($id);
        if ($user) {
            $newPassword = bin2hex(random_bytes(4));
            $this->userRepo->update($id, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);
            AuditLog::log('password_reset', 'user', $id, "Password reset for {$user['name']}");
            Session::flash('success', "Password reset to: {$newPassword}");
        }
        Response::back();
    }

    // ─── 3. SCHOLARSHIP MANAGEMENT ──────────────────────────────────────────

    public function scholarships(): void
    {
        $status = $this->input('status');
        $all = $status ? $this->scholarshipRepo->getByStatus($status) : $this->scholarshipRepo->getAll();
        $this->view('admin/scholarships', [
            'title' => 'Scholarship Management',
            'scholarships' => $all,
            'totalScholarships' => $this->scholarshipRepo->count(),
            'activeCount' => $this->scholarshipRepo->countByStatus('open'),
            'draftCount' => $this->scholarshipRepo->countByStatus('draft'),
            'closedCount' => $this->scholarshipRepo->countByStatus('closed'),
            'totalApplicants' => $this->applicationRepo->count(),
        ]);
    }

    public function scholarshipDetail(Request $request, int $id): void
    {
        $s = $this->scholarshipRepo->findById($id);
        if (!$s) { $this->notFound(); return; }
        $this->view('admin/scholarship_detail', [
            'title' => "Scholarship: {$s['name']}",
            's' => $s,
            'applicants' => $this->applicationRepo->findByScholarship($id),
        ]);
    }

    public function scholarshipToggleStatus(Request $request, int $id): void
    {
        $s = $this->scholarshipRepo->findById($id);
        if ($s) {
            $newStatus = match ($s['status']) {
                'open' => 'closed',
                'closed' => 'open',
                'draft' => 'open',
                default => 'open',
            };
            $this->scholarshipRepo->update($id, ['status' => $newStatus]);
            AuditLog::log('scholarship_status', 'scholarship', $id, "{$s['name']} → {$newStatus}");
            Session::flash('success', "Scholarship {$s['name']} is now {$newStatus}");
        }
        Response::back();
    }

    public function scholarshipEdit(Request $request, int $id): void
    {
        $s = $this->scholarshipRepo->findById($id);
        if (!$s) { $this->notFound(); return; }

        $data = $request->validate([
            'name' => 'required|min:3',
            'amount' => 'required',
            'deadline' => 'required',
        ]);

        $old = $s;
        $this->scholarshipRepo->update($id, [
            'name' => $data['name'],
            'amount' => $data['amount'],
            'deadline' => $data['deadline'],
            'description' => $request->input('description'),
            'min_cgpa' => $request->input('min_cgpa'),
            'max_income' => $request->input('max_income'),
        ]);

        AuditLog::log('scholarship_edited', 'scholarship', $id, "{$s['name']} edited by admin", $old, $this->scholarshipRepo->findById($id));
        Session::flash('success', 'Scholarship updated');
        Response::back();
    }

    public function scholarshipDelete(Request $request, int $id): void
    {
        $s = $this->scholarshipRepo->findById($id);
        if ($s) {
            AuditLog::log('scholarship_deleted', 'scholarship', $id, "{$s['name']} deleted", $s, null);
            $this->scholarshipRepo->delete($id);
            Session::flash('success', 'Scholarship deleted');
        }
        Response::back();
    }

    // ─── 4. PROVIDER MANAGEMENT ─────────────────────────────────────────────

    public function providers(): void
    {
        $filter = $this->input('filter');
        $providers = match ($filter) {
            'verified' => $this->providerRepo->getVerified(),
            'unverified' => $this->providerRepo->getUnverified(),
            default => $this->providerRepo->getAll(),
        };
        $totalProviders = $this->providerRepo->count();
        $verifiedCount = $this->providerRepo->countVerified();
        $this->view('admin/providers', [
            'title' => 'Provider Management',
            'providers' => $providers,
            'verifiedCount' => $verifiedCount,
            'unverifiedCount' => $totalProviders - $verifiedCount,
            'totalProviders' => $totalProviders,
            'staffCount' => (int) (\App\Core\Database::connect())->query("SELECT COUNT(*) FROM staff")->fetchColumn(),
        ]);
    }

    public function providerDetail(Request $request, int $id): void
    {
        $p = $this->providerRepo->findById($id);
        if (!$p) { $this->notFound(); return; }
        $this->view('admin/provider_detail', [
            'title' => "Provider: {$p['company_name']}",
            'p' => $p,
            'staff' => $this->providerRepo->getStaff($id),
            'scholarships' => $this->scholarshipRepo->findByProvider($id),
        ]);
    }

    public function providerVerify(Request $request, int $id): void
    {
        $p = $this->providerRepo->findById($id);
        if ($p) {
            $this->providerRepo->update($id, [
                'is_verified' => !$p['is_verified'],
                'status' => $p['is_verified'] ? 'pending' : 'active',
            ]);
            AuditLog::log('provider_verify', 'provider', $id, "{$p['company_name']} verification " . ($p['is_verified'] ? 'revoked' : 'approved'));
            Session::flash('success', "Provider {$p['company_name']} " . ($p['is_verified'] ? 'unverified' : 'verified'));
        }
        Response::back();
    }

    public function providerSuspend(Request $request, int $id): void
    {
        $p = $this->providerRepo->findById($id);
        if ($p) {
            $newStatus = $p['status'] === 'suspended' ? 'active' : 'suspended';
            $this->providerRepo->update($id, ['status' => $newStatus]);
            AuditLog::log('provider_suspend', 'provider', $id, "{$p['company_name']} → {$newStatus}");
            Session::flash('success', "Provider {$p['company_name']} {$newStatus}");
        }
        Response::back();
    }

    public function providerRemoveStaff(Request $request, int $providerId, int $staffId): void
    {
        $p = $this->providerRepo->findById($providerId);
        if ($p) {
            $db = (new \App\Core\Database)::connect();
            $db->prepare("DELETE FROM staff WHERE id = ? AND provider_id = ?")->execute([$staffId, $providerId]);
            AuditLog::log('provider_staff_removed', 'provider', $providerId, "Staff #{$staffId} removed from {$p['company_name']}");
            Session::flash('success', 'Staff removed');
        }
        Response::back();
    }

    // ─── 5. APPLICATION MONITORING ──────────────────────────────────────────

    public function applications(): void
    {
        $status = $this->input('status');
        $scholarshipId = $this->input('scholarship_id');
        $apps = $this->applicationRepo->filter($status, $scholarshipId ? (int)$scholarshipId : null);
        $this->view('admin/applications', [
            'title' => 'Application Monitoring',
            'applications' => $apps,
            'scholarships' => $this->scholarshipRepo->getAll(),
            'totalApplications' => $this->applicationRepo->count(),
            'pendingCount' => $this->applicationRepo->countByStatus('submitted'),
            'approvedCount' => $this->applicationRepo->countByStatus('approved'),
            'rejectedCount' => $this->applicationRepo->countByStatus('rejected'),
        ]);
    }

    public function applicationDetail(Request $request, int $id): void
    {
        $app = $this->applicationRepo->findById($id);
        if (!$app) { $this->notFound(); return; }
        $this->view('admin/application_detail', [
            'title' => "Application #{$id}",
            'app' => $app,
        ]);
    }

    public function applicationForceStatus(Request $request, int $id): void
    {
        $app = $this->applicationRepo->findById($id);
        if ($app) {
            $status = $request->input('status', 'approved');
            $this->applicationRepo->updateStatus($id, $status, "Admin override: {$status}");
            AuditLog::log('application_force_status', 'application', $id, "App #{$id} force → {$status}");
            Session::flash('success', "Application #{$id} set to {$status}");
        }
        Response::back();
    }

    // ─── 6. PAYMENT & SaaS MANAGEMENT ───────────────────────────────────────

    public function subscriptions(): void
    {
        $all = $this->subRepo->getAll();
        $studentAll = $this->studentSubRepo->getAll();
        $active = array_filter($all, fn($s) => ($s['status'] ?? '') === 'active');
        $expired = array_filter($all, fn($s) => ($s['status'] ?? '') === 'expired');
        $studentActive = array_filter($studentAll, fn($s) => ($s['status'] ?? '') === 'active');
        $monthly = $this->paymentRepo->totalRevenue();
        $this->view('admin/subscriptions', [
            'title' => 'Subscriptions',
            'subscriptions' => $all,
            'studentSubscriptions' => $studentAll,
            'totalSubscriptions' => count($all),
            'activeSubscriptions' => count($active),
            'expiredSubscriptions' => count($expired),
            'activeStudentSubscriptions' => count($studentActive),
            'monthlyRevenue' => $monthly,
        ]);
    }

    public function subscriptionDetail(Request $request, int $id): void
    {
        $sub = $this->subRepo->findById($id);
        if (!$sub) { $this->notFound(); return; }
        $this->view('admin/subscription_detail', [
            'title' => 'Subscription Detail',
            'subscription' => $sub,
        ]);
    }

    public function subscriptionToggle(Request $request, int $id): void
    {
        $sub = $this->subRepo->findById($id);
        if ($sub) {
            $newStatus = $sub['status'] === 'active' ? 'suspended' : 'active';
            $this->subRepo->update($id, ['status' => $newStatus]);
            AuditLog::log('subscription_toggle', 'subscription', $id, "Sub #{$id} → {$newStatus}");
            Session::flash('success', "Subscription {$newStatus}");
        }
        Response::back();
    }

    public function transactions(): void
    {
        $status = $this->input('status');
        $payments = $status ? $this->paymentRepo->getByStatus($status) : $this->paymentRepo->getAll();
        $this->view('admin/transactions', [
            'title' => 'Transactions',
            'payments' => $payments,
            'totalTransactions' => count($payments),
            'totalRevenue' => $this->paymentRepo->totalRevenue(),
        ]);
    }

    public function paymentRefund(Request $request, int $id): void
    {
        $pay = $this->paymentRepo->findById($id);
        if ($pay && $pay['status'] === 'success') {
            $this->paymentRepo->update($id, ['status' => 'refunded']);
            AuditLog::log('payment_refund', 'payment', $id, "Payment #{$id} refunded");
            Session::flash('success', 'Payment refunded');
        }
        Response::back();
    }

    public function plans(): void
    {
        $this->view('admin/plans', [
            'title' => 'Subscription Plans',
            'providerPlans' => $this->subRepo->getAllPlansAdmin(),
            'studentPlans' => $this->studentSubRepo->getAllPlans(),
        ]);
    }

    public function savePlan(Request $request): void
    {
        $data = $request->validate(['name' => 'required', 'price' => 'required', 'duration_days' => 'required']);
        $planId = $request->input('id');
        $planData = [
            'name' => $data['name'], 'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'max_staff' => $request->input('max_staff', 1),
            'max_scholarships' => $request->input('max_scholarships', 5),
            'features' => $request->input('features'),
        ];

        if ($planId) {
            $old = $this->subRepo->findPlanById($planId);
            $this->subRepo->updatePlan($planId, $planData);
            AuditLog::log('plan_update', 'subscription_plan', $planId, "Plan {$planData['name']} updated", $old, $planData);
        } else {
            $newId = $this->subRepo->createPlan($planData);
            AuditLog::log('plan_create', 'subscription_plan', $newId, "Plan {$planData['name']} created", null, $planData);
        }
        Session::flash('success', 'Plan saved');
        Response::back();
    }

    public function deletePlan(Request $request, int $id): void
    {
        $plan = $this->subRepo->findPlanById($id);
        if ($plan) {
            AuditLog::log('plan_delete', 'subscription_plan', $id, "Plan {$plan['name']} deleted", $plan, null);
            $this->subRepo->deletePlan($id);
        }
        Session::flash('success', 'Plan deleted');
        Response::back();
    }

    public function studentPlanToggleStatus(Request $request, int $id): void
    {
        $plan = $this->studentSubRepo->findPlanById($id);
        if ($plan) {
            $this->studentSubRepo->togglePlanStatus($id);
            AuditLog::log('student_plan_toggle', 'student_plan', $id, "Student plan {$plan['name']} toggled");
            Session::flash('success', "Student plan {$plan['name']} updated");
        }
        Response::back();
    }

    // ─── 7. SYSTEM SETTINGS ─────────────────────────────────────────────────

    public function settings(): void
    {
        $this->view('admin/settings', [
            'title' => 'System Settings',
            'settings' => $this->settingsRepo->getAll(),
        ]);
    }

    public function updateSettings(Request $request): void
    {
        foreach ($_POST as $key => $value) {
            if ($key === '_token') continue;
            $this->settingsRepo->set($key, $value);
        }

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg'])) {
                $dir = 'public/uploads';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $filename = 'logo.' . $ext;
                move_uploaded_file($_FILES['logo']['tmp_name'], "$dir/$filename");
                $this->settingsRepo->set('logo', "uploads/$filename");
            }
        }

        AuditLog::log('settings_updated', 'system', null, 'System settings updated');
        Session::flash('success', 'Settings saved');
        Response::back();
    }

    // ─── 8. ANALYTICS ───────────────────────────────────────────────────────

    public function analytics(): void
    {
        $this->view('admin/analytics', [
            'title' => 'Analytics',
            'userGrowth' => $this->userRepo->getUserGrowth(),
            'registrationTrend' => $this->userRepo->getRegistrationTrend(12),
            'applicationTrend' => $this->applicationRepo->getApplicationTrend(12),
            'topScholarships' => $this->applicationRepo->getTopScholarships(10),
            'revenueTrend' => $this->paymentRepo->getRevenueTrend(),
            'totalUsers' => $this->userRepo->count(),
            'totalStudents' => $this->userRepo->countByRole('student'),
            'totalProviders' => $this->userRepo->countByRole('provider'),
            'totalAdmins' => $this->userRepo->countByRole('admin'),
            'totalApplications' => $this->applicationRepo->count(),
            'totalRevenue' => $this->paymentRepo->totalRevenue(),
        ]);
    }

    // ─── 9. SECURITY & LOGS ─────────────────────────────────────────────────

    public function securityLogs(): void
    {
        $filter = $this->input('filter', 'login');
        $data = ['title' => 'Security & Logs', 'tab' => $filter];

        if ($filter === 'login') {
            $data['logs'] = $this->auditRepo->getByAction('login', 100);
        } elseif ($filter === 'failed') {
            $data['logs'] = $this->auditRepo->getFailedLogins(100);
        } else {
            $data['logs'] = $this->auditRepo->getAll(100);
            $data['adminActions'] = $this->auditRepo->getAll(100);
        }

        $data['totalLogs'] = $this->auditRepo->count();
        $data['failedLogins'] = count($this->auditRepo->getFailedLogins(100));
        $data['adminActions'] ??= count($this->auditRepo->getAll(100));

        $this->view('admin/security_logs', $data);
    }

    public function auditLogs(): void
    {
        $action = $_GET['action'] ?? '';
        $search = $_GET['search'] ?? '';

        if ($action) {
            $logs = $this->auditRepo->getByAction($action, 200);
        } elseif ($search) {
            $all = $this->auditRepo->getAll(500);
            $logs = array_filter($all, fn($l) => stripos($l['description'] ?? '', $search) !== false || stripos($l['user_name'] ?? '', $search) !== false);
        } else {
            $logs = $this->auditRepo->getAll(200);
        }

        $this->view('admin/audit_logs', [
            'title' => 'Audit Logs',
            'logs' => $logs,
        ]);
    }

    // ─── 10. NOTIFICATIONS / ANNOUNCEMENTS ──────────────────────────────────

    public function announcements(): void
    {
        $this->view('admin/announcements', [
            'title' => 'Announcements',
            'announcements' => $this->announcementRepo->getAll(),
        ]);
    }

    public function createAnnouncement(Request $request): void
    {
        $data = $request->validate(['title' => 'required', 'message' => 'required']);
        $this->announcementRepo->create([
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $request->input('type', 'info'),
            'target_role' => $request->input('target_role', 'all'),
            'created_by' => Auth::id(),
        ]);
        AuditLog::log('announcement_created', 'announcement', null, "Announcement: {$data['title']}");
        Session::flash('success', 'Announcement created');
        Response::back();
    }

    public function deleteAnnouncement(Request $request, int $id): void
    {
        $this->announcementRepo->delete($id);
        Session::flash('success', 'Announcement deleted');
        Response::back();
    }

    // ─── 11. SUPPORT / FEEDBACK ─────────────────────────────────────────────

    public function supportTickets(): void
    {
        $tickets = $this->ticketRepo->getAll();
        $total = count($tickets);
        $openCount = count(array_filter($tickets, fn($t) => ($t['status'] ?? '') === 'open'));
        $inProgressCount = count(array_filter($tickets, fn($t) => ($t['status'] ?? '') === 'in_progress'));
        $resolvedCount = count(array_filter($tickets, fn($t) => ($t['status'] ?? '') === 'resolved'));
        $closedCount = count(array_filter($tickets, fn($t) => ($t['status'] ?? '') === 'closed'));
        $this->view('admin/support_tickets', [
            'title' => 'Support Tickets',
            'tickets' => $tickets,
            'totalTickets' => $total,
            'openTickets' => $openCount,
            'inProgressTickets' => $inProgressCount,
            'resolvedTickets' => $resolvedCount,
            'closedTickets' => $closedCount,
        ]);
    }

    public function ticketReply(Request $request, int $id): void
    {
        $data = $request->validate(['reply' => 'required']);
        $this->ticketRepo->reply($id, $data['reply'], Auth::id());
        AuditLog::log('ticket_replied', 'support_ticket', $id, 'Admin replied to ticket');
        Session::flash('success', 'Reply sent');
        Response::back();
    }

    public function ticketStatus(Request $request, int $id): void
    {
        $status = $request->input('status', 'closed');
        $this->ticketRepo->updateStatus($id, $status);
        Session::flash('success', "Ticket status: {$status}");
        Response::back();
    }

    public function feedbackList(): void
    {
        $all = $this->feedbackRepo->getAll();
        $pending = count(array_filter($all, fn($f) => ($f['status'] ?? 'pending') === 'pending'));
        $reviewed = count(array_filter($all, fn($f) => ($f['status'] ?? '') === 'reviewed'));
        $this->view('admin/feedback', [
            'title' => 'User Feedback',
            'feedback' => $all,
            'totalFeedback' => count($all),
            'pendingCount' => $pending,
            'reviewedCount' => $reviewed,
        ]);
    }

    public function feedbackStatus(Request $request, int $id): void
    {
        $status = $request->input('status', 'reviewed');
        $this->feedbackRepo->updateStatus($id, $status);
        Session::flash('success', 'Feedback status updated');
        Response::back();
    }

    // ─── PROFILE ────────────────────────────────────────────────────────────

    public function profile(): void
    {
        $this->view('admin/profile', [
            'title' => 'My Profile',
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(Request $request): void
    {
        $data = $request->validate(['name' => 'required|min:3', 'email' => 'required|email']);
        $old = Auth::user();
        $this->userRepo->update(Auth::id(), ['name' => $data['name'], 'email' => $data['email']]);
        $updated = $this->userRepo->findById(Auth::id());
        Session::set('user', $updated);
        AuditLog::log('profile_update', 'user', Auth::id(), 'Admin profile updated', $old, $updated);
        Session::flash('success', 'Profile updated');
        Response::redirect('/admin/profile');
    }

    // ─── HELPERS ────────────────────────────────────────────────────────────

    private function input(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $_POST[$key] ?? $default;
    }

    private function notFound(): void
    {
        http_response_code(404);
        $this->view('errors/404', ['title' => 'Not Found']);
        exit;
    }
}
