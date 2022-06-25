<?php 

class Countries extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('countries/countries_model');
		//$this->load->library('pktdblib');
		$setup = $this->setup();	
	}

	function setup(){
		$countries = $this->countries_model->tbl_countries();
		return TRUE;
	}

	function index($id = NULL) {
		$data['countries'] = $this->pktdblib->get_active_list();
		$data['content'] = 'countries/index';
		$this->template->static_layout($data);
		//print_r($data['countries']);exit;
	}

	function get_dropdown_list($id = NULL)
	{
		$countryList = [];
		$this->pktdblib->set_table('countries');
		$countries = $this->pktdblib->get_active_list();
		$this->pktlib->parseOutput($this->config->item('response_format'), ['countries'=>$countries]);
		foreach($countries as $key => $country) {
			$countryList[$country['id']] = $country['name'];
		}
		return $countryList;	
	}

	function _register_admin_add($data) {
		$this->pktdblib->set_table("countries");
		$id = $this->pktdblib->_insert($data);
		return json_encode(["msg"=>"Countries Successfully Inserted", "status"=>"success", 'id'=>$id]);
	}
}
	