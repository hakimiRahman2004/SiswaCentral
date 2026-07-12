-- Add 'pending' to scholarships status ENUM for admin approval flow
ALTER TABLE scholarships MODIFY COLUMN status ENUM('draft','pending','open','closed') DEFAULT 'draft';

-- Table for scholarship supporting documents uploaded by providers for admin review
CREATE TABLE IF NOT EXISTS scholarship_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scholarship_id INT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    mime_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (scholarship_id) REFERENCES scholarships(id) ON DELETE CASCADE
) ENGINE=InnoDB;
