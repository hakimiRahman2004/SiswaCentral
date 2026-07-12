<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Helpers\PlanGuard;
use App\Repositories\ScholarshipRepository;
use App\Repositories\ApplicationRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\StudentProfileRepository;
use App\Repositories\SavedScholarshipRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Repositories\SupportTicketRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\StaffRepository;

class StudentController extends Controller
{
    private ScholarshipRepository $scholarshipRepo;
    private ApplicationRepository $applicationRepo;
    private DocumentRepository $documentRepo;
    private StudentProfileRepository $profileRepo;
    private SavedScholarshipRepository $savedRepo;
    private NotificationRepository $notificationRepo;
    private UserRepository $userRepo;
    private \App\Repositories\StudentSubscriptionRepository $subRepo;
    private SupportTicketRepository $ticketRepo;
    private ProviderRepository $providerRepo;
    private StaffRepository $staffRepo;

    public function __construct()
    {
        parent::__construct();
        $this->scholarshipRepo = new ScholarshipRepository();
        $this->applicationRepo = new ApplicationRepository();
        $this->documentRepo = new DocumentRepository();
        $this->profileRepo = new StudentProfileRepository();
        $this->savedRepo = new SavedScholarshipRepository();
        $this->notificationRepo = new NotificationRepository();
        $this->userRepo = new UserRepository();
        $this->subRepo = new \App\Repositories\StudentSubscriptionRepository();
        $this->ticketRepo = new SupportTicketRepository();
        $this->providerRepo = new ProviderRepository();
        $this->staffRepo = new StaffRepository();
    }

    public function dashboard(): void
    {
        $userId = Auth::id();
        $profile = $this->profileRepo->findByUserId($userId);
        $applications = $this->applicationRepo->findByStudent($userId);
        $totalApplications = count($applications);
        $pendingCount = $this->applicationRepo->countByStudentAndStatus($userId, 'submitted');
        $approvedCount = $this->applicationRepo->countByStudentAndStatus($userId, 'approved');
        $screeningCount = $this->applicationRepo->countByStudentAndStatus($userId, 'screening');
        $openScholarships = $this->scholarshipRepo->countOpen();
        $documentCount = count($this->documentRepo->findByUser($userId));
        $savedCount = $this->savedRepo->countByStudent($userId);
        $recentApps = array_slice($applications, 0, 5);
        $unread = $this->notificationRepo->countUnread($userId);
        $subscription = $this->subRepo->getActiveSubscription($userId);
        $usage = PlanGuard::usage();

        $profileFields = ['phone', 'cgpa', 'course', 'university', 'income', 'date_of_birth', 'gender', 'address', 'about'];
        $filled = 0;
        foreach ($profileFields as $f) {
            if (!empty($profile[$f])) $filled++;
        }
        $profilePct = $profile ? round(($filled / count($profileFields)) * 100) : 0;

        // Recommended scholarships (top 3)
        $allOpen = $this->scholarshipRepo->findOpen();
        $recommended = [];
        if ($profile && $profile['cgpa'] && $profile['course']) {
            $scored = [];
            foreach ($allOpen as $s) {
                $score = 0;
                if ($s['min_cgpa'] && $profile['cgpa'] >= $s['min_cgpa']) $score += 40;
                if ($s['eligible_courses'] && stripos($s['eligible_courses'], $profile['course']) !== false) $score += 30;
                if ($s['max_income'] && $profile['income'] <= $s['max_income']) $score += 20;
                if ($s['amount'] > 0) $score += 10;
                $scored[] = array_merge($s, ['match_score' => $score]);
            }
            usort($scored, fn($a, $b) => $b['match_score'] - $a['match_score']);
            $recommended = array_slice($scored, 0, 3);
        }

        $this->view('student/dashboard', [
            'title' => 'Dashboard',
            'profile' => $profile,
            'profilePct' => $profilePct,
            'totalApplications' => $totalApplications,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'screeningCount' => $screeningCount,
            'openScholarships' => $openScholarships,
            'documentCount' => $documentCount,
            'savedCount' => $savedCount,
            'recentApps' => $recentApps,
            'unread' => $unread,
            'subscription' => $subscription,
            'usage' => $usage,
            'recommended' => $recommended,
            'showMatching' => PlanGuard::hasMatching(),
        ]);
    }

    public function scholarships(): void
    {
        $q = $this->request->get('q');
        $course = $this->request->get('course');
        $cgpa = $this->request->get('cgpa');
        $income = $this->request->get('income');

        if ($q || $course || $cgpa || $income) {
            $scholarships = $this->scholarshipRepo->search($q, $course, null, $cgpa ? (float) $cgpa : null, $income ? (float) $income : null);
        } else {
            $scholarships = $this->scholarshipRepo->findOpen();
        }

        $savedIds = [];
        foreach ($this->savedRepo->findByStudent(Auth::id()) as $s) {
            $savedIds[$s['scholarship_id']] = true;
        }

        $appliedIds = [];
        foreach ($this->applicationRepo->findByStudent(Auth::id()) as $a) {
            $appliedIds[$a['scholarship_id']] = true;
        }

        $profile = $this->profileRepo->findByUserId(Auth::id());
        $showMatching = PlanGuard::hasMatching();

        // Attach match scores for Premium users
        if ($showMatching && $profile) {
            foreach ($scholarships as &$s) {
                $score = 0;
                if ($s['min_cgpa'] && $profile['cgpa'] >= $s['min_cgpa']) $score += 35;
                if ($s['max_income'] && $profile['income'] <= $s['max_income']) $score += 25;
                if ($s['eligible_courses'] && $profile['course'] && stripos($s['eligible_courses'], $profile['course']) !== false) $score += 25;
                if ($s['amount'] > 0) $score += 15;
                $s['match_score'] = $score;
            }
        }

        $this->view('student/scholarships', [
            'title' => 'Scholarships',
            'scholarships' => $scholarships,
            'savedIds' => $savedIds,
            'appliedIds' => $appliedIds,
            'profile' => $profile,
            'q' => $q,
            'course' => $course,
            'cgpa' => $cgpa,
            'income' => $income,
            'showMatching' => $showMatching,
        ]);
    }

    public function scholarshipDetail(Request $request, int $id): void
    {
        $scholarship = $this->scholarshipRepo->findById($id);
        if (!$scholarship) {
            Response::redirect('/scholarships');
        }
        $hasApplied = $this->applicationRepo->findByStudentAndScholarship(Auth::id(), $id);
        $isSaved = $this->savedRepo->isSaved(Auth::id(), $id);
        $documents = $this->documentRepo->findByUser(Auth::id());
        $profile = $this->profileRepo->findByUserId(Auth::id());
        $sub = $this->subRepo->getActiveSubscription(Auth::id());
        $showMatching = PlanGuard::hasMatching();

        // Smart match score
        $matchScore = 0;
        if ($showMatching && $profile) {
            if ($scholarship['min_cgpa'] && $profile['cgpa'] >= $scholarship['min_cgpa']) $matchScore += 35;
            if ($scholarship['max_income'] && $profile['income'] <= $scholarship['max_income']) $matchScore += 25;
            if ($scholarship['eligible_courses'] && $profile['course'] && stripos($scholarship['eligible_courses'], $profile['course']) !== false) $matchScore += 25;
            if ($scholarship['amount'] > 0) $matchScore += 15;
        }

        $matchLabel = $matchScore >= 70 ? 'High' : ($matchScore >= 40 ? 'Medium' : 'Low');

        $this->view('student/scholarship-detail', [
            'title' => $scholarship['name'],
            'scholarship' => $scholarship,
            'hasApplied' => $hasApplied !== null,
            'isSaved' => $isSaved,
            'documents' => $documents,
            'profile' => $profile,
            'subscription' => $sub,
            'showMatching' => $showMatching,
            'matchScore' => $matchScore,
            'matchLabel' => $matchLabel,
        ]);
    }

    public function apply(Request $request, int $id): void
    {
        $scholarship = $this->scholarshipRepo->findById($id);
        if (!$scholarship || $scholarship['status'] !== 'open') {
            Session::flash('error', 'Scholarship not available');
            Response::back();
        }

        $existing = $this->applicationRepo->findByStudentAndScholarship(Auth::id(), $id);
        if ($existing) {
            Session::flash('error', 'You have already applied to this scholarship');
            Response::back();
        }

        $profile = $this->profileRepo->findByUserId(Auth::id());
        if (!$profile || !$profile['cgpa'] || !$profile['course']) {
            Session::flash('error', 'Complete your profile before applying');
            Response::redirect('/profile');
        }

        // Plan enforcement — guard
        PlanGuard::canApply();

        $appId = $this->applicationRepo->create([
            'student_id' => Auth::id(),
            'scholarship_id' => $id,
            'provider_id' => $scholarship['provider_id'],
        ]);

        // ponytail: log initial status
        $this->applicationRepo->logStatusChange($appId, null, 'submitted', Auth::id());

        // ponytail: notify student
        $this->notificationRepo->create([
            'user_id' => Auth::id(),
            'type' => 'application',
            'title' => 'Application Submitted',
            'message' => "Your application for {$scholarship['name']} has been submitted.",
        ]);

        // Notify provider owner
        $provider = $this->providerRepo->findById($scholarship['provider_id']);
        if ($provider && !empty($provider['user_id'])) {
            $this->notificationRepo->create([
                'user_id' => $provider['user_id'],
                'type' => 'application',
                'title' => 'New Application Received',
                'message' => "A student has applied for \"{$scholarship['name']}\". Review their application in your dashboard.",
            ]);
        }

        // Notify provider staff members
        $staffMembers = $this->staffRepo->findByProvider($scholarship['provider_id']);
        foreach ($staffMembers as $staff) {
            if ($staff['user_id'] != ($provider['user_id'] ?? 0)) {
                $this->notificationRepo->create([
                    'user_id' => $staff['user_id'],
                    'type' => 'application',
                    'title' => 'New Application Received',
                    'message' => "A new application has been submitted for \"{$scholarship['name']}\".",
                ]);
            }
        }

        Session::flash('success', 'Application submitted successfully!');
        Response::redirect('/applications');
    }

    public function applications(): void
    {
        $applications = $this->applicationRepo->findByStudent(Auth::id());
        $this->view('student/applications', [
            'title' => 'My Applications',
            'applications' => $applications,
        ]);
    }

    public function applicationDetail(Request $request, int $id): void
    {
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['student_id'] !== Auth::id()) {
            Response::redirect('/applications');
        }
        $logs = $this->applicationRepo->getStatusLogs($id);
        $interview = $this->applicationRepo->getInterview($id);
        $essayRequest = $this->applicationRepo->getEssayRequest($id);
        $this->view('student/application-detail', [
            'title' => 'Application Details',
            'app' => $app,
            'logs' => $logs,
            'interview' => $interview,
            'essayRequest' => $essayRequest,
        ]);
    }

    public function cancelApplication(Request $request, int $id): void
    {
        $cancelled = $this->applicationRepo->cancel($id, Auth::id());
        if ($cancelled) {
            $this->applicationRepo->logStatusChange($id, 'submitted', 'rejected', Auth::id(), 'Cancelled by student');
            Session::flash('success', 'Application cancelled');
        } else {
            Session::flash('error', 'Only submitted applications can be cancelled');
        }
        Response::redirect('/applications');
    }

    public function submitEssay(Request $request, int $id): void
    {
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['student_id'] !== Auth::id()) Response::redirect('/applications');
        $essay = trim((string) $request->input('essay'));
        $requestData = $this->applicationRepo->getEssayRequest($id);
        if (!$requestData || $essay === '') {
            Session::flash('error', 'Write your essay before submitting.');
            Response::back();
        }
        if (strlen($essay) > 10000) {
            Session::flash('error', 'Your essay must be 10,000 characters or fewer.');
            Response::back();
        }
        if (!empty($requestData['submitted_at'])) {
            Session::flash('error', 'This essay has already been submitted.');
            Response::back();
        }
        if (!empty($requestData['due_at']) && strtotime($requestData['due_at']) < time()) {
            Session::flash('error', 'The deadline for this essay has passed.');
            Response::back();
        }
        if (!$this->applicationRepo->submitEssay($id, Auth::id(), $essay)) {
            Session::flash('error', 'Your essay could not be submitted. Please try again.');
            Response::back();
        }
        $provider = $this->providerRepo->findById($app['provider_id']);
        if ($provider && !empty($provider['user_id'])) {
            $this->notificationRepo->create(['user_id' => $provider['user_id'], 'type' => 'essay_submission', 'title' => 'Essay submitted', 'message' => $app['student_name'] . ' submitted the requested essay for "' . $app['scholarship_name'] . '".']);
        }
        Session::flash('success', 'Essay submitted successfully.');
        Response::redirect('/applications/' . $id);
    }

    public function saveScholarship(Request $request, int $id): void
    {
        $this->savedRepo->save(Auth::id(), $id);
        Session::flash('success', 'Scholarship saved');
        Response::back();
    }

    public function unsaveScholarship(Request $request, int $id): void
    {
        $this->savedRepo->unsave(Auth::id(), $id);
        Session::flash('success', 'Scholarship removed from saved');
        Response::back();
    }

    public function savedScholarships(): void
    {
        $saved = $this->savedRepo->findByStudent(Auth::id());
        $this->view('student/saved-scholarships', [
            'title' => 'Saved Scholarships',
            'saved' => $saved,
        ]);
    }

    public function documents(): void
    {
        $documents = $this->documentRepo->findByUser(Auth::id());
        $profile = $this->profileRepo->findByUserId(Auth::id());
        $usage = PlanGuard::usage();
        $this->view('student/documents', [
            'title' => 'My Documents',
            'documents' => $documents,
            'profile' => $profile,
            'usage' => $usage,
        ]);
    }

    public function uploadDocument(Request $request): void
    {
        $data = $request->validate(['type' => 'required']);
        $file = $request->file('document');

        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Upload failed');
            Response::back();
        }

        // Plan enforcement — guard
        PlanGuard::canUploadDocument();

        $allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($file['type'], $allowed)) {
            Session::flash('error', 'Invalid file type. Accepted: PDF, JPG, PNG, DOC, DOCX');
            Response::back();
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            Session::flash('error', 'File too large. Maximum 5MB');
            Response::back();
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('doc_') . '.' . $ext;
        $filePath = 'uploads/documents/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../../public/' . $filePath)) {
            Session::flash('error', 'Failed to save file');
            Response::back();
        }

        $this->documentRepo->create([
            'user_id' => Auth::id(),
            'type' => $data['type'],
            'file_name' => $file['name'],
            'file_path' => $filePath,
            'file_size' => $file['size'],
            'mime_type' => $file['type'],
        ]);

        Session::flash('success', 'Document uploaded successfully');
        Response::back();
    }

    public function deleteDocument(Request $request, int $id): void
    {
        $doc = $this->documentRepo->findById($id);
        if ($doc && $doc['user_id'] === Auth::id()) {
            $fullPath = __DIR__ . '/../../public/' . $doc['file_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $this->documentRepo->delete($id);
            Session::flash('success', 'Document deleted');
        }
        Response::back();
    }

    public function profile(): void
    {
        $profile = $this->profileRepo->findByUserId(Auth::id());
        $user = Auth::user();
        $this->view('student/profile', [
            'title' => 'My Profile',
            'profile' => $profile,
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request): void
    {
        $data = $request->validate([
            'phone' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'cgpa' => 'required',
            'course' => 'required',
            'university' => 'required',
            'income' => 'required',
        ]);

        $this->profileRepo->upsert(Auth::id(), [
            'user_id' => Auth::id(),
            'phone' => $data['phone'],
            'address' => $data['address'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'cgpa' => (float) $data['cgpa'],
            'course' => $data['course'],
            'university' => $data['university'],
            'income' => (float) $data['income'],
            'about' => $request->input('about') ?? '',
        ]);

        Session::flash('success', 'Profile updated successfully');
        Response::back();
    }

    public function settings(): void
    {
        $user = Auth::user();
        $subscription = $this->subRepo->getActiveSubscription(Auth::id());
        $this->view('student/settings', [
            'title' => 'Settings',
            'user' => $user,
            'subscription' => $subscription,
        ]);
    }

    public function updateSettings(Request $request): void
    {
        $data = $request->validate(['name' => 'required']);

        $this->userRepo->update(Auth::id(), ['name' => $data['name']]);

        // ponytail: update session user
        $user = Auth::user();
        $user['name'] = $data['name'];
        Auth::login($user);

        $password = $request->input('current_password');
        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('new_password_confirmation');

        if ($password && $newPassword) {
            if ($newPassword !== $confirmPassword) {
                Session::flash('error', 'New passwords do not match');
                Response::back();
            }
            if (strlen($newPassword) < 6) {
                Session::flash('error', 'Password must be at least 6 characters');
                Response::back();
            }
            if (!password_verify($password, $user['password'])) {
                Session::flash('error', 'Current password is incorrect');
                Response::back();
            }
            $this->userRepo->update(Auth::id(), ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);
        }

        Session::flash('success', 'Settings updated');
        Response::back();
    }

    public function supportTickets(): void
    {
        $tickets = $this->ticketRepo->findByUser(Auth::id());
        $this->view('student/support-tickets', [
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

        $userName = Auth::user()['name'] ?? 'Student';

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

        Session::flash('success', 'Support ticket submitted. We\'ll get back to you soon.');
        Response::redirect('/support-tickets');
    }

    public function feedback(): void
    {
        $this->view('student/feedback', ['title' => 'Submit Feedback']);
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

        $userName = Auth::user()['name'] ?? 'Student';

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
        Response::redirect('/feedback');
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

    public function notifications(): void
    {
        $notifications = $this->notificationRepo->findByUser(Auth::id());
        $this->view('student/notifications', [
            'title' => 'Notifications',
            'notifications' => $notifications,
        ]);
    }

    // ─── ENHANCED APPLICATION FORM ──────────────────────────────────────

    public function newApplication(Request $request, int $scholarshipId): void
    {
        $scholarship = $this->scholarshipRepo->findById($scholarshipId);
        if (!$scholarship || $scholarship['status'] !== 'open') {
            Session::flash('error', 'Scholarship not available');
            Response::redirect('/scholarships');
        }

        $existing = $this->applicationRepo->findByStudentAndScholarship(Auth::id(), $scholarshipId);
        if ($existing) {
            Session::flash('error', 'You have already applied to this scholarship');
            Response::redirect("/applications/{$existing['id']}");
        }

        $profile = $this->profileRepo->findByUserId(Auth::id());
        if (!$profile || !$profile['cgpa'] || !$profile['course']) {
            Session::flash('error', 'Complete your profile before applying');
            Response::redirect('/profile');
        }

        PlanGuard::canApply();

        $user = Auth::user();
        $documents = $this->documentRepo->findByUser(Auth::id());

        $this->view('student/application-form', [
            'title' => 'Apply - ' . $scholarship['name'],
            'scholarship' => $scholarship,
            'profile' => $profile,
            'user' => $user,
            'documents' => $documents,
        ]);
    }

    public function submitApplication(Request $request, int $scholarshipId): void
    {
        $scholarship = $this->scholarshipRepo->findById($scholarshipId);
        if (!$scholarship || $scholarship['status'] !== 'open') {
            Session::flash('error', 'Scholarship not available');
            Response::redirect('/scholarships');
        }

        $existing = $this->applicationRepo->findByStudentAndScholarship(Auth::id(), $scholarshipId);
        if ($existing) {
            Session::flash('error', 'You have already applied to this scholarship');
            Response::redirect("/applications/{$existing['id']}");
        }

        PlanGuard::canApply();

        $data = $request->validate([
            'achievements' => 'required',
            'motivation_letter' => 'required|min:50',
        ]);

        $details = [
            'cgpa' => $request->input('cgpa'),
            'course' => $request->input('course'),
            'university' => $request->input('university'),
            'income' => $request->input('income'),
            'achievements' => $data['achievements'],
            'co_curricular' => $request->input('co_curricular', ''),
            'motivation_letter' => $data['motivation_letter'],
            'emergency_name' => $request->input('emergency_name', ''),
            'emergency_phone' => $request->input('emergency_phone', ''),
            'emergency_relationship' => $request->input('emergency_relationship', ''),
            'document_ids' => $request->input('document_ids', []),
        ];

        $appId = $this->applicationRepo->createFull([
            'student_id' => Auth::id(),
            'scholarship_id' => $scholarshipId,
            'provider_id' => $scholarship['provider_id'],
            'details' => $details,
        ]);

        // Attach selected documents
        $docIds = $request->input('document_ids', []);
        if (!empty($docIds)) {
            $db = \App\Core\Database::connect();
            foreach ($docIds as $docId) {
                $stmt = $db->prepare("INSERT INTO application_documents (application_id, document_id) VALUES (?, ?)");
                $stmt->execute([$appId, $docId]);
            }
        }

        $app = $this->applicationRepo->findById($appId);
        $user = Auth::user();

        // Notify student
        $this->notificationRepo->create([
            'user_id' => Auth::id(),
            'type' => 'application_submitted',
            'title' => 'Application Submitted',
            'message' => "Your application {$app['application_number']} for \"{$scholarship['name']}\" has been submitted.",
        ]);

        // Notify provider
        $provider = $this->providerRepo->findById($scholarship['provider_id']);
        if ($provider && !empty($provider['user_id'])) {
            $this->notificationRepo->create([
                'user_id' => $provider['user_id'],
                'type' => 'new_application',
                'title' => 'New Application Received',
                'message' => "{$user['name']} has applied for \"{$scholarship['name']}\" ({$app['application_number']}).",
            ]);
        }

        // Send email
        $emailService = new \App\Services\EmailService();
        $emailService->applicationSubmitted($user, $scholarship, $app['application_number']);

        $this->applicationRepo->logStatusChange($appId, null, 'submitted', Auth::id());

        Session::flash('success', 'Application submitted successfully!');
        Response::redirect('/applications');
    }

    public function saveDraft(Request $request, int $id): void
    {
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['student_id'] != Auth::id()) {
            Response::redirect('/applications');
        }

        if (!in_array($app['status'], ['pending', 'submitted'])) {
            Session::flash('error', 'Cannot edit this application');
            Response::back();
        }

        $details = $this->applicationRepo->getDetails($id) ?? [];
        $newDetails = array_merge($details, [
            'cgpa' => $request->input('cgpa') ?? $details['cgpa'] ?? '',
            'course' => $request->input('course') ?? $details['course'] ?? '',
            'university' => $request->input('university') ?? $details['university'] ?? '',
            'income' => $request->input('income') ?? $details['income'] ?? '',
            'achievements' => $request->input('achievements') ?? $details['achievements'] ?? '',
            'co_curricular' => $request->input('co_curricular') ?? $details['co_curricular'] ?? '',
            'motivation_letter' => $request->input('motivation_letter') ?? $details['motivation_letter'] ?? '',
            'emergency_name' => $request->input('emergency_name') ?? $details['emergency_name'] ?? '',
            'emergency_phone' => $request->input('emergency_phone') ?? $details['emergency_phone'] ?? '',
            'emergency_relationship' => $request->input('emergency_relationship') ?? $details['emergency_relationship'] ?? '',
        ]);

        $this->applicationRepo->saveDraft([
            'id' => $id,
            'student_id' => Auth::id(),
            'scholarship_id' => $app['scholarship_id'],
            'provider_id' => $app['provider_id'],
            'application_number' => $app['application_number'],
            'details' => $newDetails,
        ]);

        Session::flash('success', 'Draft saved successfully');
        Response::back();
    }

    public function withdrawApplication(Request $request, int $id): void
    {
        $app = $this->applicationRepo->findById($id);
        if (!$app || $app['student_id'] != Auth::id()) {
            Response::redirect('/applications');
        }

        if (!in_array($app['status'], ['pending', 'submitted', 'screening'])) {
            Session::flash('error', 'Cannot withdraw this application');
            Response::back();
        }

        $this->applicationRepo->withdraw($id, Auth::id());
        $this->applicationRepo->logStatusChange($id, $app['status'], 'withdrawn', Auth::id(), 'Withdrawn by student');

        $scholarship = $this->scholarshipRepo->findById($app['scholarship_id']);
        $this->notificationRepo->create([
            'user_id' => Auth::id(),
            'type' => 'application_withdrawn',
            'title' => 'Application Withdrawn',
            'message' => "Your application {$app['application_number']} has been withdrawn.",
        ]);

        // Notify provider
        if ($scholarship) {
            $provider = $this->providerRepo->findById($scholarship['provider_id']);
            if ($provider && !empty($provider['user_id'])) {
                $this->notificationRepo->create([
                    'user_id' => $provider['user_id'],
                    'type' => 'application_withdrawn',
                    'title' => 'Application Withdrawn',
                    'message' => "Application {$app['application_number']} for \"{$scholarship['name']}\" has been withdrawn by the student.",
                ]);
            }
        }

        Session::flash('success', 'Application withdrawn');
        Response::redirect('/applications');
    }
}
