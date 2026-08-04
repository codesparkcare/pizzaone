<?php
$mysqli = new mysqli("localhost", "root", "", "pizzaone");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Create addons table
$sql = "CREATE TABLE IF NOT EXISTS addons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) DEFAULT 0.00,
    type ENUM('include', 'exclude') NOT NULL DEFAULT 'include',
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($mysqli->query($sql) === TRUE) {
    echo "Table addons created successfully\n";
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}

// Create category_addons table
$sql = "CREATE TABLE IF NOT EXISTS category_addons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    addon_id INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (addon_id) REFERENCES addons(id) ON DELETE CASCADE
)";

if ($mysqli->query($sql) === TRUE) {
    echo "Table category_addons created successfully\n";
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
