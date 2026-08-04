<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_shop_users_table extends CI_Migration {

    public function up() {
        $this->load->dbforge();
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE,
                'unique' => TRUE,
            ],
            'password_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'shop_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('shop_users', TRUE);
    }

    public function down() {
        $this->load->dbforge();
        $this->dbforge->drop_table('shop_users', TRUE);
    }
}
