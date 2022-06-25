<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends MY_Controller {
  
  public $apiurl;
  //public $token;

  function __construct() {
    parent::__construct();
    
    $this->apiurl = 'https://localhost/restfulapi';
    //$this->token = $this->login();
    
    
  }

  public function post_api($url, $method='POST', $params){
    echo "hi";exit;
     if($this->input->is_ajax_request()){  
    
    // $this->form_validation->set_rules('lat', 'Latitude', 'required');
    // $this->form_validation->set_rules('lng', 'Longitude', 'required');
     $url  = $this->apiurl.'/customer';
    //echo $this->token;exit;
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_POSTFIELDS =>$params,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
  }
 
}
}
?>