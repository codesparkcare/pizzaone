<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pizzaone';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

echo "=== PRODUCTS TABLE COLUMNS ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM products");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo $row['Field'] . ' (' . $row['Type'] . ')' . "\n";
    }
} else {
    echo "Error showing columns for products: " . $mysqli->error . "\n";
}

echo "\n=== TABLES IN PIZZAONE ===\n";
$res2 = $mysqli->query("SHOW TABLES");
if ($res2) {
    while ($row = $res2->fetch_array()) {
        echo $row[0] . "\n";
    }
}

$mysqli->close();
