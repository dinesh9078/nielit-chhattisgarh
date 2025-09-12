<?php
require_once 'db.php';

echo "<h1>Adding Test Notices</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;} .success{color:green;} .error{color:red;}</style>";

try {
    // Clear existing notices
    $conn->query("DELETE FROM notices");
    echo "<div class='success'>✓ Cleared existing notices</div>";
    
    // Add sample notices
    $notices = [
        [
            'title' => 'Admission Open for AI & Data Science (Batch 2025)',
            'description' => 'Applications are now open for the upcoming AI and Data Science batch starting in March 2025. Limited seats available.',
            'date' => '2025-01-08',
            'status' => 'live',
            'attachment_url' => ''
        ],
        [
            'title' => 'Examination Timetable – February 2025',
            'description' => 'Download the complete examination schedule for all courses.',
            'date' => '2025-01-05',
            'status' => 'live',
            'attachment_url' => '#'
        ],
        [
            'title' => 'Placement Drive with Infosys – Registrations Open',
            'description' => 'Limited seats available for qualified candidates. Register before the deadline.',
            'date' => '2025-01-01',
            'status' => 'live',
            'attachment_url' => ''
        ],
        [
            'title' => 'Cyber Security Workshop – Limited Seats',
            'description' => 'Hands-on workshop with industry experts. Advanced booking required.',
            'date' => '2024-12-28',
            'status' => 'draft',
            'attachment_url' => ''
        ],
        [
            'title' => 'Python Programming Course - New Batch',
            'description' => 'Starting next month with updated curriculum.',
            'date' => '2024-12-20',
            'status' => 'archived',
            'attachment_url' => ''
        ]
    ];
    
    $stmt = $conn->prepare("INSERT INTO notices (title, description, date, status, attachment_url, created_by) VALUES (?, ?, ?, ?, ?, 1)");
    
    foreach ($notices as $notice) {
        $stmt->bind_param("sssss", 
            $notice['title'], 
            $notice['description'], 
            $notice['date'], 
            $notice['status'], 
            $notice['attachment_url']
        );
        
        if ($stmt->execute()) {
            echo "<div class='success'>✓ Added: " . htmlspecialchars($notice['title']) . " (" . $notice['status'] . ")</div>";
        } else {
            echo "<div class='error'>✗ Failed to add: " . htmlspecialchars($notice['title']) . "</div>";
        }
    }
    
    $stmt->close();
    
    // Show counts
    $result = $conn->query("SELECT status, COUNT(*) as count FROM notices GROUP BY status");
    echo "<br><h3>Notice Counts:</h3>";
    while ($row = $result->fetch_assoc()) {
        echo "<div>• " . ucfirst($row['status']) . ": " . $row['count'] . "</div>";
    }
    
    echo "<br><div style='background:#e8f5e8;padding:1rem;border-radius:8px;border:2px solid green;'>";
    echo "<h3>🎉 Test Notices Added Successfully!</h3>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li><a href='index.php' target='_blank'>View the website</a> - You should see live notices</li>";
    echo "<li><a href='admin_dashboard.php' target='_blank'>Access admin panel</a> - Manage all notices</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
}

$conn->close();
?>
