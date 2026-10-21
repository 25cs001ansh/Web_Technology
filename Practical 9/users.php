<?php
require_once "db.php";
$result = $conn->query("SELECT id, username, email, password, created_at FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>MySQL Database - Users Table</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8fafc; padding: 30px; }
        .box { max-width: 900px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        h2 { margin-top: 0; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px 14px; text-align: left; font-size: 14px; }
        th { background: #0f172a; color: white; }
        tr:nth-child(even) { background: #f1f5f9; }
        .hash { font-family: Consolas, monospace; color: #0284c7; font-size: 12px; word-break: break-all; }
        .badge { background: #10b981; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Database Table: <code>user_registration.users</code> <span class="badge">MySQL Database</span></h2>
    <p>Below records show that passwords are securely encrypted using <strong>password_hash() (BCRYPT)</strong> and inserted into the database:</p>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Password (Hashed format)</th>
            <th>Created At</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><span class="hash"><?php echo htmlspecialchars($row['password']); ?></span></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No users found in database.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
