<?php
include 'connection.php';
$EmailErr = $_GET['email'] ?? ' ';
$PasswordErr = $_GET['password'] ?? ' ';
?>
<form action="serve.php" method="post">