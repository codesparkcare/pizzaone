<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pizzaone';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS `smtp_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `smtp_host` VARCHAR(255) NOT NULL DEFAULT '',
  `smtp_port` INT NOT NULL DEFAULT 587,
  `smtp_crypto` VARCHAR(10) NOT NULL DEFAULT 'tls',
  `smtp_user` VARCHAR(255) NOT NULL DEFAULT '',
  `smtp_pass` VARCHAR(255) NOT NULL DEFAULT '',
  `from_email` VARCHAR(255) NOT NULL DEFAULT '',
  `from_name` VARCHAR(255) NOT NULL DEFAULT 'Pizza One',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (!$mysqli->query($sql)) {
    echo "Error creating table: " . $mysqli->error . "\n";
    exit(1);
}

// Insert default row if table is empty
$check = $mysqli->query("SELECT id FROM `smtp_settings` LIMIT 1");
if ($check && $check->num_rows == 0) {
    $insert = "INSERT INTO `smtp_settings` 
    (`smtp_host`, `smtp_port`, `smtp_crypto`, `smtp_user`, `smtp_pass`, `from_email`, `from_name`, `is_active`, `created_at`) 
    VALUES ('smtp.gmail.com', 587, 'tls', 'info@pizzaone.fr', '', 'info@pizzaone.fr', 'Pizza One', 1, NOW());";
    $mysqli->query($insert);
    echo "Default SMTP row inserted.\n";
}

echo "smtp_settings table created successfully.\n";
$mysqli->close();
?>
