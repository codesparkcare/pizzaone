<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pizzaone';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Drop existing table if it exists
$mysqli->query('DROP TABLE IF EXISTS `orders`');

$sql = "CREATE TABLE `orders` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_type` VARCHAR(20) NOT NULL,
  `shop_id` INT UNSIGNED NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `customer_address` TEXT NULL,
  `notes` TEXT NULL,
  `payment_method` VARCHAR(30) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `delivery_fee` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (!$mysqli->query($sql)) {
    echo "Error creating table: " . $mysqli->error . "\n";
    exit(1);
}

$indexes = [
    "CREATE INDEX idx_status ON `orders`(`status`)",
    "CREATE INDEX idx_created_at ON `orders`(`created_at`)"
];
foreach ($indexes as $idxSql) {
    if (!$mysqli->query($idxSql) && strpos($mysqli->error, 'Duplicate key name') === false) {
        echo "Error creating index: " . $mysqli->error . "\n";
        exit(1);
    }
}

echo "Orders table recreated successfully.\n";
$mysqli->close();
?>
