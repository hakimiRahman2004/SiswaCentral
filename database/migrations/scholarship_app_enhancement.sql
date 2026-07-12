-- ============================================================
-- Scholarship Application Management System - Database Migration
-- ============================================================

-- 1. Expand application status ENUM to include all workflow states
ALTER TABLE applications MODIFY COLUMN status ENUM(
  'pending','submitted','screening','shortlisted','interview',
  'document_required','approved','rejected','withdrawn','expired'
) DEFAULT 'submitted';

-- 2. Add review tracking columns to applications
ALTER TABLE applications ADD COLUMN reviewed_by INT NULL AFTER notes;
ALTER TABLE applications ADD COLUMN reviewed_at TIMESTAMP NULL AFTER reviewed_by;
ALTER TABLE applications ADD COLUMN awarded_at TIMESTAMP NULL AFTER reviewed_at;
ALTER TABLE applications ADD COLUMN rejection_reason VARCHAR(500) NULL AFTER awarded_at;
ALTER TABLE applications ADD COLUMN application_number VARCHAR(20) NULL AFTER rejection_reason;

-- 3. Add application detail fields (stored as JSON for flexibility)
ALTER TABLE applications ADD COLUMN details JSON NULL AFTER application_number;

-- 4. Performance indexes
ALTER TABLE applications ADD INDEX idx_app_status (status);
ALTER TABLE applications ADD INDEX idx_app_student_status (student_id, status);
ALTER TABLE applications ADD INDEX idx_app_provider_status (provider_id, status);
ALTER TABLE applications ADD INDEX idx_app_number (application_number);

-- 5. Document request table
CREATE TABLE IF NOT EXISTS document_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    description TEXT,
    deadline DATE,
    status ENUM('pending','submitted','expired') DEFAULT 'pending',
    uploaded_document_id INT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Email logs table
CREATE TABLE IF NOT EXISTS email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    recipient_email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT,
    type VARCHAR(50),
    status ENUM('sent','failed','pending') DEFAULT 'pending',
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. Application notes (internal provider notes)
CREATE TABLE IF NOT EXISTS application_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    user_id INT NOT NULL,
    note TEXT NOT NULL,
    is_internal TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Generate application numbers for existing applications
UPDATE applications SET application_number = CONCAT('APP-', YEAR(created_at), '-', LPAD(id, 4, '0')) WHERE application_number IS NULL;
