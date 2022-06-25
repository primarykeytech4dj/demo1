<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends MY_Controller {
	function __construct() {
		parent::__construct();
		//check_user_login(FALSE);
	}

	function initialize() {
       $ci =& get_instance();
       $ci->load->helper('language');
       $siteLang = $ci->session->userdata('site_lang');
       if ($siteLang) {
           $ci->lang->load('content',$siteLang);
       } else {
           $ci->lang->load('content','english');
       }
   }

}