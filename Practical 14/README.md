# Practical 14: Admin Dashboard with RBAC & Fetch-Based CRUD

An enterprise-grade, single-page Admin Dashboard built with **PHP 8**, **MySQL**, **Bootstrap 5**, and modern **JavaScript Fetch API (async/await)**.

---

## 🌟 Key Features

1. **Role-Based Access Control (RBAC):**
   - Dynamic Navigation Bar loaded according to authorization level (*Admin*, *Editor*, *User*).
   - SQL-level restriction using PDO prepared statements (`WHERE min_role IN (...)`).
   - Server-side security enforcement with custom **HTTP 403 Forbidden** restricted page for non-admin accounts.

2. **Real-Time Dynamic KPI Statistics:**
   - Aggregated metrics cards for **Total Users**, **Enrolled Students**, **Active Events**, and **Audit Actions**.
   - Live **Latest Student / User Registrations** feed with role badges.

3. **Asynchronous CRUD (Zero Page Reloads):**
   - Full **Create, Read, Update, and Delete** operations for inventory items via modern JavaScript `fetch()`.
   - Client form interception using `event.preventDefault()` with real-time DOM updates and non-blocking toast alerts.

4. **Persistent System Audit Trail:**
   - Dedicated `audit_logs` table tracking critical administrative operations (`CREATE_ITEM`, `UPDATE_ITEM`, `DELETE_ITEM`, `SECURITY_403_BLOCKED`, `SYSTEM_INIT`).
   - Automatically logs actor username, role, action code, details, client IP address, and timestamp.

---

## 📁 File Structure

```text
Practical 14/
├── api.php             # RESTful JSON API handling RBAC menu, stats, CRUD, and audit logging
├── dashboard.php       # Responsive Bootstrap 5 Admin Dashboard UI
├── db.php              # Safe PDO database connection with error handling
├── schema.sql          # Complete MySQL schema and seed data (users, events, items, menus, audit_logs)
├── script.js           # Client-side asynchronous fetch API and DOM manipulation logic
├── unauthorized.php    # HTTP 403 Forbidden Access Restricted error page
├── charusat_logo.jpg   # University logo asset
└── cspit_logo.jpg      # Institute logo asset
```

---

## 🚀 Setup & Execution

1. **Database Setup:**
   - Import `schema.sql` into your MySQL/MariaDB server:
     ```bash
     mysql -u root -p < schema.sql
     ```
   - This creates database `dashboard_db` with all tables and seed records.

2. **Run Locally:**
   - Place files inside your web server directory (e.g., `xampp/htdocs/practical14/`).
   - Access the dashboard in your browser:
     ```text
     http://localhost:8000/practical14/dashboard.php
     ```

3. **Test RBAC & 403 Security:**
   - Switch roles using the top navbar selector (*Admin*, *Editor*, *User*).
   - Access restricted console:
     ```text
     http://localhost:8000/practical14/unauthorized.php?role=editor
     ```
