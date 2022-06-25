<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stocks extends MY_Controller {
	public $ajax = FALSE;
	function __construct() {
		parent::__construct();
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				//check_user_login(FALSE);
			}
		}
		check_user_login(FALSE);
		$this->load->model('stocks/stock_model');
		$this->load->model('address/address_model');
		$this->load->model('products/product_model');
		$setup = $this->setup();
		$this->ajax = $this->input->is_ajax_request()?TRUE:FALSE;
	}

	function setup(){
		$setup = $this->stock_model->tbl_stocks();
		$stockOut = $this->stock_model->tbl_stockout();
		return TRUE;
	}
	
	function admin_dashboard(){
        $startDate = date('Y-m-d');$endDate = date('Y-m-d', strtotime('- 7 day'));
        //echo $startDate.'<br/>'; echo $endDate;exit;
        $this->pktdblib->set_table('stocks');
        $data['totalInward'] = $this->pktdblib->custom_query('select count(id) as count from stocks where is_active=true and inward_date between "'.$endDate.'" and "'.$startDate.'"');
       //echo $this->db->last_query();exit;
        $data['currentMonthInward'] = $this->pktdblib->custom_query('select count(id) as count from stocks where is_active=true and inward_date like "'.date('Y-m').'%"');
        $data['weeklyInward'] = $this->pktdblib->custom_query('select count(id) as count from stocks where is_active=true and inward_date between "'.$endDate.'" and "'.$startDate.'"');
        $data['todaysInward'] = $this->pktdblib->custom_query('select count(id) as count from stocks where is_active=true and inward_date like "'.date('Y-m-d').'%"');

        $this->load->view('stocks/dashboard', $data);
    }

	function admin_index() {
		if($this->input->is_ajax_request()){  
            
           $postData = $this->input->post();
            //echo "<pre>"; print_r($postData);exit;
            $data = $this->stock_model->stockList($postData);
            foreach($data['aaData'] as $key=>$v){
                //echo "<pre>"; print_r($v);exit;
                $action = '<div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                        <span class="fa fa-caret-down" ></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>'.anchor("#", '<i class="fa fa-eye"></i> View', ['class'=>'dynamic-modal load-ajax', 'data-path'=>"stocks/admin_view/".$v['id'], 'data-modal-title'=>"Stock Details", 'data-model-size'=>"modal-lg"]).'</li>
                   		<li>'.anchor(custom_constants::edit_stock_url."/".$v['id'], '<i class="fa fa-edit"></i> Edit', ['class'=>'']).'</li>
                   		<li>'.anchor("#", '<i class="fa fa-trash"></i> Delete', ['class'=>'removebutton alert-danger', 'data-link'=>"stocks/custom_admin_delete", 'data-id' => $v['id'], 'data-table'=>"stocks"]).'</li>';
                      
                    $action.='</ul>
                </div>';
                $data['aaData'][$key]['action'] = $action;
                //echo $action;exit;
            }
            echo json_encode($data);
            exit;
            
        }
		$data['meta_title'] = 'Stock listing';
		$data['meta_description'] = 'Stock Details';
		$data['title'] = 'Module :: Stock';
		$data['heading'] = '<i class="fa fa-shopping-cart margin-r-5"></i> Stock Inward';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'admin_stock_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_stock_listing($data = []) {
		$this->load->view("stocks/admin_index", $data);
	}

	function admin_view($id) {

		$data['meta_title'] = 'Stocks';
		$data['meta_description'] = 'Stock with Details';
		$data['title'] = 'Module :: Stock';
		$data['heading'] = '<i class="fa fa-shopping-cart margin-r-5"></i>Stock';
		$this->pktdblib->set_table('stocks');
		$data['stock'] = $this->pktdblib->get_where($id);
		$this->pktdblib->set_table('company_warehouse');
		$data['warehouse'] = $this->pktdblib->get_where($data['stock']['company_warehouse_id']);

		$this->pktdblib->set_table('vendors');
		$data['vendor'] = $this->pktdblib->get_where($data['stock']['vendor_id']);
		$data['stockDetails'] = $this->pktdblib->custom_query('select stock_details.*,products.product, concat(attributes.unit," ",attributes.uom) as uom from stock_details inner join products on products.id = stock_details.product_id left join product_attributes pa on pa.id=stock_details.product_attribute_id left join attributes on attributes.id=pa.attribute_id where stock_id ='.$id);
		$this->load->view("stocks/admin_view", $data);
	}

	function admin_stockout_index() {

		$data['meta_title'] = 'Stockout listing';
		$data['meta_description'] = 'Stockout Details';
		$data['title'] = 'Module :: Stock';
		$data['heading'] = '<i class="fa fa-shopping-cart margin-r-5"></i> Stock Outward';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'admin_stockout_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_stockout_listing($data = []) {	
		$this->stock_model->set_table('stockout');
		$stockout = $this->stock_model->stockOutList('created desc');
		$data['stockout'] = $stockout->result_array();
		/*echo '<pre>';
		print_r($data);exit;*/
		$this->load->view("stocks/admin_stockout_index", $data);
	}

	function admin_stockout_view($id) {
		$data['meta_title'] = 'Stockout';
		$data['meta_description'] = 'Stockout with Details';
		$data['title'] = 'Module :: Stock';
		$data['heading'] = '<i class="fa fa-shopping-cart margin-r-5"></i>Stock';
		$this->pktdblib->set_table('stockout');
		$data['stock'] = $this->pktdblib->get_where($id);
		$this->pktdblib->set_table('brokers');
		$data['broker'] = $this->pktdblib->get_where($data['stock']['broker_id']);
		$data['stockoutDetails'] = $this->pktdblib->custom_query('select stockout_details.*,products.product from stockout_details inner join products on products.id = stockout_details.product_id where stockout_id ='.$id);
		//echo "<pre>"; print_r($data);exit;
		$this->load->view("stocks/admin_stockout_view", $data);
	}

	function stockout_view($data=[]) {
		$this->stock_model->set_table('stockout_details');
		$stockDetails = $this->stock_model->stockOutList('created desc');
		$data['stockoutDetail'] = $stockDetails->result_array();
		$this->load->view("stocks/admin_stockout_view", $data);
	}


	function _register_new_stock($data) {
		if(empty(trim($data['stock_code']))){
		    $data['stock_code'] = NULL;
		}
		$this->pktdblib->set_table("stocks");
		$id = $this->pktdblib->_insert($data);
		if(empty($data['stock_code'])){
			$data['stock_code'] = $stockCode = $this->create_stock_code($id['id']);
			$updArr['id'] = $id['id'];
			$updArr['stock_code'] = $stockCode;
			$updCode = $this->edit_stock($id['id'], $updArr);
		}
		$data = $this->stock_details($id['id']);
		return json_encode(['message' =>'Stock Added Successfully', "status"=>"success", 'id'=> $id['id'], 'stocks'=>$data]);
	}

	function edit_stock($id=NULL, $post_data = []){
		//check_user_login(FALSE);
		if(NULL == $id)
			return false;

		$this->pktdblib->set_table('stocks');
		if($this->pktdblib->_update($id,$post_data))
			return true;
		else
			return false;
	}

	// Add new user
	function admin_add() {
		//check_user_login(FALSE);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			/*echo '<pre>';
			print_r($this->input->post());
			exit;*/
			$data['values_posted'] = $this->input->post('data');
			//echo '<pre>';print_r($_POST);exit;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			
			$this->form_validation->set_rules('data[stocks][inward_date]', 'Inward Date', 'required');
			$this->form_validation->set_rules('data[stocks][po_no]', 'PO Number', 'required|is_unique[stocks.po_no]');
			$this->form_validation->set_rules('data[stocks][vendor_id]', 'Vendor', 'required');
			$this->form_validation->set_rules('data[stocks][stock_code]', 'Stock Code', 'max_length[255]|is_unique[stocks.stock_code]');
			$this->form_validation->set_rules('data[stocks][company_warehouse_id]', 'Company Warehouse', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				//print_r($_FILES);exit;
				if(!empty($_FILES['invoice']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['invoice'], 'path'=>'../content/uploads/stocks/','ext'=>'gif|jpg|png|jpeg|pdf', 'fieldname'=>'invoice', 'arrindex'=>'invoice'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$_POST['data']['stocks']['invoice'] = $profileImg['filename'];
					}
					else {
						$error['invoice'] = $profileImg['error'];
					}
				}else{
					$_POST['data']['stocks']['invoice'] = '';
				}
				/*echo '<pre>';
				print_r($this->input->post());
				exit;*/
				if(empty($error)){
					$post_data = $this->input->post('data[stocks]');//$_POST['data']['stocks'];
					$post_data['inward_date'] = $this->pktlib->dmYtoYmd($post_data['inward_date']);
					$post_data['created'] = date('Y-m-d H:i:s');
					$post_data['created_by'] = $this->session->userdata('user_id');
					$reg_stock = json_decode($this->_register_new_stock($post_data), true);
					
					if($reg_stock['status'] === "success")
					{
						$_POST['data']['stocks'] = $reg_stock['stocks'];
						$_POST['data']['stocks']['id'] = $reg_stock['id'];
						
						if(NULL !== $this->input->post('stock_details')){
							$stockDetails = $this->admin_add_multiple_stock_details($this->input->post('stock_details'), $reg_stock['id']);
							$_POST['data']['stock_details'] = $this->input->post('stock_details');
						}

						/*Equiry Amount update*/
						$upd = $this->calculateEnquiryAmount($reg_stock['id']);

						if(NULL !== ($this->input->post('quotation'))){

							$quotation = $this->enquiry_to_quotation($_POST['data']);
						}
						$msg = array('message'=>'Stock Added Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::new_stock_url);
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
			}else{
				$msg = array('message'=>'Error Occured'.validation_errors(), 'class'=>'alert alert-danger');
	                $this->session->set_flashdata('message',$msg);
			}
		}
		
		$data['meta_title'] = "New Enquiry";
		$data['meta_description'] = "New Enquiry";
		$data['title'] = "Module :: Stock";
		$data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> New Inward';
		//echo "reached here"; exit;
		$data['modules'][] = "stocks";
		$data['methods'][] = "admin_stock_form";
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

            $("#vendor_id").on("change", function(){

            	var id = this.id;
            	var vendorId = $(this).val();
            	var url = $(this).attr("data-link");
            	var target = $(this).attr("data-target");
            	$.ajax({
		          type: "POST",
		          dataType: "html",
		          url : base_url+url,
		          data: {"user_id":vendorId, "type":"vendors"},
		          success: function(response) {
		            console.log(response);
		            $("#"+target).val(response);
		          }
		        
		        });
				
            })

            $("form").on("submit", function(e){
            	e.preventDefault();
            	if($("#vendor_id").val()==0){
            		alert("Please select vendor");
            		$("#vendor_id").focus();
            		return false;
            	}

            	if($("#company_warehouse_id").val()==0){
            		alert("Please select Warehouse");
            		$("#company_warehouse_id").focus();
            		return false;
            	}
            	var error = false;
            	$(".select2").each(function(){
            		
            		if($(this).hasClass("required")){
            			var id = this.id;
            			
            			if($("#"+id).val()=="" || $("#"+id).val()=="0"){

	            			alert("Selected Field is missing");
	            			this.focus();
	            			error = true;
	            			return false;
            			}
            			//return false;
            		}
            	})

            	if(error == false){
            		this.submit();
            	}
            	
            	
            })
		</script>';
		//echo '<pre>';print_r($data['option']['product']);
		//echo Modules::run("templates/login_template", $data);
		echo Modules::run("templates/admin_template", $data);
		
	}

	function admin_stock_form($data = []) {


		$this->pktdblib->set_table('products');
		$data['products'] = $this->pktdblib->get_active_list();
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}
		$this->pktdblib->set_table('vendors');
		$vendors = $this->pktdblib->get('created asc');
		$data['option']['vendor'][0] = 'Select Vendor';
		foreach ($vendors->result_array() as $vendorKey => $vendor) {
			$data['option']['vendor'][$vendor['id']] = $vendor['company_name']." (".$vendor['first_name']." ".$vendor['middle_name']." ".$vendor['surname'].")";
		}

		$this->pktdblib->set_table('company_warehouse');
		$query = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['companyWarehouse'][0] = 'Select Warehouse/Cutter';
		foreach ($query->result_array() as $warehouseKey => $warehouse) {
			$data['option']['companyWarehouse'][$warehouse['id']] = $warehouse['warehouse'];
		}

		$data['option']['uom'] = $this->pktlib->uom();
		//print_r($data['companies']);exit;

		$this->load->view("stocks/admin_add", $data);
	}
	
	function admin_add_2() {
		//check_user_login(FALSE);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$data['values_posted'] = $_POST['data'];
			//echo '<pre>';print_r($_POST);exit;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			
			$this->form_validation->set_rules('data[stocks][inward_date]', 'Inward Date', 'required');
			//$this->form_validation->set_rules('data[stocks][lot_no]', 'Lot Number', 'required');
			$this->form_validation->set_rules('data[stocks][vendor_id]', 'Vendor', 'required');
			//$this->form_validation->set_rules('data[stocks][grade]', 'Grade', 'required');
			$this->form_validation->set_rules('data[stocks][stock_code]', 'Stock Code', 'max_length[255]|is_unique[stocks.stock_code]');
			$this->form_validation->set_rules('data[stocks][company_warehouse_id]', 'Company Warehouse', 'required');
			//$this->form_validation->set_rules('data[stocks][company_warehouse_id]', 'Company Warehouse', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				//print_r($_FILES);exit;
				if(!empty($_FILES['invoice']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['invoice'], 'path'=>'../content/uploads/stocks/','ext'=>'gif|jpg|png|jpeg|pdf', 'fieldname'=>'invoice', 'arrindex'=>'invoice'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$_POST['data']['stocks']['invoice'] = $profileImg['filename'];
					}
					else {
						$error['invoice'] = $profileImg['error'];
					}
				}else{
					$_POST['data']['stocks']['invoice'] = '';
				}

				if(empty($error)){
					//echo '<pre>';print_r($_POST);exit;
					$post_data = $_POST['data']['stocks'];
					$post_data['inward_date'] = $this->pktlib->dmYtoYmd($post_data['inward_date']);
					//$post_data['referred_by'] = (NULL !== $this->input->post('data[enquiry_references][user_type]'))?$this->input->post('data[enquiry_references][user_type]'):'';
					//echo '<pre>';//print_r($_POST);exit;
					$productId = (NULL!==$this->input->post('product_id'))?$this->input->post('product_id'):'';
					$reg_stock = json_decode($this->_register_new_stock($post_data), true);
					
					if($reg_stock['status'] === "success")
					{
						$_POST['data']['stocks'] = $reg_stock['stocks'];
						$_POST['data']['stocks']['id'] = $reg_stock['id'];
						
						if(NULL !== $this->input->post('stock_details')){
							$stockDetails = $this->admin_add_multiple_stock_details($this->input->post('stock_details'), $reg_stock['id'], $productId);
							$_POST['data']['stock_details'] = $this->input->post('stock_details');
						}

						/*Equiry Amount update*/
						$upd = $this->calculateEnquiryAmount($reg_stock['id']);

						if(NULL !== ($this->input->post('quotation'))){

							$quotation = $this->enquiry_to_quotation($_POST['data']);
						}//exit;
						$msg = array('message'=>'Stock Added Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::new_stock_url);
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
		$data['title'] = "Module :: Stock";
		$data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> New Inward';
		//echo "reached here"; exit;
		$data['modules'][] = "stocks";
		$data['methods'][] = "admin_stock_form_2";
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

            $("#vendor_id").on("change", function(){

            	var id = this.id;
            	var vendorId = $(this).val();
            	var url = $(this).attr("data-link");
            	var target = $(this).attr("data-target");
            	$.ajax({
		          type: "POST",
		          dataType: "html",
		          url : base_url+url,
		          data: {"user_id":vendorId, "type":"vendors"},
		          success: function(response) {
		            console.log(response);
		            $("#"+target).val(response);
		          }
		        
		        });
				
            })

            $("form").on("submit", function(e){
            	e.preventDefault();
            	if($("#vendor_id").val()==0){
            		alert("Please select vendor");
            		$("#vendor_id").focus();
            		return false;
            	}

            	if($("#company_warehouse_id").val()==0){
            		alert("Please select Warehouse");
            		$("#company_warehouse_id").focus();
            		return false;
            	}
            	var error = false;
            	$(".select2").each(function(){
            		
            		if($(this).hasClass("required")){
            			var id = this.id;
            			
            			if($("#"+id).val()=="" || $("#"+id).val()=="0"){

	            			alert("Selected Field is missing");
	            			this.focus();
	            			error = true;
	            			return false;
            			}
            			//return false;
            		}
            	})

            	if(error == false){
            		this.submit();
            	}
            	
            	
            })
		</script>';
		//echo '<pre>';print_r($data['option']['product']);
		//echo Modules::run("templates/login_template", $data);
		echo Modules::run("templates/admin_template", $data);
		
	}

	function admin_stock_form_2($data = []) {


		$this->pktdblib->set_table('products');
		$data['products'] = $this->pktdblib->get_active_list();
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}
		$this->pktdblib->set_table('vendors');
		$vendors = $this->pktdblib->get('created asc');
		$data['option']['vendor'][0] = 'Select Vendor';
		foreach ($vendors->result_array() as $vendorKey => $vendor) {
			$data['option']['vendor'][$vendor['id']] = $vendor['company_name']." (".$vendor['first_name']." ".$vendor['middle_name']." ".$vendor['surname'].")";
		}

		$this->pktdblib->set_table('company_warehouse');
		$query = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['companyWarehouse'][0] = 'Select Warehouse/Cutter';
		foreach ($query->result_array() as $warehouseKey => $warehouse) {
			$data['option']['companyWarehouse'][$warehouse['id']] = $warehouse['warehouse'];
		}

		$data['option']['uom'] = $this->pktlib->uom();
		//print_r($data['companies']);exit;

		$this->load->view("stocks/admin_add_2", $data);
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

	function create_stock_code($enqId){
		$stockCode = 'S';
		if($enqId>0 && $enqId<=9)
			$stockCode .= '000000';
		elseif($enqId>=10 && $enqId<=99)
			$stockCode .= '00000';
		elseif($enqId>=100 && $enqId<=999)
			$stockCode .= '0000';
		elseif($enqId>=1000 && $enqId<=9999)
			$stockCode .= '000';
		elseif($enqId>=10000 && $enqId<=99999)
			$stockCode .= '00';
		elseif($enqId>=100000 && $enqId<=999999)
			$stockCode .= '0';

		$stockCode .= $enqId;
		return $stockCode;
	}


	function admin_edit($id = null) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			/*echo '<pre>';
			print_r($this->input->post());exit;*/
			
			$data['values_posted'] = $_POST['data'];
			$data['stocks'] = $this->input->post('data[stocks]');
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			
			$this->form_validation->set_rules('data[stocks][inward_date]', 'Inward Date', 'required');
			$this->form_validation->set_rules('data[stocks][vendor_id]', 'Vendor', 'required');
			$this->form_validation->set_rules('data[stocks][stock_code]', 'Stock Code', 'max_length[255]');
			$this->form_validation->set_rules('data[stocks][company_warehouse_id]', 'Company Warehouse', 'required');
			if($this->form_validation->run('stocks') !== FALSE) {
				$profileImg = '';
				$post_data = $_POST['data']['stocks'];
				
				if(!empty($_FILES['invoice']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['invoice'], 'path'=>'../content/uploads/stocks/','ext'=>'gif|jpg|png|jpeg|pdf', 'fieldname'=>'invoice', 'arrindex'=>'invoice'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$post_data['invoice'] = $profileImg['filename'];
						unset($post_data['invoice2']);
					}
					else {
						$error['invoice'] = $profileImg['error'];
					}
				}else{
					$post_data['invoice'] = $post_data['invoice2'];
					unset($post_data['invoice2']);
					//$post_data['']
				}
				if(empty($error)) {
					$post_data['inward_date'] = $this->pktlib->dmYtoYmd($post_data['inward_date']);
					$productId = NULL!==($this->input->post('product_id'))?$this->input->post('product_id'):'';
					//echo $productId;
					if(isset($post_data['is_active'])){
						$post_data['is_active'] = true;
					}else{
						$post_data['is_active'] = false;
					}
					$post_data['modified'] = date('Y-m-d H:i:s');
					$post_data['modified_by'] = $this->session->userdata('user_id');
					//echo '<pre>';print_r($post_data);exit;
					if($this->edit_stock($id,$post_data)) {
						if(NULL !== $this->input->post('stock_details')){
							$insertDetail = [];
							$updateDetail = [];
							/*echo '<pre>';
							print_r($this->input->post('stock_details'));*/
							//exit;
							foreach ($this->input->post('stock_details') as $detailKey => $detail) {
								$detail['product_id'] = $detail['product_id'];
								if(!empty($detail['id'])){
									$detail['mfg_date'] = $this->pktlib->dmYtoYmd($detail['mfg_date']);
									$detail['exp_date'] = $this->pktlib->dmYtoYmd($detail['exp_date']);
									$detail['modified'] = date('Y-m-d H:i:s');
									$detail['modified_by'] = $this->session->userdata('user_id');
									$updateDetail[] = $detail;
								}else{
									$detail['created_by'] = $this->session->userdata('user_id');
									$detail['created'] = date('Y-m-d H:i:s');
									$insertDetail[] = $detail;
								}
								
							}
							//echo '<pre>';print_r($updateDetail);exit; 
							$_POST['data']['stock_details'] = $this->input->post('stock_details');
							
							if(!empty($insertDetail)){
								$enqDetails = $this->admin_add_multiple_stock_details($insertDetail, $id, $productId);
							}

							if(!empty($updateDetail)){
								$enqDetails = $this->admin_edit_multiple_stock_details($updateDetail);
							}
						}

						/*Equiry Amount update*/
						$upd = $this->calculateEnquiryAmount($id);						
                        $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                             $this->session->set_flashdata('message',$msg);
                        }
                        else {
						//echo "string";exit;
                            $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                            $this->session->set_flashdata('message',$msg);
                        }
						//echo "raeched here";exit;
                        redirect(custom_constants::edit_stock_url."/".$id);

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
			$this->stock_model->set_table("stocks");
			$data['stocks'] = $this->stock_details($id);
			$data['values_posted']['stocks'] = $data['stocks'];
		}
		$data['id'] = $id;
		if(!($this->input->get('tab')))
			$data['tab'] = 'personal_info';
		else
			$data['tab'] = $this->input->get('tab');
		$data['meta_title'] = 'edit stocks';
		$data['meta_description'] = 'edit stocks';
		$data['title'] = 'Module :: Stock';
		$data['heading'] = 'Edit stocks';
		
		$data['content'] = 'stocks/admin_edit';

		$this->pktdblib->set_table('companies');
		$companies = $this->pktdblib->get('created asc');
		foreach ($companies->result_array() as $companyKey => $company) {
			$data['companies'][$company['id']] = $company['company_name'];
		}
		$this->stock_model->set_table('stock_details');
		$stockDetails = $this->stock_model->get_where(['stock_id' => $id, 'is_active'=>true]);
		$data['stockDetails'] = $stockDetails->result_array();
		$data['option']['attribute'][0] = 'Select Attribute';
		//echo '<pre>';
		foreach ($data['stockDetails'] as $detailKey => $detail) {
			$pa = $this->pktdblib->custom_query('Select product_attributes.id, concat(attributes.unit, " ", attributes.uom) as uom from product_attributes inner join attributes on attributes.id=product_attributes.attribute_id where product_attributes.product_id="'.$detail['product_id'].'" and product_attributes.is_active=true');
			//print_r($pa);
			foreach ($pa as $pakey => $attribute) {
				$data['option']['attribute'][$detail['product_id']][$attribute['id']] = $attribute['uom'];
			}
			$data['stockDetails'][$detailKey]['total'] = ($detail['unit_price']*$detail['qty'])+ (($detail['unit_price']*$detail['qty'])*$detail['tax']/100.00);
		}
		//echo '</pre>';
		$data['products'] = $this->product_model->get_active_list();
		/*echo '<pre>';
		print_r($data['products']);*/
		$data['option']['product'][0] = 'Select Product';
		//print_r($data['option']['product'][0]);
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}

		$this->pktdblib->set_table('vendors');
		$vendors = $this->pktdblib->get('created asc');
		$data['option']['vendor'][0] = 'Select Vendor';
		foreach ($vendors->result_array() as $vendorKey => $vendor) {
			$data['option']['vendor'][$vendor['id']] = $vendor['company_name']." (".$vendor['first_name']." ".$vendor['middle_name']." ".$vendor['surname'].")";
		}

		$this->pktdblib->set_table('company_warehouse');
		$query = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['companyWarehouse'][0] = 'Select Warehouse/Cutter';
		foreach ($query->result_array() as $warehouseKey => $warehouse) {
			$data['option']['companyWarehouse'][$warehouse['id']] = $warehouse['warehouse'];
		}

		$data['option']['uom'] = $this->pktlib->uom();

		$data['js'][] = '<script>$("form").on("submit", function(e){
            	e.preventDefault();
            	if($("#vendor_id").val()==0){
            		alert("Please select vendor");
            		$("#vendor_id").focus();
            		return false;
            	}

            	if($("#company_warehouse_id").val()==0){
            		alert("Please select Warehouse");
            		$("#company_warehouse_id").focus();
            		return false;
            	}
            	var error = false;
            	$(".select2").each(function(){
            		
            		if($(this).hasClass("required")){
            			var id = this.id;
            			
            			if($("#"+id).val()=="" || $("#"+id).val()=="0"){

	            			alert("Selected Field is missing");
	            			this.focus();
	            			error = true;
	            			return false;
            			}
            			//return false;
            		}
            	})

            	if(error == false){
            		this.submit();
            	}
            	
            	
            })</script>';

		echo Modules::run("templates/admin_template", $data);
	}

	function stock_details($id, $table = 'stocks') {
		$this->stock_model->set_table($table);
		$stockDetails = $this->stock_model->get_where($id);
		return $stockDetails->row_array();
		
	}

	function get_idwise_stockdetail($id) {
		//$this->pktdblib->set_table('stock_details');
		
		$stockDetails = $this->pktdblib->custom_query('select stock_details.*, stocks.company_warehouse_id from stock_details inner join stocks on stocks.id=stock_details.stock_id where stock_details.id='.$id);
		$stockDetails[0]['remark'] = Modules::run('stocks/coilwise_remark', $stockDetails[0]['coil_no'], $stockDetails[0]['company_warehouse_id']);
		echo json_encode($stockDetails[0]);
		exit;
	}


	function get_paramwise_stockdetail() {
		/*$_POST['product_id']=1;
		$_POST['grade']='SPHT-1';*/
		//$this->pktdblib->set_table('stock_details');
		$productId = $this->input->post('product_id');
		$grade = $this->input->post('grade');
		$sql = 'select stock_details.*, stocks.inward_date from stock_details inner join stocks on stocks.id=stock_details.stock_id where 1=1 ';
		if(NULL!==($this->input->post('product_id'))){
			$sql.=' AND stock_details.product_id='.$this->input->post('product_id');
		}
		if(NULL!==($this->input->post('grade'))){
			$sql.=' AND stock_details.grade="'.$this->input->post('grade').'"';
		}
		if(NULL!==($this->input->post('coil'))){
			$sql.=' AND stock_details.coil_no="'.$this->input->post('coil').'"';
		}

		$sql.' AND stock_details.balance_qty>0 order by stocks.inward_date asc';

		//echo $sql;
		$stockDetails = $this->pktdblib->custom_query($sql);
		$thicknessList = [0=>'Dimension'];
		foreach ($stockDetails as $key => $detail) {
		    $thicknessList[$key+1]['id'] = $detail['thickness']." X ".$detail['width']." X ".$detail['length']." (Bal :".$detail['balance_qty']." MT)";
			$thicknessList[$key+1]['text'] = $detail['thickness']." X ".$detail['width']." X ".$detail['length']." (Bal :".$detail['balance_qty']." MT)";
		}
		echo json_encode($thicknessList);
		exit;
	}
	
	function get_paramwise_coildetail() {
		/*$_POST['product_id']=2;
		$_POST['grade']='Korea';*/
		//$this->pktdblib->set_table('stock_details');
		$productId = $this->input->post('product_id');
		$coilNo = $this->input->post('coil_no');
		$sql = 'select stock_details.*, stocks.inward_date from stock_details inner join stocks on stocks.id=stock_details.stock_id where 1=1 ';
		if(NULL!==($this->input->post('product_id'))){
			$sql.=' AND stock_details.product_id='.$this->input->post('product_id');
		}
		if(NULL!==($this->input->post('coil_no'))){
			$sql.=' AND stocks.coil_no="'.$this->input->post('coil_no').'"';
		}

		$sql.' AND stock_details.balance_qty>0 order by stocks.inward_date asc';

		//echo $sql;
		$coilDetails = $this->pktdblib->custom_query($sql);
		$coilDetails = $this->pktdblib->custom_query($sql);
		//print_r($coilDetails);exit;
		$coilList = [0=>'Coil No Listed'];
		foreach ($coilDetails as $key => $detail) {
			$coilList[$key+1]['id'] = $detail['coil_no'];
			$coilList[$key+2]['text'] = $detail['coil_no']." - ".$detail['balance_qty'];
			//$coilList[$key+1]['id'] = $coilList[$key+1]['text'] = $detail['thickness']." X ".$detail['width']." X ".$detail['length']." (Bal :".$detail['balance_qty']." MT)";
		}
		echo json_encode($thicknessList);
		exit;
	}

	function getCoilWiseDetails() {
		if(!$this->input->post()){
			echo json_encode(['msg'=>'Invalid Request', 'status'=>'fail']);
		}
		$productId = $this->input->post('product_id');
		$coilNo = $this->input->post('coil_no');
		
		$sql = 'select stock_details.*, stocks.inward_date from stock_details inner join stocks on stocks.id=stock_details.stock_id where 1=1 ';
		if(NULL!==($this->input->post('product_id'))){
			$sql.=' AND stock_details.product_id='.$this->input->post('product_id');
		}
		if(NULL!==($this->input->post('coil_no'))){
			$sql.=' AND stock_details.coil_no="'.$this->input->post('coil_no').'"';
		}

		$sql.' AND stock_details.balance_qty>0 order by stocks.inward_date asc';

		//echo $sql;
		//echo json_encode($sql);exit;
		$coilDetails = $this->pktdblib->custom_query($sql);
		//$coilDetails = $this->pktdblib->custom_query($sql);
		//print_r($coilDetails);exit;
		$gradeList = [0=>'Grade Listed'];
		$coilList = [0=>'Coil No Listed'];
		$thicknessList = [0=>'Dimension Listed Here'];
		foreach ($coilDetails as $key => $detail) {
			$coilList[$key+1]['id'] = $detail['coil_no'];
			$coilList[$key+1]['text'] = $detail['coil_no']." - ".$detail['balance_qty'];
			$gradeList[$key+1]['id'] = $detail['grade'];
			$gradeList[$key+1]['text'] = $detail['grade'];
			$thicknessList[$key+1]['id'] = $thicknessList[$key+1]['text'] = $detail['thickness']." X ".$detail['width']." X ".$detail['length']." (Bal :".$detail['balance_qty']." MT)";
			//$coilList[$key+1]['id'] = $coilList[$key+1]['text'] = $detail['thickness']." X ".$detail['width']." X ".$detail['length']." (Bal :".$detail['balance_qty']." MT)";
		}
		echo json_encode(['coilList'=>$coilList,'gradeList'=>$gradeList, 'thicknessList'=>$thicknessList]);
		exit;
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
		$this->stock_model->set_table('stocks');
		$typeWiseUsers = $this->stock_model->get_custom_address_type_users($userType);
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
		$this->stock_model->set_table('enquiry_references');
		$insert = $this->stock_model->_insert($data);
		return $insert;
	}

	function stocks_details() {
		$data['meta_title'] = "stocks Details";
		$data['meta_description'] = "stocks Details";
		
		$data['content'] = "stocks/stocks_details";
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_stock_details() {
		$this->load->view("stocks/stocks_details");
	}

	function admin_add_multiple_stock_details($data, $stockId, $productId=0) {
		$stockDetails = [];
		//echo '<pre>';print_r($data);exit;
		foreach ($data as $key => $stockDetail) {
			$stockDetails[$key] = $stockDetail;
			$stockDetails[$key]['stock_id'] = $stockId;
			$stockDetails[$key]['mfg_date'] = $this->pktlib->dmYtoYmd($stockDetail['mfg_date']);
			$stockDetails[$key]['exp_date'] = $this->pktlib->dmYtoYmd($stockDetail['exp_date']);
			if(isset($stockDetail['id']) && $stockDetail['id']!=''){
				$stockDetails[$key]['modified'] = date('Y-m-d H:i:s');
				$stockDetails[$key]['modified_by'] = $this->session->userdata('user_id');
			}else{
				$stockDetails[$key]['created'] = date('Y-m-d H:i:s');
				$stockDetails[$key]['created_by'] = $this->session->userdata('user_id');

			}
		}
		//print_r($stockDetails);exit;
		$this->pktdblib->set_table('stock_details');
		$detailCount = $this->pktdblib->_insert_multiple($stockDetails);
		if($detailCount)
			return $detailCount;
		else
			return false;
	}

	function enquiry_to_quotation($data) {
		//echo '<pre>';
		//print_r($data);exit;
		$quotationArray['enquiry_id'] = $data['stocks']['id'];
		$quotationArray['quotation_date'] = date('Y-m-d H:i:s');
		$quotationArray['amount_before_tax'] = $data['stocks']['amount_before_tax'];
		$quotationArray['amount_after_tax'] = $data['stocks']['amount_after_tax'];
		$quotationArray['created'] = date('Y-m-d H:i:s');
		$quotationArray['modified'] = date('Y-m-d H:i:s');
		$quotationArray['message'] = $data['stocks']['message'];
		//$quotationDetails = [];
		$quotation = json_decode(Modules::run("quotations/_register_new_quotation", $quotationArray), true);
		if($quotation['status']=="success"){
			$quotationDetails = Modules::run("quotations/admin_add_multiple_quotation_details", $data['stock_details'], $quotation['id'] );
			return $quotationDetails;
			//print_r($quotationDetails);
		}else{
			return false;
		}
		
		//exit;
	}

	function admin_followup() {

		$data['meta_title'] = 'stocks listing';
		$data['meta_description'] = 'stocks Details';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'enquiry_followup';
		echo Modules::run("templates/admin_template", $data);
	}

	function enquiry_followup($data = []) {
		
		$this->stock_model->set_table('stocks');
		$condition = [1=>1];
		if(isset($data['condition'])){
			$condition = $data['condition'];
		}
		$stocks = $this->stock_model->enquiry_followup($condition);
		$data['stocks'] = $stocks->result_array();
		//print_r($data);exit;
		$this->load->view("stocks/admin_followup", $data);
	}

	function delete() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = $this->input->post('id');
			$this->stock_model->set_table($this->input->post('table'));
			$arrayData['is_active'] = $this->input->post('is_active');
			$arrayData['modified'] = date('Y-m-d H:i:s');
			$response = $this->stock_model->_update($id, $arrayData);
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
			$this->stock_model->set_table($this->input->post('table'));
			$enquiryDetails = $this->stock_model->get_where($id);
			$enquirDet = $enquiryDetails->row_array();
			$arrayData['is_active'] = $this->input->post('is_active');
			$arrayData['modified'] = date('Y-m-d H:i:s');
			$response = $this->stock_model->_update($id, $arrayData);
			$this->calculateEnquiryAmount($enquirDet['enquiry_id']);
			if($response){
				echo json_encode(['status'=>'success', 'id'=>$id]);
				exit;
			}
		}
			
		echo json_encode(['status'=>'error', 'msg'=>'Invalid Input']);
		exit;
		
	}

	function calculateEnquiryAmount($stockId) {
		$this->stock_model->set_table('stock_details');
		$stockDetails = $this->stock_model->get_where(['stock_id'=>$stockId, 'is_active'=>true]);
		$amtBeforeTax = 0;
		$amtAfterTax = 0;
		foreach ($stockDetails->result_array() as $key => $detail) {
			$amtBeforeTax = $amtBeforeTax + ($detail['qty']*$detail['unit_price']);
			$amtAfterTax = $amtAfterTax+(($detail['qty']*$detail['unit_price'])+($detail['qty']*$detail['unit_price'])*$detail['tax']/100.00);
		}

		$stockArray = ['id'=>$stockId, 'amount_before_tax'=>$amtBeforeTax, 'amount_after_tax'=>$amtAfterTax];
		$res = $this->edit_stock($stockId, $stockArray);
		return $res;

	}

	function admin_edit_multiple_stock_details($data) {
	    //echo '<pre>';print_r($data);exit;
		$this->pktdblib->set_table('stock_details');
		$upd = $this->pktdblib->update_multiple('id', $data);
		//print_r($upd);exit;
		return $upd;
	}

	function admin_add_project(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[stocks][project_name]', 'Project name', 'required|max_length[255]');
			$this->form_validation->set_rules('data[stocks][customer_id]', 'Customer', 'required');
			$this->form_validation->set_rules('data[stocks][message]', 'Comment', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];

				if(empty($error)){
					$post_data = $_POST['data']['stocks'];
					$post_data['date'] = $this->pktlib->dmYtoYmd($post_data['date']);
					$reg_stock = json_decode($this->_register_new_stock($post_data), true);
					
					if($reg_stock['status'] === "success")
					{
						
						$msg = array('message'=>'stock Added Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::edit_project_url."/".$reg_stock['id']);
					}
					else
					{
						// Registration error
						$data['form_error'] = $reg_stock['msg'];
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
		$data['modules'][] = "stocks";
		$data['methods'][] = "admin_project_form";
		
		//echo '<pre>';print_r($data['option']['product']);
		//echo Modules::run("templates/login_template", $data);
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_project_form($data = []) {
		$data['customers']  = [];

		if(isset($data['module']) && $data['module']=='customers'){
				$data['customers'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
		}else{
			
			$data['customers'] = Modules::run("customers/get_Customer_list_dropdown");
		}
			//print_r($data['customers']);exit;
		//$data['option']['referred_by'] = $this->referredby_option();
		//$data['products'] = $this->product_model->get_active_list();
		/*echo '<pre>';
		print_r($data['products']);*/
		/*$data['option']['product'][0] = 'Select Product';
		//print_r($data['option']['product'][0]);
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}
		$this->stock_model->set_table('companies');
		$companies = $this->stock_model->get('created asc');
		foreach ($companies->result_array() as $companyKey => $company) {
			$data['companies'][$company['id']] = $company['company_name'];
		}*/
		//print_r($data['companies']);exit;

		$this->load->view("stocks/admin_add_project", $data);
	}

	function admin_edit_project($id = null) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$data['values_posted'] = $_POST['data'];
			$data['stocks'] = $this->input->post('data[stocks]');
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[stocks][project_name]', 'first name', 'required|max_length[255]');
			
			$this->form_validation->set_rules('data[stocks][date]', 'dob', 'required');
			
			if($this->form_validation->run('stocks') !== FALSE) {
				$profileImg = '';
				
				$data['values_posted'] = $_POST['data'];
				if(empty($error)) {
					$post_data = $_POST['data']['stocks'];
					$post_data['date'] = $this->pktlib->dmYtoYmd($post_data['date']);
					
					if($this->edit_stock($id,$post_data)) {
						
                        $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                             $this->session->set_flashdata('message',$msg);
                        }
                        else {
						//echo "string";exit;
                            $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                            $this->session->set_flashdata('message',$msg);
                        }
						//echo "raeched here";exit;
                        redirect(custom_constants::edit_project_url."/".$id.'?tab=document');

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
			$this->stock_model->set_table("stocks");
			$data['stocks'] = $this->stock_details($id);
		//	print_r($data['stocks']);exit;
			$data['values_posted']['stocks'] = $data['stocks'];
		}
		/*echo '<pre>';
		print_r($data);exit;*/
		$data['id'] = $id;
		if(!($this->input->get('tab')))
			$data['tab'] = 'personal_info';
		else
			$data['tab'] = $this->input->get('tab');
		$data['meta_title'] = 'Edit stock';
		$data['meta_description'] = 'Edit stock';
		
		$data['content'] = 'stocks/admin_edit_project';

		$documentData = ['url'=>custom_constants::edit_project_url.'/'.$id.'?tab=document', 'module'=>'stocks', 'user_id'=>$id, 'type'=>'stocks', 'stock'=>$data['stocks']];
		$data['document'] = Modules::run("upload_documents/admin_add", $documentData);

		$documentListData = ['condition'=>['user_documents.user_id'=>$id, 'user_documents.user_type'=>'stocks'], 'module'=>'stocks'];
		$data['documentList'] = Modules::run("upload_documents/admin_document_listing", $documentListData);

		$data['customers']  = [];

			$data['customers'] = Modules::run("customers/get_Customer_list_dropdown");
		
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_index_projects() {

		$data['meta_title'] = 'Project listing';
		$data['meta_description'] = 'Projects Details';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'admin_project_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_project_listing($data = []) {
		
		$this->stock_model->set_table('stocks');
		$stocks = $this->stock_model->stockList();
		$data['stocks'] = $stocks->result_array();
		//($data);exit;
		$this->load->view("stocks/admin_index_projects", $data);
	}

	function report(){
		$sql = 'Select stocks.*, concat(vendors.first_name," ", vendors.middle_name, " ", vendors.surname) as vendor, vendors.company_name, company_warehouse.warehouse, (select GROUP_CONCAT(coil_no SEPARATOR ", ") from stock_details where stock_details.stock_id=stocks.id and stock_details.is_active=true) as coil_no from stocks left join vendors on vendors.id=stocks.vendor_id left join company_warehouse on company_warehouse.id=stocks.company_warehouse_id where stocks.is_active=true';
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data['values_posted'] = $this->input->post('data');
			$fromDate = $this->pktlib->dmYtoYmd($this->input->post('data[stocks][from_date]'));
			$toDate = $this->pktlib->dmYtoYmd($this->input->post('data[stocks][to_date]'));


			if($fromDate==$toDate){
				$sql.=' And stocks.inward_date like "'.$fromDate.'"';
			}else{
				$sql.=' And stocks.inward_date between "'.$fromDate.'" AND "'.$toDate.'"';

			}

			$sql.=' And stocks.is_active=true order by stocks.inward_date asc';
			//echo $sql;
			/*echo '<pre>';
			print_r($report);
			exit;*/
		}else{
			$sql.=' And stocks.inward_date like "'.date('Y-m-d').'" AND stocks.is_active=true';
		}
		
		$data['reports'] = $this->pktdblib->custom_query($sql);
		$data['meta_title'] = 'Report';
		$data['meta_description'] = 'Report';
		$data['title'] = 'Report';
		$data['heading'] = 'Stock Inward Report';
		$data['meta_keyword'] = 'Report';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'timely_report';
		$data['js'][] = "<script type=\"text/javascript\">
			$(document).ready(function() {
                // Setup - add a text input to each footer cell
                $('#search th').each( function () {
                    var title = $(this).text();
                    $(this).html( '<input type=\"text\" placeholder=\"Search '+title+'\" />' );
                } );
             
                // DataTable
                var table = $('#report').DataTable({paging:false,bAutoWidth:false});
             
                // Apply the search
                table.columns().every( function () {
                    var that = this;
             
                    $( 'input', this.header() ).on( 'keyup change', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    } );
                } );
            } );
		</script>";
		echo Modules::run("templates/report_template", $data);
	}

	function timely_report($data = []){
		//Datewise report
		$this->load->view('stocks/report_1');
	}

		// Add new order
	function admin_new_stock_out($orderId = NULL) {
		//Create order option for AJS impex
		//check_user_login(FALSE);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			//echo '<pre>';print_r($_POST);exit;
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			
			$this->form_validation->set_rules('data[stockout][outward_date]', 'Outward Date', 'required');
			$this->form_validation->set_rules('data[stockout][lorry_no]', 'Lorry Number', 'required');
			$this->form_validation->set_rules('data[stockout][broker_id]', 'Broker', 'required');
			$this->form_validation->set_rules('data[stockout][order_code]', 'Sales Order Number', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				//print_r($_FILES);exit;
				if(!empty($_FILES['invoice']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['invoice'], 'path'=>'../content/uploads/stocks/','ext'=>'gif|jpg|png|jpeg|pdf', 'fieldname'=>'invoice', 'arrindex'=>'invoice'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$_POST['data']['stockout']['invoice'] = $profileImg['filename'];
					}
					else {
						$error['invoice'] = $profileImg['error'];
					}
				}else{
					//$_POST['data']['stockout']['invoice'] = '';
				}

				if(empty($error)){
					//$this->pktdblib->db->trans_start(TRUE);
					$post_data = $_POST['data']['stockout'];
					$post_data['order_code'] = base64_decode($post_data['order_code']);
					$post_data['outward_date'] = $this->pktlib->dmYtoYmd($post_data['outward_date']);
					$post_data['created'] = $post_data['modified'] = date('Y-m-d H:i:s');
					$reg_stock = json_decode($this->_register_new_stockout($post_data), true);
					
					if($reg_stock['status'] === "success")
					{	
						//$_POST['data']['stocks'] = $reg_stock['stocks'];
						//$_POST['stockout_details']['stockout_id'] = $reg_stock['id'];
						$stockOutDetails = $this->input->post('stockout_details');
						$stockDetails = [];
						foreach ($stockOutDetails as $key => $detail) {
							if(isset($detail['order_detail_id']) && !empty($detail['order_detail_id'])){

								//$coil = $this->pktdblib->get_where($detail['stock_detail_id']);
								$this->pktdblib->set_table('order_details');
								$orderDetails = $this->pktdblib->get_where($detail['order_detail_id']);
								$orderDetails['is_active'] = 8;
								//echo '<pre>';print_r($orderDetails);exit;
								$this->pktdblib->set_table('stock_details');
								$coil = $this->pktdblib->custom_query('select sd.id as stock_detail_id, sd.coil_no, s.company_warehouse_id, sd.balance_qty from stock_details sd inner join stocks s on s.id=sd.stock_id where sd.coil_no="'.$orderDetails['coil_no'].'"');
								//print_r($coil);
								unset($stockOutDetails[$key]['unit_price']);
								//$stockOutDetails[$key]['coil_no'] = $coil['coil_no'];
								$stockOutDetails[$key]['product_id'] = $orderDetails['product_id'];
								$stockOutDetails[$key]['coil_no'] = $orderDetails['coil_no'];
								$stockOutDetails[$key]['stock_detail_id'] = $coil[0]['stock_detail_id'];
								$stockOutDetails[$key]['company_warehouse_id'] = $coil[0]['company_warehouse_id'];
								$stockOutDetails[$key]['stockout_id'] = $reg_stock['id'];
								$stockDetails[$key]['modified'] = $stockOutDetails[$key]['modified'] = $stockOutDetails[$key]['created'] = date('Y-m-d H:i:s');
								unset($stockOutDetails[$key]['order_detail_id']);

								$stockDetails[$key]['id'] = $coil[0]['stock_detail_id'];
								$stockDetails[$key]['balance_qty'] = $coil[0]['balance_qty']-$detail['qty'];
							}else{
								unset($stockOutDetails[$key]);
							}
						}
						//echo '<pre>';print_r($stockOutDetails);print_r($stockDetails);
						//exit;
						$this->pktdblib->set_table('stockout_details');
						$this->pktdblib->_insert_multiple($stockOutDetails);
						$this->pktdblib->set_table('stock_details');
						$this->pktdblib->update_multiple('id', $stockDetails);
						$msg = array('message'=>'Stock updated Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::new_stockout_url);
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
		}/*else{
			//if(NULL!==$orderId){
				if(isset($_GET['order_detail_id']) && !empty($_GET['order_detail_id'])){
			//echo "hii";exit;
					//echo $orderId;exit;
				$this->pktdblib->set_table('orders');
				$order = $this->pktdblib->get_where($orderId);
				$data['values_posted']['stockout']['order_code'] = $order['order_code'];
				$data['values_posted']['stockout']['broker_id'] = $order['broker_id'];

				$data['values_posted']['stockout_details'] = $this->pktdblib->custom_query('Select * from order_details where order_id='.$_GET['order_detail_id']);
				//print_r($data['values_posted']['stockout_details']);exit;

				$stock = [];
				//echo '<pre>';
				foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
					$data['option']['coil_no'][$detail['product_id']] = ['0'=>'Select Coil No'];
					//echo 'select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true and thickness='.$detail['thickness'].' and width='.$detail['width'].'<br>';
					$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true and thickness='.$detail['thickness'].' and width='.$detail['width']);
					foreach ($stockDetails as $key2 => $coilNo) {
						//print_r($coilNo);
						$data['option']['coil_no'][$detail['product_id']][$coilNo['id']] = $coilNo['coil_no'];
					}
				}
			}
			//print_r($option);exit;
		}*/
		//print_r($data['option']['coil_no']);
		
		
		

		$data['meta_title'] = "Stock Outward";
		$data['meta_description'] = "Stock Outward";
		$data['heading'] = "New Stock Out";
		$data['title'] = "Modules :: Stock";
		//echo "reached here"; exit;
		$data['modules'][] = "stocks";
		$data['methods'][] = "admin_stockout_form";
		$data['js'][] = '<script type="text/javascript">
            /*$(".product").on("change", function(){
            	var id = this.id;
            	//alert($(this).val());
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
				
            })*/
            $("body").on("change", ".stockDetail", function(){
            	var stockDetailId = $(this).val();
            	var trId = $(this).closest("tr").attr("id");
            	console.log(trId);
            	console.log(stockDetailId);
            	$.ajax({
					type: "POST",
					dataType: "json",
					url : base_url+"stocks/get_idwise_stockdetail/"+stockDetailId,
					success: function(response) {
						console.log(response);
						$("#thickness_"+trId).val(response.thickness);
						$("#company_warehouse_id_"+trId).val(response.company_warehouse_id).trigger("change.select2");
						
						$("#width_"+trId).val(response.width);
						$("#balance_qty_"+trId).val("Bal: "+response.balance_qty+" "+response.uom);
						$("#remark_"+trId).html("Remark: "+response.remark);
					}
		        
		        });
            });
            $("body").on("change",".orderWiseDetail", function(){
            	//console.log("hiii");
            	
            	var orderCode = $(this).val();
            	console.log(orderCode);
				url = base_url+"stocks/out?code="+orderCode;
				window.location.replace(url);
            	
            });
            $(".check_to_dispatch").click(function(){
            	var detailId = $(this).val();
            	var trId = $(this).closest("tr").attr("id");
            	var id = $(this).attr("id");
            	
		            if($(this).prop("checked") == true){
		                //alert("Checkbox is checked.");
		                $("#qty_"+trId).removeAttr("readonly");
		            }
		            else if($(this).prop("checked") == false){
		                //alert("Checkbox is unchecked.");
		                $("#qty_"+trId).attr("readonly", "readonly");

		            }
            })
            $("form").on("submit", function(){
            	/*alert("hii");
            	return false;*/
            	if($("#broker_id").val() <=0 || $("#broker_id").val() ==""){
            		alert("Please select Broker");
            		$("#broker_id").focus();
            		return false;
            	}

            	if($(".stock").val() <=0 || $(".stock").val() ==""){
            		alert("Please select Product");
            		$(this).focus();
            		return false;
            	}
            	var checkClass = $("input:checkbox.check_to_dispatch:checked").length;
            	//alert("class is checked "+checkClass);
            	if(checkClass==0){
            		alert("Please Select Product to Dispatch");
					return false;
            	}
            })
		</script>';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_stockout_form($data = []) {
		if(isset($_GET['order_detail_id']) && !empty($_GET['order_detail_id'])){
			//echo " hii";exit;
			$detailId= $_GET['order_detail_id'];
			$data['flag'] = 1;
			//echo $detailId;exit;
			//print_r($data['disabled']);exit;
			/*$this->pktdblib->set_table('orders');
			$orderCode = $this->pktdblib->custom_query('select od.*, o.order_code, broker_id from order_details od left join orders o on o.id=od.order_id left join brokers b on b.id=o.broker_id where od.id='.$detailId);
			$coil = $this->pktdblib->custom_query('select sd.*, s.company_warehouse_id from stock_details sd left join stocks s on s.id=sd.stock_id where sd.coil_no="'.$orderCode[0]['coil_no'].'"');*/
			$this->pktdblib->set_table('orders');
			//$orderData = $this->pktdblib->custom_query('select o.*, b.id as broker_id, concat(b.first_name, " ", b.middle_name, " ", b.surname) as broker_name from orders o inner join brokers b on b.id=o.broker_id where o.order_code="'.$code.'"'); 
			$orderDetails = $this->pktdblib->custom_query('select od.*,o.order_code, o.broker_id, concat(b.first_name, " ", b.middle_name, " ", b.surname)as broker_name, sd.id as stock_detail_id, s.company_warehouse_id, sd.balance_qty, p.product from order_details od inner join orders o on o.order_code=od.order_code inner join products p on p.id=od.product_id inner join stock_details sd on sd.coil_no=od.coil_no and sd.balance_qty>0 inner join stocks s on s.id=sd.stock_id inner join brokers b on b.id=o.broker_id where od.id='.$detailId);
			//echo $this->db->last_query();exit;
			//echo '<pre>';//print_r($orderData);
			/*if(empty($orderDetails)){
				redirect($_SERVER['HTTP_REFERER']);
			}*/
			//print_r($orderDetails);exit;
			$data['values_posted']['stockout']['order_code'] = base64_encode($orderDetails[0]['order_code']);
			$data['values_posted']['stockout']['broker_id'] = $orderDetails[0]['broker_id'];
			$data['values_posted']['stockout_details'] = $orderDetails;
			$data['values_posted']['stockout_details'][0]['stock_detail_id'] = $orderDetails[0]['stock_detail_id'];
			$data['values_posted']['stockout_details'][0]['company_warehouse_id'] = $orderDetails[0]['company_warehouse_id'];
			$data['values_posted']['stockout_details'][0]['balance_qty'] = $orderDetails[0]['balance_qty'];
			//echo '<pre>';print_r($data['values_posted']['stockout_details']);exit;

			$stock = [];
				//echo '<pre>';
				foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
					$data['option']['coil_no'][$detail['product_id']] = ['0'=>'Select Coil No'];
					$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and is_active= true');
					//print_r($stockDetails);//exit;
					foreach ($stockDetails as $key2 => $coilNo) {
						$data['option']['coil_no'][$detail['product_id']][$coilNo['id']] = $coilNo['coil_no'];
					}
				}

			//echo '<pre>';print_r($orderCode);exit;
			//print_r($data['values_posted']['stockout']['order_code']);//exit;
		}else if(isset($_GET['code']) && !empty($_GET['code'])){
			$code= base64_decode($_GET['code']);
			$data['flag'] = 1;
			$query = Modules::run('orders/pending_dispatch_order', ['order_code'=>$code]);
			//print_r($query);
			$orderData = [];
			$orderDetails = [];
			$counter = 0;
			foreach ($query as $key => $order) {
				$orderData['broker_name'] = $order['broker_name'];
				$orderData['broker_id'] = $order['broker_id'];
				$orderData['order_code'] = $order['order_code'];
				$orderData['po_no'] = $order['po_no'];
				$orderData['order_date'] = $order['order_date'];
				$orderData['message'] = $order['message'];
				$orderData['is_active'] = $order['is_active'];
				$orderDetails[] = $order;
				$counter++;
			}
			/*echo '<pre>';
			print_r($orderData);
			print_r($orderDetails);exit;*/
			//print_r($data['disabled']);exit;
			//echo ($code);exit;
			/*$this->pktdblib->set_table('orders');
			$orderData = $this->pktdblib->custom_query('select o.*, b.id as broker_id, concat(b.first_name, " ", b.middle_name, " ", b.surname) as broker_name from orders o inner join brokers b on b.id=o.broker_id where o.order_code="'.$code.'"'); 
			$query = 'select od.*, sd.id as stock_detail_id, s.company_warehouse_id, sd.balance_qty, p.product from order_details od inner join orders o on o.order_code=od.order_code inner join products p on p.id=od.product_id inner join stock_details sd on sd.coil_no=od.coil_no and sd.balance_qty>0 inner join stocks s on s.id=sd.stock_id where o.is_active=true and od.is_active=true and o.order_code="'.$code.'" and od.order_code not in (select od.order_code from order_details od inner join orders o on o.order_code=od.order_code and o.is_active=true inner join stockout so on so.order_code=o.order_code inner join stockout_details sod on sod.stockout_id=so.id and sod.coil_no=od.coil_no and sod.product_id=od.product_id)';
			echo $query;
			$orderDetails = $this->pktdblib->custom_query($query);
			echo '<pre>';print_r($orderDetails);exit;*/
			/*if(empty($orderDetails)){
				redirect($_SERVER['HTTP_REFERER']);
			}*/
			/*echo '<pre>';
			print_r($orderData);
			print_r($orderDetails);exit;*/
			$orderData['order_code'] = base64_encode($orderData['order_code']);
			$data['values_posted']['stockout'] = $orderData;
			$data['values_posted']['stockout_details'] = $orderDetails;
			$stock = [];
				//echo '<pre>';
			foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
				$data['option']['coil_no'][$detail['product_id']] = ['0'=>'Select Coil No'];
				$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and is_active= true');
				//print_r($stockDetails);//exit;
				foreach ($stockDetails as $key2 => $coilNo) {
					$data['option']['coil_no'][$detail['product_id']][$coilNo['id']] = $coilNo['coil_no'];
				}
			}

			//echo '<pre>';print_r($orderCode);exit;
			//print_r($data['values_posted']['stockout']['order_code']);//exit;
		}

		//echo "reached here";
		$this->pktdblib->set_table('brokers');
		$brokers = $this->pktdblib->get('created asc');
		$data['option']['broker'][] = 'Select Broker';
		foreach ($brokers->result_array() as $brokerKey => $broker) {
			$data['option']['broker'][$broker['id']] = $broker['company_name']." (".$broker['first_name']." ".$broker['middle_name']." ".$broker['surname'].")";
		}

		$this->pktdblib->set_table('company_warehouse');
		$query = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['companyWarehouse'][0] = 'Select Warehouse/Cutter';
		foreach ($query->result_array() as $warehouseKey => $warehouse) {
			$data['option']['companyWarehouse'][$warehouse['id']] = $warehouse['warehouse'];
		}

		$data['option']['uom'] = $this->pktlib->uom();

		$data['option']['stock_detail'][0] = 'Select Coil No';
		$data['option']['referred_by'] = $this->referredby_option();
		$data['products'] = $this->product_model->get_active_list();
		
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}

		/*$this->pktdblib->set_table('orders');
		$data['option']['orderCodes'][] = "Select";
		$query = $this->pktdblib->get_where_custom('is_active', '1');

		foreach ($query->result_array() as $key => $orders) {
			$data['option']['orderCodes'][$orders['order_code']] = $orders['order_code'];
		}*/
		$data['option']['orderCodes'][] = 'Select';
		$orderCode = Modules::run('orders/pending_dispatch_order');
		foreach ($orderCode as $key => $order) {
			$data['option']['orderCodes'][base64_encode($order['order_code'])] = $order['order_code'];
		}
		$this->load->view("stocks/admin_stockout_form",$data);
	}
	
	function admin_new_stock_out_2($orderId = NULL) {
		//Create order option for AJS impex
		//check_user_login(FALSE);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			
			$this->form_validation->set_rules('data[stockout][outward_date]', 'Outward Date', 'required');
			$this->form_validation->set_rules('data[stockout][lorry_no]', 'Lorry Number', 'required');
			$this->form_validation->set_rules('data[stockout][broker_id]', 'Broker', 'required');
			$this->form_validation->set_rules('data[stockout][order_code]', 'Sales Order Number', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				//print_r($_FILES);exit;
				if(!empty($_FILES['invoice']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['invoice'], 'path'=>'../content/uploads/stocks/','ext'=>'gif|jpg|png|jpeg|pdf', 'fieldname'=>'invoice', 'arrindex'=>'invoice'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$_POST['data']['stockout']['invoice'] = $profileImg['filename'];
					}
					else {
						$error['invoice'] = $profileImg['error'];
					}
				}else{
					//$_POST['data']['stockout']['invoice'] = '';
				}

				if(empty($error)){
					//$this->pktdblib->db->trans_start(TRUE);
					$post_data = $_POST['data']['stockout'];
					$post_data['outward_date'] = $this->pktlib->dmYtoYmd($post_data['outward_date']);
					$post_data['created'] = $post_data['modified'] = date('Y-m-d H:i:s');
					$reg_stock = json_decode($this->_register_new_stockout($post_data), true);
					
					if($reg_stock['status'] === "success")
					{
						//$_POST['data']['stocks'] = $reg_stock['stocks'];
						//$_POST['stockout_details']['stockout_id'] = $reg_stock['id'];
						$stockOutDetails = $this->input->post('stockout_details');
						$stockDetails = [];
						$this->pktdblib->set_table('stock_details');
						foreach ($stockOutDetails as $key => $detail) {
							$coil = $this->pktdblib->get_where($detail['stock_detail_id']);
							unset($stockOutDetails[$key]['unit_price']);
							$stockOutDetails[$key]['coil_no'] = $coil['coil_no'];
							$stockOutDetails[$key]['stockout_id'] = $reg_stock['id'];
							$stockOutDetails[$key]['created'] = $stockOutDetails[$key]['modified'] = date('Y-m-d H:i:s');
							$stockDetails[$key]['id'] = $detail['stock_detail_id'];
							$stockDetails[$key]['balance_qty'] = $coil['balance_qty']-$detail['qty'];
						}
						//echo '<pre>'; print_r($stockOutDetails);exit;
						$this->pktdblib->set_table('stockout_details');
						$this->pktdblib->_insert_multiple($stockOutDetails);
						$this->pktdblib->set_table('stock_details');
						$this->pktdblib->update_multiple('id', $stockDetails);
						$msg = array('message'=>'Stock updated Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::direct_stockout_url);
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
		}else{

			if(NULL!==$orderId){
				$this->pktdblib->set_table('orders');
				$order = $this->pktdblib->get_where($orderId);
				$data['values_posted']['stockout']['order_code'] = $order['order_code'];
				$data['values_posted']['stockout']['broker_id'] = $order['broker_id'];

				$data['values_posted']['stockout_details'] = $this->pktdblib->custom_query('Select * from order_details where order_id='.$orderId);
				$stock = [];
				//echo '<pre>';
				foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
					$data['option']['coil_no'][$detail['product_id']] = ['0'=>'Select Coil No'];
					//echo 'select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true and thickness='.$detail['thickness'].' and width='.$detail['width'].'<br>';
					$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true and thickness='.$detail['thickness'].' and width='.$detail['width']);
					foreach ($stockDetails as $key2 => $coilNo) {
						//print_r($coilNo);
						$data['option']['coil_no'][$detail['product_id']][$coilNo['id']] = $coilNo['coil_no'];
					}
				}
			}
			//print_r($option);exit;
		}
		//print_r($data['option']['coil_no']);
		
		
		$this->pktdblib->set_table('brokers');
		$brokers = $this->pktdblib->get('created asc');
		$data['option']['broker'][] = 'Select Broker';
		foreach ($brokers->result_array() as $brokerKey => $broker) {
			$data['option']['broker'][$broker['id']] = $broker['company_name']." (".$broker['first_name']." ".$broker['middle_name']." ".$broker['surname'].")";
		}
		
		$this->pktdblib->set_table('customers');
		$customers = $this->pktdblib->get('created asc');
		$data['option']['customers'][] = 'Select Party';
		/*echo '<pre>';
		print_r($customers->result_array());exit;*/
		foreach ($customers->result_array() as $customerKey => $customer) {
			$data['option']['customers'][$customer['company_name']] = $customer['company_name'];
		}

		$this->pktdblib->set_table('company_warehouse');
		$query = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['companyWarehouse'][0] = 'Select Warehouse/Cutter';
		foreach ($query->result_array() as $warehouseKey => $warehouse) {
			$data['option']['companyWarehouse'][$warehouse['id']] = $warehouse['warehouse'];
		}

		$data['option']['uom'] = $this->pktlib->uom();

		$data['option']['stock_detail'][0] = 'Select Coil No';
		$data['option']['referred_by'] = $this->referredby_option();
		$data['products'] = $this->product_model->get_active_list();
		
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}

		$this->pktdblib->set_table('orders');
		$data['option']['orderCodes'][] = "Select";
		$query = $this->pktdblib->get_where_custom('is_active', '1');

		foreach ($query->result_array() as $key => $orders) {
			$data['option']['orderCodes'][$orders['order_code']] = $orders['order_code'];
		}
		$data['meta_title'] = "Stock Outward";
		$data['meta_description'] = "Stock Outward";
		$data['heading'] = "New Stock Out";
		$data['title'] = "Modules :: Stock";
		//echo "reached here"; exit;
		$data['modules'][] = "stocks";
		$data['methods'][] = "admin_stockout_form_2";
		$data['js'][] = '<script type="text/javascript">
            /*$(".product").on("change", function(){
            	var id = this.id;
            	//alert($(this).val());
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
				
            })*/
            $("body").on("change", ".stockDetail", function(){
            	var stockDetailId = $(this).val();
            	var trId = $(this).closest("tr").attr("id");
            	console.log(trId);
            	console.log(stockDetailId);
            	$.ajax({
					type: "POST",
					dataType: "json",
					url : base_url+"stocks/get_idwise_stockdetail/"+stockDetailId,
					success: function(response) {
						console.log(response);
						$("#thickness_"+trId).val(response.thickness);
						$("#company_warehouse_id_"+trId).val(response.company_warehouse_id).trigger("change.select2");
						
						$("#width_"+trId).val(response.width);
						$("#balance_qty_"+trId).val("Bal: "+response.balance_qty+" "+response.uom);
						$("#remarklabel_"+trId).text("Remark: "+response.remark);
					}
		        
		        });
            });
            $("form").on("submit", function(){
            	/*alert("hii");
            	return false;*/
            	if($("#broker_id").val() <=0 || $("#broker_id").val() ==""){
            		alert("Please select Broker");
            		$("#broker_id").focus();
            		return false;
            	}

            	if($(".stock").val() <=0 || $(".stock").val() ==""){
            		alert("Please select Product");
            		$(this).focus();
            		return false;
            	}
            })
		</script>';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_stockout_form_2($data = []) {
		
		//echo "reached here";
		$this->load->view("stocks/admin_stockout_form_2");
	}

	function getProductwiseDetail($data = []){
		$productId = '';
		//echo '<pre>';
		if(!$this->input->post('params')){
			echo json_encode(['msg'=>'Invalid Request', 'status'=>'fail']);
			exit;
		}
		if(!empty($data)){
			$_POST['params'] = $data['product_id'];
		}
		$productId = $this->input->post('params');
		//echo json_encode($productId);exit;
		$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where product_id='.$productId.' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true');
		$arr = [0=>['id'=>0, 'text'=>'Select Coil']];
		foreach ($stockDetails as $key => $detail) {
			$arr[$key+1]['id'] = $detail['id'];
			$arr[$key+1]['text'] = $detail['coil_no'];
		}
		//echo json_encode($stockDetails);exit;
		
		echo json_encode($arr);
		exit;
	}

	function _register_new_stockout($data) {
		
		$this->pktdblib->set_table("stockout");
		$id = $this->pktdblib->_insert($data);
		//$data = $this->stock_details($id['id']);
		return json_encode(['message' =>'Stock Added Successfully', "status"=>"success", 'id'=> $id['id']]);
	}

	function delivery_report(){
		$sql = 'Select stockout_details.*, stockout.order_code, concat(brokers.first_name," ", brokers.middle_name, " ", brokers.surname) as broker, brokers.company_name, company_warehouse.warehouse, stock_details.grade, stockout.outward_date, stockout.lorry_no, products.product from stockout_details inner join stockout on stockout.id=stockout_details.stockout_id left join brokers on brokers.id=stockout.broker_id left join company_warehouse on company_warehouse.id=stockout_details.company_warehouse_id inner join stock_details on stock_details.id=stockout_details.stock_detail_id inner join stocks on stocks.id=stock_details.stock_id inner join products on products.id=stock_details.product_id where 1=1';
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data['values_posted'] = $this->input->post('data');
			$fromDate = $this->pktlib->dmYtoYmd($this->input->post('data[stocks][from_date]'));
			$toDate = $this->pktlib->dmYtoYmd($this->input->post('data[stocks][to_date]'));


			if($fromDate==$toDate){
				$sql.=' And stockout.outward_date like "'.$fromDate.'"';
			}else{
				$sql.=' And stockout.outward_date between "'.$fromDate.'" AND "'.$toDate.'"';

			}
			
			if($this->input->post('data[stocks][coil_no]')!=''){
			    $sql.=' AND stockout_details.coil_no="'.$this->input->post('data[stocks][coil_no]').'"';
			}

			$sql.=' And stockout.is_active=true And stockout_details.is_active=true order by stockout.outward_date asc, stockout.order_code';
			//echo $sql;
			/*echo '<pre>';
			print_r($report);
			exit;*/
		}else{
			$sql.=' And stockout.outward_date like "'.date('Y-m-d').'" AND stockout.is_active=true And stockout_details.is_active=true';
		}
		
		$data['reports'] = $this->pktdblib->custom_query($sql);
		/*echo '<pre>';
		print_r($data['reports']);exit;*/
		$data['meta_title'] = 'Report';
		$data['meta_description'] = 'Report';
		$data['title'] = 'Report';
		$data['heading'] = 'Delivery Report';
		$data['meta_keyword'] = 'Report';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'delivery_report_format1';
		/*$data['js'][] = '<script type="text/javascript">
			$(".report").DataTable({
		      "paging": false,
		      "autoWidth": true
		    });
		</script>';*/
		echo Modules::run("templates/report_template", $data);
	}

	function delivery_report_format1($data = []){
		//Datewise report
		$this->load->view('stocks/delivery_report');
	}

	function stock_report(){
		$sql2 = 'select sum(order_details.qty) as qty, order_details.coil_no from order_details INNER JOIN orders on orders.order_code=order_details.order_code INNER JOIN brokers on brokers.id=orders.broker_id inner join customers on customers.id=orders.customer_id INNER JOIN products on products.id=order_details.product_id inner join stock_details on stock_details.coil_no=order_details.coil_no and stock_details.product_id=order_details.product_id and stock_details.balance_qty>0 INNER JOIN stocks on stocks.id=stock_details.stock_id WHERE orders.is_active=true and order_details.is_active=true and order_details.id not in (SELECT od.id FROM `order_details` od INNER JOIN orders o on o.order_code=od.order_code INNER JOIN stockout s on s.order_code=o.order_code INNER JOIN stockout_details sod on sod.stockout_id=s.id and sod.product_id=od.product_id and sod.coil_no=od.coil_no) group by order_details.coil_no';

		$orderedQty = $this->pktdblib->custom_query($sql2);
		/*echo $this->db->last_query();exit;*/
		//echo "<pre>"; print_r($orderedQty);exit;
		$data['orderDetails'] = [];
		foreach ($orderedQty as $key => $od) {
			$data['orderDetails'][$od['coil_no']] = $od['qty'];
		}
		$sql = 'Select stock_details.*, stocks.stock_code, stocks.lot_no as stock_lot, concat(vendors.first_name," ", vendors.middle_name, " ", vendors.surname) as vendor, vendors.company_name, company_warehouse.warehouse, product, stocks.grade as stock_grade, stocks.inward_date from stock_details inner join stocks on stocks.id=stock_details.stock_id left join products on products.id=stock_details.product_id left join vendors on vendors.id=stocks.vendor_id left join company_warehouse on company_warehouse.id=stocks.company_warehouse_id where stock_details.is_active=true and stocks.is_active=true And stock_details.balance_qty>=1';
		if(NULL!==$this->input->post('vendor_id') && $this->input->post('vendor_id')!=0){
            $sql.= ' AND stocks.vendor_id='.$this->input->post('vendor_id');
        }

        if(NULL!==$this->input->post('warehouse_id') && $this->input->post('warehouse_id')!=0){
            $sql.= ' AND stocks.company_warehouse_id like "'.$this->input->post('warehouse_id').'"';
        }
        if(NULL!==$this->input->post('product_id') && $this->input->post('product_id')!=0){
            $sql.= ' AND stock_details.product_id like "'.$this->input->post('product_id').'"';
        }
        
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data['values_posted'] = $this->input->post('data');
			$fromDate = $this->pktlib->dmYtoYmd($this->input->post('data[stocks][from_date]'));
			$toDate = $this->pktlib->dmYtoYmd($this->input->post('data[stocks][to_date]'));


			if($fromDate==$toDate){
				$sql.=' And stocks.inward_date like "'.$fromDate.'"';
			}else{
				$sql.=' And stocks.inward_date between "'.$fromDate.'" AND "'.$toDate.'"';

			}

			
			//echo $sql;
			/*echo '<pre>';
			print_r($report);
			exit;*/
		}else{
			$sql.=' And stocks.inward_date BETWEEN "'.date('Y-m-d', strtotime('-6 month')).'" AND "'.date('Y-m-d').'"';
		}
		$sql.=' order by stock_details.balance_qty desc';
		//echo $sql;
		//echo $this->db->last_query();exit;
		$data['reports'] = $this->pktdblib->custom_query($sql);
		$this->pktdblib->set_table('company_warehouse');
		$warehouses = $this->pktdblib->get_where_custom('is_active', true);
		//$data['warehouse'] = $warehouse->result_array();
		$data['option']['warehouse'][0] = 'Select Warehouse';
		foreach ($warehouses->result_array() as $warehouseKey => $value) {
			$data['option']['warehouse'][$value['id']] = $value['warehouse'];
		}

		$this->pktdblib->set_table('products');
		$product = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['product'][0] = 'Select Product';
		foreach ($product->result_array() as $productKey => $value) {
			$data['option']['product'][$value['id']] = $value['tally_name'];
		}
		//echo '<pre>';print_r($data['option']);exit;
		$this->pktdblib->set_table('vendors');
		$vendor = $this->pktdblib->get_where_custom('is_active', true);
		//$data['vendors'] = $vendor->result_array();
		$data['option']['vendors'][0] = 'Select Vendor';
		foreach ($vendor->result_array() as $vendorKey => $value) {
			$data['option']['vendors'][$value['id']] = $value['first_name']." ".$value['middle_name']." ".$value['surname']." - ".$value['emp_code'];
		}
		/*echo '<pre>';
		print_r($data['reports']);exit;*/
		$data['meta_title'] = 'Report';
		$data['meta_description'] = 'Report';
		$data['title'] = 'Report';
		$data['heading'] = 'Stock Report';
		$data['meta_keyword'] = 'Report';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'stock_report_format1';
		$data['js'][] = "<script type=\"text/javascript\">
						$(document).ready(function() {

                $('#search th').each( function () {
                    var title = $(this).text();
                    $(this).html( '<input type=\"text\" placeholder=\"Search '+title+'\" />' );
                } );

			    $('#report').dataTable({	
					paging:false,
					footerCallback: function ( row, data, start, end, display ) {
							var table = this.api(), data;	 
							// Remove the formatting to get integer data for summation
							var intVal = function ( i ) {
								return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ?	i : 0;
							};
							table.columns().every( function () {
			                    var that = this;
			                    $( 'input', this.header() ).on( 'keyup change', function () {
			                        if ( that.search() !== this.value ) {
			                            that
			                                .search( this.value )
			                                .draw();
			                        }
			                    } );
			                } );

			                // total_quantity over all pages
							total_nquantity = table.column( 12 ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							},0 );
							
							// total_balance_quantity over this page
							total_net_quantity = table.column( 12, { page: 'current'} ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );

							// total_quantity over all pages
							total_bquantity = table.column( 13 ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							},0 );
							
							// total_balance_quantity over this page
							total_booked_quantity = table.column( 13, { page: 'current'} ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
				 
							// total_quantity over all pages
							total_pquantity = table.column( 14 ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							},0 );
							
							// total_balance_quantity over this page
							total_physical_quantity = table.column( 14, { page: 'current'} ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							
							total_net_quantity = parseFloat(total_net_quantity);
							total_nquantity = parseFloat(total_nquantity);
							total_booked_quantity = parseFloat(total_booked_quantity);
							total_bquantity = parseFloat(total_bquantity);
							total_physical_quantity = parseFloat(total_physical_quantity);
							total_pquantity = parseFloat(total_pquantity);
							// Update footer
							$('#netquantity').html(total_net_quantity.toFixed(3));
							$('#bookedquantity').html(total_booked_quantity.toFixed(3));	
							$('#physicalquantity').html(total_physical_quantity.toFixed(3));				

						},		
				});
			});
		</script>";


		/*$data['js'][] = "<script type=\"text/javascript\">
			$(document).ready(function() {
                // Setup - add a text input to each footer cell
                $('#search th').each( function () {
                    var title = $(this).text();
                    $(this).html( '<input type=\"text\" placeholder=\"Search '+title+'\" />' );
                } );

                // DataTable
                var table = $('#report').DataTable({paging:false,bAutoWidth:false});
                // Apply the search
                table.columns().every( function () {
                    var that = this;
                    $( 'input', this.header() ).on( 'keyup change', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    } );
                } );
            } );
		</script>";*/
		/*echo "<pre>";
		print_r($data);*/
		echo Modules::run("templates/report_template", $data);
	}

	function stock_report_format1($data = []){
		//Datewise report
		$this->load->view('stocks/stock_report');
	}

	function admin_edit_stock_out($stockOutId = NULL) {
	    //echo "hii";exit;
		//Create order option for AJS impex
		//check_user_login(FALSE);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			//echo '<pre>';print_r($_POST);exit;
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			
			$this->form_validation->set_rules('data[stockout][outward_date]', 'Outward Date', 'required');
			$this->form_validation->set_rules('data[stockout][lorry_no]', 'Lorry Number', 'required');
			$this->form_validation->set_rules('data[stockout][broker_id]', 'Broker', 'required');
			$this->form_validation->set_rules('data[stockout][order_code]', 'Sales Order Number', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				//print_r($_FILES);exit;
				if(!empty($_FILES['invoice']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['invoice'], 'path'=>'../content/uploads/stocks/','ext'=>'gif|jpg|png|jpeg|pdf', 'fieldname'=>'invoice', 'arrindex'=>'invoice'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$_POST['data']['stockout']['invoice'] = $profileImg['filename'];
					}
					else {
						$error['invoice'] = $profileImg['error'];
					}
				}else{
					//$_POST['data']['stockout']['invoice'] = '';
				}

				if(empty($error)){
					//$this->pktdblib->db->trans_start(TRUE);
					$post_data = $_POST['data']['stockout'];
					$post_data['outward_date'] = $this->pktlib->dmYtoYmd($post_data['outward_date']);
					$post_data['created'] = $post_data['modified'] = date('Y-m-d H:i:s');
					$this->pktdblib->set_table('stockout');
					$stockOut = $this->pktdblib->_update($stockOutId, $post_data);
					
					if($stockOut){
						$stockOutDetails = $this->input->post('stockout_details');
						$stockDetails = [];
						$this->pktdblib->set_table('stock_details');
						//echo '<pre>';
						foreach ($stockOutDetails as $key => $detail) {
							//print_r($detail);
							$this->pktdblib->set_table('stock_details');
							$stockOutDetail = $this->pktdblib->get_where($detail['id']);
							//print_r($stockOutDetail);
							/*if($detail['stock_detail_id'] == $stockOutDetail['id']){
								echo "coil no n quantity is same";
							}else{
								echo "coil no n quantity is different";

							}*/
							//print_r($stockDetail);
							$coil = $this->pktdblib->get_where($detail['stock_detail_id']);
							unset($stockOutDetails[$key]['unit_price']);
							$stockOutDetails[$key]['coil_no'] = $coil['coil_no'];
							$stockOutDetails[$key]['stockout_id'] = $stockOutId;
							$stockOutDetails[$key]['modified'] = date('Y-m-d H:i:s');
							/*$stockDetails[$key]['modified'] = $stockOutDetails[$key]['modified'] = $stockOutDetails[$key]['created'] = date('Y-m-d H:i:s');
							$stockDetails[$key]['id'] = $detail['stock_detail_id'];
							$stockDetails[$key]['balance_qty'] = $coil['balance_qty']-$detail['qty'];*/
						}
						//exit;
						$this->pktdblib->set_table('stockout_details');
						$this->pktdblib->update_multiple('id',$stockOutDetails);
						/*$this->pktdblib->set_table('stock_details');
						$this->pktdblib->update_multiple('id', $stockDetails);*/
						$msg = array('message'=>'Stock updated Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect('stocks/admin_edit_stock_out/'.$stockOutId);
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
		}else{
            
			if(NULL!==$stockOutId){
			    //echo "reached here";exit;
				$this->pktdblib->set_table('stockout');
				$stockOut = $this->pktdblib->get_where($stockOutId);
				$this->pktdblib->set_table('stockout_details');
				$stockOutDetails = $this->pktdblib->get_where_custom('stockout_id', $stockOutId);
				
				//echo '<pre>';
				//print_r($stockOut);print_r($stockOutDetails->result_array());//exit;
				$data['values_posted']['stockout'] = $stockOut;
				//$data['values_posted']['stockout']['broker_id'] = $stockOut['broker_id'];

				$data['values_posted']['stockout_details'] = $this->pktdblib->custom_query('Select * from stockout_details where stockout_id='.$stockOutId);
				
				//echo '<pre>';print_r($data['values_posted']['stockout']);print_r($data['values_posted']['stockout_details']);exit;
				$stock = [];
				//echo '<pre>';
				foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
					//print_r($detail['coil_no']);
					$data['option']['coil_no'][$detail['product_id']] = ['0'=>'Select Coil No'];
					//echo 'select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true and thickness='.$detail['thickness'].' and width='.$detail['width'].'<br>';
					//print_r($data['option']);
					$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and is_active= true');
					//print_r($stockDetails);exit;
					foreach ($stockDetails as $key2 => $coilNo) {
						//print_r($coilNo);
						$data['option']['coil_no'][$detail['product_id']][$coilNo['id']] = $coilNo['coil_no'];
						//$data['option']['coil_no'][$coilNo['coil_no']] = $coilNo['coil_no'];
						//echo '<pre>';print_r($data['option']['coil_no'][$detail['product_id']][$coilNo['id']]);
					}
				}
				//print_r($data['option']['coil_no']);
				//exit;
			}
			//print_r($option);exit;
		}
		//print_r($data['option']['coil_no']);
		//echo "hii";exit;
		$data['id'] = $stockOutId;
		$this->pktdblib->set_table('brokers');
		$brokers = $this->pktdblib->get('created asc');
		$data['option']['broker'][] = 'Select Broker';
		foreach ($brokers->result_array() as $brokerKey => $broker) {
			$data['option']['broker'][$broker['id']] = $broker['company_name']." (".$broker['first_name']." ".$broker['middle_name']." ".$broker['surname'].")";
		}

		$this->pktdblib->set_table('company_warehouse');
		$query = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['companyWarehouse'][0] = 'Select Warehouse/Cutter';
		foreach ($query->result_array() as $warehouseKey => $warehouse) {
			$data['option']['companyWarehouse'][$warehouse['id']] = $warehouse['warehouse'];
		}

		$data['option']['uom'] = $this->pktlib->uom();

		$data['option']['stock_detail'][0] = 'Select Coil No';
		$data['option']['referred_by'] = $this->referredby_option();
		$data['products'] = $this->product_model->get_active_list();
		
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}

		$this->pktdblib->set_table('orders');
		$data['option']['orderCodes'][] = "Select";
		$query = $this->pktdblib->get_where_custom('is_active', '1');

		foreach ($query->result_array() as $key => $orders) {
			$data['option']['orderCodes'][$orders['order_code']] = $orders['order_code'];
		}
		$data['meta_title'] = "Stock Outward";
		$data['meta_description'] = "Stock Outward";
		$data['heading'] = "New Stock Out";
		$data['title'] = "Modules :: Stock";
		//echo "reached here"; exit;
		$data['modules'][] = "stocks";
		$data['methods'][] = "admin_edit_stockout_form";
		$data['js'][] = '<script type="text/javascript">
            /*$(".product").on("change", function(){
            	var id = this.id;
            	//alert($(this).val());
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
				
            })*/
            $("body").on("change", ".stockDetail", function(){
            	var stockDetailId = $(this).val();
            	var trId = $(this).closest("tr").attr("id");
            	console.log(trId);
            	console.log(stockDetailId);
            	$.ajax({
					type: "POST",
					dataType: "json",
					url : base_url+"stocks/get_idwise_stockdetail/"+stockDetailId,
					success: function(response) {
						console.log(response);
						$("#thickness_"+trId).val(response.thickness);
						$("#company_warehouse_id_"+trId).val(response.company_warehouse_id).trigger("change.select2");
						
						$("#width_"+trId).val(response.width);
						$("#balance_qty_"+trId).val("Bal: "+response.balance_qty+" "+response.uom);
						console.log(response.remark);
						$("#remark_"+trId).text("Remark: "+response.remark);
					}
		        
		        });
            });
            $("form").on("submit", function(){
            	/*alert("hii");
            	return false;*/
            	if($("#broker_id").val() <=0 || $("#broker_id").val() ==""){
            		alert("Please select Broker");
            		$("#broker_id").focus();
            		return false;
            	}

            	if($(".stock").val() <=0 || $(".stock").val() ==""){
            		alert("Please select Product");
            		$(this).focus();
            		return false;
            	}
            })
		</script>';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_edit_stockout_form($data = []) {
		
		//echo "reached here";
		$this->load->view("stocks/admin_edit_stockout_form");
	}

	function admin_order_detail_listing(){
		$sql = 'select concat(c.first_name, " ",c.middle_name, " ",c.surname) as customer_name, concat(b.first_name, " ", b.middle_name, " ", b.surname) as broker_name, o.order_code, o.order_date, p.product, od.* from order_details od left join orders o on o.order_code=od.order_code left join customers c on c.id=o.customer_id left join brokers b on b.id=o.broker_id left join products p on p.id=od.product_id where od.id not in (select od.id from order_details od inner join orders o on o.order_code=od.order_code and o.is_active=true inner join stockout so on so.order_code=o.order_code inner join stockout_details sod on sod.stockout_id=so.id and sod.coil_no=od.coil_no and sod.product_id=od.product_id)';
		$data['orderDetail'] = $this->pktdblib->custom_query($sql);
		//echo $this->db->last_query();
		//echo '<pre>';print_r($data['orderDetail']);exit;
		$data['meta_title'] = 'Stock Dispatch';
		$data['meta_description'] = 'Stock Dispatch';
		$data['title'] = 'Stock Dispatch';
		$data['heading'] = 'Stock Stock Dispatch';
		$data['meta_keyword'] = 'Stock Dispatch';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'dispatch_stock_wise';
		echo Modules::run("templates/admin_template", $data);
	}

	function dispatch_stock_wise(){
		$this->load->view('stocks/order_wise_dispatch');
	}

	function order_wise_order_detail(){
		$this->pktdblib->set_table('order_details');
		$orderDetails = $this->pktdblib->get_where_custom('order_code', $orderCode);
		print_r($orderDetails);
	}

	function admin_stock_report(){
		$sql2 = 'select sum(order_details.qty) as qty, order_details.coil_no from order_details INNER JOIN orders on orders.order_code=order_details.order_code INNER JOIN brokers on brokers.id=orders.broker_id inner join customers on customers.id=orders.customer_id INNER JOIN products on products.id=order_details.product_id inner join stock_details on stock_details.coil_no=order_details.coil_no and stock_details.product_id=order_details.product_id and stock_details.balance_qty>0 INNER JOIN stocks on stocks.id=stock_details.stock_id WHERE orders.is_active=true and order_details.is_active=true and order_details.id not in (SELECT od.id FROM `order_details` od INNER JOIN orders o on o.order_code=od.order_code INNER JOIN stockout s on s.order_code=o.order_code INNER JOIN stockout_details sod on sod.stockout_id=s.id and sod.product_id=od.product_id and sod.coil_no=od.coil_no) group by order_details.coil_no';

		$orderedQty = $this->pktdblib->custom_query($sql2);
		$data['orderDetails'] = [];
		foreach ($orderedQty as $key => $od) {
			$data['orderDetails'][$od['coil_no']] = $od['qty'];
		}
		//print_r($data['orderDetails']);exit;
		/*echo '<pre>';
		print_r($orderedQty);
		exit;*/
		$sql = 'Select stock_details.*, stocks.stock_code, stocks.lot_no as stock_lot, concat(vendors.first_name," ", vendors.middle_name, " ", vendors.surname) as vendor, vendors.company_name, company_warehouse.warehouse, product, stocks.grade as stock_grade, stocks.inward_date from stock_details inner join stocks on stocks.id=stock_details.stock_id left join products on products.id=stock_details.product_id left join vendors on vendors.id=stocks.vendor_id left join company_warehouse on company_warehouse.id=stocks.company_warehouse_id where  stock_details.is_active=true and stocks.is_active=true And stock_details.balance_qty>=1';
		if(NULL!==$this->input->post('vendor_id') && $this->input->post('vendor_id')!=0){
            $sql.= ' AND stocks.vendor_id='.$this->input->post('vendor_id');
        }

        if(NULL!==$this->input->post('warehouse_id') && $this->input->post('warehouse_id')!=0){
            $sql.= ' AND stocks.company_warehouse_id like "'.$this->input->post('warehouse_id').'"';
        }
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data['values_posted'] = $this->input->post('data');
			$fromDate = $this->pktlib->dmYtoYmd($this->input->post('data[stocks][from_date]'));
			$toDate = $this->pktlib->dmYtoYmd($this->input->post('data[stocks][to_date]'));


			if($fromDate==$toDate){
				$sql.=' And stocks.inward_date like "'.$fromDate.'"';
			}else{
				$sql.=' And stocks.inward_date between "'.$fromDate.'" AND "'.$toDate.'"';

			}

			//$sql.=' order by stocks.inward_date asc';
			//echo $sql;
			/*echo '<pre>';
			print_r($report);
			exit;*/
		}else{
			$sql.=' And stocks.inward_date BETWEEN "'.date('Y-m-d', strtotime('-6 month')).'" AND "'.date('Y-m-d').'"';
		}
		//$sql.='  and stock_details.product_id=8 and stock_details.grade="9002"';
		$sql.=' order by stock_details.balance_qty desc';
		//echo $sql;
		$this->pktdblib->set_table('company_warehouse');
		$warehouses = $this->pktdblib->get_where_custom('is_active', true);
		//$data['warehouse'] = $warehouse->result_array();
		$data['option']['warehouse'][0] = 'Select Warehouse';
		foreach ($warehouses->result_array() as $warehouseKey => $value) {
			$data['option']['warehouse'][$value['id']] = $value['warehouse'];
		}
		//echo '<pre>';print_r($data['option']);exit;
		$this->pktdblib->set_table('vendors');
		$vendor = $this->pktdblib->get_where_custom('is_active', true);
		//$data['vendors'] = $vendor->result_array();
		$data['option']['vendors'][0] = 'Select Vendor';
		foreach ($vendor->result_array() as $vendorKey => $value) {
			$data['option']['vendors'][$value['id']] = $value['first_name']." ".$value['middle_name']." ".$value['surname']." - ".$value['emp_code'];
		}
		$data['reports'] = $this->pktdblib->custom_query($sql);
		/*echo '<pre>';
		print_r($data['reports']);exit;*/
		$data['meta_title'] = 'Report';
		$data['meta_description'] = 'Report';
		$data['title'] = 'Report';
		$data['heading'] = 'Stock Report';
		$data['meta_keyword'] = 'Report';
		$data['modules'][] = 'stocks';
		$data['methods'][] = 'admin_stock_report_format1';
		$data['js'][] = "<script type=\"text/javascript\">
						$(document).ready(function() {

                $('#search th').each( function () {
                    var title = $(this).text();
                    $(this).html( '<input type=\"text\" placeholder=\"Search '+title+'\" />' );
                } );

			    $('#report').dataTable({	
					paging:false,
					footerCallback: function ( row, data, start, end, display ) {
							var table = this.api(), data;	 
							// Remove the formatting to get integer data for summation
							var intVal = function ( i ) {
								return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ?	i : 0;
							};
							table.columns().every( function () {
			                    var that = this;
			                    $( 'input', this.header() ).on( 'keyup change', function () {
			                        if ( that.search() !== this.value ) {
			                            that
			                                .search( this.value )
			                                .draw();
			                        }
			                    } );
			                } );

			                // total_quantity over all pages
							total_nquantity = table.column( 10 ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							},0 );
							
							// total_balance_quantity over this page
							total_net_quantity = table.column( 10, { page: 'current'} ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );

							// total_quantity over all pages
							total_bquantity = table.column( 11 ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							},0 );
							
							// total_balance_quantity over this page
							total_booked_quantity = table.column( 11, { page: 'current'} ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
				 
							// total_quantity over all pages
							total_pquantity = table.column( 12 ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							},0 );
							
							// total_balance_quantity over this page
							total_physical_quantity = table.column( 12, { page: 'current'} ).data().reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							
							total_net_quantity = parseFloat(total_net_quantity);
							total_nquantity = parseFloat(total_nquantity);
							total_booked_quantity = parseFloat(total_booked_quantity);
							total_bquantity = parseFloat(total_bquantity);
							total_physical_quantity = parseFloat(total_physical_quantity);
							total_pquantity = parseFloat(total_pquantity);
							// Update footer
							$('#netquantity').html(total_net_quantity.toFixed(3));
							$('#bookedquantity').html(total_booked_quantity.toFixed(3));	
							$('#physicalquantity').html(total_physical_quantity.toFixed(3));				

						},		
				});
			});
		</script>";
		echo Modules::run("templates/report_template", $data);
	}

	function admin_stock_report_format1($data = []){
		//Datewise report
		$this->load->view('stocks/admin_stock_report');
	}

	function daily_inward(){
		$inwardDate = '2019-03-31';//date('Y-m-d');
		$sql = "Select concat('v.first_name', ' ', 'v.middle_name', ' ', 'v.surname',' - ', 'v.company_name') as vendor, s.*, w.warehouse from stocks s left join vendors v on v.id=s.vendor_id left join company_warehouse w on w.id=s.company_warehouse_id where 1=1";
		if($this->input->post('is_active')){
			$sql.=' AND s.is_active='.$this->input->post('is_active');
		}else{
			$sql.=' AND s.is_active=1';
		}

		if($this->input->post('vendor_id')){
			$sql.=' AND s.vendor_id='.$this->input->post('vendor_id');
		}

		if($this->input->post('inward_date')){
			$inwardDate = $this->pktlib->dmYtoYmd($this->input->post('inward_date'));
			$sql.=' AND s.inward_date LIKE "'.$inwardDate.'"';
		}else{
			$sql.=' AND s.inward_date LIKE "'.$inwardDate.'"';
		}

		if($this->input->post('company_warehouse_id')){
			$sql.=' AND s.company_warehouse_id='.$this->input->post('company_warehouse_id');
		}
		//echo $sql;	
		$stocks = $this->pktdblib->custom_query($sql);
		foreach ($stocks as $key => $stock) {
			$sql2 = 'Select sd.*, p.product from stock_details sd inner join products p on p.id=sd.product_id where sd.stock_id='.$stock['id'].' AND p.is_active=true';
			$stocks[$key]['stockDetails'] = $this->pktdblib->custom_query($sql2);
		}

		$this->pktdblib->set_table('company_warehouse');
		$warehouses = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['warehouse'][0] = 'Select Warehouse';
		foreach ($warehouses->result_array() as $warehouseKey => $value) {
			$data['option']['warehouse'][$value['id']] = $value['warehouse'];
		}
		$this->pktdblib->set_table('vendors');
		$vendor = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['vendors'][0] = 'Select Vendor';
		foreach ($vendor->result_array() as $vendorKey => $value) {
			$data['option']['vendors'][$value['id']] = $value['first_name']." ".$value['middle_name']." ".$value['surname']." - ".$value['emp_code'];
		}
		$data['stocks'] = $stocks;
		$data['meta_title'] = 'Report';
		$data['meta_description'] = 'Report';
		$data['title'] = 'Report';
		$data['heading'] = 'Stock Report';
		$data['meta_keyword'] = 'Report';
		$data['content'] = 'stocks/daily_incoming';
		echo Modules::run("templates/report_template", $data);
	}
    
    function upload_stock_csv_file(){
        //echo 'hii';exit;
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //echo '<pre>';
            $stocks = [];
            $error = [];
            //$stockCode = time();
            if(!empty($_FILES)) {
                $fname = $_FILES['sel_file']['name'];
                $chk_ext = explode('.',$fname);
                if(end($chk_ext)=='xlsx' || end($chk_ext) == 'xls' || end($chk_ext) == 'csv') {
                $filename = $_FILES['sel_file']['tmp_name'];
                $this->load->library('excel');
                $objPHPExcel = PHPExcel_IOFactory::load($filename);
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                //echo "<pre>";
                foreach ($cell_collection as $cell) {
                //print_r($cell);exit();
                   $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                   $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                   $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                   if ($row == 1) {
                       $header[$row][$column] = $data_value;
                   } else {
                       $arr_data[$row][$column] = $data_value;
                   }
                }
                //exit;
                $data['header'] = $header;
                $data['values'] = $arr_data;
                //echo "<pre>";
                foreach ($data['values'] as $xlsxkey => $xlsxvalue) {
                   //echo $xlsxvalue['B'];exit;
                    /*echo $xlsxkey;
                    print_r($xlsxvalue);*/
                    foreach ($xlsxvalue as $key => $value) {
                        /*echo "<pre>";
                        print_r($value);*/
                        $xlsxUploadedData[$xlsxkey-2][] = $value;
                    }
                //exit;
                }
                foreach ($xlsxUploadedData as $xlskey => $xlsvalue) {
                    //echo '<pre>';print_r($xlsvalue);exit;
                    $this->pktdblib->set_table('stock_details');
                    $stk_dtl = $this->pktdblib->custom_query('select sd.* from stock_details sd inner join stocks s on s.id=sd.stock_id where sd.grade="'.$xlsvalue[5].'" AND sd.coil_no="'.$xlsvalue[8].'" and sd.is_active=true and s.is_active=true');
                    //echo $this->db->last_query();exit;
                    if(count($stk_dtl)>=1){
                        $error[]= "Duplicate entry not allowed";
                    }
                    if ($xlsvalue[3]=='-') {
                    	$xlsvalue[3] = '';	
                    }
                    if ($xlsvalue[1]=='-') {
                    	$xlsvalue[1] = '';	
                    }
                    if ($xlsvalue[2]=='-') {
                    	$xlsvalue[2] = '';	
                    }
                   	$this->pktdblib->set_table('company_warehouse');
                              $query = $this->pktdblib->custom_query('select * from company_warehouse where warehouse="'.$xlsvalue[6].'"');
                 	//echo $this->db->last_query();exit;
                    /*echo $xlsvalue[6].'<br>';
                    echo $xlsvalue[7].'<br>';
                    echo $xlsvalue[8].'<br>';*/
                    
                    if(count($query)==0){
                    $error[]= "Invalid Warehouse on row no ".$xlskey;
                    }
                    $Vendor = '';
                    $this->pktdblib->set_table('vendors');
                    $vendor = $this->pktdblib->custom_query('select * from vendors where company_name="'.$xlsvalue[4].'"');
                    if(count($vendor)>0){
                    $Vendor = $vendor[0]['id'];
                    }
                    if(count($vendor) == 0){
                        $vendorDetail = [];
                        $vendorDetail['first_name'] = $vendorDetail['company_name'] = trim($xlsvalue[4]);
                        $vendorDetail['created'] = $vendorDetail['modified'] = date('Y-m-d H:i:s');
                        $Vender = $this->pktdblib->_insert($vendorDetail);
                        $Vendor = $Vender['id'];
                        //$error[]= "Invalid vendor on row no ".$xlskey;
                    }
                    //echo $Vendor;exit;
                    $this->pktdblib->set_table('products');
                    $product = $this->pktdblib->custom_query('select * from products where product="'.$xlsvalue[7].'"');
                    if(count($product)==0){
                        //echo $xlsvalue[7].'<br>';
                        $error[]= "Invalid product on row no ".$xlskey;
                    }
            
                    //exit;
            		
                    if(empty($error)){
                        //echo "hi";exit;
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stocks']['company_warehouse_id'] = $query[0]['id'];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stocks']['inward_date'] = date('Y-m-d', strtotime($xlsvalue[0]));
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stocks']['stock_code'] = time().$xlskey;
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stocks']['lot_no'] = $xlsvalue[3];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['invoice_no'] = $xlsvalue[2];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['lorry_no'] = $xlsvalue[1];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stocks']['vendor_id'] = $Vendor;
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stocks']['is_active'] = true;
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stocks']['created'] = $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stocks']['modified'] = date('Y-m-d H:i:s');
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['product_id'] = $product[0]['id'];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['coil_no'] = $xlsvalue[8];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['grade'] = $xlsvalue[5];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['thickness'] = $xlsvalue[9];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['length'] = $xlsvalue[11];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['piece'] = $xlsvalue[12]; 
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['lot_no'] = $xlsvalue[2];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['width'] = $xlsvalue[10];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['order_qty'] = $xlsvalue[13];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['qty'] = $xlsvalue[14];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['balance_qty'] = $xlsvalue[14];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['uom'] = $xlsvalue[15];
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['remark'] = $xlsvalue[16];
                            
                            if(isset($xlsvalue[17])){
                            $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['reck'] = $xlsvalue[17];
                            }
                            else{
                                $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['reck'] = '1';
                            };
                            
                            if(isset($xlsvalue[18])){
                            $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['running_mtr'] = $xlsvalue[18];
                            }
                            else{
                                $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['running_mtr'] = '0';
                            };
                        
                        $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['created'] = $stocks[$xlsvalue[0]][$xlsvalue[6]][$xlsvalue[4]]['stockDetails'][$xlskey]['modified'] = date('Y-m-d H:i:s');
                
                    } 
                }
            } 
            //echo '<pre>'; print_r($stocks);exit;
            if(empty($error)){
                //echo "hi";exit;
                foreach ($stocks as $dateKey => $dateWiseStock) {
                    foreach ($dateWiseStock as $warehouseKey => $warehouseStock) {
                        foreach ($warehouseStock as $vendorKey => $vendorWiseStock) {
                            //echo '<pre>'; print_r($vendorWiseStock);
                            $this->pktdblib->set_table('stocks');
                            $stockId = $this->pktdblib->_insert($vendorWiseStock['stocks']);
                            $vendorWiseStock['stockDetails'] = array_values($vendorWiseStock['stockDetails']);
                            foreach ($vendorWiseStock['stockDetails'] as $stockDetailKey => $stockDetail) {
                                if ($stockId['id']) {
                                    //print_r($stockDetail);exit;
                                    $stockDetail['stock_id'] = $stockId['id'];
                                    $this->pktdblib->set_table('stock_details');
                                    $this->pktdblib->_insert($stockDetail);
                                }
                           }
                        }
                    }
                }
                $msg = array('message'=>'Stocks Added Successfully', 'class'=>'alert alert-success');
                $this->session->set_flashdata('message',$msg);
                redirect('stocks/upload_stock_csv_file');
            }
            else{
                $msg = array('error' => $error, 'class' => 'alert alert-danger');
                $this->session->set_flashdata('error',$msg);
                redirect('stocks/upload_stock_csv_file');
            }	
        }
        }
        //$data['option']['customers'] = $this->get_Customer_list_dropdown();
        $data['content'] = 'stocks/upload_csv_stocks';
        $data['meta_title'] = "ERP : Upload Multiple Site";
        $data['title'] = "ERP : Upload Multiple Site";
        $data['meta_description'] = "ERP : Customer Upload Multiple Site";
        echo Modules::run("templates/admin_template", $data);
        //exit;
    }
    

    function multiple_stock_out($orderId = NULL) {
		//Create order option for AJS impex
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$error = [];
			$profileImg = '';
			

				if(empty($error)){
					$stockOut = [];
					$post_data = $_POST['stockout'];
					foreach ($post_data as $key => $value) {
						$value['outward_date'] = $this->pktlib->dmYtoYmd($value['outward_date']);
						$value['created'] = $value['modified'] = date('Y-m-d H:i:s');
					    $query = $this->pktdblib->custom_query('select * from stockout where outward_date="'.$value['outward_date'].'" AND lorry_no="'.$value['lorry_no'].'" AND order_code="'.$value['order_code'].'" AND broker_id='.$value['broker_id'].'');
						
						if (count($query)>0) {
							$stockOut['id'] = $query[0]['id'];
							$_POST['stockout_details'][$key]['stockout_id'] = $query[0]['id'];

						}
						else{
							$this->pktdblib->set_table('stockout');
							$stockout = $this->pktdblib->_insert($value);
							$_POST['stockout_details'][$key]['stockout_id'] = $stockout['id'];
						}
					}

					$stockOutDetails = $this->input->post('stockout_details');
					//print_r($stockOutDetails);exit;
					$stockDetails = [];
					$this->pktdblib->set_table('stock_details');
					foreach ($stockOutDetails as $key => $detail) {
						//print_r($detail);exit;
						$coil = $this->pktdblib->get_where($detail['stock_detail_id']);
						unset($stockOutDetails[$key]['unit_price']);
						$stockOutDetails[$key]['coil_no'] = $coil['coil_no'];
						$stockOutDetails[$key]['created'] = $stockOutDetails[$key]['modified'] = date('Y-m-d H:i:s');
					}
					$this->pktdblib->set_table('stockout_details');
					$this->pktdblib->_insert_multiple($stockOutDetails);
					$msg = array('message'=>'Stock updated Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
					redirect(custom_constants::multiple_stockout_url);

					
				}else{
					$msg = array('message'=>'Failed to Upload Image. Error: '.$error['profile_img'], 'class'=>'alert alert-danger');
	                $this->session->set_flashdata('message',$msg);
				}
			//}
		}else{

			if(NULL!==$orderId){
				$this->pktdblib->set_table('orders');
				$order = $this->pktdblib->get_where($orderId);
				$data['values_posted']['stockout']['order_code'] = $order['order_code'];
				$data['values_posted']['stockout']['broker_id'] = $order['broker_id'];

				$data['values_posted']['stockout_details'] = $this->pktdblib->custom_query('Select * from order_details where order_id='.$orderId);
				$stock = [];
				//echo '<pre>';
				foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
					$data['option']['coil_no'][$detail['product_id']] = ['0'=>'Select Coil No'];
					$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true and thickness='.$detail['thickness'].' and width='.$detail['width']);
					foreach ($stockDetails as $key2 => $coilNo) {
						//print_r($coilNo);
						$data['option']['coil_no'][$detail['product_id']][$coilNo['id']] = $coilNo['coil_no'];
					}
				}
			}
			
		}
		
		$this->pktdblib->set_table('brokers');
		$brokers = $this->pktdblib->get('created asc');
		$data['option']['broker'][] = 'Select Broker';
		foreach ($brokers->result_array() as $brokerKey => $broker) {
			$data['option']['broker'][$broker['id']] = $broker['company_name']." (".$broker['first_name']." ".$broker['middle_name']." ".$broker['surname'].")";
		}

		$this->pktdblib->set_table('company_warehouse');
		$query = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['companyWarehouse'][0] = 'Select Warehouse/Cutter';
		foreach ($query->result_array() as $warehouseKey => $warehouse) {
			$data['option']['companyWarehouse'][$warehouse['id']] = $warehouse['warehouse'];
		}

		$data['option']['uom'] = $this->pktlib->uom();

		$data['option']['stock_detail'][0] = 'Select Coil No';
		$data['option']['referred_by'] = $this->referredby_option();
		$data['products'] = $this->product_model->get_active_list();
		
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}

		$this->pktdblib->set_table('orders');
		$data['option']['orderCodes'][] = "Select";
		$query = $this->pktdblib->get_where_custom('is_active', '1');

		foreach ($query->result_array() as $key => $orders) {
			$data['option']['orderCodes'][$orders['order_code']] = $orders['order_code'];
		}
		$data['meta_title'] = "Stock Outward";
		$data['meta_description'] = "Stock Outward";
		$data['heading'] = "Multiple Dispatch";
		$data['title'] = "Modules :: Stock";
		//echo "reached here"; exit;
		$data['modules'][] = "stocks";
		$data['methods'][] = "multiple_stockout_form";
		$data['js'][] = '<script type="text/javascript">
            /*$(".product").on("change", function(){
            	var id = this.id;
            	//alert($(this).val());
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
				
            })*/
            $("body").on("change", ".stockDetail", function(){
            	var stockDetailId = $(this).val();
            	var trId = $(this).closest("tr").attr("id");
            	console.log(trId);
            	console.log(stockDetailId);
            	$.ajax({
					type: "POST",
					dataType: "json",
					url : base_url+"stocks/get_idwise_stockdetail/"+stockDetailId,
					success: function(response) {
						console.log(response);
						$("#thickness_"+trId).val(response.thickness);
						$("#company_warehouse_id_"+trId).val(response.company_warehouse_id).trigger("change.select2");
						
						$("#width_"+trId).val(response.width);
						$("#balance_qty_"+trId).val("Bal: "+response.balance_qty+" "+response.uom);
						$("#remark_"+trId).html("Remark: "+response.remark);
					}
		        
		        });
            });
            $("form").on("submit", function(){
            	/*alert("hii");
            	return false;*/
            	if($("#broker_id").val() <=0 || $("#broker_id").val() ==""){
            		alert("Please select Broker");
            		$("#broker_id").focus();
            		return false;
            	}

            	if($(".stock").val() <=0 || $(".stock").val() ==""){
            		alert("Please select Product");
            		$(this).focus();
            		return false;
            	}
            })
		</script>';
		echo Modules::run("templates/admin_template", $data);
	}
	
	function multiple_stockout_form($data = []) {
		
		//echo "reached here";
		$this->load->view("stocks/multiple_stockout_form");
	}

/*	function admin_delete($del_id){
		//echo $del_id;exit;
		$query = $this->pktdblib->custom_query("update stocks set is_active = 0 where id = ".$del_id."");
		return true;
	}*/

	/*function admin_stockout_delete($del_id){
		//echo $del_id;exit;
		$query = $this->pktdblib->custom_query("update stockout set is_active = 0 where id = ".$del_id."");
		return true;
	}*/

	function admin_delete(){
		$data = [];
		$id = $this->input->post('id');
		$data['is_active'] = $this->input->post('is_active');
		$data['modified'] = date('Y-m-d H:i:s');
		$table = $this->input->post('table');
		$this->pktdblib->set_table($table);
		$query = $this->pktdblib->_update($id,$data);
		return true;
	}

	function custom_admin_delete(){
		$data = [];
		$id = $this->input->post('id');
		$data['is_active'] = $this->input->post('is_active');
		$data['modified'] = date('Y-m-d H:i:s');
		$table = $this->input->post('table');
		$this->pktdblib->set_table($table);
			$query = $this->pktdblib->_update($id,$data);
		if ($table == 'stocks') {
			
			$data['stock_id'] = $id;
			$this->pktdblib->set_table('stock_details');
			$upd=$this->pktdblib->_update($id,$data, 'stock_id');
		}elseif($table=='stockout'){
			$data['stockout_id'] = $id;
			$this->pktdblib->set_table('stockout_details');
			$upd=$this->pktdblib->_update($id,$data, 'stockout_id');
		}
		return true;
	}
	
	function admin_new_stock_out_3($orderId = NULL) {
		//Create order option for AJS impex
		//check_user_login(FALSE);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			//echo '<pre>';print_r($_POST);exit;
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			
			$this->form_validation->set_rules('data[stockout][outward_date]', 'Outward Date', 'required');
			$this->form_validation->set_rules('data[stockout][lorry_no]', 'Lorry Number', 'required');
			$this->form_validation->set_rules('data[stockout][broker_id]', 'Broker', 'required');
			$this->form_validation->set_rules('data[stockout][order_code]', 'Sales Order Number', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$error = [];
				$profileImg = '';
				//print_r($_FILES);exit;
				if(!empty($_FILES['invoice']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['invoice'], 'path'=>'../content/uploads/stocks/','ext'=>'gif|jpg|png|jpeg|pdf', 'fieldname'=>'invoice', 'arrindex'=>'invoice'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$_POST['data']['stockout']['invoice'] = $profileImg['filename'];
					}
					else {
						$error['invoice'] = $profileImg['error'];
					}
				}else{
					//$_POST['data']['stockout']['invoice'] = '';
				}

				if(empty($error)){
					//$this->pktdblib->db->trans_start(TRUE);
					$post_data = $_POST['data']['stockout'];
					$post_data['order_code'] = base64_decode($post_data['order_code']);
					$post_data['outward_date'] = $this->pktlib->dmYtoYmd($post_data['outward_date']);
					$post_data['created'] = $post_data['modified'] = date('Y-m-d H:i:s');
					$reg_stock = json_decode($this->_register_new_stockout($post_data), true);
					
					if($reg_stock['status'] === "success")
					{	
						//$_POST['data']['stocks'] = $reg_stock['stocks'];
						//$_POST['stockout_details']['stockout_id'] = $reg_stock['id'];
						$stockOutDetails = $this->input->post('stockout_details');
						$orderDetailData = [];
						$stockDetails = [];
						foreach ($stockOutDetails as $key => $detail) {
							if(isset($detail['order_detail_id']) && !empty($detail['order_detail_id'])){

								//$coil = $this->pktdblib->get_where($detail['stock_detail_id']);
								$this->pktdblib->set_table('order_details');
								$orderDetails = $this->pktdblib->get_where($detail['order_detail_id']);
								$orderDetails['is_active'] = 8;
								
								//echo '<pre>';print_r($orderDetails);
								$this->pktdblib->set_table('stock_details');
								$coil = $this->pktdblib->custom_query('select sd.id as stock_detail_id, sd.coil_no, s.company_warehouse_id, sd.balance_qty from stock_details sd inner join stocks s on s.id=sd.stock_id where sd.coil_no="'.$orderDetails['coil_no'].'"');
								//print_r($coil);
								unset($stockOutDetails[$key]['unit_price']);
								//$stockOutDetails[$key]['coil_no'] = $coil['coil_no'];
								$stockOutDetails[$key]['product_id'] = $orderDetails['product_id'];
								$stockOutDetails[$key]['coil_no'] = $coil[0]['coil_no'];
								$stockOutDetails[$key]['stock_detail_id'] = $coil[0]['stock_detail_id'];
								$stockOutDetails[$key]['company_warehouse_id'] = $coil[0]['company_warehouse_id'];
								$stockOutDetails[$key]['stockout_id'] = $reg_stock['id'];
								$stockDetails[$key]['modified'] = $stockOutDetails[$key]['modified'] = $stockOutDetails[$key]['created'] = date('Y-m-d H:i:s');
								$orderDetailData[$key]['id'] = $stockOutDetails[$key]['order_detail_id'];
								$orderDetailData[$key]['coil_no'] = $coil[0]['coil_no'];
								unset($stockOutDetails[$key]['order_detail_id']);

								$stockDetails[$key]['id'] = $coil[0]['stock_detail_id'];
								$stockDetails[$key]['balance_qty'] = $coil[0]['balance_qty']-$detail['qty'];
							}else{
								unset($stockOutDetails[$key]);
							}
						}
						//echo '<pre>';print_r($stockOutDetails);print_r($stockDetails);
						//exit;
						$this->pktdblib->set_table('stockout_details');
						$this->pktdblib->_insert_multiple($stockOutDetails);
						$this->pktdblib->set_table('stock_details');
						$this->pktdblib->update_multiple('id', $stockDetails);
						if(!empty($orderDetailData)){
						    $this->pktdblib->set_table('order_details');
						    $this->pktdblib->update_multiple('id', $orderDetailData);
						    if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){
                                $tallySalesOrder = Modules::run('tally/import_salesorder',$post_data['order_code']);
                            }
						}
						
						$msg = array('message'=>'Stock updated Successfully', 'class'=>'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
						redirect(custom_constants::new_stockout_url);
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
		}/*else{
			//if(NULL!==$orderId){
				if(isset($_GET['order_detail_id']) && !empty($_GET['order_detail_id'])){
			//echo "hii";exit;
					//echo $orderId;exit;
				$this->pktdblib->set_table('orders');
				$order = $this->pktdblib->get_where($orderId);
				$data['values_posted']['stockout']['order_code'] = $order['order_code'];
				$data['values_posted']['stockout']['broker_id'] = $order['broker_id'];

				$data['values_posted']['stockout_details'] = $this->pktdblib->custom_query('Select * from order_details where order_id='.$_GET['order_detail_id']);
				//print_r($data['values_posted']['stockout_details']);exit;

				$stock = [];
				//echo '<pre>';
				foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
					$data['option']['coil_no'][$detail['product_id']] = ['0'=>'Select Coil No'];
					//echo 'select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true and thickness='.$detail['thickness'].' and width='.$detail['width'].'<br>';
					$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where product_id='.$detail['product_id'].' and balance_qty>0 and stock_id in (select id from stocks where is_active=true) and is_active=true and thickness='.$detail['thickness'].' and width='.$detail['width']);
					foreach ($stockDetails as $key2 => $coilNo) {
						//print_r($coilNo);
						$data['option']['coil_no'][$detail['product_id']][$coilNo['id']] = $coilNo['coil_no'];
					}
				}
			}
			//print_r($option);exit;
		}*/
		//print_r($data['option']['coil_no']);
		
		
		

		$data['meta_title'] = "Stock Outward";
		$data['meta_description'] = "Stock Outward";
		$data['heading'] = "New Stock Out";
		$data['title'] = "Modules :: Stock";
		//echo "reached here"; exit;
		$data['modules'][] = "stocks";
		$data['methods'][] = "admin_stockout_form_3";
		$data['js'][] = '<script type="text/javascript">
            /*$(".product").on("change", function(){
            	var id = this.id;
            	//alert($(this).val());
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
				
            })*/
            $("body").on("change", ".stockDetail", function(){
            	var stockDetailId = $(this).val();
            	var trId = $(this).closest("tr").attr("id");
            	console.log(trId);
            	console.log(stockDetailId);
            	$.ajax({
					type: "POST",
					dataType: "json",
					url : base_url+"stocks/get_idwise_stockdetail/"+stockDetailId,
					success: function(response) {
						console.log(response);
						$("#thickness_"+trId).val(response.thickness);
						$("#company_warehouse_id_"+trId).val(response.company_warehouse_id).trigger("change.select2");
						
						$("#width_"+trId).val(response.width);
						$("#balance_qty_"+trId).val("Bal: "+response.balance_qty+" "+response.uom);
						$("#remark_"+trId).html("Remark: "+response.remark);
					}
		        
		        });
            });
            $("body").on("change",".orderWiseDetail", function(){
            	//console.log("hiii");
            	
            	var orderCode = $(this).val();
            	console.log(orderCode);
				url = base_url+"stocks/out2?code="+orderCode;
				window.location.replace(url);
            	
            });
            $(".check_to_dispatch").click(function(){
            	var detailId = $(this).val();
            	var trId = $(this).closest("tr").attr("id");
            	var id = $(this).attr("id");
            	
		            if($(this).prop("checked") == true){
		                //alert("Checkbox is checked.");
		                $("#qty_"+trId).removeAttr("readonly");
		            }
		            else if($(this).prop("checked") == false){
		                //alert("Checkbox is unchecked.");
		                $("#qty_"+trId).attr("readonly", "readonly");

		            }
            })
            $("form").on("submit", function(){
            	/*alert("hii");
            	return false;*/
            	if($("#broker_id").val() <=0 || $("#broker_id").val() ==""){
            		alert("Please select Broker");
            		$("#broker_id").focus();
            		return false;
            	}

            	if($(".stock").val() <=0 || $(".stock").val() ==""){
            		alert("Please select Product");
            		$(this).focus();
            		return false;
            	}
            	var checkClass = $("input:checkbox.check_to_dispatch:checked").length;
            	//alert("class is checked "+checkClass);
            	if(checkClass==0){
            		alert("Please Select Product to Dispatch");
					return false;
            	}
            })
		</script>';
		echo Modules::run("templates/admin_template", $data);
	}
	
	function admin_stockout_form_3($data = []) {
		if(isset($_GET['order_detail_id']) && !empty($_GET['order_detail_id'])){
			//echo " hii";exit;
			$detailId= $_GET['order_detail_id'];
			$data['flag'] = 1;
			//echo $detailId;exit;
			//print_r($data['disabled']);exit;
			/*$this->pktdblib->set_table('orders');
			$orderCode = $this->pktdblib->custom_query('select od.*, o.order_code, broker_id from order_details od left join orders o on o.id=od.order_id left join brokers b on b.id=o.broker_id where od.id='.$detailId);
			$coil = $this->pktdblib->custom_query('select sd.*, s.company_warehouse_id from stock_details sd left join stocks s on s.id=sd.stock_id where sd.coil_no="'.$orderCode[0]['coil_no'].'"');*/
			$this->pktdblib->set_table('orders');
			//$orderData = $this->pktdblib->custom_query('select o.*, b.id as broker_id, concat(b.first_name, " ", b.middle_name, " ", b.surname) as broker_name from orders o inner join brokers b on b.id=o.broker_id where o.order_code="'.$code.'"'); 
			$orderDetails = $this->pktdblib->custom_query('select od.*,o.order_code, o.broker_id, concat(b.first_name, " ", b.middle_name, " ", b.surname)as broker_name, sd.id as stock_detail_id, s.company_warehouse_id, sd.balance_qty, p.product from order_details od inner join orders o on o.order_code=od.order_code inner join products p on p.id=od.product_id inner join stock_details sd on sd.coil_no=od.coil_no and sd.balance_qty>0 inner join stocks s on s.id=sd.stock_id inner join brokers b on b.id=o.broker_id where od.is_active=1 and o.is_active=1 and od.id='.$detailId);
			//echo $this->db->last_query();exit;
			//echo '<pre>';//print_r($orderData);
			/*if(empty($orderDetails)){
				redirect($_SERVER['HTTP_REFERER']);
			}*/
			//print_r($orderDetails);exit;
			$data['values_posted']['stockout']['order_code'] = base64_encode($orderDetails[0]['order_code']);
			$data['values_posted']['stockout']['broker_id'] = $orderDetails[0]['broker_id'];
			$data['values_posted']['stockout_details'] = $orderDetails;
			$data['values_posted']['stockout_details'][0]['stock_detail_id'] = $orderDetails[0]['stock_detail_id'];
			$data['values_posted']['stockout_details'][0]['company_warehouse_id'] = $orderDetails[0]['company_warehouse_id'];
			$data['values_posted']['stockout_details'][0]['balance_qty'] = $orderDetails[0]['balance_qty'];
			//echo '<pre>';print_r($data['values_posted']['stockout_details']);exit;

			$stock = [];
			//print_r($data['values_posted']['stockout_details']);exit;
				//echo '<pre>';
			foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
				$data['option']['coil_no'][$detail['product_id']] = ['0'=>'Select Coil No'];
				$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no from stock_details where stock_details.product_id='.$detail['product_id'].' and stock_details.thickness='.$detail['thickness'].' and width='.$detail['width'].' and stock_details.balance_qty>'.$detail['qty'].' and stock_details.is_active= true');
				//print_r($stockDetails);exit;
				foreach ($stockDetails as $key2 => $coilNo) {
					$data['option']['coil_no'][$detail['product_id']][$coilNo['id']] = $coilNo['coil_no'];
				}
			}

			//echo '<pre>';print_r($orderCode);exit;
			//print_r($data['values_posted']['stockout']['order_code']);//exit;
		}else if(isset($_GET['code']) && !empty($_GET['code'])){
			$code= base64_decode($_GET['code']);
			$data['flag'] = 1;
			$query = Modules::run('orders/pending_dispatch_order', ['order_code'=>$code]);
			//print_r($query);
			$orderData = [];
			$orderDetails = [];
			$counter = 0;
			foreach ($query as $key => $order) {
				$orderData['broker_name'] = $order['broker_name'];
				$orderData['broker_id'] = $order['broker_id'];
				$orderData['order_code'] = $order['order_code'];
				$orderData['po_no'] = $order['po_no'];
				$orderData['order_date'] = $order['order_date'];
				$orderData['message'] = $order['message'];
				$orderData['is_active'] = $order['is_active'];
				$orderDetails[] = $order;
				$counter++;
			}
		
			$orderData['order_code'] = base64_encode($code);//base64_encode($orderData['order_code']);
			$data['values_posted']['stockout'] = $orderData;
			$data['values_posted']['stockout_details'] = $orderDetails;
			$stock = [];
			$data['option']['coil_no'] = ['0'=>'Select Coil No'];
			//echo '<pre>';
			foreach ($data['values_posted']['stockout_details'] as $key => $detail) {
			    //print_r($detail);
				if(!isset($data['option']['coil_no'][$detail['product_id']][str_replace(" ","_",$detail['grade'])])){
				    $data['option']['coil_no'][$detail['product_id']][str_replace(" ","_",$detail['grade'])][] = 'Select';
				}
				//echo 'select stock_details.id, stock_details.coil_no, stock_details.grade from stock_details where stock_details.product_id='.$detail['product_id'].' and stock_details.grade="'.$detail['grade'].'" and stock_details.thickness='.$detail['thickness'].' and width='.(int)$detail['width'].' and stock_details.balance_qty>1 and stock_details.is_active= true<br>';
				$stockDetails = $this->pktdblib->custom_query('select stock_details.id, stock_details.coil_no, stock_details.grade from stock_details where stock_details.product_id='.$detail['product_id'].' and stock_details.grade="'.$detail['grade'].'" and stock_details.thickness='.$detail['thickness'].' and width='.(int)$detail['width'].' and stock_details.balance_qty>1 and stock_details.is_active= true');
				//print_r($stockDetails);exit;
				foreach ($stockDetails as $key2 => $coilNo) {
				    
				    //echo $detail['grade']." ".$coilNo['grade']." ".str_replace(" ","_",$detail['grade'])."<br>";
					$data['option']['coil_no'][$detail['product_id']][str_replace(" ","_",$detail['grade'])][$coilNo['id']] = $coilNo['coil_no'];
				}
			}
            //echo '</pre>';
			//echo '<pre>';print_r($data['option']['coil_no']);exit;
			//print_r($data['values_posted']['stockout']['order_code']);//exit;
		}

		//echo "reached here";
		$this->pktdblib->set_table('brokers');
		$brokers = $this->pktdblib->get('created asc');
		$data['option']['broker'][] = 'Select Broker';
		foreach ($brokers->result_array() as $brokerKey => $broker) {
			$data['option']['broker'][$broker['id']] = $broker['company_name']." (".$broker['first_name']." ".$broker['middle_name']." ".$broker['surname'].")";
		}

		$this->pktdblib->set_table('company_warehouse');
		$query = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['companyWarehouse'][0] = 'Select Warehouse/Cutter';
		foreach ($query->result_array() as $warehouseKey => $warehouse) {
			$data['option']['companyWarehouse'][$warehouse['id']] = $warehouse['warehouse'];
		}

		$data['option']['uom'] = $this->pktlib->uom();

		$data['option']['stock_detail'][0] = 'Select Coil No';
		$data['option']['referred_by'] = $this->referredby_option();
		$data['products'] = $this->product_model->get_active_list();
		
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}

		/*$this->pktdblib->set_table('orders');
		$data['option']['orderCodes'][] = "Select";
		$query = $this->pktdblib->get_where_custom('is_active', '1');

		foreach ($query->result_array() as $key => $orders) {
			$data['option']['orderCodes'][$orders['order_code']] = $orders['order_code'];
		}*/
		$data['option']['orderCodes'][] = 'Select';
		$orderCode = Modules::run('orders/pending_dispatch_order');
		foreach ($orderCode as $key => $order) {
			$data['option']['orderCodes'][base64_encode($order['order_code'])] = $order['order_code'];
		}
		$this->load->view("stocks/admin_stockout_form_3",$data);
	}
	
	
	function gradewise_report(){
	    /*$_SERVER['REQUEST_METHOD'] = 'POST';
	    $_POST['product_id'] = 8;*/
	    if($_SERVER['REQUEST_METHOD'] == 'POST'){
	        
	        $sql = 'SELECT sum(stock_details.balance_qty) as balance_qty, stock_details.grade, stock_details.thickness, stock_details.width FROM `stock_details` INNER JOIN stocks on stocks.id=stock_details.stock_id and stocks.is_active=true WHERE stock_details.product_id="'.$this->input->post('product_id').'" and stock_details.balance_qty>1 AND stock_details.is_active=true GROUP BY stock_details.grade,stock_details.thickness, stock_details.width ORDER by stock_details.thickness,stock_details.grade,stock_details.width ASC';
	        $stocks = $this->pktdblib->custom_query($sql);
	        $reports = [];
	        $grades = [];
	        foreach($stocks as $key=>$stock){
	            if(!in_array($stock['grade'], $grades)){
	                $grades[] = $stock['grade'];
	            }
	            $reports[number_format($stock['thickness'],2).'x'.$stock['width']][$stock['grade']] = $stock['balance_qty'];
	        }
	        $data['reports'] = $reports;
	        $data['grades'] = $grades;
	        //echo $this->input->post('product_id');exit;
	        //$data['product'] = json_decode(Modules::run('products/get_product_detail_ajax/'.$this->input->post('product_id')), true);
	        //print_r($data['product']);exit;
	       /* echo '<pre>';
	        print_r($grades);
	        print_r($report);*/
	        
	    }
	    $this->pktdblib->set_table('products');
		$product = $this->pktdblib->get_where_custom('is_active', true);
		$data['option']['product'][0] = 'Select Product';
		foreach ($product->result_array() as $productKey => $value) {
			$data['option']['product'][$value['id']] = $value['tally_name'];
		}
	    $data['meta_title'] = "Gradewise Stock Report";
		$data['meta_description'] = "Gradewise Stock Report";
		$data['heading'] = "Gradewise Stock Report";
		$data['title'] = "Modules :: Stock";
	    $data['content'] = 'stocks/grade_wise_qty_report';
	    echo Modules::run("templates/report_template", $data);
	}
    
    function coilwise_remark($coilNo=NULL, $warehouse=NULL){
        $remark = '';
        if($coilNo==NULL || $warehouse==NULL){
            return $remark;
        }
        $sql = $this->db->query('Select remark from stockout_details where coil_no="'.$coilNo.'" AND company_warehouse_id='.$warehouse.' order by id desc limit 1');
        $data = $sql->row_array();
        return $data['remark'];
        
    }

    function checkPo(){
    	if($_SERVER['REQUEST_METHOD']=="POST"){
    		$this->pktdblib->set_table('stocks');
    		$checkPo = $this->pktdblib->get_where_custom('po_no', $this->input->post('po_no'));
    		if($checkPo->num_rows()>0){
    			echo json_encode(['status'=>false, 'msg'=>'PO number already exists']);
    		}else{
    			echo json_encode(['status'=>true]);
    		}
    	}else{
    		echo json_encode(['status'=>false, 'msg'=>'Invalid Request']);
    	}
    		exit;
    }

    function barcode($stockDetailId=NULL){
    	if(NULL===$stockDetailId){
    		show_404();
    		exit;
    	}

    	if($_SERVER['REQUEST_METHOD']=='POST'){

    	}

    	$data['meta_title'] = "Gradewise Stock Report";
		$data['meta_description'] = "Gradewise Stock Report";
		$data['heading'] = "Gradewise Stock Report";
		$data['title'] = "Modules :: Stock";
	    $data['modules'][] = 'stocks';
	    $data['methods'][] = 'load_barcode_form';
	    echo Modules::run("templates/admin_template", $data);
    }

    function load_barcode_form($stockDetailId = NULL){
    	//echo $this->uri->segment(3);
    	$stockDetailId = (NULL===$this->uri->segment(3))?$stockDetailId:$this->uri->segment(3);
    	if(NULL===$stockDetailId){
    		show_404();
    		exit;
    	}
    	$data['stock_detail_id'] = $stockDetailId;
    	$detail = $this->pktdblib->custom_query('Select sd.*, s.stock_code, s.inward_date from stock_details sd inner join stocks s on s.id=sd.stock_id where sd.id="'.$stockDetailId.'"');
    	$_POST['generate_barcode'] = true;
    	$_POST['barcode_text'] = $detail[0]['stock_code']."-".$detail[0]['id'];
    	include('./qrcode/generate_code.php');

    	echo '<pre>';print_r($detail);
    }
}
