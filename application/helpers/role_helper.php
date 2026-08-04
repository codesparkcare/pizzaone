<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_user_role')) {
    function get_user_role() {
        $ci =& get_instance();
        return $ci->session->userdata('user_role');
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        $role = get_user_role();
        return $role === 'admin' || $role === 'super_admin';
    }
}

if (!function_exists('is_staff')) {
    function is_staff() {
        return get_user_role() === 'staff';
    }
}

if (!function_exists('is_customer')) {
    function is_customer() {
        return get_user_role() === 'customer';
    }
}

// Check if current admin is super admin
if (!function_exists('is_super_admin')) {
    function is_super_admin() {
        $ci = & get_instance();
        $role = $ci->session->userdata('admin_role');
        return $role === 'super_admin';
    }
}

if (!function_exists('require_role')) {
    function require_role(array $roles) {
        $ci =& get_instance();
        $role = get_user_role();
        if (!in_array($role, $roles)) {
            show_error('Access denied – insufficient permissions.', 403);
        }
    }
}
