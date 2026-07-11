<?php

namespace App\Services;

use App\Core\Auth;
use App\Core\Session;
use App\Repositories\UserRepository;
use App\Repositories\ProviderRepository;

class AuthService
{
    private UserRepository $userRepo;
    private ProviderRepository $providerRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->providerRepo = new ProviderRepository();
    }

    public function register(array $data, ?string $documentPath = null): array
    {
        if ($this->userRepo->findByEmail($data['email'])) {
            return ['success' => false, 'error' => 'Email already registered'];
        }

        $userId = $this->userRepo->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'],
        ]);

        if ($data['role'] === 'provider') {
            $this->providerRepo->create([
                'user_id' => $userId,
                'company_name' => $data['name'] . ' (Provider)',
                'document' => $documentPath,
            ]);
        }

        $user = $this->userRepo->findById($userId);
        Auth::login($user);

        return ['success' => true, 'user' => $user];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Invalid email or password'];
        }

        if ($user['status'] !== 'active') {
            return ['success' => false, 'error' => 'Account is inactive'];
        }

        Auth::login($user);

        return ['success' => true, 'user' => $user];
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
