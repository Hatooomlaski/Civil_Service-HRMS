<?php
session_start();
include 'connection.php';
$NameErr = $_GET['fullname'] ?? ' ';
$EmailErr = $_GET['email'] ?? ' ';
$PasswordErr = $_GET['password'] ?? ' ';
$Newpassword = $_GET['hashed_password'] ?? ' ';
$session_value = $_SESSION['fullname'] ?? '';;
?>