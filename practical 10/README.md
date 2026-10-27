# Practical 10: Secure Login/Logout with Sessions, Role-Based Access, Timeout, and Remember-Me Token

## 1. Objective
Implement a secure login/logout system using PHP sessions with:
- Password verification using `password_verify()`
- Secure session management
- Session ID regeneration after login (`session_regenerate_id(true)`)
- Role-based access control (RBAC)
- Admin and Student dashboards
- Session timeout handling
- Secure logout
- Unauthorized access protection
- Optional Remember-Me functionality using secure cryptographic tokens

---

## 2. Software Requirements
- XAMPP (Apache & MySQL)
- PHP (v8.x+)
- VS Code
- Web Browser & Browser Developer Tools

---

## 3. Prerequisite & Database Structure
This practical uses the `users` table created in Practical 9 within the `user_registration` database.
Execute the following to ensure all required fields are present:

```sql
ALTER TABLE users ADD role VARCHAR(20) NOT NULL DEFAULT 'student';
ALTER TABLE users ADD last_login DATETIME NULL;
ALTER TABLE users ADD remember_token_hash VARCHAR(255) NULL;
ALTER TABLE users ADD remember_token_expiry DATETIME NULL;
```

### Table Structure (`users` table)
| Field | Type | Purpose |
| :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | Unique user identifier (Primary Key) |
| `username` | VARCHAR(50) UNIQUE | Unique username |
| `email` | VARCHAR(100) UNIQUE | Registered email address |
| `password` | VARCHAR(255) | Hashed password generated via `password_hash()` |
| `role` | VARCHAR(20) | User role (`student` or `admin`) |
| `last_login` | DATETIME | Timestamp of last successful login |
| `remember_token_hash` | VARCHAR(255) | SHA-256 hash of remember-me token |
| `remember_token_expiry`| DATETIME | Expiration timestamp of remember-me token |
| `created_at` | TIMESTAMP | Account registration timestamp |

---

## 4. Test Accounts Setup (Hashed Passwords)
Both test accounts use the password: `Password@123` (or `123456`) hashed securely:
- **Student User:** `student@gmail.com` / `student01@gmail.com` | Role: `student`
- **Admin User:** `admin@gmail.com` / `admin01@gmail.com` | Role: `admin`

---

## 5. Project Directory Structure
```text
practical10/
│
├── db.php                     # MySQL database connection using MySQLi
├── login.php                  # Login form with email, password, and remember-me fields
├── authenticate.php           # Login authentication with password_verify() and session_regenerate_id()
├── dashboard.php              # Main dashboard with session validation & 5-minute timeout
├── admin_dashboard.php        # Dashboard accessible only to Admin users (RBAC)
├── student_dashboard.php      # Dashboard accessible only to Student users (RBAC)
├── logout.php                 # Secure logout and session destruction
├── timeout.php                # Session timeout handling logic
├── style.css                  # CSS file for styling
├── user_registration.sql      # Exported MySQL database dump with users and roles
└── README.md                  # Comprehensive Practical 10 documentation


## 6. Complete Testing Checklist & Results

| Test Case | Condition / Action | Expected Result | Status |
| :---: | :--- | :--- | :---: |
| **1** | Correct student login (`student@gmail.com`) | Student Dashboard with `role: student` | ✅ PASS |
| **2** | Correct admin login (`admin@gmail.com`) | Admin Dashboard with `role: admin` | ✅ PASS |
| **3** | Wrong password / Wrong email | `Invalid email or password.` | ✅ PASS |
| **4** | Student opens Admin Dashboard | `Unauthorized Access: Admins only.` (403) | ✅ PASS |
| **5** | Admin opens Student Dashboard | `Unauthorized Access: Students only.` (403) | ✅ PASS |
| **6** | Logout action | Session destroyed & redirected with logout message | ✅ PASS |
| **7** | Dashboard access after logout | `Please login first.` | ✅ PASS |
| **8** | Session inactivity timeout (5 mins) | `Session expired. Please login again.` | ✅ PASS |
| **9** | Session ID regeneration on login | New `session_id()` generated via `session_regenerate_id(true)` | ✅ PASS |
| **10** | Password verification | `password_verify()` safely checks BCRYPT hashes | ✅ PASS |
