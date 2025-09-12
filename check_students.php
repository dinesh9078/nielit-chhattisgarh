<?php
require_once 'db.php';

echo "<h1>👥 Student Accounts Status</h1>";

try {
    // Check if students table exists
    $result = $conn->query("SHOW TABLES LIKE 'students'");
    if ($result->num_rows == 0) {
        echo "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin:10px 0;'>";
        echo "<h3>❌ Students table does not exist!</h3>";
        echo "<p>The students table needs to be created first.</p>";
        echo "</div>";
        
        // Create students table
        $createTable = "CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone VARCHAR(15) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($createTable)) {
            echo "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:5px; margin:10px 0;'>";
            echo "<h3>✅ Students table created successfully!</h3>";
            echo "</div>";
        } else {
            echo "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin:10px 0;'>";
            echo "<h3>❌ Error creating students table:</h3>";
            echo "<p>" . $conn->error . "</p>";
            echo "</div>";
            exit;
        }
    } else {
        echo "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:5px; margin:10px 0;'>";
        echo "<h3>✅ Students table exists</h3>";
        echo "</div>";
    }
    
    // Check existing students
    $result = $conn->query("SELECT id, name, email, phone, created_at FROM students ORDER BY created_at DESC");
    
    echo "<div style='background:#d1ecf1; color:#0c5460; padding:15px; border-radius:5px; margin:10px 0;'>";
    echo "<h3>📊 Existing Students (" . $result->num_rows . ")</h3>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1' style='border-collapse:collapse; width:100%; margin:10px 0;'>";
        echo "<tr style='background:#f2f2f2;'><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Created</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
            echo "<td>" . date('M d, Y', strtotime($row["created_at"])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No students found in database.</p>";
    }
    echo "</div>";
    
    // Create test students if none exist
    if ($result->num_rows == 0) {
        echo "<div style='background:#fff3cd; color:#856404; padding:15px; border-radius:5px; margin:10px 0;'>";
        echo "<h3>🔧 Creating Test Student Accounts...</h3>";
        
        $testStudents = [
            [
                'name' => 'Test Student One',
                'email' => 'student1@test.com',
                'phone' => '9876543210',
                'password' => 'password123'
            ],
            [
                'name' => 'Test Student Two', 
                'email' => 'student2@test.com',
                'phone' => '9876543211',
                'password' => 'password123'
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'phone' => '9876543212', 
                'password' => 'test123'
            ]
        ];
        
        foreach ($testStudents as $student) {
            $hashedPassword = password_hash($student['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO students (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $student['name'], $student['email'], $student['phone'], $hashedPassword);
            
            if ($stmt->execute()) {
                echo "<p>✅ Created: <strong>" . $student['name'] . "</strong> - Email: " . $student['email'] . " - Password: " . $student['password'] . "</p>";
            } else {
                echo "<p>❌ Failed to create: " . $student['name'] . " - " . $conn->error . "</p>";
            }
            $stmt->close();
        }
        echo "</div>";
        
        // Show updated list
        echo "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:5px; margin:10px 0;'>";
        echo "<h3>🎉 Test accounts created successfully!</h3>";
        echo "<p>Refresh this page to see the updated student list.</p>";
        echo "</div>";
    }
    
    echo "<div style='background:#e2e3e5; color:#383d41; padding:15px; border-radius:5px; margin:10px 0;'>";
    echo "<h3>🔑 Test Login Credentials</h3>";
    echo "<p><strong>Option 1:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> student1@test.com</li>";
    echo "<li><strong>Password:</strong> password123</li>";
    echo "</ul>";
    echo "<p><strong>Option 2:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> john@test.com</li>";
    echo "<li><strong>Password:</strong> test123</li>";
    echo "</ul>";
    echo "<p><strong>Phone Login:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Phone:</strong> 9876543210</li>";
    echo "<li><strong>Password:</strong> password123</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='margin:20px 0;'>";
    echo "<a href='student_login.html' style='padding:10px 20px; background:#007bff; color:white; text-decoration:none; border-radius:5px; margin:5px;'>Test Login</a>";
    echo "<a href='test_login_flow.php' style='padding:10px 20px; background:#28a745; color:white; text-decoration:none; border-radius:5px; margin:5px;'>Test Full Flow</a>";
    echo "<a href='check_students.php' style='padding:10px 20px; background:#6c757d; color:white; text-decoration:none; border-radius:5px; margin:5px;'>Refresh</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin:10px 0;'>";
    echo "<h3>❌ Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

$conn->close();
?>

<style>
    body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>
