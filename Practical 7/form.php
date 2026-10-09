<?php
session_start();

// Generate CSRF Token for security
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrf_token = $_SESSION['csrf_token'];

$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Practical 7 - Student Registration Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f4; }
        .form-box { background: #fff; padding: 25px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .input-field { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="email"], select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; width: 100%; font-size: 16px; }
        button:hover { background: #0056b3; }
        .success { color: green; font-weight: bold; margin-bottom: 15px; }
        .danger { color: red; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Student Registration Form</h2>

    <?php if ($message): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="process.php" method="POST">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="input-field">
            <label>Full Name:</label>
            <input type="text" name="name" required placeholder="e.g. Ansh Adodariya">
        </div>

        <div class="input-field">
            <label>Email Address:</label>
            <input type="email" name="email" required placeholder="e.g. anshadodariya1@gmail.com">
        </div>

        <div class="input-field">
            <label>Mobile Number:</label>
            <input type="text" name="mobile" required placeholder="e.g. 9876543210">
        </div>

        <div class="input-field">
            <label>Course:</label>
            <select name="course" required>
                <option value="">Select Course</option>
                <option value="Computer Science Engineering">Computer Science Engineering</option>
                <option value="Information Technology">Information Technology</option>
                <option value="Artificial Intelligence & Data Science">Artificial Intelligence & Data Science</option>
                <option value="Cyber Security">Cyber Security</option>
                <option value="Electrical Engineering">Electrical Engineering</option>
                <option value="Mechanical Engineering">Mechanical Engineering</option>
                <option value="Civil Engineering">Civil Engineering</option>
            </select>
        </div>

        <button type="submit">Submit & Save Record</button>
    </form>

    <br>
    <a href="display.php">View Stored Records (CSV/JSON) &rarr;</a>
</div>

</body>
</html>