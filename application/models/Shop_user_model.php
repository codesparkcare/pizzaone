<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop_user_model extends CI_Model {
    protected $table = 'shop_users';

    public function get_all_with_shop() {
        $this->db->select('shop_users.*, shops.name as shop_name');
        $this->db->from($this->table);
        $this->db->join('shops', 'shops.id = shop_users.shop_id', 'left');
        $this->db->order_by('shop_users.id', 'DESC');
        return $this->db->get()->result();
    }

    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);
        return $this->db->insert($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
