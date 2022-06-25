<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sliders extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Check login and make sure email has been verified
		foreach(custom_constants::$protected_pages as $page)
        {   
            if(strpos($this->uri->uri_string, $page) === 0)
            {   
                check_user_login(TRUE);
            }
        }
        
        $this->load->model("sliders/sliders_model");
        $setup = $this->setup();
       //print_r($query);exit;
	}

    function setup() {
        $slider = $this->sliders_model->tbl_slider();
        return TRUE;
    }

    function index() {
        /*$this->pktdblib->set_table('sliders');
        $slider = $this->pktdblib->get('id desc');
        $data['slider'] = $slider->result_array();*/
        //print_r($data['vendors']);
        $data['meta_title'] = "ERP";
        $data['title'] = "ERP : Slider";
        $data['meta_description'] = "Slider Panel";
        $data['meta_keyword'] = "Slider Panel";

        $data['modules'][] = "sliders";
        $data['methods'][] = "slider_listing";
        
        echo Modules::run("templates/default_template", $data);
    }

	function slider_listing($data = []) {
        //echo 'reached here';
        $params = [];
        if(isset($data['condition']))
            $params['condition'] = $data['condition'];

        if(isset($params['order']))
            $params['order'] = $data['order'];

        $data['sliders'] = $this->sliders_model->get_slider($params);
        //print_r($data['sliders']);
        //$this->load->view("sliders/admin_index", $data);
		$this->load->view("sliders/index", $data);
		//$this->load->view("sliders/venedor_slider",$data);
	}


    function view($sliderCode = NULL){
        if(NULL == $sliderCode){
            echo show_404();
            exit;
        }

        $data['sliderCode'] = $sliderCode;
        $data['meta_title'] = "Slider Module";
        $data['title'] = "Slider";
        $data['meta_description'] = "Slider Panel";
        $data['modules'][] = "sliders";
        $data['methods'][] = "slider_view";
        
        echo Modules::run("templates/default_template", $data);
    }

    function slider_view($data=[]){
        //print_r($data);
        $data['sliders'] = $this->sliders_model->get_slider_detail($data);
        $this->load->view("sliders/valuka-slider", $data);
        //$data['sliderCode'] = 
        //print_r($sliders);exit;
    }

     /*function venedor_slider(){
        $data['slider'] = $this->pktdblib->custom_query("select * from slider_details left join sliders on sliders.id = slider_details.slider_id where slider_details.is_active=true");
        //echo '<pre>';
        //print_r($data);exit;
        $this->load->view('sliders/venedor_slider', $data);
     }*/

     function venedor_slider($data = []) {
        //print_r($data);exit;
        //echo "hiii";exit;
        $this->pktdblib->set_table("sliders");
        $sql = "select slider_details.*, sliders.slider_code, sliders.css,sliders.js from sliders left join slider_details  on slider_details.slider_id = sliders.id and slider_details.is_active=true  where 1=1";

        if(isset($data['condition'])){
            foreach ($data['condition'] as $key => $condition) {
                # code...
                $sql.=" AND ".$key."='".$condition."'";
            }
        }

        $sql.= " AND sliders.is_active=true";

        if(isset($data['order']))
            $sql.=" ORDER BY ".$data['order'];
        else
            $sql.=" ORDER BY sliders.modified DESC";

        if(isset($data['limit']))
            $sql.=" limit ".$data['limit'];
        else
            $sql.=" limit 21";
        $data['sliders'] = $this->pktlib->custom_query($sql);
       //echo '<pre>'; print_r($data['sliders']);exit;
        $this->pktlib->parseOutput($this->config->item('response_format'), ['sliders'=>$data]);
        //echo "<pre>";
        //print_r($data['sliders']);exit;
        $data['css'][] = '<style>'.$data['sliders'][0]['css'].'</style>';
        $this->load->view('sliders/venedor_slider', $data);

    }

}