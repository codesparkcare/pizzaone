<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_offers_table extends CI_Migration {
    public function up()
    {
        $this->load->dbforge();

        // Offers table
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'offer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('offers', TRUE);

        // Add offer_id to products
        $product_fields = [
            'offer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'default' => NULL,
                'after' => 'category_id'
            ]
        ];
        $this->dbforge->add_column('products', $product_fields);
    }

    public function down()
    {
        $this->load->dbforge();
        $this->dbforge->drop_table('offers', TRUE);
        $this->dbforge->drop_column('products', 'offer_id');
    }
}
?>
