-- Add 'in_progress' to support_tickets status ENUM
ALTER TABLE support_tickets MODIFY COLUMN status ENUM('open','in_progress','resolved','closed') DEFAULT 'open';
