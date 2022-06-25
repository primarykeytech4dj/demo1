<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brands extends MY_Controller {

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
        
        $this->load->model('brands/brand_model');
        $setup = $this->setup();
    }

    function setup(){
        $brands = $this->brand_model->tbl_brands();
        return TRUE;
    }

    function admin_index() {
        $data['meta_title'] = 'brands listing';
        $data['meta_description'] = 'brands Details';
        $data['modules'][] = 'brands';
        $data['methods'][] = 'admin_brand_listing';
        echo Modules::run("templates/admin_template", $data);
    }

    function admin_brand_listing($data = []) {
        //$data['brands'] = $this->brand_model->get_brands_details();
        $this->pktdblib->set_table("manufacturing_brands");
        $brands = $this->pktdblib->get('is_active desc, modified DESC, id DESC');
        $data['brands'] = $brands->result_array();
        $this->load->view("brands/admin_brand_listing", $data);
    }


    // Add new user
    function admin_add() {
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //print_r($_POST);exit;
            $data['values_posted'] = $this->input->post('data');
            
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->form_validation->set_rules('data[manufacturing_brands][brand]', 'Brand', 'required|max_length[255]|is_unique[manufacturing_brands.brand]');
            if($this->form_validation->run()!==FALSE)
            {
                $error = [];
                $profileImg = '';
                if(!empty($_FILES['brand_logo']['name'])) {
                    $profileFileValidationParams = ['file' =>$_FILES['brand_logo'], 'path'=>'../content/uploads/brands/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'brand_logo', 'arrindex'=>'brand_logo', 'thumb'=>['path'=>'../content/uploads/brands/thumbs/', 'folder'=>'brands']];
                    $profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
                    if(empty($profileImg['error'])) {
                        $_POST['data']['manufacturing_brands']['brand_logo'] = $profileImg['filename'];
                    }
                    else {
                        $error['brand_logo'] = $profileImg['error'];
                    }
                }else {
                    $_POST['data']['manufacturing_brands']['brand_logo'] = '';
                }

                if(empty($error)) {
                    $post_data = $_POST['data']['manufacturing_brands'];
                    $reg_brand = json_decode($this->_register_admin_add($post_data), true);
                    
                    if($reg_brand['status'] === "success")
                    {
                        $msg = array('message'=>'Brand Added Successfully', 'class'=>'alert alert-success');
                        $this->session->set_flashdata('message',$msg);
                        redirect(custom_constants::new_brand_url);
                    }
                    else
                    {
                        // Registration error
                        $data['form_error'] = $reg_brand['msg'];
                    }
                }else {
                    $msg = array('message'=>'Failed to Upload Image. Error: '.$error['brand_logo'], 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
                }
            }
        }
        
        /*print_r($data['roles']);
        exit;*/
        $data['meta_title'] = "New User";
        $data['meta_description'] = "New user registration";
        $data['modules'][] = "brands";
        $data['methods'][] = "admin_brand_register";
        echo Modules::run("templates/admin_template", $data);
    }


    function admin_brand_register() {
        $this->load->view("brands/admin_add");
    }


    function _register_admin_add($data) {
        $this->pktdblib->set_table("manufacturing_brands");
        $id = $this->pktdblib->_insert($data);
        
        return json_encode(["msg"=>"Brand Successfully Inserted", "status"=>"success", 'id'=>$id]);
    }

    function edit_brand($id=NULL, $post_data = []) {
        if(NULL == $id)
            return false;

        if($this->pktdblib->_update($id,$post_data))
            return true;
        else
            return false;
    }

    function admin_edit($id = null) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $data['values_posted'] = $_POST['data'];
            
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->form_validation->set_rules('data[brand][brand_name]', 'Brand Name', 'required|max_length[255]');
            if($this->form_validation->run('brands') !== FALSE) {
                $profileImg = '';
                $post_data = $_POST['data']['manufacturing_brands'];
                
                if(!empty($_FILES['brand_logo']['name'])) {
                    $profileFileValidationParams = ['file' =>$_FILES['brand_logo'], 'path'=>'../content/uploads/brands/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'brand_logo', 'arrindex'=>'brand_logo', 'thumb'=>['path'=>'../content/uploads/brands/thumbs/', 'folder'=>'brands']];
                    $profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
                    if(empty($profileImg['error'])) {
                        $post_data['brand_logo'] = $profileImg['filename'];
                        unset($post_data['brand_logo2']);
                    }
                    else {
                        $error['brand_logo'] = $profileImg['error'];
                    }
                }else {
                    $post_data['brand_logo'] = $post_data['brand_logo2'];
                    unset($post_data['brand_logo2']);
                }
                
                if(empty($error)) {
                    $this->pktdblib->set_table("manufacturing_brands");
                    if($this->edit_brand($id,$post_data)) {
                        
                        $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                             $this->session->set_flashdata('message',$msg);
                        }
                        else {
                            $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                            $this->session->set_flashdata('message',$msg);
                        }
                        redirect(custom_constants::edit_brand_url ."/".$id.'?tab=address');
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
            $data['values_posted']['manufacturing_brands'] = $this->brand_details($id);
        }

        $data['manufacturing_brands'] = $data['values_posted']['manufacturing_brands'];
        

        $data['id'] = $id;
        $data['meta_title'] = "New Brand";
        $data['meta_description'] = "New Brand registration";
        $data['modules'][] = "brands";
        $data['methods'][] = "admin_edit_form";
        echo Modules::run("templates/admin_template", $data);
    }

    function admin_edit_form(){
        $this->load->view("brands/admin_edit");
    }

    function brand_details($id) {
        $this->pktdblib->set_table('manufacturing_brands');
        $brandDetails = $this->pktdblib->get_where($id);
        //print_r($brandDetails);
        return $brandDetails;
        
    }

    function admin_view($id=NULL) {
        if(NULL==$id) {
            redirect('brand');
        }
        $this->brand_model->set_table('brand');
        $employee = $this->brand_model->get_where($id);
        $data['user'] = $employee->row_array();
        $data['content'] = 'brand/admin_view';
        $data['meta_title'] = 'brand';
        $data['meta_description'] = 'brand';

        echo Modules::run("templates/admin_template", $data);
    }

    function createThumbnail(){
        
        $this->pktlib->createThumbs("../content/uploads/brands/", "../content/uploads/brands/thumbs/", 300, 'brands');
    }

}