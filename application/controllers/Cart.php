<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
    }

    /**
     * Show the add to cart page for a product
     */
    public function add($product_id = null)
    {
        if (!$product_id) {
            redirect('menu');
        }

        $product = $this->Common_model->get_single('products', ['id' => $product_id]);
        if (!$product) {
            redirect('menu');
        }

        // Get product sizes
        $this->db->select('sizes.id as size_id, sizes.name, product_sizes.price, product_sizes.id as ps_id');
        $this->db->from('product_sizes');
        $this->db->join('sizes', 'sizes.id = product_sizes.size_id');
        $this->db->where('product_sizes.product_id', $product_id);
        $product->sizes = $this->db->get()->result();

        // Get addon groups for this product (new system)
        $product->addon_groups = $this->Common_model->get_addon_groups_by_product($product_id);
        
        // Fallback to old system for backward compatibility
        if (empty($product->addon_groups)) {
            $this->db->select('addons.*');
            $this->db->from('addons');
            $this->db->join('product_addons', 'product_addons.addon_id = addons.id');
            $this->db->where('product_addons.product_id', $product_id);
            $product->addons = $this->db->get()->result();
            $this->Common_model->attach_size_prices($product->addons);
        } else {
            $product->addons = [];
        }

        // Get category and subcategory info
        $category = $this->Common_model->get_single('categories', ['id' => $product->category_id]);
        if ($product->subcategory_id) {
            $subcategory = $this->Common_model->get_single('categories', ['id' => $product->subcategory_id]);
            $product->subcategory = $subcategory;
        }
        $product->category = $category;

        // Get offer info
        if (!empty($product->offer_id)) {
            $offer = $this->Common_model->get_single('offers', ['id' => $product->offer_id]);
            if ($offer) {
                $product->offer_name = $offer->offer_name;
            }
        }

        $data['product'] = $product;
        $data['title'] = t('Ajouter au panier - ', 'Add to Cart - ') . $product->name;

        $this->load->view('includes/header', $data);
        $this->load->view('add_to_cart', $data);
        $this->load->view('includes/footer');
    }

    /**
     * AJAX: Get quick view modal HTML
     */
    public function quick_view($product_id = null)
    {
        if (!$product_id || !$this->input->is_ajax_request()) {
            echo json_encode(['status' => 'error', 'message' => t('Requête invalide', 'Invalid request')]);
            return;
        }

        $product = $this->Common_model->get_single('products', ['id' => $product_id]);
        if (!$product) {
            echo json_encode(['status' => 'error', 'message' => t('Produit non trouvé', 'Product not found')]);
            return;
        }

        // Get product sizes
        $this->db->select('sizes.id as size_id, sizes.name, product_sizes.price, product_sizes.id as ps_id');
        $this->db->from('product_sizes');
        $this->db->join('sizes', 'sizes.id = product_sizes.size_id');
        $this->db->where('product_sizes.product_id', $product_id);
        $product->sizes = $this->db->get()->result();

        // Get addon groups for this product (new system)
        $product->addon_groups = $this->Common_model->get_addon_groups_by_product($product_id);
        
        // Fallback to old system for backward compatibility
        if (empty($product->addon_groups)) {
            $this->db->select('addons.*');
            $this->db->from('addons');
            $this->db->join('product_addons', 'product_addons.addon_id = addons.id');
            $this->db->where('product_addons.product_id', $product_id);
            $product->addons = $this->db->get()->result();
            $this->Common_model->attach_size_prices($product->addons);
        } else {
            $product->addons = [];
        }

        // Get category and subcategory info
        $category = $this->Common_model->get_single('categories', ['id' => $product->category_id]);
        if ($product->subcategory_id) {
            $subcategory = $this->Common_model->get_single('categories', ['id' => $product->subcategory_id]);
            $product->subcategory = $subcategory;
        }
        $product->category = $category;

        // Get offer info
        if (!empty($product->offer_id)) {
            $offer = $this->Common_model->get_single('offers', ['id' => $product->offer_id]);
            if ($offer) {
                $product->offer_name = $offer->offer_name;
            }
        }

        // Check wishlist status for logged in users
        $product->in_wishlist = false;
        if ($this->session->userdata('user_id')) {
            $user_id = $this->session->userdata('user_id');
            $exists = $this->Common_model->get_single('wishlists', ['user_id' => $user_id, 'product_id' => $product_id]);
            if ($exists) {
                $product->in_wishlist = true;
            }
        }

        $data['product'] = $product;
        
        // Return HTML string
        $html = $this->load->view('add_to_cart_modal', $data, true);
        echo json_encode(['status' => 'success', 'html' => $html]);
    }

    /**
     * AJAX: Add item to cart (stored in session)
     */
    public function add_item()
    {
        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity', true);
        $size_price = $this->input->post('size_price', true);
        $addon_ids = $this->input->post('addon_ids[]'); // Array of addon IDs (old system)
        $addon_prices = $this->input->post('addon_prices[]'); // Array of addon prices (old system)
        $addon_group_ids = $this->input->post('addon_group_ids[]'); // Array of addon group IDs (new system)
        $addon_group_prices = $this->input->post('addon_group_prices[]'); // Array of addon group prices (new system)

        // Get product info
        $product = $this->Common_model->get_single('products', ['id' => $product_id]);
        if (!$product) {
            echo json_encode(['status' => 'error', 'message' => t('Produit non trouvé', 'Product not found')]);
            return;
        }

        // Determine if product has sizes
        $has_sizes = $this->db->where('product_id', $product_id)->from('product_sizes')->count_all_results() > 0;

        // Validate inputs
        if (!$product_id || !$quantity) {
            echo json_encode(['status' => 'error', 'message' => t('Produit ou quantité invalide', 'Invalid product or quantity')]);
            return;
        }
        if ($has_sizes && !$size_price) {
            echo json_encode(['status' => 'error', 'message' => t('La taille est obligatoire pour ce produit', 'Size required for this product')]);
            return;
        }

        // Set default size_price for products without sizes
        if (!$has_sizes) {
            $size_price = $product->price ?? 0;
        }

        // Initialize cart in session if not exists
        if (!$this->session->userdata('cart')) {
            $this->session->set_userdata('cart', []);
        }

        $cart = $this->session->userdata('cart');
        
        // Create unique cart item key based on product, size, and all addon selections
        // Combine both old addon IDs and new addon group IDs for uniqueness
        $addon_string = !empty($addon_ids) ? implode(',', $addon_ids) : '';
        $addon_group_string = !empty($addon_group_ids) ? implode(',', $addon_group_ids) : '';
        $all_addons_string = $addon_string . '|' . $addon_group_string;
        $item_key = $product_id . '_' . $size_price . '_' . md5($all_addons_string);

        // Calculate item total (old addons)
        $addon_total = 0;
        if (!empty($addon_prices)) {
            foreach ($addon_prices as $price) {
                $addon_total += floatval($price);
            }
        }

        // Calculate item total (new addon groups)
        if (!empty($addon_group_prices)) {
            foreach ($addon_group_prices as $price) {
                $addon_total += floatval($price);
            }
        }

        $item_total = (floatval($size_price) + $addon_total) * intval($quantity);

        // Add or update item in cart
        if (isset($cart[$item_key])) {
            $cart[$item_key]['quantity'] += intval($quantity);
            $cart[$item_key]['item_total'] = (floatval($cart[$item_key]['size_price']) + $addon_total) * intval($cart[$item_key]['quantity']);
        } else {
            $cart[$item_key] = [
                'product_id' => $product_id,
                'product_name' => $product->name,
                'product_image' => $product->image,
                'size_price' => $size_price,
                'addon_ids' => $addon_ids,
                'addon_prices' => $addon_prices,
                'addon_group_ids' => $addon_group_ids,
                'addon_group_prices' => $addon_group_prices,
                'quantity' => intval($quantity),
                'item_total' => $item_total
            ];
        }

        // Update session cart
        $this->session->set_userdata('cart', $cart);

        echo json_encode([
            'status' => 'success',
            'message' => $product->name . t(' ajouté au panier !', ' added to cart!'),
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Show cart contents
     */
    public function view()
    {
        $cart = $this->session->userdata('cart') ?: [];
        
        $data['cart_items'] = $cart;
        $data['title'] = t('Mon Panier', 'Shopping Cart');

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['item_total'];
        }
        $data['subtotal'] = $subtotal;
        $data['tax'] = $subtotal * 0.1; // 10% tax
        $data['total'] = $subtotal + $data['tax'];

        $this->load->view('includes/header', $data);
        $this->load->view('cart_view', $data);
        $this->load->view('includes/footer');
    }

    /**
     * AJAX: Remove item from cart
     */
    public function remove_item()
    {
        $item_key = $this->input->post('item_key');

        if (!$item_key) {
            echo json_encode(['status' => 'error', 'message' => t('Article invalide', 'Invalid item')]);
            return;
        }

        $cart = $this->session->userdata('cart') ?: [];

        if (isset($cart[$item_key])) {
            unset($cart[$item_key]);
            $this->session->set_userdata('cart', $cart);
            
            echo json_encode([
                'status' => 'success',
                'message' => t('Article retiré du panier', 'Item removed from cart'),
                'cart_count' => count($cart)
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => t('Article non trouvé', 'Item not found')]);
        }
    }

    /**
     * AJAX: Update item quantity
     */
    public function update_quantity()
    {
        $item_key = $this->input->post('item_key');
        $quantity = $this->input->post('quantity', true);

        if (!$item_key || !$quantity) {
            echo json_encode(['status' => 'error', 'message' => t('Requête invalide', 'Invalid request')]);
            return;
        }

        $cart = $this->session->userdata('cart') ?: [];

        if (isset($cart[$item_key])) {
            $item = $cart[$item_key];
            $item['quantity'] = intval($quantity);
            
            // Recalculate item total
            $addon_total = 0;
            if (!empty($item['addon_prices'])) {
                foreach ($item['addon_prices'] as $price) {
                    $addon_total += floatval($price);
                }
            }
            $item['item_total'] = (floatval($item['size_price']) + $addon_total) * intval($quantity);
            
            $cart[$item_key] = $item;
            $this->session->set_userdata('cart', $cart);
            
            // Calculate new totals
            $subtotal = 0;
            foreach ($cart as $c) {
                $subtotal += $c['item_total'];
            }
            
            echo json_encode([
                'status' => 'success',
                'item_total' => number_format($item['item_total'], 2),
                'subtotal' => number_format($subtotal, 2),
                'tax' => number_format($subtotal * 0.1, 2),
                'total' => number_format($subtotal + ($subtotal * 0.1), 2)
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => t('Article non trouvé', 'Item not found')]);
        }
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $cart = $this->session->userdata('cart') ?: [];
        if (empty($cart)) {
            redirect('cart');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['item_total'];
        }

        // Get all active shops
        $shops = $this->db->get_where('shops', ['is_active' => 1])->result();

        $data['cart_items']  = $cart;
        $data['subtotal']    = $subtotal;
        $data['shops']       = $shops;
        $data['title']       = t('Commande', 'Checkout');

        $this->load->view('includes/header', $data);
        $this->load->view('checkout', $data);
        $this->load->view('includes/footer');
    }

    /**
     * AJAX: Get shop address by ID
     */
    public function get_shop($shop_id = null)
    {
        if (!$shop_id) {
            echo json_encode(['status' => 'error']);
            return;
        }
        $shop = $this->db->get_where('shops', ['id' => $shop_id, 'is_active' => 1])->row();
        if ($shop) {
            echo json_encode(['status' => 'success', 'shop' => $shop]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    /**
     * Process the order (place_order)
     */
    public function place_order()
    {
        $cart = $this->session->userdata('cart') ?: [];
        if (empty($cart)) {
            redirect('cart');
        }

        $order_type    = $this->input->post('order_type');   // collect or delivery
        $shop_id       = $this->input->post('shop_id');
        $customer_name = $this->input->post('customer_name', true);
        $customer_phone= $this->input->post('customer_phone', true);
        $customer_addr = $this->input->post('customer_address', true);
        $notes         = $this->input->post('notes', true);
        $payment       = $this->input->post('payment_method', true);

        // Basic validation
        if (!$customer_name || !$customer_phone || !$order_type || !$payment) {
            $this->session->set_flashdata('checkout_error', t('Veuillez remplir tous les champs obligatoires.', 'Please fill in all required fields.'));
            redirect('cart/checkout');
        }

        $subtotal = 0;
        foreach ($cart as $item) { $subtotal += $item['item_total']; }
        $delivery_fee = ($order_type === 'delivery') ? 5.00 : 0.00;
        $total = $subtotal + $delivery_fee;

        // Get shop info
        $shop = $shop_id ? $this->db->get_where('shops', ['id' => $shop_id])->row() : null;

        // Insert order into database
        $order_data = [
            'order_type'      => $order_type,
            'shop_id'         => $shop_id,
            'customer_name'   => $customer_name,
            'customer_phone'  => $customer_phone,
            'customer_address'=> $customer_addr ?? '',
            'notes'           => $notes,
            'payment_method'  => $payment,
            'subtotal'        => $subtotal,
            'delivery_fee'    => $delivery_fee,
            'total'           => $total,
            'total_amount'    => $total,
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s')
        ];
        $order_id = $this->Common_model->insert('orders', $order_data);
        // Store order confirmation in session for frontend display
        $this->session->set_userdata('last_order', array_merge($order_data, [
            'id' => $order_id,
            'cart_items' => $cart,
            'shop' => $shop,
            'total_amount' => $total
        ]));

        // Clear cart
        $this->session->unset_userdata('cart');

        // Redirect to order confirmed page
        redirect('cart/order_confirmed');
    }

    /**
     * Order confirmed page
     */
    public function order_confirmed()
    {
        $order = $this->session->userdata('last_order');
        if (!$order) { redirect('menu'); }

        $data['order'] = $order;
        $data['title'] = t('Commande confirmée !', 'Order Confirmed!');

        $this->load->view('includes/header', $data);
        $this->load->view('order_confirmed', $data);
        $this->load->view('includes/footer');
    }

    /**
     * Customer order list page
     */
    public function my_orders()
    {
        // Retrieve last order to get customer identifier (phone)
        $order = $this->session->userdata('last_order');
        if (!$order) {
            // No recent order, redirect to home
            redirect('menu');
        }
        $customer_phone = $order['customer_phone'];
        // Fetch all orders for this phone number
        $data['orders'] = $this->Common_model->get_where('orders', ['customer_phone' => $customer_phone]);
        $data['title'] = t('Mes Commandes', 'My Orders');
        $this->load->view('includes/header', $data);
        $this->load->view('customer_orders', $data);
        $this->load->view('includes/footer');
    }


    /**
     * Empty the entire cart
     */
    public function clear()
    {
        $this->session->unset_userdata('cart');
        echo json_encode(['status' => 'success', 'message' => t('Panier vidé', 'Cart cleared')]);
    }
}
?>
