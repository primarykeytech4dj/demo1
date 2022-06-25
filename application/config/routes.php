<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = "home";

$route['404_override'] 			= 'errors/error_404';

/* Login module related routes  */

$route['login/user_logout'] = "";
$route['logout'] = "login/user_logout";

/* Admin Panel Dashboard module related routes  */

$route['admin_panel'] = "";
$route['admin'] = "admin_panel";
$route['contact-us'] = "companies";
$route['about-us/(:any)'] = "companies/about_company/$1";
$route['about-us'] = "companies/about_company";
//$route['product-category/(:any)'] = "products/get_categorylist/$1";
$route['product-category'] = "products/product_list";
$route['category/(:any)'] = "products/category/$1";
//$route['product-category'] = "products/get_categorylist";
$route['left-product-category/(:any)'] = "products/left_get_categorywise_product/$1";
$route['right-product-category/(:any)'] = "products/right_get_categorywise_product/$1";
$route['product/(:any)'] = "products/get_single_product/$1";
$route['product-list/(:any)'] = "products/left_get_categorywise_product/$1";
$route['projects'] = "orders/index";
$route['project-detail/(:any)'] = "orders/view/$1";
$route['project-detail'] = "orders/index";

//$route['services/(:any)'] = "products/index/$1";
//$route['(services)'] = "services/category_index";
//$route['services'] = "services/index";
$route['projects'] = "products/projects";
$route['projects/category/(:any)'] = "products/category/$1";


/* End of file routes.php */
/* Location: ./application/config/routes.php */