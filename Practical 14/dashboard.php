<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard with RBAC & Fetch-Based CRUD</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --sidebar-bg: #0f172a;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1e293b;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -2px rgba(0,0,0,0.12);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            background: #ffffff;
        }
        .card-custom .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 20px;
            font-weight: 600;
        }
        .nav-link-dynamic {
            color: #cbd5e1 !important;
            padding: 8px 14px !important;
            border-radius: 6px;
            transition: all 0.2s;
            font-size: 0.92rem;
            font-weight: 500;
        }
        .nav-link-dynamic:hover, .nav-link-dynamic.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1);
        }
        .table > :not(caption) > * > * {
            padding: 12px 14px;
            vertical-align: middle;
        }
        .badge-role-admin { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-role-editor { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-role-user { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
        .badge-student { background: #dcfce7; color: #166534; font-size: 0.75rem; }
        .audit-badge { font-size: 0.75rem; font-weight: 600; padding: 4px 8px; border-radius: 4px; }
        .audit-create { background: #dcfce7; color: #166534; }
        .audit-update { background: #fef3c7; color: #92400e; }
        .audit-delete { background: #fee2e2; color: #991b1b; }
        .audit-system { background: #e0e7ff; color: #3730a3; }
        .audit-security { background: #f3e8ff; color: #6b21a8; }
    </style>
</head>
<body>

<!-- Dynamic Navbar with RBAC Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-2 px-3 sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="#">
            <i class="bi bi-shield-lock-fill text-primary fs-4"></i>
            <span>AdminHub <span class="badge bg-primary fs-7">RBAC & CRUD</span></span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topNav">
            <!-- Dynamic RBAC Menu Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="dynamicMenu">
                <!-- Injected via loadMenu() according to selected role -->
            </ul>

            <!-- Controls & Active Role Switcher -->
            <div class="d-flex align-items-center gap-3">
                <a href="unauthorized.php?role=guest" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" title="Test Non-Admin 403 Forbidden Access Restricted Page">
                    <i class="bi bi-shield-slash"></i> Test 403 Restriction
                </a>

                <div class="d-flex align-items-center bg-dark bg-opacity-50 px-3 py-1 rounded-pill border border-secondary border-opacity-25">
                    <label class="text-secondary small me-2 mb-0 fw-semibold"><i class="bi bi-person-gear"></i> Role:</label>
                    <select id="roleSelector" class="form-select form-select-sm bg-dark text-white border-0 py-0" style="width: auto; cursor: pointer;" onchange="changeRole()">
                        <option value="admin" selected>Admin (Full Access)</option>
                        <option value="editor">Editor (Inventory & Content)</option>
                        <option value="user">User (View Only)</option>
                    </select>
                </div>

                <div class="text-end d-none d-md-block text-white">
                    <div class="fw-semibold small" id="currentUserLabel">Ansh Adodari</div>
                    <div class="text-secondary" style="font-size: 0.72rem;">25CS001 • <span id="currentRoleBadge" class="badge bg-success">ADMIN</span></div>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 py-4">

    <!-- Top Row: Dynamic Statistics Cards -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Registered Users -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Users</span>
                        <h3 class="fw-bold mb-0 mt-1" id="statUsers">-</h3>
                        <small class="text-success"><i class="bi bi-arrow-up-right"></i> All Active Accounts</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Registered Students -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Students Enrolled</span>
                        <h3 class="fw-bold mb-0 mt-1" id="statStudents">-</h3>
                        <small class="text-primary"><i class="bi bi-mortarboard-fill"></i> CSPIT Students</small>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Active Events -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Campus Events</span>
                        <h3 class="fw-bold mb-0 mt-1" id="statEvents">-</h3>
                        <small class="text-warning"><i class="bi bi-calendar2-check"></i> Upcoming Activities</small>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Total Audit Logs -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Audit Actions</span>
                        <h3 class="fw-bold mb-0 mt-1" id="statAudits">-</h3>
                        <small class="text-danger"><i class="bi bi-shield-check"></i> Security Verified</small>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-journal-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Two-Column Layout -->
    <div class="row g-4">
        
        <!-- Left Column: Inventory CRUD Management -->
        <div class="col-lg-7">
            <!-- Item Form Card (Create / Update) -->
            <div class="card card-custom mb-4" id="formCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span id="formTitle" class="d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle text-primary"></i> Add New Inventory Item
                    </span>
                    <span class="badge bg-light text-secondary border" id="roleActionIndicator">Role: Admin (Full Access)</span>
                </div>
                <div class="card-body">
                    <form id="itemForm" onsubmit="saveItem(event)">
                        <input type="hidden" id="itemId">
                        <div class="row g-2 mb-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold">Item Name *</label>
                                <input type="text" class="form-control form-control-sm" id="itemName" placeholder="e.g., Wireless Mechanical Keyboard" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Category *</label>
                                <input type="text" class="form-control form-control-sm" id="itemCategory" placeholder="e.g., Accessories, Electronics" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Price ($) *</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="itemPrice" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-3">
                                <i class="bi bi-check-lg me-1"></i> Save Item (Fetch API)
                            </button>
                            <button type="button" id="cancelBtn" class="btn btn-secondary btn-sm" onclick="resetForm()" style="display:none;">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </button>
                        </div>
                    </form>
                    <div id="statusAlert" class="mt-2" style="display:none;"></div>
                </div>
            </div>

            <!-- Inventory Items Table Card -->
            <div class="card card-custom">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-box-seam text-primary"></i> Inventory Items (Asynchronous Fetch CRUD)
                    </span>
                    <button class="btn btn-outline-primary btn-sm" onclick="loadItems()">
                        <i class="bi bi-arrow-repeat"></i> Reload
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th style="width: 140px;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Populated dynamically by loadItems() -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Latest Registrations & Audit Logs -->
        <div class="col-lg-5">
            
            <!-- Widget 1: Latest Student / User Registrations -->
            <div class="card card-custom mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-lines-fill text-success"></i> Latest Registrations
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success">Live Sync</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Role</th>
                                    <th>Registered</th>
                                </tr>
                            </thead>
                            <tbody id="latestUsersBody">
                                <!-- Injected via loadStats() -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Widget 2: Audit Log Proof (Recorded Admin Actions) -->
            <div class="card card-custom">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-danger"></i> System Audit Logs
                    </span>
                    <button class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="loadAuditLogs()">
                        <i class="bi bi-arrow-clockwise"></i> Sync
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table table-sm table-striped align-middle mb-0" style="font-size: 0.82rem;">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Time</th>
                                    <th>Action</th>
                                    <th>Actor</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody id="auditLogsBody">
                                <!-- Injected via loadAuditLogs() -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- JavaScript Fetch Logic -->
<script>
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
        badge.innerText = currentRole.toUpperCase();
        
        if (currentRole === 'admin') {
            badge.className = 'badge bg-success';
            document.getElementById('roleActionIndicator').innerText = 'Role: Admin (Full Access)';
            document.getElementById('formCard').style.display = 'block';
        } else if (currentRole === 'editor') {
            badge.className = 'badge bg-warning text-dark';
            document.getElementById('roleActionIndicator').innerText = 'Role: Editor (Create & Edit Only)';
            document.getElementById('formCard').style.display = 'block';
        } else {
            badge.className = 'badge bg-secondary';
            document.getElementById('roleActionIndicator').innerText = 'Role: User (Read-Only Access)';
            document.getElementById('formCard').style.display = 'none';
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
                document.getElementById('statUsers').innerText = result.stats.total_users;
                document.getElementById('statStudents').innerText = result.stats.total_students;
                document.getElementById('statEvents').innerText = result.stats.total_events;
                document.getElementById('statAudits').innerText = result.stats.total_audits;

                const usersTbody = document.getElementById('latestUsersBody');
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
        } catch (err) {
            console.error('Stats error:', err);
        }
    }

    // 4. Fetch All Items (Read)
    async function loadItems() {
        try {
            const res = await fetch(`${API_URL}?action=read`);
            const result = await res.json();
            const tbody = document.getElementById('itemsTableBody');
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
            console.error('Items fetch error:', err);
        }
    }

    // 5. Fetch Audit Logs
    async function loadAuditLogs() {
        try {
            const res = await fetch(`${API_URL}?action=get_audit_logs`);
            const result = await res.json();
            const tbody = document.getElementById('auditLogsBody');
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
            console.error('Audit logs fetch error:', err);
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

    // Helper: Display Alert Banner
    function showAlert(msg, type) {
        const alertBox = document.getElementById('statusAlert');
        alertBox.className = `alert alert-${type} py-1 px-3 small`;
        alertBox.innerText = msg;
        alertBox.style.display = 'block';
        setTimeout(() => { alertBox.style.display = 'none'; }, 3500);
    }

    // Helper: Escape HTML
    function escapeHtml(text) {
        return text ? text.replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m]) : '';
    }
</script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
