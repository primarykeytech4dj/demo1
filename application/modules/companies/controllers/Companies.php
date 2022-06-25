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
	}

	function index(){
		//echo "hiii";exit;
		$id = custom_constants::company_id;
		//$id = 1;
		$data = ['id'=>$id];
		$type = 'login';
		//$this->address_model->set_table("user_roles");

		$data['companyDetail'] = Modules::run('companies/get_company_details', $data);
		$data['title'] = $data['companyDetail']['company_name'];
		$data['meta_title'] = $data['companyDetail']['meta_title'];
		$data['meta_description'] = $data['companyDetail']['meta_description'];
		$data['meta_keyword'] = $data['companyDetail']['meta_keyword'];
		$this->address_model->set_table("address");
		$data['address'] = $this->address_model->userBasedAddress($id, $type);
		//print_r($data['address']);exit;
		$data['content'] = 'companies/company_details';

		//$data['contact'] = Modules::run("enquiries/contact_form_1");
		$data['contact'] = Modules::run("enquiries/contact_form_1");

		//echo "hi"; exit;
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'Contact']
		];
		$data['js'][] = '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlrxiw1CIsCn9OqtvJs83XMLC4jNTSKFc"></script>
		<script>
            // When the window has finished loading create our google map below
            google.maps.event.addDomListener(window, "load", init);

            function init() {
                // Basic options for a simple Google Map
                // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
                var mapOptions = {
                    // How zoomed in you want the map to start at (always required)
                    zoom: 11,

                    scrollwheel: false,

                    // The latitude and longitude to center the map (always required)
                    center: new google.maps.LatLng(40.6700, -73.9400), // New York

                    // How you would like to style the map.
                    // This is where you would paste any style found on Snazzy Maps.
					styles: [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]
                };

                // Get the HTML DOM element that will contain your map
                // We are using a div with id="map" seen below in the <body>
                var mapElement = document.getElementById("map");

                // Create the Google Map using our element and options defined above
                var map = new google.maps.Map(mapElement, mapOptions);

                // Let\'s also add a marker while we\'re at it
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(40.6700, -73.9400),
                    map: map,
                    title: "Snazzy!"
                });
            }
		</script>';
		echo Modules::run('templates/default_template', $data);
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
		$this->companies_model->set_table('companies');
		$query = $this->companies_model->get('modified desc');
		return $query->result_array();
	}

	function admin_view($id=NULL) {
		if(NULL==$id) {
			//echo "hi";
			redirect('companies/admin_index');
		}
			//echo "hello";

		$this->companies_model->set_table('companies');
		$company = $this->companies_model->get_where($id);
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
		$this->companies_model->set_table("companies");
		$companyDetail = $this->companies_model->get_where($data['id']);
        //print_r($companyDetail);exit;
		return $companyDetail;
	}

	function about_company($slug = ''){
		if('' == $slug){
			$this->companies_model->set_table('companies');
			$company = $this->companies_model->get_where_custom('is_active', true);
			$company = $company->row_array();
			$slug = $company['slug'];
		}

		$data['companyDetail'] = Modules::run('companies/get_slugwise_company_details', $slug);
		$data['title'] = $data['companyDetail']['company_name'];
		$data['meta_title'] = $data['companyDetail']['meta_title'];
		$data['meta_description'] = $data['companyDetail']['meta_description'];
		$data['meta_keyword'] = $data['companyDetail']['meta_keyword'];
		$data['modules'][] = "companies";
		$data['methods'][] = "aboutus";

		//$data['methods'][] = "get_company_details";
		//$this->address_model->set_table("address");
		//$data['aboutus'] = $data['companyDetail']['about_company'];
	//print_r($data['address']);
		//$data['content'] = 'companies/aboutus';

		//$data['contact'] = Modules::run("enquiries/contact_form_1");
		//print_r($data);
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'About '.$data['companyDetail']['company_name']]
		];
		echo Modules::run('templates/default_template', $data);
	}

	function aboutus($slug=''){
		if('' == $slug){
			$this->companies_model->set_table('companies');
			$company = $this->companies_model->get_where_custom('id', custom_constants::company_id);
			$company = $company->row_array();
			$slug = $company['slug'];
		}

		$data['companyDetail'] = Modules::run('companies/get_slugwise_company_details', $slug);
		$data['aboutus'] = $data['companyDetail']['about_company'];
		$this->load->view('companies/aboutus', $data);


	}

	function get_slugwise_company_details($slug = ''){
		if($slug==''){
			show_404();
			exit;
		}
		//print_r($data);exit;
		$this->companies_model->set_table("companies");
		$companyDetail = $this->companies_model->get_where_custom('slug', $slug);

		return $companyDetail->row_array();
	}
	
	function admin_add(){
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $this->input->post('data');
			$this->form_validation->set_rules('data[companies][company_name]', ' Company Name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[companies][first_name]', ' first Name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[companies][middle_name]', ' middle Name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[companies][surname]', ' surName', 'required|max_length[255]');
			$this->form_validation->set_rules('data[companies][primary_email]', ' Primary Email', 'required|max_length[255]');
			$this->form_validation->set_rules('data[companies][secondary_email]', 'Secondary Email', 'max_length[255]');
			$this->form_validation->set_rules('data[companies][contact_1]', ' contact_1', 'required|numeric');
			$this->form_validation->set_rules('data[companies][contact_2]', ' contact_2', 'numeric');

			if($this->form_validation->run('companies')!== false) {
				$error = [];
				$logoImg = '';
				
				//print_r($_FILES['logo']['name']);//exit;
				if(!empty($_FILES['logo']['name'])){
					$logoFileValidationParams = ['file'=>$_FILES['logo'], 'path'=>'./assets/uploads/company_logos', 'ext'=>'jpg|png|gif|jpeg', 'fieldname'=>'logo', 'arrindex'=>'logo'];
					//print_r($logoFileValidationParams);//exit;
					$logoImg = $this->pktlib->upload_single_file($logoFileValidationParams);
					//print_r($logoImg);exit;
					if(empty($logoImg['error'])) {
						$_POST['data']['companies']['logo'] = $logoImg['filename'];
					}
					else {
						$error['companies'] = $logoImg['error'];
					}
				}
				if(empty($error)) {
					//print_r($this->input->post('data[companies]'));
					$postData = $this->input->post('data[companies]');
					//print_r($postData);exit;
					$regCompany = json_decode($this->register_company($postData),true);
					/*$register_product_category = json_decode($this->register_product_category($post_data), true);*/
					//print_r($regCompany);exit;
					if($regCompany['status'] === "success") {
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
		$this->companies_model->set_table('companies');
		$insertData = $data;
		$id = $this->companies_model->_insert($insertData);
		return json_encode(['message'=>'Company Added Successfully ', 'status'=>'success', 'id'=> $id]);
	}

	function admin_edit($id) {
		check_user_login(FALSE);
		//echo $id;
		if($_SERVER['REQUEST_METHOD']=='POST') {
			$data['values_posted'] = $_POST; 

			$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
			$this->form_validation->set_rules("data[companies][company_name]", 'Company Name', 'required');
			$this->form_validation->set_rules("data[companies][first_name]", 'First Name', 'required|max_length[255]');
			$this->form_validation->set_rules("data[companies][middle_name]", 'Middle Name', 'max_length[255]');
			$this->form_validation->set_rules("data[companies][surname]", 'Surname', 'required|max_length[255]');
			$this->form_validation->set_rules("data[companies][primary_email]", 'Primary Email', 'required|max_length[255]|valid_email');
			$this->form_validation->set_rules("data[companies][secondary_email]", 'Secondary Email', 'max_length[255]|valid_email');
			$this->form_validation->set_rules("data[companies][contact_1]", 'Contact 1', 'required|max_length[15]|numeric');
			$this->form_validation->set_rules("data[companies][contact_2]", 'Contact 2', 'max_length[15]|numeric');

			if($this->form_validation->run('companies')!== false){
				$logoImg = '';
				$postData = $_POST['data']['companies'];
				//echo content_url();
				if(!empty($_FILES['logo']['name'])) {
					$logoFileValidationParams = ['file'=>$_FILES['logo'], 'path'=>'../content/uploads/company_logos', 'fieldname'=>'logo', 'ext'=>'jpeg|png|jpg|gif', 'arrindex'=>'logo'];
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

					if($this->companies_model->update($id, $postData)) {
						$msg = array('message'=>'Data Update Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
					}
					else {
						$msg = array('message'=>'some problem occured', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
					}
					redirect('companies/admin_edit'."/".$id.'?tab=address');
				} 
				else {
					$msg = array('message'=>'some error occured while uploading'.$error, 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}
			} 
			else {
				$data['companies'] = $data['values_posted']['data']['companies'];
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

		$AddressListData = ['condition'=>['address.user_id'=>$id, 'address.type'=>'companies'], 'module'=>'companies'];
		$data['addressList'] = Modules::run('address/address_listing', $AddressListData);
		$this->address_model->set_table('address');
		$addressData = ['url'=>custom_constants::edit_company_url.'/'.$id.'?tab=bank_account', 'module'=>'companies', 'user_id'=>$id, 'type'=>'companies', 'user_detail'=>$data['companies']];
		if($this->input->get('address_id')) {
			$addressEditData = $this->address_model->get_where($this->input->get('address_id'));
			$addressData['address'] = $addressEditData->row_array();
			$data['address'] = Modules::run('address/view_address_edit_form',$addressData);
		}else {
			$data['address'] = Modules::run("address/view_address_form", $addressData);
		}

		/* Bank Account Related Code Starts Here  */
		$bankAccountListData = ['condition'=>['bank_accounts.user_id'=>$id, 'bank_accounts.user_type'=>'companies'], 'module'=>'companies'];
		$this->address_model->set_table('bank_accounts');
		$data['bankAccountList'] = Modules::run("bank_accounts/account_listing", $bankAccountListData);
		//print_r($data['bankAccountList']);exit;
		//$this->address_model->set_table('bank_accounts');
		$bankAccountData = ['url'=>custom_constants::edit_company_url.'/'.$id.'?tab=document', 'module'=>'companies', 'user_id'=>$id, 'type'=>'companies', 'user_detail'=>$data['companies']];
		if($this->input->get('bank_account_id')){ 
			$bankAccountData['bank_accounts'] = Modules::run("bank_accounts/account_details", $this->input->get('bank_account_id'));
			$data['bank_account'] = Modules::run("bank_accounts/admin_edit", $bankAccountData);
			//print_r($data['bank_account']);exit;
		}else{
			$data['bank_account'] = Modules::run("bank_accounts/admin_add", $bankAccountData);
		}
		/*Bank account ends*/

		/*Document Uploads*/
		$documentListData = ['condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'companies'], 'module'=>'companies'];
		//$this->address_model->set_table('bank_accounts');
		$data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

		$documentData = ['url'=>custom_constants::edit_company_url.'/'.$id.'?tab=document', 'module'=>'companies', 'user_id'=>$id, 'type'=>'companies', 'user_detail'=>$data['companies']];
		
		$data['document'] = Modules::run("upload_documents/admin_add", $documentData);
		$data['js'][] = '<script type="text/javascript">
            CKEDITOR.replace("about_company");
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
		$this->companies_model->set_table('companies');
		$companyDetails = $this->companies_model->get_where($id);
		//echo '<pre>';
		//print_r($companyDetails);
		return $companyDetails;
	}

	function get_Customer_list_dropdown(){
		$this->companies_model->set_table('companies');
        $companies = $this->companies_model->get('companies.is_active desc');
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
		$_POST['id'] = custom_constants::company_id;
		$_POST['table'] = 'company_infrastructures';
		$_POST['is_active'] = 'is_active';

		$_SERVER['REQUEST_METHOD'] = 'POST';
		//print_r($_SERVER['REQUEST_METHOD']);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = $this->input->post('id');
			//echo "hi";
			//echo $id;exit;
			$this->companies_model->set_table($this->input->post('table'));
			$infraDetails = $this->companies_model->get_where($id);
			//print_r($faqDetails);
			//$faqDet = $faqDetails->row_array();
			$arrayData['is_active'] = $this->input->post('is_active');
			$arrayData['modified'] = date('Y-m-d H:i:s');
			$response = $this->companies_model->update($id, $arrayData);
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
							$this->companies_model->set_table("infrastructure_medias");
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
		$this->companies_model->set_table("companies");
		$company = $this->companies_model->get('is_active desc');
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
		$this->companies_model->set_table('company_infrastructures');
		$insertData = $data;
		$id = $this->companies_model->_insert($insertData);
		return json_encode(['message'=>'Infrastructure Added Successfully ', 'status'=>'success', 'id'=> $id]);
	}

	function get_media_type(){
		$query = ['Select Media Type' => 'Select Media Type',  'Image' => 'Image', 'video'=>'YouTube URL']; 
		//print_r($query);
		return $query;
	}

	function insert_multiple($data) {
		$query = $this->companies_model->_insert_multiple($data);
		return $query;
	}

	function infrastructure($slug=NULL) {
		if(NULL==$slug) {
			//echo "hi";
			redirect('companies');
		}
			//echo "hello";
		$this->companies_model->set_table('companies');
		$companyDetail = $this->companies_model->get_where_custom('short_code', $slug);
		$companyDetails = $companyDetail->row_array();
		$id = $companyDetails['id'];
		//print_r($companyDetail);exit;
		$this->companies_model->set_table('company_infrastructures');
		$infrastructure = $this->companies_model->get_where_custom('company_id', $id);
		$data['infrastructures'] = $infrastructure->row_array();
		//print_r($data['infrastructures']);exit;
		//$data['infrastructure'] = $infrastructure;
		$this->companies_model->set_table('infrastructure_medias');
		$infrastructureMedia = $this->companies_model->get_where_custom('company_infrastructure_id', $data['infrastructures']['id']);
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
}