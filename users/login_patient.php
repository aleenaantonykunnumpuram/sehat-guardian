<?php
session_start();
require_once("../includes/db_connect.php");

$message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    // ✅ Corrected query
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = 'patient'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password_input, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['role'] = $role;

            header("Location: dashboard_patient.php");
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "Patient not found or invalid credentials.";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Patient Login - Sehat Guardian</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        .back-home {
            margin-top: 10px;
            text-align: center;
        }
        .back-home a {
            color: #007bff;
            text-decoration: none;
        }
        .back-home a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<form method="POST" action="">
    <h2>Patient Login</h2>
    <input type="email" name="email" placeholder="Your Email" required />
    <input type="password" name="password" placeholder="Your Password" required />
    <button type="submit" name="login">Login</button>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="back-home">
        <a href="../homepage.html">← Back to Home</a>
    </div>
</form>

</body>
</html>
