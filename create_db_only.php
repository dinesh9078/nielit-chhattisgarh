<?php
// Simple database creation script
echo "<h1>Create Database - NIELIT Chhattisgarh</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;background:#f5f5f5;} .success{color:green;font-weight:bold;} .error{color:red;font-weight:bold;} .info{color:blue;}</style>";

$host = "localhost";
$user = "root";
$pass = "";

try {
    // Connect to MySQL server without selecting a database
    $conn = new mysqli($host, $user, $pass);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "<div class='success'>✓ Connected to MySQL server</div>";
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS nielit_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>✓ Database 'nielit_db' created successfully!</div>";
    } else {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    // Select the database
    $conn->select_db("nielit_db");
    
    // Create admin table
    $sql = "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>✓ Admin table created successfully!</div>";
    } else {
        throw new Exception("Error creating admin table: " . $conn->error);
    }
    
    // Insert admin account
    $email = "admin@nielit.gov.in";
    $password = password_hash("admin123", PASSWORD_BCRYPT);
    
    $sql = "INSERT IGNORE INTO admins (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    
    if ($stmt->execute()) {
        echo "<div class='success'>✓ Admin account created successfully!</div>";
        echo "<div class='info'>📧 Email: admin@nielit.gov.in</div>";
        echo "<div class='info'>🔐 Password: admin123</div>";
    } else {
        echo "<div class='info'>ℹ️ Admin account already exists</div>";
    }
    
    $stmt->close();
    $conn->close();
    
    echo "<br><div style='background:white;padding:1rem;border-radius:8px;border:2px solid green;'>";
    echo "<h2>🎉 Setup Complete!</h2>";
    echo "<p>You can now log in to the admin panel:</p>";
    echo "<p><strong>URL:</strong> <a href='admin_login.php'>http://localhost/nielit-chhattisgarh/admin_login.php</a></p>";
    echo "<p><strong>Email:</strong> admin@nielit.gov.in</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
    echo "<div class='info'>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Make sure XAMPP Control Panel is open</li>";
    echo "<li>Start Apache and MySQL services in XAMPP</li>";
    echo "<li>Check if MySQL is running on port 3306</li>";
    echo "</ul>";
    echo "</div>";
}
?>
