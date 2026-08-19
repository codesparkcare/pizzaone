<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pizzaone';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

// 1. Check if 'shops' column exists in 'products' table
$res = $mysqli->query("SHOW COLUMNS FROM products LIKE 'shops'");
if ($res && $res->num_rows == 0) {
    $alter = $mysqli->query("ALTER TABLE products ADD COLUMN `shops` VARCHAR(255) NOT NULL DEFAULT '1,2' AFTER `image`");
    if ($alter) {
        echo "Column 'shops' added to 'products' table successfully.\n";
    } else {
        echo "Error adding 'shops' column: " . $mysqli->error . "\n";
    }
} else {
    echo "Column 'shops' already exists in 'products' table.\n";
}

// Ensure existing NULL or empty shops values default to '1,2'
$mysqli->query("UPDATE products SET shops = '1,2' WHERE shops IS NULL OR shops = ''");

// 2. Ensure shops table has Villiers-le-bel and Le Plessis-Bouchard
$shop1 = $mysqli->query("SELECT * FROM shops WHERE id = 1");
if ($shop1 && $shop1->num_rows > 0) {
    $mysqli->query("UPDATE shops SET name = 'Villiers-le-bel', address = '11 Place de la Tolinette, 95400 Villiers Le Bel' WHERE id = 1");
} else {
    $mysqli->query("INSERT INTO shops (id, name, address, phone, email, is_active, created_at) VALUES (1, 'Villiers-le-bel', '11 Place de la Tolinette, 95400 Villiers Le Bel', '0134141516', 'villiers@pizzaone.fr', 1, NOW())");
}

$shop2 = $mysqli->query("SELECT * FROM shops WHERE id = 2");
if ($shop2 && $shop2->num_rows > 0) {
    $mysqli->query("UPDATE shops SET name = 'Le Plessis-Bouchard', address = 'Commercial des Hauts de Saint-Nicolas 95130 Le Plessis-Bouchard', phone = '0134141516', is_active = 1 WHERE id = 2");
} else {
    $mysqli->query("INSERT INTO shops (id, name, address, phone, email, is_active, created_at) VALUES (2, 'Le Plessis-Bouchard', 'Commercial des Hauts de Saint-Nicolas 95130 Le Plessis-Bouchard', '0134141516', 'plessis@pizzaone.fr', 1, NOW())");
}

echo "Shops table updated successfully.\n";

$mysqli->close();
