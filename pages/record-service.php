<?php
session_start();
include '../includes/connection.php'; // adjust path

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Handle Add Record
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $staff_id = (int)$_POST['staff_id'];
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $responsibilities = mysqli_real_escape_string($conn, $_POST['responsibilities']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    mysqli_query($conn, "INSERT INTO record_of_service (staff_id, position, department, start_date, end_date, responsibilities, remarks)
                         VALUES ($staff_id,'$position','$department','$start_date','$end_date','$responsibilities','$remarks')");
    header("Location: record-service.php");
    exit();
}

// Handle Edit Record
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $staff_id = (int)$_POST['staff_id'];
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $responsibilities = mysqli_real_escape_string($conn, $_POST['responsibilities']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    mysqli_query($conn, "UPDATE record_of_service SET
        staff_id=$staff_id,
        position='$position',
        department='$department',
        start_date='$start_date',
        end_date='$end_date',
        responsibilities='$responsibilities',
        remarks='$remarks'
        WHERE id=$id");
    header("Location: record-service.php");
    exit();
}

// Handle Delete Record
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM record_of_service WHERE id=$id");
    header("Location: record-service.php");
    exit();
}

// Fetch records with staff names
$result = mysqli_query($conn, "SELECT r.*, s.full_name FROM records_of_service r 
                               JOIN staff s ON r.staff_id = s.id
                               ORDER BY r.id DESC");

// Fetch staff for dropdown
$staffs = mysqli_query($conn, "SELECT id, full_name FROM staff ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Civil Service HRMS - Record of Service</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex min-h-screen">

<!-- Sidebar -->
<div class="w-64 bg-emerald-700 text-white p-4">
    <div class="flex flex-col justify-center items-center p-7">
        <img src="../images/ogunlogo.jpg" alt="Logo" class="h-20 w-20 mb-4">
        <h2 class="text-xl font-bold">Civil Service HRMS</h2>
        <p class="text-sm mt-1">Welcome, <?php echo htmlspecialchars($_SESSION['username']??""); ?></p>
    </div>
    <ul class="mt-6">
        <li class="mb-3"><a href="dashboard.php" class="block p-2 hover:bg-emerald-600 rounded">🏠 Dashboard</a></li>
        <li class="mb-3"><a href="staff.php" class="block p-2 hover:bg-emerald-600 rounded">👥 Staff</a></li>
        <li class="mb-3"><a href="leave.php" class="block p-2 hover:bg-emerald-600 rounded">📁 Leave Information</a></li>
        <li class="mb-3"><a href="record-service.php" class="block p-2 bg-emerald-900 rounded">📜 Record of Service</a></li>
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
        <h1 class="text-2xl font-bold">Record of Service</h1>
        <p class="text-gray-600">Dashboard › Record of Service</p>
    </div>

    <div class="flex justify-between items-center mb-4">
        <input type="text" id="searchInput" placeholder="Search by staff, position or department" class="border p-2 rounded w-1/3"/>
        <button id="addRecordBtn" class="bg-emerald-700 text-white px-4 py-2 rounded hover:bg-emerald-800">Add Record</button>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200" id="recordTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsibilities</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while($record = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($record['full_name']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($record['position']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($record['department']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($record['start_date']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($record['end_date']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($record['responsibilities']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($record['remarks']); ?></td>
                    <td class="px-6 py-4">
                        <button class="text-blue-600 hover:text-blue-900 mr-2 viewBtn"
                            data-staff="<?php echo htmlspecialchars($record['full_name']); ?>"
                            data-position="<?php echo htmlspecialchars($record['position']); ?>"
                            data-department="<?php echo htmlspecialchars($record['department']); ?>"
                            data-start="<?php echo htmlspecialchars($record['start_date']); ?>"
                            data-end="<?php echo htmlspecialchars($record['end_date']); ?>"
                            data-responsibilities="<?php echo htmlspecialchars($record['responsibilities']); ?>"
                            data-remarks="<?php echo htmlspecialchars($record['remarks']); ?>"
                        >View</button>
                        <button class="text-green-600 hover:text-green-900 mr-2 editBtn"
                            data-id="<?php echo $record['id']; ?>"
                            data-staff_id="<?php echo $record['staff_id']; ?>"
                            data-position="<?php echo htmlspecialchars($record['position']); ?>"
                            data-department="<?php echo htmlspecialchars($record['department']); ?>"
                            data-start="<?php echo htmlspecialchars($record['start_date']); ?>"
                            data-end="<?php echo htmlspecialchars($record['end_date']); ?>"
                            data-responsibilities="<?php echo htmlspecialchars($record['responsibilities']); ?>"
                            data-remarks="<?php echo htmlspecialchars($record['remarks']); ?>"
                        >Edit</button>
                        <a href="?delete_id=<?php echo $record['id']; ?>" class="text-red-600 hover:text-red-900"
                           onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</div>

<!-- Add/Edit Record Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded w-1/3 max-h-[90vh] overflow-auto">
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Record</h2>
        <form method="POST" id="recordForm">
            <input type="hidden" name="action" value="add" id="actionInput">
            <input type="hidden" name="id" id="recordId">

            <label class="block mb-2">Staff</label>
            <select name="staff_id" id="staffSelect" class="w-full border p-2 rounded mb-3" required>
                <option value="">Select Staff</option>
                <?php while($s = mysqli_fetch_assoc($staffs)): ?>
                <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['full_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label class="block mb-2">Position</label>
            <input type="text" name="position" id="position" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Department</label>
            <input type="text" name="department" id="department" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Start Date</label>
            <input type="date" name="start_date" id="startDate" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">End Date</label>
            <input type="date" name="end_date" id="endDate" class="w-full border p-2 rounded mb-3">

            <label class="block mb-2">Responsibilities</label>
            <textarea name="responsibilities" id="responsibilities" class="w-full border p-2 rounded mb-3"></textarea>

            <label class="block mb-2">Remarks</label>
            <textarea name="remarks" id="remarks" class="w-full border p-2 rounded mb-4"></textarea>

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
        <h2 class="text-xl font-bold mb-4">Record Details</h2>
        <p><strong>Staff:</strong> <span id="viewStaff"></span></p>
        <p><strong>Position:</strong> <span id="viewPosition"></span></p>
        <p><strong>Department:</strong> <span id="viewDepartment"></span></p>
        <p><strong>Start Date:</strong> <span id="viewStart"></span></p>
        <p><strong>End Date:</strong> <span id="viewEnd"></span></p>
        <p><strong>Responsibilities:</strong> <span id="viewResponsibilities"></span></p>
        <p><strong>Remarks:</strong> <span id="viewRemarks"></span></p>
        <div class="flex justify-end mt-4">
            <button id="closeViewBtn" class="px-4 py-2 border rounded">Close</button>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('modal');
const viewModal = document.getElementById('viewModal');
const addBtn = document.getElementById('addRecordBtn');
const cancelBtn = document.getElementById('cancelBtn');
const actionInput = document.getElementById('actionInput');
const recordIdInput = document.getElementById('recordId');
const staffSelect = document.getElementById('staffSelect');
const positionInput = document.getElementById('position');
const departmentInput = document.getElementById('department');
const startDateInput = document.getElementById('startDate');
const endDateInput = document.getElementById('endDate');
const responsibilitiesInput = document.getElementById('responsibilities');
const remarksInput = document.getElementById('remarks');

// Add Record Modal
addBtn.onclick = () => {
    modal.classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Add Record';
    actionInput.value = 'add';
    recordIdInput.value = '';
    staffSelect.value = '';
    positionInput.value = '';
    departmentInput.value = '';
    startDateInput.value = '';
    endDateInput.value = '';
    responsibilitiesInput.value = '';
    remarksInput.value = '';
};

// Cancel modal
cancelBtn.onclick = () => modal.classList.add('hidden');

// Edit Record Modal
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.onclick = () => {
        document.getElementById('modalTitle').textContent = 'Edit Record';
        modal.classList.remove('hidden');
        actionInput.value = 'edit';
        recordIdInput.value = btn.dataset.id;
        staffSelect.value = btn.dataset.staff_id;
        positionInput.value = btn.dataset.position;
        departmentInput.value = btn.dataset.department;
        startDateInput.value = btn.dataset.start;
        endDateInput.value = btn.dataset.end;
        responsibilitiesInput.value = btn.dataset.responsibilities;
        remarksInput.value = btn.dataset.remarks;
    };
});

// View Record Modal
document.querySelectorAll('.viewBtn').forEach(btn => {
    btn.onclick = () => {
        document.getElementById('viewStaff').textContent = btn.dataset.staff;
        document.getElementById('viewPosition').textContent = btn.dataset.position;
        document.getElementById('viewDepartment').textContent = btn.dataset.department;
        document.getElementById('viewStart').textContent = btn.dataset.start;
        document.getElementById('viewEnd').textContent = btn.dataset.end;
        document.getElementById('viewResponsibilities').textContent = btn.dataset.responsibilities;
        document.getElementById('viewRemarks').textContent = btn.dataset.remarks;
        viewModal.classList.remove('hidden');
    };
});

document.getElementById('closeViewBtn').onclick = () => viewModal.classList.add('hidden');

// Search
const searchInput = document.getElementById('searchInput');
const recordTable = document.getElementById('recordTable').getElementsByTagName('tbody')[0];
searchInput.addEventListener('keyup', function() {
    const filter = searchInput.value.toLowerCase();
    Array.from(recordTable.rows).forEach(row => {
        const staff = row.cells[0].textContent.toLowerCase();
        const position = row.cells[1].textContent.toLowerCase();
        const department = row.cells[2].textContent.toLowerCase();
        row.style.display = staff.includes(filter) || position.includes(filter) || department.includes(filter) ? '' : 'none';
    });
});
</script>
</body>
</html>
