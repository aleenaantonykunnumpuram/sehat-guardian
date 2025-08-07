<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seen_alerts'])) {
    $seen_alerts = $_POST['seen_alerts'];
    foreach ($seen_alerts as $alert_id) {
        $stmt = $conn->prepare("UPDATE alerts SET seen_by_admin = 1 WHERE id = ?");
        $stmt->bind_param("i", $alert_id);
        $stmt->execute();
    }
}

header("Location: view_alerts.php?filter=unseen");
exit();
