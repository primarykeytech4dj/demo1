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

$route['default_controller'] 				= "admin_panel";
$route['404_override'] 						= 'error/error_404';

$route['admin']								= "admin_panel/index";

$route['logout'] = 'login/user_logout';


$route['deliveryboy'] = "fieldmember_panel/index";
$route['assigndeliveryboy'] = 'fieldmember_panel/admin_assign';


/* End of file routes.php */

/* Location: ./application/config/routes.php */

/*$route['news'] = "news/admin_index";
$route['news/index'] = "news/admin_index";
$route['createNews'] = "news/admin_add";
$route['editNews/(:any)'] = "news/admin_edit/$1";
$route['editNews'] = 'errors/error_404';
$route['readNews/(:any)'] = 'news/admin_view/$1';
$route['readNews'] = 'errors/error_404';*/

/* product category module related routes  */
/*Backend*/
/*$route['newsCategories'] = "news/admin_index_category";
$route['createNewsCategory'] = "news/admin_add_category";
$route['editNewsCategory/(:any)'] = "news/admin_edit_category/$1";
$route['editNewsCategory'] = 'errors/error_404';
$route['readNewsCategory/(:any)'] = 'news/admin_view_category/$1';
$route['readNewsCategory'] = 'errors/error_404';*/