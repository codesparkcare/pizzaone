<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_reviews_table extends CI_Migration {
    public function up()
    {
        $this->load->dbforge();

        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'rating' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 5,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 1, // 1 = Active, 0 = Inactive
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('reviews', TRUE);
    }

    public function down()
    {
        $this->load->dbforge();
        $this->dbforge->drop_table('reviews', TRUE);
    }
}
?>
