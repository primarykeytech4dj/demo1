<?php 

class Testimonials extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('testimonials/testimonial_model');
		$this->load->model('companies/companies_model');

	}

	function admin_add() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';
			//print_r($_FILES['company_testimonials']);exit;
			//print_r($_POST['company_testimonials']);exit;
			//print_r($_FILES['company_testimonials']['name']);exit;

			$data['values_posted'] = $_POST['company_testimonials'];
			$imageName = '';

			$this->form_validation->set_rules('data[testimonials][name]', 'Name');
			$this->form_validation->set_rules('data[testimonials][company]', 'Company');
			$this->form_validation->set_rules('data[testimonials][designation]', 'Designation');
			$this->form_validation->set_rules('data[testimonials][comment]', 'Comment');

			if($this->form_validation->set_rules('testimonials')!== FALSE) {
				$postData = $data['values_posted'];
				//echo '<pre>';print_r($postData);exit;
				$insert = [];
				$update = [];
				if(!empty($_FILES['company_testimonials']['name'])) {
					$imageFileValidationParams = ['file'=>$_FILES['company_testimonials'], 'path'=>'./assets/uploads/testimonials', 'ext'=>'jpeg|jpg|png|gif', 'fieldname'=>'image', 'arrindex'=>'company_testimonials'];
					//print_r($imageFileValidationParams);exit;
					$imageName = $this->pktlib->upload_multiple_file($imageFileValidationParams);
					//print_r($imageName);exit;
					if(empty($imageName['error'])) {
						$postData['image'] = $imageName['filename'];
						unset($postData['image_2']);
					}
					else {
						$error['image'] = $imageName['error'];
					}
				}
				else {
					$postData['image'] = $postData['image_2'];
					unset($postData['image_2']);
				}
				//print_r($postData['image']);exit;
					//echo "hi";

				foreach ($data['values_posted'] as $testimonialKey => $testimonial) {
					//echo "hi";
					//$testimonial['image'] = $postData['image'];
					//$testimonial['modified'] = date('Y-m-d H:i:s'); 
					//print_r($testimonial);
	 				/*if(!empty($imageName['filename'][$testimonialKey])) {
	 					echo " filename not empty";
 					$data['values_posted'][$testimonialKey]['image'] = $imageName['filename'][$testimonialKey];
	 				}else {
	 					echo " filename  empty";
	 					$data['values_posted'][$testimonialKey]['image'] = $data['values_posted'][$testimonialKey]['image_2'];
	 				}
	 				print_r($data['values_posted'][$testimonialKey]['image']);*/

	 				if(!empty($imageName['filename'][$testimonialKey])) {
	 					//echo " filename not empty";
 					$testimonial['image'] = $imageName['filename'][$testimonialKey];
	 				}else {
	 					//echo " filename  empty";
	 					$testimonial['image'] = $testimonial['image_2'];
	 				}
	 				//print_r($testimonial['image']);
					//exit;
					$testimonial['modified'] = date('Y-m-d H:i:s'); 
					$testimonial['is_active'] = TRUE;
					unset($testimonial['image_2']);
					//print_r($testimonial);

					if(NULL!==$testimonial['is_active']){
						$testimonial['is_active'] = TRUE;
					}else{
						$testimonial['is_active'] = FALSE;

					}
					if(isset($testimonial['id']) && !empty($testimonial['id'])) {
						$update[] = $testimonial;
					}else {
						unset($testimonial['id']);
	 					$testimonial['created'] = date('Y-m-d H:i:s');
	 					$insert[] = $testimonial;
					}
					
				}
				/*print_r($update);
				print_r($insert);
				exit;*/
				if(!empty($insert)) {
					$this->testimonial_model->set_table("company_testimonials");
					$this->insert_multiple($insert);
					$msg = array('message' => 'Testimonial Inserted Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}

				if(!empty($update)) {
					$this->testimonial_model->set_table("company_testimonials");

					$this->update_multiple($update);
					$msg = array('message' => 'Testimonial updated Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}
				redirect(custom_constants::new_testimonial_url);

			}else {
				$msg = array('message' => 'Validation error occured'. validation_errors(), 'class'=>'alert alert-danger');
				$this->session->set_flashdata('message', $msg);

			}
		}
		$this->testimonial_model->set_table("company_testimonials");
		$testimonial = $this->testimonial_model->get('is_active', TRUE);
		$data['testimonials'] = $testimonial->result_array();
		
		//print_r($data['testimonials']);
		$data['meta_title'] = "New Testimonial";
		$data['meta_description'] = "New Testimonial";
		$data['meta_keyword'] = "New Testimonial";
		$data['modules'][] = "testimonials";
		$data['methods'][] = "admin_add_testimonials";
		echo Modules::run('templates/admin_template', $data);
	}

	function admin_add_testimonials() {
		$this->load->view("testimonials/admin_add");
	}

	function update_multiple($data) {
		//echo "reched in update_multiple_product_images";
		$query = $this->testimonial_model->_update_multiple('id',$data);
		return $query;
	}

	function insert_multiple($data) {
		//print_r($data);exit;
		$query = $this->testimonial_model->_insert_multiple($data);
		return $query;
	}

	function deleteTestDetails() {
		$_POST['id'] = 1;
		$_POST['table'] = 'company_testimonials';
		$_POST['is_active'] = 'is_active';

		$_SERVER['REQUEST_METHOD'] = 'POST';
		//print_r($_SERVER['REQUEST_METHOD']);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo "hi";
			$id = $this->input->post('id');
			//echo $id;exit;
			$this->testimonial_model->set_table($this->input->post('table'));
			$testDetails = $this->testimonial_model->get_where($id);
			//print_r($faqDetails);
			//$faqDet = $faqDetails->row_array();
			$arrayData['is_active'] = $this->input->post('is_active');
			$arrayData['modified'] = date('Y-m-d H:i:s');
			$response = $this->testimonial_model->_update($id, $arrayData);
			if($response){
				echo json_encode(['status'=>'success', 'id'=>$id]);
				exit;
			}
		}
			
		echo json_encode(['status'=>'error', 'msg'=>'Invalid Input']);
		exit;
		
	}
}