<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'pizzaone');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$sql = "ALTER TABLE `addon_groups` 
ADD COLUMN `min_selections` int(11) NOT NULL DEFAULT '0',
ADD COLUMN `max_selections` int(11) NOT NULL DEFAULT '0';";

if ($mysqli->query($sql)) {
    echo "Columns added to addon_groups successfully.\n";
} else {
    echo "Error adding columns: " . $mysqli->error . "\n";
}
$mysqli->close();
?>
