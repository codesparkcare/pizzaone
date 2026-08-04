<?php
define('BASEPATH', '1');
require 'application/config/database.php';
$db = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
if ($db->connect_error) die("Connection failed: " . $db->connect_error);

$res = $db->query('SELECT * FROM migrations');
if ($res) {
    while ($row = $res->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error: " . $db->error;
}
