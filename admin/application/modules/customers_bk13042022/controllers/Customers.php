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
            }
        }
        check_user_login(FALSE);
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

    function admin_dashboard(){
        $this->pktdblib->set_table('customers');
        $data['totalCustomer'] = $this->pktdblib->count_where('is_active', true);
       $this->pktdblib->set_table('vendors');
        $data['totalVendor'] = $this->pktdblib->count_where('is_active', true);
        $this->pktdblib->set_table('brokers');
        $data['totalBroker'] = $this->pktdblib->count_where('is_active', true);
        $this->pktdblib->set_table('employees');
        $data['totalEmployee'] = $this->pktdblib->count_where('is_active', true);
        $this->load->view('customers/admin_dashboard', $data);
    }

    function admin_index() {
        
        //echo "<pre>"; print_r($this->session->userdata());exit;
        if($this->input->is_ajax_request()){  
            
            $postData = $this->input->post();
            //echo '<pre>';print_r($postData);exit;
            $postData['customers'] = [];
            if(in_array(7, $this->session->userdata('roles')) || in_array(6, $this->session->userdata('roles'))){
               $postData['customers']['referral_code'] = $this->session->userdata('employees')['emp_code'];
            }
            $data = $this->customer_model->customerList($postData);
            //echo "<pre>"; print_r($data);exit;
            
            foreach($data['aaData'] as $key=>$v){
                //echo "<pre>"; print_r($v);
                $multiSite = ($v['has_multiple_sites']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';
                //$data['aaData'][$key]['has_multiple_sites'] = "<i class='".$multiSite."'></i>";
                $active = ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';
                $data['aaData'][$key]['is_active'] = "<i class='".$active."'></i>";
                //$data['aaData'][$key]['image'] = "<img src='".content_url('uploads/profile_images/'.$v['image'])."' class='img-responsive'></i>";

                $app_order_count = ''.anchor("#", ''.$v['app_order_count'].'', ['class'=>'dynamic-modal load-ajax', 'data-path'=>"orders/admin_app_order_count/".$v['id'], 'data-modal-title'=>"App Usage Statistic", 'data-model-size'=>"modal-lg"]).'';

                $action = '
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                        <span class="fa fa-caret-down" ></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>'.anchor("customers/adminview/".$v['id'], '<i class="fa fa-eye"></i> View').'</li>
                        ';
                        if($v['has_multiple_sites']==TRUE){
                        $action.='<li>'.anchor("customers/editcustomer/".$v['id'], '<i class="fa fa-edit"></i> Edit').'</li>'; 
                         
                     }else{
                         $action.='<li>'.anchor("customers/edit_customer/".$v['id'].'?tab=address', '<i class="fa fa-edit"></i> Edit').'</li>'; 
                     }
                     $action.='<li>'.anchor("customers/editcustomer/".$v['id'].'?tab=address', '<i class="fa fa-edit"></i> Add Address').'</li>';
                        if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){ 
                    $action.='<li>'.anchor("tally/import_ledger/".$v['id']."/customers", '<i class="fa fa-sign-in"></i> Send To Tally', ['target'=>'_new']).'</li>';
                     } 
                     
                    $action.='</ul>
                </div>';
                $data['aaData'][$key]['action'] = $action;
                $data['aaData'][$key]['app_order_count'] = $app_order_count;
                //echo $action;exit;
            }
            echo json_encode($data);
            exit;
            
        }
        /*$this->pktdblib->set_table('customers');
        //$customers = $this->pktdblib->get('id desc');
        $customers = $this->pktdblib->get_where_custom('is_active', true);

        $data['customers'] = $customers->result_array();*/
        $data['customers'] = $this->pktdblib->custom_query('select customers.*, concat(customers.first_name," ", customers.middle_name," ",customers.surname," - ", customers.company_name) as fullname, customer_categories.category_name from customers left join customer_categories on customers.customer_category_id=customer_categories.id where customers.is_active=1');
        //echo '<pre>';print_r($data['customers']);exit;
        $this->pktdblib->set_table('login');
        // foreach ($data['customers'] as $key => $customer) {
        //     $login = $this->pktdblib->get_where_custom('username', $customer['emp_code']);
        //     if(!empty($login)){
        //         $logins = $login->row_array();
        //         print_r($logins);
        //         $data['customers'][$key]['login_id'] = $logins['id'];
        //     }

        // }
        
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
            $data['values_posted'] = $this->input->post('data');
            $this->form_validation->set_rules('data[customers][first_name]', 'first name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customers][company_name]', 'company name', 'max_length[255]|is_unique[customers.company_name]');
             $this->form_validation->set_rules('data[customers][primary_email]', 'primary email', 'max_length[255]|valid_email|is_unique[customers.primary_email]');
            $this->form_validation->set_rules('data[customers][contact_1]', 'contact 1', 'required|max_length[15]|min_length[10]|numeric');
            $this->form_validation->set_rules('data[customers][contact_2]', 'contact 2', 'max_length[15]|min_length[10]|numeric');
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
                    $postData['primary_email'] = (empty(trim($postData['primary_email']))?NULL:trim($postData['primary_email']));
                    $postData['emp_code'] = (empty(trim($postData['emp_code']))?NULL:trim($postData['emp_code']));
                    $postData['created'] = date('Y-m-d H:i:s');
                    $postData['modified'] = date('Y-m-d H:i:s');
                    $postData['created_by'] = $this->session->userdata('user_id');
                    $postData['modified_by'] = $this->session->userdata('user_id');
                    if(NULL !==($this->session->userdata('employees'))){
                        $postData['referral_code'] = $this->session->userdata('employees')['emp_code'];
                    }
                   // echo '<pre>';print_r($postData);exit;
                    $reg_customer = json_decode($this->_register_admin_add($postData), true);
                    //print_r($reg_customer);exit;

                    if($reg_customer['status'] === 'success') {
                        $msg=[];
                      // echo '<pre>'; print_r($reg_customer['customers']['id']);exit;
                        $postData['id'] = $reg_customer['id'];
                        if($_SESSION['application']['multiple_company']){
                            $companyCustomer=[];
                            foreach ($this->input->post("data[companies_customers][company_id]") as $key => $value) {
                                //print_r($value);exit;
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
                        
                        if(array_key_exists('customer_zones', $this->input->post('data'))){
                            $zone['customer_id'] = $reg_customer['id'];
                            $zone['zone_no'] = $this->input->post('data[customer_zones][zone_no]');
                            $zone['route_no'] = $this->input->post('data[customer_zones][route_no]');
                            $this->pktdblib->set_table("customer_zones");
                            $zone = $this->pktdblib->_insert($zone);
                            //echo $this->db->last_query();
                        }
                        //exit;
                        if($_SESSION['application']['is_erpnext']){
                            //$params = ['company_name'=>$postData['company_name'],'referral_code'=>$postData['referral_code'],'primary_email'=>$postData['primary_email'], 'contact_1'=>$postData['contact_1'], 'id'=>$postData['id']];
                            $erp = Modules::run('erpnext/create_customer', base64_encode($reg_customer['customers']['emp_code']));
                            
                        }
                        /*if(NULL!==$postData['primary_email']){
                            $login = Modules::run('login/register_customer_to_login', $postData);
                            $msg = array('message'=>'Login created Successfully', 'class'=>'alert alert-success');
                        }*/

                        /*if Tally integrated*/
                        if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){
                            $tallyLedger = Modules::run('tally/import_ledger',$reg_customer['id'], 'customers');
                        }

                        if(!$this->input->is_ajax_request()){
                            $msg = array('message'=> 'Customer Added Successfully', 'class' => 'alert alert-success');
                            $this->session->set_flashdata('message',$msg);
                            redirect(custom_constants::edit_customer_url."/".$reg_customer['customers']['id'].'?tab=address');
                        }else{
                            echo json_encode(['status'=>1, 'value'=>[0=>['id'=>$reg_customer['customers']['id'], 'text'=>$reg_customer['customers']['first_name']." ".$reg_customer['customers']['middle_name']." ".$reg_customer['customers']['surname']." | ".$reg_customer['customers']['company_name']]], 'msg'=>'Customer Created Successfully']);
                            exit;
                        }
                    }
                    else {
                        $data['form_error'] = $reg_customer['msg'];
                    }
                }else{
                    if(!$this->input->is_ajax_request()){
                    $msg = array('message'=> 'Error while uploading file'.$error['profile_img'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                    }else{
                        echo json_encode(['status'=>0, 'msg'=>'Error while uploading file'.$error['profile_img']]);
                        exit;
                    }
                }


            }else{

                if(!$this->input->is_ajax_request()){
                    $msg = array('message'=> 'validation Error Occured '.validation_errors(), 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                }else{
                    echo json_encode(['status'=>0, 'msg'=>'validation Error Occured '.validation_errors()]);
                    exit;
                }
                //echo validation_errors();
            }
             
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
    
    function admin_add_new() {
        //echo "<pre>";
        //print_r($this->session->userdata());exit;
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
           /*echo '<pre>';
            print_r($_POST);
            print_r($_FILES);
            print_r($this->input->post());
            echo '</pre>';exit;
            return false;*/
            $data['values_posted'] = $this->input->post('data');

            $this->form_validation->set_rules('data[customers][first_name]', 'first name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customers][company_name]', 'company name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customers][primary_email]', 'primary email', 'required|max_length[255]|valid_email|is_unique[customers.primary_email]');
            $this->form_validation->set_rules('data[customers][contact_1]', 'contact 1', 'required|max_length[15]|min_length[10]|numeric|is_unique[customers.contact_1]');
            
            if($this->form_validation->run()!== FALSE) { //echo "hii";
                $error = false;
                //print_r($_FILES);exit;
                $postData = $this->input->post('data[customers]');
                if(NULL !==($this->session->userdata('employees'))){
                    $postData['referral_code'] = $this->session->userdata('employees')['emp_code'];
                }
                $postData['joining_date'] = $postData['created'] = date('Y-m-d H:i:s');
                $postData['modified'] = date('Y-m-d H:i:s');
                $postData['created_by'] = $this->session->userdata('user_id');
                $postData['modified_by'] = $this->session->userdata('user_id');
                $postData['emp_code'] = (!isset($postData['emp_code']) || empty($postData['emp_code'])?NULL:trim($postData['emp_code']));
                //print_r($error);exit;
                if (!$error) {
                    $this->db->trans_start(); # Starting Transaction
                    $postData['primary_email'] = (empty(trim($postData['primary_email']))?NULL:trim($postData['primary_email']));
                    // echo '<pre>';print_r($postData);exit;
                    $reg_customer = json_decode($this->_register_admin_add($postData), true);
                    //print_r($reg_customer);exit;

                    if($reg_customer['status'] === 'success') {
                        $msg=[];
                      // echo '<pre>'; print_r($reg_customer['customers']['id']);exit;
                        $postData['id'] = $reg_customer['id'];
                        if($_SESSION['application']['multiple_company']){
                            $companyCustomer=[];
                            foreach ($this->input->post("data[companies_customers][company_id]") as $key => $value) {
                                //print_r($value);exit;
                                $companyCustomer[$key]['company_id'] = $value;
                                $companyCustomer[$key]['customer_id'] = $reg_customer['id'];
                                $companyCustomer[$key]['is_active'] = true;
                                $companyCustomer[$key]['created'] = $companyCustomer[$key]['modified'] = date('Y-m-d H:i:s');
                            }
                                //print_r($companyCustomer);exit;
                            if(!empty($companyCustomer)){
                                /*echo "hii";*/
                                $this->pktdblib->set_table("companies_customers");
                                $ins = $this->pktdblib->_insert_multiple($companyCustomer);
                                if(!$ins){
                                    $error = true;
                                }
                            }
                        }
                        
                        if(isset($postData['primary_email']) && !empty($postData['primary_email']) || isset($postData['contact_1']) && !empty($postData['contact_1'])){
                            $login = Modules::run('login/register_customer_to_login', $postData);
                            if(!$login){
                                $error = true;
                            }
                        }

                        $message = 'Customer Added Successfully';
                        $class = 'alert alert-success';
                        if($error){
                            $message = 'Failed to create customer';
                            $class = 'alert alert-danger';
                            $this->db->trans_rollback();
                        }else{
                            $this->db->trans_commit();
                        }
                        if(!$this->input->is_ajax_request()){
                            $msg = array('message'=> $message, 'class' => $class);
                            $this->session->set_flashdata('message',$msg);
                            redirect(custom_constants::edit_customer_url."/".$reg_customer['customers']['id'].'?tab=address');
                        }else{
                            echo json_encode(['status'=>1, 'value'=>[0=>['id'=>$reg_customer['customers']['id'], 'text'=>$reg_customer['customers']['first_name']." ".$reg_customer['customers']['middle_name']." ".$reg_customer['customers']['surname']." | ".$reg_customer['customers']['company_name']]], 'msg'=>$message]);
                            exit;
                        }
                    }
                    else {
                        $data['form_error'] = $reg_customer['msg'];
                    }
                }else{
                    if(!$this->input->is_ajax_request()){
                    $msg = array('message'=> 'Error while uploading file'.$error['profile_img'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                    }else{
                        echo json_encode(['status'=>0, 'msg'=>'Error while uploading file'.$error['profile_img']]);
                        exit;
                    }
                }


            }else{

                if(!$this->input->is_ajax_request()){
                    $msg = array('message'=> 'validation Error Occured '.validation_errors(), 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                }else{
                    echo json_encode(['status'=>0, 'msg'=>'validation Error Occured '.validation_errors()]);
                    exit;
                }
                //echo validation_errors();
            }
             
        }
        
        //print_r( $data['option']['blood_group'][$blood_group['blood_group']]);
        
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Customer Module";
        $data['meta_description'] = "New Customer";
        
        $data['modules'][] = "customers";
        $data['methods'][] = "admin_add_customer_new";
        
        echo Modules::run("templates/admin_template", $data);
           // }
            
            
    }

    function _register_admin_add($data) {
        //echo '<pre>';print_r($data);exit;
        $data['customer_category_id'] = isset($data['customer_category_id'])?$data['customer_category_id']:1;
        $this->pktdblib->set_table("customers");
        if($this->pktdblib->count_where("primary_email", $data['primary_email']) > 0  && $data['primary_email'] !== NULL)
        {
            $customer = $this->pktdblib->get_where_custom('primary_email', $data['primary_email']);
            $customerDetails = $customer->row_array();
            return json_encode(["msg"=>"email is already in use", "status"=>"success", 'customers'=>$customerDetails, 'is_new'=>false]);
        }
        
        $this->pktdblib->set_table("customers");
        $id = $this->pktdblib->_insert($data);
        //print_r($id);exit;
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
    
    function _register_admin_add_new($data) {
        //echo '<pre>';print_r($data);exit;
        $data['customer_category_id'] = isset($data['customer_category_id'])?$data['customer_category_id']:1;
        $this->pktdblib->set_table("customers");
        if($this->pktdblib->count_where("primary_email", $data['primary_email']) > 0  && $data['primary_email'] !== NULL)
        {
            $customer = $this->pktdblib->get_where_custom('primary_email', $data['primary_email']);
            $customerDetails = $customer->row_array();
            return json_encode(["msg"=>"email is already in use", "status"=>"success", 'customers'=>$customerDetails, 'is_new'=>false]);
        }
        
        $this->pktdblib->set_table("customers");
        $id = $this->pktdblib->_insert($data);
        //print_r($id);exit;
        $customer = $this->get_customer_details($id['id']);
        //print_r($id);
        return json_encode(["msg" => "Data Added Successfully", "status" => "success", 'id' => $id['id'], 'customers' => $customer, 'is_new'=>true ]);
    }

    function edit_customer($id=NULL, $postData = []) {
        //print_r($postData);exit;
        if(NULL == $id)
            return false;

        if($this->pktdblib->_update($id,$postData))
            return true;
        else
            return false;
    }
    
    
	function admin_add_customer($data=[]) {
        //print_r($this->input->is_ajax_request());
        $this->pktdblib->set_table('companies');
        $companies = $this->pktdblib->get_where_custom('is_active', true);
        $data['companies'] = $companies->result_array();
        $data['option']['company'] = [''=>'Select Company'];
        foreach ($data['companies'] as $key => $company) {
            $data['option']['company'][$company['id']] = $company['company_name'];
        }

        $data['categories'] = $this->customer_model->get_categorylist_for_customer();
        //print_r($data['categories']);
        //exit;
        $data['option']['category'][0] = 'Select Category';
        foreach($data['categories'] as $categoryKey => $category){
            
            $data['option']['category'][$category['id']] = $category['category_name'];
        }
        $blood_group = $this->customer_model->get_dropdown_list();
       // print_r($blood_group);
        $data['option']['blood_group'][NULL] = "Select blood_group";
        foreach ($blood_group as $blood_groupKey => $blood_group) {
            //print_r($blood_groupKey);
            //print_r($blood_group);
            $data['option']['blood_group'][$blood_group['blood_group']] = $blood_group['blood_group'];
        }
        
        $data['customerHasZone'] = false;
        $chkzone = $this->pktdblib->custom_query('SHOW TABLES LIKE "customer_zones"');
        
        if(!empty($chkzone)){
            $data['customerHasZone'] = true;
        }
        
		$this->load->view("customers/admin_add", $data);
		//$this->load->view("admin_panel/login_register");
	}
	
	function admin_add_customer_new($data=[]) {
        //print_r($this->input->is_ajax_request());
        $this->pktdblib->set_table('companies');
        $companies = $this->pktdblib->get_where_custom('is_active', true);
        $data['companies'] = $companies->result_array();
        $data['option']['company'] = [''=>'Select Company'];
        foreach ($data['companies'] as $key => $company) {
            $data['option']['company'][$company['id']] = $company['company_name'];
        }

        
        
		$this->load->view("customers/admin_add_new", $data);
		//$this->load->view("admin_panel/login_register");
	}
	
	

    function admin_edit($id = NULL) {

    	if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST['data'];
    		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
    		$this->form_validation->set_rules('data[customers][first_name]','first name', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[customers][surname]', 'surname', 'max_length[255]');
    		$this->form_validation->set_rules('data[customers][company_name]', 'company_name', 'max_length[255]');
    		$this->form_validation->set_rules('data[customers][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
    		$this->form_validation->set_rules('data[customers][contact_2]', 'contact_2', 'max_length[15]|min_length[10]|numeric');
    		$this->form_validation->set_rules('data[customers][primary_email]', 'primary_email', 'max_length[255]|valid_email');
    		$this->form_validation->set_rules('data[customers][secondary_email]', 'secondary_email', 'max_length[255]');
            /*$this->form_validation->set_rules('data[customers][pan_no]', 'pan no', 'is_unique[customers.pan_no]|alpha_numeric');
            $this->form_validation->set_rules('data[customers][gst_no]', 'gst_no', 'is_unique[customers.gst_no]');*/
    		
    		if($this->form_validation->run('customers')!== FALSE) {
    			$profileImg = '';
    			$postData = $data['values_posted']['customers'];
                if(isset($postData['is_active']))
                    $postData['is_active'] = true;
                else
                    $postData['is_active'] = false;
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
    				$data['values_posted']['customers']['profile_img'] = $postData['profile_img'] = $postData['profile_img2'];
    				unset($postData['profile_img2']);
    			}
                //print_r($data['values_posted']);exit;
    			if(empty($error)) {
    				//print_r($this->customer_model->_update_customer($id, $postData));
                    $this->pktdblib->set_table("customers");
                    $postData['primary_email'] = (empty(trim($postData['primary_email']))?NULL:trim($postData['primary_email']));
                    $postData['has_multiple_sites'] = isset($postData['has_multiple_sites'])?TRUE:FALSE;
                    //print_r($postData);exit;
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
                                $this->pktdblib->_delete_by_column('customer_id',$id);
                            }
                            
                        if($_SESSION['application']['is_erpnext']){
                            $address = Modules::run('address/getAddress',['user_id'=>$id,'type'=>'customers']);
                            $customers = $this->pktdblib->custom_query('select * from erpnext_user where user_id='.$id);
                            if(!empty($customers)){
                                $customer = $customers[0];
                                $params = ['name'=>$customer['customer_name'],
                                        'customer_name'=>$postData['company_name'],
                                        'customer_type'=>'Company',
                                        'customer_group'=>'All Customer Groups',
                                        'customer_primary_contact' => $customer['contact_name'],
                                        'mobile_no' => $postData['contact_1'],
                                        'email_id' => $postData['primary_email'],
                                        'referral_code' => $postData['referral_code'],'doctype' => 'Customer'];
                                        isset($address[0])?$params['customer_primary_address'] = $address[0]['site_name']:'';
                                $erp = Modules::run('erpnext/update_customer',$params);
                            }
                        }
                        
                        if(array_key_exists('customer_zones', $this->input->post('data'))){
                            $chk = $this->pktdblib->custom_query('Select * from customer_zones where customer_id='.$id);
                            if(empty($chk)){
                                $zone['customer_id'] = $id;
                                $zone['zone_no'] = $this->input->post('data[customer_zones][zone_no]');
                                $zone['route_no'] = $this->input->post('data[customer_zones][route_no]');
                                $this->pktdblib->set_table("customer_zones");
                                $zone = $this->pktdblib->_insert($zone);
                            }else{
                                $zone['zone_no'] = $this->input->post('data[customer_zones][zone_no]');
                                $zone['route_no'] = $this->input->post('data[customer_zones][route_no]');
                                $this->db->where('customer_id', $id);
                                $this->db->update('customer_zones', $zone);
                            }
                            //echo $this->db->last_query();
                        }
                        /*if Tally integrated*/
                        if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){
                           // echo "hiiii";exit;
                            $tallyLedger = Modules::run('tally/import_ledger',$id, 'customers');
                            //print_r($tallyLedger);exit;
                        }
    					$msg = array('message' => "data updated successfully", 'class' => 'alert alert-success fade-in');
    					$this->session->set_flashdata('message', $msg);
    				}
    				else {
    					$msg = array('message' => 'some problem occured while updating', 'class' => 'alert alert-danger fade-in');
    					$this->session->set_flashdata('message', $msg);
    				}
                    /*if Tally integrated*/
                    if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){
                        $tallyLedger = Modules::run('tally/import_ledger', $id, 'customers');
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
    		$czones = $this->pktdblib->custom_query('select zone_no, route_no from customer_zones where customer_id='.$id);
    		if(!empty($czones)){
    		    $data['values_posted']['customer_zone'] = $czones[0];
    		}
        }
        
        
        //$data['values_posted']['customers'] = $this->get_customer_details($id);
    	$data['customers'] = $data['values_posted']['customers'];
    	if($_SESSION['application']['multiple_company']){
            $this->pktdblib->set_table('companies');
            $companies = $this->pktdblib->get_where_custom('is_active', true);
            $data['companies'] = $companies->result_array();
            $data['option']['company'][0] = 'Select Company';
            foreach ($data['companies'] as $key => $company) {
                $data['option']['company'][$company['id']] = $company['company_name'];
            }
    	}

        $data['categories'] = $this->customer_model->get_categorylist_for_customer();
        /*print_r($data['categories']);
        exit;*/
        $data['option']['category'][0] = 'Select Category';
        foreach($data['categories'] as $categoryKey => $category){
            
            $data['option']['category'][$category['id']] = $category['category_name'];
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
    	
        $data['meta_title'] = "Edit Customer";
        $data['title'] = "ERP Edit Customer";
        $data['meta_description'] = "Edit Customer";
       	$data['content'] = 'customers/admin_edit';
        $type = 'customers';
        $loginId = $id;
        $userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>'customers']);
        //echo $this->db->last_query();exit;
        //echo '<pre>';print_r($userRoles);//exit;
        if($userRoles!=FALSE && count($userRoles)){
            //echo "hii";exit;
            $loginId = $userRoles[0]['login_id'];
            $type = 'login';
        }
        //echo "hello";exit;
        //echo $loginId;echo $type;exit;
        /*$addressListData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=address', 'condition'=>['address.user_id'=>$loginId, 'address.type'=>$type], 'module'=>'customers'];
        //echo '<pre>';print_r($addressListData);exit;
        $data['addressList'] = Modules::run("address/admin_address_listing", $addressListData);
        //echo '<pre>';print_r($data['addressList']);exit;
        $this->pktdblib->set_table('address');
        $addressData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=address', 'module'=>'customers', 'user_id'=>$loginId, 'type'=>$type, 'user_detail'=>$data['customers']];
        if($this->input->get('address_id')) { 
            $addressData['address'] = $this->pktdblib->get_where($this->input->get('address_id'));
            $data['address'] = Modules::run("address/admin_edit_form", $addressData);
        }else {
            $data['address'] = Modules::run("address/admin_add_form", $addressData);
        }*/
        
        /* Bank Account Related Code Starts Here  */
        /*$bankAccountListData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=bank_account', 'condition'=>['bank_accounts.user_id'=>$loginId, 'bank_accounts.user_type'=>$type], 'module'=>'customers'];
        $this->pktdblib->set_table('bank_accounts');
        $data['bankAccountList'] = Modules::run("bank_accounts/admin_index_listing", $bankAccountListData);
        //print_r($data['bankAccountList']);exit;

        $bankAccountData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=bank_account', 'module'=>'customers', 'user_id'=>$loginId, 'type'=>$type, 'user_detail'=>$data['customers']];
        if($this->input->get('bank_account_id')) { 
            $bankAccountData['bank_accounts'] = Modules::run("bank_accounts/account_details", $this->input->get('bank_account_id'));
            $data['bank_account'] = Modules::run("bank_accounts/admin_edit_form", $bankAccountData);
        }else {
            $data['bank_account'] = Modules::run("bank_accounts/admin_add_form", $bankAccountData);
        }*/
        /*Bank account ends*/

        /*Document Uploads*/
        /*$documentListData = [ 'condition'=>['user_documents.user_id'=>$loginId, 'user_documents.user_type'=>$type], 'module'=>'customers'];
        //$this->address_model->set_table('bank_accounts');
        $data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

        $documentData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=document', 'module'=>'customers', 'user_id'=>$loginId, 'type'=>$type, 'user_detail'=>$data['customers']];
        
        $data['document'] = Modules::run("upload_documents/admin_add_form", $documentData);
        */
        /*Customer Sites*/
        //print_r($data['customers']);
        
        // $productListData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=customer_services', 'condition'=>['customer_services.customer_id'=>$id], 'module'=>'customers'];

        // $data['productList'] = Modules::run("products/admin_customer_product_listing", $id);

        //print_r($data['productList']);exit;

        // $this->pktdblib->set_table('customer_services');
        // $productData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=customer_services', 'module'=>'customers', 'customer_id'=>$id, 'user_detail'=>$data['customers']];

        //echo '<pre>';print_r($productData);exit;
        /*if($this->input->get('product_id')) { 
            $productData['product'] = $this->pktdblib->get_where($this->input->get('product_id'));
            $data['product'] = Modules::run("products/admin_edit_form", $productData);
        }else {
            $data['product'] = Modules::run("products/customer_products", $productData);
        }*/
            // $data['product'] = Modules::run("products/admin_add_customer_products", $data['id']);
        //exit();

        $data['js'][0] = "<script type = 'text/javascript'>
            $('.maxCurrentDate').on('ready', 'blur', function(){
                //alert('hii');
            });
            $('.create-call').on('click', function(e){
                //console.log('hiii');
                var customerId = $(this).attr('data-id');
                var addressId = $(this).attr('data-address-id');
                //console.log(customerId);
                //console.log(addressId);
                window.location.replace('calls/admin_add?customer_id='+customerId);

            });
        </script>
        ";
        
        if(!($this->input->get('tab'))){
    	    $data['tab'] = 'personal_info';
    	}
    	else{
    	    $data['tab'] = $this->input->get('tab');
    	    
    	}
    	$data['js'][] = '<script type="text/javascript">
    	    $(document).ready(function(){
    	        var tab = $("#tabing").val();
    	        $("."+tab).click();
    	        
    	    });
    	</script>';	
        
        $data['customerHasZone'] = false;
        $chkzone = $this->pktdblib->custom_query('SHOW TABLES LIKE "customer_zones"');
        
        if(!empty($chkzone)){
            $data['customerHasZone'] = true;
        }
        echo Modules::run('templates/admin_template', $data);
    }
    
    function admin_edit_new($id = NULL) {

    	if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST['data'];
    		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
    		$this->form_validation->set_rules('data[customers][first_name]','first name', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[customers][surname]', 'surname', 'max_length[255]');
    		$this->form_validation->set_rules('data[customers][company_name]', 'company_name', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[customers][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
    		$this->form_validation->set_rules('data[customers][primary_email]', 'primary_email', 'required|max_length[255]|valid_email');
    		/*$this->form_validation->set_rules('data[customers][pan_no]', 'pan no', 'is_unique[customers.pan_no]|alpha_numeric');
            $this->form_validation->set_rules('data[customers][gst_no]', 'gst_no', 'is_unique[customers.gst_no]');*/
    		
    		if($this->form_validation->run('customers')!== FALSE) {
    			$profileImg = '';
    			$postData = $data['values_posted']['customers'];
                if(isset($postData['is_active']))
                    $postData['is_active'] = true;
                else
                    $postData['is_active'] = false;
    		    //print_r($data['values_posted']);exit;
    			if(empty($error)) {
    				//print_r($this->customer_model->_update_customer($id, $postData));
                    $this->pktdblib->set_table("customers");
                    $postData['primary_email'] = (empty(trim($postData['primary_email']))?NULL:trim($postData['primary_email']));
                    $postData['has_multiple_sites'] = isset($postData['has_multiple_sites'])?TRUE:FALSE;
                    //print_r($postData);exit;
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
                                $this->pktdblib->_delete_by_column('customer_id',$id);
                            }
                            
                            if(array_key_exists('customer_zones', $this->input->post('data'))){
                                $chk = $this->pktdblib->custom_query('Select * from customer_zones where customer_id='.$id);
                                if(empty($chk)){
                                    $zone['customer_id'] = $id;
                                    $zone['zone_no'] = $this->input->post('data[customer_zones][zone_no]');
                                    $zone['route_no'] = $this->input->post('data[customer_zones][route_no]');
                                    $this->pktdblib->set_table("customer_zones");
                                    $zone = $this->pktdblib->_insert($zone);
                                }else{
                                    $zone['zone_no'] = $this->input->post('data[customer_zones][zone_no]');
                                    $zone['route_no'] = $this->input->post('data[customer_zones][route_no]');
                                    $this->db->where('customer_id', $id);
                                    $this->db->update('customer_zones', $zone);
                                }
                                //echo $this->db->last_query();
                            }
                            /*if Tally integrated*/
                            if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){
                               // echo "hiiii";exit;
                                $tallyLedger = Modules::run('tally/import_ledger',$id, 'customers');
                                //print_r($tallyLedger);exit;
                            }
    					$msg = array('message' => "data updated successfully", 'class' => 'alert alert-success fade-in');
    					$this->session->set_flashdata('message', $msg);
    				}
    				else {
    					$msg = array('message' => 'some problem occured while updating', 'class' => 'alert alert-danger fade-in');
    					$this->session->set_flashdata('message', $msg);
    				}
                    /*if Tally integrated*/
                    if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){
                        $tallyLedger = Modules::run('tally/import_ledger', $id, 'customers');
                    }
    				redirect(custom_constants::edit_customer_url_new."/".$id.'?tab=address');
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
    		$czones = $this->pktdblib->custom_query('select zone_no, route_no from customer_zones where customer_id='.$id);
    		if(!empty($czones)){
    		    $data['values_posted']['customer_zone'] = $czones[0];
    		}
        }
        
        
        //$data['values_posted']['customers'] = $this->get_customer_details($id);
    	$data['customers'] = $data['values_posted']['customers'];
    	if($_SESSION['application']['multiple_company']){
            $this->pktdblib->set_table('companies');
            $companies = $this->pktdblib->get_where_custom('is_active', true);
            $data['companies'] = $companies->result_array();
            $data['option']['company'][0] = 'Select Company';
            foreach ($data['companies'] as $key => $company) {
                $data['option']['company'][$company['id']] = $company['company_name'];
            }
    	}

        $data['categories'] = $this->customer_model->get_categorylist_for_customer();
        /*print_r($data['categories']);
        exit;*/
        $data['option']['category'][0] = 'Select Category';
        foreach($data['categories'] as $categoryKey => $category){
            
            $data['option']['category'][$category['id']] = $category['category_name'];
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

    	$data['id'] = $id;
    	
        $data['meta_title'] = "Edit Customer";
        $data['title'] = "ERP Edit Customer";
        $data['meta_description'] = "Edit Customer";
       	$data['content'] = 'customers/admin_edit_new';
        $type = 'customers';
        $loginId = $id;
        $userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>'customers']);
        //echo $this->db->last_query();exit;
        //echo '<pre>';print_r($userRoles);//exit;
        if($userRoles!=FALSE && count($userRoles)){
            //echo "hii";exit;
            $loginId = $userRoles[0]['login_id'];
            $type = 'login';
        }
        //echo "hello";exit;
        //echo $loginId;echo $type;exit;
        $data['js'][0] = "<script type = 'text/javascript'>
            $('.maxCurrentDate').on('ready', 'blur', function(){
                //alert('hii');
            });
            $('.create-call').on('click', function(e){
                //console.log('hiii');
                var customerId = $(this).attr('data-id');
                var addressId = $(this).attr('data-address-id');
                //console.log(customerId);
                //console.log(addressId);
                window.location.replace('calls/admin_add?customer_id='+customerId);

            });
        </script>
        ";
        
        if(!($this->input->get('tab'))){
    	    $data['tab'] = 'personal_info';
    	}
    	else{
    	    $data['tab'] = $this->input->get('tab');
    	    
    	}
    	$data['js'][] = '<script type="text/javascript">
    	    $(document).ready(function(){
    	        var tab = $("#tabing").val();
    	        $("."+tab).click();
    	        
    	    });
    	</script>';	
        $data['customerHasZone'] = false;
        $chkzone = $this->pktdblib->custom_query('SHOW TABLES LIKE "customer_zones"');
        
        if(!empty($chkzone)){
            $data['customerHasZone'] = true;
        }
        echo Modules::run('templates/admin_template', $data);
    }
    
    function siteTab($id){
        $data['customers'] = $this->get_customer_details($id);
        
        $siteListData = ['condition'=>['customer_sites.customer_id'=>$id, 'customer_sites.is_active'=>TRUE], 'module'=>'customers'];
            //$this->address_model->set_table('bank_accounts');
           
            //print_r($data['siteList']);exit;
        $siteData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=sites', 'module'=>'customers', 'customer_id'=>$id, 'type'=>'customers', 'user_detail'=>$data['customers']];
        //echo $this->input->get('site_id');exit;
        if($this->input->get('site_id')){ 
            
            $siteData['values_posted']['customer_sites'] = Modules::run("customer_sites/get_site_details", $this->input->get('site_id'));
            /*echo '<pre>';
            print_r($siteData['values_posted']['customer_sites']);exit;*/
            $siteData['values_posted']['address'] = Modules::run("address/address_details", $siteData['values_posted']['customer_sites']['address_id']);
            /*echo '<pre>';
            print_r($siteData);exit;*/
            echo Modules::run("customer_sites/admin_add_couriersite_form", $siteData);
            //print_r($data['bank_account']);exit;
        }else{ //print_r($siteData);
            echo Modules::run("customer_sites/admin_add_couriersite_form", $siteData);
        }
        
         echo Modules::run("customer_sites/admin_index_listing", $siteListData);
    }
    
    function admin_order_view($id) {
        $this->pktdblib->set_table('orders');
        $order = $this->pktdblib->get_where_custom('customer_id',$id);
        $data['order'] = $order->result_array();
        if (!empty($data['order'])) {
            $this->pktdblib->set_table('brokers');
            $data['broker'] = $this->pktdblib->get_where($data['order'][0]['broker_id']);
        }
        echo Modules::run("orders/admin_ord_view", $data);

    }

    function admin_bank_view($id, $view = NULL) {
        //echo $id;exit;
        $this->pktdblib->set_table('bank_accounts');
        $order = $this->pktdblib->get_where_custom('user_id',$id);
        //echo $this->db->last_query();exit;
        $data['accounts'] = $order->result_array();
        $data['view'] = $view;
        $data['module'] = 'customers';
        //echo "<pre>"; print_r($data);exit;
        echo Modules::run("bank_accounts/admin_index_listing", $data);

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

    function get_Customer_list_dropdown($data = []){
        $this->pktdblib->set_table('customers');
        $sql = 'Select * from customers where 1=1';
        if(isset($data['customer_id'])){
            $sql.=' AND id='.$data['customer_id'];
        }else{
            $sql.=' and is_active=true';
        }
        $customers = $this->pktdblib->custom_query($sql);//get_active_list();
        //print_r($customers);
        $dropDown = [''=>'Select Customer'];
        foreach ($customers as $key => $customer) {
            $dropDown[$customer['id']] = $customer['first_name']." ".$customer['middle_name']." ".$customer['surname'];
            if(!empty($customer['emp_code']))
                $dropDown[$customer['id']].=' | '.$customer['emp_code'];

            if(!empty($customer['company_name']))
                $dropDown[$customer['id']].=' | '.$customer['company_name'];
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

    function upload_customer(){
        $this->pktdblib->set_table('companies');
        $companies = $this->pktdblib->get_where_custom('is_active', true);
        $data['companies'] = $companies->result_array();
        $data['option']['company'][0] = 'Select Company';
        foreach ($data['companies'] as $key => $company) {
            $data['option']['company'][$company['id']] = $company['company_name'];
        }
        $data['meta_title'] = "Upload Customers";
        $data['meta_description'] = "Upload Customers";
        $data['meta_keyword'] = "Upload Customers";
        $data['content'] = 'customers/upload_customers';
        echo Modules::run('templates/admin_template', $data);
        //$this->load->view('customers/upload_customers');
    }

    function upload_customer2(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<pre>';
            if(!empty($_FILES)) {
                $fname = $_FILES['sel_file']['name'];
                $chk_ext = explode('.',$fname);
                if(end($chk_ext)=='xlsx' || end($chk_ext) == 'xls' || end($chk_ext) == 'csv') {
                    $filename = $_FILES['sel_file']['tmp_name'];
                    $this->load->library('excel');
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($filename);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        if ($row == 1) {
                            $header[$row][$column] = $data_value;
                        } else {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                    foreach ($data['values'] as $xlsxkey => $xlsxvalue) {
                        //print_r($xlsxvalue);
                        foreach ($xlsxvalue as $key => $value) {
                            $xlsxUploadedData[$xlsxkey-2][] = $value;
                        }
                    }
                    
                    $companyId = $this->input->post('data[company_customers][company_id]');
                    $customerAddress = [];
                    $address = ['address_1', 'address_2', 'country_id', 'state_id', 'city_id','area_id', 'pincode', 'site_name'];
                    $customerArray=  [];
                    $customers = ['first_name','middle_name', 'surname', 'gender', 'company_name', 'primary_email', 'secondary_email', 'contact_1', 'contact_2', 'dob', 'joining_date', 'blood_group', 'emp_code'];

                        $error = [];
                    foreach ($xlsxUploadedData as $xlskey => $xlsvalue) {
                        $counter = 0;
                        $countryId = 0;
                        $stateId = 0;
                        $cityId = 0;
                        $areaId = 0;
                        /*for($i=6; $i<=13; $i++){
                            $customerAddress[$xlskey]['user_id'] = $companyId;
                            $customerAddress[$xlskey][$address[$counter]] = $xlsvalue[$i];
                            $counter = $counter+1;
                        }
                        //print_r($customerAddress);exit;
                        $this->pktdblib->set_table('countries');
                        $countries = $this->pktdblib->get_where_custom('name', $customerAddress[$xlskey]['country_id']);
                        $country = $countries->row_array();
                        if(count($country)>0){
                            $countryId = 1;
                        }
                        else{
                            if (!empty($customerAddress[$xlskey]['country_id'])) {
                                $countryData = ['name'=>$customerAddress[$xlskey]['country_id'], 'is_active' => 1,'created' => date('Y-m-d H:i:s') ,'modified' => date('Y-m-d H:i:s') ];
                                $this->pktdblib->set_table('countries');
                                $id = $this->pktdblib->_insert($countryData);
                                $countryId = $id['id'];
                            }
                            $customerAddress[$xlskey]['country_id'] = $countryId;
                        }
                        $customerAddress[$xlskey]['country_id'] = $countryId;
                        $this->pktdblib->set_table('states');
                        $states = $this->pktdblib->get_where_custom('state_name', $customerAddress[$xlskey]['state_id']);
                        $state = $states->row_array();
                        if(count($state)>0){
                            $stateId = $state['id'];
                        }
                        else {
                            if(trim($customerAddress[$xlskey]['state_id']) == '-'){
                                $stateId = 1;
                            }
                            else{
                                $stateData = ['country_id'=>$customerAddress[$xlskey]['country_id'], 'state_name'=>$customerAddress[$xlskey]['state_id'], 'is_active' => 1,'created' => date('Y-m-d H:i:s') ,'modified' => date('Y-m-d H:i:s') ];
                                $this->pktdblib->set_table('states');
                                $id = $this->pktdblib->_insert($stateData);
                                $stateId = $id['id'];
                            }
                        }
                        $customerAddress[$xlskey]['state_id'] = $stateId;
                        $this->pktdblib->set_table('cities');
                        $cities = $this->pktdblib->get_where_custom('city_name like ', $customerAddress[$xlskey]['city_id']);
                        if($cities->num_rows()>0){
                            $city = $cities->row_array();
                            $cityId = $city['id'];
                        }
                        else {
                            if(trim($customerAddress[$xlskey]['city_id']) == '-'){ 
                                //echo "hii";exit;
                                $cityId = 1;
                            }
                            else{ 
                                $cityData = ['country_id'=>$customerAddress[$xlskey]['country_id'], 'state_id'=>$customerAddress[$xlskey]['state_id'], 'city_name'=>$customerAddress[$xlskey]['city_id'], 'is_active' => 1,'created' => date('Y-m-d H:i:s') ,'modified' => date('Y-m-d H:i:s') ];
                                $this->pktdblib->set_table('cities');
                                $id = $this->pktdblib->_insert($cityData);
                                $cityId = $id['id'];
                            }
                        }
                        $customerAddress[$xlskey]['city_id'] = $cityId;
                        $area = $this->pktdblib->custom_query("select * from areas where city_id=".$cityId. " and area_name='".$customerAddress[$xlskey]['area_id']."'");
                        if(count($area)>0){
                            $areaId = $area[0]['id'];
                        }
                        else {
                            if(trim($customerAddress[$xlskey]['area_id']) == '-'){
                                $areaId = 1;
                            }
                            else{
                                $areaData = ['city_id'=>$customerAddress[$xlskey]['city_id'], 'area_name'=>$customerAddress[$xlskey]['area_id'], 'is_active' => 1,'created' => date('Y-m-d H:i:s') ,'modified' => date('Y-m-d H:i:s') ];
                                $this->pktdblib->set_table('areas');
                                $id = $this->pktdblib->_insert($areaData);
                                $areaId = $id['id'];
                            }
                        }

                        $customerAddress[$xlskey]['area_id'] = $areaId;
                        $customerAddress[$xlskey]['type'] = 'customers';
                        $this->pktdblib->set_table("address");
                        $addressId = $this->pktdblib->_insert($customerAddress[$xlskey]);*/
                        $counter = 0;
                        $id = 0;
                        for($i=0; $i<11; $i++){
                            if($i==0){
                                $name = explode(' ', trim($xlsvalue[$i]));

                                if(count($name)>2){
                                    for($k=0; $k<=2; $k++){
                                        $customerArray[$xlskey][$customers[$k]] =  $name[$k];
                                    }
                                }elseif(count($name)==2){
                                    $customerArray[$xlskey][$customers[0]] =  $name[0];
                                    $customerArray[$xlskey][$customers[1]] =  '';
                                    $customerArray[$xlskey][$customers[2]] =  $name[1];
                                }else{
                                    $customerArray[$xlskey][$customers[0]] =  $name[0];
                                    $customerArray[$xlskey][$customers[1]] =  '';
                                    $customerArray[$xlskey][$customers[2]] =  '';
                                }
                                $counter=count($customerArray[$xlskey]);
                                
                            }
                            else{
                                
                                $customerArray[$xlskey][$customers[$counter]] =  ($xlsvalue[$i]!='-')?$xlsvalue[$i]:'';
                                //$customerArray[$xlskey]['customer_id'] = $companyId;
                                $counter = $counter+1;
                            }

                        }
                        if(is_numeric($customerArray[$xlskey]['dob'])){

                         $customerArray[$xlskey]['dob'] = $this->pktlib->excel_to_php_date($customerArray[$xlskey]['dob']);
                        } else {
                                //echo$customerArray[$xlskey]['dob'];
                                $customerArray[$xlskey]['dob'] = $this->pktlib->dmYtoYmd($customerArray[$xlskey]['dob']);
                        }
                        if(is_numeric($customerArray[$xlskey]['joining_date'])){
                            $customerArray[$xlskey]['joining_date'] = $this->pktlib->excel_to_php_date($customerArray[$xlskey]['joining_date']);
                        }
                        //print_r($customerArray[$xlskey]);exit;
                        $this->pktdblib->set_table('customers');
                        $checkContactExist = $this->pktdblib->get_where_custom('contact_1', $customerArray[$xlskey]['contact_1']);
                        if($checkContactExist->num_rows()>0){
                            echo "Contact exist";//exit;
                            $error[$xlskey] = 'This Contact is Already Present'.$customerArray[$xlskey]['contact_1'];
                            continue;
                            //$this->session->set_flashdata('message',$msg); 
                        }else{
                            echo "contact not exist";//exit;
                            $this->pktdblib->set_table('customers');
                            $checkEmailExist = $this->pktdblib->get_where_custom('primary_email', $customerArray[$xlskey]['primary_email']);
                            if($checkEmailExist->num_rows()>0){
                                echo "Email exist in customer";//exit;
                                $error[$xlskey] = 'This Email  is Already Present in customer. Please Change'.$customerArray[$xlskey]['primary_email'];
                                continue; 
                            }else{
                                $this->pktdblib->set_table("customers");
                                $customerId = $this->pktdblib->_insert($customerArray[$xlskey]);
                                $id = $customerId['id'];
                                if($customerId){

                                if(trim($customerArray[$xlskey]['emp_code']) == '-' || $customerArray[$xlskey]['emp_code']== '' ) {
                                    //echo "emp code blank";//exit;
                                    $empCode = $this->create_cust_code($customerId['id']);
                                    $updArr['id'] = $customerId['id'];
                                    $updArr['emp_code'] = $empCode;
                                    $this->pktdblib->set_table('customers');
                                    $updCode = $this->edit_customer($customerId['id'], $updArr);
                                    //print_r($updCode);exit;
                                }

                                $companyArray = [];
                                $companyArray['company_id'] = $this->input->post("data[company_customers][company_id]");
                                //print_r($this->input->post());exit;
                                $companyArray['customer_id'] = $id;

                                $this->pktdblib->set_table("companies_customers");
                                $companyCustomer = $this->pktdblib->_insert($companyArray);
                                }
                                $this->pktdblib->set_table('login');
                                $checkLoginExist = $this->pktdblib->get_where_custom('email', $customerArray[$xlskey]['primary_email']);
                                if($checkLoginExist->num_rows()>0){
                                $loginExist = $checkLoginExist->row_array();
                                $customerArray[$xlskey]['id'] = $loginExist['id'];
                                $login = Modules::run('login/register_customer_to_login', $customerArray[$xlskey]);
                                $msg = array('message'=>'Login Created Successfully', 'class'=>'alert alert-success');
                                    $this->session->set_flashdata('message',$msg); 
                                }
                            }
                            
                        }
                        $counter = 0;
                        for($j=11; $j<19; $j++){
                            $customerAddress[$xlskey]['user_id'] = $id;
                            $customerAddress[$xlskey][$address[$counter]] = $xlsvalue[$j];
                            $counter = $counter+1;
                            //print_r($customerAddress);
                        }
                        //print_r($customerAddress);
                        //exit;
                        $this->pktdblib->set_table('countries');
                        $countries = $this->pktdblib->get_where_custom('name', $customerAddress[$xlskey]['country_id']);
                        $country = $countries->row_array();
                        if(count($country)>0){
                            $countryId = $country['id'];
                        }else {
                            if(trim($customerAddress[$xlskey]['country_id']) == '-'){
                                $countryId = 1;
                            }
                            else{
                                $countryData = ['name'=>$customerAddress[$xlskey]['country_id'], 'is_active' => 1,'created' => date('Y-m-d H:i:s') ,'modified' => date('Y-m-d H:i:s') ];
                                $this->pktdblib->set_table('countries');
                                $id = $this->pktdblib->_insert($countryData);
                                $countryId = $id['id'];
                            }
                        }
                        $customerAddress[$xlskey]['country_id'] = $countryId;
                        $this->pktdblib->set_table("states");
                        $states = $this->pktdblib->get_where_custom('state_name', $customerAddress[$xlskey]['state_id']);
                        if($states->num_rows()>0){
                            $state = $states->row_array();
                            $stateId = $state['id'];
                        }else {
                            if(trim($customerAddress[$xlskey]['state_id']) == '-'){
                                $stateId = 1;
                            }
                            else{
                                $countryData = ['country_id'=>$countryId,'name'=>$customerAddress[$xlskey]['state_id'], 'is_active' => 1,'created' => date('Y-m-d H:i:s') ,'modified' => date('Y-m-d H:i:s') ];
                                $this->pktdblib->set_table('states');
                                $id = $this->pktdblib->_insert($stateData);
                                $stateId = $id['id'];
                            }
                        }
                        $customerAddress[$xlskey]['state_id'] = $stateId;
                        $this->pktdblib->set_table("cities");
                        $cities = $this->pktdblib->get_where_custom('city_name', $customerAddress[$xlskey]['city_id']);
                        if($cities->num_rows()>0){
                            $city = $cities->row_array();
                            $cityId = $city['id'];
                        }else {
                            if(trim($customerAddress[$xlskey]['city_id']) == '-'){
                                $cityId = 1;
                            }
                            else{
                                $cityData = ['country_id'=>$countryId, 'state_id'=>$stateId, 'name'=>$customerAddress[$xlskey]['city_id'], 'is_active' => 1,'created' => date('Y-m-d H:i:s') ,'modified' => date('Y-m-d H:i:s') ];
                                $this->pktdblib->set_table('cities');
                                $id = $this->pktdblib->_insert($cityData);
                                $cityId = $id['id'];
                            }
                        }
                        $customerAddress[$xlskey]['city_id'] = $cityId;
                        $this->pktdblib->set_table("areas");
                        $areas = $this->pktdblib->get_where_custom('area_name', $customerAddress[$xlskey]['area_id']);
                        if($areas->num_rows()>0){
                            $area = $areas->row_array();
                            $areaId = $area['id'];
                        }else {
                            if(trim($customerAddress[$xlskey]['area_id']) == '-'){
                                $areaId = 1;
                            }
                            else{
                                $areaData = ['city_id'=>$cityId,'area_name'=>$customerAddress[$xlskey]['area_id'], 'is_active' => 1,'created' => date('Y-m-d H:i:s') ,'modified' => date('Y-m-d H:i:s') ];
                                $this->pktdblib->set_table('areas');
                                $id = $this->pktdblib->_insert($areaData);
                                $areaId = $id['id'];
                            }
                        }
                        $customerAddress[$xlskey]['area_id'] = $areaId;
                        $customerAddress[$xlskey]['type'] = 'customers';
                        $this->pktdblib->set_table("address");
                        $addressId = $this->pktdblib->_insert($customerAddress[$xlskey]);
                        if($addressId['status'] =='success'){
                            $msg = array('message' => 'Address Added Successfully','class' => 'alert alert-success');
                            $this->session->set_flashdata('message',$msg);
                        }else{
                        $msg = array('message' => 'Some Error Occured while Adding Address. Please try again later','class' => 'alert alert-danger');
                        $this->session->set_flashdata('message',$msg);
                        //redirect("customer_sites/upload_multiple_sites");
                        redirect("customers/upload_customer");
                        }

                    }
                    //print_r($customerArray);exit;
                    
                }
            }
        }
        redirect(custom_constants::upload_customer_site_url);

        //redirect('customer_sites/upload_multiple_sites');
        //exit;
    }

    function customer_report(){
        $sql = "Select c.*, cc.company_name as company, a.address_1, a.address_2, a.pincode, a.site_name, cn.name, s.state_name, ct.city_name, ar.area_name from customers c left join companies_customers co on co.customer_id=c.id left join companies cc on cc.id=co.company_id left join address a on c.id=a.user_id and a.is_default=true and a.type='customers' and c.is_active=true left join countries cn on a.country_id=cn.id left join states s on a.state_id=s.id left join cities ct on a.city_id=ct.id left join areas ar on a.area_id=ar.id where c.is_active=true";
        $data['customers'] = $this->pktdblib->custom_query($sql);
        //echo '<pre>';print_r($data['customers']);exit;
        $data['meta_description'] = "Customer Report";
        $data['title'] = "Customer Report";
        $data['meta_title'] = "Customer Report";
        $data['meta_keyword'] = "Customer Report";
        $data['modules'][] = "customers";
        $data['methods'][] = "customer_reports";
        echo Modules::run('templates/report_template', $data);
    }

    function customer_reports(){
        $this->load->view('customers/customer_reports');
    }

    function admin_category_index(){
        $data['meta_title'] = 'Customer Category';
        $data['title'] = 'Module :: Customer';
        $data['heading'] = '<i class="fa fa-list margin-r-5"></i> Customer Categories';
        $data['meta_description'] = 'Customer Category';
        //$data['module'] = 'address';
        //$data['content'] = 'address/address_listing';
        $data['modules'][] = 'customers';
        $data['methods'][] = 'admin_category_listing';
        
        echo Modules::run("templates/admin_template", $data);   
    }

    function admin_category_listing($data = []) {
        $condition = [];
        if(isset($data['condition']))
            $condition = $data['condition'];
        //echo "string"; exit;
        $data['categories'] = $this->customer_model->get_category_list();
        $this->load->view("customers/admin_category_listing", $data);
    }

    function admin_add_category() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            /*echo '<pre>';
            print_r($_POST);exit;*/
            $data['values_posted'] = $_POST;
            $this->form_validation->set_rules('data[customer_categories][parent_id]', 'parent');
            $this->form_validation->set_rules('data[customer_categories][category_name]', 'category_name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customer_categories][slug]', 'slug', 'required|max_length[255]|is_unique[customer_categories.slug]');
            if($this->form_validation->run('customer_categories')!== false) {
                $error = [];
              // echo "validation run";exit;
                if(empty($error)) {
                    $post_data = $this->input->post('data[customer_categories]');
                    //echo '<pre>';print_r($post_data); exit;
                    $register_customer_category = json_decode($this->register_customer_category($post_data), true);
                    if($register_customer_category['status'] === 'success') {
                        $msg = array('message'=>'Customer Category Created Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    redirect(custom_constants::new_customer_category_url);

                    }
                    else {
                        $data['form_error'] = $register_customer_category['msg'];
                    }
                }
                else {
                    //print_r($error);
                    $msg = array('message'=>'unable to add customer', 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                }
            }
            else {
                $msg = array('message'=>'unable to add customer following error occured. '.validation_errors(), 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
            }

        }

        //echo "<pre>";
        $data['parents'] = $this->customer_model->get_category_dropdown_list();
        //print_r($data['parents']);
        $data['option']['parent'][0] = 'Select Parent';
        foreach($data['parents'] as $parentKey => $parent){
            
            $data['option']['parent'][$parent['id']] = $parent['category_name'];
        }
        
        $data['modules'][] = 'customers';
        $data['methods'][]= 'admin_add_customer_category';
        //$data['content'] = 'customers/add_customers';
        $data['meta_title'] = 'New Customer Category';
        $data['title'] = 'Module :: Customer';
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> Customer Categories';
        $data['js'][] = '<script type="text/javascript">
            
            $(document).on("submit", "#customer_categories", function(){
                alert(CKEDITOR.instances.editor1.getData());
                return false;
              $(".editor1").val(CKEDITOR.instances.editor1.getData());
              $(".editor1").show();
            });
        </script>';
        $data['meta_description'] = 'New customer Category';
        
        echo Modules::run('templates/admin_template', $data);
    }

    function register_customer_category($data) {
        $insert_data = $data;
        $this->customer_model->set_table("customer_categories");
        $id = $this->customer_model->_insert($insert_data);
        return json_encode(['message' =>'customer added Successfully', "status"=>"success", 'id'=> $id]);
    }

    function admin_add_customer_category() {
        $this->load->view('customers/admin_add_customer_category');
    }

    function admin_edit_category($id = NULL) {
        //check_user_login(FALSE);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $error=[];
           /* echo '<pre>';
            print_r($_POST);
            echo '</pre>';exit;*/
            $data['values_posted'] = $_POST['data'];
            $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
            $this->form_validation->set_rules("data[customer_categories][parent_id]", 'Parent', 'required');
            $this->form_validation->set_rules("data[customer_categories][category_name]", 'Category Name', 'required|max_length[255]');
            //$this->form_validation->set_rules("data[customer_categories][description]", 'Description', 'max_length[255]');
            $this->form_validation->set_rules("data[customer_categories][gst]", 'GST', 'max_length[255]');
            if($this->form_validation->run('customer_categories')!== FALSE){
                $postData = $this->input->post('data[customer_categories]');
                /*$vendorCategoryImg = '';
            //echo "hi";
                //print_r($postData);exit;
                if(!empty($_FILES['image_name_1']['name'])) {
                    $vendorCategoryFileValidationParams = ['file'=>$_FILES['image_name_1'], 'path'=>'../content/uploads/vendor_categories', 'fieldname'=>'image_name_1', 'ext'=>'jpeg|png|jpg|gif', 'arrindex'=>'image_name_1'];
                //print_r($vendorCategoryFileValidationParams);exit;
                    $vendorCategoryImg = $this->pktlib->upload_single_file($vendorCategoryFileValidationParams);
                    if(empty($vendorCategoryImg['error'])) {
                        $postData['image_name_1'] = $vendorCategoryImg['filename'];
                        unset($postData['image_name_1_2']);
                    }
                    else {
                        $error['image_name_1'] = $vendorCategoryImg['error'];
                    }
                }
                else {
                    $postData['image_name_1'] = $postData['image_name_1_2'];
                    unset($postData['image_name_1_2']);
                }
                //print_r($_FILES);
                if(!empty($_FILES['image_name_2']['name'])) {
                    $vendorCategoryFileValidationParams = ['file'=>$_FILES['image_name_2'], 'path'=>'../content/uploads/vendor_categories', 'fieldname'=>'image_name_2', 'ext'=>'jpeg|png|jpg|gif', 'arrindex'=>'image_name_2'];
                    $vendorCategoryImg = $this->pktlib->upload_single_file($vendorCategoryFileValidationParams);
                    //print_r($vendorCategoryImg);exit;
                    if(empty($vendorCategoryImg['error'])) {
                        $postData['image_name_2'] = $vendorCategoryImg['filename'];
                        unset($postData['image_name_2_2']);
                    }
                    else {
                        $error['image_name_2'] = $vendorCategoryImg['error'];
                    }
                }
                else {
                    $postData['image_name_2'] = $postData['image_name_2_2'];
                    unset($postData['image_name_2_2']);
                }*/
                //print_r($postData);exit;
                if(empty($error)) {
                    //print_r($postData);exit;
                    $this->pktdblib->set_table('customer_categories');
                    if($this->pktdblib->_update($id, $postData)) {
                        $msg = array('message'=>'Data Updated Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    }
                    else {
                        $msg = array('message'=>'Some problem occured', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    }
                    redirect(custom_constants::edit_customer_category_url."/".$id);
                } 
                else {
                    //print_r($error);
                    $msg = array('message'=>'some error occured while uploading'.$error, 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                }
            } 
            else {
                //$data['values_posted']['customer_categories']['image_name_1'] = $data['values_posted']['customer_categories']['image_name_1_2']; 
                //$data['values_posted']['customer_categories']['image_name_2'] = $data['values_posted']['customer_categories']['image_name_2_2']; 
                //echo validation_errors();exit;
                $msg = array('message'=>'Some validation error occured'.validation_errors(), 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
            }
        }  
        else { //echo $id;
            $data['customer_categories'] = $this->get_customer_category_details($id);
            $data['values_posted']['customer_categories'] = $data['customer_categories'];
            //print_r($data['values_posted']['customer_categories']);

        } 
    
        $data['parents'] = $this->get_customer_categories_list();
        //echo '<pre>';print_r($data['parents']);exit;
        $data['option']['parent'][0] = 'Select Parent';
            /*echo '<pre>';*/

        foreach ($data['parents'] as $parentKey => $parent) {
            $data['option']['parent'][$parent['id']] = $parent['category_name'];
            
         } 
         //print_r($data['option']['parent'][$parent['id']]);
        $data['id'] = $id;
        $data['modules'][] = 'customers';
        $data['methods'][]= 'admin_edit_customer_category';
        $data['title'] = 'Edit customer Category';
        $data['meta_title'] = 'Edit customer Category';
        $data['meta_description'] = 'Edit customer Category';
        $data['title'] = 'Module :: customer';
        $data['heading'] = '<i class="fa fa-pencil margin-r-5"></i> customer Categories';
        $data['js'][] = '<script type="text/javascript">
            
            $(document).on("submit", "#customer_categories", function(){
                alert(CKEDITOR.instances.editor1.getData());
                return false;
              $(".editor1").val(CKEDITOR.instances.editor1.getData());
              $(".editor1").show();
            });
        </script>';
        
        echo Modules::run('templates/admin_template', $data);
    }

    function admin_edit_customer_category() {
        $this->load->view('customers/admin_edit_customer_category');
    }

    function get_customer_category_details($id) {
        // echo "reached get_product_category_details";
        $this->pktdblib->set_table('customer_categories');
        $customerCategoryDetail = $this->pktdblib->get_where($id);
        //print_r($customerCategoryDetail);
        return $customerCategoryDetail;
    }

    function get_customer_categories_list() {
        $this->customer_model->set_table('customer_categories');
        $customerCategories = $this->customer_model->get_customer_category_list();
        return $customerCategories;
    }

    function customer_view($id = NULL){
        $sql = 'select c.id as customer_id, concat(c.first_name, " ", c.middle_name, " ", c.surname)as customer_name, comp.company_name, c_cat.category_name, a.id as address_id, a.address_1 as site_address_1, a.address_2 as site_address_2, cs.id as site_id, cs.site_name, concat(cs.first_name, " ", cs.middle_name, " ", cs.surname) as site_contact_person, css.warranty from customers c left join companies_customers c_cust on c.id=c_cust.customer_id left join customer_categories c_cat on c.customer_category_id=c_cat.id left join companies comp on c_cust.company_id=comp.id left join customer_sites cs on cs.customer_id=c.id left join address a on cs.address_id=a.id left join customer_site_services css on cs.id=css.customer_site_id where a.type="customers" order by c.id asc';
        $query = $this->pktdblib->custom_query($sql);
        echo '<pre>';print_r($query);exit;
    }

    function getCompanyWiseCustomers(){
        //echo "hiii";exit;
        if(!$this->input->post('params'))
            return;
        //echo json_encode($this->input->post('params'));
        $companyId = $this->input->post('params');
        //$companyId = 1;
        //echo $companyId;exit;
        $customerList = [0=>['id' => 0, 'text' =>'Customer Listed']];
        $customers = $this->pktdblib->custom_query('Select c.*, cc.id as company_id, cc.company_name from customers c inner join companies_customers comp_cust on c.id=comp_cust.customer_id left join companies cc on comp_cust.company_id=cc.id where comp_cust.company_id="'.$companyId.'" and c.is_active=true');
        //echo json_encode("hiii");exit;
        //echo json_encode($this->db->last_query());exit;
        //echo '<pre>';print_r($customers);exit;
        foreach ($customers as $key => $customer) {
            //print_r($customer);
            $customerList[$key+1]['id'] = $customer['id'];
            $customerList[$key+1]['text'] = $customer['first_name']." ".$customer['middle_name']." ".$customer['surname']." - ".$customer['company_name'];
        }
        //echo '<pre>';print_r($customerList);exit;
        //exit;

        echo json_encode($customerList);

    }

    function checkData(){
        $contact = $this->input->post();
        $response=false;
        // do check
        if ($this->data_exists($data) ) {
            //echo "hii";
            $response['status'] = true;
        }else{ //echo "hello";
            $response['status'] = false;
        }
        // echo json
        echo json_encode($response);
        exit;
    }

    function data_exists($data){
        //echo $data;exit;
        $this->pktdblib->set_table('customers');
        $contact = $this->pktdblib->get_where_custom($data['type'], $data['value']);
        if(count($contact)>0){
            
            return true;
        }else{
            return false;
        }
    }

    function getlistofledger()
    {
        ini_set('display_errors', 1);
        ini_set("memory_limit","500M");
        ini_set('max_execution_time', 400);   



        // $data =
        //                 "<ENVELOPE>" .  
        //                 "<HEADER>" .   
        //                 "<TALLYREQUEST>Export Data</TALLYREQUEST>" .  
        //                 "</HEADER>" .  
        //                 "<BODY>" .  
        //                 "<EXPORTDATA>" .  
        //                 "<REQUESTDESC>" .  
        //                 "<REPORTNAME>List of Accounts</REPORTNAME>" .  
        //                 "<STATICVARIABLES>" .  
        //                 "<SVEXPORTFORMAT>\$\$SysName:XML</SVEXPORTFORMAT>" .
        //                 "<SVFROMDATE>20190315</SVFROMDATE>" .
        //                 "<SVTODATE>20190331</SVTODATE>" .
        //                 "<ACCOUNTTYPE>Sales Orders Book</ACCOUNTTYPE>" .  
        //                 "<!--Other possible values for ACCOUNTTYPE tag are given below-->" .  
        //                 "<!--All Acctg. Masters, All Inventory Masters,All Statutory Masters-->" .  
        //                 "<!--Ledgers,Groups,Cost Categories,Cost Centres-->" .  
        //                 "<!--Units,Godowns,Stock Items,Stock Groups,Stock Categories-->" .  
        //                 "<!--Voucher types,Currencies,Employees,Budgets & Scenarios-->" .  
        //                 "</STATICVARIABLES>" .  
        //                 "</REQUESTDESC>" .  
        //                 "</EXPORTDATA>" .  
        //                 "</BODY>" .  
        //                 "</ENVELOPE>"; 
        $data = '<ENVELOPE>
        <HEADER>
        <TALLYREQUEST>Export Data</TALLYREQUEST>
        </HEADER>
        <BODY>
        <EXPORTDATA>
        <REQUESTDESC>
        <STATICVARIABLES>

                <SVFROMDATE>20190315</SVFROMDATE>
                <SVTODATE>20190331</SVTODATE>

        <SHOWCREATEDBY>YES</SHOWCREATEDBY>
        <SHOWPARTYNAME>Yes</SHOWPARTYNAME>

        <VOUCHERTYPENAME>Sales Order</VOUCHERTYPENAME>
        </STATICVARIABLES>

        <REPORTNAME>List of Accounts</REPORTNAME>
        <STATICVARIABLES>
            <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
        </STATICVARIABLES>

        </REQUESTDESC>
        </EXPORTDATA>
        </BODY>
        </ENVELOPE>';
        // $data = '<ENVELOPE>
        //     <HEADER>
        //     <VERSION>1</VERSION>
        //     <TALLYREQUEST>Export</TALLYREQUEST>
        //     <TYPE>Data</TYPE>
        //     <ID>Sales Orders Book</ID>
        //     </HEADER>
        //     <BODY>
        //     <DESC>
        //     <STATICVARIABLES>
        //     <EXPLODEFLAG>Yes</EXPLODEFLAG>
        //     <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
        //     <SVFROMDATE>15/03/2019</SVFROMDATE>
        //     <SVTODATE>31/03/2019</SVTODATE>
        //     </STATICVARIABLES>
        //     <TDL>
        //     <TDLMESSAGE>
        //     <REPORT NAME="Sales Orders Book">
        //     </REPORT>
        //     </TDLMESSAGE>
        //     </TDL>
        //     </DESC>
        //     </BODY>
        //     </ENVELOPE>';   

        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, 'http://111.119.207.78:9002/');  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data ); //$data = "XML data"  
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $response = curl_exec ($ch); 
        // print_r($response); 
        if(curl_errno($ch)){  
        echo curl_error($ch);  
        }  
        else{  
        print "<pre lang='xml'>" . htmlspecialchars($response) . "</pre>";  
        curl_close($ch);  
        }    
        return "";  
        }



    function importledger()
    {
        ini_set('display_errors', 1);
        ini_set("memory_limit","500M");
        $data = '<ENVELOPE>
 <HEADER>
  <TALLYREQUEST>Import Data</TALLYREQUEST>
 </HEADER>
 <BODY>
  <IMPORTDATA>
   <REQUESTDESC>
    <REPORTNAME>All Masters</REPORTNAME>
    <STATICVARIABLES>
     <SVCURRENTCOMPANY>AJS IMPEX  PVT. LTD. 2018-19</SVCURRENTCOMPANY>
    </STATICVARIABLES>
   </REQUESTDESC>
   <REQUESTDATA>
<TALLYMESSAGE xmlns:UDF="TallyUDF">
     <LEDGER NAME="A A INTERNATIONAL (NEW)" RESERVEDNAME="">
      <ADDRESS.LIST TYPE="String">
       <ADDRESS>OFFICE NO. 801, 8th FLOOR, C-2 WING,</ADDRESS>
       <ADDRESS>SKYLINE WEALTH SPACE, PREMIER ROAD,</ADDRESS>
       <ADDRESS>NEAR VIDYAVIHAR STATION, GHATKOPAR (W),</ADDRESS>
       <ADDRESS>MUMBAI-400086.</ADDRESS>
      </ADDRESS.LIST>
      <MAILINGNAME.LIST TYPE="String">
       <MAILINGNAME>A A INTERNATIONAL (NEW)</MAILINGNAME>
      </MAILINGNAME.LIST>
      <OLDAUDITENTRYIDS.LIST TYPE="Number">
       <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
      </OLDAUDITENTRYIDS.LIST>
      <STARTINGFROM>20160401</STARTINGFROM>
      <STREGDATE/>
      <LBTREGNDATE/>
      <SAMPLINGDATEONEFACTOR/>
      <SAMPLINGDATETWOFACTOR/>
      <ACTIVEFROM/>
      <ACTIVETO/>
      <CREATEDDATE>20171012</CREATEDDATE>
      <ALTEREDON>20181006</ALTEREDON>
      <VATAPPLICABLEDATE/>
      <EXCISEREGISTRATIONDATE/>
      <PANAPPLICABLEFROM/>
      <PAYINSRUNNINGFILEDATE/>
      <VATTAXEXEMPTIONDATE/>
      <GUID>572f2ebb-d04c-4c56-94f3-a79d8923e290-00002b57</GUID>
      <CURRENCYNAME>?</CURRENCYNAME>
      <EMAIL>aainternationalindia@gmail.com</EMAIL>
      <STATENAME/>
      <PINCODE>400086</PINCODE>
      <WEBSITE/>
      <INCOMETAXNUMBER>ABBFA1690E</INCOMETAXNUMBER>
      <SALESTAXNUMBER/>
      <INTERSTATESTNUMBER>27661100729C</INTERSTATESTNUMBER>
      <VATTINNUMBER>27661100729V</VATTINNUMBER>
      <COUNTRYNAME>India</COUNTRYNAME>
      <EXCISERANGE/>
      <EXCISEDIVISION/>
      <EXCISECOMMISSIONERATE/>
      <LBTREGNNO/>
      <LBTZONE/>
      <EXPORTIMPORTCODE/>
      <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
      <VATDEALERTYPE/>
      <PRICELEVEL/>
      <UPLOADLASTREFRESH/>
      <PARENT>Sundry Debtors - MARKET</PARENT>
      <SAMPLINGMETHOD/>
      <SAMPLINGSTRONEFACTOR/>
      <IFSCODE/>
      <NARRATION/>
      <REQUESTORRULE/>
      <GRPDEBITPARENT/>
      <GRPCREDITPARENT/>
      <SYSDEBITPARENT/>
      <SYSCREDITPARENT/>
      <TDSAPPLICABLE/>
      <TCSAPPLICABLE/>
      <GSTAPPLICABLE/>
      <CREATEDBY>AJS</CREATEDBY>
      <ALTEREDBY>AJS</ALTEREDBY>
      <TAXCLASSIFICATIONNAME/>
      <TAXTYPE>Others</TAXTYPE>
      <BILLCREDITPERIOD/>
      <BANKDETAILS/>
      <BANKBRANCHNAME/>
      <BANKBSRCODE/>
      <DEDUCTEETYPE/>
      <BUSINESSTYPE/>
      <TYPEOFNOTIFICATION/>
      <MSMEREGNUMBER/>
      <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
      <RELATEDPARTYID/>
      <RELPARTYISSUINGAUTHORITY/>
      <IMPORTEREXPORTERCODE/>
      <EMAILCC/>
      <DESCRIPTION/>
      <LEDADDLALLOCTYPE/>
      <TRANSPORTERID/>
      <LEDGERPHONE/>
      <LEDGERFAX/>
      <LEDGERCONTACT>A.A.INTERNATIONAL</LEDGERCONTACT>
      <LEDGERMOBILE>9320049212</LEDGERMOBILE>
      <RELATIONTYPE/>
      <MAILINGNAMENATIVE/>
      <STATENAMENATIVE/>
      <COUNTRYNAMENATIVE/>
      <BASICTYPEOFDUTY/>
      <GSTTYPE/>
      <EXEMPTIONTYPE/>
      <APPROPRIATEFOR/>
      <SUBTAXTYPE/>
      <STXNATUREOFPARTY/>
      <NAMEONPAN/>
      <USEDFORTAXTYPE/>
      <ECOMMMERCHANTID/>
      <PARTYGSTIN>27ABBFA1690E1ZR</PARTYGSTIN>
      <GSTDUTYHEAD/>
      <GSTAPPROPRIATETO/>
      <GSTTYPEOFSUPPLY/>
      <GSTNATUREOFSUPPLY/>
      <CESSVALUATIONMETHOD/>
      <PAYTYPE/>
      <PAYSLIPNAME/>
      <ATTENDANCETYPE/>
      <LEAVETYPE/>
      <CALCULATIONPERIOD/>
      <ROUNDINGMETHOD/>
      <COMPUTATIONTYPE/>
      <CALCULATIONTYPE/>
      <PAYSTATTYPE/>
      <PROFESSIONALTAXNUMBER/>
      <USERDEFINEDCALENDERTYPE/>
      <ITNATURE/>
      <ITCOMPONENT/>
      <NOTIFICATIONNUMBER/>
      <REGISTRATIONNUMBER/>
      <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
      <ABATEMENTNOTIFICATIONNO/>
      <STXDUTYHEAD/>
      <STXCLASSIFICATION/>
      <NOTIFICATIONSLNO/>
      <SERVICETAXAPPLICABLE/>
      <EXCISELEDGERCLASSIFICATION/>
      <EXCISEREGISTRATIONNUMBER/>
      <EXCISEACCOUNTHEAD/>
      <EXCISEDUTYTYPE/>
      <EXCISEDUTYHEADCODE/>
      <EXCISEALLOCTYPE/>
      <EXCISEDUTYHEAD/>
      <NATUREOFSALES/>
      <EXCISENOTIFICATIONNO/>
      <EXCISEIMPORTSREGISTARTIONNO/>
      <EXCISEAPPLICABILITY/>
      <EXCISETYPEOFBRANCH/>
      <EXCISEDEFAULTREMOVAL/>
      <EXCISENOTIFICATIONSLNO/>
      <TYPEOFTARIFF/>
      <EXCISEREGNO/>
      <EXCISENATUREOFPURCHASE/>
      <TDSDEDUCTEETYPEMST/>
      <TDSRATENAME/>
      <TDSDEDUCTEESECTIONNUMBER/>
      <PANSTATUS/>
      <DEDUCTEEREFERENCE/>
      <TDSDEDUCTEETYPE/>
      <ITEXEMPTAPPLICABLE/>
      <TAXIDENTIFICATIONNO/>
      <LEDGERFBTCATEGORY/>
      <BRANCHCODE/>
      <CLIENTCODE/>
      <BANKINGCONFIGBANK/>
      <BANKINGCONFIGBANKID/>
      <BANKACCHOLDERNAME/>
      <USEFORPOSTYPE/>
      <PAYMENTGATEWAY/>
      <TYPEOFINTERESTON>Voucher Date</TYPEOFINTERESTON>
      <BANKCONFIGIFSC/>
      <BANKCONFIGMICR/>
      <BANKCONFIGSHORTCODE/>
      <PYMTINSTOUTPUTNAME/>
      <PRODUCTCODETYPE/>
      <SALARYPYMTPRODUCTCODE/>
      <OTHERPYMTPRODUCTCODE/>
      <PAYMENTINSTLOCATION/>
      <ENCRPTIONLOCATION/>
      <NEWIMFLOCATION/>
      <IMPORTEDIMFLOCATION/>
      <BANKNEWSTATEMENTS/>
      <BANKIMPORTEDSTATEMENTS/>
      <BANKMICR/>
      <CORPORATEUSERNOECS/>
      <CORPORATEUSERNOACH/>
      <CORPORATEUSERNAME/>
      <IMFNAME/>
      <PAYINSBATCHNAME/>
      <LASTUSEDBATCHNAME/>
      <PAYINSFILENUMPERIOD/>
      <ENCRYPTEDBY/>
      <ENCRYPTEDID/>
      <ISOCURRENCYCODE/>
      <BANKCAPSULEID/>
      <SALESTAXCESSAPPLICABLE/>
      <VATTAXEXEMPTIONNATURE/>
      <VATTAXEXEMPTIONNUMBER/>
      <LEDSTATENAME>Maharashtra</LEDSTATENAME>
      <VATAPPLICABLE/>
      <PARTYBUSINESSTYPE/>
      <PARTYBUSINESSSTYLE/>
      <ISBILLWISEON>Yes</ISBILLWISEON>
      <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
      <ISINTERESTON>Yes</ISINTERESTON>
      <ALLOWINMOBILE>No</ALLOWINMOBILE>
      <ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>
      <ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>
      <ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>
      <ASORIGINAL>Yes</ASORIGINAL>
      <ISCONDENSED>No</ISCONDENSED>
      <AFFECTSSTOCK>No</AFFECTSSTOCK>
      <ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>
      <FORPAYROLL>No</FORPAYROLL>
      <ISABCENABLED>No</ISABCENABLED>
      <ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>
      <INTERESTONBILLWISE>Yes</INTERESTONBILLWISE>
      <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
      <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
      <USEFORVAT>No</USEFORVAT>
      <IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>
      <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
      <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
      <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
      <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
      <ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>
      <ISTDSEXPENSE>No</ISTDSEXPENSE>
      <ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>
      <ISRELATEDPARTY>No</ISRELATEDPARTY>
      <USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>
      <ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>
      <APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>
      <ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>
      <INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>
      <INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>
      <ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>
      <OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>
      <ISAGAINSTFORMC>No</ISAGAINSTFORMC>
      <ISCHEQUEPRINTINGENABLED>No</ISCHEQUEPRINTINGENABLED>
      <ISPAYUPLOAD>No</ISPAYUPLOAD>
      <ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>
      <ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>
      <ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>
      <CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>
      <ISTRANSPORTER>No</ISTRANSPORTER>
      <USEFORNOTIONALITC>No</USEFORNOTIONALITC>
      <ISECOMMOPERATOR>No</ISECOMMOPERATOR>
      <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
      <USEFORGRATUITY>No</USEFORGRATUITY>
      <ISTDSPROJECTED>No</ISTDSPROJECTED>
      <FORSERVICETAX>No</FORSERVICETAX>
      <ISINPUTCREDIT>No</ISINPUTCREDIT>
      <ISEXEMPTED>No</ISEXEMPTED>
      <ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>
      <ISSTXPARTY>No</ISSTXPARTY>
      <ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>
      <ISUSEDFORCVD>No</ISUSEDFORCVD>
      <LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>
      <ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>
      <ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>
      <ISSEZPARTY>No</ISSEZPARTY>
      <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
      <ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>
      <ISEDDSUPPORTED>No</ISEDDSUPPORTED>
      <HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>
      <HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>
      <HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>
      <HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>
      <HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>
      <HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>
      <HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>
      <HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>
      <HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>
      <HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>
      <ISEBANKINGENABLED>No</ISEBANKINGENABLED>
      <ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>
      <ISBATCHENABLED>No</ISBATCHENABLED>
      <ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>
      <HASEDDCITY>No</HASEDDCITY>
      <HASECHEQUECITY>No</HASECHEQUECITY>
      <ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>
      <HASCLIENTCODE>No</HASCLIENTCODE>
      <PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>
      <PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>
      <ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>
      <ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>
      <ISSCBUAE>No</ISSCBUAE>
      <ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>
      <ISSALARYGROUPED>No</ISSALARYGROUPED>
      <USEFORPURCHASETAX>No</USEFORPURCHASETAX>
      <AUDITED>No</AUDITED>
      <SAMPLINGNUMONEFACTOR>0</SAMPLINGNUMONEFACTOR>
      <SAMPLINGNUMTWOFACTOR>0</SAMPLINGNUMTWOFACTOR>
      <SORTPOSITION> 1000</SORTPOSITION>
      <ALTERID> 138126</ALTERID>
      <DEFAULTLANGUAGE>0</DEFAULTLANGUAGE>
      <RATEOFTAXCALCULATION>0</RATEOFTAXCALCULATION>
      <GRATUITYMONTHDAYS>0</GRATUITYMONTHDAYS>
      <GRATUITYLIMITMONTHS>0</GRATUITYLIMITMONTHS>
      <CALCULATIONBASIS>0</CALCULATIONBASIS>
      <ROUNDINGLIMIT>0</ROUNDINGLIMIT>
      <ABATEMENTPERCENTAGE>0</ABATEMENTPERCENTAGE>
      <TDSDEDUCTEESPECIALRATE>0</TDSDEDUCTEESPECIALRATE>
      <BENEFICIARYCODEMAXLENGTH>0</BENEFICIARYCODEMAXLENGTH>
      <ECHEQUEPRINTLOCATIONVERSION>0</ECHEQUEPRINTLOCATIONVERSION>
      <ECHEQUEPAYABLELOCATIONVERSION>0</ECHEQUEPAYABLELOCATIONVERSION>
      <EDDPRINTLOCATIONVERSION>0</EDDPRINTLOCATIONVERSION>
      <EDDPAYABLELOCATIONVERSION>0</EDDPAYABLELOCATIONVERSION>
      <PAYINSRUNNINGFILENUM>0</PAYINSRUNNINGFILENUM>
      <TRANSACTIONTYPEVERSION>0</TRANSACTIONTYPEVERSION>
      <PAYINSFILENUMLENGTH>0</PAYINSFILENUMLENGTH>
      <SAMPLINGAMTONEFACTOR/>
      <SAMPLINGAMTTWOFACTOR/>
      <OPENINGBALANCE/>
      <CREDITLIMIT/>
      <GRATUITYLIMITAMOUNT/>
      <ODLIMIT/>
      <TEMPGSTCGSTRATE>0</TEMPGSTCGSTRATE>
      <TEMPGSTSGSTRATE>0</TEMPGSTSGSTRATE>
      <TEMPGSTIGSTRATE>0</TEMPGSTIGSTRATE>
      <TEMPISVATFIELDSEDITED/>
      <TEMPAPPLDATE/>
      <TEMPCLASSIFICATION/>
      <TEMPNATURE/>
      <TEMPPARTYENTITY/>
      <TEMPBUSINESSNATURE/>
      <TEMPVATRATE>0</TEMPVATRATE>
      <TEMPADDLTAX>0</TEMPADDLTAX>
      <TEMPCESSONVAT>0</TEMPCESSONVAT>
      <TEMPTAXTYPE/>
      <TEMPMAJORCOMMODITYNAME/>
      <TEMPCOMMODITYNAME/>
      <TEMPCOMMODITYCODE/>
      <TEMPSUBCOMMODITYCODE/>
      <TEMPUOM/>
      <TEMPTYPEOFGOODS/>
      <TEMPTRADENAME/>
      <TEMPGOODSNATURE/>
      <TEMPSCHEDULE/>
      <TEMPSCHEDULESLNO/>
      <TEMPISINVDETAILSENABLE/>
      <TEMPLOCALVATRATE>0</TEMPLOCALVATRATE>
      <TEMPVALUATIONTYPE/>
      <TEMPISCALCONQTY/>
      <TEMPISSALETOLOCALCITIZEN/>
      <LEDISTDSAPPLICABLECURRLIAB/>
      <ISPRODUCTCODEEDITED/>
      <SERVICETAXDETAILS.LIST>      </SERVICETAXDETAILS.LIST>
      <LBTREGNDETAILS.LIST>      </LBTREGNDETAILS.LIST>
      <VATDETAILS.LIST>      </VATDETAILS.LIST>
      <SALESTAXCESSDETAILS.LIST>      </SALESTAXCESSDETAILS.LIST>
      <GSTDETAILS.LIST>      </GSTDETAILS.LIST>
      <LANGUAGENAME.LIST>
       <NAME.LIST TYPE="String">
        <NAME>A A INTERNATIONAL (NEW)</NAME>
       </NAME.LIST>
       <LANGUAGEID> 1033</LANGUAGEID>
      </LANGUAGENAME.LIST>
      <XBRLDETAIL.LIST>      </XBRLDETAIL.LIST>
      <AUDITDETAILS.LIST>      </AUDITDETAILS.LIST>
      <SCHVIDETAILS.LIST>      </SCHVIDETAILS.LIST>
      <EXCISETARIFFDETAILS.LIST>      </EXCISETARIFFDETAILS.LIST>
      <TCSCATEGORYDETAILS.LIST>      </TCSCATEGORYDETAILS.LIST>
      <TDSCATEGORYDETAILS.LIST>      </TDSCATEGORYDETAILS.LIST>
      <SLABPERIOD.LIST>      </SLABPERIOD.LIST>
      <GRATUITYPERIOD.LIST>      </GRATUITYPERIOD.LIST>
      <ADDITIONALCOMPUTATIONS.LIST>      </ADDITIONALCOMPUTATIONS.LIST>
      <EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>
      <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
      <BANKALLOCATIONS.LIST>      </BANKALLOCATIONS.LIST>
      <PAYMENTDETAILS.LIST>      </PAYMENTDETAILS.LIST>
      <BANKEXPORTFORMATS.LIST>      </BANKEXPORTFORMATS.LIST>
      <BILLALLOCATIONS.LIST>      </BILLALLOCATIONS.LIST>
      <INTERESTCOLLECTION.LIST>
       <INTERESTFROMDATE/>
       <INTERESTTODATE/>
       <INTERESTSTYLE>365-Day Year</INTERESTSTYLE>
       <INTERESTBALANCETYPE/>
       <INTERESTAPPLON/>
       <INTERESTFROMTYPE>Past Due Date</INTERESTFROMTYPE>
       <ROUNDTYPE/>
       <INTERESTRATE> 24</INTERESTRATE>
       <INTERESTAPPLFROM>0</INTERESTAPPLFROM>
       <ROUNDLIMIT>0</ROUNDLIMIT>
      </INTERESTCOLLECTION.LIST>
      <LEDGERCLOSINGVALUES.LIST>      </LEDGERCLOSINGVALUES.LIST>
      <LEDGERAUDITCLASS.LIST>      </LEDGERAUDITCLASS.LIST>
      <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
      <TDSEXEMPTIONRULES.LIST>      </TDSEXEMPTIONRULES.LIST>
      <DEDUCTINSAMEVCHRULES.LIST>      </DEDUCTINSAMEVCHRULES.LIST>
      <LOWERDEDUCTION.LIST>      </LOWERDEDUCTION.LIST>
      <STXABATEMENTDETAILS.LIST>      </STXABATEMENTDETAILS.LIST>
      <LEDMULTIADDRESSLIST.LIST>      </LEDMULTIADDRESSLIST.LIST>
      <STXTAXDETAILS.LIST>      </STXTAXDETAILS.LIST>
      <CHEQUERANGE.LIST>      </CHEQUERANGE.LIST>
      <DEFAULTVCHCHEQUEDETAILS.LIST>      </DEFAULTVCHCHEQUEDETAILS.LIST>
      <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
      <BRSIMPORTEDINFO.LIST>      </BRSIMPORTEDINFO.LIST>
      <AUTOBRSCONFIGS.LIST>      </AUTOBRSCONFIGS.LIST>
      <BANKURENTRIES.LIST>      </BANKURENTRIES.LIST>
      <DEFAULTCHEQUEDETAILS.LIST>      </DEFAULTCHEQUEDETAILS.LIST>
      <DEFAULTOPENINGCHEQUEDETAILS.LIST>      </DEFAULTOPENINGCHEQUEDETAILS.LIST>
      <CANCELLEDPAYALLOCATIONS.LIST>      </CANCELLEDPAYALLOCATIONS.LIST>
      <ECHEQUEPRINTLOCATION.LIST>      </ECHEQUEPRINTLOCATION.LIST>
      <ECHEQUEPAYABLELOCATION.LIST>      </ECHEQUEPAYABLELOCATION.LIST>
      <EDDPRINTLOCATION.LIST>      </EDDPRINTLOCATION.LIST>
      <EDDPAYABLELOCATION.LIST>      </EDDPAYABLELOCATION.LIST>
      <AVAILABLETRANSACTIONTYPES.LIST>      </AVAILABLETRANSACTIONTYPES.LIST>
      <LEDPAYINSCONFIGS.LIST>      </LEDPAYINSCONFIGS.LIST>
      <TYPECODEDETAILS.LIST>      </TYPECODEDETAILS.LIST>
      <FIELDVALIDATIONDETAILS.LIST>      </FIELDVALIDATIONDETAILS.LIST>
      <INPUTCRALLOCS.LIST>      </INPUTCRALLOCS.LIST>
      <GSTCLASSFNIGSTRATES.LIST>      </GSTCLASSFNIGSTRATES.LIST>
      <EXTARIFFDUTYHEADDETAILS.LIST>      </EXTARIFFDUTYHEADDETAILS.LIST>
      <VOUCHERTYPEPRODUCTCODES.LIST>      </VOUCHERTYPEPRODUCTCODES.LIST>
      <UDF:VATDEALERNATURE.LIST DESC="`VATDealerNature`" ISLIST="YES" TYPE="String" INDEX="10031">
       <UDF:VATDEALERNATURE DESC="`VATDealerNature`">Registered Dealer</UDF:VATDEALERNATURE>
      </UDF:VATDEALERNATURE.LIST>
     </LEDGER>
    </TALLYMESSAGE>
    </REQUESTDATA>
    </IMPORTDATA>
    </BODY>
    </ENVELOPE>';



        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, 'http://111.119.207.78:9002/');  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data ); //$data = "XML data"  
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $response = curl_exec ($ch); 
        // print_r($response); 
        if(curl_errno($ch)){  
        echo curl_error($ch);  
        }  
        else{  
        print "<pre lang='xml'>" . htmlspecialchars($response) . "</pre>";  
        curl_close($ch);  
        }    
        return "";  
        }



    function importledger1()
    {
        ini_set('display_errors', 1);
        ini_set("memory_limit","500M");
        $data = '<ENVELOPE>
    <HEADER>
    <VERSION>1</VERSION>
    <TALLYREQUEST>EXPORT</TALLYREQUEST>
    <TYPE>COLLECTION</TYPE>
    <ID>Remote Ledger Coll</ID>
    </HEADER>
    <BODY>
    <DESC>
    <STATICVARIABLES>
    <SVEXPORTFORMAT>\$\$SysName:XML</SVEXPORTFORMAT>
    </STATICVARIABLES>
    <TDL>
    <TDLMESSAGE>
    <COLLECTION NAME="Remote Ledger Coll"
    ISINITIALIZE="Yes">
    <TYPE>LEDGER</TYPE>
    <NATIVEMETHOD>ledger</NATIVEMETHOD>
    <NATIVEMETHOD>email</NATIVEMETHOD>
    <NATIVEMETHOD>address</NATIVEMETHOD>
    <NATIVEMETHOD>PINCODE</NATIVEMETHOD>
    <NATIVEMETHOD>guid</NATIVEMETHOD>
    <NATIVEMETHOD>emailcc</NATIVEMETHOD>
    <NATIVEMETHOD>name</NATIVEMETHOD>
    <NATIVEMETHOD>PARTYGSTIN</NATIVEMETHOD>
    <NATIVEMETHOD>CREATEDDATE</NATIVEMETHOD>
    <NATIVEMETHOD>ALTEREDON</NATIVEMETHOD>
    <NATIVEMETHOD>LEDGERMOBILE</NATIVEMETHOD>
    <NATIVEMETHOD>PARENT</NATIVEMETHOD>

    </COLLECTION>
    </TDLMESSAGE>
    </TDL>
    </DESC>
    </BODY>
    </ENVELOPE>';



        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, 'http://111.119.207.78:9002/');  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data ); //$data = "XML data"  
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $response = curl_exec ($ch); 
        // print_r($response); 
        if(curl_errno($ch)){  
        echo curl_error($ch);  
        }  
        else{  
        print "<pre lang='xml'>" . htmlspecialchars($response) . "</pre>";  
        curl_close($ch);  
        }    
        return "";  
    }
    
    function export(){
        $sql = 'Select c.*, concat(c.first_name," ",c.middle_name," ",c.surname) as contact_person, concat(l.first_name," ",l.surname) as created_person from customers c left join login l on l.id=c.created_by  order by c.created DESC, c.first_name ASC';
        $query = $this->pktdblib->custom_query($sql);
        
        $chkzone = $this->pktdblib->custom_query('SHOW TABLES LIKE "customer_zones"');
        
        $fiscalYr = $this->pktdblib->custom_query('Select distinct fiscal_yr from orders order by fiscal_yr ASC');
        $fileName = 'customers-'.date('dmY');
        $this->load->library('excel');
        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $tableColumns = ['Zone No', 'Route No', 'Customer Code', 'Shop Name/ Tally Name', 'Contact Person', 'Billing Name', 'Email Id', 'Contact 1', 'Referral Code', 'Entered By', 'Registered On', 'Status', 'Address', 'Area', 'Pincode'];
        foreach($fiscalYr as $yr){
            array_push($tableColumns, $yr['fiscal_yr']);
        }
        $column = 0;
        foreach($tableColumns as $field){
            $excel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);//->getStyle( $column )->getFont()->setBold( true );;
            $column++;
        }
        $excel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(38);
        $excelRow = 2;
        //echo '<pre>';
        foreach($query as $customer){
            $sql = 'Select a.id,a.site_name, a.pincode, a.tally_address, a.address_1, a.address_2, ar.area_name from customers c inner join address a on a.user_id=c.id and a.type="customers" inner join areas ar on ar.id=a.area_id where a.user_id='.$customer['id'].' and a.is_active=true';
            //echo 'select * from user_roles where user_id='.$userId.' and account_type="'.$userType.'"';
            $sql2 = $this->pktdblib->custom_query('select * from user_roles where user_id='.$customer['id'].' and account_type="customers"');
            if(count($sql2)>0){
                $role = [];
                foreach($sql2 as $roleAddress){
                    //print_r($roleAddress);
                    $role[] = $roleAddress['login_id'];
                }
                $sql.= ' UNION Select a.id,a.site_name, a.pincode, a.tally_address, a.address_1, a.address_2, ar.area_name from address a inner join areas ar on ar.id=a.area_id where a.is_active=true and a.user_id in ('.implode(",", $role).') and a.type="login"';
            }
            $typeWiseUsers = $this->pktdblib->custom_query($sql);
            $typeWiseUsers = (count($typeWiseUsers)>0)?$typeWiseUsers[0]:[];
            
            //print_r($typeWiseUsers);
            $col = 0;
            if(!empty($chkzone)){
                $czone = $this->pktdblib->custom_query('Select * from customer_zones where customer_id='.$customer['id']);
                if(!empty($czone)){
                    $customerHasZone = true;
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $excelRow, $czone[0]['zone_no']);
                    $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $czone[0]['route_no']);
                }else{
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $excelRow, '');
                    $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, '');
                }
            }else{
                $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $excelRow, $customer['zone_no']);
                $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $customer['route_no']);
            }
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $customer['emp_code']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $customer['company_name']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $customer['contact_person'])->getStyle('B')->getAlignment()->setWrapText(true);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, (count($typeWiseUsers)>0)?$typeWiseUsers['site_name']:$customer['company_name']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $customer['primary_email']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $customer['contact_1']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $customer['referral_code']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $customer['created_person']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, date('d/m/Y', strtotime($customer['created'])));
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, ($customer['is_active']==true)?'Active':'Deleted');
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, (count($typeWiseUsers)>0)?$typeWiseUsers['address_1']. " ".$typeWiseUsers['address_2']:"");
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, (count($typeWiseUsers)>0)?$typeWiseUsers['area_name']:"");
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, (count($typeWiseUsers)>0)?$typeWiseUsers['pincode']:"");
            foreach($fiscalYr as $yr){
                //echo 'select sum(amount_after_tax) as business_value from orders where customer_id='.$customer['id'].' and order_status_id not in (0,1,2,8) and fiscal_yr like "'.$yr['fiscal_yr'].'"';
                $orders = $this->pktdblib->custom_query('select sum(amount_after_tax) as business_value from orders where customer_id='.$customer['id'].' and order_status_id not in (0,1,2,8) and fiscal_yr like "'.$yr['fiscal_yr'].'"');
                //print_r($orders);
                if(count($orders)>0){
                    $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $orders[0]['business_value']);
                }else{
                    $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, '0.00');
                }
            }
            $excelRow++;
        }
        //exit;
        //echo '<pre>';print_r($excel);exit;
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    
    function customer_with_default_address(){
        $sql = 'select * from (Select c.*, ar.area_name from customers c inner join user_roles ur on ur.user_id=c.id and ur.account_type="customers" left join address a on a.user_id=ur.login_id and a.type="login" and a.is_default=true left join areas ar on ar.id=a.area_id where c.is_active=true';
        $sql.=' UNION';
        $sql.=' Select c.*, ar.area_name from customers c left join address a on a.user_id=c.id and a.type="customers" and a.is_default=true left join areas ar on ar.id=a.area_id where c.is_active=true';
        $sql.=' ) t order by TRIM(t.first_name) ASC';
        $customers = $this->pktdblib->custom_query($sql);
        return $customers;
    }
    
    function get_customer_details_based_on_code($code=NULL) {
        if(NULL===$code){
            return FALSE;
        }
        //echo "reached in Customer module";
        //print_r($id);
        $this->pktdblib->set_table('customers');
        $res = $this->pktdblib->get_where_custom('emp_code', base64_decode($code));
        $customerdetails = $res->row_array();
        //print_r($customerdetails);
        return $customerdetails;
    }
    
    public function bulk_update(){
        if($this->input->is_ajax_request()){  
            
            $postData = $this->input->post();
            //echo '<pre>';print_r($postData);exit;
            $postData['customers'] = [];
            if(in_array(7, $this->session->userdata('roles')) || in_array(6, $this->session->userdata('roles'))){
               $postData['customers']['referral_code'] = $this->session->userdata('employees')['emp_code'];
            }
            $data = $this->customer_model->customerList($postData);
            //echo "<pre>"; print_r($data);exit;
            
            foreach($data['aaData'] as $key=>$v){
                //echo "<pre>"; print_r($v);
                $data['aaData'][$key]['is_active'] = '<input type="checkbox" name="customers['.$key.'][is_active]" id="is_active_'.$key.'" '.($v['is_active']?"checked=checked":"").'>';
                //$data['aaData'][$key]['company_name'] = '<input type="text" name="customers['.$key.'][company_name]" id="company_name_'.$key.'" value="'.$v['company_name'].'">';
                $data['aaData'][$key]['referral_code'] = '<input type="hidden" name="customers['.$key.'][id]" id="id_'.$key.'" value="'.$v['id'].'"><input type="text" name="customers['.$key.'][referral_code]" id="referral_code_'.$key.'" value="'.$v['referral_code'].'">';
                $data['aaData'][$key]['zone_no'] = '<input type="text" name="customer_zones['.$key.'][zone_no]" id="zone_no_'.$key.'" value="'.$v['zone_no'].'">';
                $data['aaData'][$key]['route_no'] = '<input type="text" name="customer_zones['.$key.'][route_no]" id="route_no_'.$key.'" value="'.$v['route_no'].'">';
                $data['aaData'][$key]['action'] = '<button class="btn" onclick="update('.$key.')">Update</button>';
            }
            echo json_encode($data);
            exit;
            
        }
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Customer Module";
        $data['meta_description'] = "Bulk Update Customer";
        
        $data['modules'][] = "customers";
        $data['methods'][] = "bulk_update_form";
        
        echo Modules::run("templates/admin_template", $data);
    }
    
    public function bulk_update_form(){
        $areas = $this->pktdblib->custom_query('Select * from areas where is_active=true order by area_name ASC');
        $data['options']['areas'] = [''=>'-Select Area-'];
        foreach($areas as $aKey=>$area){
            $data['options']['areas'][$area['id']] = $area['area_name'];
        }
        $this->load->view("customers/bulk_update", $data);
    }
    
    public function updateZones(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //echo 'select from customer_zones where customer_id='.$this->input->post('customer_id');
            $sql = $this->pktdblib->custom_query('select * from customer_zones where customer_id='.$this->input->post('customer_id'));
            //print_r($sql);exit;
            $customerZone = [
                'zone_no'=>$this->input->post('zone_no'),
                'route_no'=>$RouteNo = $this->input->post('route_no'),
                'customer_id'=>$this->input->post('customer_id')
            ];
            
            $this->pktdblib->set_table('customers');
            $this->pktdblib->_update($this->input->post('customer_id'), ['referral_code'=>$this->input->post('referral_code'), 'modified'=>date('Y-m-d H:i:s'), 'modified_by'=>$this->session->userdata('user_id')]);
            if(count($sql)>0){
                $sql = $this->pktdblib->custom_query('update customer_zones set zone_no="'.$this->input->post('zone_no').'", route_no="'.$this->input->post('route_no').'" where customer_id='.$this->input->post('customer_id'));
                if($sql){
                    echo json_encode(['status'=>'success', 'msg'=>'Data Updated Successfully']);
                }else{
                    echo json_encode(['status'=>'failed', 'msg'=>'Some Error occurred']);
                }
                exit;
            }else{
                $this->pktdblib->set_table('customer_zones');
                $zone = $this->pktdblib->_insert($customerZone);
                if($zone){
                    
                    echo json_encode(['status'=>'success', 'msg'=>'Route and zones created Successfully']);
                
                }
            }
            
            
            exit;
        }else{
            echo json_encode(['status'=>'fail', 'msg'=>'Invalid Request']);
            exit;
        }
    }

}