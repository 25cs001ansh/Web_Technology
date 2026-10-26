<?php

http_response_code(403);

require_once 'db.php';

$role = $_GET['role'] ?? 'guest';
$user = $_GET['user'] ?? 'guest_user';
$ip   = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

// Record security audit log for forbidden access attempt
try {
    $stmt = $pdo->prepare("INSERT INTO audit_logs (username, role, action, details, ip_address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user, $role, 'SECURITY_403_BLOCKED', 'Attempted unauthorized navigation to System Settings / Admin Console', $ip]);
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden - Access Restricted | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            max-width: 650px;
            width: 100%;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .error-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .error-icon {
            font-size: 4rem;
            line-height: 1;
            margin-bottom: 12px;
            display: inline-block;
        }
        .badge-status {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .security-details {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px dashed #cbd5e1;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="error-card mx-auto">
        <div class="error-header">
            <div class="error-icon">
                <i class="bi bi-shield-x"></i>
            </div>
            <h1 class="h3 fw-bold mb-1">HTTP 403 - Forbidden</h1>
            <span class="badge-status">ACCESS RESTRICTED BY RBAC POLICY</span>
        </div>
        
        <div class="p-4 p-md-5">
            <h5 class="fw-semibold text-danger mb-2">
                <i class="bi bi-exclamation-octagon-fill me-2"></i>Non-Admin Access Restricted
            </h5>
            <p class="text-secondary mb-3">
                You do not have permission to view or manage this administrative resource. 
                The requested endpoint (<code>System Settings / Admin Management</code>) enforces 
                <strong>Role-Based Access Control (RBAC)</strong> and strictly requires the 
                <span class="badge bg-danger text-uppercase">admin</span> privilege level.
            </p>

            <div class="security-details">
                <div class="detail-row">
                    <span class="text-muted"><i class="bi bi-person-badge me-1"></i> Current User Role:</span>
                    <strong class="text-capitalize text-danger"><?php echo htmlspecialchars($role); ?></strong>
                </div>
                <div class="detail-row">
                    <span class="text-muted"><i class="bi bi-lock me-1"></i> Required Permission:</span>
                    <strong class="text-success">admin</strong>
                </div>
                <div class="detail-row">
                    <span class="text-muted"><i class="bi bi-hdd-network me-1"></i> Client IP Address:</span>
                    <code><?php echo htmlspecialchars($ip); ?></code>
                </div>
                <div class="detail-row">
                    <span class="text-muted"><i class="bi bi-journal-text me-1"></i> Security Event Status:</span>
                    <span class="badge bg-secondary">Logged to audit_logs</span>
                </div>
                <div class="detail-row">
                    <span class="text-muted"><i class="bi bi-clock-history me-1"></i> Timestamp:</span>
                    <span><?php echo date('Y-m-d H:i:s'); ?></span>
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                <a href="dashboard.php" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-arrow-left me-1"></i> Return to Dashboard
                </a>
                <a href="dashboard.php?role=admin" class="btn btn-outline-secondary">
                    <i class="bi bi-person-gear me-1"></i> Switch to Admin
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
