<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'pizzaone');

echo "--- SHOPS ---\n";
$res = $mysqli->query('SELECT * FROM shops');
if($res) {
  while($row = $res->fetch_assoc()) { print_r($row); }
} else { echo "No shops table or empty: " . $mysqli->error . "\n"; }

echo "--- SHOP USERS ---\n";
$res2 = $mysqli->query('SELECT * FROM shop_users');
if($res2) {
  while($row = $res2->fetch_assoc()) { print_r($row); }
} else { echo "No shop_users table or empty: " . $mysqli->error . "\n"; }
$mysqli->close();
?>
