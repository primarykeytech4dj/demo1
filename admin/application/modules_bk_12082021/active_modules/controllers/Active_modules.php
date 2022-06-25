<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Active_modules extends MY_Controller {
	function __construct() {
		parent::__construct();
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->model('active_modules/active_module_model');
		$this->load->library('pktdblib');
	}

	function admin_index() {
		$data['meta_title'] = 'Active Modules';
		$data['meta_description'] = 'Active Modules';
		//$data['module'] = 'address';
		//$data['content'] = 'address/address_listing';
		$data['modules'][] = 'active_modules';
		$data['methods'][] = 'module_listing';
	
		echo Modules::run("templates/admin_template", $data); 	
	}

	function module_listing($data = []) {
		
		$this->pktdblib->set_table('active_modules');
		$data['modules'] = $this->pktdblib->get_list(['order'=>'id desc']);

		$this->load->view("active_modules/admin_index", $data);
	}

	function admin_add($data = []) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        $redirectUrl = 'active_modules/newModule';
	        //print_r($_POST);exit;
			$this->form_validation->set_rules('data[active_modules][module]', 'Module Name', 'required|max_length[320]');
			$this->form_validation->set_rules('data[active_modules][version]', 'Version', 'required|max_length[320]');
			$this->form_validation->set_rules('data[active_modules][slug]', 'Slug', 'required|is_unique[active_modules.slug]');
			$this->form_validation->set_rules('data[active_modules][route]', 'Route', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$post_data = $_POST;
				
				$activeModule = json_decode($this->register_module($this->input->post('data[active_modules]')), true);
				if($activeModule['status'] === "success")
				{ 
	                if($this->input->post('module')!='address'){
	                	$redirectUrl = $this->input->post('url')/*.'&address_id='.$user_address['id']*/;
	                }
					$msg = array('message' => 'Module Added Successfully','class' => 'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
				}
				else
				{
					$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
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
		$data['meta_title'] = "Modules";
		$data['meta_description'] = "Active Modules";
		$data['modules'][] = "active_modules";
		$data['methods'][] = "admin_add_form";
		/*echo '<pre>';
		print_r($data);exit;*/
		echo Modules::run("templates/admin_template", $data);
	}

	function register_module($data) { 
		$this->pktdblib->set_table("active_modules");
		$id = $this->pktdblib->_insert($data);
		return json_encode(["msg"=>"Module Added Successfully", "status"=>"success", 'id'=>$id]);
	}

	function module_details($id) {
		$this->pktdblib->set_table('active_modules');
		$moduleDetails = $this->pktdblib->get_where($id);
		return $moduleDetails->row_array();
	}

	function admin_edit($id = null){
		//print_r($data);
		//echo "hello";
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
			//echo "hello";
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[active_modules][module]', 'Module Name', 'required|max_length[320]');
			$this->form_validation->set_rules('data[active_modules][version]', 'Version', 'required|max_length[320]');
			$this->form_validation->set_rules('data[active_modules][slug]', 'Slug', 'required|is_unique[active_modules.slug]');
			$this->form_validation->set_rules('data[active_modules][route]', 'Route', 'required');
			if($this->form_validation->run() !== FALSE) {
				$_POST['data']['active_modules']['is_active'] = (null!=$this->input->post('data[active_modules][is_active]'))?true:false;
				$post_data = $_POST['data']['active_modules'];
				if($this->pktdblib->_update($id,$post_data)) {
                    $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                    $this->session->set_flashdata('message',$msg);
                }
                else {
                    $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('message',$msg);
                }

			}else{
				$msg = array('message' => validation_errors(),'class' => 'alert alert-danger fade in');
                $this->session->set_flashdata('message', $msg);
			}
            redirect($this->input->post('url'));

		}
		else {
			$this->pktdblib->set_table("active_modules");
			$data['active_modules'] = $this->module_details($id);
			//print_r($data['active_modules']);exit;
			$data['values_posted']['active_modules'] = $data['active_modules'];
		}
		$data['id'] = $id;
		
		$data['meta_title'] = 'Edit Module';
		$data['meta_description'] = 'Edit Module';
		$data['modules'][] = 'active_modules';
		$data['methods'][] = 'admin_edit_form';
		echo Modules::run("templates/admin_template", $data);
	}


	function admin_add_form($data = []) {

		$cities = $this->cities_model->get_dropdown_list();
		//$data['option'] = [];
		$data['option']['modules'][0] = "Select Module";
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
		/*echo '<pre>';
		print_r($data);exit;*/
		if(!isset($data['type'])){
			$data['type'] = '';//variable is passed through other modules
			$data['user_id'] = '';
		}
		
		$data['users']  = [];
		/*echo '<pre>';
		print_r($data);
		exit;*/
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
		}else{
			$data['option']['typeLists'] = [''=>'Address belongs to?', 'employees'=>'Employee', 'customers'=>'Customer', 'suppliers'=>'Supplier', 'companies'=>'companies'];

		}
		$this->load->view('address/admin_add', $data);
	}

	function admin_edit_form($data = []) {
		//echo $this->uri->segment(3);exit;
		if(empty($data)){
			$data['address'] = $this->module_details($this->uri->segment(3));
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
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname'].' | '.$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['enquiries'=>'Leads'];
		}else{
			$data['option']['typeLists'] = [''=>'Address belongs to?', 'employees'=>'Employee', 'customers'=>'Customer', 'suppliers'=>'Supplier', 'companies'=>'companies'];

		}

		$data['values_posted']['address'] = $data['address'];
		
		$this->load->view('address/admin_edit_address', $data);
	}

	function view_user_wise_address($data = NULL) {
		if(NULL == $data){
			show_404();
		}
		//print_r($data);exit;
		$data['address'] = $this->address_model->getUserWiseAddress($data['user_id'], $data['type']);
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
		$this->address_model->set_table('address');
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
		
		$this->load->view('address/add', $data);
	}

	function add($data = []) {
		//print_r($data);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        $redirectUrl = $this->input->post('url');
	        //print_r($_POST);exit;
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
				$_POST['data']['address']['user_id'] = $this->session->userdata('employee_id');
				$_POST['data']['address']['type'] = 'customers';
				$post_data = $_POST;
				
				$user_address = json_decode($this->register_user_address($post_data['data']['address']), true);
				if($user_address['status'] === "success")
				{ 
					
	                $redirectUrl = 'address/edit_address/'.$user_address['id'];
	                if($this->input->post('module')!='address'){
	                	$redirectUrl = $this->input->post('url');
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
		$data['methods'][] = "admin_add";
		/*echo '<pre>';
		print_r($data);exit;*/
		echo Modules::run("templates/admin_template", $data);
	}

	function edit_form($data = []) {
		//echo $this->uri->segment(3);exit;
		if(empty($data)){
			$data['address'] = $this->module_details($this->uri->segment(3));
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
				if($this->address_model->update_address($id,$post_data)) {
						
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
			$this->address_model->set_table("address");
			$data['address'] = $this->module_details($id);
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
}