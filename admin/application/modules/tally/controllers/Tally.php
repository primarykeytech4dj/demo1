<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tally extends MY_Controller {
  public $tallyServer;
  public $usagetype = '';
  function __construct() {
    parent::__construct();
    
    //check_user_login(FALSE);
    $this->usagetype = (NULL!==$this->input->get('tally'))?'tallyxml':'';
    $this->tallyServer = (NULL!==$this->input->get('tally'))?'':custom_constants::tally_server;//custom_constants::tally_server;
    /*$check = $this->get_tally_company();
    if(!$check){
        return FALSE;
    }*/
    //echo $this->tallyServer;exit;
    $this->load->model("tally/tally_model");
    $setup = $this->setup();
  }

  function setup(){
    $tbl = $this->tally_model->tbl_tally_ledger();
    return TRUE;
  }

  function admin_tally($xml=''){
     /*echo '<pre>';
      echo $xml;
      echo '</pre>';*/
      if($xml==''){
          $xml = '<ENVELOPE>
      <HEADER>
        <VERSION>1</VERSION>
        <TALLYREQUEST>EXPORT</TALLYREQUEST>
        <TYPE>COLLECTION</TYPE>
        <ID>Remote Ledger Coll</ID>
      </HEADER>
      <BODY>
        <DESC>
          <STATICVARIABLES>
            <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
          </STATICVARIABLES>
          <TDL>
            <TDLMESSAGE>
              <COLLECTION NAME="Remote Ledger Coll" ISINITIALIZE="Yes">
                  <TYPE>Ledger</TYPE>
                  <NATIVEMETHOD>Name</NATIVEMETHOD>
                  <NATIVEMETHOD>OpeningBalance</NATIVEMETHOD>
              </COLLECTION>   
            </TDLMESSAGE>
          </TDL>
        </DESC>
      </BODY>
    </ENVELOPE>';
      }
      /*exec("curl traceroute http://111.119.207.78:9002", $output, $return_var);
      echo $return_var;
      print_r($output);
      exit;*/
       /*exec('curl -X POST -d <ENVELOPE>
      <HEADER>
        <VERSION>1</VERSION>
        <TALLYREQUEST>EXPORT</TALLYREQUEST>
        <TYPE>COLLECTION</TYPE>
        <ID>Remote Ledger Coll</ID>
      </HEADER>
      <BODY>
        <DESC>
          <STATICVARIABLES>
            <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
          </STATICVARIABLES>
          <TDL>
            <TDLMESSAGE>
              <COLLECTION NAME="Remote Ledger Coll" ISINITIALIZE="Yes">
                  <TYPE>Ledger</TYPE>
                  <NATIVEMETHOD>Name</NATIVEMETHOD>
                  <NATIVEMETHOD>OpeningBalance</NATIVEMETHOD>
              </COLLECTION>   
            </TDLMESSAGE>
          </TDL>
        </DESC>
      </BODY>
    </ENVELOPE> http://111.119.207.78:9002/', $result, $return);
    echo $return;
       print_r($result);
      exit;*/
    //echo $xml;
    ini_set('display_errors', 1);
    $arr1 = array();
    $DSPDISPNAME = '';
    $BSSUBAMT = '';
    $BSMAINAMT = '';
    $error = [];
    try {
      $server = $this->tallyServer;
      //echo $server;
      $headers = array("Content-type: application/xml;charset=UTF-8", "Content-length: " . strlen($xml), "Connection: close");
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $server);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 100);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      $data = curl_exec($ch);
      //print_r($data);
      $data = str_replace('&#4;', '', $data);
      //print_r($data);
      //$res = simplexml_load_string($data);
      //print_r($res->BODY->DATA->COLLECTION);
      //exit;
      if (curl_errno($ch)) {
        $error =  curl_error($ch);
        print_r($error);
      } else {
        curl_close($ch);
        /*$res = simplexml_load_string($data);
        print_r($res);
        exit;*/
        $p = xml_parser_create();
        xml_parse_into_struct($p, $data, $vals, $index);
        xml_parser_free($p);
        // echo '<pre>';
        // print_r($vals);
        // exit;
        return ['data'=>$vals, 'status'=>1];
      }
    }
    catch(Exception $e) {
        print_r($e->getMessage());
      return ['status'=>0, 'error'=>$e->getMessage()];
    }
  }

  function ledgerMapping(){
    if($_SERVER['REQUEST_METHOD']=='POST'){
      //print_r($this->input->post());
      $this->form_validation->set_rules('ledgerName', 'Ledger Name', 'required|is_unique[tally_ledger.ledger_name]');
      $this->form_validation->set_rules('ledgerType', 'Account Type', 'required');
      $this->form_validation->set_rules('userId', 'first name', 'required');
      $this->form_validation->set_rules('is_active', 'first name', 'required');
      if($this->form_validation->run())
      {
        $tallyLedger['ledger_name'] = $this->input->post('ledgerName');
        $tallyLedger['account_type'] = $this->input->post('ledgerType');
        $tallyLedger['is_active'] = $this->input->post('is_active');
        $tallyLedger['created'] = $tallyLedger['modified'] = date('Y-m-d H:i:s');
        $tallyLedger['user_id'] = $this->input->post('userId');
        $userId = explode('-', $this->input->post('userId'));
        if(count($userId)>1){
          $tallyLedger['account_type'] = $userId[1];
          $tallyLedger['user_id'] = $userId[0];
        }
        $this->pktdblib->set_table('tally_ledger');
        $mapLedger = $this->pktdblib->_insert($tallyLedger);
        if($mapLedger['status']=='success'){
          if(!$this->input->is_ajax_request()){
            $msg = array('message'=>'Ledger Mapped Successfully', 'class'=>'alert alert-success');
            $this->session->set_flashdata('message',$msg);
            redirect('tally/ledgerMapping');
          }else{
            echo json_encode(['status'=>1, 'msg'=>'Ledger Mapped Successfully']);
            exit;
          }
        }else{
          if(!$this->input->is_ajax_request()){
            $msg = array('message'=>'Some Error Occurred', 'class'=>'alert alert-success');
            $this->session->set_flashdata('message',$msg);
            redirect('tally/ledgerMapping');
          }else{
            echo json_encode(['status'=>1, 'msg'=>'Ledger Mapped Successfully']);
            exit;
          }
        }
        
      }else{
        if(!$this->input->is_ajax_request()){
          $msg = array('message'=> 'validation Error Occured '.validation_errors(), 'class' => 'alert alert-danger');
          $this->session->set_flashdata('message', $msg);
        }else{
          echo json_encode(['status'=>0, 'msg'=>'validation Error Occured '.validation_errors()]);
          exit;
        }
      }
    }
    $data['meta_title'] = "Tally PHP";
    $data['meta_description'] = "Tally PHP Integration";
    $data['modules'][] = "tally";
    $data['methods'][] = "admin_add_form";
    /*echo '<pre>';
    print_r($data);exit;*/
    $data['js'][] = '<script type="text/javascript" src="'.assets_url().'admin_lte/js/core_tally.js"></script>';
    echo Modules::run("templates/admin_template", $data);
  }

  function admin_add_form(){
    $ledger = '<ENVELOPE>
      <HEADER>
        <VERSION>1</VERSION>
        <TALLYREQUEST>EXPORT</TALLYREQUEST>
        <TYPE>COLLECTION</TYPE>
        <ID>Remote Ledger Coll</ID>
      </HEADER>
      <BODY>
        <DESC>
          <STATICVARIABLES>
            <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
          </STATICVARIABLES>
          <TDL>
            <TDLMESSAGE>
              <COLLECTION NAME="Remote Ledger Coll" ISINITIALIZE="Yes">
                  <TYPE>Ledger</TYPE>
                  <NATIVEMETHOD>Name</NATIVEMETHOD>
                  <NATIVEMETHOD>OpeningBalance</NATIVEMETHOD>
              </COLLECTION>   
            </TDLMESSAGE>
          </TDL>
        </DESC>
      </BODY>
    </ENVELOPE>';

    $tallyDatas = $this->admin_tally($ledger);
    /*echo '<pre>';
    print_r($tallyDatas);exit;*/
    $ledgerNames = [];

    $this->pktdblib->set_table('tally_ledger');
    $ledger = $this->pktdblib->get('ledger_name');
    $data['ledgerAccounts'] = [];
    foreach ($ledger->result_array() as $key => $account) {
      $data['ledgerAccounts'][] = $account['ledger_name'];
    }
    if($tallyDatas['status']){
      //echo '<pre>';
      //print_r($tallyDatas['data']);exit;
      foreach ($tallyDatas['data'] as $key => $tally) {
        if(is_array($tally)){
          if(isset($tally['tag']) && strtolower($tally['tag'])=='ledger' && isset($tally["attributes"]["NAME"]) && !in_array($tally["attributes"]["NAME"], $data['ledgerAccounts'])){
            //print_r($tally);
            $ledgerNames[] = $tally["attributes"]["NAME"];
          }
        }
      }
    }else{
      if(!$this->input->is_ajax_request()){
          $msg = array('message'=> 'Failed to connect to Tally '.$tallyDatas['error'], 'class' => 'alert alert-danger');
          $this->session->set_flashdata('message', $msg);
        }else{
          echo json_encode(['status'=>0, 'msg'=>'Failed to connect to Tally '.$tallyDatas['error']]);
          exit;
        }
    }
    /*print_r($ledgerNames);
    echo '</pre>';

    exit;*/
    $data['ledgers'] = $ledgerNames;//$this->testledger();//
    $data['option']['ledgerTypes'] = $this->pktlib->meetingList();
    $data['option']['user_id'] = [0=>'Select User'];
    if(NULL!==$this->input->get('offset')){
      $data['offset'] = $this->input->get('offset');
    }else{
      $data['offset'] = 1;
    }

    $data['limit'] = 30;
    if (isset($_GET["page"])) { $data['page']  = $_GET["page"]; } else { $data['page']=1; };  
    $data['start_from'] = ($data['page']-1) * $data['limit'];
    $data['totalRecords'] = count($data['ledgers']);
    $data['totalPages'] = ceil($data['totalRecords']/$data['limit']);
    //echo "hii";exit;
    $this->load->view('tally/ledgerMapping', $data);
  }
  
  function auto_tally_mapping(){
    
    //echo '<pre>';
    //print_r($dt);
    $typeMapping = $this->usertype_mapping();


    $dt = $this->pktdblib->custom_query('Select * from `TABLE 53` order by parent asc');
    //-print_r($typeMapping);exit();
    $ledgers = [];
    foreach ($dt as $key => $ledger) {
      $ledgers[$typeMapping[$ledger['ledger_type']]][] = $ledger;
    }

    $this->pktdblib->set_table('tally_ledger');
    $query = $this->pktdblib->get('ledger_name');
    $mappedLedgers = [];
    foreach ($query->result_array() as $key => $tallyLedger) {
      $mappedLedgers[] = $tallyLedger['ledger_name'];
    };
    echo '<pre>';
    //print_r($mappedLedgers);exit;
    //exit;
    //echo '<pre>';print_r($ledgers['customers']);exit;
    if(isset($ledgers['customers'])){
      $column = [];
      $customers = [];
      $counter = 0;
      $addressCounter = 0;
      $tallyLedger = [];
      foreach ($ledgers['customers'] as $custKey => $customer) {
        
        if(!in_array($customer['ledger_name'], $mappedLedgers) && ($customer['gst_no']!='' && $customer['gst_no']!='0')){
          if($counter>500){
            break;
          }
          //print_r($customer);
          $tallyLedger[$custKey]['ledger_name'] = $customer['ledger_name'];
          $tallyLedger[$custKey]['created'] = $tallyLedger[$custKey]['modified'] = date('Y-m-d H:i:s');
          $tallyLedger[$custKey]['account_type'] = 'customers';
          $tallyLedger[$custKey]['is_active'] = true;
          if(!array_key_exists($customer['gst_no'], $customers)){
            $firstName = $customer['parent'];
            if(trim($customer['party_name'])!=''){
              $firstName = $customer['party_name'];
            }
            $customers[$customer['gst_no']]['party']['customer_category_id'] = 1;
            $customers[$customer['gst_no']]['party']['first_name'] = $firstName;
            $customers[$customer['gst_no']]['party']['contact_1'] = $customer['contact_1'];
            $customers[$customer['gst_no']]['party']['contact_2'] = $customer['contact_2'];
            $customers[$customer['gst_no']]['party']['joining_date'] = $customer['joining_date'];
            $customers[$customer['gst_no']]['party']['primary_email'] = (empty(trim($customer['email'])))?NULL:$customer['email'];
            $customers[$customer['gst_no']]['party']['secondary_email'] = $customer['email_cc'];
            $customers[$customer['gst_no']]['party']['pan_no'] = $customer['income_tax_number'];
            $customers[$customer['gst_no']]['party']['gst_no'] = $customer['gst_no'];
            $customers[$customer['gst_no']]['party']['adhaar_no'] = $customer['vat_no'];
            $customers[$customer['gst_no']]['party']['created'] = $customers[$customer['gst_no']]['party']['modified'] = date('Y-m-d H:i:s');
            $insCustomer = json_decode(Modules::run('customers/_register_admin_add', $customers[$customer['gst_no']]['party']), true);
            if($insCustomer['status']=='success'){
              $addressCounter = 0;
              $customers[$customer['gst_no']]['party']['id'] = $customers[$customer['gst_no']]['address'][$addressCounter]['user_id'] = $tallyLedger[$custKey]['user_id'] = $insCustomer['customers']['id'];
              if(!empty(trim($customer['address_1']))){
                $customers[$customer['gst_no']]['address'][$addressCounter]['address_1'] = $customer['address_1'];
                $customers[$customer['gst_no']]['address'][$addressCounter]['address_2'] = $customer['address_2'];
                $customers[$customer['gst_no']]['address'][$addressCounter]['state_id'] = $customer['state'];
                $customers[$customer['gst_no']]['address'][$addressCounter]['country_id'] = $customer['country'];
                $customers[$customer['gst_no']]['address'][$addressCounter]['site_name'] = $customer['ledger_name'];
                $customers[$customer['gst_no']]['address'][$addressCounter]['user_id'] = $insCustomer['customers']['id'];
                $customers[$customer['gst_no']]['address'][$addressCounter]['type'] = 'customers';
                $customers[$customer['gst_no']]['address'][$addressCounter]['created'] = $customers[$customer['gst_no']]['address'][$addressCounter]['modified'] = date('Y-m-d H:i:s');
              }
            }
          }else{
            $tallyLedger[$custKey]['user_id'] = $customers[$customer['gst_no']]['party']['id'];
            $tallyLedger[$custKey]['account_type'] = 'customers';
            $tallyLedger[$custKey]['is_active'] = FALSE;
            if(!empty(trim($customer['address_1']))){

              $addressCounter++;
              $customers[$customer['gst_no']]['address'][$addressCounter]['address_1'] = $customer['address_1'];
              $customers[$customer['gst_no']]['address'][$addressCounter]['address_2'] = $customer['address_2'];
              $customers[$customer['gst_no']]['address'][$addressCounter]['state_id'] = $customer['state'];
              $customers[$customer['gst_no']]['address'][$addressCounter]['country_id'] = $customer['country'];
              $customers[$customer['gst_no']]['address'][$addressCounter]['site_name'] = $customer['ledger_name'];
              $customers[$customer['gst_no']]['address'][$addressCounter]['user_id'] = $customers[$customer['gst_no']]['party']['id'];
              $customers[$customer['gst_no']]['address'][$addressCounter]['type'] = 'customers';
              $customers[$customer['gst_no']]['address'][$addressCounter]['created'] = $customers[$customer['gst_no']]['address'][$addressCounter]['modified'] = date('Y-m-d H:i:s');
            }
          }
          if(!empty($customers[$customer['gst_no']]['address'])){

            $this->pktdblib->set_table('address');
            $insAddress = $this->pktdblib->_insert_multiple($customers[$customer['gst_no']]['address']);
          }
          $counter++;
        }else{
          if(!in_array($customer['ledger_name'], $mappedLedgers)){
            $tallyLedger[$custKey]['user_id'] = 0;
            $tallyLedger[$custKey]['account_type'] = 'na';
            $tallyLedger[$custKey]['ledger_name'] = $customer['ledger_name'];
            $tallyLedger[$custKey]['created'] = $tallyLedger[$custKey]['modified'] = date('Y-m-d H:i:s');
          }
        }
      }

      if(!empty($tallyLedger)){

        $this->pktdblib->set_table('tally_ledger');
        $ledgerMapping = $this->pktdblib->_insert_multiple($tallyLedger);
      }
      echo 'Customer:';
      print_r($customers);
      //exit;
    }
    //exit;
    if(isset($ledgers['vendors'])){
      $vendors = [];
      //$counter = 0;
      $addressCounter = 0;
      $tallyLedger = [];
      //echo $counter;
      foreach ($ledgers['vendors'] as $vendorKey => $vendor) {
        
        if(!in_array($vendor['ledger_name'], $mappedLedgers) && ($vendor['gst_no']!='' && $vendor['gst_no']!='0')){
          if($counter>500){
            break;
          }
          //print_r($vendor);
          $tallyLedger[$vendorKey]['ledger_name'] = $vendor['ledger_name'];
          $tallyLedger[$vendorKey]['created'] = $tallyLedger[$vendorKey]['modified'] = date('Y-m-d H:i:s');
          $tallyLedger[$vendorKey]['account_type'] = 'vendors';
          $tallyLedger[$vendorKey]['is_active'] = true;
          if(!array_key_exists($vendor['gst_no'], $vendors)){
            $firstName = $vendor['parent'];
            if(trim($vendor['party_name'])!=''){
              $firstName = $vendor['party_name'];
            }
            $vendors[$vendor['gst_no']]['party']['vendor_category_id'] = 1;
            $vendors[$vendor['gst_no']]['party']['first_name'] = $firstName;
            $vendors[$vendor['gst_no']]['party']['contact_1'] = $vendor['contact_1'];
            $vendors[$vendor['gst_no']]['party']['contact_2'] = $vendor['contact_2'];
            $vendors[$vendor['gst_no']]['party']['joining_date'] = $vendor['joining_date'];
            $vendors[$vendor['gst_no']]['party']['primary_email'] = (empty(trim($vendor['email'])))?NULL:$vendor['email'];
            $vendors[$vendor['gst_no']]['party']['secondary_email'] = $vendor['email_cc'];
            $vendors[$vendor['gst_no']]['party']['pan_no'] = $vendor['income_tax_number'];
            $vendors[$vendor['gst_no']]['party']['gst_no'] = $vendor['gst_no'];
            $vendors[$vendor['gst_no']]['party']['adhaar_no'] = $vendor['vat_no'];
            $vendors[$vendor['gst_no']]['party']['created'] = $vendors[$vendor['gst_no']]['party']['modified'] = date('Y-m-d H:i:s');
            $insvendor = json_decode(Modules::run('vendors/_register_admin_add', $vendors[$vendor['gst_no']]['party']), true);
            if($insvendor['status']=='success'){
              $addressCounter = 0;
              $vendors[$vendor['gst_no']]['party']['id'] = $vendors[$vendor['gst_no']]['address'][$addressCounter]['user_id'] = $tallyLedger[$vendorKey]['user_id'] = $insvendor['vendors']['id'];
              if(!empty(trim($vendor['address_1']))){
                $vendors[$vendor['gst_no']]['address'][$addressCounter]['address_1'] = $vendor['address_1'];
                $vendors[$vendor['gst_no']]['address'][$addressCounter]['address_2'] = $vendor['address_2'];
                $vendors[$vendor['gst_no']]['address'][$addressCounter]['state_id'] = $vendor['state'];
                $vendors[$vendor['gst_no']]['address'][$addressCounter]['country_id'] = $vendor['country'];
                $vendors[$vendor['gst_no']]['address'][$addressCounter]['site_name'] = $vendor['ledger_name'];
                $vendors[$vendor['gst_no']]['address'][$addressCounter]['user_id'] = $insvendor['vendors']['id'];
                $vendors[$vendor['gst_no']]['address'][$addressCounter]['type'] = 'vendors';
                $vendors[$vendor['gst_no']]['address'][$addressCounter]['created'] = $vendors[$vendor['gst_no']]['address'][$addressCounter]['modified'] = date('Y-m-d H:i:s');
              }
            }
          }else{
            $tallyLedger[$vendorKey]['user_id'] = $vendors[$vendor['gst_no']]['party']['id'];
            $tallyLedger[$vendorKey]['account_type'] = 'vendors';
            $tallyLedger[$vendorKey]['is_active'] = false;
            if(!empty(trim($vendor['address_1']))){

              $addressCounter++;
              $vendors[$vendor['gst_no']]['address'][$addressCounter]['address_1'] = $vendor['address_1'];
              $vendors[$vendor['gst_no']]['address'][$addressCounter]['address_2'] = $vendor['address_2'];
              $vendors[$vendor['gst_no']]['address'][$addressCounter]['state_id'] = $vendor['state'];
              $vendors[$vendor['gst_no']]['address'][$addressCounter]['country_id'] = $vendor['country'];
              $vendors[$vendor['gst_no']]['address'][$addressCounter]['site_name'] = $vendor['ledger_name'];
              $vendors[$vendor['gst_no']]['address'][$addressCounter]['user_id'] = $vendors[$vendor['gst_no']]['party']['id'];
              $vendors[$vendor['gst_no']]['address'][$addressCounter]['type'] = 'vendors';
              $vendors[$vendor['gst_no']]['address'][$addressCounter]['created'] = $vendors[$vendor['gst_no']]['address'][$addressCounter]['modified'] = date('Y-m-d H:i:s');
            }
          }
          if(!empty($vendors[$vendor['gst_no']]['address'])){

            $this->pktdblib->set_table('address');
            $insAddress = $this->pktdblib->_insert_multiple($vendors[$vendor['gst_no']]['address']);
          }
          $counter++;
        }else{
          if(!in_array($vendor['ledger_name'], $mappedLedgers)){
            $tallyLedger[$vendorKey]['ledger_name'] = $vendor['ledger_name'];
            $tallyLedger[$vendorKey]['created'] = $tallyLedger[$vendorKey]['modified'] = date('Y-m-d H:i:s');
            $tallyLedger[$vendorKey]['account_type'] = 'na';
            $tallyLedger[$vendorKey]['is_active'] = false;
          }
        }
      }

      if(!empty($tallyLedger)){
        $tallyLedger = array_values($tallyLedger);
        $this->pktdblib->set_table('tally_ledger');
        $ledgerMapping = $this->pktdblib->_insert_multiple($tallyLedger);
      }
      echo "Vendors: ";
      print_r($vendors);//exit;
    }

    if(isset($ledgers['brokers'])){
      $brokers = [];
      //$counter = 0;
      $addressCounter = 0;
      $tallyLedger = [];
      //echo $counter;
      foreach ($ledgers['brokers'] as $brokerKey => $broker) {
        
        if(!in_array($broker['ledger_name'], $mappedLedgers) && ($broker['income_tax_number']!='' && $broker['income_tax_number']!='0')){
          if($counter>500){
            break;
          }
          //print_r($broker);
          $tallyLedger[$brokerKey]['ledger_name'] = $broker['ledger_name'];
          $tallyLedger[$brokerKey]['created'] = $tallyLedger[$brokerKey]['modified'] = date('Y-m-d H:i:s');
          $tallyLedger[$brokerKey]['account_type'] = 'brokers';
          $tallyLedger[$brokerKey]['is_active'] = true;
          if(!array_key_exists($broker['income_tax_number'], $brokers)){
            $firstName = $broker['parent'];
            if(trim($broker['party_name'])!=''){
              $firstName = $broker['party_name'];
            }
            $brokers[$broker['income_tax_number']]['party']['broker_category_id'] = 1;
            $brokers[$broker['income_tax_number']]['party']['first_name'] = $firstName;
            $brokers[$broker['income_tax_number']]['party']['contact_1'] = $broker['contact_1'];
            $brokers[$broker['income_tax_number']]['party']['contact_2'] = $broker['contact_2'];
            $brokers[$broker['income_tax_number']]['party']['joining_date'] = $broker['joining_date'];
            $brokers[$broker['income_tax_number']]['party']['primary_email'] = (empty(trim($broker['email'])))?NULL:$broker['email'];
            $brokers[$broker['income_tax_number']]['party']['secondary_email'] = $broker['email_cc'];
            $brokers[$broker['income_tax_number']]['party']['pan_no'] = $broker['income_tax_number'];
            $brokers[$broker['income_tax_number']]['party']['gst_no'] = $broker['income_tax_number'];
            $brokers[$broker['income_tax_number']]['party']['adhaar_no'] = $broker['vat_no'];
            $brokers[$broker['income_tax_number']]['party']['created'] = $brokers[$broker['income_tax_number']]['party']['modified'] = date('Y-m-d H:i:s');
            $insbroker = json_decode(Modules::run('brokers/_register_admin_add', $brokers[$broker['income_tax_number']]['party']), true);
            if($insbroker['status']=='success'){
              $addressCounter = 0;
              $brokers[$broker['income_tax_number']]['party']['id'] = $brokers[$broker['income_tax_number']]['address'][$addressCounter]['user_id'] = $tallyLedger[$brokerKey]['user_id'] = $insbroker['brokers']['id'];
              if(!empty(trim($broker['address_1']))){
                $brokers[$broker['income_tax_number']]['address'][$addressCounter]['address_1'] = $broker['address_1'];
                $brokers[$broker['income_tax_number']]['address'][$addressCounter]['address_2'] = $broker['address_2'];
                $brokers[$broker['income_tax_number']]['address'][$addressCounter]['state_id'] = $broker['state'];
                $brokers[$broker['income_tax_number']]['address'][$addressCounter]['country_id'] = $broker['country'];
                $brokers[$broker['income_tax_number']]['address'][$addressCounter]['site_name'] = $broker['ledger_name'];
                $brokers[$broker['income_tax_number']]['address'][$addressCounter]['user_id'] = $insbroker['brokers']['id'];
                $brokers[$broker['income_tax_number']]['address'][$addressCounter]['type'] = 'brokers';
                $brokers[$broker['income_tax_number']]['address'][$addressCounter]['created'] = $brokers[$broker['income_tax_number']]['address'][$addressCounter]['modified'] = date('Y-m-d H:i:s');
              }
            }
          }else{
            $tallyLedger[$brokerKey]['user_id'] = $brokers[$broker['income_tax_number']]['party']['id'];
            $tallyLedger[$brokerKey]['account_type'] = 'brokers';
            $tallyLedger[$brokerKey]['is_active'] = false;
            if(!empty(trim($broker['address_1']))){

              $addressCounter++;
              $brokers[$broker['income_tax_number']]['address'][$addressCounter]['address_1'] = $broker['address_1'];
              $brokers[$broker['income_tax_number']]['address'][$addressCounter]['address_2'] = $broker['address_2'];
              $brokers[$broker['income_tax_number']]['address'][$addressCounter]['state_id'] = $broker['state'];
              $brokers[$broker['income_tax_number']]['address'][$addressCounter]['country_id'] = $broker['country'];
              $brokers[$broker['income_tax_number']]['address'][$addressCounter]['site_name'] = $broker['ledger_name'];
              $brokers[$broker['income_tax_number']]['address'][$addressCounter]['user_id'] = $brokers[$broker['income_tax_number']]['party']['id'];
              $brokers[$broker['income_tax_number']]['address'][$addressCounter]['type'] = 'brokers';
              $brokers[$broker['income_tax_number']]['address'][$addressCounter]['created'] = $brokers[$broker['income_tax_number']]['address'][$addressCounter]['modified'] = date('Y-m-d H:i:s');
            }
          }
          if(!empty($brokers[$broker['income_tax_number']]['address'])){

            $this->pktdblib->set_table('address');
            $insAddress = $this->pktdblib->_insert_multiple($brokers[$broker['income_tax_number']]['address']);
          }
          $counter++;
        }else{
          if(!in_array($broker['ledger_name'], $mappedLedgers)){

            $tallyLedger[$brokerKey]['ledger_name'] = $broker['ledger_name'];
            $tallyLedger[$brokerKey]['created'] = $tallyLedger[$brokerKey]['modified'] = date('Y-m-d H:i:s');
            $tallyLedger[$brokerKey]['account_type'] = 'na';
            $tallyLedger[$brokerKey]['is_active'] = false;
          }
        }
      }

      if(!empty($tallyLedger)){
        $tallyLedger = array_values($tallyLedger);
        $this->pktdblib->set_table('tally_ledger');
        $ledgerMapping = $this->pktdblib->_insert_multiple($tallyLedger);
      }
      echo "Brokers: ";
      print_r($brokers);//exit;
    }

    if(isset($ledgers['employees'])){
      //print_r($ledgers['employees']);
    }

    //print_r($ledgers['na']);
    if(isset($ledgers['na'])){
      //print_r($ledgers['na']);
      $tallyLedger = [];
      foreach ($ledgers['na'] as $naKey => $na) {
        if($counter>500){
          break;
        }
        if(!in_array($customer['ledger_name'], $mappedLedgers)){
          $tallyLedger[$naKey]['ledger_name'] = $na['ledger_name'];
          $tallyLedger[$naKey]['account_type'] = 'na';
          $tallyLedger[$naKey]['user_id'] = 0;
          $tallyLedger[$naKey]['is_active'] = 0;
          $tallyLedger[$naKey]['created'] = $tallyLedger[$naKey]['modified'] = date('Y-m-d H:i:s');
          $counter++;
        }
      }
      //print_r($tallyLedger);
      if(!empty($tallyLedger)){
        $this->pktdblib->set_table('tally_ledger');
        $ledgerMapping = $this->pktdblib->_insert_multiple($tallyLedger);
        echo "NA:";
        print_r($tallyLedger);
      }
    } 
  }

  function so(){
    if($_SERVER['REQUEST_METHOD']=='POST'){
      //print_r($this->input->post());
      $this->form_validation->set_rules('ledgerName', 'Ledger Name', 'required|is_unique[tally_ledger.ledger_name]');
      $this->form_validation->set_rules('ledgerType', 'Account Type', 'required');
      $this->form_validation->set_rules('userId', 'first name', 'required');
      $this->form_validation->set_rules('is_active', 'first name', 'required');
      if($this->form_validation->run())
      {
        $tallyLedger['ledger_name'] = $this->input->post('ledgerName');
        $tallyLedger['account_type'] = $this->input->post('ledgerType');
        $tallyLedger['is_active'] = $this->input->post('is_active');
        $tallyLedger['created'] = $tallyLedger['modified'] = date('Y-m-d H:i:s');
        $tallyLedger['user_id'] = $this->input->post('userId');
        $userId = explode('-', $this->input->post('userId'));
        if(count($userId)>1){
          $tallyLedger['account_type'] = $userId[1];
          $tallyLedger['user_id'] = $userId[0];
        }
        $this->pktdblib->set_table('tally_ledger');
        $mapLedger = $this->pktdblib->_insert($tallyLedger);
        if($mapLedger['status']=='success'){
          if(!$this->input->is_ajax_request()){
            $msg = array('message'=>'Ledger Mapped Successfully', 'class'=>'alert alert-success');
            $this->session->set_flashdata('message',$msg);
            redirect('tally/ledgerMapping');
          }else{
            echo json_encode(['status'=>1, 'msg'=>'Ledger Mapped Successfully']);
            exit;
          }
        }else{
          if(!$this->input->is_ajax_request()){
            $msg = array('message'=>'Some Error Occurred', 'class'=>'alert alert-success');
            $this->session->set_flashdata('message',$msg);
            redirect('tally/ledgerMapping');
          }else{
            echo json_encode(['status'=>1, 'msg'=>'Ledger Mapped Successfully']);
            exit;
          }
        } 
      }else{
        if(!$this->input->is_ajax_request()){
          $msg = array('message'=> 'validation Error Occured '.validation_errors(), 'class' => 'alert alert-danger');
          $this->session->set_flashdata('message', $msg);
        }else{
          echo json_encode(['status'=>0, 'msg'=>'validation Error Occured '.validation_errors()]);
          exit;
        }
      }
    }
    $data['meta_title'] = "Tally PHP";
    $data['meta_description'] = "Tally PHP Integration";
    $data['modules'][] = "tally";
    $data['methods'][] = "admin_add_form";
    /*echo '<pre>';
    print_r($data);exit;*/
    $data['js'][] = '<script type="text/javascript" src="'.assets_url().'admin_lte/js/core_tally.js"></script>';
    echo Modules::run("templates/admin_template", $data);
  }

  function admin_sales_order(){
    /*$xml = '<ENVELOPE><HEADER><VERSION>1</VERSION><TALLYREQUEST>Export</TALLYREQUEST><TYPE>Data</TYPE><ID>Sales Orders Book</ID></HEADER><BODY><DESC><STATICVARIABLES><EXPLODEFLAG>Yes</EXPLODEFLAG><SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT><SVFROMDATE>01/04/2018</SVFROMDATE><SVTODATE>01/04/2018</SVTODATE></STATICVARIABLES><TDL><TDLMESSAGE><REPORT NAME="Sales Orders Book"></REPORT></TDLMESSAGE></TDL></DESC></BODY></ENVELOPE>';*/

    $xml = '<ENVELOPE>
    <HEADER>
    <VERSION>1</VERSION>
    <TALLYREQUEST>EXPORT</TALLYREQUEST>
    <TYPE>COLLECTION</TYPE>
    <ID>Vouchers</ID> 
    </HEADER>
    <BODY>
    <DESC>
    <STATICVARIABLES>
    <VoucherTypeName>Vouchers</VoucherTypeName>
    <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
    <SVFROMDATE TYPE="Date">20200204</SVFROMDATE>
    <SVTODATE TYPE="Date">20200204</SVTODATE>
    </STATICVARIABLES>   
    </DESC>
    </BODY>
    </ENVELOPE>';
    /*$xml = '<ENVELOPE>
    <HEADER>
    <VERSION>1</VERSION>
    <TALLYREQUEST>EXPORT</TALLYREQUEST>
    <TYPE>COLLECTION</TYPE>
    <ID>COL_SALES_VOUCHERS</ID> 
    </HEADER>
    <BODY>
    <DESC>
    <STATICVARIABLES>
    <VoucherTypeName>Sales</VoucherTypeName>
    <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
    <SVFROMDATE TYPE="Date">20180401</SVFROMDATE>
    <SVTODATE TYPE="Date">20180401</SVTODATE>
    </STATICVARIABLES>   
      <TDL> 
        <TDLMESSAGE> 
          <COLLECTION NAME="COL_SALES_VOUCHERS" ISINITIALIZE="Yes"> 
              <TYPE>Voucher</TYPE> 
          </COLLECTION> 
        </TDLMESSAGE> 
      </TDL>     
    </DESC>
    </BODY>
    </ENVELOPE>';*/
    $tallyDatas = $this->admin_tally($xml);
    echo '<pre>';
    print_r($tallyDatas);exit;
    $ledgerNames = [];

    $this->pktdblib->set_table('tally_ledger');
    $ledger = $this->pktdblib->get('ledger_name');
    $data['ledgerAccounts'] = [];
    foreach ($ledger->result_array() as $key => $account) {
      $data['ledgerAccounts'][] = $account['ledger_name'];
    }
    if($tallyDatas['status']){
      //echo '<pre>';
      //print_r($tallyDatas['data']);exit;
      foreach ($tallyDatas['data'] as $key => $tally) {
        if(is_array($tally)){
          if(isset($tally['tag']) && strtolower($tally['tag'])=='ledger' && isset($tally["attributes"]["NAME"]) && !in_array($tally["attributes"]["NAME"], $data['ledgerAccounts'])){
            //print_r($tally);
            $ledgerNames[] = $tally["attributes"]["NAME"];
          }
        }
      }
    }else{
      if(!$this->input->is_ajax_request()){
          $msg = array('message'=> 'Failed to connect to Tally '.$tallyDatas['error'], 'class' => 'alert alert-danger');
          $this->session->set_flashdata('message', $msg);
        }else{
          echo json_encode(['status'=>0, 'msg'=>'Failed to connect to Tally '.$tallyDatas['error']]);
          exit;
        }
    }
    /*print_r($ledgerNames);
    echo '</pre>';

    exit;*/
    $data['ledgers'] = $ledgerNames;//$this->testledger();//
    $data['option']['ledgerTypes'] = $this->pktlib->meetingList();
    $data['option']['user_id'] = [0=>'Select User'];
    if(NULL!==$this->input->get('offset')){
      $data['offset'] = $this->input->get('offset');
    }else{
      $data['offset'] = 1;
    }

    $data['limit'] = 30;
    if (isset($_GET["page"])) { $data['page']  = $_GET["page"]; } else { $data['page']=1; };  
    $data['start_from'] = ($data['page']-1) * $data['limit'];
    $data['totalRecords'] = count($data['ledgers']);
    $data['totalPages'] = ceil($data['totalRecords']/$data['limit']);
    $this->load->view('tally/ledgerMapping', $data);
  }

  function list_of_company(){
    $strRequestXML = "<ENVELOPE><HEADER><TALLYREQUEST>Export Data</TALLYREQUEST>  </HEADER>  <BODY><EXPORTDATA><REQUESTDESC><REPORTNAME>List of Companies</REPORTNAME><STATICVARIABLES><SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT></STATICVARIABLES></REQUESTDESC></EXPORTDATA></BODY></ENVELOPE>";
    $tallyData = $this->admin_tally($strRequestXML);
    echo '<pre>';
    print_r($tallyData);
  }

  function tally_to_customer()
    {
        ini_set('display_errors', 1);
        ini_set("memory_limit","500M");
        $data = '<ENVELOPE>
 <HEADER>
  <TALLYREQUEST>Import Data</TALLYREQUEST>
 </HEADER>
 <BODY>
  <IMPORTDATA>
   <REQUESTDESC>
    <REPORTNAME>All Masters</REPORTNAME>
    <STATICVARIABLES>
     <SVCURRENTCOMPANY>'.custom_constants::tally_current_company.'</SVCURRENTCOMPANY>
    </STATICVARIABLES>
   </REQUESTDESC>
   <REQUESTDATA>
<TALLYMESSAGE xmlns:UDF="TallyUDF">
     <LEDGER NAME="Primary key technologies C - test2" RESERVEDNAME="">
      <ADDRESS.LIST TYPE="String">
       <ADDRESS>Shop no F3, Vini heights chs,</ADDRESS>
       <ADDRESS>Nalla sopara (west),</ADDRESS>
      </ADDRESS.LIST>
      <MAILINGNAME.LIST TYPE="String">
       <MAILINGNAME>Deepak Jha</MAILINGNAME>
      </MAILINGNAME.LIST>
      <OLDAUDITENTRYIDS.LIST TYPE="Number">
       <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
      </OLDAUDITENTRYIDS.LIST>
      <STARTINGFROM>20190330</STARTINGFROM>
      <STREGDATE/>
      <LBTREGNDATE/>
      <SAMPLINGDATEONEFACTOR/>
      <SAMPLINGDATETWOFACTOR/>
      <ACTIVEFROM/>
      <ACTIVETO/>
      <VATAPPLICABLEDATE/>
      <EXCISEREGISTRATIONDATE/>
      <PANAPPLICABLEFROM/>
      <PAYINSRUNNINGFILEDATE/>
      <VATTAXEXEMPTIONDATE/>
      <GUID>AJS/CL/000001</GUID>
      <CURRENCYNAME></CURRENCYNAME>
      <EMAIL>primarykeytech@gmail.com</EMAIL>
      <STATENAME/>
      <PINCODE>401203</PINCODE>
      <WEBSITE/>
      <INCOMETAXNUMBER>ANTPJ9973D</INCOMETAXNUMBER>
      <SALESTAXNUMBER/>
      <INTERSTATESTNUMBER></INTERSTATESTNUMBER>
      <VATTINNUMBER></VATTINNUMBER>
      <COUNTRYNAME>India</COUNTRYNAME>
      <EXCISERANGE/>
      <EXCISEDIVISION/>
      <EXCISECOMMISSIONERATE/>
      <LBTREGNNO/>
      <LBTZONE/>
      <EXPORTIMPORTCODE/>
      <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
      <VATDEALERTYPE/>
      <PRICELEVEL/>
      <UPLOADLASTREFRESH/>
      <PARENT>Sundry Debtors</PARENT>
      <SAMPLINGMETHOD/>
      <SAMPLINGSTRONEFACTOR/>
      <IFSCODE/>
      <REQUESTORRULE/>
      <GRPDEBITPARENT/>
      <GRPCREDITPARENT/>
      <SYSDEBITPARENT/>
      <SYSCREDITPARENT/>
      <TDSAPPLICABLE/>
      <TCSAPPLICABLE/>
      <GSTAPPLICABLE/>
      <CREATEDBY>AJS</CREATEDBY>
      <ALTEREDBY>AJS</ALTEREDBY>
      <TAXCLASSIFICATIONNAME/>
      <TAXTYPE>Others</TAXTYPE>
      <BILLCREDITPERIOD/>
      <BANKDETAILS/>
      <BANKBRANCHNAME/>
      <BANKBSRCODE/>
      <DEDUCTEETYPE/>
      <BUSINESSTYPE/>
      <TYPEOFNOTIFICATION/>
      <MSMEREGNUMBER/>
      <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
      <RELATEDPARTYID/>
      <RELPARTYISSUINGAUTHORITY/>
      <IMPORTEREXPORTERCODE/>
      <EMAILCC/>
      <DESCRIPTION/>
      <LEDADDLALLOCTYPE/>
      <TRANSPORTERID/>
      <LEDGERPHONE/>
      <LEDGERFAX/>
      <LEDGERCONTACT>Primary key technologies C - test2 C - test</LEDGERCONTACT>
      <LEDGERMOBILE>9920758818</LEDGERMOBILE>
      <RELATIONTYPE/>
      <MAILINGNAMENATIVE/>
      <STATENAMENATIVE/>
      <COUNTRYNAMENATIVE/>
      <BASICTYPEOFDUTY/>
      <GSTTYPE/>
      <EXEMPTIONTYPE/>
      <APPROPRIATEFOR/>
      <SUBTAXTYPE/>
      <STXNATUREOFPARTY/>
      <NAMEONPAN/>
      <USEDFORTAXTYPE/>
      <ECOMMMERCHANTID/>
      <PARTYGSTIN>27ANTPJ9973D1ZA</PARTYGSTIN>
      <GSTDUTYHEAD/>
      <GSTAPPROPRIATETO/>
      <GSTTYPEOFSUPPLY/>
      <GSTNATUREOFSUPPLY/>
      <CESSVALUATIONMETHOD/>
      <PAYTYPE/>
      <PAYSLIPNAME/>
      <ATTENDANCETYPE/>
      <LEAVETYPE/>
      <CALCULATIONPERIOD/>
      <ROUNDINGMETHOD/>
      <COMPUTATIONTYPE/>
      <CALCULATIONTYPE/>
      <PAYSTATTYPE/>
      <PROFESSIONALTAXNUMBER/>
      <USERDEFINEDCALENDERTYPE/>
      <ITNATURE/>
      <ITCOMPONENT/>
      <NOTIFICATIONNUMBER/>
      <REGISTRATIONNUMBER/>
      <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
      <ABATEMENTNOTIFICATIONNO/>
      <STXDUTYHEAD/>
      <STXCLASSIFICATION/>
      <NOTIFICATIONSLNO/>
      <SERVICETAXAPPLICABLE/>
      <EXCISELEDGERCLASSIFICATION/>
      <EXCISEREGISTRATIONNUMBER/>
      <EXCISEACCOUNTHEAD/>
      <EXCISEDUTYTYPE/>
      <EXCISEDUTYHEADCODE/>
      <EXCISEALLOCTYPE/>
      <EXCISEDUTYHEAD/>
      <NATUREOFSALES/>
      <EXCISENOTIFICATIONNO/>
      <EXCISEIMPORTSREGISTARTIONNO/>
      <EXCISEAPPLICABILITY/>
      <EXCISETYPEOFBRANCH/>
      <EXCISEDEFAULTREMOVAL/>
      <EXCISENOTIFICATIONSLNO/>
      <TYPEOFTARIFF/>
      <EXCISEREGNO/>
      <EXCISENATUREOFPURCHASE/>
      <TDSDEDUCTEETYPEMST/>
      <TDSRATENAME/>
      <TDSDEDUCTEESECTIONNUMBER/>
      <PANSTATUS/>
      <DEDUCTEEREFERENCE/>
      <TDSDEDUCTEETYPE/>
      <ITEXEMPTAPPLICABLE/>
      <TAXIDENTIFICATIONNO/>
      <LEDGERFBTCATEGORY/>
      <BRANCHCODE/>
      <CLIENTCODE/>
      <BANKINGCONFIGBANK/>
      <BANKINGCONFIGBANKID/>
      <BANKACCHOLDERNAME/>
      <USEFORPOSTYPE/>
      <PAYMENTGATEWAY/>
      <TYPEOFINTERESTON>Voucher Date</TYPEOFINTERESTON>
      <BANKCONFIGIFSC/>
      <BANKCONFIGMICR/>
      <BANKCONFIGSHORTCODE/>
      <PYMTINSTOUTPUTNAME/>
      <PRODUCTCODETYPE/>
      <SALARYPYMTPRODUCTCODE/>
      <OTHERPYMTPRODUCTCODE/>
      <PAYMENTINSTLOCATION/>
      <ENCRPTIONLOCATION/>
      <NEWIMFLOCATION/>
      <IMPORTEDIMFLOCATION/>
      <BANKNEWSTATEMENTS/>
      <BANKIMPORTEDSTATEMENTS/>
      <BANKMICR/>
      <CORPORATEUSERNOECS/>
      <CORPORATEUSERNOACH/>
      <CORPORATEUSERNAME/>
      <IMFNAME/>
      <PAYINSBATCHNAME/>
      <LASTUSEDBATCHNAME/>
      <PAYINSFILENUMPERIOD/>
      <ENCRYPTEDBY/>
      <ENCRYPTEDID/>
      <ISOCURRENCYCODE/>
      <BANKCAPSULEID/>
      <SALESTAXCESSAPPLICABLE/>
      <VATTAXEXEMPTIONNATURE/>
      <VATTAXEXEMPTIONNUMBER/>
      <LEDSTATENAME>Maharashtra</LEDSTATENAME>
      <VATAPPLICABLE/>
      <PARTYBUSINESSTYPE/>
      <PARTYBUSINESSSTYLE/>
      <ISBILLWISEON>Yes</ISBILLWISEON>
      <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
      <ISINTERESTON>Yes</ISINTERESTON>
      <ALLOWINMOBILE>No</ALLOWINMOBILE>
      <ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>
      <ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>
      <ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>
      <ASORIGINAL>Yes</ASORIGINAL>
      <ISCONDENSED>No</ISCONDENSED>
      <AFFECTSSTOCK>No</AFFECTSSTOCK>
      <ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>
      <FORPAYROLL>No</FORPAYROLL>
      <ISABCENABLED>No</ISABCENABLED>
      <ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>
      <INTERESTONBILLWISE>Yes</INTERESTONBILLWISE>
      <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
      <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
      <USEFORVAT>No</USEFORVAT>
      <IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>
      <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
      <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
      <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
      <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
      <ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>
      <ISTDSEXPENSE>No</ISTDSEXPENSE>
      <ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>
      <ISRELATEDPARTY>No</ISRELATEDPARTY>
      <USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>
      <ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>
      <APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>
      <ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>
      <INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>
      <INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>
      <ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>
      <OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>
      <ISAGAINSTFORMC>No</ISAGAINSTFORMC>
      <ISCHEQUEPRINTINGENABLED>No</ISCHEQUEPRINTINGENABLED>
      <ISPAYUPLOAD>No</ISPAYUPLOAD>
      <ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>
      <ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>
      <ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>
      <CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>
      <ISTRANSPORTER>No</ISTRANSPORTER>
      <USEFORNOTIONALITC>No</USEFORNOTIONALITC>
      <ISECOMMOPERATOR>No</ISECOMMOPERATOR>
      <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
      <USEFORGRATUITY>No</USEFORGRATUITY>
      <ISTDSPROJECTED>No</ISTDSPROJECTED>
      <FORSERVICETAX>No</FORSERVICETAX>
      <ISINPUTCREDIT>No</ISINPUTCREDIT>
      <ISEXEMPTED>No</ISEXEMPTED>
      <ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>
      <ISSTXPARTY>No</ISSTXPARTY>
      <ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>
      <ISUSEDFORCVD>No</ISUSEDFORCVD>
      <LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>
      <ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>
      <ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>
      <ISSEZPARTY>No</ISSEZPARTY>
      <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
      <ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>
      <ISEDDSUPPORTED>No</ISEDDSUPPORTED>
      <HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>
      <HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>
      <HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>
      <HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>
      <HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>
      <HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>
      <HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>
      <HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>
      <HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>
      <HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>
      <ISEBANKINGENABLED>No</ISEBANKINGENABLED>
      <ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>
      <ISBATCHENABLED>No</ISBATCHENABLED>
      <ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>
      <HASEDDCITY>No</HASEDDCITY>
      <HASECHEQUECITY>No</HASECHEQUECITY>
      <ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>
      <HASCLIENTCODE>No</HASCLIENTCODE>
      <PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>
      <PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>
      <ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>
      <ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>
      <ISSCBUAE>No</ISSCBUAE>
      <ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>
      <ISSALARYGROUPED>No</ISSALARYGROUPED>
      <USEFORPURCHASETAX>No</USEFORPURCHASETAX>
      <AUDITED>No</AUDITED>
      <SAMPLINGNUMONEFACTOR>0</SAMPLINGNUMONEFACTOR>
      <SAMPLINGNUMTWOFACTOR>0</SAMPLINGNUMTWOFACTOR>
      <SORTPOSITION> 1000</SORTPOSITION>
      <ALTERID> </ALTERID>
      <DEFAULTLANGUAGE>0</DEFAULTLANGUAGE>
      <RATEOFTAXCALCULATION>0</RATEOFTAXCALCULATION>
      <GRATUITYMONTHDAYS>0</GRATUITYMONTHDAYS>
      <GRATUITYLIMITMONTHS>0</GRATUITYLIMITMONTHS>
      <CALCULATIONBASIS>0</CALCULATIONBASIS>
      <ROUNDINGLIMIT>0</ROUNDINGLIMIT>
      <ABATEMENTPERCENTAGE>0</ABATEMENTPERCENTAGE>
      <TDSDEDUCTEESPECIALRATE>0</TDSDEDUCTEESPECIALRATE>
      <BENEFICIARYCODEMAXLENGTH>0</BENEFICIARYCODEMAXLENGTH>
      <ECHEQUEPRINTLOCATIONVERSION>0</ECHEQUEPRINTLOCATIONVERSION>
      <ECHEQUEPAYABLELOCATIONVERSION>0</ECHEQUEPAYABLELOCATIONVERSION>
      <EDDPRINTLOCATIONVERSION>0</EDDPRINTLOCATIONVERSION>
      <EDDPAYABLELOCATIONVERSION>0</EDDPAYABLELOCATIONVERSION>
      <PAYINSRUNNINGFILENUM>0</PAYINSRUNNINGFILENUM>
      <TRANSACTIONTYPEVERSION>0</TRANSACTIONTYPEVERSION>
      <PAYINSFILENUMLENGTH>0</PAYINSFILENUMLENGTH>
      <SAMPLINGAMTONEFACTOR/>
      <SAMPLINGAMTTWOFACTOR/>
      <CREDITLIMIT/>
      <GRATUITYLIMITAMOUNT/>
      <ODLIMIT/>
      <TEMPGSTCGSTRATE>0</TEMPGSTCGSTRATE>
      <TEMPGSTSGSTRATE>0</TEMPGSTSGSTRATE>
      <TEMPGSTIGSTRATE>0</TEMPGSTIGSTRATE>
      <TEMPISVATFIELDSEDITED/>
      <TEMPAPPLDATE/>
      <TEMPCLASSIFICATION/>
      <TEMPNATURE/>
      <TEMPPARTYENTITY/>
      <TEMPBUSINESSNATURE/>
      <TEMPVATRATE>0</TEMPVATRATE>
      <TEMPADDLTAX>0</TEMPADDLTAX>
      <TEMPCESSONVAT>0</TEMPCESSONVAT>
      <TEMPTAXTYPE/>
      <TEMPMAJORCOMMODITYNAME/>
      <TEMPCOMMODITYNAME/>
      <TEMPCOMMODITYCODE/>
      <TEMPSUBCOMMODITYCODE/>
      <TEMPUOM/>
      <TEMPTYPEOFGOODS/>
      <TEMPTRADENAME/>
      <TEMPGOODSNATURE/>
      <TEMPSCHEDULE/>
      <TEMPSCHEDULESLNO/>
      <TEMPISINVDETAILSENABLE/>
      <TEMPLOCALVATRATE>0</TEMPLOCALVATRATE>
      <TEMPVALUATIONTYPE/>
      <TEMPISCALCONQTY/>
      <TEMPISSALETOLOCALCITIZEN/>
      <LEDISTDSAPPLICABLECURRLIAB/>
      <ISPRODUCTCODEEDITED/>
      <SERVICETAXDETAILS.LIST>      </SERVICETAXDETAILS.LIST>
      <LBTREGNDETAILS.LIST>      </LBTREGNDETAILS.LIST>
      <VATDETAILS.LIST>      </VATDETAILS.LIST>
      <SALESTAXCESSDETAILS.LIST>      </SALESTAXCESSDETAILS.LIST>
      <GSTDETAILS.LIST>      </GSTDETAILS.LIST>
      <LANGUAGENAME.LIST>
       <NAME.LIST TYPE="String">
        <NAME>Primary key technologies C - test2</NAME>
       </NAME.LIST>
       <LANGUAGEID> 1033</LANGUAGEID>
      </LANGUAGENAME.LIST>
      <XBRLDETAIL.LIST>      </XBRLDETAIL.LIST>
      <AUDITDETAILS.LIST>      </AUDITDETAILS.LIST>
      <SCHVIDETAILS.LIST>      </SCHVIDETAILS.LIST>
      <EXCISETARIFFDETAILS.LIST>      </EXCISETARIFFDETAILS.LIST>
      <TCSCATEGORYDETAILS.LIST>      </TCSCATEGORYDETAILS.LIST>
      <TDSCATEGORYDETAILS.LIST>      </TDSCATEGORYDETAILS.LIST>
      <SLABPERIOD.LIST>      </SLABPERIOD.LIST>
      <GRATUITYPERIOD.LIST>      </GRATUITYPERIOD.LIST>
      <ADDITIONALCOMPUTATIONS.LIST>      </ADDITIONALCOMPUTATIONS.LIST>
      <EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>
      <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
      <BANKALLOCATIONS.LIST>      </BANKALLOCATIONS.LIST>
      <PAYMENTDETAILS.LIST>      </PAYMENTDETAILS.LIST>
      <BANKEXPORTFORMATS.LIST>      </BANKEXPORTFORMATS.LIST>
      <BILLALLOCATIONS.LIST>      </BILLALLOCATIONS.LIST>
      <INTERESTCOLLECTION.LIST>
       <INTERESTFROMDATE/>
       <INTERESTTODATE/>
       <INTERESTSTYLE>365-Day Year</INTERESTSTYLE>
       <INTERESTBALANCETYPE/>
       <INTERESTAPPLON/>
       <INTERESTFROMTYPE>Past Due Date</INTERESTFROMTYPE>
       <ROUNDTYPE/>
       <INTERESTRATE> 24</INTERESTRATE>
       <INTERESTAPPLFROM>0</INTERESTAPPLFROM>
       <ROUNDLIMIT>0</ROUNDLIMIT>
      </INTERESTCOLLECTION.LIST>
      <LEDGERCLOSINGVALUES.LIST>      </LEDGERCLOSINGVALUES.LIST>
      <LEDGERAUDITCLASS.LIST>      </LEDGERAUDITCLASS.LIST>
      <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
      <TDSEXEMPTIONRULES.LIST>      </TDSEXEMPTIONRULES.LIST>
      <DEDUCTINSAMEVCHRULES.LIST>      </DEDUCTINSAMEVCHRULES.LIST>
      <LOWERDEDUCTION.LIST>      </LOWERDEDUCTION.LIST>
      <STXABATEMENTDETAILS.LIST>      </STXABATEMENTDETAILS.LIST>
      <LEDMULTIADDRESSLIST.LIST>      </LEDMULTIADDRESSLIST.LIST>
      <STXTAXDETAILS.LIST>      </STXTAXDETAILS.LIST>
      <CHEQUERANGE.LIST>      </CHEQUERANGE.LIST>
      <DEFAULTVCHCHEQUEDETAILS.LIST>      </DEFAULTVCHCHEQUEDETAILS.LIST>
      <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
      <BRSIMPORTEDINFO.LIST>      </BRSIMPORTEDINFO.LIST>
      <AUTOBRSCONFIGS.LIST>      </AUTOBRSCONFIGS.LIST>
      <BANKURENTRIES.LIST>      </BANKURENTRIES.LIST>
      <DEFAULTCHEQUEDETAILS.LIST>      </DEFAULTCHEQUEDETAILS.LIST>
      <DEFAULTOPENINGCHEQUEDETAILS.LIST>      </DEFAULTOPENINGCHEQUEDETAILS.LIST>
      <CANCELLEDPAYALLOCATIONS.LIST>      </CANCELLEDPAYALLOCATIONS.LIST>
      <ECHEQUEPRINTLOCATION.LIST>      </ECHEQUEPRINTLOCATION.LIST>
      <ECHEQUEPAYABLELOCATION.LIST>      </ECHEQUEPAYABLELOCATION.LIST>
      <EDDPRINTLOCATION.LIST>      </EDDPRINTLOCATION.LIST>
      <EDDPAYABLELOCATION.LIST>      </EDDPAYABLELOCATION.LIST>
      <AVAILABLETRANSACTIONTYPES.LIST>      </AVAILABLETRANSACTIONTYPES.LIST>
      <LEDPAYINSCONFIGS.LIST>      </LEDPAYINSCONFIGS.LIST>
      <TYPECODEDETAILS.LIST>      </TYPECODEDETAILS.LIST>
      <FIELDVALIDATIONDETAILS.LIST>      </FIELDVALIDATIONDETAILS.LIST>
      <INPUTCRALLOCS.LIST>      </INPUTCRALLOCS.LIST>
      <GSTCLASSFNIGSTRATES.LIST>      </GSTCLASSFNIGSTRATES.LIST>
      <EXTARIFFDUTYHEADDETAILS.LIST>      </EXTARIFFDUTYHEADDETAILS.LIST>
      <VOUCHERTYPEPRODUCTCODES.LIST>      </VOUCHERTYPEPRODUCTCODES.LIST>
      <UDF:VATDEALERNATURE.LIST DESC="`VATDealerNature`" ISLIST="YES" TYPE="String" INDEX="10031">
       <UDF:VATDEALERNATURE DESC="`VATDealerNature`">Registered Dealer</UDF:VATDEALERNATURE>
      </UDF:VATDEALERNATURE.LIST>
     </LEDGER>
    </TALLYMESSAGE>
    </REQUESTDATA>
    </IMPORTDATA>
    </BODY>
    </ENVELOPE>';
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, 'http://111.119.207.78:9002/');  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data ); //$data = "XML data"  
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $response = curl_exec ($ch); 
        // print_r($response); 
        if(curl_errno($ch)){  
        echo curl_error($ch);  
        }  
        else{  
        print "<pre lang='xml'>" . htmlspecialchars($response) . "</pre>";  
        curl_close($ch);  
        }    
        return "";  
        }


    function tally_to_vender()
    {
        ini_set('display_errors', 1);
        ini_set("memory_limit","500M");
        $data = '<TALLYMESSAGE xmlns:UDF="TallyUDF">
     <LEDGER NAME="AAKAR STEEL (P)" RESERVEDNAME="">
      <ADDRESS.LIST TYPE="String">
       <ADDRESS>303 GIRIRAJ BLDG. SANT TUKARAM ROAD\</ADDRESS>
       <ADDRESS>MASJID BUNDER (E) MUMBAI-400009</ADDRESS>
      </ADDRESS.LIST>
      <MAILINGNAME.LIST TYPE="String">
       <MAILINGNAME>AAKAR STEEL (P)</MAILINGNAME>
      </MAILINGNAME.LIST>
      <OLDAUDITENTRYIDS.LIST TYPE="Number">
       <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
      </OLDAUDITENTRYIDS.LIST>
      <STARTINGFROM/>
      <STREGDATE/>
      <LBTREGNDATE/>
      <SAMPLINGDATEONEFACTOR/>
      <SAMPLINGDATETWOFACTOR/>
      <ACTIVEFROM/>
      <ACTIVETO/>
      <CREATEDDATE>20181113</CREATEDDATE>
      <ALTEREDON>20190109</ALTEREDON>
      <VATAPPLICABLEDATE/>
      <EXCISEREGISTRATIONDATE/>
      <PANAPPLICABLEFROM/>
      <PAYINSRUNNINGFILEDATE/>
      <VATTAXEXEMPTIONDATE/>
      <GUID>572f2ebb-d04c-4c56-94f3-a79d8923e290-00006251</GUID>
      <CURRENCYNAME>?</CURRENCYNAME>
      <EMAIL/>
      <STATENAME/>
      <PINCODE>400009</PINCODE>
      <WEBSITE/>
      <INCOMETAXNUMBER>BCDPN8847C</INCOMETAXNUMBER>
      <SALESTAXNUMBER/>
      <INTERSTATESTNUMBER/>
      <VATTINNUMBER/>
      <COUNTRYNAME>India</COUNTRYNAME>
      <EXCISERANGE/>
      <EXCISEDIVISION/>
      <EXCISECOMMISSIONERATE/>
      <LBTREGNNO/>
      <LBTZONE/>
      <EXPORTIMPORTCODE/>
      <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
      <VATDEALERTYPE>Regular</VATDEALERTYPE>
      <PRICELEVEL/>
      <UPLOADLASTREFRESH/>
      <PARENT>Sundry Creditors</PARENT>
      <SAMPLINGMETHOD/>
      <SAMPLINGSTRONEFACTOR/>
      <IFSCODE/>
      <REQUESTORRULE/>
      <GRPDEBITPARENT/>
      <GRPCREDITPARENT/>
      <SYSDEBITPARENT/>
      <SYSCREDITPARENT/>
      <TDSAPPLICABLE/>
      <TCSAPPLICABLE/>
      <GSTAPPLICABLE/>
      <CREATEDBY>RAJAN</CREATEDBY>
      <ALTEREDBY>AJS</ALTEREDBY>
      <TAXCLASSIFICATIONNAME/>
      <TAXTYPE>Others</TAXTYPE>
      <BILLCREDITPERIOD/>
      <BANKDETAILS/>
      <BANKBRANCHNAME/>
      <BANKBSRCODE/>
      <DEDUCTEETYPE/>
      <BUSINESSTYPE/>
      <TYPEOFNOTIFICATION/>
      <MSMEREGNUMBER/>
      <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
      <RELATEDPARTYID/>
      <RELPARTYISSUINGAUTHORITY/>
      <IMPORTEREXPORTERCODE/>
      <EMAILCC/>
      <DESCRIPTION/>
      <LEDADDLALLOCTYPE/>
      <TRANSPORTERID/>
      <LEDGERPHONE/>
      <LEDGERFAX/>
      <LEDGERCONTACT/>
      <LEDGERMOBILE/>
      <RELATIONTYPE/>
      <MAILINGNAMENATIVE/>
      <STATENAMENATIVE/>
      <COUNTRYNAMENATIVE/>
      <BASICTYPEOFDUTY/>
      <GSTTYPE/>
      <EXEMPTIONTYPE/>
      <APPROPRIATEFOR/>
      <SUBTAXTYPE/>
      <STXNATUREOFPARTY/>
      <NAMEONPAN/>
      <USEDFORTAXTYPE/>
      <ECOMMMERCHANTID/>
      <PARTYGSTIN>27BCDPN8847C1ZF</PARTYGSTIN>
      <GSTDUTYHEAD/>
      <GSTAPPROPRIATETO/>
      <GSTTYPEOFSUPPLY/>
      <GSTNATUREOFSUPPLY/>
      <CESSVALUATIONMETHOD/>
      <PAYTYPE/>
      <PAYSLIPNAME/>
      <ATTENDANCETYPE/>
      <LEAVETYPE/>
      <CALCULATIONPERIOD/>
      <ROUNDINGMETHOD/>
      <COMPUTATIONTYPE/>
      <CALCULATIONTYPE/>
      <PAYSTATTYPE/>
      <PROFESSIONALTAXNUMBER/>
      <USERDEFINEDCALENDERTYPE/>
      <ITNATURE/>
      <ITCOMPONENT/>
      <NOTIFICATIONNUMBER/>
      <REGISTRATIONNUMBER/>
      <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
      <ABATEMENTNOTIFICATIONNO/>
      <STXDUTYHEAD/>
      <STXCLASSIFICATION/>
      <NOTIFICATIONSLNO/>
      <SERVICETAXAPPLICABLE/>
      <EXCISELEDGERCLASSIFICATION/>
      <EXCISEREGISTRATIONNUMBER/>
      <EXCISEACCOUNTHEAD/>
      <EXCISEDUTYTYPE/>
      <EXCISEDUTYHEADCODE/>
      <EXCISEALLOCTYPE/>
      <EXCISEDUTYHEAD/>
      <NATUREOFSALES/>
      <EXCISENOTIFICATIONNO/>
      <EXCISEIMPORTSREGISTARTIONNO/>
      <EXCISEAPPLICABILITY/>
      <EXCISETYPEOFBRANCH/>
      <EXCISEDEFAULTREMOVAL/>
      <EXCISENOTIFICATIONSLNO/>
      <TYPEOFTARIFF/>
      <EXCISEREGNO/>
      <EXCISENATUREOFPURCHASE/>
      <TDSDEDUCTEETYPEMST/>
      <TDSRATENAME/>
      <TDSDEDUCTEESECTIONNUMBER/>
      <PANSTATUS/>
      <DEDUCTEEREFERENCE/>
      <TDSDEDUCTEETYPE/>
      <ITEXEMPTAPPLICABLE/>
      <TAXIDENTIFICATIONNO/>
      <LEDGERFBTCATEGORY/>
      <BRANCHCODE/>
      <CLIENTCODE/>
      <BANKINGCONFIGBANK/>
      <BANKINGCONFIGBANKID/>
      <BANKACCHOLDERNAME/>
      <USEFORPOSTYPE/>
      <PAYMENTGATEWAY/>
      <TYPEOFINTERESTON/>
      <BANKCONFIGIFSC/>
      <BANKCONFIGMICR/>
      <BANKCONFIGSHORTCODE/>
      <PYMTINSTOUTPUTNAME/>
      <PRODUCTCODETYPE/>
      <SALARYPYMTPRODUCTCODE/>
      <OTHERPYMTPRODUCTCODE/>
      <PAYMENTINSTLOCATION/>
      <ENCRPTIONLOCATION/>
      <NEWIMFLOCATION/>
      <IMPORTEDIMFLOCATION/>
      <BANKNEWSTATEMENTS/>
      <BANKIMPORTEDSTATEMENTS/>
      <BANKMICR/>
      <CORPORATEUSERNOECS/>
      <CORPORATEUSERNOACH/>
      <CORPORATEUSERNAME/>
      <IMFNAME/>
      <PAYINSBATCHNAME/>
      <LASTUSEDBATCHNAME/>
      <PAYINSFILENUMPERIOD/>
      <ENCRYPTEDBY/>
      <ENCRYPTEDID/>
      <ISOCURRENCYCODE/>
      <BANKCAPSULEID/>
      <SALESTAXCESSAPPLICABLE/>
      <VATTAXEXEMPTIONNATURE/>
      <VATTAXEXEMPTIONNUMBER/>
      <LEDSTATENAME>Maharashtra</LEDSTATENAME>
      <VATAPPLICABLE/>
      <PARTYBUSINESSTYPE/>
      <PARTYBUSINESSSTYLE/>
      <ISBILLWISEON>Yes</ISBILLWISEON>
      <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
      <ISINTERESTON>No</ISINTERESTON>
      <ALLOWINMOBILE>No</ALLOWINMOBILE>
      <ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>
      <ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>
      <ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>
      <ASORIGINAL>Yes</ASORIGINAL>
      <ISCONDENSED>No</ISCONDENSED>
      <AFFECTSSTOCK>No</AFFECTSSTOCK>
      <ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>
      <FORPAYROLL>No</FORPAYROLL>
      <ISABCENABLED>No</ISABCENABLED>
      <ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>
      <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
      <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
      <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
      <USEFORVAT>No</USEFORVAT>
      <IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>
      <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
      <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
      <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
      <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
      <ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>
      <ISTDSEXPENSE>No</ISTDSEXPENSE>
      <ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>
      <ISRELATEDPARTY>No</ISRELATEDPARTY>
      <USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>
      <ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>
      <APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>
      <ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>
      <INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>
      <INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>
      <ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>
      <OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>
      <ISAGAINSTFORMC>No</ISAGAINSTFORMC>
      <ISCHEQUEPRINTINGENABLED>Yes</ISCHEQUEPRINTINGENABLED>
      <ISPAYUPLOAD>No</ISPAYUPLOAD>
      <ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>
      <ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>
      <ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>
      <CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>
      <ISTRANSPORTER>No</ISTRANSPORTER>
      <USEFORNOTIONALITC>No</USEFORNOTIONALITC>
      <ISECOMMOPERATOR>No</ISECOMMOPERATOR>
      <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
      <USEFORGRATUITY>No</USEFORGRATUITY>
      <ISTDSPROJECTED>No</ISTDSPROJECTED>
      <FORSERVICETAX>No</FORSERVICETAX>
      <ISINPUTCREDIT>No</ISINPUTCREDIT>
      <ISEXEMPTED>No</ISEXEMPTED>
      <ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>
      <ISSTXPARTY>No</ISSTXPARTY>
      <ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>
      <ISUSEDFORCVD>No</ISUSEDFORCVD>
      <LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>
      <ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>
      <ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>
      <ISSEZPARTY>No</ISSEZPARTY>
      <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
      <ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>
      <ISEDDSUPPORTED>No</ISEDDSUPPORTED>
      <HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>
      <HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>
      <HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>
      <HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>
      <HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>
      <HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>
      <HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>
      <HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>
      <HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>
      <HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>
      <ISEBANKINGENABLED>No</ISEBANKINGENABLED>
      <ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>
      <ISBATCHENABLED>No</ISBATCHENABLED>
      <ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>
      <HASEDDCITY>No</HASEDDCITY>
      <HASECHEQUECITY>No</HASECHEQUECITY>
      <ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>
      <HASCLIENTCODE>No</HASCLIENTCODE>
      <PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>
      <PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>
      <ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>
      <ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>
      <ISSCBUAE>No</ISSCBUAE>
      <ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>
      <ISSALARYGROUPED>No</ISSALARYGROUPED>
      <USEFORPURCHASETAX>No</USEFORPURCHASETAX>
      <AUDITED>No</AUDITED>
      <SAMPLINGNUMONEFACTOR>0</SAMPLINGNUMONEFACTOR>
      <SAMPLINGNUMTWOFACTOR>0</SAMPLINGNUMTWOFACTOR>
      <SORTPOSITION> 1000</SORTPOSITION>
      <ALTERID> 140098</ALTERID>
      <DEFAULTLANGUAGE>0</DEFAULTLANGUAGE>
      <RATEOFTAXCALCULATION>0</RATEOFTAXCALCULATION>
      <GRATUITYMONTHDAYS>0</GRATUITYMONTHDAYS>
      <GRATUITYLIMITMONTHS>0</GRATUITYLIMITMONTHS>
      <CALCULATIONBASIS>0</CALCULATIONBASIS>
      <ROUNDINGLIMIT>0</ROUNDINGLIMIT>
      <ABATEMENTPERCENTAGE>0</ABATEMENTPERCENTAGE>
      <TDSDEDUCTEESPECIALRATE>0</TDSDEDUCTEESPECIALRATE>
      <BENEFICIARYCODEMAXLENGTH>0</BENEFICIARYCODEMAXLENGTH>
      <ECHEQUEPRINTLOCATIONVERSION>0</ECHEQUEPRINTLOCATIONVERSION>
      <ECHEQUEPAYABLELOCATIONVERSION>0</ECHEQUEPAYABLELOCATIONVERSION>
      <EDDPRINTLOCATIONVERSION>0</EDDPRINTLOCATIONVERSION>
      <EDDPAYABLELOCATIONVERSION>0</EDDPAYABLELOCATIONVERSION>
      <PAYINSRUNNINGFILENUM>0</PAYINSRUNNINGFILENUM>
      <TRANSACTIONTYPEVERSION>0</TRANSACTIONTYPEVERSION>
      <PAYINSFILENUMLENGTH>0</PAYINSFILENUMLENGTH>
      <SAMPLINGAMTONEFACTOR/>
      <SAMPLINGAMTTWOFACTOR/>
      <CREDITLIMIT/>
      <GRATUITYLIMITAMOUNT/>
      <ODLIMIT/>
      <TEMPGSTCGSTRATE>0</TEMPGSTCGSTRATE>
      <TEMPGSTSGSTRATE>0</TEMPGSTSGSTRATE>
      <TEMPGSTIGSTRATE>0</TEMPGSTIGSTRATE>
      <TEMPISVATFIELDSEDITED/>
      <TEMPAPPLDATE/>
      <TEMPCLASSIFICATION/>
      <TEMPNATURE/>
      <TEMPPARTYENTITY/>
      <TEMPBUSINESSNATURE/>
      <TEMPVATRATE>0</TEMPVATRATE>
      <TEMPADDLTAX>0</TEMPADDLTAX>
      <TEMPCESSONVAT>0</TEMPCESSONVAT>
      <TEMPTAXTYPE/>
      <TEMPMAJORCOMMODITYNAME/>
      <TEMPCOMMODITYNAME/>
      <TEMPCOMMODITYCODE/>
      <TEMPSUBCOMMODITYCODE/>
      <TEMPUOM/>
      <TEMPTYPEOFGOODS/>
      <TEMPTRADENAME/>
      <TEMPGOODSNATURE/>
      <TEMPSCHEDULE/>
      <TEMPSCHEDULESLNO/>
      <TEMPISINVDETAILSENABLE/>
      <TEMPLOCALVATRATE>0</TEMPLOCALVATRATE>
      <TEMPVALUATIONTYPE/>
      <TEMPISCALCONQTY/>
      <TEMPISSALETOLOCALCITIZEN/>
      <LEDISTDSAPPLICABLECURRLIAB/>
      <ISPRODUCTCODEEDITED/>
      <SERVICETAXDETAILS.LIST>      </SERVICETAXDETAILS.LIST>
      <LBTREGNDETAILS.LIST>      </LBTREGNDETAILS.LIST>
      <VATDETAILS.LIST>      </VATDETAILS.LIST>
      <SALESTAXCESSDETAILS.LIST>      </SALESTAXCESSDETAILS.LIST>
      <GSTDETAILS.LIST>      </GSTDETAILS.LIST>
      <LANGUAGENAME.LIST>
       <NAME.LIST TYPE="String">
        <NAME>AAKAR STEEL (P)</NAME>
       </NAME.LIST>
       <LANGUAGEID> 1033</LANGUAGEID>
      </LANGUAGENAME.LIST>
      <XBRLDETAIL.LIST>      </XBRLDETAIL.LIST>
      <AUDITDETAILS.LIST>      </AUDITDETAILS.LIST>
      <SCHVIDETAILS.LIST>      </SCHVIDETAILS.LIST>
      <EXCISETARIFFDETAILS.LIST>      </EXCISETARIFFDETAILS.LIST>
      <TCSCATEGORYDETAILS.LIST>      </TCSCATEGORYDETAILS.LIST>
      <TDSCATEGORYDETAILS.LIST>      </TDSCATEGORYDETAILS.LIST>
      <SLABPERIOD.LIST>      </SLABPERIOD.LIST>
      <GRATUITYPERIOD.LIST>      </GRATUITYPERIOD.LIST>
      <ADDITIONALCOMPUTATIONS.LIST>      </ADDITIONALCOMPUTATIONS.LIST>
      <EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>
      <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
      <BANKALLOCATIONS.LIST>      </BANKALLOCATIONS.LIST>
      <PAYMENTDETAILS.LIST>      </PAYMENTDETAILS.LIST>
      <BANKEXPORTFORMATS.LIST>      </BANKEXPORTFORMATS.LIST>
      <BILLALLOCATIONS.LIST>      </BILLALLOCATIONS.LIST>
      <INTERESTCOLLECTION.LIST>      </INTERESTCOLLECTION.LIST>
      <LEDGERCLOSINGVALUES.LIST>      </LEDGERCLOSINGVALUES.LIST>
      <LEDGERAUDITCLASS.LIST>      </LEDGERAUDITCLASS.LIST>
      <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
      <TDSEXEMPTIONRULES.LIST>      </TDSEXEMPTIONRULES.LIST>
      <DEDUCTINSAMEVCHRULES.LIST>      </DEDUCTINSAMEVCHRULES.LIST>
      <LOWERDEDUCTION.LIST>      </LOWERDEDUCTION.LIST>
      <STXABATEMENTDETAILS.LIST>      </STXABATEMENTDETAILS.LIST>
      <LEDMULTIADDRESSLIST.LIST>      </LEDMULTIADDRESSLIST.LIST>
      <STXTAXDETAILS.LIST>      </STXTAXDETAILS.LIST>
      <CHEQUERANGE.LIST>      </CHEQUERANGE.LIST>
      <DEFAULTVCHCHEQUEDETAILS.LIST>      </DEFAULTVCHCHEQUEDETAILS.LIST>
      <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
      <BRSIMPORTEDINFO.LIST>      </BRSIMPORTEDINFO.LIST>
      <AUTOBRSCONFIGS.LIST>      </AUTOBRSCONFIGS.LIST>
      <BANKURENTRIES.LIST>      </BANKURENTRIES.LIST>
      <DEFAULTCHEQUEDETAILS.LIST>      </DEFAULTCHEQUEDETAILS.LIST>
      <DEFAULTOPENINGCHEQUEDETAILS.LIST>      </DEFAULTOPENINGCHEQUEDETAILS.LIST>
      <CANCELLEDPAYALLOCATIONS.LIST>      </CANCELLEDPAYALLOCATIONS.LIST>
      <ECHEQUEPRINTLOCATION.LIST>      </ECHEQUEPRINTLOCATION.LIST>
      <ECHEQUEPAYABLELOCATION.LIST>      </ECHEQUEPAYABLELOCATION.LIST>
      <EDDPRINTLOCATION.LIST>      </EDDPRINTLOCATION.LIST>
      <EDDPAYABLELOCATION.LIST>      </EDDPAYABLELOCATION.LIST>
      <AVAILABLETRANSACTIONTYPES.LIST>      </AVAILABLETRANSACTIONTYPES.LIST>
      <LEDPAYINSCONFIGS.LIST>      </LEDPAYINSCONFIGS.LIST>
      <TYPECODEDETAILS.LIST>      </TYPECODEDETAILS.LIST>
      <FIELDVALIDATIONDETAILS.LIST>      </FIELDVALIDATIONDETAILS.LIST>
      <INPUTCRALLOCS.LIST>      </INPUTCRALLOCS.LIST>
      <GSTCLASSFNIGSTRATES.LIST>      </GSTCLASSFNIGSTRATES.LIST>
      <EXTARIFFDUTYHEADDETAILS.LIST>      </EXTARIFFDUTYHEADDETAILS.LIST>
      <VOUCHERTYPEPRODUCTCODES.LIST>      </VOUCHERTYPEPRODUCTCODES.LIST>
     </LEDGER>
    </TALLYMESSAGE>';
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, 'http://111.119.207.78:9002/');  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data ); //$data = "XML data"  
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $response = curl_exec ($ch); 
        // print_r($response); 
        if(curl_errno($ch)){  
        echo curl_error($ch);  
        }  
        else{  
        print "<pre lang='xml'>" . htmlspecialchars($response) . "</pre>";  
        curl_close($ch);  
        }    
        return "";  
        }

    function tally_to_broker()
    {
        ini_set('display_errors', 1);
        ini_set("memory_limit","500M");
        $data = '<TALLYMESSAGE xmlns:UDF="TallyUDF">
     <LEDGER NAME="AJAY  PAREKH (B)" RESERVEDNAME="">
      <ADDRESS.LIST TYPE="String">
       <ADDRESS>104/a, Shree Yamuna, Poisar, Kandivali (W),</ADDRESS>
       <ADDRESS>Mumbai 400 067</ADDRESS>
      </ADDRESS.LIST>
      <MAILINGNAME.LIST TYPE="String">
       <MAILINGNAME>AJAY  PAREKH (B)</MAILINGNAME>
      </MAILINGNAME.LIST>
      <OLDAUDITENTRYIDS.LIST TYPE="Number">
       <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
      </OLDAUDITENTRYIDS.LIST>
      <STARTINGFROM/>
      <STREGDATE/>
      <LBTREGNDATE/>
      <SAMPLINGDATEONEFACTOR/>
      <SAMPLINGDATETWOFACTOR/>
      <ACTIVEFROM/>
      <ACTIVETO/>
      <CREATEDDATE>20171226</CREATEDDATE>
      <ALTEREDON>20190118</ALTEREDON>
      <VATAPPLICABLEDATE/>
      <EXCISEREGISTRATIONDATE/>
      <PANAPPLICABLEFROM>20170401</PANAPPLICABLEFROM>
      <PAYINSRUNNINGFILEDATE/>
      <VATTAXEXEMPTIONDATE/>
      <GUID>572f2ebb-d04c-4c56-94f3-a79d8923e290-000046ba</GUID>
      <CURRENCYNAME>?</CURRENCYNAME>
      <EMAIL/>
      <STATENAME/>
      <PINCODE>400067</PINCODE>
      <WEBSITE/>
      <INCOMETAXNUMBER>AFEPP2438N</INCOMETAXNUMBER>
      <SALESTAXNUMBER/>
      <INTERSTATESTNUMBER/>
      <VATTINNUMBER/>
      <COUNTRYNAME>India</COUNTRYNAME>
      <EXCISERANGE/>
      <EXCISEDIVISION/>
      <EXCISECOMMISSIONERATE/>
      <LBTREGNNO/>
      <LBTZONE/>
      <EXPORTIMPORTCODE/>
      <GSTREGISTRATIONTYPE>Unregistered</GSTREGISTRATIONTYPE>
      <VATDEALERTYPE>Regular</VATDEALERTYPE>
      <PRICELEVEL/>
      <UPLOADLASTREFRESH/>
      <PARENT>SUNDRY CREDITORS-BROKER</PARENT>
      <SAMPLINGMETHOD/>
      <SAMPLINGSTRONEFACTOR/>
      <IFSCODE/>
      <REQUESTORRULE/>
      <GRPDEBITPARENT/>
      <GRPCREDITPARENT/>
      <SYSDEBITPARENT/>
      <SYSCREDITPARENT/>
      <TDSAPPLICABLE/>
      <TCSAPPLICABLE/>
      <GSTAPPLICABLE/>
      <CREATEDBY>shedge</CREATEDBY>
      <ALTEREDBY>RAJAN</ALTEREDBY>
      <TAXCLASSIFICATIONNAME/>
      <TAXTYPE>Others</TAXTYPE>
      <BILLCREDITPERIOD/>
      <BANKDETAILS/>
      <BANKBRANCHNAME/>
      <BANKBSRCODE/>
      <DEDUCTEETYPE/>
      <BUSINESSTYPE/>
      <TYPEOFNOTIFICATION/>
      <MSMEREGNUMBER/>
      <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
      <RELATEDPARTYID/>
      <RELPARTYISSUINGAUTHORITY/>
      <IMPORTEREXPORTERCODE/>
      <EMAILCC/>
      <DESCRIPTION/>
      <LEDADDLALLOCTYPE/>
      <TRANSPORTERID/>
      <LEDGERPHONE/>
      <LEDGERFAX/>
      <LEDGERCONTACT/>
      <LEDGERMOBILE/>
      <RELATIONTYPE/>
      <MAILINGNAMENATIVE/>
      <STATENAMENATIVE/>
      <COUNTRYNAMENATIVE/>
      <BASICTYPEOFDUTY/>
      <GSTTYPE/>
      <EXEMPTIONTYPE/>
      <APPROPRIATEFOR/>
      <SUBTAXTYPE/>
      <STXNATUREOFPARTY/>
      <NAMEONPAN>AJAY PAREKH (B)</NAMEONPAN>
      <USEDFORTAXTYPE/>
      <ECOMMMERCHANTID/>
      <PARTYGSTIN/>
      <GSTDUTYHEAD/>
      <GSTAPPROPRIATETO/>
      <GSTTYPEOFSUPPLY/>
      <GSTNATUREOFSUPPLY/>
      <CESSVALUATIONMETHOD/>
      <PAYTYPE/>
      <PAYSLIPNAME/>
      <ATTENDANCETYPE/>
      <LEAVETYPE/>
      <CALCULATIONPERIOD/>
      <ROUNDINGMETHOD/>
      <COMPUTATIONTYPE/>
      <CALCULATIONTYPE/>
      <PAYSTATTYPE/>
      <PROFESSIONALTAXNUMBER/>
      <USERDEFINEDCALENDERTYPE/>
      <ITNATURE/>
      <ITCOMPONENT/>
      <NOTIFICATIONNUMBER/>
      <REGISTRATIONNUMBER/>
      <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
      <ABATEMENTNOTIFICATIONNO/>
      <STXDUTYHEAD/>
      <STXCLASSIFICATION/>
      <NOTIFICATIONSLNO/>
      <SERVICETAXAPPLICABLE/>
      <EXCISELEDGERCLASSIFICATION/>
      <EXCISEREGISTRATIONNUMBER/>
      <EXCISEACCOUNTHEAD/>
      <EXCISEDUTYTYPE/>
      <EXCISEDUTYHEADCODE/>
      <EXCISEALLOCTYPE/>
      <EXCISEDUTYHEAD/>
      <NATUREOFSALES/>
      <EXCISENOTIFICATIONNO/>
      <EXCISEIMPORTSREGISTARTIONNO/>
      <EXCISEAPPLICABILITY/>
      <EXCISETYPEOFBRANCH/>
      <EXCISEDEFAULTREMOVAL/>
      <EXCISENOTIFICATIONSLNO/>
      <TYPEOFTARIFF/>
      <EXCISEREGNO/>
      <EXCISENATUREOFPURCHASE/>
      <TDSDEDUCTEETYPEMST/>
      <TDSRATENAME/>
      <TDSDEDUCTEESECTIONNUMBER/>
      <PANSTATUS/>
      <DEDUCTEEREFERENCE/>
      <TDSDEDUCTEETYPE>Individual/HUF - Resident</TDSDEDUCTEETYPE>
      <ITEXEMPTAPPLICABLE/>
      <TAXIDENTIFICATIONNO/>
      <LEDGERFBTCATEGORY/>
      <BRANCHCODE/>
      <CLIENTCODE/>
      <BANKINGCONFIGBANK/>
      <BANKINGCONFIGBANKID/>
      <BANKACCHOLDERNAME/>
      <USEFORPOSTYPE/>
      <PAYMENTGATEWAY/>
      <TYPEOFINTERESTON/>
      <BANKCONFIGIFSC/>
      <BANKCONFIGMICR/>
      <BANKCONFIGSHORTCODE/>
      <PYMTINSTOUTPUTNAME/>
      <PRODUCTCODETYPE/>
      <SALARYPYMTPRODUCTCODE/>
      <OTHERPYMTPRODUCTCODE/>
      <PAYMENTINSTLOCATION/>
      <ENCRPTIONLOCATION/>
      <NEWIMFLOCATION/>
      <IMPORTEDIMFLOCATION/>
      <BANKNEWSTATEMENTS/>
      <BANKIMPORTEDSTATEMENTS/>
      <BANKMICR/>
      <CORPORATEUSERNOECS/>
      <CORPORATEUSERNOACH/>
      <CORPORATEUSERNAME/>
      <IMFNAME/>
      <PAYINSBATCHNAME/>
      <LASTUSEDBATCHNAME/>
      <PAYINSFILENUMPERIOD/>
      <ENCRYPTEDBY/>
      <ENCRYPTEDID/>
      <ISOCURRENCYCODE/>
      <BANKCAPSULEID/>
      <SALESTAXCESSAPPLICABLE/>
      <VATTAXEXEMPTIONNATURE/>
      <VATTAXEXEMPTIONNUMBER/>
      <LEDSTATENAME>Maharashtra</LEDSTATENAME>
      <VATAPPLICABLE/>
      <PARTYBUSINESSTYPE/>
      <PARTYBUSINESSSTYLE/>
      <ISBILLWISEON>Yes</ISBILLWISEON>
      <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
      <ISINTERESTON>No</ISINTERESTON>
      <ALLOWINMOBILE>No</ALLOWINMOBILE>
      <ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>
      <ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>
      <ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>
      <ASORIGINAL>Yes</ASORIGINAL>
      <ISCONDENSED>No</ISCONDENSED>
      <AFFECTSSTOCK>No</AFFECTSSTOCK>
      <ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>
      <FORPAYROLL>No</FORPAYROLL>
      <ISABCENABLED>No</ISABCENABLED>
      <ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>
      <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
      <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
      <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
      <USEFORVAT>No</USEFORVAT>
      <IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>
      <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
      <ISTDSAPPLICABLE>Yes</ISTDSAPPLICABLE>
      <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
      <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
      <ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>
      <ISTDSEXPENSE>No</ISTDSEXPENSE>
      <ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>
      <ISRELATEDPARTY>No</ISRELATEDPARTY>
      <USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>
      <ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>
      <APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>
      <ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>
      <INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>
      <INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>
      <ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>
      <OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>
      <ISAGAINSTFORMC>No</ISAGAINSTFORMC>
      <ISCHEQUEPRINTINGENABLED>Yes</ISCHEQUEPRINTINGENABLED>
      <ISPAYUPLOAD>No</ISPAYUPLOAD>
      <ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>
      <ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>
      <ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>
      <CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>
      <ISTRANSPORTER>No</ISTRANSPORTER>
      <USEFORNOTIONALITC>No</USEFORNOTIONALITC>
      <ISECOMMOPERATOR>No</ISECOMMOPERATOR>
      <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
      <USEFORGRATUITY>No</USEFORGRATUITY>
      <ISTDSPROJECTED>No</ISTDSPROJECTED>
      <FORSERVICETAX>No</FORSERVICETAX>
      <ISINPUTCREDIT>No</ISINPUTCREDIT>
      <ISEXEMPTED>No</ISEXEMPTED>
      <ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>
      <ISSTXPARTY>No</ISSTXPARTY>
      <ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>
      <ISUSEDFORCVD>No</ISUSEDFORCVD>
      <LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>
      <ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>
      <ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>
      <ISSEZPARTY>No</ISSEZPARTY>
      <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
      <ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>
      <ISEDDSUPPORTED>No</ISEDDSUPPORTED>
      <HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>
      <HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>
      <HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>
      <HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>
      <HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>
      <HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>
      <HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>
      <HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>
      <HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>
      <HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>
      <ISEBANKINGENABLED>No</ISEBANKINGENABLED>
      <ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>
      <ISBATCHENABLED>No</ISBATCHENABLED>
      <ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>
      <HASEDDCITY>No</HASEDDCITY>
      <HASECHEQUECITY>No</HASECHEQUECITY>
      <ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>
      <HASCLIENTCODE>No</HASCLIENTCODE>
      <PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>
      <PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>
      <ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>
      <ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>
      <ISSCBUAE>No</ISSCBUAE>
      <ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>
      <ISSALARYGROUPED>No</ISSALARYGROUPED>
      <USEFORPURCHASETAX>No</USEFORPURCHASETAX>
      <AUDITED>No</AUDITED>
      <SAMPLINGNUMONEFACTOR>0</SAMPLINGNUMONEFACTOR>
      <SAMPLINGNUMTWOFACTOR>0</SAMPLINGNUMTWOFACTOR>
      <SORTPOSITION> 1000</SORTPOSITION>
      <ALTERID> 139017</ALTERID>
      <DEFAULTLANGUAGE>0</DEFAULTLANGUAGE>
      <RATEOFTAXCALCULATION>0</RATEOFTAXCALCULATION>
      <GRATUITYMONTHDAYS>0</GRATUITYMONTHDAYS>
      <GRATUITYLIMITMONTHS>0</GRATUITYLIMITMONTHS>
      <CALCULATIONBASIS>0</CALCULATIONBASIS>
      <ROUNDINGLIMIT>0</ROUNDINGLIMIT>
      <ABATEMENTPERCENTAGE>0</ABATEMENTPERCENTAGE>
      <TDSDEDUCTEESPECIALRATE>0</TDSDEDUCTEESPECIALRATE>
      <BENEFICIARYCODEMAXLENGTH>0</BENEFICIARYCODEMAXLENGTH>
      <ECHEQUEPRINTLOCATIONVERSION>0</ECHEQUEPRINTLOCATIONVERSION>
      <ECHEQUEPAYABLELOCATIONVERSION>0</ECHEQUEPAYABLELOCATIONVERSION>
      <EDDPRINTLOCATIONVERSION>0</EDDPRINTLOCATIONVERSION>
      <EDDPAYABLELOCATIONVERSION>0</EDDPAYABLELOCATIONVERSION>
      <PAYINSRUNNINGFILENUM>0</PAYINSRUNNINGFILENUM>
      <TRANSACTIONTYPEVERSION>0</TRANSACTIONTYPEVERSION>
      <PAYINSFILENUMLENGTH>0</PAYINSFILENUMLENGTH>
      <SAMPLINGAMTONEFACTOR/>
      <SAMPLINGAMTTWOFACTOR/>
      <OPENINGBALANCE>8242.00</OPENINGBALANCE>
      <CREDITLIMIT/>
      <GRATUITYLIMITAMOUNT/>
      <ODLIMIT/>
      <TEMPGSTCGSTRATE>0</TEMPGSTCGSTRATE>
      <TEMPGSTSGSTRATE>0</TEMPGSTSGSTRATE>
      <TEMPGSTIGSTRATE>0</TEMPGSTIGSTRATE>
      <TEMPISVATFIELDSEDITED/>
      <TEMPAPPLDATE/>
      <TEMPCLASSIFICATION/>
      <TEMPNATURE/>
      <TEMPPARTYENTITY/>
      <TEMPBUSINESSNATURE/>
      <TEMPVATRATE>0</TEMPVATRATE>
      <TEMPADDLTAX>0</TEMPADDLTAX>
      <TEMPCESSONVAT>0</TEMPCESSONVAT>
      <TEMPTAXTYPE/>
      <TEMPMAJORCOMMODITYNAME/>
      <TEMPCOMMODITYNAME/>
      <TEMPCOMMODITYCODE/>
      <TEMPSUBCOMMODITYCODE/>
      <TEMPUOM/>
      <TEMPTYPEOFGOODS/>
      <TEMPTRADENAME/>
      <TEMPGOODSNATURE/>
      <TEMPSCHEDULE/>
      <TEMPSCHEDULESLNO/>
      <TEMPISINVDETAILSENABLE/>
      <TEMPLOCALVATRATE>0</TEMPLOCALVATRATE>
      <TEMPVALUATIONTYPE/>
      <TEMPISCALCONQTY/>
      <TEMPISSALETOLOCALCITIZEN/>
      <LEDISTDSAPPLICABLECURRLIAB/>
      <ISPRODUCTCODEEDITED/>
      <SERVICETAXDETAILS.LIST>      </SERVICETAXDETAILS.LIST>
      <LBTREGNDETAILS.LIST>      </LBTREGNDETAILS.LIST>
      <VATDETAILS.LIST>      </VATDETAILS.LIST>
      <SALESTAXCESSDETAILS.LIST>      </SALESTAXCESSDETAILS.LIST>
      <GSTDETAILS.LIST>      </GSTDETAILS.LIST>
      <LANGUAGENAME.LIST>
       <NAME.LIST TYPE="String">
        <NAME>AJAY  PAREKH (B)</NAME>
       </NAME.LIST>
       <LANGUAGEID> 1033</LANGUAGEID>
      </LANGUAGENAME.LIST>
      <XBRLDETAIL.LIST>      </XBRLDETAIL.LIST>
      <AUDITDETAILS.LIST>      </AUDITDETAILS.LIST>
      <SCHVIDETAILS.LIST>      </SCHVIDETAILS.LIST>
      <EXCISETARIFFDETAILS.LIST>      </EXCISETARIFFDETAILS.LIST>
      <TCSCATEGORYDETAILS.LIST>      </TCSCATEGORYDETAILS.LIST>
      <TDSCATEGORYDETAILS.LIST>      </TDSCATEGORYDETAILS.LIST>
      <SLABPERIOD.LIST>      </SLABPERIOD.LIST>
      <GRATUITYPERIOD.LIST>      </GRATUITYPERIOD.LIST>
      <ADDITIONALCOMPUTATIONS.LIST>      </ADDITIONALCOMPUTATIONS.LIST>
      <EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>
      <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
      <BANKALLOCATIONS.LIST>      </BANKALLOCATIONS.LIST>
      <PAYMENTDETAILS.LIST>      </PAYMENTDETAILS.LIST>
      <BANKEXPORTFORMATS.LIST>      </BANKEXPORTFORMATS.LIST>
      <BILLALLOCATIONS.LIST>
       <BILLDATE>20180331</BILLDATE>
       <YEAREND/>
       <NAME>21-3/17-18</NAME>
       <BILLCREDITPERIOD JD="43189" P="31-Mar-2018">31-Mar-2018</BILLCREDITPERIOD>
       <ISADVANCE>No</ISADVANCE>
       <OPENINGBALANCE>8242.00</OPENINGBALANCE>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <UDF:AJS_BILLALLOC_BROKER.LIST DESC="`AJS_BillAlloc_Broker`" ISLIST="YES" TYPE="String" INDEX="22001">
        <UDF:AJS_BILLALLOC_BROKER DESC="`AJS_BillAlloc_Broker`">ABDUL MALIK (B)</UDF:AJS_BILLALLOC_BROKER>
       </UDF:AJS_BILLALLOC_BROKER.LIST>
      </BILLALLOCATIONS.LIST>
      <INTERESTCOLLECTION.LIST>      </INTERESTCOLLECTION.LIST>
      <LEDGERCLOSINGVALUES.LIST>      </LEDGERCLOSINGVALUES.LIST>
      <LEDGERAUDITCLASS.LIST>      </LEDGERAUDITCLASS.LIST>
      <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
      <TDSEXEMPTIONRULES.LIST>      </TDSEXEMPTIONRULES.LIST>
      <DEDUCTINSAMEVCHRULES.LIST>
       <NATUREOFPAYMENT>&#4; All Items</NATUREOFPAYMENT>
      </DEDUCTINSAMEVCHRULES.LIST>
      <LOWERDEDUCTION.LIST>      </LOWERDEDUCTION.LIST>
      <STXABATEMENTDETAILS.LIST>      </STXABATEMENTDETAILS.LIST>
      <LEDMULTIADDRESSLIST.LIST>      </LEDMULTIADDRESSLIST.LIST>
      <STXTAXDETAILS.LIST>      </STXTAXDETAILS.LIST>
      <CHEQUERANGE.LIST>      </CHEQUERANGE.LIST>
      <DEFAULTVCHCHEQUEDETAILS.LIST>      </DEFAULTVCHCHEQUEDETAILS.LIST>
      <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
      <BRSIMPORTEDINFO.LIST>      </BRSIMPORTEDINFO.LIST>
      <AUTOBRSCONFIGS.LIST>      </AUTOBRSCONFIGS.LIST>
      <BANKURENTRIES.LIST>      </BANKURENTRIES.LIST>
      <DEFAULTCHEQUEDETAILS.LIST>      </DEFAULTCHEQUEDETAILS.LIST>
      <DEFAULTOPENINGCHEQUEDETAILS.LIST>      </DEFAULTOPENINGCHEQUEDETAILS.LIST>
      <CANCELLEDPAYALLOCATIONS.LIST>      </CANCELLEDPAYALLOCATIONS.LIST>
      <ECHEQUEPRINTLOCATION.LIST>      </ECHEQUEPRINTLOCATION.LIST>
      <ECHEQUEPAYABLELOCATION.LIST>      </ECHEQUEPAYABLELOCATION.LIST>
      <EDDPRINTLOCATION.LIST>      </EDDPRINTLOCATION.LIST>
      <EDDPAYABLELOCATION.LIST>      </EDDPAYABLELOCATION.LIST>
      <AVAILABLETRANSACTIONTYPES.LIST>      </AVAILABLETRANSACTIONTYPES.LIST>
      <LEDPAYINSCONFIGS.LIST>      </LEDPAYINSCONFIGS.LIST>
      <TYPECODEDETAILS.LIST>      </TYPECODEDETAILS.LIST>
      <FIELDVALIDATIONDETAILS.LIST>      </FIELDVALIDATIONDETAILS.LIST>
      <INPUTCRALLOCS.LIST>      </INPUTCRALLOCS.LIST>
      <GSTCLASSFNIGSTRATES.LIST>      </GSTCLASSFNIGSTRATES.LIST>
      <EXTARIFFDUTYHEADDETAILS.LIST>      </EXTARIFFDUTYHEADDETAILS.LIST>
      <VOUCHERTYPEPRODUCTCODES.LIST>      </VOUCHERTYPEPRODUCTCODES.LIST>
      <UDF:ISBROKER.LIST DESC="`IsBroker`" ISLIST="YES" TYPE="Logical" INDEX="45">
       <UDF:ISBROKER DESC="`IsBroker`">Yes</UDF:ISBROKER>
      </UDF:ISBROKER.LIST>
     </LEDGER>
    </TALLYMESSAGE>';
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, 'http://111.119.207.78:9002/');  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data ); //$data = "XML data"  
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $response = curl_exec ($ch); 
        // print_r($response); 
        if(curl_errno($ch)){  
        echo curl_error($ch);  
        }  
        else{  
        print "<pre lang='xml'>" . htmlspecialchars($response) . "</pre>";  
        curl_close($ch);  
        }    
        return "";  
        }

  function tally_to_erp($type){
    $xml='<ENVELOPE>
    <HEADER>
    <VERSION>1</VERSION>
    <TALLYREQUEST>EXPORT</TALLYREQUEST>
    <TYPE>COLLECTION</TYPE>
    <ID>Remote Ledger Coll</ID>
    </HEADER>
    <BODY>
    <DESC>
    <STATICVARIABLES>
    <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
    </STATICVARIABLES>
    <TDL>
    <TDLMESSAGE>
    <COLLECTION NAME="Remote Ledger Coll"
    ISINITIALIZE="Yes">
    <TYPE>'.$type.'</TYPE>
    <NATIVEMETHOD>ledger</NATIVEMETHOD>
    <NATIVEMETHOD>email</NATIVEMETHOD>
    <NATIVEMETHOD>address</NATIVEMETHOD>
    <NATIVEMETHOD>PINCODE</NATIVEMETHOD>
    <NATIVEMETHOD>guid</NATIVEMETHOD>
    <NATIVEMETHOD>emailcc</NATIVEMETHOD>
    <NATIVEMETHOD>name</NATIVEMETHOD>
    <NATIVEMETHOD>PARTYGSTIN</NATIVEMETHOD>
    <NATIVEMETHOD>CREATEDDATE</NATIVEMETHOD>
    <NATIVEMETHOD>ALTEREDON</NATIVEMETHOD>
    <NATIVEMETHOD>LEDGERMOBILE</NATIVEMETHOD>
    <NATIVEMETHOD>PARENT</NATIVEMETHOD>

    </COLLECTION>
    </TDLMESSAGE>
    </TDL>
    </DESC>
    </BODY>
    </ENVELOPE>';

    $tallyDatas = $this->admin_tally($xml);
    // echo "hi";exit;
   echo '<pre>';
    print_r($tallyDatas);exit;
    $data['ledgerAccounts'] = [];
    if($tallyDatas['status']){
      //echo '<pre>';
      //print_r($tallyDatas['data']);exit;
      foreach ($tallyDatas['data'] as $key => $tally) {
        if(is_array($tally)){
          if(isset($tally['tag']) && strtolower($tally['tag'])=='ledger' && isset($tally["attributes"]["NAME"]) && !in_array($tally["attributes"]["NAME"]/*, $data['ledgerAccounts']*/)){
            echo '<pre>';
            print_r($tally);
            $ledgerNames[] = $tally["attributes"]["NAME"];
          }
        }
      }
    }
  }

  function testdata(){
    $xml='<ENVELOPE>
<HEADER>
<TALLYREQUEST>Export Data</TALLYREQUEST>
</HEADER>
<BODY>
<EXPORTDATA>
<REQUESTDESC>
<STATICVARIABLES>
</STATICVARIABLES>

<!--Specify the Report Name here-->
<REPORTNAME>Sales Order</REPORTNAME>

</REQUESTDESC>
</EXPORTDATA>
</BODY>
</ENVELOPE>';

    $tallyDatas = $this->admin_tally($xml);
    echo '<pre>';
    //echo "hi";exit;
    print_r($tallyDatas);exit;
    $data['ledgerAccounts'] = [];
    if($tallyDatas['status']){
      //echo '<pre>';
      //print_r($tallyDatas['data']);exit;
      foreach ($tallyDatas['data'] as $key => $tally) {
        if(is_array($tally)){
          if(isset($tally['tag']) && strtolower($tally['tag'])=='ledger' && isset($tally["attributes"]["NAME"]) && !in_array($tally["attributes"]["NAME"]/*, $data['ledgerAccounts']*/)){
            echo '<pre>';
            print_r($tally);
            $ledgerNames[] = $tally["attributes"]["NAME"];
          }
        }
      }
    }
  }

  function export_tally_ledger(){
        if(date('H')<11 || date('H')>21 || date('D') == 'Sun')
        {
            return true;
        }
        if(date('D') == 'Sun') { 
          return true;
        }
      $xml = '<ENVELOPE>
          <HEADER>
            <VERSION>1</VERSION>
            <TALLYREQUEST>EXPORT</TALLYREQUEST>
            <TYPE>COLLECTION</TYPE>
            <ID>Remote Ledger Coll</ID>
          </HEADER>
          <BODY>
            <DESC>
              <STATICVARIABLES>
              <SVEXPORTFORMAT>\$\$SysName:XML</SVEXPORTFORMAT>
              </STATICVARIABLES>
              <TDL>
                <TDLMESSAGE>
                  <COLLECTION NAME="Remote Ledger Coll"
                  ISINITIALIZE="Yes">
                    <TYPE>LEDGER</TYPE>
                    <NATIVEMETHOD>PARENT</NATIVEMETHOD>
                    <NATIVEMETHOD>ledger</NATIVEMETHOD>
                    <NATIVEMETHOD>email</NATIVEMETHOD>
                    <NATIVEMETHOD>ADDRESS</NATIVEMETHOD>
                    <NATIVEMETHOD>pincode</NATIVEMETHOD>
                    <NATIVEMETHOD>guid</NATIVEMETHOD>
                    <NATIVEMETHOD>emailcc</NATIVEMETHOD>
                    <NATIVEMETHOD>name</NATIVEMETHOD>
                    <NATIVEMETHOD>PARTYGSTIN</NATIVEMETHOD>
                    <NATIVEMETHOD>CREATEDDATE</NATIVEMETHOD>
                    <NATIVEMETHOD>ALTEREDON</NATIVEMETHOD>
                    <NATIVEMETHOD>LEDGERMOBILE</NATIVEMETHOD>
                    <NATIVEMETHOD>LEDSTATENAME</NATIVEMETHOD>
                    <NATIVEMETHOD>COUNTRYOFRESIDENCE</NATIVEMETHOD>
                    <NATIVEMETHOD>ALTERID</NATIVEMETHOD>
                    <NATIVEMETHOD>OPENINGBALANCE</NATIVEMETHOD>
                    <NATIVEMETHOD>INCOMETAXNUMBER</NATIVEMETHOD>
                  </COLLECTION>
                </TDLMESSAGE>
              </TDL>
            </DESC>
          </BODY>
        </ENVELOPE>';
  
        $error = [];
        $sundrycreditor = [];
        $res = $this->curl_handling($xml);
        //print_r($res);exit();
        //echo "<pre>";
        $typeMapping = $this->usertype_mapping();
        //echo '<pre>';print_r($typeMapping);exit;
        $this->pktdblib->set_table('tally_ledger');
        $ledger = $this->pktdblib->get('ledger_name');
        $ledgerAccounts = [];
        //print_r($ledger->result_array());
        foreach ($ledger->result_array() as $key => $account) {
          $ledgerAccounts[] = $account['ledger_name'];
        }

        $loopCount = 0;
        //print_r($ledgerAccounts);exit();
        foreach ((array)$res->BODY->DATA->COLLECTION as $colKey => $collection) {
          //echo $colKey.'<br>';
          if($colKey!=='@attributes'){
            $collection = json_decode(json_encode($collection, true));
            foreach ($collection as $ledgerKey => $ledger) {
              $ledger = (array)$ledger;
              if(!array_key_exists($ledger['PARENT'], $typeMapping)){
                $typeMapping[$ledger['PARENT']] = 'na';
                continue;
              }
              if($typeMapping[$ledger['PARENT']]=='vendors'){
                    //continue;
              }
              if(in_array(isset($ledger['NAME'])?$ledger['NAME']:$ledger['@attributes']->NAME, $ledgerAccounts)){
                //echo "data present ".$ledgerKey.'<br>';
                continue;
              }
              if($loopCount>=25){
                break;
              }

              $userData = [];
              $userEmail = [];
              $tallyLedgerArray = [];
             // print_r($ledger);exit();

              $counter = isset($sundrycreditor[$typeMapping[$ledger['PARENT']]])?count($sundrycreditor[$typeMapping[$ledger['PARENT']]]):0;
              //echo $ledger->PARENT.'<br>';
              $sundrycreditor[$typeMapping[$ledger['PARENT']]][$counter] = $ledger;
              //print_r($typeMapping[$ledger['PARENT']]);//exit;p
              $userData['first_name'] = isset($ledger['NAME'])?$ledger['NAME']:$ledger['@attributes']->NAME;
              $tallyLedgerArray['ledger_name'] = $userData['first_name'];
              $tallyLedgerArray['account_type'] = $typeMapping[$ledger['PARENT']]; 

              if($typeMapping[$ledger['PARENT']]=== 'na'){
                $tallyLedgerArray['user_id']  = 0;
              }else{
                $addressArray = ['type'=>$typeMapping[$ledger['PARENT']]];
                //print_r($ledger['@attributes']);exit();
                $userData['primary_email'] = isset($ledger['EMAIL'])?$ledger['EMAIL']:NULL;
                $userData['contact_1'] = isset($ledger['PHONE'])?$ledger['PHONE']:'';
                $userData['gst_no'] = isset($ledger['PARTYGSTIN'])?$ledger['PARTYGSTIN']:'';
                $userData['pan_no'] = isset($ledger['INCOMETAXNUMBER'])?$ledger['INCOMETAXNUMBER']:'';
                $userData['created'] = $userData['modified']=date('Y-m-d H:i:s');
                // print_r($typeMapping[$ledger['PARENT']]);exit;
                // print_r($userData);exit;
                if($typeMapping[$ledger['PARENT']] == 'employees'){
                  unset($userData['gst_no']);
                }else{
                  $userData['company_name'] = $userData['first_name'];
                }
                $response=json_decode(Modules::run($typeMapping[$ledger['PARENT']].'/_register_admin_add', $userData), true);
                //print_r($response[$typeMapping[$ledger['PARENT']]]);
                //print_r($response);//exit;
                $addressArray['user_id'] = $tallyLedgerArray['user_id'] = $response[$typeMapping[$ledger['PARENT']]]['id'];

                //print_r($response[$typeMapping[$ledger['PARENT']]]['id']);exit;
                $loginArray = [];
                /*if(isset($userData['primary_email']) && !empty($userData['primary_email'])){
                  $loginArray= $userData;
                  $loginArray['id'] = $response[$typeMapping[$ledger['PARENT']]]['id'];
                  $loginArray['account_type'] = $typeMapping[$ledger['PARENT']];
                  $loginArray['surname'] = '';
                  $addressArray['type'] = 'login';
                  $login = [];
                  if($loginArray['account_type'] =='vendors'){

                    $login = json_decode(Modules::run('login/register_vendor_to_login', $loginArray), true);
                  }
                  else if($loginArray['account_type'] =='customers'){

                    $login = json_decode(Modules::run('login/register_customer_to_login', $loginArray), true);

                  }
                  else if($loginArray['account_type'] =='employees'){

                    $login = json_decode(Modules::run('login/register_employee_to_login', $loginArray), true);

                  }
                  else if($loginArray['account_type'] =='brokers'){

                    $login = json_decode(Modules::run('login/register_broker_to_login', $loginArray), true);

                  }
                  $addressArray['user_id'] = $login['id'];
                  $addressArray['type'] = 'login';
                }*/
                if(isset($ledger['EMAIL']))
                {
                  $userEmail[] = $ledger['EMAIL'];
                }
                //print_r($userEmail);
            
                if (isset($ledger['ADDRESS.LIST'])) {
                  $str = '';
                  $ledger['ADDRESS.LIST'] = (array)$ledger['ADDRESS.LIST'];
                  //print_r($ledger['ADDRESS.LIST']);
                  if (is_array($ledger['ADDRESS.LIST']['ADDRESS'])) {
                    $str = implode(" ",$ledger['ADDRESS.LIST']['ADDRESS']);
                  }
                  if(trim($str)!=''){
                    
                    $addressArray['address_1'] = $str;
                    $addressArray['tally_address'] = $str;
                    if(isset($ledger['LEDSTATENAME'])){
                      $this->pktdblib->set_table('states');
                      $query = $this->pktdblib->get_where_custom('state_name', $ledger['LEDSTATENAME']);
                      if($query->num_rows()){
                        $state = $query->row_array();
                        $addressArray['state_id'] = $state['id'];
                      }
                    }
                    $addressArray['country_id'] = 1;
                    $addressArray['created'] = $addressArray['modified'] = date('Y-m-d H:i:s');
                    $addressArray['site_name'] = 'Billing Address';
                    $this->pktdblib->set_table('address');
                    $insAddress = $this->pktdblib->_insert($addressArray);

                  }else{
                    unset($addressArray);
                  }
                }
              
              }
              $tallyLedgerArray['is_active'] = 1;
              $tallyLedgerArray['created'] = $tallyLedgerArray['modified'] = date('Y-m-d H:i:s');
              $this->pktdblib->set_table('tally_ledger');
              $insTallyLedger = $this->pktdblib->_insert($tallyLedgerArray);
              $loopCount = ++$loopCount;
              echo '<pre>';print_r($tallyLedgerArray);
            }
          }
        } 
  }

  function usertype_mapping(){
    /*$typeMapping = [
      'SUNDRY CREDITOR-ABB'=>'vendors',
      'Sundry Creditors'=>'vendors',
      'COMMUNICATION EXPENSES'=>'na',
      'ADVANCE TO CREDITORS'=>'na',
      'Sundry Creditors - Brokers Debit Balance'=>'na',
      'OFFICE EQUIPMENT'=>'na',
      'Sundry Debtors - MARKET'=>'customers',
      'SUNDRY CREDITORS-BROKER'=>'brokers',
      'S'=>'na',
      'Goods on  Consignment (Stock Trfr)'=>'na',
      'Sundry Debtors'=>'customers',
      'Sundry Creditors for Exp. - Brokers'=>'na',
      'Sundry Creditor Expenses New'=>'na',
      'Sundry Debtors - CONSUMER'=>'customers',
      'PLANT & MACHINERY'=>'na',
      'REBATE & DISCOUNT'=>'na',
      'Salary Account - Staff'=>'employees',
      'ADVANCED TAX ASS YR 11-12'=>'na',
      'ADVANCED TAX ASS YR 12-13'=>'na',
      'ADVANCED TAX ASS YR 14-15'=>'na',
      'ADVANCED TAX ASS YR 16-17'=>'na',
      'ADVANCED TAX ASS YR. 17-18'=>'na',
      'ADVANCED TAX ASS YR. 18-19'=>'na',
      'ADVANCED TAX ASS YR. 19-20'=>'na',
      'BUSINESS PROMOTION'=>'na',
      'Legal & Professional Expenses-194J'=>'na',
      'MARGIN A/C - Nett'=>'na',
      'Staff Loan'=>'na',
      'Staff Advances Against Expenses'=>'na',
      'Staff Salary Advance'=>'na',
      'Diwali Bonus'=>'na',
      'LEAVE SALARY TO STAFF-192B'=>'na',
      'IMPORT PURCHASE'=>'na',
      'LOAN LIABILITY -DEPOSIT'=>'na',
      'Sundry Debtors (Rent)'=>'na',
      'AIR CONDITIONER'=>'na',
      'OTHER CURRENT LIABILITY'=>'na',
      'SHORT TERM LOAN & ADVANCES'=>'na',
      'Long Term Loan & Advances'=>'na',
      'Unsecured Loan'=>'na',
      'SHARE  APPLICATION MONEY PENDING ALLOTMMENT'=>'na',
      'SHORT TERM LOANS & ADVANCES'=>'na',
      'BRANCH/DIVISION'=>'na',
      'Advance  From Customer'=>'na',
      'Sundry Creditors - Import'=>'na',
      'SUNDRY CREDITORS OTHERS'=>'na',
      'DIRECTORS LOAN'=>'na',
      'DIRECTORS REMUNERATION - 192B'=>'na',
      'CORPORATE  LOANS'=>'na',
      'BANK CURRENT ACCOUNTS'=>'na',
      'Bank OD A/c'=>'na',
      'FIXED DEPOSIT - ANDHRA'=>'na',
      'Collatral FDR with Bank'=>'na',
      'Sundry Drs'=>'customers',
      'RENT REPAIR & MAINTANCE CHGS'=>'na',
      'Penalty Dis. Allowance'=>'na',
      'Other Long-Term  Liability'=>'na',
      'SECURITY DEPOSIT'=>'na',
      'GENERAL &  OFFICE EXPENSES'=>'na',
      'AUDITORS REMUNERATION - 194J'=>'na',
      'OTHER EXPENSES'=>'na',
      'Car Loan'=>'na',
      'OTHER LOAN & ADVANCES'=>'na',
      'Bank LC Discounting Charges(Domestic)'=>'na',
      'Bank LC Discounting Charges(Foriegn)'=>'na',
      'Bank & Other Interest Paid'=>'na',
      'L.C.ACCPETANCE ( DOMESTIC)'=>'na',
      'DONATIONS & CSR'=>'na',
      'STAFF BONUS'=>'na',
      'BROKERAGE & COMMISSIONS - 194H'=>'na',
      'COMPUTER & PRINTER'=>'na',
      'VEHICLE &  MAINTAINANCE (GST EXEMPTED)'=>'na',
      'Cash-in-hand'=>'na',
      'O'=>'na',
      'GST ON EXPENCES'=>'na',
      'GST (2018-19)'=>'na',
      'VAT / CST/TDS/GST EXPENSES'=>'na',
      'Bank Accounts'=>'na',
      'Interest and Borrowing Cost'=>'na',
      'ADMINISTRATIVE EXPENSES'=>'na',
      'COMPUTER EXPENSES'=>'na',
      'BUILDINGS'=>'na',
      'TDS APPLICABLE- 194J'=>'na',
      'TRAVELLING EXPS.'=>'na',
      'Legal & Professional Expenses'=>'na',
      'LABOUR CHARGES-194C'=>'na',
      'VAT COLLECTED ON SALE'=>'na',
      'Purchase Accounts--Audit'=>'na',
      'VAT EXPENSES'=>'na',
      'Provisions'=>'na',
      'Provision for Duties & Taxes'=>'na',
      'OTHER FINANCE CHARGES'=>'na',
      'DEFFERED TAX ASSET/LIABILITY'=>'na',
      'DERIVATIVES'=>'na',
      'DEPRECIATION'=>'na',
      'Reserves & Surplus'=>'na',
      'OTHER RECEIVABLES'=>'na',
      'Other Receivable'=>'na',
      'LC PAYABLE - BOI'=>'na',
      'LC PAYABLE - BOM'=>'na',
      'LC PAYABLE - IOB'=>'na',
      'LC PAYABLE - OBC'=>'na',
      'LC PAYABLE - UBI'=>'na',
      'Indirect Incomes'=>'na',
      'Secured Loan'=>'na',
      'Short Term Secured Loans'=>'na',
      'Electricity Expenses (GST EXEMPTED)'=>'na',
      'PROVISION FOR EXPENSES'=>'na',
      'Sales Accounts'=>'na',
      'TDS LIAB - 2015-16'=>'na',
      'FIXED DEPOSIT-BOI'=>'na',
      'FIXED DEPOSIT - BOM'=>'na',
      'FIXED DEPOSIT - CBI'=>'na',
      'FIXED DEPOSIT - IOB'=>'na',
      'FIXED DEPOSIT - LVB'=>'na',
      'FIXED DEPOSIT - OBC'=>'na',
      'FIXED DEPOSIT - SBBJ'=>'na',
      'FIXED DEPOSIT - SBI'=>'na',
      'FIXED DEPOSIT-SOUTH INDIAN BANK'=>'na',
      'FIXED DEPOSIT TMB'=>'na',
      'FIXED DEPOSIT - UBI'=>'na',
      'Professional Tax'=>'na',
      'FOREIGN EXCHANGE GAIN/ LOSS'=>'na',
      'FREIGHT , LOADING & UNLOADING CHGS.'=>'na',
      'Indirect Expenses'=>'na',
      'FUR'=>'na',
      'FURNITURE & FIXTURES'=>'na',
      'FORWARD PREMIUM PAYABLE'=>'na',
      'Advance Recevable in Cash Or Kind'=>'na',
      'INVESTMENTS'=>'na',
      'INVESTMENT FOR COLLATRAL SECURETY IN BANK'=>'na',
      'Duties & Taxes'=>'na',
      'Miraj Metals'=>'na',
      'MOTOR CAR'=>'na',
      'Interest From Customer'=>'na',
      'Interest Recd.'=>'na',
      'Insurance Charges'=>'na',
      'FINANCE CHARGES'=>'na',
      'INTEREST ON INCOME TAX REFUND'=>'na',
      'SERVICE TAX/ SBC / KKC'=>'na',
      'INTEREST RECEIVABLE'=>'na',
      'INTEREST ON FDR (SBBJ)'=>'na',
      'FAMILY MEMEBRS'=>'na',
      'SUNDRY CREDITORS ( Usance Interest )'=>'na',
      'Commodity Trading A/c'=>'na',
      'SERVICE TAX LIABILITY'=>'na',
      'Sahyog'=>'na',
      'L.C.ACCEPTANCE-FOREIGN'=>'na',
      'L.C.PAYABLE-SOUTH INDIAN'=>'na',
      'NON TDS - 194J'=>'na',
      'PROFIT OR LOSS ON CURRENCY'=>'na',
      'PROFIT OR LOSS ON CURRENCY - F & O'=>'na',
      'PROFIT OR LOSS ON LUTUAL FUNDS (Disallowed)'=>'na',
      'Purch-Rate Diffrence'=>'na',
      'Buildings Staff'=>'na',
      'BUILDINGS - STAFF QUARTER'=>'na',
      'DISALLOWABLE EXPENSES'=>'na',
      'ADVANCE FOR PROPERTY'=>'na',
      'MOBILE'=>'na',
      'VAT (2017-18)'=>'na',
      'VAT LIABILITY 2017-18'=>'na',
      'MOTOR CYCLE'=>'na',
      'Other Bank Charges'=>'na',
      'Currency Futures'=>'na',
      'S.T.LOANS & ADVANCES'=>'na',
      'PREPAID EXPENSES'=>'na',
      'PRINTING & STATIONERY'=>'na',
      'STAFF WELFARE'=>'na',
      'RENT , RATES & TAXES-194I'=>'na',
      'Primary'=>'na',
      'Currency Options'=>'na',
      'Long Term Provision'=>'na',
      'ADVANCED TAX ASS YR 15-16'=>'na',
      'ADVANCED TAX ASS YR 13-14'=>'na',
      'Sundry Misc. Expenses'=>'na',
      'SALE -RATE DIFF'=>'na',
      'BANK RECURRING DEPOSIT'=>'na',
      'Currency A/c-Dhwaja'=>'na',
      'OTHER CURRENT ASSET'=>'na',
      'RENT RECEIVED'=>'na',
      'REPAIR & MAINTANCE CHAGES'=>'na',
      'SAD (SPL ADDL DUTY)RECEIVABLE A/C.'=>'na',
      'STAFF COST'=>'na',
      'Short Term Borrowings'=>'na',
      'SHARE CAPITAL ACCOUNT'=>'na',
      'Other Investments'=>'na',
      'SHORT OR EXCESS PROVISION'=>'na',
      'CAPITAL GAIN'=>'na',
      'CSR EXP - DISALLOW'=>'na',
      'VEHICLE & MOTOR CARS'=>'na',
      'Speculative Profit'=>'na',
      'SHARE ISSUE EXPENSES'=>'na',
      'Processing Charges'=>'na',
      'Loans (Liability)'=>'na',
      'Stock-in-hand'=>'na',
      'WAREHOUSING CHGS-194C'=>'na',
      'COMMODITY EXCHANGE - DEPOSIT'=>'na',
      'Sundry Debtors-Abb'=>'na',
      'OTHER LIABILITIES'=>'na',
      'SUSPENSE A/C'=>'na',
      'TCS LIABILITY'=>'na',
      'TDS LIAB - 194C'=>'na',
      'TDS LIAB - 194H'=>'na',
      'TDS LIABILITY'=>'na',
      'TDS LIAB - 194J'=>'na',
      'TDS LIAB - 192B'=>'na',
      'TDS LIAB - 194 I'=>'na',
      'CURRENT LIABILITIES & PROVISION'=>'na',
      'FIXED ASSETS'=>'na',
      'Direct Expenses'=>'na',
      'Tirupati Asscociates'=>'na',
      'Other Expense'=>'na',
      'TRANSPORT CHARGES - 194C'=>'na',
      'INVESTMENT - 14A'=>'na',
      'VAT LIABILITY'=>'na'
    ];

    return $typeMapping;*/
    $typeMapping = [
      'SUNDRY CREDITOR-ABB'=>'vendors',
      'Sundry Creditors'=>'vendors',
      'COMMUNICATION EXPENSES'=>'na',
      'ADVANCE TO CREDITORS'=>'na',
      'Sundry Creditors - Brokers Debit Balance'=>'na',
      'OFFICE EQUIPMENT'=>'na',
      'Sundry Debtors - MARKET'=>'customers',
      'SUNDRY CREDITORS-BROKER'=>'brokers',
      'CREDITORS FOR BROKERAGE' => 'brokers',
      'S'=>'na',
      'Goods on  Consignment (Stock Trfr)'=>'na',
      'Sundry Debtors'=>'customers',
      'Sundry Creditors for Exp. - Brokers'=>'na',
      'Sundry Creditor Expenses New'=>'na',
      'Sundry Debtors - CONSUMER'=>'customers',
      'PLANT & MACHINERY'=>'na',
      'REBATE & DISCOUNT'=>'na',
      'Salary Account - Staff'=>'employees',
      'SALARY'=>'employees',
      'SALARY (TALOJA)'=>'employees',
      'ADVANCED TAX ASS YR 11-12'=>'na',
      'ADVANCED TAX ASS YR 12-13'=>'na',
      'ADVANCED TAX ASS YR 14-15'=>'na',
      'ADVANCED TAX ASS YR 16-17'=>'na',
      'ADVANCED TAX ASS YR. 17-18'=>'na',
      'ADVANCED TAX ASS YR. 18-19'=>'na',
      'ADVANCED TAX ASS YR. 19-20'=>'na',
      'BUSINESS PROMOTION'=>'na',
      'Legal & Professional Expenses-194J'=>'na',
      'MARGIN A/C - Nett'=>'na',
      'Staff Loan'=>'na',
      'Staff Advances Against Expenses'=>'na',
      'Staff Salary Advance'=>'na',
      'Diwali Bonus'=>'na',
      'LEAVE SALARY TO STAFF-192B'=>'na',
      'IMPORT PURCHASE'=>'na',
      'LOAN LIABILITY -DEPOSIT'=>'na',
      'Sundry Debtors (Rent)'=>'na',
      'AIR CONDITIONER'=>'na',
      'OTHER CURRENT LIABILITY'=>'na',
      'SHORT TERM LOAN & ADVANCES'=>'na',
      'Long Term Loan & Advances'=>'na',
      'Unsecured Loan'=>'na',
      'SHARE  APPLICATION MONEY PENDING ALLOTMMENT'=>'na',
      'SHORT TERM LOANS & ADVANCES'=>'na',
      'BRANCH/DIVISION'=>'na',
      'Advance  From Customer'=>'na',
      'Sundry Creditors - Import'=>'na',
      'SUNDRY CREDITORS OTHERS'=>'na',
      'DIRECTORS LOAN'=>'na',
      'DIRECTORS REMUNERATION - 192B'=>'na',
      'CORPORATE  LOANS'=>'na',
      'BANK CURRENT ACCOUNTS'=>'na',
      'Bank OD A/c'=>'na',
      'FIXED DEPOSIT - ANDHRA'=>'na',
      'Collatral FDR with Bank'=>'na',
      'Sundry Drs'=>'customers',
      'RENT REPAIR & MAINTANCE CHGS'=>'na',
      'Penalty Dis. Allowance'=>'na',
      'Other Long-Term  Liability'=>'na',
      'SECURITY DEPOSIT'=>'na',
      'GENERAL &  OFFICE EXPENSES'=>'na',
      'AUDITORS REMUNERATION - 194J'=>'na',
      'OTHER EXPENSES'=>'na',
      'Car Loan'=>'na',
      'OTHER LOAN & ADVANCES'=>'na',
      'Bank LC Discounting Charges(Domestic)'=>'na',
      'Bank LC Discounting Charges(Foriegn)'=>'na',
      'Bank & Other Interest Paid'=>'na',
      'L.C.ACCPETANCE ( DOMESTIC)'=>'na',
      'DONATIONS & CSR'=>'na',
      'STAFF BONUS'=>'na',
      'BROKERAGE & COMMISSIONS - 194H'=>'na',
      'COMPUTER & PRINTER'=>'na',
      'VEHICLE &  MAINTAINANCE (GST EXEMPTED)'=>'na',
      'Cash-in-hand'=>'na',
      'O'=>'na',
      'GST ON EXPENCES'=>'na',
      'GST (2018-19)'=>'na',
      'VAT / CST/TDS/GST EXPENSES'=>'na',
      'Bank Accounts'=>'na',
      'Interest and Borrowing Cost'=>'na',
      'ADMINISTRATIVE EXPENSES'=>'na',
      'COMPUTER EXPENSES'=>'na',
      'BUILDINGS'=>'na',
      'TDS APPLICABLE- 194J'=>'na',
      'TRAVELLING EXPS.'=>'na',
      'Legal & Professional Expenses'=>'na',
      'LABOUR CHARGES-194C'=>'na',
      'VAT COLLECTED ON SALE'=>'na',
      'Purchase Accounts--Audit'=>'na',
      'VAT EXPENSES'=>'na',
      'Provisions'=>'na',
      'Provision for Duties & Taxes'=>'na',
      'OTHER FINANCE CHARGES'=>'na',
      'DEFFERED TAX ASSET/LIABILITY'=>'na',
      'DERIVATIVES'=>'na',
      'DEPRECIATION'=>'na',
      'Reserves & Surplus'=>'na',
      'OTHER RECEIVABLES'=>'na',
      'Other Receivable'=>'na',
      'LC PAYABLE - BOI'=>'na',
      'LC PAYABLE - BOM'=>'na',
      'LC PAYABLE - IOB'=>'na',
      'LC PAYABLE - OBC'=>'na',
      'LC PAYABLE - UBI'=>'na',
      'Indirect Incomes'=>'na',
      'Secured Loan'=>'na',
      'Short Term Secured Loans'=>'na',
      'Electricity Expenses (GST EXEMPTED)'=>'na',
      'PROVISION FOR EXPENSES'=>'na',
      'Sales Accounts'=>'na',
      'TDS LIAB - 2015-16'=>'na',
      'FIXED DEPOSIT-BOI'=>'na',
      'FIXED DEPOSIT - BOM'=>'na',
      'FIXED DEPOSIT - CBI'=>'na',
      'FIXED DEPOSIT - IOB'=>'na',
      'FIXED DEPOSIT - LVB'=>'na',
      'FIXED DEPOSIT - OBC'=>'na',
      'FIXED DEPOSIT - SBBJ'=>'na',
      'FIXED DEPOSIT - SBI'=>'na',
      'FIXED DEPOSIT-SOUTH INDIAN BANK'=>'na',
      'FIXED DEPOSIT TMB'=>'na',
      'FIXED DEPOSIT - UBI'=>'na',
      'Professional Tax'=>'na',
      'FOREIGN EXCHANGE GAIN/ LOSS'=>'na',
      'FREIGHT , LOADING & UNLOADING CHGS.'=>'na',
      'Indirect Expenses'=>'na',
      'FUR'=>'na',
      'FURNITURE & FIXTURES'=>'na',
      'FORWARD PREMIUM PAYABLE'=>'na',
      'Advance Recevable in Cash Or Kind'=>'na',
      'INVESTMENTS'=>'na',
      'INVESTMENT FOR COLLATRAL SECURETY IN BANK'=>'na',
      'Duties & Taxes'=>'na',
      'Miraj Metals'=>'na',
      'MOTOR CAR'=>'na',
      'Interest From Customer'=>'na',
      'Interest Recd.'=>'na',
      'Insurance Charges'=>'na',
      'FINANCE CHARGES'=>'na',
      'INTEREST ON INCOME TAX REFUND'=>'na',
      'SERVICE TAX/ SBC / KKC'=>'na',
      'INTEREST RECEIVABLE'=>'na',
      'INTEREST ON FDR (SBBJ)'=>'na',
      'FAMILY MEMEBRS'=>'na',
      'SUNDRY CREDITORS ( Usance Interest )'=>'na',
      'Commodity Trading A/c'=>'na',
      'SERVICE TAX LIABILITY'=>'na',
      'Sahyog'=>'na',
      'L.C.ACCEPTANCE-FOREIGN'=>'na',
      'L.C.PAYABLE-SOUTH INDIAN'=>'na',
      'NON TDS - 194J'=>'na',
      'PROFIT OR LOSS ON CURRENCY'=>'na',
      'PROFIT OR LOSS ON CURRENCY - F & O'=>'na',
      'PROFIT OR LOSS ON LUTUAL FUNDS (Disallowed)'=>'na',
      'Purch-Rate Diffrence'=>'na',
      'Buildings Staff'=>'na',
      'BUILDINGS - STAFF QUARTER'=>'na',
      'DISALLOWABLE EXPENSES'=>'na',
      'ADVANCE FOR PROPERTY'=>'na',
      'MOBILE'=>'na',
      'VAT (2017-18)'=>'na',
      'VAT LIABILITY 2017-18'=>'na',
      'MOTOR CYCLE'=>'na',
      'Other Bank Charges'=>'na',
      'Currency Futures'=>'na',
      'S.T.LOANS & ADVANCES'=>'na',
      'PREPAID EXPENSES'=>'na',
      'PRINTING & STATIONERY'=>'na',
      'STAFF WELFARE'=>'na',
      'RENT , RATES & TAXES-194I'=>'na',
      'Primary'=>'na',
      'Currency Options'=>'na',
      'Long Term Provision'=>'na',
      'ADVANCED TAX ASS YR 15-16'=>'na',
      'ADVANCED TAX ASS YR 13-14'=>'na',
      'Sundry Misc. Expenses'=>'na',
      'SALE -RATE DIFF'=>'na',
      'BANK RECURRING DEPOSIT'=>'na',
      'Currency A/c-Dhwaja'=>'na',
      'OTHER CURRENT ASSET'=>'na',
      'RENT RECEIVED'=>'na',
      'REPAIR & MAINTANCE CHAGES'=>'na',
      'SAD (SPL ADDL DUTY)RECEIVABLE A/C.'=>'na',
      'STAFF COST'=>'na',
      'Short Term Borrowings'=>'na',
      'SHARE CAPITAL ACCOUNT'=>'na',
      'Other Investments'=>'na',
      'SHORT OR EXCESS PROVISION'=>'na',
      'CAPITAL GAIN'=>'na',
      'CSR EXP - DISALLOW'=>'na',
      'VEHICLE & MOTOR CARS'=>'na',
      'Speculative Profit'=>'na',
      'SHARE ISSUE EXPENSES'=>'na',
      'Processing Charges'=>'na',
      'Loans (Liability)'=>'na',
      'Stock-in-hand'=>'na',
      'WAREHOUSING CHGS-194C'=>'na',
      'COMMODITY EXCHANGE - DEPOSIT'=>'na',
      'Sundry Debtors-Abb'=>'na',
      'OTHER LIABILITIES'=>'na',
      'SUSPENSE A/C'=>'na',
      'TCS LIABILITY'=>'na',
      'TDS LIAB - 194C'=>'na',
      'TDS LIAB - 194H'=>'na',
      'TDS LIABILITY'=>'na',
      'TDS LIAB - 194J'=>'na',
      'TDS LIAB - 192B'=>'na',
      'TDS LIAB - 194 I'=>'na',
      'CURRENT LIABILITIES & PROVISION'=>'na',
      'FIXED ASSETS'=>'na',
      'Direct Expenses'=>'na',
      'Tirupati Asscociates'=>'na',
      'Other Expense'=>'na',
      'TRANSPORT CHARGES - 194C'=>'na',
      'INVESTMENT - 14A'=>'na',
      'VAT LIABILITY'=>'na'
    ];

    return $typeMapping;
  }
  
  function tally_reverseledger_mapping(){

    $tallyMapping = [
      'vendors'=>'Sundry Creditors',
      'customers'=>'ERP Debtor2',
      'brokers'=>'CREDITORS FOR BROKERAGE',
      'employees'=>'SALARY'
    ];

    return $tallyMapping;

  }

    function tally_sales_order($xml=''){
    if($xml==''){
      $xml = '<ENVELOPE>
      <HEADER>
      <TALLYREQUEST>Export Data</TALLYREQUEST>
      </HEADER>
      <BODY>
      <EXPORTDATA>
      <REQUESTDESC>
      <STATICVARIABLES>
      <SVFROMDATE>20191001</SVFROMDATE>
      <SVTODATE>20191001</SVTODATE>
      <!--Theis will show the User name who created Voucher-->
      <SHOWCREATEDBY>YES</SHOWCREATEDBY>
      <SHOWPARTYNAME>Yes</SHOWPARTYNAME>
      <!--Specify the Voucher Type here-->
      <!-- Ex . Sales/Sale Export -->
      <VOUCHERTYPENAME>Sales Order</VOUCHERTYPENAME>
      </STATICVARIABLES>
      <!--Specify the Report Name here-->
      <REPORTNAME>Voucher Register</REPORTNAME>
      <STATICVARIABLES>
          <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
      </STATICVARIABLES>
      </REQUESTDESC>
      </EXPORTDATA>
      </BODY>
      </ENVELOPE>';
    }
    $res = $this->curl_handling($xml);
    //$res = $this->sample_salesorder();
    echo '<pre>';
    print_r($res);exit;
    $orderData = [];
    $productData = [];
    $order = [];
    $error = [];
    $orderDetail = [];
    $orderDetailCounter = 0;
    //echo "<pre>";
    //print_r($res->BODY->IMPORTDATA->REQUESTDATA);exit;
    $id = 0;
    $flag = 0;
    foreach ((array)$res->BODY->IMPORTDATA->REQUESTDATA as $soKey => $salesorder) {
      if($soKey!=='@attributes'){
        $salesorder = json_decode(json_encode($salesorder, true));
        foreach ((array)$salesorder as $saleSOkey => $voucher) {
          $voucher = (array)$voucher; 
          //print_r($voucher);
          if(isset($voucher['VOUCHER'])){
            foreach (array($voucher['VOUCHER']) as $vchkey => $vcher) {
            $vcher = (array)$vcher; 
                foreach (array($vcher['ADDRESS.LIST']) as $addIvkey => $inAddres) {
                  //$address[$saleSOkey]['invoice_add'] = implode(' ', $addres->ADDRESS);
                    $add = implode(' ', $inAddres->ADDRESS);
                    //print_r($add);
                      if (trim($add!='')) {
                        //$address[$saleSOkey]['tally_address'] = implode(' ', $addres->ADDRESS);
                        $this->pktdblib->set_table('address');
                        $query = $this->pktdblib->get_where_custom('tally_address', $add);
                        //echo $this->db->last_query();
                          $addr = $query->row_array();
                          //print_r($addr);exit;
                          $order[$saleSOkey]['invoice_address_id'] = $addr['id'];
                          //$order[$saleSOkey]['delivery_address_id'] = $addr['id'];
                      }
                }
                if (isset($vcher['BASICBUYERADDRESS.LIST']->BASICBUYERADDRESS)) {
                  foreach (array($vcher['BASICBUYERADDRESS.LIST']) as $addDvkey => $dvAddres) {
                  //$address[$saleSOkey]['invoice_add'] = implode(' ', $addres->ADDRESS);
                    $add = implode(' ', $dvAddres->BASICBUYERADDRESS);
                      if (trim($add!='')) {
                        //$address[$saleSOkey]['tally_address'] = implode(' ', $addres->ADDRESS);
                        $this->pktdblib->set_table('address');
                        $query = $this->pktdblib->get_where_custom('tally_address', $add);
                        //echo $this->db->last_query();
                          $addr = $query->row_array();
                          //print_r($addr);exit;
                          //$order[$saleSOkey]['invoice_address_id'] = $addr['id'];
                          $order[$saleSOkey]['delivery_address_id'] = $addr['id'];
                      }
                      else{
                          $order[$saleSOkey]['delivery_address_id'] = $order[$saleSOkey]['invoice_address_id'];
                      }
                  }
                  
                }

                
                /*foreach (array($vcher['BASICBUYERADDRESS.LIST']) as $addkey => $addres) {
                  $address[$saleSOkey]['delivery_add'] = implode(' ', $addres->BASICBUYERADDRESS);
                }*/
            }
          }
          if(!isset($voucher['VOUCHER']))
            continue;
          $tax_rate = $voucher['VOUCHER']->CLASSNAME;
          $tax = substr($tax_rate, 7,2);
          if ($tax_rate == 'Sales Interstate') {
           $tax = 18;
          }
          if(!is_numeric($tax))
           $tax=0;
         //echo substr($tax_rate, 7,2);exit;
          /*$order['party_name'] = isset($voucher['VOUCHER']->PARTYNAME)?$voucher['VOUCHER']->PARTYNAME:'';
          echo $order['party_name'];*/
          $query = $this->pktdblib->custom_query('select * from tally_ledger where ledger_name="'.$voucher['VOUCHER']->PARTYNAME.'"');
                //print_r($query);exit;
          if(count($query)>0){
            $userId = $query[0]['user_id'];
            $order[$saleSOkey]['customer_id'] = $userId;
          }
          //print_r($voucher['VOUCHER']->BASICORDERREF);
          echo '<br>';
          if(isset($voucher['VOUCHER']->BASICORDERREF) && !empty($voucher['VOUCHER']->BASICORDERREF)){
            if(!isset($voucher['VOUCHER']->BASICORDERREF)){
              $broker[0] = 'Direct';
            }
            $broker = explode('-', $voucher['VOUCHER']->BASICORDERREF);
            $tallyLedger = $this->pktdblib->custom_query('Select * from tally_ledger where account_type="brokers" and ledger_name like "'.$broker[0].'"');
            //echo $this->db->last_query();exit;
            //print_r($tallyLedger);exit;
            $order[$saleSOkey]['broker_id'] = (count($tallyLedger))?$tallyLedger[0]['user_id']:0;
            $order[$saleSOkey]['brokerage_type'] = 'percentage';
            $order[$saleSOkey]['brokerage_value'] = isset($broker[1])?(float)trim($broker[1]):0;
            //print_r($order);
            //exit;
          }
          $orderTerm = (array)$voucher['VOUCHER'];
          //echo $voucher['VOUCHER']->VOUCHERNUMBER;
          //print_r($orderTerm);
          //print_r($orderTerm['BASICORDERTERMS.LIST']->BASICORDERTERMS);exit;
          $order[$saleSOkey]['order_date'] = isset($voucher['VOUCHER']->DATE)?date('Y-m-d', strtotime($voucher['VOUCHER']->DATE)):'';
          $order[$saleSOkey]['created'] = $order[$saleSOkey]['modified'] = date('Y-m-d H:i:s');
          $order[$saleSOkey]['order_code'] =  isset($voucher['VOUCHER']->VOUCHERNUMBER)?$voucher['VOUCHER']->VOUCHERNUMBER:'';
          $orderQuery = $this->pktdblib->custom_query('select * from orders where order_code="'.$order[$saleSOkey]['order_code'].'"');
          if (count($orderQuery)>0) {
            continue;
          }
          if ($flag == 0) {
            $orderDate = $this->pktlib->get_fiscal_year($order[$saleSOkey]['order_date']);
            //echo $orderDate;
            $maxId = $this->pktdblib->custom_query('select max(id) as id from orders where fiscal_yr="'.$orderDate.'"');
            //echo $maxId;exit;
            /*$order[$saleSOkey]['id'] = $maxId[0]['id']++;*/
            $id = $id+1;
          }else{
            $id = $id+1;
          }
          $order[$saleSOkey]['id'] = $id;
          $order[$saleSOkey]['fiscal_yr'] = $orderDate;        
          $order[$saleSOkey]['no_of_days'] = isset($orderTerm['BASICDUEDATEOFPYMT'])?$orderTerm['BASICDUEDATEOFPYMT']:'';
          /*foreach (array($orderTerm['LEDGERENTRIES.LIST']) as $ledEkey => $ledgerEntery) {
            print_r($ledgerEntery);
          }*/
          if ($orderTerm['LEDGERENTRIES.LIST'][1]->LEDGERNAME=='LOADING CHARGES (S)') {
            $order[$saleSOkey]['other_charges'] = $orderTerm['LEDGERENTRIES.LIST'][1]->AMOUNT;
          }
          else{
            $order[$saleSOkey]['other_charges'] = 0;

          }
          /*$order[$saleSOkey]['other_charges'] = isset($orderTerm['LEDGERENTRIES.LIST'][1]->AMOUNT)?$orderTerm['LEDGERENTRIES.LIST'][1]->AMOUNT:'';*/
          $order[$saleSOkey]['terms_of_delivery'] = isset($orderTerm['BASICORDERTERMS.LIST']->BASICORDERTERMS)?$orderTerm['BASICORDERTERMS.LIST']->BASICORDERTERMS:'';
          //print_r($order);exit;
          //$orderData['basic_buyer_address'] = isset($voucher['VOUCHER']->BASICORDERTERMS)?$voucher['VOUCHER']->BASICORDERTERMS:'';
          //$orderData['country'] = isset($voucher['VOUCHER']->COUNTRYOFRESIDENCE)?$voucher['VOUCHER']->COUNTRYOFRESIDENCE:'';
          
          foreach($voucher as $vKey => $addressList){
            $addressList = (array)$addressList;
            $this->pktdblib->set_table('products');
            $productStockName = isset($addressList['ALLINVENTORYENTRIES.LIST'])?$addressList['ALLINVENTORYENTRIES.LIST']:'';
            $sumAmount=[];
            if (!empty($productStockName)) {
              $inventoryList = [];
              //echo $order[$saleSOkey]['order_code'].'<br>';
              //print_r(count($addressList['ALLINVENTORYENTRIES.LIST']));
              if(count($addressList['ALLINVENTORYENTRIES.LIST'])==1){
                  $inventoryList[0] = (array)$addressList['ALLINVENTORYENTRIES.LIST'];
              }else{
                $inventoryList = $addressList['ALLINVENTORYENTRIES.LIST'];
              }
              foreach($inventoryList as $inventoryKey => $inventory) {
                $inventory = (array)$inventory;
                if(!isset($inventory['AMOUNT'])){
                  unset($order[$saleSOkey]);
                  continue;
                }
                
                //print_r($inventory);
                //echo $inventoryKey;
                $sumAmount[]= $inventory['AMOUNT'];
                //print_r($sumAmount);exit;
                //print_r($inventory['BASICUSERDESCRIPTION.LIST']);//exit;
                $tallycoil = '';
                if (count($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION)>2) {
                    $tallycoil = $inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[2];
                }
                $this->pktdblib->set_table('stock_details');
                //$tallycoil = $inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[1];
                $stockDetails = $this->pktdblib->get_where_custom('coil_no', $tallycoil);
                $product = $stockDetails->row_array();
                //print_r($product);
                $productSalesOrderCounter = '';
                $orderDetail[$orderDetailCounter]['product_id'] = $product['product_id'];

                if($product['product_id']==''){
                  //unset($order[$saleSOkey]);
                  //continue;
                }
                $orderDetail[$orderDetailCounter]['grade'] = 'default';
                //print_r($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION);
                echo '<br>';
                $productsize = '';
                if(!is_array($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION)){

                  $productsize = $inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION;
                }else{

                  $productsize = isset($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[0])?$inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[0]:'';
                }
                //exit;
                //echo 'dimension=';print_r($productsize);
                if (!empty($productsize)) {
                  $productSize = explode('X', strtoupper($productsize));
                  $orderDetail[$orderDetailCounter]['thickness'] = (float)$productSize[0];
                  $orderDetail[$orderDetailCounter]['width'] = (float)$productSize[1];
                  $orderDetail[$orderDetailCounter]['length'] = (isset($productSize[2]))?(float)$productSize[2]:0;
                }
                $orderDetail[$orderDetailCounter]['grade'] = isset($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[1])?$inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[1]:'default';
                //$tallycoil = (!is_array($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION))?$inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[1]:'';
                $tallycoil = isset($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[2])?$inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[2]:'';

                $orderDetail[$orderDetailCounter]['coil_no'] = isset($tallycoil)?$tallycoil:'';
                //$orderDetail[$orderDetailCounter]['make'] = isset($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[3])?$inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[3]:'';
                //$orderDetail[$orderDetailCounter]['weight_type'] = isset($inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[4])?$inventory['BASICUSERDESCRIPTION.LIST']->BASICUSERDESCRIPTION[4]:'';

                //echo $orderDetail[$orderDetailCounter]['product_thikness1'];exit;
                if (isset($inventory['BASICUSERDESCRIPTION'])) {
                $productSOKey = $this->productSalesOrderMapping();
                $productSalesOrderCounter = count($inventory['BASICUSERDESCRIPTION']);
                }
                for ($x = 0; $x < $productSalesOrderCounter; $x++) {
                $orderDetail['productDescription'][$productSOKey[$x]] = isset($inventory['BASICUSERDESCRIPTION'][$x])?$inventory['BASICUSERDESCRIPTION'][$x]:'';
                } 
                $orderDetail[$orderDetailCounter]['qty'] = isset($inventory['ACTUALQTY'])?$inventory['ACTUALQTY']:0;
                
                $orderDetail[$orderDetailCounter]['unit_price'] = (float)$inventory['RATE'];
                $orderDetail[$orderDetailCounter]['amt_before_tax'] = isset($inventory['AMOUNT'])?$inventory['AMOUNT']:0;
                $orderDetail[$orderDetailCounter]['tax'] = $tax;
                $orderDetail[$orderDetailCounter]['created'] = $orderDetail[$orderDetailCounter]['modified'] = date('Y-m-d H:i:s');
                //echo 'tax='.$tax.' before tax='.$orderDetail[$orderDetailCounter]['amt_before_tax'].'<br>';
                $orderDetail[$orderDetailCounter]['amt_after_tax'] = ($orderDetail[$orderDetailCounter]['amt_before_tax'])+($orderDetail[$orderDetailCounter]['amt_before_tax'])*($tax/100);
                //echo $orderDetail[$orderDetailCounter]['amt_after_tax'];
                $orderDetail[$orderDetailCounter]['uom'] = 'MT';
                $orderDetail[$orderDetailCounter]['qty'] = (float)$inventory['BILLEDQTY'];
                $orderDetail[$orderDetailCounter]['order_code'] = $order[$saleSOkey]['order_code'];
                $orderDetailCounter++;
              }
              ///print_r($order);
              if(isset($order[$saleSOkey])){
                /*echo $order[$saleSOkey]['other_charges'];*/
                $order[$saleSOkey]['amount_before_tax'] =  array_sum($sumAmount);
                $amountAfterTax = $order[$saleSOkey]['amount_before_tax'] + $order[$saleSOkey]['other_charges'];
                $order[$saleSOkey]['amount_after_tax'] = $amountAfterTax + ($amountAfterTax*($tax/100));
              }
            } 
          }
        }
       }
      /*print_r($order);
      print_r($orderDetail);exit;*/
      $this->pktdblib->set_table('orders');
      $this->pktdblib->_insert_multiple($order);
      $this->pktdblib->set_table('order_details');
      $this->pktdblib->_insert_multiple($orderDetail);
    }
  }
 
  function import_salesorder($orderCode) {
    //echo '<pre>';print_r($_SESSION);exit;
    //echo "reached in tally import_salesorder";exit;
    //echo urldecode($orderCode);exit;
    $orderCode = base64_decode($orderCode);
    //echo $orderCode;exit;
    //echo '<pre>';
    $this->pktdblib->set_table('orders');
    $code = $this->pktdblib->get_where_custom('order_code', $orderCode);
    //echo $this->db->last_query();exit;
    $orderData = $code->result_array();
    $order = $orderData[0];
    
    /*echo '<pre>';
    print_r($order);
    exit;*/
    //echo "<pre>";
    $orderDetails = $this->pktdblib->custom_query('select od.*, p.product, p.tally_name from order_details od left join products p on p.id=od.product_id left join orders o on o.order_code=od.order_code where od.order_code="'.$order['order_code'].'" and od.is_active!=5');
    //echo $this->db->last_query();exit;
    //echo '<pre>';
    //print_r($orderCode);//exit;
    $invoiceAddressQuery = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join orders o on o.invoice_address_id=a.id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where o.order_code="'.$orderCode.'"');
    //echo "invoiceAddress".'<br>';
    //print_r($invoiceAddressQuery);
    $invoiceAddress = isset($invoiceAddressQuery[0]['tally_address'])?$invoiceAddressQuery[0]['tally_address']:'';
    $invoicePincode = isset($invoiceAddressQuery[0]['pincode'])?$invoiceAddressQuery[0]['pincode']:'';
    $invoiceState = isset($invoiceAddressQuery[0]['state_name'])?$invoiceAddressQuery[0]['state_name']:'';
    $invoiceCity = isset($invoiceAddressQuery[0]['city_name'])?$invoiceAddressQuery[0]['city_name']:'';
    $invoiceCountry = isset($invoiceAddressQuery[0]['name'])?$invoiceAddressQuery[0]['name']:'';

    $deliveryAddressQuery = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join orders o on o.delivery_address_id=a.id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where o.order_code="'.$orderCode.'"');
    //print_r($deliveryAddressQuery);exit;
    $deliveryAddress = isset($deliveryAddressQuery[0]['tally_address'])?$deliveryAddressQuery[0]['tally_address']:'';
    $deliveryState = isset($deliveryAddressQuery[0]['state_name'])?$deliveryAddressQuery[0]['state_name']:'';
    $deliveryCity = isset($deliveryAddressQuery[0]['city'])?$deliveryAddressQuery[0]['city']:'';
    $deliveryCountry = isset($deliveryAddressQuery[0]['name'])?$deliveryAddressQuery[0]['name']:'';
    $deliveryPincode = isset($deliveryAddressQuery[0]['pincode'])?$deliveryAddressQuery[0]['pincode']:'';
    //print_r($deliveryAddressQuery);exit();
    $customerAddress = $this->pktdblib->custom_query('select a.* from address a where type="customers" and user_id='.$order['customer_id']);
    $this->pktdblib->set_table('customers');
    $customer = $this->pktdblib->get_where($order['customer_id']);
    //echo '<pre>';print_r($order['customer_site_id']);exit;
    $site = [];
    if($order['customer_site_id']!==0){
      //echo "hiiiii";//exit;
      $this->pktdblib->set_table('customer_sites');
      $site = $this->pktdblib->get_where($order['customer_site_id']);
     
      /*if (!empty($site)) {
          $this->pktdblib->set_table('customer_sites');
          $customer = $this->pktdblib->get_where($site['id']);
      }*/
    }
    //exit;
    $this->pktdblib->set_table('brokers');
    $broker = $this->pktdblib->get_where($order['broker_id']);
    //print_r($broker);exit;
    $ledgerName = isset($customer['company_name'])?$customer['company_name']:'';
    //echo $ledgerName;exit;
    $orderCode = $order['id'];
    $paymentDueDay = isset($order['no_of_days'])?$order['no_of_days']:'';
    /*echo '<pre>';
    print_r($order);exit;*/
   /* echo $order['amount_before_tax'].'<br>';
    echo $order['amount_before_tax'];exit;*/
    $tax = isset($order['amount_before_tax'])?($order['amount_after_tax']-$order['amount_before_tax'])/$order['amount_before_tax']*100:0;
    //$tax = ($order['amount_before_tax']/$order['amount_before_tax'])*100;//isset($order['tax'])?$order['tax']:'';
    //echo $tax;exit;
    $deliveryTerm = isset($order['terms_of_delivery'])?$order['terms_of_delivery']:'';
    $orderCreatedDate = isset($order['created'])?date('Ymd', strtotime($order['created'])):'';
    $orderModifiedDate = isset($order['modified'])?date('Ymd', strtotime($order['modified'])):'';
    $amt_before_tax = isset($order['amount_before_tax'])?$order['amount_before_tax']:'';
    $gst = '';
    $gstNo = isset($customer['gst_no'])?$customer['gst_no']:'';
    //echo $gstNo;exit;
    $brokerLedgerName = isset($broker['company_name'])?$broker['company_name']:'';
    $sumAmount = [];
    $total_amount = '';
    $roundOff = '';
    //echo $brokerLedgerName;exit;
    $tax=round($tax);
    $this->pktdblib->set_table('companies');
    $company = $this->pktdblib->get_where(custom_constants::company_id);
    $qry = $this->pktdblib->custom_query('select * from login where id="'.$order['sale_by'].'"');
    $login = $qry[0];
    $loadingCharge = 0;
    //$order['order_date'] = '2020-02-01';
    $xml = '
    <ENVELOPE>
      <HEADER>
        <TALLYREQUEST>Import Data</TALLYREQUEST>
      </HEADER>
      <BODY>
        <IMPORTDATA>
          <REQUESTDESC>
            <REPORTNAME>Vouchers</REPORTNAME>
            <STATICVARIABLES>
                <SVCURRENTCOMPANY>'.custom_constants::tally_current_company.'</SVCURRENTCOMPANY>
            </STATICVARIABLES>
            
          </REQUESTDESC>
          <REQUESTDATA>
            <TALLYMESSAGE xmlns:UDF="TallyUDF">
              <VOUCHER REMOTEID="'.$order['order_code'].'" VOUCHERTYPENAME="Sales Order" VCHTYPE="Sales Order" ACTION="Create" OBJVIEW="Invoice Voucher View">
                    <ADDRESS.LIST TYPE="String">
                          <ADDRESS>'.htmlspecialchars($invoiceAddressQuery[0]['address_1']).'</ADDRESS>
                          <ADDRESS>'.htmlspecialchars($invoiceAddressQuery[0]['address_2']).'</ADDRESS>
                          <ADDRESS>'.$invoiceAddressQuery[0]['area_name'].', '.$invoiceAddressQuery[0]['city_name'].'</ADDRESS>
                          <ADDRESS>'.$invoiceAddressQuery[0]['state_name'].', '.$invoiceAddressQuery[0]['name'].'</ADDRESS>
                          <ADDRESS>'.$invoicePincode.'</ADDRESS>

                    </ADDRESS.LIST>
                    <BASICBUYERADDRESS.LIST TYPE="String">
                          <BASICBUYERADDRESS>'.htmlspecialchars($deliveryAddressQuery[0]['address_1']).'</BASICBUYERADDRESS>
                          <BASICBUYERADDRESS>'.htmlspecialchars($deliveryAddressQuery[0]['address_2']).'</BASICBUYERADDRESS>
                          <BASICBUYERADDRESS>'.$deliveryAddressQuery[0]['area_name'].', '.$deliveryAddressQuery[0]['city_name'].'</BASICBUYERADDRESS>
                          <BASICBUYERADDRESS>'.$deliveryAddressQuery[0]['state_name'].', '.$deliveryAddressQuery[0]['name'].'</BASICBUYERADDRESS>
                          <BASICBUYERADDRESS>'.$deliveryPincode.'</BASICBUYERADDRESS>
                    </BASICBUYERADDRESS.LIST>
                    <OLDAUDITENTRYIDS.LIST TYPE="Number">
                          <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                    </OLDAUDITENTRYIDS.LIST>
                    <ACTIVETO/>
                    <DATE>'.date('Ymd', strtotime($order['order_date'])).'</DATE>
                    <STATENAME>'.$invoiceState.'</STATENAME>
                    <NARRATION>'.htmlspecialchars($order['message']).'</NARRATION>
                    <COUNTRYOFRESIDENCE>'.$invoiceCountry.'</COUNTRYOFRESIDENCE>
                    <PARTYGSTIN>'.$gstNo.'</PARTYGSTIN>
                    <PARTYNAME>'.htmlspecialchars($ledgerName).'</PARTYNAME>
                    <VOUCHERTYPENAME>Sales Order</VOUCHERTYPENAME>
                    <REFERENCE>'.$orderCode.'</REFERENCE>
                    <VOUCHERNUMBER>'.$orderCode.'</VOUCHERNUMBER>
                    <PARTYLEDGERNAME>'.htmlspecialchars($ledgerName).'</PARTYLEDGERNAME>
                    <BASICBASEPARTYNAME>'.htmlspecialchars($ledgerName).'</BASICBASEPARTYNAME>
                    <CSTFORMISSUETYPE/>
                    <CSTFORMRECVTYPE/>
                    <CONSIGNEECSTNUMBER/>
                    <BUYERSCSTNUMBER/>
                    <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
                    <PLACEOFSUPPLY>'.$deliveryState.'</PLACEOFSUPPLY>
                    <CONSIGNEEGSTIN>'.$gstNo.'</CONSIGNEEGSTIN>
                    <BASICSHIPPEDBY>'.$brokerLedgerName.'</BASICSHIPPEDBY>
                    <BASICDESTINATIONCOUNTRY>'.htmlspecialchars($deliveryAddressQuery[0]['name']).'</BASICDESTINATIONCOUNTRY>
                    <BASICBUYERNAME>'.htmlspecialchars($deliveryAddressQuery[0]['site_name']).'</BASICBUYERNAME>
                    <BASICPLACEOFRECEIPT/>
                    <BASICSHIPDOCUMENTNO/>
                    <BASICPORTOFLOADING/>
                    <BASICPORTOFDISCHARGE/>';
                    $brokerage = ($order['brokerage_value']>0)?'TO PAY':'NETT';
                    $xml.='<BASICFINALDESTINATION>'.$brokerage.'</BASICFINALDESTINATION>
                    <BASICORDERREF>'.$login['first_name'].' '.$login['surname'].'</BASICORDERREF>
                    <BASICDUEDATEOFPYMT>'.$paymentDueDay.' DAY</BASICDUEDATEOFPYMT>
                    <VCHGSTCLASS/>
                    ';
                    if(isset($site['pan_no'])){

                      $xml.='<CONSIGNEEPINNUMBER>'.$site['pan_no'].'</CONSIGNEEPINNUMBER>';
                    }else{
                      $xml.='<CONSIGNEEPINNUMBER>'.$customer['pan_no'].'</CONSIGNEEPINNUMBER>';

                    }

                    $xml.='<CONSIGNEESTATENAME>'.$deliveryState.'</CONSIGNEESTATENAME>
                    <ENTEREDBY>hiren</ENTEREDBY>
                    <DIFFACTUALQTY>No</DIFFACTUALQTY>
                    <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
                    <ASORIGINAL>No</ASORIGINAL>
                    <AUDITED>No</AUDITED>
                    <FORJOBCOSTING>No</FORJOBCOSTING>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <EFFECTIVEDATE>'.date('Ymd', strtotime($order['order_date'])).'</EFFECTIVEDATE>
                    <USEFOREXCISE>No</USEFOREXCISE>
      <ISFORJOBWORKIN>No</ISFORJOBWORKIN>
      <ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>
      <USEFORINTEREST>No</USEFORINTEREST>
      <USEFORGAINLOSS>No</USEFORGAINLOSS>
      <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
      <USEFORCOMPOUND>No</USEFORCOMPOUND>
      <USEFORSERVICETAX>No</USEFORSERVICETAX>
      <ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>
      <EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>
      <USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>
      <IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>
      <EXCISEOPENING>No</EXCISEOPENING>
      <USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>
      <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
      <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
      <ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>
      <INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>
      <ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>
      <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
      <IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>
      <ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>
      <ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>
      <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
      <ISISDVOUCHER>No</ISISDVOUCHER>
      <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
      <ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>
      <ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>
      <GSTNOTEXPORTED>No</GSTNOTEXPORTED>
      <IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>
      <ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>
      <ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>
      <ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>
      <ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>
      <ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>
      <ISCANCELLED>No</ISCANCELLED>
      <HASCASHFLOW>No</HASCASHFLOW>
      <ISPOSTDATED>No</ISPOSTDATED>
      <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
      <ISINVOICE>No</ISINVOICE>
      <MFGJOURNAL>No</MFGJOURNAL>
      <HASDISCOUNTS>No</HASDISCOUNTS>
      <ASPAYSLIP>No</ASPAYSLIP>
      <ISCOSTCENTRE>No</ISCOSTCENTRE>
      <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
      <ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>
      <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
      <ISVOID>No</ISVOID>
      <ISONHOLD>No</ISONHOLD>
      <ORDERLINESTATUS>No</ORDERLINESTATUS>
      <VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>
      <VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>
      <ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>
      <VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>
      <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
      <ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>
      <ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>
      <ISDELETED>No</ISDELETED>
      <CHANGEVCHMODE>No</CHANGEVCHMODE>
      <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
      <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
      <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
      <DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>
      <SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
      <EWAYBILLDETAILS.LIST>      </EWAYBILLDETAILS.LIST>
      <INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>
      <INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>
      <INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>
      <ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>
      <ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>
      <INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>
      <LEDGERENTRIES.LIST>
       <OLDAUDITENTRYIDS.LIST TYPE="Number">
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
       </OLDAUDITENTRYIDS.LIST>
       <LEDGERNAME>'.htmlspecialchars($ledgerName).'</LEDGERNAME>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
       <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
       <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
       <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>';
        $sumAmount[] = $amt_before_tax;
        $xml.='<AMOUNT>-'.$order['amount_after_tax'].'</AMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
       <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
      </LEDGERENTRIES.LIST>';
                    if (substr($customer['gst_no'], 0, 2) == substr($company['gst_no'], 0, 2)) {
                          $sumAmount[] = ($amt_before_tax+$loadingCharge)*($tax/100);
                         $total_amount = number_format((float)array_sum($sumAmount), 2, '.', '');
                         $total_amount = explode('.', $total_amount);
                         $roundOff = $total_amount[1];
                          $xml.='      <LEDGERENTRIES.LIST>
       <OLDAUDITENTRYIDS.LIST TYPE="Number">
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
       </OLDAUDITENTRYIDS.LIST>
       <BASICRATEOFINVOICETAX.LIST TYPE="Number">
        <BASICRATEOFINVOICETAX> 9</BASICRATEOFINVOICETAX>
       </BASICRATEOFINVOICETAX.LIST>
       <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
       <LEDGERNAME>CGST</LEDGERNAME>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
       <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
       <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
       <ROUNDLIMIT> 1</ROUNDLIMIT>
       <AMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/200).'</AMOUNT>
       <VATEXPAMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/200).'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
       <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
      </LEDGERENTRIES.LIST>
      <LEDGERENTRIES.LIST>
       <OLDAUDITENTRYIDS.LIST TYPE="Number">
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
       </OLDAUDITENTRYIDS.LIST>
       <BASICRATEOFINVOICETAX.LIST TYPE="Number">
        <BASICRATEOFINVOICETAX> 9</BASICRATEOFINVOICETAX>
       </BASICRATEOFINVOICETAX.LIST>
       <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
       <LEDGERNAME>SGST</LEDGERNAME>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
       <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
       <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
       <ROUNDLIMIT> 1</ROUNDLIMIT>
       <AMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/200).'</AMOUNT>
       <VATEXPAMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/200).'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
       <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
      </LEDGERENTRIES.LIST>';
                    }else{
                          $sumAmount[] = ($amt_before_tax+$loadingCharge)*($tax/100);
                          $total_amount = number_format((float)array_sum($sumAmount), 2, '.', '');
                          $total_amount = explode('.', $total_amount);
                          $roundOff = $total_amount[1];
                        $xml.='<LEDGERENTRIES.LIST>
                                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                      <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                </OLDAUDITENTRYIDS.LIST>
                                <BASICRATEOFINVOICETAX.LIST TYPE="Number">
                                      <BASICRATEOFINVOICETAX> 18</BASICRATEOFINVOICETAX>
                                </BASICRATEOFINVOICETAX.LIST>
                                <ORIGPURCHINVDATE/>
                                <NARRATION/>
                                <ADDLALLOCTYPE/>
                                <TAXCLASSIFICATIONNAME/>
                                <NOTIFICATIONSLNO/>
                                <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                                <LEDGERNAME>IGST</LEDGERNAME>
                                <TAXUNITNAME/>
                                <STATNATURENAME/>
                                <GOODSTYPE/>
                                <METHODTYPE/>
                                <CLASSRATE/>
                                <STATCLASSIFICATIONNAME/>
                                <EXCISECLASSIFICATIONNAME/>
                                <ISZRBASICSERVICE/>
                                <VATCOMMODITYNAME/>
                                <SCHEDULE/>
                                <SCHEDULESERIALNUMBER/>
                                <VATCOMMODITYCODE/>
                                <VATSUBCOMMODITYCODE/>
                                <VATTRADENAME/>
                                <VATMAJORCOMMODITYNAME/>
                                <TDSPARTYNAME/>
                                <VATPARTYORGNAME/>
                                <XBRLADJTYPE/>
                                <VOUCHERFBTCATEGORY/>
                                <TYPEOFTAXPAYMENT/>
                                <VATCALCULATIONTYPE/>
                                <VATWORKSCONTRACTTYPE/>
                                <VATWCDESCRIPTION/>
                                <GSTCLASS/>
                                <SCHVIADJTYPE/>
                                <GSTOVRDNCLASSIFICATION/>
                                <GSTOVRDNNATURE/>
                                <GSTOVRDNINELIGIBLEITC/>
                                <GSTOVRDNISREVCHARGEAPPL/>
                                <GSTOVRDNTAXABILITY/>
                                <VATGOODSNATURE/>
                                <POSPAYMENTTYPE/>
                                <GSTPARTYLEDGER/>
                                <ORIGPURCHPARTY/>
                                <ORIGPURCHPARTYADDRESS/>
                                <ORIGPURCHINVNO/>
                                <ORIGPURCHNOTE/>
                                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                <ISPARTYLEDGER>No</ISPARTYLEDGER>
                                <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                                <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                                <STCRADJPERCENT>0</STCRADJPERCENT>
                                <ROUNDLIMIT> 1</ROUNDLIMIT>
                                <RATEOFADDLVAT>0</RATEOFADDLVAT>
                                <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                                <VATTAXRATE>0</VATTAXRATE>
                                <VATITEMQTY>0</VATITEMQTY>
                                <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                                <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                                <GSTTAXRATE>0</GSTTAXRATE>
                                <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                                <CAPVATTAXRATE>0</CAPVATTAXRATE>
                                <AMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/100).'</AMOUNT>
                                <FBTEXEMPTAMOUNT/>
                                <VATASSESSABLEVALUE/>
                                <VATWCCOSTOFLAND/>
                                <VATWCDEDLABOURCHARGES/>
                                <VATWCOTHERDEDUCTIONAMT/>
                                <VATWCVALUEOFTAXFREEGOODS/>
                                <VATWCOTHERCHARGES/>
                                <VATWCSUBCONTRACTORAMT/>
                                <PREVAMOUNT/>
                                <PREVINVTOTALAMT/>
                                <VATWCDEDUCTIONAMOUNT/>
                                <ORIGINVGOODSVALUE/>
                                <CENVATCAPTINPUTAMT/>
                                <GSTASSESSABLEVALUE/>
                                <IGSTLIABILITY/>
                                <CGSTLIABILITY/>
                                <SGSTLIABILITY/>
                                <GSTCESSLIABILITY/>
                                <GSTOVRDNASSESSABLEVALUE/>
                                <GSTASSBLVALUE/>
                                <ORIGINVGOODSTAXVALUE/>
                                <VATWCESTABLISHMENTCOST/>
                                <VATWCCONTRACTORPROFIT/>
                                <VATWCPLANNINGDESIGNFEES/>
                                <VATWCMACHINERYTOOLSCHARGES/>
                                <VATWCCONSUMABLESCOST/>
                                <VATEXPAMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/100).'</VATEXPAMOUNT>
                                <VATASSBLVALUE/>
                                <VATACCEPTEDTAXAMT/>
                                <VATACCEPTEDADDLTAXAMT/>
                                <CASHRECEIVED/>
                                <CAPVATASSEABLEVALUE/>
                                <CAPVATTAXVALUE/>
                                <ORIGPURCHVALUE/>
                                <SUPPLYMARGVAL/>
                                <DENOMINATIONCOUNT2000X>0</DENOMINATIONCOUNT2000X>
                                <DENOMINATIONCOUNT1000X>0</DENOMINATIONCOUNT1000X>
                                <DENOMINATIONCOUNT500X>0</DENOMINATIONCOUNT500X>
                                <DENOMINATIONCOUNT200X>0</DENOMINATIONCOUNT200X>
                                <DENOMINATIONCOUNT100X>0</DENOMINATIONCOUNT100X>
                                <DENOMINATIONCOUNT50X>0</DENOMINATIONCOUNT50X>
                                <DENOMINATIONCOUNT20X>0</DENOMINATIONCOUNT20X>
                                <DENOMINATIONCOUNT10X>0</DENOMINATIONCOUNT10X>
                                <DENOMINATIONCOUNT5X>0</DENOMINATIONCOUNT5X>
                                <DENOMINATIONCOUNT2X>0</DENOMINATIONCOUNT2X>
                                <DENOMINATIONCOUNT1X>0</DENOMINATIONCOUNT1X>
                                <DENOMINATIONAMOUNTOTHERS/>
                                <CURRPARTYLEDGERNAME/>
                                <TEMPVATCLASSIFICATION/>
                                <VATGCCTAXAMOUNT/>
                                <GSTCOMPDUTYAMOUNT/>
                                <EXCISETEMPDUTYVAL/>
                                <ISVATADJLEDGER/>
                                <ISCLASSIFYMODIFY/>
                                <VATASSESMENTATE>0</VATASSESMENTATE>
                                <GSTLEDGERDISCOUNT/>
                                <GVATLEDGEREXCISE/>
                                <GSTDUTYAMOUNT/>
                                <RBVATTAXAMOUNT/>
                                <SALESCESSTAXAMOUNT/>
                                <INVOICEROUNDINGDIFFVAL/>
                                <SERVICETAXDETAILS.LIST></SERVICETAXDETAILS.LIST>
                                <BANKALLOCATIONS.LIST></BANKALLOCATIONS.LIST>
                                <BILLALLOCATIONS.LIST></BILLALLOCATIONS.LIST>
                                <INTERESTCOLLECTION.LIST></INTERESTCOLLECTION.LIST>
                                <OLDAUDITENTRIES.LIST></OLDAUDITENTRIES.LIST>
                                <ACCOUNTAUDITENTRIES.LIST></ACCOUNTAUDITENTRIES.LIST>
                                <AUDITENTRIES.LIST></AUDITENTRIES.LIST>
                                <INPUTCRALLOCS.LIST></INPUTCRALLOCS.LIST>
                                <DUTYHEADDETAILS.LIST></DUTYHEADDETAILS.LIST>
                                <EXCISEDUTYHEADDETAILS.LIST></EXCISEDUTYHEADDETAILS.LIST>
                                <RATEDETAILS.LIST></RATEDETAILS.LIST>
                                <SUMMARYALLOCS.LIST></SUMMARYALLOCS.LIST>
                                <STPYMTDETAILS.LIST></STPYMTDETAILS.LIST>
                                <EXCISEPAYMENTALLOCATIONS.LIST></EXCISEPAYMENTALLOCATIONS.LIST>
                                <TAXBILLALLOCATIONS.LIST></TAXBILLALLOCATIONS.LIST>
                                <TAXOBJECTALLOCATIONS.LIST></TAXOBJECTALLOCATIONS.LIST>
                                <TDSEXPENSEALLOCATIONS.LIST></TDSEXPENSEALLOCATIONS.LIST>
                                <VATSTATUTORYDETAILS.LIST></VATSTATUTORYDETAILS.LIST>
                                <COSTTRACKALLOCATIONS.LIST></COSTTRACKALLOCATIONS.LIST>
                                <REFVOUCHERDETAILS.LIST></REFVOUCHERDETAILS.LIST>
                                <INVOICEWISEDETAILS.LIST></INVOICEWISEDETAILS.LIST>
                                <VATITCDETAILS.LIST></VATITCDETAILS.LIST>
                                <ADVANCETAXDETAILS.LIST></ADVANCETAXDETAILS.LIST>
                          </LEDGERENTRIES.LIST>';  
                    }
                    $qtySum = [];
                    foreach ($orderDetails as $ODKey => $orderDetail) {
                          //print_r($orderDetail);exit;
                          $coilNo = isset($orderDetail['coil_no'])?$orderDetail['coil_no']:'';
                          $grade = isset($orderDetail['grade'])?$orderDetail['grade']:'';
                          $thickness = isset($orderDetail['thickness'])?$orderDetail['thickness']:'';
                          $width = isset($orderDetail['width'])?$orderDetail['width']:'';
                          $length = (isset($orderDetail['length']) && $orderDetail['length']>0)?$orderDetail['length']:'CTL';
                          $qty = isset($orderDetail['qty'])?$orderDetail['qty']:'';
                          $unitPrice = isset($orderDetail['unit_price'])?$orderDetail['unit_price']:'';
                          $tallyName = isset($orderDetail['tally_name'])?$orderDetail['tally_name']:'';
                          $remark = isset($orderDetail['remark'])?$orderDetail['remark']:'';

                          $amount = isset($orderDetail['unit_price'])?$unitPrice*$qty:'';
                          $dueOn = isset($orderDetail['due_on'])?date('Y-m-d',strtotime($orderDetail['due_on'])):'';
                          $qtySum[] = $qty;

                          $xml.='<ALLINVENTORYENTRIES.LIST>
                          <BASICUSERDESCRIPTION.LIST TYPE="String">
                          <BASICUSERDESCRIPTION>'.$thickness.'X'.$width.'X'.$length.'</BASICUSERDESCRIPTION>
                          </BASICUSERDESCRIPTION.LIST>
                          <ORIGINVOICEDATE/>
                          <ORIGSALESINVDATE/>
                          <DESCRIPTION/>
                          <STOCKITEMNAME>'.$tallyName.'</STOCKITEMNAME>
                          <STATNATURENAME/>
                          <EXCISECLASSIFICATIONNAME/>
                          <ISZRBASICSERVICE/>
                          <EXCISETARIFF/>
                          <EXCISEEXEMPTION/>
                          <TRADERCNSALESNUMBER/>
                          <BASICPACKAGEMARKS/>
                          <BASICNUMPACKAGES/>
                          <SDTAXCLASSIFICATIONNAME/>
                          <NATUREOFCOMPONENT/>
                          <COMPONENTLISTTYPE/>
                          <DISPLAYNATUREOFCOMPONENT/>
                          <BOMNAME/>
                          <ORIGINVOICENUMBER/>
                          <ORIGINVOICEBOOKNAME/>
                          <ORIGSALESINVNO/>
                          <EXCISERETURNINVOICENO/>
                          <REASONOFREJECTION/>
                          <EXCISECREDITPARTY/>
                          <EXCISECREDITSTKITEM/>
                          <EXCISECREDITCATEGORY/>
                          <TRADERSUPPLIERINVOICENO/>
                          <EXCISESALESINVOICENO/>
                          <ADDLAMOUNT/>
                          <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                          <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                          <ISAUTONEGATE>No</ISAUTONEGATE>
                          <ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
                          <ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
                          <ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
                          <ISPRIMARYITEM>No</ISPRIMARYITEM>
                          <ISSCRAP>No</ISSCRAP>
                          <RATE>'.$unitPrice.'/MT</RATE>
                          <DISCOUNT>0</DISCOUNT>
                          <VATTAXRATE>0</VATTAXRATE>
                          <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                          <EXCISEMRPABATEMENT>0</EXCISEMRPABATEMENT>
                          <ADDLCOSTPERC>0</ADDLCOSTPERC>
                          <REVISEDRATEOFDUTY>0</REVISEDRATEOFDUTY>
                          <ORIGRATEOFDUTY>0</ORIGRATEOFDUTY>
                          <ORIGMRPABATEMENT>0</ORIGMRPABATEMENT>
                          <EXCISERETURNDUTYRATE>0</EXCISERETURNDUTYRATE>
                          <GVATEXCISERATE>0</GVATEXCISERATE>
                          <AMOUNT>'.$amount.'</AMOUNT>
                          <EXCISEASSESSABLEVALUE/>
                          <VATASSESSABLEVALUE/>
                          <ORIGINVGOODSVALUE/>
                          <GSTASSBLVALUE/>
                          <ORIGINVGOODSTAXVALUE/>
                          <VATASSBLVALUE/>
                          <VATACCEPTEDTAXAMT/>
                          <VATACCEPTEDADDLTAXAMT/>
                          <GVATEXCISEAMT/>
                          <ACTUALQTY> '.$orderDetail['qty'].' MT</ACTUALQTY>
                          <BILLEDQTY> '.$orderDetail['qty'].' MT</BILLEDQTY>
                          <ORIGACTUALQTY/>
                          <ORIGBILLEDQTY/>
                          <EXCISERETURNINVOICEQTY/>
                          <USABLEQTY/>
                          <EXCISECREDITPENDINGQTY/>
                          <MRPRATE/>
                          <EXCISEMRPRATE/>
                          <ORIGRATE/>
                          <REVISEDRATE/>
                          <ESCALATIONRATE/>
                          <ORIGMRPRATE/>
                          <ORIGRATEOFQTY/>
                          <REVISEDRATEOFQTY/>
                          <INCLVATRATE/>
                          <INCLUSIVETAXVALUE/>
                          <ISINCLTAXRATEFIELDEDITED/>
                          <ISSTOCKITEMEXIST/>
                          <STOCKITEMACTQTY/>
                          <TEMPISEXCISEITEMEXIST/>
                          <ISEXCISEOVERRIDEDETAILS/>
                          <ISALLOCATEDINACCT/>
                          <PREVREVISEDRATEPER/>
                          <PREVSTOCKITEMNAME/>
                          <EXCISECREDITNOTENUMBER/>
                          <ORIGCONSUMEQTY/>
                          <TEMPSTKBOMQTY/>
                          <BATCHALLOCATIONS.LIST>
                          <MFDON/>
                          <ORDERYEAREND/>
                          <TRACKINGYEAREND/>
                          <ORDERPRECLOSUREDATE/>
                          <NARRATION/>
                          <GODOWNNAME>KRISHNA SHEET PROCESSORS PVT LTD.</GODOWNNAME>
                          <BATCHNAME>&#4; Any</BATCHNAME>
                          <DESTINATIONGODOWNNAME/>
                          <INDENTNO/>
                          <ORDERTYPE/>
                          <PARENTITEM/>
                          <ORDERCLOSUREREASON/>
                          <ORDERNO>'.$orderCode.'</ORDERNO>
                          <TRACKINGNUMBER/>
                          <DYNAMICCSTNO/>
                          <DYNAMICCSTPARENTITEM/>
                          <SUMDYNAMICCSTNO/>
                          <SUMBATCHNAME/>
                          <SUMTRACKINGNUMBER/>
                          <SUMORDERNO/>
                          <SUMINDENTNO/>
                          <ADDLAMOUNT/>
                          <DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED>
                          <AMOUNT>'.$amount.'</AMOUNT>
                          <ADDLEXPENSEAMOUNT/>
                          <BATCHDIFFVAL/>
                          <ACTUALQTY> '.$orderDetail['qty'].' MT</ACTUALQTY>
                          <BILLEDQTY> '.$orderDetail['qty'].' MT</BILLEDQTY>
                          <ORDERPRECLOSUREQTY/>
                          <ORIGACTUALQTY/>
                          <ORIGBILLEDQTY/>
                          <BATCHPHYSDIFF/>
                          <ORIGRATE/>
                          <REVISEDRATE/>
                          <ESCALATIONRATE/>
                          <INCLVATRATE/>
                          <EXPIRYPERIOD/>
                          <INDENTDUEDATE/>
                          <ORDERDUEDATE P="'.date('d-M-Y',strtotime($dueOn)).'">'.date('d-M-Y',strtotime($dueOn)).'</ORDERDUEDATE>
                          <INCLUSIVETAXVALUE/>
                          <ISPRECLOSED/>
                          <BATCHALLOCBOMNAME/>
                          <PREVBATCHALLOCBOMNAME/>
                          <BOMALLOCACCEPTED/>
                          <BATCHALLOCBOMBASEQTY/>
                          <COSTTRACKID>0</COSTTRACKID>
                          <ISINCLTAXRATEFIELDEDITED/>
                          <ADDITIONALDETAILS.LIST></ADDITIONALDETAILS.LIST>
                          <VOUCHERCOMPONENTLIST.LIST></VOUCHERCOMPONENTLIST.LIST>
                          </BATCHALLOCATIONS.LIST>
                          <ACCOUNTINGALLOCATIONS.LIST>
                          <OLDAUDITENTRYIDS.LIST TYPE="Number">
                          <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                          </OLDAUDITENTRYIDS.LIST>
                          <ORIGPURCHINVDATE/>
                          <NARRATION/>
                          <ADDLALLOCTYPE/>
                          <TAXCLASSIFICATIONNAME/>
                          <NOTIFICATIONSLNO/>
                          <ROUNDTYPE/>
                          <LEDGERNAME>Gst Sales</LEDGERNAME>
                          <TAXUNITNAME/>
                          <STATNATURENAME/>
                          <GOODSTYPE/>
                          <METHODTYPE/>
                          <CLASSRATE/>
                          <STATCLASSIFICATIONNAME/>
                          <EXCISECLASSIFICATIONNAME/>
                          <ISZRBASICSERVICE/>
                          <VATCOMMODITYNAME/>
                          <SCHEDULE/>
                          <SCHEDULESERIALNUMBER/>
                          <VATCOMMODITYCODE/>
                          <VATSUBCOMMODITYCODE/>
                          <VATTRADENAME/>
                          <VATMAJORCOMMODITYNAME/>
                          <TDSPARTYNAME/>
                          <VATPARTYORGNAME/>
                          <XBRLADJTYPE/>
                          <VOUCHERFBTCATEGORY/>
                          <TYPEOFTAXPAYMENT/>
                          <VATCALCULATIONTYPE/>
                          <VATWORKSCONTRACTTYPE/>
                          <VATWCDESCRIPTION/>
                          <GSTCLASS/>
                          <SCHVIADJTYPE/>
                          <GSTOVRDNCLASSIFICATION/>
                          <GSTOVRDNNATURE/>
                          <GSTOVRDNINELIGIBLEITC/>
                          <GSTOVRDNISREVCHARGEAPPL/>
                          <GSTOVRDNTAXABILITY/>
                          <VATGOODSNATURE/>
                          <POSPAYMENTTYPE/>
                          <GSTPARTYLEDGER/>
                          <ORIGPURCHPARTY/>
                          <ORIGPURCHPARTYADDRESS/>
                          <ORIGPURCHINVNO/>
                          <ORIGPURCHNOTE/>
                          <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                          <LEDGERFROMITEM>No</LEDGERFROMITEM>
                          <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                          <ISPARTYLEDGER>No</ISPARTYLEDGER>
                          <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                          <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                          <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                          <STCRADJPERCENT>0</STCRADJPERCENT>
                          <ROUNDLIMIT>0</ROUNDLIMIT>
                          <RATEOFADDLVAT>0</RATEOFADDLVAT>
                          <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                          <VATTAXRATE>0</VATTAXRATE>
                          <VATITEMQTY>0</VATITEMQTY>
                          <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                          <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                          <GSTTAXRATE>0</GSTTAXRATE>
                          <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                          <CAPVATTAXRATE>0</CAPVATTAXRATE>
                          <AMOUNT>'.$amount.'</AMOUNT>
                          <FBTEXEMPTAMOUNT/>
                          <VATASSESSABLEVALUE/>
                          <VATWCCOSTOFLAND/>
                          <VATWCDEDLABOURCHARGES/>
                          <VATWCOTHERDEDUCTIONAMT/>
                          <VATWCVALUEOFTAXFREEGOODS/>
                          <VATWCOTHERCHARGES/>
                          <VATWCSUBCONTRACTORAMT/>
                          <PREVAMOUNT/>
                          <PREVINVTOTALAMT/>
                          <VATWCDEDUCTIONAMOUNT/>
                          <ORIGINVGOODSVALUE/>
                          <CENVATCAPTINPUTAMT/>
                          <GSTASSESSABLEVALUE/>
                          <IGSTLIABILITY/>
                          <CGSTLIABILITY/>
                          <SGSTLIABILITY/>
                          <GSTCESSLIABILITY/>
                          <GSTOVRDNASSESSABLEVALUE/>
                          <GSTASSBLVALUE/>
                          <ORIGINVGOODSTAXVALUE/>
                          <VATWCESTABLISHMENTCOST/>
                          <VATWCCONTRACTORPROFIT/>
                          <VATWCPLANNINGDESIGNFEES/>
                          <VATWCMACHINERYTOOLSCHARGES/>
                          <VATWCCONSUMABLESCOST/>
                          <VATEXPAMOUNT/>
                          <VATASSBLVALUE/>
                          <VATACCEPTEDTAXAMT/>
                          <VATACCEPTEDADDLTAXAMT/>
                          <CASHRECEIVED/>
                          <CAPVATASSEABLEVALUE/>
                          <CAPVATTAXVALUE/>
                          <ORIGPURCHVALUE/>
                          <SUPPLYMARGVAL/>
                          <DENOMINATIONCOUNT2000X>0</DENOMINATIONCOUNT2000X>
                          <DENOMINATIONCOUNT1000X>0</DENOMINATIONCOUNT1000X>
                          <DENOMINATIONCOUNT500X>0</DENOMINATIONCOUNT500X>
                          <DENOMINATIONCOUNT200X>0</DENOMINATIONCOUNT200X>
                          <DENOMINATIONCOUNT100X>0</DENOMINATIONCOUNT100X>
                          <DENOMINATIONCOUNT50X>0</DENOMINATIONCOUNT50X>
                          <DENOMINATIONCOUNT20X>0</DENOMINATIONCOUNT20X>
                          <DENOMINATIONCOUNT10X>0</DENOMINATIONCOUNT10X>
                          <DENOMINATIONCOUNT5X>0</DENOMINATIONCOUNT5X>
                          <DENOMINATIONCOUNT2X>0</DENOMINATIONCOUNT2X>
                          <DENOMINATIONCOUNT1X>0</DENOMINATIONCOUNT1X>
                          <DENOMINATIONAMOUNTOTHERS/>
                          <CURRPARTYLEDGERNAME/>
                          <TEMPVATCLASSIFICATION/>
                          <VATGCCTAXAMOUNT/>
                          <GSTCOMPDUTYAMOUNT/>
                          <EXCISETEMPDUTYVAL/>
                          <ISVATADJLEDGER/>
                          <ISCLASSIFYMODIFY/>
                          <VATASSESMENTATE>0</VATASSESMENTATE>
                          <GSTLEDGERDISCOUNT/>
                          <GVATLEDGEREXCISE/>
                          <GSTDUTYAMOUNT/>
                          <RBVATTAXAMOUNT/>
                          <SALESCESSTAXAMOUNT/>
                          <INVOICEROUNDINGDIFFVAL/>
                          <SERVICETAXDETAILS.LIST></SERVICETAXDETAILS.LIST>
                          <BANKALLOCATIONS.LIST></BANKALLOCATIONS.LIST>
                          <BILLALLOCATIONS.LIST></BILLALLOCATIONS.LIST>
                          <INTERESTCOLLECTION.LIST></INTERESTCOLLECTION.LIST>
                          <OLDAUDITENTRIES.LIST></OLDAUDITENTRIES.LIST>
                          <ACCOUNTAUDITENTRIES.LIST></ACCOUNTAUDITENTRIES.LIST>
                          <AUDITENTRIES.LIST></AUDITENTRIES.LIST>
                          <INPUTCRALLOCS.LIST></INPUTCRALLOCS.LIST>
                          <DUTYHEADDETAILS.LIST></DUTYHEADDETAILS.LIST>
                          <EXCISEDUTYHEADDETAILS.LIST></EXCISEDUTYHEADDETAILS.LIST>
                          <RATEDETAILS.LIST></RATEDETAILS.LIST>
                          <SUMMARYALLOCS.LIST></SUMMARYALLOCS.LIST>
                          <STPYMTDETAILS.LIST></STPYMTDETAILS.LIST>
                          <EXCISEPAYMENTALLOCATIONS.LIST></EXCISEPAYMENTALLOCATIONS.LIST>
                          <TAXBILLALLOCATIONS.LIST></TAXBILLALLOCATIONS.LIST>
                          <TAXOBJECTALLOCATIONS.LIST></TAXOBJECTALLOCATIONS.LIST>
                          <TDSEXPENSEALLOCATIONS.LIST></TDSEXPENSEALLOCATIONS.LIST>
                          <VATSTATUTORYDETAILS.LIST></VATSTATUTORYDETAILS.LIST>
                          <COSTTRACKALLOCATIONS.LIST></COSTTRACKALLOCATIONS.LIST>
                          <REFVOUCHERDETAILS.LIST></REFVOUCHERDETAILS.LIST>
                          <INVOICEWISEDETAILS.LIST></INVOICEWISEDETAILS.LIST>
                          <VATITCDETAILS.LIST></VATITCDETAILS.LIST>
                          <ADVANCETAXDETAILS.LIST></ADVANCETAXDETAILS.LIST>
                          </ACCOUNTINGALLOCATIONS.LIST>
                          <DUTYHEADDETAILS.LIST></DUTYHEADDETAILS.LIST>
                          <SUPPLEMENTARYDUTYHEADDETAILS.LIST></SUPPLEMENTARYDUTYHEADDETAILS.LIST>
                          <TAXOBJECTALLOCATIONS.LIST></TAXOBJECTALLOCATIONS.LIST>
                          <REFVOUCHERDETAILS.LIST></REFVOUCHERDETAILS.LIST>
                          <EXCISEALLOCATIONS.LIST></EXCISEALLOCATIONS.LIST>
                          <EXPENSEALLOCATIONS.LIST></EXPENSEALLOCATIONS.LIST>
                          </ALLINVENTORYENTRIES.LIST>';

                    }
                    $xml.='<PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
                    <ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
                    <GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>
                    <GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>
                    <TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>
              </VOUCHER>
            </TALLYMESSAGE>
          </REQUESTDATA>
        </IMPORTDATA>
      </BODY>
    </ENVELOPE>';

    

    //echo $xml;exit;
    $res = $this->curl_handling($xml);
    if($this->uri->segment(1)=='tally'){
        //exit;
        echo '<pre>';
        print_r($res);
        exit;
    }
    return $res;
  }

    function export_ledgerxml($params=[]/*$type='customers', $customerIds = []*/){
        //print_r($params);exit;
        $customers = [];
        if(!isset($params['ids'])){
            $sql = 'Select id from '.$params['account'].' where is_active=true';
            if(!empty($sql)){
                $sql.=' AND id in ('.implode(',', array_unique($params['ids'])).')';
            }
            $customers = $this->pktdblib->custom_query($sql);
        }else{
            $customers = $params['ids'];
        }
        
        if(empty($customers)){
            return false;
        }
        //$sql.='  limit 300';
        
        $xml = '<ENVELOPE>
               <HEADER>
                <TALLYREQUEST>Import Data</TALLYREQUEST>
               </HEADER>
               <BODY>
                <IMPORTDATA>
                    <REQUESTDESC>
                        <REPORTNAME>All Masters</REPORTNAME>
                        <STATICVARIABLES>
                            <SVCURRENTCOMPANY>'.custom_constants::tally_current_company.'</SVCURRENTCOMPANY>
                        </STATICVARIABLES>
                    </REQUESTDESC>
                 <REQUESTDATA>
              <TALLYMESSAGE xmlns:UDF="TallyUDF">';
        foreach($customers as $cKey=>$customer){
            $export = $this->import_ledger($customer,$params['account'], $requesttype = $params['requestType']);
            //print_r($insert);
            $xml.= $export;
        }
        $xml.='</TALLYMESSAGE>
                  </REQUESTDATA>
                  </IMPORTDATA>
                  </BODY>
        </ENVELOPE>';
        
        $ledgerContent = file_put_contents("ledgers.xml", $xml);
        force_download('ledgers.xml', NULL);
        return true;
    }

    function import_ledger($id, $accountType, $requesttype=''){
        //echo $id." ".$accountType;exit;
    
        //$id = 2;$accountType = 'customers';
        $parent = $this->tally_reverseledger_mapping();
        //print_r($parent);
        $ledger = [];
        $contactPerson = $mailingName = $ledgerName = '';
        $ledgerId = $id;
        //echo '<pre>';print_r($parent);exit;
        
        $addressData = [];
        if($accountType!='address'){
            
            $this->pktdblib->set_table($accountType);
            $ledger = $this->pktdblib->get_where($id);
            /*echo '<pre>';
            print_r($ledger);exit;*/
            $query = [];
            if($accountType!='customer_sites'){
               $query = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id inner join user_roles ur on ur.login_id=a.user_id and a.type like "login" where ur.user_id='.$id.' and ur.account_type="'.$accountType.'" AND a.is_default=true');
             
            }else{
                $query = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where a.id='.$ledger['address_id']);
            }
            
           //echo '<pre>'; print_r($query);exit;
            //echo '<pre>';
            if(count($query)>0){
              //echo "login not found<br>";
                $addressData = $query;
            }else{
                //echo "login found<br>";
              $address = $this->pktdblib->custom_query('select a.*,cn.name, st.state_name, ct.city_name, ar.area_name from address a left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where a.user_id='.$id.' and a.type="'.$accountType.'" And a.is_default=true');
              $addressData = $address;
            }
            //echo '<pre>'.$id;print_r($addressData);
            if(empty($addressData)){
                return '';
            }
            $contactPerson = $mailingName = isset($ledger['first_name'])?$ledger['first_name']." ".$ledger['middle_name']." ".$ledger['surname']:'';
            $ledgerName = (!empty($ledger['company_name']))?$ledger['company_name']:$contactPerson;
        }else{
            
            $query = $this->pktdblib->custom_query('select a.*,cn.name, st.state_name, ct.city_name, ar.area_name from address a left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where a.id='.$id);
              
           //echo '<pre>'; print_r($address);exit;
            //echo '<pre>';
            if(count($query)>0){
                if($query[0]['type']=='login' || $query[0]['type']=='employees'){
                    return false;
                }   
                $this->pktdblib->set_table($query[0]['type']);
                $ledger = $this->pktdblib->get_where($query[0]['user_id']);
                $ledgerId = $query[0]['user_id'];
                $addressData = $query;
                $accountType = $query[0]['type'];
                $contactPerson = $mailingName = isset($ledger['first_name'])?$ledger['first_name']." ".$ledger['middle_name']." ".$ledger['surname']:'';
                $ledgerName = ($query[0]['is_default']===FALSE)?$ledger['company_name']:$query[0]['site_name'];
            }else{
              return false;
            }
        }
        //echo $accountType;exit;
        if($accountType=='customers'){
          if(isset($_SESSION['application']['tally_has_multiple_address']) && $_SESSION['application']['tally_has_multiple_address']==TRUE){
            $customerSite = $this->pktdblib->custom_query('select id from customer_sites where customer_id='.$ledgerId.' and is_active=true');
            if(count($customerSite)>0){
              $multiAddress = $this->import_ledger2($customerSite[0]['id'], 'customer_sites');
              return $multiAddress;
            }
          }
        }/*else{
            echo "hello";
            exit;
        }*/
        /*echo '<pre>';
        print_r($ledger);
        print_r($addressData);
        
        exit;*/
        /*if($accountType!=='employees'){
            $mailingName  = $ledgerName = isset($ledger['company_name'])?$ledger['company_name']:'';
        }*/
        
        $gst_no = (isset($addressData[0]['gst_no']))?$addressData[0]['gst_no']:'Unregistered';
        if($gst_no=='Unregistered'){
            $gst_no = (isset($ledger['gst_no']) && !empty($ledger['gst_no']))?$ledger['gst_no']:'Unregistered';
        }
        $address1 = isset($addressData[0]['address_1'])?$addressData[0]['address_1']:'';
        $address2 = isset($addressData[0]['address_2'])?$addressData[0]['address_2']:'';
        $area = isset($addressData[0]['area_name'])?$addressData[0]['area_name']:'';
        $city = isset($addressData[0]['city_name'])?$addressData[0]['city_name']:'';
        $pincode = isset($addressData[0]['pincode'])?$addressData[0]['pincode']:'';
        $state = isset($addressData[0]['state_name'])?$addressData[0]['state_name']:'';
        $country = isset($addressData[0]['name'])?$addressData[0]['name']:'';
        $guid = isset($ledger['emp_code'])?$ledger['emp_code']:'';
        $pan = isset($ledger['pan_no'])?$ledger['pan_no']:'';
        //$gst_no = (isset($addressData[0]['gst_no'])?$addressData[0]['gst_no']:(isset($ledger['gst_no']) && !empty($ledger['gst_no']))?$ledger['gst_no']:'Unregistered');
        $email = isset($ledger['primary_email'])?$ledger['primary_email']:'';
        $contact = isset($ledger['contact_1'])?$ledger['contact_1']:'';
        /*echo '<pre>';
        print_r($ledger);
        exit;*/
        $custCode = $ledger['id'];
        
        $gstType = 'Regular';
        if(empty(trim($gst_no))){
            $gstType = 'Unregistered';
        }
        $xml = '
                   <LEDGER NAME="'.htmlspecialchars(trim($ledgerName)).' ('.$custCode.')" RESERVEDNAME="">
                    <ADDRESS.LIST TYPE="String">
                     <ADDRESS>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $address1).'</ADDRESS>
                     <ADDRESS>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $address2).'</ADDRESS>
                     <ADDRESS>'.$area.', '.$city.' - '.$pincode.'</ADDRESS>
                     <ADDRESS>FSSAI:'.(isset($addressData[0]['fssi'])?$addressData[0]['fssi']:'').'</ADDRESS>
                     
                    </ADDRESS.LIST>
                    <MAILINGNAME.LIST TYPE="String">
                     <MAILINGNAME>'.htmlspecialchars(trim($ledgerName)).'</MAILINGNAME>
                    </MAILINGNAME.LIST>
                    <OLDAUDITENTRYIDS.LIST TYPE="Number">
                     <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                    </OLDAUDITENTRYIDS.LIST>
                    <STARTINGFROM>'.date('Ymd',strtotime($ledger['created'])).'</STARTINGFROM>
                    <STREGDATE/>
                    <LBTREGNDATE/>
                    <SAMPLINGDATEONEFACTOR/>
                    <SAMPLINGDATETWOFACTOR/>
                    <ACTIVEFROM/>
                    <ACTIVETO/>
                    <CREATEDDATE>'.date('Ymd',strtotime($ledger['created'])).'</CREATEDDATE>
                    <ALTEREDON>'.date('Ymd',strtotime($ledger['modified'])).'</ALTEREDON>
                    <EMAIL>'.htmlspecialchars(strtolower($email)).'</EMAIL>
                    <STATENAME>'.$state.'</STATENAME>
                    <PINCODE>'.$pincode.'</PINCODE>
                    <INCOMETAXNUMBER>'.$pan.'</INCOMETAXNUMBER>
                    <COUNTRYNAME>'.$country.'</COUNTRYNAME>
                    <GSTREGISTRATIONTYPE>'.$gstType.'</GSTREGISTRATIONTYPE>
                    <PARENT>'.$parent[$accountType].'</PARENT>
                    <CREATEDBY>Yatish</CREATEDBY>
                    <ALTEREDBY>Yatish</ALTEREDBY>
                    <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
                    <LEDGERCONTACT>'.htmlspecialchars($contactPerson).'</LEDGERCONTACT>
                    <LEDGERMOBILE>'.$contact.'</LEDGERMOBILE>
                    <PARTYGSTIN>'.$gst_no.'</PARTYGSTIN>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TYPEOFINTERESTON>Voucher Date</TYPEOFINTERESTON>
                    <LEDSTATENAME>'.$state.'</LEDSTATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>Yes</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>
                    <ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>
                    <ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>
                    <ASORIGINAL>Yes</ASORIGINAL>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>No</AFFECTSSTOCK>
                    <ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>
                    <FORPAYROLL>No</FORPAYROLL>
                    <ISABCENABLED>No</ISABCENABLED>
                    <ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>
                    <INTERESTONBILLWISE>Yes</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>
                    <ISTDSEXPENSE>No</ISTDSEXPENSE>
                    <ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>
                    <ISRELATEDPARTY>No</ISRELATEDPARTY>
                    <USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>
                    <ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>
                    <APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>
                    <ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>
                    <INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>
                    <INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>
                    <ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>
                    <OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>
                    <ISAGAINSTFORMC>No</ISAGAINSTFORMC>
                    <ISCHEQUEPRINTINGENABLED>No</ISCHEQUEPRINTINGENABLED>
                    <ISPAYUPLOAD>No</ISPAYUPLOAD>
                    <ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>
                    <ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>
                    <ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>
                    <CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>
                    <ISTRANSPORTER>No</ISTRANSPORTER>
                    <USEFORNOTIONALITC>No</USEFORNOTIONALITC>
                    <ISECOMMOPERATOR>No</ISECOMMOPERATOR>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <ISTDSPROJECTED>No</ISTDSPROJECTED>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>
                    <ISSTXPARTY>No</ISSTXPARTY>
                    <ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>
                    <ISUSEDFORCVD>No</ISUSEDFORCVD>
                    <LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>
                    <ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>
                    <ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>
                    <ISSEZPARTY>No</ISSEZPARTY>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>
                    <ISEDDSUPPORTED>No</ISEDDSUPPORTED>
                    <HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>
                    <HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>
                    <HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>
                    <HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>
                    <HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>
                    <HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>
                    <HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>
                    <HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>
                    <HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>
                    <HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>
                    <ISEBANKINGENABLED>No</ISEBANKINGENABLED>
                    <ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>
                    <ISBATCHENABLED>No</ISBATCHENABLED>
                    <ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>
                    <HASEDDCITY>No</HASEDDCITY>
                    <HASECHEQUECITY>No</HASECHEQUECITY>
                    <ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>
                    <HASCLIENTCODE>No</HASCLIENTCODE>
                    <PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>
                    <PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>
                    <ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>
                    <ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>
                    <ISSCBUAE>No</ISSCBUAE>
                    <ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>
                    <ISSALARYGROUPED>No</ISSALARYGROUPED>
                    <USEFORPURCHASETAX>No</USEFORPURCHASETAX>
                    <AUDITED>No</AUDITED>
                    <SAMPLINGNUMONEFACTOR>0</SAMPLINGNUMONEFACTOR>
                    <SAMPLINGNUMTWOFACTOR>0</SAMPLINGNUMTWOFACTOR>
                    <SORTPOSITION> 1000</SORTPOSITION>
                    <DEFAULTLANGUAGE>0</DEFAULTLANGUAGE>
                    <RATEOFTAXCALCULATION>0</RATEOFTAXCALCULATION>
                    <GRATUITYMONTHDAYS>0</GRATUITYMONTHDAYS>
                    <GRATUITYLIMITMONTHS>0</GRATUITYLIMITMONTHS>
                    <CALCULATIONBASIS>0</CALCULATIONBASIS>
                    <ROUNDINGLIMIT>0</ROUNDINGLIMIT>
                    <ABATEMENTPERCENTAGE>0</ABATEMENTPERCENTAGE>
                    <TDSDEDUCTEESPECIALRATE>0</TDSDEDUCTEESPECIALRATE>
                    <BENEFICIARYCODEMAXLENGTH>0</BENEFICIARYCODEMAXLENGTH>
                    <ECHEQUEPRINTLOCATIONVERSION>0</ECHEQUEPRINTLOCATIONVERSION>
                    <ECHEQUEPAYABLELOCATIONVERSION>0</ECHEQUEPAYABLELOCATIONVERSION>
                    <EDDPRINTLOCATIONVERSION>0</EDDPRINTLOCATIONVERSION>
                    <EDDPAYABLELOCATIONVERSION>0</EDDPAYABLELOCATIONVERSION>
                    <PAYINSRUNNINGFILENUM>0</PAYINSRUNNINGFILENUM>
                    <TRANSACTIONTYPEVERSION>0</TRANSACTIONTYPEVERSION>
                    <PAYINSFILENUMLENGTH>0</PAYINSFILENUMLENGTH>
                    <TEMPVATRATE>0</TEMPVATRATE>
                    <TEMPADDLTAX>0</TEMPADDLTAX>
                    <TEMPCESSONVAT>0</TEMPCESSONVAT>
                    <TEMPLOCALVATRATE>0</TEMPLOCALVATRATE>
                    <LANGUAGENAME.LIST>
                     <NAME.LIST TYPE="String">
                      <NAME>'.htmlspecialchars(trim($ledgerName)).' ('.$custCode.')</NAME>
                     </NAME.LIST>
                     <LANGUAGEID> 1033</LANGUAGEID>
                    </LANGUAGENAME.LIST>';
                    //print_r($address);exit;
                    if(!($addressData[0]['is_default'])){
                  //$customerSites2 = $this->pktdblib->custom_query('Select cs.*, a.address_1, a.address_2, cn.name, st.state_name, ct.city_name, ar.area_name, a.pincode from customer_sites cs inner join address a on a.id=cs.address_id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where cs.customer_id='.$customerSites[0]['customer_id'].' and cs.is_active=true');
                  //foreach ($customerSites2 as $key => $value) {
                    //print_r($value);exit;
                   
                    $xml.= '
                    <LEDMULTIADDRESSLIST.LIST>
                            <ADDRESS.LIST TYPE="String">
                             <ADDRESS>'.$address[0]['site_name'].'</ADDRESS>
                             <ADDRESS>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $address[0]['address_1']).'</ADDRESS>
                             <ADDRESS>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $address[0]['address_2']).'</ADDRESS>
                             <ADDRESS>'.$address[0]['area_name'].', '.$address[0]['city_name'].'</ADDRESS>
                            </ADDRESS.LIST>
                            <PINCODE TYPE="String">'.$address[0]['pincode'].'</PINCODE>
                            
                            <COUNTRYNAME TYPE="String">'.$address[0]['name'].'</COUNTRYNAME>
                            <ADDRESSNAME TYPE="String">'.$address[0]['site_name'].'</ADDRESSNAME>
                            
                            <STATE TYPE="String">'.$address[0]['state_name'].'</STATE>
                            <ISOTHTERRITORYASSESSEE TYPE="Logical">No</ISOTHTERRITORYASSESSEE>
                            <EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>
                           </LEDMULTIADDRESSLIST.LIST>';
                  //}
                 }
                    $xml.='<INTERESTCOLLECTION.LIST>
                     <INTERESTSTYLE>365-Day Year</INTERESTSTYLE>
                     <INTERESTBALANCETYPE/>
                     <INTERESTFROMTYPE>Past Due Date</INTERESTFROMTYPE>
                     <INTERESTRATE> 24</INTERESTRATE>
                     <INTERESTAPPLFROM>0</INTERESTAPPLFROM>
                     <ROUNDLIMIT>0</ROUNDLIMIT>
                    </INTERESTCOLLECTION.LIST>
                    <UDF:VATDEALERNATURE.LIST DESC="`VATDealerNature`" ISLIST="YES" TYPE="String" INDEX="10031">
                     <UDF:VATDEALERNATURE DESC="`VATDealerNature`">Registered Dealer</UDF:VATDEALERNATURE>
                    </UDF:VATDEALERNATURE.LIST>
                   </LEDGER>
                  ';
        if($requesttype!='' && $requesttype=='export'){
            return $xml;
        }
        //echo '<pre>';echo $xml;exit;
        $importLedger = $this->curl_handling($xml);
        //print_r($importLedger);exit;
        if($this->uri->segment(1)=='tally'){
        //echo $xml;//exit;
        //exit;
        echo '<pre>';
        print_r($importLedger);
        exit;
        }
        return $importLedger;
    }

    function curl_handling($xml){
        //echo $xml;
        ini_set('display_errors', 1);
        ini_set('max_execution_time', 400);   
        ini_set("memory_limit","600M");
        $arr1 = array();
        $DSPDISPNAME = '';
        $BSSUBAMT = '';
        $BSMAINAMT = '';
        $error = [];
        try {
          $server = $this->tallyServer;
          //echo $server;
          $headers = array("Content-type: application/xml;charset=UTF-8", "Content-length: " . strlen($xml), "Connection: close");
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_URL, $server);
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          curl_setopt($ch, CURLOPT_TIMEOUT, 300);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
          curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLINFO_HEADER_OUT, true);
          curl_setopt($ch, CURLOPT_VERBOSE, true);
          $data = curl_exec($ch);
          
          if (curl_errno($ch)) { 
            $error =  curl_error($ch);
            print_r($error);
          } else {
            curl_close($ch);
            $data = str_replace('&#4; ', '', $data);
            $data = str_replace('', ' ', $data);
    
            //print_r($data);exit;
            $res = simplexml_load_string($data);
            //print_r($res);exit;
            return $res;
            //print_r($res->BODY->IMPORTDATA->REQUESTDATA->TALLYMESSAGE);exit;
            
          }
        }
        catch(Exception $e) {
            print_r($e->getMessage());
          return ['status'=>0, 'error'=>$e->getMessage()];
        }
    }

  function test_export($xml=''){
      //echo date("Y-m-d h:i:sa");exit;
    if($xml==''){

      $xml='<ENVELOPE>
     <HEADER>
             <VERSION>1</VERSION>
             <TALLYREQUEST>Export</TALLYREQUEST>
             <TYPE>Data</TYPE>
             <ID>Trial Balance</ID>
     </HEADER>
     <BODY>
             <DESC>
                     <STATICVARIABLES>
                               <EXPLODEFLAG>Yes</EXPLODEFLAG>
                               <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
                     </STATICVARIABLES>
             </DESC>
     </BODY>
</ENVELOPE>';
        /*$xml = '<ENVELOPE>
  <HEADER>
    <TALLYREQUEST>Export Data</TALLYREQUEST>
  </HEADER>
  <BODY>
    <EXPORTDATA>
      <REQUESTDESC>
        <STATICVARIABLES>
          <!--Specify the Report FORMAT here-->
          <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
          <ACCOUNTTYPE>Ledgers</ACCOUNTTYPE>
          <!-- Specify the LedgerName here -->
          <LEDGERNAME>AARYA PALLET MANUFACTURERS</LEDGERNAME>
        </STATICVARIABLES>
        <!-- Report Name -->
        <REPORTNAME>List of Accounts</REPORTNAME>
      </REQUESTDESC>
    </EXPORTDATA>
  </BODY>
</ENVELOPE>';*/
           
    }
    $res = $this->curl_handling($xml);
    echo "<pre>";
    print_r($res);exit;
  }

  function productSalesOrderMapping(){

     $prodSOMapping = [
      '0'=>'product_size',
      '1'=>'coil_no',
      '2'=>'make',
      '3'=>'ctl',
      '4'=>'product_size',
      '5'=>'coil_no',
      '6'=>'make',
      '7'=>'ctl',
      '8'=>'product_size',
      '9'=>'coil_no',
      '10'=>'make',
      '11'=>'ctl',
      '12'=>'product_size',
      '13'=>'coil_no',
      '14'=>'make',
      '15'=>'ctl'
    ];
    return $prodSOMapping;
  }

  function tally_sales_order2($xml=''){

    if($xml==''){
      $xml = '<ENVELOPE>
      <HEADER>
      <TALLYREQUEST>Export Data</TALLYREQUEST>
      </HEADER>
      <BODY>
      <EXPORTDATA>
      <REQUESTDESC>
      <STATICVARIABLES>
      <SVFROMDATE>20200204</SVFROMDATE>
      <SVTODATE>20200204</SVTODATE>
      <!--Theis will show the User name who created Voucher-->
      <SHOWCREATEDBY>YES</SHOWCREATEDBY>
      <SHOWPARTYNAME>Yes</SHOWPARTYNAME>
      <!--Specify the Voucher Type here-->
      <!-- Ex . Sales/Sale Export -->
      <VOUCHERTYPENAME>Sales Order</VOUCHERTYPENAME>
      </STATICVARIABLES>
      <!--Specify the Report Name here-->
      <REPORTNAME>Voucher Register</REPORTNAME>
      <STATICVARIABLES>
          <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
      </STATICVARIABLES>
      </REQUESTDESC>
      </EXPORTDATA>
      </BODY>
      </ENVELOPE>';
    }
    $res = $this->curl_handling($xml);
    //$res = $this->sample_salesorder();
    echo '<pre>';
    print_r($res);exit;
    $order = [];
    $error = [];
    echo "<pre>";
    //print_r($res->BODY->IMPORTDATA->REQUESTDATA);exit;
    $id = 0;
    $flag = 0;
    foreach ((array)$res->BODY->IMPORTDATA->REQUESTDATA as $soKey => $salesorder) {
      if($soKey!=='@attributes'){
        $salesorder = json_decode(json_encode($salesorder, true));
        foreach ((array)$salesorder as $saleSOkey => $voucher) {
          $voucher = (array)$voucher; 
          if(!isset($voucher['VOUCHER']))
            continue;
          $tax_rate = $voucher['VOUCHER']->CLASSNAME;
          $tax = substr($tax_rate, 7,2);
          $query = $this->pktdblib->custom_query('select * from tally_ledger where ledger_name="'.$voucher['VOUCHER']->PARTYNAME.'"');
                //print_r($query);exit;
          if(count($query)>0){
            $userId = $query[0]['user_id'];
            $order[$saleSOkey]['customer_id'] = $userId;
          }
          if(isset($voucher['VOUCHER']->BASICORDERREF) && !empty($voucher['VOUCHER']->BASICORDERREF)){

            /*print_r($voucher['VOUCHER']->BASICORDERREF);
            echo '<br><br>';*/
            if(!isset($voucher['VOUCHER']->BASICORDERREF)){
              $broker[0] = 'Direct';
            }else{

            }
            $broker = explode('-', $voucher['VOUCHER']->BASICORDERREF);
            //print_r($broker);
            $tallyLedger = $this->pktdblib->custom_query('Select * from tally_ledger where account_type="brokers" and ledger_name like "'.$broker[0].'"');
            //print_r($tallyLedger);
            $order[$saleSOkey]['broker_id'] = isset($tallyLedger[0]['user_id'])?$tallyLedger[0]['user_id']:0;
            $order[$saleSOkey]['brokerage_type'] = 'percentage';
            $order[$saleSOkey]['brokerage_value'] = isset($broker[1])?(float)trim($broker[1]):0;
          }
          $orderTerm = (array)$voucher['VOUCHER'];
          $order[$saleSOkey]['order_date'] = isset($voucher['VOUCHER']->DATE)?date('Y-m-d', strtotime($voucher['VOUCHER']->DATE)):'';
          $order[$saleSOkey]['created'] = $order[$saleSOkey]['modified'] = date('Y-m-d H:i:s');
          $order[$saleSOkey]['order_code'] =  isset($voucher['VOUCHER']->VOUCHERNUMBER)?$voucher['VOUCHER']->VOUCHERNUMBER:'';
          $orderQuery = $this->pktdblib->custom_query('select * from orders where order_code="'.$order[$saleSOkey]['order_code'].'"');
          if (count($orderQuery)>0) {
            continue;
          }
          if ($flag == 0) {
            $orderDate = $this->pktlib->get_fiscal_year($order[$saleSOkey]['order_date']);
            $maxId = $this->pktdblib->custom_query('select max(id) as id from orders where fiscal_yr="'.$orderDate.'"');
            /*$order[$saleSOkey]['id'] = $maxId[0]['id']++;*/
            $id = $id+1;
          }else{
            $id = $id+1;
          }
          $order[$saleSOkey]['id'] = $id;
          $order[$saleSOkey]['no_of_days'] = isset($orderTerm['BASICDUEDATEOFPYMT'])?$orderTerm['BASICDUEDATEOFPYMT']:'';
          $order[$saleSOkey]['other_charges'] = isset($orderTerm['LEDGERENTRIES.LIST'][1]->AMOUNT)?$orderTerm['LEDGERENTRIES.LIST'][1]->AMOUNT:'';
          $order[$saleSOkey]['terms_of_delivery'] = isset($orderTerm['BASICORDERTERMS.LIST']->BASICORDERTERMS)?$orderTerm['BASICORDERTERMS.LIST']->BASICORDERTERMS:'';
          foreach($voucher as $vKey => $addressList){
            $addressList = (array)$addressList;
            $this->pktdblib->set_table('products');
            $productStockName = isset($addressList['ALLINVENTORYENTRIES.LIST'])?$addressList['ALLINVENTORYENTRIES.LIST']:'';
            $orderDetail = [];
            $sumAmount=[];
          }
        }
      }
    }
    print_r($order);exit;
  }

  function ledger_from_ledgername($xml='')
  {
    if($xml==''){
      $xml = '<ENVELOPE>
              <HEADER>
              <TALLYREQUEST>Export Data</TALLYREQUEST>
              </HEADER>
              <BODY>
              <EXPORTDATA>
              <REQUESTDESC>
              <REPORTNAME>Ledger Vouchers</REPORTNAME>
              <STATICVARIABLES>
              <SVCURRENTCOMPANY>'.custom_constants::tally_current_company.'</SVCURRENTCOMPANY>
              <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
              <LEDGERNAME>AAKAF STEEL PRIVATE LIMITED (GUJARAT)</LEDGERNAME>
              </STATICVARIABLES>
              </REQUESTDESC>
              </EXPORTDATA>
              </BODY>
              </ENVELOPE>';
               }
      $res = $this->curl_handling($xml);
    //$res = $this->sample_salesorder();
    echo '<pre>';
    print_r($res);exit;
  }
  
     function individual_outstanding($ledgerName){
    $ledgerName = htmlspecialchars($ledgerName);
      $xml = '<ENVELOPE>
      <HEADER>
      <TALLYREQUEST>Export Data</TALLYREQUEST>
      </HEADER>
      <BODY>
      <EXPORTDATA>
      <REQUESTDESC>
      <STATICVARIABLES>
      <LEDGERNAME>'.$ledgerName.'</LEDGERNAME>
      <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
      </STATICVARIABLES>
      <REPORTNAME>Ledger Outstandings</REPORTNAME>
      </REQUESTDESC>
      </EXPORTDATA>
      </BODY>
      </ENVELOPE>';
    $res = Modules::run("tally/curl_handling",$xml);
    return $res;
  }

  function broker_outstanding($brokerName){
      $xml = '<ENVELOPE>
              <HEADER>
              <TALLYREQUEST>Export Data</TALLYREQUEST>
              </HEADER>
              <BODY>
              <EXPORTDATA>
              <REQUESTDESC>
              <STATICVARIABLES>

              <!--Specify the Export format here  HTML or XML or SDF-->
              <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
              </STATICVARIABLES>

              <!--Specify the Report Name here-->
              <REPORTNAME>Bills Receivable</REPORTNAME>

              </REQUESTDESC>
              </EXPORTDATA>
              </BODY>
              </ENVELOPE>';
               
    //}
    $res = $this->curl_handling($xml);
    $res = json_decode(json_encode($res), true);
    $broker = [];
    $outstanding = [];
    foreach ($res['AJS_OS_BROKERNAME'] as $key => $value) {
      if (!empty($value) && $value == $brokerName) {
        $broker[$key] = $value;
        $outstanding[$key] = $res['BILLCL'][$key];
      }
      /*else
      {
        $broker[$key] = 0;
      }*/
    }
    $outstandings = abs(array_sum($outstanding));
    return number_format($outstandings, 2) ;
  }

  
   function broker_billwise_outstanding($brokerName = NULL){
    if(NULL!==$brokerName || $brokerName!=0){ 
      $brokerName = base64_decode($brokerName);
    }
    //echo $brokerName.'<br>';//exit;
    $xml = '<ENVELOPE>
            <HEADER>
            <TALLYREQUEST>Export Data</TALLYREQUEST>
            </HEADER>
            <BODY>
            <EXPORTDATA>
            <REQUESTDESC>
            <STATICVARIABLES>

            <!--Specify the Export format here  HTML or XML or SDF-->
            <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
            </STATICVARIABLES>

            <!--Specify the Report Name here-->
            <REPORTNAME>Bills Receivable</REPORTNAME>

            </REQUESTDESC>
            </EXPORTDATA>
            </BODY>
            </ENVELOPE>';
    $res = $this->curl_handling($xml);
    $res = json_decode(json_encode($res), true);
    $this->pktdblib->set_table('brokers');
    $brokers = $this->pktdblib->get('created asc');
    $data['option']['broker'][] = 'Select Broker';
    $data['outstanding'] = [];
    if(isset($res['AJS_OS_BROKERNAME'])){
      foreach ($res['AJS_OS_BROKERNAME'] as $key => $value) {
        $data['option']['broker'][base64_encode((is_array($value))?'NB':$value)] = (is_array($value))?'NB':$value;
        if ($brokerName!==NULL) { 
          
          if($brokerName==$value){
            $data['outstanding'][(!empty($value))?$value:'NB'][$key]['broker_name'] = (is_array($value))?'NB':$value;
            $data['outstanding'][(!empty($value))?$value:'NB'][$key]['outstanding'] = $res['BILLCL'][$key];
            $data['outstanding'][(!empty($value))?$value:'NB'][$key]['bill_no'] = $res['BILLFIXED'][$key]['BILLREF'];
            $data['outstanding'][(!empty($value))?$value:'NB'][$key]['bill_party'] = $res['BILLFIXED'][$key]['BILLPARTY'];
            $data['outstanding'][(!empty($value))?$value:'NB'][$key]['bill_date'] = $res['BILLFIXED'][$key]['BILLDATE'];

          }
          
        }else{
          //$data['option']['broker'][base64_encode((is_array($value))?'NB':$value)] = (is_array($value))?'NB':$value;
          $data['outstanding'][(!empty($value))?$value:'NB'][$key]['broker_name'] = (is_array($value))?'NB':$value;
          $data['outstanding'][(!empty($value))?$value:'NB'][$key]['outstanding'] = $res['BILLCL'][$key];
          $data['outstanding'][(!empty($value))?$value:'NB'][$key]['bill_no'] = $res['BILLFIXED'][$key]['BILLREF'];
          $data['outstanding'][(!empty($value))?$value:'NB'][$key]['bill_party'] = $res['BILLFIXED'][$key]['BILLPARTY'];
            $data['outstanding'][(!empty($value))?$value:'NB'][$key]['bill_date'] = $res['BILLFIXED'][$key]['BILLDATE'];


        }
      }
    }
    //print_r($data['outstanding']);exit;
    $data['meta_title'] = "ERP: Broker Outstanding";
    $data['title'] = "ERP : Tally Module";
    $data['meta_description'] = "Broker Outstanding";
    $data['heading'] = '<i class="fa fa-shopping-cart margin-r-5"></i> Broker Outstanding';
    $data['modules'][] = 'tally';
    $data['methods'][] = 'outstanding_view';
    echo Modules::run("templates/admin_template", $data);
  }

  function outstanding_view(){
    $this->load->view('tally/broker_outstanding');

  }

  function customer_outstanding($customerName = NULL){

    if(NULL!==$customerName || $customerName =0){ 
      $customerName = base64_decode($customerName);
    }

    $xml = '
          <ENVELOPE>
          <HEADER>
          <TALLYREQUEST>Export Data</TALLYREQUEST>
          </HEADER>
          <BODY>
          <EXPORTDATA>
          <REQUESTDESC>
          <STATICVARIABLES>
          <GROUPNAME>Sundry Debtors</GROUPNAME>
          <SHOWBYBILL>YES</SHOWBYBILL>
           <SHOWBROKERNAME>YES</SHOWBROKERNAME>
          <EXPLODEFLAG>Yes</EXPLODEFLAG>
          <SHOWBILLTYPE>$$SysName:AllBills</SHOWBILLTYPE>
          <!--$$SysName:DebitBills  $$SysName:CreditBills -->
          <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
          </STATICVARIABLES>
          <REPORTNAME>Group Outstandings</REPORTNAME>
          </REQUESTDESC>
          </EXPORTDATA>
          </BODY>
          </ENVELOPE>
          ';
    $res = $this->curl_handling($xml);
    $res = json_decode(json_encode($res), true);
    //print_r($res);exit;
    $this->pktdblib->set_table('customers');
    $customers = $this->pktdblib->get('created asc');
    $data['option']['customer'][] = 'Select Customer';
    $data['outstanding'] = [];
    foreach ($res['BILLFIXED'] as $key => $value) {
          $partybill = $value['BILLPARTY'];
          $data['option']['customer'][base64_encode($partybill)] = $partybill;
        if ($customerName!==NULL) { 
          if($customerName==$value['BILLPARTY']){
            $data['outstanding'][$partybill][$key]['outstanding'] = $res['BILLCL'][$key];
            $data['outstanding'][$partybill][$key]['bill_no'] = $res['BILLFIXED'][$key]['BILLREF'];
            $data['outstanding'][$partybill][$key]['bill_party'] = $res['BILLFIXED'][$key]['BILLPARTY'];
            $data['outstanding'][$partybill][$key]['bill_date'] = $res['BILLFIXED'][$key]['BILLDATE'];
            $data['outstanding'][$partybill][$key]['bill_due'] = $res['BILLDUE'][$key];
            $data['outstanding'][$partybill][$key]['bill_over_due'] = $res['BILLOVERDUE'][$key];
        }
      }
    }
    $data['meta_title'] = "ERP: Customer Outstanding";
    $data['title'] = "ERP : Customer Outstanding";
    $data['meta_description'] = "Customer Outstanding";
    $data['heading'] = '<i class="fa fa-shopping-cart margin-r-5"></i> Customer Outstanding';
    $data['title'] = 'Modules :: Tally Module';
    $data['modules'][] = 'tally';
    $data['methods'][] = 'customer_outstanding_view';
    
    echo Modules::run("templates/admin_template", $data);
  }

   function customer_outstanding_view(){
    $this->load->view('tally/customer_outstanding');

  }
  
   function export_multiadd_tally_ledger(){
        if(date('H')<11 || date('H')>21 || date('D') == 'Sun')
        {
            return true;
        }
        if(date('D') == 'Sun') { 
          return true;
        }
      $xml = '<ENVELOPE>
          <HEADER>
            <VERSION>1</VERSION>
            <TALLYREQUEST>EXPORT</TALLYREQUEST>
            <TYPE>COLLECTION</TYPE>
            <ID>Remote Ledger Coll</ID>
          </HEADER>
          <BODY>
            <DESC>
              <STATICVARIABLES>
              <SVEXPORTFORMAT>\$\$SysName:XML</SVEXPORTFORMAT>
              </STATICVARIABLES>
              <TDL>
                <TDLMESSAGE>
                  <COLLECTION NAME="Remote Ledger Coll"
                  ISINITIALIZE="Yes">
                    <TYPE>LEDGER</TYPE>
                    <NATIVEMETHOD>PARENT</NATIVEMETHOD>
                    <NATIVEMETHOD>ledger</NATIVEMETHOD>
                    <NATIVEMETHOD>email</NATIVEMETHOD>
                    <NATIVEMETHOD>ADDRESS</NATIVEMETHOD>
                    <NATIVEMETHOD>LEDMULTIADDRESSLIST.LIST</NATIVEMETHOD>
                    <NATIVEMETHOD>MULTIADDRESS</NATIVEMETHOD>
                    <NATIVEMETHOD>pincode</NATIVEMETHOD>
                    <NATIVEMETHOD>guid</NATIVEMETHOD>
                    <NATIVEMETHOD>emailcc</NATIVEMETHOD>
                    <NATIVEMETHOD>name</NATIVEMETHOD>
                    <NATIVEMETHOD>PARTYGSTIN</NATIVEMETHOD>
                    <NATIVEMETHOD>CREATEDDATE</NATIVEMETHOD>
                    <NATIVEMETHOD>ALTEREDON</NATIVEMETHOD>
                    <NATIVEMETHOD>LEDGERMOBILE</NATIVEMETHOD>
                    <NATIVEMETHOD>LEDSTATENAME</NATIVEMETHOD>
                    <NATIVEMETHOD>COUNTRYOFRESIDENCE</NATIVEMETHOD>
                    <NATIVEMETHOD>ALTERID</NATIVEMETHOD>
                    <NATIVEMETHOD>OPENINGBALANCE</NATIVEMETHOD>
                    <NATIVEMETHOD>INCOMETAXNUMBER</NATIVEMETHOD>
                  </COLLECTION>
                </TDLMESSAGE>
              </TDL>
            </DESC>
          </BODY>
        </ENVELOPE>';
  
        $error = [];
        $sundrycreditor = [];
        $res = $this->curl_handling($xml);
        /*echo "<pre>";
        print_r($res);exit();*/
        $typeMapping = $this->usertype_mapping();
        //echo '<pre>';print_r($typeMapping);exit;
        $this->pktdblib->set_table('tally_ledger');
        $ledger = $this->pktdblib->get('ledger_name');
        /*echo "<pre>";
        print_r($ledger);exit;*/
        $ledgerAccounts = [];
        //print_r($ledger->result_array());
        foreach ($ledger->result_array() as $key => $account) {
          $ledgerAccounts[] = $account['ledger_name'];
        }

        $loopCount = 0;
        //print_r($ledgerAccounts);exit();
        foreach ((array)$res->BODY->DATA->COLLECTION as $colKey => $collection) {
          //echo $colKey.'<br>';
          if($colKey!=='@attributes'){
            $collection = json_decode(json_encode($collection, true));
            foreach ($collection as $ledgerKey => $ledger) {
              $ledger = (array)$ledger;
              if(!array_key_exists($ledger['PARENT'], $typeMapping)){
                $typeMapping[$ledger['PARENT']] = 'na';
                continue;
              }
              if($typeMapping[$ledger['PARENT']]=='vendors'){
                    //continue;
              }
              if(in_array(isset($ledger['NAME'])?$ledger['NAME']:$ledger['@attributes']->NAME, $ledgerAccounts)){
                //echo "data present ".$ledgerKey.'<br>';
                continue;
              }
              if($loopCount>=25){
                break;
              }

              $userData = [];
              $userEmail = [];
              $tallyLedgerArray = [];
             // print_r($ledger);exit();

              $counter = isset($sundrycreditor[$typeMapping[$ledger['PARENT']]])?count($sundrycreditor[$typeMapping[$ledger['PARENT']]]):0;
              //echo $ledger->PARENT.'<br>';
              $sundrycreditor[$typeMapping[$ledger['PARENT']]][$counter] = $ledger;
              //print_r($typeMapping[$ledger['PARENT']]);//exit;p
              $userData['first_name'] = isset($ledger['NAME'])?$ledger['NAME']:$ledger['@attributes']->NAME;
              $tallyLedgerArray['ledger_name'] = $userData['first_name'];
              $tallyLedgerArray['account_type'] = $typeMapping[$ledger['PARENT']]; 

              if($typeMapping[$ledger['PARENT']]=== 'na'){
                $tallyLedgerArray['user_id']  = 0;
              }else{
                //print_r($ledger['@attributes']);exit();
                $userData['primary_email'] = isset($ledger['EMAIL'])?$ledger['EMAIL']:NULL;
                $userData['contact_1'] = isset($ledger['PHONE'])?$ledger['PHONE']:'';
                $userData['gst_no'] = isset($ledger['PARTYGSTIN'])?$ledger['PARTYGSTIN']:'';
                $userData['pan_no'] = isset($ledger['INCOMETAXNUMBER'])?$ledger['INCOMETAXNUMBER']:'';
                $userData['created'] = $userData['modified']=date('Y-m-d H:i:s');
                // print_r($typeMapping[$ledger['PARENT']]);exit;
                // print_r($userData);exit;
                if($typeMapping[$ledger['PARENT']] == 'employees'){
                  unset($userData['gst_no']);
                }else{
                  $userData['company_name'] = $userData['first_name'];
                }
                $response=json_decode(Modules::run($typeMapping[$ledger['PARENT']].'/_register_admin_add', $userData), true);
                //print_r($response[$typeMapping[$ledger['PARENT']]]);
                //print_r($response);//exit;
                $loginArray = [];
                if(isset($ledger['EMAIL']))
                {
                  $userEmail[] = $ledger['EMAIL'];
                }
                $addressArray[0] = ['type'=>$typeMapping[$ledger['PARENT']]];
                $addressArray[0]['user_id'] = $tallyLedgerArray['user_id'] = $response[$typeMapping[$ledger['PARENT']]]['id'];
                  $str = '';
                  $mAddress = [];
                  $addressArray = [];
                if (isset($ledger['ADDRESS.LIST'])) {
                  $ledger['ADDRESS.LIST'] = (array)$ledger['ADDRESS.LIST'];
                  if (is_array($ledger['ADDRESS.LIST']['ADDRESS'])) {
                    $str = implode(" ",$ledger['ADDRESS.LIST']['ADDRESS']);
                  }
                  if(trim($str)!=''){
                    $addressArray[0]['address_1'] = $str;
                    $addressArray[0]['tally_address'] = $str;
                    if(isset($ledger['LEDSTATENAME'])){
                      $this->pktdblib->set_table('states');
                      $query = $this->pktdblib->get_where_custom('state_name', $ledger['LEDSTATENAME']);
                      if($query->num_rows()){
                        $state = $query->row_array();
                        $addressArray[0]['state_id'] = $state['id'];
                      }
                    }
                    $addressArray[0]['country_id'] = 1;
                    $addressArray[0]['created'] = $addressArray[0]['modified'] = date('Y-m-d H:i:s');
                    $addressArray[0]['site_name'] = $userData['first_name'];
                    //$addressCount = count($addressArray);
                  }else{
                    unset($addressArray[0]);
                  }
                }
                $count = count($addressArray);
                  if (is_array($ledger['LEDMULTIADDRESSLIST.LIST'])) {
                    $mulLedgerAddress = json_decode(json_encode($ledger['LEDMULTIADDRESSLIST.LIST']), true);
                    //foreach ($count as $key => $value) {
                      foreach ($mulLedgerAddress as $addKey => $addValue) {
                        
                          if (is_array($addValue[$addKey]['ADDRESS.LIST']['ADDRESS'])) {
                            $mAddress[$addKey] = implode(' ', $addValue[$addKey]['ADDRESS.LIST']['ADDRESS']);
                          }
                          if(trim($mAddress[$addKey])!=''){
                            $addressArray[count($addressArray)] = ['type'=>$typeMapping[$ledger['PARENT']]];
                            $addressArray[count($addressArray)]['user_id'] = $tallyLedgerArray['user_id'] = $response[$typeMapping[$ledger['PARENT']]]['id'];
                            $addressArray[count($addressArray)]['address_1'] = $mAddress[$addKey];
                            $addressArray[count($addressArray)]['tally_address'] = $mAddress[$addKey];
                            if(isset($mulLedgerAddress[$addKey]['STATE'])){
                              $this->pktdblib->set_table('states');
                              $query = $this->pktdblib->get_where_custom('state_name', $mulLedgerAddress[$addKey]['STATE']);
                              if($query->num_rows()){
                                $state = $query->row_array();
                                $addressArray[count($addressArray)]['state_id'] = $state['id'];
                              }
                            }
                            $addressArray[count($addressArray)]['country_id'] = 1;
                            $addressArray[count($addressArray)]['created'] = $addressArray[count($addressArray)]['modified'] = date('Y-m-d H:i:s');
                            $addressArray[count($addressArray)]['site_name'] = $mulLedgerAddress[$addKey]['ADDRESSNAME'];
                          }
                      }
                    //}
                }
                //print_r($addressArray);exit;
                $this->pktdblib->set_table('address');
                $insAddress = $this->pktdblib->_insert_multiple($addressArray);
              }
              $tallyLedgerArray['is_active'] = 1;
              $tallyLedgerArray['created'] = $tallyLedgerArray['modified'] = date('Y-m-d H:i:s');
              //$this->pktdblib->set_table('tally_ledger');
              //$insTallyLedger = $this->pktdblib->_insert($tallyLedgerArray);
              $loopCount = ++$loopCount;
              //echo '<pre>';print_r($tallyLedgerArray);
            }
          }
        } 
    }
    
    function import_salesorder2($orderCode) {
    // echo 'hii';exit;
    //echo '<pre>';print_r($_SESSION);exit;
    //echo "reached in tally import_salesorder";exit;
    //echo urldecode($orderCode);exit;
    $orderCode = base64_decode($orderCode);
    //echo $orderCode;exit;
    //echo '<pre>';
    $this->pktdblib->set_table('orders');
    $code = $this->pktdblib->get_where_custom('order_code', $orderCode);
    $orderData = $code->result_array();
    $order = $orderData[0];
    /*echo '<pre>';
    print_r($order);
    exit;*/
    //echo "<pre>";
    $orderDetails = $this->pktdblib->custom_query('select od.*, p.product, p.tally_name from order_details od left join products p on p.id=od.product_id left join orders o on o.order_code=od.order_code where od.order_code="'.$order['order_code'].'"');
    //echo $this->db->last_query();exit;
    //echo '<pre>';
    //print_r($orderCode);//exit;
    $invoiceAddressQuery = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join orders o on o.invoice_address_id=a.id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where o.order_code="'.$orderCode.'"');
    //echo "invoiceAddress".'<br>';
    //print_r($invoiceAddressQuery);
    $invoiceAddress = isset($invoiceAddressQuery[0]['tally_address'])?$invoiceAddressQuery[0]['tally_address']:'';
    $invoicePincode = isset($invoiceAddressQuery[0]['pincode'])?$invoiceAddressQuery[0]['pincode']:'';
    $invoiceState = isset($invoiceAddressQuery[0]['state_name'])?$invoiceAddressQuery[0]['state_name']:'';
    $invoiceCity = isset($invoiceAddressQuery[0]['city_name'])?$invoiceAddressQuery[0]['city_name']:'';
    $invoiceCountry = isset($invoiceAddressQuery[0]['name'])?$invoiceAddressQuery[0]['name']:'';

    $deliveryAddressQuery = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join orders o on o.delivery_address_id=a.id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where o.order_code="'.$orderCode.'"');
    //print_r($deliveryAddressQuery);exit;
    $deliveryAddress = isset($deliveryAddressQuery[0]['tally_address'])?$deliveryAddressQuery[0]['tally_address']:'';
    $deliveryState = isset($deliveryAddressQuery[0]['state_name'])?$deliveryAddressQuery[0]['state_name']:'';
    $deliveryCity = isset($deliveryAddressQuery[0]['city'])?$deliveryAddressQuery[0]['city']:'';
    $deliveryCountry = isset($deliveryAddressQuery[0]['name'])?$deliveryAddressQuery[0]['name']:'';
    $deliveryPincode = isset($deliveryAddressQuery[0]['pincode'])?$deliveryAddressQuery[0]['pincode']:'';
    //print_r($deliveryAddressQuery);exit();
    $customerAddress = $this->pktdblib->custom_query('select a.* from address a where type="customers" and user_id='.$order['customer_id']);
    $this->pktdblib->set_table('customers');
    $customer = $this->pktdblib->get_where($order['customer_id']);
    //echo '<pre>';print_r($order['customer_site_id']);exit;
    if($order['customer_site_id']!==0){
      //echo "hiiiii";//exit;
      $this->pktdblib->set_table('customer_sites');
      $site = $this->pktdblib->get_where($order['customer_site_id']);
     
      if (!empty($site)) {
        /*if ($site!==0) {*/
        //echo "reached here";exit;
          $this->pktdblib->set_table('customer_sites');
          $customer = $this->pktdblib->get_where($site['id']);
      }
    }
    //exit;
    $this->pktdblib->set_table('brokers');
    $broker = $this->pktdblib->get_where($order['broker_id']);
    //print_r($broker);exit;
    $ledgerName = isset($customer['company_name'])?$customer['company_name']:'';
    //echo $ledgerName;exit;
    $orderCode = isset($order['order_code'])?$order['order_code']:'';
    $paymentDueDay = isset($order['no_of_days'])?$order['no_of_days']:'';
    //echo '<pre>';
    //print_r($order);exit;
    $tax = isset($order['amount_before_tax'])?($order['amount_after_tax']-$order['amount_before_tax'])/$order['amount_before_tax']*100:0;
    //$tax = ($order['amount_before_tax']/$order['amount_before_tax'])*100;//isset($order['tax'])?$order['tax']:'';
    //echo $tax;exit;
    $deliveryTerm = isset($order['terms_of_delivery'])?$order['terms_of_delivery']:'';
    $orderCreatedDate = isset($order['created'])?date('Ymd', strtotime($order['created'])):'';
    $orderModifiedDate = isset($order['modified'])?date('Ymd', strtotime($order['modified'])):'';
    $amt_before_tax = isset($order['amount_before_tax'])?$order['amount_before_tax']:'';
    $gst = '';
    $gstNo = isset($customer['gst_no'])?$customer['gst_no']:'';
    //echo $gstNo;exit;
    $brokerLedgerName = isset($broker['company_name'])?$broker['company_name']:'';
    $sumAmount = [];
    $total_amount = '';
    $roundOff = '';
    //echo $brokerLedgerName;exit;
    $tax=round($tax);
    $this->pktdblib->set_table('companies');
    $company = $this->pktdblib->get_where(custom_constants::company_id);
    
/*<STATICVARIABLES>
               <SVCURRENTCOMPANY>'.custom_constants::tally_current_company.'</SVCURRENTCOMPANY>
              </STATICVARIABLES>*/
    $xml = '<ENVELOPE>
             <HEADER>
              <TALLYREQUEST>Import Data</TALLYREQUEST>
             </HEADER>
             <BODY>
              <IMPORTDATA>
               <REQUESTDESC>
                <REPORTNAME>Vouchers</REPORTNAME>
               </REQUESTDESC>
             <REQUESTDATA>
              <TALLYMESSAGE xmlns:UDF="TallyUDF">
               <VOUCHER REMOTEID="'.$order['order_code'].'"  VCHTYPE="Sales Order"  ACTION="Create" OBJVIEW="Invoice Voucher View">
                <ADDRESS.LIST TYPE="String">
                 <ADDRESS>'.htmlspecialchars($invoiceAddressQuery[0]['address_1']).'</ADDRESS>
                 <ADDRESS>'.htmlspecialchars($invoiceAddressQuery[0]['address_2']).'</ADDRESS>
                 <ADDRESS>'.$invoiceAddressQuery[0]['area_name'].', '.$invoiceAddressQuery[0]['city_name'].'</ADDRESS>
                 <ADDRESS>'.$invoiceAddressQuery[0]['state_name'].', '.$invoiceAddressQuery[0]['name'].'</ADDRESS>
                 <ADDRESS>'.$invoicePincode.'</ADDRESS>

                </ADDRESS.LIST>
                <BASICBUYERADDRESS.LIST TYPE="String">
                 <BASICBUYERADDRESS>'.htmlspecialchars($deliveryAddressQuery[0]['address_1']).'</BASICBUYERADDRESS>
                 <BASICBUYERADDRESS>'.htmlspecialchars($deliveryAddressQuery[0]['address_2']).'</BASICBUYERADDRESS>
                 <BASICBUYERADDRESS>'.$deliveryAddressQuery[0]['area_name'].', '.$deliveryAddressQuery[0]['city_name'].'</BASICBUYERADDRESS>
                 <BASICBUYERADDRESS>'.$deliveryAddressQuery[0]['state_name'].', '.$deliveryAddressQuery[0]['name'].'</BASICBUYERADDRESS>
                 <BASICBUYERADDRESS>'.$deliveryPincode.'</BASICBUYERADDRESS>

                </BASICBUYERADDRESS.LIST>
                <BASICORDERTERMS.LIST TYPE="String">
                 <BASICORDERTERMS>'.$deliveryTerm.'</BASICORDERTERMS>
                </BASICORDERTERMS.LIST>
                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                 <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                </OLDAUDITENTRYIDS.LIST>
                <ACTIVETO/>
                <DATE>'.date('Ymd', strtotime($order['order_date'])).'</DATE>
                <STATENAME>'.$invoiceState.'</STATENAME>
                
                <NARRATION>'.htmlspecialchars($order['message']).'</NARRATION>
                <ALTEREDBY>AJS</ALTEREDBY>
                <COUNTRYOFRESIDENCE>'.$invoiceCountry.'</COUNTRYOFRESIDENCE>
                <PARTYGSTIN>'.$gstNo.'</PARTYGSTIN>';
               
                if (substr($customer['gst_no'], 0, 2) == substr($company['gst_no'], 0, 2)) {
                $xml.='<CLASSNAME>SALES @'.$tax.'% GST</CLASSNAME>';
                }else{
                    $xml.='<CLASSNAME>SALES INTERSTATE @ '.$tax.'%</CLASSNAME>';
                }
                
                $xml.='<PARTYNAME>'.htmlspecialchars($ledgerName).'</PARTYNAME>
                <VOUCHERTYPENAME>Sales Order</VOUCHERTYPENAME>
                <REFERENCE>'.$orderCode.'</REFERENCE>
                <VOUCHERNUMBER>'.$orderCode.'</VOUCHERNUMBER>
                <PARTYLEDGERNAME>'.htmlspecialchars($ledgerName).'</PARTYLEDGERNAME>
                <BASICBASEPARTYNAME>'.htmlspecialchars($ledgerName).'</BASICBASEPARTYNAME>
                <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
                <PLACEOFSUPPLY>'.$deliveryState.'</PLACEOFSUPPLY>
                <CONSIGNEEGSTIN>'.$gstNo.'</CONSIGNEEGSTIN>
                <BASICDESTINATIONCOUNTRY>'.htmlspecialchars($deliveryAddressQuery[0]['name']).'</BASICDESTINATIONCOUNTRY>
                <BASICBUYERNAME>'.htmlspecialchars($deliveryAddressQuery[0]['site_name']).'</BASICBUYERNAME>';
                $brokerage = ($order['brokerage_value']>0)?$order['brokerage_value']:'NET';
                $xml.='<BASICORDERREF>'.$brokerLedgerName.' - '.$brokerage.'</BASICORDERREF>
                <BASICDUEDATEOFPYMT>'.$paymentDueDay.'</BASICDUEDATEOFPYMT>
                <PLACEOFSUPPLYSTATE>'.$deliveryState.'</PLACEOFSUPPLYSTATE>
                <PLACEOFSUPPLYCOUNTRY>'.htmlspecialchars($deliveryAddressQuery[0]['name']).'</PLACEOFSUPPLYCOUNTRY>
                <CONSIGNEEPINNUMBER>'.$deliveryPincode.'</CONSIGNEEPINNUMBER>
                <CONSIGNEESTATENAME>'.$deliveryState.'</CONSIGNEESTATENAME>
                <ENTEREDBY>AJS</ENTEREDBY>
                <DIFFACTUALQTY>No</DIFFACTUALQTY>
                <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
                <ASORIGINAL>No</ASORIGINAL>
                <AUDITED>No</AUDITED>
                <FORJOBCOSTING>No</FORJOBCOSTING>
                <ISOPTIONAL>No</ISOPTIONAL>
                <EFFECTIVEDATE>'.date('Ymd', strtotime($order['order_date'])).'</EFFECTIVEDATE>
                <USEFOREXCISE>No</USEFOREXCISE>
                <ISFORJOBWORKIN>No</ISFORJOBWORKIN>
                <ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>
                <USEFORINTEREST>No</USEFORINTEREST>
                <USEFORGAINLOSS>No</USEFORGAINLOSS>
                <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
                <USEFORCOMPOUND>No</USEFORCOMPOUND>
                <USEFORSERVICETAX>No</USEFORSERVICETAX>
                <ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>
                <EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>
                <USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>
                <IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>
                <EXCISEOPENING>No</EXCISEOPENING>
                <USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>
                <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
                <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
                <ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>
                <INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>
                <ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>
                <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
                <IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>
                <ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>
                <ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>
                <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
                <ISISDVOUCHER>No</ISISDVOUCHER>
                <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
                <ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>
                <ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>
                <GSTNOTEXPORTED>No</GSTNOTEXPORTED>
                <IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>
                <ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>
                <ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>
                <ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>
                <ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>
                <ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>
                <ISCANCELLED>No</ISCANCELLED>
                <HASCASHFLOW>No</HASCASHFLOW>
                <ISPOSTDATED>No</ISPOSTDATED>
                <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
                <ISINVOICE>No</ISINVOICE>
                <MFGJOURNAL>No</MFGJOURNAL>
                <HASDISCOUNTS>No</HASDISCOUNTS>
                <ASPAYSLIP>No</ASPAYSLIP>
                <ISCOSTCENTRE>No</ISCOSTCENTRE>
                <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
                <ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>
                <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
                <ISVOID>No</ISVOID>
                <ISONHOLD>No</ISONHOLD>
                <ORDERLINESTATUS>No</ORDERLINESTATUS>
                <VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>
                <VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>
                <ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>
                <VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>
                <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                <ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>
                <ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>
                <ISDELETED>No</ISDELETED>
                <CHANGEVCHMODE>No</CHANGEVCHMODE>';
                /*<ALTERID>282613</ALTERID>
                <MASTERID>98388</MASTERID>
                <VOUCHERKEY>77777777</VOUCHERKEY>*/
                $xml.='<ECFEERATE>0</ECFEERATE>
                <VATCONSIGNMENTNO>0</VATCONSIGNMENTNO>
                <VATTDSRATE>0</VATTDSRATE>
                <EIDEFAULTLED_CLASSRATE>0</EIDEFAULTLED_CLASSRATE>
                <EIDEFAULTLED_CLASSADDLRATE>0</EIDEFAULTLED_CLASSADDLRATE>
                <TEMPCONSIGNORPINCODENUMBER>0</TEMPCONSIGNORPINCODENUMBER>
                <TEMPGSTEWAYPINCODENUMBER>0</TEMPGSTEWAYPINCODENUMBER>
                <TEMPGSTEWAYCONSPINCODENUMBER>0</TEMPGSTEWAYCONSPINCODENUMBER>
                <TEMPCONSIGNEEPINCODENUMBER>0</TEMPCONSIGNEEPINCODENUMBER>
                <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
                <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
                <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
                <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
                <DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>
                <SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
                <EWAYBILLDETAILS.LIST>      </EWAYBILLDETAILS.LIST>
                <INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>
                <INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>
                <INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>
                <ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>
                <ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>
                <INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>
                <LEDGERENTRIES.LIST>
                 <OLDAUDITENTRYIDS.LIST TYPE="Number">
                  <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                 </OLDAUDITENTRYIDS.LIST>
                 <ORIGPURCHINVDATE/>
                 <ADDLALLOCTYPE/>
                 <TAXCLASSIFICATIONNAME/>
                 <NOTIFICATIONSLNO/>
                 <ROUNDTYPE/>
                 <LEDGERNAME>'.htmlspecialchars($ledgerName).'</LEDGERNAME>
                 <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                 <LEDGERFROMITEM>No</LEDGERFROMITEM>
                 <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                 <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                 <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
                 <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                 <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                 <STCRADJPERCENT>0</STCRADJPERCENT>
                 <ROUNDLIMIT>0</ROUNDLIMIT>
                 <RATEOFADDLVAT>0</RATEOFADDLVAT>
                 <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                 <VATTAXRATE>0</VATTAXRATE>
                 <VATITEMQTY>0</VATITEMQTY>
                 <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                 <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                 <GSTTAXRATE>0</GSTTAXRATE>
                 <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                 <CAPVATTAXRATE>0</CAPVATTAXRATE>';
                 $sumAmount[] = $amt_before_tax;
                 //echo $xml;
                 //echo $amt_before_tax;exit;
                //echo $amount;exit;
                 $xml.='<AMOUNT>-'.$order['amount_after_tax'].'</AMOUNT>
                 <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                 <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                 <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
                 <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                 <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                 <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                 <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                 <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                 <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                 <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                 <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                 <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                 <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                 <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                 <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                 <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                 <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                 <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                 <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                 <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                 <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                 <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                 <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                </LEDGERENTRIES.LIST>
                <LEDGERENTRIES.LIST>
                 <OLDAUDITENTRYIDS.LIST TYPE="Number">
                  <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                 </OLDAUDITENTRYIDS.LIST>
                 <LEDGERNAME>LOADING CHARGES (S)</LEDGERNAME>
                 <METHODTYPE>As User Defined Value</METHODTYPE>
                 <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                 <LEDGERFROMITEM>No</LEDGERFROMITEM>
                 <REMOVEZEROENTRIES>Yes</REMOVEZEROENTRIES>
                 <ISPARTYLEDGER>No</ISPARTYLEDGER>
                 <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                 <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                 <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                 <STCRADJPERCENT>0</STCRADJPERCENT>
                 <ROUNDLIMIT>0</ROUNDLIMIT>
                 <RATEOFADDLVAT>0</RATEOFADDLVAT>
                 <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                 <VATTAXRATE>0</VATTAXRATE>
                 <VATITEMQTY>0</VATITEMQTY>
                 <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                 <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                 <GSTTAXRATE>0</GSTTAXRATE>
                 <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                 <CAPVATTAXRATE>0</CAPVATTAXRATE>';
                 $sql = 'select sum(qty) as qty from order_details where order_code="'.$orderCode.'" and is_active =true';
                 $otherCharges = $this->pktdblib->custom_query($sql);
                 $loadingCharge = $otherCharges[0]['qty']*$order['other_charges'];
                 //echo $order['other_charges'];exit;
                 $sumAmount[] = $order['other_charges'];
                 //echo $xml;
                 //print_r($sumAmount);exit;
                 $xml.='<AMOUNT>'.$loadingCharge.'</AMOUNT>';
                 
                 $xml.='<VATEXPAMOUNT>'.$loadingCharge.'</VATEXPAMOUNT>
                 <VATASSESMENTATE>0</VATASSESMENTATE>
                 <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                 <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                 <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
                 <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                 <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                 <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                 <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                 <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                 <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                 <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                 <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                 <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                 <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                 <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                 <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                 <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                 <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                 <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                 <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                 <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                 <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                 <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                 <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                </LEDGERENTRIES.LIST>';
                if (substr($customer['gst_no'], 0, 2) == substr($company['gst_no'], 0, 2)) {

                $xml.='<LEDGERENTRIES.LIST>
                 <OLDAUDITENTRYIDS.LIST TYPE="Number">
                  <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                 </OLDAUDITENTRYIDS.LIST>
                 <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                 <LEDGERNAME>SGST OUTPUT @ 9%</LEDGERNAME>
                 <METHODTYPE>GST</METHODTYPE>
                 <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                 <LEDGERFROMITEM>No</LEDGERFROMITEM>
                 <REMOVEZEROENTRIES>Yes</REMOVEZEROENTRIES>
                 <ISPARTYLEDGER>No</ISPARTYLEDGER>
                 <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                 <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                 <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                 <STCRADJPERCENT>0</STCRADJPERCENT>
                 <ROUNDLIMIT>0</ROUNDLIMIT>
                 <RATEOFADDLVAT>0</RATEOFADDLVAT>
                 <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                 <VATTAXRATE>0</VATTAXRATE>
                 <VATITEMQTY>0</VATITEMQTY>
                 <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                 <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                 <GSTTAXRATE>0</GSTTAXRATE>
                 <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                 <CAPVATTAXRATE>0</CAPVATTAXRATE>';
                 //echo ($amt_before_tax+$order['other_charges'])*0.09;exit;
                 //echo $tax;exit;
                 //$sumAmount[] = ($amt_before_tax+$order['other_charges'])*($tax/100);
                 $sumAmount[] = ($amt_before_tax+$loadingCharge)*($tax/100);

                 $total_amount = number_format((float)array_sum($sumAmount), 2, '.', '');
                 $total_amount = explode('.', $total_amount);
                 $roundOff = $total_amount[1];
                 //echo $roundOff;exit;

                 $xml.='<AMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/200).'</AMOUNT>
                 <VATEXPAMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/200).'</VATEXPAMOUNT>
                 <VATASSESMENTATE>0</VATASSESMENTATE>
                 <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                 <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                 <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
                 <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                 <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                 <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                 <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                 <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                 <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                 <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                 <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                 <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                 <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                 <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                 <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                 <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                 <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                 <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                 <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                 <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                 <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                 <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                 <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                </LEDGERENTRIES.LIST>
                <LEDGERENTRIES.LIST>
                 <OLDAUDITENTRYIDS.LIST TYPE="Number">
                  <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                 </OLDAUDITENTRYIDS.LIST>
                 <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                 <LEDGERNAME>CGST OUTPUT @ 9%</LEDGERNAME>
                 <METHODTYPE>GST</METHODTYPE>
                 <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                 <LEDGERFROMITEM>No</LEDGERFROMITEM>
                 <REMOVEZEROENTRIES>Yes</REMOVEZEROENTRIES>
                 <ISPARTYLEDGER>No</ISPARTYLEDGER>
                 <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                 <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                 <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                 <STCRADJPERCENT>0</STCRADJPERCENT>
                 <ROUNDLIMIT>0</ROUNDLIMIT>
                 <RATEOFADDLVAT>0</RATEOFADDLVAT>
                 <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                 <VATTAXRATE>0</VATTAXRATE>
                 <VATITEMQTY>0</VATITEMQTY>
                 <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                 <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                 <GSTTAXRATE>0</GSTTAXRATE>
                 <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                 <CAPVATTAXRATE>0</CAPVATTAXRATE>
                 <AMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/200).'</AMOUNT>
                 <VATEXPAMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/200).'</VATEXPAMOUNT>
                 <VATASSESMENTATE>0</VATASSESMENTATE>
                 <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                 <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                 <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
                 <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                 <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                 <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                 <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                 <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                 <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                 <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                 <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                 <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                 <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                 <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                 <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                 <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                 <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                 <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                 <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                 <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                 <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                 <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                 <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                </LEDGERENTRIES.LIST>';
                }
                else{
                 $xml.='<LEDGERENTRIES.LIST>
                 <OLDAUDITENTRYIDS.LIST TYPE="Number">
                  <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                 </OLDAUDITENTRYIDS.LIST>
                 <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                 <LEDGERNAME>IGST OUTPUT @ 18%</LEDGERNAME>
                 <METHODTYPE>GST</METHODTYPE>
                 <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                 <LEDGERFROMITEM>No</LEDGERFROMITEM>
                 <REMOVEZEROENTRIES>Yes</REMOVEZEROENTRIES>
                 <ISPARTYLEDGER>No</ISPARTYLEDGER>
                 <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                 <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                 <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                 <STCRADJPERCENT>0</STCRADJPERCENT>
                 <ROUNDLIMIT>0</ROUNDLIMIT>
                 <RATEOFADDLVAT>0</RATEOFADDLVAT>
                 <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                 <VATTAXRATE>0</VATTAXRATE>
                 <VATITEMQTY>0</VATITEMQTY>
                 <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                 <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                 <GSTTAXRATE>0</GSTTAXRATE>
                 <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                 <CAPVATTAXRATE>0</CAPVATTAXRATE>';
                 //echo ($amt_before_tax+$order['other_charges'])*0.09;exit;
                 //echo $tax;exit;
                 $sumAmount[] = ($amt_before_tax+$loadingCharge)*($tax/100);
                 $total_amount = number_format((float)array_sum($sumAmount), 2, '.', '');
                 $total_amount = explode('.', $total_amount);
                 $roundOff = $total_amount[1];
                 //echo $roundOff;exit;

                 $xml.='<AMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/100).'</AMOUNT>
                 <VATEXPAMOUNT>'.($amt_before_tax+$loadingCharge)*($tax/100).'</VATEXPAMOUNT>
                 <VATASSESMENTATE>0</VATASSESMENTATE>
                 <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                 <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                 <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
                 <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                 <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                 <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                 <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                 <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                 <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                 <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                 <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                 <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                 <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                 <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                 <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                 <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                 <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                 <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                 <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                 <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                 <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                 <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                 <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                </LEDGERENTRIES.LIST>'; 
                }
                //echo $xml;exit;
                $xml.='<LEDGERENTRIES.LIST>
                 <OLDAUDITENTRYIDS.LIST TYPE="Number">
                  <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                 </OLDAUDITENTRYIDS.LIST>
                 <LEDGERNAME>ROUND OFF</LEDGERNAME>
                 <METHODTYPE>As User Defined Value</METHODTYPE>
                 <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                 <LEDGERFROMITEM>No</LEDGERFROMITEM>
                 <REMOVEZEROENTRIES>Yes</REMOVEZEROENTRIES>
                 <ISPARTYLEDGER>No</ISPARTYLEDGER>
                 <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                 <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                 <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                 <STCRADJPERCENT>0</STCRADJPERCENT>
                 <ROUNDLIMIT>0</ROUNDLIMIT>
                 <RATEOFADDLVAT>0</RATEOFADDLVAT>
                 <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                 <VATTAXRATE>0</VATTAXRATE>
                 <VATITEMQTY>0</VATITEMQTY>
                 <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                 <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                 <GSTTAXRATE>0</GSTTAXRATE>
                 <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                 <CAPVATTAXRATE>0</CAPVATTAXRATE>
                 <AMOUNT>(-)0.'.$roundOff.'</AMOUNT>
                 <VATEXPAMOUNT>0.20</VATEXPAMOUNT>
                 <VATASSESMENTATE>0</VATASSESMENTATE>
                 <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                 <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                 <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
                 <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                 <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                 <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                 <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                 <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                 <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                 <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                 <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                 <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                 <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                 <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                 <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                 <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                 <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                 <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                 <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                 <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                 <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                 <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                 <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                </LEDGERENTRIES.LIST>';
                  //print_r($sumAmount);exit;
                  $qtySum = [];
                  foreach ($orderDetails as $ODKey => $orderDetail) {
                    //print_r($orderDetail);exit;
                    $coilNo = isset($orderDetail['coil_no'])?$orderDetail['coil_no']:'';
                    $grade = isset($orderDetail['grade'])?$orderDetail['grade']:'';
                    $thickness = isset($orderDetail['thickness'])?$orderDetail['thickness']:'';
                    $width = isset($orderDetail['width'])?$orderDetail['width']:'';
                    $length = isset($orderDetail['length'])?$orderDetail['length']:'';
                    $qty = isset($orderDetail['qty'])?$orderDetail['qty']:'';
                    $unitPrice = isset($orderDetail['unit_price'])?$orderDetail['unit_price']:'';
                    $tallyName = isset($orderDetail['tally_name'])?$orderDetail['tally_name']:'';
                    $remark = isset($orderDetail['remark'])?$orderDetail['remark']:'';

                    $amount = isset($orderDetail['unit_price'])?$unitPrice*$qty:'';
                    $dueOn = isset($orderDetail['due_on'])?date('Y-m-d',strtotime($orderDetail['due_on'])):'';
                    $qtySum[] = $qty;
                    
                    //print_r($dueOn);exit;
                 $xml.='<ALLINVENTORYENTRIES.LIST>
                  <BASICUSERDESCRIPTION.LIST TYPE="String">
                    <BASICUSERDESCRIPTION>'.$thickness.'MMX'.$width.'X'.$length.'</BASICUSERDESCRIPTION>
                    <BASICUSERDESCRIPTION>'.$grade.'</BASICUSERDESCRIPTION>
                    <BASICUSERDESCRIPTION>'.$coilNo.'</BASICUSERDESCRIPTION>
                    <BASICUSERDESCRIPTION>'.$remark.'</BASICUSERDESCRIPTION>
                  </BASICUSERDESCRIPTION.LIST>
                 <STOCKITEMNAME>'.$tallyName.'</STOCKITEMNAME>
                 <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                 <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                 <ISAUTONEGATE>No</ISAUTONEGATE>
                 <ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
                 <ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
                 <ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
                 <ISPRIMARYITEM>No</ISPRIMARYITEM>
                 <ISSCRAP>No</ISSCRAP>
                 <RATE>'.$unitPrice.'/M.T.</RATE>
                 <DISCOUNT>0</DISCOUNT>
                 <VATTAXRATE>0</VATTAXRATE>
                 <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                 <EXCISEMRPABATEMENT>0</EXCISEMRPABATEMENT>
                 <ADDLCOSTPERC>0</ADDLCOSTPERC>
                 <REVISEDRATEOFDUTY>0</REVISEDRATEOFDUTY>
                 <ORIGRATEOFDUTY>0</ORIGRATEOFDUTY>
                 <ORIGMRPABATEMENT>0</ORIGMRPABATEMENT>
                 <EXCISERETURNDUTYRATE>0</EXCISERETURNDUTYRATE>
                 <GVATEXCISERATE>0</GVATEXCISERATE>
                 <AMOUNT>'.$amount.'</AMOUNT>
                 <ACTUALQTY> '.$orderDetail['qty'].' M.T.</ACTUALQTY>
                <BILLEDQTY> '.$orderDetail['qty'].' M.T.</BILLEDQTY>
                 <BATCHALLOCATIONS.LIST>
                  <BATCHNAME>Any</BATCHNAME>';
                  $xml.='<DESTINATIONGODOWNNAME/>
                  <ORDERNO>'.$orderCode.'</ORDERNO>
                  <DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED>
                  <AMOUNT>'.$amount.'</AMOUNT>
                  <ACTUALQTY> '.$orderDetail['qty'].' M.T.</ACTUALQTY>
                  <BILLEDQTY> '.$orderDetail['qty'].' M.T.</BILLEDQTY>
                  ';
                  /*<ACTUALQTY> '.$orderDetail['qty'].' M.T.</ACTUALQTY>
                  <BILLEDQTY> '.$orderDetail['qty'].' M.T.</BILLEDQTY>*/

                  $xml.='<ORDERDUEDATE P="'.date('d-F-Y',strtotime($dueOn)).'">'.date('d-F-Y',strtotime($dueOn)).'</ORDERDUEDATE>
                  <ADDITIONALDETAILS.LIST>        </ADDITIONALDETAILS.LIST>
                  <VOUCHERCOMPONENTLIST.LIST>        </VOUCHERCOMPONENTLIST.LIST>
                 </BATCHALLOCATIONS.LIST>
                 <ACCOUNTINGALLOCATIONS.LIST>
                  <OLDAUDITENTRYIDS.LIST TYPE="Number">
                   <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                  </OLDAUDITENTRYIDS.LIST>';
                  if (substr($customer['gst_no'], 0, 2) == substr($company['gst_no'], 0, 2)) {
                    $xml.='<LEDGERNAME>SALES @'.$tax.'% GST</LEDGERNAME>';
                  }else{
                      $xml.='<LEDGERNAME>SALES INTERSTATE @ '.$tax.'%</LEDGERNAME>';
                  }
                  $xml.='<CLASSRATE>100.00000</CLASSRATE>
                  <GSTOVRDNNATURE>Sales Taxable</GSTOVRDNNATURE>
                  <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                  <LEDGERFROMITEM>No</LEDGERFROMITEM>
                  <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                  <ISPARTYLEDGER>No</ISPARTYLEDGER>
                  <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                  <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                  <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                  <STCRADJPERCENT>0</STCRADJPERCENT>
                  <ROUNDLIMIT>0</ROUNDLIMIT>
                  <RATEOFADDLVAT>0</RATEOFADDLVAT>
                  <RATEOFCESSONVAT>0</RATEOFCESSONVAT>
                  <VATTAXRATE>0</VATTAXRATE>
                  <VATITEMQTY>0</VATITEMQTY>
                  <PREVINVTOTALNUM>0</PREVINVTOTALNUM>
                  <VATWCDEDUCTIONRATE>0</VATWCDEDUCTIONRATE>
                  <GSTTAXRATE>0</GSTTAXRATE>
                  <ORIGINVGOODSQTY>0</ORIGINVGOODSQTY>
                  <CAPVATTAXRATE>0</CAPVATTAXRATE>
                  <AMOUNT>'.$amount.'</AMOUNT>
                  <VATASSESMENTATE>0</VATASSESMENTATE>
                  <SERVICETAXDETAILS.LIST>        </SERVICETAXDETAILS.LIST>
                  <BANKALLOCATIONS.LIST>        </BANKALLOCATIONS.LIST>
                  <BILLALLOCATIONS.LIST>        </BILLALLOCATIONS.LIST>
                  <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
                  <OLDAUDITENTRIES.LIST>        </OLDAUDITENTRIES.LIST>
                  <ACCOUNTAUDITENTRIES.LIST>        </ACCOUNTAUDITENTRIES.LIST>
                  <AUDITENTRIES.LIST>        </AUDITENTRIES.LIST>
                  <INPUTCRALLOCS.LIST>        </INPUTCRALLOCS.LIST>
                  <DUTYHEADDETAILS.LIST>        </DUTYHEADDETAILS.LIST>
                  <EXCISEDUTYHEADDETAILS.LIST>        </EXCISEDUTYHEADDETAILS.LIST>
                  <RATEDETAILS.LIST>
                   <GSTRATEDUTYHEAD>Integrated Tax</GSTRATEDUTYHEAD>
                   <GSTRATE>0</GSTRATE>
                   <GSTRATEPERUNIT>0</GSTRATEPERUNIT>
                   <TEMPGSTRATE>0</TEMPGSTRATE>
                  </RATEDETAILS.LIST>
                  <RATEDETAILS.LIST>
                   <GSTRATEDUTYHEAD>Central Tax</GSTRATEDUTYHEAD>
                   <GSTRATE>0</GSTRATE>
                   <GSTRATEPERUNIT>0</GSTRATEPERUNIT>
                   <TEMPGSTRATE>0</TEMPGSTRATE>
                  </RATEDETAILS.LIST>
                  <RATEDETAILS.LIST>
                   <GSTRATEDUTYHEAD>State Tax</GSTRATEDUTYHEAD>
                   <GSTRATE>0</GSTRATE>
                   <GSTRATEPERUNIT>0</GSTRATEPERUNIT>
                   <TEMPGSTRATE>0</TEMPGSTRATE>
                  </RATEDETAILS.LIST>
                  <RATEDETAILS.LIST>
                   <GSTRATEDUTYHEAD>Cess</GSTRATEDUTYHEAD>
                   <GSTRATE>0</GSTRATE>
                   <GSTRATEPERUNIT>0</GSTRATEPERUNIT>
                   <TEMPGSTRATE>0</TEMPGSTRATE>
                  </RATEDETAILS.LIST>
                  <SUMMARYALLOCS.LIST>        </SUMMARYALLOCS.LIST>
                  <STPYMTDETAILS.LIST>        </STPYMTDETAILS.LIST>
                  <EXCISEPAYMENTALLOCATIONS.LIST>        </EXCISEPAYMENTALLOCATIONS.LIST>
                  <TAXBILLALLOCATIONS.LIST>        </TAXBILLALLOCATIONS.LIST>
                  <TAXOBJECTALLOCATIONS.LIST>        </TAXOBJECTALLOCATIONS.LIST>
                  <TDSEXPENSEALLOCATIONS.LIST>        </TDSEXPENSEALLOCATIONS.LIST>
                  <VATSTATUTORYDETAILS.LIST>        </VATSTATUTORYDETAILS.LIST>
                  <COSTTRACKALLOCATIONS.LIST>        </COSTTRACKALLOCATIONS.LIST>
                  <REFVOUCHERDETAILS.LIST>        </REFVOUCHERDETAILS.LIST>
                  <INVOICEWISEDETAILS.LIST>        </INVOICEWISEDETAILS.LIST>
                  <VATITCDETAILS.LIST>        </VATITCDETAILS.LIST>
                  <ADVANCETAXDETAILS.LIST>        </ADVANCETAXDETAILS.LIST>
                 </ACCOUNTINGALLOCATIONS.LIST>
                 <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                 <SUPPLEMENTARYDUTYHEADDETAILS.LIST>       </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
                 <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                 <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                 <EXCISEALLOCATIONS.LIST>       </EXCISEALLOCATIONS.LIST>
                 <EXPENSEALLOCATIONS.LIST>       </EXPENSEALLOCATIONS.LIST>
                 
                </ALLINVENTORYENTRIES.LIST>
                ';
                  }
                $xml.='<PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
                <ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
                <GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>
                <GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>
                <TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>
               </VOUCHER>
              </TALLYMESSAGE>
           </REQUESTDATA>
          </IMPORTDATA>
          </BODY>
          </ENVELOPE>';
        //echo '<pre>'; print('$xml'); exit;
        $res = $this->curl_handling($xml);
        return $res;
  }

  function import_ledger2($id, $accountType, $type=NULL){
      
    $setup = $this->session->userdata('application');
    if((!isset($setup['tally']) || !$setup['tally'])){
      return true;
    }
    $parent = $this->tally_reverseledger_mapping();
    $ledger = [];
    $addressData = []; //default Address
    $ledgerSites = [];
    $contactPerson = $mailingName = $ledgerName = '';
    
    if($accountType=='customer_sites'){
      $customerSites = $this->pktdblib->custom_query('Select cs.*, a.address_1, a.address_2, cn.name, st.state_name, ct.city_name, ar.area_name, a.pincode from customer_sites cs inner join address a on a.id=cs.address_id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where cs.id='.$id);
      //print_r($customerSites);//exit;

      $this->pktdblib->set_table('customers');
      $ledger = $this->pktdblib->get_where($customerSites[0]['customer_id']);
      $userRoles = $this->pktdblib->custom_query('Select * from user_roles where account_type="customers" AND user_id='.$customerSites[0]['customer_id']);
      //print_r($userRoles);//exit;

      if(count($userRoles)>0){ //echo "hii";
        $addressData = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id inner join user_roles ur on ur.login_id=a.user_id and a.type like "login" where a.user_id='.$userRoles[0]['login_id'].' and a.type="login" and a.is_default=true');
      }else{ //echo 'hello';
        $addQuery = 'select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where a.user_id='.$customerSites[0]['customer_id'].' and a.type="customers" and a.is_default=true'; 
        //echo $addQuery;
        $addressData = $this->pktdblib->custom_query($addQuery);
      }
      //print_r($addressData);exit;

      if($setup['tally_has_multiple_address']){
        $contactPerson = $mailingName = isset($ledger['first_name'])?$ledger['first_name']." ".$ledger['middle_name']." ".$ledger['surname']:'';
        $ledgerName = $ledger['company_name'];
        $ledgerSites = $customerSites;
      }else{
        $contactPerson = $mailingName = isset($ledger['first_name'])?$ledger['first_name']." ".$ledger['middle_name']." ".$ledger['surname']:'';
        $ledgerName = $ledger['company_name'];
        $ledger = $customerSites;
      }
      $accountType = 'customers'; 
    }
    $address1 = isset($addressData[0]['address_1'])?$addressData[0]['address_1']:'';
    $address2 = isset($addressData[0]['address_2'])?$addressData[0]['address_2']:'';
    $area = isset($addressData[0]['area_name'])?$addressData[0]['area_name']:'';
    $city = isset($addressData[0]['city_name'])?$addressData[0]['city_name']:'';
    $pincode = isset($addressData[0]['pincode'])?$addressData[0]['pincode']:'';
    $state = isset($addressData[0]['state_name'])?$addressData[0]['state_name']:'';
    $country = isset($addressData[0]['name'])?$addressData[0]['name']:'';
    $guid = isset($ledger['emp_code'])?$ledger['emp_code']:'';
    $pan = isset($ledger['pan_no'])?$ledger['pan_no']:'';
    $gst_no = (isset($ledger['gst_no']) && !empty($ledger['gst_no']))?$ledger['gst_no']:'Unregistered';
    $email = isset($ledger['primary_email'])?$ledger['primary_email']:'';
    $contact = isset($ledger['contact_1'])?$ledger['contact_1']:'';
    //echo $accountType;
    //echo "reached heer";exit;
      $xml = '<ENVELOPE>
               <HEADER>
                <TALLYREQUEST>Import Data</TALLYREQUEST>
               </HEADER>
               <BODY>
                <IMPORTDATA>
                 <REQUESTDESC>
                  <REPORTNAME>All Masters</REPORTNAME>
                  </REQUESTDESC>
                 <REQUESTDATA>
              <TALLYMESSAGE xmlns:UDF="TallyUDF">
                <LEDGER NAME="'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $ledgerName).'" RESERVEDNAME="">
                  <ADDRESS.LIST TYPE="String">
                   <ADDRESS>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $address1).'</ADDRESS>
                   <ADDRESS>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $address2).'</ADDRESS>
                   <ADDRESS>'.$area.', '.$city.' - '.$pincode.'</ADDRESS>
                   
                  </ADDRESS.LIST>
                  <CREATEDDATE TYPE="Date"></CREATEDDATE>
                  <ALTEREDON TYPE="Date">'.date('Ymd',strtotime($ledger['modified'])).'</ALTEREDON>
                  <EMAIL TYPE="String">'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', strtolower($email)).'</EMAIL>
                  <PINCODE TYPE="String">'.$pincode.'</PINCODE>
                  <INCOMETAXNUMBER TYPE="String">'.$pan.'</INCOMETAXNUMBER>
                 <PARENT TYPE="String">'.$parent[$accountType].'</PARENT>
                 <COUNTRYOFRESIDENCE TYPE="String">'.$country.'</COUNTRYOFRESIDENCE>
                 <PARTYGSTIN TYPE="String">'.$gst_no.'</PARTYGSTIN>
                 <LEDSTATENAME TYPE="String">'.$state.'</LEDSTATENAME>
                 
                 <LANGUAGENAME.LIST>
                  <NAME.LIST TYPE="String">
                   <NAME>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $ledgerName).'</NAME>
                  </NAME.LIST>
                  <LANGUAGEID TYPE="Number"> 1033</LANGUAGEID>
                 </LANGUAGENAME.LIST>';
                 if(count($customerSites)>0){
                  $customerSites2 = $this->pktdblib->custom_query('Select cs.*, a.address_1, a.address_2, cn.name, st.state_name, ct.city_name, ar.area_name, a.pincode from customer_sites cs inner join address a on a.id=cs.address_id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where cs.customer_id='.$customerSites[0]['customer_id'].' and cs.is_active=true');
                  foreach ($customerSites2 as $key => $value) {
                    //print_r($value);exit;
                   
                    $xml.= '
                    <LEDMULTIADDRESSLIST.LIST>
                            <ADDRESS.LIST TYPE="String">
                             <ADDRESS>'.$value['company_name'].'</ADDRESS>
                             <ADDRESS>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $value['address_1']).'</ADDRESS>
                             <ADDRESS>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $value['address_2']).'</ADDRESS>
                             <ADDRESS>'.$value['area_name'].', '.$value['city_name'].'</ADDRESS>
                            </ADDRESS.LIST>
                            <PINCODE TYPE="String">'.$value['pincode'].'</PINCODE>
                            <INCOMETAXNUMBER TYPE="String">'.$value['pan_no'].'</INCOMETAXNUMBER>
                            <COUNTRYNAME TYPE="String">'.$value['name'].'</COUNTRYNAME>
                            <ADDRESSNAME TYPE="String">'.$value['site_name'].'</ADDRESSNAME>
                            <PARTYGSTIN TYPE="String">'.$value['gst'].'</PARTYGSTIN>
                            <STATE TYPE="String">'.$value['state_name'].'</STATE>
                            <ISOTHTERRITORYASSESSEE TYPE="Logical">No</ISOTHTERRITORYASSESSEE>
                            <EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>
                           </LEDMULTIADDRESSLIST.LIST>';
                  }
                 }
                  $xml.= '
                  </LEDGER>
                  </TALLYMESSAGE>
                  </REQUESTDATA>
                  </IMPORTDATA>
                  </BODY>
    </ENVELOPE>';
    echo $xml;exit;
    $importLedger = $this->curl_handling($xml);
    //print_r($importLedger);exit;
    if($this->uri->segment(1)=='tally'){
        //echo $xml;//exit;
        //exit;
        echo '<pre>';
        print_r($importLedger);
        exit;
    }
    return $importLedger;
  }   
  
  function get_tally_company() {
      /*$xml = '<ENVELOPE>
        <HEADER>
        <TALLYREQUEST>Export Data</TALLYREQUEST>
        </HEADER>
        <BODY>
        <EXPORTDATA><REQUESTDESC>
        <REPORTNAME>List of Companies</REPORTNAME><STATICVARIABLES>
        <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT></STATICVARIABLES></REQUESTDESC></EXPORTDATA>
        </BODY>
        </ENVELOPE>';*/
        $xml = '<ENVELOPE>
            <HEADER>
                <VERSION>1</VERSION>
                <TALLYREQUEST>Export</TALLYREQUEST>
                <TYPE>Collection</TYPE>
                <ID>Hostcmpondisk</ID>
            </HEADER>
            <BODY>
                <DESC>
                    <STATICVARIABLES>
                   <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
                </STATICVARIABLES>
                <TDL>
                    <TDLMESSAGE>
                       <COLLECTION Name="Hostcmpondisk">
                       <TYPE>Company On Disk</TYPE>
                       <NATIVEMETHOD>Name</NATIVEMETHOD>
                       </COLLECTION>
                    </TDLMESSAGE>
                </TDL>
            </DESC>
            </BODY>
        </ENVELOPE>';
               
      $res = json_decode(json_encode($this->curl_handling($xml)), true);
      /*echo '<pre>';
      print_r($res['BODY']['DATA']['COLLECTION']['COMPANYONDISK']);*/
      if(isset($res['BODY']['DATA']['COLLECTION']['COMPANYONDISK'])){
          $companies = [];
          foreach($res['BODY']['DATA']['COLLECTION']['COMPANYONDISK'] as $company){
              //print_r($company['COMPANYNUMBER']);
              $companies[$company['COMPANYNUMBER']] = $company['NAME'];
          }
          //exit;
          if(in_array(custom_constants::tally_current_company, $companies)){
              return TRUE;
          }else{
              return FALSE;
          }
      }else{
          return FALSE;
      }
    //$res = $this->sample_salesorder();
    /*echo '<pre>';
    print_r($res);exit;*/
  
  }
}