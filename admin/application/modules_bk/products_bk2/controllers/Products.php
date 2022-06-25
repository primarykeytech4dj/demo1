<?php 
//ALTER TABLE `product_attributes` ADD `per_unit_margin` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `uom`;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Products extends MY_Controller {
	function __construct() {
		parent::__construct();

		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
			}
		}
		check_user_login(FALSE);
		$this->load->model('products/product_model');
		$setup = $this->setup();	
	}

	function setup(){
		//exit;
		$products = $this->product_model->tbl_product_categories();
		$productDetails = $this->product_model->tbl_product_details();
		$manufacturingBrands = Modules::run('brands/setup');
		return TRUE;
	}

	function admin_index($id = NULL) {
		if($this->input->is_ajax_request()){  
            
           $postData = $this->input->post();
            //echo "<pre>"; print_r($postData);exit;
            $data = $this->product_model->productList($postData);
            foreach($data['aaData'] as $key=>$v){
                //echo "<pre>"; print_r($v);exit;
                $action = '
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                        <span class="fa fa-caret-down" ></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>'.anchor(custom_constants::admin_product_view.'/'.$v['id'], 'View', ['class'=>'']).'</li>
                   <li>'.anchor(custom_constants::edit_product_url."/".$v['id'], 'Edit', ['class'=>'']).'</li>';
                      
                    $action.='</ul>
                </div>';
                $data['aaData'][$key]['action'] = $action;
                //echo $action;exit;
            }
            echo json_encode($data);
            exit;
            
        }
		$data['meta_title'] = "Products";
		$data['meta_description'] = "Products";
		$data['modules'][] = "products";
		$data['methods'][] = "admin_product_listing";

		echo modules::run('templates/admin_template', $data);
		
	}

	function admin_product_listing($data=[]) {
		
		$this->load->view('products/admin_index',$data);
	}

	function admin_product_image_index($id = NULL) {
		$data['meta_title'] = "Product Images";
		$data['meta_description'] = "Product Images";
		$data['modules'][] = "products";
		$data['methods'][] = "admin_product_image_listing";
		echo modules::run('templates/admin_template', $data);
	} 


	function admin_product_image_listing($data = []) {
		//echo $productId;
		$condition = '';
			//$condition['product_id'] = $productId;
		if(isset($data['condition'])) 
			$condition = $data['condition'];
		//print_r($condition);exit;
		$data['productImages'] = $this->product_wise_product_image_listing($condition);
		//echo '<pre>';
		//print_r($data['productImages']);exit;
		$this->load->view('Products/admin_product_image_index', $data);
	}

	function get_service_list_dropdown(){
		$this->load->model('products/product_model');	
		$serviceList = $this->product_model->get_active_service_list();
		$products = [''=>'Select Service'];
		foreach ($serviceList as $key => $service) {
			$products[$service['id']] = $service['product'];
		}
		return $products;
	}

	function admin_add_category() {
		//check_user_login(FALSE);
		$this->load->model('products/product_model');
		//echo "new_product";

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			/*echo '<pre>';
			print_r($_POST);exit;*/
			//print_r($_FILES);
			$data['values_posted'] = $this->input->post();
			$this->form_validation->set_rules('data[product_categories][parent_id]', 'parent');
			$this->form_validation->set_rules('data[product_categories][category_name]', 'category_name', 'required|max_length[255]');
			//$this->form_validation->set_rules('data[product_categories][slug]', 'slug', 'required|max_length[255]|is_unique[product_categories.slug]');
			if($this->form_validation->run('product_categories')!== false) {
				$profileImg1 = '';
				$profileImg2 = '';

				$error = [];
				if(!empty($_FILES['image_name_1']['name'])) {
					$productCategoriesValidationParams1 =['file' =>$_FILES['image_name_1'], 'path'=>'../content/uploads/product_categories/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'image_name_1', 'arrindex'=>'image_name_1'];
					$profileImg1 = $this->pktlib->upload_single_file($productCategoriesValidationParams1);
					if(empty($profileImg1['error'])) {
						$_POST['data']['product_categories']['image_name_1'] = $profileImg1['filename'];
					} else { //echo "error found in image 1";
						$error['image_name_1'] = $profileImg1['error'];
					}
				}//exit;

				if(!empty($_FILES['image_name_2']['name'])) {
					//echo "reached in files";
					$productCategoriesValidationParams2 =['file' =>$_FILES['image_name_2'], 'path'=>'../content/uploads/product_categories/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'image_name_2', 'arrindex'=>'image_name_2'];
					$profileImg2 = $this->pktlib->upload_single_file($productCategoriesValidationParams2);
					if(empty($profileImg2['error'])) {
						if(empty($_POST['data']['product_categories']['image_name_2'])) {
						$_POST['data']['product_categories']['image_name_2'] = $profileImg1['filename'];
						}else{
							$_POST['data']['product_categories']['image_name_2'] = $profileImg2['filename'];

						}
					} else {
						$error['image_name_2'] = $profileImg2['error'];
					}
				}else {
					if(isset($profileImg1['filename'])) {
						$_POST['data']['product_categories']['image_name_2'] = $profileImg1['filename'];
					}
					
				}
				if(empty($error)) {
					$post_data = $this->input->post('data[product_categories]');
					$config = array(
	                    'table'         => 'product_categories',
	                    'id'            => 'id',
	                    'field'         => 'slug',
	                    'title'         => 'category_name',
	                    'replacement'   => 'dash' // Either dash or underscore
	                );
	                $this->load->library('slug', $config);
					$post_data['slug'] = $this->slug->create_uri(array('category_name' => $post_data['category_name']), '', '');
					$post_data['full_banner'] = (isset($post_data['full_banner']))?true:false;
					$register_product_category = json_decode($this->register_product_category($post_data), true);
					if($register_product_category['status'] === 'success') {
						$companyProductCategory = [];
						if(isset($_SESSION['application']['multiple_company']) && $_SESSION['application']['multiple_company']==true){
					 	foreach ($this->input->post("data[companies_product_categories][company_id]") as $key => $value) {
					 		$companyProductCategory[$key]['company_id'] = $value;
    					 	$companyProductCategory[$key]['product_category_id'] = $register_product_category['id']['id'];
    					 	$companyProductCategory[$key]['is_active'] = true;
    					 	$companyProductCategory[$key]['created'] = $companyProductCategory[$key]['modified'] = date('Y-m-d H:i:s');
    					 	}
    					 	if(!empty($companyProductCategory)){
    					 		$this->pktdblib->set_table("companies_product_categories");
    					 		$this->pktdblib->_insert_multiple($companyProductCategory);
    					 	}
						}
						$msg = array('message'=>'Product Category Added Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
					redirect(custom_constants::new_product_category_url);

					}
					else {
						$data['form_error'] = $register_product_category['msg'];
					}
				}
				else {
					$msg = array('message'=>'unable to add product', 'class'=>'alert alert-danger');
					$this->session->set_flashdata('message', $msg);
				}
			}
			else {
				$msg = array('message'=>'unable to add product following error occured. '.validation_errors(), 'class'=>'alert alert-danger');
					$this->session->set_flashdata('message', $msg);
			}

		}

		if($_SESSION['application']['multiple_company']){
			$data['option']['company'] = json_decode(Modules::run('companies/get_dropdown_list'), true);
		}
		/*$data['parents'] = $this->product_model->get_category_dropdown_list();
		//print_r($data['parents']);
		$data['option']['parent'][0] = 'Select Parent';
		foreach($data['parents'] as $parentKey => $parent){
			
			$data['option']['parent'][$parent['id']] = $parent['category_name'];
		}*/

		$data['option']['parent'] = $this->parentCategories(NULL!==$this->session->userdata('access_to_company')?$this->session->userdata('access_to_company'):[], 0, $level='', $result = [0=>'Select']);
		
		$data['modules'][] = 'products';
		$data['methods'][]= 'admin_add_product_category';
		//$data['content'] = 'products/add_products';
		$data['title'] = 'Add Product Category';
		$data['meta_title'] = 'Add Product Category';
		$data['meta_description'] = 'Add Product Category';
		
		echo Modules::run('templates/admin_template', $data);
	}

	function register_product_category($data) {
		$insert_data = $data;
		$this->product_model->set_table("product_categories");
		$id = $this->product_model->_insert($insert_data);
		return json_encode(['message' =>'Enquiry added Successfully', "status"=>"success", 'id'=> $id]);
	}

	function admin_add_product_category() {
		$this->load->view('products/admin_add_product_category');
	}

	function admin_edit_category($id = NULL) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
			$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
			$this->form_validation->set_rules("data[product_categories][parent_id]", 'Parent', 'required');
			$this->form_validation->set_rules("data[product_categories][category_name]", 'Category Name', 'required|max_length[255]');
			$this->form_validation->set_rules("data[product_categories][gst]", 'GST', 'max_length[255]');
			if($this->form_validation->run('product_categories')!== FALSE){
				$productCategoryImg = '';
			//echo "hi";
				$postData = $_POST['data']['product_categories'];
				//print_r($postData);exit;
				if(!empty($_FILES['image_name_1']['name'])) {
					$productCategoryFileValidationParams = ['file'=>$_FILES['image_name_1'], 'path'=>'../content/uploads/product_categories', 'fieldname'=>'image_name_1', 'ext'=>'jpeg|png|jpg|gif', 'arrindex'=>'image_name_1'];
					$productCategoryImg = $this->pktlib->upload_single_file($productCategoryFileValidationParams);
					if(empty($productCategoryImg['error'])) {
						$postData['image_name_1'] = $productCategoryImg['filename'];
						unset($postData['image_name_1_2']);
					}
					else {
						$error['image_name_1'] = $productCategoryImg['error'];
					}
				}
				else {
					$postData['image_name_1'] = $postData['image_name_1_2'];
					unset($postData['image_name_1_2']);
				}
				if(!empty($_FILES['image_name_2']['name'])) {
					$productCategoryFileValidationParams = ['file'=>$_FILES['image_name_2'], 'path'=>'../content/uploads/product_categories', 'fieldname'=>'image_name_2', 'ext'=>'jpeg|png|jpg|gif', 'arrindex'=>'image_name_2'];
					$productCategoryImg = $this->pktlib->upload_single_file($productCategoryFileValidationParams);
					if(empty($productCategoryImg['error'])) {
						$postData['image_name_2'] = $productCategoryImg['filename'];
						unset($postData['image_name_2_2']);
					}
					else {
						$error['image_name_2'] = $productCategoryImg['error'];
					}
				}
				else {
					$postData['image_name_2'] = $postData['image_name_2_2'];
					unset($postData['image_name_2_2']);
				}
				if(empty($error)) {
				    /*echo '<pre>';
				    print_r($_POST);
				    exit;*/
				    $postData['is_active'] = (isset($postData['is_active']))?true:false;
				    $postData['full_banner'] = (isset($postData['full_banner']))?true:false;
				    $config = array(
	                    'table'         => 'product_categories',
	                    'id'            => 'id',
	                    'field'         => 'slug',
	                    'title'         => 'category_name',
	                    'replacement'   => 'dash' // Either dash or underscore
	                );
	                $this->load->library('slug', $config);
					$postData['slug'] = $this->slug->create_uri(array('category_name' => $postData['category_name']), $id, '');
				// 	echo '<pre>';
				// 	print_r($postData);
				// 	exit;
					if($this->product_model->update_product_categories($id, $postData)) {
						//print_r($this->input->post("data"));
						if(($_SESSION['application']['multiple_company']) && NULL!==($this->input->post("data[companies_product_categories][company_id]"))){
							//echo $_SESSION['application']['multiple_company'];
							$this->pktdblib->set_table("companies_product_categories");

							$this->pktdblib->_delete_by_column('product_category_id',$id);
							$companyProductCategory = [];
					 	foreach ($this->input->post("data[companies_product_categories][company_id]") as $key => $value) {
					 		$companyProductCategory[$key]['company_id'] = $value;
					 		$companyProductCategory[$key]['product_category_id'] = $id;
					 		$companyProductCategory[$key]['is_active'] = true;
					 		$companyProductCategory[$key]['created'] = $companyProductCategory[$key]['modified'] = date('Y-m-d H:i:s');
					 		}
					 		//print_r($companyProductCategory);
						 	if(!empty($companyProductCategory)){
						 		$this->pktdblib->set_table("companies_product_categories");
						 		$this->pktdblib->_insert_multiple($companyProductCategory);
						 	}
						}else{
							$this->pktdblib->set_table("companies_product_categories");
							$this->pktdblib->_delete_by_column('product_category_id',$id);
						}
						//exit;
						$msg = array('message'=>'Data Updated Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
					}
					else {
						$msg = array('message'=>'some problem occured', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
					}
					redirect(custom_constants::edit_product_category_url."/".$id);
				} 
				else {
					$msg = array('message'=>'some error occured while uploading'.$error, 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
				}
			} 
			else {
				$data['values_posted']['product_categories']['image_name_1'] = $data['values_posted']['product_categories']['image_name_1_2']; 
				$data['values_posted']['product_categories']['image_name_2'] = $data['values_posted']['product_categories']['image_name_2_2']; 
				$msg = array('message'=>'some validation error occured'.validation_errors(), 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
			}
		}  
		else {
			$data['product_categories'] = $this->get_product_category_details($id);
			$data['values_posted']['product_categories'] = $data['product_categories'];
		} 
		
		if($_SESSION['application']['multiple_company']){
			$data['option']['company'] = json_decode(Modules::run('companies/get_dropdown_list'), true);
		 	$companyProductCategories = $this->pktdblib->custom_query("select company_id from companies_product_categories where product_category_id=".$id);
		 	//print_r($companyProductCategories);exit;
		 	foreach ($companyProductCategories as $key => $company) {
		 		$data['company_id'][] = $company['company_id'];
		 	}
		 }
		
		$data['option']['parent'] = $this->parentCategories(NULL!==$this->session->userdata('access_to_company')?$this->session->userdata('access_to_company'):[], 0, $level='', $result = [0=>'Select']);
		$data['id'] = $id;
		$data['modules'][] = 'products';
		$data['methods'][]= 'admin_edit_product_category';
		$data['title'] = 'Edit Product Category';
		$data['meta_title'] = 'Edit Product Category';
		$data['meta_description'] = 'Edit Product Category';
		
		echo Modules::run('templates/admin_template', $data);
	}

	function admin_edit_product_category() {
		$this->load->view('products/admin_edit_product_category');
	}


	function get_product_category_details($id) {
		$this->product_model->set_table('product_categories');
		$productCategoryDetail = $this->product_model->get_where($id);
		return $productCategoryDetail;
	}

	function admin_edit($id) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
			if(!$_SESSION['application']['product_has_multiple_categories']){
			    $this->form_validation->set_rules("data[products][product_category_id]", 'Product Category', 'required');
			}
			
			$this->form_validation->set_rules("data[products][product]", 'Product', 'required|max_length[255]');
			$this->form_validation->set_rules("data[products][product_code]", 'Product Code', 'max_length[255]');
			$this->form_validation->set_rules("data[products][base_price]", 'Base Price', 'max_length[255]');

			if($this->form_validation->run('products')!== FALSE){
				$productImg = '';
				$postData = $_POST['data']['products'];
				if(isset($postData['is_sale']))
					$postData['is_sale'] = true;
				else
					$postData['is_sale'] = false;

				if(isset($postData['is_new']))
					$postData['is_new'] = true;
				else
					$postData['is_new'] = false;

				if(isset($postData['is_gift']))
					$postData['is_gift'] = true;
				else
					$postData['is_gift'] = false;

				if(isset($postData['is_pack']))
					$postData['is_pack'] = true;
				else
					$postData['is_pack'] = false;

				if(isset($postData['show_on_website']))
					$postData['show_on_website'] = true;
				else
					$postData['show_on_website'] = false;
					
				if(isset($postData['is_active']))
					$postData['is_active'] = true;
				else
					$postData['is_active'] = false;

				if(isset($postData['overall_stock_mgmt']))
					$postData['overall_stock_mgmt'] = true;
				else
					$postData['overall_stock_mgmt'] = false;

				if(empty($error)) {
					$config = array(
	                    'table'         => 'products',
	                    'id'            => 'id',
	                    'field'         => 'slug',
	                    'title'         => 'product',
	                    'replacement'   => 'dash' // Either dash or underscore
	                );
	                $this->load->library('slug', $config);
					$postData['slug'] = $this->slug->create_uri(array('product' => $this->input->post('data[products][product]')), $id, '');
					if($this->edit_product($id,$postData))
					{	
						if(isset($_SESSION['application']['multiple_company']) && $_SESSION['application']['multiple_company']!=1 && NULL!==($this->input->post("data[companies_products][company_id]"))){
							$this->pktdblib->set_table("companies_products");

							$this->pktdblib->_delete_by_column('product_id',$id);
							$companyProduct = [];
					 	foreach ($this->input->post("data[companies_products][company_id]") as $key => $value) {
					 		$companyProduct[$key]['company_id'] = $value;
					 		$companyProduct[$key]['product_id'] = $id;
					 		$companyProduct[$key]['is_active'] = true;
					 		$companyProduct[$key]['created'] = $companyProduct[$key]['modified'] = date('Y-m-d H:i:s');
					 		}
						 	if(!empty($companyProduct)){
						 		$this->pktdblib->set_table("companies_products");
						 		$this->pktdblib->_insert_multiple($companyProduct);
						 	}
						}else{
							$chk = $this->product_model->check_table('companies_products');
							if($chk){
								$this->pktdblib->set_table("companies_products");
								$this->pktdblib->_delete_by_column('product_id',$id);
							}
						}
						if(($_SESSION['application']['product_has_multiple_brand']) && NULL!==($this->input->post("data[brand_products][brand_id]"))){
							$this->pktdblib->set_table("brand_products");

							$this->pktdblib->_delete_by_column('product_id',$id);
							$brandProduct = [];
					 	foreach ($this->input->post("data[brand_products][brand_id]") as $key => $value) {
					 		$brandProduct[$key]['brand_id'] = $value;
					 		$brandProduct[$key]['product_id'] = $id;
					 		$brandProduct[$key]['is_active'] = true;
					 		$brandProduct[$key]['created'] = $brandProduct[$key]['modified'] = date('Y-m-d H:i:s');
					 		}
						 	if(!empty($brandProduct)){
						 		$this->pktdblib->set_table("brand_products");
						 		$this->pktdblib->_insert_multiple($brandProduct);
						 	}
						}else{
							$chk = $this->product_model->check_table('brand_products');
							if($chk){
								$this->pktdblib->set_table("brand_products");
								$this->pktdblib->_delete_by_column('product_id',$id);
							}
						}
						
						if(($_SESSION['application']['product_has_multiple_categories']) && NULL!==($this->input->post("data[product_product_categories][product_category_id]"))){
						 	$this->pktdblib->set_table("product_product_categories");
							$this->pktdblib->_delete_by_column('product_id',$id);
						 	$productProductCategories = [];
						 	foreach ($this->input->post("data[product_product_categories][product_category_id]") as $key => $value) {
						 		$productProductCategories[$key]['product_category_id'] = $value;
						 	    $productProductCategories[$key]['product_id'] = $id;
						 	}
						 	if(!empty($productProductCategories)){
						 		$this->pktdblib->set_table("product_product_categories");
						 		$this->pktdblib->_insert_multiple($productProductCategories);
						 	}
						}
						$msg = array('message' => 'Data Updated Successfully', 'class' => ' alert alert-success');
						$this->session->set_flashdata('message', $msg);
					} 
					else {
						$msg = array('message' => 'Some error occured while updating','class'=>'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
					}
					redirect(custom_constants::edit_product_url."/".$id."?tab=product_images");
				}
				else {
					$msg = array('message' => 'Some error occured while updating','class'=>'alert alert-danger');
					$this->session->set_flashdata('message', $msg);	
				}
			}
			else {
				$msg = array('message' => 'validation error'.validation_errors() ,'class'=>'alert alert-danger');
				$this->session->set_flashdata('message', $msg);
			}


		}
		else {
			$data['products'] = $this->get_product_details($id);
			$data['values_posted']['products'] = $data['products'];
			$data['product_images'] = $this->get_product_image_details($id); 
			$data['values_posted']['product_images'] = $data['product_images'];
		}
		
		$data['productCategories'] = $this->product_model->get_categorylist_for_product();
		$data['option']['category'] = $this->parentCategories(NULL!==$this->session->userdata('access_to_company')?$this->session->userdata('access_to_company'):[], 0, $level='', $result = [0=>'Select']);
		$data['id'] = $id;
		if(!($this->input->get('tab'))){
			$data['tab'] = 'product_details';
		}
		else {
			$data['tab'] = $this->input->get('tab');	
		}
		$this->pktdblib->set_table('companies');
		 $companies = $this->pktdblib->get_where_custom('is_active', true);
		 $data['companies'] = $companies->result_array();
		 $data['option']['company'][0] = 'Select Company';
		 foreach ($data['companies'] as $key => $company) {
		 	$data['option']['company'][$company['id']] = $company['company_name'];
		 }

		if($_SESSION['application']['multiple_company']){
			$companyProducts = $this->pktdblib->custom_query("select company_id from companies_products where product_id=".$id);
			foreach ($companyProducts as $key => $company) {
				$data['company_id'][] = $company['company_id'];
			}
		}
		$this->pktdblib->set_table('manufacturing_brands');
		$brands = $this->pktdblib->get_where_custom('is_active', true);
		$data['brands'] = $brands->result_array();
		//print_r($data['brands']);
		$data['option']['brand'][0] = 'Select brand';
		foreach ($data['brands'] as $key => $brand) {
			$data['option']['brand'][$brand['id']] = $brand['brand'];
		}
		//print_r($data['option']['brand']);
		if($_SESSION['application']['product_has_multiple_brand']){
			$brandProduct = $this->pktdblib->custom_query("select brand_id from brand_products where product_id=".$id);

			foreach ($brandProduct as $key => $brand) {
				$data['brand_id'][] = $brand['brand_id'];
			}
		}
		
		if($_SESSION['application']['product_has_multiple_categories']){
		    $productProductCategories = $this->pktdblib->custom_query("select product_category_id from product_product_categories where product_id=".$id);

			foreach ($productProductCategories as $key => $category) {
				$data['product_category_id'][] = $category['product_category_id'];
			}
		}

		$this->pktdblib->set_table('attributes');
        $attributes = $this->pktdblib->get_where_custom('is_active', true);
        $data['attributes'] = $attributes->result_array();
        $data['option']['attributes'][] = 'Select base Uom';
        foreach ($data['attributes'] as $key => $attribute) {
            $data['option']['attributes'][$attribute['unit'].' '.$attribute['uom']] = $attribute['unit'].' '.$attribute['uom'];
        }
		$data['modules'][0] = 'products';
		$data['methods'][0]= 'admin_edit_product';
		$data['product_type'] = $this->get_product_type();
		$data['option']['product_type'] = $data['product_type'];
		$data['packProducts'] = Modules::run('products/admin_edit_pack_products', $id);
		$data['productImages'] = Modules::run('products/admin_edit_product_images', $id);
		$data['variation'] = Modules::run('products/product_variation', $id);
		$data['attribute'] = Modules::run('products/admin_edit_product_attributes', $id);
		$data['rateCalculator'] = Modules::run('products/rate_calculator_form', $id);
		$this->pktdblib->set_table('product_details');
		$data['productDetails'] = Modules::run('products/admin_edit_product_details', $id);
		$data['title'] = 'Edit Product Detail';
		$data['meta_title'] = 'Edit Product Detail';
		$data['meta_description'] = 'Edit Product Detail';
		$data['js'][] = '<script type="text/javascript">
            $(document).on("change", ".type", function() {
            var trid = $(this).closest("tr").attr("id");
            /*console.log(trid);
            console.log(this.value);*/
			    if (this.value == "image")
			    {
			     console.log("type is image");
			      $("#image_name_1_"+trid).show();
			      $("#featured_image_video_"+trid).hide();
			      
			    }
			    else
			    {
			     console.log("type is video");
			      $("#image_name_1_"+trid).hide();
			      $("#featured_image_video_"+trid).show();
			    }
  			});

		$(".finalprice").on("keyup", function(){
		    console.log("reached in final price");
            var trid = $(this).closest("tr").attr("id");
            var mrp = $("#mrp_"+trid).val();
			var finalprice = $("#final_price_"+trid).val();
			
			var discount = ((mrp-finalprice)/mrp)*100.00;
			console.log("discount:"+discount);
			
			$("#discount_"+trid).val(discount.toFixed(2));
            
        })
		$(document).on("keyup, blur", ".increased_price", function(){
			var trid = $(this).closest("tr").attr("id");
			var marginPercent = $(this).val();
			var productId = $(this).attr("data-id");
			var increasePrice = $("#increased_percentage_"+trid).val()
			//console.log("increased_percent="+increasePrice);
			//console.log("trid="+trid);
			//console.log("attribute_value ="+attributeVal);
			//console.log("product_id="+productId);
			var attributeId = $("#attribute_id_"+trid).attr("id");
			//console.log(attributeId);
			var attributeText = $("#attribute_id_"+trid+" option:selected").html();
			//console.log(attributeText);
			$.ajax({
                    type: "POST",
                    dataType: "json",
                    data:{"productId":productId, "attribute":attributeText, "margin":marginPercent},
                    url : base_url+"products/attribute_wise_mrp",
                    success: function(response) {
                        
                        console.log(JSON.stringify(response));
                        //console.log(response["base_price"]);
                    	console.log("1 unit value after conversion="+response.unit);
                    	console.log("given unit="+response.baseUnit);
                    	var unitValue = response.unit*response.baseUnit;
                    	console.log("unit value="+unitValue);
                    	var price = response.base_price;
                    	console.log("price="+price);
                    	var afterIncreasePercent = Math.ceil(price+(price*increasePrice)/100);
                    	console.log("afterIncreasePercent="+afterIncreasePercent.toFixed(2));
                        $("#price_"+trid).val(afterIncreasePercent.toFixed(2));
                        $("#mrp_"+trid).val(afterIncreasePercent.toFixed(2));
                    }
                
                });
			});

			$(document).on("keyup", ".product_discount", function(){
			var trid = $(this).closest("tr").attr("id");
			var productId = $(this).attr("data-id");
			var discount = $("#discount_"+trid).val();
			var price = $("#mrp_"+trid).val();
			//console.log(trid);
			//console.log("price="+price);
			//console.log("product_discount="+discount);
			//console.log(discount);
			var discountedPrice = price-(price*discount/100);
			console.log(discountedPrice);
			//$("label#final_price_"+trid).val(discountedPrice.toFixed(2));
			$("#final_price_"+trid).val(discountedPrice.toFixed(2));
			});
		
		
        </script>';
		echo Modules::run('templates/admin_template', $data);
	}

	function admin_edit_product() {
		$this->load->view('products/admin_edit');
	}
	
    function admin_edit_product_images($productId) {
    	//echo $productId;
 		if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
 			//print_r($_POST);exit;
 			$data['values_posted'] = $_POST; 
 			/*echo '<pre>';
 			print_r($_POST);exit;*/
 			//print_r($data['values_posted']);
 			//print_r($_FILES['product_images']);//exit;
 			$productImg1 = '';
			$productImg2 = '';
			$insert = [];
			$update = [];
 			if(count($_FILES['product_images']['name'])>0) {
 					//echo " multiple images are found";//exit;
 					$productImageFileValidationParams1 = ['file'=>$_FILES['product_images'], 'path'=>'../content/uploads/products', 'ext'=>'jpeg|jpg|png|gif', 'fieldname'=>'image_name_1', 'arrindex'=>'product_images'];
 					//print_r($productImageFileValidationParams1);
    				$productImg1 = $this->pktlib->upload_multiple_file($productImageFileValidationParams1);
    				//print_r($productImg1);
    				//exit;
    				if(empty($productImg1['error'])) {
    					//$postData['productImg1'] = $productImg1['filename'];
						//unset($postData['logo2']);
    				}
    				if(!$productImg1) {
    					$msg = array('message' => "Some error occured with file", 'class'=>'alert alert-danger');
    					$this->session->set_flashdata('message', $msg);	
    				}

    				$productImageFileValidationParams2 = ['file'=>$_FILES['product_images'], 'path'=>'../content/uploads/products', 'ext'=>'jpeg|jpg|png|gif', 'fieldname'=>'image_name_2', 'arrindex'=>'product_images'];
    				$productImg2 = $this->pktlib->upload_multiple_file($productImageFileValidationParams2);
    				//print_r($productImg2);

    				if(!$productImg2) {
    					$msg = array('message' => "Some error occured with file", 'class'=>'alert alert-danger');
    					$this->session->set_flashdata('message', $msg);	
    				}
 				}
 				//echo '<pre>';print_r($this->input->post('product_images'));exit;
 			foreach ($this->input->post('product_images') as $imageKey => $value) {
 				//echo $imageKey;
 				//print_r($value);
 				
				if($value['type'] == 'image'){

	 				if(!empty($productImg1['filename'][$imageKey])) {
	 					$value['image_name_1'] = $productImg1['filename'][$imageKey];
	 				}else {
	 					$value['image_name_1'] = $value['image_name_1_2'];
	 				}

	 				if(!empty($productImg2['filename'][$imageKey])) {
			 			$value['image_name_2'] = $productImg2['filename'][$imageKey];	
			 		}else {
			 			$value['image_name_2'] = $value['image_name_2_2'];
			 		}
				}else{
					 			$value['image_name_1'] = $value['video'];
					 			$value['image_name_2'] = '';

					 		}
				unset($value['video']);

 				/*if(!empty($productImg2['filename'][$imageKey])) {
 					$_POST['product_images'][$imageKey]['image_name_2'] = $productImg2['filename'][$imageKey];
 				}else {
 					$_POST['product_images'][$imageKey]['image_name_2'] = $_POST['product_images'][$imageKey]['image_name_2_2'];
 				}*/
 				if(isset($value['featured_image'])) {
					$value['featured_image']= true;
				}else {
					$value['featured_image']= false;
				}
				if(isset($value['is_active'])) {
					$value['is_active']= true;
				}else {
					$value['is_active']= false;
				}
 				$value['product_id'] = $productId;

 				unset($value['image_name_1_2']);

 				unset($value['image_name_2_2']);
 				unset($value['video']);
 				$value['modified'] = date('Y-m-d H:i:s');
 				if(isset($value['id']) && !empty($value['id'])) {
 					$update[] = $value;
 					//print_r($update);
 				}else {
 					unset($value['id']);
 					$value['created'] = date('Y-m-d H:i:s');
 					$insert[] = $value;
 				}
 			}
 			/*echo '<pre>';print_r($update);
 			print_r($insert);
 			exit;*/
 			if(!empty($update)) {
 				//$this->pktdblib->set_table('product_images');
 				//$query = $this->pktdblib->update_multiple('product_id',$update);
				$this->update_multiple_product_images($update);
 			}

 			if(!empty($insert)) {
 				//$this->insert_multiple_product_images($insert);
 				$this->pktdblib->set_table('product_images');
 				$query = $this->pktdblib->_insert_multiple($insert);
 			}
 			$msg = array('message' => 'Data updated Successfully' ,'class'=>'alert alert-success');
			$this->session->set_flashdata('message', $msg);
			redirect('products/admin_edit/'.$productId."?tab=product_images");

 		}
 		$data['id'] = $productId;
 		$data['product_images'] = $this->get_product_image_details($productId);
        $data['option']['type'] = ['Select Type', 'image'=>'image', 'video'=>'video'];

 		//print_r($data['product_images']);
 		$this->load->view('products/admin_edit_product_images', $data);
    }

	function get_product_details($id) {
		$this->product_model->set_table('products');
		$product = $this->product_model->get_where($id);
		return $product;
	}

	function get_product_details_ajax($id) {
		$this->product_model->set_table('products');
		$product = $this->product_model->get_where($id);
		echo json_encode($product);
		exit;
	}

	function product_wise_product_image_listing($data = []){
		$condition = '';
			$condition['product_images.product_id'] = $data['product_images.productid'];

		if(isset($data['condition']))
			$condition = $data['condition'];
		$this->product_model->set_table('product_images');
		$productImage = $this->product_model->get_product_image_list($condition);
		return $productImage;
	}

	function get_product_image_details($productId) {

		$this->product_model->set_table('product_images');
		$productImage = $this->product_model->get_where_custom('product_id', $productId);
		return $productImage->result_array();
	}


	function get_product_categories_list() {
		$this->product_model->set_table('product_categories');
		$productCategories = $this->product_model->get_product_category_list();
		return $productCategories;
	}

	function admin_add(){
		if($_SERVER['REQUEST_METHOD']=='POST') {
			$data['values_posted'] = $this->input->post('data');
			//echo '<pre>';print_r($this->input->post());exit;
			if(!$_SESSION['application']['product_has_multiple_categories']){
			    $this->form_validation->set_rules('data[products][product_category_id]', 'category');
			}
			$this->form_validation->set_rules('data[products][product]', 'product', 'required|max_length[255]');
			$this->form_validation->set_rules('data[products][product_type]', 'product type', 'max_length[6]');
			//$this->form_validation->set_rules('data[products][slug]', 'Slug', 'required|is_unique[products.slug]');
			$this->form_validation->set_rules('data[products][product_code]', 'product code');
			$this->form_validation->set_rules('data[products][base_price]', 'base_price', 'required|max_length[255]|numeric');
			if($this->form_validation->run('products')!== false) {
				$error=[];
				$productImg1 = [];
				$productImg2 = [];
				/*echo '<pre>';
				print_r($this->input->post('product_images'));
				print_r($_FILES);
				echo '</pre>';exit;*/
				if(!empty($_FILES['product_images']['name'])) {
					/*echo '<pre>';
					print_r($_FILES['product_images']);*/
					$productValidationParams1 = ['file'=>$_FILES['product_images'], 'path'=>'../content/uploads/products/', 'fieldname'=>'image_name_1', 'ext'=>'jpg|jpeg|gif|png|PNG|JPG', 'arrindex'=>'product_images', 'thumb'=>['path'=>'../content/uploads/products/thumbs/', 'folder'=>'products']];
					$productImg1 = $this->pktlib->upload_multiple_file($productValidationParams1);
					/*echo '<pre>';
					print_r($productImg1);
					exit;*/
					if(!empty($productImg1['error'])) {
						 $msg = array('message' => 'Some Error occured with File 1'.$productImg1['error']['product_images'][0]['error'],'class' => 'alert alert-danger fade in');
                    	$this->session->set_flashdata('message',$msg);
                    	//redirect(custom_constants::new_product_url);
					}
					/*echo '<pre>';
					print_r($_FILES['product_images']);exit;*/
					if(isset($_FILES['product_images'][0]['image_name_2'])){

						$productValidationParams2 = ['file'=>$_FILES['product_images'], 'path'=>'../content/uploads/products/', 'fieldname'=>'image_name_2', 'ext'=>'jpg|jpeg|gif|png', 'arrindex'=>'product_images', 'thumb'=>['path'=>'../content/uploads/products/thumbs/', 'folder'=>'products']];
						$productImg2 = $this->pktlib->upload_multiple_file($productValidationParams2);
						//print_r($productImg2);exit;
						if(!empty($productImg2['error'])) {
							$msg = array('message' => 'Some Error occured with File 2'.$productImg2['error']['product_images'][0]['error'],'class' => 'alert alert-danger fade in');
		                    $this->session->set_flashdata('message',$msg);
						}
					}

				} 
					$postData = $this->input->post('data[products]');
					$postData['created'] = date('Y-m-d H:i:s');
					$postData['modified'] = date('Y-m-d H:i:s');
					$config = array(
	                    'table'         => 'products',
	                    'id'            => 'id',
	                    'field'         => 'slug',
	                    'title'         => 'product',
	                    'replacement'   => 'dash' // Either dash or underscore
	                );
	                $this->load->library('slug', $config);
					$postData['slug'] = $this->slug->create_uri(array('product' => $this->input->post('data[products][product]')), '', '');
					if(isset($postData['overall_stock_mgmt']))
						$postData['overall_stock_mgmt'] = true;
					else
						$postData['overall_stock_mgmt'] = false;
					$regProduct = json_decode($this->register_products($postData),true);
					 if($regProduct['status']==='success') {
					 	if(isset($_SESSION['application']['multiple_company']) && $_SESSION['application']['multiple_company']!=1 && NULL!==($this->input->post("data[companies_products][company_id]"))){
						 	$companyProduct = [];
						 	foreach ($this->input->post("data[companies_products][company_id]") as $key => $value) {
						 		$companyProduct[$key]['company_id'] = $value;
						 	$companyProduct[$key]['product_id'] = $regProduct['id'];
						 	$companyProduct[$key]['is_active'] = true;
						 	$companyProduct[$key]['created'] = $companyProduct[$key]['modified'] = date('Y-m-d H:i:s');
						 	}
						 	if(!empty($companyProduct)){
						 		$this->pktdblib->set_table("companies_products");
						 		$this->pktdblib->_insert_multiple($companyProduct);
						 	}
						 }
						if(($_SESSION['application']['product_has_multiple_brand']) && NULL!==($this->input->post("data[brand_products][brand_id]"))){
						 	$brandProduct = [];
						 	foreach ($this->input->post("data[brand_products][brand_id]") as $key => $value) {
						 		$brandProduct[$key]['brand_id'] = $value;
						 	$brandProduct[$key]['product_id'] = $regProduct['id'];
						 	$brandProduct[$key]['is_active'] = true;
						 	$brandProduct[$key]['created'] = $brandProduct[$key]['modified'] = date('Y-m-d H:i:s');
						 	}
						 	if(!empty($brandProduct)){
						 		$this->pktdblib->set_table("brand_products");
						 		$this->pktdblib->_insert_multiple($brandProduct);
						 	}
						}
						
						if(($_SESSION['application']['product_has_multiple_categories']) && NULL!==($this->input->post("data[product_product_categories][product_category_id]"))){
						 	$productProductCategories = [];
						 	foreach ($this->input->post("data[product_product_categories][product_category_id]") as $key => $value) {
						 		$productProductCategories[$key]['product_category_id'] = $value;
						 	    $productProductCategories[$key]['product_id'] = $regProduct['id'];
						 	}
						 	if(!empty($productProductCategories)){
						 		$this->pktdblib->set_table("product_product_categories");
						 		$this->pktdblib->_insert_multiple($productProductCategories);
						 	}
						}
					 	
					 	$insert = [];
					 	/*echo '<pre>';
					 	print_r($_POST);*/
					 	if(NULL!==($this->input->post('pack_products'))){
					 		//print_r($regProduct);
					 		$data['values_posted']['pack_products'] = $this->input->post('pack_products');
						 	foreach ($this->input->post('pack_products') as $packKey => $value) {
						 		if($value['product_id']==0){
						 			unset($data['values_posted']['pack_products'][$packKey]);
						 			continue;
						 		}
						 		$data['values_posted']['pack_products'][$packKey]['basket_id'] = $regProduct['id'];
						 		$data['values_posted']['pack_products'][$packKey]['created'] = date('Y-m-d H:i:s');
						 		$data['values_posted']['pack_products'][$packKey]['created_by'] = $this->session->userdata('user_id'); 
						 	}
						 	if(count($data['values_posted']['pack_products'])>0){
						 		$insert = $data['values_posted']['pack_products'];
						 		//print_r($insert);
						 		if(!empty($insert)) {
						 			$this->product_model->set_table("pack_products");
						 			$this->product_model->_insert_multiple($insert);
						 			//$msg = array('message'=>'Pack products Added Successfully.','class'=>'alert alert-success' );
						 			//$this->session->set_flashdata('message', $msg);
						 		}
						 	}
					 	}

					 	$productImage = [];
					 	
					 	foreach($this->input->post('product_images') as $imageKey => $image) {
					 		
					 		if($image['type']=='image'){

						 		$image['image_name_1'] = $productImg1['filename'][$imageKey];
						 		if(empty($productImg2['filename'][$imageKey])) {
						 		$image['image_name_2'] = $productImg1['filename'][$imageKey];
						 		}else {
						 			$image['image_name_2'] = $productImg2['filename'][$imageKey];	
						 		}
					 		}else{
					 			$image['image_name_1'] = $image['video'];
					 			$image['image_name_2'] = '';

					 		}
					 			unset($image['video']);
						 		$image['product_id'] = $regProduct['id']; 
						 		$image['created'] = $image['modified'] = date('Y-m-d H:i:s'); 
						 		if(isset($image['featured_image'])) {
						 			$image['featured_image'] = true;
						 		}else{
						 			$image['featured_image'] = false;
						 		}

						 		if(isset($image['is_active'])) {
						 			$image['is_active'] = true;
						 		}else{
						 			$image['is_active'] = false;
						 		}
						 		$productImage[] = $image;
					 		//}
						}
					//echo '<pre>';print_r($productImage);exit;
						if(!empty($productImage)) {
							$this->pktdblib->set_table('product_images');
							$query = $this->pktdblib->_insert_multiple($productImage);
					 	}
					 	//exit;
					 	$msg = array('message'=>'Product Added Successfully. Product Id : '.$regProduct['id'], 'class'=>'alert alert-success' );
					 	$this->session->set_flashdata('message', $msg);
					 	redirect('products/editproduct/'.$regProduct['id']); 
					 	
					 }
					 else {
					 	$msg = array('message'=>'Failed to add products', 'class'=>'alert alert-danger' );
					 	$this->session->set_flashdata('message', $msg);
					 }				
			} else {
				$msg = array('message'=>'error occured while adding products'.validation_errors(), 'class'=>'alert alert-danger' );
					 	$this->session->set_flashdata('message', $msg);
			}
		}
		$data['option']['company'] = json_decode(Modules::run('companies/get_dropdown_list'), true);
		$this->pktdblib->set_table('manufacturing_brands');
		$brands = $this->pktdblib->get_where_custom('is_active', true);
		$data['brands'] = $brands->result_array();
		$data['option']['brand'][0] = 'Select brand';
		foreach ($data['brands'] as $key => $brand) {
		$data['option']['brand'][$brand['id']] = $brand['brand'];
		}

		/*$data['categories'] = $this->product_model->get_categorylist_for_product();
		if(count($data['categories'])>1)
		$data['option']['category'][0] = 'Select Category';
		foreach($data['categories'] as $categoryKey => $category){
			
			$data['option']['category'][$category['id']] = $category['category_name'];
		}*/
		$data['option']['category'] = $this->parentCategories(NULL!==$this->session->userdata('access_to_company')?$this->session->userdata('access_to_company'):[], 0, $level='', $result = [0=>'Select']);
		/*echo '<pre>';
		print_r($data['option']['category']);exit;*/
		$data['products'] = $this->product_model->get_active_list();
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}

		$this->pktdblib->set_table('attributes');
        $attributes = $this->pktdblib->get_where_custom('is_active', true);
        $data['attributes'] = $attributes->result_array();
        $data['option']['attributes'][] = 'Select base Uom';
        foreach ($data['attributes'] as $key => $attribute) {
            $data['option']['attributes'][$attribute['unit'].' '.$attribute['uom']] = $attribute['unit'].' '.$attribute['uom'];
        }
		$data['option']['type'] = ['Select Type', 'image'=>'image', 'video'=>'video'];
		$data['modules'][] = 'products';
		$data['methods'][]= 'admin_add_product';
		$data['product_type'] = $this->get_product_type();
		$data['title'] = 'Add Products';
		$data['meta_title'] = 'Add Products';
		$data['meta_description'] = 'Add Products';
		$data['js'][] = '<script type="text/javascript">
			CKEDITOR.replace("description",{
                height:400,
                filebrowserUploadUrl:assets_url+"admin_lte/plugins/ckeditor_full/fileupload.php",
            });
            
            $(document).on("submit", "#new_product", function(){
              $(".editor1").val(CKEDITOR.instances.editor1.getData());
              $(".editor1").show();
            });
        </script>';
		$data['js'][] = '<script type="text/javascript">
            $(document).on("change", ".type", function() {
            	var trid = $(this).closest("tr").attr("id");
            	//console.log(trid);
            	//console.log(this.value);
			    if (this.value == "image")
			    {
			     console.log("type is image");
			      $("#image_name_1_"+trid).show();
			      $("#featured_image_video_"+trid).hide();
			      
			    }
			    else
			    {
			     console.log("type is video");
			      $("#image_name_1_"+trid).hide();
			      $("#featured_image_video_"+trid).show();
			    }
  			});
    	
        </script>';
		echo Modules::run('templates/admin_template', $data);
	}

	
	function register_products($data) {
		$insert_data = $data;
		$this->product_model->set_table('products');
		$result = $this->product_model->_insert($insert_data);
		if($result['status'] == 'success'){
			if(empty($data['product_code'])){
				$productCode = $this->create_product_code($result['id']);
				$updArr['id'] = $result['id'];
				$updArr['product_code'] = $productCode;
				$updCode = $this->edit_product($result['id'], $updArr);
			}
			$product = $this->get_product_details($result['id']);
			return json_encode(['message'=>'Products Addded Successfully', 'status'=>'success', 'id'=>$result['id'], 'products'=>$product]);
		}else{
			return json_encode(['message'=>'Some Error Occurred', 'status'=>'success', 'id'=>$result['id']]);
		}
	}

	function edit_product($id=NULL, $post_data = []) {
		if(NULL == $id)
			return false;
		$this->product_model->set_table('products');
		if($this->product_model->_update($id,$post_data)){
			return true;
		}
		else
			return false;
	}

	function admin_add_product() {
		$this->load->view('products/admin_add');
	}

	function get_product_category_list() {
		$this->product_model->set_table('products');
		$query = $this->product_model->get_product_category_list();
		//print_r($query);
		return $query;
	}

	function get_productwise_images($productId){
		$this->product_model->set_table('product_images');
		$images = $this->product_model->get_where_custom('product_id', $productId);
		return $images->result_array();
	}


	function checkCategory_is_parent($categoryId){
		$this->product_model->set_table('product_categories');
		$categories = $this->product_model->get_where_custom('parent_id',$categoryId);
		$category = $categories->result_array();
		return count($category);
	}

	function get_categorylist($parentSlug = '') {
		$parentId = 1;
		$category = $this->id_wise_category($parentId);
		$breadCrumb[0] = ['url'=>'/', 'title'=>'Home'];
		if('' !== $parentSlug){ 
			$category = $this->get_slugwise_category($parentSlug);
			$parentId = $category['id'];
			$backTraverseCategory = $this->backTraverse_category($category['parent_id']);
			
		}

		$this->product_model->set_table('product_categories');
		$query = $this->product_model->get_where_custom('parent_id', $parentId);
		$data['categories'] = $query->result_array();

		foreach ($data['categories'] as $catKey => $cat) {
			$childCategory = $this->product_model->get_where_custom('parent_id', $cat['id']);
			$childCategory = $childCategory->result_array();
			if(count($childCategory)>0)
				$data['categories'][$catKey]['is_parent'] = true;
			else
				$data['categories'][$catKey]['is_parent'] = false;

		}
		$data['category'] = $category;
		$data['content'] = 'products/categorylist2';
		$data['title'] = $category['category_name'];
		$data['meta_title'] = $category['meta_title'];
		$data['meta_description'] = $category['meta_description'];
		$data['meta_keyword'] = $category['meta_keyword'];
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>ucfirst($category['category_name'])]
		];
		//echo $category['slug'];exit;
		$data['products'] = Modules::run("products/left_get_categorywise_product", $category['slug']);
		echo Modules::run('templates/obaju_inner_template', $data);

	}

	function insert_multiple_product_images($data) {
		 $this->product_model->set_table("product_images");
		$query = $this->product_model->_insert_multiple($data);
		return $query;
	}

	function update_multiple_product_images($data) {
		//echo "reched in update_multiple_product_images";
		$this->product_model->set_table("product_images");
		$query = $this->product_model->update_multiple('id',$data);
		return $query;
	}

	function category_wise_product_listing($data = []){
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		$this->product_model->set_table('products');
		$res = $this->product_model->get_product_list($condition);
		return $res;
	}

	function admin_category_index(){
		$data['meta_title'] = 'edit employees';
		$data['meta_description'] = 'edit employees';
		$data['modules'][] = 'products';
		$data['methods'][] = 'admin_category_listing';
		
		echo Modules::run("templates/admin_template", $data); 	
	}

	function admin_category_listing($data = []) {
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		$this->product_model->set_table('product_categories');
		$data['categories'] = $this->product_model->get_category_list();
		$this->load->view("products/admin_category_listing", $data);
	}

	function get_product_type(){
		$query = ['Select Product Type','Product', 'Service', 'Product & Services']; 
		return $query;
	}

	function admin_view($id=NULL){
		if(NULL==$id){
			redirect('product/admin_product_index');
		}
		$this->product_model->set_table('products');
		$product = $this->product_model->get_where_product($id);
		$data['product'] = $product;
		$data['content'] = 'products/admin_view_product';
		$data['meta_title'] = 'Products';
		$data['meta_description'] = 'Products';
		$productListData = ['condition'=>['product_images.productid'=>$id],'module'=>'products'];
		$data['productImage'] = Modules::run("products/admin_product_image_listing", $productListData);
		echo modules::run('templates/admin_template', $data);
	}

	function admin_view_category($id=NULL) {
		if(NULL==$id) {
			redirect('products/admin_category_index');
		}
		$this->product_model->set_table('product_categories');
		$category = $this->product_model->get_where($id);
		$data['product_category'] = $category;
		$data['content'] = 'products/admin_view_category';
		$data['meta_title'] = 'products';
		$data['meta_description'] = 'products';
		$productListData = ['condition'=>['products.product_category_id'=>$id], 'module'=>'products'];
		$data['categoryWiseProducts'] = Modules::run("products/admin_product_listing", $productListData);

		echo modules::run('templates/admin_template', $data);
	}

	function get_product_list($data = []) {
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		$this->product_model->set_table('products');
		$res = $this->product_model->get_product_details($condition);
		return $res;
	}

	function getProductWisePackProduct() {
		if(!$this->input->post('params'))
			return;
		$condition = [];
		$condition['pack_products.is_active'] = TRUE;
		$basketId = $this->input->post('params');
		if(!empty($basketId)) {
			$condition['pack_products.basket_id'] = $basketId;
		}
		$this->product_model->set_table("pack_products");
		$productWisePackProducts = $this->product_model->get_product_wise_pack_product($condition);
		$packProductList = [0=>['id' => 0, 'text' => 'Select ']];
		foreach ($productWisePackProducts as $key => $packProduct) {
			$packProductList[$key+1]['id'] = $packProduct['id'];
			$packProductList[$key+1]['text'] = $packProduct['product'];
		}
		echo json_encode($packProductList);
		exit;
	}

	function get_product_detail_ajax($productId) {
		$this->product_model->set_table('products');
		$product = $this->product_model->get_product_details(['products.id'=>$productId]);
		echo json_encode($product);
		exit;
	}

	function create_product_code($productId) {
		
		$productCode = "P";
		if($productId>0 && $productId<=9)
			$productCode .= '000000';
			
		elseif($productId>=10 && $productId<=99)
			$productCode .= '00000';
		elseif($productId>=100 && $productId<=999)
			$productCode .= '0000';
		elseif($productId>=1000 && $productId<=9999)
			$productCode .= '000';
		elseif($productId>=10000 && $productId<=99999)
			$productCode .= '00';
		elseif($productId>=100000 && $productId<=999999)
			$productCode .= '0';

		$productCode .= $productId;
		return $productCode;
	}

	function createThumbnail(){
    	
    	$this->pktlib->createThumbs("../content/uploads/products/", "../content/uploads/products/thumbs/", 300, 'products');
    }

    function product_variation($productId){

    	if($_SERVER['REQUEST_METHOD'] == 'POST'){
    		$data['values_posted'] = $this->input->post('data[product_variations][variation_id]');
    		$insert = [];
    		foreach ($data['values_posted'] as $key => $value) {
    			$insert[$key]['product_id'] = $productId;
    			$insert[$key]['variation_id'] = $value;
    			$insert[$key]['created'] = $insert[$key]['modified'] = date('Y-m-d H:i:s');
    		}
    		if(!empty($insert)){
	    		$this->pktdblib->set_table("product_variations");
	    		$this->pktdblib->_delete_by_column('product_id', $productId);
    			$this->pktdblib->_insert_multiple($insert);
    			$msg = array('message'=>'Product Variation Created Successfully', 'class'=>'alert alert-success' );
				$this->session->set_flashdata('message', $msg);
    			redirect(custom_constants::edit_product_url."/".$productId);
    		}
    	}
		$data['id'] = $productId;
		$productVariations = $this->pktdblib->custom_query("select * from product_variations where is_active= true and product_id=".$productId);
		$variationArray = [];
		foreach ($productVariations as $productVariationKey => $productVariationValue) {
			$variationArray[$productVariationKey] = $productVariationValue['variation_id'];
		}
		$data['productVariations'] = $variationArray;
		$variations = $this->pktdblib->custom_query("select * from variations where is_active = true order by name asc");
		$data['option']['variation'][0] = "Select Name";
		$nameVariation = [];
		foreach ($variations as $key => $variation) {
			$nameVariation[$variation['name']][]= $variation;
		}
		$data['nameVariation'] = isset($nameVariation)?$nameVariation:'';	
		$data['values_posted'] = $data['productVariations'];
		$this->load->view('products/admin_edit_product_variations', $data);		
	}

	function getVariationWiseValue() {
		if(!$this->input->post('params'))
			return;

		$condition = [];
		$condition = ['is_active' => TRUE];
		$name = $this->input->post('params');
		if(!empty($variationId)) {
			$condition = ['variation_id'=>$variationId];
		}
		$variationWiseValues = $this->pktdblib->custom_query("select * from variations where name like '".$name."' and is_active= true");
		$List = [0=>['id'=>0, 'text'=>'Select Variation']];
		foreach ($variationWiseValues as $key => $variation) {
			$List[$key+1]['id'] = $variation['id'];
			$List[$key+1]['text'] = $variation['value'].($name=='color')?'<span style="background:'.$variation['value'].'">&nbsp;</span>':'';
		}
		echo json_encode($List);
	}

	function getCompanyWiseProductCategories(){
		//$_POST['params'] = 2;
		echo json_encode("hiii");
		echo  json_encode($this->input->post());exit;
		/*$company = $_POST['params'];
		//$this->input->post('params') = $company;
		//echo $company;exit;
		if(!$this->input->post('params'))
			return;

		$condition = [];
		$condition = ['companies_product_categories.is_active' => TRUE];
		$companyId = $this->input->post('params');
		//echo $name;exit;
		if(!empty($variationId)) {
			$condition = ['company_id'=>$companyId];
		}

		$companyWiseProductCategory = $this->parentCategories($companyId);
		echo '<pre>';print_r($companyWiseProductCategory);exit;
		//$companyWiseProductCategory = $this->product_model->get_country_wise_state($condition);
		$List = [0=>['id'=>0, 'text'=>'Select Product Category']];

		//print_r(json_encode($countryWiseStates));exit;
		foreach ($companyWiseProductCategory as $key => $category) {
			
			$List[$key+1]['id'] = $category['company_id'];
			$List[$key+1]['text'] = $category['product_category_id'];
		}//exit;
		//echo '<pre>';print_r($List);exit;
		
		echo json_encode($List);*/
	}

	function parentCategories($companyId = [], $parent = 0, $level='', $result = [0=>'Select']){
        $sql = "select product_categories.id, product_categories.category_name from product_categories where is_active=true and parent_id=".$parent;
        /*echo '<pre>';
        print_r($this->session->userdata());
        exit;*/
        /*echo '<pre>';
        print_r($this->session->userdata());*/
        if($this->session->userdata('application')['multiple_company'] && !empty($companyId) && !in_array(1, $this->session->userdata('roles'))){
        	$sql.=' and id in (Select company_id from companies_product_categories where company_id in ('.implode(',', $companyId).'))';
        }
        //echo $sql;
        $parents = $this->pktdblib->custom_query($sql);
        //print_r($parents);
        foreach ($parents as $key => $parent) {
            $result[$parent['id']] = ucfirst($parent['category_name']); 
            $result = $this->childCategories($parent['id'],'--', $result);
        }

        return $result;

    }

    function childCategories($parent, $level='--', $result){
        $childs = $this->pktdblib->custom_query('Select * from product_categories where is_active=true and parent_id='.$parent.' order by category_name ASC');
        foreach ($childs as $key => $child) {
            $result[$child['id']] = $level.ucfirst($child['category_name']); 
            $result = $this->childCategories($child['id'], '--'.$level, $result);
        }
       return $result;
    }

    function customer_products($customerId){
    	//echo $customerId;exit;
    	if($_SERVER['REQUEST_METHOD'] == 'POST'){
    		//echo '<pre>';
    		//print_r($this->input->post());exit;
    		$insert = [];
    		$update = [];
    		//echo '<pre>';
    		foreach ($this->input->post('customer_services') as $key => $value) {
    			//print_r($value);
    			$value['customer_id'] = $customerId;
    			$value['is_active'] = true;
    			$value['modified'] = date("Y-m-d H:i:s");
    			if(isset($value['id']) && !empty($value['id'])) {
    				//echo "in update";
						$update[] = $value;
					}else {
						//echo "in inert";
						unset($value['id']);
	 					$value['created'] = date('Y-m-d H:i:s');
	 					$insert[] = $value;
					}
    		}
    		//exit;
    		/*echo '<pre>';print_r($insert);
    		print_r($update);exit;*/
    		if(!empty($insert)){
    			$this->pktdblib->set_table('customer_services');
    			$this->pktdblib->_insert_multiple($insert);
    			$msg = array('message'=>'Products Added Successfully', 'class'=>'alert alert-success', 'status'=>true);
    		}
    		if(!empty($update)){
    			$this->pktdblib->set_table('customer_services');
    			$this->pktdblib->update_multiple('id',$update);
    			$msg = array('message'=>'Products Updated Successfully', 'class'=>'alert alert-success', 'status'=>true);
    		}
    		if($this->input->is_ajax_request()){
    			echo json_encode($msg);
    			exit;
    		}else{
    			$this->session->set_flashdata('message', $msg);
    			redirect('products/customer_products/'.$customerId);
    		}
    	}
    	$sql = 'select cs.* from customer_services cs where cs.customer_id='.$customerId;
    	$data['values_posted']['customer_services'] = $this->pktdblib->custom_query($sql);
    	//echo '<pre>';print_r($data['values_posted']['customer_services']);exit;
    	foreach ($data['values_posted']['customer_services'] as $detailKey => $detail) {
                $productId[] = $detail['product_id'];
                $data['option']['variation'][$detail['id']] = [];
                if($detail['variation_id']!=0){
                    $variation = $this->pktdblib->custom_query('Select concat(v.name," ", v.value) as variation, v.id from variations v where v.id='.$detail['variation_id']);
                    //print_r($variation);
                    $data['option']['variation'][$detail['id']][$variation[0]['id']] = $variation[0]['variation'];
                }
                
            }//exit;
    	//echo '<pre>';
    	//print_r($data['values_posted']['customer_services']);//exit;
    	$data['id'] = $customerId;
    	$this->pktdblib->set_table('user_roles');
    	$condition = [];
    	$condition['account_type'] = 'customers';
    	$condition['user_id'] = $customerId;
    	$customer = $this->pktdblib->get_condition_wise_data($condition);
    	if(count($customer)>0){
    		$sql = 'select * from address where user_id='.$customer['login_id'].' and type="login"';
    	}else{
    		$sql = 'select * from address where user_id='.$customerId.' and type="customers"';
    	}
    	$address = $this->pktdblib->custom_query($sql);
    	$data['option']['address'][0] = 'Select Address';
    	foreach ($address as $key => $value) {
    		$data['option']['address'][$value['id']] = $value['site_name']." - ".$value['pincode'];
    	}
    	
    	$this->pktdblib->set_table('manufacturing_brands');
        $brands= $this->pktdblib->get_where_custom('is_active', true);
        $data['brands'] = $brands->result_array();
        $data['option']['brand'][0] = 'Select Brand';
        foreach ($data['brands'] as $brandKey => $brand) {
            $data['option']['brand'][$brand['id']] = $brand['brand'];
        }
        $data['option']['services'] = Modules::run('products/get_service_list_dropdown');

        $data['warranty'] = Modules::run('customer_sites/get_product_warranty');
    	$data['title'] = 'Customer Service';
        $data['meta_title'] = 'Customer Service';
        $data['meta_description'] = 'Customer Service';
        $data['meta_keyword'] = 'Customer Service';
        $data['modules'][] = 'products';
        $data['methods'][] = 'customer_product_form';

        echo Modules::run('templates/admin_template', $data);
    }

    function customer_product_form(){
    	$this->load->view('products/customer_products');
    }

    function admin_customer_product_listing(/*$data = []*/$customerId){
    	//echo $customerId;//exit;
    	$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		//echo "string"; exit;
		//print_r($condition);
		//$data['address'] = $this->address_model->get_address_list($condition);
		/*print_r($data);
		exit;*/
		$sql = 'select cs.*, concat(c.first_name," ", c.middle_name," ", c.surname) as customer_name, p.product, mb.brand, concat(v.name," ", v.value) as variation, concat(a.site_name," - ", a.pincode) as installation_address, a.id as address_id, ccs.call_log_id, ccs.customer_service_id from customer_services cs inner join customers c on cs.customer_id=c.id left join manufacturing_brands mb on cs.manufacturing_brand_id=mb.id left join products p on cs.product_id=p.id left join variations v on cs.variation_id=v.id left join address a on cs.installation_address_id=a.id  left join call_customer_services ccs on cs.id=ccs.customer_service_id where cs.customer_id='.$customerId;
		$data['products'] = $this->pktdblib->custom_query($sql);
		//echo '<pre>';print_r($data['products']);exit;
		$this->load->view("products/admin_customer_product_listing", $data);
    }

    function admin_add_customer_products($customerId){
    	//echo $customerId;exit;
    	if($_SERVER['REQUEST_METHOD'] == 'POST'){
    		/*echo '<pre>';
    		print_r($this->input->post());exit;*/
    		$insert = [];
    		$update = [];
    		//echo '<pre>';
    		foreach ($this->input->post('customer_services') as $key => $value) {
    			//print_r($value);
    			$value['customer_id'] = $customerId;
    			$value['is_active'] = true;
    			$value['modified'] = date("Y-m-d H:i:s");
    			if(isset($value['id']) && !empty($value['id'])) {
    				//echo "in update";
						$update[] = $value;
					}else {
						//echo "in inert";
						unset($value['id']);
	 					$value['created'] = date('Y-m-d H:i:s');
	 					$insert[] = $value;
					}
    		}
    		//exit;
    		/*echo '<pre>';print_r($insert);
    		print_r($update);exit;*/
    		if(!empty($insert)){
    			$this->pktdblib->set_table('customer_services');
    			$this->pktdblib->_insert_multiple($insert);
    			$msg = array('message'=>'Products Added Successfully', 'class'=>'alert alert-success', 'status'=>true);
    		}
    		if(!empty($update)){
    			$this->pktdblib->set_table('customer_services');
    			$this->pktdblib->update_multiple('id',$update);
    			$msg = array('message'=>'Products Updated Successfully', 'class'=>'alert alert-success', 'status'=>true);
    		}
    		if($this->input->is_ajax_request()){
    			echo json_encode($msg);
    			exit;
    		}else{
    			$this->session->set_flashdata('message', $msg);
    			redirect('products/customer_products/'.$customerId);
    		}
    	}
    	$sql = 'select cs.* from customer_services cs where cs.customer_id='.$customerId;
    	$data['values_posted']['customer_services'] = $this->pktdblib->custom_query($sql);
    	//echo '<pre>';print_r($data['values_posted']['customer_services']);exit;
    	foreach ($data['values_posted']['customer_services'] as $detailKey => $detail) {
                $productId[] = $detail['product_id'];
                $data['option']['variation'][$detail['id']] = [];
                if($detail['variation_id']!=0){
                    $variation = $this->pktdblib->custom_query('Select concat(v.name," ", v.value) as variation, v.id from variations v where v.id='.$detail['variation_id']);
                    //print_r($variation);
                    $data['option']['variation'][$detail['id']][$variation[0]['id']] = $variation[0]['variation'];
                }
                
            }//exit;
    	//echo '<pre>';
    	//print_r($data['values_posted']['customer_services']);//exit;
    	$data['id'] = $customerId;
    	$this->pktdblib->set_table('user_roles');
    	$condition = [];
    	$condition['account_type'] = 'customers';
    	$condition['user_id'] = $customerId;
    	$customer = $this->pktdblib->get_condition_wise_data($condition);
    	if(count($customer)>0){
    		$sql = 'select * from address where user_id='.$customer['login_id'].' and type="login"';
    	}else{
    		$sql = 'select * from address where user_id='.$customerId.' and type="customers"';
    	}
    	$address = $this->pktdblib->custom_query($sql);
    	$data['option']['address'][0] = 'Select Address';
    	foreach ($address as $key => $value) {
    		$data['option']['address'][$value['id']] = $value['site_name']." - ".$value['pincode'];
    	}
    	
    	$this->pktdblib->set_table('manufacturing_brands');
        $brands= $this->pktdblib->get_where_custom('is_active', true);
        $data['brands'] = $brands->result_array();
        $data['option']['brand'][0] = 'Select Brand';
        foreach ($data['brands'] as $brandKey => $brand) {
            $data['option']['brand'][$brand['id']] = $brand['brand'];
        }
        $data['option']['services'] = Modules::run('products/get_service_list_dropdown');
        //echo '<pre>';print_r($data);exit;

        $data['warranty'] = Modules::run('customer_sites/get_product_warranty');
    	$this->load->view('products/customer_products', $data);
    }

    function admin_edit_product_details($productId) {
 		if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
 			$data['values_posted'] = $_POST; 
			$insert = [];
			$update = [];
			$data['values_posted'] = $this->input->post('data');
		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
				$postData = $this->input->post('data[product_details]');
				$postData['modified'] = date('Y-m-d H:i:s');
				if($postData['id']){
					$update = $postData;
				}else{
					$postData['created'] = date('Y-m-d H:i:s');
					$insert = $postData;
				}
				$flag = false;
				$this->pktdblib->set_table('product_details');
				if(!empty($insert))
					$flag = $this->pktdblib->_insert($postData);

				if(!empty($update))
					$flag = $this->pktdblib->_update($update['id'], $update);

				if($flag){
					$msg = array('message'=>'Product Details updated Successfully', 'class'=>'alert alert-success' );
					$this->session->set_flashdata('message', $msg);
					redirect('products/editproduct/'.$productId.'?tab=product_images');
				}else{
					$msg = array('message'=>'Some Error Occurred.', 'class'=>'alert alert-danger' );
					$this->session->set_flashdata('message', $msg);
					redirect('products/editproduct/'.$productId.'?tab=product_details');
				}
 		}else{
 			$this->pktdblib->set_table('product_details');
 			$productDetails = $this->pktdblib->get_where_custom('product_id', $productId);
 			$data['values_posted']['product_details'] = $productDetails->row_array();
 		}
 		$data['id'] = $productId;
 		$this->load->view('products/admin_edit_product_details', $data);
    }

    function update_stock($companyId=1){
    	if($_SERVER['REQUEST_METHOD'] == 'POST'){
    		//echo '<pre>';
    		foreach ($this->input->post('data[products]') as $key => $product) {
    			if(isset($product['show_on_website']))
    				$_POST['data']['products'][$key]['show_on_website'] = true;
    			else
    				$_POST['data']['products'][$key]['show_on_website'] = false;

    			if(isset($product['is_sale']))
    				$_POST['data']['products'][$key]['is_sale'] = true;
    			else
    				$_POST['data']['products'][$key]['is_sale'] = false;

    		}
    		$insert = [];
    		$update = [];
    		foreach ($this->input->post('data[product_details]') as $detailKey => $detail) {
    			$detail['product_id'] = $_POST['data']['products'][$detailKey]['id'];
    			if(!empty($detail['id']))
    				$update[] = $detail;
    			else{
    				unset($detail['id']);
    				$insert[] = $detail;
    			}
    		}
    		
    		$this->pktdblib->set_table('products');
    		$this->pktdblib->update_multiple('id', $this->input->post('data[products]'));

    		$this->pktdblib->set_table('product_details');
    		if(!empty($insert)){
    			$this->pktdblib->_insert_multiple($insert);
    		}

    		if(!empty($update)){
    			$this->pktdblib->update_multiple('id', $update);
    		}
    		
    		$msg = array('message'=>'Data Updated Successfully', 'class'=>'alert alert-success' );
			$this->session->set_flashdata('message', $msg);
			redirect('products/update_stock/'.$companyId);
    	}
    	$data['meta_title'] = "Products";
		$data['meta_description'] = "Products";
		$condition = [];
		if(TRUE == $this->session->userdata('application')['multiple_company']){
		    $condition['companies_products.company_id'] = $companyId;
		}

		$params['condition'] = $condition;

		$data['products'] = $this->product_model->get_stock_details($params);
		//echo $this->db->last_query();
		$this->pktdblib->set_table('attributes');
        $attributes = $this->pktdblib->get_where_custom('is_active', true);
        $data['attributes'] = $attributes->result_array();
        $data['option']['attributes'][0] = 'Select base Uom';
        foreach ($data['attributes'] as $key => $attribute) {
            $data['option']['attributes'][$attribute['unit'].' '.$attribute['uom']] = $attribute['unit'].' '.$attribute['uom'];
        }
		/*echo '<pre>';
		print_r($data['products']);exit;*/
		$data['content'] = "products/admin_update_stock_form";
		$data['option']['discount_type'] = [''=>'Select Type', 'percentage'=>'Percent', 'value'=>'value'];
		$data['company_id'] = $companyId;
		//$data['methods'][] = "";

		echo modules::run('templates/admin_template', $data);
    }

   function admin_edit_product_attributes($productId) {
    	//echo $productId;//exit;
 		if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
 			//echo '<pre>';print_r($_POST);exit;
 			$data['values_posted'] = $_POST['product_attributes']; 
 			$insert = [];
			$update = [];
			$attributeArray = [];

			$maxId = $this->pktdblib->custom_query('select max(id) as maxid from product_attributes');
			/*print_r($maxId);
			exit;*/
			//echo '<pre>';
 			foreach ($data['values_posted'] as $key => $value) {
 				//print_r($value);
 				if(isset($value['is_active']))
 					$value['is_active'] = TRUE;
 				else
 					$value['is_active'] = FALSE;
 					
 				if(isset($value['is_default']))
 					$value['is_default'] = TRUE;
 				else
 					$value['is_default'] = FALSE;
 				$value['product_id'] = $productId;
 				$value['modified'] = date('Y-m-d H:i:s');
 				if(isset($value['id']) && !empty($value['id'])){
 					$update[] = $value;
 				}else{
 				    unset($value['id']);
 					$value['created'] = date('Y-m-d H:i:s');
 					$value['id'] = $maxId[0]['maxid']+$key+1;
 					$insert[] = $value;
  				}
 			}
 			/*echo '<pre>';
 			print_r($insert);
 			print_r($update);
 			exit;*/
 			if(!empty($update)) {
 				$this->pktdblib->set_table('product_attributes');
				$this->pktdblib->update_multiple('id', $update);
 			}

 			if(!empty($insert)) {
 				$this->pktdblib->set_table('product_attributes');
 				$this->pktdblib->_insert_multiple($insert);
 			}
 			$msg = array('message' => 'Data updated Successfully' ,'class'=>'alert alert-success');
			$this->session->set_flashdata('message', $msg);
			redirect('products/admin_edit/'.$productId."?tab=product_attribute");

 		}
 		$data['id'] = $productId;
 		$this->pktdblib->set_table('products');
 		$data['product'] = $this->pktdblib->get_where($productId);
 		//print_r($data['product']);exit;
 		$this->pktdblib->set_table('product_attributes');
 		$productAttributes = $this->pktdblib->get_where_custom('product_id',$productId);
 		$data['product_attributes'] = $productAttributes->result_array();
 		$data['option']['attribute'][0] = 'Select Attribute';
 		$this->pktdblib->set_table('attributes');
 		$attribute = $this->pktdblib->get_where_custom('is_active', true);
 		$attributes = $attribute->result_array();
 		foreach ($attributes as $attributeKey => $attributeValue) {
 			$data['option']['attribute'][$attributeValue['id']] = $attributeValue['unit']." ".$attributeValue['uom'];
 		}
 		
 		//print_r($data['product_images']);
 		$this->load->view('products/admin_edit_product_attributes', $data);
    }

    function attribute_wise_mrp(){
    	/*$productId = 60;
		$attribute = '50 gm';
		$unit = 1;
		echo "productId=".$productId.'<br/>';
		echo "attribute=".$attribute.'<br/>';
		echo "unit=".$unit.'<br/>';//exit;*/
		$productId = '';
		$attribute = '';
		$unit = '';
		//print_r($this->input->post());exit;
		//echo "reached here";exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo "reached here";exit;
			$productId = $this->input->post('productId');
			$attribute = $this->input->post('attribute');
			$unit = $this->input->post('unit');
		}else{
			$productId = $data['productId'];
			$attribute = $data['attribute'];
			$unit = $data['unit'];
		}
		
		
		//echo "unit2=".$unit;exit;
		$requireUOM = explode(" ", $attribute);
		//print_r($requireUOM);
		//print_r(json_encode($requireUOM[1]));exit;
    	$basePrice = $this->pktdblib->custom_query('select base_uom, base_price from products where id='.$productId);
    	//print_r($basePrice);
    	$requireUOM[1] = trim(strtoupper($requireUOM[1]));
    	$base = explode(" ", $basePrice[0]['base_uom']);
    	//print_r($base);exit;
    	$basePrice[0]['base_uom'] = $base[1];//preg_replace('/\d/', '', trim(strtoupper($basePrice[0]['base_uom'])) );;
    	
    	/*if(trim(strtoupper($requireUOM[1])) !=trim(strtoupper($basePrice[0]['base_uom']))){*/
    	$unit = 1;
    	//echo $requireUOM[1]." ".$basePrice[0]['base_uom'];exit;
    	if(($requireUOM[1])!=($basePrice[0]['base_uom'])){
    	    //echo "reached";
    		$unit = $this->pktlib->unit_convertion($requireUOM[1],$basePrice[0]['base_uom']);
    		/*$conversion = $this->pktlib->unit_convertion($requireUOM[1],$basePrice[0]['base_uom']);
            $afterConversion = $requireUOM[0]*$conversion;
    		echo "unit=".$afterConversion;exit;*/
    	
    	}
    	$basePrice = ($basePrice[0]['base_price']*($unit*$requireUOM[0]))/$base[0];
    	//echo $requireUOM[0]." ".$base[0]." ".$unit." ".($unit*$requireUOM[0]);exit;
    	$priceList = [
    		'base_uom' => $basePrice[0]['base_uom'],'base_price' => $basePrice, 'unit'=>$unit, 'baseUnit'=>$unit*$requireUOM[0]]	;
    	
         //$myJSON = JSON.stringify($basePrice);

    	echo json_encode($priceList);exit;
    }
    
    function product_attribute_list($arr=[]){
	    //print_r($arr);
	    //method was created for Android Application
	    $query = $this->pktdblib->custom_query('SELECT product_attributes.id, CONCAT(attributes.unit, " ",attributes.uom) as uom FROM `product_attributes` INNER JOIN attributes on attributes.id=product_attributes.attribute_id WHERE product_attributes.is_active=true');
	    $productAttribute = [];
	    foreach($query as $key=>$attribute){
	        $productAttribute[$attribute['id']] = strtoupper($attribute['uom']);
	    }
	    
	    return $productAttribute;
	}
	
	function ordered_product_attribute_list($arr=[]){
	    //print_r($arr);
	    //method was created for Android Application
	    $query = $this->pktdblib->custom_query('SELECT product_attributes.id, CONCAT(attributes.unit, " ",attributes.uom) as uom FROM `product_attributes` INNER JOIN attributes on attributes.id=product_attributes.attribute_id WHERE 1=1');
	    $productAttribute = [];
	    foreach($query as $key=>$attribute){
	        $productAttribute[$attribute['id']] = strtoupper($attribute['uom']);
	    }
	    
	    return $productAttribute;
	}

	function upload_product_master_csv_file(){
		/*echo '<pre>';
		print_r($_SESSION);
		exit;*/
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$products = [];
            $error = [];
            //$productCode = '';
            //$productId = '';
            //$brandId = '';
            //$productId = '';
            //$parentCatId = 0;
            //$childCatId = 0;
            if(!empty($_FILES)) {
                $fname = $_FILES['sel_file']['name'];
                $chk_ext = explode('.',$fname);
                if(end($chk_ext)=='xlsx' || end($chk_ext) == 'xls' || end($chk_ext) == 'csv') {
                	$companyProducts = [];
	                $filename = $_FILES['sel_file']['tmp_name'];
	                //print_r($filename);exit;
	                $this->load->library('excel');
	                $objPHPExcel = PHPExcel_IOFactory::load($filename);
	                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
	                //echo "<pre>";print_r($cell_collection);exit;
	                foreach ($cell_collection as $cell) {
	                   $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
	                   $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
	                   $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
	                   if ($row == 1) {
	                       $header[$row][$column] = $data_value;
	                   } else {
	                       $arr_data[$row][$column] = $data_value;
	                   }
	                }
	                //echo '<pre>';print_r($arr_data);exit;
	                $data['header'] = $header;
	                $data['values'] = $arr_data;
	                //echo "<pre>";print_r($data['values']);exit;
	                $productImage = [];
	                foreach ($data['values'] as $xlsxkey => $xlsxvalue) {
	                	//echo $xlsxkey;
	                    foreach ($xlsxvalue as $key => $value) {
	                        $xlsxUploadedData[$xlsxkey-2][] = $value;
	                    }
	                }//exit;
            //echo '<pre>';print_r($_FILES);exit;
	               //echo '<pre>'; print_r($xlsxUploadedData);exit;
	               
	                //echo '<pre>';
	                
	                //echo count($xlsxUploadedData);exit;
	                foreach ($xlsxUploadedData as $xlskey => $xlsvalue) {
	                	/*if($xlskey==0){
	                		continue;
	                	}*/
	                	$parentCatId = 0;
	                	$childCatId = 0;
	                	$productId = 0;
	                	$brands = $this->pktdblib->custom_query('select mb.* from manufacturing_brands mb  where mb.brand like "'.trim(ucfirst($xlsvalue[0])).'"');
	                	//print_r($brands);exit;
	                    if(count($brands)>0){
	                        $brandId = $brands[0]['id'];
	                    	
	                    }else{
	                    	$config = array(
			                    	'table'			=>'manufacturing_brands',
				                    'id'            => 'id',
				                    'field'         => 'slug',
				                    'title'         => 'name',
				                    'replacement'   => 'dash' // Either dash or underscore
			                	);
			                	$this->load->library('slug', $config);
	                    		$brandData['brand'] = trim(strtolower($xlsvalue[0]));
	                    		$brandData['slug']  = 
	                    		$this->slug->create_uri(array('name' => trim(strtolower($xlsvalue[0])), /*$id*/0, ''));
	                    		$brandData['text'] = trim(strtolower($xlsvalue[0]));
	                    		$brandData['is_active'] = true;
	                    		$brandData['created'] = $brandData['modified'] = date('Y-m-d H:i:s');
		                    	$brandData['created_by'] = $brandData['modified_by'] = $this->session->userdata('user_id');
		                    	$this->pktdblib->set_table('manufacturing_brands');
		                    	$brand = $this->pktdblib->_insert($brandData);
		                    	if($brand['status'] == 'success'){
		                    		$brandId = $brand['id'];
		                    		$msg = array('message'=>'Brand Added Successfully', 'class'=>'alert alert-success');
		                    	}else{
		                        	$error[]= "Error Occured at row ".$xlskey;
		                    	}
	                    }

	                    $parentCategory = $this->pktdblib->custom_query('select pc.* from product_categories pc  where pc.category_name="'.trim(strtolower($xlsvalue[1])).'"');
	                    if(count($parentCategory)>0){
	                        $parentCatId = $parentCategory[0]['id'];
	                    	
	                    }else{
	                    	$config = array(
			                    	'table'			=>'product_categories',
				                    'id'            => 'id',
				                    'field'         => 'slug',
				                    'title'         => 'name',
				                    'replacement'   => 'dash' // Either dash or underscore
			                	);
			                	$this->load->library('slug', $config);
	                    		$categoryData['category_name'] = trim(strtolower($xlsvalue[1]));
	                    		$categoryData['slug']  = 
	                    		$this->slug->create_uri(array('name' => trim(strtolower($xlsvalue[1])), /*$id*/1, ''));
	                    		//$categoryData['text'] = trim(ucfirst($xlsvalue[1]));
	                    		$categoryData['parent_id'] = $parentCatId;
	                    		$categoryData['is_active'] = true;
	                    		$categoryData['created'] = $categoryData['modified'] = date('Y-m-d H:i:s');
		                    	$categoryData['created_by'] = $categoryData['modified_by'] = $this->session->userdata('user_id');
		                    	$this->pktdblib->set_table('product_categories');
		                    	$category = $this->pktdblib->_insert($categoryData);
		                    	if($category['status'] == 'success'){
		                    		$parentCatId = $category['id'];
		                    		$msg = array('message'=>'Parent Category Added Successfully', 'class'=>'alert alert-success');
		                    	}else{
		                        	$error[]= "Error Occured at row ".$xlskey;
		                    	}
	                    }

	                    $childCategory = $this->pktdblib->custom_query('select pc.* from product_categories pc  where pc.parent_id='.$parentCatId.' and pc.category_name="'.trim(strtolower($xlsvalue[2])).'"');
	                    //echo $this->db->last_query().'<br>';
	                    if(count($childCategory)>0){
	                        $childCatId = $childCategory[0]['id'];
	                    	
	                    }else{
	                    	$config = array(
			                    	'table'			=>'product_categories',
				                    'id'            => 'id',
				                    'field'         => 'slug',
				                    'title'         => 'name',
				                    'replacement'   => 'dash' // Either dash or underscore
			                	);
			                	$this->load->library('slug', $config);
	                    		$childCategoryData['category_name'] = trim(strtolower($xlsvalue[2]));
	                    		$childCategoryData['slug']  = 
	                    		$this->slug->create_uri(array('name' => trim(strtolower($xlsvalue[2])), /*$id*/1, ''));
	                    		//$childCategoryData['text'] = trim(ucfirst($xlsvalue[1]));
	                    		$childCategoryData['parent_id'] = $parentCatId;
	                    		$childCategoryData['is_active'] = true;
	                    		$childCategoryData['created'] = $childCategoryData['modified'] = date('Y-m-d H:i:s');
		                    	$childCategoryData['created_by'] = $childCategoryData['modified_by'] = $this->session->userdata('user_id');
		                    	$this->pktdblib->set_table('product_categories');
		                    	$childCategory = $this->pktdblib->_insert($childCategoryData);
		                    	if($childCategory['status'] == 'success'){
		                    		$childCategoryId = $childCategory['id'];
		                    		$msg = array('message'=>'Category Added Successfully', 'class'=>'alert alert-success');
		                    	}else{
		                        	$error[]= "Error Occured while adding child category at row ".$xlskey;
		                    	}
	                    }
	                    //echo $childCatId;exit;
	                    $products = $this->pktdblib->custom_query('select p.*, mb.brand from products p inner join brand_products bp on bp.product_id=p.id inner join manufacturing_brands mb on mb.id=bp.brand_id inner join product_categories pc on pc.id=p.product_category_id where  p.product="'.trim(strtolower($xlsvalue[4])).'" and pc.category_name="'.trim(strtolower($xlsvalue[2])).'" and mb.brand="'.trim(strtolower($xlsvalue[0])).'"');
	                    //print_r($products);exit;
	                    if(count($products)>=1){
	                        //$error[]= "Duplicate entry at row ".$xlskey;
	                        $productId = $products[0]['id'];
	                        $productCode = $products[0]['product_code'];
	                    }else{
	                    	$config = array(
			                   	'table'			=>'products',
				                'id'            => 'id',
				            	'field'         => 'slug',
				                'title'         => 'name',
				                'replacement'   => 'dash' // Either dash or underscore
			                );
	                    	$this->load->library('slug', $config);
	                    	$productData['product'] = $productData['meta_title'] = $productData['meta_keyword'] = $productData['meta_description'] = trim(strtolower($xlsvalue[4]));
	                    	$productData['slug']  = $this->slug->create_uri(array('name' => trim(strtolower($xlsvalue[4])), /*$id*/0, ''));
	                    	$productData['product_category_id'] =  $childCatId;
	                    	$productData['product_type'] = 1;
 	                    	$productData['product_code'] = NULL;
 	                    	if($xlsvalue[6] = trim(strtolower('yes'))){
 	                    		$productData['base_price'] = ($xlsvalue[7] == '-')?0:$xlsvalue[7];
 	                    		$productData['base_uom'] = ($xlsvalue[7] == '-')?0:$xlsvalue[5];
 	                    	}
	                    	//$productData['base_price'] = $xlsvalue[3];
	                    	//$productData['base_uom'] = $xlsvalue[4];
	                    	$productData['created'] = $productData['modified'] = date('Y-m-d H:i:s');
	                    	$productData['created_by'] = $productData['modified_by'] = $this->session->userdata('user_id');
	                    	$productData['is_active'] = true;
	                    	$this->pktdblib->set_table('products');
            				$newProduct = json_decode($this->register_products($productData),true);

	                    	if($newProduct['status'] == 'success'){
	                    		if($this->session->userdata('application')['multiple_company'] && !$this->session->userdata('application')['share_products']){
	                    			
	                    			foreach ($this->session->userdata('access_to_company') as $accessKey => $accessVal) {
	                    				$companyProducts[$newProduct['id']]['company_id'] = $accessVal;
	                    				$companyProducts[$newProduct['id']]['product_id'] = $newProduct['id'];
	                    				$companyProducts[$newProduct['id']]['created'] = date('Y-m-d H:i:s');
	                    			}

	                    			$this->pktdblib->set_table('companies_products');
		                    		$companyProductEntry = $this->pktdblib->_insert_multiple($companyProducts);
	                    		}
	                    		$productId = $newProduct['id'];
	                    		$this->pktdblib->set_table('products');
	                    		$productDetail = $this->pktdblib->get_where($productId);
	                    		$productCode = $productDetail['product_code'];
	                    		$brandProductData['brand_id'] = $brandId;
	                    		$brandProductData['product_id'] = $productId;
	                    		$brandProductData['is_active'] = true;
	                    		$brandProductData['created'] = $brandProductData['modified'] = date('Y-m-d H:i:s');
	                    		$brandProductData['created_by'] = $brandProductData['modified_by'] = $this->session->userdata('user_id');
	                    		$this->pktdblib->set_table('brand_products');
	                    		$brandProduct = $this->pktdblib->_insert($brandProductData);
	                    		if($brandProduct['status']=='success'){

	                    			$msg = array('message'=>'Model Added Successfully', 'class'=>'alert alert-success');
	                    		}
	                    	}else{
	                        	$error[]= "Error Occured at row ".$xlskey;
	                    	}
	                    }
	                    $attribute = explode(' ', $xlsvalue[5]);
	                	//echo '<pre>';print_r($xlsvalue[5]);exit;

	                    $attributeDetail = $this->pktdblib->custom_query('select * from attributes where unit='.$attribute[0].' and uom="'.strtolower($attribute[1]).'"');
	                    
	                    if(count($attributeDetail)>=1){
	                        //$error[]= "Duplicate entry at row ".$xlskey;
	                        $attributeId = $attributeDetail[0]['id'];
                    	}else{
	                    	$attributeData['unit'] = $attribute[0];
	                    	$attributeData['uom']  = strtolower($attribute[1]);
	                    	//$productData['base_uom'] = $xlsvalue[4];
	                    	$attributeData['created'] = $attributeData['modified'] = date('Y-m-d H:i:s');
	                    	$attributeData['created_by'] = $attributeData['modified_by'] = $this->session->userdata('user_id');
	                    	$attributeData['is_active'] = true;
	                    	$this->pktdblib->set_table('attributes');
	                    	$newAttribute = $this->pktdblib->_insert($attributeData);
	                    	if($newAttribute['status'] == 'success'){
	                    		$attributeId = $newAttribute['id'];
	                    		$msg = array('message'=>'Model Added Successfully', 'class'=>'alert alert-success');

	                    		
	                    	}else{
	                        	$error[]= "Error Occured while adding attribute at row ".$xlskey;
	                    	}
	                    }

	                    $productAttribute = $this->pktdblib->custom_query('select * from product_attributes where  id='.$xlsvalue[3]);
	                    /*echo 'select * from product_attributes where  id='.$xlsvalue[3].'<br>';exit;*/
	                    if(count($productAttribute)>0){
	                    	if($productAttribute[0]['product_id'] != $productId){
	                    		$error[] = "attribute of productId =".$productAttribute[0]['product_id']." sap code = ".$xlsvalue[3]." productId = ".$productId." Duplicate sap code on row ".$xlskey;
	                    		continue;
	                    	}else{ //echo "reached here";exit;
	                    		$update['product_id'] = $productId;
	                    		$update['attribute_id'] = $attributeId;
	                    		$update['id'] = $xlsvalue[3];
	                    		$update['price'] = ($xlsvalue[7] == '-')?0:$xlsvalue[7]; $xlsvalue[7];
	                    		$update['discount'] = ($xlsvalue[8] == '-')?0:$xlsvalue[8]; 
	                    		//$xlsvalue[8];
	                    		$update['mrp'] = ($xlsvalue[9] == '-')?0:$xlsvalue[9]; 
	                    		//$xlsvalue[9];
	                    		$update['stock_qty'] = ($xlsvalue[10] == '-')?0:$xlsvalue[10];
	                    		//$xlsvalue[10];
	                    		//increase in percent bhi calculate karwa

	                    		$update['modified_by'] = $this->session->userdata('user_id');
	                    		$update['modified'] = date('Y-m-d H:i:s');
	                    		/*echo '<pre>';
	                    		print_r($xlsvalue);
	                    		print_r($update);
	                    		exit;*/
	                    		$this->pktdblib->set_table('product_attributes');
	                    		$this->pktdblib->_update($xlsvalue[3], $update);
	                    		//$this->db->last_query().'<br>';
	                    	}
	                        //$error[]= "Duplicate entry at row ".$xlskey;
	                        //echo "hii";exit;
	                        $productAttributeId = $productAttribute[0]['id'];
                    	}else{
                    		//echo "hello";exit;
                    		if(trim($xlsvalue[6]) == 'yes'){
	                    		$productAttributeData['is_default']  = true;
                    		}elseif (trim($xlsvalue[6]) == 'no') {
	                    		$productAttributeData['is_default']  = false;
                    		}
                    		$productAttributeData['id'] = $xlsvalue[3];
	                    	$productAttributeData['product_id'] = $productId;
	                    	$productAttributeData['attribute_id']  = $attributeId;
	                    	$productAttributeData['price'] = ($xlsvalue[7] == '-')?0:$xlsvalue[7]; 
	                    	$productAttributeData['discount'] = ($xlsvalue[8] == '-')?0:$xlsvalue[8]; 
	                    	$productAttributeData['mrp'] = ($xlsvalue[9] == '-')?0:$xlsvalue[9]; 
	                    	$productAttributeData['stock_qty'] = ($xlsvalue[10] == '-')?0:$xlsvalue[10];
	                    	//$productData['base_uom'] = $xlsvalue[4];
	                    	$productAttributeData['created'] = $productAttributeData['modified'] = date('Y-m-d H:i:s');
	                    	$productAttributeData['created_by'] = $productAttributeData['modified_by'] = $this->session->userdata('user_id');
	                    	$productAttributeData['is_active'] = true;
	                    	$this->pktdblib->set_table('product_attributes');
	                    	$newProductAttribute = $this->pktdblib->_insert($productAttributeData);
	                    	if($newProductAttribute['status'] == 'success'){
	                    		$productAttributeId = $newProductAttribute['id'];
	                    		$msg = array('message'=>'Product Attribute Added Successfully', 'class'=>'alert alert-success');

	                    		
	                    	}else{
	                        	$error[]= "Error Occured while adding product attribute at row ".$xlskey;
	                    	}
	                    }
	                    /*$imageCounter = 5;
	                    for ($i=0; $i < 10; $i++) { 
	                    	if(trim($xlsvalue[$imageCounter]) == '-'){
								continue;
							}else{
		                    	if($i==0){
		                    		$productImage[$xlskey][$i]['featured_image'] = true;
		                    	}else{
		                    		$productImage[$xlskey][$i]['featured_image'] = false;
		                    	}

		                    	$productImage[$xlskey][$i]['image_name_1'] = $xlsvalue[$imageCounter];
		                    	$productImage[$xlskey][$i]['product_id'] = $productId;
		                    	$productImage[$xlskey][$i]['type'] = 'image';
		                    	$productImage[$xlskey][$i]['is_active'] = true;
		                    	$productImage[$xlskey][$i]['created'] = $productImage[$xlskey][$i]['modified'] = date('Y-m-d H:i:s');
		                    	$productImage[$xlskey][$i]['created_by'] = $productImage[$xlskey][$i]['modified_by'] = $this->session->userdata('user_id');
		                    	
							}
							//echo '<pre>';print_r($carImage);
	                    	$imageCounter = $imageCounter +1;
	                    	//echo '<pre>';print_r($carImage[$i]);//exit;
	                    }*/
	                    
	                    
	                }
	                //exit;
            	}
            	if($this->session->userdata('application')['multiple_company'] && !$this->session->userdata('application')['share_products']){
        			$this->pktdblib->set_table('companies_products');
            		$companyProductEntry = $this->pktdblib->_insert_multiple(array_values($companyProducts));
        		}
            	if(empty($error)){
            		//echo "string";
	                 
            		 
		            $this->session->set_flashdata('message', $msg);
            		
            		redirect('products/productmasters');	
	            }else{
	            	//echo "hello";exit;
	            	/*$csv = array('error' => $error, 'class' => 'alert alert-danger');
	            	//echo '<pre>';print_r($csv['error']);exit;
                	$this->session->set_flashdata('error', $csv);
                	redirect('products/productmasters');	*/
                	$msg = array('error' => $error, 'class' => 'alert alert-danger');
                	$this->session->set_flashdata('error',$msg);
	            }
                //redirect('products/productmasters');

            	//echo '<pre>';print_r($cars);exit;
            	
            	//print_r($variations);exit;
        	}
        }
		$data['content'] = 'products/upload_product_master_csv_file';
        $data['meta_title'] = "ERP : Upload Product Masters";
        $data['title'] = "ERP : Upload Product Masters";
        $data['meta_description'] = "ERP :Upload Product Masters";
        echo Modules::run("templates/admin_template", $data);
    }

    function update_product_price_stock(){
    	if($_SERVER['REQUEST_METHOD'] == 'POST') {
    	//echo "hii";print_r($_FILES);//exit;
            $error = [];
            $update  = [];
            if(!empty($_FILES)) {
                $fname = $_FILES['sel_file']['name'];
            //echo $fname;exit;
                $chk_ext = explode('.',$fname);
                if(end($chk_ext)=='xlsx' || end($chk_ext) == 'xls' || end($chk_ext) == 'csv') {
               $filename = $_FILES['sel_file']['tmp_name'];
               //print_r($filename);exit;
               $this->load->library('excel');
               $objPHPExcel = PHPExcel_IOFactory::load($filename);
               $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
               //echo "<pre>";print_r($cell_collection);exit;
               foreach ($cell_collection as $cell) {
                  $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                  $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                  $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                  if ($row == 1) {
                      $header[$row][$column] = $data_value;
                  } else {
                      $arr_data[$row][$column] = $data_value;
                  }
               }
               //echo '<pre>';print_r($arr_data);exit;
               $data['header'] = $header;
               $data['values'] = $arr_data;
               //echo "<pre>";print_r($data['values']);exit;
               $productImage = [];
               foreach ($data['values'] as $xlsxkey => $xlsxvalue) {
                //echo $xlsxkey;
                   foreach ($xlsxvalue as $key => $value) {
                       $xlsxUploadedData[$xlsxkey-2][] = $value;
                   }
               }//exit;
            //echo '<pre>';print_r($_FILES);exit;
              //echo '<pre>'; print_r($xlsxUploadedData);
              //exit;
             
               //echo '<pre>';
               
              // echo count($xlsxUploadedData);exit;
               foreach ($xlsxUploadedData as $xlskey => $xlsvalue) {
                /*if($xlskey==0){
                continue;
                }*/
                $postData = $this->input->post('data[product]');
                //print_r($postData);exit;
                $productAttrId = 0;
               
                $productAttr = $this->pktdblib->custom_query('select pa.* from product_attributes pa  where pa.id like '.trim($xlsvalue[0]));
                //print_r($brands);exit;
                   if(count($productAttr)>0){
                       $productAttrId = $productAttr[0]['id'];
                   $updateArray['id'] = $productAttrId;
                   if($postData['type'] == 'price'){
                    $updateArray['price'] = $xlsvalue[1];
                   }else if($postData['type'] == 'stock'){
                    $updateArray['stock_qty'] = $xlsvalue[1];

                   }
                   
                   }else{
                    $error[] = 'Sap Code not exist in row '.$xlskey;
                    continue;
                   }
                   $updateArray['is_active'] = true;
                   $updateArray['modified_by'] = $this->session->userdata('user_id');
                   $updateArray['modified'] = date('Y-m-d H:i:s');
                   $update[] = $updateArray;
                   
                   
                   
                   
               }
               //exit;
            }
            /*echo '<pre>';echo count($update);print_r($update);
            echo '<br>';
            print_r($error);exit;*/
            if(empty($error)){
            //echo "string";
               
            $this->pktdblib->set_table('product_attributes');
            $this->pktdblib->update_multiple('id', $update);
            $msg = array('message'=>'Data Updated Successfully', 'class'=>'alert alert-success');
           $this->session->set_flashdata('message', $msg);
           
            //redirect('products/update_product_price_stock');
           }else{
            $msg = array('error' => $error, 'class' => 'alert alert-danger');
                $this->session->set_flashdata('error',$msg);
           }
                redirect('products/updateproductstockprice');

            //echo '<pre>';print_r($cars);exit;
           
            //print_r($variations);exit;
        }
        }
	    $data['type'] = ['price'=>'Price', 'stock'=>'Stock'];
	    $data['content'] = 'products/upload_product_price_stock_csv_file';
        $data['meta_title'] = "ERP : Update Price/ Stock";
        $data['title'] = "ERP : Update Price/ Stock";
        $data['meta_description'] = "ERP :Update Price/ Stock";
        echo Modules::run("templates/admin_template", $data);
    }

    function productwise_attributes($data=[]){
        if($_SERVER['REQUEST_METHOD']=='POST' || !empty($data)){
    		//return ($data['params']);
        	if(empty($data))
            	$params = $this->input->post('params');
            else
            	$params = $data['params'];

            //echo $params;exit;

            $sql = 'Select product_attributes.id, concat(attributes.unit," ", attributes.uom) as attribute, product_attributes.is_active from product_attributes inner join attributes on attributes.id=product_attributes.attribute_id where product_attributes.product_id="'.$params.'" and product_attributes.is_active=true order by product_attributes.is_default DESC';
            //echo $sql;
            $productAttributes = $this->pktdblib->custom_query($sql);
            $response = [];//[0=>['id'=>0, 'text'=>'Select Attribute', 'selected'=>'selected']];
            //print_r($productAttributes);exit;
            foreach($productAttributes as $key=>$attribute){
                $response[$key]['id'] = $attribute['id'];
                $response[$key]['text'] = $attribute['attribute'];
                if(!$attribute['is_active'])
                {
                    $response[$key]['disable'] = true;
                }
            }
            //print_r($response);exit;
            if($this->input->is_ajax_request()){
            	echo json_encode($response);
            	exit;
            }else{
            	return json_encode($response);
            }
            //exit;
        }
    }
    
    function admin_edit_pack_products($productId){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
        	/*echo '<pre>';
 			print_r($this->input->post());exit;*/
 			$data['values_posted'] = $_POST; 
 			
			$insert = [];
			$update = [];
 			foreach ($this->input->post('pack_products') as $key => $value) {
 				
				if(isset($value['is_active'])) {
					$value['is_active']= true;
				}else {
					$value['is_active']= false;
				}
				$value['created'] = $value['modified'] = date('Y-m-d H:i:s');
				$value['created_by'] = $value['modified_by'] = $this->session->userdata('user_id');
 				$value['basket_id'] = $productId;
				$insert[] = $value;
 			}
 			$this->pktdblib->set_table("pack_products");
			$this->pktdblib->_delete_by_column('basket_id',$productId);
 			
 			if(!empty($insert)) {
 				//$this->insert_multiple_product_images($insert);
 				$this->pktdblib->set_table('pack_products');
 				$query = $this->pktdblib->_insert_multiple($insert);
 			}
 			$msg = array('message' => 'Data updated Successfully' ,'class'=>'alert alert-success');
			$this->session->set_flashdata('message', $msg);
			redirect('products/admin_edit/'.$productId."?tab=pack_products");

 		}
 		$data['id'] = $productId;
 		$data['packProducts'] = $this->pktdblib->custom_query('Select * from pack_products where basket_id='.$productId.' order by priority asc');
        $data['option']['type'] = ['Select Type', 'image'=>'image', 'video'=>'video'];
        $data['products'] = $this->product_model->get_active_list();
		$data['option']['product'][0] = 'Select Product';
		$data['option']['productAttribute'] = [0=>[''=>'Select Attribute']];
		//echo '<pre>';
		foreach ($data['products'] as $productKey => $product) {
			$productAttributes = json_decode(Modules::run('products/productwise_attributes', ['params'=>$product['id']]), true);
			foreach ($productAttributes as $pakey => $attribute) {
				$data['option']['productAttribute'][$product['id']][$attribute['id']] = $attribute['text'];
			}
			//print_r($productAttributes);
			$data['option']['product'][$product['id']] = $product['product'];
		}
		/*print_r($data['option']['productAttribute']);
		exit;*/
 		//print_r($data['product_images']);
 		$this->load->view('products/admin_edit_pack_products', $data);
    }
    
    public function idWiseProductAttribute($id){
        $this->pktdblib->set_table('product_attributes');
        $productAttributes = $this->pktdblib->get_where($id);
        if($this->input->is_ajax_request()){ 
               echo json_encode(['status'=>'success', 'data'=>$productAttributes]);
               exit;
        }else{
            return $productAttributes; 
        }
    }
    
    function rate_calculator(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //echo '<pre>';
             //print_r($this->input->post('product_attributes'));
            $productAttributes = $this->input->post('product_attributes');
            foreach($productAttributes as $key=>$attribute){
                //print_r($attribute);
                /*if(isset($attribute['is_active'])){
                    $productAttributes[$key]['is_active'] = true;
                }else{
                    $productAttributes[$key]['is_active'] = false;
                }*/
                if(isset($attribute['price'])){
                    $productAttributes[$key]['mrp'] = $attribute['price'];
                }else{
                    unset($productAttributes[$key]);
                }
            }
           
            //print_r($productAttributes);//exit;
            $this->pktdblib->set_table('product_attributes');
            $upd = $this->pktdblib->update_multiple('id', $productAttributes);
            
            if($upd){
                $msg = array('message'=>'Price updated Successfully', 'class'=>'alert alert-success');
                $this->session->set_flashdata('message', $msg);
           
                
            }else{
                $msg = array('message'=>'Some Error Occurred', 'class'=>'alert alert-success');
                $this->session->set_flashdata('message', $msg);
            }
            redirect($this->input->post('url'));
        }
        $data['meta_title'] = "Rate calculator";
		$data['meta_description'] = "Rate calculator";
		$data['modules'][] = "products";
		$data['methods'][] = "rate_calculator_form";
		echo modules::run('templates/admin_template', $data);
    }
    
    function rate_calculator_form($productId = NULL){
        $sql = 'Select p.*, c.category_name from products p inner join product_categories c on c.id=p.product_category_id where p.is_active=true and p.show_on_website=true';
        if(NULL!==$productId){
            $sql.=' AND p.id="'.$productId.'"';
        }
        $sql.=' order by c.category_name ASC';
        //echo $sql;
        $products = $this->pktdblib->custom_query($sql);
        foreach($products as $pKey=>$product){
            if($_SESSION['application']['product_has_multiple_categories']){
                
            }
            $sql2 = 'SELECT pa.*, concat(a.unit, " ",a.uom) as variant FROM `product_attributes` pa INNER JOIN attributes a on a.id=pa.attribute_id WHERE pa.is_active=true';
            $sql2.=' AND pa.product_id="'.$product['id'].'"';
            $sql2.=' ORDER BY is_active DESC';
            $productAttributes = $this->pktdblib->custom_query($sql2);
            $products[$pKey]['attributes'] = $productAttributes;
        }
        $data['products'] = $products;
        /*echo '<pre>';
        print_r($products);
        exit;*/
        $this->load->view('products/rate_calculator', $data);
    }
    
    function per_unit_calculation(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //print_r($this->input->post());
            $requireUOM = explode(" ", $this->input->post('uom'));
            $baseUOM = explode(" ", $this->input->post('base_uom'));
            $unit = $this->pktlib->unit_convertion($requireUOM[1], $baseUOM[1]);
            //echo $unit." <br>";
            $price = ($this->input->post('base_price')+$this->input->post('margin'))*$requireUOM[0]*$unit;
            echo json_encode(['price'=>ceil($price), 'status'=>'success']);
            exit;
        }
    }
   
}
	