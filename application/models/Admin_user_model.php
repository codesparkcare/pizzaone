<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_user_model extends CI_Model {
    protected $table = 'admins';

    public function get_all() {
        return $this->db->order_by('admin_id', 'DESC')->get($this->table)->result();
    }

    public function get_by_id($admin_id) {
        return $this->db->get_where($this->table, ['admin_id' => $admin_id])->row();
    }

    public function get_by_username($username) {
        return $this->db->get_where($this->table, ['username' => $username])->row();
    }

    // $data should contain 'username', 'password', 'role'
    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);
        return $this->db->insert($this->table, $data);
    }

    // $data may contain 'password' (plain) optionally
    public function update($admin_id, $data) {
        if (!empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        return $this->db->where('admin_id', $admin_id)->update($this->table, $data);
    }

    /**
    * Seed default admin users (superadmin, admin, staff) with hashed passwords.
    *
    * This method can be called from a one‑time CLI script.
    */
    public function seed_default_admins()
    {
        $default_password = 'Pizza@123*';
        $hash = password_hash($default_password, PASSWORD_BCRYPT);
        $users = [
            [
                'username' => 'superadmin',
                'password_hash' => $hash,
                'role' => 'super_admin',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'admin',
                'password_hash' => $hash,
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'staff',
                'password_hash' => $hash,
                'role' => 'staff',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        foreach ($users as $user) {
            // Avoid duplicate inserts
            $existing = $this->db->get_where($this->table, ['username' => $user['username']])->row();
            if (!$existing) {
                $this->db->insert($this->table, $user);
            }
        }
    }

    /**
    * Ensure the admins table has a password_hash column and migrates any old plaintext passwords.
    */
    public function migrate_passwords()
    {
        // Add column if missing
        if (!$this->db->field_exists('password_hash', $this->table)) {
            $fields = [
                'password_hash' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE
                ]
            ];
            $this->dbforge->add_column($this->table, $fields);
        }
        // Migrate existing plaintext passwords (if any) to hashes
        $admins = $this->db->select('admin_id, password')->where('password IS NOT NULL')->get($this->table)->result();
        foreach ($admins as $admin) {
            if (password_get_info($admin->password)['algo'] === 0) { // not a hash
                $hash = password_hash($admin->password, PASSWORD_BCRYPT);
                $this->db->where('admin_id', $admin->admin_id)
                    ->update($this->table, ['password_hash' => $hash]);
            }
        }
    }
}
?>
