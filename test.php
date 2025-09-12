<?php
/**
 * Simple connectivity test for NIELIT Chhattisgarh setup
 */
echo "<h1>NIELIT Chhattisgarh - System Test</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

echo "<h2>🔍 System Information</h2>";
echo "<div class='info'>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";
echo "</div>";

echo "<h2>🗄️ Database Connection Test</h2>";

try {
    require_once 'db.php';
    echo "<div class='success'>✓ Database connection successful!</div>";
    
    // Test if nielit_db database exists
    $result = $conn->query("SELECT DATABASE() as current_db");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<div class='info'>📊 Connected to database: " . $row['current_db'] . "</div>";
    }
    
    // Test if admin table exists and has data
    $result = $conn->query("SELECT COUNT(*) as count FROM admins");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<div class='info'>👤 Admin accounts in database: " . $row['count'] . "</div>";
    }
    
    // Show all tables
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "<div class='info'>📋 Available tables:<br>";
        while ($row = $result->fetch_array()) {
            echo "&nbsp;&nbsp;• " . $row[0] . "<br>";
        }
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Database connection failed: " . $e->getMessage() . "</div>";
    echo "<div class='info'>Make sure MySQL is running in XAMPP Control Panel</div>";
}

echo "<h2>📁 File System Test</h2>";
$files_to_check = [
    'index.html',
    'admin_login.php', 
    'admin_dashboard.php',
    'style.css',
    'script.js',
    'setup.php',
    'setup_database.sql'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "<div class='success'>✓ $file exists</div>";
    } else {
        echo "<div class='error'>✗ $file missing</div>";
    }
}

echo "<h2>🌐 Access Links</h2>";
echo "<div>";
echo "<a href='index.html' target='_blank'>🏠 Homepage</a><br>";
echo "<a href='admin_login.php' target='_blank'>🔐 Admin Login</a><br>";
echo "<a href='setup.php' target='_blank'>⚙️ Database Setup</a><br>";
echo "<a href='http://localhost/phpmyadmin/' target='_blank'>🗄️ phpMyAdmin</a><br>";
echo "</div>";

echo "<br><hr>";
echo "<p><small>If everything shows green checkmarks, your setup is ready!</small></p>";
?>
