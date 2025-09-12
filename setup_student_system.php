<?php
require_once 'db.php';

echo "<h1>Setup Student System</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;} .success{color:green;font-weight:bold;} .error{color:red;font-weight:bold;} .info{color:blue;}</style>";

try {
    // Ensure students table exists with password column
    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255),
        phone VARCHAR(20) NOT NULL UNIQUE,
        course VARCHAR(255),
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_phone (phone)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>✓ Students table ready!</div>";
    } else {
        throw new Exception("Error creating students table: " . $conn->error);
    }
    
    // Clear existing test students
    $conn->query("DELETE FROM students WHERE phone IN ('9876543210', '9876543211', '9876543212')");
    echo "<div class='info'>• Cleared existing test accounts</div>";
    
    // Add test students
    $test_students = [
        [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '9876543210',
            'course' => 'Python Programming',
            'password' => 'student123'
        ],
        [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com', 
            'phone' => '9876543211',
            'course' => 'AI/ML using Python',
            'password' => 'student123'
        ],
        [
            'name' => 'Raj Patel',
            'email' => '',
            'phone' => '9876543212',
            'course' => 'Cyber Security',
            'password' => 'student123'
        ]
    ];
    
    $stmt = $conn->prepare("INSERT INTO students (name, email, phone, course, password) VALUES (?, ?, ?, ?, ?)");
    
    echo "<div class='info'><h3>Creating test student accounts:</h3></div>";
    
    foreach ($test_students as $student) {
        $hashed_password = password_hash($student['password'], PASSWORD_BCRYPT);
        
        $stmt->bind_param("sssss", 
            $student['name'], 
            $student['email'], 
            $student['phone'], 
            $student['course'], 
            $hashed_password
        );
        
        if ($stmt->execute()) {
            echo "<div class='success'>✓ Created account for: " . htmlspecialchars($student['name']) . "</div>";
            echo "<div class='info'>&nbsp;&nbsp;📧 Email: " . ($student['email'] ?: 'Not provided') . "</div>";
            echo "<div class='info'>&nbsp;&nbsp;📱 Phone: " . $student['phone'] . "</div>";
            echo "<div class='info'>&nbsp;&nbsp;🔐 Password: " . $student['password'] . "</div>";
            echo "<div class='info'>&nbsp;&nbsp;📚 Course: " . $student['course'] . "</div><br>";
        } else {
            echo "<div class='error'>✗ Failed to create: " . htmlspecialchars($student['name']) . "</div>";
        }
    }
    
    $stmt->close();
    
    // Show final count
    $result = $conn->query("SELECT COUNT(*) as count FROM students");
    $count = $result->fetch_assoc()['count'];
    
    echo "<div style='background:#e8f5e8;padding:1rem;border-radius:8px;border:2px solid green;margin-top:2rem;'>";
    echo "<h2>🎉 Student System Setup Complete!</h2>";
    echo "<p><strong>Total students in database: $count</strong></p>";
    
    echo "<h3>🧪 Test the Student System:</h3>";
    echo "<ol>";
    echo "<li><strong>Student Login:</strong> <a href='student_login.php' target='_blank'>http://localhost/nielit-chhattisgarh/student_login.php</a></li>";
    echo "<li><strong>Student Registration:</strong> <a href='student_registration.php' target='_blank'>http://localhost/nielit-chhattisgarh/student_registration.php</a></li>";
    echo "<li><strong>Main Website:</strong> <a href='index.php' target='_blank'>http://localhost/nielit-chhattisgarh/</a></li>";
    echo "</ol>";
    
    echo "<h3>🔐 Test Credentials:</h3>";
    echo "<ul>";
    echo "<li><strong>Student 1:</strong> Phone: <code>9876543210</code> | Password: <code>student123</code></li>";
    echo "<li><strong>Student 2:</strong> Phone: <code>9876543211</code> | Password: <code>student123</code></li>";
    echo "<li><strong>Student 3:</strong> Phone: <code>9876543212</code> | Password: <code>student123</code></li>";
    echo "</ul>";
    
    echo "<p><strong>Note:</strong> You can also login using email addresses for students 1 & 2.</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
    
    echo "<br><h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Make sure MySQL service is running in XAMPP</li>";
    echo "<li>Check if database 'nielit_db' exists</li>";
    echo "<li>Run the notices table setup first if needed</li>";
    echo "</ul>";
}

$conn->close();
?>
