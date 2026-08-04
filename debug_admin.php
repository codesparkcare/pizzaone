<?php
$mysqli = new mysqli('localhost','root','', 'pizzaone');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}
$res = $mysqli->query('SELECT admin_id, username, password_hash FROM admins');
if (!$res) {
    die('Query error: ' . $mysqli->error);
}
while($row = $res->fetch_assoc()){
    var_export($row);
    echo PHP_EOL;
}
?>
