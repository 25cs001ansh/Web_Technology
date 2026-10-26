<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'db.php';

// Helper Function: Record Admin & System Action into audit_logs table
function log_audit_action($pdo, $username, $role, $action, $details) {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = $pdo->prepare("INSERT INTO audit_logs (username, role, action, details, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $role, $action, $details, $ip]);
    } catch (Exception $e) {
        // Silent catch for audit logging
    }
}

$action = $_GET['action'] ?? '';
$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$actor  = $_GET['user'] ?? 'ansh_admin';
$role   = $_GET['role'] ?? 'admin';

switch ($action) {

    // -------------------------------------------------------------
    // 1. DYNAMIC MENU WITH ROLE-BASED ACCESS CONTROL (RBAC)
    // -------------------------------------------------------------
    case 'get_menu':
        $requestedRole = $_GET['role'] ?? 'user';
        
        // RBAC Role Hierarchy
        $rolesAllowed = ['user'];
        if ($requestedRole === 'editor') {
            $rolesAllowed = ['user', 'editor'];
        } elseif ($requestedRole === 'admin') {
            $rolesAllowed = ['user', 'editor', 'admin'];
        }
        
        $placeholders = implode(',', array_fill(0, count($rolesAllowed), '?'));
        $stmt = $pdo->prepare("SELECT title, link FROM menu_items WHERE min_role IN ($placeholders)");
        $stmt->execute($rolesAllowed);
        
        echo json_encode([
            'status' => 'success',
            'role'   => $requestedRole,
            'data'   => $stmt->fetchAll()
        ]);
        break;

    // -------------------------------------------------------------
    // 2. DYNAMIC DASHBOARD STATS & LATEST REGISTRATIONS
    // -------------------------------------------------------------
    case 'get_stats':
        $userCount    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $studentCount = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 'student'")->fetchColumn();
        $eventCount   = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
        $itemCount    = $pdo->query("SELECT COUNT(*) FROM items")->fetchColumn();
        $auditCount   = $pdo->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn();

        $latestUsersStmt = $pdo->query("SELECT id, username, email, role, user_type, status, created_at FROM users ORDER BY id DESC LIMIT 5");
        $latestUsers = $latestUsersStmt->fetchAll();

        echo json_encode([
            'status' => 'success',
            'stats'  => [
                'total_users'    => (int)$userCount,
                'total_students' => (int)$studentCount,
                'total_events'   => (int)$eventCount,
                'total_items'    => (int)$itemCount,
                'total_audits'   => (int)$auditCount
            ],
            'latest_registrations' => $latestUsers
        ]);
        break;

    // -------------------------------------------------------------
    // 3. CRUD: READ (Fetch All Items)
    // -------------------------------------------------------------
    case 'read':
        $stmt = $pdo->query("SELECT * FROM items ORDER BY id DESC");
        echo json_encode([
            'status' => 'success',
            'data'   => $stmt->fetchAll()
        ]);
        break;

    // -------------------------------------------------------------
    // 4. CRUD: CREATE (Add New Inventory Item with Audit Log)
    // -------------------------------------------------------------
    case 'create':
        if (!empty($input['name']) && !empty($input['category']) && isset($input['price'])) {
            $stmt = $pdo->prepare("INSERT INTO items (name, category, price) VALUES (?, ?, ?)");
            $stmt->execute([$input['name'], $input['category'], $input['price']]);
            $newId = $pdo->lastInsertId();

            // Record audit log
            log_audit_action($pdo, $actor, $role, 'CREATE_ITEM', "Created item ID #$newId: {$input['name']} (Cat: {$input['category']}, Price: \${$input['price']})");

            echo json_encode([
                'status'  => 'success',
                'message' => 'Item created successfully',
                'id'      => $newId
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        }
        break;

    // -------------------------------------------------------------
    // 5. CRUD: UPDATE (Modify Item with Audit Log)
    // -------------------------------------------------------------
    case 'update':
        if (!empty($input['id']) && !empty($input['name']) && !empty($input['category']) && isset($input['price'])) {
            $stmt = $pdo->prepare("UPDATE items SET name = ?, category = ?, price = ? WHERE id = ?");
            $stmt->execute([$input['name'], $input['category'], $input['price'], $input['id']]);

            // Record audit log
            log_audit_action($pdo, $actor, $role, 'UPDATE_ITEM', "Updated item ID #{$input['id']}: {$input['name']} (Price: \${$input['price']})");

            echo json_encode([
                'status'  => 'success',
                'message' => 'Item updated successfully'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid data provided']);
        }
        break;

    // -------------------------------------------------------------
    // 6. CRUD: DELETE (Remove Item with Audit Log)
    // -------------------------------------------------------------
    case 'delete':
        if (!empty($input['id'])) {
            // Fetch name for detailed audit
            $fetchStmt = $pdo->prepare("SELECT name FROM items WHERE id = ?");
            $fetchStmt->execute([$input['id']]);
            $item = $fetchStmt->fetch();
            $itemName = $item['name'] ?? 'Unknown Item';

            $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
            $stmt->execute([$input['id']]);

            // Record audit log
            log_audit_action($pdo, $actor, $role, 'DELETE_ITEM', "Deleted item ID #{$input['id']} ($itemName)");

            echo json_encode([
                'status'  => 'success',
                'message' => 'Item deleted successfully'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Item ID required']);
        }
        break;

    // -------------------------------------------------------------
    // 7. AUDIT LOGS: Fetch Recorded Admin Actions
    // -------------------------------------------------------------
    case 'get_audit_logs':
        $stmt = $pdo->query("SELECT * FROM audit_logs ORDER BY id DESC LIMIT 10");
        echo json_encode([
            'status' => 'success',
            'data'   => $stmt->fetchAll()
        ]);
        break;

    // -------------------------------------------------------------
    // 8. SECURITY / RBAC VERIFICATION CHECK
    // -------------------------------------------------------------
    case 'check_admin_access':
        $userRole = $_GET['role'] ?? 'user';
        if ($userRole !== 'admin') {
            http_response_code(403);
            log_audit_action($pdo, $actor, $userRole, 'ACCESS_DENIED_403', "Blocked unauthorized access attempt to admin settings");
            echo json_encode([
                'status'  => 'forbidden',
                'code'    => 403,
                'message' => 'Access Restricted: You do not have administrator privileges.'
            ]);
        } else {
            echo json_encode([
                'status'  => 'granted',
                'code'    => 200,
                'message' => 'Access Granted: Administrator credentials confirmed.'
            ]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action parameter']);
        break;
}
?>
