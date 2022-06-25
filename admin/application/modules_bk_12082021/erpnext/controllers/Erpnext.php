<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Erpnext extends MY_Controller {
  public $tallyServer;
  public $username;
  public $password;
  public $grant_type;
  public $scope;
  public $apiurl;
  public $token;
  function __construct() {
    parent::__construct();
    
    check_user_login(FALSE);
    $this->username = 'api1@niftyplastics.in';
    $this->password = 'Ema@api#1';
    $this->client_id = '02bb4218a2';
    $this->grant_type = 'password';
    $this->scope = 'all openid';
    $this->apiurl = 'https://expede.erpnext.com/api';
    $this->load->library('pktdblib');
    $this->token = $this->login();
    if(!$this->token){
      return false;
    }
  }

  function setup(){
    //$tbl = $this->tally_model->tbl_tally_ledger();
    return TRUE;
  }


  public function login(){
    $url  = $this->apiurl.'/method/frappe.integrations.oauth2.get_token';
    $params['username'] = $this->username;//'api1@niftyplastics.in';
    $params['password'] = $this->password;//'Ema@api#1';
    $params['client_id'] = $this->client_id;
    $params['grant_type'] = $this->grant_type;
    $params['scope'] = $this->scope;
    $login = $this->login_curl($url, 'POST', $params);

    //print_r($login);exit;
    $postData['url'] = $url;
    $postData['short_name'] = 'login';
    $postData['request'] = json_encode($params);
    $postData['response'] = $login;
    $postData['created'] = date('Y-m-d H:i:s');
    $postData['created_by'] = $this->session->userdata('user_id');
    //echo '<pre>';print_r($postData);exit;
    $this->pktdblib->set_table('erpnext_api_logs');
    $apiRequest = $this->pktdblib->_insert($postData);
    /*echo $this->db->last_query();
    exit;*/
    $result = json_decode($login, true);
    //echo '<pre>';print_r($result);exit;

    $token = isset($result['access_token'])?$result['access_token']:false;
    return $token;
  }

  public function login_curl($url, $method = 'POST',$params){
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
      CURLOPT_POSTFIELDS => $params,
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
    
  }

  public function curl_request($url, $method='POST', $params){
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
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    return $response;
  }

  public function create_customer($data=[]){
    //$data = ['company_name'=>'pkt','referral_code'=>'123','primary_email'=>'test@gmail.com', 'contact_1'=>'9167632340', 'id'=>1];
    $url = $this->apiurl.'/resource/Customer';
    $params['customer_name'] = $data['company_name'];
    $params['naming_series'] = 'CN.########';
    $params['customer_group'] = 'All Customer Groups';
    $params['territory'] = 'All Territories';
    $params['entered_by'] = $this->username;
    $params['registered_on'] = date('Y-m-d H:i:s');
    $params['referral_code'] = $data['referral_code'];
    $createCustomer = json_decode($this->postApi($url, 'POST', json_encode($params), $this->token),true);
    //print_r($createCustomer);exit;
    $postData['url'] = $url;
    $postData['short_name'] = 'create customer';
    $postData['request'] = json_encode($params);
    $postData['response'] = json_encode($createCustomer);
    $postData['created'] = date('Y-m-d H:i:s');
    $postData['created_by'] = $this->session->userdata('user_id');
    //echo '<pre>';print_r($postData);exit;
    $apiLog = $this->api_log($postData);
    if(isset($createCustomer['data'])){
      $contactUrl = $this->apiurl.'/resource/Contact';
      $contactparams['first_name'] = $data['first_name'];
      $contactparams['middle_name'] = $data['middle_name'];
      $contactparams['last_name'] = $data['surname'];
      $contactparams['email_id'] = $data['primary_email'];
      $contactparams['is_primary_contact'] = 0;
      $contactparams['email_ids'] = [['email_id'=>$data['primary_email']]];
      $contactparams['phone_nos'] = [['phone'=>$data['contact_1'],'is_primary_phone'=>1,'is_primary_mobile_no'=>1]];
      $contactparams['links'] = [['link_doctype'=>'Customer', 'link_name'=>$createCustomer['data']['name']]];
      $contactparams['unsubscribed'] = 0;
      //echo json_encode($contactparams);exit;
      $createContact = json_decode($this->postApi($contactUrl, 'POST', json_encode($contactparams)),true);
      $postData['url'] = $contactUrl;
      $postData['short_name'] = 'create contact';
      $postData['request'] = json_encode($contactparams);
      $postData['response'] = json_encode($createContact);
      $postData['created'] = date('Y-m-d H:i:s');
      $postData['created_by'] = $this->session->userdata('user_id');
      $apiLog = $this->api_log($postData);
      
      $insert = ['customer_name'=>$createCustomer['data']['name'],
      'contact_name'=>$createContact['data']['name'],'account_type'=>'customers', 'user_id'=>$data['id'], 'is_contact_created'=>1, 'created'=>date('Y-m-d H:i:s'), 'created_by'=>$this->session->userdata('user_id')];
      $this->pktdblib->set_table('erpnext_user');
      $user = $this->pktdblib->_insert($insert);
      if($user){
        return true;
      }
      /*print_r($user);
      echo 'contact created successfully';exit;*/
    }
  }

  public function add_address($data = []){
    //echo 'reached in erpnext module';print_r($data);exit;
    if($data['module'] != 'customers'){
      return false;
    }
    $addrParams['user_id'] = $data['id'];
    $addrParams['type'] = $data['type'];
    $addrParams['site_name'] = $data['site_name'];
    $user = $this->get_user_details($addrParams);
    if(!$user){
      return false;
    }
    $url = $this->apiurl.'/resource/Address';
    $data = ['address_title'=>$user['site_name'],'address_type'=>'Billing','address_line1'=>$user['address_1'],
    'address_line2'=> $user['address_2'],
    'city'=> $user['city_name'],
    'county'=> null,
    'state'=> $user['state_name'],
    'country'=> $user['country_name'],
    'pincode'=> null,
    'email_id'=> $user['primary_email'],
    'phone'=> $user['primary_email'],
    'fax'=> null,
    'is_primary_address'=> 1,
    'is_shipping_address'=> 0,
    'disabled'=> 0,
    'is_your_company_address'=> 0,
    'links'=>[['link_doctype'=>'Customer','link_name'=>$user['customer_name']]],
    'tax_category'=> null,
    'gstin'=> null,
    //'gst_state'=> 'Maharashtra',
    'gst_state_number'=> '27',
    'route_no'=> '1'];
    $params = $data;

    $createAddress = json_decode($this->postApi($url, 'POST', json_encode($params), $this->token),true);
    //echo '<pre>';print_r($createAddress);exit;
    $postData['url'] = $url;
    $postData['short_name'] = 'Create Address';
    $postData['request'] = json_encode($params);
    $postData['response'] = json_encode($createAddress);
    $postData['created'] = date('Y-m-d H:i:s');
    $postData['created_by'] = $this->session->userdata('user_id');
    $apiLog = $this->api_log($postData);
    return true;
    //echo '<pre>';print_r($createAddress);exit;
  }

  public function update_address($data = []){
    //echo 'reached in erpnext module'.'<br>';echo '<pre>';print_r($data);exit;
    if($data['module'] != 'customers'){
      return false;
    }
    $addrParams['user_id'] = $data['id'];
    $addrParams['type'] = $data['type'];
    $addrParams['site_name'] = $data['site_name'];
    $user = $this->get_user_details($addrParams);
    if(!$user){
      return false;
    }
    isset($user['site_name'])?$params['address_title'] = $user['site_name']:'';
        $params['address_type'] = "Billing";
        isset($user['address_1'])?$params['address_line1'] = $user['address_1']:'';
        isset($user['address_2'])?$params['address_line2'] = $user['address_2']:'';
        isset($user['city_name'])?$params['city'] = $user['city_name']:'';
        $params['county'] = null;
        isset($user['state_name'])?$params['state'] = $user['state_name']:'';
        isset($user['country_name'])?$params['country'] = $user['country_name']:'';
        isset($user['pincode'])?$params['pincode'] = $user['pincode']:'';
        isset($user['primary_email'])?$params['email_id'] = $user['primary_email']:'';
        isset($user['referral_code'])?$params['phone'] = $user['referral_code']:'';
        $params['links'] = [
            'link_doctype'=>'Customer',
            'link_name'=> $user['customer_name']
        ];
        isset($user['state_name'])?$params['gst_state'] = $user['state_name']:'';
        $params['route_no'] = 1;
        //echo '<pre>';print_r($params);exit;
        //echo json_encode($params);
        https://expede.erpnext.com/api/resource/Address/Customer Test-Billing
        $url = $this->apiurl.'/resource/Address/'.$data['site_name'].'-Billing';
        $method = 'PUT';
        $result = json_decode($this->postApi($url, 'PUT', json_encode($params)),true);
        if($method == 'POST' || $method == 'PUT'){
          $postData['url'] = $url;
          $postData['short_name'] = 'update address';
          $postData['request'] = json_encode($params);
          $postData['response'] = json_encode($result);
          $postData['created'] = date('Y-m-d H:i:s');
          $postData['created_by'] = $this->session->userdata('user_id');
          $apiLog = $this->api_log($postData);

        }
        if($result){
          //echo '<pre>';print_r($result);exit;
          return true;
        }
  }

  public function postApi($url, $method='POST', $params){
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
        'Authorization: Bearer '.$this->token
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
  }

  public function api_log($data){
    //print_r($data);exit;
    $this->pktdblib->set_table('erpnext_api_logs');
    $response = $apiRequest = $this->pktdblib->_insert($data);
    return $response;

  }

  public function get_customer_details($name=''){
    $name = 'CN00000015';
    $url = $this->apiurl.'/resource/Customer/'.$name;
    $result = json_decode($this->postApi($url, 'GET', ''),true);
   // print_r($result);exit;
    $postData['url'] = $url;
    $postData['short_name'] = 'RetrieveCustomerDetails';
    //$postData['request'] = json_encode($params);
    $postData['response'] = json_encode($result);
    $postData['created'] = date('Y-m-d H:i:s');
    $postData['created_by'] = $this->session->userdata('user_id');
    $apiLog = $this->api_log($postData);
    echo '<pre>';print_r($result);exit;
  }

  public function get_address_name($phone=''){
    $phone = '916763240';
    $url = $this->apiurl.'/resource/Address?filters={"phone":"'.$phone.'"}';
    $result = json_decode($this->postApi($url, 'GET', ''),true);
    //echo $url.'<br>';print_r($result);exit;
    $postData['url'] = $url;
    $postData['short_name'] = 'RetrieveAddressName';
    //$postData['request'] = json_encode($params);
    $postData['response'] = json_encode($result);
    $postData['created'] = date('Y-m-d H:i:s');
    $postData['created_by'] = $this->session->userdata('user_id');
    $apiLog = $this->api_log($postData);
    //echo '<pre>';print_r($result);exit;
  }

  public function get_contact_name($phone=''){
    //$phone = '9167632340';
    $url = $this->apiurl.'/resource/Contact?filters={"mobile_no":"'.$phone.'"}';
    $params['filters'] = ['mobile_no'=>$phone];
    $result = json_decode($this->postApi($url, 'GET', json_encode($params)),true);
    //echo $url.'<br>';print_r($result);exit;
    $postData['url'] = $url;
    $postData['short_name'] = 'RetrieveContactName';
    $postData['request'] = json_encode($params);
    $postData['response'] = json_encode($result);
    $postData['created'] = date('Y-m-d H:i:s');
    $postData['created_by'] = $this->session->userdata('user_id');
    $apiLog = $this->api_log($postData);
    echo '<pre>';print_r($result);exit;
  }

  public function get_address_detail($addressName=''){
    //$addressName = 'Customer Test-Billing-1';
    $url = $this->apiurl.'/resource/Address/'.$addressName;
    $result = json_decode($this->postApi($url, 'GET', ''),true);
    //echo $url.'<br>';print_r($result);exit;
    $postData['url'] = $url;
    $postData['short_name'] = 'RetrieveAddressDetails';
    //$postData['request'] = json_encode($params);
    $postData['response'] = json_encode($result);
    $postData['created'] = date('Y-m-d H:i:s');
    $postData['created_by'] = $this->session->userdata('user_id');
    $apiLog = $this->api_log($postData);
    //echo '<pre>';print_r($result);exit;
  }  

  public function get_contact_detail($name=''){
    //$name = 'pkt-CN00000017';
    $url = $this->apiurl.'/resource/Contact/'.$name;
    $result = json_decode($this->postApi($url, 'GET', ''),true);
    //echo $url.'<br>';print_r($result);exit;
    $postData['url'] = $url;
    $postData['short_name'] = 'RetrieveContactDetails';
    //$postData['request'] = json_encode($params);
    $postData['response'] = json_encode($result);
    $postData['created'] = date('Y-m-d H:i:s');
    $postData['created_by'] = $this->session->userdata('user_id');
    $apiLog = $this->api_log($postData);
    //echo '<pre>';print_r($result);exit;
  } 

  public function get_erpnext_status($url='', $method='', $type='',$attr='', $shortname = ''){
    //$attr = 'Customer Test-Billing-1';$method='GET';$type = 'Address';
    $attr = 'Customer Test-Billing-1';$method='GET';$type = 'Address';
    //delete
    $url  = $this->apiurl.'/resource/'.$type.'/'.$attr;
    $result = json_decode($this->postApi($url, $method, ''),true);
    if($method == 'POST' || $method == 'PUT'){
      $postData['url'] = $url;
      $postData['short_name'] = $shortname;
      //$postData['request'] = json_encode($params);
      $postData['response'] = json_encode($result);
      $postData['created'] = date('Y-m-d H:i:s');
      $postData['created_by'] = $this->session->userdata('user_id');
      $apiLog = $this->api_log($postData);

    }
    echo '<pre>';print_r($result);exit;
    //echo $url.'<br>';print_r($result);exit;
  }  

  public function update_customer($data = []){

        
        $params['modified'] = date('Y-m-d H:i:s');
        $params['modified_by'] = $this->username;
        isset($data['company_name'])?$params['customer_name'] = $data['company_name']:'';
        isset($data['customer_type'])?$params['customer_type'] = $data['customer_type']:'';
        isset($data['customer_group'])?$params['customer_group'] = $data['customer_group']:'';
        isset($data['customer_primary_contact'])?$params['customer_primary_contact'] = $data['customer_primary_contact']:'';
        isset($data['mobile_no'])?$params['mobile_no'] = $data['mobile_no']:'';
        isset($data['email_id'])?$params['email_id'] = $data['email_id']:'';
        isset($data['customer_primary_address'])?$params['customer_primary_address'] = $data['customer_primary_address']:'';
        isset($data['referral_code'])?$params['referral_code'] = $data['referral_code']:'';
        isset($data['doctype'])?$params['doctype'] = $data['doctype']:'';
        //echo '<pre>';print_r($params);exit;
        //echo json_encode($params);
        $url = $this->apiurl.'/resource/Customer/'.$data['name'];
        $method = 'PUT';
        $result = json_decode($this->postApi($url, 'PUT', json_encode($params)),true);
        if($method == 'POST' || $method == 'PUT'){
          $postData['url'] = $url;
          $postData['short_name'] = 'update customer';
          $postData['request'] = json_encode($params);
          $postData['response'] = json_encode($result);
          $postData['created'] = date('Y-m-d H:i:s');
          $postData['created_by'] = $this->session->userdata('user_id');
          $apiLog = $this->api_log($postData);

        }
        if($result){
          return true;
        }
        //echo '<pre>';print_r($result);exit;
        //echo json_encode($data);
      }

    public function get_user_details($data = []){
        //$data['user_id'] = 1039;$data['type'] = 'customers';
        $sql = 'select c.*, e.contact_name, e.customer_name, e.user_id as erp_user_id, a.*, cn.name as country_name, st.state_name, ct.city_name from address a join customers c on c.id=a.user_id and a.type="customers" join erpnext_user e on e.user_id=c.id left join countries cn on cn.id=a.country_id left join states st on st.id=a.id left join cities ct on ct.id=a.city_id WHERE a.user_id='.$data['user_id'].' and a.type="customers" and a.site_name="'.$data['site_name'].'" UNION select customers.*, e.contact_name, e.customer_name,e.user_id as erp_user_id, a.*, cn.name as country_name, st.state_name, ct.city_name from address a join login on login.id=a.user_id and type="login" join customers on customers.id=login.employee_id join erpnext_user e on e.user_id=customers.id left join countries cn on cn.id=a.country_id left join states st on st.id=a.id left join cities ct on ct.id=a.city_id WHERE customers.id='.$data['user_id'].' and a.type="login" and a.site_name="'.$data['site_name'].'" LIMIT 0, 25';
      $detail = $this->pktdblib->custom_query($sql);
      //echo $this->db->last_query();exit;
      //echo '<pre>';print_r($detail);exit;
      return $detail[0];
    }
      
    public function test($id){
        //echo $id;exit;
        $this->pktdblib->set_table('customers');
        $cust = $this->pktdblib->get_where($id);
        $create = $this->create_customer($cust);
        echo '<pre>';print_r($create);
    }
}