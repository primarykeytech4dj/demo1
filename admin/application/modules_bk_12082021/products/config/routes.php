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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = "home";
$route['404_override'] = 'error/error_404';

/* products module related routes  */
/*Backend*/
$route['products/adminindex'] = "products/admin_index";
$route['products'] = "products/admin_index";
$route['products/newproduct'] = "products/admin_add";
$route['products/editproduct/(:any)'] = "products/admin_edit/$1";
$route['products/editproduct'] = 'error/error_404';
$route['products/adminview/(:any)'] = 'products/admin_view/$1';
$route['products/adminview'] = 'error/error_404';
$route['products/productmasters'] = 'products/upload_product_master_csv_file';
/* product category module related routes  */
/*Backend*/
$route['products/adminindexcategory'] = "products/admin_category_index";
$route['products/newcategory'] = "products/admin_add_category";
$route['products/editcategory/(:any)'] = "products/admin_edit_category/$1";
$route['products/editcategory'] = 'error/error_404';
$route['products/adminviewcategory/(:any)'] = 'products/admin_view_category/$1';
$route['products/adminviewcategory'] = 'error/error_404';
$route['products/update-product-snp'] = 'products/update_product_price_stock';




