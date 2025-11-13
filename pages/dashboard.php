<?php
session_start();
include '../includes/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch logged-in user info
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Dashboard stats
$totalStaff = $conn->query("SELECT COUNT(*) AS total FROM staff")->fetch_assoc()['total'] ?? 0;
$activeLeave = $conn->query("SELECT COUNT(*) AS total FROM leaves WHERE status='approved'")->fetch_assoc()['total'] ?? 0;
$pendingLeave = $conn->query("SELECT COUNT(*) AS total FROM leaves WHERE status='pending'")->fetch_assoc()['total'] ?? 0;
$departments = $conn->query("SELECT COUNT(DISTINCT department) AS total FROM staff")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Civil Service HRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 bg-emerald-700 text-white min-h-screen p-4">


            <div class="w=full bg-emerald-700 flex flex-col justify-center items-center text-white p-7">
                <div class="text-center">
                    <div class="flex justify-center ">

                        <!-- Logo Placeholder -->
                        <div class="w-20 h-20 border-white flex items-center justify-center">
                            <div>
                                <img src="../images/ogunlogo.jpg" alt="" class="h-20 w-20">

                            </div>
                        </div>
                    </div>
                    <!-- <h2 class="text-2xl font-bold mb-1"></h2> -->
                </div>
            </div>

            <ul>
                <li class="mb-3"><a href="dashboard.php" class="block p-2 bg-emerald-800 rounded"><span class="w-6">🏠</span> <span>Dashboard</span></a></li>
                <li class="mb-3"><a href="staff.php" class="block p-2 hover:bg-emerald-600 rounded"><span class="w-6">👥</span> <span>Staff</span></a></li>
                <li class="mb-3"><a href="leave.php" class="block p-2 hover:bg-emerald-600 rounded"><span class="w-6">📁</span> <span>Leave Information</span></a></li>
                <li class="mb-3"><a href="record-service.php" class="block p-2 hover:bg-emerald-600 rounded"><span class="w-6">📜</span> <span>Record of Service</span></a></li>
                <li class="mb-3"><a href="report.php" class="block p-2 hover:bg-emerald-600 rounded"><span class="w-6">📊</span> <span>Reports</span></a></li>
                <?php if ($_SESSION['role'] === 'Admin'): ?>
                    <li class="mb-3"><a href="manage_users.php" class="block p-2 bg-emerald-900 rounded">⚙️ Manage Users</a></li>
                <?php endif; ?>
                <!-- <li class="mb-3"><a href="change_password.php" class="block p-2 hover:bg-emerald-600 rounded">Change Password</a></li> -->
                <li class="mt-8"><a href="../actions/logout.php" class="block p-2 bg-red-600 text-center rounded"><span class="w-6">↩️</span> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-emerald-700">Dashboard</h1>
                <p class="text-gray-600">Welcome, <span class="font-semibold text-emerald-700"><?= htmlspecialchars($_SESSION['full_name'] ?? 'Guest') ?></span></p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <h2 class="text-gray-500 text-sm">Total Staff</h2>
                    <p class="text-3xl font-bold text-emerald-700"><?= $totalStaff ?></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <h2 class="text-gray-500 text-sm">Active Leave</h2>
                    <p class="text-3xl font-bold text-emerald-700"><?= $activeLeave ?></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <h2 class="text-gray-500 text-sm">Pending Leave</h2>
                    <p class="text-3xl font-bold text-emerald-700"><?= $pendingLeave ?></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <h2 class="text-gray-500 text-sm">Departments</h2>
                    <p class="text-3xl font-bold text-emerald-700"><?= $departments ?></p>
                </div>
            </div>

            <!-- Activity Section -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-emerald-700 mb-4">Recent Activities</h2>
                <p class="text-gray-600">This section will later show recent leave requests, staff updates, etc.</p>
            </div>
        </div>
    </div>
</body>

</html>