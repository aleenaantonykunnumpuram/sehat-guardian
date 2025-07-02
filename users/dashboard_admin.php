<?php
require_once("../includes/auth.php");
checkAuth('admin');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 100px;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
        }

        .btn {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .add-patient-btn {
            background-color: #007bff;
            color: white;
        }

        .add-patient-btn:hover {
            background-color: #0056b3;
        }
    </style>

    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                alert("You have been logged out.");
                window.location.href = "logout.php";
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
    <button class="btn add-patient-btn" onclick="window.location.href='add_patient.php'">Add Patient</button>
    <button class="btn logout-btn" onclick="confirmLogout()">Logout</button>
</div>

</body>
</html>
