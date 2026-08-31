<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Admin_user_model');
        $this->load->model('Shop_user_model');
    }

    private function check_login()
    {
        if (!$this->session->userdata('admin_id')) {
            redirect('admin/login');
        }

        // Role-Based Access Control
        $role = $this->session->userdata('admin_role');
        if ($role === 'staff') {
            $restricted_methods = ['shop_users', 'add_shop_user', 'delete_shop_user', 'admins', 'add_admin', 'edit_admin', 'delete_admin', 'update_admin'];
            $current_method = $this->router->fetch_method();
            if (in_array($current_method, $restricted_methods)) {
                $this->session->set_flashdata('error', 'Access Denied: You do not have permission to view that page.');
                redirect('admin/dashboard');
            }
        }
    }

    public function index()
    {
        $this->check_login();
        redirect('admin/dashboard');
    }

    public function login()
    {
        // Auto-seed default admins including 'super admin'
        $this->Admin_user_model->seed_default_admins();

        if ($this->session->userdata('admin_id')) {
            redirect('admin/dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == TRUE) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $admin = $this->Common_model->get_single('admins', ['username' => $username]);
            // Prefer hashed password, but fallback to plain password if hash missing
            if ($admin && (
                    (!empty($admin->password_hash) && password_verify($password, $admin->password_hash)) ||
                    (empty($admin->password_hash) && $admin->password === $password)
                )) {
                $this->session->set_userdata([
                    'admin_id' => $admin->admin_id,
                    'admin_username' => $admin->username,
                    'admin_role' => $admin->role,
                    'user_role' => $admin->role
                ]);
                redirect('admin/dashboard');
            } else {
                // Check if it's a shop user (staff)
                $shop_user = $this->Common_model->get_single('shop_users', ['username' => $username]);
                if ($shop_user && password_verify($password, $shop_user->password_hash)) {
                    $this->session->set_userdata([
                        'admin_id' => 'shop_' . $shop_user->id,
                        'admin_username' => $shop_user->username,
                        'admin_role' => 'staff',
                        'user_role' => 'staff',
                        'shop_id' => $shop_user->shop_id
                    ]);
                    redirect('admin/dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Invalid Username or Password');
                    redirect('admin/login');
                }
            }
        }

        $this->load->view('admin/login');
    }

    public function logout()
    {
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('admin_username');
        redirect('admin/login');
    }
    // DEBUG: expose admin row for given username
    public function debug_login_row($username = 'admin')
    {
        $admin = $this->Common_model->get_single('admins', ['username' => $username]);
        echo '<pre>'; var_dump($admin); echo '</pre>';
    }


    public function dashboard()
    {
        $this->check_login();
        $data['title'] = 'Dashboard';
        $data['total_products'] = $this->Common_model->get_count('products');
        $data['total_categories'] = $this->Common_model->get_count('categories');
        if ($this->session->userdata('admin_role') === 'staff') {
            $shop_id = $this->session->userdata('shop_id');
            $this->db->where('shop_id', $shop_id);
            $data['total_orders'] = $this->db->count_all_results('orders');
            
            $this->db->select('shops.name, COUNT(orders.id) as count');
            $this->db->from('shops');
            $this->db->join('orders', 'orders.shop_id = shops.id', 'left');
            $this->db->where('shops.id', $shop_id);
            $this->db->group_by('shops.id');
            $data['shop_orders'] = $this->db->get()->result_array();
        } else {
            $data['total_orders'] = $this->Common_model->get_count('orders');
            
            $this->db->select('shops.name, COUNT(orders.id) as count');
            $this->db->from('shops');
            $this->db->join('orders', 'orders.shop_id = shops.id', 'left');
            $this->db->group_by('shops.id');
            $data['shop_orders'] = $this->db->get()->result_array();
        }

        $data['total_admins'] = $this->Common_model->get_count('admins');

        // Recent orders
        $this->db->order_by('id', 'DESC');
        $this->db->limit(5);
        if ($this->session->userdata('admin_role') === 'staff') {
            $this->db->where('shop_id', $this->session->userdata('shop_id'));
        }
        $data['recent_orders'] = $this->db->get('orders')->result();

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/includes/footer');
    }

    // Categories
    public function categories()
    {
        $this->check_login();
        $data['title'] = 'Manage Categories';
        $data['categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/categories', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_category()
    {
        $this->check_login();

        $config['upload_path'] = './assets/images/categories/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        $image = '';
        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            $image = $upload_data['file_name'];
        }

        $data = [
            'name' => $this->input->post('name'),
            'image' => $image,
            'parent_id' => $this->input->post('parent_id') ? $this->input->post('parent_id') : 0,
            'status' => 1
        ];

        $this->Common_model->insert('categories', $data);
        $this->session->set_flashdata('success', 'Category added successfully');
        redirect('admin/categories');
    }

    public function edit_category($id)
    {
        $this->check_login();
        $data['title'] = 'Edit Category';
        $data['category'] = $this->Common_model->get_single('categories', ['id' => $id]);
        $data['parent_categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0, 'id !=' => $id]);

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/edit_category', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_category($id)
    {
        $this->check_login();

        $category = $this->Common_model->get_single('categories', ['id' => $id]);
        $image = $category->image;

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './assets/images/categories/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                // Delete old image
                if ($image) {
                    @unlink('./assets/images/categories/' . $image);
                }
                $upload_data = $this->upload->data();
                $image = $upload_data['file_name'];
            }
        }

        $data = [
            'name' => $this->input->post('name'),
            'image' => $image,
            'parent_id' => $this->input->post('parent_id') ? $this->input->post('parent_id') : 0
        ];

        $this->Common_model->update('categories', ['id' => $id], $data);
        $this->session->set_flashdata('success', 'Category updated successfully');
        redirect('admin/categories');
    }

    public function delete_category($id)
    {
        $this->check_login();
        $category = $this->Common_model->get_single('categories', ['id' => $id]);
        if ($category && $category->image) {
            @unlink('./assets/images/categories/' . $category->image);
        }
        $this->Common_model->delete('categories', ['id' => $id]);
        $this->session->set_flashdata('success', 'Category deleted successfully');
        redirect('admin/categories');
    }
    public function delete_multiple_categories()
    {
        $this->check_login();
        $ids = $this->input->post('category_ids');
        if ($ids && is_array($ids)) {
            foreach ($ids as $id) {
                $category = $this->Common_model->get_single('categories', ['id' => $id]);
                if ($category && $category->image) {
                    @unlink('./assets/images/categories/' . $category->image);
                }
                $this->Common_model->delete('categories', ['id' => $id]);
            }
            $this->session->set_flashdata('success', 'Selected categories deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'No categories selected');
        }
        redirect('admin/categories');
    }
    // Sub Categories
    public function sub_categories()
    {
        $this->check_login();
        $data['title'] = 'Manage Sub Categories';
        // Fetch all categories where parent_id != 0 (sub categories)
        $data['sub_categories'] = $this->Common_model->get_where('categories', ['parent_id !=' => 0]);
        $data['parent_categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/sub_categories', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_sub_category()
    {
        $this->check_login();

        $config['upload_path'] = './assets/images/categories/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }
        $this->load->library('upload', $config);

        $image = '';
        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            $image = $upload_data['file_name'];
        }

        $data = [
            'name' => $this->input->post('name'),
            'image' => $image,
            // parent_id must be provided, default to 0 if empty
            'parent_id' => $this->input->post('parent_id') ? $this->input->post('parent_id') : 0,
            'status' => 1
        ];

        $this->Common_model->insert('categories', $data);
        $this->session->set_flashdata('success', 'Sub Category added successfully');
        redirect('admin/sub_categories');
    }
    public function products()
    {
        $this->check_login();
        $data['title'] = 'Manage Products';
        $data['products'] = $this->Common_model->get_products_with_category();
        $data['categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);
        $data['addons'] = $this->Common_model->get_all('addons');
        $data['all_shops'] = $this->Common_model->get_all('shops');

        // Load addon groups with their linked addons for grouped display
        $groups = $this->Common_model->get_all('addon_groups');
        foreach ($groups as $group) {
            $group->addons = $this->Common_model->get_addons_in_group($group->id);
        }
        usort($groups, function ($a, $b) {
            $a_extra = (stripos($a->name, 'extra') !== false || stripos($a->name, 'supplement') !== false) ? 1 : 0;
            $b_extra = (stripos($b->name, 'extra') !== false || stripos($b->name, 'supplement') !== false) ? 1 : 0;
            if ($a_extra !== $b_extra) {
                return $a_extra - $b_extra;
            }
            return $b->id - $a->id;
        });
        $data['addon_groups'] = $groups;

        // Load offers
        $data['offers'] = $this->Common_model->get_all('offers');

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/products', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_product()
    {
        $this->check_login();

        $config['upload_path'] = './assets/images/products/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        $image = '';
        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            $image = $upload_data['file_name'];
        }

        $shops_input = $this->input->post('shops');
        $shops_str = ($shops_input && is_array($shops_input)) ? implode(',', $shops_input) : '1,2';

        $data = [
            'category_id' => $this->input->post('category_id'),
            'subcategory_id' => $this->input->post('subcategory_id') ? $this->input->post('subcategory_id') : NULL,
            'offer_id' => $this->input->post('offer_id') ? $this->input->post('offer_id') : NULL,
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price') ? $this->input->post('price') : 0,
            'image' => $image,
            'shops' => $shops_str,
            'status' => 1
        ];

        $size_ids = $this->input->post('size_ids');
        $size_prices = $this->input->post('size_prices');
        $addon_ids = $this->input->post('addon_ids');

        $product_id = $this->Common_model->insert('products', $data);

        if ($size_ids && is_array($size_ids)) {
            foreach ($size_ids as $index => $size_id) {
                if (!empty($size_prices[$index])) {
                    $this->Common_model->insert('product_sizes', [
                        'product_id' => $product_id,
                        'size_id' => $size_id,
                        'price' => $size_prices[$index]
                    ]);
                }
            }
        }

        if ($addon_ids && is_array($addon_ids)) {
            foreach ($addon_ids as $addon_id) {
                $this->Common_model->insert('product_addons', [
                    'product_id' => $product_id,
                    'addon_id' => $addon_id
                ]);
            }
        }

        // Link addon groups to the product
        $group_ids = $this->input->post('product_addon_group_ids');

        if ($group_ids && is_array($group_ids)) {
            foreach ($group_ids as $group_id) {
                $this->Common_model->insert('product_addon_groups', [
                    'product_id' => $product_id,
                    'group_id' => $group_id,
                    'min_selections' => 0,
                    'max_selections' => 1,
                    'is_required' => 0,
                    'sort_order' => 0
                ]);
            }
        }

        $this->session->set_flashdata('success', 'Product and sizes added successfully');
        redirect('admin/products');
    }

    public function download_product_sample_csv()
    {
        $this->check_login();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=sample_products_import.csv');

        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Column Headers
        fputcsv($output, ['Name', 'Category', 'Subcategory', 'Description', 'Base_Price', 'Sizes', 'Addon_Groups', 'Direct_Addons', 'Shops']);

        // Sample Data Rows
        fputcsv($output, [
            'Pizza Regina',
            'Pizzas',
            'Base Tomate',
            'Sauce tomate, mozzarella, jambon, champignons',
            '0',
            'Senior:9.00|Mega:13.50|Familiale:17.00',
            'Suppléments Ingrédients, Choix de Sauce',
            'Double Fromage',
            '1,2'
        ]);

        fputcsv($output, [
            'Pizza Chèvre Miel',
            'Pizzas',
            'Base Crème',
            'Crème fraîche, mozzarella, chèvre, miel, noix',
            '0',
            'Senior:10.00|Mega:14.50|Familiale:18.00',
            'Suppléments Ingrédients',
            'Extra Miel',
            '1,2'
        ]);

        fputcsv($output, [
            'Coca-Cola 33cl',
            'Boissons',
            '',
            'Boisson rafraîchissante 33cl',
            '2.00',
            '',
            '',
            '',
            '1,2'
        ]);

        fclose($output);
        exit;
    }

    public function import_products()
    {
        $this->check_login();

        if (empty($_FILES['csv_file']['name'])) {
            $this->session->set_flashdata('error', 'Please select a CSV file to import.');
            redirect('admin/products');
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, "r");

        if ($handle === FALSE) {
            $this->session->set_flashdata('error', 'Failed to open CSV file.');
            redirect('admin/products');
        }

        // Read header row
        $raw_header = fgetcsv($handle, 4096, ",");
        if (!$raw_header || count($raw_header) < 2) {
            rewind($handle);
            $raw_header = fgetcsv($handle, 4096, ";");
        }

        if (!$raw_header) {
            $this->session->set_flashdata('error', 'Invalid CSV file format.');
            fclose($handle);
            redirect('admin/products');
        }

        // Normalize header keys
        $headers = array_map(function($h) {
            $h = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $h);
            return strtolower(trim($h, " \t\n\r\0\x0B\"'"));
        }, $raw_header);

        $col_map = [];
        foreach ($headers as $idx => $name) {
            $name_clean = str_replace(['_', ' '], '', $name);
            if (strpos($name_clean, 'name') !== false || strpos($name_clean, 'nom') !== false || strpos($name_clean, 'product') !== false) {
                if (!isset($col_map['name'])) $col_map['name'] = $idx;
            }
            if (strpos($name_clean, 'subcategory') !== false || strpos($name_clean, 'subcat') !== false || strpos($name_clean, 'souscategorie') !== false) {
                $col_map['subcategory'] = $idx;
            } elseif (strpos($name_clean, 'category') !== false || strpos($name_clean, 'categorie') !== false || strpos($name_clean, 'cat') !== false) {
                $col_map['category'] = $idx;
            }
            if (strpos($name_clean, 'desc') !== false) {
                $col_map['description'] = $idx;
            }
            if (strpos($name_clean, 'price') !== false || strpos($name_clean, 'prix') !== false || strpos($name_clean, 'tarif') !== false) {
                if (!isset($col_map['price']) || strpos($name_clean, 'base') !== false) {
                    $col_map['price'] = $idx;
                }
            }
            if (strpos($name_clean, 'size') !== false || strpos($name_clean, 'taille') !== false || strpos($name_clean, 'format') !== false) {
                $col_map['sizes'] = $idx;
            }
            if (strpos($name_clean, 'addongroup') !== false || strpos($name_clean, 'extragroup') !== false || strpos($name_clean, 'group') !== false) {
                $col_map['addon_groups'] = $idx;
            }
            if (strpos($name_clean, 'directaddon') !== false || strpos($name_clean, 'addon') !== false || strpos($name_clean, 'extra') !== false) {
                if (!isset($col_map['addon_groups']) || $idx != ($col_map['addon_groups'] ?? -1)) {
                    $col_map['direct_addons'] = $idx;
                }
            }
            if (strpos($name_clean, 'shop') !== false || strpos($name_clean, 'magasin') !== false || strpos($name_clean, 'store') !== false) {
                $col_map['shops'] = $idx;
            }
        }

        if (!isset($col_map['name']) || !isset($col_map['category'])) {
            $this->session->set_flashdata('error', 'CSV must contain at least "Name" and "Category" columns.');
            fclose($handle);
            redirect('admin/products');
        }

        $imported = 0;
        $skipped = 0;

        $delimiter = (count($raw_header) > 1 && strpos(implode(',', $raw_header), ';') !== false) ? ';' : ',';

        while (($row = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {
            if (empty($row) || (count($row) == 1 && empty($row[0]))) continue;

            $name = isset($col_map['name']) && isset($row[$col_map['name']]) ? trim($row[$col_map['name']]) : '';
            $cat_name = isset($col_map['category']) && isset($row[$col_map['category']]) ? trim($row[$col_map['category']]) : '';

            if (empty($name) || empty($cat_name)) {
                $skipped++;
                continue;
            }

            // 1. Match or Create Parent Category
            $cat = $this->Common_model->get_single('categories', ['name' => $cat_name, 'parent_id' => 0]);
            if (!$cat) {
                $this->db->where('LOWER(name)', strtolower($cat_name));
                $this->db->where('parent_id', 0);
                $cat = $this->db->get('categories')->row();
            }
            if (!$cat) {
                $cat_id = $this->Common_model->insert('categories', [
                    'name' => $cat_name,
                    'parent_id' => 0,
                    'status' => 1
                ]);
            } else {
                $cat_id = $cat->id;
            }

            // 2. Match or Create Subcategory
            $subcat_id = NULL;
            $subcat_name = isset($col_map['subcategory']) && isset($row[$col_map['subcategory']]) ? trim($row[$col_map['subcategory']]) : '';
            if (!empty($subcat_name)) {
                $subcat = $this->Common_model->get_single('categories', ['name' => $subcat_name, 'parent_id' => $cat_id]);
                if (!$subcat) {
                    $this->db->where('LOWER(name)', strtolower($subcat_name));
                    $this->db->where('parent_id', $cat_id);
                    $subcat = $this->db->get('categories')->row();
                }
                if (!$subcat) {
                    $subcat_id = $this->Common_model->insert('categories', [
                        'name' => $subcat_name,
                        'parent_id' => $cat_id,
                        'status' => 1
                    ]);
                } else {
                    $subcat_id = $subcat->id;
                }
            }

            // 3. Main Product fields
            $description = isset($col_map['description']) && isset($row[$col_map['description']]) ? trim($row[$col_map['description']]) : '';
            $raw_price = isset($col_map['price']) && isset($row[$col_map['price']]) ? trim($row[$col_map['price']]) : '0';
            $clean_price = preg_replace('/[^0-9\.]/', '', str_replace(',', '.', $raw_price));
            $price = is_numeric($clean_price) ? floatval($clean_price) : 0;
            
            $shops_raw = isset($col_map['shops']) && isset($row[$col_map['shops']]) ? trim($row[$col_map['shops']]) : '';
            $shops_arr = [];
            if (!empty($shops_raw)) {
                $parts = explode(',', $shops_raw);
                foreach ($parts as $part) {
                    $p_clean = strtolower(trim($part));
                    if ($p_clean === '1' || strpos($p_clean, 'villiers') !== false) {
                        $shops_arr[] = '1';
                    }
                    if ($p_clean === '2' || strpos($p_clean, 'plessis') !== false || strpos($p_clean, 'bouchard') !== false) {
                        $shops_arr[] = '2';
                    }
                }
            }
            if (empty($shops_arr)) {
                if ($this->session->userdata('admin_role') === 'staff') {
                    $shops_arr[] = (string)$this->session->userdata('shop_id');
                } else {
                    $shops_arr = ['1', '2'];
                }
            }
            $shops_str = implode(',', array_unique($shops_arr));

            $product_id = $this->Common_model->insert('products', [
                'category_id' => $cat_id,
                'subcategory_id' => $subcat_id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'image' => 'default.png',
                'shops' => $shops_str,
                'status' => 1
            ]);

            // 4. Parse Sizes (e.g. Senior:9.00|Mega:13.50|Familiale:17.00 OR Senior:9,00 € ; Mega:13.50 €)
            $sizes_str = isset($col_map['sizes']) && isset($row[$col_map['sizes']]) ? trim($row[$col_map['sizes']]) : '';
            $min_size_price = null;
            if (!empty($sizes_str)) {
                $size_pairs = preg_split('/[|;\n\r]+/', $sizes_str);
                foreach ($size_pairs as $pair) {
                    $pair = trim($pair);
                    if (empty($pair)) continue;

                    $parts = preg_split('/[:=\-]/', $pair, 2);
                    if (count($parts) == 2) {
                        $s_name = trim($parts[0]);
                        $s_price_raw = trim($parts[1]);
                        $clean_s_price = preg_replace('/[^0-9\.]/', '', str_replace(',', '.', $s_price_raw));
                        $s_price = is_numeric($clean_s_price) ? floatval($clean_s_price) : 0;

                        if ($s_name !== '') {
                            $sz = $this->Common_model->get_single('sizes', ['name' => $s_name]);
                            if (!$sz) {
                                $this->db->where('LOWER(name)', strtolower($s_name));
                                $sz = $this->db->get('sizes')->row();
                            }
                            if (!$sz) {
                                $sz_id = $this->Common_model->insert('sizes', ['name' => $s_name]);
                            } else {
                                $sz_id = $sz->id;
                            }

                            // Link size to category in category_sizes table
                            $cs = $this->Common_model->get_single('category_sizes', ['category_id' => $cat_id, 'size_id' => $sz_id]);
                            if (!$cs) {
                                $this->Common_model->insert('category_sizes', ['category_id' => $cat_id, 'size_id' => $sz_id]);
                            }

                            $this->Common_model->insert('product_sizes', [
                                'product_id' => $product_id,
                                'size_id' => $sz_id,
                                'price' => $s_price
                            ]);

                            if ($min_size_price === null || $s_price < $min_size_price) {
                                $min_size_price = $s_price;
                            }
                        }
                    }
                }
            }

            // If base price was 0 or empty, but product has sizes, set product price to minimum size price
            if ($price == 0 && $min_size_price !== null && $min_size_price > 0) {
                $this->Common_model->update('products', ['id' => $product_id], ['price' => $min_size_price]);
            }

            // 5. Parse Addon Groups (e.g. Suppléments Ingrédients, Choix de Sauce)
            $groups_str = isset($col_map['addon_groups']) && isset($row[$col_map['addon_groups']]) ? trim($row[$col_map['addon_groups']]) : '';
            if (!empty($groups_str)) {
                $group_names = preg_split('/[,|]/', $groups_str);
                foreach ($group_names as $g_name) {
                    $g_name = trim($g_name);
                    if (empty($g_name)) continue;

                    $grp = $this->Common_model->get_single('addon_groups', ['name' => $g_name]);
                    if (!$grp) {
                        $this->db->where('LOWER(name)', strtolower($g_name));
                        $grp = $this->db->get('addon_groups')->row();
                    }
                    if (!$grp) {
                        $grp_id = $this->Common_model->insert('addon_groups', ['name' => $g_name]);
                    } else {
                        $grp_id = $grp->id;
                    }

                    $this->Common_model->insert('product_addon_groups', [
                        'product_id' => $product_id,
                        'group_id' => $grp_id,
                        'min_selections' => 0,
                        'max_selections' => 1,
                        'is_required' => 0,
                        'sort_order' => 0
                    ]);
                }
            }

            // 6. Parse Direct Addons (e.g. Double Fromage, Olives)
            $addons_str = isset($col_map['direct_addons']) && isset($row[$col_map['direct_addons']]) ? trim($row[$col_map['direct_addons']]) : '';
            if (!empty($addons_str)) {
                $addon_names = preg_split('/[,|]/', $addons_str);
                foreach ($addon_names as $a_name) {
                    $a_name = trim($a_name);
                    if (empty($a_name)) continue;

                    $adn = $this->Common_model->get_single('addons', ['name' => $a_name]);
                    if (!$adn) {
                        $this->db->where('LOWER(name)', strtolower($a_name));
                        $adn = $this->db->get('addons')->row();
                    }
                    if (!$adn) {
                        $adn_id = $this->Common_model->insert('addons', ['name' => $a_name, 'price' => 0]);
                    } else {
                        $adn_id = $adn->id;
                    }

                    $this->Common_model->insert('product_addons', [
                        'product_id' => $product_id,
                        'addon_id' => $adn_id
                    ]);
                }
            }

            $imported++;
        }

        fclose($handle);

        $msg = "Successfully imported {$imported} product(s).";
        if ($skipped > 0) {
            $msg .= " Skipped {$skipped} row(s) due to missing required fields.";
        }

        $this->session->set_flashdata('success', $msg);
        redirect('admin/products');
    }

    public function delete_product($id)
    {
        $this->check_login();
        $product = $this->Common_model->get_single('products', ['id' => $id]);
        if ($product && $product->image) {
            @unlink('./assets/images/products/' . $product->image);
        }
        $this->Common_model->delete('product_sizes', ['product_id' => $id]);
        $this->Common_model->delete('products', ['id' => $id]);
        $this->session->set_flashdata('success', 'Product deleted successfully');
        redirect('admin/products');
    }

    // Orders
    public function orders()
    {
        $this->check_login();
        $data['title'] = 'Manage Orders';

        if ($this->session->userdata('admin_role') === 'staff') {
            $this->db->select('orders.*, shops.name as shop_name');
            $this->db->from('orders');
            $this->db->join('shops', 'shops.id = orders.shop_id', 'left');
            $this->db->where('orders.shop_id', $this->session->userdata('shop_id'));
            $data['orders'] = $this->db->get()->result();
        } else {
            $this->db->select('orders.*, shops.name as shop_name');
            $this->db->from('orders');
            $this->db->join('shops', 'shops.id = orders.shop_id', 'left');
            $data['orders'] = $this->db->get()->result();
        }

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/orders', $data);
        $this->load->view('admin/includes/footer');
    }

    public function view_order($id)
    {
        $this->check_login();
        $data['title'] = 'View Order Details';
        
        $this->db->select('orders.*, shops.name as shop_name');
        $this->db->from('orders');
        $this->db->join('shops', 'shops.id = orders.shop_id', 'left');
        $this->db->where('orders.id', $id);
        
        if ($this->session->userdata('admin_role') === 'staff') {
            $this->db->where('orders.shop_id', $this->session->userdata('shop_id'));
        }
        
        $data['order'] = $this->db->get()->row();
        
        if (!$data['order']) {
            $this->session->set_flashdata('error', 'Order not found or access denied.');
            redirect('admin/orders');
        }

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/view_order', $data);
        $this->load->view('admin/includes/footer');
    }

    public function ajax_view_order($id)
    {
        $this->check_login();
        
        $this->db->select('orders.*, shops.name as shop_name');
        $this->db->from('orders');
        $this->db->join('shops', 'shops.id = orders.shop_id', 'left');
        $this->db->where('orders.id', $id);
        
        if ($this->session->userdata('admin_role') === 'staff') {
            $this->db->where('orders.shop_id', $this->session->userdata('shop_id'));
        }
        
        $order = $this->db->get()->row();
        
        if ($order) {
            $order->formatted_date = date('d M Y, h:i A', strtotime($order->created_at));
            $order->subtotal = number_format($order->subtotal, 2);
            $order->delivery_fee = number_format($order->delivery_fee, 2);
            $order->total_amount = number_format($order->total_amount, 2);
            echo json_encode(['status' => 'success', 'order' => $order]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Order not found']);
        }
    }

    public function update_order_status($id)
    {
        $this->check_login();
        $status = $this->input->post('status');
        $this->Common_model->update('orders', ['id' => $id], ['status' => $status]);
        $this->session->set_flashdata('success', 'Order status updated successfully');
        redirect('admin/orders');
    }

    public function edit_product($id)
    {
        $this->check_login();
        $data['title'] = 'Edit Product';
        $data['product'] = $this->Common_model->get_single('products', ['id' => $id]);
        $data['categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);
        $data['subcategories'] = [];
        if (!empty($data['product']->category_id)) {
            $data['subcategories'] = $this->Common_model->get_where('categories', ['parent_id' => $data['product']->category_id]);
        }
        $data['all_shops'] = $this->Common_model->get_all('shops');

        // Get sizes for the product's category
        $this->db->select('sizes.*, product_sizes.price as size_price, product_sizes.id as ps_id');
        $this->db->from('sizes');
        $this->db->join('category_sizes', 'category_sizes.size_id = sizes.id');
        $this->db->join('product_sizes', 'product_sizes.size_id = sizes.id AND product_sizes.product_id = ' . $id, 'left');
        $this->db->where('category_sizes.category_id', $data['product']->category_id);
        $data['sizes'] = $this->db->get()->result();

        // Get all addons
        $data['addons'] = $this->Common_model->get_all('addons');

        // Get addons linked to this product
        $this->db->select('addon_id');
        $this->db->from('product_addons');
        $this->db->where('product_id', $id);
        $linked_addons = $this->db->get()->result();
        $data['product_addon_ids'] = array_map(function ($addon) {
            return $addon->addon_id; }, $linked_addons);

        // Get all addon groups and their items
        $groups = $this->Common_model->get_all('addon_groups');
        foreach ($groups as $group) {
            $group->addons = $this->Common_model->get_addons_in_group($group->id);
        }

        // Get product addon groups
        $this->db->select('*');
        $this->db->from('product_addon_groups');
        $this->db->where('product_id', $id);
        $linked_groups = $this->db->get()->result();
        $data['product_addon_groups'] = [];
        foreach ($linked_groups as $lg) {
            $data['product_addon_groups'][$lg->group_id] = $lg;
        }

        // Sort addon_groups: Linked groups first (with product-specific on top and Extra/Supplement on bottom), then unlinked groups
        usort($groups, function ($a, $b) use ($data) {
            $a_linked = isset($data['product_addon_groups'][$a->id]) ? 1 : 0;
            $b_linked = isset($data['product_addon_groups'][$b->id]) ? 1 : 0;
            if ($a_linked !== $b_linked) {
                return $b_linked - $a_linked;
            }
            $a_extra = (stripos($a->name, 'extra') !== false || stripos($a->name, 'supplement') !== false) ? 1 : 0;
            $b_extra = (stripos($b->name, 'extra') !== false || stripos($b->name, 'supplement') !== false) ? 1 : 0;
            if ($a_extra !== $b_extra) {
                return $a_extra - $b_extra;
            }
            return $b->id - $a->id;
        });
        $data['addon_groups'] = $groups;

        // Get all offers
        $data['offers'] = $this->Common_model->get_all('offers');

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/edit_product', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_product($id)
    {
        $this->check_login();

        $product = $this->Common_model->get_single('products', ['id' => $id]);
        $image = $product->image;

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './assets/images/products/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                if ($image)
                    @unlink('./assets/images/products/' . $image);
                $upload_data = $this->upload->data();
                $image = $upload_data['file_name'];
            }
        }

        $shops_input = $this->input->post('shops');
        $shops_str = ($shops_input && is_array($shops_input)) ? implode(',', $shops_input) : '';

        $data = [
            'category_id' => $this->input->post('category_id'),
            'subcategory_id' => $this->input->post('subcategory_id') ? $this->input->post('subcategory_id') : NULL,
            'offer_id' => $this->input->post('offer_id') ? $this->input->post('offer_id') : NULL,
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price') ? $this->input->post('price') : 0,
            'shops' => $shops_str
        ];
        if ($image)
            $data['image'] = $image;

        $this->Common_model->update('products', ['id' => $id], $data);

        // Update Sizes
        $size_ids = $this->input->post('size_ids');
        $size_prices = $this->input->post('size_prices');

        $this->Common_model->delete('product_sizes', ['product_id' => $id]);
        if ($size_ids && is_array($size_ids)) {
            foreach ($size_ids as $index => $size_id) {
                if (!empty($size_prices[$index])) {
                    $this->Common_model->insert('product_sizes', [
                        'product_id' => $id,
                        'size_id' => $size_id,
                        'price' => $size_prices[$index]
                    ]);
                }
            }
        }

        // Update Addons
        $addon_ids = $this->input->post('addon_ids');
        $this->Common_model->delete('product_addons', ['product_id' => $id]);
        if ($addon_ids && is_array($addon_ids)) {
            foreach ($addon_ids as $addon_id) {
                $this->Common_model->insert('product_addons', [
                    'product_id' => $id,
                    'addon_id' => $addon_id
                ]);
            }
        }

        // Update Product Addon Groups
        $group_ids = $this->input->post('product_addon_group_ids');

        $this->Common_model->delete('product_addon_groups', ['product_id' => $id]);
        if ($group_ids && is_array($group_ids)) {
            foreach ($group_ids as $group_id) {
                $this->Common_model->insert('product_addon_groups', [
                    'product_id' => $id,
                    'group_id' => $group_id,
                    'min_selections' => 0,
                    'max_selections' => 1,
                    'is_required' => 0,
                    'sort_order' => 0
                ]);
            }
        }

        $this->session->set_flashdata('success', 'Product updated successfully');
        redirect('admin/products');
    }

    // Offers
    public function offers()
    {
        $this->check_login();
        $data['title'] = 'Manage Offers';
        $data['offers'] = $this->Common_model->get_all('offers');

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/offers', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_offer()
    {
        $this->check_login();
        $offer_name = $this->input->post('offer_name');

        if ($offer_name) {
            $this->Common_model->insert('offers', [
                'offer_name' => $offer_name,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $this->session->set_flashdata('success', 'Offer added successfully');
        } else {
            $this->session->set_flashdata('error', 'Offer name is required');
        }
        redirect('admin/offers');
    }

    public function delete_offer($id)
    {
        $this->check_login();
        
        // Unlink this offer from products first
        $this->Common_model->update('products', ['offer_id' => $id], ['offer_id' => NULL]);
        
        $this->Common_model->delete('offers', ['id' => $id]);
        $this->session->set_flashdata('success', 'Offer deleted successfully');
        redirect('admin/offers');
    }

    public function edit_offer($id)
    {
        $this->check_login();
        $data['title'] = 'Edit Offer';
        $data['offer'] = $this->Common_model->get_single('offers', ['id' => $id]);

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/edit_offer', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_offer($id)
    {
        $this->check_login();
        $offer_name = $this->input->post('offer_name');

        if ($offer_name) {
            $this->Common_model->update('offers', ['id' => $id], ['offer_name' => $offer_name]);
            $this->session->set_flashdata('success', 'Offer updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Offer name is required');
        }
        redirect('admin/offers');
    }

    // Sizes
    public function sizes()
    {
        $this->check_login();
        $data['title'] = 'Manage Sizes';
        $data['sizes'] = $this->Common_model->get_all('sizes');
        $data['categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/sizes', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_size()
    {
        $this->check_login();
        $name = $this->input->post('name');
        $category_ids = $this->input->post('category_ids');

        if ($name) {
            $size_id = $this->Common_model->insert('sizes', ['name' => $name]);

            if ($category_ids && is_array($category_ids)) {
                foreach ($category_ids as $cat_id) {
                    $this->Common_model->insert('category_sizes', [
                        'category_id' => $cat_id,
                        'size_id' => $size_id
                    ]);
                }
            }

            $this->session->set_flashdata('success', 'Size added and linked to categories successfully');
        }
        redirect('admin/sizes');
    }

    public function delete_size($id)
    {
        $this->check_login();
        $this->Common_model->delete('category_sizes', ['size_id' => $id]);
        $this->Common_model->delete('sizes', ['id' => $id]);
        $this->session->set_flashdata('success', 'Size deleted successfully');
        redirect('admin/sizes');
    }

    public function edit_size($id)
    {
        $this->check_login();
        $data['title'] = 'Edit Size';
        $data['size'] = $this->Common_model->get_single('sizes', ['id' => $id]);
        $data['categories'] = $this->Common_model->get_where('categories', ['parent_id' => 0]);
        $data['selected_categories'] = array_column($this->Common_model->get_where('category_sizes', ['size_id' => $id]), 'category_id');

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/edit_size', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_size($id)
    {
        $this->check_login();
        $name = $this->input->post('name');
        $category_ids = $this->input->post('category_ids');

        if ($name) {
            $this->Common_model->update('sizes', ['id' => $id], ['name' => $name]);

            // Refresh category links
            $this->Common_model->delete('category_sizes', ['size_id' => $id]);
            if ($category_ids && is_array($category_ids)) {
                foreach ($category_ids as $cat_id) {
                    $this->Common_model->insert('category_sizes', [
                        'category_id' => $cat_id,
                        'size_id' => $id
                    ]);
                }
            }

            $this->session->set_flashdata('success', 'Size updated successfully');
        }
        redirect('admin/sizes');
    }

    public function get_sizes_by_category($cat_id)
    {
        $this->check_login();
        $this->db->select('sizes.*');
        $this->db->from('sizes');
        $this->db->join('category_sizes', 'category_sizes.size_id = sizes.id');
        $this->db->where('category_sizes.category_id', $cat_id);
        $query = $this->db->get();
        echo json_encode($query->result());
    }

    public function get_subcategories_by_category($parent_id)
    {
        $this->check_login();
        $subcategories = $this->Common_model->get_where('categories', ['parent_id' => $parent_id]);
        echo json_encode($subcategories);
    }

    // Addons (Individual)
    public function addons()
    {
        $this->check_login();
        $data['title'] = 'Manage Addons';
        $data['addons'] = $this->Common_model->get_all('addons');
        $data['sizes'] = $this->Common_model->get_all('sizes');
        $this->Common_model->attach_size_prices($data['addons']);

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/addons', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_addon()
    {
        $this->check_login();
        $data = [
            'name' => $this->input->post('name'),
            'price' => ($this->input->post('price') !== '' && $this->input->post('price') !== null) ? $this->input->post('price') : 0,
            'type' => $this->input->post('type')
        ];
        $addon_id = $this->Common_model->insert('addons', $data);

        $size_prices = $this->input->post('size_prices');
        if (!empty($size_prices) && is_array($size_prices)) {
            foreach ($size_prices as $size_id => $p) {
                if ($p !== '' && $p !== null) {
                    $this->Common_model->insert('addon_size_prices', [
                        'addon_id' => $addon_id,
                        'size_id' => $size_id,
                        'price' => floatval($p)
                    ]);
                }
            }
        }

        $this->session->set_flashdata('success', 'Addon added successfully');
        redirect('admin/addons');
    }

    public function edit_addon($id)
    {
        $this->check_login();
        $data['title'] = 'Edit Addon';
        $addon = $this->Common_model->get_single('addons', ['id' => $id]);
        if ($addon) {
            $addon_arr = [$addon];
            $this->Common_model->attach_size_prices($addon_arr);
        }
        $data['addon'] = $addon;
        $data['sizes'] = $this->Common_model->get_all('sizes');

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/edit_addon', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_addon($id)
    {
        $this->check_login();
        $data = [
            'name' => $this->input->post('name'),
            'price' => ($this->input->post('price') !== '' && $this->input->post('price') !== null) ? $this->input->post('price') : 0,
            'type' => $this->input->post('type')
        ];
        $this->Common_model->update('addons', ['id' => $id], $data);

        $this->Common_model->delete('addon_size_prices', ['addon_id' => $id]);
        $size_prices = $this->input->post('size_prices');
        if (!empty($size_prices) && is_array($size_prices)) {
            foreach ($size_prices as $size_id => $p) {
                if ($p !== '' && $p !== null) {
                    $this->Common_model->insert('addon_size_prices', [
                        'addon_id' => $id,
                        'size_id' => $size_id,
                        'price' => floatval($p)
                    ]);
                }
            }
        }

        $this->session->set_flashdata('success', 'Addon updated successfully');
        redirect('admin/addons');
    }

    public function delete_addon($id)
    {
        $this->check_login();
        $this->Common_model->delete('addons', ['id' => $id]);
        if ($this->db->table_exists('addon_size_prices')) {
            $this->Common_model->delete('addon_size_prices', ['addon_id' => $id]);
        }
        $this->session->set_flashdata('success', 'Addon deleted successfully');
        redirect('admin/addons');
    }

    public function delete_multiple_addons()
    {
        $this->check_login();
        $addon_ids = $this->input->post('addon_ids');
        if (!empty($addon_ids)) {
            if (is_array($addon_ids)) {
                if (count($addon_ids) == 1 && strpos($addon_ids[0], ',') !== false) {
                    $addon_ids = explode(',', $addon_ids[0]);
                }
            } else if (is_string($addon_ids)) {
                $addon_ids = explode(',', $addon_ids);
            }
            foreach ($addon_ids as $id) {
                if (!empty($id)) {
                    $this->Common_model->delete('addons', ['id' => $id]);
                    if ($this->db->table_exists('addon_size_prices')) {
                        $this->Common_model->delete('addon_size_prices', ['addon_id' => $id]);
                    }
                }
            }
            $this->session->set_flashdata('success', 'Selected addons deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'No addons selected');
        }
        redirect('admin/addons');
    }

    // Addon Groups
    public function addon_groups()
    {
        $this->check_login();
        $data['title'] = 'Manage Addon Groups';
        $data['groups'] = $this->Common_model->get_all('addon_groups');
        $data['addons'] = $this->Common_model->get_all('addons');

        // Load addons for each group
        foreach ($data['groups'] as $group) {
            $group->addons = $this->Common_model->get_addons_in_group($group->id);
        }

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/addon_groups', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_addon_group()
    {
        $this->check_login();
        $name = $this->input->post('name');
        $condition = $this->input->post('selection_condition');
        $addon_ids = $this->input->post('addon_ids'); // array of selected addon IDs

        $min_selections = 0;
        $max_selections = 999;

        if ($condition == 'choose_1') {
            $min_selections = 1;
            $max_selections = 1;
        } elseif ($condition == 'choose_2') {
            $min_selections = 2;
            $max_selections = 2;
        } elseif ($condition == 'choose_3') {
            $min_selections = 3;
            $max_selections = 3;
        } elseif ($condition == 'choose_4') {
            $min_selections = 4;
            $max_selections = 4;
        } elseif ($condition == 'choose_5') {
            $min_selections = 5;
            $max_selections = 5;
        }

        if ($name) {
            $group_id = $this->Common_model->insert('addon_groups', [
                'name' => $name,
                'description' => '',
                'min_selections' => $min_selections,
                'max_selections' => $max_selections
            ]);

            if (!empty($addon_ids) && is_array($addon_ids)) {
                foreach ($addon_ids as $addon_id) {
                    $this->Common_model->insert('addon_group_items', [
                        'group_id' => $group_id,
                        'addon_id' => $addon_id
                    ]);
                }
            }
            $this->session->set_flashdata('success', 'Addon Group created successfully');
        } else {
            $this->session->set_flashdata('error', 'Group name is required');
        }
        redirect('admin/addon_groups');
    }

    public function edit_addon_group($id)
    {
        $this->check_login();
        $data['title'] = 'Edit Addon Group';
        $data['group'] = $this->Common_model->get_single('addon_groups', ['id' => $id]);
        $data['addons'] = $this->Common_model->get_all('addons');

        // Get currently linked addon IDs
        $linked_addons = $this->Common_model->get_addons_in_group($id);
        $data['linked_addon_ids'] = array_column($linked_addons, 'id');

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/edit_addon_group', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_addon_group($id)
    {
        $this->check_login();
        $name = $this->input->post('name');
        $condition = $this->input->post('selection_condition');
        $addon_ids = $this->input->post('addon_ids'); // array of selected addon IDs

        $min_selections = 0;
        $max_selections = 999;

        if ($condition == 'choose_1') {
            $min_selections = 1;
            $max_selections = 1;
        } elseif ($condition == 'choose_2') {
            $min_selections = 2;
            $max_selections = 2;
        } elseif ($condition == 'choose_3') {
            $min_selections = 3;
            $max_selections = 3;
        } elseif ($condition == 'choose_4') {
            $min_selections = 4;
            $max_selections = 4;
        } elseif ($condition == 'choose_5') {
            $min_selections = 5;
            $max_selections = 5;
        }

        if ($name) {
            $this->Common_model->update('addon_groups', ['id' => $id], [
                'name' => $name,
                'description' => '',
                'min_selections' => $min_selections,
                'max_selections' => $max_selections
            ]);

            // Delete old linkages and insert new ones
            $this->Common_model->delete('addon_group_items', ['group_id' => $id]);

            if (!empty($addon_ids) && is_array($addon_ids)) {
                foreach ($addon_ids as $addon_id) {
                    $this->Common_model->insert('addon_group_items', [
                        'group_id' => $id,
                        'addon_id' => $addon_id
                    ]);
                }
            }
            $this->session->set_flashdata('success', 'Addon Group updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Group name is required');
        }
        redirect('admin/addon_groups');
    }

    public function delete_addon_group($id)
    {
        $this->check_login();
        $this->Common_model->delete('addon_groups', ['id' => $id]);
        $this->session->set_flashdata('success', 'Addon Group deleted successfully');
        redirect('admin/addon_groups');
    }

    public function delete_multiple_addon_groups()
    {
        $this->check_login();
        $group_ids = $this->input->post('group_ids');
        if (!empty($group_ids)) {
            if (is_array($group_ids)) {
                if (count($group_ids) == 1 && strpos($group_ids[0], ',') !== false) {
                    $group_ids = explode(',', $group_ids[0]);
                }
            } else if (is_string($group_ids)) {
                $group_ids = explode(',', $group_ids);
            }
            foreach ($group_ids as $id) {
                if (!empty($id)) {
                    $this->Common_model->delete('addon_groups', ['id' => $id]);
                }
            }
            $this->session->set_flashdata('success', 'Selected Addon Groups deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'No Addon Groups selected');
        }
        redirect('admin/addon_groups');
    }


    public function admins()
    {
        $this->check_login();
        $data['title'] = 'Manage Admin Users';
        $data['admins'] = $this->Common_model->get_all('admins');
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/admins', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_admin()
    {
        $this->check_login();
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $role = $this->input->post('role');
        if ($username && $password && $role) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $this->Common_model->insert('admins', [
                'username' => $username,
                'password_hash' => $hash,
                'role' => $role
            ]);
            $this->session->set_flashdata('success', 'Admin user added');
        } else {
            $this->session->set_flashdata('error', 'All fields required');
        }
        redirect('admin/admins');
    }

    public function edit_admin($id)
    {
        $this->check_login();
        $data['title'] = 'Edit Admin User';
        $data['admin'] = $this->Common_model->get_single('admins', ['id' => $id]);
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/edit_admin', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_admin($id)
    {
        $this->check_login();
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $role = $this->input->post('role');
        $data = ['username' => $username, 'role' => $role];
        if ($password) {
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }
        $this->Common_model->update('admins', ['id' => $id], $data);
        $this->session->set_flashdata('success', 'Admin updated');
        redirect('admin/admins');
    }

    public function delete_admin($id)
    {
        $this->check_login();
        $this->Common_model->delete('admins', ['id' => $id]);
        $this->session->set_flashdata('success', 'Admin deleted');
        redirect('admin/admins');
    }

    // Seed default accounts (superadmin, admin, staff)
    public function seed_accounts()
    {
        $this->check_login();
        $accounts = [
            ['username' => 'superadmin', 'password' => 'Pizza@123*', 'role' => 'super_admin'],
            ['username' => 'admin', 'password' => 'Admin@123', 'role' => 'admin'],
            ['username' => 'staff', 'password' => 'Staff@123', 'role' => 'staff']
        ];
        foreach ($accounts as $acc) {
            $existing = $this->Common_model->get_single('admins', ['username' => $acc['username']]);
            if (!$existing) {
                $this->Common_model->insert('admins', [
                    'username' => $acc['username'],
                    'password_hash' => password_hash($acc['password'], PASSWORD_BCRYPT),
                    'role' => $acc['role']
                ]);
            }
        }
        $this->session->set_flashdata('success', 'Default accounts seeded');
        redirect('admin/admins');
    }

    // Reset a user's password
    public function reset_user_password($id)
    {
        $this->check_login();
        $new_password = $this->input->post('new_password');
        if ($new_password) {
            $hash = password_hash($new_password, PASSWORD_BCRYPT);
            $this->Common_model->update('admins', ['id' => $id], ['password_hash' => $hash]);
            $this->session->set_flashdata('success', 'Password reset');
        } else {
            $this->session->set_flashdata('error', 'New password required');
        }
        redirect('admin/admins');
    }


    // --- Shop Users Management ---
    public function shop_users()
    {
        $this->check_login();
        $data['title'] = 'Manage Shop Users';
        $data['users'] = $this->Shop_user_model->get_all_with_shop();
        $data['shops'] = $this->Common_model->get_all('shops');

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/shop_users', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_shop_user()
    {
        $this->check_login();
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[shop_users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('shop_id', 'Shop', 'required');

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'name' => $this->input->post('name'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'shop_id' => $this->input->post('shop_id')
            ];
            $this->Shop_user_model->create($data);
            $this->session->set_flashdata('success', 'User added successfully');
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
        redirect('admin/shop_users');
    }

    public function delete_shop_user($id)
    {
        $this->check_login();
        $this->Shop_user_model->delete($id);
        $this->session->set_flashdata('success', 'User deleted successfully');
        redirect('admin/shop_users');
    }

    // --- Reviews Management ---
    public function reviews()
    {
        $this->check_login();
        $data['title'] = 'Manage Reviews';
        $this->db->order_by('id', 'DESC');
        $data['reviews'] = $this->db->get('reviews')->result();

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/reviews', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_review()
    {
        $this->check_login();
        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
        $this->form_validation->set_rules('rating', 'Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('comment', 'Comment', 'required');

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'customer_name' => $this->input->post('customer_name'),
                'rating' => $this->input->post('rating'),
                'comment' => $this->input->post('comment'),
                'status' => $this->input->post('status') ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->Common_model->insert('reviews', $data);
            $this->session->set_flashdata('success', 'Review added successfully');
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
        redirect('admin/reviews');
    }

    public function update_review_status($id)
    {
        $this->check_login();
        $status = $this->input->post('status');
        $this->Common_model->update('reviews', ['id' => $id], ['status' => $status]);
        $this->session->set_flashdata('success', 'Review status updated');
        redirect('admin/reviews');
    }

    public function delete_review($id)
    {
        $this->check_login();
        $this->Common_model->delete('reviews', ['id' => $id]);
        $this->session->set_flashdata('success', 'Review deleted successfully');
        redirect('admin/reviews');
    }
    public function slider_videos()
    {
        $this->check_login();
        $data['title'] = 'Manage Slider Videos';
        $data['videos'] = $this->Common_model->get_all('slider_videos', 'id', 'DESC');
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/slider_videos', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_slider_video()
    {
        $this->check_login();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
            $this->session->set_flashdata('error', 'The uploaded video file is too large. Please upload a smaller video (Max ' . ini_get('post_max_size') . ').');
            redirect('admin/slider_videos');
        }

        $this->form_validation->set_rules('title', 'Title', 'required');

        if ($this->form_validation->run() == TRUE) {
            $video_filename = '';
            if (isset($_FILES['video_file']['name']) && !empty($_FILES['video_file']['name'])) {
                $config['upload_path'] = './assets/videos/';
                $config['allowed_types'] = 'mp4|avi|mov|wmv|webm';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('video_file')) {
                    $upload_data = $this->upload->data();
                    $video_filename = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/slider_videos');
                }
            } else {
                $this->session->set_flashdata('error', 'Video file is required.');
                redirect('admin/slider_videos');
            }

            $data = [
                'title' => $this->input->post('title'),
                'video_url' => $video_filename,
                'status' => $this->input->post('status') ? 1 : 0
            ];
            $this->Common_model->insert('slider_videos', $data);
            $this->session->set_flashdata('success', 'Slider Video added successfully');
        } else {
            if (validation_errors()) {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
        redirect('admin/slider_videos');
    }

    public function edit_slider_video($id)
    {
        $this->check_login();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
            $this->session->set_flashdata('error', 'The uploaded video file is too large. Please upload a smaller video (Max ' . ini_get('post_max_size') . ').');
            redirect('admin/edit_slider_video/' . $id);
        }

        $data['title'] = 'Edit Slider Video';
        $data['video'] = $this->Common_model->get_single('slider_videos', ['id' => $id]);

        if (!$data['video']) {
            redirect('admin/slider_videos');
        }

        $this->form_validation->set_rules('title', 'Title', 'required');

        if ($this->form_validation->run() == TRUE) {
            $update_data = [
                'title' => $this->input->post('title'),
                'status' => $this->input->post('status') ? 1 : 0
            ];

            if (isset($_FILES['video_file']['name']) && !empty($_FILES['video_file']['name'])) {
                $config['upload_path'] = './assets/videos/';
                $config['allowed_types'] = 'mp4|avi|mov|wmv|webm';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('video_file')) {
                    $upload_data = $this->upload->data();
                    $update_data['video_url'] = $upload_data['file_name'];
                    
                    // Delete old video
                    if (file_exists('./assets/videos/' . $data['video']->video_url) && $data['video']->video_url != '') {
                        unlink('./assets/videos/' . $data['video']->video_url);
                    }
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/edit_slider_video/' . $id);
                }
            }
            
            $this->Common_model->update('slider_videos', ['id' => $id], $update_data);
            $this->session->set_flashdata('success', 'Slider Video updated successfully');
            redirect('admin/slider_videos');
        }

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/edit_slider_video', $data);
        $this->load->view('admin/includes/footer');
    }

    public function delete_slider_video($id)
    {
        $this->check_login();
        $video = $this->Common_model->get_single('slider_videos', ['id' => $id]);
        if ($video) {
            if (file_exists('./assets/videos/' . $video->video_url) && $video->video_url != '') {
                unlink('./assets/videos/' . $video->video_url);
            }
            $this->Common_model->delete('slider_videos', ['id' => $id]);
            $this->session->set_flashdata('success', 'Slider Video deleted successfully');
        }
        redirect('admin/slider_videos');
    }

    // ==========================================
    // Customer Management Methods
    // ==========================================
    public function customers()
    {
        $this->check_login();
        $data['title'] = 'Manage Customers';

        // Fetch customers with order statistics
        $sql = "SELECT u.*, 
                       COUNT(o.id) as total_orders, 
                       IFNULL(SUM(o.total_amount), 0) as total_spent,
                       MAX(o.created_at) as last_order_date
                FROM users u
                LEFT JOIN orders o ON (o.user_id = u.id OR (u.phone IS NOT NULL AND u.phone != '' AND o.customer_phone = u.phone))
                GROUP BY u.id
                ORDER BY u.id DESC";
        $data['customers'] = $this->db->query($sql)->result();

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/customers', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_customer()
    {
        $this->check_login();

        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $insert_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'password_hash' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->Common_model->insert('users', $insert_data);
            $this->session->set_flashdata('success', 'Customer added successfully!');
        }

        redirect('admin/customers');
    }

    public function edit_customer($id)
    {
        $this->check_login();
        $customer = $this->Common_model->get_single('users', ['id' => $id]);

        if (!$customer) {
            $this->session->set_flashdata('error', 'Customer not found.');
            redirect('admin/customers');
        }

        $email = $this->input->post('email');
        if ($email != $customer->email) {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        }
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $update_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $email,
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            ];

            $password = $this->input->post('password');
            if (!empty($password)) {
                $update_data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $this->Common_model->update('users', ['id' => $id], $update_data);
            $this->session->set_flashdata('success', 'Customer details updated successfully!');
        }

        redirect('admin/customers');
    }

    public function delete_customer($id)
    {
        $this->check_login();
        $customer = $this->Common_model->get_single('users', ['id' => $id]);
        if ($customer) {
            $this->Common_model->delete('users', ['id' => $id]);
            $this->session->set_flashdata('success', 'Customer deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Customer not found.');
        }
        redirect('admin/customers');
    }

    public function customer_details_json($id)
    {
        $this->check_login();
        $customer = $this->Common_model->get_single('users', ['id' => $id]);
        if (!$customer) {
            echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
            return;
        }

        $sql = "SELECT o.*, s.name as shop_name 
                FROM orders o 
                LEFT JOIN shops s ON s.id = o.shop_id 
                WHERE o.user_id = ? OR (o.customer_phone IS NOT NULL AND o.customer_phone != '' AND o.customer_phone = ?) 
                ORDER BY o.id DESC";
        $orders = $this->db->query($sql, [$id, $customer->phone])->result();

        echo json_encode([
            'status' => 'success',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->first_name . ' ' . $customer->last_name,
                'email' => $customer->email,
                'phone' => $customer->phone ?: 'N/A',
                'address' => $customer->address ?: 'N/A',
                'created_at' => date('d M Y, h:i A', strtotime($customer->created_at))
            ],
            'orders' => $orders
        ]);
    }

    // ==========================================
    // SMTP Settings Management
    // ==========================================
    private function ensure_smtp_table_exists()
    {
        if (!$this->db->table_exists('smtp_settings')) {
            $this->load->dbforge();
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'smtp_host' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => ''
                ],
                'smtp_port' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 587
                ],
                'smtp_crypto' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                    'default' => 'tls'
                ],
                'smtp_user' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => ''
                ],
                'smtp_pass' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => ''
                ],
                'from_email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => ''
                ],
                'from_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => 'Pizza One'
                ],
                'is_active' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => FALSE
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => TRUE,
                    'default' => NULL
                ]
            ];
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table('smtp_settings', TRUE);

            $this->db->insert('smtp_settings', [
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'smtp_crypto' => 'tls',
                'from_name' => 'Pizza One',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function smtp_settings()
    {
        $this->check_login();
        $this->ensure_smtp_table_exists();
        $data['title'] = 'SMTP Settings';
        
        // Fetch current SMTP settings
        $smtp = $this->db->get('smtp_settings')->row();
        if (!$smtp) {
            $smtp = (object)[
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'smtp_crypto' => 'tls',
                'smtp_user' => '',
                'smtp_pass' => '',
                'from_email' => '',
                'from_name' => 'Pizza One',
                'is_active' => 1
            ];
        }
        $data['smtp'] = $smtp;

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $update_data = [
                'smtp_host' => trim($this->input->post('smtp_host', true)),
                'smtp_port' => intval($this->input->post('smtp_port', true)),
                'smtp_crypto' => trim($this->input->post('smtp_crypto', true)),
                'smtp_user' => trim($this->input->post('smtp_user', true)),
                'smtp_pass' => trim($this->input->post('smtp_pass', true)),
                'from_email' => trim($this->input->post('from_email', true)),
                'from_name' => trim($this->input->post('from_name', true)),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $check = $this->db->get('smtp_settings')->row();
            if ($check) {
                $this->db->where('id', $check->id)->update('smtp_settings', $update_data);
            } else {
                $update_data['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('smtp_settings', $update_data);
            }

            $this->session->set_flashdata('success', 'SMTP settings updated successfully!');
            redirect('admin/smtp_settings');
        }

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/smtp_settings', $data);
        $this->load->view('admin/includes/footer');
    }

    public function test_smtp()
    {
        $this->check_login();
        $this->ensure_smtp_table_exists();
        $test_email = $this->input->post('test_email', true);

        if (!filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid test recipient email address.']);
            return;
        }

        $smtp = $this->db->get('smtp_settings')->row();
        if (!$smtp || !$smtp->is_active) {
            echo json_encode(['status' => 'error', 'message' => 'SMTP is currently disabled or not configured.']);
            return;
        }

        // Configure CodeIgniter Email Library with SMTP
        $config = [
            'protocol'   => 'smtp',
            'smtp_host'  => $smtp->smtp_host,
            'smtp_port'  => $smtp->smtp_port,
            'smtp_crypto'=> $smtp->smtp_crypto,
            'smtp_user'  => $smtp->smtp_user,
            'smtp_pass'  => $smtp->smtp_pass,
            'mailtype'   => 'html',
            'charset'    => 'utf-8',
            'wordwrap'   => TRUE,
            'newline'    => "\r\n",
            'crlf'       => "\r\n"
        ];

        $this->load->library('email');
        $this->email->initialize($config);

        $from_email = !empty($smtp->from_email) ? $smtp->from_email : $smtp->smtp_user;
        $from_name  = !empty($smtp->from_name) ? $smtp->from_name : 'Pizza One';

        $this->email->from($from_email, $from_name);
        $this->email->to($test_email);
        $this->email->subject('🍕 SMTP Connection Test - Pizza One');
        
        $body = "<div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>";
        $body .= "<h2 style='color: #e74c3c;'>Pizza One SMTP Test</h2>";
        $body .= "<p>Congratulations! Your SMTP settings are working properly.</p>";
        $body .= "<p><strong>Sent at:</strong> " . date('Y-m-d H:i:s') . "</p>";
        $body .= "<p><strong>Host:</strong> " . htmlspecialchars($smtp->smtp_host) . ":" . $smtp->smtp_port . "</p>";
        $body .= "</div>";

        $this->email->message($body);

        if ($this->email->send()) {
            echo json_encode(['status' => 'success', 'message' => 'Test email sent successfully to ' . $test_email]);
        } else {
            $debugger = $this->email->print_debugger(['headers']);
            echo json_encode(['status' => 'error', 'message' => 'Failed to send email. Error: ' . strip_tags($debugger)]);
        }
    }
}
?>