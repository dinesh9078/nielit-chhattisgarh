<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/db.php';

$email = 'admin@example.com';
$plain = 'admin123'; // <- choose your password
$hash  = password_hash($plain, PASSWORD_BCRYPT);

$stmt = $conn->prepare('INSERT INTO admins (email, password) VALUES (?, ?) ON DUPLICATE KEY UPDATE password = VALUES(password)');
$stmt->bind_param('ss', $email, $hash);
$stmt->execute();

echo "Seeded/updated admin: $email with password: $plain\n";
