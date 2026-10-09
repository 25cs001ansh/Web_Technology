<?php
// Practical 8: Database Connection & Prepared Statement Data Retrieval Test
require_once "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 8 - Database Connection & Prepared Statements Test</title>
    <style>
        :root {
            --primary: #2563eb;
            --success: #10b981;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 30px 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 24px 30px;
            border-radius: 14px;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
        }
        .header-card h1 {
            margin: 0 0 8px 0;
            font-size: 24px;
        }
        .header-card p {
            margin: 0;
            color: #94a3b8;
            font-size: 14px;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            margin-top: 12px;
            border: 1px solid rgba(52, 211, 153, 0.3);
        }
        .section-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .section-card h2 {
            margin: 0 0 16px 0;
            font-size: 18px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .badge-count {
            background: #eff6ff;
            color: var(--primary);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        tr:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-card">
            <h1>Practical 8: StudentHub PDO Database Verification</h1>
            <p>Prepared Statements Execution & 3NF Relational Database Querying</p>
            <div class="status-badge">
                <span>●</span> PDO Connection Active & Verified (try-catch error handling)
            </div>
        </div>

        <?php
        try {
            // 1. Fetch Students using Prepared Statement
            $stmt = $pdo->prepare("SELECT * FROM students");
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Fetch Events using Prepared Statement
            $stmtEvt = $pdo->prepare("SELECT * FROM events");
            $stmtEvt->execute();
            $events = $stmtEvt->fetchAll(PDO::FETCH_ASSOC);

            // 3. Fetch Registrations JOIN Query using Prepared Statement
            $sqlReg = "SELECT r.registration_id, s.name AS student_name, s.email, e.event_name, e.venue, e.event_date 
                       FROM registrations r 
                       JOIN students s ON r.student_id = s.student_id 
                       JOIN events e ON r.event_id = e.event_id";
            $stmtReg = $pdo->prepare($sqlReg);
            $stmtReg->execute();
            $registrations = $stmtReg->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Table 1: Students -->
        <div class="section-card">
            <h2>
                <span>1. Registered Students Table (3NF Table: `students`)</span>
                <span class="badge-count"><?php echo count($students); ?> Records Found</span>
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><strong>#<?php echo htmlspecialchars($student['student_id']); ?></strong></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['course']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Table 2: Events -->
        <div class="section-card">
            <h2>
                <span>2. Event Catalog (3NF Table: `events`)</span>
                <span class="badge-count"><?php echo count($events); ?> Events Found</span>
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>Event ID</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Venue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $evt): ?>
                    <tr>
                        <td><strong>#<?php echo htmlspecialchars($evt['event_id']); ?></strong></td>
                        <td><?php echo htmlspecialchars($evt['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($evt['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($evt['venue']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Table 3: Registrations JOIN Query -->
        <div class="section-card">
            <h2>
                <span>3. Relational Event Registrations (3NF Table: `registrations` with Prepared JOIN Query)</span>
                <span class="badge-count"><?php echo count($registrations); ?> Registrations</span>
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>Reg ID</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Registered Event</th>
                        <th>Venue</th>
                        <th>Event Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrations as $reg): ?>
                    <tr>
                        <td><strong>REG-<?php echo htmlspecialchars($reg['registration_id']); ?></strong></td>
                        <td><?php echo htmlspecialchars($reg['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($reg['email']); ?></td>
                        <td><span style="color: #2563eb; font-weight:600;"><?php echo htmlspecialchars($reg['event_name']); ?></span></td>
                        <td><?php echo htmlspecialchars($reg['venue']); ?></td>
                        <td><?php echo htmlspecialchars($reg['event_date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php
        } catch (PDOException $e) {
            echo "<div style='color:red; padding:20px; background:#fee2e2; border-radius:8px;'>";
            echo "<h3>PDO Query Execution Error:</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
        }
        ?>
    </div>
</body>
</html>
