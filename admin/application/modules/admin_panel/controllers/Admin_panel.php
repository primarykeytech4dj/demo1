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
 
 
class Admin_Panel extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Check login and make sure email has been verified
		check_user_login(FALSE);
		$setup = $this->setup();
	}

	function setup(){
		$companies = Modules::run('companies/setup');
		return TRUE;
	}

    function index() {
		$data['user_id'] = $this->session->userdata['user_id'];
		$data['username'] = $this->session->userdata['username'];
		$data['account_type'] = $this->session->userdata['account_type'];
		$data['logged_in_since'] = $this->session->userdata['logged_in_since'];
		
		$data['meta_title'] = "Dashboard";
		$data['meta_description'] = "Dashboard";
		$roles = 'others';	
		if(in_array(7, $this->session->userdata('roles'))){
			$roles = 'Sales';
		}elseif (in_array(6, $this->session->userdata('roles'))) {
			$roles = 'Delivery Boy';
		}
		$report = ['quicklinks'=>['icon'=>'fa-link', 'data'=>[]], 'delivery'=>['icon'=>'fa-truck', 'data'=>[]], 'orders'=>['icon'=>'fa-shopping-cart', 'data'=>[]]];
		$quicklinks = ['1','2'];
		$report['quicklinks']['data'][] = ['title'=>'Record Visit', 'icon'=>'fa-link','url'=>anchor('customers/visit_customer_list', 'View <i class="fa fa-arrow-circle-right"></i>', ['class'=>'small-box-footer get_token']), 'count'=>'', 'background'=>'bg-green'];
		$date = (NULL===$this->session->userdata('dashboard_date'))?date('Y-m-d'):$this->session->userdata('dashboard_date');
		if($_SERVER['REQUEST_METHOD']=='POST'){
		    //echo '<pre>';print_r($this->input->post('dashboard_date'));exit;
		    $date = $this->pktlib->dmYtoYmd($this->input->post('dashboard_date'));
		    $this->session->set_userdata(['dashboard_date'=>$date]);
		}
		
		//echo $date;

		if($roles!='Sales'){
			$sql4 = 'Select count(distinct id) as order_count from orders where 1=1';
			if($roles=='Delivery Boy'){
				$sql4.=' AND id in (select order_id from deliveryboy_order where employee_id="'.$this->session->userdata('employees')['id'].'" and created like "'.$date.'%") ';
			}else{
			    $sql4.=' AND id in (select order_id from order_logs where order_status_id=9 and created like "'.$date.'%") ';
			}
            //echo $sql4;//exit;
			$order = $this->pktdblib->custom_query($sql4);
			$orderListingUrl = custom_constants::admin_order_listing_url;
			if($roles=='Delivery Boy'){
				$orderListingUrl = custom_constants::fieldmember_url;
			}
			$report['delivery']['data'][] = ['title'=>'Assigned Deliveries', 'url'=>anchor($orderListingUrl, 'View <i class="fa fa-arrow-circle-right"></i>', ['class'=>'small-box-footer']), 'icon'=>'fa-truck', 'count'=>$order[0]['order_count'], 'background'=>'bg-green'];
			
			// print_r($order);exit;
			$sql5 = 'Select count(id) as order_count from orders where order_status_id=2';
			if($roles=='Delivery Boy'){
				$sql5.=' AND id in (select order_id from deliveryboy_order where employee_id="'.$this->session->userdata('employees')['id'].'" AND created like "'.date('Y-m-d').'")';
			}else{
			    $sql5.=' AND id in (select order_id from order_logs where order_status_id=2 and created like "'.$date.'%") ';
			}

			$order = $this->pktdblib->custom_query($sql5);
			$orderListingUrl = custom_constants::admin_order_listing_url;
			if($roles=='Delivery Boy'){
				$orderListingUrl = custom_constants::fieldmember_url;
			}
			$report['delivery']['data'][] = ['title'=>'Cancelled Deliveries', 'url'=>anchor($orderListingUrl, 'View <i class="fa fa-arrow-circle-right"></i>', ['class'=>'small-box-footer']), 'icon'=>'fa-truck', 'count'=>$order[0]['order_count'], 'background'=>'bg-red'];

			$sql6 = 'Select count(id) as order_count from orders where order_status_id=9 ';
			if($roles=='Delivery Boy'){
				$sql6.=' AND id in (select order_id from deliveryboy_order where employee_id="'.$this->session->userdata('employees')['id'].'")';
			}else{
			    
			}

			$order = $this->pktdblib->custom_query($sql6);
			$orderListingUrl = custom_constants::admin_order_listing_url;
			if($roles=='Delivery Boy'){
				$orderListingUrl = custom_constants::fieldmember_url;
			}
			$report['delivery']['data'][] = ['title'=>'Pending Deliveries', 'url'=>anchor($orderListingUrl, 'View <i class="fa fa-arrow-circle-right"></i>', ['class'=>'small-box-footer']), 'icon'=>'fa-truck', 'count'=>$order[0]['order_count'], 'background'=>'bg-yellow'];
		}


		$sql = 'Select count(id) as download from customers where created like "'.$date.'%"';
		if($roles=='Sales'){
			$sql.=' AND referral_code like "'.$this->session->userdata('employees')['emp_code'].'"';
		}elseif ($roles=='Delivery Boy') {
			$sql.=' AND referral_code like "'.$this->session->userdata('employees')['emp_code'].'"';
		}

		$download = $this->pktdblib->custom_query($sql);
		$report['orders']['data'][] = ['title'=>'New Downloads', 'url'=>anchor('customers/adminindex', 'View <i class="fa fa-arrow-circle-right"></i>', ['class'=>'small-box-footer']), 'icon'=>'fa-download', 'count'=>$download[0]['download'], 'background'=>'bg-aqua'];

		$sql2 = 'Select count(id) as order_count from orders where date like "'.$date.'%"';
		if($roles!='others'){
			$sql2.=' AND sale_by like "'.$this->session->userdata('employees')['emp_code'].'"';
		}
		//echo $sql2;
		$order = $this->pktdblib->custom_query($sql2);
		$orderListingUrl = custom_constants::admin_order_listing_url;
		/*if($roles=='Delivery Boy'){
			$orderListingUrl = custom_constants::fieldmember_url;
		}*/
		$report['orders']['data'][] = ['title'=>'New Orders', 'url'=>anchor($orderListingUrl, 'View <i class="fa fa-arrow-circle-right"></i>', ['class'=>'small-box-footer']), 'icon'=>'fa-shopping-cart', 'count'=>$order[0]['order_count'], 'background'=>'bg-yellow'];


		$sql3 = 'Select count(id) as order_count from orders where date like "'.$date.'%" and order_status_id=2';
		if($roles!='others'){
			$sql3.=' AND sale_by like "'.$this->session->userdata('employees')['emp_code'].'"';
		}
		//echo $sql2;
		$order = $this->pktdblib->custom_query($sql3);
		$orderListingUrl = custom_constants::admin_order_listing_url;
		/*if($roles=='Delivery Boy'){
			$orderListingUrl = custom_constants::fieldmember_url;
		}*/
		$report['orders']['data'][] = ['title'=>'Cancelled Orders', 'url'=>anchor($orderListingUrl, 'View <i class="fa fa-arrow-circle-right"></i>', ['class'=>'small-box-footer']), 'icon'=>'fa-shopping-cart', 'count'=>$order[0]['order_count'], 'background'=>'bg-red'];
		
		
		$data['reports'] = $report;
		$data['modules'][] = "admin_panel";
		$data['methods'][] = "view_admin_panel_default";
		$data['dashboard_date'] = $date;
		echo Modules::run("templates/admin_template", $data);
    }
	
	function view_admin_panel_default() {
	    $data['status'] = 'in process';
		$data['pendingOrders'] = Modules::run('orders/admin_order_listing', $data);	
		$this->load->view("admin_panel/admin_panel_default", $data);
	}


}
