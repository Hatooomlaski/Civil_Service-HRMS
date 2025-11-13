<?php
session_start();
include '../includes/connection.php'; // Adjust path

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}



// Handles Add Leave
if (isset($_POST['action']) && $_POST['action'] === 'add') {
  $staff_id = (int)$_POST['staff_id']; // Use staff.id
  $leave_type = mysqli_real_escape_string($conn, $_POST['leave_type']);
  $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
  $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $reason = mysqli_real_escape_string($conn, $_POST['reason']);

  mysqli_query($conn, "INSERT INTO leaves (staff_id, leave_type, start_date, end_date, status, reason)
                        VALUES ($staff_id,'$leave_type','$start_date','$end_date','$status','$reason')");
  header("Location: leave.php");
  exit();
}

// Handle Edit Leave
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
  $id = (int)$_POST['id'];
  $staff_id = (int)$_POST['staff_id']; 
  $leave_type = mysqli_real_escape_string($conn, $_POST['leave_type']);
  $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
  $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $reason = mysqli_real_escape_string($conn, $_POST['reason']);

  mysqli_query($conn, "UPDATE leaves SET
        staff_id=$staff_id,
        leave_type='$leave_type',
        start_date='$start_date',
        end_date='$end_date',
        status='$status',
        reason='$reason'
        WHERE id=$id");
  header("Location: leave.php");
  exit();
}

// Handle Delete Leave
if (isset($_GET['delete_id'])) {
  $id = (int)$_GET['delete_id'];
  mysqli_query($conn, "DELETE FROM leaves WHERE id=$id");
  header("Location: leave.php");
  exit();
}

// Fetch leave records with staff full names
$result = mysqli_query($conn, "SELECT l.*, s.full_name 
                              FROM leaves l 
                              JOIN staff s ON l.staff_id = s.id
                              ORDER BY l.id DESC");

// Fetch staff for dropdown
$staffs = mysqli_query($conn, "SELECT id, full_name FROM staff ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Civil Service HRMS - Leave Information</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <div class="w-64 bg-emerald-700 text-white p-4">
      <div class="flex flex-col justify-center items-center p-7">
        <img src="../images/ogunlogo.jpg" alt="Logo" class="h-20 w-20 mb-4">
        <h2 class="text-xl font-bold">Civil Service HRMS</h2>
        <p class="text-sm mt-1">Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Guest'); ?></p>
      </div>
      <ul class="mt-6">
        <li class="mb-3"><a href="dashboard.php" class="block p-2 hover:bg-emerald-600 rounded">🏠 Dashboard</a></li>
        <li class="mb-3"><a href="staff.php" class="block p-2 hover:bg-emerald-600 rounded">👥 Staff</a></li>
        <li class="mb-3"><a href="leave.php" class="block p-2 bg-emerald-900 rounded">📁 Leave Information</a></li>
        <li class="mb-3"><a href="record-service.php" class="block p-2 hover:bg-emerald-600 rounded">📜 Record of Service</a></li>
        <li class="mb-3"><a href="report.php" class="block p-2 hover:bg-emerald-600 rounded">📊 Reports</a></li>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
          <li class="mb-3"><a href="manage_users.php" class="block p-2 bg-emerald-900 rounded">⚙️ Manage Users</a></li>
        <?php endif; ?>
        <li class="mt-8"><a href="../actions/logout.php" class="block p-2 bg-red-600 text-center rounded">↩️ Logout</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <div class="mb-6">
        <h1 class="text-2xl font-bold">Leave Information</h1>
        <p class="text-gray-600">Dashboard › Leave Management</p>
      </div>

      <div class="flex justify-between items-center mb-4">
        <input type="text" id="searchInput" placeholder="Search by staff name or leave type" class="border p-2 rounded w-1/3" />
        <button id="addLeaveBtn" class="bg-emerald-700 text-white px-4 py-2 rounded hover:bg-emerald-800">Add Leave</button>
      </div>

      <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200" id="leaveTable">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Leave Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php while ($leave = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td class="px-6 py-4"><?php echo htmlspecialchars($leave['full_name']); ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars(ucfirst($leave['leave_type'])); ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars($leave['start_date']); ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars($leave['end_date']); ?></td>
                <td class="px-6 py-4">
                  <span class="px-2 inline-flex text-xs font-semibold rounded-full <?php
                                                                                    echo $leave['status'] == 'approved' ? 'bg-green-100 text-green-800' : ($leave['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                                                                    ?>">
                    <?php echo ucfirst($leave['status']); ?></span>
                </td>
                <td class="px-6 py-4"><?php echo htmlspecialchars($leave['reason']); ?></td>
                <td class="px-6 py-4">
                  <button class="text-blue-600 hover:text-blue-900 mr-2 viewBtn"
                    data-staff="<?php echo htmlspecialchars($leave['full_name']); ?>"
                    data-leave_type="<?php echo htmlspecialchars($leave['leave_type']); ?>"
                    data-start="<?php echo htmlspecialchars($leave['start_date']); ?>"
                    data-end="<?php echo htmlspecialchars($leave['end_date']); ?>"
                    data-status="<?php echo htmlspecialchars($leave['status']); ?>"
                    data-reason="<?php echo htmlspecialchars($leave['reason']); ?>">View</button>
                  <button class="text-green-600 hover:text-green-900 mr-2 editBtn"
                    data-id="<?php echo $leave['id']; ?>"
                    data-staff_id="<?php echo $leave['staff_id']; ?>"
                    data-leave_type="<?php echo htmlspecialchars($leave['leave_type']); ?>"
                    data-start="<?php echo htmlspecialchars($leave['start_date']); ?>"
                    data-end="<?php echo htmlspecialchars($leave['end_date']); ?>"
                    data-status="<?php echo htmlspecialchars($leave['status']); ?>"
                    data-reason="<?php echo htmlspecialchars($leave['reason']); ?>">Edit</button>
                  <a href="?delete_id=<?php echo $leave['id']; ?>" class="text-red-600 hover:text-red-900"
                    onclick="return confirm('Are you sure you want to delete this leave?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Add/Edit Leave Modal -->
  <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded w-1/3 max-h-[90vh] overflow-auto">
      <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Leave</h2>
      <form method="POST" id="leaveForm">
        <input type="hidden" name="action" value="add" id="actionInput">
        <input type="hidden" name="id" id="leaveId">

        <label class="block mb-2">Staff</label>
        <select name="staff_id" id="staffSelect" class="w-full border p-2 rounded mb-3" required>
          <option value="">Select Staff</option>
          <?php while ($s = mysqli_fetch_assoc($staffs)): ?>
            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['full_name']); ?></option>
          <?php endwhile; ?>
        </select>

        <label class="block mb-2">Leave Type</label>
        <select name="leave_type" id="leaveType" class="w-full border p-2 rounded mb-3" required>
          <option value="annual">Annual</option>
          <option value="sick">Sick</option>
          <option value="study">Study</option>
        </select>

        <label class="block mb-2">Start Date</label>
        <input type="date" name="start_date" id="startDate" class="w-full border p-2 rounded mb-3" required>

        <label class="block mb-2">End Date</label>
        <input type="date" name="end_date" id="endDate" class="w-full border p-2 rounded mb-3" required>

        <label class="block mb-2">Status</label>
        <select name="status" id="status" class="w-full border p-2 rounded mb-3">
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>

        <label class="block mb-2">Reason</label>
        <textarea name="reason" id="reason" class="w-full border p-2 rounded mb-4"></textarea>

        <div class="flex justify-end">
          <button type="button" id="cancelBtn" class="mr-2 px-4 py-2 border rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-emerald-700 text-white rounded hover:bg-emerald-800">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Leave Modal -->
  <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded w-1/3 max-h-[90vh] overflow-auto">
      <h2 class="text-xl font-bold mb-4">Leave Details</h2>
      <p><strong>Staff:</strong> <span id="viewStaff"></span></p>
      <p><strong>Leave Type:</strong> <span id="viewLeaveType"></span></p>
      <p><strong>Start Date:</strong> <span id="viewStart"></span></p>
      <p><strong>End Date:</strong> <span id="viewEnd"></span></p>
      <p><strong>Status:</strong> <span id="viewStatus"></span></p>
      <p><strong>Reason:</strong> <span id="viewReason"></span></p>
      <div class="flex justify-end mt-4">
        <button id="closeViewBtn" class="px-4 py-2 border rounded">Close</button>
      </div>
    </div>
  </div>

  <script>
    const modal = document.getElementById('modal');
    const viewModal = document.getElementById('viewModal');
    const addBtn = document.getElementById('addLeaveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const actionInput = document.getElementById('actionInput');
    const leaveIdInput = document.getElementById('leaveId');
    const staffSelect = document.getElementById('staffSelect');
    const leaveTypeInput = document.getElementById('leaveType');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const statusInput = document.getElementById('status');
    const reasonInput = document.getElementById('reason');

    // Add Leave Modal
    addBtn.onclick = () => {
      modal.classList.remove('hidden');
      document.getElementById('modalTitle').textContent = 'Add Leave';
      actionInput.value = 'add';
      leaveIdInput.value = '';
      staffSelect.value = '';
      leaveTypeInput.value = 'annual';
      startDateInput.value = '';
      endDateInput.value = '';
      statusInput.value = 'pending';
      reasonInput.value = '';
    };

    // Cancel modal
    cancelBtn.onclick = () => modal.classList.add('hidden');

    // Edit Leave Modal
    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.onclick = () => {
        document.getElementById('modalTitle').textContent = 'Edit Leave';
        modal.classList.remove('hidden');
        actionInput.value = 'edit';
        leaveIdInput.value = btn.dataset.id;
        staffSelect.value = btn.dataset.staff_id;
        leaveTypeInput.value = btn.dataset.leave_type;
        startDateInput.value = btn.dataset.start;
        endDateInput.value = btn.dataset.end;
        statusInput.value = btn.dataset.status;
        reasonInput.value = btn.dataset.reason;
      };
    });

    // View Leave Modal
    document.querySelectorAll('.viewBtn').forEach(btn => {
      btn.onclick = () => {
        document.getElementById('viewStaff').textContent = btn.dataset.staff;
        document.getElementById('viewLeaveType').textContent = btn.dataset.leave_type;
        document.getElementById('viewStart').textContent = btn.dataset.start;
        document.getElementById('viewEnd').textContent = btn.dataset.end;
        document.getElementById('viewStatus').textContent = btn.dataset.status;
        document.getElementById('viewReason').textContent = btn.dataset.reason;
        viewModal.classList.remove('hidden');
      };
    });

    document.getElementById('closeViewBtn').onclick = () => viewModal.classList.add('hidden');

    // Search
    const searchInput = document.getElementById('searchInput');
    const leaveTable = document.getElementById('leaveTable').getElementsByTagName('tbody')[0];
    searchInput.addEventListener('keyup', function() {
      const filter = searchInput.value.toLowerCase();
      Array.from(leaveTable.rows).forEach(row => {
        const staff = row.cells[0].textContent.toLowerCase();
        const leaveType = row.cells[1].textContent.toLowerCase();
        row.style.display = staff.includes(filter) || leaveType.includes(filter) ? '' : 'none';
      });
    });
  </script>
</body>

</html>