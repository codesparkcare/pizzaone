<?php
require_once('application/config/database.php');
$mysqli = new mysqli('localhost', $db['default']['username'], $db['default']['password'], $db['default']['database']);
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}
$res = $mysqli->query("SELECT * FROM admins WHERE username = 'admin' LIMIT 1");
if (!$res) {
    die('Query error: ' . $mysqli->error);
}
$row = $res->fetch_object();
var_dump($row);
?>
