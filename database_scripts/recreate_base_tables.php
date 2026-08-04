<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'pizzaone');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$queries = [
    "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        parent_id INT DEFAULT 0,
        name VARCHAR(255) NOT NULL,
        image VARCHAR(255),
        status TINYINT DEFAULT 1
    )",
    "CREATE TABLE IF NOT EXISTS subcategories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        image VARCHAR(255),
        status TINYINT DEFAULT 1
    )",
    "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT DEFAULT NULL,
        subcategory_id INT DEFAULT NULL,
        offer_id INT DEFAULT NULL,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) DEFAULT 0.00,
        image VARCHAR(255),
        status TINYINT DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS sizes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        status TINYINT DEFAULT 1
    )"
];

foreach ($queries as $query) {
    if ($mysqli->query($query) === TRUE) {
        echo "Table created successfully\n";
    } else {
        echo "Error creating table: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
?>
