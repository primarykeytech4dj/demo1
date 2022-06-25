<?php

class Areas extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('areas_model');
		$setup = $this->setup();	
	}

	function setup(){
		$areas = $this->areas_model->tbl_areas();
		return TRUE;
	}

	function index($id = NULL) {
		$data['areas'] = $this->pktdblib->get_active_list();
		$data['content'] = 'areas/index';
		$this->template->static_layout($data);
		//print_r($data['countries']);exit;
	}

	function getCityWiseAreas() {
		//$_POST['params'] = 1;
		//print_r($_POST);
		//echo "hii";
		//exit;
		if(!$this->input->post('params'))
			return;
		$condition = [];
		$condition = ['is_active' => TRUE];
		$cityId = $this->input->post('params');
		//echo "hello";exit;
		if(!empty($cityId)) {
			$condition = ['city_id'=>$cityId];
		}

		//echo json_encode($condition);exit;
		$cityWiseAreas = $this->areas_model->get_city_wise_areas($condition);
		$areaList = [0=>['id'=>0, 'text'=>'Select Area']];

		/*$areaList = [];*/
		foreach ($cityWiseAreas as $key => $area) {
			$areaList[$key+1]['id'] = $area['id'];
			$areaList[$key+1]['text'] = $area['area_name'];
		}
		echo json_encode($areaList);
		//print_r($stateList);exit;
		exit;

	}

	function _register_admin_add($data) {
		//print_r($data);exit;
		$this->pktdblib->set_table("areas");
		$id = $this->pktdblib->_insert($data);
		return json_encode(["msg"=>"Area Successfully Inserted", "status"=>"success", 'id'=>$id['id']]);
	}
}

?>