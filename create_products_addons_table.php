<?php
$mysqli = new mysqli("localhost", "root", "", "pizzaone");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Create product_addons junction table
$query1 = "CREATE TABLE IF NOT EXISTS product_addons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    addon_id INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (addon_id) REFERENCES addons(id) ON DELETE CASCADE,
    UNIQUE KEY unique_product_addon (product_id, addon_id)
)";

// Add subcategory_id to products table if it doesn't exist
$query2 = "ALTER TABLE products ADD COLUMN subcategory_id INT DEFAULT NULL";

if ($mysqli->query($query1) === TRUE) {
    echo "Table product_addons created successfully\n";
} else {
    if (strpos($mysqli->error, "already exists") !== FALSE) {
        echo "Table product_addons already exists\n";
    } else {
        echo "Error creating product_addons table: " . $mysqli->error . "\n";
    }
}

if ($mysqli->query($query2) === TRUE) {
    echo "Column subcategory_id added to products successfully\n";
} else {
    if (strpos($mysqli->error, "Duplicate column") !== FALSE) {
        echo "Column subcategory_id already exists\n";
    } else {
        echo "Error adding subcategory_id column: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
?>
