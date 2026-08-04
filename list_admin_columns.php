<?php
$mysqli = new mysqli('localhost','root','', 'pizzaone');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}
$res = $mysqli->query('SHOW COLUMNS FROM admins');
if (!$res) {
    die('Query error: ' . $mysqli->error);
}
while($row = $res->fetch_assoc()){
    echo $row['Field'] . ' : ' . $row['Type'] . "\n";
}
?>
