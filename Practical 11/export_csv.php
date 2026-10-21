<?php
include 'db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export.csv');

$output = fopen('php://output', 'w');

// Output column headers
fputcsv($output, ['ID', 'Name', 'Email', 'Course', 'Gender', 'Status', 'Registration Date']);

// Fetch all student records
$query = "SELECT id, name, email, course, gender, status, created_at FROM students ORDER BY id ASC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
