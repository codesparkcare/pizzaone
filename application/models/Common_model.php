<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->check_and_migrate_schema();
    }

    private function check_and_migrate_schema() {
        if ($this->session && $this->session->userdata('db_schema_migrated_v3')) {
            return;
        }

        try {
            // Ensure wishlists table exists
            $this->ensure_wishlists_table();
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

            // 4. Auto repair products with price = 0 that have product_sizes
            if ($this->db->table_exists('products') && $this->db->table_exists('product_sizes')) {
                $zero_products = $this->db->query("SELECT id FROM products WHERE price = 0 OR price IS NULL")->result();
                foreach ($zero_products as $zp) {
                    $min_ps = $this->db->query("SELECT MIN(price) as min_p FROM product_sizes WHERE product_id = ?", [$zp->id])->row();
                    if ($min_ps && $min_ps->min_p > 0) {
                        $this->db->query("UPDATE products SET price = ? WHERE id = ?", [$min_ps->min_p, $zp->id]);
                    }
                }
            }

            // 5. Sync category_sizes for all product_sizes
            if ($this->db->table_exists('products') && $this->db->table_exists('product_sizes') && $this->db->table_exists('category_sizes')) {
                $missing_links = $this->db->query("
                    SELECT DISTINCT p.category_id, ps.size_id 
                    FROM product_sizes ps
                    JOIN products p ON p.id = ps.product_id
                    LEFT JOIN category_sizes cs ON cs.category_id = p.category_id AND cs.size_id = ps.size_id
                    WHERE p.category_id IS NOT NULL AND p.category_id > 0 AND cs.category_id IS NULL
                ")->result();

                foreach ($missing_links as $ml) {
                    $this->db->query("INSERT INTO category_sizes (category_id, size_id) VALUES (?, ?)", [$ml->category_id, $ml->size_id]);
                }
            }

            // 6. Ensure 'addon_size_prices' table exists
            if (!$this->db->table_exists('addon_size_prices')) {
                $this->db->query("CREATE TABLE IF NOT EXISTS addon_size_prices (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    addon_id INT NOT NULL,
                    size_id INT NOT NULL,
                    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                    UNIQUE KEY addon_size (addon_id, size_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            }

            // 8. Ensure payment_settings table exists
            $this->ensure_payment_settings_table();

            // 9. Ensure user_addresses table exists
            $this->ensure_user_addresses_table();

            if ($this->session) {
                $this->session->set_userdata('db_schema_migrated_v5', true);
            }
        } catch (Exception $e) {
            log_message('error', 'Auto migration exception: ' . $e->getMessage());
        }
    }

    public function ensure_user_addresses_table() {
        if (!$this->db->table_exists('user_addresses')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS user_addresses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                label VARCHAR(50) DEFAULT 'Maison',
                address TEXT NOT NULL,
                city VARCHAR(100) DEFAULT NULL,
                postal_code VARCHAR(20) DEFAULT NULL,
                is_default TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                KEY user_id (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Migrate existing addresses from users table
            $this->db->query("INSERT INTO user_addresses (user_id, label, address, is_default)
                SELECT u.id, 'Maison', u.address, 1
                FROM users u
                WHERE u.address IS NOT NULL AND TRIM(u.address) != ''
                  AND NOT EXISTS (
                    SELECT 1 FROM user_addresses ua WHERE ua.user_id = u.id
                  );");
        }
    }

    public function get_user_addresses($user_id) {
        $this->ensure_user_addresses_table();
        return $this->db->where('user_id', $user_id)->order_by('is_default', 'DESC')->order_by('id', 'DESC')->get('user_addresses')->result();
    }

    public function add_user_address($user_id, $data) {
        $this->ensure_user_addresses_table();
        // If this is set as default or is the user's first address, set is_default = 1
        $count = $this->db->where('user_id', $user_id)->count_all_results('user_addresses');
        $is_default = (!empty($data['is_default']) || $count === 0) ? 1 : 0;
        
        if ($is_default) {
            $this->db->where('user_id', $user_id)->update('user_addresses', ['is_default' => 0]);
        }

        $insert_data = [
            'user_id'     => $user_id,
            'label'       => !empty($data['label']) ? trim($data['label']) : 'Maison',
            'address'     => trim($data['address']),
            'city'        => !empty($data['city']) ? trim($data['city']) : null,
            'postal_code' => !empty($data['postal_code']) ? trim($data['postal_code']) : null,
            'is_default'  => $is_default,
        ];
        $this->db->insert('user_addresses', $insert_data);
        $new_id = $this->db->insert_id();

        // Also sync to users.address if default
        if ($is_default) {
            $this->db->where('id', $user_id)->update('users', ['address' => trim($data['address'])]);
        }

        return $new_id;
    }

    public function delete_user_address($user_id, $address_id) {
        $this->ensure_user_addresses_table();
        $addr = $this->db->get_where('user_addresses', ['id' => $address_id, 'user_id' => $user_id])->row();
        if ($addr) {
            $was_default = $addr->is_default;
            $this->db->delete('user_addresses', ['id' => $address_id, 'user_id' => $user_id]);
            
            // If the deleted address was default, promote another one
            if ($was_default) {
                $next = $this->db->where('user_id', $user_id)->order_by('id', 'DESC')->get('user_addresses')->row();
                if ($next) {
                    $this->db->where('id', $next->id)->update('user_addresses', ['is_default' => 1]);
                    $this->db->where('id', $user_id)->update('users', ['address' => $next->address]);
                }
            }
            return true;
        }
        return false;
    }

    public function set_default_user_address($user_id, $address_id) {
        $this->ensure_user_addresses_table();
        $addr = $this->db->get_where('user_addresses', ['id' => $address_id, 'user_id' => $user_id])->row();
        if ($addr) {
            $this->db->where('user_id', $user_id)->update('user_addresses', ['is_default' => 0]);
            $this->db->where('id', $address_id)->update('user_addresses', ['is_default' => 1]);
            $this->db->where('id', $user_id)->update('users', ['address' => $addr->address]);
            return true;
        }
        return false;
    }

    public function ensure_wishlists_table() {
        if (!$this->db->table_exists('wishlists')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS wishlists (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                KEY user_id (user_id),
                KEY product_id (product_id),
                UNIQUE KEY unique_user_product (user_id, product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function ensure_payment_settings_table() {
        if (!$this->db->table_exists('payment_settings')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS payment_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                payment_key VARCHAR(50) NOT NULL UNIQUE,
                name_fr VARCHAR(100) NOT NULL,
                name_en VARCHAR(100) NOT NULL,
                description_fr VARCHAR(255) DEFAULT NULL,
                description_en VARCHAR(255) DEFAULT NULL,
                icon VARCHAR(50) DEFAULT 'fas fa-credit-card',
                is_enabled TINYINT(1) NOT NULL DEFAULT 1,
                sort_order INT NOT NULL DEFAULT 0,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $this->db->query("INSERT INTO payment_settings (payment_key, name_fr, name_en, description_fr, description_en, icon, is_enabled, sort_order) VALUES 
                ('cash', 'Espèces à la livraison / retrait', 'Cash on Delivery / Pickup', 'Paiement en espèces lors de la livraison ou au comptoir', 'Pay with cash upon delivery or pickup at the store', 'fas fa-money-bill-wave', 1, 1),
                ('card', 'Carte bancaire', 'Credit / Debit Card', 'Paiement sécurisé par carte bancaire au livreur ou au comptoir', 'Secure payment by card to the driver or at the counter', 'fas fa-credit-card', 1, 2)
                ON DUPLICATE KEY UPDATE payment_key=payment_key;");
        }
    }

    public function get_active_payment_methods() {
        $this->ensure_payment_settings_table();
        return $this->db->where('is_enabled', 1)->order_by('sort_order', 'ASC')->get('payment_settings')->result();
    }

    public function get_all_payment_methods() {
        $this->ensure_payment_settings_table();
        return $this->db->order_by('sort_order', 'ASC')->get('payment_settings')->result();
    }

    public function insert($table, $data) {
        if ($table === 'wishlists') {
            $this->ensure_wishlists_table();
        }
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function get_all($table, $order_by = null, $order_type = 'DESC') {
        if ($table === 'wishlists') {
            $this->ensure_wishlists_table();
        }
        if ($order_by) {
            $this->db->order_by($order_by, $order_type);
        }
        return $this->db->get($table)->result();
    }

    public function get_where($table, $where) {
        if ($table === 'wishlists') {
            $this->ensure_wishlists_table();
        }
        return $this->db->get_where($table, $where)->result();
    }

    public function get_single($table, $where) {
        if ($table === 'wishlists') {
            $this->ensure_wishlists_table();
        }
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

    public function get_products_by_category($category_id = null, $shop_id = null, $limit = null, $offset = null) {
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
        if ($limit !== null) {
            if ($offset !== null) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        $products = $this->db->get()->result();
        $this->attach_sizes($products);
        return $products;
    }

    public function get_products_by_category_count($category_id = null, $shop_id = null) {
        if ($shop_id === null && $this->session->userdata('selected_shop_id')) {
            $shop_id = $this->session->userdata('selected_shop_id');
        }
        $this->db->from('products');
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
        return $this->db->count_all_results();
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

    public function search_products($query, $shop_id = null) {
        if ($shop_id === null && $this->session->userdata('selected_shop_id')) {
            $shop_id = $this->session->userdata('selected_shop_id');
        }
        $this->db->select('products.*, categories.name as category_name, COALESCE(subcats.name, "") as subcategory_name, offers.offer_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('categories as subcats', 'subcats.id = products.subcategory_id AND products.subcategory_id IS NOT NULL', 'left');
        $this->db->join('offers', 'offers.id = products.offer_id', 'left');
        if (!empty($query)) {
            $this->db->group_start();
            $this->db->like('products.name', $query);
            $this->db->or_like('products.description', $query);
            $this->db->or_like('categories.name', $query);
            $this->db->group_end();
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
     * Attach size-specific prices to addon objects/arrays
     */
    public function attach_size_prices(&$addons) {
        if (empty($addons)) return;
        if (!$this->db->table_exists('addon_size_prices')) return;

        foreach ($addons as &$addon) {
            $addon_id = is_object($addon) ? ($addon->id ?? null) : (isset($addon['id']) ? $addon['id'] : null);
            if (!$addon_id) continue;

            $prices = $this->db->query("
                SELECT asp.size_id, asp.price, s.name as size_name 
                FROM addon_size_prices asp 
                JOIN sizes s ON s.id = asp.size_id 
                WHERE asp.addon_id = ?
            ", [$addon_id])->result();

            $size_prices_map = [];
            $size_prices_by_name = [];
            foreach ($prices as $p) {
                $size_prices_map[$p->size_id] = floatval($p->price);
                $size_prices_by_name[strtolower(trim($p->size_name))] = floatval($p->price);
            }
            if (is_object($addon)) {
                $addon->size_prices = $size_prices_map;
                $addon->size_prices_by_name = $size_prices_by_name;
            } else {
                $addon['size_prices'] = $size_prices_map;
                $addon['size_prices_by_name'] = $size_prices_by_name;
            }
        }
    }

    /**
     * Get all addons in a specific addon group
     */
    public function get_addons_in_group($group_id) {
        $this->db->select('addons.*');
        $this->db->from('addons');
        $this->db->join('addon_group_items', 'addon_group_items.addon_id = addons.id');
        $this->db->where('addon_group_items.group_id', $group_id);
        $addons = $this->db->get()->result();
        $this->attach_size_prices($addons);
        return $addons;
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

    /**
     * Send email notification to Super Admin on new order
     */
    public function send_order_admin_notification($order_id, $order_data, $cart_items, $shop = null) {
        try {
            if (!$this->db->table_exists('smtp_settings')) {
                return false;
            }
            $smtp = $this->db->get('smtp_settings')->row();
            if (!$smtp || !$smtp->is_active) {
                return false;
            }

            $admin_email = 'pizzaone95130@gmail.com';
            $from_email  = !empty($smtp->from_email) ? $smtp->from_email : (!empty($smtp->smtp_user) ? $smtp->smtp_user : 'commande@pizzaonerestaurant.com');
            $from_name   = !empty($smtp->from_name) ? $smtp->from_name : 'Pizza One';

            $config = [
                'protocol'    => 'smtp',
                'smtp_host'   => $smtp->smtp_host,
                'smtp_port'   => (int)$smtp->smtp_port,
                'smtp_crypto' => $smtp->smtp_crypto,
                'smtp_user'   => $smtp->smtp_user,
                'smtp_pass'   => $smtp->smtp_pass,
                'mailtype'    => 'html',
                'charset'     => 'utf-8',
                'wordwrap'    => TRUE,
                'newline'     => "\r\n",
                'crlf'        => "\r\n"
            ];

            $ci = &get_instance();
            $ci->load->library('email');
            $ci->email->initialize($config);

            $ci->email->from($from_email, $from_name);
            $ci->email->to($admin_email);
            $ci->email->cc('ak812282@gmail.com');

            $shop_name = $shop ? (is_object($shop) ? $shop->name : ($shop['name'] ?? 'Pizza One')) : 'Pizza One';
            $total_val = number_format(floatval($order_data['total'] ?? $order_data['total_amount'] ?? 0), 2);
            $order_type_str = (($order_data['order_type'] ?? '') === 'collect') ? 'À emporter (Click & Collect)' : 'Livraison à domicile';
            $payment_str = (($order_data['payment_method'] ?? '') === 'cash') ? 'Espèces à la livraison / retrait' : 'Carte bancaire';

            $subject = "🍕 Nouvelle Commande #{$order_id} - {$shop_name} (€{$total_val})";
            $ci->email->subject($subject);

            // Build HTML items rows
            $items_html = '';
            if (!empty($cart_items)) {
                foreach ($cart_items as $item) {
                    $qty = $item['quantity'] ?? 1;
                    $pname = htmlspecialchars($item['product_name'] ?? 'Produit');
                    $itotal = number_format(floatval($item['item_total'] ?? 0), 2);
                    $items_html .= "
                    <tr style='border-bottom: 1px solid #f1f5f9;'>
                        <td style='padding: 12px 10px; color: #1e293b; font-weight: 600;'>{$pname}</td>
                        <td style='padding: 12px 10px; text-align: center; color: #64748b;'>x{$qty}</td>
                        <td style='padding: 12px 10px; text-align: right; color: #1e293b; font-weight: 600;'>€{$itotal}</td>
                    </tr>";
                }
            }

            $customer_name  = htmlspecialchars($order_data['customer_name'] ?? 'Client');
            $customer_phone = htmlspecialchars($order_data['customer_phone'] ?? 'N/A');
            $customer_addr  = htmlspecialchars($order_data['customer_address'] ?? 'N/A');
            $notes          = htmlspecialchars($order_data['notes'] ?? '');
            $created_at     = date('d/m/Y à H:i', strtotime($order_data['created_at'] ?? 'now'));
            $subtotal_val   = number_format(floatval($order_data['subtotal'] ?? 0), 2);
            $delivery_val   = number_format(floatval($order_data['delivery_fee'] ?? 0), 2);
            $admin_url      = base_url('admin/orders');

            $notes_block = '';
            if (!empty($notes)) {
                $notes_block = "
                <div style='margin-top: 15px; padding: 12px 15px; background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 4px;'>
                    <strong style='color: #92400e; font-size: 13px;'>📝 Instructions spéciales :</strong>
                    <p style='margin: 4px 0 0 0; color: #78350f; font-size: 14px;'>{$notes}</p>
                </div>";
            }

            $body = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <title>{$subject}</title>
            </head>
            <body style='margin:0; padding:20px; background-color:#f4f7f6; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>
                <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td align='center'>
                            <table width='600' style='max-width:600px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);' border='0' cellspacing='0' cellpadding='0'>
                                
                                <!-- Header -->
                                <tr>
                                    <td style='background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); padding: 30px; text-align: center; color: #ffffff;'>
                                        <h1 style='margin:0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;'>🍕 PIZZA ONE</h1>
                                        <p style='margin: 6px 0 0 0; font-size: 16px; opacity: 0.95;'>Nouvelle commande reçue !</p>
                                        <div style='display: inline-block; margin-top: 12px; background: rgba(255,255,255,0.2); padding: 4px 14px; border-radius: 20px; font-size: 14px; font-weight: 600;'>
                                            Commande #{$order_id}
                                        </div>
                                    </td>
                                </tr>

                                <!-- Content Body -->
                                <tr>
                                    <td style='padding: 25px 30px;'>
                                        
                                        <!-- Order Info Badges -->
                                        <table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-bottom: 20px;'>
                                            <tr>
                                                <td width='50%' style='padding: 10px; background: #f8fafc; border-radius: 8px;'>
                                                    <div style='font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700;'>📍 Magasin</div>
                                                    <div style='font-size: 15px; font-weight: 700; color: #1e293b; margin-top: 3px;'>{$shop_name}</div>
                                                </td>
                                                <td width='10'></td>
                                                <td width='50%' style='padding: 10px; background: #f8fafc; border-radius: 8px;'>
                                                    <div style='font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700;'>🛵 Mode</div>
                                                    <div style='font-size: 15px; font-weight: 700; color: #1e293b; margin-top: 3px;'>{$order_type_str}</div>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Customer Box -->
                                        <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 20px;'>
                                            <h3 style='margin: 0 0 12px 0; font-size: 15px; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;'>
                                                👤 Informations Client
                                            </h3>
                                            <table width='100%' style='font-size: 14px; color: #334155;'>
                                                <tr>
                                                    <td style='padding: 3px 0; width: 100px; color: #64748b;'><strong>Nom :</strong></td>
                                                    <td style='padding: 3px 0; font-weight: 600; color: #0f172a;'>{$customer_name}</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 3px 0; color: #64748b;'><strong>Téléphone :</strong></td>
                                                    <td style='padding: 3px 0; font-weight: 600; color: #e74c3c;'>
                                                        <a href='tel:{$customer_phone}' style='color: #e74c3c; text-decoration: none;'>{$customer_phone}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 3px 0; color: #64748b;'><strong>Adresse :</strong></td>
                                                    <td style='padding: 3px 0; font-weight: 500;'>{$customer_addr}</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 3px 0; color: #64748b;'><strong>Paiement :</strong></td>
                                                    <td style='padding: 3px 0; font-weight: 600;'>{$payment_str}</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 3px 0; color: #64748b;'><strong>Date :</strong></td>
                                                    <td style='padding: 3px 0;'>{$created_at}</td>
                                                </tr>
                                            </table>
                                            {$notes_block}
                                        </div>

                                        <!-- Items List -->
                                        <h3 style='margin: 20px 0 10px 0; font-size: 15px; color: #1e293b;'>📋 Articles commandés</h3>
                                        <table width='100%' border='0' cellspacing='0' cellpadding='0' style='border-collapse: collapse; font-size: 14px;'>
                                            <thead>
                                                <tr style='background: #f1f5f9; text-transform: uppercase; font-size: 11px; color: #475569;'>
                                                    <th style='padding: 10px; text-align: left;'>Article</th>
                                                    <th style='padding: 10px; text-align: center;'>Quantité</th>
                                                    <th style='padding: 10px; text-align: right;'>Prix</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$items_html}
                                            </tbody>
                                        </table>

                                        <!-- Totals -->
                                        <table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-top: 15px; font-size: 14px;'>
                                            <tr>
                                                <td style='padding: 4px 10px; text-align: right; color: #64748b;'>Sous-total :</td>
                                                <td style='padding: 4px 10px; text-align: right; width: 100px; font-weight: 600; color: #1e293b;'>€{$subtotal_val}</td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 4px 10px; text-align: right; color: #64748b;'>Frais de livraison :</td>
                                                <td style='padding: 4px 10px; text-align: right; font-weight: 600; color: #1e293b;'>€{$delivery_val}</td>
                                            </tr>
                                            <tr style='border-top: 2px solid #e2e8f0;'>
                                                <td style='padding: 12px 10px; text-align: right; font-size: 16px; font-weight: 700; color: #0f172a;'>TOTAL :</td>
                                                <td style='padding: 12px 10px; text-align: right; font-size: 18px; font-weight: 800; color: #e74c3c;'>€{$total_val}</td>
                                            </tr>
                                        </table>

                                        <!-- Action Button -->
                                        <div style='text-align: center; margin-top: 25px;'>
                                            <a href='{$admin_url}' style='background: #e74c3c; color: #ffffff; padding: 12px 28px; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; display: inline-block; box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);'>
                                                Gérer cette commande dans l'Admin &rarr;
                                            </a>
                                        </div>

                                    </td>
                                </tr>

                                <!-- Footer -->
                                <tr>
                                    <td style='background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0;'>
                                        Cet email a été généré automatiquement par le système de commande en ligne <strong>Pizza One</strong>.<br>
                                        Expéditeur configuré : {$from_email}
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>";

            $ci->email->message($body);
            return $ci->email->send();
        } catch (\Exception $e) {
            log_message('error', 'Failed to send admin order email: ' . $e->getMessage());
            return false;
        }
    }
}
?>
