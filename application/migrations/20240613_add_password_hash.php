<?php
define('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add password_hash column to admins table
 * Timestamp: 20240613
 */
class Migration_Add_password_hash extends CI_Migration {
    public function up() {
        $this->load->dbforge();
        // Add password_hash column if it does not exist
        if (!$this->db->field_exists('password_hash', 'admins')) {
            $fields = [
                'password_hash' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE,
                ],
            ];
            $this->dbforge->add_column('admins', $fields);
        }
    }
    public function down() {
        $this->load->dbforge();
        if ($this->db->field_exists('password_hash', 'admins')) {
            $this->dbforge->drop_column('admins', 'password_hash');
        }
    }
}
?>
