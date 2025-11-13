<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Civil Service HR - Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex">
  <?php if (isset($_SESSION['error'])): ?>
      <p class="bg-red-100 text-red-700 text-center p-2 mb-3 rounded">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </p>
    <?php endif; ?>
  <!-- Left Section -->
  <div class="w-1/3 bg-green-600 flex flex-col justify-center items-center text-white p-10">
    <div class="text-center">
      <div class="flex justify-center mb-4">
        <!-- Logo Placeholder -->
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
      

      <form class="space-y-5">
        <form action="actions/login_actions2.php" method="post">
<!-- <button type="submit"></button> -->
        <div>
          <label for="email" class="block text-gray-700 mb-2">Email</label>
          <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your email" required>
        </div>

        <div>
          <label for="password" class="block text-gray-700 mb-2">Password</label>
          <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your password" required>
        </div>

        <button type="submit" name="login" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition duration-200">
          Login
        </button>

        <p class="text-center text-sm text-gray-600 mt-4">
          <a href="#" class="text-green-600 hover:underline">Forgot password?</a>
        </p>
      </form>

      <p id="errorMsg" class="text-center text-red-600 mt-4 hidden">Please fill in all fields correctly.</p>
    </div>
  </div>

  <!-- JavaScript Validation -->
  <!-- <script>
    const form = document.getElementById("loginForm");
    const errorMsg = document.getElementById("errorMsg");

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

      if (!email || !password || !email.includes("@")) {
        errorMsg.classList.remove("hidden");
      } else {
        errorMsg.classList.add("hidden");
        alert("Login successful!");
        form.reset();
      } -->
      <!-- //  setTimeout(() => {
      //   window.location.href = "dashboard.html"; // Redirect to dashboard page
      // });
    });
  </script> -->

</body>
</html>



<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Civil Service HRMS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex">
  <!-- <div class="w-full max-w-sm bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-emerald-600 text-center mb-4"></h2> -->
    <?php if (isset($_SESSION['error'])): ?>
      <p class="bg-red-100 text-red-700 text-center p-2 mb-3 rounded">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </p>
    <?php endif; ?>

        <!-- Left Section -->
  <div class="w-1/3 bg-emerald-700 flex flex-col justify-center items-center text-white p-10">
    <div class="text-center">
      <div class="flex justify-center mb-4">
        <!-- Logo Placeholder -->
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

    <form action="actions/login_action.php" method="POST" class="space-y-5">
        <div>
          <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
          <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your email" required>
        </div>

         <!-- <div class=" bg-green-600 flex flex-col justify-center items-center text-white p-10">
    <div class="text-center"> -->

      <!-- <div class="flex justify-center mb-4"> -->
        

      <!-- <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Email</label>
        <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div> -->

      <div>
          <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
          <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none" placeholder="Enter your password" required>
        </div>

      <!-- <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Password</label>
        <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div> -->

      <button type="submit" name="login" class="w-full bg-emerald-700 text-white py-2 rounded hover:bg-emerald-700">
        Login
      </button>
    </form>
  </div>
</body>
</html>
