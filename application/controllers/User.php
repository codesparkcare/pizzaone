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
        $data['user'] = $this->User_model->get_user_by_id($user_id);

        $this->load->view('includes/header');
        $this->load->view('user/account', $data);
        $this->load->view('includes/footer');
    }

    public function logout() {
        $this->session->unset_userdata(array('user_id', 'user_name', 'user_email'));
        redirect('user/login');
    }
}
