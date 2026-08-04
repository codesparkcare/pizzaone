<?php
// This script creates an admin account for testing
require_once('system/core/CodeIgniter.php');

// Get CodeIgniter instance
$CI = get_instance();

// Check if admin exists
$admin = $CI->db->get_where('admins', ['username' => 'admin'])->row();

if ($admin) {
    echo "Admin account already exists: username = 'admin'\n";
    // Try to reset password
    $new_password = password_hash('admin123', PASSWORD_BCRYPT);
    $CI->db->update('admins', ['password' => $new_password], ['username' => 'admin']);
    echo "Password updated to: admin123\n";
} else {
    // Create new admin
    $admin_data = [
        'username' => 'admin',
        'password' => password_hash('admin123', PASSWORD_BCRYPT),
        'email' => 'admin@pizzaone.local',
        'created_at' => date('Y-m-d H:i:s')
    ];
    $CI->db->insert('admins', $admin_data);
    echo "Admin account created with username 'admin' and password 'admin123'\n";
}

echo "You can now login with:\nUsername: admin\nPassword: admin123\n";
?>
