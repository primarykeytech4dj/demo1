<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address extends MY_Controller {
	function __construct() {
		parent::__construct();
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->model('address/address_model');
		$this->load->model('cities/cities_model');
		$this->load->model('countries/countries_model');
		$this->load->model('states/states_model');
		$this->load->model('areas/areas_model');
		$setup = $this->setup();
	}

	function setup(){
        $address = $this->address_model->tbl_address();
        return TRUE;
    }

	function admin_index() {
		$data['meta_title'] = 'Address Module';
		$data['meta_description'] = 'Address Module';
		$data['meta_keyword'] = 'Address Module';
		//$data['module'] = 'address';
		//$data['content'] = 'address/address_listing';
		$data['modules'][] = 'address';
		$data['methods'][] = 'admin_address_listing';
	
		echo Modules::run("templates/admin_template", $data); 	
	}

	function admin_address_listing($data = []) {
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		//echo "string"; exit;
		//print_r($condition);
		$condition['address.is_active'] = true;
		$data['address'] = $this->address_model->get_address_list($condition);
		/*print_r($data);
		exit;*/
		$this->load->view("address/admin_index", $data);
	}

	function admin_add($data = []) {
		//echo '<pre>';print_r($data);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        $redirectUrl = 'address/admin_add';
	        //echo '<pre>';print_r($_POST);exit;
			$this->form_validation->set_rules('data[address][address_1]', 'address_1', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][address_2]', 'address_2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'state', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'city', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'pincode', 'required');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			if($this->form_validation->run()!==FALSE)
			{
				$_POST['data']['address']['is_default'] = (null!=$this->input->post('data[address][is_default]'))?true:false;
				//$_POST['data']['address']['is_active'] = (null!=$this->input->post('data[address][is_active]'))?true:false;
				$post_data = $_POST;
				/*echo '<pre>';
				print_r($post_data);
				exit;*/
				$countryId = '';
				$stateId = '';
				$cityId = '';
				$areaId = '';
				if(!empty($post_data['data']['countries']['name'])){
					$country['name'] = $post_data['data']['countries']['name'];
					$country['is_active'] = TRUE;
					$country['created'] = date('Y-m-d H:i:s');
					$country['modified'] = date('Y-m-d H:i:s');
					$countryId = json_decode(Modules::run('countries/_register_admin_add', $country), true);
					$post_data['data']['address']['country_id'] = $countryId['id'];
					unset($post_data['data']['countries']['name']);
				}//exit;
					//echo '<pre>';print_r($post_data);exit;
				if(!empty($post_data['data']['states']['state_name'])){
					$state['state_name'] = $post_data['data']['states']['state_name'];
					$state['is_active'] = TRUE;
					$state['country_id'] = $post_data['data']['address']['country_id'];
					$state['created'] = date('Y-m-d H:i:s');
					$state['modified'] = date('Y-m-d H:i:s');
					$stateId = json_decode(Modules::run('states/_register_admin_add', $state),true);
					print_r($stateId['id']);exit;
					$post_data['data']['address']['state_id'] = $stateId['id'];
					unset($post_data['data']['states']['state_name']);

				}
				if(!empty($post_data['data']['cities']['city_name'])){
					$city['city_name'] = $post_data['data']['cities']['city_name'];
					$city['short_name'] = $post_data['data']['cities']['city_name'];
					$city['is_active'] = TRUE;
					$city['country_id'] = $post_data['data']['address']['country_id'];
					$city['state_id'] = $post_data['data']['address']['state_id'];
					$city['created'] = date('Y-m-d H:i:s');
					$city['modified'] = date('Y-m-d H:i:s');
					$cityId = json_decode(Modules::run('cities/_register_admin_add', $city), true);
					//print_r($cityId);exit;
					$post_data['data']['address']['city_id'] = $cityId['id'];
					unset($post_data['data']['cities']['city_name']);

				}
				if(!empty($post_data['data']['areas']['area_name'])){
					$area['area_name'] = $post_data['data']['areas']['area_name'];
					$area['is_active'] = TRUE;
					$area['city_id'] = $post_data['data']['address']['city_id'];
					$area['created'] = date('Y-m-d H:i:s');
					$area['modified'] = date('Y-m-d H:i:s');
					$areaId = json_decode(Modules::run('areas/_register_admin_add', $area),true);
					$post_data['data']['address']['area_id'] = $areaId['id'];
					unset($post_data['data']['areas']['area_name']);

				}
				/*echo '<pre>';print_r($post_data);
				exit;*/

				$user_address = json_decode($this->register_user_address($post_data['data']['address']), true);
				if($user_address['status'] === "success")
				{ 
					/*print_r($user_address);
					exit;*/
					if(isset($post_data['data']['add_to_site']) && $post_data['data']['add_to_site']==true){
						$post_data['data']['address']['id'] = $user_address['id']['id'];
						Modules::run("customer_sites/add_default_site", $post_data['data']['address']);
					}
					//exit;
	                $redirectUrl = 'address/admin_edit/'.$user_address['id'];
	                if($this->input->post('module')!='address'){
	                	$redirectUrl = $this->input->post('url')/*.'&address_id='.$user_address['id']*/;
	                }
	                //echo $redirectUrl; exit;
					$msg = array('message' => 'Address Added Successfully','class' => 'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
				}
				else
				{
					$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
					//$data['form_error'] = $reg_user['msg'];
				}
			}else{
					//echo validation_errors();exit;
				$msg = array('message' => "Following Validation Error Occurred.".validation_errors(), 'class' => 'alert alert-danger');
                $this->session->set_flashdata('message',$msg);
			}
				redirect($redirectUrl);
		}
		
		
		
		//$data['areas'] = $this->areas_model->get_list();
		//$data['states'] = $this->states_model->get_list();
		//$data['countries'] = $this->countries_model->get_list();
		$data['meta_title'] = "Address";
		$data['meta_description'] = "Address Details";
		$data['modules'][] = "address";
		$data['methods'][] = "admin_add_form";
		/*echo '<pre>';
		print_r($data);exit;*/
		echo Modules::run("templates/admin_template", $data);
	}

	function register_user_address($data) { 
	    //echo "reached here";exit;
		//print_r($data);exit;
		//echo "hello";exit;
		/*$data = $_POST;*/
		//print_r($_POST);exit();
		$insert_data = $data;
		//print_r($insert_data);exit;
		$this->pktdblib->set_table("address");
		$id = $this->pktdblib->_insert($insert_data);
		
		$data['id'] = $id['id'];
		return json_encode(["msg"=>"Address Added Successfully", "status"=>"success", 'id'=>$id, 'address'=>$data]);
	}

	function user_login($addressID) {
		//$this->address_model->set_table("address");
		$data['address']['id'] = $addressID;
		//print_r($data['user']);
		$data['id']['type'] = 'address';
		//exit;
		//print_r($data);exit;
		$data['module'] = 'login';
		$data['method'] = 'user_address';
		echo Modules::run("login/user_address", $data);
	}

	function address_details($id) {
		$this->pktdblib->set_table('address');
		$addressDetails = $this->pktdblib->get_where($id);
		/*echo '<pre>';
		print_r($addressDetails);*/
		return $addressDetails;
	}

	function admin_edit($id = null){
		//print_r($data);
		//echo "hello";
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
			//echo "hello";
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[address][address_1]', 'Address_1', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][address_2]', 'Address_2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'Country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'State', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'City', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'Area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'Pincode', 'required|max_length[6]');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			if($this->form_validation->run('address') !== FALSE) {
				//print_r($this->input->post('data[address][is_default]'));
				$_POST['data']['address']['is_default'] = (null!=$this->input->post('data[address][is_default]'))?true:false;
				//print_r($_POST);exit;
				$post_data = $_POST['data']['address'];
			}else{
				$error[] = validation_errors();
				//print_r(validation_errors());
			}

			if(empty($error)) { //echo "hii"; exit;
				$this->pktdblib->set_table('address');
				if($this->pktdblib->_update($id,$post_data)) {
                    $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                         $this->session->set_flashdata('message',$msg);
                }
                else {
                    $msg = array('message' => 'Some problem occured while updating','class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('message',$msg);
                }
                redirect($this->input->post('url'));

            }
            else{
                $msg = array('message' => $error,'class' => 'alert alert-danger fade in');
                $this->session->set_flashdata('message', $msg);
                redirect($this->input->post('url'));
            }
		}
		else {
			//$this->address_model->set_table("address");
			$data['address'] = $this->address_details($id);
			//print_r($data['address']);exit;
			$data['values_posted']['address'] = $data['address'];
		}
		$data['id'] = $id;
		
		$data['meta_title'] = 'edit address';
		$data['meta_description'] = 'edit address';
		$data['modules'][] = 'address';
		$data['methods'][] = 'admin_edit_form';
		echo Modules::run("templates/admin_template", $data);
	}


	function admin_add_form($data = []) {
		/*echo '<pre>';
		print_r($data);
		exit;*/
		$cities = $this->cities_model->get_dropdown_list();
		//$data['option'] = [];
		$data['option']['cities'][0] = "Select City";
		//print_r($cities);
		/*foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}*/

		$states = $this->states_model->get_dropdown_list();
		$data['option']['states'][0] = "Select State";
		/*foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}*/
		/*print_r($data['option']['states']);
		exit;*/

		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		$data['option']['areas'][0] = "Select Area";
		/*foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}*/
		/*echo '<pre>';
		print_r($data);exit;*/
		if(!isset($data['type'])){
			$data['type'] = '';//variable is passed through other modules
			$data['user_id'] = '';
		}
		/*
		
		$data['users']  = [];
		
		if(isset($data['module']) && $data['module']=='employees'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['emp_code']];
			$data['option']['typeLists'] = ['employees'=>'employees'];

		}elseif(isset($data['module']) && ($data['module']=='customers' || $data['module']=='customers_v2')){
				$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = ['customers'=>'customers'];

			
		}elseif(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['companies'=>'companies'];
		}elseif(isset($data['module']) && $data['module']=='enquiries'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['enquiries'=>'Lead'];
		}*/
		/*echo '<pre>';
		print_r($data);
		exit;*/
		if(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = [$data['type']=>$data['module']];
		}elseif(isset($data['module']) && $data['module']!='companies'){
			$data['users'] = [$data['user_id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = [$data['type']=>ucfirst($data['module'])];
		}
		else{
			$data['users'] = [];
			$data['option']['typeLists'] = [''=>'Select', 'enquiries'=>'Lead/Enquiry'];
		}
		$data['isCustomerSiteModuleExists'] = is_dir(APPPATH.'modules/customer_sites');
		$this->load->view('address/admin_add', $data);
	}

	function admin_edit_form($data = []) {
		//echo $this->uri->segment(3);exit;
		//print_r($data);exit;
		if(empty($data)){
			$data['address'] = $this->address_details($this->uri->segment(3));
		}
		//print_r($data);
		$cities = $this->cities_model->get_dropdown_list();
		//$data['option'] = [];
		$data['option']['cities'][0] = "Select City";
		//print_r($cities);
		foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		$data['option']['states'][0] = "Select State";
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		/*print_r($data['option']['states']);
		exit;*/

		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		$data['option']['areas'][0] = "Select Area";
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}

		if(!isset($data['type'])){
			$data['type'] = '';//variable is passed through other modules
			$data['user_id'] = '';
		}
		
		$data['users']  = [];
		//echo $data['module'];
		/*if(isset($data['module']) && $data['module']=='employees'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['emp_code']];
			$data['option']['typeLists'] = ['employees'=>'employees'];

		}elseif(isset($data['module']) && ($data['module']=='customers' || $data['module']=='customers_v2')){
				$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = ['customers'=>'customers'];

			
		}elseif(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['companies'=>'companies'];
		}elseif(isset($data['module']) && $data['module']=='enquiries'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname'].' | '.$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['enquiries'=>'Leads'];
		}else{
			$data['option']['typeLists'] = [''=>'Address belongs to?', 'employees'=>'Employee', 'customers'=>'Customer', 'suppliers'=>'Supplier', 'companies'=>'companies'];

		}*/
		/*if(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['login'=>'companies'];
		}elseif(isset($data['module']) && $data['module']=='enquiries'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['enquiries'=>'Lead'];

		}else{
			$data['users'] = [$data['user_id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = ['login'=>'companies'];
		}*/
		if(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = [$data['type']=>$data['module']];
		}elseif(isset($data['module']) && $data['module']!='companies'){
			$data['users'] = [$data['user_id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = [$data['type']=>ucfirst($data['module'])];
		}
		else{
			$data['users'] = [];
			$data['option']['typeLists'] = [''=>'Select', 'enquiries'=>'Lead/Enquiry'];
		}
		

		$data['values_posted']['address'] = $data['address'];
		
		$this->load->view('address/admin_edit', $data);
	}

	function view_user_wise_address($data = NULL) {
	    $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            $data = $params;
        }
		if(NULL == $data){
			show_404();
		}
		
		//print_r($data);exit;
		$this->address_model->set_table('address');
		$data['address'] = $this->address_model->userBasedAddress($data['user_id'], $data['type']);
		//echo $this->db->last_query();exit;
		$this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'address'=>$data]);
		$this->load->view('address/admin_address_listing', $data);;
		/*print_r($address);
		exit;*/
	}

	function type_wise_user(){
		//print_r($_POST);exit;
		//$_POST['params'] = "employees";
		
		if(!$this->input->post('params'))
			return;

		$addressType = $this->input->post('params');
		
		//echo json_encode($condition);exit;
		//$this->address_model->set_table('address');
		$typeWiseUsers = $this->address_model->get_custom_address_type_users($addressType);
		//print_r($typeWiseUsers);exit;
		$userList = [0=>['id'=>0, 'text'=>'Select User']];
		foreach ($typeWiseUsers as $key => $typeWiseUser) {
			$userList[$key+1]['id'] = $typeWiseUser['id'];
			$userList[$key+1]['text'] = $typeWiseUser['fullname']." | ".$typeWiseUser['emp_code'];
		}
		
		echo json_encode($userList);
		exit;
	}

	function add_form($data = []) {

		$cities = $this->cities_model->get_dropdown_list();
		$data['option']['cities'][0] = "Select City";
		foreach ($cities as $cityKey => $city) {
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		$data['option']['states'][0] = "Select State";
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		
		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		$data['option']['areas'][0] = "Select Area";
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}
		
		$customerSites = Modules::run('customer_sites/admin_index');
		//print_r($customerSites);exit;
		$this->load->view('address/add', $data);
	}

	function add($data = []) {
	    $params = json_decode(file_get_contents('php://input'), TRUE);
	    //echo '<pre>';print_r($params);exit;
        if(!empty($params)){
            $_POST['data'] = $params;
        }
		//print_r($_POST);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $redirectUrl = $this->input->post('url');
	        $this->form_validation->set_rules('data[address][address_1]', 'address_1', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][address_2]', 'address_2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'state', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'city', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'pincode', 'required');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			if($this->form_validation->run()!==FALSE)
			{ 
				$_POST['data']['address']['is_default'] = true;//(null!=$this->input->post('data[address][is_default]'))?true:false;
				$_POST['data']['address']['user_id'] = (!isset($_POST['data']['address']['user_id']))?$this->session->userdata('employee_id'):$_POST['data']['address']['user_id'];
				$_POST['data']['address']['created'] =  $_POST['data']['address']['modified'] = date('Y-m-d H:i:s');
				$_POST['data']['address']['type'] = !isset($_POST['data']['address']['type'])?'customers':$_POST['data']['address']['type'];
				$post_data = $this->input->post();
				//print_r($post_data);exit;
				$user_address = json_decode($this->register_user_address($post_data['data']['address']), true);
				//print_r($user_address);exit;
				if($user_address['status'] === "success")
				{ 
					$this->pktlib->parseOutput($this->config->item('response_format'), $user_address);
	                $redirectUrl = custom_constants::edit_address_url.'/'.$user_address['id'];
	                if($this->input->post('module')!='address'){
	                	$redirectUrl = $this->input->post('url');
	                }
	                //echo $redirectUrl; exit;
					$msg = array('message' => 'Address Added Successfully','class' => 'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
				}
				else
				{
				    $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'fail', 'error'=>"Some Error Occured. Please try after sometime"]);
					$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
					//$data['form_error'] = $reg_user['msg'];
				}
			}else{ 
			    $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'fail','error'=>validation_errors()]);
				$msg = array('message' => "Following Validation Error Occurred.".validation_errors(), 'class' => 'alert alert-danger');
                $this->session->set_flashdata('message',$msg);
			}
				redirect($redirectUrl);
		}
		
		
		
		//$data['areas'] = $this->areas_model->get_list();
		//$data['states'] = $this->states_model->get_list();
		//$data['countries'] = $this->countries_model->get_list();
		$data['meta_title'] = "Address";
		$data['meta_description'] = "Address Details";
		$data['modules'][] = "address";
		$data['methods'][] = "admin_add_form";
		/*echo '<pre>';
		print_r($data);exit;*/
		echo Modules::run("templates/admin_template", $data);
	}

	function edit_form($data = []) {
		//echo $this->uri->segment(3);exit;
		if(empty($data)){
			$data['address'] = $this->address_details($this->uri->segment(3));
		}
		//print_r($data);
		$cities = $this->cities_model->get_dropdown_list();
		//$data['option'] = [];
		$data['option']['cities'][0] = "Select City";
		//print_r($cities);
		foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		$data['option']['states'][0] = "Select State";
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		/*print_r($data['option']['states']);
		exit;*/

		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		$data['option']['areas'][0] = "Select Area";
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}

		$data['values_posted']['address'] = $data['address'];
		
		$this->load->view('address/edit', $data);
	}

	function edit($id = null){
		//print_r($data);
		//echo "hello";
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
			//echo "hello";
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[address][address_1]', 'Address 1', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][address_2]', 'Address 2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'Country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'State', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'City', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'Area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'Pincode', 'required|max_length[6]');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			if($this->form_validation->run('address') !== FALSE) {
				//print_r($this->input->post('data[address][is_default]'));
				$_POST['data']['address']['is_default'] = (null!=$this->input->post('data[address][is_default]'))?true:false;
				//print_r($_POST);exit;
				$post_data = $_POST['data']['address'];
			}else{
				$error[] = validation_errors();
				//print_r(validation_errors());
			}

			//print_r($_POST);exit;
			if(empty($error)) { //echo "hii"; exit;
				if($this->pktdblib->_update($id,$post_data)) {
						
                    $msg = array('message' => 'Address Updated Successfully  ','class' => 'alert alert-success fade in');
                         $this->session->set_flashdata('message',$msg);
                }
                else {
				//echo "string";exit;
                     $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                     $this->session->set_flashdata('message',$msg);
                }
                redirect($this->input->post('url'));

            }
            else{
                $msg = array('message' => $error,'class' => 'alert alert-danger fade in');
                $this->session->set_flashdata('message', $msg);
                //redirect('address/edit_address/'.$id);
                redirect($this->input->post('url'));
            }
		}
		else {
			//$this->address_model->set_table("address");
			$data['address'] = $this->address_details($id);
			//print_r($data['address']);exit;
			$data['values_posted']['address'] = $data['address'];
		}
		$data['id'] = $id;
		
		$data['meta_title'] = 'Edit Address';
		$data['meta_description'] = 'Edit Address';
		$data['modules'][] = 'address';
		$data['methods'][] = 'edit_form';
		echo Modules::run("templates/admin_template", $data);
	}
	
	function delete_address(){
	    if($_SERVER['REQUEST_METHOD']=='POST'){
	        $params = json_decode(file_get_contents('php://input'), TRUE);
    	    if(NULL===$params['user_id'] || NULL===$params['address_id'] || NULL===$params['login_id']){
    	        $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'fail', 'error'=>"Invalid Access"]);
    	    }
    	    
    	    $up = $this->pktdblib->custom_query('Update address set is_active=0 where id="'.$params['address_id'].'" and user_id="'.$params['login_id'].'" and type="login"');
    	    //print_r($up);exit;
    	    if($up){
    	        $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'msg'=>"Address Deleted Successfully"]);
    	    }else{
    	        $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'fail', 'msg'=>"Some Error Occured. Please try after sometime"]);
    	    }
	    }else{
	        $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'fail', 'error'=>"Invalid Access"]);
	    }
	}
}