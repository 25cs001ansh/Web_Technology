# Practical 10: Secure Session Management & Role-Based Authentication System

## 📌 Aim & Overview
To implement a secure user login, session management, and role-based access control system in **PHP & MySQL**, incorporating session timeout, session regeneration, remember-me functionality, and secure logout mechanisms.

---

## 📋 Practical 10 Submission Checklist & Features

| Feature / Requirement | Implementation File | Details & Technical Highlights | Status |
| :--- | :--- | :--- | :---: |
| **Database Connection** | [`db.php`](file:///d:/WDF/practical%2010/db.php) | Connects to `user_registration` / `user_system` using MySQLi with `utf8mb4` charset. | ✅ Verified |
| **Login Interface** | [`login.php`](file:///d:/WDF/practical%2010/login.php) | Responsive HTML5 form with Remember-Me checkbox and URL query error display. | ✅ Verified |
| **Authentication Logic** | [`authenticate.php`](file:///d:/WDF/practical%2010/authenticate.php) | Verifies password hash (`password_verify`), regenerates session ID (`session_regenerate_id(true)`), updates `last_login`, and manages Remember-Me tokens. | ✅ Verified |
| **Role-Based Dashboard** | [`dashboard.php`](file:///d:/WDF/practical%2010/dashboard.php) | Restricts unauthenticated access (`!isset($_SESSION['user_id'])`), checks automatic 5-minute inactivity timeout, and routes users to role dashboards. | ✅ Verified |
| **Admin Dashboard** | [`admin_dashboard.php`](file:///d:/WDF/practical%2010/admin_dashboard.php) | Exclusive dashboard view for users with `admin` role. | ✅ Verified |
| **Student Dashboard** | [`student_dashboard.php`](file:///d:/WDF/practical%2010/student_dashboard.php) | Exclusive dashboard view for users with `student` role. | ✅ Verified |
| **Session Inactivity Timeout** | [`timeout.php`](file:///d:/WDF/practical%2010/timeout.php) | Auto-destroys session after 300 seconds (5 minutes) of user inactivity. | ✅ Verified |
| **Secure Logout** | [`logout.php`](file:///d:/WDF/practical%2010/logout.php) | Clears session arrays (`$_SESSION = []`), expires session cookies, removes remember token, and calls `session_destroy()`. | ✅ Verified |

---

## 📁 Project Directory Structure
```text
practical 10 /
├── db.php                     # MySQLi Database Connection script
├── login.php                  # User Login Form interface
├── authenticate.php           # Credential validation, password verification & session initiation
├── dashboard.php              # Central user dashboard with session & timeout checks
├── admin_dashboard.php        # Admin role-restricted dashboard view
├── student_dashboard.php      # Student role-restricted dashboard view
├── timeout.php                # Session inactivity helper script
├── logout.php                 # Secure session destruction and cookie cleanup
├── style.css                  # UI styling for login & dashboard interfaces
└── README.md                  # Comprehensive Practical 10 documentation & checklist
```

---

## 🔒 Security Best Practices Implemented

1. **Session Fixation Prevention (`session_regenerate_id(true)`):**
   Regenerates the session ID immediately upon successful authentication to invalidate old session identifiers.

2. **Password Hash Verification (`password_verify()`):**
   Verifies user-submitted plain text passwords against BCRYPT hashes stored in MySQL.

3. **Inactivity Session Timeout (300 Seconds):**
   Automatically invalidates sessions if no activity occurs within 5 minutes (`time() - $_SESSION['last_activity'] > 300`).

4. **Secure Logout & Cookie Cleanup:**
   Unsets `$_SESSION`, destroys session data, and expires session cookies (`setcookie(session_name(), '', time() - 42000)`).

5. **Remember-Me Cryptographic Token:**
   Uses cryptographically secure random tokens (`bin2hex(random_bytes(32))`) with SHA-256 hash storage in database and `HttpOnly` cookies.

---

## 🌐 Localhost Endpoints & Testing

| Endpoint URL | Description | Test Condition / Credentials |
| :--- | :--- | :--- |
| `http://localhost:8000/practical10/login.php` | Login Form | Render login UI with email/password fields |
| `http://localhost:8000/practical10/dashboard.php` | User Dashboard | Redirects to `login.php` if not logged in |
| `http://localhost:8000/practical10/logout.php` | Logout Action | Destroys session and redirects to `login.php` |
