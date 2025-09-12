<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login Flow | NIELIT Chhattisgarh</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        ol { margin-left: 20px; }
        li { margin: 8px 0; }
    </style>
</head>
<body>
    <h1>🔐 Login Flow Test</h1>
    
    <?php if (isset($_SESSION['student_id'])): ?>
        <div class="status success">
            <h3>✅ You are currently logged in</h3>
            <p><strong>Student ID:</strong> <?php echo htmlspecialchars($_SESSION['student_id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['student_name'] ?? 'Not set'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['student_email'] ?? 'Not set'); ?></p>
            <a href="student_logout.php" class="btn btn-danger">Logout</a>
        </div>
    <?php else: ?>
        <div class="status warning">
            <h3>⚠️ You are not logged in</h3>
            <p>You need to log in to test the course registration flow.</p>
            <a href="student_login.html" class="btn">Login Now</a>
        </div>
    <?php endif; ?>

    <div class="status info">
        <h3>🧪 Test Instructions</h3>
        <p><strong>Scenario 1: User not logged in</strong></p>
        <ol>
            <li>If you're logged in, <a href="student_logout.php" class="btn btn-danger">Logout First</a></li>
            <li><a href="course.html" class="btn" target="_blank">Visit Course Page</a></li>
            <li>Click on any "Apply Course" button (e.g., MCA)</li>
            <li>You should be redirected to the login page</li>
            <li>Login with your student credentials</li>
            <li>After successful login, you should be redirected to Course Registration</li>
        </ol>

        <p><strong>Scenario 2: User already logged in</strong></p>
        <ol>
            <li>Make sure you're logged in (see status above)</li>
            <li><a href="course.html" class="btn" target="_blank">Visit Course Page</a></li>
            <li>Click on any "Apply Course" button</li>
            <li>You should go directly to Course Registration (no login required)</li>
        </ol>
    </div>

    <div class="status info">
        <h3>🔗 Quick Test Links</h3>
        <p><strong>Course Apply Links (will check login):</strong></p>
        <a href="check_login.php?action=apply&course=MCA" class="btn" target="_blank">Apply for MCA</a>
        <a href="check_login.php?action=apply&course=MTech" class="btn" target="_blank">Apply for MTech</a>
        <a href="check_login.php?action=apply&course=CCC" class="btn" target="_blank">Apply for CCC</a>

        <p><strong>Direct Links:</strong></p>
        <a href="student_login.html" class="btn">Student Login</a>
        <a href="Course_Registration.php?course=MCA" class="btn" target="_blank">Course Registration (Protected)</a>
        <a href="course.html" class="btn" target="_blank">Course Page</a>
    </div>

    <div class="status info">
        <h3>📋 How It Works</h3>
        <ul>
            <li><strong>Course page apply buttons</strong> now point to <code>check_login.php</code></li>
            <li><strong>check_login.php</strong> checks if user is logged in</li>
            <li><strong>If logged in:</strong> Redirects to <code>Course_Registration.php</code></li>
            <li><strong>If not logged in:</strong> Redirects to <code>student_login.php</code> with return URL</li>
            <li><strong>After login:</strong> Student is redirected back to course registration</li>
            <li><strong>Course_Registration.php</strong> has session protection and shows student info</li>
        </ul>
    </div>

    <div class="status">
        <h3>🔄 Refresh Status</h3>
        <a href="test_login_flow.php" class="btn">Refresh This Page</a>
    </div>

</body>
</html>
