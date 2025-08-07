<?php
require_once("../includes/db_connect.php");
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE medicine_schedule SET status = 'Taken', taken_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
?>
