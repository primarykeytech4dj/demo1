<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquiries extends MY_Controller {
	private $companyId;
	function __construct() {
		parent::__construct();
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->model('enquiries/enquiries_model');
		$this->load->model('address/address_model');

		$setup = $this->setup();
		$this->companyId = 1;
        //$this->session->set_userdata();

		//$this->output->enable_profiler(TRUE);
	}

	function setup(){
		$setup = $this->enquiries_model->tbl_enquiries();
	}

	function contact_form_1($data = []) {
		$data['num1'] = rand(0,9);
		//echo $rand."<br>";
		$data['num2'] = rand(0,9);
		$data['sum'] = ($data['num1']+$data['num2']); 
		$this->session->set_userdata(['captcha'=>$data['sum']]);
		//print_r($this->session->set_userdata(['captcha'=>$data['sum']]));exit;

		//echo $rand2."<br> sum=".($rand+$rand2);exit;
		$this->load->view("enquiries/contact_form", $data);
	}

	function process_form() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';print_r($_POST);exit;
			$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
			$this->form_validation->set_rules('data[enquiries][first_name]', 'first name', 'required|max_length[255]');
			//$this->form_validation->set_rules('data[enquiries][surname]', 'last name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[enquiries][primary_email]', 'email', 'required|max_length[255]|valid_email');
			/*$this->form_validation->set_rules('data[enquiries][contact_1]', 'contact', 'required|max_length[255]');*/
			$this->form_validation->set_rules('data[enquiries][message]', 'message', 'required|max_length[255]');
			$this->form_validation->set_rules('data[captcha]', 'Captcha', 'required');

			if($this->form_validation->run('enquiries')!== FALSE) {
				$error = [];
				if($this->input->post('data[captcha]') != $this->session->userdata('captcha')){
					$error[] = "Invalid Total of 2 numbers";
				}
				//echo "reached in process_form";
				if(empty($error)) {
					$post_data = $this->input->post('data[enquiries]');
					$post_data['referred_by'] = "Website";
					$post_data['created'] = date('Y-m-d H:i:s');
					$post_data['modified'] = date('Y-m-d H:i:s');
					$post_data['company_id'] = $this->companyId;

					$reg_cont = json_decode($this->_register_new_enquiry($post_data), true);
					if($reg_cont['status'] === "success") {
						/*echo '<pre>';
						print_r($this->config);
						exit;*/
						if(NULL!==$this->input->post('data[enquiry_details]')){
							//print_r($this->input->post('data[enquiry_details]'));
							$this->pktdblib->set_table('products');
							$product = $this->pktdblib->get_where($this->input->post('data[enquiry_details][product_id]'));
							if(!empty($product)){
								$enquiryDetails['enquiry_id'] = $reg_cont['id'];
								$enquiryDetails['created'] = date('Y-m-d H:i:s');
								$enquiryDetails['modified'] = date('Y-m-d H:i:s');
								$enquiryDetails['product_id'] = $product['id'];
								$enquiryDetails['unit_price'] = $product['base_price'];
								$enquiryDetails['uom'] = 'nos';
								$this->pktdblib->set_table('enquiry_details');

								$this->pktdblib->_insert($enquiryDetails);
								$this->session->set_userdata('enquiries', $reg_cont['id']);
							}

						}
						/*echo '<pre>';
						print_r($this->input->post());
						exit;*/
						$this->load->library('email');
						$this->email->from('noreply@'.$_SERVER['SERVER_NAME'], $_SERVER['SERVER_NAME']);
						$mailData = ['id'=>$this->companyId];

						$mail = Modules::run('companies/get_company_details', $mailData);
						/*echo '<pre>';
						print_r($mail);
						exit;*/	
						$this->email->to($mail['primary_email']);
						//$this->email->to('primarykeytech@gmail.com');
						$this->email->set_newline("\r\n");
						
						$this->email->subject('Received Online Enquiry for '.$_SERVER['SERVER_NAME']);
						//print_r($reg_cont['enquiries']);
						$msg = Modules::run("email/enquiryEmail", $reg_cont);
						//echo $msg;exit;
						$this->email->message($msg);

						$this->email->send();
						/*echo $this->email->print_debugger();
						exit;*/
						//echo $reg_cont['enquiries']['enq_no'];exit;
						$msg = array('message' => 'Your Enquiry has been posted. Your Enquiry number is : '.$reg_cont['enquiries']['enq_code'], 'class' => 'alert alert-success', 'id'=>'enquiry');
						$this->session->set_flashdata('message', $msg);
						redirect(($this->input->post('data[redirectUrl]'))?$this->input->post('data[redirectUrl]').'?#enquiry':'contact-us?#contact-us');
					} else {
						$data['form_error'] = $reg_cont['msg'];
					}
				}else {
					$str = implode('<br>', $error);
					$msg = array('message' => 'Following Error Occured<br>'.$str, 'class' => 'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
				}

			}else {
				//echo validation_errors();
				$msg = array('message' => 'validation_error occured'.validation_errors(), 'class' => 'alert alert-danger', 'id'=>'enquiry');
						$this->session->set_flashdata('message', $msg);
			}	
		}
		redirect('contact-us?#contact-us');
	}

	function admin_index() {

		$data['meta_title'] = 'Enquiries listing';
		$data['meta_description'] = 'Enquiries Details';
		$data['modules'][] = 'enquiries';
		$data['methods'][] = 'admin_enquiry_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_enquiry_listing($data = []) {
		
		$this->enquiries_model->set_table('enquiries');
		$enquiries = $this->enquiries_model->get('created desc');
		$data['enquiries'] = $enquiries->result_array();
		//print_r($data);exit;
		$this->load->view("enquiries/admin_index", $data);
	}

	function _register_new_enquiry($data) {
		
		$this->enquiries_model->set_table("enquiries");
		$id = $this->enquiries_model->_insert($data);
		if(empty($data['enq_code'])){
			$data['enq_code'] = $enqCode = $this->create_enq_code($id);
			$updArr['id'] = $id;
			$updArr['enq_code'] = $enqCode;
			$updCode = $this->edit_enquiry($id, $updArr);
		}
		$data = $this->enquiry_details($id);
		return json_encode(['message' =>'Enquiry added Successfully', "status"=>"success", 'id'=> $id, 'enquiries'=>$data]);
	}

	function edit_enquiry($id=NULL, $post_data = []){
		//check_user_login(FALSE);
		if(NULL == $id)
			return false;

		$this->enquiries_model->set_table('enquiries');
		if($this->enquiries_model->_update($id,$post_data))
			return true;
		else
			return false;
	}

	// Add new user
	function admin_add() {
		//check_user_login(FALSE);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[enquiries][first_name]', 'first name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[enquiries][surname]', 'surname', 'required|max_length[255]|min_length[2]');
			$this->form_validation->set_rules('data[enquiries][dob]', 'dob', 'required');
			$this->form_validation->set_rules('data[enquiries][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[enquiries][contact_2]', 'contact_2', 'max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[enquiries][primary_email]', 'primary email', 'required|max_length[255]|valid_email');
			$this->form_validation->set_rules('data[enquiries][emp_code]', 'Employee Code', 'max_length[255]|is_unique[enquiries.emp_code]');
			$this->form_validation->set_rules('data[enquiries][secondary_email]', 'secondary email', 'max_length[255]|valid_email');
			$this->form_validation->set_rules('data[enquiries][message]', 'Enquiry', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				if(!empty($_FILES['profile_img']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'./assets/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$_POST['data']['enquiries']['profile_img'] = $profileImg['filename'];
					}
					else {
						$error['profile_img'] = $profileImg['error'];
					}
				}else{
					$_POST['data']['enquiries']['profile_img'] = '';
				}

				if(empty($error)){
					$post_data = $_POST['data']['enquiries'];
					$post_data['dob'] = $this->pktlib->dmYtoYmd($post_data['dob']);
					$post_data['referred_by'] = (NULL !== $this->input->post('data[enquiry_references][user_type]'))?$this->input->post('data[enquiry_references][user_type]'):'';
					$reg_enq = json_decode($this->_register_new_enquiry($post_data), true);
					
					if($reg_enq['status'] === "success")
					{
						$_POST['data']['enquiries'] = $reg_enq['enquiries'];
						$_POST['data']['enquiries']['id'] = $reg_enq['id'];
						if(NULL !== $this->input->post('data[enquiry_references]')){
							$this->admin_add_enquiry_reference($this->input->post('data[enquiry_references]'), $reg_enq['id']);
						}

						if(NULL !== $this->input->post('enquiry_details')){
							$enqDetails = $this->admin_add_multiple_enquiry_details($this->input->post('enquiry_details'), $reg_enq['id']);
							$_POST['data']['enquiry_details'] = $this->input->post('enquiry_details');
						}

						/*Equiry Amount update*/
						$upd = $this->calculateEnquiryAmount($reg_enq['id']);

						if(NULL !== ($this->input->post('quotation'))){

							$quotation = $this->enquiry_to_quotation($_POST['data']);
						}
						$msg = array('message'=>'Enquiry Added Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::edit_enquiry_url."/".$reg_enq['id']);
					}
					else
					{
						// Registration error
						$data['form_error'] = $reg_enq['msg'];
					}
				}else{
					$msg = array('message'=>'Failed to Upload Image. Error: '.$error['profile_img'], 'class'=>'alert alert-danger');
	                $this->session->set_flashdata('message',$msg);
				}
			}
		}
		
		
		$data['meta_title'] = "New Enquiry";
		$data['meta_description'] = "New Enquiry";
		//echo "reached here"; exit;
		$data['modules'][] = "enquiries";
		$data['methods'][] = "admin_enquiry_form";
		$data['js'][] = '<script type="text/javascript">
            $(".product").on("change", function(){
            	var id = this.id;
            	alert($(this).val());
            	var productId = $(this).val();
            	$.ajax({
		          type: "POST",
		          dataType: "html",
		          url : "products/get_product_details/"+productId,
		          data: "product_id="+productId,
		          success: function(response) {
		            console.log(response);
		            //$("#"+datatarget).select2("destroy").empty().select2({data : response});
		          }
		        
		        });
				
            })
		</script>';
		//echo '<pre>';print_r($data['option']['product']);
		//echo Modules::run("templates/login_template", $data);
		echo Modules::run("templates/admin_template", $data);
		
	}

	function admin_enquiry_form($data = []) {
		if(!isset($data['type'])){
			$data['type'] = '';//variable is passed through other modules
			$data['user_id'] = '';
		}
		
		$data['users']  = [];

		if(isset($data['module']) && $data['module']=='employees'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['emp_code']];
			$data['option']['typeLists'] = ['employees'=>'employees'];

		}elseif(isset($data['module']) && $data['module']=='customers'){
				$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = ['customers'=>'customers'];

			
		}elseif(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['companies'=>'companies'];
		}elseif(isset($data['module']) && $data['module']=='enquiries'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['enquiries'=>'Lead'];
		}else{
			$data['option']['typeLists'] = $this->pktlib->referalList();
			$_POST['params'] =  'companies';
			$data['users'] = Modules::run("companies/type_wise_user", $_POST);
		}
		$data['option']['referred_by'] = $this->referredby_option();
		$data['products'] = $this->product_model->get_active_list();
		/*echo '<pre>';
		print_r($data['products']);*/
		$data['option']['product'][0] = 'Select Product';
		//print_r($data['option']['product'][0]);
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}
		$this->enquiries_model->set_table('companies');
		$companies = $this->enquiries_model->get('created asc');
		foreach ($companies->result_array() as $companyKey => $company) {
			$data['companies'][$company['id']] = $company['company_name'];
		}
		//print_r($data['companies']);exit;

		$this->load->view("enquiries/admin_add", $data);
	}

	function referredby_option(){
		$array = [
			'self' => 'self',
			'customers' => 'Other Client',
			'employee' => 'Employee',
			'just dial' => 'Just Dial',
			'business associates' => 'Business Associates',
		];

		return $array;
	}

	function create_enq_code($enqId){
		$enqCode = 'L';
		if($enqId>0 && $enqId<=9)
			$enqCode .= '000000';
		elseif($enqId>=10 && $enqId<=99)
			$enqCode .= '00000';
		elseif($enqId>=100 && $enqId<=999)
			$enqCode .= '0000';
		elseif($enqId>=1000 && $enqId<=9999)
			$enqCode .= '000';
		elseif($enqId>=10000 && $enqId<=99999)
			$enqCode .= '00';
		elseif($enqId>=100000 && $enqId<=999999)
			$enqCode .= '0';

		$enqCode .= $enqId;
		return $enqCode;
	}


	function admin_edit($id = null) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$data['values_posted'] = $_POST['data'];
			$data['enquiries'] = $this->input->post('data[enquiries]');
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[enquiries][first_name]', 'first name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[enquiries][surname]', 'surname', 'required|max_length[255]|min_length[2]');
			
			$this->form_validation->set_rules('data[enquiries][dob]', 'dob', 'required');
			$this->form_validation->set_rules('data[enquiries][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[enquiries][contact_2]', 'contact_2', 'max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[enquiries][primary_email]', 'primary email', 'required|max_length[255]|valid_email');
			$this->form_validation->set_rules('data[enquiries][secondary_email]', 'secondary email', 'max_length[255]|valid_email');
			if($this->form_validation->run('enquiries') !== FALSE) {
				$profileImg = '';
				$post_data = $_POST['data']['enquiries'];
				
				if(!empty($_FILES['profile_img']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'./assets/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					if(empty($profileImg['error'])) {
						$_POST['data']['enquiries']['profile_img'] = $profileImg['filename'];
						unset($post_data['profile_img2']);
					}
					else {
						$error['profile_img'] = $profileImg['error'];
					}
				}else{
					$_POST['data']['enquiries']['profile_img'] = $post_data['profile_img2'];
					unset($post_data['profile_img2']);
					//$post_data['']
				}
				$data['values_posted'] = $_POST['data'];
				if(empty($error)) {
					$post_data['dob'] = $this->pktlib->dmYtoYmd($post_data['dob']);
					
					if($this->edit_enquiry($id,$post_data)) {
						if(NULL !== $this->input->post('enquiry_details')){
							$insertDetail = [];
							$updateDetail = [];
							/*echo '<pre>';
							print_r($this->input->post('enquiry_details'));*/
							//exit;
							foreach ($this->input->post('enquiry_details') as $detailKey => $detail) {
								$detail['modified'] = date('Y-m-d H:i:s');
								if(!empty($detail['id'])){
									$updateDetail[] = $detail;
								}else{
									$detail['created'] = date('Y-m-d H:i:s');
									$insertDetail[] = $detail;
								}
								
							}
							$_POST['data']['enquiry_details'] = $this->input->post('enquiry_details');
							/*print_r($insertDetail);
							print_r($updateDetail);
							exit;*/
							if(!empty($insertDetail)){
								$enqDetails = $this->admin_add_multiple_enquiry_details($insertDetail, $id);
							}

							if(!empty($updateDetail)){
								$enqDetails = $this->admin_edit_multiple_enquiry_details($updateDetail);
							}
						}

						/*Equiry Amount update*/
						$upd = $this->calculateEnquiryAmount($id);

						if(NULL !== ($this->input->post('quotation'))){

							$quotation = $this->enquiry_to_quotation($_POST['data']);
						}
						
                        $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                             $this->session->set_flashdata('message',$msg);
                        }
                        else {
						//echo "string";exit;
                            $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                            $this->session->set_flashdata('message',$msg);
                        }
						//echo "raeched here";exit;
                        redirect(custom_constants::edit_enquiry_url."/".$id.'?tab=address');

                    }
                    else{
                        $msg = array('message' => $error,'class' => 'alert alert-danger fade in');
                        $this->session->set_flashdata('error', $msg);
                    }
				}else{
					//echo validation_errors(); exit;
					$msg = array('message' => 'Some Validation Error Occurred'.validation_errors(),'class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('error', $msg);
				}

		}
		else {
			$this->enquiries_model->set_table("enquiries");
			$data['enquiries'] = $this->enquiry_details($id);
		//	print_r($data['enquiries']);exit;
			$data['values_posted']['enquiries'] = $data['enquiries'];
		}
		$data['id'] = $id;
		if(!($this->input->get('tab')))
			$data['tab'] = 'personal_info';
		else
			$data['tab'] = $this->input->get('tab');
		$data['meta_title'] = 'edit enquiries';
		$data['meta_description'] = 'edit enquiries';
		
		$data['content'] = 'enquiries/admin_edit';

		$AddressListData = ['condition'=>['address.user_id'=>$id, 'address.type'=>'enquiries'], 'module'=>'enquiries'];
		//$this->address_model->set_table('address');
		$data['addressList'] = Modules::run("address/address_listing", $AddressListData);
		/*echo '<pre>';
		print_r($data['enquiries']);exit;*/
		$this->address_model->set_table('address');
		$addressData = ['url'=>custom_constants::edit_enquiry_url.'/'.$id.'?tab=address', 'module'=>'enquiries', 'user_id'=>$id, 'type'=>'enquiries', 'user_detail'=>$data['enquiries']];
		if($this->input->get('address_id')){ 
			//$addressData['address'] = 
			$addressEditData = $this->address_model->get_where($this->input->get('address_id'));
			$addressData['address'] = $addressEditData->row_array();
			$data['address'] = Modules::run("address/view_address_edit_form", $addressData);
		}else{
			$data['address'] = Modules::run("address/view_address_form", $addressData);
		}

		/*Document Uploads*/
		$documentListData = ['condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'enquiries'], 'module'=>'enquiries'];
		//$this->address_model->set_table('bank_accounts');
		$data['documentList'] = Modules::run("upload_documents/document_listing", $documentListData);

		$documentData = ['url'=>custom_constants::edit_enquiry_url.'/'.$id.'?tab=document', 'module'=>'enquiries', 'user_id'=>$id, 'type'=>'enquiries', 'user_detail'=>$data['enquiries']];
		
		$data['document'] = Modules::run("upload_documents/admin_add", $documentData);

		$followupListData = ['condition'=>['follow_ups.type_id'=>$id, 'follow_ups.type'=>'enquiries'], 'module'=>'enquiries'];
		//print_r($followupListData);
		//$this->address_model->set_table('bank_accounts');
		$data['followupList'] = Modules::run('follow_ups/admin_followup_listing', $followupListData);
		//print_r($data['followupList']);exit;

		$this->follow_ups_model->set_table('follow_ups');
		$followupData = ['url'=>custom_constants::edit_enquiry_url.'/'.$id.'?tab=followup', 'module'=>'enquiries', 'user_id'=>$id, 'type'=>'enquiries', 'user_detail'=>$data['enquiries']];
		if($this->input->get('followup_id')){ 
			//$addressData['address'] = 
			$followupEditData = $this->follow_ups_model->get_where($this->input->get('followup_id'));
			$followupData['follow_ups'] = $followupEditData->row_array();
			$data['follow_ups'] = Modules::run("follow_ups/admin_edit_form/".$this->input->get('followup_id'), $followupData);
		}else{ //echo "hh";exit;
			$data['follow_ups'] = Modules::run("follow_ups/admin_add_form", $followupData);
		}
		
		$data['document'] = Modules::run("upload_documents/admin_add", $documentData);
		/*Meeting module starts*/
		$meetingListData = ['condition'=>['meetings.type_id'=>$id, 'meetings.type'=>'enquiries'], 'module'=>'enquiries'];
		//$this->meetings_model->set_table('meeting');
		$data['meetingList'] = Modules::run("meetings/admin_meeting_listing", $meetingListData);
		/*echo '<pre>';
		print_r($data['enquiries']);exit;*/
		$this->meetings_model->set_table('meetings');
		$meetingData = ['url'=>custom_constants::edit_enquiry_url.'/'.$id.'?tab=meeting', 'module'=>'enquiries', 'user_id'=>$id, 'type'=>'enquiries', 'user_detail'=>$data['enquiries']];
		if($this->input->get('meeting_id')){ 
			//$meetingData['meeting'] = 
			$meetingEditData = $this->meetings_model->get_where($this->input->get('meeting_id'));
			$meetingData['meeting'] = $meetingEditData->row_array();
			$data['meetings'] = Modules::run("meetings/admin_edit_form", $meetingData);
		}else{
			$data['meetings'] = Modules::run("meetings/admin_add_form", $meetingData);
		}

		$this->enquiries_model->set_table('companies');
		$companies = $this->enquiries_model->get('created asc');
		foreach ($companies->result_array() as $companyKey => $company) {
			$data['companies'][$company['id']] = $company['company_name'];
		}
		$this->enquiries_model->set_table('enquiry_details');
		$enquiryDetails = $this->enquiries_model->get_where(['enquiry_id' => $id, 'is_active'=>true]);
		$data['enquiryDetails'] = $enquiryDetails->result_array();

		foreach ($data['enquiryDetails'] as $detailKey => $detail) {
			$data['enquiryDetails'][$detailKey]['total'] = ($detail['unit_price']*$detail['qty'])+ (($detail['unit_price']*$detail['qty'])*$detail['tax']/100.00);
		}

		$data['products'] = $this->product_model->get_active_list();
		/*echo '<pre>';
		print_r($data['products']);*/
		$data['option']['product'][0] = 'Select Product';
		//print_r($data['option']['product'][0]);
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}

		$this->enquiries_model->set_table('quotations');
		$is_quoted = $this->enquiries_model->get_where(['enquiry_id'=>$id,'is_approved !='=>1]);
		//print_r($is_quoted->result_array());
		$data['is_quoted'] = $is_quoted->num_rows();

		//print_r($data['is_quoted']);exit;
		/*print_r($data['products']);
		exit;	*/	
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_view($id=NULL){
		if(NULL==$id){
			redirect(custom_constants::admin_enquiries_listing_url);
		}
		$this->enquiries_model->set_table('enquiries');
		$enquiry = $this->enquiries_model->get_where($id);
		$data['user'] = $enquiry->row_array();
		$data['content'] = 'enquiries/admin_view';
		$data['meta_title'] = 'Enquiries';
		$data['meta_description'] = 'Enquiries';
		$addressListData = ['condition'=>['address.user_id'=>$id, 'address.type'=>'enquiries'], 'module'=>'enquiries'];
		$data['addressList'] = Modules::run("address/address_listing", $addressListData);

		/*Documents*/
		$documentListData = ['condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'enquiries'], 'module'=>'enquiries'];
		$data['documentList'] = Modules::run("upload_documents/document_listing", $documentListData);

		echo Modules::run("templates/admin_template", $data);
	}

	function enquiry_details($id) {
		$this->enquiries_model->set_table('enquiries');
		$employeeDetails = $this->enquiries_model->get_where($id);
		return $employeeDetails->row_array();
		
	}

	function type_wise_user(){
		//print_r($_POST);exit;
		//$_POST['params'] = "companies";
		
		if(!$this->input->post('params'))
			return;

		$userType = $this->input->post('params');
		
		if($userType=='just_dial'){
			$userList[0]['id'] = 0;
			$userList[0]['text'] = 'Just Dial';
			echo json_encode($userList);
			exit;
		}elseif ($userType=='website') {
			$userList[0]['id'] = 0;
			$userList[0]['text'] = 'Online';
			echo json_encode($userList);
			exit;
		}
		//echo json_encode($condition);exit;
		$this->enquiries_model->set_table('enquiries');
		$typeWiseUsers = $this->enquiries_model->get_custom_address_type_users($userType);
		//print_r($typeWiseUsers);exit;
		$userList = [0=>['id'=>0, 'text'=>'Select']];
		foreach ($typeWiseUsers as $key => $typeWiseUser) {
			$userList[$key+1]['id'] = $typeWiseUser['id'];
			$userList[$key+1]['text'] = $typeWiseUser['fullname']." | ".$typeWiseUser['emp_code'];
		}
		
		echo json_encode($userList);
		exit;
	}

	function admin_add_enquiry_reference($data, $enqId) {
		$referenceArray['enquiry_id'] = $enqId;
		$referenceArray['user_type'] = $data['user_type'];
		$referenceArray['user_id'] = $data['user_id'];
		$referenceArray['created'] = date('Y-m-d H:i:s');
		$referenceArray['modified'] = date('Y-m-d H:i:s');
		$this->enquiries_model->set_table('enquiry_references');
		$insert = $this->enquiries_model->_insert($data);
		return $insert;
	}

	function enquiries_details() {
		$data['meta_title'] = "Enquiries Details";
		$data['meta_description'] = "Enquiries Details";
		
		$data['content'] = "enquiries/enquiries_details";
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_enquiry_details() {
		$this->load->view("enquiries/enquiries_details");
	}

	function admin_add_multiple_enquiry_details($data, $enqId) {
		$enquiryDetails = [];
		$updEnq['id'] = $enqId;
		foreach ($data as $key => $enquiryDetail) {
			$enquiryDetails[$key] = $enquiryDetail;
			$enquiryDetails[$key]['enquiry_id'] = $enqId;
			$enquiryDetails[$key]['created'] = date('Y-m-d H:i:s');
			$enquiryDetails[$key]['modified'] = date('Y-m-d H:i:s');
			//$enquiryDetails[$key]['quot_no'] = 1;
		}

		$this->enquiries_model->set_table('enquiry_details');
		$detailCount = $this->enquiries_model->_insert_multiple($enquiryDetails);
		if($detailCount)
			return $detailCount;
		else
			return false;
	}

	function enquiry_to_quotation($data) {
		//echo '<pre>';
		//print_r($data);exit;
		$quotationArray['enquiry_id'] = $data['enquiries']['id'];
		$quotationArray['quotation_date'] = date('Y-m-d H:i:s');
		$quotationArray['amount_before_tax'] = $data['enquiries']['amount_before_tax'];
		$quotationArray['amount_after_tax'] = $data['enquiries']['amount_after_tax'];
		$quotationArray['created'] = date('Y-m-d H:i:s');
		$quotationArray['modified'] = date('Y-m-d H:i:s');
		$quotationArray['message'] = $data['enquiries']['message'];
		//$quotationDetails = [];
		$quotation = json_decode(Modules::run("quotations/_register_new_quotation", $quotationArray), true);
		if($quotation['status']=="success"){
			$quotationDetails = Modules::run("quotations/admin_add_multiple_quotation_details", $data['enquiry_details'], $quotation['id'] );
			return $quotationDetails;
			//print_r($quotationDetails);
		}else{
			return false;
		}
		
		//exit;
	}

	function admin_followup() {

		$data['meta_title'] = 'Enquiries listing';
		$data['meta_description'] = 'Enquiries Details';
		$data['modules'][] = 'enquiries';
		$data['methods'][] = 'enquiry_followup';
		echo Modules::run("templates/admin_template", $data);
	}

	function enquiry_followup($data = []) {
		
		$this->enquiries_model->set_table('enquiries');
		$condition = [1=>1];
		if(isset($data['condition'])){
			$condition = $data['condition'];
		}
		$enquiries = $this->enquiries_model->enquiry_followup($condition);
		$data['enquiries'] = $enquiries->result_array();
		//print_r($data);exit;
		$this->load->view("enquiries/admin_followup", $data);
	}

	function delete() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = $this->input->post('id');
			$this->enquiries_model->set_table($this->input->post('table'));
			$arrayData['is_active'] = $this->input->post('is_active');
			$arrayData['modified'] = date('Y-m-d H:i:s');
			$response = $this->enquiries_model->_update($id, $arrayData);
			if($response){
				echo json_encode(['status'=>'success', 'id'=>$id]);
				exit;
			}
		}
			
		echo json_encode(['status'=>'error', 'msg'=>'Invalid Input']);
		exit;
		
	}

	function deleteEnquiryDetails() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = $this->input->post('id');
			$this->enquiries_model->set_table($this->input->post('table'));
			$enquiryDetails = $this->enquiries_model->get_where($id);
			$enquirDet = $enquiryDetails->row_array();
			$arrayData['is_active'] = $this->input->post('is_active');
			$arrayData['modified'] = date('Y-m-d H:i:s');
			$response = $this->enquiries_model->_update($id, $arrayData);
			$this->calculateEnquiryAmount($enquirDet['enquiry_id']);
			if($response){
				echo json_encode(['status'=>'success', 'id'=>$id]);
				exit;
			}
		}
			
		echo json_encode(['status'=>'error', 'msg'=>'Invalid Input']);
		exit;
		
	}

	function calculateEnquiryAmount($enquiryId) {
		$this->enquiries_model->set_table('enquiry_details');
		$enquiryDetails = $this->enquiries_model->get_where(['enquiry_id'=>$enquiryId, 'is_active'=>true]);
		$amtBeforeTax = 0;
		$amtAfterTax = 0;
		foreach ($enquiryDetails->result_array() as $key => $detail) {
			$amtBeforeTax = $amtBeforeTax + ($detail['qty']*$detail['unit_price']);
			$amtAfterTax = $amtAfterTax+(($detail['qty']*$detail['unit_price'])+($detail['qty']*$detail['unit_price'])*$detail['tax']/100.00);
		}

		$enquiryArray = ['id'=>$enquiryId, 'amount_before_tax'=>$amtBeforeTax, 'amount_after_tax'=>$amtAfterTax];
		$res = $this->edit_enquiry($enquiryId, $enquiryArray);
		return $res;

	}

	function admin_edit_multiple_enquiry_details($data) {
		$this->enquiries_model->set_table('enquiry_details');
		$upd = $this->enquiries_model->_update_multiple('id', $data);
		return $udp;
	}

	function enquiry_2() {

		//echo "reached here"; exit;
		
		/*echo Modules::run("templates/obaju_home_template", $data);*/
		$data['title'] = "New Enquiry";
		$data['meta_keyword'] = "New Enquiry";
		$data['meta_title'] = "New Enquiry";
		$data['meta_description'] = "New Enquiry";
		$data['modules'][] = "enquiries";
		$data['methods'][] = "admin_enquiry_form_2";
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'Contact']
		];
		echo Modules::run('templates/obaju_inner_template', $data);
	}

	function admin_enquiry_form_2() {
		$this->load->view('enquiries/enquiry_form_2');
	}

	function add_to_enquiry_ajax(){
		/*session_destroy();exit;*/
		//$data['product_id'] = $this->input->post('productId');
		$data['params'] = $this->input->post();
		$data['product'] = Modules::run('products/get_product_details', $this->input->post('productId'));
		//print_r($data['product']);
		if(NULL!==$this->session->userdata('enquiries')){
			$this->pktdblib->set_table('enquiry_details');
			$enqDetails = $this->pktdblib->custom_query('select * from enquiry_details where enquiry_id='.$this->session->userdata('enquiries').' and product_id='.$this->input->post('productId'));
			//print_r($enqDetails);exit;
			//print_r($this->session->userdata('enquiries'));exit;
			//print_r($this->input->post('productId'));
			if(empty($enqDetails)){
				$this->pktdblib->set_table('products');
				$product = $this->pktdblib->get_where($this->input->post('productId'));
				if(!empty($product)){
					$enquiryDetails['enquiry_id'] = $this->session->userdata('enquiries');
					$enquiryDetails['created'] = date('Y-m-d H:i:s');
					$enquiryDetails['modified'] = date('Y-m-d H:i:s');
					$enquiryDetails['product_id'] = $product['id'];
					$enquiryDetails['unit_price'] = $product['base_price'];
					$enquiryDetails['uom'] = 'nos';
					$this->pktdblib->set_table('enquiry_details');

					$cartDetails = $this->pktdblib->_insert($enquiryDetails);
					if($cartDetails){
						echo "<div class='fa alert alert-success text-center'><h2>Enquiry Placed Successfully</h2></div>";
					}
					//$this->session->set_userdata('enquiries', $reg_cont['id']);
				}else{
					echo "Invalid Product";
				}
			}else{
				echo "<div class='fa alert alert-success text-center'><h2>Enquiry has already been placed</h2></div>";
			}
			
			//exit;

		}else{
			$data['enquiryNum1'] = rand(0,9);
			//echo $rand."<br>";
			$data['enquiryNum2'] = rand(0,9);
			$data['enquirySum'] = ($data['enquiryNum1']+$data['enquiryNum2']); 
			$this->session->set_userdata(['captcha'=>$data['enquirySum']]);
			
			//echo "session does not exists";
			//$this->session->set_userdata('product_id', $cart['product_id']);

			$this->load->view('enquiries/enquiry_form', $data);
		}
	}
}
