<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_accounts extends MY_Controller {
	function __construct() {
		parent::__construct();
		check_user_login(FALSE);
		$this->load->model('bank_accounts/bank_accounts_model');
		$setup = $this->setup();
	}

	function setup(){
        $bankAccount = $this->bank_accounts_model->tbl_bank_accounts();
        return TRUE;
    }

	function admin_index() {
		$data['meta_title'] = 'Bank Accounts';
		$data['meta_description'] = 'Bank Accounts';
		$data['modules'][] = 'bank_accounts';
		$data['methods'][] = 'admin_index_listing';
		
		echo Modules::run("templates/admin_template", $data); 	
	}

	function admin_index_listing($data = []) {
		//print_r($data);
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];

		//$this->pktdblib->set_table('bank_accounts');
		$data['accounts'] = $this->pktdblib->createquery(['conditions'=>$condition, 'table'=>'bank_accounts']);
		//print_r($data);exit;
		$this->load->view("bank_accounts/admin_index", $data);
	}

	function admin_add($data = []) {
		//print_r($data);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$this->form_validation->set_rules('data[bank_accounts][user_type]', 'user type', 'required|max_length[20]');
			$this->form_validation->set_rules('data[bank_accounts][user_id]', 'user id', 'required');
			$this->form_validation->set_rules('data[bank_accounts][bank_name]', 'bank name', 'required');
			$this->form_validation->set_rules('data[bank_accounts][account_type]', 'account type', 'required');
			$this->form_validation->set_rules('data[bank_accounts][account_number]', 'account number', 'required|is_unique[bank_accounts.account_number]');
			$this->form_validation->set_rules('data[bank_accounts][ifsc_code]', 'ifsc code', 'required');
			$this->form_validation->set_rules('data[bank_accounts][branch]', 'branch', 'required');
			/*echo '<pre>';
			echo $this->form_validation->run('bank_accounts')."<br>";
			print_r($_POST);//exit;
			print_r(validation_errors());exit;*/
			if($this->form_validation->run('bank_accounts')!==FALSE)
			{
				$_POST['data']['bank_accounts']['is_default'] = (null!=$this->input->post('data[bank_accounts][is_default]'))?true:false;
				$_POST['data']['bank_accounts']['is_active'] = (null!=$this->input->post('data[bank_accounts][is_active]'))?true:false;

				$post_data = $_POST;
				
				$user_account = json_decode($this->register_bank_account($post_data['data']['bank_accounts']), true);
	            $redirectUrl = custom_constants::new_bank_account_url;
				if($user_account['status'] === "success")
				{ 
	                $redirectUrl = custom_constants::edit_bank_account_url.'/'.$user_account['id'];
	                if($this->input->post('module')!='bank_accounts'){
	                	$redirectUrl = $this->input->post('url')/*.'&address_id='.$user_address['id']*/;
	                }
					$msg = array('message' => 'Bank Account Added Successfully','class' => 'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
				}
				else
				{
					$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
					//$data['form_error'] = $reg_user['msg'];
				}
				redirect($redirectUrl);
			}else{
				$msg = array('message' => validation_errors(), 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
			}
			//print_r(validation_errors());exit;
		}
		
		
		
		//$data['areas'] = $this->areas_model->get_list();
		//$data['states'] = $this->states_model->get_list();
		//$data['countries'] = $this->countries_model->get_list();
		$data['meta_title'] = "Bank Accounts";
		$data['meta_description'] = "New Bank Account";
		$data['modules'][] = "bank_accounts";
		$data['methods'][] = "admin_add_form";
		/*echo '<pre>';
		print_r($data);exit;*/
		echo Modules::run("templates/admin_template", $data);
	}

	function register_bank_account($data) { 
		//print_r($data);exit;
		//echo "hello";exit;
		/*$data = $_POST;*/
		//print_r($_POST);exit();
		$insert_data = $data;
		//print_r($insert_data);exit;
		$this->pktdblib->set_table("bank_accounts");
		$id = $this->pktdblib->_insert($insert_data);
		return json_encode(["msg"=>"Account Added Successfully", "status"=>"success", 'id'=>$id]);
	}

	function account_details($id) {
		$this->pktdblib->set_table('bank_accounts');
		$accountDetails = $this->pktdblib->get_where($id);
		/*echo '<pre>';
		print_r($accountDetails);exit;*/
		return $accountDetails;
	}

	function admin_edit($id = null){
		//print_r($data);
		//echo "hello";
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
			//echo "hello";
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[bank_accounts][user_id]', 'user id', 'required|max_length[60]');
			$this->form_validation->set_rules('data[bank_accounts][user_type]', 'user type', 'required|max_length[60]');
			$this->form_validation->set_rules('data[bank_accounts][bank_name]', 'bank name', 'required');
			$this->form_validation->set_rules('data[bank_accounts][account_type]', 'account type', 'required');
			$this->form_validation->set_rules('data[bank_accounts][account_number]', 'account number', 'required');
			$this->form_validation->set_rules('data[bank_accounts][ifsc_code]', 'ifsc code', 'required');
			$this->form_validation->set_rules('data[bank_accounts][branch]', 'branch', 'required');
			if($this->form_validation->run('bank_accounts') !== FALSE) {
				$_POST['data']['bank_accounts']['is_active'] = (null !== $this->input->post('data[bank_accounts][is_active]'))?true:false;
				$_POST['data']['bank_accounts']['is_default'] = (null !== $this->input->post('data[bank_accounts][is_default]'))?true:false;
				$post_data = $this->input->post('data[bank_accounts]');
			}else{
				$error[] = validation_errors();
				//print_r(validation_errors());
			}

			//print_r($post_data);exit;
			if(empty($error)) { //echo "hii"; exit;
				$this->pktdblib->set_table('bank_accounts');
				if($this->pktdblib->_update($id,$post_data)) {
						
                    $msg = array('message' => 'Bank Account Details Updated Successfully  ','class' => 'alert alert-success fade in');
                         $this->session->set_flashdata('message',$msg);
                }
                else {
				//echo "string";exit;
                     $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                     $this->session->set_flashdata('message',$msg);
                }
                   // $this->session->keep_flashdata('message',$msg);
                    //redirect(custom_constants::update_user_url ."/".$id);
                    redirect($this->input->post('url'));

            }
            else{
                $msg = array('message' => $error,'class' => 'alert alert-danger fade in');
                $this->session->set_flashdata('message', $msg);
                redirect('bank_accounts/edit_account/'.$id);
            }
		}else {
			$data['bank_accounts'] = $this->account_details($id);
			$data['values_posted']['bank_accounts'] = $data['bank_accounts'];
		}
		$data['id'] = $id;
		$data['meta_title'] = 'Bank Accounts';
		$data['meta_description'] = 'Bank Accounts';
		$data['modules'][] = 'bank_accounts';
		$data['methods'][] = 'admin_edit_form';
		echo Modules::run("templates/admin_template", $data);
	}


	function admin_add_form($data = []) {
		$data['values_posted'] = [];
		if(NULL !== $this->input->post('data'))
			$data['values_posted'] = $_POST;

		//print_r($data);exit;
		//print_r($data['values_posted']);
		if(!isset($data['type'])){
			$data['type'] = '';//variable is passed through other modules
			$data['user_id'] = '';
		}
		
		$data['users']  = [];

		/*if(isset($data['module']) && $data['module']=='employees'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['emp_code']];
			$data['option']['typeLists'] = ['employees'=>'employees'];

		}elseif(isset($data['module']) &&  ($data['module']=='customers' || $data['module']=='customers_v2')){
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
		
		$data['option']['accountTypes'] = [''=>'Account Type?', 'Saving Account'=>'Saving Account', 'Current Account'=>'Current Account'];
		/*echo '<pre>';
		print_r($data);exit;*/

		/*if(isset($_POST['data']))
			$data['values_posted'] = $_POST['data'];*/
		$this->load->view('bank_accounts/admin_add', $data);
	}

	function admin_edit_form($data = []) {
		//echo $this->uri->segment(3);exit;
		if(empty($data)){
			$data['bank_accounts'] = $this->account_details($this->uri->segment(3));
		}

		$data['values_posted']['data']['bank_accounts'] = $data['bank_accounts'];

		if(!isset($data['type'])){
			$data['type'] = '';//variable is passed through other modules
			$data['user_id'] = '';
		}
		
		$data['users']  = [];
		
		/*if(isset($data['module']) && $data['module']=='employees'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['emp_code']];
			$data['option']['typeLists'] = ['employees'=>'employees'];

		}elseif(isset($data['module']) &&  ($data['module']=='customers' || $data['module']=='customers_v2')){
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
		//print_r($data['users']);exit;
		/*echo '<pre>';
		print_r($data);exit;*/
		//$data['option']['typeLists'] = [''=>'Address belongs to?', 'employees'=>'Employee', 'customers'=>'Customer', 'suppliers'=>'Supplier'];
		$data['option']['accountTypes'] = [''=>'Account Type?', 'Saving Account'=>'Saving Account', 'Current Account'=>'Current Account'];
		$this->load->view('bank_accounts/admin_edit', $data);
	}

	function view_user_wise_accounts($data = NULL) {
		if(NULL == $data){
			show_404();
		}
		$this->load->view('address/admin_account_listing', $data);;
	}

	function type_wise_user(){
		//print_r($_POST);exit;
		//$_POST['params'] = "employees";
		
		if(!$this->input->post('params'))
			return;

		$addressType = $this->input->post('params');
		
		//echo json_encode($condition);exit;
		//$this->bank_accounts_model->set_table('address');
		$typeWiseUsers = $this->bank_accounts_model->get_custom_account_type_users($addressType);
		//print_r($typeWiseUsers);exit;
		$userList = [0=>['id'=>0, 'text'=>'Select User']];
		foreach ($typeWiseUsers as $key => $typeWiseUser) {
			$userList[$key+1]['id'] = $typeWiseUser['id'];
			$userList[$key+1]['text'] = $typeWiseUser['fullname']." | ".$typeWiseUser['emp_code'];
			//$userList[$key+1]['data'] =  '<span><img src="'.base_url().'assets/uploads/profile_images/'.$typeWiseUser['profile_img'].'" class="img-flag"></span>';
		}
		/*echo '<pre>';
		print_r($userList);
		exit;*/
		echo json_encode($userList);
		//print_r($stateList);exit;
		exit;

	}

	function add_form() {
		//echo "reached here";
		$data['values_posted'] = [];
		if(NULL !== $this->input->post('data'))
			$data['values_posted'] = $_POST;
		
		$data['option']['accountTypes'] = [''=>'Account Type?', 'Saving Account'=>'Saving Account', 'Current Account'=>'Current Account'];
		
		$this->load->view('bank_accounts/add', $data);
	}

	function add($data = []) {
		//print_r($data);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $redirectUrl = 'bank_accounts/add';
            if($this->input->post('module')!='bank_accounts'){
            	$redirectUrl = $this->input->post('url')/*.'&address_id='.$user_address['id']*/;
            }
			/*$this->form_validation->set_rules('data[bank_accounts][user_type]', 'user type', 'required|max_length[20]');
			$this->form_validation->set_rules('data[bank_accounts][user_id]', 'user id', 'required');*/
			$this->form_validation->set_rules('data[bank_accounts][bank_name]', 'bank name', 'required');
			$this->form_validation->set_rules('data[bank_accounts][account_type]', 'account type', 'required');
			$this->form_validation->set_rules('data[bank_accounts][account_number]', 'account number', 'required|is_unique[bank_accounts.account_number]');
			$this->form_validation->set_rules('data[bank_accounts][ifsc_code]', 'ifsc code', 'required');
			$this->form_validation->set_rules('data[bank_accounts][branch]', 'branch', 'required');
			
			if($this->form_validation->run('bank_accounts')!==FALSE)
			{
				
				$post_data = $_POST['data']['bank_accounts'];
				$post_data['user_id'] = $this->session->userdata('employee_id');
				$post_data['created'] = date('Y-m-d H:i:s');
				$post_data['modified'] = date('Y-m-d H:i:s');
				$post_data['user_type'] = 'customers';
				$user_account = json_decode($this->register_bank_account($post_data), true);
				//print_r($user_account);exit;
				if($user_account['status'] === "success")
				{ 
	                if($this->input->post('module')!='bank_accounts'){
		            	$redirectUrl = $this->input->post('url')/*.'&address_id='.$user_address['id']*/;
		            }
					$msg = array('message' => 'Bank Account Added Successfully','class' => 'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
				}
				else
				{
					$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
					
				}
				
			}else{
				$msg = array('message' => validation_errors(), 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
			}
				redirect($redirectUrl);

			//print_r(validation_errors());exit;
		}
		
		$data['meta_title'] = "Bank Accounts";
		$data['meta_description'] = "New Bank Account";
		$data['modules'][] = "bank_accounts";
		$data['methods'][] = "add_form";
		
		echo Modules::run("templates/admin_template", $data);
	}

	function edit_form($data = []) {
		//echo $this->uri->segment(3);exit;
		if(empty($data)){
			$data['bank_accounts'] = $this->account_details($this->uri->segment(3));
		}

		$data['values_posted']['data']['bank_accounts'] = $data['bank_accounts'];

		$data['option']['accountTypes'] = [''=>'Account Type?', 'Saving Account'=>'Saving Account', 'Current Account'=>'Current Account'];
		$this->load->view('bank_accounts/edit', $data);
	}


	function edit($id = null){
		//print_r($data);
		//echo "hello";
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
			//echo "hello";
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			/*$this->form_validation->set_rules('data[bank_accounts][user_id]', 'user id', 'required|max_length[60]');
			$this->form_validation->set_rules('data[bank_accounts][user_type]', 'user type', 'required|max_length[60]');*/
			$this->form_validation->set_rules('data[bank_accounts][bank_name]', 'bank name', 'required');
			$this->form_validation->set_rules('data[bank_accounts][account_type]', 'account type', 'required');
			$this->form_validation->set_rules('data[bank_accounts][account_number]', 'account number', 'required');
			$this->form_validation->set_rules('data[bank_accounts][ifsc_code]', 'ifsc code', 'required');
			$this->form_validation->set_rules('data[bank_accounts][branch]', 'branch', 'required');
			if($this->form_validation->run('bank_accounts') !== FALSE) {
				$_POST['data']['bank_accounts']['is_active'] = true;//(null !== $this->input->post('data[bank_accounts][is_active]'))?true:false;
				$_POST['data']['bank_accounts']['is_default'] = true; //(null !== $this->input->post('data[bank_accounts][is_default]'))?true:false;
				$post_data = $this->input->post('data[bank_accounts]');
			}else{
				$error[] = validation_errors();
				//print_r(validation_errors());
			}

			if(empty($error)) { //echo "hii"; exit;
				$this->pktdblib->set_table('bank_accounts');
				if($this->pktdblib->_update($id,$post_data)) {
						
                    $msg = array('message' => 'Bank Account Details Updated Successfully  ','class' => 'alert alert-success fade in');
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
                redirect('bank_accounts/edit/'.$id);
            }
		}
		else {
			//$this->bank_accounts_model->set_table("bank_accounts");
			$data['bank_accounts'] = $this->account_details($id);
			//print_r($data['address']);exit;
			$data['values_posted']['bank_accounts'] = $data['bank_accounts'];
		}
		$data['id'] = $id;
		$data['meta_title'] = 'Bannk Accounts';
		$data['meta_description'] = 'Bank Accounts';
		$data['modules'][] = 'bank_accounts';
		$data['methods'][] = 'edit_form';
		echo Modules::run("templates/oxiinc_template", $data);
	}

}