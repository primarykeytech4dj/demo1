<?php 

class Setup extends MY_Controller {
	function __construct() {
		parent::__construct();

		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->model('setup/setup_model');
		$setup = $this->setup();	
	}

	function setup(){
		//exit;
		/*$products = $this->product_model->tbl_product_categories();
		$productDetails = $this->product_model->tbl_product_details();*/

		return TRUE;
	}

	function admin_index($id = NULL) {

		$data['meta_title'] = "Variations";
		$data['meta_description'] = "Variations";
		$data['modules'][] = "setup";
		$data['methods'][] = "admin_variation_listing";

		echo modules::run('templates/admin_template', $data);
	}

	

	function admin_variation_setup() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';print_r($this->input->post('variations'));exit;
			$data['values_posted'] = $this->input->post('variations');
			 //echo '<pre>';print_r($data['values_posted']);exit;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
				$postData = $data['values_posted'];
				$insert = [];
				$update = [];
				//echo '<pre>';
				foreach ($postData as $key => $variation) {
					//print_r($variation);
					if(isset($variation['is_active'])){
						$variation['is_active'] = TRUE;
					}else{
						$variation['is_active'] = false;
					}
					$variation['modified'] = date('Y-m-d H:i:s');
					if(!empty($variation['name_input'])){
						$variation['name'] = $variation['name_input'];
					}
					unset($variation['name_input']);
					//print_r($variation);
					if(isset($variation['id']) && !empty($variation['id'])){
						$update[] = $variation;
					}else{
						unset($variation['id']);
	 					$variation['created'] = date('Y-m-d H:i:s');
	 					$insert[] = $variation;
					}
					
				}//exit;
				//print_r($update);print_r($insert);
				//exit;
				if(!empty($insert)){
					$this->pktdblib->set_table("variations");
					$this->pktdblib->_insert_multiple($insert);
					$msg = array('message' => 'Variation Inserted Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}
				if(!empty($update)){
					$this->pktdblib->set_table("variations");
					$this->pktdblib->update_multiple('id',$update);
				}
				$msg = array('message' => 'Variation Updated Successfully', 'class'=>'alert alert-success');
				$this->session->set_flashdata('message', $msg);
				redirect('setup/variationsetup');
		}
		
			
		$data['variations'] = $this->pktdblib->custom_query("select * from variations order by is_active desc,name asc");
			//print_r($data['variations']);exit;
			$data['values_posted'] = $data['variations'];
		$variations = $this->pktdblib->custom_query("select Distinct name from variations order by is_active desc, name asc");
		$data['option']['variation'][0] = "Select Name";
		foreach ($variations as $key => $variation) {
			//print_r($variation['name']);
			$data['option']['variation'][$variation['name']] = $variation['name'];
		}
		$data['content'] = 'setup/admin_edit_variation';
		$data['title'] = 'Edit Variation';
		$data['meta_title'] = 'Edit Variation';
		$data['meta_description'] = 'Edit Variation';
		echo Modules::run('templates/admin_template', $data);
	}

	function admin_image_setup() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';print_r($this->input->post());exit;
			$data['values_posted'] = $this->input->post('image_setup');
			 //echo '<pre>';print_r($data['values_posted']);exit;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			
			

			
				$postData = $data['values_posted'];
				$insert = [];
				$update = [];
				/*echo '<pre>';
				print_r($postData);*/
				foreach ($postData as $key => $imageSetup) {
					//print_r($imageSetup);
					if(isset($imageSetup['is_active'])){
						$imageSetup['is_active'] = TRUE;
					}else{
						$imageSetup['is_active'] = false;
					}
					$imageSetup['modified'] = date('Y-m-d H:i:s');
					if(!empty($imageSetup['name_input'])){
						$imageSetup['image_folder_name'] = $imageSetup['name_input'];
					}
					unset($imageSetup['name_input']);
					//print_r($imageSetup);
					if(isset($imageSetup['id']) && !empty($imageSetup['id'])){
						$update[] = $imageSetup;
					}else{
						unset($imageSetup['id']);
	 					$imageSetup['created'] = date('Y-m-d H:i:s');
	 					$insert[] = $imageSetup;
					}
					
				}//exit;
				/*print_r($update);print_r($insert);
				exit;*/
				if(!empty($insert)){
					$this->pktdblib->set_table("image_setup");
					$this->pktdblib->_insert_multiple($insert);
					$msg = array('message' => 'Image Setup Inserted Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}
				if(!empty($update)){
					$this->pktdblib->set_table("image_setup");
					$this->pktdblib->update_multiple('id',$update);
				}
				$msg = array('message' => 'Image Setup Updated Successfully', 'class'=>'alert alert-success');
				$this->session->set_flashdata('message', $msg);
				redirect('setup/imagesetup');
			


		}
		
			
		$data['images'] = $this->pktdblib->custom_query("select * from image_setup order by is_active desc,image_folder_name asc");
			//print_r($data['variations']);exit;
			$data['values_posted'] = $data['images'];
		$images = $this->pktdblib->custom_query("select Distinct image_folder_name from image_setup order by is_active desc, image_folder_name asc");
		$data['option']['image'][0] = "Select Folder Name";
		foreach ($images as $key => $image) {
			//print_r($variation['name']);
			$data['option']['image'][$image['image_folder_name']] = $image['image_folder_name'];
		}
		$data['content'] = 'setup/image_variation';
		$data['title'] = 'Edit Variation';
		$data['meta_title'] = 'Edit Variation';
		$data['meta_description'] = 'Edit Variation';
		echo Modules::run('templates/admin_template', $data);
	}

	function admin_uom_setup() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';print_r($this->input->post());exit;
			$data['values_posted'] = $this->input->post('uom');
			 //echo '<pre>';print_r($data['values_posted']);exit;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
				$postData = $data['values_posted'];
				$insert = [];
				$update = [];
				//echo '<pre>';
				foreach ($postData as $key => $uom) {
					//print_r($uom);
					if(isset($uom['is_active'])){
						$uom['is_active'] = TRUE;
					}else{
						$uom['is_active'] = false;
					}
					$uom['modified'] = date('Y-m-d H:i:s');
					
					if(isset($uom['id']) && !empty($uom['id'])){
						$update[] = $uom;
					}else{
						unset($uom['id']);
	 					$uom['created'] = date('Y-m-d H:i:s');
	 					$insert[] = $uom;
					}
					
				}//exit;
				/*echo '<pre>';print_r($update);print_r($insert);
				exit;*/
				if(!empty($insert)){
					$this->pktdblib->set_table("uom");
					$this->pktdblib->_insert_multiple($insert);
					$msg = array('message' => 'UOM Inserted Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}
				if(!empty($update)){
					$this->pktdblib->set_table("uom");
					$this->pktdblib->update_multiple('id',$update);
				}
				$msg = array('message' => 'UOM Updated Successfully', 'class'=>'alert alert-success');
				$this->session->set_flashdata('message', $msg);
				redirect('setup/uomsetup');
		}
		
			
		$data['uom'] = $this->pktdblib->custom_query("select * from uom order by is_active desc,uom asc");
			//echo '<pre>';
		//print_r($data['uom']);//exit;
			$data['values_posted'] = $data['uom'];
		$uom = $this->pktdblib->custom_query("select Distinct uom from uom order by is_active desc, uom asc");
		$data['option']['uom'][0] = "Select uom";
		foreach ($uom as $key => $uom) {
			//print_r($uom['uom']);
			$data['option']['uom'][$uom['uom']] = $uom['uom'];
		}
		$data['content'] = 'setup/admin_edit_uom';
		$data['title'] = 'Edit uom';
		$data['meta_title'] = 'Edit uom';
		$data['meta_description'] = 'Edit uom';
		echo Modules::run('templates/admin_template', $data);
	}

	function admin_attribute_setup() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';print_r($this->input->post());exit;
			$data['values_posted'] = $this->input->post('attributes');
			 //echo '<pre>';print_r($data['values_posted']);exit;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
				$postData = $data['values_posted'];
				$insert = [];
				$update = [];
				//echo '<pre>';
				foreach ($postData as $key => $attributes) {
					//print_r($attributes);
					if(isset($attributes['is_active'])){
						$attributes['is_active'] = TRUE;
					}else{
						$attributes['is_active'] = false;
					}
					$attributes['modified'] = date('Y-m-d H:i:s');
					
					if(isset($attributes['id']) && !empty($attributes['id'])){
						$update[] = $attributes;
					}else{
						unset($attributes['id']);
	 					$attributes['created'] = date('Y-m-d H:i:s');
	 					$insert[] = $attributes;
					}
					
				}//exit;
				/*echo '<pre>';print_r($update);print_r($insert);
				exit;*/
				if(!empty($insert)){
					$this->pktdblib->set_table("attributes");
					$this->pktdblib->_insert_multiple($insert);
					$msg = array('message' => 'attributes Inserted Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}
				if(!empty($update)){
					$this->pktdblib->set_table("attributes");
					$this->pktdblib->update_multiple('id',$update);
				}
				$msg = array('message' => 'Attribute Updated Successfully', 'class'=>'alert alert-success');
				$this->session->set_flashdata('message', $msg);
				redirect('setup/attributesetup');
		}
		
			
		$data['attribute'] = $this->pktdblib->custom_query("select * from attributes order by is_active desc, unit asc");
			//echo '<pre>';
		//print_r($data['attribute']);//exit;
			$data['values_posted'] = $data['attribute'];
		$attributes = $this->pktdblib->custom_query("select Distinct unit from attributes order by is_active desc, unit asc");
		$data['option']['attribute'][0] = "Select Attribute";
		foreach ($attributes as $key => $attribute) {
			//print_r($attribute['attribute']);
			$data['option']['attribute'][$attribute['unit']] = $attribute['unit'];
		}
		$this->pktdblib->set_table('uom');
		$uomCode = $this->pktdblib->get_where_custom('is_active', true);
		$uomShortCode = $uomCode->result_array();
		$data['uom'] = [];
		foreach ($uomShortCode as $uomKey => $uom) {
			$data['uom'][$uom['short_code']] = $uom['short_code'];
		}
		$data['content'] = 'setup/admin_edit_attribute';
		$data['title'] = 'Edit Attribute';
		$data['meta_title'] = 'Edit Attribute';
		$data['meta_description'] = 'Edit Attribute';
		echo Modules::run('templates/admin_template', $data);
	}

}
	