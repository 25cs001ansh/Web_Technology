<?php

$host = "localhost";
$dbname = "studenthub";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Output success message if script is accessed directly
    if (basename($_SERVER['SCRIPT_FILENAME']) === 'db.php') {
        echo "<!DOCTYPE html><html><head><title>PDO DB Connection</title>";
        echo "<style>body{font-family:'Segoe UI',sans-serif;background:#f4f6f9;padding:40px;display:flex;justify-content:center;align-items:center;height:80vh;}";
        echo ".card{background:#fff;padding:30px 40px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.1);text-align:center;}";
        echo ".badge{display:inline-block;padding:8px 16px;background:#10b981;color:#fff;border-radius:20px;font-weight:600;font-size:14px;margin-bottom:15px;}";
        echo "h2{color:#1e293b;margin:0 0 10px;}</style></head><body>";
        echo "<div class='card'>";
        echo "<div class='badge'>✓ PDO Connection Active</div>";
        echo "<h2>Database Connected Successfully!</h2>";
        echo "<p style='color:#64748b;'>Connected to MySQL Database: <strong>studenthub</strong> via PDO with try-catch error handling.</p>";
        echo "</div></body></html>";
    }
} catch (PDOException $e) {
    if (basename($_SERVER['SCRIPT_FILENAME']) === 'db.php') {
        echo "<!DOCTYPE html><html><head><title>DB Error</title></head><body>";
        echo "<div style='color:#ef4444;padding:20px;background:#fee2e2;border-radius:8px;'>";
        echo "<h3>Connection Failed:</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div></body></html>";
    } else {
        die("Connection Failed: " . $e->getMessage());
    }
}
?>
