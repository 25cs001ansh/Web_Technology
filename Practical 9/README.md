# Practical 9: Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing

## 📌 Aim & Problem Definition
Implement secure user registration by inserting user details into MySQL using **MySQLi**. Validate input on frontend and backend, check for duplicate email/username, hash passwords using `password_hash()`, record registration audit logs, and display appropriate messages.

---

## 📁 Project Structure
```text
Practical 9 /
├── db.php                     # MySQLi connection file
├── register.php               # HTML5 registration form with CSRF token & validation UI
├── process_register.php       # Secure MySQLi backend processor (Prepared Statements, Hashing, Duplicate Check)
├── users.php                  # Registered users list & Audit log viewer
├── schema.sql                 # SQL database dump for MySQL import
├── Practical_9_Lab_Manual.docx# CHARUSAT Laboratory Manual report
└── README.md                  # Documentation & Viva Voce Q&A
```

---

## 🔑 Key Features & Technical Implementation

1. **MySQLi Connection (`db.php`)**:
   - Initializes MySQLi connection to `user_system` database with `utf8mb4` charset.

2. **Frontend & Backend Validation (`register.php`, `process_register.php`)**:
   - JavaScript validation checks matching password and min length 6.
   - Backend validation checks required fields, email format (`FILTER_VALIDATE_EMAIL`), and password confirmation.

3. **Duplicate Check via Prepared Statements**:
   - Queries `SELECT id FROM users WHERE username = ? OR email = ?` using `mysqli_stmt::bind_param()`.

4. **Password Hashing (`password_hash()`)**:
   - Hashes passwords using `password_hash($password, PASSWORD_DEFAULT)` before executing SQL insert.

5. **Advanced Extension (Audit Logs & CSRF)**:
   - Records registration event in `audit_logs` table (`user_id`, `action`, `details`, `ip_address`).
   - CSRF token validation on form submission.

---

## 🛠️ Setup & XAMPP Deployment

1. Start **Apache** and **MySQL** in XAMPP.
2. Import `schema.sql` into MySQL using phpMyAdmin or terminal.
3. Copy `Practical 9` folder to `D:\xampp\htdocs\practical9`.
4. Open in browser:
   - **Registration Form**: [http://localhost:8000/practical9/register.php](http://localhost:8000/practical9/register.php)
   - **Users & Audit Logs**: [http://localhost:8000/practical9/users.php](http://localhost:8000/practical9/users.php)

---

## 💡 Viva Voce Q&A Reference

| Viva Question | Technical Answer / Key Explanation |
| :--- | :--- |
| **Why is `password_hash()` preferred over `md5()`?** | `password_hash()` uses bcrypt/argon2 algorithms with automatic salting and configurable cost factors, making it resistant to rainbow tables and brute-force attacks. |
| **How does duplicate check work?** | Before inserting, a `SELECT` query checks if the entered email/username already exists in the database. |
| **Why use MySQLi Prepared Statements?** | Prepared statements separate SQL template logic from data parameters, eliminating SQL Injection vulnerabilities. |
| **What is the purpose of an Audit Log?** | Audit logs record user activity events (timestamp, action, IP) for security tracking and compliance. |
