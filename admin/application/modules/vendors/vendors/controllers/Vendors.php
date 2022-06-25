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
 
 
class Vendors extends MY_Controller {

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
        $this->load->model("vendors/vendor_model");
        $this->load->model('address/address_model');
        $this->load->model('cities/cities_model');
        $this->load->model('countries/countries_model');
        $this->load->model('states/states_model');
        $this->load->model('areas/areas_model');
        $this->load->model('login/mdl_login');
        $this->load->library('ajax_pagination');
		//$this->load->library('mlm_lib');
        //$this->vendor_model->set_table('vendors');
        $setup = $this->setup();

	}

    function setup(){
        $vendors = $this->vendor_model->tbl_vendor_categories();
        
        return TRUE;
    }

    function admin_index() {
        $this->pktdblib->set_table('vendors');
        $vendors = $this->pktdblib->get('id desc');
        $data['vendors'] = $vendors->result_array();
        
        $this->pktdblib->set_table('login');
        foreach ($data['vendors'] as $key => $customer) {
            $login = $this->pktdblib->get_where_custom('username', $customer['emp_code']);
            if(!empty($login)){
                $login = $login->row_array();
                //print_r($login->row_array());
                $data['vendors'][$key]['login_id'] = $login['id'];
            }

        }
        
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : vendors";
        $data['heading'] = '<i class="fa fa-list margin-r-5"></i> Vendor List';
        $data['meta_description'] = "Vendor Module";
        
        $data['modules'][] = "vendors";
        $data['methods'][] = "admin_vendor_listing";
        
        echo Modules::run("templates/admin_template", $data);
    }

	function admin_vendor_listing() {
		$this->load->view("vendors/admin_index");
		//$this->load->view("admin_panel/login_register");
	}

    function admin_add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //print_r($this->input->post());
            $data['values_posted'] = $_POST['data'];
            $this->form_validation->set_rules('data[vendors][first_name]', 'first name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[vendors][company_name]', 'company name', 'required|max_length[255]');
             $this->form_validation->set_rules('data[vendors][primary_email]', 'primary email', 'max_length[255]|valid_email|is_unique[vendors.primary_email]');
            $this->form_validation->set_rules('data[vendors][contact_1]', 'contact 1', 'required|max_length[15]|min_length[10]|numeric');
            $this->form_validation->set_rules('data[vendors][contact_2]', 'contact 2', 'max_length[15]|min_length[10]|numeric');
            if($this->form_validation->run()!== FALSE) { 
                $error = [];
                $post_data = $this->input->post('data[vendors]');
                $profileImg = '';
                if(!empty($_FILES['profile_img']['name'])) {
                    $profileFileValidationParams = ['file' => $_FILES['profile_img'], 'path'=> '../content/uploads/profile_images', 'ext' => 'gif|jpg|png|jpeg', 'fieldname' =>'profile_img', 'arrindex' =>'profile_img'];
                    $profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
                    if(empty($profileImg['error'])) {
                        $post_data['profile_img'] = $profileImg['filename'];
                    }
                    else {
                        $error['profile_img'] = $profileImg['error'];

                    }
                } else {
                    $post_data['profile_img'] = '';
                }
                //print_r($error);exit;
                if (empty($error)) {
                    $post_data['has_multiple_sites'] = isset($post_data['has_multiple_sites'])?TRUE:FALSE;
                    $post_data['joining_date'] = $this->pktlib->dmYtoYmd($post_data['joining_date']);
                    $reg_vendor = json_decode($this->_register_admin_add($post_data), true);
                    //print_r($reg_vendor);
                    if($reg_vendor['status'] === 'success') {
                        $post_data['id'] = $reg_vendor['id'];
                        //redirect(custom_constants::edit_vendor_url."/".$reg_vendor['id'].'?tab=address');
                        if(!$this->input->is_ajax_request()){
                            $this->session->set_flashdata('message',$msg);
                            redirect(custom_constants::edit_vendor_url."/".$reg_vendor['vendors']['id'].'?tab=address');
                        }else{
                            echo json_encode(['status'=>1, 'value'=>[0=>['id'=>$reg_vendor['vendors']['id'], 'text'=>$reg_vendor['vendors']['first_name']." ".$reg_vendor['vendors']['middle_name']." ".$reg_vendor['vendors']['surname']." | ".$reg_vendor['vendors']['company_name']]], 'msg'=>'Vendor Created Successfully']);
                            exit;
                        }
                    }
                    else {
                        //$data['form_error'] = $reg_vendor['msg'];
                        if(!$this->input->is_ajax_request()){
                            $msg = array('message'=> 'Following Error Occurred '.$reg_vendor['msg'], 'class' => 'alert alert-danger');
                            $this->session->set_flashdata('message', $msg);
                        }else{
                            echo json_encode(['status'=>0, 'msg'=>'Error while uploading file'.$reg_vendor['msg']]);
                            exit;
                        }
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
            }
             
        }
        

        //print_r($data['option']['vendor_categories']);exit;
        //print_r( $data['option']['blood_group'][$blood_group['blood_group']]);
        
        $data['meta_title'] = "ERP";
        $data['meta_description'] = "New Vendor";
        
        $data['modules'][] = "vendors";
        $data['methods'][] = "admin_add_vendor";
        
        echo Modules::run("templates/admin_template", $data);
    }

    function _register_admin_add($data) {
        //print_r($data);exit;
        if(!isset($data['vendor_category_id']))
        {
            $data['vendor_category_id']=1;
        }
        $this->pktdblib->set_table("vendors");
        if($this->pktdblib->count_where("primary_email", $data['primary_email']) > 0  && $data['primary_email'] !== NULL)
        {
            $vendor = $this->pktdblib->get_where_custom('primary_email', $data['primary_email']);
            $vendorDetails = $vendor->row_array();
            return json_encode(["msg"=>"email is already in use", "status"=>"success", 'vendors'=>$vendorDetails, 'is_new'=>false]);
        }
        
        $this->pktdblib->set_table("vendors");
        $id = $this->pktdblib->_insert($data);
        if(empty($data['emp_code'])) {
            $empCode = $this->create_cust_code($id['id']);
            $updArr['id'] = $id['id'];
            $updArr['emp_code'] = $empCode;
            $this->pktdblib->set_table('vendors');
            $updCode = $this->edit_vendor($id['id'], $updArr);
        }
        $vendor = $this->get_vendor_details($id['id']);
        //print_r($id);
        return json_encode(["msg" => "Data Added Successfully", "status" => "success", 'id' => $id['id'], 'vendors' => $vendor, 'is_new'=>true ]);
    }

    function edit_vendor($id=NULL, $post_data = []) {
        if(NULL == $id)
            return false;

        if($this->pktdblib->_update($id,$post_data))
            return true;
        else
            return false;
    }
    

	function admin_add_vendor($data=[]) {
        $blood_group = $this->vendor_model->get_dropdown_list();
        $data['option']['blood_group'][NULL] = "Select blood_group";
        foreach ($blood_group as $blood_groupKey => $blood_group) {
            $data['option']['blood_group'][$blood_group['blood_group']] = $blood_group['blood_group'];
        }
        $this->pktdblib->set_table('vendor_categories');
        $vendorCategories = $this->pktdblib->get_where_custom('is_active', true);
        
        $data['option']['vendor_categories'][NULL] = "Select Vendor Categories";
        foreach ($vendorCategories->result_array() as $key => $category) {
            $data['option']['vendor_categories'][$category['id']] = $category['category_name'];
        }

        $data['title'] = "ERP : Vendor Module";
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> New Vendor';

		$this->load->view("vendors/admin_add", $data);
	}

    function admin_edit($id = NULL) {

    	if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST['data'];
    		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
    		$this->form_validation->set_rules('data[vendors][first_name]','first name', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[vendors][surname]', 'surname', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[vendors][company_name]', 'company_name', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[vendors][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
    		$this->form_validation->set_rules('data[vendors][contact_2]', 'contact_2', 'max_length[15]|min_length[10]|numeric');
    		$this->form_validation->set_rules('data[vendors][primary_email]', 'primary_email', 'required|max_length[255]');
    		$this->form_validation->set_rules('data[vendors][secondary_email]', 'secondary_email', 'max_length[255]');
            /*$this->form_validation->set_rules('data[vendors][pan_no]', 'pan no', 'is_unique[vendors.pan_no]|alpha_numeric');
            $this->form_validation->set_rules('data[vendors][gst_no]', 'gst_no', 'is_unique[vendors.gst_no]');*/
    		
    		if($this->form_validation->run('vendors')!== FALSE) {
    			$profileImg = '';
    			$post_data = $data['values_posted']['vendors'];
    			if(!empty($_FILES['profile_img']['name'])) {
    				$profileFileValidationParams = ['file'=>$_FILES['profile_img'], 'path' => '../content/uploads/profile_images/', 'ext' => 'jpg|png|gif|jpeg', 'fieldname' =>'profile_img', 'arrindex' => 'profile_img'];
    				$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
    				if(empty($profileImg['error'])) {
    					$post_data['profile_img'] = $profileImg['filename'];
    					unset($post_data['profile_img2']);
    				}
    				else {
                        $data['values_posted']['vendors']['profile_img'] = $this->input->post('data[vendors][profile_img2]');
    					$error = $profileImg['error']['profile_img'];
     				}
    			}
    			else {
    				$post_data['profile_img'] = $post_data['profile_img2'];
    				unset($post_data['profile_img2']);
    			}

    			if(empty($error)) {
    				//print_r($this->vendor_model->_update_customer($id, $post_data));
                    $this->pktdblib->set_table("vendors");
                    if(isset($post_data['has_multiple_sites']))
                        $post_data['has_multiple_sites'] = 1;
                        
                    if(isset($post_data['is_active']))
                        $post_data['is_active'] = 1;
                        
                    /*echo '<pre>';
                    print_r($post_data);
                    exit;*/
                    
    				if($this->edit_vendor($id, $post_data)) {
    					$msg = array('message' => "data updated successfully", 'class' => 'alert alert-success fade-in');
    					$this->session->set_flashdata('message', $msg);
    				}
    				else {
    					$msg = array('message' => 'some problem occured while updating', 'class' => 'alert alert-danger fade-in');
    					$this->session->set_flashdata('message', $msg);
    				}
    				redirect(custom_constants::edit_vendor_url."/".$id.'?tab=address');
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
    		//$this->vendor_model->set_table("vendors");
    		$data['values_posted']['vendors'] = $this->get_vendor_details($id);
        }
    	$data['vendors'] = $data['values_posted']['vendors'];
        

    	$blood_group = $this->vendor_model->get_dropdown_list();
    	foreach ($blood_group as $blood_groupKey => $blood_group) {
    		$data['option']['blood_group'][$blood_group['blood_group']] = $blood_group['blood_group'];
    	}

    	$data['id'] = $id;
    	if(!($this->input->get('tab')))
    		$data['tab'] = 'personal_info';
    	else
    		$data['tab'] = $this->input->get('tab');

        /*$userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>'vendors']);
        //echo '<pre>';print_r($userRoles);exit;
        $loginId = $userRoles[0]['login_id'];
        $roleId = $userRoles[0]['role_id'];*/

        $AddressListData = ['url'=>custom_constants::edit_vendor_url.'/'.$id.'?tab=address', 'condition'=>['address.user_id'=>$id, 'address.type'=>'vendors'], 'module'=>'vendors'];
        $data['addressList'] = Modules::run("address/admin_address_listing", $AddressListData);
        //print_r($data['addressList']);exit;
        $this->pktdblib->set_table('address');
        $addressData = ['url'=>custom_constants::edit_vendor_url.'/'.$id.'?tab=address', 'module'=>'vendors', 'user_id'=>$id, 'type'=>'vendors', 'user_detail'=>$data['vendors']];
        if($this->input->get('address_id')) { 
            $addressData['address'] = $this->pktdblib->get_where($this->input->get('address_id'));
            $data['address'] = Modules::run("address/admin_edit_form", $addressData);
        }else {
            $data['address'] = Modules::run("address/admin_add_form", $addressData);
        }
        /* Bank Account Related Code Starts Here  */
        $bankAccountListData = ['url'=>custom_constants::edit_vendor_url.'/'.$id.'?tab=bank_account', 'condition'=>['bank_accounts.user_id'=>$id, 'bank_accounts.user_type'=>'vendors'], 'module'=>'vendors'];
        $this->pktdblib->set_table('bank_accounts');
        $data['bankAccountList'] = Modules::run("bank_accounts/admin_index_listing", $bankAccountListData);
        //print_r($data['bankAccountList']);exit;

        $bankAccountData = ['url'=>custom_constants::edit_vendor_url.'/'.$id.'?tab=bank_account', 'module'=>'vendors', 'user_id'=>$id, 'type'=>'vendors', 'user_detail'=>$data['vendors']];
        if($this->input->get('bank_account_id')) { 
            $bankAccountData['bank_accounts'] = Modules::run("bank_accounts/account_details", $this->input->get('bank_account_id'));
            $data['bank_account'] = Modules::run("bank_accounts/admin_edit_form", $bankAccountData);
        }else {
            $data['bank_account'] = Modules::run("bank_accounts/admin_add_form", $bankAccountData);
        }
        /*Bank account ends*/

        /*Document Uploads*/
        $documentListData = [ 'condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'vendors'], 'module'=>'vendors'];
        //$this->address_model->set_table('bank_accounts');
        $data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

        $documentData = ['url'=>custom_constants::edit_vendor_url.'/'.$id.'?tab=document', 'module'=>'vendors', 'user_id'=>$id, 'type'=>'vendors', 'user_detail'=>$data['vendors']];
        
        $data['document'] = Modules::run("upload_documents/admin_add_form", $documentData);

        /*Customer Sites*/
        /*echo '<pre>';
        print_r($data['vendors']);
        exit;*/
        /*if($data['vendors']['has_multiple_sites']){
            $siteListData = ['condition'=>['vendor_sites.customer_id'=>$id, 'vendor_sites.is_active'=>TRUE], 'module'=>'vendors'];
            //$this->address_model->set_table('bank_accounts');
            $data['siteList'] = Modules::run("vendor_sites/admin_index_listing", $siteListData);
            //print_r($data['siteList']);exit;
            $siteData = ['url'=>custom_constants::edit_vendor_url.'/'.$id.'?tab=sites', 'module'=>'vendors', 'customer_id'=>$id, 'type'=>'vendors', 'user_detail'=>$data['vendors']];
            if($this->input->get('site_id')){ 
                $siteData['values_posted']['vendor_sites'] = Modules::run("vendor_sites/get_site_details", $this->input->get('site_id'));
                $siteData['values_posted']['address'] = Modules::run("address/address_details", $siteData['values_posted']['vendor_sites']['address_id']);
                /*echo '<pre>';
                print_r($siteData);exit;*/
                /*$data['vendor_sites'] = Modules::run("vendor_sites/admin_add_couriersite_form", $siteData);
                //print_r($data['bank_account']);exit;
            }else{
                $data['vendor_sites'] = Modules::run("vendor_sites/admin_add_couriersite_form", $siteData);
            }
        }*/

        $this->pktdblib->set_table('vendor_categories');
        $vendorCategories = $this->pktdblib->get_where_custom('is_active', true);
        /*print_r($vendorCategories);
        exit;*/
        $data['option']['vendor_categories'][NULL] = "Select Vendor Categories";
        foreach ($vendorCategories->result_array() as $key => $category) {
            //print_r($blood_groupKey);
            //print_r($blood_group);
            $data['option']['vendor_categories'][$category['id']] = $category['category_name'];
        }

        $data['js'][0] = "<script type = 'text/javascript'>
            $('.maxCurrentDate').on('ready', 'blur', function(){
                alert('hii');
            })
            </script>
        ";
        
       	$data['content'] = 'vendors/admin_edit';

        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Vendor Module";
        $data['meta_description'] = "Edit Vendor";
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> Edit Vendor';
        echo Modules::run('templates/admin_template', $data);
    }  

    function edit_vendor_details() {
        $this->load->view("vendors/edit_vendor");
    }

    function get_vendor_details($id) {
        //echo "reached in Customer module";
        //print_r($id);
        $this->pktdblib->set_table('vendors');
        $customerdetails = $this->pktdblib->get_where($id);
        return $customerdetails;
    }

    function get_Customer_list_dropdown(){
        $this->pktdblib->set_table('vendors');
        $vendors = $this->pktdblib->get_active_list();
        //print_r($vendors);
        $dropDown = [''=>'Select Customer'];
        foreach ($vendors as $key => $customer) {
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
            redirect('vendors');
        }
        $this->pktdblib->set_table('vendors');
        $data['user'] = $this->pktdblib->get_where($id);
        //$data['user'] = $customer->row_array();
        $userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>'vendors']);
        //echo '<pre>';print_r($userRoles);exit;
        $loginId = $userRoles[0]['login_id'];
        $roleId = $userRoles[0]['role_id'];
        $data['content'] = 'vendors/admin_view';
        $data['meta_title'] = 'vendors';
        $data['meta_description'] = 'vendors';
        $addressListData = ['condition'=>['address.user_id'=>$loginId, 'address.type'=>'login'], 'module'=>'vendors'];
        //$this->address_model->set_table('address');
        $data['addressList'] = Modules::run("address/admin_address_listing", $addressListData);

        $bankAccountListData = ['condition'=>['bank_accounts.user_id'=>$loginId, 'bank_accounts.user_type'=>'login'], 'module'=>'vendors'];
        //$this->address_model->set_table('address');
        $data['bankAccountList'] = Modules::run("bank_accounts/admin_index_listing", $bankAccountListData);

        /*Documents*/
        $documentListData = ['condition'=>['user_documents.user_id'=>$loginId, 'user_documents.user_type'=>'login'], 'module'=>'vendors'];
        //$this->address_model->set_table('bank_accounts');
        $data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);
        if($data['user']['has_multiple_sites']){
            $siteListData = ['condition'=>['vendor_sites.customer_id'=>$id, 'vendor_sites.is_active'=>TRUE], 'module'=>'vendors'];
            $data['sites'] = Modules::run("vendor_sites/admin_index_listing", $siteListData);
            //print_r($data['sites']);exit;
        }

        $data['otherDetailsList'] = Modules::run('vendors/employee_other_details', ['customer_id'=>$id]);

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
        $empCode = $companyDetails['short_code']."/v/";
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

    function getCityWisevendors(){
        //$_POST['params'] = 1;
        if(!$this->input->post('params'))
            return;

        $condition = [];
        $condition = ['vendors.is_active' => TRUE];
        $cityId = $this->input->post('params');
        if(!empty($cityId)) {
            $condition['address.city_id'] = $cityId;
        }
        $this->vendor_model->set_table('vendors');
        $cityWisevendors = $this->vendor_model->getCityWisevendors($condition);
        $customerList = [];
        $customerList[$key][0] = 'Select Proposer';
        foreach ($cityWisevendors as $key => $cityWisecustomer) {
            $customerList[$key]['id'] = $cityWisecustomer['id'];
            $customerList[$key]['text'] = $cityWisecustomer['first_name']." ".$cityWisecustomer['middle_name']." ".$cityWisecustomer['surname']." | ".$cityWisecustomer['emp_code'];
        }
        echo json_encode($customerList);
    }

    function get_empId_based_data(){
        $sql = "Select concat(first_name, ' ', middle_name, ' ', surname) as fullname from vendors where is_active=true";
        if(NULL!==$this->input->post('emp_code'))
            $sql.=" AND emp_code LIKE '".$this->input->post('emp_code')."'";

        $query = $this->pktdblib->custom_query($sql);
        if(!empty($query))
            echo json_encode($query[0]['fullname']);
        else
            echo json_encode('');
        exit;
    }

    function admin_category_index(){
        $data['meta_title'] = 'Vendor Category';
        $data['title'] = 'Module :: Vendor';
        $data['heading'] = '<i class="fa fa-list margin-r-5"></i> Vendor Categories';
        $data['meta_description'] = 'Vendor Category';
        //$data['module'] = 'address';
        //$data['content'] = 'address/address_listing';
        $data['modules'][] = 'vendors';
        $data['methods'][] = 'admin_category_listing';
        
        echo Modules::run("templates/admin_template", $data);   
    }

    function admin_category_listing($data = []) {
        $condition = [];
        if(isset($data['condition']))
            $condition = $data['condition'];
        //echo "string"; exit;
        $data['categories'] = $this->vendor_model->get_category_list();
        /*echo '<pre>';
        print_r($data['categories']);
        echo '</pre>';exit;*/
        $this->load->view("vendors/admin_category_listing", $data);
    }

    function admin_add_category() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST;
            $this->form_validation->set_rules('data[vendor_categories][parent_id]', 'parent');
            $this->form_validation->set_rules('data[vendor_categories][category_name]', 'category_name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[vendor_categories][slug]', 'slug', 'required|max_length[255]|is_unique[vendor_categories.slug]');
            if($this->form_validation->run('vendor_categories')!== false) {
                $error = [];
               
                if(empty($error)) {
                    $post_data = $this->input->post('data[vendor_categories]');
                    //print_r($post_data);
                    $register_vendor_category = json_decode($this->register_vendor_category($post_data), true);
                    if($register_vendor_category['status'] === 'success') {
                        $msg = array('message'=>'Vendor Category Created Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    redirect(custom_constants::new_vendor_category_url);

                    }
                    else {
                        $data['form_error'] = $register_vendor_category['msg'];
                    }
                }
                else {
                    //print_r($error);
                    $msg = array('message'=>'unable to add vendor', 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                }
            }
            else {
                $msg = array('message'=>'unable to add vendor following error occured. '.validation_errors(), 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
            }

        }

        //echo "<pre>";
        $data['parents'] = $this->vendor_model->get_category_dropdown_list();
        //print_r($data['parents']);
        $data['option']['parent'][0] = 'Select Parent';
        foreach($data['parents'] as $parentKey => $parent){
            
            $data['option']['parent'][$parent['id']] = $parent['category_name'];
        }
        
        $data['modules'][] = 'vendors';
        $data['methods'][]= 'admin_add_vendor_category';
        //$data['content'] = 'vendors/add_vendors';
        $data['meta_title'] = 'New Vendor Category';
        $data['title'] = 'Module :: Vendor';
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> Vendor Categories';
        $data['meta_description'] = 'New Vendor Category';
        
        echo Modules::run('templates/admin_template', $data);
    }

    function register_vendor_category($data) {
        $insert_data = $data;
        //echo "hi";

        $this->vendor_model->set_table("vendor_categories");
        $id = $this->vendor_model->_insert($insert_data);
        return json_encode(['message' =>'Vendor added Successfully', "status"=>"success", 'id'=> $id]);
    }

    function admin_add_vendor_category() {
        $this->load->view('vendors/admin_add_vendor_category');
    }

    function admin_edit_category($id = NULL) {
        //check_user_login(FALSE);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $error=[];
            /*echo '<pre>';
            print_r($_POST);
            echo '</pre>';exit;*/
            $data['values_posted'] = $_POST['data'];
            $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
            $this->form_validation->set_rules("data[vendor_categories][parent_id]", 'Parent', 'required');
            $this->form_validation->set_rules("data[vendor_categories][category_name]", 'Category Name', 'required|max_length[255]');
            //$this->form_validation->set_rules("data[vendor_categories][description]", 'Description', 'max_length[255]');
            $this->form_validation->set_rules("data[vendor_categories][gst]", 'GST', 'max_length[255]');
            if($this->form_validation->run('vendor_categories')!== FALSE){
                $postData = $this->input->post('data[vendor_categories]');
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
                    $this->pktdblib->set_table('vendor_categories');
                    if($this->pktdblib->_update($id, $postData)) {
                        $msg = array('message'=>'Data Updated Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    }
                    else {
                        $msg = array('message'=>'Some problem occured', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    }
                    redirect(custom_constants::edit_vendor_category_url."/".$id);
                } 
                else {
                    //print_r($error);
                    $msg = array('message'=>'some error occured while uploading'.$error, 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                }
            } 
            else {
                $data['values_posted']['vendor_categories']['image_name_1'] = $data['values_posted']['vendor_categories']['image_name_1_2']; 
                $data['values_posted']['vendor_categories']['image_name_2'] = $data['values_posted']['vendor_categories']['image_name_2_2']; 
                //echo validation_errors();exit;
                $msg = array('message'=>'Some validation error occured'.validation_errors(), 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
            }
        }  
        else { //echo $id;
            $data['vendor_categories'] = $this->get_vendor_category_details($id);
            $data['values_posted']['vendor_categories'] = $data['vendor_categories'];
            //print_r($data['values_posted']['vendor_categories']);

        } 
    
        $data['parents'] = $this->get_vendor_categories_list();
        //echo '<pre>';print_r($data['parents']);exit;
        $data['option']['parent'][0] = 'Select Parent';
            /*echo '<pre>';*/

        foreach ($data['parents'] as $parentKey => $parent) {
            $data['option']['parent'][$parent['id']] = $parent['category_name'];
            
         } 
         //print_r($data['option']['parent'][$parent['id']]);
        $data['id'] = $id;
        $data['modules'][] = 'vendors';
        $data['methods'][]= 'admin_edit_vendor_category';
        $data['title'] = 'Edit vendor Category';
        $data['meta_title'] = 'Edit vendor Category';
        $data['meta_description'] = 'Edit vendor Category';
        $data['title'] = 'Module :: Vendor';
        $data['heading'] = '<i class="fa fa-pencil margin-r-5"></i> Vendor Categories';
        $data['js'][] = '<script type="text/javascript">
            
            $(document).on("submit", "#vendor_categories", function(){
                alert(CKEDITOR.instances.editor1.getData());
                return false;
              $(".editor1").val(CKEDITOR.instances.editor1.getData());
              $(".editor1").show();
            });
        </script>';
        
        echo Modules::run('templates/admin_template', $data);
    }

    function admin_edit_vendor_category() {
        $this->load->view('vendors/admin_edit_vendor_category');
    }

    function get_vendor_category_details($id) {
        // echo "reached get_product_category_details";
        $this->pktdblib->set_table('vendor_categories');
        $vendorCategoryDetail = $this->pktdblib->get_where($id);
        //print_r($vendorCategoryDetail);
        return $vendorCategoryDetail;
    }

    function get_vendor_categories_list() {
        $this->vendor_model->set_table('vendor_categories');
        $vendorCategories = $this->vendor_model->get_vendor_category_list();
        return $vendorCategories;
    }

    function get_vendorwise_site(){
        //print_r($this->input->post());
        $sql = "Select site_name from address where user_id='".$this->input->post('user_id')."' and type='".$this->input->post('type')."' and is_active=true";
        $address = $this->pktdblib->custom_query($sql);
        //print_r($address);
        //$address = $query->row_array();
        echo (isset($address[0]['site_name'])?$address[0]['site_name']:'');
        exit;
    }

}
