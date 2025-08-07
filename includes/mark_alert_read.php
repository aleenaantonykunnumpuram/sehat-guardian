<?php
require_once("../includes/db_connect.php");
$id = $_GET['id'];
$conn->query("UPDATE alerts SET status='Read' WHERE id=$id");
?>
