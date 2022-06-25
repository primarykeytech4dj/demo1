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
 
 
class Errors extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Redirect to the login if page requested is in a protected section
		foreach(custom_constants::$protected_pages as $page)
		{
			if(strpos($this->uri->uri_string, $page) === 0)
			{
				check_user_login(FALSE);
			}
		}
	}
	
	function error_404() {
		$data['meta_title'] = "No Data Found";
		$data['title'] = "No Data Found";
		$data['meta_description'] = "No Data Found";
		$data['meta_keyword'] = "No Data Found";
		
		$data['modules'][] = "errors";
		$data['methods'][] = "view_error_404";
		
		echo Modules::run("templates/default_template", $data);
	}

	function coming_soon() {
		$data['meta_title'] = "Coming Soon";
		$data['title'] = "Coming Soon";
		$data['meta_description'] = "Coming Soon";
		$data['meta_keyword'] = "Coming Soon, under development";
		
		$data['modules'][] = "errors";
		$data['methods'][] = "view_coming_soon";
		
		echo Modules::run("templates/default_template", $data);
	}
	
	function view_error_404() {
		$this->load->view("errors/error_404");
	}

	function view_coming_soon() {
		$this->load->view("errors/coming_soon");
	}
}
