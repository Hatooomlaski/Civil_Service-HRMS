<!--
// session_start();
// session_unset();
// session_destroy();
// header('Location: ../index.php');
//exit;

<?php
session_start();

// Clear session
$_SESSION = [];
session_destroy();

// Clear cookies
setcookie('user_id', '', time() - 3600, "/");
setcookie('full_name', '', time() - 3600, "/");
setcookie('role', '', time() - 3600, "/");

header("Location: ../index.php");
exit();
