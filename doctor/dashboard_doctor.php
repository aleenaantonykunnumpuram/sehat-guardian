<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f9ff;
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
            color: #007bff;
        }

        .logout {
            margin-top: 20px;
        }

        .logout a {
            color: #fff;
            background: #dc3545;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .logout a:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h2>Welcome Dr. <?= htmlspecialchars($_SESSION['user_name']) ?></h2>
    <p>This is your doctor dashboard.</p>

    <div class="logout">
        <a href="../users/logout.php">Logout</a>
    </div>
</div>

</body>
</html>
