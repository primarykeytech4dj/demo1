<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees extends MY_Controller {

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
		
		$this->load->model('employees/employees_model');
		$this->load->model('address/address_model');
		$this->load->model('cities/cities_model');
		$this->load->model('countries/countries_model');
		$this->load->model('states/states_model');
		$this->load->model('areas/areas_model');
		$this->load->model('login/mdl_login');
		$setup = $this->setup();
	}

	function setup(){
		$employees = $this->employees_model->tbl_employees();
		return TRUE;
	}

	function admin_index() {
		$data['meta_title'] = 'employees listing';
		$data['meta_description'] = 'Employees Details';
		$data['modules'][] = 'employees';
		$data['methods'][] = 'admin_employee_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_employee_listing($data = []) {
		$data['employees'] = $this->employees_model->get_employees_details();
		$this->load->view("employees/admin_employee_listing", $data);
	}

	function admin_index_other_staff() {
		$data['meta_title'] = 'employees listing';
		$data['meta_description'] = 'Employees Details';
		$data['modules'][] = 'employees';
		$data['methods'][] = 'admin_other_staff_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_other_staff_listing($data = []) {
		$data['employees'] = $this->employees_model->get_employees_details();
		$this->load->view("employees/admin_index_other_staff", $data);
	}

	// Add new user
	function admin_add() {
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';print_r($this->input->post());exit;
			$data['values_posted'] = $this->input->post('data');
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[employees][first_name]', 'first name', 'required|max_length[255]|alpha_dash');
			$this->form_validation->set_rules('data[employees][surname]', 'surname', 'required|max_length[255]|min_length[2]|alpha_dash');
			$this->form_validation->set_rules('data[employees][dob]', 'dob', 'required');
			$this->form_validation->set_rules('data[employees][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][contact_2]', 'contact_2', 'max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][primary_email]', 'primary email', 'required|max_length[255]|valid_email|is_unique[login.email]');
			$this->form_validation->set_rules('data[employees][emp_code]', 'Employee Code', 'max_length[255]|is_unique[employees.emp_code]');
			$this->form_validation->set_rules('data[employees][secondary_email]', 'secondary email', 'max_length[255]|valid_email');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				if(!empty($_FILES['profile_img']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'../content/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					if(empty($profileImg['error'])) {
						$_POST['data']['employees']['profile_img'] = $profileImg['filename'];
					}
					else {
						$error['profile_img'] = $profileImg['error'];
					}
				}else {
					$_POST['data']['employees']['profile_img'] = '';
				}

				if(empty($error)) {
					$post_data = $_POST['data']['employees'];
					$post_data['dob'] = $this->pktlib->dmYtoYmd($post_data['dob']);
					$post_data['allow_login'] = (null !== ($this->input->post('data[employees][allow_login]')))?$this->input->post('data[employees][allow_login]'):0;
					$reg_user = json_decode($this->_register_admin_add($post_data), true);
					
					if($reg_user['status'] === "success")
					{
						// Successfully registered
						if(!empty($post_data)){
							$post_data['id'] = $reg_user['id'];
							$login = Modules::run('login/register_employee_to_login', $post_data);
							$msg = array('message'=>'Login Added Successfully', 'class'=>'alert alert-success');
	                    	$this->session->set_flashdata('message',$msg);
						}
						$msg = array('message'=>'Employee Added Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::edit_employee_url."/".$reg_user['id']);
					}
					else
					{
						// Registration error
						$data['form_error'] = $reg_user['msg'];
					}
				}else {
					$msg = array('message'=>'Failed to Upload Image. Error: '.$error['profile_img'], 'class'=>'alert alert-danger');
	                $this->session->set_flashdata('message',$msg);
				}
			}
		}
		
		$blood_groups = $this->employees_model->get_dropdown_list();
		$data['option']['blood_group'][NULL] = 'Select Blood Group'; 
		foreach($blood_groups as $blood_groupKey => $blood_group) {
			$data['option']['blood_group'][$blood_group['id']] = $blood_group['blood_group'];
		}

		$cities = $this->cities_model->get_dropdown_list();
		foreach ($cities as $cityKey => $city) {
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}

		$countries =$this->countries_model->get_dropdown_list();
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}

		$data['areas'] = $this->areas_model->get_list();
		$data['states'] = $this->states_model->get_list();
		$data['countries'] = $this->countries_model->get_list();
		$this->pktdblib->set_table('roles');
		$roles = $this->pktdblib->get_active_list();
		$data['roles'] = [''=>'Select Role'];
		foreach ($roles as $key => $role) {
			if($role['id']>1)
			$data['option']['roles'][$role['id']] = $role['role_name'].' - '.$role['role_code'];
		}
		/*print_r($data['roles']);
		exit;*/
		$data['meta_title'] = "New User";
		$data['meta_description'] = "New user registration";
		$data['modules'][] = "employees";
		$data['methods'][] = "admin_employee_register";
		echo Modules::run("templates/admin_template", $data);
	}


	function admin_employee_register() {
		$this->load->view("employees/admin_add");
	}

	function _register_admin_add($data) {
		$this->employees_model->set_table("employees");
		
		if($this->employees_model->count_where("primary_email", $data['primary_email']) > 0 && $data['primary_email'] !== NULL)
		{
			return json_encode(["msg"=>"email is already in use", "status"=>"error"]);
		}
		
		$this->employees_model->set_table("employees");
		$id = $this->employees_model->_insert($data);
		if(empty($data['emp_code'])) {
			$empCode = $this->create_code($id);
			$updArr['id'] = $id;
			$updArr['emp_code'] = $empCode;
			$updCode = $this->edit_employee($id, $updArr);
		}
		
		return json_encode(["msg"=>"Employees Successfully Inserted", "status"=>"success", 'id'=>$id]);
	}

	function edit_employee($id=NULL, $post_data = []) {
		if(NULL == $id)
			return false;

		if($this->employees_model->update_employees($id,$post_data))
			return true;
		else
			return false;
	}

	function admin_edit($id = null) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[employees][first_name]', 'first name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[employees][surname]', 'surname', 'required|max_length[255]|min_length[2]');
			$this->form_validation->set_rules('data[employees][dob]', 'dob', 'required');
			$this->form_validation->set_rules('data[employees][start_date]', 'start_date', 'required');
			$this->form_validation->set_rules('data[employees][contact_1]', 'contact_1', 'required|max_length[12]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][contact_2]', 'contact_2', 'max_length[12]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][primary_email]', 'Primary email', 'max_length[255]|valid_email');
			$this->form_validation->set_rules('data[employees][secondary_email]', 'secondary email', 'max_length[255]|valid_email');
			if($this->form_validation->run('employees') !== FALSE) {
				$profileImg = '';
				$post_data = $_POST['data']['employees'];
				
				if(!empty($_FILES['profile_img']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'../content/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					if(empty($profileImg['error'])) {
						$post_data['profile_img'] = $profileImg['filename'];
						unset($post_data['profile_img2']);
					}
					else {
						$error['profile_img'] = $profileImg['error'];
					}
				}else {
					$post_data['profile_img'] = $post_data['profile_img2'];
					unset($post_data['profile_img2']);
				}
				
				if(empty($error)) {
					$post_data['dob'] = DateTime::createFromFormat('d/m/Y', $post_data['dob']);
					$post_data['dob'] = $post_data['dob']->format('Y-m-d');
					$post_data['start_date'] = DateTime::createFromFormat('d/m/Y', $post_data['start_date']);
					$post_data['start_date'] = $post_data['start_date']->format('Y-m-d');
					if($this->edit_employee($id,$post_data)) {
						if(!empty($post_data)){

							/*$login = Modules::run('login/admin_edit_login', $id,$post_data);*/
							$login = Modules::run('login/edit_employee_login', $id);
							$msg = array('message'=>'Login Updated Successfully', 'class'=>'alert alert-success');
	                    	$this->session->set_flashdata('message',$msg);
						}
                        $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                             $this->session->set_flashdata('message',$msg);
                        }
                        else {
                            $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                            $this->session->set_flashdata('message',$msg);
                        }
                        redirect(custom_constants::edit_employee_url ."/".$id.'?tab=address');
                    }
                    else {
                        $msg = array('message' => $error,'class' => 'alert alert-danger fade in');
                        $this->session->set_flashdata('error', $msg);
                    }
				}else {
					$msg = array('message' => 'Some Validation Error Occurred'.validation_errors(),'class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('error', $msg);
				}

		}
		else {
			$data['values_posted']['employees'] = $this->employee_details($id);
		}

		$data['employees'] = $data['values_posted']['employees'];
		
		if(!($this->input->get('tab')))
			$data['tab'] = 'personal_info';
		else
			$data['tab'] = $this->input->get('tab');

		$blood_groups = $this->employees_model->get_dropdown_list();
		
		foreach($blood_groups as $blood_groupKey => $blood_group) {
			$data['option']['blood_group'][$blood_group['blood_group']] = $blood_group['blood_group'];
		}
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
		$data['meta_title'] = 'edit employees';
		$data['meta_description'] = 'edit employees';
		
		$data['content'] = 'employees/admin_edit';
		//echo $id;
		$userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>'employees']);
		//echo '<pre>';print_r($userRoles);exit;
		$loginId = $userRoles[0]['login_id'];
		$roleId = $userRoles[0]['role_id'];

		$AddressListData = ['url'=>custom_constants::edit_employee_url.'/'.$id.'?tab=address', 'condition'=>['address.user_id'=>$loginId, 'address.type'=>'login'], 'module'=>'employees'];
		$data['addressList'] = Modules::run("address/admin_address_listing", $AddressListData);

		$this->pktdblib->set_table('address');
		$addressData = ['url'=>custom_constants::edit_employee_url.'/'.$id.'?tab=address', 'module'=>'employees', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['employees']];
		if($this->input->get('address_id')) { 
			$addressData['address'] = $this->pktdblib->get_where($this->input->get('address_id'));
			$data['address'] = Modules::run("address/admin_edit_form", $addressData);
		}else {
			$data['address'] = Modules::run("address/admin_add_form", $addressData);
		}
		/* Bank Account Related Code Starts Here  */
		$bankAccountListData = ['url'=>custom_constants::edit_employee_url.'/'.$id.'?tab=bank_account', 'condition'=>['bank_accounts.user_id'=>$loginId, 'bank_accounts.user_type'=>'login'], 'module'=>'employees'];
		$this->pktdblib->set_table('bank_accounts');
		$data['bankAccountList'] = Modules::run("bank_accounts/admin_index_listing", $bankAccountListData);
		//print_r($data['bankAccountList']);exit;

		$bankAccountData = ['url'=>custom_constants::edit_employee_url.'/'.$id.'?tab=bank_account', 'module'=>'employees', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['employees']];
		if($this->input->get('bank_account_id')) { 
			$bankAccountData['bank_accounts'] = Modules::run("bank_accounts/account_details", $this->input->get('bank_account_id'));
			$data['bank_account'] = Modules::run("bank_accounts/admin_edit_form", $bankAccountData);
		}else {
			$data['bank_account'] = Modules::run("bank_accounts/admin_add_form", $bankAccountData);
		}
		/*Bank account ends*/

		/*Document Uploads*/
		$documentListData = [ 'condition'=>['user_documents.user_id'=>$loginId, 'user_documents.user_type'=>'login'], 'module'=>'employees'];
		//$this->address_model->set_table('bank_accounts');
		$data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

		$documentData = ['url'=>custom_constants::edit_employee_url.'/'.$id.'?tab=document', 'module'=>'employees', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['employees']];
		
		$data['document'] = Modules::run("upload_documents/admin_add_form", $documentData);
		
		/* Other Employee Details */
		$data['otherDetailsList'] = Modules::run('employees/employee_other_details', ['employee_id'=>$id]);
		$data['otherDetailForm'] = Modules::run('employees/edit_employee_other_details', ['employee_id'=>$id]);

		/*Followup module starts*/
		/*$followupListData = ['condition'=>['follow_ups.type_id'=>$id, 'follow_ups.type'=>'employees'], 'module'=>'employees'];
		$data['followupList'] = Modules::run('follow_ups/admin_followup_listing', $followupListData);

		$this->follow_ups_model->set_table('follow_ups');
		$followupData = ['url'=>custom_constants::edit_enquiry_url.'/'.$id.'?tab=followup', 'module'=>'employees', 'user_id'=>$id, 'type'=>'employees', 'user_detail'=>$data['employees']];
		if($this->input->get('followup_id')) { 
			$followupEditData = $this->follow_ups_model->get_where($this->input->get('followup_id'));
			$followupData['follow_ups'] = $followupEditData->row_array();
			$data['followupForm'] = Modules::run("follow_ups/admin_edit_form/".$this->input->get('followup_id'), $followupData);
		}else { 
			$data['followupForm'] = Modules::run("follow_ups/admin_add_form", $followupData);
		}*/

		/*Meeting module starts*/
		/*$meetingListData = ['condition'=>['meetings.type_id'=>$id, 'meetings.type'=>'employees'], 'module'=>'employees'];
		$data['meetingList'] = Modules::run("meetings/admin_meeting_listing", $meetingListData);
		$this->meetings_model->set_table('meetings');
		$meetingData = ['url'=>custom_constants::edit_enquiry_url.'/'.$id.'?tab=meeting', 'module'=>'employees', 'user_id'=>$id, 'type'=>'employees', 'user_detail'=>$data['employees']];
		if($this->input->get('meeting_id')) { 
			$meetingEditData = $this->meetings_model->get_where($this->input->get('meeting_id'));
			$meetingData['meeting'] = $meetingEditData->row_array();
			$data['meetingForm'] = Modules::run("meetings/admin_edit_form", $meetingData);
		}else {
			$data['meetingForm'] = Modules::run("meetings/admin_add_form", $meetingData);
		}*/

		/*Login Module Starts*/
		//$this->employees_model->set_table('login');
		/*$loginData = ['url'=>custom_constants::edit_employee_url.'/'.$id, 'module'=>'employees', 'employee_id'=>$id, 'account_type'=>'employees', 'user_detail'=>$data['employees']];
		$this->mdl_login->set_table("user_roles");
		$userRoleCondition['user_id'] = $id;
		$userRoleCondition['role_id'] = 4; 
		$loginData['user'] = $this->mdl_login->get_user_details($userRoleCondition);
		//print_r($loginData['user']);exit;
		$this->mdl_login->set_table("login");
		$loginCondition['id'] = $loginData['user']['login_id'];
		//print_r($loginCondition);exit;
		$loginData['login'] = $this->mdl_login->get_user_details($loginCondition);
		$loginData['id'] = $_GET['login_id'] = $loginData['login']['id'];
		$data['login'] = Modules::run("login/view_edit_login", $loginData);*///exit;
		/*echo '<pre>';
		print_r($loginData['login']);exit;*/
		/*if($this->input->get('login_id')) {  echo "hii";
			$loginEditData = $this->employees_model->get_where($this->input->get('login_id'));
			$loginData['login'] = $loginEditData->row_array();*/
			/*echo '<pre>';
			print_r($loginData);exit;*/

		/*}else { 
			$data['login'] = Modules::run('login/edit_login_details', $loginData);
 		}*/

 		$data['id'] = $id;

		echo Modules::run("templates/admin_template", $data);
	}

	function employee_details($id) {
		$this->pktdblib->set_table('employees');
		$employeeDetails = $this->pktdblib->get_where($id);
		//print_r($employeeDetails);
		return $employeeDetails;
		
	}


	function _check_related_modules($moduleName = 'employees') {
		$data['content'] = [$moduleName=>custom_constants::edit_employee_url, 'address'=>custom_constants::user_address_url, 'login'=>custom_constants::register_url];
		return $data;
	}

	function create_code($empId) {
		$companyId = '';
		$this->pktdblib->set_table("companies");
		$companyDetails = $this->pktdblib->get_where(1);
		//$empCode = Modules::run("companies/company_details/1");
		//$empCode = 'MISS';
		$empCode = $companyDetails['short_code']."/EP/";
		//print_r($companyDetails['short_code']."/"."Driver");exit;
		if($empId>0 && $empId<=9)
			$empCode .= '000000';
			
		elseif($empId>=10 && $empId<=99)
			$empCode .= '00000';
		elseif($empId>=100 && $empId<=999)
			$empCode .= '0000';
		elseif($empId>=1000 && $empId<=9999)
			$empCode .= '000';
		elseif($empId>=10000 && $empId<=99999)
			$empCode .= '00';
		elseif($empId>=100000 && $empId<=999999)
			$empCode .= '0';

		$empCode .= $empId;


		//echo "reached in create emp code method"; print_r($empCode);exit;
		return $empCode;
	}

	function admin_view($id=NULL) {
		if(NULL==$id) {
			redirect('employees');
		}
		$this->employees_model->set_table('employees');
		$employee = $this->employees_model->get_where($id);
		$data['user'] = $employee->row_array();
		$data['content'] = 'employees/admin_view';
		$data['meta_title'] = 'Employees';
		$data['meta_description'] = 'Employees';
		/*Address list Code*/
		$addressListData = ['condition'=>['address.user_id'=>$id, 'address.type'=>'employees'], 'module'=>'employees'];
		$data['addressList'] = Modules::run("address/admin_address_listing", $addressListData);
		/*Bank Account List code*/
		$bankAccountListData = ['condition'=>['bank_accounts.user_id'=>$id, 'bank_accounts.user_type'=>'employees'], 'module'=>'employees'];
		//$this->address_model->set_table('address');
		$data['bankAccountList'] = Modules::run("bank_accounts/admin_index_listing", $bankAccountListData);

		/*Documents*/
		$documentListData = ['condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'employees'], 'module'=>'employees'];
		$data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

		$data['otherDetailsList'] = Modules::run('employees/employee_other_details', ['employee_id'=>$id]);

		/*Documents*/
		/*$tripListData = ['condition'=>['user_trips.user_id'=>$id, 'user_trips.is_active'=>true], 'module'=>'employees'];
		$data['tripList'] = Modules::run("user_trips/trip_listing", $tripListData);*/

		echo Modules::run("templates/admin_template", $data);
	}

	function edit_employee_other_details($data = []) {
		$this->employees_model->set_table('employees_salaries');
		if($_SERVER['REQUEST_METHOD']=='POST') {
			$data['values_posted'] = $_POST;
			$this->form_validation->set_rules('data[employees_salaries][employee_id]', 'Employee', 'required');
			$this->form_validation->set_rules('data[employees_salaries][employment_start_date]', 'Start Date', 'required');
			
			$this->form_validation->set_rules('data[employees_salaries][salary]', 'Salary', 'required|numeric');
			$this->form_validation->set_rules('data[employees_salaries][provident_fund]', 'Provident Fund', 'required|numeric');
			$this->form_validation->set_rules('data[employees_salaries][esic]', 'ESIC', 'required|numeric');
			$this->form_validation->set_rules('data[employees_salaries][professional_tax]', 'Professional Tax', 'required|numeric');
			
			if($this->form_validation->run('employees_salaries') !== FALSE) {
				
				$post_data = $this->input->post('data[employees_salaries]');
				$post_data['employment_start_date'] = date('Y-m-d 00:00:00', strtotime($post_data['employment_start_date']));
				if(!empty($post_data['employment_end_date'])) {
					$endDate = explode('/', $post_data['employment_end_date']);
					$post_data['employment_end_date'] = $endDate[2]."-".$endDate[1]."-".$endDate[0];
				}

				if(!isset($post_data['is_active']))
					$post_data['is_active'] = false;
				$this->employees_model->set_table('employees_salaries');
				$ins = false;
				if(!empty($post_data['id'])) {
					$ins = $this->employees_model->update_other_details($post_data, ['id'=>$post_data['id']]);
				}else {
					$ins = $this->employees_model->insert_other_details($post_data);
				}
				if(!$ins) {
					$msg = array('message' => 'Some Error Occurred','class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('message', $msg);
				}else {
					$msg = array('message' => 'Data Entered Successfully','class' => 'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
				}
			}else {
				$msg = array('message' => 'Some Error Occurred'.validation_errors(),'class' => 'alert alert-danger fade in');
                $this->session->set_flashdata('message',$msg);
			}

			redirect(custom_constants::edit_employee_url ."/".$data['values_posted']['data']['employees_salaries']['employee_id'].'?tab=other');
		}else {
			$data['values_posted']['data']['employees_salaries'] = $this->employees_model->get_employee_active_other_details($data['employee_id']); 
		}
		
		$data['employeeOtherDetails'] = $this->employees_model->get_employee_active_other_details(isset($data['employee_id'])?$data['employee_id']:$data['values_posted']['data']['employees_salaries']['employee_id']);
		$this->load->view('employees/edit_employee_other_details', $data);
	}

	function employee_other_details($data = [])
	{
		$this->employees_model->set_table('employees_salaries');
		$data['otherDetails'] = $this->employees_model->get_employee_other_details($data['employee_id']);
		$this->load->view('employees/employee_other_details', $data);
	}

	function employee_dropdown_list() {
		$this->employees_model->set_table('employees');
		$employees = $this->employees_model->get_employees_details('first_name asc');
		$employeeList = [''=>'Select'];
		foreach ($employees as $key => $employee) {
			$employeeList[$employee['id']] = $employee['first_name']." ".$employee['middle_name']." ".$employee['surname']." | ".$employee['emp_code'];
		}

		return $employeeList;
	}

	function employee_list() {
		$this->employees_model->set_table('employees');
		$employees = $this->employees_model->employee_list('first_name asc');
		return $employees;
	}


	function admin_add_staff() {
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[employees][first_name]', 'first name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[employees][surname]', 'surname', 'required|max_length[255]|min_length[2]');
			$this->form_validation->set_rules('data[employees][dob]', 'dob', 'required');
			$this->form_validation->set_rules('data[employees][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][contact_2]', 'contact_2', 'max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][emp_code]', 'Employee Code', 'max_length[255]|is_unique[employees.emp_code]');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				if(!empty($_FILES['profile_img']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'../content/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					if(empty($profileImg['error'])) {
						$_POST['data']['employees']['profile_img'] = $profileImg['filename'];
					}
					else {
						$error['profile_img'] = $profileImg['error'];
					}
				}else {
					$_POST['data']['employees']['profile_img'] = '';
				}

				if(empty($error)) {
					$post_data = $_POST['data']['employees'];
					$post_data['dob'] = $this->pktlib->dmYtoYmd($post_data['dob']);
					$reg_user = json_decode($this->_register_admin_add($post_data), true);
					
					if($reg_user['status'] === "success")
					{
						// Successfully registered
						$msg = array('message'=>'Employee Added Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::edit_other_staff_url."/".$reg_user['id']);
					}
					else
					{
						// Registration error
						$data['form_error'] = $reg_user['msg'];
					}
				}else {
					$msg = array('message'=>'Failed to Upload Image. Error: '.$error['profile_img'], 'class'=>'alert alert-danger');
	                $this->session->set_flashdata('message',$msg);
				}
			}
		}
		
		$blood_groups = $this->employees_model->get_dropdown_list();
		$data['option']['blood_group'][NULL] = 'Select Blood Group'; 
		foreach($blood_groups as $blood_groupKey => $blood_group) {
			$data['option']['blood_group'][$blood_group['id']] = $blood_group['blood_group'];
		}

		$cities = $this->cities_model->get_dropdown_list();
		foreach ($cities as $cityKey => $city) {
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}

		$countries =$this->countries_model->get_dropdown_list();
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}

		$data['areas'] = $this->areas_model->get_list();
		$data['states'] = $this->states_model->get_list();
		$data['countries'] = $this->countries_model->get_list();
		$data['meta_title'] = "New User";
		$data['meta_description'] = "New user registration";
		$data['modules'][] = "employees";
		$data['methods'][] = "admin_other_staff_register";
		echo Modules::run("templates/admin_template", $data);
	}


	function admin_other_staff_register() {
		$this->load->view("employees/admin_add_other_staff");
	}

	function admin_edit_staff($id = null) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[employees][first_name]', 'first name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[employees][surname]', 'surname', 'required|max_length[255]|min_length[2]');
			$this->form_validation->set_rules('data[employees][dob]', 'dob', 'required');
			$this->form_validation->set_rules('data[employees][start_date]', 'start_date', 'required');
			$this->form_validation->set_rules('data[employees][contact_1]', 'contact_1', 'required|max_length[12]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][contact_2]', 'contact_2', 'max_length[12]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][primary_email]', 'Primary email', 'max_length[255]|valid_email');
			$this->form_validation->set_rules('data[employees][secondary_email]', 'secondary email', 'max_length[255]|valid_email');
			if($this->form_validation->run('employees') !== FALSE) {
				$profileImg = '';
				$post_data = $_POST['data']['employees'];
				
				if(!empty($_FILES['profile_img']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'../content/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					if(empty($profileImg['error'])) {
						$post_data['profile_img'] = $profileImg['filename'];
						unset($post_data['profile_img2']);
					}
					else {
						$error['profile_img'] = $profileImg['error'];
					}
				}else {
					$post_data['profile_img'] = $post_data['profile_img2'];
					unset($post_data['profile_img2']);
				}
				
				if(empty($error)) {
					$post_data['dob'] = DateTime::createFromFormat('d/m/Y', $post_data['dob']);
					$post_data['dob'] = $post_data['dob']->format('Y-m-d');
					$post_data['start_date'] = DateTime::createFromFormat('d/m/Y', $post_data['start_date']);
					$post_data['start_date'] = $post_data['start_date']->format('Y-m-d');
					if($this->edit_employee($id,$post_data)) {
						
                        $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                             $this->session->set_flashdata('message',$msg);
                        }
                        else {
                            $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                            $this->session->set_flashdata('message',$msg);
                        }
                        redirect(custom_constants::edit_other_staff_url ."/".$id.'?tab=address');
                    }
                    else {
                        $msg = array('message' => $error,'class' => 'alert alert-danger fade in');
                        $this->session->set_flashdata('error', $msg);
                    }
				}else {
					$msg = array('message' => 'Some Validation Error Occurred'.validation_errors(),'class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('error', $msg);
				}

		}
		else {
			$this->employees_model->set_table("employees");
			$data['employees'] = $this->employee_details($id);
			$data['values_posted']['employees'] = $data['employees'];
		}
		$data['id'] = $id;
		if(!($this->input->get('tab')))
			$data['tab'] = 'personal_info';
		else
			$data['tab'] = $this->input->get('tab');

		$blood_groups = $this->employees_model->get_dropdown_list();
		
		foreach($blood_groups as $blood_groupKey => $blood_group) {
			$data['option']['blood_group'][$blood_group['blood_group']] = $blood_group['blood_group'];
		}
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
		$data['meta_title'] = 'edit employees';
		$data['meta_description'] = 'edit employees';
		
		$data['content'] = 'employees/admin_edit_other_staff';

		$AddressListData = ['condition'=>['address.user_id'=>$id, 'address.type'=>'employees'], 'module'=>'employees'];
		$data['addressList'] = Modules::run("address/address_listing", $AddressListData);

		$this->address_model->set_table('address');
		$addressData = ['url'=>custom_constants::edit_other_staff_url.'/'.$id.'?tab=address', 'module'=>'employees', 'user_id'=>$id, 'type'=>'employees', 'user_detail'=>$data['employees']];
		if($this->input->get('address_id')) { 
			$addressEditData = $this->address_model->get_where($this->input->get('address_id'));
			$addressData['address'] = $addressEditData->row_array();
			$data['address'] = Modules::run("address/view_address_edit_form", $addressData);
		}else {
			$data['address'] = Modules::run("address/view_address_form", $addressData);
		}
		/* Bank Account Related Code Starts Here  */
		$bankAccountListData = ['condition'=>['bank_accounts.user_id'=>$id, 'bank_accounts.user_type'=>'employees'], 'module'=>'employees'];
		$this->address_model->set_table('bank_accounts');
		$data['bankAccountList'] = Modules::run("bank_accounts/account_listing", $bankAccountListData);

		$bankAccountData = ['url'=>custom_constants::edit_other_staff_url.'/'.$id.'?tab=bank_account', 'module'=>'employees', 'user_id'=>$id, 'type'=>'employees', 'user_detail'=>$data['employees']];
		if($this->input->get('bank_account_id')) { 
			$bankAccountData['bank_accounts'] = Modules::run("bank_accounts/account_details", $this->input->get('bank_account_id'));
			$data['bank_account'] = Modules::run("bank_accounts/admin_edit", $bankAccountData);
		}else {
			$data['bank_account'] = Modules::run("bank_accounts/admin_add", $bankAccountData);
		}
		/*Bank account ends*/

		/*Document Uploads*/
		$documentListData = ['condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'employees'], 'module'=>'employees'];
		//$this->address_model->set_table('bank_accounts');
		$data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);
		//print_r($data['documentList']);exit;

		$documentData = ['url'=>custom_constants::edit_other_staff_url.'/'.$id.'?tab=document', 'module'=>'employees', 'user_id'=>$id, 'type'=>'employees', 'user_detail'=>$data['employees']];
		
		$data['document'] = Modules::run("upload_documents/admin_add", $documentData);
		
		/* Other Employee Details */
		$data['otherDetailsList'] = Modules::run('employees/employee_other_details', ['employee_id'=>$id]);
		$data['otherDetailForm'] = Modules::run('employees/edit_employee_other_details', ['employee_id'=>$id]);

		/*Followup module starts*/
		/*$followupListData = ['condition'=>['follow_ups.type_id'=>$id, 'follow_ups.type'=>'employees'], 'module'=>'employees'];
		$data['followupList'] = Modules::run('follow_ups/admin_followup_listing', $followupListData);

		$this->follow_ups_model->set_table('follow_ups');
		$followupData = ['url'=>custom_constants::edit_other_staff_url.'/'.$id.'?tab=followup', 'module'=>'employees', 'user_id'=>$id, 'type'=>'employees', 'user_detail'=>$data['employees']];
		if($this->input->get('followup_id')) { 
			$followupEditData = $this->follow_ups_model->get_where($this->input->get('followup_id'));
			$followupData['follow_ups'] = $followupEditData->row_array();
			$data['followupForm'] = Modules::run("follow_ups/admin_edit_form/".$this->input->get('followup_id'), $followupData);
		}else { 
			$data['followupForm'] = Modules::run("follow_ups/admin_add_form", $followupData);
		}*/

		/*Meeting module starts*/
		/*$meetingListData = ['condition'=>['meetings.type_id'=>$id, 'meetings.type'=>'employees'], 'module'=>'employees'];
		$data['meetingList'] = Modules::run("meetings/admin_meeting_listing", $meetingListData);
		$this->meetings_model->set_table('meetings');
		$meetingData = ['url'=>custom_constants::edit_other_staff_url.'/'.$id.'/'.$id.'?tab=meeting', 'module'=>'employees', 'user_id'=>$id, 'type'=>'employees', 'user_detail'=>$data['employees']];
		if($this->input->get('meeting_id')) { 
			$meetingEditData = $this->meetings_model->get_where($this->input->get('meeting_id'));
			$meetingData['meeting'] = $meetingEditData->row_array();
			$data['meetingForm'] = Modules::run("meetings/admin_edit_form", $meetingData);
		}else {
			$data['meetingForm'] = Modules::run("meetings/admin_add_form", $meetingData);
		}*/

		echo Modules::run("templates/admin_template", $data);
	}

	function login_details($id) {
		$this->employees_model->set_table('login');
		$loginDetails = $this->employees_model->get_where_custom('employee_id',$id);
		return $loginDetails->row_array();
		
	}
	



}