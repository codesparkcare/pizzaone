<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ensure admins table has a 'role' column.
 * Run via CLI: php index.php alter_admins
 */
class Alter_admins extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
        $this->load->database();
    }

    public function index() {
        if (!$this->db->field_exists('role', 'admins')) {
            $fields = [
                'role' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => FALSE,
                    'default' => 'admin'
                ]
            ];
            $this->dbforge->add_column('admins', $fields);
            echo "[OK] Added 'role' column to admins table.\n";
        } else {
            echo "[SKIP] 'role' column already exists.\n";
        }
    }
}
?>
