-- Ensure student plans exist (idempotent — safe to re-run)
INSERT IGNORE INTO student_plans (id, name, price, duration_days, max_applications, max_documents, has_smart_matching, has_priority_support, features, is_active) VALUES
(1, 'Basic', 0, 30, 3, 5, 0, 0, '["3 applications per month","5 document uploads","Basic scholarship search","Email support"]', 1),
(2, 'Premium', 19.90, 30, 999, 99, 1, 1, '["Unlimited applications","Unlimited document uploads","Smart matching engine","Priority support","Early access scholarships","Advanced analytics"]', 1);

-- Give all existing students without a subscription the Basic plan
INSERT INTO student_subscriptions (user_id, plan_id, status, start_date, end_date)
SELECT u.id, 1, 'active', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY)
FROM users u
WHERE u.role = 'student'
  AND u.id NOT IN (SELECT user_id FROM student_subscriptions);
