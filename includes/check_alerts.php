<?php
require_once("../includes/db_connect.php");

// Get latest unread alert
$sql = "SELECT * FROM alerts WHERE status='Unread' ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $alert = $result->fetch_assoc();
    echo json_encode([
        'new_alerts' => true,
        'alert_id' => $alert['id'],
        'patient_id' => $alert['patient_id'],
        'alert_type' => $alert['alert_type'],
        'message' => $alert['message']
    ]);
} else {
    echo json_encode(['new_alerts' => false]);
}
?>
