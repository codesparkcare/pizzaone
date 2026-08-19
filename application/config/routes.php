<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = 'admin';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/login'] = 'admin/login';
$route['admin/logout'] = 'admin/logout';
$route['admin/categories'] = 'admin/categories';
$route['admin/add_category'] = 'admin/add_category';
$route['admin/edit_category/(:any)'] = 'admin/edit_category/$1';
$route['admin/update_category/(:any)'] = 'admin/update_category/$1';
$route['admin/delete_category/(:any)'] = 'admin/delete_category/$1';
$route['admin/products'] = 'admin/products';
$route['admin/orders'] = 'admin/orders';
$route['admin/sizes'] = 'admin/sizes';
$route['admin/add_size'] = 'admin/add_size';
$route['admin/delete_size/(:any)'] = 'admin/delete_size/$1';
$route['admin/edit_size/(:any)'] = 'admin/edit_size/$1';
$route['admin/update_size/(:any)'] = 'admin/update_size/$1';
$route['admin/add_product'] = 'admin/add_product';
$route['admin/edit_product/(:any)'] = 'admin/edit_product/$1';
$route['admin/update_product/(:any)'] = 'admin/update_product/$1';
$route['admin/admins'] = 'admin/admins';
$route['admin/add_admin'] = 'admin/add_admin';
$route['admin/edit_admin/(:num)'] = 'admin/edit_admin/$1';
$route['admin/delete_admin/(:num)'] = 'admin/delete_admin/$1';

$route['about'] = 'welcome/about';
$route['contact'] = 'welcome/contact';
$route['menu'] = 'welcome/menu';
$route['menu/(:any)'] = 'welcome/menu/$1';
$route['api/product-details/(:any)'] = 'welcome/get_product_details/$1';
$route['wishlist'] = 'user/wishlist';

// Cart routes
$route['cart/add/(:any)'] = 'cart/add/$1';
$route['cart/add_item'] = 'cart/add_item';
$route['cart/view'] = 'cart/view';
$route['cart/remove_item'] = 'cart/remove_item';
$route['cart/update_quantity'] = 'cart/update_quantity';
$route['cart'] = 'cart/view';
$route['cart/clear'] = 'cart/clear';
$route['cart/my_orders'] = 'cart/my_orders';
