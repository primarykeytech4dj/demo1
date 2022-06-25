<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 |	CONTROLLER SUMMARY AND DATABASE TABLES
 |--------------------------------------------------------------------------
 | 
 |	Templates is used to put together the main structure of the HTML view. It
 |	calls head, header, content and footer in most cases. Other items can been
 |	called and used. Each part can be dynamic but content is loaded through
 |	modules and methods.
 |
 |	Database table structure
 |
 |	No table
 |
 */

class Templates extends MY_Controller
{
	private $meta_module;
	private $id;
	private $templateType;
	private $template = 'admin';
	function __construct() {
		parent::__construct();
		// change id in case of id other than 1
		$this->id = 1;
		$this->templateType = 'companies';
		/*echo '<pre>';
		print_r($this->session->userdata('roles'));exit;*/
		if(NULL!==$this->session->userdata('roles') && (in_array(6, $this->session->userdata('roles')) || in_array(7, $this->session->userdata('roles')))){
		    $this->template = 'fieldmember';
		}
	}

	function admin_template($data) {
	    $companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);
		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		$this->session->set_userdata($data['meta_keyword']=$data['websiteInfo']['meta_keyword']);
		$this->session->set_userdata($data['logo']=$data['websiteInfo']['logo']);
		if($this->input->is_ajax_request()){

			$this->load->view('templates/admin/content', $data);
		}else{

			$this->load->view('templates/'.$this->template.'/head', $data);
			$this->load->view('templates/'.$this->template.'/header', $data);
			$this->load->view('templates/'.$this->template.'/content', $data);
			$this->load->view('templates/'.$this->template.'/footer', $data);
		}
	}
	
	function admin_template_tab($data) {
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		$this->load->view('templates/'.$this->template.'/admin_template_head', $meta);
		$this->load->view('templates/'.$this->template.'/admin_template_header', $data);
		$this->load->view('templates/'.$this->template.'/admin_template_tab_content', $data);
		$this->load->view('templates/'.$this->template.'/admin_template_footer', $data);
	}

	function login_template($data) {
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		
		$this->load->view('templates/login/head', $meta);

		//$this->load->view('templates/login/header', $data);
		$this->load->view('templates/login/content', $data);
		$this->load->view('templates/login/footer', $data);
	}
	
	function error_template($data) {
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		
		$this->load->view('templates/error/error_template_head', $meta);

		$this->load->view('templates/error/error_template_header', $data);
		$this->load->view('templates/error/error_template_content', $data);
		$this->load->view('templates/error/error_template_footer', $data);
	}

	function obaju_home_template($data = []) {
		
			Modules::run('templates/admin_template', $data);
		}

	function obaju_inner_template($data = []) {
			Modules::run('templates/admin_template', $data);
		
	}

	function templateAddress($companyId = 1, $type = 'companies'){
		//echo $companyId." ".$type;exit;
		$this->load->model('address/address_model');
		//$this->pktdblib->set_table('address');
		$address = $this->address_model->userBasedDefaultAddress($companyId, $type);

		//print_r($address);exit;
		return $address;
	}

	function email_frontTemplate($data = []) {
		$companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);

		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		$this->load->view('templates/email/template_1', $data);
		$this->load->view('templates/email/footer', $data);
	}

	function oxiinc_template($data = []) {
		$companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);
		//print_r($data['websiteInfo']);
		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		/*$data['js'][] = '<script type="text/javascript">
			$(document).on("click", ".main-navigation li a span", function(event){
				event.preventDefault();
				//alert("hii");
				return false;
			})
		</script>';*/
		//print_r($data['websiteInfo']);exit;
		$this->load->view('templates/oxiinc/head', $data);
		$this->load->view('templates/oxiinc/header', $data);
		$this->load->view('templates/oxiinc/content', $data);
		$this->load->view('templates/oxiinc/footer', $data);
	}

	function news_template($data) {
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		$this->load->view('templates/news/head', $meta);
		$this->load->view('templates/news/header', $data);
		$this->load->view('templates/news/content_inner', $data);
		$this->load->view('templates/news/footer', $data);
	}
	
	function subscribe(){
    	if($_SERVER['REQUEST_METHOD'] == 'POST') {
    		echo json_encode($_POST);
    		exit;
    		
    	}else{
    		echo json_encode(['status'=>false, 'message'=>'Invalid Input']);
    		exit;
    	}
    
    }
    
    function report_template($data) {
        $companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);
		$data['websiteAddress'] = $this->templateAddress($this->id, 'login');
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		$meta['title'] = $data['title'];
		$meta['meta_keyword'] = (isset($data['meta_keyword']))?$data['meta_keyword']:'report';
		//echo $this->input->is_ajax_request();
		if($this->input->is_ajax_request()){ //echo "hii";
			$this->load->view('templates/report/content', $data);
		}else{
			//echo "hello";
			$this->load->view('templates/report/head', $data);
			$this->load->view('templates/report/header', $data);
			$this->load->view('templates/report/content', $data);
			$this->load->view('templates/report/footer', $data);
		}
	}
	//10/20/2020 deliveryboy template
	function fieldmember_template($data) {
		$companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);
		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		$this->session->set_userdata($data['meta_keyword']=$data['websiteInfo']['meta_keyword']);
		$this->session->set_userdata($data['logo']=$data['websiteInfo']['logo']);
		//echo var_dump($data['websiteInfo']['meta_keyword']);exit;
		//echo var_dump($data);exit;
		if($this->input->is_ajax_request()){

			$this->load->view('templates/fieldmember/content', $data);
		}else{

			$this->load->view('templates/'.$this->template.'/head', $data);
			$this->load->view('templates/'.$this->template.'/header', $data);
			$this->load->view('templates/'.$this->template.'/content', $data);
			$this->load->view('templates/'.$this->template.'/footer', $data);
		}
	}
}
