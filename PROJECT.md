# PROJECT.md

# SISWACentral — Product Specification

## 🧭 Product Overview

SISWACentral is a **centralized scholarship platform** that connects students and scholarship providers into a single ecosystem.

The platform eliminates repetitive application processes by allowing:

* Students to create **one profile**
* Upload **documents once**
* Apply to **multiple scholarships**

At the same time, providers can:

* Manage scholarships
* Review applications
* Analyze data
* Automate workflows

---

# 🎯 Core Value Proposition

## For Students

> Apply to multiple scholarships without repeating the same process.

* One account
* One profile
* One document vault
* Unlimited applications

---

## For Providers

> Manage scholarship applications efficiently in one platform.

* Centralized system
* Faster screening
* Automated filtering
* Analytics insights

---

# 👥 User Roles

## 1. Student

### Key Capabilities

* Register & login
* Manage profile
* Upload documents
* Search scholarships
* Apply for scholarships
* Track application status
* Receive notifications

---

## 2. Provider

### Key Capabilities

* Register organization
* Subscribe to plan
* Create scholarships
* Define requirements
* Filter applicants
* Review applications
* Manage staff
* View reports

---

## 3. Admin

### Key Capabilities

* Approve providers
* Manage users
* Moderate scholarships
* Monitor platform usage
* Manage subscriptions
* Handle payments

---

# 🧱 Core Modules

## 1. Authentication Module

* Register (Student / Provider)
* Login / Logout
* Email verification
* Password reset
* Session management

---

## 2. Student Module

### Profile System

* Personal info
* Education
* Financial background
* Skills & achievements

---

### Document Vault

Students upload once:

* IC / Passport
* Transcript
* Income proof
* Resume
* Certificates

Reusable across applications.

---

### Scholarship Marketplace

Features:

* Search
* Filter
* Sort
* Save scholarships

---

### Application System

* One-click apply
* Auto attach documents
* Track status
* View history

---

## 3. Provider Module

### Provider Onboarding

Flow:

Register → Upload documents → Admin approval → Subscription → Dashboard access

---

### Scholarship Management

* Create scholarship
* Edit / update
* Publish / unpublish
* Close applications

---

### Requirement Builder

Define required documents:

* Mandatory
* Optional

---

### Eligibility Rules

* CGPA
* Income
* Course
* University
* Location

---

### Application Pipeline

```id="pipelineflow"
Submitted
→ Screening
→ Shortlisted
→ Interview
→ Approved
→ Rejected
```

---

### Staff Management

Roles:

* Owner
* Admin
* Reviewer
* Finance
* Viewer

---

### Analytics Dashboard

* Total applications
* Approval rate
* Demographics
* Trends

---

## 4. Admin Module

### Provider Verification

* Review documents
* Approve / reject
* Prevent fraud

---

### Scholarship Moderation

* Review submissions
* Approve before publish

---

### Platform Monitoring

* Active users
* Total applications
* Revenue tracking

---

# 💳 SaaS Subscription System

## Subscription Flow

Provider selects plan → Payment → Subscription activated

---

## Plan Structure

### Starter

* Limited usage

### Professional

* Full features

### Enterprise

* Advanced features

---

## Subscription Status

* Pending
* Active
* Expired
* Cancelled
* Suspended

---

# 💰 Payment System (ToyyibPay)

## Integration Type

* ToyyibPay Sandbox (development)
* Production ready later

---

## Payment Flow

1. Provider selects plan
2. System generates bill
3. Redirect to ToyyibPay
4. Payment processed
5. Callback updates system
6. Subscription activated

---

# 🔄 Workflow

## Student Workflow

Register → Complete Profile → Upload Documents → Search Scholarship → Apply → Track Status

---

## Provider Workflow

Register → Verify → Subscribe → Create Scholarship → Receive Applications → Review → Approve

---

## Admin Workflow

Monitor → Verify Providers → Approve Scholarships → Manage Platform

---

# 🧠 Smart Features (Future)

## Recommendation Engine

* Suggest scholarships based on profile

---

## Eligibility Checker

* Auto detect eligibility before applying

---

## AI Document Review

* Detect missing / invalid documents

---

## AI Essay Review

* Score essays
* Suggest improvements

---

# 📊 Marketplace Concept

All scholarships are centralized.

Students can:

* Discover scholarships nationwide
* Compare opportunities
* Apply easily

---

# ⚙️ System Rules

* Students must complete profile before applying
* Providers must be approved before publishing
* Subscription must be active to use provider features
* Applications cannot be edited after submission (optional rule)

---

# 🔐 Access Control

Role-based system:

* Student → own data only
* Provider → own tenant data only
* Admin → full access

---

# 🧪 MVP Scope (Minimum Viable Product)

## Must Have

* Authentication
* Student profile
* Document upload
* Scholarship listing
* Application system
* Provider dashboard

---

## Nice to Have

* Recommendation system
* Analytics
* Notifications

---

## Future (Post-MVP)

* AI features
* Mobile app
* API integration
* Marketplace expansion

---

# 🏁 Summary

SISWACentral is designed as:

* A **marketplace platform**
* A **multi-tenant SaaS system**
* A **student-first application experience**

The main goal is to simplify scholarship access while giving providers powerful tools to manage applications efficiently.
