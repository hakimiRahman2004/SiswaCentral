# ARCHITECTURE.md

# SISWACentral — System Architecture

## 🧭 Overview

SISWACentral is built using a **custom PHP MVC architecture** with a **multi-tenant SaaS design**.

The system separates concerns into:

* Presentation (Views)
* Business Logic (Services)
* Data Access (Repositories)
* Core Framework (Routing, Middleware, Base Classes)

---

# 🏗️ Architecture Pattern

## MVC Structure

```id="mvcstructure"
Controller → Service → Repository → Database
         ↓
       View
```

---

## Responsibilities

### Controller

* Handle HTTP requests
* Validate input
* Call service layer
* Return response / view

---

### Service

* Business logic
* Data processing
* Workflow handling

---

### Repository

* Database queries
* Data persistence
* PDO usage

---

### Model (Optional)

* Data structure representation

---

### View

* UI rendering (HTML + Tailwind)
* No business logic

---

# 📁 Folder Structure

```id="fullstructure"
app/
│
├── Controllers/
├── Models/
├── Services/
├── Repositories/
├── Middleware/
├── Core/
├── Helpers/
└── Views/
```

---

## Core Directory

```id="corestructure"
Core/
├── Router.php
├── Controller.php
├── Model.php
├── Database.php
├── Request.php
├── Response.php
├── Session.php
└── Auth.php
```

---

# 🌐 Request Lifecycle

```id="lifecycle"
Request
  ↓
Router
  ↓
Middleware
  ↓
Controller
  ↓
Service
  ↓
Repository
  ↓
Database
  ↓
Response → View
```

---

# 🔐 Middleware System

Middleware is used for:

* Authentication
* Authorization
* Subscription check
* CSRF validation

---

## Example Middleware

### AuthMiddleware

* Check if user logged in

---

### RoleMiddleware

* Check user role (admin/provider/student)

---

### SubscriptionMiddleware

* Ensure provider subscription is active

---

### TenantMiddleware

* Ensure data belongs to correct tenant

---

# 🧩 Multi-Tenant Design

## Concept

Each provider is a **tenant**.

---

## Implementation Strategy

Use `tenant_id` in all provider-related tables.

---

## Example Tables

```id="tenantexample"
providers
scholarships
applications
staff
```

Each table includes:

```id="tenantid"
tenant_id
```

---

## Data Isolation

* Provider A cannot access Provider B data
* Queries always filter by `tenant_id`

---

# 👤 User System

## Users Table

```id="usersystem"
users
- id
- name
- email
- password
- role
```

---

## Roles

* admin
* provider
* student

---

# 🔄 Service Layer Design

Each module has its own service.

---

## Example Services

* AuthService
* StudentService
* ProviderService
* ScholarshipService
* ApplicationService
* SubscriptionService
* PaymentService

---

## Example Flow

Controller:

```id="controllerflow"
$service->applyScholarship($data);
```

Service:

```id="serviceflow"
validateEligibility
attachDocuments
saveApplication
triggerNotification
```

---

# 🧾 Repository Layer

Handles database only.

---

## Example Repository

* UserRepository
* ScholarshipRepository
* ApplicationRepository
* SubscriptionRepository

---

## Rules

* No business logic
* Only SQL queries
* Always use PDO prepared statements

---

# 🔐 Authentication System

## Method

* Session-based authentication

---

## Features

* Login / Logout
* Password hashing
* Session regeneration
* Remember me (optional)

---

# 🔑 Authorization

Use role + permission checks.

---

## Example

```id="authcheck"
if ($user->role !== 'admin') {
    denyAccess();
}
```

---

# 💳 Subscription Architecture

## Key Components

* SubscriptionService
* PaymentService
* Billing module

---

## Flow

```id="subflow"
Check subscription
→ Allow access OR restrict features
```

---

## Restriction Logic

If expired:

* Disable create/edit
* Allow billing access only

---

# 💰 Payment Integration Layer

## Design

Abstract payment gateway.

---

## Structure

```id="paymentstructure"
PaymentGatewayInterface

├── ToyyibPayService
├── BillplzService (future)
├── StripeService (future)
```

---

## Benefits

* Easy to switch gateway
* Scalable

---

# 📦 File Upload System

## Structure

```id="uploadstructure"
uploads/
├── documents/
├── avatars/
├── logos/
└── temp/
```

---

## Rules

* Validate MIME type
* Limit file size
* Unique file naming
* Secure access

---

# ⚡ Performance Strategy

* Pagination for large data
* Lazy loading
* Indexed database fields
* Optimized queries

---

# 🔐 Security Practices

* PDO prepared statements
* CSRF tokens
* XSS sanitization
* Input validation
* Session security

---

# 📡 API Readiness (Future)

System should be API-ready.

---

## Structure

```id="apistructure"
api/
├── v1/
│   ├── AuthController
│   ├── ScholarshipController
│   └── ApplicationController
```

---

# 🧠 Coding Rules for OpenCode

## MUST FOLLOW

* Use MVC structure strictly
* Use Service layer for logic
* Use Repository for DB
* No SQL in controller
* No logic in views

---

## DO NOT

* Mix responsibilities
* Write raw SQL in controllers
* Skip validation
* Hardcode values

---

# 🧪 Error Handling

* Try-catch for services
* Central error handler
* User-friendly messages

---

# 🏁 Summary

SISWACentral architecture is designed to be:

* Modular
* Scalable
* Maintainable
* SaaS-ready

This ensures the system can grow from:

* Student project
  → Startup product
  → Production SaaS platform
