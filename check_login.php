<?php
session_start();

// Function to check if student is logged in
function isStudentLoggedIn() {
    return isset($_SESSION['student_id']) && !empty($_SESSION['student_id']);
}

// Function to get course registration URL with login redirect
function getCourseRegistrationUrl($courseCode) {
    if (isStudentLoggedIn()) {
        // User is logged in, go directly to course registration
        return "Course_Registration.php?course=" . urlencode($courseCode);
    } else {
        // User not logged in, redirect to login with return URL
        $returnUrl = "Course_Registration.php?course=" . urlencode($courseCode);
        return "student_login.php?redirect=" . urlencode($returnUrl);
    }
}

// Handle AJAX requests for login status
if (isset($_GET['action']) && $_GET['action'] === 'check_login_status') {
    header('Content-Type: application/json');
    echo json_encode([
        'logged_in' => isStudentLoggedIn(),
        'student_name' => $_SESSION['student_name'] ?? null,
        'student_email' => $_SESSION['student_email'] ?? null
    ]);
    exit;
}

// Handle course apply redirect
if (isset($_GET['course']) && isset($_GET['action']) && $_GET['action'] === 'apply') {
    $courseCode = $_GET['course'];
    $redirectUrl = getCourseRegistrationUrl($courseCode);
    header("Location: " . $redirectUrl);
    exit;
}
?>
