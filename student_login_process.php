<?php
// student_login_process.php — processes the student login POST
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: student_login.php');
  exit;
}

$emailOrPhone = trim($_POST['emailOrPhone'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($emailOrPhone === '' || $password === '') {
  header('Location: student_login.php?error=' . urlencode('Please enter both email/phone and password.'));
  exit;
}

try {
    // Check if input is email or phone
    $isEmail = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL);
    
    if ($isEmail) {
        // Search by email
        $stmt = $conn->prepare('SELECT id, name, email, phone, password FROM students WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $emailOrPhone);
    } else {
        // Search by phone
        $stmt = $conn->prepare('SELECT id, name, email, phone, password FROM students WHERE phone = ? LIMIT 1');
        $stmt->bind_param('s', $emailOrPhone);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    
    if ($student && password_verify($password, $student['password'])) {
        // Login successful
        $_SESSION['student_id'] = (int)$student['id'];
        $_SESSION['student_name'] = $student['name'];
        $_SESSION['student_email'] = $student['email'];
        session_regenerate_id(true);
        
        // Check if there's a redirect URL (for course registration)
        if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
            $redirectUrl = $_POST['redirect'];
            // Validate the redirect URL for security
            if (strpos($redirectUrl, 'Course_Registration.html') !== false || 
                strpos($redirectUrl, 'student_dashboard.php') !== false) {
                header('Location: ' . $redirectUrl);
                exit;
            }
        }
        
        header('Location: student_dashboard.php');
        exit;
    } else {
        header('Location: student_login.php?error=' . urlencode('Invalid credentials. Please try again.'));
        exit;
    }
    
} catch (Exception $e) {
    error_log("Student login error: " . $e->getMessage());
    header('Location: student_login.php?error=' . urlencode('Login system temporarily unavailable. Please try again later.'));
    exit;
}
?>
