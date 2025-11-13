<?php
session_start();
include '../includes/connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
// if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
//     // Redirect non-admin users
//     header("Location: dashboard.php");
//     exit();
// }

// Handle Add User
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    mysqli_query($conn, "INSERT INTO users (full_name, email, username, password, role) 
                        VALUES ('$full_name', '$email', '$username', '$password', '$role')");
    header("Location: manage_users.php");
    exit();
}

// Handle Edit User
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $update_query = "UPDATE users SET full_name='$full_name', email='$email', username='$username', role='$role'";

    // Only update password if new password provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update_query .= ", password='$password'";
    }

    $update_query .= " WHERE id=$id";
    mysqli_query($conn, $update_query);
    header("Location: manage_users.php");
    exit();
}

// Handle Delete User
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    header("Location: manage_users.php");
    exit();
}

// Fetch all users
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users | Civil Service HRMS</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex min-h-screen">

<!-- Sidebar -->
<div class="w-64 bg-emerald-700 text-white p-4">
    <div class="flex flex-col justify-center items-center p-7">
        <img src="../images/ogunlogo.jpg" alt="Logo" class="h-20 w-20 mb-4">
        <p class="text-sm mt-1">Welcome, <?= htmlspecialchars($_SESSION['full_name']??'Guest'); ?></p>
    </div>
    <ul class="mt-6">
        <li class="mb-3"><a href="dashboard.php" class="block p-2 hover:bg-emerald-600 rounded">🏠 Dashboard</a></li>
        <li class="mb-3"><a href="staff.php" class="block p-2 hover:bg-emerald-600 rounded">👥 Staff</a></li>
        <li class="mb-3"><a href="leave.php" class="block p-2 hover:bg-emerald-600 rounded">📁 Leave Info</a></li>
        <li class="mb-3"><a href="record-service.php" class="block p-2 hover:bg-emerald-600 rounded">📜 Records</a></li>
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
        <h1 class="text-2xl font-bold">Manage Users</h1>
        <p class="text-gray-600">Dashboard › Manage Users</p>
    </div>

    <div class="flex justify-between items-center mb-4">
        <input type="text" id="searchInput" placeholder="Search by name, email, or username" class="border p-2 rounded w-1/3"/>
        <button id="addUserBtn" class="bg-emerald-700 text-white px-4 py-2 rounded hover:bg-emerald-800">Add User</button>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200" id="usersTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="px-6 py-4"><?= htmlspecialchars($user['full_name']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($user['email']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($user['username']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($user['role']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($user['created_at']); ?></td>
                    <td class="px-6 py-4">
                        <button class="text-blue-600 hover:text-blue-900 mr-2 viewBtn"
                                data-id="<?= $user['id']; ?>"
                                data-full_name="<?= htmlspecialchars($user['full_name']); ?>"
                                data-email="<?= htmlspecialchars($user['email']); ?>"
                                data-username="<?= htmlspecialchars($user['username']); ?>"
                                data-role="<?= htmlspecialchars($user['role']); ?>"
                                >View</button>
                        <button class="text-green-600 hover:text-green-900 mr-2 editBtn"
                                data-id="<?= $user['id']; ?>"
                                data-full_name="<?= htmlspecialchars($user['full_name']); ?>"
                                data-email="<?= htmlspecialchars($user['email']); ?>"
                                data-username="<?= htmlspecialchars($user['username']); ?>"
                                data-role="<?= htmlspecialchars($user['role']); ?>"
                                >Edit</button>
                        <a href="?delete_id=<?= $user['id']; ?>" class="text-red-600 hover:text-red-900"
                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
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
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add User</h2>
        <form method="POST" id="userForm">
            <input type="hidden" name="action" value="add" id="actionInput">
            <input type="hidden" name="id" id="userId">

            <label class="block mb-2">Full Name</label>
            <input type="text" name="full_name" id="fullName" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Email</label>
            <input type="email" name="email" id="email" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Username</label>
            <input type="text" name="username" id="username" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Password <small>(Leave empty to keep current)</small></label>
            <input type="password" name="password" id="password" class="w-full border p-2 rounded mb-3">

            <label class="block mb-2">Role</label>
            <select name="role" id="role" class="w-full border p-2 rounded mb-4">
                <option value="Admin">Admin</option>
                <option value="User">User</option>
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
        <h2 class="text-xl font-bold mb-4">User Details</h2>
        <p><strong>Full Name:</strong> <span id="viewFullName"></span></p>
        <p><strong>Email:</strong> <span id="viewEmail"></span></p>
        <p><strong>Username:</strong> <span id="viewUsername"></span></p>
        <p><strong>Role:</strong> <span id="viewRole"></span></p>
        <div class="flex justify-end mt-4">
            <button id="closeViewBtn" class="px-4 py-2 border rounded">Close</button>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('modal');
const viewModal = document.getElementById('viewModal');
const addBtn = document.getElementById('addUserBtn');
const cancelBtn = document.getElementById('cancelBtn');
const userForm = document.getElementById('userForm');
const modalTitle = document.getElementById('modalTitle');
const actionInput = document.getElementById('actionInput');
const userIdInput = document.getElementById('userId');
const fullNameInput = document.getElementById('fullName');
const emailInput = document.getElementById('email');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const roleInput = document.getElementById('role');

// Open Add Modal
addBtn.onclick = () => {
    modalTitle.textContent = 'Add User';
    actionInput.value = 'add';
    userIdInput.value = '';
    fullNameInput.value = '';
    emailInput.value = '';
    usernameInput.value = '';
    passwordInput.value = '';
    roleInput.value = 'User';
    modal.classList.remove('hidden');
};

// Cancel Modal
cancelBtn.onclick = () => modal.classList.add('hidden');

// Open Edit Modal
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.onclick = () => {
        modalTitle.textContent = 'Edit User';
        actionInput.value = 'edit';
        userIdInput.value = btn.dataset.id;
        fullNameInput.value = btn.dataset.full_name;
        emailInput.value = btn.dataset.email;
        usernameInput.value = btn.dataset.username;
        passwordInput.value = '';
        roleInput.value = btn.dataset.role;
        modal.classList.remove('hidden');
    };
});

// Open View Modal
document.querySelectorAll('.viewBtn').forEach(btn => {
    btn.onclick = () => {
        document.getElementById('viewFullName').textContent = btn.dataset.full_name;
        document.getElementById('viewEmail').textContent = btn.dataset.email;
        document.getElementById('viewUsername').textContent = btn.dataset.username;
        document.getElementById('viewRole').textContent = btn.dataset.role;
        viewModal.classList.remove('hidden');
    };
});

document.getElementById('closeViewBtn').onclick = () => viewModal.classList.add('hidden');

// Simple search
const searchInput = document.getElementById('searchInput');
const usersTable = document.getElementById('usersTable').getElementsByTagName('tbody')[0];
searchInput.addEventListener('keyup', function() {
    const filter = searchInput.value.toLowerCase();
    Array.from(usersTable.rows).forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const email = row.cells[1].textContent.toLowerCase();
        const username = row.cells[2].textContent.toLowerCase();
        row.style.display = name.includes(filter) || email.includes(filter) || username.includes(filter) ? '' : 'none';
    });
});
</script>
</body>
</html>
