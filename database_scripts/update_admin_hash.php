<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'pizzaone');
$hash = password_hash('admin123', PASSWORD_BCRYPT);
$mysqli->query("UPDATE admins SET password_hash='$hash' WHERE username='admin'");
echo "Admin password updated to 'admin123'\n";
$mysqli->close();
?>
