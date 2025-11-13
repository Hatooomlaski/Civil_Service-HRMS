<?php
session_start();
include '../includes/connection.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Compare plain password (change to password_verify() if hashed)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: ../dashboard.php');
            exit;
        } else {
            $_SESSION['error'] = 'Incorrect password.';
            header('Location: ../index.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'User not found.';
        header('Location: ../index.php');
        exit;
    }
}
?>
