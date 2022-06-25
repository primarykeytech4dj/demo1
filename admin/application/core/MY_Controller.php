<?php
class MY_Controller extends MX_Controller {
    
    protected $data;
    function __construct() {
    	parent::__construct();
        $this->data = [
            'meta_title' => 'PKT: ERP',
            'meta_keyword' => 'PKT: ERP',
            'meta_description' => "ERP for all needs"
        ];
    	$this->load->module('templates', $this->data);
        //$this->load->module('Admin');
        /*print_r($this->session->has_userdata());die;
    	if($this->session->has_userdata('email')) {
    		redirect('admin/adminMethod');
    	}

    	else {
    		
    	}*/
    	
    	

    }

    function return_type($return_type = NULL, $arrayData = []) {
        
        switch($return_type){
            case 'json':
                return json_encode($arrayData);
                break;
            case 'xml':
                $xml = new SimpleXMLElement('<root/>');
                array_walk_recursive($arrayData, array ($xml, 'addChild'));
                return $xml->asXML();
                //array_to_xml();

                break;
            case NULL:
                return $arrayData;
                break;
        }
        
    }

    function download($filename, $path) {
        force_download($filename, $path);
    }
    
}

