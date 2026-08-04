<?php
define('BASEPATH', true);
define('ENVIRONMENT', 'development');
require_once('application/config/database.php');
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
if ($mysqli->connect_error) { die('Connect: '.$mysqli->connect_error); }
$res = $mysqli->query("SELECT password_hash FROM admins WHERE username='admin' LIMIT 1");
$row = $res->fetch_object();
if ($row) {
    $valid = password_verify('admin123', $row->password_hash);
    echo $valid ? "Password matches\n" : "Password does NOT match\n";
} else {
    echo "Admin not found\n";
}
?>
