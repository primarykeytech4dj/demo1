<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_documents extends MY_Controller {
	function __construct() {
		parent::__construct();
		check_user_login(FALSE);
		$this->load->model('upload_documents/upload_documents_model');
		$setup = $this->setup();
		//$this->upload_documents_model->set_table('user_documents');
	}

	function setup(){
        $userDocument = $this->upload_documents_model->tbl_user_documents();
        $document = $this->upload_documents_model->tbl_documents();
        return TRUE;
    }

	function admin_index() {
		$data['meta_title'] = 'Document Uploads';
		$data['meta_description'] = 'Document Uploads';
		$data['modules'][] = 'upload_documents';
		$data['methods'][] = 'admin_document_listing';
		echo Modules::run("templates/admin_template", $data); 	
	}

	function admin_document_listing($data = []) {
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		//echo "string"; exit;
		//print_r($condition);exit;
		$data['documents'] = $this->upload_documents_model->get_document_list($condition);
		//print_r($data['accounts']);exit;
		$this->load->view("upload_documents/admin_index", $data);
	}

	function admin_add($data = []) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$documentFile = [];
			/*echo '<pre>';
			print_r($this->input->post('user_documents'));
			exit;*/
            if(!empty($_FILES['user_documents']['name'])){
                $documentFileValidationParams = ['file'=>$_FILES['user_documents'], 'path'=>'../content/uploads/documents/', 'ext'=>'gif|jpg|png|jpeg|pdf|xls|xlxs', 'fieldname'=>'file', 'arrindex'=>'user_documents'];
                $documentFile = $this->pktlib->upload_multiple_file($documentFileValidationParams);
                if(!empty($documentFile['error'])){
                    $res = array('msg' => 'Some Error occured with File','class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('msg',$res);
                    redirect($this->input->post('url').'?tab=document');
                }
            }
            //print_r($documentFile);
            $insertArray = [];
            $updateArray = [];
            foreach ($this->input->post('user_documents') as $documentKey => $documentValue) {
            	if(!empty($documentFile['filename'][$documentKey])){
	            	$documentValue['file'] = $_POST['user_documents'][$documentKey]['file'] = $documentFile['filename'][$documentKey];
	            }else{
	            	if(isset($documentValue['file2'])){
	            		$documentValue['file'] = $_POST['user_documents'][$documentKey]['file'] = $documentValue['file2'];
	            	}
	            }
	            if(isset($documentValue['file2'])){
	            	unset($documentValue['file2']);
	            }

            	if(isset($documentValue['is_active']))
            		$documentValue['is_active'] = true;
            	else
            		$documentValue['is_active'] = false;

            	if(!empty($_POST['user_documents'][$documentKey]['file'])){
	            	if(isset($documentValue['id']) && !empty($documentValue['id'])){

	            		$updateArray[] = $documentValue;
	            	}else{
	            		$insertArray[] = $documentValue;
	            	}
	            }
            }
            /*echo '<pre>';
            print_r($insertArray);
            print_r($updateArray);
            exit;*/
            if(!empty($insertArray)){
            	$user_document = json_decode($this->upload_multiple_doc($insertArray), true);
            }

            if(!empty($updateArray)){
            	$user_document = json_decode($this->update_multiple_doc($updateArray, 'id'), true);
            }

            $msg = array('message' => 'Document Added Successfully','class' => 'alert alert-success');
            $this->session->set_flashdata('message',$msg);
            redirect($_POST['url']);
			//exit;
			
		}
		
		$data['meta_title'] = "Upload Document";
		$data['meta_description'] = "Upload Document";
		$data['modules'][] = "upload_documents";
		$data['methods'][] = "admin_add_form";
		echo Modules::run("templates/admin_template", $data);
	} 

	function upload_doc($data) { 
		$insert_data = $data;
		$this->pktdblib->set_table("user_documents");
		$id = $this->pktdblib->_insert($insert_data);
		return json_encode(["msg"=>"Document Added Successfully", "status"=>"success", 'id'=>$id]);
	}

	function upload_multiple_doc($data) { 
		$insert_data = $data;
		$this->pktdblib->set_table("user_documents");
		$id = $this->pktdblib->_insert_multiple($insert_data);
		return json_encode(["msg"=>"Document Added Successfully", "status"=>"success", 'id'=>$id]);
	}

	function update_multiple_doc($data, $field) { 
		$update_data = $data;
		$this->pktdblib->set_table("user_documents");
		$id = $this->pktdblib->update_multiple($field, $update_data);
		return json_encode(["msg"=>"Document Updated Successfully", "status"=>"success", 'id'=>$id]);
	}

	function document_details($id) {
		$this->pktdblib->set_table('user_documents');
		$accountDetails = $this->pktdblib->get_where($id);
		return $accountDetails;
	}

	function custom_document_details($data = []) {
		$documentDetails = $this->upload_documents_model->userBasedDefaultDocument($data['user_id'], $data['type']);
		return $documentDetails;
	}

	function admin_add_form($data = []) {
		$data['values_posted'] = [];
		if(NULL !== $this->input->post('data'))
			$data['values_posted'] = $this->input->post();

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
		}elseif(isset($data['module']) && $data['module']=='orders'){
			
			$this->load->model('customers/customer_model');
			$this->customer_model->set_table('customers');
			$customer = $this->customer_model->get_where($data['order']['customer_id']);
			
			$data['user_detail'] = $customer->row_array();
			$data['users'] = [$data['order']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['company_name']." | ".$data['order']['order_code']];
			$data['option']['typeLists'] = ['orders'=>'Orders (Projects)'];
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

		$data['userDocuments'] = [];
		if(isset($data['user_id'])){
			$data['userDocuments'] =  Modules::run("upload_documents/custom_document_details", $data);
		}

		$data['documents'] = $this->upload_documents_model->get_document_type_list();
		$this->load->view('upload_documents/admin_add', $data);
	}

	function view_user_wise_documents($data = NULL) {
		if(NULL == $data){
			show_404();
		}
		$data['documents'] = $this->upload_documents_model->userBasedDocument($data['user_id'], $data['type']);
		$this->load->view('upload_documents/admin_document_listing', $data);;
	}

	function type_wise_user(){
		
		if(!$this->input->post('params'))
			return;

		$addressType = $this->input->post('params');
		
		//$this->upload_documents_model->set_table('address');
		$typeWiseUsers = $this->upload_documents_model->get_custom_document_type_users($addressType);
		$userList = [0=>['id'=>0, 'text'=>'Select User']];
		foreach ($typeWiseUsers as $key => $typeWiseUser) {
			$userList[$key+1]['id'] = $typeWiseUser['id'];
			$userList[$key+1]['text'] = $typeWiseUser['fullname']." | ".$typeWiseUser['emp_code'];
		}
		
		echo json_encode($userList);
		exit;

	}

	function deactivate_document($id){
		$this->pktdblib->set_table('documents');
		return $this->pktdblib->_update($id, ['is_active'=>FALSE]);
	}

	function download_document($id){
		/*echo '<pre>';
		print_r($_SERVER);exit;*/
		$documentDetails = Modules::run("upload_documents/document_details", $id);
		/*echo content_url().'uploads/documents/'.$documentDetails['file'];
		exit;*/
		if(file_exists('../content/uploads/documents/'.$documentDetails['file'])){
			//echo $documentDetails['file'];exit;
			force_download('../content/uploads/documents/'.$documentDetails['file'], NULL);
		} else {
			$msg = array('message' => 'File Does not Exists','class' => 'alert alert-danger');
            $this->session->set_flashdata('message',$msg);
            redirect($_SERVER['HTTP_REFERER']);
		}

	}

	function admin_add_docType() {
		//echo "reached here";exit;
		$data['values_posted'] = [];
		

		$data['documents'] = $this->upload_documents_model->get_document_type_list();
		$data['meta_title'] = 'Document Types';
		$data['meta_description'] = 'Document Types';
		$data['values_posted']['data']['documents'] = $data['documents'];
		$data['modules'][] = 'upload_documents';
		$data['methods'][] = 'documentTypeForm';
		echo Modules::run("templates/admin_template", $data); 
	}

	function documentTypeForm() {
		
		$this->load->view('upload_documents/admin_add_docType');
	}

	function add_form($data = []) {
		
		$data['values_posted'] = $this->pktlib->createquery(['table'=>'user_documents', 'conditions'=>['user_documents.user_id'=>$this->session->userdata('employee_id'), 'user_documents.user_type'=>'customers', 'user_documents.is_active'=>true]]);

		//print_r($data['values_posted']);exit;
		if(NULL !== $this->input->post('data'))
			$data['values_posted'] = $_POST;

		
		$data['userDocuments'] = [];
		if(isset($data['user_id'])){
			$data['userDocuments'] =  Modules::run("upload_documents/custom_document_details", $data);
		}

		$data['documents'] = $this->upload_documents_model->get_document_type_list();
		$this->load->view('upload_documents/add', $data);
	}


	function add($data = []) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$documentFile = [];
            if(!empty($_FILES['user_documents']['name'])){
                $documentFileValidationParams = ['file'=>$_FILES['user_documents'], 'path'=>'../content/uploads/documents/', 'ext'=>'gif|jpg|png|jpeg|pdf|xls|xlxs', 'fieldname'=>'file', 'arrindex'=>'user_documents'];
                $documentFile = $this->pktlib->upload_multiple_file($documentFileValidationParams);
                if(!empty($documentFile['error'])){
                    $res = array('msg' => 'Some Error occured with File','class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('msg',$res);
                    redirect($_POST['url'].'?tab=document');
                }
            }
            //print_r($documentFile);
            $insertArray = [];
            $updateArray = [];
            foreach ($_POST['user_documents'] as $documentKey => $documentValue) {
            	if(!empty($documentFile['filename'][$documentKey])){
	            	$documentValue['file'] = $_POST['user_documents'][$documentKey]['file'] = $documentFile['filename'][$documentKey];
	            }else{
	            	if(isset($documentValue['file2'])){
	            		$documentValue['file'] = $_POST['user_documents'][$documentKey]['file'] = $documentValue['file2'];
	            	}
	            }
	            if(isset($documentValue['file2'])){
	            	unset($documentValue['file2']);
	            }

            	if(isset($documentValue['is_active']) && $documentValue['is_active']==true)
            		$documentValue['is_active'] = true;
            	else
            		$documentValue['is_active'] = false;

            	if(!empty($_POST['user_documents'][$documentKey]['file'])){
            		$documentValue['user_id'] = $this->session->userdata('employee_id');
            		$documentValue['user_type'] = 'customers';
	            	if(isset($documentValue['id']) && !empty($documentValue['id'])){

	            		$updateArray[] = $documentValue;
	            	}else{
	            		$insertArray[] = $documentValue;
	            	}
	            }
            }
            if(!empty($insertArray)){
            	$user_document = json_decode($this->upload_multiple_doc($insertArray), true);
            }

            if(!empty($updateArray)){
            	$user_document = json_decode($this->update_multiple_doc($updateArray, 'id'), true);
            }

            $msg = array('message' => 'Document Added Successfully','class' => 'alert alert-success');
            $this->session->set_flashdata('message',$msg);
            redirect($_POST['url']);
			//exit;
			
		}
		
		$data['meta_title'] = "Upload Document";
		$data['meta_description'] = "Upload Document";
		$data['modules'][] = "upload_documents";
		$data['methods'][] = "add_form";
		echo Modules::run("templates/admin_template", $data);
	}

}