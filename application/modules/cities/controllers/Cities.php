<?php

class Cities extends MY_Controller {
	function __construct() {
		parent::__construct();
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		//echo "reached here";exit;
		$this->load->model('cities/cities_model');
		$setup = $this->setup();
	}

	function setup(){
		//echo 2;exit;
		$cities = $this->cities_model->tbl_cities();
		return TRUE;
	}

	function get_dropdown_list($id = NULL)
	{
		$cityList = [];
		$this->pktdblib->set_table('cities');
		$cities = $this->pktdblib->get_active_list();
		foreach($cities as $key => $city) {
			$cityList[$city['id']] = $city['city_name'];
		}
		return $cityList;	
	}

	function index($id = NULL) {
		$data['meta_title'] = "ERP : City";
		$data['title'] = "ERP : City";
		$data['meta_description'] = "ERP : Cities";
		//$data['content'] = "customer_sites/admin_add";
		//$data['method'][] = "admin_add";
		$data['methods'][] = "admin_city_list";

		$data['modules'][] = "cities";
		//$data['countries'] = $this->cities_model->get_active_list();
		//$data['content'] = 'Countries/index';
		echo Modules::run("templates/admin_template", $data);
		//print_r($data['countries']);exit;
	}

	function admin_city_list(){
		
		$this->cities_model->set_table('cities');
		$data['cities'] = $this->cities_model->get_list();

		$this->load->view("cities/admin_city_listing", $data);
	}

	function getStateWiseCities() {
		$params = json_decode(file_get_contents('php://input'), TRUE);
	    if(!empty($params)){
            //print_r($params);exit;
            $_POST = $params;
            
        }
		if(!$this->input->post('params'))
			return;
		
		$condition = [];
		$condition = ['is_active' => TRUE];
		$stateId = $this->input->post('params');
		//echo "hello";exit;
		if(!empty($stateId)) {
			$condition = ['state_id'=>$stateId];
		}

		//echo json_encode($condition);exit;
		$stateWiseCities = $this->cities_model->get_state_wise_cities($condition);

		$this->pktlib->parseOutput($this->config->item('response_format'), ['cities'=>$stateWiseCities]);
		$cityList = [0=>['id'=>0, 'text'=>'Select City']];
		foreach ($stateWiseCities as $key => $city) {
			$cityList[$key+1]['id'] = $city['id'];
			$cityList[$key+1]['text'] = $city['city_name'];
		}
		//print_r($cityList);exit;
		echo json_encode($cityList);
		//print_r($stateList);exit;
		exit;

	}

	function _register_admin_add($data) {
		$this->pktdblib->set_table("cities");
		$id = $this->pktdblib->_insert($data);
		return json_encode(["msg"=>"City Successfully Inserted", "status"=>"success", 'id'=>$id['id']]);
	}
}

?>