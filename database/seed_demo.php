<?php
/**
 * Seed demo data: test accounts, scholarships, applications, notifications.
 * Idempotent — safe to re-run.
 */
require_once __DIR__ . '/../app/Core/env.php';
require_once __DIR__ . '/../vendor/autoload.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::connect();
    $now = date('Y-m-d H:i:s');

    echo "=== Seeding Demo Data ===\n\n";

    // ─── 1. Student user ─────────────────────────────────────────────────
    $studentEmail = 'student@siswacentral.com';
    $studentId = $db->prepare("SELECT id FROM users WHERE email = ?");
    $studentId->execute([$studentEmail]);
    $studentId = $studentId->fetchColumn();

    if (!$studentId) {
        $db->prepare("INSERT INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, 'student', 'active', ?)")
            ->execute(['Ahmad Student', $studentEmail, password_hash('student123', PASSWORD_DEFAULT), $now]);
        $studentId = $db->lastInsertId();
        echo "Created student: $studentEmail / student123\n";
    } else {
        echo "Student already exists.\n";
    }

    // Student profile
    $check = $db->prepare("SELECT COUNT(*) FROM student_profiles WHERE user_id = ?");
    $check->execute([$studentId]);
    if (!$check->fetchColumn()) {
        $db->prepare("INSERT INTO student_profiles (user_id, phone, address, date_of_birth, gender, cgpa, course, university, income) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([$studentId, '012-3456789', 'No. 12, Jalan Universiti, 50603 Kuala Lumpur', '2001-05-15', 'male', 3.52, 'Computer Science', 'Universiti Malaya', 3500.00]);
        echo "Created student profile.\n";
    }

    // ─── 2. Provider user ────────────────────────────────────────────────
    $providerEmail = 'provider@siswacentral.com';
    $providerUserId = $db->prepare("SELECT id FROM users WHERE email = ?");
    $providerUserId->execute([$providerEmail]);
    $providerUserId = $providerUserId->fetchColumn();

    if (!$providerUserId) {
        $db->prepare("INSERT INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, 'provider', 'active', ?)")
            ->execute(['Yayasan Pendidikan Maju', $providerEmail, password_hash('provider123', PASSWORD_DEFAULT), $now]);
        $providerUserId = $db->lastInsertId();
        echo "Created provider user: $providerEmail / provider123\n";
    } else {
        echo "Provider user already exists.\n";
    }

    // Provider company
    $providerId = $db->prepare("SELECT id FROM providers WHERE user_id = ?");
    $providerId->execute([$providerUserId]);
    $providerId = $providerId->fetchColumn();

    if (!$providerId) {
        $db->prepare("INSERT INTO providers (user_id, company_name, registration_no, phone, address, is_verified, status, created_at) VALUES (?, ?, ?, ?, ?, 1, 'active', ?)")
            ->execute([$providerUserId, 'Yayasan Pendidikan Maju', 'REG-2018-00472', '03-78901234', 'Level 15, Menara Budaya, Jalan Tun Razak, 50400 Kuala Lumpur', $now]);
        $providerId = $db->lastInsertId();
        // Staff entry
        $db->prepare("INSERT INTO staff (provider_id, user_id, role, status) VALUES (?, ?, 'owner', 'active')")
            ->execute([$providerId, $providerUserId]);
        echo "Created provider company + staff entry.\n";
    } else {
        echo "Provider company already exists.\n";
    }

    // ─── 3. Free subscription for provider (Starter plan) ────────────────
    $subCheck = $db->prepare("SELECT COUNT(*) FROM subscriptions WHERE provider_id = ? AND status = 'active'");
    $subCheck->execute([$providerId]);
    if (!$subCheck->fetchColumn()) {
        $starterPlan = $db->query("SELECT id FROM subscription_plans WHERE price = 0 LIMIT 1")->fetchColumn();
        if ($starterPlan) {
            $db->prepare("INSERT INTO subscriptions (provider_id, plan_id, status, start_date, end_date) VALUES (?, ?, 'active', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY))")
                ->execute([$providerId, $starterPlan]);
            echo "Added free Starter subscription for provider.\n";
        }
    }

    // ─── 4. Student subscription (Basic plan) ───────────────────────────
    $stuSubCheck = $db->prepare("SELECT COUNT(*) FROM student_subscriptions WHERE user_id = ? AND status = 'active'");
    $stuSubCheck->execute([$studentId]);
    if (!$stuSubCheck->fetchColumn()) {
        $basicPlan = $db->query("SELECT id FROM student_plans WHERE price = 0 LIMIT 1")->fetchColumn();
        if ($basicPlan) {
            $db->prepare("INSERT INTO student_subscriptions (user_id, plan_id, status, start_date, end_date) VALUES (?, ?, 'active', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY))")
                ->execute([$studentId, $basicPlan]);
            echo "Added Basic subscription for student.\n";
        }
    }

    // ─── 5. Sample scholarships ─────────────────────────────────────────
    $existingScholarships = $db->prepare("SELECT COUNT(*) FROM scholarships WHERE provider_id = ?");
    $existingScholarships->execute([$providerId]);
    if ($existingScholarships->fetchColumn() == 0) {
        $scholarships = [
            ['Computer Science Excellence Award', 'Full tuition coverage for outstanding Computer Science students pursuing their degree at any Malaysian public university. Includes monthly living allowance of RM1,000.', 50000.00, 3.50, 5000.00, 'Computer Science, Information Technology, Software Engineering', 'CGPA 3.5+, household income ≤ RM5,000, active in co-curricular activities', '2026-09-30', 'open'],
            ['Engineering & Technology Scholarship', 'Supporting bright engineering minds. Covers tuition fees up to RM40,000 per year plus internship placement with our industry partners.', 40000.00, 3.20, 6000.00, 'Civil Engineering, Mechanical Engineering, Electrical Engineering, Chemical Engineering', 'CGPA 3.2+, household income ≤ RM6,000, enrolled in accredited engineering program', '2026-10-15', 'open'],
            ['Bumiputera Academic Excellence', 'Merit-based scholarship for high-achieving Bumiputera students in any field of study. Includes mentorship program.', 30000.00, 3.00, 7000.00, 'All fields', 'CGPA 3.0+, household income ≤ RM7,000, Bumiputera status', '2026-08-31', 'open'],
            ['Women in STEM Scholarship', 'Encouraging female students to pursue careers in Science, Technology, Engineering, and Mathematics. Full tuition coverage.', 45000.00, 3.40, 5500.00, 'STEM fields: Science, Technology, Engineering, Mathematics', 'CGPA 3.4+, female, household income ≤ RM5,500, pursuing STEM degree', '2026-11-01', 'open'],
            ['Community Leadership Grant', 'For students who have demonstrated exceptional community service and leadership potential. Partial scholarship with leadership development program.', 15000.00, 2.80, 8000.00, 'All fields', 'CGPA 2.8+, proven community involvement, leadership roles in organizations', '2026-07-31', 'closed'],
            ['Dean\'s List Scholarship', 'Sponsored by industry partners for students maintaining Dean\'s List standing. Renewable annually based on performance.', 20000.00, 3.70, 10000.00, 'Business, Finance, Accounting, Economics', 'CGPA 3.7+, enrolled in business-related program, strong extracurricular record', '2026-12-31', 'open'],
        ];

        $stmt = $db->prepare("INSERT INTO scholarships (provider_id, name, description, amount, min_cgpa, max_income, eligible_courses, requirements, deadline, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($scholarships as $i => $s) {
            $created = date('Y-m-d H:i:s', strtotime("-" . (6 - $i) . " days"));
            $stmt->execute([$providerId, $s[0], $s[1], $s[2], $s[3], $s[4], $s[5], $s[6], $s[7], $s[8], $created]);
        }
        echo "Created " . count($scholarships) . " sample scholarships.\n";
    } else {
        echo "Scholarships already exist.\n";
    }

    // ─── 6. Sample applications ─────────────────────────────────────────
    $existingApps = $db->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ?");
    $existingApps->execute([$studentId]);
    if ($existingApps->fetchColumn() == 0) {
        $scholarshipIds = $db->prepare("SELECT id, name FROM scholarships WHERE provider_id = ? ORDER BY created_at ASC");
        $scholarshipIds->execute([$providerId]);
        $scholarships = $scholarshipIds->fetchAll();

        $appData = [
            ['submitted', 3, 'submitted'],
            ['screening', 5, 'screening'],
            ['shortlisted', 7, 'shortlisted'],
            ['approved', 10, 'approved'],
        ];

        $stmt = $db->prepare("INSERT INTO applications (student_id, scholarship_id, provider_id, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($appData as $i => $a) {
            $sch = $scholarships[$i] ?? $scholarships[0];
            $created = date('Y-m-d H:i:s', strtotime("-" . (10 - $i * 2) . " days"));
            $updated = date('Y-m-d H:i:s', strtotime("-" . (8 - $i * 2) . " days"));
            $stmt->execute([$studentId, $sch['id'], $providerId, $a[2], $created, $updated]);
        }
        echo "Created 4 sample applications.\n";
    } else {
        echo "Applications already exist.\n";
    }

    // ─── 7. Sample notifications ────────────────────────────────────────
    $existingNotifs = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ?");
    $existingNotifs->execute([$studentId]);
    if ($existingNotifs->fetchColumn() == 0) {
        $studentNotifs = [
            ['application', 'Application Received', 'Your application for "Computer Science Excellence Award" has been received and is being reviewed.'],
            ['application', 'Application Shortlisted', 'Congratulations! You have been shortlisted for the "Engineering & Technology Scholarship". We will contact you for an interview.'],
            ['application', 'Application Approved', 'Great news! Your application for "Bumiputera Academic Excellence" has been approved. Check your email for next steps.'],
            ['subscription', 'Basic Plan Active', 'Your Basic subscription plan is active. You have 3 applications remaining this month.'],
            ['system', 'Welcome to SISWACentral', 'Welcome aboard! Complete your profile to unlock personalized scholarship recommendations.'],
        ];
        $stmt = $db->prepare("INSERT INTO notifications (user_id, type, title, message, created_at) VALUES (?, ?, ?, ?, ?)");
        foreach ($studentNotifs as $i => $n) {
            $created = date('Y-m-d H:i:s', strtotime("-" . (14 - $i * 2) . " days"));
            $stmt->execute([$studentId, $n[0], $n[1], $n[2], $created]);
        }
        echo "Created 5 student notifications.\n";
    } else {
        echo "Student notifications already exist.\n";
    }

    // Provider notifications
    $existingProvNotifs = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ?");
    $existingProvNotifs->execute([$providerUserId]);
    if ($existingProvNotifs->fetchColumn() == 0) {
        $providerNotifs = [
            ['application', 'New Application', 'A student has applied for "Computer Science Excellence Award". Review their application.'],
            ['application', 'Application Update', 'A student has withdrawn their application for "Community Leadership Grant".'],
            ['system', 'Subscription Reminder', 'Your Starter plan has 5 days remaining. Upgrade to Professional to continue posting scholarships.'],
            ['application', 'New Application', 'A new application has been submitted for "Engineering & Technology Scholarship".'],
            ['system', 'Welcome to SISWACentral', 'Your provider account is now active. Start creating scholarships to attract top talent.'],
        ];
        $stmt = $db->prepare("INSERT INTO notifications (user_id, type, title, message, created_at) VALUES (?, ?, ?, ?, ?)");
        foreach ($providerNotifs as $i => $n) {
            $created = date('Y-m-d H:i:s', strtotime("-" . (12 - $i) . " days"));
            $stmt->execute([$providerUserId, $n[0], $n[1], $n[2], $created]);
        }
        echo "Created 5 provider notifications.\n";
    } else {
        echo "Provider notifications already exist.\n";
    }

    echo "\n=== Demo seed complete! ===\n";
    echo "Student:  student@siswacentral.com / student123\n";
    echo "Provider: provider@siswacentral.com / provider123\n";
    echo "Admin:    admin@siswacentral.com / admin123\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
