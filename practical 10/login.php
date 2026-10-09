<?php
session_start();
require_once "db.php";

// Auto-login via Remember-Me token if session not active
if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_token"])) {
    $raw_token = $_COOKIE["remember_token"];
    $token_hash = hash("sha256", $raw_token);

    $stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE remember_token_hash = ? AND remember_token_expiry > NOW()");
    if ($stmt) {
        $stmt->bind_param("s", $token_hash);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["last_activity"] = time();
            header("Location: dashboard.php");
            exit;
        }
        $stmt->close();
    }
}

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
<input
type="email"
name="email"
required
>
<label>Password:</label>
<input
type="password"
name="password"
required
>
<div style="margin-top: 12px; display: flex; align-items: center; gap: 8px;">
<input type="checkbox" name="remember_me" id="remember_me" style="width: auto; margin-top: 0;">
<label for="remember_me" style="margin-top: 0; display: inline;">Remember Me</label>
</div>
<button type="submit">Login</button>
</form>
</div>
</body>
</html>
