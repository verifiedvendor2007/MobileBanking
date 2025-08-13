-- mb-secure-4321 install SQL for MobileBanking prototype
-- Run this in phpMyAdmin after creating the database.

CREATE TABLE IF NOT EXISTS users (
  id VARCHAR(36) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  balance DECIMAL(14,2) DEFAULT 0,
  country VARCHAR(100),
  language VARCHAR(10),
  profile_pic VARCHAR(255),
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transactions (
  id VARCHAR(36) PRIMARY KEY,
  sender_id VARCHAR(36),
  receiver_id VARCHAR(36),
  amount DECIMAL(14,2) NOT NULL,
  note TEXT,
  status ENUM('pending','approved','failed','reversed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id),
  FOREIGN KEY (receiver_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS admin_notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(50),
  message TEXT,
  is_read TINYINT(1) DEFAULT 0,
  meta JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
  id VARCHAR(36) PRIMARY KEY,
  user_id VARCHAR(36),
  subject VARCHAR(255),
  body TEXT,
  attachment VARCHAR(255),
  status ENUM('unread','read','closed') DEFAULT 'unread',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS settings (
  id INT PRIMARY KEY DEFAULT 1,
  site_name VARCHAR(255),
  live_chat LONGTEXT,
  smtp_host VARCHAR(255),
  smtp_port INT,
  smtp_user VARCHAR(255),
  smtp_pass VARCHAR(255),
  smtp_secure VARCHAR(10),
  admin_email VARCHAR(255)
);

-- Insert default settings row
INSERT INTO settings (id, site_name) VALUES (1, 'MobileBanking')
ON DUPLICATE KEY UPDATE site_name=VALUES(site_name);

-- Seed admin user (password: mb-secure-4321) - please change after first login
INSERT INTO users (id,name,email,password_hash,balance,is_admin) VALUES (
  'admin-0000-0000-0000-000000000001',
  'Super Admin',
  'nobleearnltd@gmail.com',
  '$2y$10$wHfXh8sFvY1nQKXf0y7mEu9hIYp3Qkq1m0FJYyqK8pQy0p7V8e3qO', -- hashed placeholder
  0.00,
  1
) ON DUPLICATE KEY UPDATE email=email;

-- Note: The password hash above is a placeholder. After importing, use PHP password_hash to set real password in DB or use admin password reset.
