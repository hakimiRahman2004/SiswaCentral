<?php
require_once __DIR__ . '/../app/Core/env.php';
require_once __DIR__ . '/../vendor/autoload.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::connect();

    $check = $db->query("SELECT COUNT(*) FROM users WHERE email = 'admin@siswacentral.com'")->fetchColumn();
    if ($check > 0) {
        echo "Admin user already exists.\n";
    } else {
        $stmt = $db->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Super Admin', 'admin@siswacentral.com', password_hash('admin123', PASSWORD_DEFAULT), 'admin', 'active']);
        echo "Admin user created: admin@siswacentral.com / admin123\n";
    }

    $planCheck = $db->query("SELECT COUNT(*) FROM subscription_plans")->fetchColumn();
    if ($planCheck > 0) {
        echo "Subscription plans already exist.\n";
    } else {
        $db->prepare("INSERT INTO subscription_plans (name, price, duration_days, max_staff, max_scholarships, features) VALUES (?, ?, ?, ?, ?, ?)")->execute(['Starter', 0, 30, 1, 3, 'Basic scholarship management, Up to 3 scholarships, Single staff']);
        $db->prepare("INSERT INTO subscription_plans (name, price, duration_days, max_staff, max_scholarships, features) VALUES (?, ?, ?, ?, ?, ?)")->execute(['Professional', 99, 30, 5, 50, 'Unlimited scholarships, Analytics, Email notifications, Up to 5 staff']);
        $db->prepare("INSERT INTO subscription_plans (name, price, duration_days, max_staff, max_scholarships, features) VALUES (?, ?, ?, ?, ?, ?)")->execute(['Enterprise', 299, 30, 20, 999, 'API access, Custom domain, White-label, Priority support, Up to 20 staff']);
        echo "Subscription plans created.\n";
    }

    echo "Seed complete.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
