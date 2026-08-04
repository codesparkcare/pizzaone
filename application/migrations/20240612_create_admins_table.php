<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Create admins table
 * Timestamp: 20240612
 */
class Migration_Create_admins_table extends CI_Migration {
    public function up()
    {
        // Load dbforge
        $this->load->dbforge();

        // Define fields
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
            // Legacy plain password (optional, may be NULL after migration)
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            // Bcrypt hash – preferred authentication method
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
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('admin_id', TRUE); // Primary key
        $this->dbforge->create_table('admins', TRUE);
    }

    public function down()
    {
        $this->load->dbforge();
        $this->dbforge->drop_table('admins', TRUE);
    }
}
?>
