<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pizzaone';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

echo "=== SHOPS TABLE CONTENT ===\n";
$res = $mysqli->query("SELECT * FROM shops");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error querying shops table: " . $mysqli->error . "\n";
}

$mysqli->close();
