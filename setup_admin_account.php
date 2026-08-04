<?php
// Direct database setup - no CodeIgniter 
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'pizzaone';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die('Connection Error: ' . $mysqli->connect_error);
}

// Check if admin table exists
$check = $mysqli->query("SHOW TABLES LIKE 'admins'");
if ($check->num_rows == 0) {
    echo "❌ Admins table does not exist<br>";
    exit;
}

// Create or update admin account
$username = 'admin';
$password = password_hash('admin123', PASSWORD_BCRYPT);

// Check if admin exists
$result = $mysqli->query("SELECT id FROM admins WHERE username='$username'");

if ($result->num_rows > 0) {
    // Update existing
    $mysqli->query("UPDATE admins SET password='$password' WHERE username='$username'");
    echo "✅ Admin account updated<br>";
} else {
    // Create new
    $mysqli->query("INSERT INTO admins (username, password, email) VALUES ('$username', '$password', 'admin@local')");
    echo "✅ Admin account created<br>";
}

echo "<strong>Login Credentials:</strong><br>";
echo "Username: admin<br>";
echo "Password: admin123<br><br>";
echo "<a href='admin/login'>Go to Admin Login</a>";

$mysqli->close();
?>
