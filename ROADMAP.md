# 🚀 SISWACentral Development Roadmap

## 📌 Overview

This roadmap outlines the full development lifecycle of **SISWACentral**, a SaaS platform that centralizes scholarships in Malaysia.

Goals:

* Build a functional MVP quickly
* Scale into a SaaS product
* Implement monetization (ToyyibPay)
* Deliver a modern, responsive system

Tech Stack:

* HTML5 + Tailwind CSS (CLI)
* PHP (OOP + PDO)
* MySQL
* JavaScript (basic interactivity)

---

# 🟢 PHASE 1: PROJECT SETUP

## 🎯 Objective:

Set up a clean and scalable project foundation

### Tasks:

* Create folder structure (MVC)
* Setup database connection (PDO)
* Configure routing system
* Setup Tailwind CSS (CLI build)
* Create base layout (header, navbar, sidebar)

### Output:

* Working base system
* Reusable layout components

---

# 🔐 PHASE 2: AUTHENTICATION SYSTEM

## 🎯 Objective:

Allow users to register and login

### Features:

* Register (student & provider)
* Login / Logout
* Role-based session (admin, student, provider)

### Tasks:

* Create `users` table
* Build AuthController
* Implement password hashing
* Setup session management

### Output:

* Secure login system
* Role-based access control

---

# 👨‍🎓 PHASE 3: STUDENT SYSTEM (CORE)

## 🎯 Objective:

Enable students to manage profile and apply for scholarships

### Features:

* Student dashboard
* Profile management (CGPA, course, income)
* Upload documents (centralized)
* View matched scholarships
* Apply with one click

### Tasks:

* Create student profile system
* Implement document upload
* Build scholarship matching logic
* Create application system

### Output:

* Fully functional student experience

---

# 🏢 PHASE 4: PROVIDER SYSTEM

## 🎯 Objective:

Allow providers to create and manage scholarships

### Features:

* Provider dashboard
* Create scholarship
* Set criteria (CGPA, income, course)
* View applicants
* Approve / reject applications

### Tasks:

* Create provider profile
* Build scholarship CRUD
* Connect applications to provider
* Display applicant data

### Output:

* Providers can manage scholarship lifecycle

---

# 💳 PHASE 5: SAAS & PAYMENT (TOYYIBPAY)

## 🎯 Objective:

Monetize platform using subscription model

### Features:

* Subscription plans (Basic / Pro)
* Payment integration (ToyyibPay sandbox)
* Payment tracking
* Plan-based feature restriction

### Tasks:

* Create plans & subscriptions tables
* Integrate ToyyibPay API
* Handle payment callback
* Restrict features based on plan

### Output:

* Fully working SaaS monetization system

---

# 🛠️ PHASE 6: ADMIN SYSTEM

## 🎯 Objective:

Provide system control and monitoring

### Features:

* Admin dashboard
* Manage users (student & provider)
* Manage scholarships
* View payments
* System overview (analytics)

### Tasks:

* Build admin panel UI
* Implement user management
* Create reporting dashboard

### Output:

* Full system control by admin

---

# 🎨 PHASE 7: UI/UX IMPROVEMENT

## 🎯 Objective:

Make system modern, responsive, and user-friendly

### Features:

* Clean dashboard design
* Responsive layout (mobile + desktop)
* Better user experience
* Loading states & alerts

### Tasks:

* Refactor UI using Tailwind
* Improve navigation
* Add interactive elements (JS)

### Output:

* Professional SaaS interface

---

# 🔒 PHASE 8: SECURITY & OPTIMIZATION

## 🎯 Objective:

Ensure system is secure and efficient

### Tasks:

* Input validation & sanitization
* CSRF protection
* File upload security
* Optimize database queries
* Add error handling

### Output:

* Secure and stable system

---

# 🚀 PHASE 9: DEPLOYMENT

## 🎯 Objective:

Launch SISWACentral online

### Tasks:

* Setup hosting (shared/VPS)
* Configure domain & SSL
* Setup production database
* Configure environment variables
* Test live system

### Output:

* Live production system

---

# 📈 PHASE 10: SCALING & FUTURE FEATURES

## 🎯 Objective:

Expand platform capabilities

### Features:

* Email notifications
* Scholarship categories
* Advanced filtering
* AI-based recommendation
* Mobile app (future)
* Analytics dashboard

### Output:

* Scalable and competitive platform

---

# 🧭 DEVELOPMENT FLOW SUMMARY

1. Setup project
2. Build authentication
3. Develop student system (core)
4. Build provider system
5. Integrate SaaS payment
6. Add admin system
7. Improve UI/UX
8. Secure & optimize
9. Deploy
10. Scale features

---

# ✅ CONCLUSION

This roadmap ensures:

* Structured development
* Clear progression from MVP → SaaS
* Strong foundation for scaling

---

🔥 Follow this roadmap step-by-step to successfully build SISWACentral into a full SaaS platform
