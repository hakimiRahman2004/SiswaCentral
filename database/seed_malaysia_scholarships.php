<?php
/**
 * Seed real Malaysian scholarship providers and their scholarships.
 * Idempotent — safe to re-run.
 */
require_once __DIR__ . '/../app/Core/env.php';
require_once __DIR__ . '/../vendor/autoload.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::connect();
    $now = date('Y-m-d H:i:s');

    echo "=== Seeding Malaysian Scholarship Data ===\n\n";

    // ─── Provider definitions: [company_name, email, password, phone, registration_no, address] ───
    $providers = [
        ['Yayasan Khazanah',          'khazanah@scholarship.my',       'khazanah123',  '03-2263 8888', 'KHZ-2006-001', 'Level 33, Tower 2, Petronas Twin Towers, 50088 KL'],
        ['Yayasan Telekom Malaysia',  'tmfoundation@scholarship.my',   'tm123',        '03-2280 0800', 'YTM-2008-002', 'TM Headquarters, Jalan Pantai Baharu, 50672 KL'],
        ['Yayasan Petronas',          'petronas@scholarship.my',       'petronas123',  '03-2331 8000', 'PET-2007-003', 'Level 18, Tower 1, Petronas Twin Towers, 50088 KL'],
        ['Yayasan Tenaga Nasional',   'tnbfoundation@scholarship.my',  'tnb123',       '03-2296 0000', 'TNB-2009-004', 'TNB Headquarters, Jalan Bukit Pantai, 59100 KL'],
        ['Yayasan Maybank',           'maybankfoundation@scholarship.my', 'maybank123', '03-2070 8833', 'MBF-2005-005', 'Menara Maybank, 100 Jalan Tun Perak, 50050 KL'],
        ['Yayasan CIMB',              'cimbfoundation@scholarship.my', 'cimb123',      '03-2261 8888', 'CIMB-2008-006', 'CIMB HQ, Jalan Stesen Sentral 2, 50470 KL'],
        ['Yayasan Bank Rakyat',       'bankrakyat@scholarship.my',     'bankrakyat123','03-2612 6200', 'YBR-2010-007', 'Menara Kembar Bank Rakyat, Jalan Travers, 50470 KL'],
        ['Yayasan Peneraju',          'peneraju@scholarship.my',       'peneraju123',  '03-2772 8000', 'YPN-2011-008', 'Level 15, Menara 2, Jalan Gelenggang, 50490 KL'],
        ['Shell Malaysia Scholarship','shell@scholarship.my',          'shell123',     '03-2091 2000', 'SHE-2004-009', 'Shell Tower, Jalan Sultan Ismail, 50250 KL'],
        ['Yayasan Gamuda',            'gamuda@scholarship.my',         'gamuda123',    '03-7918 8000', 'GAM-2012-010', 'Gamuda HQ, Jalan Angkasa Mas, 59200 KL'],
        ['Yayasan Sime Darby',        'simbefoundation@scholarship.my','simedarby123', '03-2711 1000', 'SIME-2003-011','Level 10, Menara Sime Darby, 47301 PJ'],
        ['Yayasan Bursa Malaysia',    'bursafoundation@scholarship.my','bursa123',     '03-2034 7000', 'BUR-2013-012', 'Bursa Malaysia, Jalan Bukit Bintang, 50300 KL'],
    ];

    $userCheckStmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $userInsertStmt = $db->prepare("INSERT IGNORE INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, 'provider', 'active', ?)");
    $providerCheckStmt = $db->prepare("SELECT id FROM providers WHERE user_id = ?");
    $providerInsertStmt = $db->prepare("INSERT IGNORE INTO providers (user_id, company_name, registration_no, phone, address, is_verified, status, created_at) VALUES (?, ?, ?, ?, ?, 1, 'active', ?)");
    $staffInsertStmt = $db->prepare("INSERT IGNORE INTO staff (provider_id, user_id, role, status) VALUES (?, ?, 'owner', 'active')");
    $subCheckStmt = $db->prepare("SELECT COUNT(*) FROM subscriptions WHERE provider_id = ? AND status = 'active'");
    $subInsertStmt = $db->prepare("INSERT INTO subscriptions (provider_id, plan_id, status, start_date, end_date) VALUES (?, ?, 'active', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY))");

    $providerIds = [];

    foreach ($providers as $p) {
        [$company, $email, $pass, $phone, $regNo, $addr] = $p;

        // Create or find user
        $userCheckStmt->execute([$email]);
        $userId = $userCheckStmt->fetchColumn();

        if (!$userId) {
            $userInsertStmt->execute([$company, $email, password_hash($pass, PASSWORD_DEFAULT), $now]);
            $userId = $db->lastInsertId();
            echo "Created user: $company / $email / $pass\n";
        }

        // Create or find provider
        $providerCheckStmt->execute([$userId]);
        $providerId = $providerCheckStmt->fetchColumn();

        if (!$providerId) {
            $providerInsertStmt->execute([$userId, $company, $regNo, $phone, $addr, $now]);
            $providerId = $db->lastInsertId();
            $staffInsertStmt->execute([$providerId, $userId]);
            echo "Created provider: $company\n";
        }

        // Assign free starter plan if none
        $subCheckStmt->execute([$providerId]);
        if (!$subCheckStmt->fetchColumn()) {
            $starterPlan = $db->query("SELECT id FROM subscription_plans WHERE price = 0 LIMIT 1")->fetchColumn();
            if ($starterPlan) {
                $subInsertStmt->execute([$providerId, $starterPlan]);
            }
        }

        $providerIds[$company] = $providerId;
    }

    echo "\n";

    // ─── Scholarships per provider ─────────────────────────────────────────
    $scholarshipCheckStmt = $db->prepare("SELECT COUNT(*) FROM scholarships WHERE provider_id = ? AND name = ?");
    $scholarshipInsertStmt = $db->prepare("INSERT INTO scholarships (provider_id, name, description, amount, min_cgpa, max_income, eligible_courses, requirements, deadline, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $data = [
        // Yayasan Khazanah
        ['Yayasan Khazanah', 'Khazanah Watan Scholarship', 'Prestige scholarship for outstanding Malaysians to pursue undergraduate studies at top global universities. Covers full tuition, living expenses, and leadership development program.', 250000.00, 3.75, 10000.00, 'All fields (must be offered admission to top 50 global university)', 'Malaysian citizen, CGPA 3.75+, exceptional leadership, strong co-curricular record', 1, '2026-10-15'],
        ['Yayasan Khazanah', 'Khazanah Nasional Degree Programme', 'Full sponsorship for Malaysian students at selected local and international partner universities. Includes internship and mentorship.', 120000.00, 3.50, 8000.00, 'Engineering, Computer Science, Economics, Law, Architecture', 'Malaysian, CGPA 3.5+, active in extracurricular activities', 0, '2026-09-30'],

        // Yayasan Telekom Malaysia
        ['Yayasan Telekom Malaysia', 'TM Foundation Scholarship', 'For students pursuing ICT-related degrees. Covers tuition, living allowance of RM1,200/month, and guaranteed internship at TM.', 80000.00, 3.30, 7000.00, 'Computer Science, IT, Software Engineering, Data Science, Telecommunications', 'CGPA 3.3+, strong interest in ICT, household income ≤ RM7,000', 1, '2026-11-01'],
        ['Yayasan Telekom Malaysia', 'TM Digital Leaders Award', 'Supports future digital leaders with full degree sponsorship, devices, and industry exposure.', 100000.00, 3.50, 6000.00, 'Computer Science, Information Systems, Multimedia, Cybersecurity', 'CGPA 3.5+, proven digital/tech portfolio', 0, '2026-08-15'],

        // Yayasan Petronas
        ['Yayasan Petronas', 'Yayasan Petronas Scholarship', 'Flagship scholarship for high achievers pursuing engineering and related fields. Full coverage including living allowance and industry placement.', 150000.00, 3.60, 8000.00, 'Chemical, Mechanical, Electrical, Civil, Petroleum Engineering', 'CGPA 3.6+, strong interest in oil & gas industry, leadership qualities', 1, '2026-12-01'],
        ['Yayasan Petronas', 'PETRONAS Education Sponsorship', 'For students in accounting, finance, and business at local universities. Includes internship and guaranteed employment upon graduation.', 70000.00, 3.30, 7000.00, 'Accounting, Finance, Business Administration, Economics', 'CGPA 3.3+, household income ≤ RM7,000', 0, '2026-10-01'],

        // Yayasan Tenaga Nasional
        ['Yayasan Tenaga Nasional', 'TNB Scholarship', 'For high-achieving students in engineering and technical fields. Full tuition coverage with living allowance.', 90000.00, 3.40, 7500.00, 'Electrical, Mechanical, Civil Engineering, Computer Science', 'CGPA 3.4+, Malaysian citizen, household income ≤ RM7,500', 1, '2026-09-15'],
        ['Yayasan Tenaga Nasional', 'Yayasan TNB Degree Programme', 'Partial to full sponsorship for students in energy-related fields at IPTA universities.', 60000.00, 3.00, 8000.00, 'Electrical Engineering, Power Systems, Energy Management, IT', 'CGPA 3.0+, household income ≤ RM8,000', 0, '2026-07-30'],

        // Yayasan Maybank
        ['Yayasan Maybank', 'Maybank Scholarship Award', 'Prestigious scholarship for outstanding students pursuing finance, banking, and related fields.', 100000.00, 3.50, 7000.00, 'Finance, Accounting, Business, Economics, Law', 'CGPA 3.5+, leadership experience, involved in community service', 1, '2026-10-30'],
        ['Yayasan Maybank', 'Maybank Foundation Education Grant', 'Need-based grant for students from low-income families pursuing tertiary education at local universities.', 30000.00, 2.80, 4000.00, 'All fields', 'Household income ≤ RM4,000, active in community activities', 0, '2026-08-31'],

        // Yayasan CIMB
        ['Yayasan CIMB', 'CIMB ASEAN Scholarship', 'Regional scholarship for outstanding ASEAN students (priority Malaysian) pursuing degree programs.', 120000.00, 3.60, 8000.00, 'Business, Finance, Economics, Accounting, Law', 'CGPA 3.6+, ASEAN citizen, demonstrated leadership potential', 1, '2026-11-15'],
        ['Yayasan CIMB', 'CIMB Foundation Education Assistance', 'Financial assistance for deserving students from B40 households.', 25000.00, 2.50, 3000.00, 'All fields', 'Household income ≤ RM3,000, good academic standing', 0, '2026-07-15'],

        // Yayasan Bank Rakyat
        ['Yayasan Bank Rakyat', 'Bank Rakyat Excellent Student Scholarship', 'For top achievers pursuing professional and degree programs at IPTA/IPTS.', 50000.00, 3.50, 6000.00, 'Finance, Business, Accounting, Banking, Law', 'CGPA 3.5+, co-curricular involvement, active in student organizations', 1, '2026-09-01'],
        ['Yayasan Bank Rakyat', 'Yayasan Bank Rakyat Sports Scholarship', 'For student athletes excelling in sports while pursuing tertiary education.', 35000.00, 2.50, 8000.00, 'All fields', 'National/state level athlete, CGPA 2.5+, active in sports', 0, '2026-06-30'],

        // Yayasan Peneraju
        ['Yayasan Peneraju', 'Peneraju Tunas Programme', 'Pre-university and degree sponsorship for Bumiputera students in STEM, professional, and technical fields.', 80000.00, 3.00, 7000.00, 'STEM, Accounting, Finance, Digital Technology, Healthcare', 'Bumiputera, CGPA 3.0+, household income ≤ RM7,000', 1, '2026-10-01'],
        ['Yayasan Peneraju', 'Peneraju Professional Certification', 'Sponsorship for professional certifications and upskilling programs in digital and technical fields.', 15000.00, 2.80, 8000.00, 'ICT, Digital Marketing, Data Analytics, Cybersecurity', 'Bumiputera aged 18-35, basic qualification in related field', 0, '2026-12-15'],

        // Shell Malaysia
        ['Shell Malaysia Scholarship', 'Shell Malaysia Scholarship', 'For outstanding students pursuing engineering and technical degrees. Offers full sponsorship plus summer internship at Shell.', 130000.00, 3.50, 9000.00, 'Chemical, Mechanical, Electrical, Petroleum Engineering', 'CGPA 3.5+, strong analytical skills, interest in energy sector', 1, '2026-10-20'],
        ['Shell Malaysia Scholarship', 'Shell Technical Excellence Award', 'Partial scholarship for students in technical fields at local polytechnics and universities.', 35000.00, 3.00, 8000.00, 'Engineering, Geoscience, Logistics, Supply Chain', 'CGPA 3.0+, technical aptitude demonstrated', 0, '2026-08-01'],

        // Yayasan Gamuda
        ['Yayasan Gamuda', 'Gamuda Scholarship', 'For students pursuing civil engineering, construction, and related fields. Includes industrial training and possible employment.', 75000.00, 3.30, 7000.00, 'Civil Engineering, Quantity Surveying, Construction Management, Architecture', 'CGPA 3.3+, interest in construction industry, strong teamwork skills', 1, '2026-11-30'],
        ['Yayasan Gamuda', 'Gamuda Women in Construction Grant', 'Encouraging female students to pursue careers in construction and engineering.', 50000.00, 3.00, 7500.00, 'Civil Engineering, Architecture, Project Management', 'Female, CGPA 3.0+, enrolled in construction-related degree', 0, '2026-09-20'],

        // Yayasan Sime Darby
        ['Yayasan Sime Darby', 'Sime Darby Outstanding Achievement Award', 'For high-achieving students in business andplantations-related fields. Covers full tuition.', 90000.00, 3.50, 8000.00, 'Business, Economics, Engineering, Agriculture, Environmental Science', 'CGPA 3.5+, leadership qualities, community involvement', 1, '2026-10-10'],
        ['Yayasan Sime Darby', 'Sime Darby Foundation Grant', 'Need-based grant for students from rural communities pursuing higher education.', 20000.00, 2.50, 3500.00, 'All fields', 'From rural/small-town background, household income ≤ RM3,500', 0, '2026-07-31'],

        // Yayasan Bursa Malaysia
        ['Yayasan Bursa Malaysia', 'Bursa Malaysia Scholarship', 'For students pursuing degrees in capital market-related fields. Includes mentorship from industry professionals.', 60000.00, 3.40, 7000.00, 'Finance, Accounting, Economics, Business, Law', 'CGPA 3.4+, interest in capital market, strong analytical skills', 1, '2026-11-01'],
        ['Yayasan Bursa Malaysia', 'Bursa Education Bursary', 'Financial assistance for students from underserved communities pursuing pre-university and degree studies.', 18000.00, 2.50, 3000.00, 'All fields', 'Household income ≤ RM3,000, good moral character', 0, '2026-06-15'],
    ];

    $count = 0;
    foreach ($data as $s) {
        [$company, $name, $desc, $amount, $cgpa, $income, $courses, $reqs, $isOpen, $deadline] = $s;
        $pid = $providerIds[$company] ?? null;
        if (!$pid) continue;

        $scholarshipCheckStmt->execute([$pid, $name]);
        if ($scholarshipCheckStmt->fetchColumn() > 0) continue;

        $status = $isOpen ? 'open' : 'closed';
        $created = date('Y-m-d H:i:s', strtotime("-" . random_int(1, 30) . " days"));
        $scholarshipInsertStmt->execute([$pid, $name, $desc, $amount, $cgpa, $income, $courses, $reqs, $deadline, $status, $created]);
        $count++;
    }

    echo "Created $count new scholarships.\n";

    echo "\n=== Malaysian scholarship seed complete! ===\n";
    echo "Total providers: " . count($providers) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
