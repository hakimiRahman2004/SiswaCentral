# SISWACentral

**SISWACentral** is a modern SaaS-based centralized scholarship platform designed to connect students and scholarship providers across Malaysia.

> **One Profile. One Upload. Unlimited Scholarship Opportunities.**

---

# 🚀 Overview

SISWACentral is not just a scholarship management system.

It is a **centralized ecosystem** where:

* Students create one account and apply to multiple scholarships
* Providers manage scholarships in a single platform
* Admin controls the entire marketplace

---

# 🎯 Vision

To become Malaysia’s **centralized scholarship platform** that simplifies how students apply and how organizations manage scholarships.

---

# 👥 User Roles

## 1. Student

* Register once
* Complete profile
* Upload documents once
* Search scholarships
* Apply to multiple providers
* Track application status

---

## 2. Provider (SaaS Customer)

Organizations such as:

* Universities
* Foundations
* Companies
* NGOs
* Government agencies

Capabilities:

* Create and manage scholarships
* Review applications
* Manage staff
* View analytics
* Subscribe to plans

---

## 3. Admin (Platform Owner)

* Approve providers
* Manage users
* Monitor platform activity
* Control subscriptions
* Moderate scholarships

---

# 🧠 Core Features

## Student

* Profile Management
* Document Vault (upload once)
* Scholarship Search & Filter
* Recommendation System
* Application Tracking
* Notifications

---

## Provider

* Scholarship Management
* Requirement Builder
* Eligibility Filtering
* Application Pipeline
* Staff Role Management
* Analytics Dashboard

---

## Admin

* Provider Verification
* Scholarship Approval
* Platform Analytics
* Subscription Monitoring
* Payment Tracking

---

# 🏗️ SaaS Architecture

SISWACentral uses a **multi-tenant architecture**.

Each provider is a separate tenant:

* Data is isolated using `tenant_id`
* Providers cannot access other provider data
* Students are shared across the platform

---

# 🔄 System Flow

## Student Flow

Register → Complete Profile → Upload Documents → Search → Apply → Track Status

---

## Provider Flow

Register → Verification → Subscription → Create Scholarship → Receive Applications → Review → Approve

---

## Admin Flow

Monitor → Verify Providers → Approve Scholarships → Manage Platform

---

# 💳 Subscription System

Providers pay monthly subscription.

## Plans Example

### Starter

* Limited staff
* Limited scholarships
* Limited applications

### Professional

* Unlimited scholarships
* Analytics
* Email notification

### Enterprise

* API access
* Custom domain
* White-label

---

# 💰 Payment Integration

Payment gateway:

* ToyyibPay (Sandbox for development)

## Payment Flow

Provider selects plan → System generates bill → Redirect to ToyyibPay → Payment success → Subscription activated

---

# 🧾 Billing System

Tables:

* subscriptions
* subscription_plans
* payments
* invoices

Features:

* Payment history
* Invoice generation
* Subscription renewal
* Status tracking

---

# 🧑‍💻 Tech Stack

## Frontend

* HTML5
* Tailwind CSS (CLI)
* Vanilla JavaScript (ES6+)

Libraries:

* Chart.js
* Flatpickr
* Tom Select

---

## Backend

* PHP 8+
* OOP
* PDO
* MVC Architecture

---

## Database

* MySQL

---

# 📁 Folder Structure

```
project/
│
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   ├── Middleware/
│   ├── Core/
│   ├── Helpers/
│   └── Views/
│
├── config/
├── database/
├── public/
├── resources/
├── uploads/
└── vendor/
```

---

# 🎨 UI/UX Design Guidelines

Design inspiration:

* Stripe
* Notion
* Vercel
* Linear

## Style

* Clean
* Minimal
* Modern SaaS look
* Rounded corners
* Soft shadows
* Thin borders

---

## Color Palette

* Primary: #2563EB
* Secondary: #7C3AED
* Success: #22C55E
* Warning: #F59E0B
* Danger: #EF4444
* Background: #F8FAFC

---

## UI Components

* Cards
* Tables
* Modals
* Dropdowns
* Toast notifications
* Sidebar navigation
* Dashboard charts

---

# 📱 Responsive Design

Mobile-first approach:

* Sidebar → collapsible
* Tables → scrollable
* Cards → stacked
* Optimized for phone, tablet, desktop

---

# ⚙️ JavaScript Usage

Used for:

* Modal & dropdown
* Sidebar toggle
* AJAX (Fetch API)
* Form validation
* Live search
* Notifications
* Upload progress

---

# 🔐 Security

* PDO Prepared Statements
* CSRF Protection
* XSS Prevention
* Session Security
* Role-based Access Control
* File validation
* Audit logs

---

# ⚡ Performance

* Pagination
* Lazy loading
* Debounced search
* Optimized assets
* Minimal JS

---

# 🧪 Development Phases

## Phase 1

* Authentication
* UI Layout
* Basic roles

## Phase 2

* Scholarship system
* Application system

## Phase 3

* Multi-tenant SaaS
* Provider verification

## Phase 4

* Subscription system
* ToyyibPay integration

## Phase 5

* AI features
* Advanced analytics

---

# 🤖 Future Features

* AI Recommendation
* AI Document Review
* AI Essay Scoring
* Mobile App
* API Integration
* White-label system
* Scholarship Marketplace

---

# 🧩 Coding Standards

* PSR-12
* OOP principles
* MVC architecture
* Reusable components
* Clean code structure
* No inline CSS/JS

---

# 📌 Notes for Developers (OpenCode)

* Follow MVC structure strictly
* Do not mix business logic in views
* Use PDO for all database queries
* Use middleware for access control
* Maintain reusable components
* Ensure responsive UI
* Follow design system consistency

---

# 🏁 Conclusion

SISWACentral is designed as a **scalable SaaS platform**, not just a student project.

It focuses on:

* Centralization
* Simplicity
* Scalability
* Real-world usability

---
