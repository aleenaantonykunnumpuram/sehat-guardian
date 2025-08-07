<?php
session_start();
require_once("../includes/db_connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$success = $error = "";

// Fetch doctors
$doctors = [];
$doctor_result = $conn->query("SELECT id, name FROM users WHERE role = 'doctor'");
while ($row = $doctor_result->fetch_assoc()) {
    $doctors[] = $row;
}

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_SESSION['user_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = trim($_POST['notes']);
    $payment_method = $_POST['payment_method'];

    $card_last4 = NULL;
    if ($payment_method === "Card" && !empty($_POST['card_number'])) {
        $card_last4 = substr(preg_replace('/\D/', '', $_POST['card_number']), -4);
    }

    $status = "Pending";
    $payment_status = "Paid";

    // Insert appointment
    $stmt = $conn->prepare("INSERT INTO appointments 
        (patient_id, doctor_id, appointment_date, appointment_time, status, notes, payment_status, payment_method, card_last4)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssss", $patient_id, $doctor_id, $appointment_date, $appointment_time, $status, $notes, $payment_status, $payment_method, $card_last4);

    if ($stmt->execute()) {
        // Get doctor name
        $doc_stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
        $doc_stmt->bind_param("i", $doctor_id);
        $doc_stmt->execute();
        $doc_stmt->bind_result($doctor_name);
        $doc_stmt->fetch();
        $doc_stmt->close();

        // Get patient name
        $pat_stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
        $pat_stmt->bind_param("i", $patient_id);
        $pat_stmt->execute();
        $pat_stmt->bind_result($patient_name);
        $pat_stmt->fetch();
        $pat_stmt->close();

        // Save data in session for PDF generation
        $_SESSION['receipt_data'] = [
            'patient_name' => $patient_name,
            'doctor_name' => $doctor_name,
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time,
            'payment_method' => $payment_method,
            'card_last4' => $card_last4
        ];

        $success = "Appointment booked and payment completed.";
    } else {
        $error = "Failed: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment with Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>Book Appointment</h2>
        <a href="dashboard_patient.php" class="btn btn-outline-secondary">← Dashboard</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?><br>
            <a href="generate_receipt.php" class="btn btn-sm btn-outline-success mt-2">Download Receipt (PDF)</a>
        </div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Doctor</label>
            <select name="doctor_id" class="form-select" required>
                <option value="">-- Select Doctor --</option>
                <?php foreach ($doctors as $doc): ?>
                    <option value="<?php echo $doc['id']; ?>"><?php echo $doc['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="appointment_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Time</label>
            <input type="time" name="appointment_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control" placeholder="Optional notes"></textarea>
        </div>

        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-select" required onchange="togglePaymentFields()">
                <option value="">-- Select --</option>
                <option value="Cash">Cash</option>
                <option value="UPI">UPI</option>
                <option value="Cheque">Cheque</option>
                <option value="Card">Card</option>
            </select>
        </div>

        <div id="card_fields" style="display: none;">
            <div class="mb-3">
                <label>Card Number</label>
                <input type="text" name="card_number" class="form-control" maxlength="19" placeholder="1234 5678 9012 3456">
            </div>
            <div class="mb-3">
                <label>Expiry</label>
                <input type="month" name="card_expiry" class="form-control">
            </div>
            <div class="mb-3">
                <label>CVV</label>
                <input type="password" name="card_cvv" class="form-control" maxlength="3">
            </div>
        </div>

        <div id="upi_fields" style="display: none;">
            <div class="mb-3">
                <label>Enter UPI ID</label>
                <input type="text" name="upi_id" class="form-control" placeholder="example@upi">
            </div>
            <div class="mb-3">
                <label>Scan QR Code</label><br>
                <img src="upi_qr_dummy.png" alt="Scan QR" style="width:200px;">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit Appointment</button>
    </form>

    <script>
    function togglePaymentFields() {
        const method = document.getElementById('payment_method').value;
        document.getElementById('card_fields').style.display = (method === 'Card') ? 'block' : 'none';
        document.getElementById('upi_fields').style.display = (method === 'UPI') ? 'block' : 'none';
    }
    </script>
</body>
</html>
