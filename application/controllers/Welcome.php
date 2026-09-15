<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
        $data['categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);
        $data['featured_products'] = $this->Common_model->get_products_by_category(null, null, 6, 0);
        $total_featured = $this->Common_model->get_products_by_category_count(null, null);
        $data['has_more_featured'] = ($total_featured > 6);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(6);
        $data['reviews'] = $this->Common_model->get_where('reviews', ['status' => 1]);
        $data['slider_videos'] = $this->Common_model->get_where('slider_videos', ['status' => 1]);
        
        // Check wishlist status for logged in users
        if ($this->session->userdata('user_id')) {
            $user_id = $this->session->userdata('user_id');
            $wishlist = $this->Common_model->get_where('wishlists', ['user_id' => $user_id]);
            $wishlist_product_ids = array_map(function($w) { return $w->product_id; }, $wishlist);
            foreach ($data['featured_products'] as &$product) {
                $product->in_wishlist = in_array($product->id, $wishlist_product_ids);
            }
        }
        
		$this->load->view('includes/header');
		$this->load->view('welcome_message', $data);
		$this->load->view('includes/footer');
	}

    public function load_more_pizzas()
    {
        $offset = intval($this->input->get_post('offset'));
        $limit = intval($this->input->get_post('limit'));
        if ($limit <= 0) {
            $limit = 6;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        $products = $this->Common_model->get_products_by_category(null, null, $limit, $offset);
        $total = $this->Common_model->get_products_by_category_count(null, null);

        // Check wishlist status for logged in users
        if ($this->session->userdata('user_id')) {
            $user_id = $this->session->userdata('user_id');
            $wishlist = $this->Common_model->get_where('wishlists', ['user_id' => $user_id]);
            $wishlist_product_ids = array_map(function($w) { return $w->product_id; }, $wishlist);
            foreach ($products as &$product) {
                $product->in_wishlist = in_array($product->id, $wishlist_product_ids);
            }
        }

        $html = '';
        foreach ($products as $p) {
            $html .= $this->load->view('partials/product_card', ['p' => $p], true);
        }

        $new_offset = $offset + count($products);
        $has_more = ($new_offset < $total) && !empty($products);

        echo json_encode([
            'status' => 'success',
            'html' => $html,
            'count' => count($products),
            'has_more' => $has_more,
            'next_offset' => $new_offset
        ]);
    }

    public function contact()
    {
        $this->load->view('includes/header');
        $this->load->view('contact');
        $this->load->view('includes/footer');
    }

    public function about()
    {
        $data['title'] = 'About Us';
        $this->load->view('includes/header', $data);
        $this->load->view('about', $data);
        $this->load->view('includes/footer');
    }

    public function menu($category_id = null)
    {
        $data['title'] = 'Our Menu';
        $data['categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);
        $data['all_categories'] = $this->Common_model->get_all('categories');
        
        // Check if the passed ID is a subcategory or parent category
        $current_category = null;
        if ($category_id) {
            $current_category = $this->Common_model->get_single('categories', ['id' => $category_id]);
        }
        
        // If it's a subcategory (has parent_id > 0), filter by subcategory
        if ($current_category && $current_category->parent_id > 0) {
            $data['products'] = $this->Common_model->get_products_by_subcategory($category_id);
            $data['current_cat_id'] = $category_id;
            $data['is_subcategory'] = true;
        } else {
            // If it's a parent category or null, use normal filtering
            $data['products'] = $this->Common_model->get_products_by_category($category_id);
            $data['current_cat_id'] = $category_id;
            $data['is_subcategory'] = false;
        }

        // Check wishlist status for logged in users
        if ($this->session->userdata('user_id')) {
            $user_id = $this->session->userdata('user_id');
            $wishlist = $this->Common_model->get_where('wishlists', ['user_id' => $user_id]);
            $wishlist_product_ids = array_map(function($w) { return $w->product_id; }, $wishlist);
            foreach ($data['products'] as &$product) {
                $product->in_wishlist = in_array($product->id, $wishlist_product_ids);
            }
        }

        if ($this->input->is_ajax_request()) {
            $cat_name = "";
            if ($category_id) {
                foreach ($data['all_categories'] as $c) {
                    if ($c->id == $category_id) {
                        $cat_name = $c->name;
                        break;
                    }
                }
            }
            $html = $this->load->view('partials/menu_products_grid', $data, true);
            echo json_encode([
                'status' => 'success',
                'html' => $html,
                'cat_name' => $cat_name,
                'count' => count($data['products']),
                'current_cat_id' => $category_id
            ]);
            return;
        }
        
        $this->load->view('includes/header', $data);
        $this->load->view('menu', $data);
        $this->load->view('includes/footer');
    }

    public function search()
    {
        $query = trim($this->input->get('q', true));
        $data['title'] = $query ? ('Search: ' . htmlspecialchars($query)) : 'Our Menu';
        $data['search_query'] = $query;
        $data['categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);
        $data['all_categories'] = $this->Common_model->get_all('categories');
        $data['current_cat_id'] = null;
        $data['is_subcategory'] = false;

        $shop_id = $this->session->userdata('selected_shop_id');
        $data['products'] = $this->Common_model->search_products($query, $shop_id);

        // Check wishlist status for logged in users
        if ($this->session->userdata('user_id')) {
            $user_id = $this->session->userdata('user_id');
            $wishlist = $this->Common_model->get_where('wishlists', ['user_id' => $user_id]);
            $wishlist_product_ids = array_map(function($w) { return $w->product_id; }, $wishlist);
            foreach ($data['products'] as &$product) {
                $product->in_wishlist = in_array($product->id, $wishlist_product_ids);
            }
        }

        if ($this->input->is_ajax_request()) {
            $html = $this->load->view('partials/menu_products_grid', $data, true);
            echo json_encode([
                'status' => 'success',
                'html' => $html,
                'cat_name' => $query,
                'count' => count($data['products'])
            ]);
            return;
        }

        $this->load->view('includes/header', $data);
        $this->load->view('menu', $data);
        $this->load->view('includes/footer');
    }

    public function get_product_details($id)
    {
        $product = $this->Common_model->get_single('products', ['id' => $id]);
        if ($product) {
            // Get Sizes
            $this->db->select('sizes.name, product_sizes.price, product_sizes.id as ps_id');
            $this->db->from('product_sizes');
            $this->db->join('sizes', 'sizes.id = product_sizes.size_id');
            $this->db->where('product_sizes.product_id', $id);
            $product->sizes = $this->db->get()->result();

            // Get Addon Groups (New System)
            $product->addon_groups = $this->Common_model->get_addon_groups_by_product($id);

            // Get Addons linked to this product (Old System - fallback if no groups)
            if (empty($product->addon_groups)) {
                $this->db->select('addons.*');
                $this->db->from('addons');
                $this->db->join('product_addons', 'product_addons.addon_id = addons.id');
                $this->db->where('product_addons.product_id', $id);
                $product->addons = $this->db->get()->result();
            } else {
                $product->addons = [];
            }

            echo json_encode(['status' => 'success', 'data' => $product]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        }
    }

    public function set_location()
    {
        $shop_id = $this->input->post('shop_id');
        $shop = $this->Common_model->get_single('shops', ['id' => $shop_id]);

        if ($shop_id == '2') {
            $shop_name = 'Le Plessis-Bouchard';
            $shop_phone = ($shop && !empty($shop->phone)) ? $shop->phone : '01 34 14 15 16';
        } else {
            $shop_id = '1';
            $shop_name = 'Villiers-le-bel';
            $shop_phone = ($shop && !empty($shop->phone)) ? $shop->phone : '01 34 19 94 56';
        }

        $this->session->set_userdata('selected_shop_id', $shop_id);
        $this->session->set_userdata('selected_shop_name', $shop_name);
        $this->session->set_userdata('selected_shop_phone', $shop_phone);

        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'status' => 'success',
                'shop_id' => $shop_id,
                'shop_name' => $shop_name,
                'shop_phone' => $shop_phone
            ]);
        } else {
            $redirect = $this->input->server('HTTP_REFERER') ? $this->input->server('HTTP_REFERER') : base_url();
            redirect($redirect);
        }
    }
}
