<?php 

class Wishlist extends MY_Controller {
	function __construct() {
		parent::__construct();

		/*foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
			}
		}*/
		check_user_login(TRUE);
		$this->load->model('checkout/checkout_model');
	}

    function index()
	{
		$data['content'] = 'wishlist/index'; // Select our view file that will display our products
    	
    	$data['title'] = 'Wishlist';
		$data['meta_title'] = 'Wishlist';
		$data['meta_description'] = 'Wishlist';
		$data['meta_keyword'] = 'Wishlist';
		
		echo Modules::run('templates/default_template', $data);
    	
	}

}
	