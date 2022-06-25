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

/* Routes related to Order module starts */
$route['stocks/adminindex'] = "stocks/admin_index";
$route['stocks/adminstockoutindex'] = "stocks/admin_stockout_index";
$route['stocks/adminview/(:any)'] = "stocks/admin_view/$1";

$route['stocks'] = "stocks/admin_index";
/*$route['stocks/admin-list'] = 'stocks/';
$route['stocks/admin-list'] = 'stocks';*/
$route['stocks/newstock'] = "stocks/admin_add";
$route['stocks/dispatch'] = "stocks/admin_new_stock_out_2";
$route['stocks/editstock/(:any)'] = "stocks/admin_edit/$1";
$route['stocks/deletestock/(:any)'] = "stocks/admin_delete/$1";
$route['stocks/deletestockout/(:any)'] = "stocks/admin_stockout_delete/$1";

//$route['stocks/deletestock'] = "stocks/admin_delete";

$route['stocks/editstock'] = 'error/error_404';
$route['stocks/adminview/(:any)'] = "stocks/admin_view/$1";
$route['stocks/out'] = "stocks/admin_new_stock_out";
$route['stocks/out/(:any)'] = "stocks/admin_new_stock_out/$1";
$route['stocks/editout/(:any)'] = "stocks/admin_edit_stock_out/$1";
$route['stocks/coilwiseso'] = "stocks/admin_stock_report";
$route['stocks/multidispatch'] = "stocks/multiple_stock_out";
$route['stocks/out2'] = "stocks/admin_new_stock_out_3";
$route['stocks/out2/(:any)'] = "stocks/admin_new_stock_out_3/$1";

/* End Order routes */
