<?php
require_once 'db.php';

echo "<h1>Setup Courses Management System</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;} .success{color:green;font-weight:bold;} .error{color:red;font-weight:bold;} .info{color:blue;}</style>";

try {
    // Create courses table
    $sql = "CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        duration VARCHAR(100) NOT NULL,
        hours VARCHAR(50),
        last_date DATE,
        status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
        enrollment_status VARCHAR(50) DEFAULT 'Enrollment Ongoing',
        course_code VARCHAR(20),
        fees DECIMAL(10,2),
        eligibility TEXT,
        syllabus_url VARCHAR(500),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_course_code (course_code)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>✓ Courses table created successfully!</div>";
    } else {
        throw new Exception("Error creating courses table: " . $conn->error);
    }
    
    // Clear existing courses if any
    $conn->query("DELETE FROM courses");
    echo "<div class='info'>• Cleared existing courses</div>";
    
    // Insert sample courses
    $sample_courses = [
        [
            'title' => 'Master of Computer Applications',
            'description' => 'A comprehensive 2-year program covering advanced computer applications, software development, and IT management.',
            'duration' => '2 Years',
            'hours' => '1800 Hours',
            'last_date' => '2025-09-14',
            'status' => 'active',
            'enrollment_status' => 'Enrollment Ongoing',
            'course_code' => 'MCA',
            'fees' => 75000.00,
            'eligibility' => 'Bachelor\'s degree in any discipline with Mathematics at 10+2 level'
        ],
        [
            'title' => 'Master of Technology (M.Tech)',
            'description' => 'Advanced engineering program specializing in cutting-edge technology and research methodologies.',
            'duration' => '2 Years',
            'hours' => '1600 Hours',
            'last_date' => '2025-09-14',
            'status' => 'active',
            'enrollment_status' => 'Enrollment Ongoing',
            'course_code' => 'MTech',
            'fees' => 85000.00,
            'eligibility' => 'Bachelor\'s degree in Engineering/Technology'
        ],
        [
            'title' => 'Foundation of Artificial Intelligence Technology',
            'description' => 'Learn the fundamentals of AI, machine learning, and deep learning with practical applications.',
            'duration' => '3 Months',
            'hours' => '90 Hours',
            'last_date' => '2025-09-18',
            'status' => 'active',
            'enrollment_status' => 'Enrollment Ongoing',
            'course_code' => 'FAIT',
            'fees' => 15000.00,
            'eligibility' => 'Graduate in any discipline'
        ],
        [
            'title' => 'Fundamentals of Data Curation using Python',
            'description' => 'Master data handling, cleaning, and analysis using Python programming language.',
            'duration' => '4 Months',
            'hours' => '120 Hours',
            'last_date' => '2025-09-18',
            'status' => 'active',
            'enrollment_status' => 'Enrollment Ongoing',
            'course_code' => 'DataCurationPython',
            'fees' => 12000.00,
            'eligibility' => 'Basic computer knowledge required'
        ],
        [
            'title' => 'Course on Computer Concepts (CCC)',
            'description' => 'Basic computer literacy course covering fundamental IT concepts and digital literacy.',
            'duration' => '3 Months',
            'hours' => '90 Hours',
            'last_date' => '2025-09-18',
            'status' => 'active',
            'enrollment_status' => 'Enrollment Ongoing',
            'course_code' => 'CCC',
            'fees' => 5000.00,
            'eligibility' => 'No specific qualification required'
        ],
        [
            'title' => 'Multimedia Development Associate',
            'description' => 'Comprehensive program covering graphics design, video editing, animation, and web development.',
            'duration' => '6 Months',
            'hours' => '330 Hours',
            'last_date' => '2025-09-18',
            'status' => 'active',
            'enrollment_status' => 'Enrollment Ongoing',
            'course_code' => 'MultimediaAssociate',
            'fees' => 25000.00,
            'eligibility' => '10+2 or equivalent'
        ]
    ];
    
    $stmt = $conn->prepare("INSERT INTO courses (title, description, duration, hours, last_date, status, enrollment_status, course_code, fees, eligibility) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    echo "<div class='info'><h3>Adding sample courses:</h3></div>";
    
    foreach ($sample_courses as $course) {
        $stmt->bind_param("ssssssssds", 
            $course['title'], 
            $course['description'], 
            $course['duration'], 
            $course['hours'],
            $course['last_date'],
            $course['status'],
            $course['enrollment_status'],
            $course['course_code'],
            $course['fees'],
            $course['eligibility']
        );
        
        if ($stmt->execute()) {
            echo "<div class='success'>✓ Added: " . htmlspecialchars($course['title']) . "</div>";
        } else {
            echo "<div class='error'>✗ Failed to add: " . htmlspecialchars($course['title']) . "</div>";
        }
    }
    
    $stmt->close();
    
    // Show final count
    $result = $conn->query("SELECT COUNT(*) as count FROM courses");
    $count = $result->fetch_assoc()['count'];
    
    echo "<br><div style='background:#e8f5e8;padding:1rem;border-radius:8px;border:2px solid green;'>";
    echo "<h2>🎉 Course Management System Ready!</h2>";
    echo "<p><strong>Total courses in database: $count</strong></p>";
    
    echo "<h3>📋 What's Next:</h3>";
    echo "<ul>";
    echo "<li><strong>Admin Panel:</strong> <a href='admin_dashboard.php' target='_blank'>Manage courses via admin dashboard</a></li>";
    echo "<li><strong>Dynamic Course Page:</strong> <a href='course.php' target='_blank'>View the new dynamic course page</a></li>";
    echo "<li><strong>Original Course Page:</strong> <a href='course.html' target='_blank'>Old static page (for reference)</a></li>";
    echo "</ul>";
    
    echo "<p><em>The admin panel now has full course management capabilities!</em></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
    
    echo "<br><h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Make sure MySQL service is running in XAMPP</li>";
    echo "<li>Check if database 'nielit_db' exists</li>";
    echo "<li>Run the main database setup first if needed</li>";
    echo "</ul>";
}

$conn->close();
?>
