<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends MY_Controller {
	function __construct() {
		parent::__construct();
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
	}

	function enquiryEmail($data = []) {
		//print_r($data);
		$this->pktdblib->set_table('enquiry_details');
		$data['enquiryDetails'] = $this->pktdblib->custom_query('select products.product, enquiry_details.unit_price from enquiry_details inner join products on products.id=enquiry_details.product_id where enquiry_details.enquiry_id='.$data['enquiries']['id']);
		//$data['enquiryDetails'] = $enqDetails->result_array();
		//print_r($data['enquiryDetails']);exit;
		$data['title'] = 'Online Enquiry';
		$data['content'] = 'enquiries/email/contactus_enquiry';
		//$this->load->view('enquiries/email/contactus_enquiry', $data);
		//exit;
		echo Modules::run("templates/email_frontTemplate", $data);
	}

	function orderEmail($data = []) {
		//print_r($data['order']['id']);
		$this->pktdblib->set_table('orders');
		$data['order'] = $this->pktdblib->get_where($data['order']['id']);
		
		$this->pktdblib->set_table('customers');
		//print_r($data['order']['customer_id']);//exit;
		$data['customer'] = $this->pktdblib->get_where($data['order']['customer_id']);
		//print_r($data['customer']);exit;
		$data['orderDetails'] = $this->pktdblib->custom_query('select products.product, order_details.* from order_details inner join products on products.id=order_details.product_id where order_details.order_id='.$data['order']['id']);
		//$data['enquiryDetails'] = $enqDetails->result_array();
		//print_r($data['enquiryDetails']);exit;
		$data['title'] = 'Online Order';
		$data['content'] = 'orders/email/order';
		//print_r($data['content']);exit;
		echo Modules::run("templates/email_frontTemplate", $data);
	}

}
