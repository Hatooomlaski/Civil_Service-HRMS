<?php
session_start();
include '../includes/connection.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch metrics for cards
$total_staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM staff"))['total'];
$total_service = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM records_of_service"))['total'];

// Fetch leave summary for chart
$leave_summary_query = mysqli_query($conn, "
    SELECT status, COUNT(*) as total 
    FROM leaves 
    GROUP BY status
");

// Prepare data for chart
$leave_labels = [];
$leave_data = [];
while($row = mysqli_fetch_assoc($leave_summary_query)){
    $leave_labels[] = ucfirst($row['status']);
    $leave_data[] = $row['total'];
}

// Optional: filter by department
$department_filter = isset($_GET['department']) ? mysqli_real_escape_string($conn, $_GET['department']) : '';
$staff_where = $department_filter ? "WHERE department='$department_filter'" : "";
$departments = mysqli_query($conn, "SELECT DISTINCT department FROM staff");

// Fetch leave details
$leave_details_query = mysqli_query($conn, "
    SELECT l.*, s.full_name, s.department 
    FROM leaves l 
    JOIN staff s ON l.staff_id = s.id
    $staff_where
    ORDER BY l.start_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports | Civil Service HRMS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

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
         <?php if($_SESSION['role'] === 'Admin'): ?>
        <li class="mb-3"><a href="manage_users.php" class="block p-2 bg-emerald-900 rounded">⚙️ Manage Users</a></li>
        <?php endif; ?>
        <!-- <li class="mb-3"><a href="change_password.php" class="block p-2 hover:bg-emerald-600 rounded">Change Password</a></li> -->
        <li class="mt-8"><a href="../actions/logout.php" class="block p-2 bg-red-600 text-center rounded"><span class="w-6">↩️</span> <span>Logout</span></a></li>
      </ul>
    </div>

  <!-- Main Content -->
  <main class="flex-1 p-6">
    <h1 class="text-2xl font-bold mb-4">Reports</h1>
    <p class="mb-6 text-gray-700">Dashboard › Reports</p>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-lg font-semibold">Total Staff</h2>
        <p class="text-3xl font-bold text-emerald-700"><?= $total_staff ?></p>
      </div>
      <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-lg font-semibold">Total Records of Service</h2>
        <p class="text-3xl font-bold text-emerald-700"><?= $total_service ?></p>
      </div>
      <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-lg font-semibold">Leaves Summary</h2>
        <canvas id="leaveChart" class="mt-4"></canvas>
      </div>
    </div>

    <!-- Filter by Department -->
    <div class="bg-white shadow-md rounded p-6 mb-6">
      <form method="GET" class="flex items-center gap-4">
        <label class="font-medium text-gray-700">Filter by Department:</label>
        <select name="department" class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none">
          <option value="">All Departments</option>
          <?php while($dept = mysqli_fetch_assoc($departments)): ?>
            <option value="<?= htmlspecialchars($dept['department']) ?>" <?= $department_filter === $dept['department'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($dept['department']) ?>
            </option>
          <?php endwhile; ?>
        </select>
        <button type="submit" class="bg-emerald-700 text-white px-4 py-2 rounded hover:bg-emerald-800">Filter</button>
      </form>
    </div>

    <!-- Leave Details Table -->
    <div class="bg-white shadow-md rounded p-6">
      <h2 class="text-xl font-semibold mb-4">Leaves Details</h2>
      <table class="w-full border-collapse border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2 text-left">Staff Name</th>
            <th class="border p-2 text-left">Department</th>
            <th class="border p-2 text-left">Leave Type</th>
            <th class="border p-2 text-left">Start Date</th>
            <th class="border p-2 text-left">End Date</th>
            <th class="border p-2 text-left">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while($leave = mysqli_fetch_assoc($leave_details_query)): ?>
          <tr>
            <td class="border p-2"><?= htmlspecialchars($leave['full_name']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($leave['department']) ?></td>
            <td class="border p-2"><?= ucfirst($leave['leave_type']) ?></td>
            <td class="border p-2"><?= $leave['start_date'] ?></td>
            <td class="border p-2"><?= $leave['end_date'] ?></td>
            <td class="border p-2">
              <?php
                $status = $leave['status'];
                $color = $status === 'approved' ? 'text-green-600' : ($status === 'pending' ? 'text-yellow-600' : 'text-red-600');
              ?>
              <span class="<?= $color ?> font-semibold"><?= ucfirst($status) ?></span>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </main>

  <!-- Chart.js Script -->
  <script>
    const ctx = document.getElementById('leaveChart').getContext('2d');
    const leaveChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: <?= json_encode($leave_labels) ?>,
        datasets: [{
          label: 'Leave Status',
          data: <?= json_encode($leave_data) ?>,
          backgroundColor: ['#16a34a', '#eab308', '#dc2626'], // Green, Yellow, Red
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
          },
        }
      }
    });
  </script>

</body>
</html>
