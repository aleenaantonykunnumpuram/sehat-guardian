<?php
$password = "Sehatguardian#0607";  // your admin password here
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Hashed password: " . $hash;
?>
