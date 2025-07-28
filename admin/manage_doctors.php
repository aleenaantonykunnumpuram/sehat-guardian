<?php
require_once("../includes/auth.php");
checkAuth('admin');
require_once("../includes/db_connect.php");

$message = "";

// Handle add doctor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_doctor'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];
    $specialization = trim($_POST['specialization']);
    $license_no = trim($_POST['license_no']);

    if (!empty($name) && !empty($email) && !empty($password_input)) {

        // ✅ Strong password check
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$/', $password_input)) {
            $message = "❌ Password must be at least 8 characters long and contain at least 1 uppercase letter, 1 number, and 1 special character.";
        } else {
            // Hash
            $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'doctor')");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;

                $stmt2 = $conn->prepare("INSERT INTO doctors (user_id, specialization, license_no) VALUES (?, ?, ?)");
                $stmt2->bind_param("iss", $user_id, $specialization, $license_no);
                $stmt2->execute();
                $stmt2->close();

                $message = "✅ New doctor added successfully.";
            } else {
                $message = "❌ Error: Could not add doctor.";
            }

            $stmt->close();
        }
    } else {
        $message = "❌ Please fill in all required fields.";
    }
}

// Handle delete doctor
if (isset($_GET['delete'])) {
    $delete_user_id = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'doctor'");
    $stmt->bind_param("i", $delete_user_id);

    if ($stmt->execute()) {
        $message = "✅ Doctor removed successfully.";
    } else {
        $message = "❌ Failed to delete doctor.";
    }

    $stmt->close();
}

// Get all doctors
$doctors = [];
$sql = "SELECT u.id, u.name, u.email, d.specialization, d.license_no 
        FROM users u 
        LEFT JOIN doctors d ON u.id = d.user_id 
        WHERE u.role = 'doctor'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctors - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0fafa;
            padding: 40px;
            position: relative;
        }

        .logout {
            position: absolute;
            top: 20px;
            right: 40px;
        }

        .logout a {
            background: #006064;
            color: #fff;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .logout a:hover {
            background: #004d40;
        }

        h2 {
            text-align: center;
            color: #006064;
            margin-bottom: 20px;
        }

        .message {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            color: #006064;
        }

        .error {
            color: red;
        }

        .form-section, .table-section {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        h3 {
            color: #00838f;
        }

        form input {
            display: block;
            width: 100%;
            padding: 10px 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        form button {
            padding: 12px 20px;
            background: #00838f;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            transition: background 0.3s;
        }

        form button:hover {
            background: #006064;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background: #006064;
            color: #fff;
        }

        .btn-delete {
            background: #dc3545;
            color: #fff;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-delete:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<div class="logout">
    <a href="dashboard_admin.php">← Back to Dashboard</a>
</div>

<h2>Manage Doctors</h2>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="form-section">
    <h3>Add New Doctor</h3>
    <form method="POST">
        <input type="text" name="name" placeholder="Doctor Name" required>
        <input type="email" name="email" placeholder="Doctor Email" required>
        <input type="password" name="password" placeholder="Temporary Password" required
            pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$"
            title="Must contain at least 1 uppercase letter, 1 number, 1 special character, and be at least 8 characters long.">
        <input type="text" name="specialization" placeholder="Specialization (optional)">
        <input type="text" name="license_no" placeholder="License No (optional)">
        <button type="submit" name="add_doctor">Add Doctor</button>
    </form>
</div>

<div class="table-section">
    <h3>All Doctors</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Specialization</th>
            <th>License No</th>
            <th>Action</th>
        </tr>
        <?php if (count($doctors) > 0): ?>
            <?php foreach ($doctors as $doc): ?>
                <tr>
                    <td><?= htmlspecialchars($doc['name']) ?></td>
                    <td><?= htmlspecialchars($doc['email']) ?></td>
                    <td><?= htmlspecialchars($doc['specialization']) ?></td>
                    <td><?= htmlspecialchars($doc['license_no']) ?></td>
                    <td>
                        <a class="btn-delete" href="?delete=<?= $doc['id'] ?>" onclick="return confirm('Delete this doctor?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">No doctors found.</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
