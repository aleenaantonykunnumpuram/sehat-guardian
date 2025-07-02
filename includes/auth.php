<?php
session_start();

function checkAuth($role) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $role) {
        header("Location: ../users/login.php");
        exit();
    }
}
?>
