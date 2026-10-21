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
├── Practical_10_Lab_Manual.docx # Laboratory manual submission report
└── README.md                  # Comprehensive Practical 10 documentation
```

---

## 6. Implementation Code Files

### 7. Database Connection – `db.php`
```php
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "user_registration";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
```

### 8. Login Page – `login.php`
```php
<?php
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Secure Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Secure Login</h2>
    <?php
    if (isset($_GET["error"])) {
        echo "<p class='error'>" . htmlspecialchars($_GET["error"]) . "</p>";
    }
    if (isset($_GET["message"])) {
        echo "<p class='success'>" . htmlspecialchars($_GET["message"]) . "</p>";
    }
    ?>
    <form action="authenticate.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <div style="margin-top: 12px; display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" name="remember_me" id="remember_me" style="width: auto; margin-top: 0;">
            <label for="remember_me" style="margin-top: 0; display: inline;">Remember Me</label>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
```

### 9. Authenticate User – `authenticate.php`
```php
<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$remember_me = isset($_POST["remember_me"]);

if ($email === "" || $password === "") {
    header("Location: login.php?error=All fields are required.");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?error=Invalid email address.");
    exit;
}

$stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    $conn->close();
    header("Location: login.php?error=Invalid email or password.");
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user["password"])) {
    $stmt->close();
    $conn->close();
    header("Location: login.php?error=Invalid email or password.");
    exit;
}

// Regenerate session ID after successful login
session_regenerate_id(true);

$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["username"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];
$_SESSION["last_activity"] = time();

// Intermediate Extension: Last login timestamp
$update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
if ($update) {
    $update->bind_param("i", $user["id"]);
    $update->execute();
    $update->close();
}

// Advanced Extension: Remember-Me token
if ($remember_me) {
    $raw_token = bin2hex(random_bytes(32));
    $token_hash = hash("sha256", $raw_token);
    $expiry = date("Y-m-d H:i:s", time() + (30 * 24 * 60 * 60));

    $rem_stmt = $conn->prepare("UPDATE users SET remember_token_hash = ?, remember_token_expiry = ? WHERE id = ?");
    if ($rem_stmt) {
        $rem_stmt->bind_param("ssi", $token_hash, $expiry, $user["id"]);
        $rem_stmt->execute();
        $rem_stmt->close();
    }

    setcookie("remember_token", $raw_token, [
        "expires" => time() + (30 * 24 * 60 * 60),
        "path" => "/",
        "secure" => false,
        "httponly" => true,
        "samesite" => "Lax"
    ]);
}

$stmt->close();
$conn->close();

header("Location: dashboard.php");
exit;
?>
```

### 12. Main Dashboard – `dashboard.php`
```php
<?php
session_start();
$timeout = 300;

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?error=Please login first.");
    exit;
}

if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit;
}

$_SESSION["last_activity"] = time();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    <p>Your role: <strong><?php echo htmlspecialchars($_SESSION["role"]); ?></strong></p>
    <p style="font-size: 13px; color: #475569; word-break: break-all;">
        Active Session ID: <code><?php echo session_id(); ?></code>
    </p>
    <?php if ($_SESSION["role"] === "admin"): ?>
        <h3>Admin Dashboard</h3>
        <p>Welcome to the Admin Dashboard.</p>
        <a href="admin_dashboard.php">Go to Admin Dashboard</a>
    <?php elseif ($_SESSION["role"] === "student"): ?>
        <h3>Student Dashboard</h3>
        <p>Welcome to the Student Dashboard.</p>
        <a href="student_dashboard.php">Go to Student Dashboard</a>
    <?php endif; ?>
    <br><br>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
```

### 13. Admin Dashboard – `admin_dashboard.php`
```php
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?error=Please login first.");
    exit;
}
if ($_SESSION["role"] !== "admin") {
    http_response_code(403);
    die("Unauthorized Access: Admins only.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin!</p>
    <p>You have access to administrator features.</p>
    <a href="dashboard.php">Back to Dashboard</a>
    <br><br>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
```

### 14. Student Dashboard – `student_dashboard.php`
```php
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?error=Please login first.");
    exit;
}
if ($_SESSION["role"] !== "student") {
    http_response_code(403);
    die("Unauthorized Access: Students only.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Student Dashboard</h2>
    <p>Welcome, Student!</p>
    <p>You have access to student features.</p>
    <a href="dashboard.php">Back to Dashboard</a>
    <br><br>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
```

### 15. Secure Logout – `logout.php`
```php
<?php
session_start();

// Step 1: Clear session variables
$_SESSION = [];

// Step 2: Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Step 3: Destroy session
session_destroy();
header("Location: login.php?message=You have been logged out successfully.");
exit;
?>
```

### 17. CSS Stylesheet – `style.css`
```css
body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
}
.container {
    width: 400px;
    margin: 50px auto;
    background-color: white;
    padding: 25px;
    border-radius: 8px;
}
h2 {
    text-align: center;
}
label {
    display: block;
    margin-top: 12px;
}
input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    box-sizing: border-box;
}
button {
    width: 100%;
    padding: 10px;
    margin-top: 20px;
}
a {
    display: inline-block;
    margin-top: 10px;
}
.error {
    color: red;
}
.success {
    color: green;
}
```

---

## 34. Complete Testing Checklist & Results

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
