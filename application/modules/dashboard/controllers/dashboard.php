<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 |	CONTROLLER SUMMARY AND DATABASE TABLES
 |--------------------------------------------------------------------------
 | 
 |	Shows custom error_404 page
 |
 |	Database table structure
 |
 |	Table name(s) - No table
 |
 */
 
 
class Dashboard extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('check_login');
		// Redirect to the login if page requested is in a protected section
		foreach(custom_constants::$protected_pages as $page)
		{
			if(strpos($this->uri->uri_string, $page) === 0)
			{
				check_user_login(TRUE);
			}
		}

		check_user_login(TRUE);

	}
	
	function dashboard(){
        $data['title'] = 'Dashboard';
        $data['meta_title'] = 'Dashboard';
        $data['meta_description'] = 'Dashboard';
        $data['meta_keyword'] = 'Dashboard';
        $data['modules'][] = 'dashboard';
        $data['methods'][] = 'home_page';
        echo Modules::run("templates/memberpanel_template" ,$data);
    }

    function home_page($data = []) {
        $this->load->view("dashboard/dashboard", $data);
    }
}
