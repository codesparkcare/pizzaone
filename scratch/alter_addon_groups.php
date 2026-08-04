<?php
$mysqli = new mysqli("localhost", "root", "", "pizzaone");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check existing columns
$result = $mysqli->query("DESCRIBE addon_groups");
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

echo "Current columns: " . implode(", ", $columns) . "\n";

if (!in_array('min_selections', $columns)) {
    if ($mysqli->query("ALTER TABLE addon_groups ADD COLUMN min_selections INT DEFAULT 0")) {
        echo "Added min_selections column successfully\n";
    } else {
        echo "Error adding min_selections: " . $mysqli->error . "\n";
    }
}

if (!in_array('max_selections', $columns)) {
    if ($mysqli->query("ALTER TABLE addon_groups ADD COLUMN max_selections INT DEFAULT 999")) {
        echo "Added max_selections column successfully\n";
    } else {
        echo "Error adding max_selections: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
?>
