<?php
$mysqli = new mysqli("localhost", "root", "", "pizzaone");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$query = "CREATE TABLE IF NOT EXISTS product_sizes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
)";

if ($mysqli->query($query) === TRUE) {
    echo "Table product_sizes created successfully\n";
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
