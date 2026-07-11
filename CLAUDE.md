# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**SISWACentral** is a centralized scholarship platform (multi-tenant SaaS) connecting students with scholarship providers across Malaysia. Built with a custom PHP MVC framework.

- **PHP 8+** with PSR-4 autoloading (`App\` → `app/`)
- **Tailwind CSS v4** via CLI (`@tailwindcss/cli`)
- **MySQL** with PDO prepared statements
- **Vanilla JavaScript (ES6+)** for interactivity
- **Session-based authentication**

## Commands

```bash
# Serve the app (PHP built-in dev server)
php -S localhost:9090 router.php

# Build Tailwind CSS
npx tailwindcss -i public/css/input.css -o public/css/output.css

# Watch Tailwind CSS for changes
npx tailwindcss -i public/css/input.css -o public/css/output.css --watch

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Database setup — run the schema SQL manually:
#   database/schema.sql  (full schema + seed data)
# Or apply individual migrations from database/migrations/
```

## Architecture

### Request Lifecycle
```
public/index.php → Router → Middleware → Controller → Service → Repository → Database → Response/View
```

### Core Framework (`app/Core/`)
- **Router** — Simple regex-based router with middleware stacking and route groups. Routes defined in `routes/web.php`. Supports `{param}` placeholders, middleware chaining, and nested groups.
- **Controller** — Base class providing `view()`, `json()`, `redirect()`, `back()` methods. Views use an output-buffer + layout pattern.
- **Database** — Singleton PDO connection via `config/database.php`. Always use PDO prepared statements.
- **Request** — Wraps `$_GET`, `$_POST`, `$_FILES`, `$_SERVER`. Includes basic validation (`required`, `min:N`, `max:N`, `email` rules via pipe syntax).
- **Response** — Static helpers for JSON, redirect, and back.
- **Session** — Flash messaging, error bags, `old()` input helpers.
- **Auth** — Session-based user facade (`user()`, `id()`, `role()`, `check()`, `is()`).

### Layer Responsibilities
- **Controller** — HTTP handling, input validation, calls service layer, returns views. Lives in `app/Controllers/`.
- **Service** — Business logic. Currently only `AuthService` exists; new services should be added in `app/Services/`.
- **Repository** — Database queries only. One per entity in `app/Repositories/`. No business logic. Always use PDO.
- **View** — HTML + Tailwind. No business logic. Lives in `app/Views/{role}/`. Layouts in `app/Views/layouts/`, partials in `app/Views/partials/`.

### Middleware Stack
- **AuthMiddleware** — Redirects to `/login` if not authenticated
- **AdminMiddleware** — 403 if not admin role
- **ProviderMiddleware** — Checks provider status (pending/active), shows pending approval screen
- **RoleMiddleware** — Generic role check
- **CsrfMiddleware** — Token validation on non-GET requests (defined but not wired in routes yet)

### Guard Helpers
- **`ProviderGuard`** — Subscription and usage enforcement for providers (scholarship limits, staff limits). Auto-expires stale subscriptions on every check.
- **`PlanGuard`** — Plan enforcement for students (application limits per month, document limits, smart matching access).

### Routing Pattern
Routes are defined in `routes/web.php`. The router supports groups with shared middleware:

```php
$router->middleware(AuthMiddleware::class);
$router->group(function () use ($router) {
    // Authenticated routes here
    $router->middleware(AdminMiddleware::class);
    $router->group(function () use ($router) {
        // Admin-only routes here
    });
});
```

### Multi-Tenant Design
Providers are tenants. All provider-related tables use `provider_id` for data isolation. Queries always filter by the current provider's ID. Students are shared across the platform.

### Key Modules
- **Auth** — Register/Login/Logout, session-based, password hashing
- **Student** — Profile, documents, scholarship search/apply, saved scholarships, applications, subscriptions
- **Provider** — Dashboard, scholarships CRUD, application pipeline (submitted → screening → shortlisted → interview → approved/rejected), staff management, analytics, subscriptions
- **Admin** — Provider verification, scholarship moderation, user management, platform analytics, subscription/plan management, support tickets, announcements, audit/security logs
- **Subscription** — Plans (Starter/Professional/Enterprise), ToyyibPay payment gateway, billing/invoicing

### Database
Full schema in `database/schema.sql` (20+ tables). Key tables: `users`, `student_profiles`, `documents`, `providers`, `staff`, `scholarships`, `applications`, `application_documents`, `subscription_plans`, `subscriptions`, `payments`, `invoices`, `notifications`, `settings`, `support_tickets`, `feedback`, `audit_logs`, `login_attempts`, `announcements`. Seed scripts in `database/`.

### Frontend
- **Tailwind CSS v4** — Design system defined in `public/css/input.css` with custom properties, component classes (`.btn`, `.card`, `.stat-card`, `.badge`, `.modal`, `.sidebar`, etc.), animations, dark mode support.
- **JavaScript** — `public/js/app.js` handles sidebar toggle, dropdowns, flash dismissal, clickable table rows, confirm dialogs, smooth scroll.
- **Views** organized by role: `app/Views/admin/`, `app/Views/provider/`, `app/Views/student/`, `app/Views/auth/`, plus shared `layouts/` and `partials/`.
- Design inspiration: Stripe, Notion, Vercel, Linear (clean, minimal, modern SaaS).

### Global Helpers (available in views)
- `e()` — `htmlspecialchars()` shorthand
- `config('app.name')` — Config access via dot notation
- `asset('path')` — Full URL for public assets
- `old('field')` — Repopulate form inputs after validation error
- `error('field')` — Display validation error for a field
- `flash('key')` — Flash/session messages
- `csrf()` / `csrfField()` — CSRF token hidden input

### Common Patterns

**Creating a new page:**
1. Add route in `routes/web.php`
2. Add controller method (or new controller in `app/Controllers/`)
3. Add repository methods if DB queries needed
4. Create view in `app/Views/{role}/`
5. Run `npx tailwindcss -i public/css/input.css -o public/css/output.css` to rebuild CSS

**Controller method pattern:**
```php
public function scholarships(): void
{
    $scholarships = $this->scholarshipRepo->findAll();
    $this->view('provider/scholarships', [
        'title' => 'My Scholarships',
        'scholarships' => $scholarships,
    ]);
}
```

**Repository method pattern (always use PDO prepared statements):**
```php
public function findByEmail(string $email): ?array
{
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() ?: null;
}
```
