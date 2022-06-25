<?php 

class Setup extends MY_Controller {
	function __construct() {
		parent::__construct();

		/*foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->model('setup/setup_model');
		$setup = $this->setup();*/	
	}

	function load_setup(){
	    //echo "hii";exit;
	    $this->pktdblib->set_table('setup');
	    $query = $this->pktdblib->get_where_custom('is_active', true);
	    $setup = $query->result_array();
	    /*print_r($setup);
	    exit;*/
	    $response = [];
	    foreach($setup as $key=>$set){
	        $response[$set['parameter']] = $set['value'];
	    }
	    
	    echo json_encode($response);
	    exit;
	}

}
	