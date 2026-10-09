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

// Backend validation
if ($email === "" || $password === "") {
    header("Location: login.php?error=All fields are required.");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?error=Invalid email address.");
    exit;
}

// Find user
$stmt = $conn->prepare(
    "SELECT id, username, email, password, role
    FROM users
    WHERE email = ?"
);
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

// Verify password
if (!password_verify($password, $user["password"])) {
    $stmt->close();
    $conn->close();
    header("Location: login.php?error=Invalid email or password.");
    exit;
}

// Regenerate session ID after successful login
session_regenerate_id(true);

// Store user information in session
$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["username"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];
$_SESSION["last_activity"] = time();

// Update last login timestamp (Section 28)
$update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
if ($update) {
    $update->bind_param("i", $user["id"]);
    $update->execute();
    $update->close();
}

// Handle Remember-Me (Section 29-32)
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

    setcookie(
        "remember_token",
        $raw_token,
        [
            "expires" => time() + (30 * 24 * 60 * 60),
            "path" => "/",
            "secure" => false,
            "httponly" => true,
            "samesite" => "Lax"
        ]
    );
}

$stmt->close();
$conn->close();

// Redirect to common dashboard
header("Location: dashboard.php");
exit;
?>
