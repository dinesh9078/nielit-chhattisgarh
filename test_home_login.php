<?php
session_start();

$isLoggedIn = isset($_SESSION['student_id']) && !empty($_SESSION['student_id']);
$studentName = $_SESSION['student_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Home Page Login Experience</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        iframe { width: 100%; height: 600px; border: 1px solid #ddd; border-radius: 8px; margin: 10px 0; }
        .comparison { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .comparison-item { padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
        h3 { margin-top: 0; }
    </style>
</head>
<body>
    <h1>🏠 Home Page Login Experience Test</h1>
    
    <?php if ($isLoggedIn): ?>
        <div class="status success">
            <h3>✅ You are currently logged in as: <?php echo htmlspecialchars($studentName); ?></h3>
            <p>The home page should show your name and personalized content.</p>
            <a href="student_logout.php" class="btn btn-danger">Logout to test non-logged-in experience</a>
        </div>
    <?php else: ?>
        <div class="status warning">
            <h3>⚠️ You are not logged in</h3>
            <p>The home page should show login options and general content.</p>
            <a href="student_login.html" class="btn btn-success">Login to test logged-in experience</a>
        </div>
    <?php endif; ?>

    <div class="status info">
        <h3>🎯 What Changes When Logged In</h3>
        
        <div class="comparison">
            <div class="comparison-item">
                <h4>🔓 When NOT logged in:</h4>
                <ul>
                    <li>Navigation shows "Login" dropdown</li>
                    <li>Shows "Register" option</li>
                    <li>Generic hero section</li>
                    <li>Standard "Explore Programs" button</li>
                </ul>
            </div>
            
            <div class="comparison-item">
                <h4>🔐 When LOGGED IN:</h4>
                <ul>
                    <li>Navigation shows <strong>student name</strong></li>
                    <li>User dropdown with Dashboard, Apply for Course, Logout</li>
                    <li>Shows "Apply Courses" instead of "Register"</li>
                    <li><strong>Welcome message</strong> in hero section</li>
                    <li>"Apply for Courses" button prominently displayed</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="status info">
        <h3>🧪 Test Instructions</h3>
        <ol>
            <li><strong>Test not logged in:</strong>
                <ul>
                    <li>Make sure you're logged out (click Logout button above if needed)</li>
                    <li>Visit the home page and notice the navigation and content</li>
                </ul>
            </li>
            <li><strong>Test logged in:</strong>
                <ul>
                    <li>Login using test credentials (student1@test.com / password123)</li>
                    <li>Visit the home page again and notice the changes</li>
                </ul>
            </li>
            <li><strong>Test navigation:</strong>
                <ul>
                    <li>When logged in, click on your name in navigation</li>
                    <li>Try the Dashboard and Apply for Course options</li>
                    <li>Use Logout option to end session</li>
                </ul>
            </li>
        </ol>
    </div>

    <div class="status">
        <h3>🔗 Quick Links</h3>
        <a href="index.php" class="btn" target="_blank">🏠 Home Page</a>
        <a href="student_login.html" class="btn">👤 Student Login</a>
        <a href="student_logout.php" class="btn btn-danger">🚪 Logout</a>
        <a href="check_students.php" class="btn">👥 Student Accounts</a>
        <a href="test_home_login.php" class="btn">🔄 Refresh This Page</a>
    </div>

    <div class="status info">
        <h3>🖼️ Home Page Preview</h3>
        <iframe src="index.php" title="Home Page Preview"></iframe>
    </div>

</body>
</html>
