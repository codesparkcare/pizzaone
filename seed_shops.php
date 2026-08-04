<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'pizzaone');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Create shops if they don't exist
$shops = [
    ['name' => 'Shop 1', 'address' => 'Address 1', 'phone' => '1234567890', 'email' => 'shop1@example.com'],
    ['name' => 'Shop 2', 'address' => 'Address 2', 'phone' => '0987654321', 'email' => 'shop2@example.com']
];

foreach ($shops as $index => $shop) {
    $name = $mysqli->real_escape_string($shop['name']);
    $address = $mysqli->real_escape_string($shop['address']);
    $phone = $mysqli->real_escape_string($shop['phone']);
    $email = $mysqli->real_escape_string($shop['email']);
    
    $check = $mysqli->query("SELECT id FROM shops WHERE name = '$name'");
    if ($check && $check->num_rows == 0) {
        $mysqli->query("INSERT INTO shops (name, address, phone, email) VALUES ('$name', '$address', '$phone', '$email')");
        echo "Created $name\n";
    }
}

// Create shop users
$shop_users = [
    ['shop_name' => 'Shop 1', 'username' => 'shop1admin', 'password' => 'password123'],
    ['shop_name' => 'Shop 2', 'username' => 'shop2admin', 'password' => 'password123']
];

foreach ($shop_users as $user) {
    $shop_name = $mysqli->real_escape_string($user['shop_name']);
    $shop_res = $mysqli->query("SELECT id FROM shops WHERE name = '$shop_name'");
    if ($shop_res && $shop_res->num_rows > 0) {
        $shop_id = $shop_res->fetch_assoc()['id'];
        $username = $mysqli->real_escape_string($user['username']);
        $password_hash = password_hash($user['password'], PASSWORD_DEFAULT);
        
        $check_user = $mysqli->query("SELECT id FROM shop_users WHERE username = '$username'");
        if ($check_user && $check_user->num_rows == 0) {
            $mysqli->query("INSERT INTO shop_users (shop_id, username, password_hash) VALUES ($shop_id, '$username', '$password_hash')");
            echo "Created user $username for $shop_name\n";
        }
    }
}

$mysqli->close();
?>
