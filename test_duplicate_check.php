<?php
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Duplicate Enrollment Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .info { background: #d1ecf1; color: #0c5460; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
    </style>
</head>
<body>
    <h1>🔍 Duplicate Enrollment Check Test</h1>
    
    <div class="test-section info">
        <h3>How the duplicate check works:</h3>
        <ul>
            <li>When a student submits a registration form</li>
            <li>System checks if <strong>same Aadhaar number</strong> + <strong>same course code</strong> already exists</li>
            <li>If found, shows error: "You are already enrolled in this course"</li>
            <li>If not found, proceeds with registration</li>
        </ul>
    </div>

    <?php
    try {
        // Show existing registrations
        $stmt = $pdo->query("SELECT application_id, full_name, aadhaar_number, course_code, course_name, submission_date FROM course_registrations ORDER BY submission_date DESC");
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div class='test-section'>";
        echo "<h3>📊 Existing Registrations (" . count($registrations) . ")</h3>";
        
        if (count($registrations) > 0) {
            echo "<table>";
            echo "<tr><th>App ID</th><th>Name</th><th>Aadhaar (Last 4)</th><th>Course Code</th><th>Course Name</th><th>Date</th></tr>";
            foreach ($registrations as $reg) {
                $maskedAadhaar = "****-****-" . substr($reg['aadhaar_number'], -4);
                echo "<tr>";
                echo "<td>" . htmlspecialchars($reg['application_id']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($maskedAadhaar) . "</td>";
                echo "<td>" . htmlspecialchars($reg['course_code']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['course_name']) . "</td>";
                echo "<td>" . date('M d, Y', strtotime($reg['submission_date'])) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No registrations yet.</p>";
        }
        echo "</div>";

        // Check for duplicates
        echo "<div class='test-section'>";
        echo "<h3>🔄 Duplicate Check Analysis</h3>";
        
        $dupQuery = "
            SELECT aadhaar_number, course_code, COUNT(*) as count, 
                   GROUP_CONCAT(full_name SEPARATOR ', ') as names,
                   GROUP_CONCAT(application_id SEPARATOR ', ') as app_ids
            FROM course_registrations 
            GROUP BY aadhaar_number, course_code 
            HAVING COUNT(*) > 1
        ";
        $dupStmt = $pdo->query($dupQuery);
        $duplicates = $dupStmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($duplicates) > 0) {
            echo "<div class='error'>";
            echo "<h4>⚠️ Found Duplicate Enrollments:</h4>";
            echo "<table>";
            echo "<tr><th>Aadhaar (Last 4)</th><th>Course Code</th><th>Count</th><th>Names</th><th>App IDs</th></tr>";
            foreach ($duplicates as $dup) {
                $maskedAadhaar = "****-****-" . substr($dup['aadhaar_number'], -4);
                echo "<tr>";
                echo "<td>" . htmlspecialchars($maskedAadhaar) . "</td>";
                echo "<td>" . htmlspecialchars($dup['course_code']) . "</td>";
                echo "<td>" . htmlspecialchars($dup['count']) . "</td>";
                echo "<td>" . htmlspecialchars($dup['names']) . "</td>";
                echo "<td>" . htmlspecialchars($dup['app_ids']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "<h4>✅ No Duplicate Enrollments Found</h4>";
            echo "<p>All registrations have unique Aadhaar + Course combinations.</p>";
            echo "</div>";
        }
        echo "</div>";

    } catch (Exception $e) {
        echo "<div class='test-section error'>";
        echo "<h3>❌ Error</h3>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
    }
    ?>

    <div class="test-section info">
        <h3>🧪 Test Instructions</h3>
        <ol>
            <li><a href="Course_Registration.html?course=MCA" class="btn" target="_blank">Register for MCA Course</a></li>
            <li>Fill the form with sample data (remember the Aadhaar number you use)</li>
            <li>Submit the form successfully</li>
            <li><a href="Course_Registration.html?course=MCA" class="btn" target="_blank">Try to register again for MCA</a></li>
            <li>Use the <strong>same Aadhaar number</strong> - you should get "already enrolled" error</li>
            <li><a href="Course_Registration.html?course=MTech" class="btn" target="_blank">Try different course (MTech)</a></li>
            <li>Use the same Aadhaar - this should work (different course)</li>
        </ol>
        <p><a href="test_duplicate_check.php" class="btn">🔄 Refresh This Page</a></p>
    </div>

</body>
</html>
