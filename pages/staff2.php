<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Civil Service HRMS - Staff Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container"> 
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
        <!-- <li class="mb-3"><a href="users.php" class="block p-2 hover:bg-emerald-600 rounded">Manage Users</a></li> -->
        <!-- <li class="mb-3"><a href="change_password.php" class="block p-2 hover:bg-emerald-600 rounded">Change Password</a></li> -->
        <li class="mt-8"><a href="../actions/logout.php" class="block p-2 bg-red-600 text-center rounded"><span class="w-6">↩️</span> <span>Logout</span></a></li>
      </ul>
    </div>

    <!-- Sidebar -->
     <!-- <aside id="sidebar" class="sidebar bg-emerald-600 text-white min-h-screen p-6 hidden lg:block">
    <div class="mb-10">
        <div class="text-xl font-bold">Civil Service HRMS</div>
    </div>

    <nav class="space-y-2 text-sm">
        <a href="dashboard.html" class="flex items-center gap-3 p-3 rounded bg-emerald-700">
        <span class="w-6">🏠</span> <span>Dashboard</span>
        </a>
        <a href="staff.html" class="flex items-center gap-3 p-3 rounded hover:bg-emerald-700">
        <span class="w-6">👥</span> <span>Staff</span>
        </a>
        <a href="leave.html" class="flex items-center gap-3 p-3 rounded hover:bg-emerald-700">
        <span class="w-6">📁</span> <span>Leave Information</span>
        </a>
        <a href="record-service.html" class="flex items-center gap-3 p-3 rounded hover:bg-emerald-700">
        <span class="w-6">📜</span> <span>Record of Service</span>
        </a>
        <a href="report.html" class="flex items-center gap-3 p-3 rounded hover:bg-emerald-700">
        <span class="w-6">📊</span> <span>Reports</span>
        </a>
        <a href="" class="flex items-center gap-3 p-3 rounded hover:bg-emerald-700">
        <span class="w-6">⚙️</span> <span>Manage Users</span>
        </a> -->
        <!-- <a href="logout.html" class="flex items-center gap-3 p-3 rounded hover:bg-emerald-700">
        <span class="w-6">↩️</span> <span>Logout</span>
        </a>

        <div class="mt-12 text-xs opacity-90">© 2025 Civil Service HRMS</div>
    </nav>
    </aside> --> 

    <!-- <aside id="sidebar" class="sidebar  bg-emerald-600 text-white min-h-screen p-6 hidden lg:block ">
      <h2>Civil Service HRMS</h2>
      <nav>
        <a href="#"><i class="icon">🏠</i> Dashboard</a>
        <a href="#" class="active"><i class="icon">👥</i> Staff</a>
        <a href="#"><i class="icon">🗓️</i> Leave Information</a>
        <a href="#"><i class="icon">📜</i> Record of Service</a>
        <a href="#"><i class="icon">📊</i> Reports</a>
        <a href="#"><i class="icon">⚙️</i> Manage Users</a>
        <a href="#"><i class="icon">🔒</i> Change Password</a>
        <a href="#"><i class="icon">↩️</i> Logout</a>
      </nav>
      <footer>© 2025 Civil Service HRMS</footer>
    </aside> -->

    <!-- Main Content -->
    <main class="main-content">
      <div class="header">
        <h1>Staff Management</h1>
        <p>Dashboard › Staff Management</p>
      </div>

      <div class="search-add">
        <input type="text" id="searchInput" placeholder="Search by name or department" />
        <button id="addStaffBtn">Add Staff</button>
      </div>

      <!-- Staff Table -->
      <div class="table-container">
        <table id="staffTable">
          <thead>
            <tr>
              <th>Staff ID</th>
              <th>Full Name</th>
              <th>Department</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>CS001</td>
              <td>Adeyemi Samuel</td>
              <td>HR & Admin</td>
              <td class="status active">Active</td>
              <td>
                <button class="action view">View</button>
                <button class="action edit">Edit</button>
                <button class="action delete">Delete</button>
              </td>
            </tr>
            <tr>
              <td>CS002</td>
              <td>Fatima Abdullahi</td>
              <td>Finance</td>
              <td class="status on-leave">On Leave</td>
              <td>
                <button class="action view">View</button>
                <button class="action edit">Edit</button>
                <button class="action delete">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Modal -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <h2>Add Staff</h2>
      <label>Full Name</label>
      <input type="text" id="fullName" placeholder="Enter full name" />
      <label>Department</label>
      <input type="text" id="department" placeholder="Enter department" />
      <label>Status</label>
      <select id="status">
        <option value="Active">Active</option>
        <option value="On Leave">On Leave</option>
      </select>
      <div class="modal-actions">
        <button id="cancelBtn">Cancel</button>
        <button id="saveBtn" class="save">Save</button>
      </div>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
