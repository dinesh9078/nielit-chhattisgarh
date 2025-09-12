<?php
// login.php — processes the admin login POST
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: admin_login.php');
  exit;
}

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '') {
  header('Location: admin_login.php?error=' . urlencode('Please enter both email and password.'));
  exit;
}

// Your table has: id, email, password
$stmt = $conn->prepare('SELECT id, email, password FROM admins WHERE email = ? LIMIT 1');
if (!$stmt) {
  header('Location: admin_login.php?error=' . urlencode('Server error: ' . $conn->error));
  exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$res  = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

$ok = false;

if ($user) {
  $stored = $user['password'];

  // 1) First, try as a hash (bcrypt/argon/etc.)
  if (password_get_info($stored)['algo'] !== 0) {
    $ok = password_verify($password, $stored);

  } else {
    // 2) Otherwise, treat stored value as plain text and compare
    if (hash_equals($stored, $password)) {
      $ok = true;

      // Optional: upgrade to a hash so it's secure going forward
      $newHash = password_hash($password, PASSWORD_BCRYPT);
      $up = $conn->prepare('UPDATE admins SET password = ? WHERE id = ?');
      if ($up) {
        $up->bind_param('si', $newHash, $user['id']);
        $up->execute();
        $up->close();
      }
    }
  }
}

if ($ok) {
  $_SESSION['admin_id']    = (int)$user['id'];
  $_SESSION['admin_email'] = $user['email'];
  session_regenerate_id(true);
  header('Location: admin_dashboard.php');
  exit;
}

header('Location: admin_login.php?error=' . urlencode('Invalid email or password.'));
exit;
