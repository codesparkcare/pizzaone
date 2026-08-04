<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pizzaone';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$alterStatements = [
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `order_type` VARCHAR(20) NOT NULL DEFAULT 'collect'",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `shop_id` INT UNSIGNED NULL",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `customer_address` TEXT NULL",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `notes` TEXT NULL",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `payment_method` VARCHAR(30) NOT NULL DEFAULT 'cash'",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `delivery_fee` DECIMAL(10,2) NOT NULL DEFAULT 0",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `total` DECIMAL(10,2) NOT NULL DEFAULT 0",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `status` VARCHAR(20) NOT NULL DEFAULT 'pending'",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `created_at` DATETIME NOT NULL",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `updated_at` DATETIME NULL DEFAULT NULL"
];

foreach ($alterStatements as $sql) {
    if (!$mysqli->query($sql)) {
        // If column already exists, ignore duplicate column errors
        if (strpos($mysqli->error, 'Duplicate column') === false) {
            echo "Error executing: $sql\n" . $mysqli->error . "\n";
            // Continue to next statement
        }
    }
}

echo "Orders table columns ensured successfully.\n";
$mysqli->close();
?>
