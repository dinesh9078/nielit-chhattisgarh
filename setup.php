<?php
/**
 * NIELIT Chhattisgarh Database Setup Script
 * Run this script once to set up the database and initial data
 */

// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "nielit_db";

echo "<h1>NIELIT Chhattisgarh - Database Setup</h1>\n";
echo "<pre>\n";

try {
    // Connect to MySQL server (without selecting database first)
    $conn = new mysqli($host, $user, $pass);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "✓ Connected to MySQL server successfully!\n";
    
    // Read and execute the SQL setup file
    $sql_file = __DIR__ . '/setup_database.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("SQL setup file not found: $sql_file");
    }
    
    $sql_content = file_get_contents($sql_file);
    if ($sql_content === false) {
        throw new Exception("Could not read SQL setup file");
    }
    
    // Split SQL statements by semicolon and execute them one by one
    $statements = array_filter(array_map('trim', explode(';', $sql_content)));
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue; // Skip empty statements and comments
        }
        
        if ($conn->query($statement) === TRUE) {
            // Get first few words of the statement for logging
            $first_words = substr($statement, 0, 50);
            echo "✓ Executed: " . $first_words . (strlen($statement) > 50 ? "..." : "") . "\n";
        } else {
            echo "✗ Error executing statement: " . $conn->error . "\n";
            echo "  Statement: " . substr($statement, 0, 100) . "...\n";
        }
    }
    
    // Test the connection with the new database
    $conn->select_db($db_name);
    
    // Verify tables were created
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "\n✓ Database setup completed successfully!\n";
        echo "\n📋 Tables created:\n";
        while ($row = $result->fetch_array()) {
            echo "  • " . $row[0] . "\n";
        }
        
        // Show admin account info
        echo "\n🔐 Default Admin Account:\n";
        echo "  Email: admin@nielit.gov.in\n";
        echo "  Password: admin123\n";
        
        echo "\n🌐 Access URLs:\n";
        echo "  Frontend: http://localhost/nielit-chhattisgarh/\n";
        echo "  Admin Login: http://localhost/nielit-chhattisgarh/admin_login.php\n";
        echo "  phpMyAdmin: http://localhost/phpmyadmin/\n";
        
    } else {
        echo "✗ Could not verify tables: " . $conn->error . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Setup failed: " . $e->getMessage() . "\n";
    echo "\nPlease make sure:\n";
    echo "1. XAMPP is running (Apache and MySQL services)\n";
    echo "2. MySQL is accessible on localhost:3306\n";
    echo "3. The MySQL root user has no password (default XAMPP setup)\n";
} finally {
    if (isset($conn) && $conn) {
        $conn->close();
    }
}

echo "\n</pre>\n";
echo "<p><a href='index.html'>← Go to Homepage</a> | <a href='admin_login.php'>Go to Admin Login →</a></p>";
?>
