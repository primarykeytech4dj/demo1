<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Check login and make sure email has been verified
		foreach(custom_constants::$protected_pages as $page)
        {   
            if(strpos($this->uri->uri_string, $page) === 0)
            {   
                check_user_login(TRUE);
            }
        }
        //check_user_login(TRUE);
		//$this->load->model("customer/mdl_admin_customer");
        $this->load->model("roles/roles_model");
       $setup = $this->setup();
       //print_r($query);exit;
	}

    function setup() {
        //echo "reached here";exit;
        $roles = $this->roles_model->tbl_roles();
       // print_r($roles);exit;
        $role_details = $this->roles_model->tbl_role_details();
       return TRUE;
    }

    function admin_index() {
        $this->pktdblib->set_table('roles');
        $role = $this->pktdblib->get('id desc');
        $data['roles'] = $role->result_array();
        //print_r($data['vendors']);
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Role";
        $data['meta_description'] = "Role Panel";
        
        $data['modules'][] = "roles";
        $data['methods'][] = "admin_role_listing";
        
        echo Modules::run("templates/admin_template", $data);
    }

	function admin_role_listing() {
		$this->load->view("roles/admin_index");
		//$this->load->view("admin_panel/login_register");
	}

    //Customer ajax loading

    function admin_add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST['data'];
            
            $this->form_validation->set_rules('data[roles][role_name]', 'Name', 'required|max_length[255]|is_unique[roles.role_name]');
            $this->form_validation->set_rules('data[roles][role_code]', 'Code', 'required|max_length[3]|is_unique[roles.role_code]');
            if($this->form_validation->run()!== FALSE) { //echo "hii";
                $error = [];
                if (empty($error)) {
                    $post_data = $this->input->post('data[roles]');
                    $post_data['created'] = $post_data['modified'] = date('Y-m-d H:i:s');
                    $this->pktdblib->set_table("roles");
                    $reg_role = json_decode($this->_register_admin_add($post_data), true);

                    if($reg_role['status'] === 'success') {
                        $insertRoleDetails = $this->input->post('data[role_details]');
                        $insertRoleDetails['role_id'] = $reg_role['id']['id'];
                        $insertRoleDetails['created'] = $insertRoleDetails['modified'] = date('Y-m-d H:i:s');
                        $this->pktdblib->set_table("role_details");
                        $reg_roleDetails = json_decode($this->_register_admin_add($insertRoleDetails), true);
                            if($reg_role['status'] === 'success') {
                                $msg = array('message'=> 'Data Added Successfully', 'class' => 'alert alert-success');
                                $this->session->set_flashdata('message', $msg);
                            }
                            else{
                                $msg = array('message'=> 'Role Details Not Added ', 'class' => 'alert alert-success');
                                $this->session->set_flashdata('message', $msg);
                            }
                        $msg = array('message'=> 'Data Added Successfully', 'class' => 'alert alert-success');
                        $this->session->set_flashdata('message', $msg);
                        redirect(custom_constants::new_role_url);
                    }
                    else {
                        $data['form_error'] = $reg_role['msg'];
                    }
                }else{
                    //print_r($error['profile_img']);
                    $msg = array('message'=> 'Error while uploading file', 'class' => 'alert alert-danger');
                        $this->session->set_flashdata('message', $msg);
                }


            }else{
                $msg = array('message'=> 'Validation Error Occured'.validation_errors(), 'class' => 'alert alert-danger');
                $this->session->set_flashdata('message', $msg);
                //echo validation_errors();
            }
             
        }
        $data['option']['module'] = ["Select Type", /*'companies'=>'Company', */'employees'=>'Employees', 'customers'=>'Customers', 'suppliers'=>'Supplier/Vendor']; 
         
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Role Module";
        $data['meta_description'] = "New Role";
        
        $data['modules'][] = "roles";
        $data['methods'][] = "admin_add_role";
        
        echo Modules::run("templates/admin_template", $data);
           // }
    }

    function _register_admin_add($data) {
       // $this->roles_model->set_table("roles");
        $id = $this->pktdblib->_insert($data);
        return json_encode(["msg" => "Data Added Successfully", "status" => "success", 'id' => $id,  'is_new'=>true ]);
    }

	function admin_add_role() {
		$this->load->view("roles/admin_add");
	}

    function admin_edit($id = NULL) {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST['data'];
            /*echo '<pre>';
            print_r($data['values_posted']);exit;*/
            $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
           $this->form_validation->set_rules('data[roles][role_name]', 'Name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[roles][role_code]', 'Code', 'required|max_length[3]');
            
            if($this->form_validation->run('roles')!== FALSE) {
                $post_data = $data['values_posted']['roles'] = $_POST['data']['roles'];
                $roleDetails = $data['values_posted']['role_details']= $_POST['data']['role_details'];

                $roleDetails = $_POST['data']['role_details'];
                if(isset($roleDetails['is_add']))
                    $roleDetails['is_add'] = true;
                else
                    $roleDetails['is_add'] = false;

                if(isset($roleDetails['is_update']))
                    $roleDetails['is_update'] = true;
                else
                    $roleDetails['is_update'] = false;

                if(isset($roleDetails['is_view']))
                    $roleDetails['is_view'] = true;
                else
                    $roleDetails['is_view'] = false;

                if(isset($roleDetails['is_delete'])){
                    $roleDetails['is_delete'] = true;
                    $roleDetails['is_view'] = true;
                }
                else
                    $roleDetails['is_delete'] = false;
                if(empty($error)) {
                    $this->pktdblib->set_table("roles");

                    if($this->edit_role($id, $post_data)) {
                        $this->pktdblib->set_table("role_details");
                         if($this->edit_role($data['values_posted']['role_details']['id'], $roleDetails)) {
                            //echo "update";exit;
                            $msg = array('message' => "data updated successfully", 'class' => 'alert alert-success fade-in');
                         }
                        $msg = array('message' => "data updated successfully", 'class' => 'alert alert-success fade-in');
                        $this->session->set_flashdata('message', $msg);
                    }
                    else {
                        $msg = array('message' => 'some problem occured while updating', 'class' => 'alert alert-danger fade-in');
                        $this->session->set_flashdata('message', $msg);
                    }
                    redirect(custom_constants::edit_role_url."/".$id);
                }
                else {
                    $msg = array('message' => $error, 'class' =>'alert alert-danger fade-in');
                    $this->session->set_flashdata('error', $msg);
                }
            } 
            else {
                $msg = array('message' => 'some validation error occured', 'class' => 'alert alert-danger fade-in');
            }

        }
        else {
            $this->pktdblib->set_table("roles");
            $data['role'] = $this->get_role_details($id);
            $data['values_posted']['role'] = $data['role'];
            $this->pktdblib->set_table("role_details");
            $data['role_details'] = $this->pktdblib->get_where_custom('role_id',$id);
            $data['values_posted']['role_details'] = $data['role_details'];
        }

        $data['tab'] = 'role';
        if($this->input->get('tab'))
            $data['tab'] = $this->input->get('tab');
        $data['id'] = $id;
        $data['roles'] = $this->get_role_details($id);

        $data['option']['module'] = ["Select Type", /*'companies'=>'Company',*/ 'employees'=>'Employees', 'customers'=>'Customers', 'suppliers'=>'Supplier/Vendor']; 

        $data['values_posted']['roles'] = $data['roles'];
        $this->pktdblib->set_table('role_details');
        $roleDetails = $this->pktdblib->get_where_custom('role_id',$id);
        $data['rolesDetails'] = $roleDetails->row_array();
         //echo '<pre>';print_r($data['rolesDetails']);exit;
        $data['values_posted']['role_details'] = $data['rolesDetails'];
        $data['role_detail_id']= $data['rolesDetails']['id'];
        $data['meta_title'] = "Edit Role";
        $data['title'] = "ERP Edit Role";
        $data['meta_description'] = "Edit Role";
        $data['content'] = 'roles/admin_edit';
        $data['menuForm'] = Modules::run('menus/assign_rolewise_menu_form', $data);
        echo Modules::run('templates/admin_template', $data);
    }  

    function edit_vender_details() {
        $this->load->view("vendors/edit_vender");
    }

    function get_role_details($id) {
        $this->pktdblib->set_table('roles');
        $roledetails = $this->pktdblib->get_where($id);
        return $roledetails/*->row_array()*/;
    }

    function edit_role($id=NULL, $post_data = []) {
        if(NULL == $id)
            return false;

        if($this->pktdblib->_update($id,$post_data)){

            //echo "heeee";exit;
            return true;
        }
        else
            return false;
    }

     function edit_roleDetails($roleId=NULL, $post_data = []) {
        if(NULL == $roleId)
            return false;

        ///$this->pktdblib->set_table('')
        if($this->pktdblib->_update($roleId,$post_data))
            return true;
        else
            return false;
    }
}