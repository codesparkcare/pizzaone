<?php
$mysqli = new mysqli('localhost', 'root', '', 'pizzaone');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Add the column if it does not exist
$mysqli->query("ALTER TABLE admins ADD COLUMN IF NOT EXISTS password_hash VARCHAR(255) NULL");
echo "Column added (if it was missing).\n";
?>
