<?php
session_start();
require_once("../includes/db_connect.php");

$message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = 'admin'");
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

            // ✅ Redirect to admin dashboard
            header("Location: dashboard_admin.php");

            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "Admin not found.";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Sehat Guardian</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.2);
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }
        .btn-home {
            background: #007bff;
            margin-top: 10px;
        }
        .btn-home:hover {
            background: #0056b3;
        }
        button:hover {
            background: #218838;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .register-link {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<form method="POST" action="">
    <h2>Admin Login</h2>
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit" name="login">Login</button>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="register-link">
        Not registered? <a href="register.php">Register here</a>
    </div>

    <!-- Home Button -->
    <button type="button" class="btn-home" onclick="window.location.href='../homepage.html'">← Back to Home</button>
</form>

</body>
</html>
