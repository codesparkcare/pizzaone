<?php
$mysqli = new mysqli("localhost", "root", "", "pizzaone");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Create addon_groups table
$query1 = "CREATE TABLE IF NOT EXISTS addon_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Create addon_group_items table - links addons to groups
$query2 = "CREATE TABLE IF NOT EXISTS addon_group_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    addon_id INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES addon_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (addon_id) REFERENCES addons(id) ON DELETE CASCADE,
    UNIQUE KEY unique_group_addon (group_id, addon_id)
)";

// Create product_addon_groups table - links addon groups to products with selection limits
$query3 = "CREATE TABLE IF NOT EXISTS product_addon_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    group_id INT NOT NULL,
    min_selections INT DEFAULT 0,
    max_selections INT DEFAULT 1,
    is_required BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES addon_groups(id) ON DELETE CASCADE,
    UNIQUE KEY unique_product_group (product_id, group_id)
)";

if ($mysqli->query($query1) === TRUE) {
    echo "Table addon_groups created successfully\n";
} else {
    if (strpos($mysqli->error, "already exists") !== FALSE) {
        echo "Table addon_groups already exists\n";
    } else {
        echo "Error creating addon_groups table: " . $mysqli->error . "\n";
    }
}

if ($mysqli->query($query2) === TRUE) {
    echo "Table addon_group_items created successfully\n";
} else {
    if (strpos($mysqli->error, "already exists") !== FALSE) {
        echo "Table addon_group_items already exists\n";
    } else {
        echo "Error creating addon_group_items table: " . $mysqli->error . "\n";
    }
}

if ($mysqli->query($query3) === TRUE) {
    echo "Table product_addon_groups created successfully\n";
} else {
    if (strpos($mysqli->error, "already exists") !== FALSE) {
        echo "Table product_addon_groups already exists\n";
    } else {
        echo "Error creating product_addon_groups table: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
?>
