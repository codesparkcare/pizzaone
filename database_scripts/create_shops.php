<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'pizzaone');
$query = "CREATE TABLE IF NOT EXISTS shops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(100),
    is_active TINYINT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($mysqli->query($query)) {
    echo "Shops table created successfully.\n";
} else {
    echo "Error: " . $mysqli->error;
}
$mysqli->close();
?>
