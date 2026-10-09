<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session timeout: 5 minutes (300 seconds). For faster testing, change to 30.
$timeout = 300;

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?error=Please login first.");
    exit;
}

if (
    isset($_SESSION["last_activity"]) &&
    (time() - $_SESSION["last_activity"] > $timeout)
) {
    // Clear session data
    $_SESSION = [];

    // Delete session cookie
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

    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit;
}

// Update last activity time
$_SESSION["last_activity"] = time();
?>
