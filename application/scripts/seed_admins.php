<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Seed admin user.
 * Run via CLI: php index.php seed_admins
 */
class Seed_admins extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_user_model');
        $this->load->database();
    }

    public function index() {
        // Truncate admins table to remove all existing users
        $this->db->truncate('admins');

        // Create admin user with username 'admin' and password 'admin123'
        $admin = [
            'username' => 'admin',
            'password' => 'admin123', // plain password for fallback
            'role' => 'admin'
        ];
        $this->Admin_user_model->create($admin);
        echo "[OK] Admin user created (admin/admin123).\n";
    }
}
?>
