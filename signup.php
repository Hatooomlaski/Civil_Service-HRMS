<?php
session_start();
include 'includes/connection.php'; // Adjust path as needed

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validation
    if (empty($full_name)) $errors[] = "Full name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($username)) $errors[] = "Username is required.";
    if (empty($password) || strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    // Check for existing email/username
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' OR username='$username'");
    if (mysqli_num_rows($check) > 0) $errors[] = "Email or username already taken.";

    if (empty($errors)) {
        // ✅ Hash the password before storing
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = mysqli_query($conn, "INSERT INTO users (full_name, email, username, password,role) VALUES ('$full_name','$email','$username','$password_hash','$role')");
        if ($insert) {
            header("Location: index.php?signup=success");
            exit();
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up | Civil Service HRMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex">

    <!-- Left Section -->
    <div class="w-1/3 bg-emerald-700 flex flex-col justify-center items-center text-white p-10">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 border-2 border-white flex items-center justify-center">
                    <div>
                        <img src="images/ogunlogo.jpg" alt="" class="h-12 w-12">
                    </div>
                </div>
            </div>
            <h1 class="text-2xl font-semibold">CIVIL SERVICE HR</h1>
            <p class="text-sm tracking-widest">MANAGEMENT SYSTEM</p>
        </div>
    </div>

    <!-- Right Section -->
    <div class="w-2/3 flex flex-col justify-center items-center bg-gray-50">
        <h2 class="text-3xl font-bold mb-6 text-left text-gray-800">Create Account</h2>
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">

            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-5">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                    <input type="text" name="full_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your full name" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your email" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Username</label>
                    <input type="text" name="username" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Choose a username" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your password" required>
                </div>


                <div>
                    <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                    <input type="password" name="confirm_password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Confirm your password" required>
                </div>

                <!-- Role selection -->
                <label class="block font-medium mb-2">Role</label>
                <select name="role" id="role" class="w-full border p-2 rounded mb-4" required>
                    <option value="User" selected>User</option>
                    <option value="Admin">Admin</option>
                </select>
                <button type="submit" class="w-full bg-emerald-700 text-white py-2 rounded hover:bg-emerald-800">
                    Sign Up
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Already have an account? <a href="index.php" class="text-emerald-700 hover:underline">Login here</a>
            </p>
        </div>
    </div>

</body>

</html>