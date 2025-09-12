<?php
require_once 'db.php';

echo "<h1>Course Registration System Test</h1>";

// Test database connections
echo "<h2>1. Database Connections</h2>";
if ($conn) {
    echo "✅ MySQLi connection: OK<br>";
} else {
    echo "❌ MySQLi connection: FAILED<br>";
}

if (isset($pdo)) {
    echo "✅ PDO connection: OK<br>";
} else {
    echo "❌ PDO connection: FAILED<br>";
}

// Test database and tables
echo "<h2>2. Database Tables</h2>";
try {
    $tables = ['course_registrations', 'academic_qualifications', 'application_logs'];
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() > 0) {
            echo "✅ Table '$table': EXISTS<br>";
        } else {
            echo "❌ Table '$table': MISSING<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test upload directories
echo "<h2>3. Upload Directories</h2>";
$dirs = [
    'uploads/registrations/photos',
    'uploads/registrations/signatures',
    'uploads/registrations/thumbprints',
    'uploads/registrations/certificates',
    'uploads/registrations/documents',
    'uploads/registrations/marksheets'
];

foreach ($dirs as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        echo "✅ Directory '$dir': EXISTS & WRITABLE<br>";
    } else {
        echo "❌ Directory '$dir': MISSING or NOT WRITABLE<br>";
    }
}

// Test form access
echo "<h2>4. Form Access</h2>";
echo "<a href='Course_Registration.html?course=MCA' target='_blank' style='padding:10px 20px; background:#007bff; color:white; text-decoration:none; border-radius:5px;'>Test Registration Form</a><br><br>";

// Show any existing registrations
echo "<h2>5. Existing Registrations</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM course_registrations");
    $count = $stmt->fetchColumn();
    echo "📊 Total registrations: $count<br>";
    
    if ($count > 0) {
        $stmt = $pdo->query("SELECT application_id, full_name, course_name, submission_date FROM course_registrations ORDER BY submission_date DESC LIMIT 5");
        echo "<table border='1' style='border-collapse:collapse; margin-top:10px;'>";
        echo "<tr><th>App ID</th><th>Name</th><th>Course</th><th>Date</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['application_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['submission_date']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "❌ Error checking registrations: " . $e->getMessage() . "<br>";
}

echo "<h2>6. System Status</h2>";
if (is_file('course_registration_process.php')) {
    echo "✅ Registration processor: EXISTS<br>";
} else {
    echo "❌ Registration processor: MISSING<br>";
}

echo "<br><strong>System is ready for testing!</strong>";
?>
