<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Seed default admin users.
 * Run via CLI: php index.php seed_admins
 */
class Seed_admins extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_user_model');
        $this->load->database();
    }

    public function index() {
        $admins = [
            [
                'username' => 'admin',
                'password' => 'admin123',
                'role' => 'admin'
            ]
        ];

        // Remove any non-admin users
        $this->db->where('username !=', 'admin');
        $this->db->delete('admins');

        foreach ($admins as $admin) {
            // Upsert admin user
            $exists = $this->db->get_where('admins', ['username' => $admin['username']])->row();
            if ($exists) {
                // Update password if needed
                $this->Admin_user_model->update($admin['username'], $admin);
                echo "[UPDATE] Admin credentials refreshed.\n";
                continue;
            }
            $this->Admin_user_model->create($admin);
            echo "[OK] Created admin user.\n";
        }
        echo "Admin reset complete.\n";
    }
}
?>
