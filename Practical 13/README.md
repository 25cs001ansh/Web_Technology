# Practical 13: PHP JSON API & AJAX/Fetch-Based CRUD (No Page Reload)

## 📌 Aim & Overview
A complete, asynchronous Single-Page Application (SPA) implementing full **CRUD** (Create, Read, Update, Delete) operations and dynamic **Role-Based Access Control (RBAC)** navigation using a RESTful PHP JSON backend and modern JavaScript `fetch()` API on the frontend without standard browser page reloads.

---

## 📋 Deliverables & Implemented Files

| Deliverable / File | Description & Technical Highlights | Status |
| :--- | :--- | :---: |
| [`schema.sql`](file:///d:/WDF/Practical%2013/schema.sql) | Database setup for `dashboard_db`, `menu_items` (with role restrictions), and `items` inventory table. | ✅ Verified |
| [`db.php`](file:///d:/WDF/Practical%2013/db.php) | Secure PDO connection to MySQL with UTF-8 character encoding and JSON error handling. | ✅ Verified |
| [`api.php`](file:///d:/WDF/Practical%2013/api.php) | RESTful JSON API handling `get_menu` (RBAC), `read`, `create`, `update`, and `delete` actions via prepared statements. | ✅ Verified |
| [`index.html`](file:///d:/WDF/Practical%2013/index.html) | Modern SPA frontend using vanilla CSS cards and modern async/await `fetch()` functions with zero page reloads. | ✅ Verified |
| [`Practical_13_Lab_Manual.docx`](file:///d:/WDF/Practical%2013/Practical_13_Lab_Manual.docx) | CHARUSAT Lab Manual report with all 10 high-fidelity framed browser screenshots embedded. | ✅ Generated |

---

## 🛠️ Key Technical Highlights

1. **Zero Page Reloads**: Handled via JavaScript `event.preventDefault()` on form submit events alongside asynchronous `async`/`await` `fetch()` HTTP calls.
2. **Backend JSON Parsing**: Standard POST requests with JSON payload headers (`Content-Type: application/json`) are parsed in PHP using `json_decode(file_get_contents('php://input'), true)`.
3. **Role-Based Access Control (RBAC)**: SQL query filtering (`WHERE min_role IN (...)`) dynamically dictates navigation menu availability based on active user role authorization (`user`, `editor`, `admin`).
4. **Prepared Statements**: All write/update/delete operations execute with parameterized PDO prepared statements (`$pdo->prepare()`) to prevent SQL Injection.

---

## 🌐 Localhost URL Endpoints
- **SPA Dashboard Frontend:** [http://localhost:8000/practical13/index.html](http://localhost:8000/practical13/index.html)
- **JSON API - Read Items:** [http://localhost:8000/practical13/api.php?action=read](http://localhost:8000/practical13/api.php?action=read)
- **JSON API - Dynamic Menu (Admin):** [http://localhost:8000/practical13/api.php?action=get_menu&role=admin](http://localhost:8000/practical13/api.php?action=get_menu&role=admin)
- **JSON API - Dynamic Menu (Editor):** [http://localhost:8000/practical13/api.php?action=get_menu&role=editor](http://localhost:8000/practical13/api.php?action=get_menu&role=editor)
- **JSON API - Dynamic Menu (User):** [http://localhost:8000/practical13/api.php?action=get_menu&role=user](http://localhost:8000/practical13/api.php?action=get_menu&role=user)
