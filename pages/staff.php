<?php
session_start();
include '../includes/connection.php'; // Adjust the path

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Handle Add Staff
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $date_joined = mysqli_real_escape_string($conn, $_POST['date_joined']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Generate staff_id
    $lastStaff = mysqli_query($conn, "SELECT staff_id FROM staff ORDER BY id DESC LIMIT 1");
    $lastRow = mysqli_fetch_assoc($lastStaff);
    if ($lastRow) {
        $lastId = (int) substr($lastRow['staff_id'], 2);
        $newId = 'S' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newId = 'S001';
    }

    mysqli_query($conn, "INSERT INTO staff 
        (staff_id, full_name, email, department, position, phone_number, date_joined, status)
        VALUES ('$newId','$full_name','$email','$department','$position','$phone_number','$date_joined','$status')");
    header("Location: staff.php");
    exit();
}

// Handle Edit Staff
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $date_joined = mysqli_real_escape_string($conn, $_POST['date_joined']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE staff SET
        full_name='$full_name',
        email='$email',
        department='$department',
        position='$position',
        phone_number='$phone_number',
        date_joined='$date_joined',
        status='$status'
        WHERE id=$id");
    header("Location: staff.php");
    exit();
}

// Handle Delete Staff
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM staff WHERE id=$id");
    header("Location: staff.php");
    exit();
}

// Fetch all staff
$result = mysqli_query($conn, "SELECT * FROM staff ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Civil Service HRMS - Staff Management</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex min-h-screen">

<!-- Sidebar -->
<div class="w-64 bg-emerald-700 text-white p-4">
    <div class="flex flex-col justify-center items-center p-7">
        <img src="../images/ogunlogo.jpg" alt="Logo" class="h-20 w-20 mb-4">
        <!-- <h2 class="text-xl font-bold">Civil Service HRMS</h2> -->
        <p class="text-sm mt-1">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']??'Guest'); ?></p>
    </div>
    <ul class="mt-6">
        <li class="mb-3"><a href="dashboard.php" class="block p-2 bg-emerald-800 rounded">🏠 Dashboard</a></li>
        <li class="mb-3"><a href="staff.php" class="block p-2 bg-emerald-900 rounded">👥 Staff</a></li>
        <li class="mb-3"><a href="leave.php" class="block p-2 hover:bg-emerald-600 rounded">📁 Leave Information</a></li>
        <li class="mb-3"><a href="record-service.php" class="block p-2 hover:bg-emerald-600 rounded">📜 Record of Service</a></li>
        <li class="mb-3"><a href="report.php" class="block p-2 hover:bg-emerald-600 rounded">📊 Reports</a></li>
        <?php if($_SESSION['role'] === 'Admin'): ?>
        <li class="mb-3"><a href="manage_users.php" class="block p-2 bg-emerald-900 rounded">⚙️ Manage Users</a></li>
        <?php endif; ?>
        <li class="mt-8"><a href="../actions/logout.php" class="block p-2 bg-red-600 text-center rounded">↩️ Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<main class="flex-1 p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Staff Management</h1>
        <p class="text-gray-600">Dashboard › Staff Management</p>
        
    </div>

    <div class="flex justify-between items-center mb-4">
        <input type="text" id="searchInput" placeholder="Search by name or department" class="border p-2 rounded w-1/3"/>
        <button id="addStaffBtn" class="bg-emerald-700 text-white px-4 py-2 rounded hover:bg-emerald-800">Add Staff</button>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200" id="staffTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while($staff = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($staff['staff_id']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($staff['full_name']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($staff['department']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs font-semibold rounded-full <?php
                            echo $staff['status']=='Active'?'bg-green-100 text-green-800':'bg-yellow-100 text-yellow-800';
                        ?>">
                        <?php echo $staff['status']; ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="text-blue-600 hover:text-blue-900 mr-2 viewBtn"
                                data-id="<?php echo $staff['id']; ?>"
                                data-staff_id="<?php echo htmlspecialchars($staff['staff_id']); ?>"
                                data-name="<?php echo htmlspecialchars($staff['full_name']); ?>"
                                data-email="<?php echo htmlspecialchars($staff['email']); ?>"
                                data-dept="<?php echo htmlspecialchars($staff['department']); ?>"
                                data-position="<?php echo htmlspecialchars($staff['position']); ?>"
                                data-phone="<?php echo htmlspecialchars($staff['phone_number']); ?>"
                                data-date_joined="<?php echo htmlspecialchars($staff['date_joined']); ?>"
                                data-status="<?php echo htmlspecialchars($staff['status']); ?>">View</button>
                        <button class="text-green-600 hover:text-green-900 mr-2 editBtn"
                                data-id="<?php echo $staff['id']; ?>"
                                data-name="<?php echo htmlspecialchars($staff['full_name']); ?>"
                                data-email="<?php echo htmlspecialchars($staff['email']); ?>"
                                data-dept="<?php echo htmlspecialchars($staff['department']); ?>"
                                data-position="<?php echo htmlspecialchars($staff['position']); ?>"
                                data-phone="<?php echo htmlspecialchars($staff['phone_number']); ?>"
                                data-date_joined="<?php echo htmlspecialchars($staff['date_joined']); ?>"
                                data-status="<?php echo htmlspecialchars($staff['status']); ?>">Edit</button>
                        <a href="?delete_id=<?php echo $staff['id']; ?>" class="text-red-600 hover:text-red-900"
                           onclick="return confirm('Are you sure you want to delete this staff?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</div>

<!-- Add/Edit Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded w-1/3 max-h-[90vh] overflow-auto">
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Staff</h2>
        <form method="POST" id="staffForm">
            <input type="hidden" name="action" value="add" id="actionInput">
            <input type="hidden" name="id" id="staffId">

            <label class="block mb-2">Full Name</label>
            <input type="text" name="full_name" id="fullName" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Email</label>
            <input type="email" name="email" id="email" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Department</label>
            <input type="text" name="department" id="department" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Position</label>
            <input type="text" name="position" id="position" class="w-full border p-2 rounded mb-3">

            <label class="block mb-2">Phone Number</label>
            <input type="text" name="phone_number" id="phone" class="w-full border p-2 rounded mb-3">

            <label class="block mb-2">Date Joined</label>
            <input type="date" name="date_joined" id="dateJoined" class="w-full border p-2 rounded mb-3">

            <label class="block mb-2">Status</label>
            <select name="status" id="status" class="w-full border p-2 rounded mb-4">
                <option value="Active">Active</option>
                <option value="On Leave">On Leave</option>
            </select>

            <div class="flex justify-end">
                <button type="button" id="cancelBtn" class="mr-2 px-4 py-2 border rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 text-white rounded hover:bg-emerald-800">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded w-1/3 max-h-[90vh] overflow-auto">
        <h2 class="text-xl font-bold mb-4">Staff Details</h2>
        <p><strong>Staff ID:</strong> <span id="viewStaffId"></span></p>
        <p><strong>Full Name:</strong> <span id="viewName"></span></p>
        <p><strong>Email:</strong> <span id="viewEmail"></span></p>
        <p><strong>Department:</strong> <span id="viewDept"></span></p>
        <p><strong>Position:</strong> <span id="viewPosition"></span></p>
        <p><strong>Phone Number:</strong> <span id="viewPhone"></span></p>
        <p><strong>Date Joined:</strong> <span id="viewDateJoined"></span></p>
        <p><strong>Status:</strong> <span id="viewStatus"></span></p>
        <div class="flex justify-end mt-4">
            <button id="closeViewBtn" class="px-4 py-2 border rounded">Close</button>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('modal');
const viewModal = document.getElementById('viewModal');
const addBtn = document.getElementById('addStaffBtn');
const cancelBtn = document.getElementById('cancelBtn');
const staffForm = document.getElementById('staffForm');
const modalTitle = document.getElementById('modalTitle');
const actionInput = document.getElementById('actionInput');
const staffIdInput = document.getElementById('staffId');
const fullNameInput = document.getElementById('fullName');
const emailInput = document.getElementById('email');
const deptInput = document.getElementById('department');
const positionInput = document.getElementById('position');
const phoneInput = document.getElementById('phone');
const dateJoinedInput = document.getElementById('dateJoined');
const statusInput = document.getElementById('status');

// Open Add Modal
addBtn.onclick = () => {
    modalTitle.textContent = 'Add Staff';
    actionInput.value = 'add';
    staffIdInput.value = '';
    fullNameInput.value = '';
    emailInput.value = '';
    deptInput.value = '';
    positionInput.value = '';
    phoneInput.value = '';
    dateJoinedInput.value = '';
    statusInput.value = 'Active';
    modal.classList.remove('hidden');
};

// Cancel Modal
cancelBtn.onclick = () => modal.classList.add('hidden');

// Open Edit Modal
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.onclick = () => {
        modalTitle.textContent = 'Edit Staff';
        actionInput.value = 'edit';
        staffIdInput.value = btn.dataset.id;
        fullNameInput.value = btn.dataset.name;
        emailInput.value = btn.dataset.email;
        deptInput.value = btn.dataset.dept;
        positionInput.value = btn.dataset.position;
        phoneInput.value = btn.dataset.phone;
        dateJoinedInput.value = btn.dataset.date_joined;
        statusInput.value = btn.dataset.status;
        modal.classList.remove('hidden');
    };
});

// Open View Modal
document.querySelectorAll('.viewBtn').forEach(btn => {
    btn.onclick = () => {
        document.getElementById('viewStaffId').textContent = btn.dataset.staff_id;
        document.getElementById('viewName').textContent = btn.dataset.name;
        document.getElementById('viewEmail').textContent = btn.dataset.email;
        document.getElementById('viewDept').textContent = btn.dataset.dept;
        document.getElementById('viewPosition').textContent = btn.dataset.position;
        document.getElementById('viewPhone').textContent = btn.dataset.phone;
        document.getElementById('viewDateJoined').textContent = btn.dataset.date_joined;
        document.getElementById('viewStatus').textContent = btn.dataset.status;
        viewModal.classList.remove('hidden');
    };
});

document.getElementById('closeViewBtn').onclick = () => viewModal.classList.add('hidden');

// Simple search
const searchInput = document.getElementById('searchInput');
const staffTable = document.getElementById('staffTable').getElementsByTagName('tbody')[0];
searchInput.addEventListener('keyup', function() {
    const filter = searchInput.value.toLowerCase();
    Array.from(staffTable.rows).forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const dept = row.cells[2].textContent.toLowerCase();
        row.style.display = name.includes(filter) || dept.includes(filter) ? '' : 'none';
    });
});
</script>
</body>
</html>
