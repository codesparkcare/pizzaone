<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'pizzaone');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS `slider_videos` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `video_url` varchar(500) NOT NULL,
    `status` tinyint(1) NOT NULL DEFAULT '1',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if ($mysqli->query($sql)) {
    echo "Table slider_videos created successfully.\n";
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}
$mysqli->close();
?>
