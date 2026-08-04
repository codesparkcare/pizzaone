<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function get_all($table, $order_by = null, $order_type = 'DESC') {
        if ($order_by) {
            $this->db->order_by($order_by, $order_type);
        }
        return $this->db->get($table)->result();
    }

    public function get_where($table, $where) {
        return $this->db->get_where($table, $where)->result();
    }

    public function get_single($table, $where) {
        return $this->db->get_where($table, $where)->row();
    }

    public function update($table, $where, $data) {
        return $this->db->update($table, $data, $where);
    }

    public function delete($table, $where) {
        return $this->db->delete($table, $where);
    }

    public function get_count($table, $where = null) {
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($table);
    }

    public function get_products_with_category() {
        $this->db->select('products.*, categories.name as category_name, COALESCE(subcats.name, "") as subcategory_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('categories as subcats', 'subcats.id = products.subcategory_id AND products.subcategory_id IS NOT NULL', 'left');
        $this->db->order_by('products.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_products_by_category($category_id = null) {
        $this->db->select('products.*, categories.name as category_name, COALESCE(subcats.name, "") as subcategory_name, offers.offer_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('categories as subcats', 'subcats.id = products.subcategory_id AND products.subcategory_id IS NOT NULL', 'left');
        $this->db->join('offers', 'offers.id = products.offer_id', 'left');
        if ($category_id) {
            $this->db->where('products.category_id', $category_id);
        }
        $this->db->where('products.status', 1);
        $this->db->order_by('products.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_products_by_subcategory($subcategory_id) {
        $this->db->select('products.*, categories.name as category_name, COALESCE(subcats.name, "") as subcategory_name, offers.offer_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('categories as subcats', 'subcats.id = products.subcategory_id AND products.subcategory_id IS NOT NULL', 'left');
        $this->db->join('offers', 'offers.id = products.offer_id', 'left');
        $this->db->where('products.subcategory_id', $subcategory_id);
        $this->db->where('products.status', 1);
        $this->db->order_by('products.id', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get addon groups for a product with all their items
     */
    public function get_addon_groups_by_product($product_id) {
        $this->db->select('pag.*, ag.name as group_name, ag.description, ag.min_selections as group_min, ag.max_selections as group_max');
        $this->db->from('product_addon_groups as pag');
        $this->db->join('addon_groups as ag', 'ag.id = pag.group_id', 'left');
        $this->db->where('pag.product_id', $product_id);
        $this->db->order_by('pag.sort_order', 'ASC');
        
        $groups = $this->db->get()->result();
        
        // Add items to each group
        foreach ($groups as $group) {
            $group->items = $this->get_addons_in_group($group->group_id);
            if (isset($group->group_min)) {
                $group->min_selections = intval($group->group_min);
            }
            if (isset($group->group_max)) {
                $group->max_selections = intval($group->group_max);
            }
            $group->is_required = ($group->min_selections > 0) ? 1 : 0;
        }
        
        return $groups;
    }

    /**
     * Get all addons in a specific addon group
     */
    public function get_addons_in_group($group_id) {
        $this->db->select('addons.*');
        $this->db->from('addons');
        $this->db->join('addon_group_items', 'addon_group_items.addon_id = addons.id');
        $this->db->where('addon_group_items.group_id', $group_id);
        return $this->db->get()->result();
    }
}
?>
