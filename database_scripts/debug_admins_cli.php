<?php
// Direct CLI script – no CI guard needed
$mysqli = new mysqli('localhost', 'root', '', 'pizzaone');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$res = $mysqli->query('SELECT id, username, password_hash FROM admins LIMIT 5');
if (!$res) {
    die('Query error: ' . $mysqli->error);
}

$rows = $res->fetch_all(MYSQLI_ASSOC);
if (empty($rows)) {
    echo "No admin rows found.\n";
} else {
    foreach ($rows as $row) {
        echo "ID: {$row['admin_id']}, Username: {$row['username']}, Hash: {$row['password_hash']}\n";
    }
}
?>
