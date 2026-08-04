<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_password_hash_to_admins extends CI_Migration {
    public function up() {
        // Load dbforge
        $this->load->dbforge();
        // Add password_hash column if it does not exist
        if (!$this->db->field_exists('password_hash', 'admins')) {
            $fields = array(
                'password_hash' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE,
                ),
            );
            $this->dbforge->add_column('admins', $fields);
        }
        // Migrate any existing plain passwords to bcrypt hashes
        $query = $this->db->select('admin_id, password')->where('password IS NOT NULL')->get('admins');
        foreach ($query->result() as $admin) {
            // If password already looks like a hash, skip
            if (password_get_info($admin->password)['algo'] === 0) {
                $hash = password_hash($admin->password, PASSWORD_BCRYPT);
                $this->db->where('admin_id', $admin->admin_id)
                         ->update('admins', array('password_hash' => $hash));
            }
        }
    }

    public function down() {
        // Remove password_hash column if needed
        if ($this->db->field_exists('password_hash', 'admins')) {
            $this->dbforge->drop_column('admins', 'password_hash');
        }
    }
}
?>
