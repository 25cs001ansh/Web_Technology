const API_URL = 'api.php';
let currentRole = 'admin';
let currentUsername = 'ansh_admin';

document.addEventListener('DOMContentLoaded', () => {
    loadMenu();
    loadStats();
    loadItems();
    loadAuditLogs();
});

// 1. Role Switcher Handler
function changeRole() {
    currentRole = document.getElementById('roleSelector').value;
    const badge = document.getElementById('currentRoleBadge');
    if (badge) badge.innerText = currentRole.toUpperCase();
    
    const roleIndicator = document.getElementById('roleActionIndicator');
    const formCard = document.getElementById('formCard');

    if (currentRole === 'admin') {
        if (badge) badge.className = 'badge bg-success';
        if (roleIndicator) roleIndicator.innerText = 'Role: Admin (Full Access)';
        if (formCard) formCard.style.display = 'block';
    } else if (currentRole === 'editor') {
        if (badge) badge.className = 'badge bg-warning text-dark';
        if (roleIndicator) roleIndicator.innerText = 'Role: Editor (Create & Edit Only)';
        if (formCard) formCard.style.display = 'block';
    } else {
        if (badge) badge.className = 'badge bg-secondary';
        if (roleIndicator) roleIndicator.innerText = 'Role: User (Read-Only Access)';
        if (formCard) formCard.style.display = 'none';
    }

    loadMenu();
    loadItems();
}

// 2. Load Dynamic RBAC Navigation Menu via Fetch
async function loadMenu() {
    try {
        const res = await fetch(`${API_URL}?action=get_menu&role=${currentRole}`);
        const result = await res.json();
        const menuUl = document.getElementById('dynamicMenu');
        if (!menuUl) return;
        menuUl.innerHTML = '';

        if (result.status === 'success') {
            result.data.forEach((item, index) => {
                const isActive = index === 0 ? 'active' : '';
                menuUl.innerHTML += `
                    <li class="nav-item">
                        <a class="nav-link nav-link-dynamic ${isActive}" href="${item.link}">${item.title}</a>
                    </li>
                `;
            });
        }
    } catch (err) {
        console.error('Menu load error:', err);
    }
}

// 3. Load Dynamic Statistics & Latest Registrations via Fetch
async function loadStats() {
    try {
        const res = await fetch(`${API_URL}?action=get_stats`);
        const result = await res.json();
        if (result.status === 'success') {
            if (document.getElementById('statUsers')) document.getElementById('statUsers').innerText = result.stats.total_users;
            if (document.getElementById('statStudents')) document.getElementById('statStudents').innerText = result.stats.total_students;
            if (document.getElementById('statEvents')) document.getElementById('statEvents').innerText = result.stats.total_events;
            if (document.getElementById('statAudits')) document.getElementById('statAudits').innerText = result.stats.total_audits;

            const usersTbody = document.getElementById('latestUsersBody');
            if (usersTbody) {
                usersTbody.innerHTML = '';
                result.latest_registrations.forEach(u => {
                    const roleClass = u.role === 'admin' ? 'badge-role-admin' : (u.role === 'editor' ? 'badge-role-editor' : 'badge-role-user');
                    usersTbody.innerHTML += `
                        <tr>
                            <td>
                                <div class="fw-semibold small">${u.username}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">${u.email}</div>
                            </td>
                            <td><span class="badge badge-student">${u.user_type}</span></td>
                            <td><span class="badge ${roleClass}">${u.role}</span></td>
                            <td class="text-muted small">${u.created_at.split(' ')[0]}</td>
                        </tr>
                    `;
                });
            }
        }
    } catch (err) {
        console.error('Stats fetch error:', err);
    }
}

// 4. Fetch All Items (Read)
async function loadItems() {
    try {
        const res = await fetch(`${API_URL}?action=read`);
        const result = await res.json();
        const tbody = document.getElementById('itemsTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        if (result.status === 'success') {
            result.data.forEach(item => {
                let actionButtons = '';
                if (currentRole === 'admin') {
                    actionButtons = `
                        <button class="btn btn-sm btn-outline-warning py-0 px-2 me-1" onclick="editItem(${item.id}, '${escapeHtml(item.name)}', '${escapeHtml(item.category)}', ${item.price})">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="deleteItem(${item.id})">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    `;
                } else if (currentRole === 'editor') {
                    actionButtons = `
                        <button class="btn btn-sm btn-outline-warning py-0 px-2" onclick="editItem(${item.id}, '${escapeHtml(item.name)}', '${escapeHtml(item.category)}', ${item.price})">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                    `;
                } else {
                    actionButtons = `<span class="badge bg-light text-muted border">Read Only</span>`;
                }

                tbody.innerHTML += `
                    <tr>
                        <td class="fw-bold text-muted">#${item.id}</td>
                        <td class="fw-semibold">${escapeHtml(item.name)}</td>
                        <td><span class="badge bg-light text-dark border">${escapeHtml(item.category)}</span></td>
                        <td class="text-success fw-bold">$${parseFloat(item.price).toFixed(2)}</td>
                        <td class="text-center">${actionButtons}</td>
                    </tr>
                `;
            });
        }
    } catch (err) {
        console.error('Items load error:', err);
    }
}

// 5. Fetch Audit Logs
async function loadAuditLogs() {
    try {
        const res = await fetch(`${API_URL}?action=get_audit_logs`);
        const result = await res.json();
        const tbody = document.getElementById('auditLogsBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        if (result.status === 'success') {
            result.data.forEach(log => {
                let badgeClass = 'audit-system';
                if (log.action.includes('CREATE')) badgeClass = 'audit-create';
                else if (log.action.includes('UPDATE')) badgeClass = 'audit-update';
                else if (log.action.includes('DELETE')) badgeClass = 'audit-delete';
                else if (log.action.includes('SECURITY') || log.action.includes('403')) badgeClass = 'audit-security';

                const timeStr = log.timestamp.split(' ')[1] || log.timestamp;

                tbody.innerHTML += `
                    <tr>
                        <td class="text-muted" style="white-space: nowrap;">${timeStr}</td>
                        <td><span class="audit-badge ${badgeClass}">${log.action}</span></td>
                        <td><strong>${log.username}</strong></td>
                        <td class="text-truncate" style="max-width: 180px;" title="${escapeHtml(log.details)}">${escapeHtml(log.details)}</td>
                    </tr>
                `;
            });
        }
    } catch (err) {
        console.error('Audit logs load error:', err);
    }
}

// 6. Asynchronous Create / Update (Save Item)
async function saveItem(event) {
    event.preventDefault();

    const id = document.getElementById('itemId').value;
    const name = document.getElementById('itemName').value;
    const category = document.getElementById('itemCategory').value;
    const price = document.getElementById('itemPrice').value;
    const action = id ? 'update' : 'create';

    const payload = { id, name, category, price };
    try {
        const res = await fetch(`${API_URL}?action=${action}&user=${currentUsername}&role=${currentRole}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();

        showAlert(result.message, result.status === 'success' ? 'success' : 'danger');
        if (result.status === 'success') {
            resetForm();
            loadItems();
            loadStats();
            loadAuditLogs();
        }
    } catch (err) {
        showAlert('Server request error: ' + err, 'danger');
    }
}

// 7. Populate Form for Edit
function editItem(id, name, category, price) {
    document.getElementById('itemId').value = id;
    document.getElementById('itemName').value = name;
    document.getElementById('itemCategory').value = category;
    document.getElementById('itemPrice').value = price;

    document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square text-warning"></i> Edit Inventory Item #' + id;
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-save me-1"></i> Update Item';
    document.getElementById('submitBtn').className = 'btn btn-warning btn-sm text-dark px-3';
    document.getElementById('cancelBtn').style.display = 'inline-block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// 8. Asynchronous Delete Item
async function deleteItem(id) {
    if (!confirm(`Are you sure you want to delete Item #${id}? This action will be recorded in audit logs.`)) return;

    try {
        const res = await fetch(`${API_URL}?action=delete&user=${currentUsername}&role=${currentRole}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const result = await res.json();
        showAlert(result.message, result.status === 'success' ? 'success' : 'danger');
        if (result.status === 'success') {
            loadItems();
            loadStats();
            loadAuditLogs();
        }
    } catch (err) {
        showAlert('Delete failed: ' + err, 'danger');
    }
}

// 9. Reset Form
function resetForm() {
    document.getElementById('itemId').value = '';
    document.getElementById('itemForm').reset();
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-plus-circle text-primary"></i> Add New Inventory Item';
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-check-lg me-1"></i> Save Item (Fetch API)';
    document.getElementById('submitBtn').className = 'btn btn-primary btn-sm px-3';
    document.getElementById('cancelBtn').style.display = 'none';
}

// Helper: Alert Display
function showAlert(msg, type) {
    const alertBox = document.getElementById('statusAlert');
    if (!alertBox) return;
    alertBox.className = `alert alert-${type} py-1 px-3 small`;
    alertBox.innerText = msg;
    alertBox.style.display = 'block';
    setTimeout(() => { alertBox.style.display = 'none'; }, 3500);
}

// Helper: Escape HTML
function escapeHtml(text) {
    return text ? text.replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m]) : '';
}
