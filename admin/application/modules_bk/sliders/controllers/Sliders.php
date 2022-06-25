<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sliders extends MY_Controller {

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
        
        $this->load->model("sliders/sliders_model");
        $setup = $this->setup();
       //print_r($query);exit;
	}

    function setup() {
        $slider = $this->sliders_model->tbl_slider();
        return TRUE;
    }

    function admin_index() {
        //$this->pktdblib->set_table('sliders');
        
        //print_r($data['vendors']);
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Slider";
        $data['meta_description'] = "Slider Panel";
        
        $data['modules'][] = "sliders";
        $data['methods'][] = "admin_slider_listing";
        
        echo Modules::run("templates/admin_template", $data);
    }

	function admin_slider_listing($data = []) {
        $data['sliders'] = $this->sliders_model->get_slider('sliders.id desc, sliders.is_active DESC');
		$this->load->view("sliders/admin_index", $data);
		//$this->load->view("admin_panel/login_register");
	}

    function admin_add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //echo '<pre>';print_r($_POST);exit;
            $data['values_posted'] = $_POST['data'];
            
            $this->form_validation->set_rules('data[sliders][name]', 'Name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[sliders][slider_code]', 'Code', 'max_length[255]');
            /*|is_unique[sliders.slider_code]*/
            if($this->form_validation->run()!== FALSE) { //echo "hii";
                $error = [];
                if (empty($error)) {
                    $post_data = $this->input->post('data[sliders]');
                    $post_data['created'] = $post_data['modified'] = date('Y-m-d H:i:s');
                    $this->pktdblib->set_table("sliders");
                    $reg_slider = json_decode($this->_register_admin_add($post_data), true);

                    if($reg_slider['status'] === 'success') {
                        $imageName = [];
                        $postData = $this->input->post('slider_details');
                        if(!empty($_FILES['slider_details']['name'])) {
                            $imageFileValidationParams = ['file'=>$_FILES['slider_details'], 'path'=>'../content/uploads/sliders/', 'ext'=>'jpeg|jpg|png|gif', 'fieldname'=>'image', 'arrindex'=>'slider_details'];
                            //print_r($imageFileValidationParams);exit;
                            $imageName = $this->pktlib->upload_multiple_file($imageFileValidationParams);
                            //print_r($imageName);exit;
                            $filename='';
                            if(empty($imageName['error'])) {
                                $filename = $imageName['filename'];
                                //unset($postData['image_2']);
                            }
                            else {
                                $error['image'] = $imageName['error'];
                            }
                        }
                        //exit;
                        if(empty($error))
                        $insert = [];
                        
                        foreach ($postData as $sliderKey => $value) {
                            $value['image'] = $filename[$sliderKey];
                            $value['created'] = date('Y-m-d H:i:s');
                            $value['modified'] = date('Y-m-d H:i:s');
                            $value['slider_id'] = $reg_slider['id']['id'];
                            $postData[$sliderKey] = $value;
                        }
                        
                        if(!empty($postData)) {
                            $this->pktdblib->set_table("slider_details");
                            $sliderDetails = $this->pktdblib->_insert_multiple($postData);
                        }
                        $msg = array('message'=> 'Data Added Successfully', 'class' => 'alert alert-success');
                        $this->session->set_flashdata('message', $msg);
                        redirect(custom_constants::new_slider_url);
                    }
                    else {
                        $data['form_error'] = $reg_slider['msg'];
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
        
        $data['imageType'] = $this->get_type();
       // print_r($data['imageType']);
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Slider Module";
        $data['meta_description'] = "New slider";
        
        $data['modules'][] = "sliders";
        $data['methods'][] = "admin_add_slider";
         $data['js'][] = '<script type="text/javascript">
            

             $("#type").on("change", function() {
                console.log(this.value);
                if (this.value == "image")
                {
                 console.log("type is Image");
                  $("#text").show();
                  $("#video").hide();
                  
                }
                else
                {
                 console.log("type is video");
                  $("#text").hide();
                  $("#video").show();
                }
            });
        
        </script>';
        
        echo Modules::run("templates/admin_template", $data);
           // }
    }

    function _register_admin_add($data) {
       // $this->roles_model->set_table("roles");
        $id = $this->pktdblib->_insert($data);
        return json_encode(["msg" => "Data Added Successfully", "status" => "success", 'id' => $id,  'is_new'=>true ]);
    }

	function admin_add_slider() {
		$this->load->view("sliders/admin_add");
	}

    function admin_edit($id = NULL) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['values_posted'] = $this->input->post();

            //echo "<pre>";
            //print_r($data['values_posted']['slider_details']);
           //exit;
            $this->form_validation->set_rules('data[sliders][name]', 'Slider Name', 'required|max_length[255]');
           
            if($this->form_validation->run()!== FALSE) {
                $profileImg = '';
                $post_data['sliders'] = $data['values_posted']['data']['sliders'];
               // print_r($post_data['sliders']);exit;
                //$post_data['slider_details'] = $data['values_posted']['slider_details'];
                
                if(empty($error)) {
                    $post_data['sliders']['modified'] = date('Y-m-d H:i:s');
                   
                    //print_r($post_data);exit;
                    $this->pktdblib->set_table("sliders");
                    if($this->edit_slider($id, $post_data['sliders'])) {
                        //echo "slider details updated";exit;
                        $postData = $data['values_posted']['slider_details'];
                        $productImg1 = '';
                        $insert = [];
                        $update = [];
                        if(count($_FILES['slider_details']['name'])>0) {
                                //echo " multiple images are found";//exit;
                                $sliderImageFileValidationParams1 = ['file'=>$_FILES['slider_details'], 'path'=>'../content/uploads/sliders/', 'ext'=>'jpeg|jpg|png|gif', 'fieldname'=>'image', 'arrindex'=>'slider_details'];
                                //print_r($sliderImageFileValidationParams1);exit;
                                $productImg1 = $this->pktlib->upload_multiple_file($sliderImageFileValidationParams1);
                                /*echo '<pre>';
                                print_r($productImg1);*/
                                //exit;
                                if(empty($productImg1['error'])) {
                                    $postData['productImg1'] = $productImg1['filename'];
                                    unset($postData['image_2']);
                                }
                                //print_r($productImg1);exit;
                                if(!$productImg1) {
                                    $msg = array('message' => "Some error occured with file", 'class'=>'alert alert-danger');
                                    $this->session->set_flashdata('message', $msg); 
                                }                                
                            }

                        foreach ($data['values_posted']['slider_details'] as $imageKey => $value) {
                            //echo $imageKey;
                            //print_r($value);
                            if(!empty($productImg1['filename'][$imageKey])) {
                                $_POST['slider_details'][$imageKey]['image'] = $productImg1['filename'][$imageKey];
                            }else {
                                $_POST['slider_details'][$imageKey]['image'] = $_POST['slider_details'][$imageKey]['image_2'];
                            }
                            
                            $_POST['slider_details'][$imageKey]['slider_id'] = $id;
                            
                            if(isset($_POST['slider_details'][$imageKey]['is_active'])){
                                    $_POST['slider_details'][$imageKey]['is_active'] = true;
                            }else{
                                $_POST['slider_details'][$imageKey]['is_active'] = false;
                            }

                            unset($_POST['slider_details'][$imageKey]['image_2']);

                            $_POST['slider_details'][$imageKey]['modified'] = date('Y-m-d H:i:s');
                            if(isset($value['id']) && !empty($value['id'])) {
                                $update[] = $_POST['slider_details'][$imageKey];
                                //print_r($update);
                            }else {
                                unset($_POST['slider_details'][$imageKey]['id']);
                                $_POST['slider_details'][$imageKey]['created'] = date('Y-m-d H:i:s');
                                $insert[] = $_POST['slider_details'][$imageKey];
                            }
                        }
                        /*echo '<pre>';
                        print_r($update);
                        print_r($insert);
                        exit;*/
                        if(!empty($update)) {
                            $this->pktdblib->set_table("slider_details");
                            $this->pktdblib->update_multiple('id',$update);
                        }

                        if(!empty($insert)) {
                            $this->pktdblib->set_table("slider_details");
                            $this->pktdblib->_insert_multiple($insert);
                        }
                                $msg = array('message' => "Slider Update successfully", 'class' => 'alert alert-success fade-in');
                                $this->session->set_flashdata('message', $msg);
                            }
                            else {
                                $msg = array('message' => 'some problem occured while updating', 'class' => 'alert alert-danger fade-in');
                                $this->session->set_flashdata('message', $msg);
                            }
                            redirect(custom_constants::edit_slider_url."/".$id);
                        }
                        else {
                            $msg = array('message' => $error, 'class' =>'alert alert-danger fade-in');
                            $this->session->set_flashdata('error', $msg);
                        }
                    }
                    else {
                        $msg = array('message' => 'some validation error occured', 'class' => 'alert alert-danger fade-in');
                    }
        }else {
            $this->pktdblib->set_table("sliders");
            $data['values_posted']['sliders'] = $this->pktdblib->get_where($id);
            $this->pktdblib->set_table("slider_details");
            $slider_details = $this->pktdblib->get_where_custom('slider_id', $data['values_posted']['sliders']['id']);
            $data['values_posted']['slider_details'] = $slider_details->result_array();
            //echo '<pre>';print_r($data['values_posted']['slider_details']);
        }
        $data['id'] = $id;
        $data['imageType'] = $this->get_type();
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Slider Module";
        $data['meta_description'] = "New Slider";
        $data['modules'][] = "sliders";
        $data['methods'][] = "admin_edit_slider";
         $data['js'][] = '<script type="text/javascript">
            

             $("#type").on("change", function() {
                console.log(this.value);
                if (this.value == "image")
                {
                 console.log("type is Image");
                  $("#text").show();
                  $("#video").hide();
                  
                }
                else
                {
                 console.log("type is video");
                  $("#text").hide();
                  $("#video").show();
                }
            });
        
        </script>';
        
        echo Modules::run("templates/admin_template", $data);
    }

    function admin_edit_slider() {
        $this->load->view("sliders/admin_edit");
    }

    function edit_slider($id=NULL, $post_data = []) {
        if(NULL == $id)
            return false;
        $this->pktdblib->set_table("sliders");
        if($this->pktdblib->_update($id,$post_data))
            return true;
        else
            return false;
    }

    function get_type(){
        $query = [0=>'Select Type', 'image'=>'Image', 'video'=>'Youtube Video']; 
        //print_r($query);
        return $query;
    }
}