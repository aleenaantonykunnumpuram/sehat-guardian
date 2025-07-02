<?php
session_start();
require_once("../includes/db_connect.php");

$message = "";

// Only allow admins to access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_admin.php");
    exit();
}

if (isset($_POST['add_patient'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gender = $_POST['gender'];
    $medical_condition = $_POST['medical_condition'];
    $role = 'patient';
    $registered_by = $_SESSION['user_id'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Email already exists.";
    } else {
        // Insert patient into unified table
        $stmt = $conn->prepare("INSERT INTO users 
            (name, email, password, role, gender, medical_condition, registered_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $name, $email, $password, $role, $gender, $medical_condition, $registered_by);

        if ($stmt->execute()) {
            // ✅ Redirect to admin dashboard after successful insert
            header("Location: dashboard_admin.php");
            exit();
        } else {
            $message = "Error registering patient.";
        }
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: #fff;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .message {
            margin-top: 10px;
            color: green;
            text-align: center;
        }

        .error {
            color: red;
        }

        .back {
            margin-top: 10px;
            text-align: center;
        }

        .back a {
            color: #007bff;
            text-decoration: none;
        }

        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<form method="POST" action="">
    <h2>Register Patient</h2>
    <input type="text" name="name" placeholder="Patient Name" required />
    <input type="email" name="email" placeholder="Patient Email" required />
    <input type="password" name="password" placeholder="Password" required />
    
    <select name="gender" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>

    <textarea name="medical_condition" placeholder="Medical Condition (optional)"></textarea>
    
    <button type="submit" name="add_patient">Add Patient</button>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</form>


</body>
</html>
