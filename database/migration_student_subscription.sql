-- Student Subscription & Payment Tables

CREATE TABLE IF NOT EXISTS student_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    duration_days INT NOT NULL DEFAULT 30,
    max_applications INT NOT NULL DEFAULT 3,
    max_documents INT NOT NULL DEFAULT 5,
    has_smart_matching TINYINT(1) NOT NULL DEFAULT 0,
    has_priority_support TINYINT(1) NOT NULL DEFAULT 0,
    features TEXT,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS student_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    status ENUM('active','expired','cancelled') NOT NULL DEFAULT 'active',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES student_plans(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS student_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subscription_id INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending','success','failed') NOT NULL DEFAULT 'pending',
    bill_code VARCHAR(100),
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subscription_id) REFERENCES student_subscriptions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Seed plans
INSERT IGNORE INTO student_plans (id, name, price, duration_days, max_applications, max_documents, has_smart_matching, has_priority_support, features) VALUES
(1, 'Basic', 0, 30, 3, 5, 0, 0, '["3 applications per month","5 document uploads","Basic scholarship search","Email support"]'),
(2, 'Pro', 19.90, 30, 999, 99, 1, 1, '["Unlimited applications","Unlimited document uploads","Smart matching engine","Priority support","Early access scholarships","Advanced analytics"]');

-- Give existing students Basic plan
INSERT INTO student_subscriptions (user_id, plan_id, status, start_date, end_date)
SELECT u.id, 1, 'active', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY)
FROM users u WHERE u.role = 'student' AND u.id NOT IN (SELECT user_id FROM student_subscriptions);
