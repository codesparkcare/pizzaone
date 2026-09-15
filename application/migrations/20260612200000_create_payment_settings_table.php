<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_payment_settings_table extends CI_Migration {
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
            'payment_key' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE,
                'unique' => TRUE,
            ],
            'name_fr' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE,
            ],
            'name_en' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE,
            ],
            'description_fr' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'description_en' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'fas fa-credit-card',
            ],
            'is_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payment_settings', TRUE);

        // Seed default methods
        $this->db->query("INSERT INTO payment_settings (payment_key, name_fr, name_en, description_fr, description_en, icon, is_enabled, sort_order) VALUES 
            ('cash', 'Espèces à la livraison / retrait', 'Cash on Delivery / Pickup', 'Paiement en espèces lors de la livraison ou au comptoir', 'Pay with cash upon delivery or pickup at the store', 'fas fa-money-bill-wave', 1, 1),
            ('card', 'Carte bancaire', 'Credit / Debit Card', 'Paiement sécurisé par carte bancaire au livreur ou au comptoir', 'Secure payment by card to the driver or at the counter', 'fas fa-credit-card', 1, 2)
            ON DUPLICATE KEY UPDATE payment_key=payment_key;");
    }

    public function down()
    {
        $this->load->dbforge();
        $this->dbforge->drop_table('payment_settings', TRUE);
    }
}
?>
