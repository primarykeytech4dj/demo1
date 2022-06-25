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
 
 
class Customers extends MY_Controller {

	function __construct() {
		parent::__construct();
		//echo "reached here";exit;
		// Check login and make sure email has been verified
		foreach(custom_constants::$protected_pages as $page)
        {   
            if(strpos($this->uri->uri_string, $page) === 0)
            {   
                check_user_login(FALSE);
            }
        }
		//$this->load->model("customer/mdl_admin_customer");
        $this->load->model("customers/customer_model");
        $this->load->model('address/address_model');
        $this->load->model('cities/cities_model');
        $this->load->model('countries/countries_model');
        $this->load->model('states/states_model');
        $this->load->model('areas/areas_model');
        $this->load->model('login/mdl_login');
        $this->load->library('ajax_pagination');
		//$this->load->library('mlm_lib');
        //$this->customer_model->set_table('customers');
        $setup = $this->setup();

	}

    function setup(){
        $customers = $this->customer_model->tbl_customers();
        
        return TRUE;
    }

    function admin_index() {
        $this->pktdblib->set_table('customers');
        $customers = $this->pktdblib->get('id desc');
        $data['customers'] = $customers->result_array();
        
        $this->pktdblib->set_table('login');
        foreach ($data['customers'] as $key => $customer) {
            $login = $this->pktdblib->get_where_custom('username', $customer['emp_code']);
            if(!empty($login)){
                $login = $login->row_array();
                //print_r($login->row_array());
                $data['customers'][$key]['login_id'] = $login['id'];
            }

        }
        
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Customers";
        $data['meta_description'] = "Customer panel";
        
        $data['modules'][] = "customers";
        $data['methods'][] = "admin_customer_listing";
        
        echo Modules::run("templates/admin_template", $data);
    }

	function admin_customer_listing() {
		$this->load->view("customers/admin_index");
		//$this->load->view("admin_panel/login_register");
	}

    function admin_add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST['data'];
            $this->form_validation->set_rules('data[customers][first_name]', 'first name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customers][company_name]', 'company name', 'required|max_length[255]');
             $this->form_validation->set_rules('data[customers][primary_email]', 'primary email', 'max_length[255]|valid_email|is_unique[login.email]');
            $this->form_validation->set_rules('data[customers][contact_1]', 'contact 1', 'required|max_length[15]|min_length[10]|numeric');
            $this->form_validation->set_rules('data[customers][contact_2]', 'contact 2', 'max_length[15]|min_length[10]|numeric');
            if($_SESSION['application']['multiple_company']){
                $this->form_validation->set_rules('data[companies_customers][company_id]', 'Company', 'required');
            }
            if($this->form_validation->run()!== FALSE) { //echo "hii";
                $error = [];
                //print_r($_FILES);exit;
                $postData = $this->input->post('data[customers]');
                $profileImg = '';
                if(!empty($_FILES['profile_img']['name'])) {
                    $profileFileValidationParams = ['file' => $_FILES['profile_img'], 'path'=> '../content/uploads/profile_images', 'ext' => 'gif|jpg|png|jpeg', 'fieldname' =>'profile_img', 'arrindex' =>'profile_img'];
                    $profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
                    //print_r($profileImg);exit;
                    if(empty($profileImg['error'])) {
                        $postData['profile_img'] = $profileImg['filename'];
                    }
                    else {
                        $error['profile_img'] = $profileImg['error'];

                    }
                } else {
                    $postData['profile_img'] = '';
                }
                //print_r($error);exit;
                if (empty($error)) {
                    $postData['has_multiple_sites'] = isset($postData['has_multiple_sites'])?TRUE:FALSE;
                    $postData['joining_date'] = $this->pktlib->dmYtoYmd($postData['joining_date']);

                    //echo '<pre>';print_r($postData);exit;
                    $reg_customer = json_decode($this->_register_admin_add($postData), true);
                    //print_r($reg_customer);exit;

                    if($reg_customer['status'] === 'success') {

                        $postData['id'] = $reg_customer['id'];
                        if($_SESSION['application']['multiple_company']){
                            $companyCustomer=[];
                            foreach ($this->input->post("data[companies_customers][company_id]") as $key => $value) {
                                # code...
                                $companyCustomer[$key]['company_id'] = $value;
                                $companyCustomer[$key]['customer_id'] = $reg_customer['id'];
                                $companyCustomer[$key]['is_active'] = true;
                                $companyCustomer[$key]['created'] = $companyCustomer[$key]['modified'] = date('Y-m-d H:i:s');
                            }
                                //print_r($companyCustomer);exit;
                            if(!empty($companyCustomer)){
                                /*echo "hii";*/
                                $this->pktdblib->set_table("companies_customers");
                                $this->pktdblib->_insert_multiple($companyCustomer);
                            }
                        }
                        $login = Modules::run('login/register_customer_to_login', $postData);
                        $msg = array('message'=>'Login created Successfully', 'class'=>'alert alert-success');
                        $this->session->set_flashdata('message',$msg);
                       /* $msg = array('message'=> 'Data Added Successfully', 'class' => 'alert alert-success');
                        $this->session->set_flashdata('message', $msg);*/
                        //redirect(custom_constants::edit_employee_url."/".$reg_customer['id']);
                        redirect(custom_constants::edit_customer_url."/".$reg_customer['id'].'?tab=address');
                    }
                    else {
                        $data['form_error'] = $reg_customer['msg'];
                    }
                }else{
                    //print_r($error['profile_img']);
                    $msg = array('message'=> 'Error while uploading file'.$error['profile_img'], 'class' => 'alert alert-danger');
                        $this->session->set_flashdata('message', $msg);
                }


            }else{
                $msg = array('message'=> 'Error while uploading file'.validation_errors(), 'class' => 'alert alert-danger');
                $this->session->set_flashdata('message', $msg);
                //echo validation_errors();
            }
             
        }
        $this->pktdblib->set_table('companies');
        $companies = $this->pktdblib->get_where_custom('is_active', true);
        $data['companies'] = $companies->result_array();
        $data['option']['company'][0] = 'Select Company';
        foreach ($data['companies'] as $key => $company) {
            $data['option']['company'][$company['id']] = $company['company_name'];
        }
        $blood_group = $this->customer_model->get_dropdown_list();
       // print_r($blood_group);
        $data['option']['blood_group'][NULL] = "Select blood_group";
        foreach ($blood_group as $blood_groupKey => $blood_group) {
            //print_r($blood_groupKey);
            //print_r($blood_group);
            $data['option']['blood_group'][$blood_group['blood_group']] = $blood_group['blood_group'];
        }
        //print_r( $data['option']['blood_group'][$blood_group['blood_group']]);
        
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Customer Module";
        $data['meta_description'] = "New Customer";
        
        $data['modules'][] = "customers";
        $data['methods'][] = "admin_add_customer";
        
        echo Modules::run("templates/admin_template", $data);
           // }
            
            
    }

    function _register_admin_add($data) {
        
        $this->pktdblib->set_table("customers");
        if($this->pktdblib->count_where("primary_email", $data['primary_email']) > 0  && $data['primary_email'] !== NULL)
        {
            $customer = $this->pktdblib->get_where_custom('primary_email', $data['primary_email']);
            $customerDetails = $customer->row_array();
            return json_encode(["msg"=>"email is already in use", "status"=>"success", 'customers'=>$customerDetails, 'is_new'=>false]);
        }
        
        $this->pktdblib->set_table("customers");
        $id = $this->pktdblib->_insert($data);
        if(empty($data['emp_code'])) {
            $empCode = $this->create_cust_code($id['id']);
            $updArr['id'] = $id['id'];
            $updArr['emp_code'] = $empCode;
            $this->pktdblib->set_table('customers');
            $updCode = $this->edit_customer($id['id'], $updArr);
        }
        $customer = $this->get_customer_details($id['id']);
        //print_r($id);
        return json_encode(["msg" => "Data Added Successfully", "status" => "success", 'id' => $id['id'], 'customers' => $customer, 'is_new'=>true ]);
    }

    function edit_customer($id=NULL, $postData = []) {
        if(NULL == $id)
            return false;

        if($this->pktdblib->_update($id,$postData))
            return true;
        else
            return false;
    }
    

	function admin_add_customer() {
		$this->load->view("customers/admin_add");
		//$this->load->view("admin_panel/login_register");
	}

    function admin_edit($id = NULL) {

    	if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST['data'];
    		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
    		$this->form_validation->set_rules('data[customers][first_name]','first name', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[customers][surname]', 'surname', 'max_length[255]');
    		$this->form_validation->set_rules('data[customers][company_name]', 'company_name', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[customers][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
    		$this->form_validation->set_rules('data[customers][contact_2]', 'contact_2', 'max_length[15]|min_length[10]|numeric');
    		$this->form_validation->set_rules('data[customers][primary_email]', 'primary_email', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[customers][secondary_email]', 'secondary_email', 'max_length[255]');
            /*$this->form_validation->set_rules('data[customers][pan_no]', 'pan no', 'is_unique[customers.pan_no]|alpha_numeric');
            $this->form_validation->set_rules('data[customers][gst_no]', 'gst_no', 'is_unique[customers.gst_no]');*/
    		
    		if($this->form_validation->run('customers')!== FALSE) {
    			$profileImg = '';
    			$postData = $data['values_posted']['customers'];
    			if(!empty($_FILES['profile_img']['name'])) {
    				$profileFileValidationParams = ['file'=>$_FILES['profile_img'], 'path' => '../content/uploads/profile_images/', 'ext' => 'jpg|png|gif|jpeg', 'fieldname' =>'profile_img', 'arrindex' => 'profile_img'];
    				$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
    				if(empty($profileImg['error'])) {
    					$postData['profile_img'] = $profileImg['filename'];
    					unset($postData['profile_img2']);
    				}
    				else {
                        $data['values_posted']['customers']['profile_img'] = $this->input->post('data[customers][profile_img2]');
    					$error = $profileImg['error']['profile_img'];
     				}
    			}
    			else {
    				$postData['profile_img'] = $postData['profile_img2'];
    				unset($postData['profile_img2']);
    			}

    			if(empty($error)) {
    				//print_r($this->customer_model->_update_customer($id, $postData));
                    $this->pktdblib->set_table("customers");
                    $postData['has_multiple_sites'] = isset($postData['has_multiple_sites'])?TRUE:FALSE;
                    $postData['is_active'] = isset($postData['is_active'])?TRUE:FALSE;
    				if($this->edit_customer($id, $postData)) {
                        if(($_SESSION['application']['multiple_company']) && NULL!==($this->input->post("data[companies_customers][company_id]"))){
                            $this->pktdblib->set_table("companies_customers");

                            $this->pktdblib->_delete_by_column('customer_id',$id);
                            $companyCustomer = [];
                        foreach ($this->input->post("data[companies_customers][company_id]") as $key => $value) {
                            # code...
                            $companyCustomer[$key]['company_id'] = $value;
                            $companyCustomer[$key]['customer_id'] = $id;
                            $companyCustomer[$key]['is_active'] = true;
                            $companyCustomer[$key]['created'] = $companyCustomer[$key]['modified'] = date('Y-m-d H:i:s');
                            }
                            if(!empty($companyCustomer)){
                                $this->pktdblib->set_table("companies_customers");
                                $this->pktdblib->_insert_multiple($companyCustomer);
                            }
                        }else{
                            $this->pktdblib->set_table("companies_customers");
                            $this->pktdblib->_delete_by_column('company_id',$id);
                        }
    					$msg = array('message' => "data updated successfully", 'class' => 'alert alert-success fade-in');
    					$this->session->set_flashdata('message', $msg);
    				}
    				else {
    					$msg = array('message' => 'some problem occured while updating', 'class' => 'alert alert-danger fade-in');
    					$this->session->set_flashdata('message', $msg);
    				}
    				redirect(custom_constants::edit_customer_url."/".$id.'?tab=address');
    			}
    			else { //echo "reached here";

    				$msg = array('message' => $error['profile_img'], 'class' =>'alert alert-danger fade-in');
    				$this->session->set_flashdata('message', $msg);
    			}
     		} 
     		else { //echo validation_errors();
     			$msg = array('message' => 'Following validation error occured'.validation_errors(), 'class' => 'alert alert-danger fade-in');
                $this->session->set_flashdata('message', $msg);

     		}

    	}
    	else {
    		//$this->customer_model->set_table("customers");
    		$data['values_posted']['customers'] = $this->get_customer_details($id);
        }
        $data['values_posted']['customers'] = $this->get_customer_details($id);
    	$data['customers'] = $data['values_posted']['customers'];
        $this->pktdblib->set_table('companies');
        $companies = $this->pktdblib->get_where_custom('is_active', true);
        $data['companies'] = $companies->result_array();
        $data['option']['company'][0] = 'Select Company';
        foreach ($data['companies'] as $key => $company) {
            $data['option']['company'][$company['id']] = $company['company_name'];
        }

        if($_SESSION['application']['multiple_company']){
            $companyProducts = $this->pktdblib->custom_query("select company_id from companies_customers where customer_id=".$id);
            //print_r($companyProducts);exit;

            foreach ($companyProducts as $key => $company) {
                //$data['company_id'][$key] = $company['company_id'];
                $data['company_id'][] = $company['company_id'];
            }
            //print_r($data['company_id']);exit;
         }

    	$blood_group = $this->customer_model->get_dropdown_list();
    	foreach ($blood_group as $blood_groupKey => $blood_group) {
    		$data['option']['blood_group'][$blood_group['blood_group']] = $blood_group['blood_group'];
    	}

    	$data['id'] = $id;
    	if(!($this->input->get('tab')))
    		$data['tab'] = 'personal_info';
    	else
    		$data['tab'] = $this->input->get('tab');
        $data['meta_title'] = "Edit Customer";
        $data['title'] = "ERP Edit Customer";
        $data['meta_description'] = "Edit Customer";
       	$data['content'] = 'customers/admin_edit';
        $type = 'customers';
        $loginId = $id;
        $userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>'customers']);
        //echo $this->db->last_query();exit;
        //echo '<pre>';print_r($userRoles);//exit;
        if($userRoles){
            //echo "hii";exit;
            $loginId = $userRoles[0]['login_id'];
            $type = 'login';
        }
        
        //echo $loginId;echo $type;exit;
        $addressListData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=address', 'condition'=>['address.user_id'=>$loginId, 'address.type'=>$type], 'module'=>'customers'];
        //echo '<pre>';print_r($addressListData);exit;
        $data['addressList'] = Modules::run("address/admin_address_listing", $addressListData);
        //print_r($data['addressList']);exit;
        $this->pktdblib->set_table('address');
        $addressData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=address', 'module'=>'customers', 'user_id'=>$loginId, 'type'=>$type, 'user_detail'=>$data['customers']];
        if($this->input->get('address_id')) { 
            $addressData['address'] = $this->pktdblib->get_where($this->input->get('address_id'));
            $data['address'] = Modules::run("address/admin_edit_form", $addressData);
        }else {
            $data['address'] = Modules::run("address/admin_add_form", $addressData);
        }
        /* Bank Account Related Code Starts Here  */
        $bankAccountListData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=bank_account', 'condition'=>['bank_accounts.user_id'=>$loginId, 'bank_accounts.user_type'=>'login'], 'module'=>'customers'];
        $this->pktdblib->set_table('bank_accounts');
        $data['bankAccountList'] = Modules::run("bank_accounts/admin_index_listing", $bankAccountListData);
        //print_r($data['bankAccountList']);exit;

        $bankAccountData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=bank_account', 'module'=>'customers', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['customers']];
        if($this->input->get('bank_account_id')) { 
            $bankAccountData['bank_accounts'] = Modules::run("bank_accounts/account_details", $this->input->get('bank_account_id'));
            $data['bank_account'] = Modules::run("bank_accounts/admin_edit_form", $bankAccountData);
        }else {
            $data['bank_account'] = Modules::run("bank_accounts/admin_add_form", $bankAccountData);
        }
        /*Bank account ends*/

        /*Document Uploads*/
        $documentListData = [ 'condition'=>['user_documents.user_id'=>$loginId, 'user_documents.user_type'=>'login'], 'module'=>'customers'];
        //$this->address_model->set_table('bank_accounts');
        $data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

        $documentData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=document', 'module'=>'customers', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['customers']];
        
        $data['document'] = Modules::run("upload_documents/admin_add_form", $documentData);
    //echo '<pre>';print_r($data['customers']);exit;
        /*Customer Sites*/
        if($data['customers']['has_multiple_sites']){
            $siteListData = ['condition'=>['customer_sites.customer_id'=>$id, 'customer_sites.is_active'=>TRUE], 'module'=>'customers'];
            //$this->address_model->set_table('bank_accounts');
            $data['siteList'] = Modules::run("customer_sites/admin_index_listing", $siteListData);
            //print_r($data['siteList']);exit;
            $siteData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=sites', 'module'=>'customers', 'customer_id'=>$id, 'type'=>'customers', 'user_detail'=>$data['customers']];
            if($this->input->get('site_id')){ 
                $siteData['values_posted']['customer_sites'] = Modules::run("customer_sites/get_site_details", $this->input->get('site_id'));
                $siteData['values_posted']['address'] = Modules::run("address/address_details", $siteData['values_posted']['customer_sites']['address_id']);
                /*echo '<pre>';
                print_r($siteData);exit;*/
                $data['customer_sites'] = Modules::run("customer_sites/admin_add_couriersite_form", $siteData);
                //print_r($data['bank_account']);exit;
            }else{
                $data['customer_sites'] = Modules::run("customer_sites/admin_add_couriersite_form", $siteData);
            }
        }

        $data['js'][0] = "<script type = 'text/javascript'>
            $('.maxCurrentDate').on('ready', 'blur', function(){
                alert('hii');
            })
            </script>
        ";
        echo Modules::run('templates/admin_template', $data);
    }  

    function edit_customer_details() {
        $this->load->view("customers/edit_customer");
    }

    function get_customer_details($id) {
        //echo "reached in Customer module";
        //print_r($id);
        $this->pktdblib->set_table('customers');
        $customerdetails = $this->pktdblib->get_where($id);
        return $customerdetails;
    }

    function get_Customer_list_dropdown(){
        $this->pktdblib->set_table('customers');
        $customers = $this->pktdblib->get_active_list();
        //print_r($customers);
        $dropDown = [''=>'Select Customer'];
        foreach ($customers as $key => $customer) {
            $dropDown[$customer['id']] = $customer['first_name']." ".$customer['middle_name']." ".$customer['surname'];
            if(!empty($customer['emp_code']))
                $dropDown[$customer['id']].=' | '.$customer['emp_code'];
        }
        return $dropDown;
        //exit;
        //print_r($dropDown);
    }

    function admin_view($id=NULL){
        if(NULL==$id){
            redirect('customers');
        }
        $this->pktdblib->set_table('customers');
        $data['user'] = $this->pktdblib->get_where($id);
        //$data['user'] = $customer->row_array();
        $userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>'customers']);
        //echo '<pre>';print_r($userRoles);exit;
        $loginId = $userRoles[0]['login_id'];
        $roleId = $userRoles[0]['role_id'];
        $data['content'] = 'customers/admin_view';
        $data['meta_title'] = 'Customers';
        $data['meta_description'] = 'Customers';
        $addressListData = ['condition'=>['address.user_id'=>$loginId, 'address.type'=>'login'], 'module'=>'customers'];
        //$this->address_model->set_table('address');
        $data['addressList'] = Modules::run("address/admin_address_listing", $addressListData);

        $bankAccountListData = ['condition'=>['bank_accounts.user_id'=>$loginId, 'bank_accounts.user_type'=>'login'], 'module'=>'customers'];
        //$this->address_model->set_table('address');
        $data['bankAccountList'] = Modules::run("bank_accounts/admin_index_listing", $bankAccountListData);

        /*Documents*/
        $documentListData = ['condition'=>['user_documents.user_id'=>$loginId, 'user_documents.user_type'=>'login'], 'module'=>'customers'];
        //$this->address_model->set_table('bank_accounts');
        $data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);
        if($data['user']['has_multiple_sites']){
            $siteListData = ['condition'=>['customer_sites.customer_id'=>$id, 'customer_sites.is_active'=>TRUE], 'module'=>'customers'];
            $data['sites'] = Modules::run("customer_sites/admin_index_listing", $siteListData);
            //print_r($data['sites']);exit;
        }

        $data['otherDetailsList'] = Modules::run('customers/employee_other_details', ['customer_id'=>$id]);

        echo Modules::run("templates/admin_template", $data);
        //$this->templates->admin_template('', $data);
        //print_r($data['employee']);
    }

    function create_cust_code($custId) {
        $companyId = '';
        //$this->load->model('companies/companies_model');
        $this->pktdblib->set_table("companies");
        $companyDetails = $this->pktdblib->get_where(1);
        //$empCode = Modules::run("companies/company_details/1");
        //$empCode = 'MISS';
        $empCode = $companyDetails['short_code']."/Cl/";
        //print_r($companyDetails['short_code']."/"."Driver");exit;
        if($custId>0 && $custId<=9)
            $empCode .= '000000';
            
        elseif($custId>=10 && $custId<=99)
            $empCode .= '00000';
        elseif($custId>=100 && $custId<=999)
            $empCode .= '0000';
        elseif($custId>=1000 && $custId<=9999)
            $empCode .= '000';
        elseif($custId>=10000 && $custId<=99999)
            $empCode .= '00';
        elseif($custId>=100000 && $custId<=999999)
            $empCode .= '0';

        $empCode .= $custId;


        //echo "reached in create emp code method"; print_r($empCode);exit;
        return $empCode;
    }

    function add() {
        //print_r($this->session->userdata());
        if(NULL !== ($this->session->userdata('roles'))){
            if(array_key_exists('1', $this->session->userdata('roles'))){
                redirect(custom_constants::customer_page_url);
            }
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            /*echo '<pre>';
            print_r($_POST);
            exit;*/
            $data['values_posted'] = $_POST['data'];
            
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->form_validation->set_rules('data[address][country_id]', 'Country', 'required');
            $this->form_validation->set_rules('data[address][state_id]', 'State', 'required');
            $this->form_validation->set_rules('data[address][city_id]', 'City', 'required');
            $this->form_validation->set_rules('data[customer_references][introducer_id]', 'Proposer', 'required');
            $this->form_validation->set_rules('data[login][username]', 'Username', 'required|is_unique[login.username]');
            $this->form_validation->set_rules('data[login][password]', 'Password', 'required');
            $this->form_validation->set_rules('data[customers][first_name]', 'First Name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customers][middle_name]', 'Middle Name', 'max_length[255]');
            $this->form_validation->set_rules('data[customers][surname]', 'Last Name', 'max_length[255]');
            
            $this->form_validation->set_rules('data[customers][gender]', 'Gender', 'required');
            $this->form_validation->set_rules('data[customers][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
            $this->form_validation->set_rules('data[customers][primary_email]', 'primary email', 'required|max_length[255]|valid_email|is_unique[login.email]');

            $this->form_validation->set_rules('data[person_under][placement]', 'Position', 'required');
            $this->form_validation->set_rules('data[login][password]', 'Password', 'required|max_length[255]|alpha_numeric');
            $this->form_validation->set_rules('data[repassword][repassword]', 'Re-Enter Password', 'required|max_length[255]|alpha_numeric|matches[data[login][password]]');
            $this->form_validation->set_rules('data[captcha]', 'Captcha', 'required');
            

            if($this->form_validation->run('login')!==FALSE)
            {
                
                $error = [];
                if($this->input->post('data[captcha]') != $this->session->userdata('captcha')){
                    $error[] = "Invalid Total of 2 numbers";
                }
                $postData = $_POST['data'];
               
                if(empty($error)){
                    //echo '<pre>';
                    if($postData['proposer_id']!=''){ //echo "hii";
                        $this->customer_model->set_table('login');
                        $sponsorData = $this->customer_model->get_where_custom('username', $postData['proposer_id']);
                        //print_r($sponsorData->row_array());
                        $sponsorData = $sponsorData->row_array();
                        //print_r($sponsorData);
                        $postData['customer_references']['introducer_id'] = $sponsorData['employee_id'];
                        $postData['customer_references']['introducer_type'] = $sponsorData['account_type'];
                    }
                   
                    if(!empty($postData['login']['username']))
                        $postData['customers']['emp_code'] = str_replace(' ', '', $postData['login']['username']);
                    $postData['customers']['joining_date'] = date('Y-m-d');
                    $reg_user = json_decode($this->_register_admin_add($postData['customers']), true);
                   
                    if($reg_user['status'] === 'success' && isset($reg_user['id']))
                    {
                        $postData['login']['employee_id'] = $reg_user['id'];
                        $postData['login']['account_type'] = 'customers';
                        $postData['login']['email'] = $postData['customers']['primary_email'];
                        $postData['login']['username'] = $reg_user['customers']['emp_code'];
                        $postData['login']['first_name'] = $reg_user['customers']['first_name'];
                        $postData['login']['surname'] = $reg_user['customers']['surname'];
                        $postData['login']['contact_1'] = $reg_user['customers']['contact_1'];
                        $postData['login']['created'] = date('Y-m-d H:i:s');
                        $postData['login']['modified'] = date('Y-m-d H:i:s');
                        //echo '<pre>';
                        //print_r($postData['login']);
                        $login = Modules::run("login/_register_user", $postData['login']);
                        //print_r($login);exit;
                        $postData['customer_references']['customer_id'] = $reg_user['id'];
                        if(!isset($postData['customer_references']['introducer_type']))
                            $postData['customer_references']['introducer_type'] = 'customers';
                        $postData['customer_references']['created'] = date('Y-m-d H:i:s');
                        $postData['customer_references']['modified'] = date('Y-m-d H:i:s');
                        $this->mlm_lib->sponsor($postData['customer_references']);

                        $postData['address']['user_id'] = $reg_user['id'];
                        $postData['address']['type'] = 'customers';
                        $postData['address']['is_default'] = true;
                        $postData['address']['created'] = date('Y-m-d H:i:s');
                        $postData['address']['modified'] = date('Y-m-d H:i:s');
                        Modules::run("address/register_user_address", $postData['address']);

                        /*print_r($postData['customer_references']['introducer_id']);exit;*/
                        $postData['person_under']['user_type'] =  $postData['customer_references']['introducer_type'];
                        $postData['person_under']['parent_id'] =  $postData['customer_references']['introducer_id'];
                        $postData['person_under']['customer_id'] =  $reg_user['id'];
                        $postData['person_under']['enquiry_id'] = 0;
                        $postData['person_under']['modified'] = date('Y-m-d H:i:s');
                        $postData['person_under']['enquiry_id'] = 0;
                        $this->mlm_lib->punderbinary($postData['person_under']);

                        //Save companies_customers data
                        $postData['companies_customers']['customer_id'] =  $reg_user['id'];
                        $postData['companies_customers']['company_id'] =  1;
                        $postData['companies_customers']['is_active'] =  1;
                        $postData['companies_customers']['created'] = date('Y-m-d H:i:s');
                        $postData['companies_customers']['modified'] = date('Y-m-d H:i:s');
                        $this->customer_model->set_table("companies_customers");
                        $id = $this->customer_model->_insert($postData['companies_customers']);
                        
                        $data['primary_email'] = $this->session->userdata('primary_email');
                        $data['registered'] = TRUE;
                       
                        $msg = array('message' => 'Account created successfully', 'class' => 'alert alert-success fade-in');
                        $this->session->set_flashdata('message', $msg);

                        redirect(custom_constants::customer_page_url);
                    }
                    else
                    {
                        $msg = array('message'=> $reg_user['msg'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                    }
                }else{
                    $msg = array('message'=> 'Incorrect Answer', 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);

                }
            }else{
               // echo validation_errors();
            }
            
        }
        
        $data['title'] = "New Customer";
        
        $data['meta_title'] = "New User";
        $data['meta_keyword'] = "Oxiinc New Customer";
        $data['meta_description'] = "New user registration";
        $data['modules'][] = "customers";
        $data['methods'][] = "add_customer";
        
        echo Modules::run("templates/oxiinc_template", $data);
    }

    function add_customer(){
        $cities = $this->cities_model->get_dropdown_list();
        //print_r($cities);
        foreach ($cities as $cityKey => $city) {
            //print_r($city);
            $data['option']['cities'][$city['id']] = $city['city_name'];
        }

        $states = $this->states_model->get_dropdown_list();
        foreach($states as $stateKey => $state) {
            $data['option']['states'][$state['id']] = $state['state_name'];
        }
        /*print_r($data['option']['states']);
        exit;*/

        $countries =$this->countries_model->get_dropdown_list();
        foreach ($countries as $countryKey => $country) {
            $data['option']['countries'][$country['id']] = $country['name'];
        }

        $areas =$this->areas_model->get_dropdown_list();
        foreach ($areas as $areaKey => $area) {
            $data['option']['areas'][$area['id']] = $area['area_name'];
        }

        $customers =$this->customer_model->get_where_custom('is_active', true);
        $data['option']['introducers'][0] = "Select Proposer";
        $customersresult = $customers->result_array();
        if(isset($customersresult) && !empty($customersresult))
        {
            $data['option']['introducers'][1] = $customersresult[0]['first_name']." ".$customersresult[0]['middle_name']." ".$customersresult[0]['surname']." | ".$customersresult[0]['emp_code'];
        }
        /*foreach ($customers->result_array() as $customerKey => $customer) {
            $data['option']['introducers'][$customer['id']] = $customer['first_name']." ".$customer['middle_name']." ".$customer['surname']." | ".$customer['emp_code'];
        }*/

        $data['num1'] = rand(0,9);
        //echo $rand."<br>";
        $data['num2'] = rand(0,9);
        $data['sum'] = ($data['num1']+$data['num2']); 
        $this->session->set_userdata(['captcha'=>$data['sum']]);
        $data['js'][] = '<script type="text/javascript">
            $(document).ready(function(){
                var sponsor_id = "'.(isset($_GET['sponsor_id']) ? $_GET['sponsor_id'] : "").'";
                
                $(document).on("blur", "#proposer_id", function(){
                    var val = $(this).val();

                    $.ajax({
                        type: "POST",
                        url: base_url+"login/get_username_based_data",
                        dataType: "json",
                        data: "username="+val,
                        success:function(response) {
                            console.log("hii");
                            console.log(response);
                            if(response!="")
                                $("#proposer_name").val(response);
                            else{
                                $(".errormessage").addClass("alert alert-danger").html("No User Found");
                                //alert("No User Found");
                                $("#proposer_id").val("");
                                $("#proposer_name").val("");
                            }
                        }
                    });
               });
               
               if(sponsor_id != "")
                {
                    $.ajax({
                        type: "POST",
                        url: base_url+"login/get_username_based_data",
                        dataType: "json",
                        data: "username="+sponsor_id,
                        success:function(response) {
                            console.log("hii");
                            console.log(response);
                            if(response!="")
                                $("#proposer_name").val(response);
                            else{
                                $(".errormessage").addClass("alert alert-danger").html("No User Found");
                                //alert("No User Found");
                                $("#proposer_id").val("");
                                $("#proposer_name").val("");
                            }
                        }
                    });
                }
               
                $("#primary_email").keyup(function() {
                    $(this).val($(this).val().replace(/\s/g, "").toLowerCase());
                });
               
                $("#username").keyup(function() {
                    $(this).val($(this).val().replace(/[_\W]+/g, "").toLowerCase());
                });

                $("#first_name").keyup(function() {
                    $(this).val($(this).val().toUpperCase());
                });

                $("#middle_name").keyup(function() {
                    $(this).val($(this).val().toUpperCase());
                });

                $("#surname").keyup(function() {
                    $(this).val($(this).val().toUpperCase());
                });

                $(document).on("blur", "#username", function(){
                    var val = $(this).val();

                    $.ajax({
                        type: "POST",
                        url: base_url+"login/get_username_based_data",
                        dataType: "json",
                        data: "username="+val,
                        success:function(response) {
                            //console.log("hii");
                            //console.log(response);
                            if(response!=""){
                                $("#username").val("");
                                $(".errormessage").addClass("alert alert-danger").html("Username already Taken");
                                //alert("Username already Taken");
                            }
                            
                        }
                    });
                });

                $(document).on("blur", "#primary_email", function(){
                    var val = $(this).val();

                    $.ajax({
                        type: "POST",
                        url: base_url+"login/get_username_based_data",
                        dataType: "json",
                        data: "email="+val,
                        success:function(response) {
                            //console.log("hii");
                            //console.log(response);
                            if(response!=""){
                                $("#primary_email").val("");
                                $(".errormessage").addClass("alert alert-danger").html("Email ID Already Exist");
                                //alert("Email ID Already Exist");
                            }
                            
                        }
                    });
                });
            });
            $(document).on("submit", "#customers", function(){
                if($("#introducer_id").val()==0){
                    if($("#proposer_id").val()==""){
                        $(".errormessage").addClass("alert alert-danger").html("No Proposer Entered");
                        $("#introducer_id").focus();
                        return false;
                    }
                }
            });
        </script>';
        $this->load->view("customers/add", $data);
    }

    function dashboard($id=NULL){
//redirect('customers/myaccount');
        check_user_login(TRUE);

        if($id===NULL){
            $id = $this->session->userdata('employee_id');
        }


        if(NULL==$id){
            redirect(custom_constants::logout_url);
        }
        
        $this->pktdblib->set_table('customers');
        $customerDetail = $this->pktdblib->get_where($id);

        //print_r($customerDetail->row_array());

        $data['user'] = $customerDetail->row_array();



        /*Address list Code*/

        $addressListData = ['condition'=>['address.user_id'=>$id, 'address.type'=>'customers'], 'module'=>'customers'];

        $this->load->model('address_model');

        //echo '<pre>';

        $this->address_model->set_table('address');

        $data['address'] = $this->address_model->userBasedAddress($id, 'customers');


        $this->pktdblib->set_table('person_under');

        $placement = $this->pktdblib->get_where_custom('customer_id', $id);

        //echo '<pre>';

        $sql = "select concat(c.first_name, ' ', c.middle_name, ' ', c.surname) as fullname, l.username, p.placement, c.profile_img from person_under p inner join customers c on c.id=p.parent_id inner join login l on l.employee_id=c.id AND l.account_type='customers' where p.customer_id='".$id."'";

        $placement = $this->pktlib->custom_query($sql);

        $data['placement']['node'] = (count($placement)>0)?$placement[0]:[];

        $sql = "select concat(c.first_name, ' ', c.middle_name, ' ', c.surname) as fullname, l.username, c.profile_img from customer_references r inner join customers c on c.id=r.introducer_id inner join login l on l.employee_id=c.id AND l.account_type='customers' where r.customer_id='".$id."'";

        //echo $sql;

        $sponsor = $this->pktlib->custom_query($sql);

        //print_r($sponsor);exit;

        $data['placement']['sponsor'] = !empty($sponsor)?$sponsor[0]:'';

       

        $this->pktdblib->set_table('roles');



        $roles = $this->pktdblib->get_where_custom('is_active', true);

        //print_r($this->session->userdata['roles']);

        foreach ($roles->result_array() as $key => $role) {

            if(in_array($role['id'], $this->session->userdata['roles']))

                if($role['role_name']=='customers')

                    $data['roles'][$role['id']] = $role['role_name'];

                else

                    $data['roles'][$role['id']] = 'e-Panelist';

        }

        

        $sql = 'Select sum(w.amount) as amount, w.income_type, i.income, w.is_reedemable from wallets w inner join mlm_income_type i on i.id=w.income_type where w.user_type="customers" and w.user_id='.$id.' AND w.is_active=true group by w.wallet_type, w.is_reedemable';

        //echo $sql;

        $wallets = $this->pktdblib->custom_query($sql);

        foreach ($wallets as $key => $wallet) {

            $data['wallets'][$wallet['income_type']][$wallet['is_reedemable']] = $wallet;

        }



        $this->pktdblib->set_table('mlm_income_type');

        $mlmIncomes = $this->pktdblib->get_where_custom('is_active', true);

        $data['mlmIncomes']  = $mlmIncomes->result_array();

       

        /*$data['memberStatistics']['placementCount'] = $this->mlm_lib->get_positionwisedownlineCount(['parent_id'=>$id]);

        $data['memberStatistics']['todayJoining'] = $this->mlm_lib->get_positionwisedownlineDateWise(['parent_id'=>$id]);*/
        $data['memberStatistics']['placementCount'] = $this->mlm_lib->countDownline($id);
        
        $data['memberStatistics']['todayJoining'] = $this->mlm_lib->countDownline($id, 'date');
       

        $data['message'] = $this->pktdblib->custom_query('Select * from messages where id in (select message_id from message_users where (receiver_id="'.$this->session->userdata('user_id').'") or receiver_id=0) And date<="'.date('Y-m-d').'" order by id DESC limit 1');

        $data['busCount'] = $this->mlm_lib->businessCount2('', $this->session->userdata('employee_id'));
        $data['todaysBusinessCount'] = $this->mlm_lib->mlmBusinessCount($id, '', [], 'amount', 'orders.date="'.date('Y-m-d').'"');
        
         $data['directCount'] = $this->mlm_lib->positionWisedirectCount($this->session->userdata('employee_id'));
        //Assign Social Media Links
        
        $assignLinks = Modules::run('social_media_share/assignLink');
        $assignVideos = Modules::run('social_media_share/assignVideo');
        $this->load->view('customers/view', $data);

    }

    function index(){
        check_user_login(TRUE);
        //print_r($this->session->userdata());
        $data['title'] = "Oxiinc :: ".$this->session->userdata('name');
        
        $data['meta_title'] = $this->session->userdata('name');
        $data['meta_keyword'] = $this->session->userdata('name');
        $data['meta_description'] = $this->session->userdata('name');
        $data['modules'][] = "customers";
        $data['methods'][] = "dashboard";
        
        echo Modules::run("templates/oxiinc_template", $data);
    }

    function view($id=NULL){
        check_user_login(TRUE);
        //print_r($this->session->userdata());
        if($id===NULL)
            $id = $this->session->userdata('employee_id');
         
        $this->pktdblib->set_table('customers');
        $customerDetail = $this->pktdblib->get_where($id);
        $data['user'] = $customerDetail->row_array();

        /*Address list Code*/
        $addressListData = ['condition'=>['address.user_id'=>$id, 'address.type'=>'customers'], 'module'=>'customers'];
        $this->load->model('address_model');
        //echo '<pre>';
        $this->address_model->set_table('address');
        $data['address'] = $this->address_model->userBasedAddress($id, 'customers');

        $this->pktdblib->set_table('person_under');
        $placement = $this->pktdblib->get_where_custom('customer_id', $id);
        //echo '<pre>';
        $sql = "select concat(c.first_name, ' ', c.middle_name, ' ', c.surname) as fullname, l.username, p.placement, c.profile_img from person_under p inner join customers c on c.id=p.parent_id inner join login l on l.employee_id=c.id AND l.account_type='customers' where p.customer_id='".$id."'";
        $placement = $this->pktlib->custom_query($sql);
        $data['placement']['node'] = (count($placement)>0)?$placement[0]:[];
        $sql = "select concat(c.first_name, ' ', c.middle_name, ' ', c.surname) as fullname, l.username, c.profile_img from customer_references r inner join customers c on c.id=r.introducer_id inner join login l on l.employee_id=c.id AND l.account_type='customers' where r.customer_id='".$id."'";
        //echo $sql;
        $sponsor = $this->pktlib->custom_query($sql);
        //print_r($sponsor);exit;
        $data['placement']['sponsor'] = !empty($sponsor)?$sponsor[0]:'';
       
        $this->pktdblib->set_table('roles');

        $roles = $this->pktdblib->get_where_custom('is_active', true);
        //print_r($this->session->userdata['roles']);
        foreach ($roles->result_array() as $key => $role) {
            if(in_array($role['id'], $this->session->userdata['roles']))
                $data['roles'][$role['id']] = $role['role_name'];
        }
        
        $sql = 'Select sum(w.amount) as amount, w.income_type, i.income, w.is_reedemable from wallets w inner join mlm_income_type i on i.id=w.income_type where w.user_type="customers" and w.user_id='.$id.' group by w.wallet_type, w.is_reedemable';
        //echo $sql;
        $wallets = $this->pktdblib->custom_query($sql);
        foreach ($wallets as $key => $wallet) {
            $data['wallets'][$wallet['income_type']][$wallet['is_reedemable']] = $wallet;
        }

        $this->pktdblib->set_table('mlm_income_type');
        $mlmIncomes = $this->pktdblib->get_where_custom('is_active', true);
        $data['mlmIncomes']  = $mlmIncomes->result_array();
       
        $data['memberStatistics']['placementCount'] = $this->mlm_lib->get_positionwisedownlineCount(['parent_id'=>$id]);
        $data['memberStatistics']['todayJoining'] = $this->mlm_lib->get_positionwisedownlineDateWise(['parent_id'=>$id]);

        $data['title'] = "Oxiinc :: ".$this->session->userdata('name');
        
        $data['meta_title'] = $this->session->userdata('name');
        $data['meta_keyword'] = $this->session->userdata('name');
        $data['meta_description'] = $this->session->userdata('name');
        $data['content'] = "customers/view";
       
        echo Modules::run("templates/oxiinc_template", $data);
    }

    function getCityWiseCustomers(){
        //$_POST['params'] = 1;
        if(!$this->input->post('params'))
            return;

        $condition = [];
        $condition = ['customers.is_active' => TRUE];
        $cityId = $this->input->post('params');
        if(!empty($cityId)) {
            $condition['address.city_id'] = $cityId;
        }
        $this->customer_model->set_table('customers');
        $cityWiseCustomers = $this->customer_model->getCityWiseCustomers($condition);
        $customerList = [];
        $customerList[$key][0] = 'Select Proposer';
        foreach ($cityWiseCustomers as $key => $cityWisecustomer) {
            $customerList[$key]['id'] = $cityWisecustomer['id'];
            $customerList[$key]['text'] = $cityWisecustomer['first_name']." ".$cityWisecustomer['middle_name']." ".$cityWisecustomer['surname']." | ".$cityWisecustomer['emp_code'];
        }
        echo json_encode($customerList);
    }

    function edit($id = NULL) {
        check_user_login(TRUE);

        $id = $this->session->userdata('employee_id');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['customers'] = $postData = $data['values_posted']['customers'] = $_POST['data']['customers'];
            $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
            $this->form_validation->set_rules('data[customers][first_name]','first name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customers][surname]', 'surname', 'required|max_length[255]');
            /*$this->form_validation->set_rules('data[customers][company_name]', 'company_name', 'required|max_length[255]');*/
            $this->form_validation->set_rules('data[customers][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
            $this->form_validation->set_rules('data[customers][contact_2]', 'contact_2', 'max_length[15]|min_length[10]|numeric');
            /*$this->form_validation->set_rules('data[customers][primary_email]', 'primary_email', 'required|max_length[255]');*/
            $this->form_validation->set_rules('data[customers][secondary_email]', 'secondary_email', 'max_length[255]');
            $this->form_validation->set_rules('data[customers][dob]', 'Date of Birth', 'required');
            
            if($this->form_validation->run('customers')!== FALSE) {
                $profileImg = '';
                
                if(!empty($_FILES['profile_img']['name'])) {
                    $profileFileValidationParams = ['file'=>$_FILES['profile_img'], 'path' => '../content/uploads/profile_images/', 'ext' => 'jpg|png|gif|jpeg', 'fieldname' =>'profile_img', 'arrindex' => 'profile_img'];
                    $profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
                    if(empty($profileImg['error'])) {
                        $postData['profile_img'] = $profileImg['filename'];
                        unset($postData['profile_img2']);
                    }
                    else {
                        $error['profile_img'] = $profileImg['error'];
                    }
                }
                else {
                    $postData['profile_img'] = $postData['profile_img2'];
                    unset($postData['profile_img2']);
                }

                if(empty($error)) {
                    $postData['dob'] = $this->pktlib->dmYtoYmd($postData['dob']);

                    //print_r($this->customer_model->_update_customer($id, $postData));
                    $this->pktdblib->set_table('customers');
                    if($this->edit_customer($id, $postData)) {
                        $msg = array('message' => "Profile Edited successfully", 'class' => 'alert alert-success fade-in');
                        $this->session->set_flashdata('message', $msg);
                    }
                    else {
                        $msg = array('message' => 'some problem occured while updating', 'class' => 'alert alert-danger fade-in');
                        $this->session->set_flashdata('message', $msg);
                    }
                    redirect(custom_constants::edit_personal_info_url.'?tab=address');
                }
                else {
                    $msg = array('message' => $error, 'class' =>'alert alert-danger fade-in');
                    $this->session->set_flashdata('error', $msg);
                }
            } 
            else {
                //echo validation_errors();exit;
                $msg = array('message' => 'some validation error occured'.validation_errors(), 'class' => 'alert alert-danger fade-in');
                $this->session->set_flashdata('error', $msg);
            }

        }
        else {
            $this->customer_model->set_table("customers");
            $data['customers'] = $this->get_customer_details($id);
            $data['values_posted']['customers'] = $data['customers'];
        }

        $blood_group = $this->customer_model->get_dropdown_list();
        foreach ($blood_group as $blood_groupKey => $blood_group) {
            $data['option']['blood_group'][$blood_group['blood_group']] = $blood_group['blood_group'];
        }

        $data['id'] = $id;
        if(!($this->input->get('tab')))
            $data['tab'] = 'personal_info';
        else
            $data['tab'] = $this->input->get('tab');
        $data['meta_title'] = "Edit Customer";
        $data['title'] = "ERP Edit Customer";
        $data['meta_description'] = "Edit Customer";
        $data['meta_keyword'] = "Edit Customer";
        $data['content'] = 'customers/edit';

        $addressListData = ['condition' => ['address.user_id'=> $id, 'address.type'=>'customers'], 'module' => 'customers'];
        //$this->address_model->set_table("address");
        //$data['addressList'] = Modules::run("address/address_listing", $addressListData);
        $this->address_model->set_table("address");
        $userAddress = $this->pktlib->createquery(['table'=>'address', 'conditions'=>['address.user_id'=>$this->session->userdata('employee_id'), 'address.type'=>'customers']]);
        //print_r($userAddress);
        $addressData = ['url' => custom_constants::edit_personal_info_url.'?tab=bank_account', 'module'=>'customers', 'user_id'=>$id,'type' => 'customers', 'user_detail' => $data['customers']]; 
        if((NULL!==$this->input->get('address_id')) || $userAddress!=FALSE) {
            $addressId = (NULL!==$this->input->get('address_id'))?$this->input->get('address_id'):$userAddress[0]['id'];
            $addressEditData = $this->address_model->get_where($addressId);
            $addressData['address'] = $addressEditData->row_array();
               
                if($addressData['address']['user_id']!=$this->session->userdata('employee_id')){
                    $msg = array('message' => 'Invalid Access', 'class' => 'alert alert-danger fade-in');
                    $this->session->set_flashdata('message', $msg);
                    redirect(custom_constants::customer_page_url);
                }
            $data['address'] = Modules::run("address/edit_form", $addressData);
        }else {
            $data['address'] = Modules::run("address/add_form", $addressData);
        }
        //echo $data['address'];exit;
        /* Bank Account Related Code Starts Here  */
        /*$bankAccountListData = ['condition'=>['bank_accounts.user_id'=>$id, 'bank_accounts.user_type'=>'customers'], 'module'=>'customers'];
        $this->address_model->set_table('bank_accounts');
        $data['bankAccountList'] = Modules::run("bank_accounts/account_listing", $bankAccountListData);*/

        //$this->address_model->set_table('bank_accounts');
        $bankAccountData = ['url'=>custom_constants::edit_personal_info_url.'?tab=document', 'module'=>'customers', 'user_id'=>$id, 'type'=>'customers', 'user_detail'=>$data['customers']];

        $userBankAccount = $this->pktlib->createquery(['table'=>'bank_accounts', 'conditions'=>['bank_accounts.user_id'=>$this->session->userdata('employee_id'), 'bank_accounts.user_type'=>'customers']]);
       /* print_r($userBankAccount);
        exit;*/
        if((NULL!==$this->input->get('bank_account_id')) || ($userBankAccount!=FALSE)){ //echo "hii";
            $bankAccountId = (NULL!==$this->input->get('bank_account_id'))?$this->input->get('bank_account_id'):$userBankAccount[0]['id'];
            $bankAccountData['bank_accounts'] = Modules::run("bank_accounts/account_details", $bankAccountId);
            //print_r($bankAccountData['bank_accounts']);exit;
            if($bankAccountData['bank_accounts']['user_id']!=$this->session->userdata('employee_id')){
                    $msg = array('message' => 'Invalid Access', 'class' => 'alert alert-danger fade-in');
                    $this->session->set_flashdata('message', $msg);
                    redirect(custom_constants::customer_page_url);
                }
            $data['bank_account'] = Modules::run("bank_accounts/edit_form", $bankAccountData);
            //print_r($data['bank_account']);exit;
        }else{ //echo "hello";
            $data['bank_account'] = Modules::run("bank_accounts/add_form", $bankAccountData);
        }
        //echo $data['bank_account'];exit;
        /*Bank account ends*/

        /*Document Uploads*/
        $documentListData = ['condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'customers'], 'module'=>'customers'];
        //$this->address_model->set_table('bank_accounts');
        $data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

        $documentData = ['url'=>custom_constants::edit_personal_info_url.'?tab=document', 'module'=>'customers', 'user_id'=>$id, 'type'=>'customers', 'user_detail'=>$data['customers']];
        
        $data['document'] = Modules::run("upload_documents/add_form", $documentData);
        
        echo Modules::run('templates/oxiinc_template', $data);
    }  

    function get_empId_based_data(){
        $sql = "Select concat(first_name, ' ', middle_name, ' ', surname) as fullname from customers where is_active=true";
        if(NULL!==$this->input->post('emp_code'))
            $sql.=" AND emp_code LIKE '".$this->input->post('emp_code')."'";

        $query = $this->pktdblib->custom_query($sql);
        if(!empty($query))
            echo json_encode($query[0]['fullname']);
        else
            echo json_encode('');
        exit;
    }
}
