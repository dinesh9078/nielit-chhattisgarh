<?php
require_once 'db.php';

echo "<h1>Create Notices Table</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;} .success{color:green;font-weight:bold;} .error{color:red;font-weight:bold;}</style>";

try {
    // Create notices table
    $sql = "CREATE TABLE IF NOT EXISTS notices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        date DATE NOT NULL,
        status ENUM('live', 'draft', 'archived') DEFAULT 'draft',
        attachment_url VARCHAR(500),
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_date (date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>✓ Notices table created successfully!</div>";
        
        // Check if table exists and show structure
        $result = $conn->query("DESCRIBE notices");
        if ($result) {
            echo "<br><h3>Table Structure:</h3>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . $row['Default'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Add some sample data
        echo "<br><h3>Adding Sample Notices:</h3>";
        
        $notices = [
            [
                'title' => 'Admission Open for AI & Data Science (Batch 2025)',
                'description' => 'Applications are now open for the upcoming AI and Data Science batch.',
                'date' => '2025-01-08',
                'status' => 'live'
            ],
            [
                'title' => 'Examination Timetable – February 2025',
                'description' => 'Download the complete examination schedule for all courses.',
                'date' => '2025-01-05',
                'status' => 'live'
            ],
            [
                'title' => 'Placement Drive with Infosys – Registrations Open',
                'description' => 'Limited seats available for qualified candidates.',
                'date' => '2025-01-01',
                'status' => 'live'
            ]
        ];
        
        $stmt = $conn->prepare("INSERT INTO notices (title, description, date, status) VALUES (?, ?, ?, ?)");
        
        foreach ($notices as $notice) {
            $stmt->bind_param("ssss", 
                $notice['title'], 
                $notice['description'], 
                $notice['date'], 
                $notice['status']
            );
            
            if ($stmt->execute()) {
                echo "<div class='success'>✓ Added: " . htmlspecialchars($notice['title']) . "</div>";
            }
        }
        
        $stmt->close();
        
        // Show final count
        $result = $conn->query("SELECT COUNT(*) as count FROM notices");
        $count = $result->fetch_assoc()['count'];
        
        echo "<br><div style='background:#e8f5e8;padding:1rem;border-radius:8px;border:2px solid green;'>";
        echo "<h3>🎉 Setup Complete!</h3>";
        echo "<p><strong>Notices table created with $count sample notices</strong></p>";
        echo "<p>Now you can:</p>";
        echo "<ul>";
        echo "<li><a href='index.php' target='_blank'>View Website</a> - See live notices</li>";
        echo "<li><a href='admin_dashboard.php' target='_blank'>Admin Panel</a> - Manage notices</li>";
        echo "</ul>";
        echo "</div>";
        
    } else {
        throw new Exception("Error creating table: " . $conn->error);
    }
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
    
    echo "<br><h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Make sure MySQL service is running in XAMPP</li>";
    echo "<li>Check if database 'nielit_db' exists</li>";
    echo "<li>Try running: <a href='create_db_only.php'>create_db_only.php</a> first</li>";
    echo "</ul>";
}

$conn->close();
?>
