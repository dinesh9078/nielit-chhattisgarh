<?php
// student_registration_process.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: student_registration.php');
  exit;
}

// Get and sanitize input
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$course = trim($_POST['course'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Full name is required.';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required.';
} elseif (!preg_match('/^\d{10}$/', $phone)) {
    $errors[] = 'Please enter a valid 10-digit phone number.';
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if (empty($password)) {
    $errors[] = 'Password is required.';
} elseif (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters long.';
}

if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match.';
}

// Check if email or phone already exists
if (empty($errors)) {
    try {
        // Check email if provided
        if (!empty($email)) {
            $stmt = $conn->prepare('SELECT id FROM students WHERE email = ? LIMIT 1');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $errors[] = 'An account with this email already exists.';
            }
            $stmt->close();
        }
        
        // Check phone
        $stmt = $conn->prepare('SELECT id FROM students WHERE phone = ? LIMIT 1');
        $stmt->bind_param('s', $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = 'An account with this phone number already exists.';
        }
        $stmt->close();
        
    } catch (Exception $e) {
        $errors[] = 'Database error. Please try again.';
        error_log("Student registration DB check error: " . $e->getMessage());
    }
}

// If there are errors, redirect back with error message
if (!empty($errors)) {
    $error_message = implode(' ', $errors);
    header('Location: student_registration.php?error=' . urlencode($error_message));
    exit;
}

// Insert new student
try {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare('INSERT INTO students (name, email, phone, course, password) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $name, $email, $phone, $course, $hashed_password);
    
    if ($stmt->execute()) {
        $student_id = $conn->insert_id;
        
        // Auto-login the new user
        $_SESSION['student_id'] = $student_id;
        $_SESSION['student_name'] = $name;
        $_SESSION['student_email'] = $email;
        
        $stmt->close();
        header('Location: student_dashboard.php?success=' . urlencode('Account created successfully! Welcome to NIELIT Chhattisgarh.'));
        exit;
        
    } else {
        throw new Exception('Failed to create account');
    }
    
} catch (Exception $e) {
    error_log("Student registration error: " . $e->getMessage());
    header('Location: student_registration.php?error=' . urlencode('Registration failed. Please try again.'));
    exit;
}
?>
