<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../includes/db_connect.php");

$message = "";

// If already logged in as admin, redirect directly
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    if (!empty($email) && !empty($password_input)) {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Invalid email format.";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$/', $password_input)) {
            $message = "❌ Password must contain at least 1 uppercase letter, 1 number, 1 special character and be at least 8 characters long.";
        } else {
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

                    header("Location: dashboard_admin.php");
                    exit();
                } else {
                    $message = "❌ Incorrect password.";
                }
            } else {
                $message = "❌ Admin account not found.";
            }

            $stmt->close();
        }

    } else {
        $message = "❌ Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Sehat Guardian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #006064, #00838f);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        h2 {
            margin-bottom: 25px;
            text-align: center;
            color: #006064;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 12px;
            font-size: 15px;
            box-sizing: border-box;
            transition: border 0.3s, box-shadow 0.3s;
        }

        input:focus {
            border-color: #00838f;
            box-shadow: 0 0 5px rgba(0, 131, 143, 0.5);
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.1s;
        }

        button[type="submit"] {
            background: #006064;
            color: white;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background: #004d40;
            transform: scale(1.02);
        }

        .btn-home {
            background: #00838f;
            color: white;
            margin-top: 15px;
        }

        .btn-home:hover {
            background: #006064;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

<form method="POST" action="">
    <h2>Admin Login</h2>

    <input type="email" name="email" placeholder="Email" required 
           pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" 
           title="Please enter a valid email address" />

    <input type="password" name="password" placeholder="Password" required
           pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$"
           title="Must contain at least 1 uppercase letter, 1 number, 1 special character, and be at least 8 characters." />

    <button type="submit" name="login">Login</button>

    <?php if (!empty($message)): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <button type="button" class="btn-home" onclick="window.location.href='../home.php'">← Back to Home</button>
</form>

</body>
</html>
