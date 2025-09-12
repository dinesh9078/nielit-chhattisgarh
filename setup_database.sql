-- NIELIT Chhattisgarh Database Setup Script
-- This script creates the necessary database and tables

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS nielit_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE nielit_db;

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create students table (for future use)
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    course VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create notices table (for dynamic notice management)
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    status ENUM('live', 'draft', 'archived') DEFAULT 'draft',
    attachment_url VARCHAR(500),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
);

-- Create applications table (for course applications)
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_name VARCHAR(255) NOT NULL,
    application_date DATE DEFAULT (CURRENT_DATE),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    documents_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Insert default admin account (password: 'admin123')
INSERT INTO admins (email, password) VALUES 
('admin@nielit.gov.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi') 
ON DUPLICATE KEY UPDATE password = VALUES(password);

-- Insert sample notices
INSERT INTO notices (title, description, date, status, attachment_url) VALUES
('Admission Open for AI & Data Science (Batch 2025)', 'Apply now for the upcoming batch starting in March 2025.', '2025-01-08', 'live', ''),
('Examination Timetable – February 2025', 'Download the complete examination schedule for all courses.', '2025-01-05', 'live', ''),
('Placement Drive with Infosys – Registrations Open', 'Limited seats available for qualified candidates. Register before deadline.', '2025-01-01', 'draft', ''),
('Cyber Security Workshop – Limited Seats', 'Hands-on workshop with industry experts. Advanced booking required.', '2024-12-28', 'archived', '')
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Create indexes for better performance
CREATE INDEX idx_admins_email ON admins(email);
CREATE INDEX idx_students_email ON students(email);
CREATE INDEX idx_notices_status ON notices(status);
CREATE INDEX idx_notices_date ON notices(date);
CREATE INDEX idx_applications_status ON applications(status);

SHOW TABLES;
