<?php
class States extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('states/states_model');
		$setup = $this->setup();

	}

	function setup(){
		$states = $this->states_model->tbl_states();
		return TRUE;
	}

	function getCountrywiseStates() {
	    
	    $params = json_decode(file_get_contents('php://input'), TRUE);
	    if(!empty($params)){
            //print_r($params);exit;
            $_POST = $params;
            
        }
		if(!$this->input->post('params'))
			return;

		$condition = [];
		$condition = ['is_active' => TRUE];
		$countryId = $this->input->post('params');
		//echo "hello";exit;
		if(!empty($countryId)) {
			$condition = ['country_id'=>$countryId];
		}

		//echo json_encode($condition);exit;
		$countryWiseStates = $this->states_model->get_country_wise_state($condition);
		$this->pktlib->parseOutput($this->config->item('response_format'), ['states'=>$countryWiseStates]);
		$stateList = [0=>['id'=>0, 'text'=>'Select State']];

		//print_r(json_encode($countryWiseStates));exit;
		/*$stateList = [];*/
		foreach ($countryWiseStates as $key => $state) {
			$stateList[$key+1]['id'] = $state['id'];
			$stateList[$key+1]['text'] = $state['state_name'];
		}
		//$this->pktlib->parseOutput($this->config->item('response_format'), $statelist);
		echo json_encode($stateList);
		
	}

	function _register_admin_add($data) {
		$this->pktdblib->set_table("states");
		$id = $this->pktdblib->_insert($data);
		//$this->pktlib->parseOutput($this->config->item('response_format'), $data);
		return json_encode(["msg"=>"State Successfully Inserted", "status"=>"success", 'id'=>$id['id']]);
	}
}

?>