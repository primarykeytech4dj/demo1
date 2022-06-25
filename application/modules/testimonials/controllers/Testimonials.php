<?php 

class Testimonials extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('testimonials/testimonial_model');
		$this->load->model('companies/companies_model');

	}

	/*function index() {
		$data['title'] = "Testimonials";
		$data['meta_title'] = "Testimonials";
		$data['meta_description'] = "Testimonials";
		$data['meta_keyword'] = "Testimonials";
		//$data['content'] = 'testimonials/index';
		$data['modules'][] = 'testimonials';
		$data['methods'][] = 'testimonial_listing';
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'Contact']
		];
		$data['js'][] = '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
    $("#testimonial-slider").owlCarousel({
        items:2,
        itemsDesktop:[1000,1],
        itemsDesktopSmall:[979,1],
        itemsTablet:[768,1],
        pagination: true,
        autoPlay:true
    });
});
        </script>';

        $data['css'][0] = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">';
        $data['css'][1] = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">';
        $data['css'][2] = '<link rel="stylesheet" type="text/css" href="'. base_url('assets/css/testimonial.css'). '">';
        //base_url('assets/css/testimonial.css'); 
        //print_r($data['css']);exit;

		echo Modules::run('templates/obaju_inner_template', $data);
	}*/


	function index(){
		$data['title'] = "Testimonials";
		$data['meta_title'] = "Testimonials";
		$data['meta_description'] = "Testimonials";
		$data['meta_keyword'] = "Testimonials";
		//$data['content'] = 'testimonials/index';
		$data['modules'][] = 'testimonials';
		$data['methods'][] = 'testimonial_listing';
		echo Modules::run('templates/default_template', $data);

	}

	function testimonial_listing() {
		$this->testimonial_model->set_table("company_testimonials");
		$testimonials = $this->testimonial_model->get_where_custom('is_active', true);
		$data['testimonials'] = $testimonials->result_array();
		$this->load->view('testimonials/index', $data);
	}
        function admin_index($id = NULL) {
		$data['meta_title'] = "Testimonials";
		$data['meta_description'] = "Testimonials";
		$data['modules'][] = "testimonials";
		$data['methods'][] = "admin_testimonial_listing";
		echo modules::run('templates/admin_template', $data);
	}

	function admin_testimonial_listing() {
		$this->testimonial_model->set_table("company_testimonials");
		$testimonials = $this->testimonial_model->get_where_custom('is_active', true);
		$data['testimonials'] = $testimonials->result_array();
		$this->load->view('testimonials/admin_index',$data);
	}

	function admin_add() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';
			//print_r($_POST);exit;
			$data['values_posted'] = $_POST['company_testimonials'];
			$imageName = '';
			foreach($data['values_posted'] as $key => $value) {
				//print_r("name => ".$value['name'].", "."company =>".$value['company']);
			}
			//print_r($value['name']. ", ". $value['company']);
			if(isset($value['name']) && $value['company']) {
				//echo "hi";exit;
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
				foreach ($data['values_posted'] as $testimonialKey => $testimonial) {
					print_r($testimonial['is_active']);
	 				if(!empty($imageName['filename'][$testimonialKey])) {
	 					//echo " filename not empty";
 					$testimonial['image'] = $imageName['filename'][$testimonialKey];
	 				}else {
	 					//echo " filename  empty";
	 					$testimonial['image'] = $testimonial['image_2'];
	 				}
					$testimonial['modified'] = date('Y-m-d H:i:s'); 
					if(isset($testimonial['is_active'])) {
						$testimonial['is_active'] = TRUE;
					}else {
						$testimonial['is_active'] = FALSE;

					}
					unset($testimonial['image_2']);
					/*if(NULL!==$testimonial['is_active']){
						$testimonial['is_active'] = TRUE;
					}else{
						$testimonial['is_active'] = FALSE;

					}*/
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
				$msg = array('message' => 'inputs required', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
			}
			//print_r($_POST['company_testimonials'][0]);
			//exit;
			
		}
		$this->testimonial_model->set_table("company_testimonials");
		$testimonial = $this->testimonial_model->get('id','ASC');
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