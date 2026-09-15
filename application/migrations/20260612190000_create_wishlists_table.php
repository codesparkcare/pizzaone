<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_wishlists_table extends CI_Migration {
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
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('product_id');
        $this->dbforge->create_table('wishlists', TRUE);
    }

    public function down()
    {
        $this->load->dbforge();
        $this->dbforge->drop_table('wishlists', TRUE);
    }
}
?>
