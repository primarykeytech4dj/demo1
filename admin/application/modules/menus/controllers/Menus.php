<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Check login and make sure email has been verified
		foreach(custom_constants::$protected_pages as $page)
        {   
            if(strpos($this->uri->uri_string, $page) === 0)
            {   
                check_user_login(FALSE);
            }
        }
		$this->load->model('menus/menus_model');
        $setup = $this->setup();
	}

    function setup(){
        $menus = $this->menus_model->tbl_menus();
        $roles = Modules::run('roles/setup');
        $tempMenus = $this->menus_model->tbl_temp_menu();
        $menuRoles = $this->menus_model->tbl_menu_roles();
        /*if($tempMenus){
            $addAdminMenus = $this->admin_default_menus();
        }*/
        return TRUE;
    }

	function admin_index(){

		$data['meta_title'] = "menus Category";
        $data['meta_description'] = "menus Category";
        //$data['condition'] = ['is_active'=> true];
        $data['modules'][] = "menus";
        $data['methods'][] = "admin_menu_category_listing";
        echo modules::run('templates/admin_template', $data);
	}

	function admin_menu_category_listing() {
        $condition = '';
        if(isset($data['condition']))
            $condition = $data['condition'];
        //echo $condition;exit;
        //$data['news_category'] = $this->category_wise_news_listing($condition);
        
        $data['categories'] = $this->menus_model->get_category_list();

        //echo '<pre>';print_r($data['category']);exit;
        $this->load->view("menus/menu_view", $data);

        //$this->load->view('news/admin_index_category', $data);
    }

    function admin_add(){
        //echo "hi";//exit;
        check_user_login();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            /*echo '<pre>';
            print_r($_POST);exit;*/
            $data['values_posted'] = $_POST['data'];
            //print_r($data['values_posted']['menu_roles']);exit;
            $this->form_validation->set_rules('data[temp_menu][parent_id]', 'parent');
            $this->form_validation->set_rules('data[temp_menu][name]', 'name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[temp_menu][slug]', 'slug', 'required|max_length[255]|is_unique[temp_menu.slug]');
            
                if(empty($error)) {
                    $post_data = $this->input->post('data[temp_menu]');
                    $post_data['created'] = date('Y-m-d H:i:s');
                    $post_data['modified'] = date('Y-m-d H:i:s');
                    $post_data['class'] = empty($post_data['class'])?$post_data['class2']:$post_data['class'];
                    unset($post_data['class2']);
                    //echo '<pre>';print_r($post_data);exit;
                    //$this->menus_model->set_table("temp_menu");
                    $register_menu_category = json_decode($this->register_menu_category($post_data), true);
                    if($register_menu_category['status'] === 'success') {
                        //print_r($data['values_posted']);exit;
                        if(!empty($data['values_posted']['menu_roles'])){

                        $data['role'] = $data['values_posted']['menu_roles']['role_id'];
                        $menuRole = $this->admin_add_menuRole($register_menu_category['id']['id'], $data['role']);
                        }
                        $msg = array('message'=>'Menu Category Added Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    //redirect(custom_constants::new_news_category_url);
                    redirect(custom_constants::new_menu_url);

                    }
                    else {
                        $data['form_error'] = $register_menu_category['msg'];
                    }
                }
                else {
                    //print_r($error);
                    $msg = array('message'=>'unable to add menu', 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                }
            }

        $this->pktdblib->set_table("menus");
        $data['menus'] = $this->pktdblib->get_active_list();
        //echo '<pre>';print_r($data['menus']);exit;
        $data['option']['menu'][0] = 'Select Menu';
        foreach($data['menus'] as $menuKey => $menu){
            
            $data['option']['menu'][$menu['id']] = $menu['name'];
        }

        $data['option']['parent'] = $this->parent();
        $this->pktdblib->set_table('roles');
        $data['roles'] = $this->pktdblib->get_active_list();
        //echo '<pre>';print_r($data['roles']);exit;
        $data['option']['roles'][0] = 'Select Role';
        foreach($data['roles'] as $roleKey => $role){
            
            $data['option']['roles'][$role['id']] = $role['role_name'];
        }
        $data['target'] = $this->pktlib->get_target();


        $data['meta_title'] = "Menu Category";
        $data['meta_description'] = "New Category";
        $data['meta_keyword'] = "New Category";
        $data['modules'][] = "menus";
        $data['methods'][] = "admin_add_menu_category";
        $data['js'][] = '<script type="text/javascript">
            /*CKEDITOR.replace("editor1");
            $(document).on("submit", "#menu", function(){
              $(".editor1").val(CKEDITOR.instances.editor1.getData());
              $(".editor1").show();
            });*/
        </script>';
        echo Modules::run('templates/admin_template', $data);
    }

    function register_menu_category($data) {
        $this->pktdblib->set_table("temp_menu");
        $id = $this->pktdblib->_insert($data);
        return json_encode(['message' =>'Category added Successfully', "status"=>"success", 'id'=> $id]);
    }

    function admin_add_menu_category() {
        $this->load->view("menus/admin_add_menu_category");
    }

    function admin_edit($id = NULL) {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $_POST['data'];
            //echo '<pre>';
            //print_r($data['values_posted']);exit;
            $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
            $this->form_validation->set_rules("data[temp_menu][parent_id]", 'Parent', 'required');
            $this->form_validation->set_rules("data[temp_menu][name]", 'Menu Name', 'required|max_length[255]');
                $postData = $_POST['data']['temp_menu'];
                /*if(isset($postData['is_custom_constant']))
                    $postData['is_custom_constant'] = true;
                else
                    $postData['is_custom_constant'] = false;*/

                //print_r($postData);
                $postData['is_custom_constant'] = isset($postData['is_custom_constant'])?true:false;
                $postData['is_active'] = isset($postData['is_active'])?true:false;
                $postData['class'] = empty($postData['class'])?$postData['class2']:$postData['class'];
                    unset($postData['class2']);
                //print_r($postData);exit;
                //if(empty($error)) {
                    $this->pktdblib->set_table('temp_menu');
                    if($this->pktdblib->_update($id, $postData)) {
                        $data['role'] = $data['values_posted']['menu_roles']['role_id'];
                        $menuRole = $this->admin_add_menuRole($id, $data['role']);
                        $msg = array('message'=>'Data Updated Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    }
                    else {
                        $msg = array('message'=>'some problem occured', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                    }
                    redirect(custom_constants::edit_menu_url."/".$id);
               /* } 
                else {
                    //print_r($error);
                    $msg = array('message'=>'some error occured while uploading'.$error, 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message', $msg);
                }*/
        }  
        else {
            $data['temp_menu'] = $this->get_menu_category_details($id);
            $data['values_posted']['temp_menu'] = $data['temp_menu'];
             $this->pktdblib->set_table("menu_roles");
            $menu_roles = $this->pktdblib->get_where_custom('menu_detail_id', $id); 
            $data['menu_roles'] = $menu_roles->result_array();
            if(count($data['menu_roles'])>0) {
                    foreach ($data['menu_roles'] as $roleKey => $role) {
                        $data['menu_role'][$roleKey] = $role['role_id'];
                    }
                $data['values_posted']['menu_roles'] = $data['menu_role'];
            }else {
                 $data['values_posted']['menu_roles'] = [];
            }

            $data['option']['parent'] = $this->parent($data['temp_menu']['menu_id']);
        } 
    
        $data['id'] = $id;
        $this->pktdblib->set_table("menus");
        $data['menus'] = $this->pktdblib->get_active_list();
        //echo '<pre>';print_r($data['menus']);exit;
        $data['option']['menu'][0] = 'Select Menu';
        foreach($data['menus'] as $menuKey => $menu){
            
            $data['option']['menu'][$menu['id']] = $menu['name'];
        }
        $data['target'] = $this->pktlib->get_target();
      
        
        
        $this->pktdblib->set_table('roles');
        $data['roles'] = $this->pktdblib->get_active_list();

        $data['option']['roles'][0] = 'Select Role';
        foreach($data['roles'] as $roleKey => $role){
            
            $data['option']['roles'][$role['id']] = $role['role_name'];
        }


        $data['modules'][] = 'menus';
        $data['methods'][]= 'admin_edit_menu_category';
        $data['title'] = 'Edit menu Category';
        $data['meta_title'] = 'Edit menu Category';
        $data['meta_description'] = 'Edit menu Category';
        //print_r($data['methods']);
        $data['js'][] = '<script type="text/javascript">
            CKEDITOR.replace("editor1");
            var html = $(".editor1").val();
            CKEDITOR.instances["editor1"].setData(html);
            $(document).on("submit", "#menu", function(){
              $(".editor1").val(CKEDITOR.instances.editor1.getData());
              $(".editor1").show();
              //return false;
            });
        </script>';
        echo Modules::run('templates/admin_template', $data);
    }

    function admin_edit_menu_category() {
        $this->load->view('menus/admin_edit_menu_category');
    }

    function get_menu_categories_list() {
        $this->menus_model->set_table('temp_menu');
        $menuCategories = $this->menus_model->get_menu_category_list();
        return $menuCategories;
    }

    function get_menu_category_details($id) {
        // echo "reached get_product_category_details";
        $this->pktdblib->set_table('temp_menu');
        $menuCategoryDetail = $this->pktdblib->get_where($id);
        //print_r($productCategoryDetail);
        return $menuCategoryDetail;
    }

     function admin_add_menuRole($menuId, $data) {
        $menu_id = $menuId;
        $this->pktdblib->set_table("menu_roles");
        $this->pktdblib->_delete_by_column('menu_detail_id', $menu_id);

        
        //echo 'reached in admin_add_newCities';print_r($data);exit;
        $insert = [];
        foreach ($data as $roleKey => $role) {
            $insert[$roleKey]['menu_detail_id'] = $menuId;
            $insert[$roleKey]['role_id'] = $role;
            $insert[$roleKey]['created'] = date("Y-m-d H:i:s");
            $insert[$roleKey]['modified'] = date("Y-m-d H:i:s");
        }
        //print_r($insert);exit;
        if(!empty($insert)) {
            $query = $this->insert_multiple($insert);
            return $query;
        }
    }

    function insert_multiple($data){
        //print_r($data);exit;
        $this->pktdblib->set_table("menu_roles");

        $query = $this->pktdblib->_insert_multiple($data);
        return $query;
    }

    function parent($menuId = 1){
        //echo '<pre>';
        $result = [0=>'Select'];
        $parents = $this->pktdblib->custom_query('Select * from temp_menu where is_active=true and parent_id=0 and menu_id='.$menuId.' order by id ASC');
        foreach ($parents as $key => $parent) {
            $result[$parent['id']] = $parent['name']; 
            $result = $this->child($parent['id'],'---', $result);
        }

        return $result;
        //echo '<pre>';
        //print_r($result);
    }

    function child($parent, $level='--', $result){
        //print_r($level);
        //print_r($result);
        //exit;
        $childs = $this->pktdblib->custom_query('Select * from temp_menu where is_active=true and parent_id='.$parent.' order by id ASC');
        foreach ($childs as $key => $child) {
            $result[$child['id']] = $level.$child['name']; 
            $result = $this->child($child['id'], '---'.$level, $result);
        }
       return $result;
    }

    function typeWiseMenuFilter(){
        //$_POST['params'] = 1;   
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $menu_id = $this->input->post('params');
            $result = [0=>['id'=>0, 'text'=>'Select']];
            $counter = 0;
            $parents = $this->pktdblib->custom_query('Select * from temp_menu where is_active=true and menu_id='.$menu_id.' and parent_id=0 order by id ASC');

            foreach ($parents as $key => $parent) {
                $counter = count($result);
                $result[$counter]['id'] = $parent['id']; 
                $result[$counter]['text'] = $parent['name']; 
                $result = $this->childMenuFilter($parent['id'],'---', $result);
                $counter = count($result);
            }
           /* echo '<pre>';
            print_r($result);
            exit;*/
            echo json_encode($result);
            exit;
            //return $result;
        }else{
            echo json_encode(['msg'=>'Invalid Input', 'status'=>false]);
            exit;
        }
    }

    function childMenuFilter($parentId, $level, $result){
         $childs = $this->pktdblib->custom_query('Select * from temp_menu where is_active=true and parent_id='.$parentId.' order by id ASC');
        foreach ($childs as $key => $child) {
            $counter = count($result);
            $result[$counter]['id'] = $child['id']; 
            $result[$counter]['text'] = $level.$child['name']; 
            /*$result[count($result)]['id'] = $child['id'];
            $result[count($result)]['text'] = $level.$child['name']; */
            $result = $this->childMenuFilter($child['id'], '---'.$level, $result);
        }
        return $result;
    }

    public function get_rolewise_menus()
    {
        //echo '<pre>';
        $menuArray = [];
        //print_r($this->session->userdata());exit;
        foreach ($this->session->userdata('roles') as $key => $role) {
            $menuRoles = $this->pktdblib->createquery([
                'table'=>'menu_roles', 
                'conditions'=>['menu_roles.role_id'=>$key],
                'fields'=>['menu_roles.menu_detail_id'],
                'order' => 'menu_detail_id asc'
            ]);
            //echo $this->db->last_query();
            //print_r($menuRoles);
            foreach ($menuRoles as $menuKey => $menuId) {
                array_push($menuArray, $menuId['menu_detail_id']);
            }
        }
        //exit;
        return array_unique($menuArray);
    }

}