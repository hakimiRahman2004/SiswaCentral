<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Response;

class DashboardController extends Controller
{
    public function welcome(): void
    {
        if (Auth::check()) {
            $role = Auth::role();
            match ($role) {
                'admin' => Response::redirect('/admin/dashboard'),
                'provider' => Response::redirect('/provider/dashboard'),
                default => Response::redirect('/dashboard'),
            };
        }
        $this->view('welcome', ['title' => config('app.name') . ' - One Profile. One Upload. Unlimited Opportunities.'], 'landing');
    }

    public function index(): void
    {
        $role = Auth::role();
        if ($role === 'student') {
            $controller = new \App\Controllers\StudentController();
            $controller->dashboard();
            return;
        }
        $this->view('dashboard', ['title' => 'Dashboard - ' . config('app.name')]);
    }
}
