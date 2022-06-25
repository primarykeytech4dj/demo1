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
 
 
class Customer_sites extends MY_Controller {

	function __construct() {
		parent::__construct();

		// Check login and make sure email has been verified
		check_user_login(FALSE);
		$this->load->library('pktdblib');
		$this->load->model('customer_sites/customer_site_model');
		$setup = $this->setup();
	}

	function setup(){
		$customerSites = $this->customer_site_model->tbl_customer_sites();
		return TRUE;
	}

	function admin_index() {
		$data['meta_title'] = 'Customer Sites';
		$data['meta_description'] = 'Customer Sites';
		//$data['module'] = 'address';
		//$data['content'] = 'address/address_listing';
		$data['modules'][] = 'customer_sites';
		$data['methods'][] = 'admin_index_listing';
		
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_index_listing($data = []) {
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		//echo "string"; exit;
		//$this->customer_site_model->set_table('customer_sites');
		$data['customerSites'] = $this->customer_site_model->get_sites_list($condition);

		$this->load->view("customer_sites/admin_index", $data);
	}

	function admin_add() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$redirectUrl = custom_constants::new_site_url;
            $error = [];
            //Validate Site Address and make entry
            $this->form_validation->set_rules('data[address][address_1]', 'address_1', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][address_2]', 'address_2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'state', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'city', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'pincode', 'required|max_length[6]|min_length[6]');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			$_POST['data']['address']['type'] = 'customers';
			//echo $this->input->post('data[customer_sites][customer_id]');
			$_POST['data']['address']['user_id'] = $this->input->post('data[customer_sites][customer_id]');
			//print_r($this->input->post());exit;
			$this->form_validation->set_rules('data[address][user_id]', 'Customer', 'required');
			if($this->form_validation->run()!== FALSE) {
				if(!isset($_POST['data']['address']['id'])){
					$address = json_decode(Modules::run('address/register_user_address', $this->input->post('data[address]')),true);
					if($address['status']=='success'){
						$_POST['data']['customer_sites']['address_id'] = $address['id']['id'];
					}
				}else{
					$this->load->model('address/address_model');
					$this->address_model->update_address($_POST['data']['address']['id'], $this->input->post('data[address]'));
						$_POST['data']['customer_sites']['address_id'] = $_POST['data']['address']['id'];

				}
				//print_r($addressId);
            }else{
            	$error[] = validation_errors();
            }
            /*echo '<pre>';
            print_r($_POST);*/
            /*print_r($error);exit;
            exit;*/
            if(empty($error)){
	            $this->form_validation->set_rules('data[customer_sites][customer_id]', 'Customer', 'required|numeric');
	            $this->form_validation->set_rules('data[customer_sites][address_id]', 'Site Address', 'required|numeric');
	            $this->form_validation->set_rules('data[customer_sites][first_name]', 'first name', 'required|max_length[255]');
	            $this->form_validation->set_rules('data[customer_sites][middle_name]', 'middle name', 'required|max_length[255]');
	            $this->form_validation->set_rules('data[customer_sites][surname]', 'surname', 'required|max_length[255]');
	             $this->form_validation->set_rules('data[customer_sites][primary_email]', 'primary email', 'max_length[255]|valid_email');
	            $this->form_validation->set_rules('data[customer_sites][contact_1]', 'contact 1', 'required|max_length[15]|min_length[10]|numeric');
	            $this->form_validation->set_rules('data[customer_sites][contact_2]', 'contact 2', 'max_length[15]|min_length[10]|numeric');
	            //print_r(validation_errors());
	            if($this->form_validation->run('data[customer_sites]')!== FALSE) { //echo "hii";exit;
	            	//print_r($this->input->post('data[customer_sites]'));
	            	$_POST['data']['customer_sites']['created'] = date('Y-m-d H:i:s');
	            	$_POST['data']['customer_sites']['modified'] = date('Y-m-d H:i:s');
	            	$post_data = $_POST;
					
					$customerSite = json_decode($this->register_site($post_data['data']['customer_sites']), true);
					if($customerSite['status'] === "success")
					{ 
						$siteServiceData = [];
						foreach ($this->input->post('customer_site_services') as $key => $value) {
							if(!empty($value['product_id'])){
								$value['customer_site_id'] = $customerSite['id'];
								$siteServiceData[] =  $value;
							}
						}
						//echo '<pre>';
						//print_r($siteServiceData);exit;
						$siteServices = Modules::run('customer_sites/register_site_multiple_service', $siteServiceData);
		                
		                $redirectUrl = $this->input->post('url')/*.'&address_id='.$user_address['id']*/;
		                
						$msg = array('message' => 'Site Added Successfully','class' => 'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
					}
	            }else{ //echo validation_errors();exit;
	            	$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
	                    $this->session->set_flashdata('message',$msg);
	            }
        	}
            redirect($redirectUrl);
            //exit;
            $data['values_posted'] = $_POST['data'];
            
        }

		$data['meta_title'] = "ERP : New Sites";
		$data['title'] = "ERP : New Sites";
		$data['meta_description'] = "ERP : Customer New Sites";
		//$data['content'] = "customer_sites/admin_add";
		//$data['method'][] = "admin_add";
		$data['methods'][] = "admin_add_form";

		$data['modules'][] = "customer_sites";

			
		
		echo Modules::run("templates/admin_template", $data);

	}

	function admin_add_form($data = array()) {
		//echo "hii";exit;
		$this->load->model('cities/cities_model');
		$this->load->model('states/states_model');
		$this->load->model('countries/countries_model');
		$this->load->model('areas/areas_model');
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

		$data['option']['customers'] = Modules::run('customers/get_Customer_list_dropdown');
		$data['option']['services'] = Modules::run('products/get_service_list_dropdown');
		$data['option']['service_charge_tye'] = ['PERCENT'=>'PERCENT', 'VALUE'=>'VALUE'];
		/*echo "<pre>";
		print_r($data['option']);exit;*/
		$this->load->view("customer_sites/admin_add", $data);
	}

	function register_site($postData){
		$insert_data = $postData;
		$this->pktdblib->set_table("customer_sites");
		//print_r($insert_data);exit;
		$id = $this->pktdblib->_insert($insert_data);
		if(!$id){
			return json_encode(["msg"=>"Failed to add Site", "status"=>"fail", 'id'=>0]);
		}else{
			return json_encode(["msg"=>"Sites Added Successfully", "status"=>"success", 'id'=>$id['id']]);
		}
	}

	function add_default_site($data=array()){
		//print_r($data);exit;
		$userRoles = $this->pktdblib->createquery(['table'=>'user_roles', 'conditions'=>['user_roles.login_id'=>$data['user_id'], 'account_type'=>'customers']]);
		
		$defaultCustomerDetails = Modules::run('customers/get_customer_details', $userRoles[0]['user_id']);
		unset($defaultCustomerDetails['id']);
		unset($defaultCustomerDetails['company_name']);
		unset($defaultCustomerDetails['emp_code']);
		unset($defaultCustomerDetails['pan_no']);
		unset($defaultCustomerDetails['gst_no']);
		unset($defaultCustomerDetails['gender']);
		unset($defaultCustomerDetails['dob']);
		unset($defaultCustomerDetails['joining_date']);
		unset($defaultCustomerDetails['has_multiple_sites']);
		unset($defaultCustomerDetails['adhaar_no']);
		/*  Array formation for Customer Sites  */
		$siteData = $defaultCustomerDetails;
		$siteData['address_id'] = $data['id'];
		$siteData['customer_id'] = $userRoles[0]['user_id'];
		$siteData['created'] = date('Y-m-d H:i:s');
		$siteData['modified'] = date('Y-m-d H:i:s');
		/*$siteData['service_charge_type'] = $data['service_charge_tye'];
		$siteData['service_charge'] = $data['service_charge'];*/
		//print_r($siteData);
		$register = json_decode($this->register_site($siteData), true);
		/*echo '<pre>';
		print_r($register);*/
		if($register['status']=='success'){
			return $register;
		}else{
			return false;
		}
		
	}

	function admin_edit_service($id = NULL){
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo '<pre>';
			//print_r($_POST);exit;
			$data['values_posted'] = $_POST;
			print_r($data['values_posted']['data']['areas']['area_name']);//exit;

			//print_r(expression)
			//print_r($data['values_posted']['faques']);exit;
			$this->form_validation->set_rules('data[customer_sites][first_name]', 'First Name');
			$this->form_validation->set_rules('data[customer_sites][middle_name]', 'Middle Name');
			$this->form_validation->set_rules('data[customer_sites][surname]', 'Surname');
			if($this->form_validation->set_rules('customer_sites')!== FALSE) {
				$postData['customer_sites'] = $data['values_posted']['data']['customer_sites'];
				//print_r($postData['customer_sites']['id']);exit;
				$postData['address'] = $data['values_posted']['data']['address'];
				//print_r($data['values_posted']['customer_site_services']);exit;

				if(!empty($data['values_posted']['data']['countries']['name'])){
					$country['name'] = $data['values_posted']['data']['countries']['name'];
					$country['is_active'] = TRUE;
					$country['created'] = date('Y-m-d H:i:s');
					$country['modified'] = date('Y-m-d H:i:s');
					$countryId = json_decode(Modules::run('countries/_register_admin_add', $country), true);
					$postData['address']['country_id'] = $countryId['id'];
					unset($data['values_posted']['data']['countries']['name']);
				}//exit;
					//echo '<pre>';print_r($post_data);exit;
				if(!empty($data['values_posted']['data']['states']['state_name'])){
					$state['state_name'] = $data['values_posted']['data']['states']['state_name'];
					$state['is_active'] = TRUE;
					$state['country_id'] = $postData['address']['country_id'];
					$state['created'] = date('Y-m-d H:i:s');
					$state['modified'] = date('Y-m-d H:i:s');
					$stateId = json_decode(Modules::run('states/_register_admin_add', $state),true);
					//print_r($stateId['id']);exit;
					$postData['address']['state_id'] = $stateId['id'];
					unset($data['values_posted']['data']['states']['state_name']);

				}
				if(!empty($data['values_posted']['data']['cities']['city_name'])){
					$city['city_name'] = $data['values_posted']['data']['cities']['city_name'];
					$city['short_name'] = $data['values_posted']['data']['cities']['city_name'];
					$city['is_active'] = TRUE;
					$city['country_id'] = $postData['address']['country_id'];
					$city['state_id'] = $postData['address']['state_id'];
					$city['created'] = date('Y-m-d H:i:s');
					$city['modified'] = date('Y-m-d H:i:s');
					$cityId = json_decode(Modules::run('cities/_register_admin_add', $city), true);
					//print_r($cityId);exit;
					$postData['address']['city_id'] = $cityId['id'];
					unset($data['values_posted']['data']['cities']['city_name']);

				}
				//print_r($data['values_posted']['data']['areas']['area_name']);exit;
				if(!empty($data['values_posted']['data']['areas']['area_name'])){
					//echo "new area found";exit;
					$area['area_name'] = $data['values_posted']['data']['areas']['area_name'];
					$area['is_active'] = TRUE;
					$area['city_id'] = $postData['address']['city_id'];
					$area['created'] = date('Y-m-d H:i:s');
					$area['modified'] = date('Y-m-d H:i:s');
					$areaId = json_decode(Modules::run('areas/_register_admin_add', $area),true);
					//print_r($areaId['id']);exit;
					$postData['address']['area_id'] = $areaId['id'];
					unset($data['values_posted']['data']['areas']['area_name']);

				}

				//	print_r($postData['address']);exit;
				
				if(empty($error)) {
					//echo "error empty";exit;
					$this->pktdblib->set_table("customer_sites");
					if($this->pktdblib->_update($postData['customer_sites']['id'],$postData['customer_sites']))
					{	
						$this->pktdblib->set_table("address");
						if($this->pktdblib->_update($postData['address']['id'],$postData['address']))
						{		
							$this->pktdblib->set_table("customer_site_services");
							$insert = [];
							$update = [];
							foreach ($data['values_posted']['customer_site_services'] as $siteServiceKey => $siteServices) {
								print_r($siteServices);
				 				$siteServices['start_date'] = $this->pktlib->dmYToYmd($siteServices['start_date']);
				 				$siteServices['end_date'] = isset($siteServices['end_date'])?$this->pktlib->dmYToYmd($siteServices['end_date']):$this->pktlib->dmYToYmd($siteServices['start_date']);
								$siteServices['modified'] = date('Y-m-d H:i:s'); 
								
								if(isset($siteServices['id']) && !empty($siteServices['id'])) {
									$update[] = $siteServices;
								}else {
									unset($siteServices['id']);
				 					$siteServices['created'] = date('Y-m-d H:i:s');
				 					$siteServices['customer_site_id'] = $postData['customer_sites']['id'];
				 					$insert[] = $siteServices;
								}
							}
							
							if(!empty($insert)) {
								$this->pktdblib->set_table("customer_site_services");
								$this->pktdblib->_insert_multiple($insert);
							}

							if(!empty($update)) {
								$this->pktdblib->set_table("customer_site_services");
								$this->pktdblib->update_multiple('id',$update);
							}
							$msg = array('message' => 'Data Updated Successfully', 'class' => ' alert alert-success');
							$this->session->set_flashdata('message', $msg);
							
						}
					} 
					else {
						$msg = array('message' => 'Some error occured while updating','class'=>'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
					}
					//exit;
					//print_r(custom_constants::edit_product_url."/".$id);exit;
					redirect(custom_constants::edit_site_service_url."/".$id);
				}
				else {
					$msg = array('message' => 'Some error occured while updating','class'=>'alert alert-danger');
					$this->session->set_flashdata('message', $msg);	
				}
				

			}else {
				$msg = array('message' => 'Validation error occured'. validation_errors(), 'class'=>'alert alert-danger');
				$this->session->set_flashdata('message', $msg);

			}

		}
		$data['id'] = $id;
		//echo $id;exit;
		//echo '<pre>';
		$this->pktdblib->set_table("customer_site_services");
		$siteServiceDetails = $this->pktdblib->get_where($id);
		//print_r($siteServiceDetails);//exit;
		$this->pktdblib->set_table("customer_sites");
		$siteDetails = $this->get_site_details($siteServiceDetails['customer_site_id']);
		//print_r($siteDetails);//exit;

		//$this->pktdblib->set_table("customer_sites");
		$customerDetails = modules::run('customers/get_customer_details',$siteDetails['customer_id']);
		//print_r($customerDetails);//exit;

		$this->pktdblib->set_table("address");
		$addressDetails = $this->pktdblib->get_where($siteDetails['address_id']);
		//print_r($addressDetails);exit;

		$this->load->model('cities/cities_model');
		$this->load->model('states/states_model');
		$this->load->model('countries/countries_model');
		$this->load->model('areas/areas_model');
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

		$data['option']['customers'] = Modules::run('customers/get_Customer_list_dropdown');
		$data['option']['services'] = Modules::run('products/get_service_list_dropdown');
		$data['option']['service_charge_tye'] = ['PERCENT'=>'PERCENT', 'VALUE'=>'VALUE'];
		$data['values_posted']['customer_sites'] = $siteDetails;
		//$data['values_posted']['customer_sites'] = $customerDetails;
		$data['values_posted']['address'] = $addressDetails;
		$data['values_posted']['siteServiceDetails'] = $siteServiceDetails;
		//echo '<pre>';
		//print_r($siteDetails);
		//print_r($customerDetails);
		//print_r($siteServiceDetails);

		$data['meta_title'] = "ERP : New Sites";
		$data['title'] = "ERP : New Sites";
		$data['meta_description'] = "ERP : Customer New Sites";
		$data['modules'][] = "customer_sites";
		$data['methods'][] = "admin_edit_service_form";
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_edit_service_form(){
		$this->load->view('customer_sites/edit_site_form');
	}

	function register_site_multiple_service($postDatas){
		//print_r($postDatas);exit;
		$insertData = [];
		foreach ($postDatas as $key => $postData) {
			$postData['start_date'] = $this->pktlib->dmYtoYmd($postData['start_date']);
			if(isset($postData['end_date']))
				$postData['end_date'] = $this->pktlib->dmYtoYmd($postData['end_date']);

			$postData['created'] = date('Y-m-d H:i:s');
			$postData['modified'] = date('Y-m-d H:i:s');
			$insertData[] = $postData; 
		}
		//$insert_data = $postData;
		//print_r($insertData);exit;
		$this->pktdblib->set_table("customer_site_services");
		//print_r($insert_data);exit;
		$id = $this->pktdblib->_insert_multiple($insertData);
		//print_r($id);
		if(!$id){
			return json_encode(["msg"=>"Failed to add service on this site", "status"=>"fail", 'id'=>0]);
		}else{
			return json_encode(["msg"=>"Service added successfully on this site", "status"=>"success", 'id'=>$id]);
		}
	}


	function admin_add2() {
		//this form is for courier service
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';
			//print_r($_POST);exit;
			$redirectUrl = custom_constants::new_courier_site_url;
            $error = [];
            //Validate Site Address and make entry
            //$this->form_validation->set_rules('data[address][address_1]', 'address_1', 'required|max_length[320]');
			//$this->form_validation->set_rules('data[address][address_2]', 'address_2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'state', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'city', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'pincode', 'required|max_length[6]|min_length[6]');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			$_POST['data']['address']['type'] = 'customers';
			//echo $this->input->post('data[customer_sites][customer_id]');
			$_POST['data']['address']['user_id'] = $this->input->post('data[customer_sites][customer_id]');
			if(!empty($_POST['data']['countries']['name'])){
					$country['name'] = $_POST['data']['countries']['name'];
					$country['is_active'] = TRUE;
					$country['created'] = date('Y-m-d H:i:s');
					$country['modified'] = date('Y-m-d H:i:s');
					$countryId = json_decode(Modules::run('countries/_register_admin_add', $country), true);
					$_POST['data']['address']['country_id'] = $countryId['id']['id'];//exit;
					unset($_POST['data']['countries']['name']);
				}//exit;
					//print_r($_POST['data']['address']['country_id']);exit;
				if(!empty($_POST['data']['states']['state_name'])){
					$state['state_name'] = $_POST['data']['states']['state_name'];
					$state['is_active'] = TRUE;
					$state['country_id'] = $_POST['data']['address']['country_id'];
					$state['created'] = date('Y-m-d H:i:s');
					$state['modified'] = date('Y-m-d H:i:s');
					$stateId = json_decode(Modules::run('states/_register_admin_add', $state),true);
					//print_r($stateId['id']);exit;
					$_POST['data']['address']['state_id'] = $stateId['id'];
					unset($_POST['data']['states']['state_name']);

				}
				if(!empty($_POST['data']['cities']['city_name'])){
					$city['city_name'] = $_POST['data']['cities']['city_name'];
					$city['short_name'] = $_POST['data']['cities']['city_name'];
					$city['is_active'] = TRUE;
					$city['country_id'] = $_POST['data']['address']['country_id'];
					$city['state_id'] = $_POST['data']['address']['state_id'];
					$city['created'] = date('Y-m-d H:i:s');
					$city['modified'] = date('Y-m-d H:i:s');
					$cityId = json_decode(Modules::run('cities/_register_admin_add', $city), true);
					//print_r($cityId);exit;
					$_POST['data']['address']['city_id'] = $cityId['id'];
					unset($_POST['data']['cities']['city_name']);

				}
				if(!empty($_POST['data']['areas']['area_name'])){
					$area['area_name'] = $_POST['data']['areas']['area_name'];
					$area['is_active'] = TRUE;
					$area['city_id'] = $_POST['data']['address']['city_id'];
					$area['created'] = date('Y-m-d H:i:s');
					$area['modified'] = date('Y-m-d H:i:s');
					$areaId = json_decode(Modules::run('areas/_register_admin_add', $area),true);
					$_POST['data']['address']['area_id'] = $areaId['id'];
					unset($_POST['data']['areas']['area_name']);

				}
			//echo '<pre>';
			//print_r($_POST['data']['address']);exit;
			$this->form_validation->set_rules('data[address][user_id]', 'Customer', 'required');
			if($this->form_validation->run()!== FALSE) {
				if(!isset($_POST['data']['address']['id'])){
					//echo "address inserted";exit;
					$address = json_decode(Modules::run('address/register_user_address', $this->input->post('data[address]')),true);
					if($address['status']=='success'){
						$_POST['data']['customer_sites']['address_id'] = $address['id']['id'];
					}
				}else{ 
					//echo "address not inserted";exit;

					//$this->load->model('address/address_model');
					$this->pktdblib->set_table('address');
					$this->pktdblib->_update($_POST['data']['address']['id'], $this->input->post('data[address]'));
					$_POST['data']['customer_sites']['address_id'] = $_POST['data']['address']['id'];

				}
				//print_r($addressId);
            }else{
            	$error[] = validation_errors();
            }
            /*echo '<pre>';
            print_r($_POST);
            exit;*/
            /*print_r($error);exit;
            exit;*/
            if(empty($error)){
	            $this->form_validation->set_rules('data[customer_sites][customer_id]', 'Customer', 'required|numeric');
	            $this->form_validation->set_rules('data[customer_sites][address_id]', 'Site Address', 'required|numeric');
	            $this->form_validation->set_rules('data[customer_sites][first_name]', 'first name', 'required|max_length[255]');
	            //$this->form_validation->set_rules('data[customer_sites][middle_name]', 'middle name', 'required|max_length[255]');
	            $this->form_validation->set_rules('data[customer_sites][surname]', 'surname', 'required|max_length[255]');
	            //$this->form_validation->set_rules('data[customer_sites][primary_email]', 'primary email', 'max_length[255]|valid_email');
	            $this->form_validation->set_rules('data[customer_sites][contact_1]', 'contact 1', 'required|max_length[15]|min_length[10]|numeric');
	            $this->form_validation->set_rules('data[customer_sites][contact_2]', 'contact 2', 'max_length[15]|min_length[10]|numeric');
	            //print_r(validation_errors());
	            if($this->form_validation->run('data[customer_sites]')!== FALSE) { //echo "hii";exit;
	            	//print_r($this->input->post('data[customer_sites]'));exit;
	            	$_POST['data']['customer_sites']['created'] = date('Y-m-d H:i:s');
	            	$_POST['data']['customer_sites']['modified'] = date('Y-m-d H:i:s');
	            	$post_data = $_POST;
	            	$customerSite = [];
	            	if(NULL!==($this->input->post('data[customer_sites][id]')) && $this->input->post('data[customer_sites][id]')!=0){
	            		$this->pktdblib->set_table('customer_sites');
	            		$upd = $this->pktdblib->_update($this->input->post('data[customer_sites][id]'), $this->input->post('data[customer_sites]'));
	            		if($upd){
	            			$customerSite['status'] = "success";
	            			$customerSite['id'] = $this->input->post('data[customer_sites][id]');
	            		}
	            	}else{

						$customerSite = json_decode($this->register_site($post_data['data']['customer_sites']), true);
	            	}
					
					if($customerSite['status'] === "success")
					{ 
						$siteServiceData = [];
						foreach ($this->input->post('customer_site_services') as $key => $value) {
							if(!empty($value['product_id'])){
								$value['customer_site_id'] = $customerSite['id'];
								$value['end_date'] = $value['start_date'];
								$siteServiceData[] =  $value;
							}
						}
						//echo '<pre>';
						//print_r($siteServiceData);exit;
						$siteServices = Modules::run('customer_sites/register_site_multiple_service', $siteServiceData);
		                
		                $redirectUrl = $this->input->post('url');
		                
						$msg = array('message' => 'Service Has Been Added To Site','class' => 'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
					}
	            }else{ //echo validation_errors();exit;
	            	$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
	                    $this->session->set_flashdata('message',$msg);
	            }
        	}
            redirect($redirectUrl);
            //exit;
            $data['values_posted'] = $_POST['data'];
            
        }
		$data['meta_title'] = "ERP : New Sites";
		$data['title'] = "ERP : New Sites";
		$data['meta_description'] = "ERP : Customer New Sites";
		$data['js'][0] = '<script type = "text/javascript">
            /*$(".maxCurrentDate").on("blur", function(){
                console.log("hii");
                var d = new Date();
				alert(d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate());
				alert(d.getDate()."")
                $(".maxCurrentDate").each(function( index ) {
			  	console.log( index + ": " + $( this ).val() );
				});
            });*/
            $("#site_id").on("change", function(){
            	var id = this.id;
        		//alert(id);
        		var val = $("#"+id).val();
        		var customerId = $("#customer_id").val();
        		//alert(val);
        		//alert(customerId);
        		if(val!=="0"){
        			window.location = base_url+"customers/editcustomer/"+customerId+"?tab=sites&site_id="+val;
        		}
        	});
            </script>
        ';

		//$data['content'] = "customer_sites/admin_add";
		//$data['method'][] = "admin_add";
		$data['methods'][] = "admin_add_couriersite_form";

		$data['modules'][] = "customer_sites";

		//echo '<pre>';print_r($data);exit;
		
		echo Modules::run("templates/admin_template", $data);

	}


	function admin_add_couriersite_form($data = array()) {
		//print_r($data);
		//echo "hii";exit;
		$this->load->model('cities/cities_model');
		$this->load->model('states/states_model');
		$this->load->model('countries/countries_model');
		$this->load->model('areas/areas_model');
		$data['option']['siteId'][0] = "Site Not Listed";
		if(isset($data['customer_id'])){
			//$this->pktdblib->set_table('customer_sites');
			$customesSites = $this->pktdblib->custom_query('Select cs.*, a.site_name,a.pincode from customer_sites cs inner join customers c on cs.customer_id=c.id inner join address a on a.id=cs.address_id where cs.customer_id="'.$data['customer_id'].'" and cs.is_active=true');
			foreach ($customesSites as $key => $site) {
				$data['option']['siteId'][$site['id']] = $site['first_name']." ".$site['middle_name']." ".$site['surname']." - ".$site['site_name']." (".$site['pincode'].")";
			}

		}
		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
		$states = $this->states_model->get_dropdown_list();
		$data['option']['states'][0] = "Select State";
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		$cities = $this->cities_model->get_dropdown_list();
		//$data['option'] = [];
		$data['option']['cities'][0] = "Select City";
		//print_r($cities);
		foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}
                        
		$areas =$this->areas_model->get_dropdown_list();
		$data['option']['areas'][0] = "Select Area";
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}

		$data['option']['customers'] = Modules::run('customers/get_Customer_list_dropdown');
		$data['option']['services'] = Modules::run('products/get_service_list_dropdown');
		$data['option']['service_charge_tye'] = ['PERCENT'=>'PERCENT', 'VALUE'=>'VALUE'];

		/*echo "<pre>";
		print_r($data['option']);exit;*/
		$this->load->view("customer_sites/admin_add_courier_form", $data);
	}

	function get_site_details($id) {
        //echo "reached in Customer module";exit;
        //print_r($id);
        $this->pktdblib->set_table('customer_sites');
        $customerdetails = $this->pktdblib->get_where($id);
        //print_r($customerdetails);exit;
        return $customerdetails;
    }

    function admin_site_service() {
		$data['meta_title'] = 'Customer Site Services';
		$data['meta_description'] = 'Customer Site Services';
		//$data['module'] = 'address';
		//$data['content'] = 'address/address_listing';
		$data['modules'][] = 'customer_sites';
		$data['methods'][] = 'admin_site_service_listing';
		
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_site_service_listing($data = []) {
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		//echo "string"; exit;
		//$this->customer_site_model->set_table('customer_site_services');
		$data['customerSiteServices'] = $this->customer_site_model->get_site_service_list($condition);
		//print_r($data['customerSiteServices']);exit;
		$this->load->view("customer_sites/admin_site_service_listing", $data);
	}

	function edit_site_service($id=NULL, $post_data = []) {
		//print_r($post_data);exit;
		if(NULL == $id)
			return false;
		$this->pktdblib->set_table('customer_sites');
		if($this->product_model->_update($id,$post_data)){
			return true;
		}
		else
			return false;
	}

	function getCustomerWiseSites(){
		if(!$this->input->post('params'))
			return;

		$customerId = $this->input->post('params');
		$siteList = [0=>'Site Not Listed'];
		$customesSites = $this->pktdblib->custom_query('Select cs.*, a.site_name,a.pincode from customer_sites cs inner join customers c on cs.customer_id=c.id inner join address a on a.id=cs.address_id where cs.customer_id="'.$customerId.'" and cs.is_active=true');
		foreach ($customesSites as $key => $site) {
			$siteList[$key+1]['id'] = $site['id'];
			$siteList[$key+1]['text'] = $site['first_name']." ".$site['middle_name']." ".$site['surname']." - ".$site['site_name']." (".$site['pincode'].")";
		}

		echo json_encode($siteList);

	}

	function unbilled_sites(){
		$data['meta_title'] = 'Customer Sites';
		$data['meta_description'] = 'Customer Sites';
		$sql = 'Select css.*, a.site_name, concat(c.first_name," ", c.middle_name, " ", c.surname) as fullname, c.company_name, c.contact_1 from customer_site_services css inner join customer_sites cs on cs.id=css.customer_site_id inner join address a on a.id=cs.address_id inner join customers c on c.id=cs.customer_id where 1=1 And css.consign_no not in (select consign_no from customer_bill_details) AND css.is_active=true';
		if(NULL!==$this->input->post('customer_id')){
			$sql.= ' AND c.id='.$this->input->post('customer_id');
		}

		$sql.' order by css.consign_no asc';
		//echo $sql;
		$data['site_services'] = $this->pktdblib->custom_query($sql);
		/*echo '<pre>';
		print_r($data['site_service']);
		exit;*/
		$data['content'] = 'customer_sites/unbilled_sites';
		//$data['methods'][] = 'admin_index_listing';
		
		echo Modules::run("templates/admin_template", $data);
	}
}