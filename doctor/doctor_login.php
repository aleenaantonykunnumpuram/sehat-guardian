<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../includes/db_connect.php");

$message = "";

// Always show form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    if (!empty($email) && !empty($password_input)) {

        // ✅ Server-side email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Invalid email format.";
        }
        // ✅ Server-side strong password validation
        elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$/', $password_input)) {
            $message = "❌ Password must contain at least 1 uppercase letter, 1 number, 1 special character and be at least 8 characters long.";
        } else {
            $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = 'doctor'");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $name, $password_hash, $role);
                $stmt->fetch();

                if (password_verify($password_input, $password_hash)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['role'] = $role;

                    header("Location: dashboard_doctor.php");
                    exit();
                } else {
                    $message = "❌ Incorrect password.";
                }
            } else {
                $message = "❌ Doctor not found.";
            }

            $stmt->close();
        }
    } else {
        $message = "❌ Please enter both email and password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Doctor Login</title>
    <style>
        body {
            background: linear-gradient(135deg, #006064, #00838f);
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #006064;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border 0.3s, box-shadow 0.3s;
        }

        .login-container input:focus {
            border-color: #00838f;
            box-shadow: 0 0 5px rgba(0, 131, 143, 0.5);
            outline: none;
        }

        .password-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 5px 0 15px 0;
            font-size: 14px;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background: #006064;
            border: none;
            color: white;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.1s;
        }

        .login-container button:hover {
            background: #004d40;
            transform: scale(1.02);
        }

        .btn-home {
            background: #00838f;
            margin-top: 10px;
        }

        .btn-home:hover {
            background: #006064;
        }

        .message {
            margin-top: 15px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Doctor Login</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required 
                pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" 
                title="Please enter a valid email address" />

            <input type="password" name="password" placeholder="Password" id="password" required 
                pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$"
                title="Must contain at least 1 uppercase letter, 1 number, 1 special character, and be at least 8 characters long." />

            <div class="password-toggle">
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label>
            </div>

            <button type="submit">Login</button>
            <button type="button" class="btn-home" onclick="window.location.href='../home.php'">← Back to Home</button>
        </form>

        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>

    <script>
        const showPassword = document.getElementById('showPassword');
        const passwordInput = document.getElementById('password');

        showPassword.addEventListener('change', function() {
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>
