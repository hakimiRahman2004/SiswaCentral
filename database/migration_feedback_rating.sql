-- Add rating column to feedback table
ALTER TABLE feedback ADD COLUMN rating TINYINT NOT NULL DEFAULT 0 AFTER subject;
