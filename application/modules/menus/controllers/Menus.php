<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Check login and make sure email has been verified
		foreach(custom_constants::$protected_pages as $page)
        {   
            if(strpos($this->uri->uri_string, $page) === 0)
            {   
                check_user_login(FALSE);
            }
        }
	
	}

    function get_menu($parentId = 0, $menuId = 1){
        $data['menus'] = $this->pktlib->create_nested_menu($parentId, $menuId);
        $this->pktlib->parseOutput($this->config->item('response_format'), $data);
        return $data;
    }
    
    function get_submenu($parentId = 0, $menuId = 1){
        $data['menus'] = $this->pktlib->get_parentwise_menu($parentId, $menuId);
        $this->pktlib->parseOutput($this->config->item('response_format'), $data);
        return $data;
    }
}