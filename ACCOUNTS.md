# SISWACentral — User Accounts Guide

## 👑 Admin Account

### Default Credentials

| Field    | Value                   |
| -------- | ----------------------- |
| Email    | `admin@siswacentral.com` |
| Password | `admin123`              |

> ⚠️ Change password immediately after first login.

### Access

| Page               | URL                   |
| ------------------ | --------------------- |
| Dashboard          | `/admin/dashboard`    |
| Manage Users       | `/admin/users`        |
| Manage Providers   | `/admin/providers`    |
| Manage Scholarships| `/admin/scholarships` |
| Payment History    | `/admin/payments`     |
| Subscription Plans | `/admin/plans`        |

### Capabilities

- View and manage all users (activate/deactivate)
- Verify or reject provider registrations
- Open/close any scholarship across the platform
- View all payments and subscription revenue
- Create, edit, and delete subscription plans
- Full platform oversight

---

## 🎓 Student Account

### Default Credentials

| Field    | Value                     |
| -------- | ------------------------- |
| Email    | `
` |
| Password | `student123`              |
| Name     | Ahmad Student             |
| CGPA     | 3.52                      |
| Course   | Computer Science          |
| University | Universiti Malaya       |

> Profile is pre-filled with sample data. Browse scholarships and apply immediately.

### Access

| Page               | URL                     |
| ------------------ | ----------------------- |
| Dashboard          | `/dashboard`            |
| My Profile         | `/profile`              |
| Account Settings   | `/settings`             |
| Browse Scholarships| `/scholarships`         |
| Saved Scholarships | `/saved-scholarships`   |
| My Applications    | `/applications`         |
| Application Detail | `/applications/{id}`    |
| Document Vault     | `/documents`            |
| Notifications      | `/notifications`        |
| Subscription       | `/subscription`         |

### Capabilities

- **Dashboard** — View real-time stats (applications count, open scholarships, document count, approvals)
- **Profile Management** — Personal info (phone, DOB, gender, address) + academic info (CGPA, course, university, income)
- **Document Vault** — Upload once (IC, transcript, income proof, resume, certificates), reuse across all applications
- **Scholarship Marketplace** — Browse, search, filter by CGPA/course/income, save/bookmark scholarships
- **Eligibility Checker** — Auto-checks if you meet CGPA/income requirements before applying
- **One-Click Apply** — Apply with a single click, documents auto-attach from vault
- **Application Tracking** — Full status timeline: submitted → screening → shortlisted → interview → approved/rejected
- **Cancel Applications** — Cancel submitted applications if needed
- **Saved Scholarships** — Bookmark scholarships for later, apply directly from saved list
- **Notifications** — Real-time alerts on application status changes
- **Account Settings** — Update name, change password

### Required Profile Fields

| Field          | Description                |
| -------------- | -------------------------- |
| Phone          | Contact number             |
| Date of Birth  | Date of birth              |
| Gender         | Male / Female              |
| CGPA           | Current CGPA (0.00 – 4.00) |
| Course         | Field of study             |
| University     | Institution name           |
| Income         | Monthly household income   |
| Address        | Home address               |

### Student Subscription Plans

| Plan         | Price    | Applications | Documents | Smart Matching |
| ------------ | -------- | ------------ | --------- | -------------- |
| **Basic**    | Free     | 3/month      | 5         | ❌              |
| **Pro**      | RM19.90/mo | Unlimited   | Unlimited | ✅              |

> Upgrade at `/subscription`. Pro includes priority support and early access scholarships.

---

## 🏢 Provider Account

### How to Register

1. Go to `/register`
2. Select **"Scholarship Provider"** as role
3. Fill in name, email, and password
4. Your provider profile is created automatically on first dashboard visit

### Quick Test Credentials

| Field    | Value                       |
| -------- | --------------------------- |
| Email    | `provider@siswacentral.com` |
| Password | `provider123`               |


### Access

| Page                  | URL                           |
| --------------------- | ----------------------------- |
| Dashboard             | `/provider/dashboard`         |
| My Scholarships       | `/provider/scholarships`      |
| Create Scholarship    | `/provider/scholarships/create` |
| All Applications      | `/provider/applications`      |
| Application Detail    | `/provider/applications/{id}` |
| Staff Management      | `/provider/staff`             |
| Subscription          | `/provider/subscription`      |
| Analytics             | `/provider/analytics`         |
| Notifications         | `/provider/notifications`     |
| Support Tickets       | `/provider/support-tickets`   |
| Settings              | `/provider/settings`          |
| Profile               | `/provider/profile`           |

### Capabilities

- **Dashboard** — Real-time stats, plan usage bars, subscription status, application breakdown, recent applicants
- **Scholarship Management** — Create, edit, delete, toggle open/close with plan enforcement (max scholarships by plan)
- **Application Management** — View applicants, update status (screening → shortlisted → interview → approved/rejected), add notes, view submitted documents
- **Staff Management** — Invite/remove team members with plan enforcement (max staff by plan)
- **Analytics** — Application trends, status breakdown, monthly chart, top scholarships by applications
- **Notifications** — Real-time alerts when students apply or status changes
- **Support Tickets** — Open support tickets, view staff responses
- **Settings** — Update name, change password
- **Subscription** — Browse/switch plans, view history, ToyyibPay integration

### Subscription Plans

| Plan         | Price    | Scholarships | Staff | Features                        |
| ------------ | -------- | ------------ | ----- | ------------------------------- |
| **Starter**  | Free     | 3            | 1     | Basic management                |
| **Professional** | RM99/mo  | 50           | 5     | Analytics, email notifications  |
| **Enterprise**| RM299/mo | Unlimited    | 20    | API access, white-label, priority |

---

## 🔐 Role Access Summary

| Feature                  | Admin | Provider | Student |
| ------------------------ | :---: | :------: | :-----: |
| Platform Dashboard       |  ✅   |    ✅    |   ✅    |
| Manage Profile           |  ✅   |    ✅    |   ✅    |
| Browse Scholarships      |  ✅   |    ❌    |   ✅    |
| Apply to Scholarships    |  ❌   |    ❌    |   ✅    |
| Track Applications       |  ✅   |    ✅    |   ✅    |
| Create Scholarships      |  ❌   |    ✅    |   ❌    |
| Review Applicants        |  ❌   |    ✅    |   ❌    |
| Manage Staff             |  ❌   |    ✅    |   ❌    |
| Manage Users             |  ✅   |    ❌    |   ❌    |
| Verify Providers         |  ✅   |    ❌    |   ❌    |
| View Payments            |  ✅   |    ❌    |   ❌    |
| Manage Plans             |  ✅   |    ❌    |   ❌    |
| Subscribe to Plans       |  ❌   |    ✅    |   ✅    |

---

## 🧪 Test Flow

1. **Login as Admin** → `admin@siswacentral.com` / `admin123` → Verify providers, manage users
2. **Register as Provider** → Create scholarships, review applications, manage staff
3. **Login as Student** → `student@siswacentral.com` / `student123` → Browse scholarships, save, apply, track status
4. **Provider Dashboard** → `/provider/dashboard` → View stats, usage bars, recent applicants
5. **Provider Settings** → `/provider/settings` → Change name or password
