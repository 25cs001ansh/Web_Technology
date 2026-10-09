<?php

session_start();

// 1. Check if form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Direct access not allowed.");
}

// 2. Validate CSRF Token
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "CSRF Token validation failed!";
    header("Location: form.php");
    exit();
}

// 3. Sanitize Inputs
$name = htmlspecialchars(trim($_POST['name']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$mobile = htmlspecialchars(trim($_POST['mobile']));
$course = htmlspecialchars(trim($_POST['course']));

// 4. Server-Side Validation
if (empty($name) || empty($email) || empty($mobile) || empty($course)) {
    $_SESSION['error'] = "All fields are required!";
    header("Location: form.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid Email Address!";
    header("Location: form.php");
    exit();
}

// 5. Store Record in JSON File (Saved directly in project root)
$json_file = "submissions.json";
$new_student = [
    "name" => $name,
    "email" => $email,
    "mobile" => $mobile,
    "course" => $course,
    "date" => date("Y-m-d H:i:s")
];

$current_data = [];
if (file_exists($json_file)) {
    $json_content = file_get_contents($json_file);
    $current_data = json_decode($json_content, true) ?? [];
}

$current_data[] = $new_student;
file_put_contents($json_file, json_encode($current_data, JSON_PRETTY_PRINT));

// 6. Store Record in CSV File (Saved directly in project root)
$csv_file = "submissions.csv";
$is_new = !file_exists($csv_file);

$file_handle = fopen($csv_file, "a");
if ($is_new) {
    fputcsv($file_handle, ["Name", "Email", "Mobile", "Course", "Date"]);
}
fputcsv($file_handle, [$name, $email, $mobile, $course, date("Y-m-d H:i:s")]);
fclose($file_handle);

// Redirect with success message
$_SESSION['message'] = "Registration details for '$name' saved successfully!";
header("Location: form.php");
exit();
?>
