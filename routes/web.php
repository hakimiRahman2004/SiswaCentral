<?php

use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Middleware\ProviderMiddleware;
use App\Controllers\DashboardController;
use App\Controllers\AuthController;
use App\Controllers\StudentController;
use App\Controllers\ProviderController;
use App\Controllers\AdminController;
use App\Controllers\SubscriptionController;

// Public
$router->get('/', [DashboardController::class, 'welcome']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
    $router->get('/logout', [AuthController::class, 'logout']);

    // Auth required
$router->middleware(AuthMiddleware::class);
$router->group(function () use ($router) {

    $router->get('/dashboard', [DashboardController::class, 'index']);

    $router->get('/scholarships', [StudentController::class, 'scholarships']);
    $router->get('/scholarships/{id}', [StudentController::class, 'scholarshipDetail']);
    $router->post('/scholarships/{id}/apply', [StudentController::class, 'apply']);
    $router->post('/scholarships/{id}/save', [StudentController::class, 'saveScholarship']);
    $router->post('/scholarships/{id}/unsave', [StudentController::class, 'unsaveScholarship']);
    $router->get('/saved-scholarships', [StudentController::class, 'savedScholarships']);

    $router->get('/applications', [StudentController::class, 'applications']);
    $router->get('/applications/{id}', [StudentController::class, 'applicationDetail']);
    $router->post('/applications/{id}/cancel', [StudentController::class, 'cancelApplication']);
    $router->post('/applications/{id}/essay', [StudentController::class, 'submitEssay']);
    $router->get('/applications/new/{scholarshipId}', [StudentController::class, 'newApplication']);
    $router->post('/applications/new/{scholarshipId}', [StudentController::class, 'submitApplication']);
    $router->post('/applications/{id}/save-draft', [StudentController::class, 'saveDraft']);
    $router->post('/applications/{id}/withdraw', [StudentController::class, 'withdrawApplication']);

    $router->get('/notifications', [StudentController::class, 'notifications']);
    $router->get('/notifications/unread-count', [StudentController::class, 'unreadCount']);
    $router->post('/notifications/{id}/read', [StudentController::class, 'markNotificationRead']);
    $router->post('/notifications/mark-all-read', [StudentController::class, 'markAllRead']);

    $router->get('/support-tickets', [StudentController::class, 'supportTickets']);
    $router->post('/support-tickets/create', [StudentController::class, 'createTicket']);

    $router->get('/feedback', [StudentController::class, 'feedback']);
    $router->post('/feedback', [StudentController::class, 'submitFeedback']);

    $router->get('/documents', [StudentController::class, 'documents']);
    $router->post('/documents/upload', [StudentController::class, 'uploadDocument']);
    $router->post('/documents/{id}/delete', [StudentController::class, 'deleteDocument']);

    $router->get('/profile', [StudentController::class, 'profile']);
    $router->post('/profile', [StudentController::class, 'updateProfile']);

    $router->get('/settings', [StudentController::class, 'settings']);
    $router->post('/settings', [StudentController::class, 'updateSettings']);

    // Student Subscription
    $router->get('/subscription', [\App\Controllers\StudentSubscriptionController::class, 'index']);
    $router->post('/subscription/subscribe', [\App\Controllers\StudentSubscriptionController::class, 'subscribe']);
    $router->get('/subscription/callback', [\App\Controllers\StudentSubscriptionController::class, 'callback']);
    $router->post('/subscription/callback', [\App\Controllers\StudentSubscriptionController::class, 'callback']);
    $router->get('/subscription/success', [\App\Controllers\StudentSubscriptionController::class, 'success']);
    $router->post('/subscription/cancel', [\App\Controllers\StudentSubscriptionController::class, 'cancel']);

    $router->get('/admin/profile', [AdminController::class, 'profile']);
    $router->post('/admin/profile', [AdminController::class, 'updateProfile']);

    // Provider onboarding (accessible before verification)
    $router->get('/provider/onboarding', [ProviderController::class, 'onboarding']);
    $router->post('/provider/onboarding', [ProviderController::class, 'saveOnboarding']);

    // Provider routes (with verification check)
    $router->group(function () use ($router) {
        $router->middleware(ProviderMiddleware::class);

    $router->get('/provider/profile', [ProviderController::class, 'profile']);
    $router->post('/provider/profile', [ProviderController::class, 'updateProfile']);

    $router->get('/provider/dashboard', [ProviderController::class, 'dashboard']);
    $router->get('/provider/scholarships', [ProviderController::class, 'scholarships']);
    $router->get('/provider/scholarships/create', [ProviderController::class, 'createScholarship']);
    $router->post('/provider/scholarships/create', [ProviderController::class, 'storeScholarship']);
    $router->get('/provider/scholarships/{id}/edit', [ProviderController::class, 'editScholarship']);
    $router->post('/provider/scholarships/{id}/edit', [ProviderController::class, 'updateScholarship']);
    $router->post('/provider/scholarships/{id}/delete', [ProviderController::class, 'deleteScholarship']);
    $router->post('/provider/scholarships/{id}/toggle', [ProviderController::class, 'toggleScholarship']);
    $router->post('/provider/scholarships/doc/{id}/delete', [ProviderController::class, 'deleteScholarshipDoc']);
    $router->get('/provider/scholarships/{id}/applicants', [ProviderController::class, 'applicants']);
    $router->post('/provider/applications/{id}/status', [ProviderController::class, 'updateApplicationStatus']);
    $router->post('/provider/applications/{id}/interview', [ProviderController::class, 'scheduleInterview']);
    $router->post('/provider/applications/{id}/essay-request', [ProviderController::class, 'requestEssay']);
    $router->get('/provider/applications', [ProviderController::class, 'allApplications']);
    $router->get('/provider/staff', [ProviderController::class, 'staff']);
    $router->post('/provider/staff/invite', [ProviderController::class, 'inviteStaff']);
    $router->post('/provider/staff/{id}/remove', [ProviderController::class, 'removeStaff']);

    // Subscription routes
    $router->get('/provider/subscription', [SubscriptionController::class, 'index']);
    $router->post('/provider/subscription/subscribe', [SubscriptionController::class, 'subscribe']);
    $router->get('/provider/subscription/callback', [SubscriptionController::class, 'callback']);
    $router->post('/provider/subscription/callback', [SubscriptionController::class, 'callback']);
    $router->get('/provider/subscription/success', [SubscriptionController::class, 'success']);
    $router->post('/provider/subscription/cancel', [SubscriptionController::class, 'cancel']);

    // Provider notifications
    $router->get('/provider/notifications', [ProviderController::class, 'notifications']);
    $router->get('/provider/notifications/unread-count', [ProviderController::class, 'unreadCount']);
    $router->post('/provider/notifications/{id}/read', [ProviderController::class, 'markNotificationRead']);
    $router->post('/provider/notifications/mark-all-read', [ProviderController::class, 'markAllRead']);

    // Provider support tickets
    $router->get('/provider/support-tickets', [ProviderController::class, 'supportTickets']);
    $router->post('/provider/support-tickets/create', [ProviderController::class, 'createTicket']);

    // Provider feedback
    $router->get('/provider/feedback', [ProviderController::class, 'feedback']);
    $router->post('/provider/feedback', [ProviderController::class, 'submitFeedback']);

    // Provider analytics
    $router->get('/provider/analytics', [ProviderController::class, 'analytics']);

    // Provider settings
    $router->get('/provider/settings', [ProviderController::class, 'settings']);
    $router->post('/provider/settings', [ProviderController::class, 'updateSettings']);

    // Provider application detail
    $router->get('/provider/applications/{id}', [ProviderController::class, 'applicationDetail']);

    // Provider bulk actions, export, notes, document requests
    $router->post('/provider/applications/bulk', [ProviderController::class, 'bulkAction']);
    $router->get('/provider/applications/export', [ProviderController::class, 'exportApplications']);
    $router->post('/provider/applications/{id}/notes', [ProviderController::class, 'addNote']);
    $router->post('/provider/applications/{id}/document-request', [ProviderController::class, 'requestDocuments']);

    }); // end provider middleware group

    // ─── ADMIN ROUTES ──────────────────────────────────────────────────────────
    $router->middleware(\App\Middleware\AdminMiddleware::class);
    $router->group(function () use ($router) {

    // Dashboard
    $router->get('/admin/dashboard', [AdminController::class, 'dashboard']);

    // User Management
    $router->get('/admin/users', [AdminController::class, 'users']);
    $router->get('/admin/students', [AdminController::class, 'students']);
    $router->get('/admin/user-providers', [AdminController::class, 'userProviders']);
    $router->get('/admin/users/{id}', [AdminController::class, 'userDetail']);
    $router->post('/admin/users/{id}/toggle-status', [AdminController::class, 'userToggleStatus']);
    $router->post('/admin/users/{id}/ban', [AdminController::class, 'userBan']);
    $router->post('/admin/users/{id}/delete', [AdminController::class, 'userDelete']);
    $router->post('/admin/users/{id}/reset-password', [AdminController::class, 'userResetPassword']);

    // Scholarship Management
    $router->get('/admin/scholarships', [AdminController::class, 'scholarships']);
    $router->get('/admin/scholarships/{id}', [AdminController::class, 'scholarshipDetail']);
    $router->post('/admin/scholarships/{id}/toggle-status', [AdminController::class, 'scholarshipToggleStatus']);
    $router->post('/admin/scholarships/{id}/edit', [AdminController::class, 'scholarshipEdit']);
    $router->post('/admin/scholarships/{id}/delete', [AdminController::class, 'scholarshipDelete']);

    // Provider Management
    $router->get('/admin/providers', [AdminController::class, 'providers']);
    $router->get('/admin/providers/verified', [AdminController::class, 'providersVerified']);
    $router->get('/admin/providers/unverified', [AdminController::class, 'providersUnverified']);
    $router->get('/admin/providers/{id}', [AdminController::class, 'providerDetail']);
    $router->post('/admin/providers/{id}/verify', [AdminController::class, 'providerVerify']);
    $router->post('/admin/providers/{id}/suspend', [AdminController::class, 'providerSuspend']);
    $router->post('/admin/providers/{id}/staff/{staffId}/remove', [AdminController::class, 'providerRemoveStaff']);

    // Application Monitoring
    $router->get('/admin/applications', [AdminController::class, 'applications']);
    $router->get('/admin/applications/{id}', [AdminController::class, 'applicationDetail']);
    $router->post('/admin/applications/{id}/force-status', [AdminController::class, 'applicationForceStatus']);
    $router->get('/admin/applications/export', [AdminController::class, 'exportApplications']);
    $router->get('/admin/email-logs', [AdminController::class, 'emailLogs']);
    $router->get('/admin/reports', [AdminController::class, 'reports']);

    // Payment & SaaS Management
    $router->get('/admin/subscriptions', [AdminController::class, 'subscriptions']);
    $router->get('/admin/subscriptions/{id}', [AdminController::class, 'subscriptionDetail']);
    $router->post('/admin/subscriptions/{id}/toggle', [AdminController::class, 'subscriptionToggle']);
    $router->get('/admin/transactions', [AdminController::class, 'transactions']);
    $router->post('/admin/transactions/{id}/refund', [AdminController::class, 'paymentRefund']);
    $router->get('/admin/plans', [AdminController::class, 'plans']);
    $router->post('/admin/plans', [AdminController::class, 'savePlan']);
    $router->post('/admin/plans/{id}/delete', [AdminController::class, 'deletePlan']);
    $router->post('/admin/plans/student/{id}/toggle', [AdminController::class, 'studentPlanToggleStatus']);

    // System Settings
    $router->get('/admin/settings', [AdminController::class, 'settings']);
    $router->post('/admin/settings', [AdminController::class, 'updateSettings']);

    // Analytics
    $router->get('/admin/analytics', [AdminController::class, 'analytics']);

    // Security & Logs
    $router->get('/admin/security-logs', [AdminController::class, 'securityLogs']);
    $router->get('/admin/audit-logs', [AdminController::class, 'auditLogs']);

    // Announcements
    $router->get('/admin/announcements', [AdminController::class, 'announcements']);
    $router->post('/admin/announcements', [AdminController::class, 'createAnnouncement']);
    $router->post('/admin/announcements/{id}/delete', [AdminController::class, 'deleteAnnouncement']);

    // Support / Feedback
    $router->get('/admin/support-tickets', [AdminController::class, 'supportTickets']);
    $router->get('/admin/support-tickets/{id}', [AdminController::class, 'supportTicketDetail']);
    $router->post('/admin/support-tickets/{id}/reply', [AdminController::class, 'ticketReply']);
    $router->post('/admin/support-tickets/{id}/status', [AdminController::class, 'ticketStatus']);
    $router->get('/admin/feedback', [AdminController::class, 'feedbackList']);
    $router->post('/admin/feedback/{id}/status', [AdminController::class, 'feedbackStatus']);

    // Notifications
    $router->get('/admin/notifications', [AdminController::class, 'notifications']);
    $router->get('/admin/notifications/unread-count', [AdminController::class, 'unreadCount']);
    $router->post('/admin/notifications/{id}/read', [AdminController::class, 'markNotificationRead']);
    $router->post('/admin/notifications/mark-all-read', [AdminController::class, 'markAllRead']);

    }); // end admin middleware group

}); // end auth group
