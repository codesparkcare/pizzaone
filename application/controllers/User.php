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
        $data['addresses'] = $this->Common_model->get_user_addresses($user_id);
        $data['title'] = t('Mon Compte & Mes Commandes', 'My Account & Orders');

        $this->load->view('includes/header', $data);
        $this->load->view('user/account', $data);
        $this->load->view('includes/footer');
    }

    public function add_address() {
        if (!$this->session->userdata('user_id')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Please login']);
                return;
            }
            redirect('user/login');
        }

        $user_id = $this->session->userdata('user_id');
        $label = $this->input->post('label', true) ?: 'Maison';
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $postal_code = $this->input->post('postal_code', true);
        $is_default = $this->input->post('is_default') ? 1 : 0;

        if (empty(trim($address))) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => t('L\'adresse ne peut pas être vide.', 'Address cannot be empty.')]);
                return;
            }
            $this->session->set_flashdata('error', t('L\'adresse ne peut pas être vide.', 'Address cannot be empty.'));
            redirect('user/account#profile-section');
        }

        $new_id = $this->Common_model->add_user_address($user_id, [
            'label'       => $label,
            'address'     => $address,
            'city'        => $city,
            'postal_code' => $postal_code,
            'is_default'  => $is_default
        ]);

        if ($this->input->is_ajax_request()) {
            $addresses = $this->Common_model->get_user_addresses($user_id);
            echo json_encode([
                'status' => 'success',
                'address_id' => $new_id,
                'address' => trim($address),
                'label' => $label,
                'addresses' => $addresses,
                'message' => t('Adresse enregistrée avec succès !', 'Address saved successfully!')
            ]);
            return;
        }

        $this->session->set_flashdata('success', t('Adresse ajoutée avec succès !', 'Address added successfully!'));
        redirect('user/account#profile-section');
    }

    public function delete_address($id) {
        if (!$this->session->userdata('user_id')) {
            redirect('user/login');
        }
        $user_id = $this->session->userdata('user_id');
        $this->Common_model->delete_user_address($user_id, intval($id));

        if ($this->input->is_ajax_request()) {
            echo json_encode(['status' => 'success', 'message' => t('Adresse supprimée.', 'Address removed.')]);
            return;
        }
        $this->session->set_flashdata('success', t('Adresse supprimée.', 'Address removed.'));
        redirect('user/account#profile-section');
    }

    public function set_default_address($id) {
        if (!$this->session->userdata('user_id')) {
            redirect('user/login');
        }
        $user_id = $this->session->userdata('user_id');
        $this->Common_model->set_default_user_address($user_id, intval($id));

        if ($this->input->is_ajax_request()) {
            echo json_encode(['status' => 'success', 'message' => t('Adresse par défaut mise à jour.', 'Default address updated.')]);
            return;
        }
        $this->session->set_flashdata('success', t('Adresse par défaut mise à jour.', 'Default address updated.'));
        redirect('user/account#profile-section');
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
?>
