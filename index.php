<?php
session_start();
include 'includes/connection.php'; // Adjust path

$errors = [];

// if($_SESSION['role'] !== 'Admin') {
//     header("Location: ../pages/dashboard.php");
//     exit();
// }


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    if (empty($username)) $errors[] = "Username or email is required.";
    if (empty($password)) $errors[] = "Password is required.";

    if (empty($errors)) {
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$username'");
        if (mysqli_num_rows($query) === 1) {
            $user = mysqli_fetch_assoc($query);

            if (password_verify($password, $user['password'])) {
                // Login success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                if ($remember) {
                    setcookie('user_id', $user['id'], time() + (7 * 24 * 60 * 60), "/");
                    setcookie('full_name', $user['full_name'], time() + (7 * 24 * 60 * 60), "/");
                    setcookie('role', $user['role'], time() + (7 * 24 * 60 * 60), "/");
                }

                header("Location: pages/dashboard.php");
                exit();
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "User not found.";
        }
    }
}

// Check for signup success
$signup_success = isset($_GET['signup']) && $_GET['signup'] === 'success';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Civil Service HRMS</title>
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
    <h2 class="text-3xl font-bold mb-6 text-left text-gray-800">Welcome</h2>
    <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">

      <?php if ($signup_success): ?>
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
          Signup successful! Please login.
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" action="" class="space-y-5">
        <div>
          <label class="block text-gray-700 font-medium mb-2">Username or Email</label>
          <input type="text" name="username" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your username or email" required>
        </div>

        <div>
        <label class="block text-gray-700 font-medium mb-2">Password</label>
        <input type="password" name="password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your password" required>
        </div>
        <div class="flex items-center">
        <input type="checkbox" name="remember" id="remember" class="mr-2">
        <label for="remember" class="text-gray-700 text-sm">Remember Me</label>
        </div>


        <button type="submit" class="w-full bg-emerald-700 text-white py-2 rounded hover:bg-emerald-800">
          Login
        </button>
      </form>

      <p class="mt-4 text-center text-sm text-gray-600">
        Don't have an account? <a href="signup.php" class="text-emerald-700 hover:underline">Sign up here</a>
      </p>
    </div>
  </div>

</body>
</html>















