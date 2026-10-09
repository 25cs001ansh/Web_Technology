<?php

require_once "db.php";

echo "<br>";

$name = "Neha";
$email = "neha@gmail.com";
$course = "Computer";

// SQL query with positional placeholders (?)
$sql = "INSERT INTO students (name, email, course) VALUES (?, ?, ?)";

// Prepare statement to prevent SQL Injection
$stmt = $pdo->prepare($sql);

// Execute statement with parameter array
$stmt->execute([
    $name,
    $email,
    $course
]);

echo "<br>Student Added Successfully";
?>
