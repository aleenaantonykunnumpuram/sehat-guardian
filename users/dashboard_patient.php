<?php
session_start();
require_once("../includes/db_connect.php");

// Check if patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login_patient.php");
    exit();
}

// Fetch patient info from users table
$patient_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, gender, medical_condition FROM users WHERE id = ? AND role = 'patient'");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$stmt->bind_result($name, $email, $gender, $medical_condition);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard - Sehat Guardian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6f2ff;
            padding: 40px;
        }

        .dashboard {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .info {
            line-height: 1.8;
            font-size: 16px;
        }

        .label {
            font-weight: bold;
        }

        .logout {
            margin-top: 20px;
            text-align: center;
        }

        .logout a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .logout a:hover {
            text-decoration: underline;
        }

        .actions {
            margin-top: 30px;
            text-align: center;
        }

        .actions a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .actions a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>

    <div class="info">
        <p><span class="label">Email:</span> <?= htmlspecialchars($email) ?></p>
        <p><span class="label">Gender:</span> <?= htmlspecialchars($gender) ?></p>
        <p><span class="label">Medical Condition:</span><br><?= nl2br(htmlspecialchars($medical_condition)) ?></p>
    </div>

    <div class="actions">
        <a href="#">View Appointments</a>
        <a href="#">Update Profile</a>
        <a href="#">Medical History</a>
    </div>

    <div class="logout">
        <a href="../users/logout.php">Logout</a>
    </div>
</div>

</body>
</html>
