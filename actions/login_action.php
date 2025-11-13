
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

        // Compare plain password 
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] === 'Admin') {
                header("Location: dashboard.php"); // Admin sees full dashboard including Manage Users
            } else {
                header("Location: dashboard.php"); // Normal user sees limited menu
            }

            header('Location: ../pages/dashboard.php');
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
