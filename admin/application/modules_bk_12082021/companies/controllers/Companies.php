<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies extends MY_Controller {

	function __construct() {
		parent::__construct();
		foreach(custom_constants::$protected_pages as $page) {	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->model('companies/companies_model');
		$this->load->model('address/address_model');
		$this->load->model('login/mdl_login');
		$setup = $this->setup();
	}

	function setup(){
		$companies = $this->companies_model->tbl_companies();
		$menus = Modules::run('menus/setup');
		//echo "reached here";exit;
		return TRUE;
	}

	function index(){
		$id = 1;
		$data = ['id'=>$id];
		$type = 'companies';
		$data['companyDetail'] = Modules::run('companies/get_company_details', $data);
		$data['title'] = $data['companyDetail']['company_name'];
		$data['meta_title'] = $data['companyDetail']['meta_title'];
		$data['meta_description'] = $data['companyDetail']['meta_description'];
		$data['meta_keyword'] = $data['companyDetail']['meta_keyword'];
		$this->address_model->set_table("address");
		$data['address'] = $this->address_model->userBasedAddress($id, $type);
		$data['content'] = 'companies/company_details';

		//$data['contact'] = Modules::run("enquiries/contact_form_1");
		$data['contact'] = Modules::run("enquiries/contact_form_2");

		//echo "hi"; exit;
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'Contact']
		];
		echo Modules::run('templates/obaju_inner_template', $data);
	}

	function admin_index() {
		check_user_login(FALSE);
		$data['companies'] = $this->get_all_company_list();
		$data['meta_title'] = 'Companies';
		$data['meta_description'] = 'Companies';
		$data['meta_keyword'] = 'Companies';
		$data['content'] = 'companies/admin_index';
		echo modules::run('templates/admin_template', $data);
	}

	function get_all_company_list() {
		/*echo '<pre>';
		print_r($_SESSION);
		echo '</pre>';*/
		$query = $this->pktdblib->custom_query('Select c.* from companies c where c.id in (Select company_id from companies_companies where parent_company_id="'.$_SESSION['companies']['id'].'")');
		return $query;
	}

	function admin_view($id=NULL) {
		if(NULL==$id) {
			//echo "hi";
			redirect('companies/admin_index');
		}
			//echo "hello";

		$this->pktdblib->set_table('companies');
		$company = $this->pktdblib->get_where($id);
		$data['user'] = $company;
		//print_r($data['user']);
		$data['content'] = 'companies/admin_view';
		$data['meta_title'] = 'Companies';
		$data['meta_description'] = 'Companies';
		$addressListData = ['condition'=>['address.user_id'=>$id, 'address.type'=>'companies'], 'module'=>'companies'];
		//$this->address_model->set_table('address');
		$data['addressList'] = Modules::run("address/address_listing", $addressListData);

		$bankAccountListData = ['condition'=>['bank_accounts.user_id'=>$id, 'bank_accounts.user_type'=>'companies'], 'module'=>'companies'];
		//$this->address_model->set_table('address');
		$data['bankAccountList'] = Modules::run("bank_accounts/account_listing", $bankAccountListData);

		/*Documents*/
		$documentListData = ['condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'companies'], 'module'=>'companies'];
		//$this->address_model->set_table('bank_accounts');
		$data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

		$data['otherDetailsList'] = Modules::run('companies/employee_other_details', ['employee_id'=>$id]);

		echo Modules::run("templates/admin_template", $data);
		//$this->templates->admin_template('', $data);
		//print_r($data['employee']);
	}


	function get_company_details($data = []){
		//print_r($data);exit;
		$type = 'companies';
		$this->pktdblib->set_table("companies");
		$companyDetail = $this->pktdblib->get_where($data['id']);

		return $companyDetail;
	}

	function about_company($slug = ''){
		if('' == $slug){
			$this->pktdblib->set_table('companies');
			$company = $this->pktdblib->get_where_custom('is_active', true);
			$company = $company->row_array();
			$slug = $company['slug'];
		}

		$data['companyDetail'] = Modules::run('companies/get_slugwise_company_details', $slug);
		$data['title'] = $data['companyDetail']['company_name'];
		$data['meta_title'] = $data['companyDetail']['meta_title'];
		$data['meta_description'] = $data['companyDetail']['meta_description'];
		$data['meta_keyword'] = $data['companyDetail']['meta_keyword'];
		//$data['modules'][] = "companies";
		//$data['modules'][] = "employees";

		//$data['methods'][] = "get_company_details";
		//$this->address_model->set_table("address");
		$data['aboutus'] = $data['companyDetail']['about_company'];
	//print_r($data['address']);
		$data['content'] = 'companies/aboutus';

		//$data['contact'] = Modules::run("enquiries/contact_form_1");
		//print_r($data);
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'About '.$data['companyDetail']['company_name']]
		];
		echo Modules::run('templates/obaju_inner_template', $data);
	}

	function get_slugwise_company_details($slug = ''){
		if($slug==''){
			show_404();
			exit;
		}
		//print_r($data);exit;
		$this->pktdblib->set_table("companies");
		$companyDetail = $this->pktdblib->get_where_custom('slug', $slug);

		return $companyDetail->row_array();
	}
	
	function admin_add(){
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $this->input->post('data');
			$this->form_validation->set_rules('data[companies][company_name]', ' Company Name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[companies][first_name]', ' First Name', 'required|max_length[255]');
			//$this->form_validation->set_rules('data[companies][middle_name]', ' middle Name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[companies][surname]', 'Surname', 'required|max_length[255]');
			$this->form_validation->set_rules('data[companies][primary_email]', ' Primary Email', 'required|max_length[255]|is_unique[login.email]');
			$this->form_validation->set_rules('data[companies][secondary_email]', 'Secondary Email', 'max_length[255]');
			$this->form_validation->set_rules('data[companies][contact_1]', 'Contact 1', 'required');
			$this->form_validation->set_rules("data[companies][website]", 'website', 'is_unique[companies.website]');
			//$this->form_validation->set_rules('data[companies][contact_2]', ' contact_2', 'numeric');

			if($this->form_validation->run('companies')!== false) {
				$error = [];
				$logoImg = '';
				
				//print_r($_FILES['logo']['name']);//exit;
				if(!empty($_FILES['logo']['name'])){
					$logoFileValidationParams = ['file'=>$_FILES['logo'], 'path'=>'../content/uploads/profile_images/', 'ext'=>'jpg|png|gif|jpeg', 'fieldname'=>'logo', 'arrindex'=>'logo'];
					//print_r($logoFileValidationParams);//exit;
					$logoImg = $this->pktlib->upload_single_file($logoFileValidationParams);
					//print_r($logoImg);exit;
					if(empty($logoImg['error'])) {
						$_POST['data']['companies']['logo'] = $logoImg['filename'];
					}
					else {
						//print_r($logoImg['error']['logo']);
						$error['companies'] = $logoImg['error']['logo']['logo'];
					}
				}
				if(empty($error)) {
					//print_r($this->input->post('data[companies]'));
					$postData = $this->input->post('data[companies]');
					$postData['created'] = $postData['modified'] = date('Y-m-d H:i:s');
					$config = array(
	                    'table'         => 'companies',
	                    'id'            => 'id',
	                    'field'         => 'slug',
	                    'title'         => 'company_name',
	                    'replacement'   => 'dash' // Either dash or underscore
	                );
	                $this->load->library('slug', $config);
					$postData['slug'] = $this->slug->create_uri(array('company_name' => $this->input->post('data[companies][company_name]')), '', '');
					$regCompany = json_decode($this->register_company($postData),true);
					/*$register_product_category = json_decode($this->register_product_category($post_data), true);*/
					//print_r($regCompany);exit;
					if($regCompany['status'] === "success") {
						$postData['id'] = $regCompany['id'];
                        $login = Modules::run('login/register_company_to_login', $postData);
                        //print_r($login);//exit;
						$msg = array('message'=>'Company Added Successfully. Company Id : '.$regCompany['id'], 'class'=>'alert alert-success');
						$this->session->set_flashdata('message', $msg);
						redirect('companies/admin_edit/'.$regCompany['id']);
					}
					else {
						$msg = array('message'=>'Failed to add Company', 'class'=>'alert alert-danger');
						$data['form_error'] = $register_product_category['msg'];
						$this->session->set_flashdata('message', $msg);
					}
				}
				else {
					$msg = array('message'=>'Some error occured while adding'.$error['companies'], 'class'=>'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
				}
			}
			else {
				$msg = array('message'=>'Some error occured while adding'.validation_errors(), 'class'=>'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
			}
		}
		$data['title'] = 'New Company';
		$data['meta_title'] = 'New Company';
		$data['meta_description'] = 'New Company';
		$data['modules'][] = 'companies';
		$data['methods'][] = 'admin_add_new_company';
		$data['js'][] = '<script type="text/javascript">
            CKEDITOR.replace("about_company");
            $(document).on("submit", "#companies", function(){
              $(".editor1").val(CKEDITOR.instances.editor1.getData());
              $(".editor1").show();
            });
        </script>';
		echo Modules::run('templates/admin_template', $data);

	}

	function admin_add_new_company() {
		$this->load->view('companies/admin_add');
	}

	function register_company($data) {
		$this->pktdblib->set_table('companies');
		if(!isset($data['slug'])){
			$slug = str_replace(' ', '-', $data['company_name']);
			$exist = $this->pktdblib->get_where_custom('slug', $slug);
			if($exist->num_rows()==0){
				$data['slug'] = $slug;
			}else{
				$data['slug'] = $slug.'-'.(($exist->num_rows())+1);
			}
		}
		$id = $this->pktdblib->_insert($data);
		
        $company = $this->get_company_details($id['id']);
        //print_r($id);
        return json_encode(["msg" => "Data Added Successfully", "status" => "success", 'id' => $id['id'], 'companies' => $company, 'is_new'=>true ]);
		//return json_encode(['message'=>'Company Added Successfully ', 'status'=>'success', 'id'=> $id]);
	}

	function admin_edit($id) {
		check_user_login(FALSE);
		//echo $id;
		if($_SERVER['REQUEST_METHOD']=='POST') {
			$data['values_posted'] = $_POST['data']; 

			$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
			$this->form_validation->set_rules("data[companies][company_name]", 'Company Name', 'required');
			$this->form_validation->set_rules("data[companies][first_name]", 'First Name', 'required|max_length[255]');
			$this->form_validation->set_rules("data[companies][middle_name]", 'Middle Name', 'max_length[255]');
			$this->form_validation->set_rules("data[companies][surname]", 'Surname', 'required|max_length[255]');
			$this->form_validation->set_rules("data[companies][primary_email]", 'Primary Email', 'required|max_length[255]|valid_email');
			$this->form_validation->set_rules("data[companies][secondary_email]", 'Secondary Email', 'max_length[255]|valid_email');
			$this->form_validation->set_rules("data[companies][contact_1]", 'Contact 1', 'required');
			

			if($this->form_validation->run('companies')!== false){
				$logoImg = '';
				$postData = $_POST['data']['companies'];
				//echo content_url();
				if(!empty($_FILES['logo']['name'])) {
					$logoFileValidationParams = ['file'=>$_FILES['logo'], 'path'=>'../content/uploads/profile_images', 'fieldname'=>'logo', 'ext'=>'jpeg|png|jpg|gif', 'arrindex'=>'logo'];
					//print_r($logoFileValidationParams);exit;
					$logoImg = $this->pktlib->upload_single_file($logoFileValidationParams);
					//print_r($logoImg);exit;
					if(empty($logoImg['error'])) {
						$postData['logo'] = $logoImg['filename'];
						unset($postData['logo2']);
					}
					else {
						$error['logo'] = $logoImg['error'];
					}
				}
				else {
					$postData['logo'] = $postData['logo2'];
					unset($postData['logo2']);
				}

				if(empty($error)) {
					/*echo '<pre>';
					print_r($postData);
					echo '</pre>';
					exit;*/
					if(isset($postData['is_active']))
						$postData['is_active'] = true;
					else
						$postData['is_active'] = false;

					$config = array(
	                    'table'         => 'companies',
	                    'id'            => 'id',
	                    'field'         => 'slug',
	                    'title'         => 'company_name',
	                    'replacement'   => 'dash' // Either dash or underscore
	                );
	                $this->load->library('slug', $config);
					$postData['slug'] = $this->slug->create_uri(array('company_name' => $this->input->post('data[companies][company_name]')), $id, '');
					$this->pktdblib->set_table('companies');
					if($this->pktdblib->_update($id, $postData)) {
						$msg = array('message'=>'Data Update Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
					}
					else {
						$msg = array('message'=>'some problem occured', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
					}
					redirect(custom_constants::edit_company_url."/".$id.'?tab=address');
				} 
				else {
					$msg = array('message'=>'some error occured while uploading'.$error, 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}
			} 
			else {
				$data['values_posted']['companies']['logo'] = $data['values_posted']['companies']['logo2'];
				$data['companies'] = $data['values_posted']['companies'];
				$msg = array('message'=>'some validation error occured'.validation_errors(), 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
			}
		}
		else {
			//$this->companies_model->set_table('companies');
			$data['companies'] = $this->company_details($id);
			$data['values_posted']['companies'] = $data['companies'];
		}
		$data['id'] = $id;
		if(!($this->input->get('tab'))){
			$data['tab'] = 'personal_info';
		}
		else {
			$data['tab'] = $this->input->get('tab');	
		}
		$data['title'] = 'Edit Company';
		$data['meta_title'] = 'Edit Company';
		$data['meta_description'] = 'Edit Company';
		$data['infrastructures'] = Modules::run('companies/admin_edit_infrastructure', $id);
		$data['media'] = Modules::run('companies/admin_edit_media', $id);
		$data['modules'][] = 'companies';
		$data['methods'][] = 'admin_edit_form';

		$userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>'companies']);
		$loginId = $userRoles[0]['login_id'];
		$roleId = $userRoles[0]['role_id'];
		// print_r($userRoles);
		// exit;
		$AddressListData = ['url'=>custom_constants::edit_company_url.'/'.$id.'?tab=address', 'condition'=>['address.user_id'=>$loginId, 'address.type'=>'login'], 'module'=>'companies', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['companies']];
		$data['addressList'] = Modules::run('address/admin_address_listing', $AddressListData);
		$this->pktdblib->set_table('address');
		$addressData = ['url'=>custom_constants::edit_company_url.'/'.$id.'?tab=bank_account', 'module'=>'companies', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['companies']];
		if($this->input->get('address_id')) {
			$addressData['address'] = $this->pktdblib->get_where($this->input->get('address_id'));
			$data['address'] = Modules::run('address/admin_edit_form',$addressData);
		}else {
			$data['address'] = Modules::run("address/admin_add_form", $addressData);
		}

		/* Bank Account Related Code Starts Here  */
		$bankAccountListData = ['url'=>custom_constants::edit_company_url.'/'.$id.'?tab=bank_account', 'condition'=>['bank_accounts.user_id'=>$loginId, 'bank_accounts.user_type'=>'login'], 'module'=>'companies'];
		$this->pktdblib->set_table('bank_accounts');
		$data['bankAccountList'] = Modules::run("bank_accounts/admin_index_listing", $bankAccountListData);
		//print_r($data['bankAccountList']);exit;
		//$this->pktdblib->set_table('bank_accounts');
		$bankAccountData = ['url'=>custom_constants::edit_company_url.'/'.$id.'?tab=document', 'module'=>'companies', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['companies']];
		if($this->input->get('bank_account_id')){ 
			$bankAccountData['bank_accounts'] = Modules::run("bank_accounts/account_details", $this->input->get('bank_account_id'));
			$data['bank_account'] = Modules::run("bank_accounts/admin_edit_form", $bankAccountData);
			//print_r($data['bank_account']);exit;
		}else{
			$data['bank_account'] = Modules::run("bank_accounts/admin_add_form", $bankAccountData);
		}
		/*Bank account ends*/

		/*Document Uploads*/
		$documentListData = ['condition'=>['user_documents.user_id'=>$loginId, 'user_documents.user_type'=>'login'], 'module'=>'companies'];
		//$this->pktdblib->set_table('bank_accounts');
		$data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

		$documentData = ['url'=>custom_constants::edit_company_url.'/'.$id.'?tab=document', 'module'=>'companies', 'user_id'=>$loginId, 'type'=>'login', 'user_detail'=>$data['companies']];
		
		$data['document'] = Modules::run("upload_documents/admin_add_form", $documentData);

		/*Login Related Code*/
		$loginData = ['url'=>custom_constants::edit_company_url.'/'.$id, 'module'=>'companies', 'company_id'=>$id, 'account_type'=>'companies', 'user_detail'=>$data['companies']];
		$this->mdl_login->set_table("user_roles");
		$userRoleCondition['user_id'] = $id;
		$userRoleCondition['role_id'] = $roleId; 
		$userRoleCondition['login_id'] = $loginId; 
		$loginData['user'] = $this->mdl_login->get_user_details($userRoleCondition);
		//print_r($loginData['user']);exit;
		//$loginCondition['id'] = $loginId;
		//print_r($loginCondition);exit;
		$this->pktdblib->set_table("login");
		$loginData['login'] = $this->pktdblib->get_where($loginId);
		//print_r($loginData['login']);exit;
		$loginData['id'] = $_GET['login_id'] = $loginId;
		/*echo '<pre>';
		print_r($loginData['login']);exit;*/
		/*if($this->input->get('login_id')) {  echo "hii";
			$loginEditData = $this->employees_model->get_where($this->input->get('login_id'));
			$loginData['login'] = $loginEditData->row_array();*/
			/*echo '<pre>';
			print_r($loginData);exit;*/
			//echo "reached here";
		$data['login'] = Modules::run("login/view_edit_login", $loginData);//exit;
 		/*Login related code ends*/

        $data['js'][] = '<script type="text/javascript">
            CKEDITOR.replace("about_company",{
                height:400,
                filebrowserUploadUrl:assets_url+"admin_lte/plugins/ckeditor_full/fileupload.php",
            });
            $(document).on("submit", "#companies", function(){
              $(".editor1").val(CKEDITOR.instances.editor1.getData());
              $(".editor1").show();
            });
        </script>';


		
		echo Modules::run('templates/admin_template', $data);

	}

	function admin_edit_form(){
		$this->load->view('companies/admin_edit');
	}

	function company_details($id) {
		$this->pktdblib->set_table('companies');
		$companyDetails = $this->pktdblib->get_where($id);
		//echo '<pre>';
		//print_r($companyDetails);
		return $companyDetails;
	}

	function get_Customer_list_dropdown(){
		$this->pktdblib->set_table('companies');
        $companies = $this->pktdblib->get('companies.is_active desc');
        //print_r($customers);
        $dropDown = [''=>'Select Companies'];
        foreach ($companies as $key => $company) {
            $dropDown[$company['id']] = $company['company_name'];
            if(!empty($company['short_code']))
                $dropDown[$company['id']].=' | '.$company['short_code'];
        }
        return $dropDown;
        //exit;
        //print_r($dropDown);
    }

	function deleteInfraDetails() {
		$_POST['id'] = 1;
		$_POST['table'] = 'company_infrastructures';
		$_POST['is_active'] = 'is_active';

		$_SERVER['REQUEST_METHOD'] = 'POST';
		//print_r($_SERVER['REQUEST_METHOD']);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = $this->input->post('id');
			//echo "hi";
			//echo $id;exit;
			$this->pktdblib->set_table($this->input->post('table'));
			$infraDetails = $this->pktdblib->get_where($id);
			//print_r($faqDetails);
			//$faqDet = $faqDetails->row_array();
			$arrayData['is_active'] = $this->input->post('is_active');
			$arrayData['modified'] = date('Y-m-d H:i:s');
			$response = $this->pktdblib->_update($id, $arrayData);
			///print_r($response);exit;
			if($response){
				echo json_encode(['status'=>'success', 'id'=>$id]);
				exit;
			}
		}
			
		echo json_encode(['status'=>'error', 'msg'=>'Invalid Input']);
		exit;
		
	}

	function admin_add_infra() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST;
			
			$this->form_validation->set_rules('data[company_infrastructures][comment]', ' Comment', 'required|min_length[10]');
			if($this->form_validation->run('company_infrastructures')!== false) {
				$error = [];
				if(empty($error)) {
					$postData = $_POST['data']['company_infrastructures'];
					$postData['created'] = date('Y-m-d H:i:s');
					$postData['modified'] = date('Y-m-d H:i:s');
					$regInfra = json_decode($this->register_infra($postData),true);
					
					if($regInfra['status'] === "success") {
						//echo "infra added . Id = ". $regInfra['id'];exit;
						$imageName = '';
						if(!empty($_FILES['infrastructure_medias']['name'])) {
							$imageFileValidationParams = ['file'=>$_FILES['infrastructure_medias'], 'path'=>'./assets/uploads/media', 'ext'=>'jpeg|jpg|png|gif', 'fieldname'=>'image', 'arrindex'=>'infrastructure_medias'];
							//print_r($imageFileValidationParams);exit;
							$imageName = $this->pktlib->upload_multiple_file($imageFileValidationParams);
							//print_r($imageName);exit;
							if(empty($imageName['error'])) {
								$postData['image'] = $imageName['filename'];
								//unset($postData['image_2']);
							}
							else {
								$error['image'] = $imageName['error'];
							}
						}
						else {
							//$postData['image'] = $postData['image_2'];
							//unset($postData['image_2']);
						}
						$insert = []; 

						foreach ($data['values_posted']['infrastructure_medias'] as $mediaKey => $media) {
							$media['company_infrastructure_id'] = $regInfra['id'];
							if(!empty($imageName['filename'][$mediaKey])) {
		 						$media['image'] = $imageName['filename'][$mediaKey];
		 					}
							$media['created'] = date('Y-m-d H:i:s');
							$media['modified'] = date('Y-m-d H:i:s');
							if(empty($media['image'])) {
		 						$media['image'] = '';

							}
							
							$insert[] = $media;
						}
						if(!empty($insert)) {
							$this->pktdblib->set_table("infrastructure_medias");
							$this->insert_multiple($insert);
							$msg = array('message' => 'Media Inserted Successfully', 'class'=>'alert alert-success');
							$this->session->set_flashdata('message', $msg);
						}

						//exit;
						$msg = array('message'=>'Infra Added Successfully. Infra Id : '.$regInfra['id'], 'class'=>'alert alert-success');
						$this->session->set_flashdata('message', $msg);
						redirect(custom_constants::new_infra_url);
					}
					else {
						$msg = array('message'=>'Failed to add Infra', 'class'=>'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
					}
				}
				else {
					$msg = array('message'=>'Some error occured while adding', 'class'=>'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
				}
			}
			else {
				$msg = array('message'=>'Some error occured while adding'.validation_errors(), 'class'=>'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
			}
		}
		$data['media_type'] = $this->get_media_type();
		$this->pktdblib->set_table("companies");
		$company = $this->pktdblib->get('is_active desc');
		$data['companies'] = $company->result_array();
		$data['option']['company'][0] = "Select Company";
		foreach ($data['companies'] as $companyKey => $company) {
			//print_r($company['id']);
			//print_r($company['company_name']);
			$data['option']['company'][$company['id']] = $company['company_name'];
		}

		if(!$this->input->get('company_id'))
			$data['company_id'] = '';
		else
			$data['company_id'] = $this->input->get('company_id');
		$data['title'] = 'New Infrastructure';
		$data['meta_title'] = 'New Infrastructure';
		$data['meta_description'] = 'New Infrastructure';
		$data['modules'][] = 'companies';
		$data['methods'][] = 'admin_add_new_infra';
		echo Modules::run('templates/admin_template', $data);

	}

	function admin_add_new_infra() {
		$this->load->view('companies/admin_add_new_infra');
	}

	function register_infra($data) {
		//print_r($data);exit;
		//echo "reached in register infra";
		$this->pktdblib->set_table('company_infrastructures');
		$insertData = $data;
		$id = $this->pktdblib->_insert($insertData);
		return json_encode(['message'=>'Infrastructure Added Successfully ', 'status'=>'success', 'id'=> $id]);
	}

	function get_media_type(){
		$query = ['Select Media Type' => 'Select Media Type',  'Image' => 'Image', 'video'=>'YouTube URL']; 
		//print_r($query);
		return $query;
	}

	function insert_multiple($data) {
		$query = $this->pktdblib->_insert_multiple($data);
		return $query;
	}

	function infrastructure($slug=NULL) {
		if(NULL==$slug) {
			//echo "hi";
			redirect('companies');
		}
			//echo "hello";
		$this->pktdblib->set_table('companies');
		$companyDetail = $this->pktdblib->get_where_custom('short_code', $slug);
		$companyDetails = $companyDetail->row_array();
		$id = $companyDetails['id'];
		//print_r($companyDetail);exit;
		$this->pktdblib->set_table('company_infrastructures');
		$infrastructure = $this->pktdblib->get_where_custom('company_id', $id);
		$data['infrastructures'] = $infrastructure->row_array();
		//print_r($data['infrastructures']);exit;
		//$data['infrastructure'] = $infrastructure;
		$this->pktdblib->set_table('infrastructure_medias');
		$infrastructureMedia = $this->pktdblib->get_where_custom('company_infrastructure_id', $data['infrastructures']['id']);
		$infrastructureMedias = $infrastructureMedia->result_array();
		/*print_r($infrastructureMedias);
		exit;*/
		$data['infrastructureMedia'] = [];
		foreach ($infrastructureMedias as $key => $media) {
			$data['infrastructureMedia'][$media['media_type']][] = $media;
		}
		/*print_r($data['infrastructureMedia']);
		exit;*/
		$data['content'] = 'companies/view_infrastructure';
		$data['meta_title'] = 'Companies Infrastructure';
		$data['title'] = 'Companies Infrastructure';
		$data['meta_description'] = 'Companies Infrastructure';
		$data['meta_keyword'] = 'Companies Infrastructure';
		
		echo Modules::run("templates/obaju_inner_template", $data);
		//$this->templates->admin_template('', $data);
		//print_r($data['employee']);
	}

	function getUserCompanies(){
		// echo '<pre>';
		// print_r($_SESSION);
		//echo "hii";
		$sql = 'Select * from companies where is_active=true';
		//echo key($_SESSION['roles']);
		///exit;
		if(in_array('companies', $_SESSION['roles'])){
			$companyId = array_search('companies', $_SESSION['roles']);
			$sql.=' AND id in (select company_id from companies_companies where parent_company_id="'.$_SESSION['companies']['id'].'")';
		}else{ //echo "hii";
			$this->pktdblib->set_table('user_roles');
			foreach ($_SESSION['roles'] as $key => $role) {
				//echo 'select * from user_roles where role_id='.$key.' and login_id='.$_SESSION['user_id'];
				$query = $this->pktdblib->custom_query('select * from user_roles where role_id='.$key.' and login_id='.$_SESSION['user_id']);
				$role = $query[0];
				if($_SESSION['application']['multiple_company'] && $role['account_type']!='companies'){
					$sql.=' AND id in (Select company_id from companies_'.$role['account_type'].' where employee_id='.$role['user_id'].')';
				}
				//print_r($role);
			}

			//$roles = $this->pktdblib->get_where($_SESSION['']);

		}
		//exit;
		$companies = $this->pktdblib->custom_query($sql);
		echo json_encode($companies);
	}

	function get_dropdown_list(){
		$companies = json_decode(Modules::run('companies/getUserCompanies'), true);//
		if(count($companies)>1){
        	$data[''] = 'Select Company';
		}else{
			$data = [];
		}
        foreach ($companies as $key => $company) {
            $data[$company['id']] = $company['company_name'];
        }

        echo json_encode($data);
	}
}