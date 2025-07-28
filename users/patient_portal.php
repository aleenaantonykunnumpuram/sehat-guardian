<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("../includes/db_connect.php");

$login_message = "";
$register_message = "";

// ✅ LOGIN HANDLER
if (isset($_POST['login'])) {
    $email = trim($_POST['login_email']);
    $password_input = $_POST['login_password'];

    if (!empty($email) && !empty($password_input)) {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = 'patient'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $password_hash, $role);
            $stmt->fetch();

            if (password_verify($password_input, $password_hash)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['patient_id'] = $id;  // ✅ This is the key line!
                $_SESSION['user_name'] = $name;
                $_SESSION['role'] = $role;
                $_SESSION['login_time'] = time();
                session_regenerate_id(true);

                header("Location: /sehat-guardian/patient/dashboard_patient.php");
                exit();
            } else {
                $login_message = "❌ Incorrect password.";
            }
        } else {
            $login_message = "❌ Patient not found.";
        }

        $stmt->close();
    } else {
        $login_message = "❌ Please enter both email and password.";
    }
}

// ✅ REGISTER HANDLER stays the same
// (Your registration part is fine)

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Patient Login/Register</title>
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

.container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #006064;
}

form {
    display: none;
}

form.active {
    display: block;
}

input {
    width: 100%;
    padding: 12px 15px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}

input:focus {
    border-color: #00838f;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    background: #00838f;
    border: none;
    color: white;
    border-radius: 12px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s, transform 0.1s;
    margin-top: 10px;
}

button:hover {
    background: #006064;
    transform: scale(1.02);
}

.toggle {
    margin-top: 20px;
    cursor: pointer;
    color: #00838f;
    font-weight: bold;
}

.toggle:hover {
    text-decoration: underline;
}

.message {
    margin-top: 10px;
    color: red;
}
    </style>
</head>
<body>
<div class="container">
    <h2 id="formTitle">Patient Login</h2>

    <!-- Login Form -->
    <form id="loginForm" class="active" method="POST" action="">
        <input type="email" name="login_email" placeholder="Email" required>
        <input type="password" name="login_password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <?php if ($login_message): ?>
            <p class="message"><?= htmlspecialchars($login_message) ?></p>
        <?php endif; ?>
    </form>

    <!-- Register Form -->
    <form id="registerForm" method="POST" action="">
        <input type="text" name="register_name" placeholder="Full Name" required>
        <input type="email" name="register_email" placeholder="Email" required>
        <input type="password" name="register_password" placeholder="Password" required 
            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
            title="Must be at least 8 characters with uppercase, lowercase, number, and special character.">
        <input type="password" name="register_confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="register">Register</button>
        <?php if ($register_message): ?>
            <p class="message"><?= htmlspecialchars($register_message) ?></p>
        <?php endif; ?>
    </form>

    <div class="toggle" onclick="toggleForms()">Switch to Register</div>
</div>

<script>
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const toggle = document.querySelector('.toggle');
const formTitle = document.getElementById('formTitle');

function toggleForms() {
    loginForm.classList.toggle('active');
    registerForm.classList.toggle('active');

    if (loginForm.classList.contains('active')) {
        toggle.textContent = 'Switch to Register';
        formTitle.textContent = 'Patient Login';
    } else {
        toggle.textContent = 'Switch to Login';
        formTitle.textContent = 'Patient Register';
    }
}
</script>
</body>
</html>
