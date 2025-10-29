// Elements
const addStaffBtn = document.getElementById('addStaffBtn');
const modal = document.getElementById('modal');
const cancelBtn = document.getElementById('cancelBtn');
const saveBtn = document.getElementById('saveBtn');
const staffTable = document.getElementById('staffTable').querySelector('tbody');
const searchInput = document.getElementById('searchInput');

// Open modal
addStaffBtn.onclick = () => modal.style.display = 'flex';

// Close modal
cancelBtn.onclick = () => modal.style.display = 'none';

// Add new staff
saveBtn.onclick = () => {
  const name = document.getElementById('fullName').value.trim();
  const dept = document.getElementById('department').value.trim();
  const status = document.getElementById('status').value;

  if (!name || !dept) {
    alert('Please fill all fields');
    return;
  }

  const id = 'CS' + String(staffTable.rows.length + 1).padStart(3, '0');
  const row = staffTable.insertRow();
  row.innerHTML = `
    <td>${id}</td>
    <td>${name}</td>
    <td>${dept}</td>
    <td class="status ${status === 'Active' ? 'active' : 'on-leave'}">${status}</td>
    <td>
      <button class="action view">View</button>
      <button class="action edit">Edit</button>
      <button class="action delete">Delete</button>
    </td>
  `;

  modal.style.display = 'none';
  document.getElementById('fullName').value = '';
  document.getElementById('department').value = '';
};

// Close modal when clicking outside
window.onclick = (e) => {
  if (e.target === modal) modal.style.display = 'none';
};

// Search filter
searchInput.addEventListener('keyup', () => {
  const filter = searchInput.value.toLowerCase();
  const rows = staffTable.querySelectorAll('tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});
