<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Routes extends MY_Controller {

	// Configuration properties used in blacklisting
	
	function __construct() {
		parent:: __construct();
	
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(TRUE);
			}
		}
		
		$this->load->model('routes/route_model');
		$this->load->model('employees/employees_model');
	}

	function route_listing() {
	    if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        
			// print_r($this->input->post());exit;
	        $this->form_validation->set_rules('zone_no', 'Zone No', 'required');
			$this->form_validation->set_rules('route_no', 'Route No', 'required');
			$this->form_validation->set_rules('route_name', 'Route Name', 'required');
			$this->form_validation->set_rules('login_id', 'Sales Person', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
			    $postData = $this->input->post();
			    $this->pktdblib->set_table('routes');
			    if(isset($postData['id']) && NULL!==$postData['id'] && $postData['id']!=''){
			        $postData['modified_by'] = $this->session->userdata('user_id');
			        $postData['modified'] = date('Y-m-d H:i:s');
			        try{
						// print_r($upd);exit;
						// print_r($postData);exit;
			            $upd = $this->pktdblib->_update($postData['id'], $postData);
			            if($upd){
    			            echo json_encode(['status'=>'success', 'message'=>'Routes Updates']);
    			            exit;
    			        }
			        }catch(Exception $e){
			            // echo json_encode(['status'=>'error', 'message'=>'Failed to update route '.$e->message()]);
						echo json_encode(['status'=>'error', 'message'=>'Failed to update route ']);
			            exit;
			        }
			        //$upd = $this->pktdblib->_update($postData['id'], $postData);
			        
			    }else{
					// print_r($postData);exit;
			        $postData['created_by'] = $this->session->userdata('user_id');
			        $postData['created'] = date('Y-m-d H:i:s');
					try{
				
						$id = $this->pktdblib->_insert($postData);
						// print_r($id);
						if($id){
							echo json_encode(['status'=>'success', 'message'=>'Routes created']);
							exit;
						}else{
							echo json_encode(['status'=>'error', 'message'=>'Failed to create route']);
							exit;
						}
			        }catch(Exception $e){
			            // echo json_encode(['status'=>'error', 'message'=>'Failed to update route '.$e->message()]);
						echo json_encode(['status'=>'error', 'message'=>'Failed to update route ']);
			            exit;
			        }
			     
			    }
			    
			}else{
				// print_r($this->input->post());exit;
			    echo json_encode(['status'=>'error', 'message'=>'Following Validation Error: '.validation_errors()]);
			    exit;
			}
			
	    }
		$data['meta_title'] = 'Routes';
		$data['meta_description'] = 'Routes';
		$data['modules'][] = 'routes';
		$data['methods'][] = 'admin_add_form';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_add_form($data = []) {
		$employees = Modules::run('employees/rolewise_employees', 7);
		$data['option']['employees'][] = 'Select';
		foreach($employees as $eKey=>$employee){
		    $data['option']['employees'][$employee['login_id']] = $employee['name']." | ".$employee['emp_code'];
		}
		$timestamp = strtotime('next Sunday');
		$days = array();
		for ($i = 0; $i < 7; $i++) {
		    $days[] = strftime('%A', $timestamp);
		    $timestamp = strtotime('+1 day', $timestamp);
		}
		foreach($days as $eKey=>$time){
		    $data['option']['days'][$time] = $time;
		}
		//echo '<pre>';print_r($employees);
		$sql = 'Select * from routes where 1=1';
		if(isset($_GET['zone_no']) && $_GET['zone_no'] !== "")
		{
			$sql .= ' AND zone_no = '.$_GET['zone_no'];
		}
		if(isset($_GET['login_id']) &&  $_GET['login_id'] > 0)
		{
			$sql .= ' AND login_id = '.$_GET['login_id'];
		}
		//echo $sql;
		$data['routes'] = $this->pktdblib->custom_query($sql);
		$this->load->view("routes/admin_add", $data);
	}


}