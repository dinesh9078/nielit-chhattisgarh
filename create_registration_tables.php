<?php
require_once 'db.php';

try {
    // Create course_registrations table
    $createRegistrationsTable = "
        CREATE TABLE IF NOT EXISTS course_registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            application_id VARCHAR(20) UNIQUE NOT NULL,
            course_code VARCHAR(50),
            course_name VARCHAR(255),
            
            -- Basic Information
            full_name VARCHAR(255) NOT NULL,
            father_name VARCHAR(255) NOT NULL,
            mother_name VARCHAR(255),
            category ENUM('General', 'OBC', 'SC', 'ST') NOT NULL,
            tribe_community VARCHAR(255),
            blood_group ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
            identification_mark TEXT,
            gender ENUM('Male', 'Female', 'Other') NOT NULL,
            date_of_birth DATE NOT NULL,
            email VARCHAR(255),
            phone VARCHAR(15) NOT NULL,
            parent_phone VARCHAR(15) NOT NULL,
            aadhaar_number VARCHAR(12) NOT NULL,
            differently_abled ENUM('yes', 'no') DEFAULT 'no',
            
            -- Address Information
            perm_address_line1 TEXT,
            perm_city VARCHAR(100),
            perm_state VARCHAR(100),
            perm_district VARCHAR(100),
            perm_pincode VARCHAR(10),
            
            corr_address_line1 TEXT,
            corr_city VARCHAR(100),
            corr_state VARCHAR(100),
            corr_district VARCHAR(100),
            corr_pincode VARCHAR(10),
            
            -- Document file paths
            passport_photo VARCHAR(500),
            signature VARCHAR(500),
            thumb_print VARCHAR(500),
            category_certificate VARCHAR(500),
            aadhaar_document VARCHAR(500),
            
            -- Application Status
            application_status ENUM('pending', 'under_review', 'approved', 'rejected') DEFAULT 'pending',
            submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_application_id (application_id),
            INDEX idx_course_code (course_code),
            INDEX idx_status (application_status),
            INDEX idx_submission_date (submission_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createRegistrationsTable);
    echo "✅ course_registrations table created successfully<br>";
    
    // Create academic_qualifications table
    $createAcademicsTable = "
        CREATE TABLE IF NOT EXISTS academic_qualifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            registration_id INT NOT NULL,
            exam_passed VARCHAR(255) NOT NULL,
            board_university VARCHAR(255) NOT NULL,
            year_of_passing INT NOT NULL,
            percentage_cgpa VARCHAR(20) NOT NULL,
            marksheet_document VARCHAR(500),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (registration_id) REFERENCES course_registrations(id) ON DELETE CASCADE,
            INDEX idx_registration_id (registration_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createAcademicsTable);
    echo "✅ academic_qualifications table created successfully<br>";
    
    // Create application_logs table for tracking status changes
    $createLogsTable = "
        CREATE TABLE IF NOT EXISTS application_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            registration_id INT NOT NULL,
            action VARCHAR(100) NOT NULL,
            old_status VARCHAR(50),
            new_status VARCHAR(50),
            notes TEXT,
            created_by VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (registration_id) REFERENCES course_registrations(id) ON DELETE CASCADE,
            INDEX idx_registration_id (registration_id),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createLogsTable);
    echo "✅ application_logs table created successfully<br>";
    
    echo "<br><h3>✅ All registration tables created successfully!</h3>";
    echo "<p>The following tables have been created:</p>";
    echo "<ul>";
    echo "<li><strong>course_registrations</strong> - Main registration data</li>";
    echo "<li><strong>academic_qualifications</strong> - Academic history</li>";
    echo "<li><strong>application_logs</strong> - Status change tracking</li>";
    echo "</ul>";
    echo "<br><a href='Course_Registration.html' class='btn'>Test Registration Form</a> | ";
    echo "<a href='admin_dashboard.php' class='btn'>Admin Dashboard</a>";
    
} catch(PDOException $e) {
    echo "<h3>❌ Error creating tables!</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

<style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
    .btn:hover { background: #0056b3; }
    ul { margin: 20px 0; }
    li { margin: 5px 0; }
</style>
