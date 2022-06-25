<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 |	CONTROLLER SUMMARY AND DATABASE TABLES
 |--------------------------------------------------------------------------
 | 
 |	This is where you can start your admin/manage/password protected stuff.
 | 
 |	Database table structure
 |
 |	Table name(s) - no tables
 |
 |
 */
 
 
class Home extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Check login and make sure email has been verified
		//check_user_login(FALSE);
	}

    function index() {
    	$data['id'] = custom_constants::company_id;
    	//echo $data['id'];
		$home = Modules::run('companies/get_company_details', $data);
		//print_r($home);exit;
		$data['meta_title'] = $home['meta_title'];
		$data['meta_description'] = $home['meta_description'];
		$data['title'] = $home['meta_title'];
		$data['meta_keyword'] = $home['meta_keyword'];
		$sliderCondition = [
			'limit'=>4, 
			'condition'=>[
				'sliders.slider_code'=>'home_001',
				'sliders.is_active' => true
				
			]
		];

    	$data['slider'] = Modules::run('sliders/venedor_slider', $sliderCondition);
    	//echo "hiii";exit;
    	//print_r($data['slider']);exit;
    	$saleCondition = [
			'limit'=>12, 
			'condition'=>[
				//'products.is_active'=>true,
				'products.is_sale'=>true,
				'products.show_on_website'=>true
			]
		];
    	$data['saleProduct'] = Modules::run('products/sale_product', $saleCondition);
    	$latestCondition = [
			/*'limit'=>4,*/
			'order'=>'created', 
			'condition'=>[
				//'products.is_active'=>true, 
				'products.is_new' => true,
				'products.show_on_website'=>true
			]
		];
    	$data['latestProduct'] = Modules::run('products/latest_product_slider', $latestCondition);
    	
    	$data['slider'] = Modules::run('sliders/venedor_slider');
    	
    	//echo "hii";exit;
    	//echo '<pre>';print_r($data['latestProduct']);exit;
		$data['modules'][] = "home";
		$data['methods'][] = "view_home_default";
		//print_r($data);exit;
		echo Modules::run("templates/default_template", $data);
    }
	
	function view_home_default() {
		$data['aboutUs'] = Modules::run('companies/aboutus');
		//$data['ourServices'] = Modules::run('products/get_service_list');
		//$data['blogs'] = Modules::run('blogs/blog_listing', ['order'=>'blogs.published_on desc', 'limit'=>3]);
		$this->pktdblib->set_table("manufacturing_brands");
		$brands = $this->pktdblib->get_where_custom('is_active', true);
		$data['brands'] = $brands->result_array();
		//print_r($data['brands']);exit;
		$this->load->view("home/home_default", $data);
	}
}
