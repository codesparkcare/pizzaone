<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->check_and_migrate_schema();
    }

    private function check_and_migrate_schema() {
        if ($this->session && $this->session->userdata('db_schema_migrated')) {
            return;
        }

        try {
            // 1. Check if 'shops' column exists in 'products' table
            if ($this->db->table_exists('products') && !$this->db->field_exists('shops', 'products')) {
                $this->db->query("ALTER TABLE products ADD COLUMN shops VARCHAR(255) NULL DEFAULT '1,2'");
                $this->db->query("UPDATE products SET shops = '1,2' WHERE shops IS NULL OR shops = ''");
            }

            // 2. Check if 'user_id' column exists in 'orders' table
            if ($this->db->table_exists('orders') && !$this->db->field_exists('user_id', 'orders')) {
                $this->db->query("ALTER TABLE orders ADD COLUMN user_id INT(11) NULL DEFAULT NULL AFTER shop_id, ADD INDEX (user_id)");
            }

            // 3. Ensure 'shops' table exists
            if (!$this->db->table_exists('shops')) {
                $this->db->query("CREATE TABLE IF NOT EXISTS shops (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    address TEXT,
                    phone VARCHAR(50),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

                $this->db->query("INSERT INTO shops (id, name, address, phone) VALUES 
                    (1, 'Villiers-le-bel', '11 Place de la Tolinette, 95400 Villiers Le Bel', '01 34 19 94 56'),
                    (2, 'Le Plessis-Bouchard', 'Commercial des Hauts de Saint-Nicolas, 95130 Le Plessis-Bouchard', '01 34 14 15 16')
                    ON DUPLICATE KEY UPDATE name=VALUES(name);");
            }

            if ($this->session) {
                $this->session->set_userdata('db_schema_migrated', true);
            }
        } catch (Exception $e) {
            log_message('error', 'Auto migration exception: ' . $e->getMessage());
        }
    }

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

    public function attach_sizes(&$products) {
        if (empty($products)) return;
        foreach ($products as &$product) {
            $this->db->select('sizes.name as size_name, product_sizes.price as size_price');
            $this->db->from('product_sizes');
            $this->db->join('sizes', 'sizes.id = product_sizes.size_id');
            $this->db->where('product_sizes.product_id', $product->id);
            $this->db->order_by('product_sizes.price', 'ASC');
            $product->sizes = $this->db->get()->result();
        }
    }

    public function get_products_with_category() {
        $this->db->select('products.*, categories.name as category_name, COALESCE(subcats.name, "") as subcategory_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('categories as subcats', 'subcats.id = products.subcategory_id AND products.subcategory_id IS NOT NULL', 'left');
        $this->db->order_by('products.id', 'DESC');
        $products = $this->db->get()->result();
        $this->attach_sizes($products);
        return $products;
    }

    public function get_products_by_category($category_id = null, $shop_id = null) {
        if ($shop_id === null && $this->session->userdata('selected_shop_id')) {
            $shop_id = $this->session->userdata('selected_shop_id');
        }
        $this->db->select('products.*, categories.name as category_name, COALESCE(subcats.name, "") as subcategory_name, offers.offer_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('categories as subcats', 'subcats.id = products.subcategory_id AND products.subcategory_id IS NOT NULL', 'left');
        $this->db->join('offers', 'offers.id = products.offer_id', 'left');
        if ($category_id) {
            $this->db->where('products.category_id', $category_id);
        }
        if ($shop_id) {
            $this->db->group_start();
            $this->db->where('products.shops IS NULL', null, false);
            $this->db->or_where("products.shops = ''", null, false);
            $this->db->or_where("FIND_IN_SET(" . intval($shop_id) . ", products.shops) > 0", null, false);
            $this->db->group_end();
        }
        $this->db->where('products.status', 1);
        $this->db->order_by('products.id', 'DESC');
        $products = $this->db->get()->result();
        $this->attach_sizes($products);
        return $products;
    }

    public function get_products_by_subcategory($subcategory_id, $shop_id = null) {
        if ($shop_id === null && $this->session->userdata('selected_shop_id')) {
            $shop_id = $this->session->userdata('selected_shop_id');
        }
        $this->db->select('products.*, categories.name as category_name, COALESCE(subcats.name, "") as subcategory_name, offers.offer_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('categories as subcats', 'subcats.id = products.subcategory_id AND products.subcategory_id IS NOT NULL', 'left');
        $this->db->join('offers', 'offers.id = products.offer_id', 'left');
        $this->db->where('products.subcategory_id', $subcategory_id);
        if ($shop_id) {
            $this->db->group_start();
            $this->db->where('products.shops IS NULL', null, false);
            $this->db->or_where("products.shops = ''", null, false);
            $this->db->or_where("FIND_IN_SET(" . intval($shop_id) . ", products.shops) > 0", null, false);
            $this->db->group_end();
        }
        $this->db->where('products.status', 1);
        $this->db->order_by('products.id', 'DESC');
        $products = $this->db->get()->result();
        $this->attach_sizes($products);
        return $products;
    }

    /**
     * Get addon groups for a product with all their items
     */
    public function get_addon_groups_by_product($product_id) {
        $this->db->select('pag.*, ag.name as group_name, ag.description, ag.min_selections as group_min, ag.max_selections as group_max');
        $this->db->from('product_addon_groups as pag');
        $this->db->join('addon_groups as ag', 'ag.id = pag.group_id', 'left');
        $this->db->where('pag.product_id', $product_id);
        $this->db->order_by("(CASE WHEN LOWER(ag.name) LIKE '%extra%' OR LOWER(ag.name) LIKE '%supplement%' THEN 1 ELSE 0 END)", "ASC", FALSE);
        $this->db->order_by('pag.sort_order', 'ASC');
        $this->db->order_by('pag.id', 'DESC');
        
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
    public function get_user_wishlist($user_id) {
        $this->db->select('products.*, categories.name as category_name, COALESCE(subcats.name, "") as subcategory_name, offers.offer_name, wishlists.id as wishlist_id');
        $this->db->from('wishlists');
        $this->db->join('products', 'products.id = wishlists.product_id');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('categories as subcats', 'subcats.id = products.subcategory_id AND products.subcategory_id IS NOT NULL', 'left');
        $this->db->join('offers', 'offers.id = products.offer_id', 'left');
        $this->db->where('wishlists.user_id', $user_id);
        $this->db->order_by('wishlists.id', 'DESC');
        $products = $this->db->get()->result();
        $this->attach_sizes($products);
        return $products;
    }
}
?>
