<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'pizzaone');
if ($mysqli->query('ALTER TABLE products DROP COLUMN offer_id')) {
    echo "Column offer_id dropped\n";
} else {
    echo "Error dropping column: " . $mysqli->error . "\n";
}
$mysqli->close();
?>
