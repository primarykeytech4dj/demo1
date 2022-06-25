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

/* news module related routes  */
/*Backend*/
$route['news'] = "news/admin_index";
$route['news/index'] = "news/admin_index";
$route['NewNews'] = "news/admin_add";
$route['EditNews/(:any)'] = "news/admin_edit/$1";
$route['EditNews'] = 'errors/error_404';
$route['ReadNews/(:any)'] = 'news/admin_view/$1';
$route['ReadNews'] = 'errors/error_404';

/* News category module related routes  */
/*Backend*/
$route['NewsCategories'] = "news/admin_index_category";
$route['NewNewsCategory'] = "news/admin_add_category";
$route['EditNewsCategory/(:any)'] = "news/admin_edit_category/$1";
$route['EditNewsCategory'] = 'errors/error_404';
$route['ReadNewsCategory/(:any)'] = 'news/admin_view_category/$1';
$route['ReadNewsCategory'] = 'errors/error_404';
