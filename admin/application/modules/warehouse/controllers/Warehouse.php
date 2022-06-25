<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class warehouse extends MY_Controller {
	function __construct() {
		parent::__construct();
		/*foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
			}
		}*/
		check_user_login(FALSE);
		//$setup = $this->setup();
		$this->load->model('warehouse/warehouse_model');
	}

    function admin_index() {
		if($this->input->is_ajax_request()){  
			
           $postData = $this->input->post();
            //echo "<pre>"; print_r($postData);exit;
            $data = $this->warehouse_model->warehouseList($postData);
            foreach($data['aaData'] as $key=>$v){
            	//echo "<pre>"; print_r($v);exit;
                $active = ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';
                $data['aaData'][$key]['is_active'] = "<i class='".$active."'></i>";
                $action = '
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                        <span class="fa fa-caret-down" ></span>
                    </button>
                    
                </div>';
                $data['aaData'][$key]['action'] = $action;
                //echo $action;exit;
            }
            echo json_encode($data);
            exit;
            
        }

		$data['meta_title'] = 'Warehouse listing';
		$data['meta_description'] = 'Warehouse Details';
		$data['heading'] = '<i class="fa fa-shopping-cart margin-r-5"></i> Warehouse List';
		$data['title'] = 'Modules :: Warehouse';
		$data['modules'][] = 'warehouse';
		$data['methods'][] = 'admin_warehouse_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_warehouse_listing($data = []) {
		
		$this->load->view("warehouse/admin_index", $data);
	}

}