<?php
session_start();
require_once("../includes/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_SESSION['user_id']; // or $_SESSION['id'] based on your login system
    $alert_type = $_POST['alert_type'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO alerts (patient_id, alert_type, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $patient_id, $alert_type, $message);
    $stmt->execute();
    $stmt->close();

  header("Location: dashboard_patient.php?alert_sent=1");


    exit();
}
?>
