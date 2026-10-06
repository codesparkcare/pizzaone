<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Handle CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        
        if ($this->input->method() === 'options') {
            http_response_code(200);
            exit();
        }

        $this->load->model('Common_model');
        $this->load->model('Admin_user_model');
        $this->Common_model->ensure_orders_fcm_schema();
    }

    private function json_response($data, $status_code = 200) {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))
            ->_display();
        exit();
    }

    private function get_request_data() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (is_array($data) && !empty($data)) {
            return $data;
        }
        return $this->input->post() ?: [];
    }

    /**
     * Health check / Ping
     */
    public function index() {
        $this->json_response([
            'status' => 'success',
            'app' => 'Pizza One Order Management API',
            'version' => '1.0.0',
            'server_time' => date('Y-m-d H:i:s'),
            'timestamp' => time()
        ]);
    }

    /**
     * Admin / Staff Login
     */
    public function login() {
        $data = $this->get_request_data();
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if (empty($username) || empty($password)) {
            $this->json_response([
                'status' => 'error',
                'message' => 'Identifiant et mot de passe requis.'
            ], 400);
        }

        // Auto-seed default admins if needed
        $this->Admin_user_model->seed_default_admins();

        // 1. Check in admins table
        $admin = $this->Common_model->get_single('admins', ['username' => $username]);
        if ($admin && (
            (!empty($admin->password_hash) && password_verify($password, $admin->password_hash)) ||
            (empty($admin->password_hash) && $admin->password === $password)
        )) {
            $token = bin2hex(random_bytes(24));
            $this->json_response([
                'status' => 'success',
                'message' => 'Connexion réussie',
                'token' => $token,
                'user' => [
                    'id' => (int)$admin->admin_id,
                    'username' => $admin->username,
                    'role' => $admin->role, // super_admin, admin
                    'shop_id' => null, // null means all shops
                    'shop_name' => 'Toutes les boutiques'
                ]
            ]);
        }

        // 2. Check in shop_users table (assigned to a specific shop)
        $shop_user = $this->Common_model->get_single('shop_users', ['username' => $username]);
        if ($shop_user && password_verify($password, $shop_user->password_hash)) {
            $shop = $this->Common_model->get_single('shops', ['id' => $shop_user->shop_id]);
            $token = bin2hex(random_bytes(24));
            $this->json_response([
                'status' => 'success',
                'message' => 'Connexion réussie',
                'token' => $token,
                'user' => [
                    'id' => (int)$shop_user->id,
                    'username' => $shop_user->username,
                    'role' => 'staff',
                    'shop_id' => (int)$shop_user->shop_id,
                    'shop_name' => $shop ? $shop->name : 'Boutique #' . $shop_user->shop_id
                ]
            ]);
        }

        $this->json_response([
            'status' => 'error',
            'message' => 'Identifiant ou mot de passe incorrect.'
        ], 401);
    }

    /**
     * Get All Active Shops
     */
    public function shops() {
        $shops = $this->db->get_where('shops', ['is_active' => 1])->result();
        $this->json_response([
            'status' => 'success',
            'shops' => $shops
        ]);
    }

    /**
     * Dashboard Statistics
     */
    public function dashboard() {
        $shop_id = $this->input->get('shop_id');
        $today = date('Y-m-d');

        $this->db->from('orders');
        $this->db->where('DATE(created_at)', $today);
        if (!empty($shop_id)) {
            $this->db->where('shop_id', (int)$shop_id);
        }
        $today_orders_count = $this->db->count_all_results();

        // Revenue today (excluding cancelled)
        $this->db->select_sum('total_amount');
        $this->db->from('orders');
        $this->db->where('DATE(created_at)', $today);
        $this->db->where('status !=', 'cancelled');
        if (!empty($shop_id)) {
            $this->db->where('shop_id', (int)$shop_id);
        }
        $revenue_row = $this->db->get()->row();
        $today_revenue = floatval($revenue_row->total_amount ?? 0);

        // Counts by status
        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];
        $status_counts = [];
        foreach ($statuses as $st) {
            $this->db->from('orders');
            $this->db->where('status', $st);
            if (!empty($shop_id)) {
                $this->db->where('shop_id', (int)$shop_id);
            }
            $status_counts[$st] = $this->db->count_all_results();
        }

        $this->json_response([
            'status' => 'success',
            'stats' => [
                'today_orders_count' => $today_orders_count,
                'today_revenue' => $today_revenue,
                'status_counts' => $status_counts,
                'active_pending' => $status_counts['pending']
            ]
        ]);
    }

    /**
     * Get Orders List with Filters
     */
    public function orders() {
        $status   = $this->input->get('status');   // pending, preparing, ready, delivered, cancelled, all
        $shop_id  = $this->input->get('shop_id');
        $date     = $this->input->get('date');      // Y-m-d or 'today'
        $search   = trim($this->input->get('search') ?? '');
        $limit    = max(1, min(100, intval($this->input->get('limit') ?: 30)));
        $page     = max(1, intval($this->input->get('page') ?: 1));
        $offset   = ($page - 1) * $limit;

        $this->db->select('orders.*, shops.name as shop_name, shops.phone as shop_phone');
        $this->db->from('orders');
        $this->db->join('shops', 'shops.id = orders.shop_id', 'left');

        if (!empty($status) && $status !== 'all') {
            if ($status === 'in_progress') {
                $this->db->where_in('orders.status', ['confirmed', 'preparing', 'ready']);
            } else {
                $this->db->where('orders.status', $status);
            }
        }

        if (!empty($shop_id)) {
            $this->db->where('orders.shop_id', (int)$shop_id);
        }

        if ($date === 'today') {
            $this->db->where('DATE(orders.created_at)', date('Y-m-d'));
        } elseif (!empty($date)) {
            $this->db->where('DATE(orders.created_at)', $date);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('orders.customer_name', $search);
            $this->db->or_like('orders.customer_phone', $search);
            $this->db->or_like('orders.customer_address', $search);
            if (is_numeric($search)) {
                $this->db->or_where('orders.id', (int)$search);
            }
            $this->db->group_end();
        }

        $this->db->order_by('orders.id', 'DESC');
        $this->db->limit($limit, $offset);
        $orders = $this->db->get()->result();

        $formatted_orders = [];
        foreach ($orders as $o) {
            $items = [];
            if (!empty($o->items_json)) {
                $decoded = json_decode($o->items_json, true);
                if (is_array($decoded)) {
                    $items = $this->normalize_items($decoded);
                }
            }

            $formatted_orders[] = [
                'id' => (int)$o->id,
                'order_type' => $o->order_type, // delivery / collect
                'shop_id' => $o->shop_id ? (int)$o->shop_id : null,
                'shop_name' => $o->shop_name ?: 'Pizza One',
                'shop_phone' => $o->shop_phone ?: '',
                'customer_name' => $o->customer_name,
                'customer_phone' => $o->customer_phone,
                'customer_address' => $o->customer_address ?: '',
                'notes' => $o->notes ?: '',
                'payment_method' => $o->payment_method,
                'subtotal' => floatval($o->subtotal),
                'delivery_fee' => floatval($o->delivery_fee),
                'total_amount' => floatval($o->total_amount ?: $o->total),
                'status' => $o->status,
                'created_at' => $o->created_at,
                'formatted_time' => date('d/m/Y H:i', strtotime($o->created_at)),
                'time_ago' => $this->time_ago($o->created_at),
                'items_count' => count($items),
                'items' => $items
            ];
        }

        // Summary counts for quick badge counters
        $this->db->select('status, COUNT(id) as count');
        $this->db->from('orders');
        if (!empty($shop_id)) {
            $this->db->where('shop_id', (int)$shop_id);
        }
        $this->db->group_by('status');
        $counts_res = $this->db->get()->result();
        $counts = ['pending' => 0, 'confirmed' => 0, 'preparing' => 0, 'ready' => 0, 'delivered' => 0, 'cancelled' => 0];
        foreach ($counts_res as $cr) {
            $counts[$cr->status] = (int)$cr->count;
        }

        $this->json_response([
            'status' => 'success',
            'orders' => $formatted_orders,
            'counts' => $counts,
            'page' => $page,
            'limit' => $limit
        ]);
    }

    /**
     * Single Order Details
     */
    public function order_details($id) {
        $this->db->select('orders.*, shops.name as shop_name, shops.phone as shop_phone, shops.address as shop_address');
        $this->db->from('orders');
        $this->db->join('shops', 'shops.id = orders.shop_id', 'left');
        $this->db->where('orders.id', (int)$id);
        $order = $this->db->get()->row();

        if (!$order) {
            $this->json_response([
                'status' => 'error',
                'message' => 'Commande introuvable.'
            ], 404);
        }

        $items = [];
        if (!empty($order->items_json)) {
            $decoded = json_decode($order->items_json, true);
            if (is_array($decoded)) {
                $items = $this->normalize_items($decoded);
            }
        }

        $data = [
            'id' => (int)$order->id,
            'order_type' => $order->order_type,
            'shop_id' => $order->shop_id ? (int)$order->shop_id : null,
            'shop_name' => $order->shop_name ?: 'Pizza One',
            'shop_phone' => $order->shop_phone ?: '',
            'shop_address' => $order->shop_address ?: '',
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'customer_address' => $order->customer_address ?: '',
            'notes' => $order->notes ?: '',
            'payment_method' => $order->payment_method,
            'subtotal' => floatval($order->subtotal),
            'delivery_fee' => floatval($order->delivery_fee),
            'total_amount' => floatval($order->total_amount ?: $order->total),
            'status' => $order->status,
            'created_at' => $order->created_at,
            'formatted_time' => date('d/m/Y H:i', strtotime($order->created_at)),
            'time_ago' => $this->time_ago($order->created_at),
            'items' => $items
        ];

        $this->json_response([
            'status' => 'success',
            'order' => $data
        ]);
    }

    /**
     * Update Order Status
     */
    public function update_order_status($id) {
        $data = $this->get_request_data();
        $status = trim($data['status'] ?? '');
        $note   = trim($data['note'] ?? '');

        $valid_statuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];
        if (!in_array($status, $valid_statuses)) {
            $this->json_response([
                'status' => 'error',
                'message' => 'Statut invalide.'
            ], 400);
        }

        $order = $this->db->get_where('orders', ['id' => (int)$id])->row();
        if (!$order) {
            $this->json_response([
                'status' => 'error',
                'message' => 'Commande introuvable.'
            ], 404);
        }

        $update_data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($note)) {
            $existing_notes = $order->notes ? $order->notes . "\n" : "";
            $update_data['notes'] = $existing_notes . "[Statut: " . ucfirst($status) . "] " . $note;
        }

        $this->Common_model->update('orders', ['id' => (int)$id], $update_data);

        $this->json_response([
            'status' => 'success',
            'message' => "Statut de la commande mis à jour : " . ucfirst($status),
            'order_id' => (int)$id,
            'new_status' => $status
        ]);
    }

    /**
     * Register FCM Device Token for Push Notifications
     */
    public function register_token() {
        $data = $this->get_request_data();
        $token = trim($data['token'] ?? '');
        $platform = trim($data['platform'] ?? 'android');
        $device_name = trim($data['device_name'] ?? '');
        $shop_id = !empty($data['shop_id']) ? (int)$data['shop_id'] : null;

        if (empty($token)) {
            $this->json_response([
                'status' => 'error',
                'message' => 'Le jeton FCM est requis.'
            ], 400);
        }

        $existing = $this->db->get_where('fcm_device_tokens', ['token' => $token])->row();
        if ($existing) {
            $this->db->where('id', $existing->id)->update('fcm_device_tokens', [
                'shop_id' => $shop_id,
                'device_name' => $device_name,
                'platform' => $platform,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->db->insert('fcm_device_tokens', [
                'token' => $token,
                'shop_id' => $shop_id,
                'device_name' => $device_name,
                'platform' => $platform,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->json_response([
            'status' => 'success',
            'message' => 'Jeton d\'appareil enregistré avec succès.'
        ]);
    }

    /**
     * Unregister FCM Device Token (on Logout)
     */
    public function unregister_token() {
        $data = $this->get_request_data();
        $token = trim($data['token'] ?? '');
        if (!empty($token)) {
            $this->db->where('token', $token)->delete('fcm_device_tokens');
        }
        $this->json_response([
            'status' => 'success',
            'message' => 'Jeton d\'appareil désinscrit.'
        ]);
    }

    /**
     * Test Push Notification
     */
    public function test_notification() {
        $result = $this->Common_model->send_fcm_new_order_notification(
            9999,
            [
                'customer_name' => 'Client Test Pizza One',
                'total_amount' => 24.50,
                'order_type' => 'delivery'
            ],
            'Villiers-le-bel'
        );

        $tokens_count = $this->db->count_all('fcm_device_tokens');

        $this->json_response([
            'status' => 'success',
            'message' => 'Notification de test envoyée.',
            'registered_devices' => $tokens_count,
            'fcm_result' => $result
        ]);
    }

    /**
     * Helper: normalize cart items for Flutter model
     */
    private function normalize_items($cart_items) {
        $result = [];
        foreach ($cart_items as $item) {
            $addons_list = [];
            if (!empty($item['addons'])) {
                if (is_array($item['addons'])) {
                    foreach ($item['addons'] as $add) {
                        $addons_list[] = is_array($add) ? ($add['name'] ?? '') : (string)$add;
                    }
                } else {
                    $addons_list[] = (string)$item['addons'];
                }
            }

            $result[] = [
                'product_id' => $item['product_id'] ?? $item['id'] ?? 0,
                'name' => $item['product_name'] ?? $item['name'] ?? 'Produit',
                'size' => $item['size'] ?? $item['size_name'] ?? '',
                'quantity' => intval($item['quantity'] ?? 1),
                'price' => floatval($item['price'] ?? 0),
                'item_total' => floatval($item['item_total'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 1))),
                'addons' => $addons_list,
                'instructions' => $item['instructions'] ?? $item['notes'] ?? ''
            ];
        }
        return $result;
    }

    /**
     * Helper: relative time string
     */
    private function time_ago($datetime) {
        $time = strtotime($datetime);
        $diff = time() - $time;
        if ($diff < 60) return "À l'instant";
        if ($diff < 3600) return floor($diff / 60) . ' min';
        if ($diff < 86400) return floor($diff / 3600) . ' h';
        return date('d/m', $time);
    }
}
