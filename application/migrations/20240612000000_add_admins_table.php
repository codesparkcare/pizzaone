<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add admins table (timestamp version)
 */
class Migration_Add_admins_table extends CI_Migration {
    public function up() {
        $this->load->dbforge();
        $fields = [
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE,
                'unique' => TRUE,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'password_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('admin_id', TRUE);
        $this->dbforge->create_table('admins', TRUE);
    }
    public function down() {
        $this->load->dbforge();
        $this->dbforge->drop_table('admins', TRUE);
    }
}
?>
