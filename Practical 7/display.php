<?php
// Practical 7: Display Stored Records from JSON and CSV Files

$json_file = "submissions.json";
$students = [];

if (file_exists($json_file)) {
    $content = file_get_contents($json_file);
    $students = json_decode($content, true) ?? [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stored Student Records</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f4; }
        .table-box { background: #fff; padding: 25px; max-width: 700px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div class="table-box">
    <h2>Stored Student Records (JSON/CSV)</h2>

    <?php if (empty($students)): ?>
        <p>No records found.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Course</th>
            </tr>
            <?php foreach ($students as $index => $s): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                    <td><?php echo htmlspecialchars($s['email']); ?></td>
                    <td><?php echo htmlspecialchars($s['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($s['course']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="form.php">&larr; Back to Registration Form</a>
</div>

</body>
</html>
