<?php
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration System Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #0056b3; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>🧪 NIELIT Course Registration System Test</h1>
    
    <div class="info status">
        <strong>System Status Check</strong><br>
        Testing all components of the dynamic course registration system...
    </div>

    <?php
    // Test Database Connection
    echo "<h2>1. Database Connection</h2>";
    try {
        $pdo->query("SELECT 1");
        echo '<div class="success status">✅ Database connection successful</div>';
    } catch (Exception $e) {
        echo '<div class="error status">❌ Database connection failed: ' . $e->getMessage() . '</div>';
    }

    // Test Tables Existence
    echo "<h2>2. Database Tables</h2>";
    $requiredTables = ['courses', 'course_registrations', 'academic_qualifications', 'application_logs'];
    foreach ($requiredTables as $table) {
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "<div class='success status'>✅ Table '$table' exists</div>";
            } else {
                echo "<div class='error status'>❌ Table '$table' missing</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error status'>❌ Error checking table '$table': " . $e->getMessage() . "</div>";
        }
    }

    // Test Courses Data
    echo "<h2>3. Courses Data</h2>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM courses");
        $count = $stmt->fetch()['count'];
        if ($count > 0) {
            echo "<div class='success status'>✅ Found $count courses in database</div>";
            
            // Display courses
            $stmt = $pdo->query("SELECT course_code, title, status, last_date FROM courses ORDER BY created_at DESC LIMIT 5");
            $courses = $stmt->fetchAll();
            
            echo "<table>";
            echo "<tr><th>Course Code</th><th>Title</th><th>Status</th><th>Last Date</th></tr>";
            foreach ($courses as $course) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($course['course_code']) . "</td>";
                echo "<td>" . htmlspecialchars($course['title']) . "</td>";
                echo "<td>" . htmlspecialchars($course['status']) . "</td>";
                echo "<td>" . htmlspecialchars($course['last_date']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo '<div class="error status">❌ No courses found in database</div>';
        }
    } catch (Exception $e) {
        echo '<div class="error status">❌ Error checking courses: ' . $e->getMessage() . '</div>';
    }

    // Test Upload Directories
    echo "<h2>4. Upload Directories</h2>";
    $uploadDirs = [
        'uploads/registrations/photos',
        'uploads/registrations/signatures', 
        'uploads/registrations/thumbprints',
        'uploads/registrations/certificates',
        'uploads/registrations/documents',
        'uploads/registrations/marksheets'
    ];
    
    foreach ($uploadDirs as $dir) {
        if (is_dir($dir) && is_writable($dir)) {
            echo "<div class='success status'>✅ Directory '$dir' exists and is writable</div>";
        } else {
            echo "<div class='error status'>❌ Directory '$dir' missing or not writable</div>";
        }
    }

    // Test Registrations (if any)
    echo "<h2>5. Registration Records</h2>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM course_registrations");
        $count = $stmt->fetch()['count'];
        echo "<div class='info status'>📊 Found $count registration records</div>";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT application_id, full_name, course_name, application_status, submission_date FROM course_registrations ORDER BY submission_date DESC LIMIT 5");
            $registrations = $stmt->fetchAll();
            
            echo "<table>";
            echo "<tr><th>Application ID</th><th>Name</th><th>Course</th><th>Status</th><th>Submitted</th></tr>";
            foreach ($registrations as $reg) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($reg['application_id']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['course_name']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['application_status']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['submission_date']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo '<div class="error status">❌ Error checking registrations: ' . $e->getMessage() . '</div>';
    }
    ?>

    <h2>6. Test Links</h2>
    <div style="margin: 20px 0;">
        <a href="course.php" class="btn">🎓 View Courses Page</a>
        <a href="Course_Registration.html?course=MCA" class="btn">📝 Test Registration Form (MCA)</a>
        <a href="admin_dashboard.php" class="btn">👨‍💼 Admin Dashboard</a>
        <a href="api/get_courses.php" class="btn">🔗 Test API Endpoint</a>
    </div>

    <h2>7. System Features</h2>
    <div class="info status">
        <strong>✅ Features Implemented:</strong><br>
        • Dynamic course loading from database<br>
        • Multi-step registration form with file uploads<br>
        • Secure file storage with validation<br>
        • Database transactions for data integrity<br>
        • Application ID generation<br>
        • Admin management API<br>
        • Status tracking and logging<br>
        • Academic qualifications support<br>
        • Address management (permanent & correspondence)<br>
        • AJAX form submission with error handling
    </div>

    <h2>8. Next Steps</h2>
    <div class="info status">
        <strong>To fully test the system:</strong><br>
        1. Click "Test Registration Form" and fill out a sample application<br>
        2. Check if files upload properly<br>
        3. Verify data is saved in database<br>
        4. Use admin dashboard to manage registrations<br>
        5. Test different course selections from the courses page
    </div>

</body>
</html>
