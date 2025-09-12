<?php
$host = "localhost";
$user = "root";      // your MySQL username
$pass = "";          // your MySQL password
$db   = "nielit_db";

// MySQLi connection (for backward compatibility)
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// PDO connection (for new registration system)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
