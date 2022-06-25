<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 |  CONTROLLER SUMMARY AND DATABASE TABLES
 |--------------------------------------------------------------------------
 | 
 |  This is where you can start your admin/manage/password protected stuff.
 | 
 |  Database table structure
 |
 |  Table name(s) - no tables
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
       // $this->load->model('address/address_model');
        $this->load->model('cities/cities_model');
        $this->load->model('countries/countries_model');
        $this->load->model('states/states_model');
        $this->load->model('areas/areas_model');
        //$this->load->model('login/mdl_login');
        //$this->load->library('ajax_pagination');
        //$this->load->library('mlm_lib');
        //$this->customer_model->set_table('customers');
        $setup = $this->setup();

    }

    function setup(){
        $customers = $this->customer_model->tbl_customer();
        
        return TRUE;
    }

    function dashboard(){
        $data['title'] = 'customers';
        $data['meta_title'] = 'customer listing';
        $data['meta_description'] = 'customers Listing';
        $data['meta_keyword'] = 'customers Listing';
        //$data['modules'][] = 'customers';
        //$data['methods'][] = 'customer_listing';
        echo Modules::run("templates/memberpanel_template" ,$data);
    }


    function index(){
        $data['title'] = 'customers';
        $data['meta_title'] = 'customer listing';
        $data['meta_description'] = 'customers Listing';
        $data['meta_keyword'] = 'customers Listing';
        $data['modules'][] = 'customers';
        $data['methods'][] = 'customer_listing';
        echo Modules::run("templates/default_template", $data);
    }

    function customer_listing($data = []) {
        $condition = ['customers.is_active'=>true];

        //print_r($data);
        $this->customer_model->set_table('customers');
        if(isset($data['conditions'])){
            $condition = $data['conditions'];
        }
        //$data['customers'] = $this->customer_model->get_customer_list($condition);
        $data['customers'] = $this->customer_model->get_activeCustomers('created');
        print_r($data);exit;
        $this->load->view("customers/index", $data);
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


    function getCityWisecustomers(){
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
        $cityWisecustomers = $this->customer_model->getCityWisecustomers($condition);
        $customerList = [];
        $customerList[$key][0] = 'Select Proposer';
        foreach ($cityWisecustomers as $key => $cityWisecustomer) {
            $customerList[$key]['id'] = $cityWisecustomer['id'];
            $customerList[$key]['text'] = $cityWisecustomer['first_name']." ".$cityWisecustomer['middle_name']." ".$cityWisecustomer['surname']." | ".$cityWisecustomer['emp_code'];
        }
        echo json_encode($customerList);
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

    function get_customerwise_site(){
        //print_r($this->input->post());
        $sql = "Select site_name from address where user_id='".$this->input->post('user_id')."' and type='".$this->input->post('type')."' and is_active=true";
        $address = $this->pktdblib->custom_query($sql);
        //print_r($address);
        //$address = $query->row_array();
        echo (isset($address[0]['site_name'])?$address[0]['site_name']:'');
        exit;
    }


    function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //echo json_encode($this->input->post());//exit;
            $params = json_decode(file_get_contents('php://input'), TRUE);
            //print_r($params);exit;
            if(!empty($params)){
                
                $_POST = $params;
            }
            
            $data['values_posted'] = $this->input->post();
            if(!empty($params)){
            $this->form_validation->set_error_delimiters('', '');
            }
            $this->form_validation->set_rules('data[customers][first_name]', 'First Name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customers][surname]', 'Surname', 'max_length[255]|min_length[2]|alpha_dash');
            $this->form_validation->set_rules('data[customers][primary_email]', 'Email', 'required|max_length[255]|valid_email|is_unique[customers.primary_email]');
            $this->form_validation->set_rules('data[customers][contact_1]', 'Mobile', 'required|max_length[10]|is_unique[customers.contact_1]');
            $this->form_validation->set_rules('data[login][password]', 'Password', 'trim|required|min_length[5]');
            $this->form_validation->set_rules('data[pwd][password2]', 'Password Confirmation', 'trim|required|matches[data[login][password]]');
            if(empty($params)){
                $this->form_validation->set_rules('data[captcha]', 'Captcha', 'required|is_numeric');
            }
            
            if($this->form_validation->run()!==FALSE)
            {
                
                $error = [];
                if($this->input->post('data[captcha]') != $this->session->userdata('customerCaptcha') && empty($params)){
                    $error[] = "Invalid Total of 2 numbers";
                }
                //print_r($error);exit;
                if(empty($error)) {
                    $post_data = $this->input->post("data[customers]");
                    $post_data['joining_date'] = $post_data['created']=$post_data['modified']= date('Y-m-d H:i:s');
                    $reg_user = json_decode($this->_register_admin_add($post_data), true);
                    //print_r($reg_user);
                    if($reg_user['status'] === "success")
                    {

                        /*Company Customers related data*/
                        $companyCustomer['company_id'] = custom_constants::company_id;
                        $companyCustomer['customer_id'] = $reg_user['id'];
                        $companyCustomer['created'] = $companyCustomer['modified'] = date('Y-m-d H:i:s');
                        $this->pktdblib->set_table('companies_customers');
                        $this->pktdblib->_insert($companyCustomer);
                        // Successfully registered
                        if(!empty($post_data)){
                            $post_data['id'] = $reg_user['id'];
                            $post_data['password'] = $this->input->post("data[login][password]");
                            $login = json_decode(Modules::run('login/register_front_customer_login', $post_data), true);
                            $login['customer_id'] = $companyCustomer['customer_id'];
                            $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'customer'=>$login]);
                            //$reg_address = [];
                            if(NULL!==$post_data['address']){
                                $post_data['address']['is_default'] = true;
                                if($login['id']){
                                    $post_data['address'] = $this->input->post("data[address]");
                                    $post_data['address']['user_id'] = $login['id'];
                                    $post_data['address']['type'] = 'login';
                                    $post_data['address']['site_name'] = 'Delivery Address';
                                    $this->pktdblib->set_table("address");
                                    $reg_address = json_decode($this->_register_address($post_data['address']), true);
                                }else{
                                    $post_data['address'] = $this->input->post("data[address]");
                                    $post_data['address']['user_id'] = $reg_user['id'];
                                    $post_data['address']['type'] = 'customers';
                                    $post_data['address']['site_name'] = 'Delivery Address';
                                    $this->pktdblib->set_table("address");
                                    $reg_address = json_decode($this->_register_address($post_data['address']), true);
                                }
                            }//end of address array check
                            
                            //print_r($login);exit;
                            $msg = array('message'=>'Login Added Successfully', 'class'=>'alert alert-success');
                            $this->session->set_flashdata('message',$msg);
                        }
                        $msg = array('message'=>'Account Created Successfully. Please login to continue', 'class'=>'alert alert-success');
                        $this->session->set_flashdata('message',$msg);
                        
                        if(NULL !== ($this->session->userdata('requested_url')))
                        {   

                            $req_url = $this->session->userdata('requested_url');
                            $this->session->unset_userdata('requested_url');

                            redirect($req_url);
                        }
                        else
                        {
                            redirect(base_url() . custom_constants::login_page_url);
                        }
                    }
                    else
                    {
                        // Registration error
                        $data['form_error'] = $reg_user['msg'];
                    }
                }else {
                    $str = implode('<br>', $error);
                    $msg = array('message' => 'Following Error Occured<br>'.$str, 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                }
            }else{
                $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'failed', 'error'=>validation_errors()]);
                 $msg = array('message'=>'Following Error Occured<br>'. validation_errors(), 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
            }
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

        $cities = $this->cities_model->get_dropdown_list();
        $data['option']['cities'][0] = "Select City";
        foreach ($cities as $cityKey => $city) {
            $data['option']['cities'][$city['id']] = $city['city_name'];
        }

        $areas = $this->areas_model->get_dropdown_list();
        $data['option']['areas'][0] = "Select Area";
        foreach ($areas as $cityKey => $city) {
            $data['option']['areas'][$city['id']] = $city['area_name'];
        }
        //echo "hii";exit;
        if(!($this->input->get('tab')))
            $data['tab'] = 'register';
        else
            $data['tab'] = $this->input->get('tab');
        $data['title'] = "Register";
        $data['meta_title'] = "New User";
        $data['meta_keyword'] = "New User";
        $data['meta_description'] = "New user registration";
        $data['modules'][] = "customers";
        $data['methods'][] = "register_form";
        $data['customerNum1'] = rand(0,9);
            //echo $rand."<br>";
            $data['customerNum2'] = rand(0,9);
            $data['customerSum'] = ($data['customerNum1']+$data['customerNum2']); 
            $this->session->set_userdata(['customerCaptcha'=>$data['customerSum']]);
        echo Modules::run("templates/default_template", $data);
    }

    function register_form(){
        //echo "hii";exit;
        $this->load->view('customers/register');

    }

    function _register_admin_add($data) {
        //print_r($data);exit;
        $this->pktdblib->set_table("customers");
        
        if($this->pktdblib->count_where("primary_email", $data['primary_email']) > 0 && $data['primary_email'] !== NULL)
        {
            return json_encode(["msg"=>"email is already in use", "status"=>"error"]);
        }
        
        $this->pktdblib->set_table("customers");
        $id = $this->pktdblib->_insert($data);
         if(empty($data['emp_code'])) {
            $custCode = $this->create_cust_code($id['id']);
            $updArr['id'] = $id['id'];
            $updArr['emp_code'] = $custCode;
            $this->pktdblib->set_table('customers');
            $updCode = $this->edit_customer($id['id'], $updArr);
        }
        return json_encode(["msg"=>"customers Successfully Inserted", "status"=>"success", 'id'=>$id['id']]);
    }

    function _register_address($data) {
        //print_r($data);exit;
        $this->pktdblib->set_table("address");
        $id = $this->pktdblib->_insert($data);
        return json_encode(["msg"=>"Address Successfully Inserted", "status"=>"success", 'id'=>$id]);
    }

     function edit_customer($id=NULL, $post_data = []) {
        if(NULL == $id)
            return false;

        if($this->pktdblib->_update($id,$post_data))
            return true;
        else
            return false;
    }

    function create_cust_code($custId) {
        $companyId = custom_constants::company_id;
        //$this->load->model('companies/companies_model');
        $this->pktdblib->set_table("companies");
        $companyDetails = $this->pktdblib->get_where($companyId);
        //$custCode = Modules::run("companies/company_details/1");
        //$custCode = 'MISS';
        $custCode = $companyDetails['short_code']."/Cl/";
        //print_r($companyDetails['short_code']."/"."Driver");exit;
        if($custId>0 && $custId<=9)
            $custCode .= '000000';
            
        elseif($custId>=10 && $custId<=99)
            $custCode .= '00000';
        elseif($custId>=100 && $custId<=999)
            $custCode .= '0000';
        elseif($custId>=1000 && $custId<=9999)
            $custCode .= '000';
        elseif($custId>=10000 && $custId<=99999)
            $custCode .= '00';
        elseif($custId>=100000 && $custId<=999999)
            $custCode .= '0';

        $custCode .= $custId;
        return $custCode;
    }
    
    function view($loginId=NULL){
        if(NULL===$loginId){
            $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'fail', 'msg'=>"Invalid Access"]);
        }
        
        $loginId = base64_decode($loginId);
        //echo $loginId;
        //echo 'select user_id from user_roles where login_id="'.$loginId.'" and account_type="customers" and is_active=true';
        $userRoles = $this->pktdblib->custom_query('select user_id from user_roles where login_id="'.$loginId.'" and account_type="customers" and is_active=true');
        if(count($userRoles)>0){
            $this->pktdblib->set_table('customers');
            $customer = $this->pktdblib->get_where($userRoles[0]['user_id']);
            $this->load->model('address/address_model');
            $this->address_model->set_table('address');
            $address['address'] = $this->address_model->userBasedAddress($loginId, 'login');
            $customer = array_merge($customer, $address);
            $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'msg'=>"User Found", 'data'=>$customer]);
        }else{
            $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'fail', 'msg'=>"Invalid Access"]);
        }
    }

}
