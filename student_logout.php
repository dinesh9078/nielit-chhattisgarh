<?php
session_start();
session_destroy();
header('Location: student_login.php?success=' . urlencode('You have been logged out successfully.'));
exit;
?>
