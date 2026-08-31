<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function login() {
        if ($this->session->userdata('user_id')) {
            redirect('user/account');
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->User_model->get_user_by_email($email);
            
            if ($user && password_verify($password, $user->password_hash)) {
                $this->session->set_userdata(array(
                    'user_id' => $user->id,
                    'user_name' => $user->first_name . ' ' . $user->last_name,
                    'user_email' => $user->email
                ));
                redirect('user/account');
            } else {
                $data['error'] = 'Invalid email or password';
                $this->load->view('includes/header');
                $this->load->view('user/login', $data);
                $this->load->view('includes/footer');
                return;
            }
        }

        $this->load->view('includes/header');
        $this->load->view('user/login');
        $this->load->view('includes/footer');
    }

    public function register() {
        if ($this->session->userdata('user_id')) {
            redirect('user/account');
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $email = $this->input->post('email');
            
            if ($this->User_model->get_user_by_email($email)) {
                $data['error'] = 'Email already exists';
                $this->load->view('includes/header');
                $this->load->view('user/register', $data);
                $this->load->view('includes/footer');
                return;
            }

            $user_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $email,
                'password_hash' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            );

            $user_id = $this->User_model->create_user($user_data);
            
            $this->session->set_userdata(array(
                'user_id' => $user_id,
                'user_name' => $user_data['first_name'] . ' ' . $user_data['last_name'],
                'user_email' => $user_data['email']
            ));
            
            redirect('user/account');
        }

        $this->load->view('includes/header');
        $this->load->view('user/register');
        $this->load->view('includes/footer');
    }

    public function account() {
        if (!$this->session->userdata('user_id')) {
            redirect('user/login');
        }
        
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        $data['user'] = $user;

        // Fetch user's orders by user_id OR matching customer_phone
        $this->db->order_by('id', 'DESC');
        if (!empty($user->phone)) {
            $clean_phone = preg_replace('/[^0-9]/', '', $user->phone);
            $alt_phone = (substr($clean_phone, 0, 1) === '0') ? substr($clean_phone, 1) : '0' . $clean_phone;

            $this->db->group_start();
            $this->db->where('user_id', $user_id);
            $this->db->or_where('customer_phone', $user->phone);
            $this->db->or_where('customer_phone', $clean_phone);
            $this->db->or_where('customer_phone', $alt_phone);
            $this->db->group_end();
        } else {
            $this->db->where('user_id', $user_id);
        }
        $orders = $this->db->get('orders')->result();

        // Get shop names for orders
        foreach ($orders as &$ord) {
            if ($ord->shop_id) {
                $shop = $this->db->get_where('shops', ['id' => $ord->shop_id])->row();
                $ord->shop_name = $shop ? $shop->name : ($ord->shop_id == 2 ? 'Le Plessis-Bouchard' : 'Villiers-le-bel');
            } else {
                $ord->shop_name = 'Villiers-le-bel';
            }
        }

        $data['orders'] = $orders;
        $data['title'] = t('Mon Compte & Mes Commandes', 'My Account & Orders');

        $this->load->view('includes/header', $data);
        $this->load->view('user/account', $data);
        $this->load->view('includes/footer');
    }

    public function logout() {
        $this->session->unset_userdata(array('user_id', 'user_name', 'user_email'));
        redirect('user/login');
    }

    public function wishlist() {
        if (!$this->session->userdata('user_id')) {
            redirect('user/login');
        }
        
        $user_id = $this->session->userdata('user_id');
        $this->load->model('Common_model');
        $data['wishlist_items'] = $this->Common_model->get_user_wishlist($user_id);

        $this->load->view('includes/header');
        $this->load->view('user/wishlist', $data);
        $this->load->view('includes/footer');
    }

    public function toggle_wishlist($product_id) {
        if (!$this->session->userdata('user_id')) {
            echo json_encode(['status' => 'error', 'message' => 'Please login to add to wishlist']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $this->load->model('Common_model');
        
        // Check if exists
        $exists = $this->Common_model->get_single('wishlists', ['user_id' => $user_id, 'product_id' => $product_id]);
        
        if ($exists) {
            $this->Common_model->delete('wishlists', ['id' => $exists->id]);
            $count = $this->db->where('user_id', $user_id)->count_all_results('wishlists');
            echo json_encode(['status' => 'removed', 'message' => 'Removed from wishlist', 'wishlist_count' => $count]);
        } else {
            $this->Common_model->insert('wishlists', ['user_id' => $user_id, 'product_id' => $product_id]);
            $count = $this->db->where('user_id', $user_id)->count_all_results('wishlists');
            echo json_encode(['status' => 'added', 'message' => 'Added to wishlist', 'wishlist_count' => $count]);
        }
    }
}
