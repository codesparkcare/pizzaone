<?php
require_once('application/config/database.php');

$mysqli = new mysqli('localhost', $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$result = $mysqli->query('SELECT admin_id, username FROM admins LIMIT 5');

if ($result->num_rows > 0) {
    echo "Admin users found:\n";
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['admin_id'] . ", Username: " . $row['username'] . "\n";
    }
} else {
    echo "No admins found. You may need to create one.\n";
}

$mysqli->close();
?>
