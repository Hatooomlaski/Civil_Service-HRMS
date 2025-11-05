<?php
session_start();
$NameErr = $_GET['name'] ?? ' ';
$EmailErr = $_GET['email'] ?? ' ';
$PasswordErr = $_GET['password'] ?? ' ';
$Newpassword = $_GET['hashed_password'] ?? ' ';
$session_value = $_SESSION['name'] ?? '';;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php htmlspecialchars($_SERVER['PHP_SELF'])?>
    <form action="serve.php" method="post">
</body>
</html>