<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address extends MY_Controller {
	function __construct() {
		parent::__construct();
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->model('address/address_model');
		$this->load->model('cities/cities_model');
		$this->load->model('countries/countries_model');
		$this->load->model('states/states_model');
		$this->load->model('areas/areas_model');
		$setup = $this->setup();
	}

	function setup(){
        $address = $this->address_model->tbl_address();
        return TRUE;
    }

	function admin_index() {
		$data['meta_title'] = 'Address Module';
		$data['meta_description'] = 'Address Module';
		$data['meta_keyword'] = 'Address Module';
		//$data['module'] = 'address';
		//$data['content'] = 'address/address_listing';
		$data['modules'][] = 'address';
		$data['methods'][] = 'admin_address_listing';
	
		echo Modules::run("templates/admin_template", $data); 	
	}

	function admin_address_listing($data = []) {
	    //print_r($data);//exit;
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		//echo "string"; exit;
		//print_r($condition);
		$data['address'] = $this->address_model->get_address_list($condition);
		/*print_r($data);
		exit;*/
		$this->load->view("address/admin_index", $data);
	}

	function admin_add($data = []) {
		//echo '<pre>';print_r($data);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        $redirectUrl = 'address/admin_add';
	        //echo '<pre>';print_r($_POST);exit;
			$this->form_validation->set_rules('data[address][address_1]', 'address_1', 'required|max_length[320]');
			//$this->form_validation->set_rules('data[address][address_2]', 'address_2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'state', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'city', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'pincode', 'required');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			if($this->form_validation->run()!==FALSE)
			{
				$_POST['data']['address']['is_default'] = (null!=$this->input->post('data[address][is_default]'))?true:false;
				//$_POST['data']['address']['is_active'] = (null!=$this->input->post('data[address][is_active]'))?true:false;
				$post_data = $_POST;
				/*echo '<pre>';
				print_r($post_data);
				exit;*/
				$countryId = '';
				$stateId = '';
				$cityId = '';
				$areaId = '';
				if(!empty($post_data['data']['countries']['name'])){
					$country['name'] = $post_data['data']['countries']['name'];
					$country['is_active'] = TRUE;
					$country['created'] = date('Y-m-d H:i:s');
					$country['modified'] = date('Y-m-d H:i:s');
					$countryId = json_decode(Modules::run('countries/_register_admin_add', $country), true);
					$post_data['data']['address']['country_id'] = $countryId['id'];//exit;
					unset($post_data['data']['countries']['name']);
				}//exit;
					//echo '<pre>';print_r($post_data);exit;
				if(!empty($post_data['data']['states']['state_name'])){
					$state['state_name'] = $post_data['data']['states']['state_name'];
					$state['is_active'] = TRUE;
					$state['country_id'] = $post_data['data']['address']['country_id'];
					$state['created'] = date('Y-m-d H:i:s');
					$state['modified'] = date('Y-m-d H:i:s');
					$stateId = json_decode(Modules::run('states/_register_admin_add', $state),true);
					//print_r($stateId['id']);exit;
					$post_data['data']['address']['state_id'] = $stateId['id'];
					unset($post_data['data']['states']['state_name']);

				}
				if(!empty($post_data['data']['cities']['city_name'])){
					$city['city_name'] = $post_data['data']['cities']['city_name'];
					$city['short_name'] = $post_data['data']['cities']['city_name'];
					$city['is_active'] = TRUE;
					$city['country_id'] = $post_data['data']['address']['country_id'];
					$city['state_id'] = $post_data['data']['address']['state_id'];
					$city['created'] = date('Y-m-d H:i:s');
					$city['modified'] = date('Y-m-d H:i:s');
					$cityId = json_decode(Modules::run('cities/_register_admin_add', $city), true);
					//print_r($cityId);exit;
					$post_data['data']['address']['city_id'] = $cityId['id'];
					unset($post_data['data']['cities']['city_name']);

				}
				if(!empty($post_data['data']['areas']['area_name'])){
					$area['area_name'] = $post_data['data']['areas']['area_name'];
					$area['is_active'] = TRUE;
					$area['city_id'] = $post_data['data']['address']['city_id'];
					$area['created'] = date('Y-m-d H:i:s');
					$area['modified'] = date('Y-m-d H:i:s');
					$areaId = json_decode(Modules::run('areas/_register_admin_add', $area),true);
					$post_data['data']['address']['area_id'] = $areaId['id'];
					unset($post_data['data']['areas']['area_name']);

				}
				/*echo '<pre>';print_r($post_data);
				exit;*/
				$uid = explode('-', $post_data['data']['address']['user_id']);
				//print_r($uid);
				if(count($uid)>1){
					$post_data['data']['address']['user_id'] = $uid[0];
					$post_data['data']['address']['type'] = $uid[1];
				}
				/*echo '<pre>';
				print_r($post_data['data']['address']);
				exit;*/
				$user_address = json_decode($this->register_user_address($post_data['data']['address']), true);
				//print_r($user_address);
				if($user_address['status'] === "success")
				{ 
					/*print_r($user_address);
					exit;*/
					if(isset($post_data['data']['add_to_site']) && $post_data['data']['add_to_site']==true){
						$post_data['data']['address']['id'] = $user_address['id']['id'];
						Modules::run("customer_sites/add_default_site", $post_data['data']['address']);
					}
					
					/*if Tally integrated*/
                    if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){
                       // echo "hiiii";exit;
                        $tallyLedger = Modules::run('tally/import_ledger',$user_address['id']['id'], 'address');
                        //print_r($tallyLedger);exit;
                    }
                    /* if erpnext enabled*/
                    if(isset($_SESSION['application']['is_erpnext']) && $_SESSION['application']['is_erpnext']==TRUE){
                       // echo "hiiii";exit;
                    	$params['id'] = $post_data['data']['address']['user_id'];
                    	$params['type'] = $post_data['data']['address']['type'];
                    	$params['module'] = $post_data['module'];
                    	$params['site_name'] = $post_data['data']['address']['site_name'];
                    	$erpnext = Modules::run('erpnext/add_address',$params);
                    }
					//exit;
	                $redirectUrl = 'address/admin_edit/'.$user_address['id']['id'];
	                if($this->input->post('module')!='address'){
	                	$redirectUrl = $this->input->post('url')/*.'&address_id='.$user_address['id']*/;
	                }
	                //echo $redirectUrl; exit;
	                if(!$this->input->is_ajax_request()){
						$msg = array('message' => 'Address Added Successfully','class' => 'alert alert-success');
	                    $this->session->set_flashdata('message',$msg);
	                }else{
	                	echo json_encode(['status'=>1, 'value'=>[0=>['id'=>$user_address['id']['id'], 'text'=>$post_data['data']['address']['site_name'].'-'.$post_data['data']['address']['pincode']]], 'msg'=>'Address Created Successfully']);
                        exit;
	                }
				}
				else
				{
					if(!$this->input->is_ajax_request()){
						$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
	                    $this->session->set_flashdata('message',$msg);
	                }else{
	                	echo json_encode(['status'=>0, 'msg'=>$reg_user['msg']]);
                    	exit;
	                }
					//$data['form_error'] = $reg_user['msg'];
				}
			}else{
					//echo validation_errors();exit;
				
                if(!$this->input->is_ajax_request()){
                    $msg = array('message' => "Following Validation Error Occurred.".validation_errors(), 'class' => 'alert alert-danger');
               		$this->session->set_flashdata('message',$msg);
                }else{
                    echo json_encode(['status'=>0, 'msg'=>'validation Error Occured '.validation_errors()]);
                    exit;
                }
			}
				redirect($redirectUrl);
		}
		
		
		
		//$data['areas'] = $this->areas_model->get_list();
		//$data['states'] = $this->states_model->get_list();
		//$data['countries'] = $this->countries_model->get_list();
		$data['meta_title'] = "Address";
		$data['meta_description'] = "Address Details";
		$data['modules'][] = "address";
		$data['methods'][] = "admin_add_form";
		/*echo '<pre>';
		print_r($data);exit;*/
		echo Modules::run("templates/admin_template", $data);
	}

	function register_user_address($data) { 
		//print_r($data);exit;
		//echo "hello";exit;
		/*$data = $_POST;*/
		//print_r($_POST);exit();
		$insert_data = $data;
		//print_r($insert_data);exit;
		$this->pktdblib->set_table("address");
		$id = $this->pktdblib->_insert($insert_data);
		return json_encode(["msg"=>"Address Added Successfully", "status"=>"success", 'id'=>$id]);
	}

	function user_login($addressID) {
		//$this->address_model->set_table("address");
		$data['address']['id'] = $addressID;
		//print_r($data['user']);
		$data['id']['type'] = 'address';
		//exit;
		//print_r($data);exit;
		$data['module'] = 'login';
		$data['method'] = 'user_address';
		echo Modules::run("login/user_address", $data);
	}

	function address_details($id) {
		$this->pktdblib->set_table('address');
		$addressDetails = $this->pktdblib->get_where($id);
		/*echo '<pre>';
		print_r($addressDetails);*/
		return $addressDetails;
	}

	function admin_edit($id = NULL){
	    if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo '<pre>';
			$post_data = $this->input->post();
			/*print_r($_POST);
			exit;*/
			$data['values_posted'] = $_POST['data'];
			//echo "hello";
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[address][address_1]', 'Address_1', 'required|max_length[320]');
			//$this->form_validation->set_rules('data[address][address_2]', 'Address_2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'Country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'State', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'City', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'Area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'Pincode', 'required|max_length[6]');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			if($this->form_validation->run('address') !== FALSE) {
				//print_r($this->input->post('data[address][is_default]'));
				/*$countryId = '';
				$stateId = '';
				$cityId = '';
				$areaId = '';*/ 
				//echo '<pre>';print_r($post_data['data']['address']);exit;

				
				if(!empty($post_data['data']['countries']['name'])){
					$country['name'] = $post_data['data']['countries']['name'];
					$country['is_active'] = TRUE;
					$country['created'] = date('Y-m-d H:i:s');
					$country['modified'] = date('Y-m-d H:i:s');
					$countryId = json_decode(Modules::run('countries/_register_admin_add', $country), true);
					$post_data['data']['address']['country_id'] = $countryId['id'];
					unset($post_data['data']['countries']['name']);
				}//exit;
					//echo '<pre>';print_r($post_data);exit;
				if(!empty($post_data['data']['states']['state_name'])){
					$state['state_name'] = $post_data['data']['states']['state_name'];
					$state['is_active'] = TRUE;
					$state['country_id'] = $post_data['data']['address']['country_id'];
					$state['created'] = date('Y-m-d H:i:s');
					$state['modified'] = date('Y-m-d H:i:s');
					$stateId = json_decode(Modules::run('states/_register_admin_add', $state),true);
					//print_r($stateId['id']);exit;
					$post_data['data']['address']['state_id'] = $stateId['id'];
					unset($post_data['data']['states']['state_name']);

				}
				if(!empty($post_data['data']['cities']['city_name'])){
					$city['city_name'] = $post_data['data']['cities']['city_name'];
					$city['short_name'] = $post_data['data']['cities']['city_name'];
					$city['is_active'] = TRUE;
					$city['country_id'] = $post_data['data']['address']['country_id'];
					$city['state_id'] = $post_data['data']['address']['state_id'];
					$city['created'] = date('Y-m-d H:i:s');
					$city['modified'] = date('Y-m-d H:i:s');
					$cityId = json_decode(Modules::run('cities/_register_admin_add', $city), true);
					//print_r($cityId);exit;
					$post_data['data']['address']['city_id'] = $cityId['id'];
					unset($post_data['data']['cities']['city_name']);

				}
				//print_r($post_data['data']['areas']['area_name']);exit;
				if(!empty($post_data['data']['areas']['area_name'])){
					//echo "new area found";exit;
					$area['area_name'] = $post_data['data']['areas']['area_name'];
					$area['is_active'] = TRUE;
					$area['city_id'] = $post_data['data']['address']['city_id'];
					$area['created'] = date('Y-m-d H:i:s');
					$area['modified'] = date('Y-m-d H:i:s');
					$areaId = json_decode(Modules::run('areas/_register_admin_add', $area),true);
					//print_r($areaId['id']);exit;
					$post_data['data']['address']['area_id'] = $areaId['id'];
					unset($post_data['data']['areas']['area_name']);

				}
				//exit;
				$post_data['data']['address']['is_default'] = (null!==$this->input->post('data[address][is_default]'))?true:false;
				
				//print_r($_POST);exit;
				//$post_data = $_POST['data']['address'];
			}else{
			    //echo validation_errors();
				$error = validation_errors();
				//print_r(validation_errors());
			}
			//echo "hii";
            //exit;
			if(empty($error)) { //echo "hii"; print_r($post_data); exit;
				$sql = 'select id, site_name from address where site_name="'.strtolower($post_data['data']['address']['site_name']).'" and type="'.$post_data['data']['address']['type'].'" and user_id="'.$post_data['data']['address']['user_id'].'"';
				//$sql.= ' and id!='.$post_data['data']['address']['user_id'];
				$sql.= ' and id!='.$id;
				$site = $this->pktdblib->custom_query($sql);
				//echo '<pre>';print_r($site);exit;
				if(count($site)>0){
    				if($this->input->is_ajax_request()){
						echo json_encode(['status'=>0, 'msg'=>"Site Name Already Exist"]);
	                    exit;
	                }else{
	                	$msg = array('message' => "Sitename Already Exist", 'class' => 'alert alert-danger');
	                	$this->session->set_flashdata('message',$msg);
            			redirect($this->input->post('url'));

	                }
    			}
				$this->pktdblib->set_table('address');
				if($this->pktdblib->_update($id,$post_data['data']['address'])) {
				    /*if Tally integrated*/
                    if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE){
                        //echo "hiiii";exit;
                        $tallyLedger = Modules::run('tally/import_ledger',$id, 'address');
                        //print_r($tallyLedger);exit;
                    }
                    
                    /*if erpnext enabled*/
                    if($_SESSION['application']['is_erpnext']){
                        $params['module'] = $post_data['data']['address']['type']; 
                        $params['id'] = $post_data['data']['address']['user_id'];
                    	$params['type'] = $post_data['data']['address']['type'];
                    	$params['module'] = $post_data['module'];
                    	$params['site_name'] = $post_data['data']['address']['site_name'];
                        $erp = Modules::run('erpnext/update_address',$params);
                        }
                    $msg = array('message' => 'Data Updated Successfully  ','class' => 'alert alert-success fade in');
                    //$this->session->set_flashdata('message',$msg);
                    if(!$this->input->is_ajax_request()){
                    
               			$this->session->set_flashdata('message',$msg);
	                }else{
	                    echo json_encode($msg);
	                    exit;
	                }
	                
                }
                else {
                    $msg = array('message' => 'Some problem occured while updating','class' => 'alert alert-danger fade in');
                    $this->session->set_flashdata('message',$msg);
                    if(!$this->input->is_ajax_request()){
	                    $msg = array('message' => "Some problem occurred while updating.", 'class' => 'alert alert-danger');
	               		$this->session->set_flashdata('message',$msg);
	                }else{
	                    echo json_encode(['status'=>0, 'msg'=>'Some problem occurred while updating']);
	                    exit;
	                }
                }
                redirect($this->input->post('url'));

            }
            else{
                if(!$this->input->is_ajax_request()){
                    $msg = array('message' => "Following Validation Error Occurred.".validation_errors(), 'class' => 'alert alert-danger');
               		$this->session->set_flashdata('message',$msg);
                }else{
                    echo json_encode(['status'=>0, 'msg'=>'validation Error Occured '.validation_errors()]);
                    exit;
                }
                redirect($this->input->post('url'));
            }
		}
		else {
			//$this->address_model->set_table("address");
			$data['address'] = $this->address_details($id);
			//print_r($data['address']);exit;
			$data['values_posted']['address'] = $data['address'];
		}
		$data['id'] = $id;
		
		$data['meta_title'] = 'edit address';
		$data['meta_description'] = 'edit address';
		$data['modules'][] = 'address';
		$data['methods'][] = 'admin_edit_form';
		echo Modules::run("templates/admin_template", $data);
	}


	function admin_add_form($data = []) {
		/*echo '<pre>';
		print_r($data);
		exit;*/
		$cities = $this->cities_model->get_dropdown_list();
		//$data['option'] = [];
		$data['option']['cities'][0] = "Select City";
		//print_r($cities);
		foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		$data['option']['states'][0] = "Select State";
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		/*print_r($data['option']['states']);
		exit;*/

		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		$data['option']['areas'][0] = "Select Area";
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}
		/*echo '<pre>';
		print_r($data);exit;*/
		if(!isset($data['type'])){
			$data['type'] = '';//variable is passed through other modules
			$data['user_id'] = '';
		}
		/*
		
		$data['users']  = [];
		
		if(isset($data['module']) && $data['module']=='employees'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['emp_code']];
			$data['option']['typeLists'] = ['employees'=>'employees'];

		}elseif(isset($data['module']) && ($data['module']=='customers' || $data['module']=='customers_v2')){
				$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = ['customers'=>'customers'];

			
		}elseif(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['companies'=>'companies'];
		}elseif(isset($data['module']) && $data['module']=='enquiries'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['enquiries'=>'Lead'];
		}*/
		/*echo '<pre>';
		print_r($data);
		exit;*/
		if(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = [$data['type']=>$data['module']];
		}elseif(isset($data['module']) && $data['module']!='companies'){
			$data['users'] = [$data['user_id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = [$data['type']=>ucfirst($data['module'])];
		}
		else{
			$data['users'] = [];
			$data['option']['typeLists'] = [''=>'Select', 'enquiries'=>'Lead/Enquiry', 'customers'=>'Customer', 'employees'=>'Employees'];
		}
		$data['isCustomerSiteModuleExists'] = is_dir(APPPATH.'modules/customer_sites');
		$this->load->view('address/admin_add', $data);
	}

	function admin_edit_form($data = []) {
	    //exit;
	    if(NULL!==$this->input->get()){
	        $data =$this->input->get(); 
		    
		    $this->pktdblib->set_table($this->input->get('module'));
		    $data['user_detail'] = $this->pktdblib->get_where($this->input->get('user_id'));
		}
		
		//echo "reached here";exit;
	    /*echo '<pre>';
	    print_r($this->input->get());exit;*/
	    //
		//echo $this->uri->segment(3);exit;
		//print_r($data);exit;
		if(is_array($data) && NULL===$this->input->get()){ //echo "hello";exit;
			$data['address'] = $this->address_details($this->uri->segment(3));
		}else{ //echo "hii";exit;
		    //print_r($this->input->get());//exit;*/
		    $addressId = $this->input->get('address_id');
		    $data['module'] = $this->input->get('module');
		    $data['address'] = $this->address_details($addressId);
		}
		//echo "reached here";exit;
		//print_r($data);exit;
		$cities = $this->pktdblib->custom_query('select * from cities where is_active=true and state_id='.$data['address']['state_id']);
		//$data['option'] = [];
		$data['option']['cities'][0] = "Select City";
		//print_r($cities);
		foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->pktdblib->custom_query('select * from states where is_active=true and country_id='.$data['address']['country_id']);
		$data['option']['states'][0] = "Select State";
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		/*print_r($data['option']['states']);
		exit;*/

		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
        //echo 'select * from areas where is_active=true and city_id='.$data['address']['city_id'];             
		$areas =$this->pktdblib->custom_query('select * from areas where is_active=true and city_id='.$data['address']['city_id']);
		$data['option']['areas'][0] = "Select Area";
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}

		if(!isset($data['type'])){
			$data['type'] = '';//variable is passed through other modules
			$data['user_id'] = '';
		}
		
		$data['users']  = [];
		//echo $data['module'];
		/*if(isset($data['module']) && $data['module']=='employees'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['emp_code']];
			$data['option']['typeLists'] = ['employees'=>'employees'];

		}elseif(isset($data['module']) && ($data['module']=='customers' || $data['module']=='customers_v2')){
				$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = ['customers'=>'customers'];

			
		}elseif(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['companies'=>'companies'];
		}elseif(isset($data['module']) && $data['module']=='enquiries'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname'].' | '.$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['enquiries'=>'Leads'];
		}else{
			$data['option']['typeLists'] = [''=>'Address belongs to?', 'employees'=>'Employee', 'customers'=>'Customer', 'suppliers'=>'Supplier', 'companies'=>'companies'];

		}*/
		/*if(isset($data['module']) && $data['module']=='companies'){
			$data['users'] = [$data['user_id']=>$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['login'=>'companies'];
		}elseif(isset($data['module']) && $data['module']=='enquiries'){
			$data['users'] = [$data['user_detail']['id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']." | ".$data['user_detail']['company_name']];
			$data['option']['typeLists'] = ['enquiries'=>'Lead'];

		}else{
			$data['users'] = [$data['user_id']=>$data['user_detail']['first_name']." ".$data['user_detail']['middle_name']." ".$data['user_detail']['surname']];
			$data['option']['typeLists'] = ['login'=>'companies'];
		}*/
		/*echo '<pre>';
		print_r($data);exit;*/
		
		//print_r($data);exit;
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
		

		$data['values_posted']['address'] = $data['address'];
		
		$this->load->view('address/admin_edit', $data);
	}

	function view_user_wise_address($data = NULL) {
		if(NULL == $data){
			show_404();
		}
		//print_r($data);exit;
		$data['address'] = $this->address_model->getUserWiseAddress($data['user_id'], $data['type']);
		$this->load->view('address/admin_address_listing', $data);;
		/*print_r($address);
		exit;*/
	}

	function type_wise_user(){
		//print_r($_POST);exit;
		//$_POST['params'] = "employees";
		
		if(!$this->input->post('params'))
			return;

		$addressType = $this->input->post('params');
		
		//echo json_encode($condition);exit;
		//$this->address_model->set_table('address');
		$typeWiseUsers = $this->address_model->get_custom_address_type_users($addressType);
		//print_r($typeWiseUsers);exit;
		$userList = [0=>['id'=>0, 'text'=>'Select User']];
		foreach ($typeWiseUsers as $key => $typeWiseUser) {
			$userList[$key+1]['id'] = $typeWiseUser['id'];
			//$userList[$key+1]['text'] = $typeWiseUser['fullname']." | ".$typeWiseUser['contact_1'];
			$userList[$key+1]['text'] = $typeWiseUser['fullname']." | ".$typeWiseUser['company_name']." | ".$typeWiseUser['contact_1'];
		}
		
		echo json_encode($userList);
		exit;
	}

	
	function type_wise_user2($data = []){
		$userType = '';
		$userId = '';
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$userType = $this->input->post('type');
			$userId = $this->input->post('id');
		}else{
			$userType = $data['type'];
			$userId = $data['id'];
		}
		$sql = 'Select a.id,a.site_name, a.pincode, a.tally_address, a.address_1, a.address_2,a.is_default from customers c inner join address a on a.user_id=c.id and a.type="'.$userType.'" where a.user_id='.$userId.' and a.is_active=true';
        //echo 'select * from user_roles where user_id='.$userId.' and account_type="'.$userType.'"';
        $sql2 = $this->pktdblib->custom_query('select * from user_roles where user_id='.$userId.' and account_type="'.$userType.'"');
        if(count($sql2)>0){
            //$sql2 = $sql2[0];
            $role = [];
            foreach($sql2 as $roleAddress){
                //print_r($roleAddress);
                $role[] = $roleAddress['login_id'];
            }
            $sql.= ' UNION Select a.id,a.site_name, a.pincode, a.tally_address, a.address_1, a.address_2,a.is_default from address a where a.is_active=true and a.user_id in ('.implode(",", $role).') and a.type="login"';
        }
        
        $sql2 = "Select * from (".$sql.") t order by t.is_default DESC";
        //echo json_encode($sql).'<br/>';exit;
		$typeWiseUsers = $this->pktdblib->custom_query($sql2);//exit;
		$userList = [];
		if(count($typeWiseUsers)>0){
    		
    		foreach ($typeWiseUsers as $key => $typeWiseUser) {
    			//print_r($typeWiseUser['id']).'<br/>';
    			$userList[$key]['id'] = $typeWiseUser['id'];
    			//$userList[$key+1]['text'] = $typeWiseUser['site_name']." ".$typeWiseUser['pincode'];
    			$userList[$key]['text'] = (!empty($typeWiseUser['tally_address']))?$typeWiseUser['site_name']." - ".$typeWiseUser['tally_address']:$typeWiseUser['address_1']." ".$typeWiseUser['address_2']." ".$typeWiseUser['pincode'];
    		}
		}else{
		    $userList = [0=>['id'=>0, 'text'=>'Select Address']];
		}
		if(empty($data)){
		    echo json_encode($userList);
		    exit;
		}else{
		    return $userList;
		}
		
	}

	function add_form($data = []) {

		$cities = $this->cities_model->get_dropdown_list();
		$data['option']['cities'][0] = "Select City";
		foreach ($cities as $cityKey => $city) {
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		$data['option']['states'][0] = "Select State";
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		
		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		$data['option']['areas'][0] = "Select Area";
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}
		
		$customerSites = Modules::run('customer_sites/admin_index');
		//print_r($customerSites);exit;
		$this->load->view('address/add', $data);
	}

	function add($data = []) {
		//print_r($data);exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        $redirectUrl = $this->input->post('url');
	        //print_r($_POST);exit;
			$this->form_validation->set_rules('data[address][address_1]', 'address_1', 'required|max_length[320]');
			//$this->form_validation->set_rules('data[address][address_2]', 'address_2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'state', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'city', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'pincode', 'required');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			if($this->form_validation->run()!==FALSE)
			{
				$_POST['data']['address']['is_default'] = true;//(null!=$this->input->post('data[address][is_default]'))?true:false;
				$_POST['data']['address']['user_id'] = $this->session->userdata('employee_id');
				$_POST['data']['address']['type'] = 'customers';
				$post_data = $_POST;
				
				$user_address = json_decode($this->register_user_address($post_data['data']['address']), true);
				if($user_address['status'] === "success")
				{ 
					
	                $redirectUrl = custom_constants::edit_address_url.'/'.$user_address['id'];
	                if($this->input->post('module')!='address'){
	                	$redirectUrl = $this->input->post('url');
	                }
	                //echo $redirectUrl; exit;
					$msg = array('message' => 'Address Added Successfully','class' => 'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
				}
				else
				{
					$msg = array('message' => $reg_user['msg'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
					//$data['form_error'] = $reg_user['msg'];
				}
			}else{
					//echo validation_errors();exit;
				$msg = array('message' => "Following Validation Error Occurred.".validation_errors(), 'class' => 'alert alert-danger');
                $this->session->set_flashdata('message',$msg);
			}
				redirect($redirectUrl);
		}
		
		
		
		//$data['areas'] = $this->areas_model->get_list();
		//$data['states'] = $this->states_model->get_list();
		//$data['countries'] = $this->countries_model->get_list();
		$data['meta_title'] = "Address";
		$data['meta_description'] = "Address Details";
		$data['modules'][] = "address";
		$data['methods'][] = "admin_add_form";
		/*echo '<pre>';
		print_r($data);exit;*/
		echo Modules::run("templates/admin_template", $data);
	}

	function edit_form($data = []) {
		//echo $this->uri->segment(3);exit;
		if(empty($data)){
			$data['address'] = $this->address_details($this->uri->segment(3));
		}
		//print_r($data);
		$cities = $this->cities_model->get_dropdown_list();
		//$data['option'] = [];
		$data['option']['cities'][0] = "Select City";
		//print_r($cities);
		foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		$data['option']['states'][0] = "Select State";
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		/*print_r($data['option']['states']);
		exit;*/

		$countries =$this->countries_model->get_dropdown_list();
		$data['option']['countries'][0] = "Select Country";
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		$data['option']['areas'][0] = "Select Area";
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}

		$data['values_posted']['address'] = $data['address'];
		
		$this->load->view('address/edit', $data);
	}

	function edit($id = null){
		//print_r($data);
		//echo "hello";
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
			//echo "hello";
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[address][address_1]', 'Address 1', 'required|max_length[320]');
			//$this->form_validation->set_rules('data[address][address_2]', 'Address 2', 'required|max_length[320]');
			$this->form_validation->set_rules('data[address][country_id]', 'Country', 'required');
			$this->form_validation->set_rules('data[address][state_id]', 'State', 'required');
			$this->form_validation->set_rules('data[address][city_id]', 'City', 'required');
			$this->form_validation->set_rules('data[address][area_id]', 'Area', 'required');
			$this->form_validation->set_rules('data[address][pincode]', 'Pincode', 'required|max_length[6]');
			$this->form_validation->set_rules('data[address][site_name]', 'Site Name', 'required');
			if($this->form_validation->run('address') !== FALSE) {
				//print_r($this->input->post('data[address][is_default]'));
				$_POST['data']['address']['is_default'] = (null!=$this->input->post('data[address][is_default]'))?true:false;
				//print_r($_POST);exit;
				$post_data = $_POST['data']['address'];
			}else{
				$error[] = validation_errors();
				//print_r(validation_errors());
			}

			//print_r($_POST);exit;
			if(empty($error)) { //echo "hii"; exit;
				if($this->pktdblib->_update($id,$post_data)) {
						
                    $msg = array('message' => 'Address Updated Successfully  ','class' => 'alert alert-success fade in');
                         $this->session->set_flashdata('message',$msg);
                }
                else {
				//echo "string";exit;
                     $msg = array('message' => 'some problem occured while updating','class' => 'alert alert-danger fade in');
                     $this->session->set_flashdata('message',$msg);
                }
                redirect($this->input->post('url'));

            }
            else{
                $msg = array('message' => $error,'class' => 'alert alert-danger fade in');
                $this->session->set_flashdata('message', $msg);
                //redirect('address/edit_address/'.$id);
                redirect($this->input->post('url'));
            }
		}
		else {
			//$this->address_model->set_table("address");
			$data['address'] = $this->address_details($id);
			//print_r($data['address']);exit;
			$data['values_posted']['address'] = $data['address'];
		}
		$data['id'] = $id;
		
		$data['meta_title'] = 'Edit Address';
		$data['meta_description'] = 'Edit Address';
		$data['modules'][] = 'address';
		$data['methods'][] = 'edit_form';
		echo Modules::run("templates/admin_template", $data);
	}

	function delete_address($id){
		//echo $id;exit;
		$this->pktdblib->set_table('address');
		$address = $this->pktdblib->_delete($id);
		//echo 'data deleted';
		//print_r($address);
		return true;
		//exit;
	}

	function type_wise_user_dropdown($data = []){
		$userType = '';
		$userId = '';
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$userType = $this->input->post('type');
			$userId = $this->input->post('id');
		}else{
			$userType = $data['type'];
			$userId = $data['id'];
		}
		$sql = 'Select a.id,a.site_name, a.pincode, a.tally_address from customers c inner join address a on a.user_id=c.id and a.type="'.$userType.'" where a.user_id='.$userId.' and a.is_active=true';
        $sql2 = $this->pktdblib->custom_query('select * from user_roles where user_id='.$userId.' and account_type="'.$userType.'"');
        if(count($sql2)>0){
            $sql2 = $sql2[0];
            $sql.= ' UNION Select a.id,a.site_name, a.pincode, a.tally_address from address a where a.is_active=true and a.user_id='.$sql2['login_id'].' and a.type="login"';
        }
		$typeWiseUsers = $this->pktdblib->custom_query($sql);
		$userList = (count($typeWiseUsers)>0)?[0=>'Select Address']:[];
		foreach ($typeWiseUsers as $key => $typeWiseUser) {
			$userList[$typeWiseUser['id']] = (!empty($typeWiseUser['tally_address']))?$typeWiseUser['tally_address']:$typeWiseUser['site_name'];
		}
		return $userList;
	}
	
	function addressTab($moduleName, $id){
	    $this->pktdblib->set_table($moduleName);
        $data[$moduleName] = $this->pktdblib->get_where($id);
        $type = $moduleName;
        $loginId = $id;
        $userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$id, 'account_type'=>$moduleName]);
        if($userRoles!=FALSE && count($userRoles)){
            $loginId = $userRoles[0]['login_id'];
            $type = 'login';
        }
        //echo $type;
        $this->pktdblib->set_table('address');
        $addressData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=address', 'module'=>$moduleName, 'user_id'=>$loginId, 'type'=>$type, 'user_detail'=>$data[$moduleName]];
        if($this->input->get('address_id')){ 
            $addressData['address'] = $this->pktdblib->get_where($this->input->get('address_id'));
            echo Modules::run("address/admin_edit_form", $addressData);
        }else {
            echo Modules::run("address/admin_add_form", $addressData);
        }
        
        $addressListData = ['url'=>custom_constants::edit_customer_url.'/'.$id.'?tab=address', 'condition'=>['address.user_id'=>$loginId, 'address.type'=>$type], 'module'=>$moduleName, 'user_id'=>$loginId, 'type'=>$type, 'user_detail'=>$data[$moduleName]];
        echo Modules::run("address/admin_address_listing", $addressListData);
    }
    
    public function getAddress($data = []){
    	$sql = 'select a.* from address a WHERE a.user_id='.$data['user_id'].' and a.type="'.$data['type'].'" and a.is_default=true UNION select a.* from address a join login on login.id=a.user_id and type="login" join customers on customers.id=login.employee_id  WHERE customers.id='.$data['user_id'].' and a.type="login" and a.is_default=true LIMIT 0, 25';
    	$address = $this->pktdblib->custom_query($sql);
    	return $address;
    }
}