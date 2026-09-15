<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_user_addresses_table extends CI_Migration {
    public function up()
    {
        $this->load->dbforge();

        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => FALSE,
                'auto_increment' => TRUE,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
            ],
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'Maison',
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => FALSE,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
            ],
            'postal_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
            ],
            'is_default' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->create_table('user_addresses', TRUE);

        // Migrate existing addresses from users table
        $this->db->query("INSERT INTO user_addresses (user_id, label, address, is_default)
            SELECT u.id, 'Maison', u.address, 1
            FROM users u
            WHERE u.address IS NOT NULL AND TRIM(u.address) != ''
              AND NOT EXISTS (
                SELECT 1 FROM user_addresses ua WHERE ua.user_id = u.id
              )");
    }

    public function down()
    {
        $this->load->dbforge();
        $this->dbforge->drop_table('user_addresses', TRUE);
    }
}
?>
