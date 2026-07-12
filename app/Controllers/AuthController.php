<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Helpers\AuditLog;
use App\Services\AuthService;
use App\Repositories\ProviderRepository;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function showLogin(): void
    {
        $this->view('auth/login', ['title' => 'Login - ' . config('app.name')], 'landing');
    }

    public function showRegister(): void
    {
        $this->view('auth/register', ['title' => 'Register - ' . config('app.name')], 'landing');
    }

    public function login(Request $request): void
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($data['email'], $data['password']);

        if (!$result['success']) {
            Session::flash('error', $result['error']);
            AuditLog::log('login_failed', 'user', null, "Failed login attempt for {$data['email']}");
            $this->back();
        }

        AuditLog::log('login', 'user', $result['user']['id'], "User {$result['user']['name']} logged in as {$result['user']['role']}");
        $this->redirectToRole($result['user']['role']);
    }

    public function register(Request $request): void
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $uploadedPath = null;
        if ($data['role'] === 'provider' && isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
                Session::flash('error', 'Document must be PDF, JPG, or PNG');
                $this->back();
            }
            $dir = 'public/uploads/documents';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $filename = 'provider_doc_' . bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['document']['tmp_name'], "$dir/$filename");
            $uploadedPath = "uploads/documents/$filename";
        }

        $result = $this->authService->register($data, $uploadedPath);

        if (!$result['success']) {
            Session::flash('error', $result['error']);
            $this->back();
        }

        AuditLog::log('register', 'user', $result['user']['id'], "User {$result['user']['name']} registered as {$result['user']['role']}");
        $this->redirectToRole($result['user']['role']);
    }

    public function logout(Request $request): void
    {
        $this->authService->logout();
        $this->redirect('/login');
    }

    private function redirectToRole(string $role): void
    {
        match ($role) {
            'admin' => $this->redirect('/admin/dashboard'),
            'provider' => $this->redirectProvider(),
            default => $this->redirect('/dashboard'),
        };
    }

    private function redirectProvider(): void
    {
        $providerRepo = new ProviderRepository();
        $provider = $providerRepo->findByUserId(\App\Core\Auth::id());

        // If provider is active (verified), go directly to dashboard
        if ($provider && $provider['status'] === 'active') {
            $this->redirect('/provider/dashboard');
        } else {
            // New or unverified providers go to onboarding
            $this->redirect('/provider/onboarding');
        }
    }
}
