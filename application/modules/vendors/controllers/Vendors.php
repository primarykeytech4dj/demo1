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
                check_user_login(FALSE);
            }
        }
		//$this->load->model("customer/mdl_admin_customer");
        $this->load->model("vendors/vendor_model");
       // $this->load->model('address/address_model');
        //$this->load->model('cities/cities_model');
        //$this->load->model('countries/countries_model');
       // $this->load->model('states/states_model');
        //$this->load->model('areas/areas_model');
        //$this->load->model('login/mdl_login');
        //$this->load->library('ajax_pagination');
		//$this->load->library('mlm_lib');
        //$this->vendor_model->set_table('vendors');
        $setup = $this->setup();

	}

    function setup(){
        $vendors = $this->vendor_model->tbl_vendor_categories();
        
        return TRUE;
    }

    

    function index(){
        $data['title'] = 'Vendors';
        $data['meta_title'] = 'Vendor listing';
        $data['meta_description'] = 'vendors Listing';
        $data['meta_keyword'] = 'vendors Listing';
        $data['modules'][] = 'vendors';
        $data['methods'][] = 'home_page';
        //print_r($data);exit;
        echo Modules::run("templates/venedor_template", $data);
    }

    function home_page(){
        $this->load->view('templates/venedor/home_page_view');
    }



    function get_customer_details($id) {
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

    function register_vendor(){
        $this->load->view('vendors/register_product');
    }

    function register2(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //echo '<pre>';
            //print_r($_POST);exit;
            $data['values_posted'] = $this->input->post('data');
            
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->form_validation->set_rules('data[vendors][first_name]', 'First Name', 'required|max_length[255]|alpha_dash');
            $this->form_validation->set_rules('data[vendors][surname]', 'Surname', 'required|max_length[255]|min_length[2]|alpha_dash');
            $this->form_validation->set_rules('data[vendors][primary_email]', 'primary email', 'required|max_length[255]|valid_email|is_unique[vendors.primary_email]');
            $this->form_validation->set_rules('data[login][password]', 'Password', 'trim|required|min_length[5]');
            $this->form_validation->set_rules('data[vendor][password2]', 'Password Confirmation', 'trim|required|matches[data[login][password]]');


            if($this->form_validation->run()!==FALSE)
            {
                $error = [];

                if(empty($error)) {
                    $post_data = $_POST['data']['vendors'];
                    $reg_user = json_decode($this->_register_admin_add($post_data), true);
                    //echo "reached";exit;
                    if($reg_user['status'] === "success")
                    {
                        // Successfully registered
                        if(!empty($post_data)){
                            $post_data['id'] = $reg_user['id'];
                           // print_r($post_data);exit;
                            $post_data['password'] = $this->input->post("data[login][password]");
                            $login = Modules::run('login/register_vendor_to_login', $post_data);
                            $msg = array('message'=>'Login Added Successfully', 'class'=>'alert alert-success');
                            $this->session->set_flashdata('message',$msg);
                        }
                        $msg = array('message'=>'Vendor Added Successfully', 'class'=>'alert alert-success');
                        $this->session->set_flashdata('message',$msg);
                        //redirect(custom_constants::edit_employee_url."/".$reg_user['id']);
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
            }else{
                 $msg = array('message'=>'Following Error Occured<br>'. validation_errors(), 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
            }
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
        $data['modules'][] = "vendors";
        $data['methods'][] = "register_form";
        echo Modules::run("templates/default_template", $data);
    }

    function register_form(){
        //echo "hii";exit;
        $this->load->view('vendors/register');

    }

    function _register_admin_add($data) {
        //print_r($data);exit;
        $this->vendor_model->set_table("vendors");
        
        if($this->vendor_model->count_where("primary_email", $data['primary_email']) > 0 && $data['primary_email'] !== NULL)
        {
            /*$sql = $this->pktdblib->custom_query("select * from login where email='".$data['primary_email']."'");
            print_r($sql);exit;*/
            //return json_encode(["msg"=>"email is already in use", "status"=>"error"]);
        }
        //echo "email is different";exit;
        
        $this->vendor_model->set_table("vendors");
        $id = $this->vendor_model->_insert($data);
        /*if(empty($data['emp_code'])) {
            $empCode = $this->create_code($id);
            $updArr['id'] = $id;
            $updArr['emp_code'] = $empCode;
            $updCode = $this->edit_employee($id, $updArr);
        }*/
        return json_encode(["msg"=>"vendors Successfully Inserted", "status"=>"success", 'id'=>$id]);
    }

}
