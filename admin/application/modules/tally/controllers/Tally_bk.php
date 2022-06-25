<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tally extends MY_Controller {
	public $tallyServer;
	function __construct() {
		parent::__construct();
		
		check_user_login(FALSE);
		$this->tallyServer = custom_constants::tally_server;
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
        //print_r($error);
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
    <ID>Sales Orders Book</ID> 
    </HEADER>
    <BODY>
    <DESC>
    <STATICVARIABLES>
    <VoucherTypeName>Sales Orders Book</VoucherTypeName>
    <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
    <SVFROMDATE TYPE="Date">20180401</SVFROMDATE>
    <SVTODATE TYPE="Date">20180401</SVTODATE>
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
     <SVCURRENTCOMPANY>AJS IMPEX  PVT. LTD. 2018-19</SVCURRENTCOMPANY>
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
      <NARRATION/>
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
      <OPENINGBALANCE/>
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
      <NARRATION/>
      <REQUESTORRULE/>
      <GRPDEBITPARENT/>
      <GRPCREDITPARENT/>
      <SYSDEBITPARENT/>
      <SYSCREDITPARENT/>
      <TDSAPPLICABLE/>
      <TCSAPPLICABLE/>
      <GSTAPPLICABLE/>
      <CREATEDBY>RAJAN</CREATEDBY>
      <ALTEREDBY>TEJASHRI</ALTEREDBY>
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
      <OPENINGBALANCE/>
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
      <NARRATION/>
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
    <TYPE>GROUP</TYPE>
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
    <NATIVEMETHOD></NATIVEMETHOD>

    </COLLECTION>
    </TDLMESSAGE>
    </TDL>
    </DESC>
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
        echo '<pre>';
        print_r($res);exit();
        //echo "<pre>";
        $typeMapping = $this->usertype_mapping();
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
              if(in_array(isset($ledger['NAME'])?$ledger['NAME']:$ledger['@attributes']->NAME, $ledgerAccounts)){
                //echo "data present ".$ledgerKey.'<br>';
                continue;
              }
              if($loopCount>=150){
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
                $addressArray = [];
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
                }
                $response=json_decode(Modules::run($typeMapping[$ledger['PARENT']].'/_register_admin_add', $userData), true);
                //print_r($response[$typeMapping[$ledger['PARENT']]]);
                //print_r($response);//exit;
                $addressArray['type'] = $tallyLedgerArray['user_id'] = $response[$typeMapping[$ledger['PARENT']]]['id'];

                //print_r($response[$typeMapping[$ledger['PARENT']]]['id']);exit;
                $loginArray = [];
                if(isset($userData['primary_email']) && !empty($userData['primary_email'])){
                  $loginArray= $userData;
                  $loginArray['id'] = $response[$typeMapping[$ledger['PARENT']]]['id'];
                  $loginArray['account_type'] = $typeMapping[$ledger['PARENT']];
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
                }
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
                    $str = implode("",$ledger['ADDRESS.LIST']['ADDRESS']);
                  }
                  if(trim($str)!=''){
                    
                    $addressArray['address_1'] = $str;
                    if(isset($ledger['LEDSTATENAME'])){
                      $this->pktdblib->set_table('states');
                      $query = $this->pktdblib->get_where_custom('state_name', $ledger['LEDSTATENAME']);
                      if($query->num_rows()){
                        $state = $query->row_array();
                        $address['state_id'] = $state['id'];
                      }
                    }
                    $address['country_id'] = 1;
                    $address['created'] = $address['modified'] = date('Y-m-d H:i:s');
                    $address['site_name'] = 'Billing Address';
                    $this->pktdblib->set_table('address');
                    $insAddress = $this->pktdblib->_insert($address);

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
              //print_r($tallyLedgerArray);
            }
          }
        } 
        //exit;
      //print_r($sundrycreditor);exit();
    
    
  }

  function usertype_mapping(){
    $typeMapping = [
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

    return $typeMapping;
  }
  
  function tally_reverseledger_mapping(){

    $tallyMapping = [
      //'vendors'=>'SUNDRY CREDITOR-ABB',
      'vendors'=>'Sundry Creditors',
      'customers'=>' Sundry Debtors - MARKET',
      'brokers'=>' SUNDRY CREDITORS-BROKER',
      //'customers'=>' Sundry Debtors - CONSUMER',
      'employees'=>' Salary Account - Staff'];

      return $tallyMapping;

  }

  function tally_sales_order($xml=''){
    //echo "hii";exit;
    if($xml==''){
      $xml = '<ENVELOPE>
      <HEADER>
      <TALLYREQUEST>Export Data</TALLYREQUEST>
      </HEADER>
      <BODY>
      <EXPORTDATA>
      <REQUESTDESC>
      <STATICVARIABLES>
      <SVFROMDATE>20190328</SVFROMDATE>
      <SVTODATE>20190401</SVTODATE>
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
    //echo '<pre>';print_r($res);exit;
    $orderData = [];
    $productData = [];
    $order = [];
    echo "<pre>";
    foreach ((array)$res->BODY->IMPORTDATA->REQUESTDATA as $soKey => $salesorder) {
      if($soKey!=='@attributes'){
        $salesorder = json_decode(json_encode($salesorder, true));
        foreach ((array)$salesorder as $saleSOkey => $voucher) {
          $voucher = (array)$voucher;
          //echo $saleSOkey.'<br/>';
          //print_r($voucher);//exit;
          //print_r(count($voucher['VOUCHER']->VOUCHERNUMBER));
          //print_r($voucher['VOUCHER']->PARTYNAME);exit;
          $error = [];
          $order[$saleSOkey]['party_name'] = isset($voucher['VOUCHER']->PARTYNAME)?$voucher['VOUCHER']->PARTYNAME:'';
          $order[$saleSOkey]['order_date'] = isset($voucher['VOUCHER']->DATE)?date('Y-m-d', strtotime($voucher['VOUCHER']->DATE)):'';
          $order[$saleSOkey]['order_code'] =  isset($voucher['VOUCHER']->VOUCHERNUMBER)?$voucher['VOUCHER']->VOUCHERNUMBER:'';
          $order[$saleSOkey]['terms_of_delivery'] = isset($voucher['VOUCHER']->BASICORDERTERMS)?$voucher['VOUCHER']->BASICORDERTERMS:'';
          //print_r($orderData);print_r($productData);exit;
        }
        //print_r($order);exit;
        //exit;
      }
    }
    $count = count($order);
          $count = $count-1;
          //echo $count;exit;
         unset($order[$count]);
          //exit;
    print_r($order);exit;
    //print_r($orderData);print_r($productData);exit;


    $order = [];
    //$orderDetail = [];
    $order['customer_id'] = $orderData['customer_id'];
    $order['terms_of_delivery'] = $orderData['mode_of_delivery'];
    $order['order_date'] = $orderData['order_date'];
    $order['order_code'] = $orderData['voucher_number'];
    $order['created'] = $order['modified'] = date('Y-m-d H:i:s');
    $orderDetail['width'] = $productData['product_width'];
    $orderDetail['thickness'] = $productData['product_thikness'];
    $orderDetail['coil_no'] = $productData['product_coil_no'];
    $orderDetail['created'] = $orderDetail['modified'] = date('Y-m-d H:i:s');
    //print_r($order);
    //print_r($orderDetail);
    $this->pktdblib->set_table('orders');
    $orderId = $this->pktdblib->_insert($order);
    //print_r($orderId);exit;
    if($orderId){
    $orderDetail['order_id'] = $orderId['id'];
    //print_r($orderDetail);exit;
      $this->pktdblib->set_table('order_details');
      $this->pktdblib->_insert($orderDetail);
      //print_r($detailId);
      echo  "order details insterted Successfully";
    }
    //exit;
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


  

  function test_import_ledger(){
    $insert = $this->import_ledger(2,"customers");
    print_r($insert);exit;
  }

  function import_ledger($id, $accountType){
    $this->pktdblib->set_table($accountType);
    $type = $this->pktdblib->get_where($id); 
    $xml = '<ENVELOPE>
               <HEADER>
                <TALLYREQUEST>Import Data</TALLYREQUEST>
               </HEADER>
               <BODY>
                <IMPORTDATA>
                 <REQUESTDESC>
                  <REPORTNAME>All Masters</REPORTNAME>
                  <STATICVARIABLES>
                   <SVCURRENTCOMPANY>AJS IMPEX  PVT. LTD. 2018-19</SVCURRENTCOMPANY>
                  </STATICVARIABLES>
                 </REQUESTDESC>
                 <REQUESTDATA>
              <TALLYMESSAGE xmlns:UDF="TallyUDF">
                   <LEDGER NAME="Primary key technologies C - test" RESERVEDNAME="">
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
                    <CREATEDDATE>20190330</CREATEDDATE>
                    <ALTEREDON>20190330</ALTEREDON>
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
                    <NARRATION/>
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
                    <LEDGERCONTACT>Primary key technologies C - test C - test</LEDGERCONTACT>
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
                    <OPENINGBALANCE/>
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
                      <NAME>Primary key technologies C - test</NAME>
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
    $importLedger = $this->curl_handling($xml);
    print_r($importLedger);exit;
    
  }

  function curl_handling($xml){
    ini_set('display_errors', 1);
    ini_set('max_execution_time', 400);   
    ini_set("memory_limit","400M");
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
      //print_r($data);exit;
      if (curl_errno($ch)) {
        $error =  curl_error($ch);
        //print_r($error);
      } else {
        curl_close($ch);
        $data = str_replace('&#4; ', '', $data);
        $data = str_replace('', ' ', $data);

        //echo '<pre>';print_r($data);exit;
        $res = simplexml_load_string($data);
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
    if($xml==''){

        $xml = '<ENVELOPE> 
                        <HEADER> 
                        <TALLYREQUEST>Export Data</TALLYREQUEST> 
                        </HEADER> 
                        <BODY> 
                        <EXPORTDATA> 
                        <REQUESTDESC> 
                        <REPORTNAME>List of Accounts</REPORTNAME> 
                        <STATICVARIABLES> 
                        <SVEXPORTFORMAT>\$\$SysName:XML</SVEXPORTFORMAT
                        <ACCOUNTTYPE>Godowns</ACCOUNTTYPE> 
                        <!--Other possible values for ACCOUNTTYPE tag are given below--> 
                        <!--All Acctg. Masters, All Inventory Masters,All Statutory Masters--> 
                        <!--Ledgers,Groups,Cost Categories,Cost Centres--> 
                        <!--Units,Godowns,Stock Items,Stock Groups,Stock Categories--> 
                        <!--Voucher types,Currencies,Employees,Budgets & Scenarios--> 
                        </STATICVARIABLES> 
                        </REQUESTDESC> 
                        </EXPORTDATA> 
                        </BODY> 
                        </ENVELOPE>';
      /*$xml = '<ENVELOPE>
              <HEADER>
                <TALLYREQUEST>Export Data</TALLYREQUEST>
              </HEADER>
              <BODY>
                <EXPORTDATA>
                  <REQUESTDESC>
                  <REPORTNAME>List of Account</REPORTNAME>
                    <STATICVARIABLES>
                      <SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
                        <ACCOUNTTYPE>Ledgers</ACCOUNTTYPE> 

                      <!--Specify the LedgerName here-->
                    </STATICVARIABLES>
                    <!--Report Name-->
                  </REQUESTDESC>
                </EXPORTDATA>
              </BODY>
            </ENVELOPE>
            ';*/
    }
    $res = $this->curl_handling($xml);
    echo "<pre>";
    print_r($res);exit;
  }

  

  function import_salesorder($orderCode) {
    //echo '<pre>';print_r($_SESSION);exit;
    //echo "reached in tally import_salesorder";exit;
    //echo urldecode($orderCode);exit;
    $orderCode = urldecode($orderCode);
    //echo '<pre>';
    $this->pktdblib->set_table('orders');
    $code = $this->pktdblib->get_where_custom('order_code', $orderCode);
    $orderData = $code->result_array();
    $order = $orderData[0];
    //echo '<pre>';
    //print_r($order[0]);//exit;
    //echo "<pre>";
    $orderDetails = $this->pktdblib->custom_query('select od.*, p.product, p.tally_name from order_details od left join products p on p.id=od.product_id left join orders o on o.order_code=od.order_code where od.order_code="'.$order['order_code'].'"');
    //echo $this->db->last_query();exit;
    //echo '<pre>';print_r($orderCode);exit;
    $invoiceAddress = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join orders o on o.invoice_address_id=a.id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where o.order_code="'.$orderCode.'"');
    //print_r($invoiceAddress);
    $deliveryAddress = $this->pktdblib->custom_query('select a.*, cn.name, st.state_name, ct.city_name, ar.area_name from address a left join orders o on o.invoice_address_id=a.id left join countries cn on cn.id=a.country_id left join states st on st.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where o.order_code="'.$orderCode.'"');
    //print_r($deliveryAddress);exit();
    $customerAddress = $this->pktdblib->custom_query('select a.* from address a where type="customers" and user_id='.$order['customer_id']);
    $this->pktdblib->set_table('customers');
    $customer = $this->pktdblib->get_where($order['customer_id']);
    //print_r($customer);
    $this->pktdblib->set_table('brokers');
    $broker = $this->pktdblib->get_where($order['broker_id']);
    //print_r($broker);exit;

    $ledgerName = isset($customer['company_name'])?$customer['company_name']:'';
    //echo $ledgerName;exit;
    $orderCode = isset($order['order_code'])?$order['order_code']:'';

    //$customerName = isset($customer['first_name'])?$cust['customer_name']:'';
    $invoiceAddress_1 = isset($invoiceAddress[0]['address_1'])?$invoiceAddress[0]['address_1']:'';
    $invoiceAddress_2 = isset($invoiceAddress[0]['address_2'])?$invoiceAddress[0]['address_2']:'';
    $deliveryAddress_1 = isset($deliveryAddress[0]['address_1'])?$deliveryAddress[0]['address_1']:'';
    $deliveryAddress_2 = isset($deliveryAddress[0]['address_2'])?$deliveryAddress[0]['address_2']:'';
    $deliveryPincode = isset($deliveryAddress[0]['pincode'])?$deliveryAddress[0]['pincode']:'';
    $invoicePincode = isset($invoiceAddress[0]['pincode'])?$invoiceAddress[0]['pincode']:'';
    $invoiceState = isset($invoiceAddress[0]['state_name'])?$invoiceAddress[0]['state_name']:'';
    $invoiceCity = isset($invoiceAddress[0]['city_name'])?$invoiceAddress[0]['city_name']:'';
    //echo $invoiceCity;exit;
    $invoiceCountry = isset($invoiceAddress[0]['country'])?$invoiceAddress[0]['country']:'';
    $deliveryState = isset($deliveryAddress[0]['state_name'])?$deliveryAddress[0]['state_name']:'';
    $deliveryCity = isset($deliveryAddress[0]['city'])?$deliveryAddress[0]['city']:'';
    $deliveryCountry = isset($deliveryAddress[0]['country'])?$deliveryAddress[0]['country']:'';
    $deliveryPincode = isset($deliveryAddress[0]['pincode'])?$deliveryAddress[0]['pincode']:'';
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
/*<STATICVARIABLES>
               <SVCURRENTCOMPANY>AJS IMPEX  PVT. LTD. 2018-19</SVCURRENTCOMPANY>
              </STATICVARIABLES>*/
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
               <VOUCHER REMOTEID="'.$order['order_code'].'"  VCHTYPE="Sales Order"  ACTION="Create" OBJVIEW="Invoice Voucher View">
                <ADDRESS.LIST TYPE="String">
                 <ADDRESS>'.$invoiceAddress_1.'</ADDRESS>
                 <ADDRESS>'.$invoiceAddress_2.'</ADDRESS>
                 <ADDRESS>'.$invoiceCity.'-'.$invoicePincode.'</ADDRESS>


                </ADDRESS.LIST>
                <BASICBUYERADDRESS.LIST TYPE="String">
                 <BASICBUYERADDRESS>'.$invoiceAddress_1.'</BASICBUYERADDRESS>
                 <BASICBUYERADDRESS>'.$invoiceAddress_2.'</BASICBUYERADDRESS>
                 <BASICBUYERADDRESS>'.$invoiceCity.'-'.$invoicePincode.'</BASICBUYERADDRESS>

                </BASICBUYERADDRESS.LIST>
                <BASICORDERTERMS.LIST TYPE="String">
                 <BASICORDERTERMS>'.$deliveryTerm.'</BASICORDERTERMS>
                </BASICORDERTERMS.LIST>
                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                 <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                </OLDAUDITENTRYIDS.LIST>
                <ACTIVETO/>
                <ALTEREDON>'.$orderCreatedDate.'</ALTEREDON>
                <DATE>'.$orderModifiedDate.'</DATE>
                <LUTDATEOFISSUE/>
                <LUTEXPIRYDATE/>
                <BONDDATEOFISSUE/>
                <BONDEXPIRYDATE/>
                <TAXCHEQUEDATE/>
                <TAXCHALLANDATE/>
                <RECONCILATIONDATE/>
                <FORM16ISSUEDATE/>
                <CSTFORMISSUEDATE/>
                <CSTFORMRECVDATE/>
                <CERTIFICATEDATE/>
                <FBTFROMDATE/>
                <FBTTODATE/>
                <RETURNINVOICEDATE/>
                <AGGREMENTORDERDATE/>
                <GOODSRCPTDATE/>
                <BILLOFENTRYDATE/>
                <LORRYRECPTDATE/>
                <TRANSSALELANDINGDATE/>
                <TRANSBUYERLANDINGDATE/>
                <CREDITLETTERDATE/>
                <AIRWAYBILLDATE/>
                <SHIPPINGBILLDATE/>
                <VATORDERDATE/>
                <INVDELIVERYDATE/>
                <REFERENCEDATE/>
                <BILLOFLADINGDATE/>
                <VATDEPOSITDATE/>
                <VATDOCUMENTDATE/>
                <VATCHALLANDATE/>
                <ECDATE/>
                <VATTDSDATE/>
                <VATPARTYTRANSRETURNDATE/>
                <ADJFROMDATE/>
                <ADJTODATE/>
                <VATDDCHEQUEDATE/>
                <TAXPAYPERIODFROMDATE/>
                <TAXPAYPERIODTODATE/>
                <STTAXCHALLANDATE/>
                <GSTCHALLANDATE/>
                <GSTCHALLANEXPIRYDATE/>
                <ADJPARTYINVOICEDATE/>
                <ADJPARTYPAYMENTDATE/>
                <PARTYORDERDATE>'.$order['order_date'].'</PARTYORDERDATE>
                <ADVANCERECEIPTDATE/>
                <REFUNDVOUCHERDATE/>
                <VATSUBMISSIONDATE/>
                <PARTYINVDATE/>
                <INSPDOCDATE/>
                <VATCANCINVDATE/>
                <VATGOODSRECEIPTDATE/>
                <VATCSTE1DATE/>
                <DISPATCHDATE/>
                <AUDITEDON/>
                <STATENAME>'.$invoiceState.'</STATENAME>
                <GSTREGISTRATIONTYPE/>
                <VATDEALERTYPE/>
                <PRICELEVEL/>
                <TDSNATUREOFPAYMENT/>
                <AUTOCOSTLEVEL/>
                <NARRATION/>
                <REQUESTORRULE/>
                <ALTEREDBY>TEJASHRI</ALTEREDBY>
                <COUNTRYOFRESIDENCE>'.$invoiceCountry.'</COUNTRYOFRESIDENCE>
                <IMPORTEREXPORTERCODE/>
                <PARTYGSTIN>'.$gstNo.'</PARTYGSTIN>
                <NATUREOFSALES/>
                <EXCISENOTIFICATIONNO/>
                <LUTNUMBER/>
                <BONDNUMBER/>
                <AUTHORITYNAME/>
                <AUTHORITYADDRESS/>
                <TAXUNITNAME/>
                <EXCISEUNITNAME/>';
               /* echo $invoiceState.'<br>';
                echo $deliveryState;*/
                //echo $invoiceState;exit;
                
                /*if ($invoiceState=='Maharashtra') {
                  $gst = "GST";
                }
                else{
                  $gst = "IGST";
                }*/
                //echo $gst;exit;
                //echo $tax;exit;
                $xml.='<CLASSNAME>SALES @'.$tax.'% GST</CLASSNAME>
                <POSCARDLEDGER/>
                <POSCASHLEDGER/>
                <POSGIFTLEDGER/>
                <POSCHEQUELEDGER/>
                <EXCISECLASSIFICATIONNAME/>
                <TDSSECTIONNO/>
                <TDSDEDNSTATUS/>
                <TAXBANKCHALLANNUMBER/>
                <TAXCHALLANBSRCODE/>
                <TAXCHEQUENUMBER/>
                <TAXBANKNAME/>
                <TAXBANKBRANCHNAME/>
                <PARTYNAME>'.$ledgerName.'</PARTYNAME>
                <VOUCHERTYPENAME>Sales Order</VOUCHERTYPENAME>
                <REFERENCE>'.$orderCode.'</REFERENCE>
                <VOUCHERNUMBER>'.$orderCode.'</VOUCHERNUMBER>
                <PARTYLEDGERNAME>'.$ledgerName.'</PARTYLEDGERNAME>
                <BASICBASEPARTYNAME>'.$ledgerName.'</BASICBASEPARTYNAME>
                <BASICVOUCHERCHEQUENAME/>
                <BASICVOUCHERCROSSCOMMENT/>
                <EXCHGCURRENCYNAME/>
                <SERIALMASTER/>
                <SERIALNUMBER/>
                <STATADJUSTMENTTYPE/>
                <STATPAYMENTTYPE/>
                <CONSIGNEEIECODE/>
                <SUPPLIERIECODE/>
                <ARESERIALMASTER/>
                <ARESERIALNUMBER/>
                <SUMAUTOVCHNUM/>
                <TAXBANKACCOUNTNUMBER/>
                <CSTFORMISSUETYPE/>
                <CSTFORMISSUENUMBER/>
                <CSTFORMRECVTYPE/>
                <CSTFORMRECVNUMBER/>
                <CONSIGNEECSTNUMBER/>
                <BUYERSCSTNUMBER/>
                <CSTFORMISSUESERIESNUM/>
                <CSTFORMRECVSERIESNUM/>
                <EXCISETREASURYNUMBER/>
                <EXCISETREASURYNAME/>
                <CERTIFICATETYPE/>
                <CERTIFICATENUMBER/>
                <AREFORMTYPE/>
                <DESTINATIONTAXUNIT/>
                <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                <POSCARDNUMBER/>
                <POSCHEQUENUMBER/>
                <POSCHEQUEBANKNAME/>
                <TAXADJUSTMENT/>
                <CHALLANTYPE/>
                <CHEQUEDEPOSITORNAME/>
                <EXCISENOTIFICATIONSERIALNO/>
                <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
                <EXCISETARIFFTYPE/>
                <CONSIGNEELBTREGNNO/>
                <CONSIGNEELBTZONE/>
                <SUPPLIERLBTREGNNO/>
                <SUPPLIERLBTZONE/>
                <LBTMAPPEDCATEGORY/>
                <LBTMAPPEDZONE/>
                <LBTNATUREOFLIABILITY/>
                <CASHPARTYPAN/>
                <CASHPARTYDEDTYPE/>
                <VCHTAXTYPE/>
                <PURPOSEOFPURCHASE/>
                <POINTOFTRANSACTION/>
                <TRANSPORTERNAME/>
                <TRANSPORTMODE/>
                <AGGREMENTORDERNO/>
                <FOREIGNSELLERNAME/>
                <EXPORTERCOUNTRY/>
                <BILLOFENTRYNO/>
                <GOODSVEHICLENUMBER/>
                <SHIPNAME/>
                <SHIPAGENTNAME/>
                <CLEARINGAGENTNAME/>
                <LORRYRECPTNO/>
                <CARRIERNAME/>
                <CREDITLETTERREF/>
                <AIRWAYBILLNO/>
                <SHIPPINGBILLNO/>
                <FWDAGENTNAME/>
                <VATORDERNO/>
                <VATSELLERTIN/>
                <TRANSSOURCEPLACE/>
                <TRANSCATEGORY/>
                <VATDOCUMENTTYPE/>
                <VATTRANSBILLQTY/>
                <VATTRANSBILLNO/>
                <BILLOFLADINGNO/>
                <VATPAIDAGAINST/>
                <VATBANKNAME/>
                <VATBANKBRANCH/>
                <VATDDCHEQUENO/>
                <VATADJUSTMENTTYPE/>
                <VATFORMSTATUS/>
                <VATDOCUMENTNUMBER/>
                <VATSOURCESTATE/>
                <VATDESTINATIONSTATE/>
                <VATDESTINATIONPLACE/>
                <VATPARTYORGTYPE/>
                <VATPARTYTYPE/>
                <VATCHALLANNUMBER/>
                <VATDESIGOFPURCHASER/>
                <VATPURCHASERCPTTYPE/>
                <VATGOODSRCPTNO/>
                <VATPARTYORGNAME/>
                <AIRPORTNAME/>
                <TDNOFAWARDER/>
                <ECNUMBER/>
                <ECISSUINGAUTHORITY/>
                <CONTRACTORTIN/>
                <VATTDSBARCODE/>
                <VATPYMTMODEOFDEPOSIT/>
                <VATPYMTTAXDESC/>
                <VATBANKACCNUMBER/>
                <VATBRANCHCODE/>
                <VATTRANSSOURCE/>
                <VATPARTYTRANSRETURNNUMBER/>
                <VATADJADDLDETAILS/>
                <VATBRANCHNAME/>
                <PRIORITYSTATECONFLICT/>
                <EICHECKPOST/>
                <PORTNAME/>
                <PORTCODE/>
                <VATEFORMAPPLICABLE/>
                <VATEFORMAPPLICABLENO/>
                <VATINCOURSEOF/>
                <VATDISPATCHTIME/>
                <VATTRANSPORTERADDRESS/>
                <VATCONTRACTEETDN/>
                <VATCONTRACTEEDISTRICT/>
                <VATCONTRACTEENAME/>
                <CONSUMERIDENTIFICATIONNUMBER/>
                <STTVCHRHANDLE/>
                <SRVTREGNUMBER/>
                <TAXPAYMENTTYPE/>
                <STTAXBANKCHALLANNUMBER/>
                <TYPEOFEXCISEVOUCHER/>
                <GSTBANKNAME/>
                <GSTBANKACCOUNTNUMBER/>
                <GSTBANKACCOUNTTYPE/>
                <GSTBANKACCOUNTHOLDER/>
                <GSTBANKBRANCHADDRESS/>
                <GSTBANKIFSCCODE/>
                <GSTBANKMICRCODE/>
                <GSTDEBITDOCNUMBER/>
                <GSTPYMTMODEOFDEPOSIT/>
                <GSTBANKBRANCHNAME/>
                <GSTINSTRUMENTNUMBER/>
                <GSTCHALLANNUMBER/>
                <GSTCPINNUMBER/>
                <GSTCINNUMBER/>
                <ADJPARTYGSTIN/>
                <ADJPARTYINVOICENO/>
                <GSTMERCHANTID/>
                <PARTYORDERNO/>
                <GSTITCREVERSALDETAILS/>
                <GSTNATUREOFRETURN/>
                <GSTITCDOCUMENTTYPE/>
                <GSTADDITIONALDETAILS/>
                <GSTACTIVITYSTATUS/>
                <GSTRECONSTATUS/>
                <GSTREASONFORREJECTION/>
                <PLACEOFSUPPLY>'.$deliveryState.'</PLACEOFSUPPLY>
                <ADVANCERECEIPTNUMBER/>
                <REFUNDVOUCHERNUMBER/>
                <URDORIGINALSALEVALUE/>
                <VCHTAXUNIT/>
                <CONSIGNEEGSTIN>'.$gstNo.'</CONSIGNEEGSTIN>
                <BASICSHIPPEDBY/>
                <BASICDESTINATIONCOUNTRY/>
                <BASICBUYERNAME>'.$ledgerName.'</BASICBUYERNAME>
                <BASICPLACEOFRECEIPT/>
                <BASICSHIPDOCUMENTNO/>
                <BASICPORTOFLOADING/>
                <BASICPORTOFDISCHARGE/>
                <BASICFINALDESTINATION/>
                <BASICORDERREF>'.$brokerLedgerName.'</BASICORDERREF>
                <BASICSHIPVESSELNO/>
                <BASICBUYERSSALESTAXNO/>
                <BASICDUEDATEOFPYMT>'.$paymentDueDay.'</BASICDUEDATEOFPYMT>
                <BASICSERIALNUMINPLA/>
                <BASICDATETIMEOFINVOICE/>
                <BASICDATETIMEOFREMOVAL/>
                <BUYERADDRESSTYPE/>
                <PARTYADDRESSTYPE/>
                <MFGRADDRESSTYPE/>
                <TRANSPORTERADDRROOM/>
                <TRANSPORTERADDRBLDG/>
                <TRANSPORTERADDRROAD/>
                <TRANSPORTERADDRAREA/>
                <TRANSPORTERADDRTOWN/>
                <TRANSPORTERADDRDIST/>
                <TRANSPORTERADDRSTATE/>
                <TRANSPORTERADDRPINCODE/>
                <TRANSPORTERADDRPHONE/>
                <TRANSPORTERADDRFAX/>
                <TRANSPORTERVEHICLE2/>
                <TRANSPORTLOCALTIN/>
                <VATCFORMISSUESTATE/>
                <PLACEOFSUPPLYSTATE/>
                <PLACEOFSUPPLYCOUNTRY/>
                <EMIRATEPOS/>
                <VCHGSTCLASS/>
                <COSTCENTRENAME/>
                <ADDITIONALNARRATION/>
                <PARTYINVNO/>
                <INSPDOCNO/>
                <SETTLEMENTTYPE/>
                <VOUCHERTIME/>
                <HOLDREFERENCE/>
                <VATCANCINVNO/>
                <VATCANCPURCTIN/>
                <VATCANCPURCNAME/>
                <VATTYPEOFDEVICE/>
                <VATBRIEFDESCRIPTION/>
                <VATDEVICENO/>
                <BUYERPINNUMBER/>
                <VATEXPORTENTRYNO/>
                <CONSIGNEEPINNUMBER>'.$deliveryPincode.'</CONSIGNEEPINNUMBER>
                <VATEXEMPTCERTIFICATENO/>
                <VATVEHICLENUMBER/>
                <VATGOODSRECEIPTNUMBER/>
                <VATMOBILENUMBER/>
                <VATCSTE1SERIESNO/>
                <VATCSTE1SERIALNO/>
                <VATPERMITFORM/>
                <VATCERTIFICATENO/>
                <CONSIGNEECIRCLE/>
                <CONSIGNEECITY/>
                <CONSIGNEESTATENAME>'.$deliveryState.'</CONSIGNEESTATENAME>
                <CONSIGNEEPINCODE/>
                <CONSIGNEEMOBILENUMBER/>
                <CONSIGNEEOTHERS/>
                <CONSIGNEEMAIL/>
                <DELIVERYCITY/>
                <DELIVERYPINCODE/>
                <DELIVERYOTHERS/>
                <DELIVERYSTATE/>
                <DISPATCHCITY/>
                <DISPATCHPINCODE/>
                <DESTINATIONPERMITNUMBER/>
                <ENTRYCHECKPOSTLOCATION/>
                <EXITCHECKPOSTLOCATION/>
                <VATTDSDEDUCTORNAME/>
                <ENTEREDBY>TEJASHRI</ENTEREDBY>
                <VOUCHERTYPEORIGNAME/>
                <DIFFACTUALQTY>No</DIFFACTUALQTY>
                <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
                <ASORIGINAL>No</ASORIGINAL>
                <AUDITED>No</AUDITED>
                <FORJOBCOSTING>No</FORJOBCOSTING>
                <ISOPTIONAL>No</ISOPTIONAL>
                <EFFECTIVEDATE>'.$orderModifiedDate.'</EFFECTIVEDATE>
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
                <BONDAMOUNT/>
                <POSCASHRECEIVED/>
                <CUSTOMDUTYPAID/>
                <VATPARTYITCCLAIMED/>
                <VATPARTYTAXLIABILITY/>
                <VALUEOFWORKSCONTRACT/>
                <ECFEEAMOUNT/>
                <ECFEEDEPOSITBYAWARDER/>
                <ECFEEDEPOSITBYCONTRACTOR/>
                <TDSDEDUCTED/>
                <VALUEOFSUBWORKSCONT/>
                <VATTDSAMT/>
                <ADJPARTYINVOICEVALUE/>
                <GSTINVASSESSABLEVALUE/>
                <VATGOODSVALUE/>
                <EXCHGRATE/>
                <PROCESSINGDURATION/>
                <TEMPGSTEWAYCONSIGNORADDRESSTYPE/>
                <TEMPGSTEWAYCONSIGNEEADDRESSTYPE/>
                <EIDEFAULTLED_ISZRBASICSERVICE/>
                <PREVBOMNAME/>
                <CURBOMNAME/>
                <COMMONNATUREOFPAYMENT/>
                <TEMPISVCHSUPPLFILLED/>
                <CURRPARTYLEDGERNAME/>
                <CURRBASICBUYERNAME/>
                <CURRPARTYNAME/>
                <CURRBUYERADDRESSTYPE/>
                <CURRPARTYADDRESSTYPE/>
                <CURRSTATENAME/>
                <CURRBASICPURCHASEORDERNO/>
                <CURRBASICSHIPDELIVERYNOTE/>
                <POSSTAXPARTYLEDGERNAME/>
                <ISCSTAGTFORMC/>
                <EIDEFAULTLED_ISUSERDEFINED/>
                <EIDEFAULTLED_CLASSNAME/>
                <EIDEFAULTLED_CLASSRATE>0</EIDEFAULTLED_CLASSRATE>
                <EIDEFAULTLED_CLASSADDLRATE>0</EIDEFAULTLED_CLASSADDLRATE>
                <TEMPVATCLASSIFICATION/>
                <ISATTDDATAPRESERVED/>
                <TEMPGSTEWAYBILLNUMBER/>
                <TEMPGSTEWAYBILLDATE/>
                <TEMPGSTEWAYSUBTYPE/>
                <TEMPGSTEWAYDOCUMENTTYPE/>
                <TEMPGSTEWAYSTATUS/>
                <TEMPGSTEWAYCONSIGNOR/>
                <TEMPGSTEWAYCONSIGNORADDRESS/>
                <TEMPGSTEWAYFROMPLACE/>
                <TEMPCONSIGNORPINCODENUMBER>0</TEMPCONSIGNORPINCODENUMBER>
                <TEMPGSTEWAYPINCODENUMBER>0</TEMPGSTEWAYPINCODENUMBER>
                <TEMPGSTEWAYPINCODE/>
                <TEMPGSTEWAYCONSIGNORTIN/>
                <TEMPGSTEWAYCONSIGNORSTATE/>
                <TEMPGSTEWAYCONSSHIPFROMSTATE/>
                <TEMPGSTEWAYCONSIGNEE/>
                <TEMPGSTEWAYCONSADDRESS/>
                <TEMPGSTEWAYCONSFROMPLACE/>
                <TEMPGSTEWAYCONSPINCODENUMBER>0</TEMPGSTEWAYCONSPINCODENUMBER>
                <TEMPCONSIGNEEPINCODENUMBER>0</TEMPCONSIGNEEPINCODENUMBER>
                <TEMPGSTEWAYCONSPINCODE/>
                <TEMPGSTEWAYCONSTIN/>
                <TEMPGSTEWAYCONSSTATE/>
                <TEMPGSTEWAYCONSSHIPTOSTATE/>
                <TEMPGSTEWAYTRANSPORTMODE/>
                <TEMPGSTEWAYDISTANCE>0</TEMPGSTEWAYDISTANCE>
                <TEMPGSTEWAYTRANSPORTERNAME/>
                <TEMPGSTEWAYVEHICLENUMBER/>
                <TEMPGSTEWAYTRANSPORTERID/>
                <TEMPGSTEWAYTRANSPORTERDOCNO/>
                <TEMPGSTEWAYTRANSPORTERDOCDATE/>
                <TEMPGSTEWAYVEHICLETYPE/>
                <TEMPGSTEWAYCONSBILLNUMBER/>
                <TEMPGSTEWAYCONSBILLDATE/>
                <TEMPISUSERDEFINEDCLASS/>
                <TEMPCLASSNATURE/>
                <TEMPGSTOVRDNINELIGIBLEITC/>
                <TEMPGSTOVRDNISREVCHARGEAPPL/>
                <TEMPGSTOVRDNTAXABILITY/>
                <TEMPGSTOVRDNISREVCHARGEAPPLSTR/>
                <TEMPGSTOVRDNINELIGIBLEITCSTR/>
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
                 <NARRATION/>
                 <ADDLALLOCTYPE/>
                 <TAXCLASSIFICATIONNAME/>
                 <NOTIFICATIONSLNO/>
                 <ROUNDTYPE/>
                 <LEDGERNAME>'.$ledgerName.'</LEDGERNAME>
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
                 $xml.='<AMOUNT>'.$amt_before_tax.'</AMOUNT>
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
                 <ORIGPURCHINVDATE/>
                 <NARRATION/>
                 <ADDLALLOCTYPE/>
                 <TAXCLASSIFICATIONNAME/>
                 <NOTIFICATIONSLNO/>
                 <ROUNDTYPE/>
                 <LEDGERNAME>LOADING CHARGES (S)</LEDGERNAME>
                 <TAXUNITNAME/>
                 <STATNATURENAME/>
                 <GOODSTYPE/>
                 <METHODTYPE>As User Defined Value</METHODTYPE>
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
                 //echo $order['other_charges'];exit;
                 $sumAmount[] = $order['other_charges'];
                 //echo $xml;
                 //print_r($sumAmount);exit;
                 $xml.='<AMOUNT>'.$order['other_charges'].'</AMOUNT>
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
                 <VATEXPAMOUNT>'.$order['other_charges'].'</VATEXPAMOUNT>
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
                if ($invoiceState=='Maharashtra') {
                  # code...
                $xml.='<LEDGERENTRIES.LIST>
                 <OLDAUDITENTRYIDS.LIST TYPE="Number">
                  <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                 </OLDAUDITENTRYIDS.LIST>
                 <ORIGPURCHINVDATE/>
                 <NARRATION/>
                 <ADDLALLOCTYPE/>
                 <TAXCLASSIFICATIONNAME/>
                 <NOTIFICATIONSLNO/>
                 <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                 <LEDGERNAME>SGST OUTPUT @ 9%</LEDGERNAME>
                 <TAXUNITNAME/>
                 <STATNATURENAME/>
                 <GOODSTYPE/>
                 <METHODTYPE>GST</METHODTYPE>
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
                 $sumAmount[] = ($amt_before_tax+$order['other_charges'])*($tax/100);
                 $total_amount = number_format((float)array_sum($sumAmount), 2, '.', '');
                 $total_amount = explode('.', $total_amount);
                 $roundOff = $total_amount[1];
                 //echo $roundOff;exit;

                 $xml.='<AMOUNT>'.($amt_before_tax+$order['other_charges'])*($tax/200).'</AMOUNT>
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
                 <VATEXPAMOUNT>'.($amt_before_tax+$order['other_charges'])*($tax/200).'</VATEXPAMOUNT>
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
                 <ORIGPURCHINVDATE/>
                 <NARRATION/>
                 <ADDLALLOCTYPE/>
                 <TAXCLASSIFICATIONNAME/>
                 <NOTIFICATIONSLNO/>
                 <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                 <LEDGERNAME>CGST OUTPUT @ 9%</LEDGERNAME>
                 <TAXUNITNAME/>
                 <STATNATURENAME/>
                 <GOODSTYPE/>
                 <METHODTYPE>GST</METHODTYPE>
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
                 <AMOUNT>'.($amt_before_tax+$order['other_charges'])*($tax/200).'</AMOUNT>
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
                 <VATEXPAMOUNT>'.($amt_before_tax+$order['other_charges'])*($tax/200).'</VATEXPAMOUNT>
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
                 <ORIGPURCHINVDATE/>
                 <NARRATION/>
                 <ADDLALLOCTYPE/>
                 <TAXCLASSIFICATIONNAME/>
                 <NOTIFICATIONSLNO/>
                 <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                 <LEDGERNAME>IGST OUTPUT @ 18%</LEDGERNAME>
                 <TAXUNITNAME/>
                 <STATNATURENAME/>
                 <GOODSTYPE/>
                 <METHODTYPE>GST</METHODTYPE>
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
                 $sumAmount[] = ($amt_before_tax+$order['other_charges'])*($tax/100);
                 $total_amount = number_format((float)array_sum($sumAmount), 2, '.', '');
                 $total_amount = explode('.', $total_amount);
                 $roundOff = $total_amount[1];
                 //echo $roundOff;exit;

                 $xml.='<AMOUNT>'.($amt_before_tax+$order['other_charges'])*($tax/100).'</AMOUNT>
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
                 <VATEXPAMOUNT>'.($amt_before_tax+$order['other_charges'])*($tax/100).'</VATEXPAMOUNT>
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
                 <ORIGPURCHINVDATE/>
                 <NARRATION/>
                 <ADDLALLOCTYPE/>
                 <TAXCLASSIFICATIONNAME/>
                 <NOTIFICATIONSLNO/>
                 <ROUNDTYPE/>
                 <LEDGERNAME>ROUND OFF</LEDGERNAME>
                 <TAXUNITNAME/>
                 <STATNATURENAME/>
                 <GOODSTYPE/>
                 <METHODTYPE>As User Defined Value</METHODTYPE>
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
                 <VATEXPAMOUNT>0.20</VATEXPAMOUNT>
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
                    //print_r($dueOn);exit;
                 $xml.='<ALLINVENTORYENTRIES.LIST>
                  <BASICUSERDESCRIPTION.LIST TYPE="String">
                    <BASICUSERDESCRIPTION>'.$thickness.'MMX'.$width.'X'.$length.'</BASICUSERDESCRIPTION>
                    <BASICUSERDESCRIPTION>'.$grade.'</BASICUSERDESCRIPTION>
                    <BASICUSERDESCRIPTION>'.$coilNo.'</BASICUSERDESCRIPTION>
                    <BASICUSERDESCRIPTION>'.$remark.'</BASICUSERDESCRIPTION>
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
                 <EXCISEASSESSABLEVALUE/>
                 <VATASSESSABLEVALUE/>
                 <ORIGINVGOODSVALUE/>
                 <GSTASSBLVALUE/>
                 <ORIGINVGOODSTAXVALUE/>
                 <VATASSBLVALUE/>
                 <VATACCEPTEDTAXAMT/>
                 <VATACCEPTEDADDLTAXAMT/>
                 <GVATEXCISEAMT/>
                 <ACTUALQTY> '.$orderDetail['qty'].' M.T.</ACTUALQTY>
                <BILLEDQTY> '.$orderDetail['qty'].' M.T.</BILLEDQTY>
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
                  <GODOWNNAME/><BATCHNAME>Any</BATCHNAME>';
                  
                  $xml.='<DESTINATIONGODOWNNAME/>
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
                  
                  ';
                  /*<ACTUALQTY> '.$orderDetail['qty'].' M.T.</ACTUALQTY>
                  <BILLEDQTY> '.$orderDetail['qty'].' M.T.</BILLEDQTY>*/

                  $xml.='<ORDERPRECLOSUREQTY/>
                  <ORIGACTUALQTY/>
                  <ORIGBILLEDQTY/>
                  <BATCHPHYSDIFF/>
                  <ORIGRATE/>
                  <REVISEDRATE/>
                  <ESCALATIONRATE/>
                  <INCLVATRATE/>
                  <EXPIRYPERIOD/>
                  <INDENTDUEDATE/>
                  <ORDERDUEDATE>'.date('d-F-Y',strtotime($dueOn)).'</ORDERDUEDATE>
                  <INCLUSIVETAXVALUE/>
                  <ISPRECLOSED/>
                  <BATCHALLOCBOMNAME/>
                  <PREVBATCHALLOCBOMNAME/>
                  <BOMALLOCACCEPTED/>
                  <BATCHALLOCBOMBASEQTY/>
                  <COSTTRACKID>0</COSTTRACKID>
                  <ISINCLTAXRATEFIELDEDITED/>
                  <ADDITIONALDETAILS.LIST>        </ADDITIONALDETAILS.LIST>
                  <VOUCHERCOMPONENTLIST.LIST>        </VOUCHERCOMPONENTLIST.LIST>
                 </BATCHALLOCATIONS.LIST>
                 
                 <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                 <SUPPLEMENTARYDUTYHEADDETAILS.LIST>       </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
                 <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                 <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                 <EXCISEALLOCATIONS.LIST>       </EXCISEALLOCATIONS.LIST>
                 <EXPENSEALLOCATIONS.LIST>       </EXPENSEALLOCATIONS.LIST>
                </ALLINVENTORYENTRIES.LIST>';
                  }
                  //echo $xml;exit;
                  //print_r($sumAmount);
                  //echo count($sumAmount).'<br>'; 
                  
                  //print_r($total_amount);exit;
                  //print_r(array_sum($sumAmount));exit;
                $xml.='<ACCOUNTINGALLOCATIONS.LIST>
                  <OLDAUDITENTRYIDS.LIST TYPE="Number">
                   <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                  </OLDAUDITENTRYIDS.LIST>
                  <ORIGPURCHINVDATE/>
                  <NARRATION/>
                  <ADDLALLOCTYPE/>
                  <TAXCLASSIFICATIONNAME/>
                  <NOTIFICATIONSLNO/>
                  <ROUNDTYPE/>
                  <LEDGERNAME>SALES @18% GST</LEDGERNAME>
                  <TAXUNITNAME/>
                  <STATNATURENAME/>
                  <GOODSTYPE/>
                  <METHODTYPE/>
                  <CLASSRATE>100.00000</CLASSRATE>
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
                  <GSTOVRDNNATURE>Sales Taxable</GSTOVRDNNATURE>
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
                   <GSTRATEVALUATIONTYPE/>
                   <GSTRATE>0</GSTRATE>
                   <GSTRATEPERUNIT>0</GSTRATEPERUNIT>
                   <TEMPGSTRATE>0</TEMPGSTRATE>
                  </RATEDETAILS.LIST>
                  <RATEDETAILS.LIST>
                   <GSTRATEDUTYHEAD>Central Tax</GSTRATEDUTYHEAD>
                   <GSTRATEVALUATIONTYPE/>
                   <GSTRATE>0</GSTRATE>
                   <GSTRATEPERUNIT>0</GSTRATEPERUNIT>
                   <TEMPGSTRATE>0</TEMPGSTRATE>
                  </RATEDETAILS.LIST>
                  <RATEDETAILS.LIST>
                   <GSTRATEDUTYHEAD>State Tax</GSTRATEDUTYHEAD>
                   <GSTRATEVALUATIONTYPE/>
                   <GSTRATE>0</GSTRATE>
                   <GSTRATEPERUNIT>0</GSTRATEPERUNIT>
                   <TEMPGSTRATE>0</TEMPGSTRATE>
                  </RATEDETAILS.LIST>
                  <RATEDETAILS.LIST>
                   <GSTRATEDUTYHEAD>Cess</GSTRATEDUTYHEAD>
                   <GSTRATEVALUATIONTYPE/>
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
                <PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
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
    /*print_r($res);
    exit;*/
    return $res;
  }

  function test_import_so(){
    $xml = '
<ENVELOPE>
 <HEADER>
  <TALLYREQUEST>Import Data</TALLYREQUEST>
 </HEADER>
 <BODY>
  <IMPORTDATA>
   <REQUESTDESC>
    <REPORTNAME>All Masters</REPORTNAME>
    <STATICVARIABLES>
     <SVCURRENTCOMPANY>AJS IMPEX  PVT. LTD. 2018-19</SVCURRENTCOMPANY>
    </STATICVARIABLES>
   </REQUESTDESC>
   <REQUESTDATA>
    <TALLYMESSAGE xmlns:UDF="TallyUDF">
     <VOUCHER REMOTEID="AJ/SO-00003/19-20" VCHTYPE="Sales Order" ACTION="Create" OBJVIEW="Invoice Voucher View">
      <ADDRESS.LIST TYPE="String">
       <ADDRESS>204/A, 2ND FLOOR,</ADDRESS>
       <ADDRESS>CORPORATE CENTER PREMISES, MAROL PIPE LINE.</ADDRESS>
       <ADDRESS>ANDHERI-KURLA ROAD,ANDHERI EAST ,</ADDRESS>
       <ADDRESS>MUMBAI-400059</ADDRESS>
      </ADDRESS.LIST>
      <BASICBUYERADDRESS.LIST TYPE="String">
       <BASICBUYERADDRESS>PLOT NO. 1260, 1261,&amp; 1262,</BASICBUYERADDRESS>
       <BASICBUYERADDRESS>KALAMBOLI STEEL NAVI MUMBAI-410218</BASICBUYERADDRESS>
      </BASICBUYERADDRESS.LIST>
      <BASICORDERTERMS.LIST TYPE="String">
       <BASICORDERTERMS>EX-TALOJA</BASICORDERTERMS>
      </BASICORDERTERMS.LIST>
      <OLDAUDITENTRYIDS.LIST TYPE="Number">
       <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
      </OLDAUDITENTRYIDS.LIST>
      <ACTIVETO/>
      <ALTEREDON/>
      <DATE>20190424</DATE>
      <LUTDATEOFISSUE/>
      <LUTEXPIRYDATE/>
      <BONDDATEOFISSUE/>
      <BONDEXPIRYDATE/>
      <TAXCHEQUEDATE/>
      <TAXCHALLANDATE/>
      <RECONCILATIONDATE/>
      <FORM16ISSUEDATE/>
      <CSTFORMISSUEDATE/>
      <CSTFORMRECVDATE/>
      <CERTIFICATEDATE/>
      <FBTFROMDATE/>
      <FBTTODATE/>
      <RETURNINVOICEDATE/>
      <AGGREMENTORDERDATE/>
      <GOODSRCPTDATE/>
      <BILLOFENTRYDATE/>
      <LORRYRECPTDATE/>
      <TRANSSALELANDINGDATE/>
      <TRANSBUYERLANDINGDATE/>
      <CREDITLETTERDATE/>
      <AIRWAYBILLDATE/>
      <SHIPPINGBILLDATE/>
      <VATORDERDATE/>
      <INVDELIVERYDATE/>
      <REFERENCEDATE/>
      <BILLOFLADINGDATE/>
      <VATDEPOSITDATE/>
      <VATDOCUMENTDATE/>
      <VATCHALLANDATE/>
      <ECDATE/>
      <VATTDSDATE/>
      <VATPARTYTRANSRETURNDATE/>
      <ADJFROMDATE/>
      <ADJTODATE/>
      <VATDDCHEQUEDATE/>
      <TAXPAYPERIODFROMDATE/>
      <TAXPAYPERIODTODATE/>
      <STTAXCHALLANDATE/>
      <GSTCHALLANDATE/>
      <GSTCHALLANEXPIRYDATE/>
      <ADJPARTYINVOICEDATE/>
      <ADJPARTYPAYMENTDATE/>
      <PARTYORDERDATE/>
      <ADVANCERECEIPTDATE/>
      <REFUNDVOUCHERDATE/>
      <VATSUBMISSIONDATE/>
      <PARTYINVDATE/>
      <INSPDOCDATE/>
      <VATCANCINVDATE/>
      <VATGOODSRECEIPTDATE/>
      <VATCSTE1DATE/>
      <DISPATCHDATE/>
      <AUDITEDON/>
      <GUID>572f2ebb-d04c-4c56-94f3-a79d8923e290-000185d5</GUID>
      <STATENAME>Maharashtra</STATENAME>
      <GSTREGISTRATIONTYPE/>
      <VATDEALERTYPE/>
      <PRICELEVEL/>
      <TDSNATUREOFPAYMENT/>
      <AUTOCOSTLEVEL/>
      <NARRATION/>
      <REQUESTORRULE/>
      <ALTEREDBY/>
      <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
      <IMPORTEREXPORTERCODE/>
      <PARTYGSTIN>27AADCD1301K1Z1</PARTYGSTIN>
      <NATUREOFSALES/>
      <EXCISENOTIFICATIONNO/>
      <LUTNUMBER/>
      <BONDNUMBER/>
      <AUTHORITYNAME/>
      <AUTHORITYADDRESS/>
      <TAXUNITNAME/>
      <EXCISEUNITNAME/>
      <CLASSNAME>SALES @18% GST</CLASSNAME>
      <POSCARDLEDGER/>
      <POSCASHLEDGER/>
      <POSGIFTLEDGER/>
      <POSCHEQUELEDGER/>
      <EXCISECLASSIFICATIONNAME/>
      <TDSSECTIONNO/>
      <TDSDEDNSTATUS/>
      <TAXBANKCHALLANNUMBER/>
      <TAXCHALLANBSRCODE/>
      <TAXCHEQUENUMBER/>
      <TAXBANKNAME/>
      <TAXBANKBRANCHNAME/>
      <PARTYNAME>DARUKHANA  STEEL PVT LTD</PARTYNAME>
      <VOUCHERTYPENAME>Sales Order</VOUCHERTYPENAME>
      <REFERENCE>AJ/SO-0328/19-20</REFERENCE>
      <VOUCHERNUMBER>AJ/SO-0328/19-20</VOUCHERNUMBER>
      <PARTYLEDGERNAME>DARUKHANA  STEEL PVT LTD</PARTYLEDGERNAME>
      <BASICBASEPARTYNAME>DARUKHANA  STEEL PVT LTD</BASICBASEPARTYNAME>
      <BASICVOUCHERCHEQUENAME/>
      <BASICVOUCHERCROSSCOMMENT/>
      <EXCHGCURRENCYNAME/>
      <SERIALMASTER/>
      <SERIALNUMBER/>
      <STATADJUSTMENTTYPE/>
      <STATPAYMENTTYPE/>
      <CONSIGNEEIECODE/>
      <SUPPLIERIECODE/>
      <ARESERIALMASTER/>
      <ARESERIALNUMBER/>
      <SUMAUTOVCHNUM/>
      <TAXBANKACCOUNTNUMBER/>
      <CSTFORMISSUETYPE/>
      <CSTFORMISSUENUMBER/>
      <CSTFORMRECVTYPE/>
      <CSTFORMRECVNUMBER/>
      <CONSIGNEECSTNUMBER/>
      <BUYERSCSTNUMBER/>
      <CSTFORMISSUESERIESNUM/>
      <CSTFORMRECVSERIESNUM/>
      <EXCISETREASURYNUMBER/>
      <EXCISETREASURYNAME/>
      <CERTIFICATETYPE/>
      <CERTIFICATENUMBER/>
      <AREFORMTYPE/>
      <DESTINATIONTAXUNIT/>
      <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
      <POSCARDNUMBER/>
      <POSCHEQUENUMBER/>
      <POSCHEQUEBANKNAME/>
      <TAXADJUSTMENT/>
      <CHALLANTYPE/>
      <CHEQUEDEPOSITORNAME/>
      <EXCISENOTIFICATIONSERIALNO/>
      <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
      <EXCISETARIFFTYPE/>
      <CONSIGNEELBTREGNNO/>
      <CONSIGNEELBTZONE/>
      <SUPPLIERLBTREGNNO/>
      <SUPPLIERLBTZONE/>
      <LBTMAPPEDCATEGORY/>
      <LBTMAPPEDZONE/>
      <LBTNATUREOFLIABILITY/>
      <CASHPARTYPAN/>
      <CASHPARTYDEDTYPE/>
      <VCHTAXTYPE/>
      <PURPOSEOFPURCHASE/>
      <POINTOFTRANSACTION/>
      <TRANSPORTERNAME/>
      <TRANSPORTMODE/>
      <AGGREMENTORDERNO/>
      <FOREIGNSELLERNAME/>
      <EXPORTERCOUNTRY/>
      <BILLOFENTRYNO/>
      <GOODSVEHICLENUMBER/>
      <SHIPNAME/>
      <SHIPAGENTNAME/>
      <CLEARINGAGENTNAME/>
      <LORRYRECPTNO/>
      <CARRIERNAME/>
      <CREDITLETTERREF/>
      <AIRWAYBILLNO/>
      <SHIPPINGBILLNO/>
      <FWDAGENTNAME/>
      <VATORDERNO/>
      <VATSELLERTIN/>
      <TRANSSOURCEPLACE/>
      <TRANSCATEGORY/>
      <VATDOCUMENTTYPE/>
      <VATTRANSBILLQTY/>
      <VATTRANSBILLNO/>
      <BILLOFLADINGNO/>
      <VATPAIDAGAINST/>
      <VATBANKNAME/>
      <VATBANKBRANCH/>
      <VATDDCHEQUENO/>
      <VATADJUSTMENTTYPE/>
      <VATFORMSTATUS/>
      <VATDOCUMENTNUMBER/>
      <VATSOURCESTATE/>
      <VATDESTINATIONSTATE/>
      <VATDESTINATIONPLACE/>
      <VATPARTYORGTYPE/>
      <VATPARTYTYPE/>
      <VATCHALLANNUMBER/>
      <VATDESIGOFPURCHASER/>
      <VATPURCHASERCPTTYPE/>
      <VATGOODSRCPTNO/>
      <VATPARTYORGNAME/>
      <AIRPORTNAME/>
      <TDNOFAWARDER/>
      <ECNUMBER/>
      <ECISSUINGAUTHORITY/>
      <CONTRACTORTIN/>
      <VATTDSBARCODE/>
      <VATPYMTMODEOFDEPOSIT/>
      <VATPYMTTAXDESC/>
      <VATBANKACCNUMBER/>
      <VATBRANCHCODE/>
      <VATTRANSSOURCE/>
      <VATPARTYTRANSRETURNNUMBER/>
      <VATADJADDLDETAILS/>
      <VATBRANCHNAME/>
      <PRIORITYSTATECONFLICT/>
      <EICHECKPOST/>
      <PORTNAME/>
      <PORTCODE/>
      <VATEFORMAPPLICABLE/>
      <VATEFORMAPPLICABLENO/>
      <VATINCOURSEOF/>
      <VATDISPATCHTIME/>
      <VATTRANSPORTERADDRESS/>
      <VATCONTRACTEETDN/>
      <VATCONTRACTEEDISTRICT/>
      <VATCONTRACTEENAME/>
      <CONSUMERIDENTIFICATIONNUMBER/>
      <STTVCHRHANDLE/>
      <SRVTREGNUMBER/>
      <TAXPAYMENTTYPE/>
      <STTAXBANKCHALLANNUMBER/>
      <TYPEOFEXCISEVOUCHER/>
      <GSTBANKNAME/>
      <GSTBANKACCOUNTNUMBER/>
      <GSTBANKACCOUNTTYPE/>
      <GSTBANKACCOUNTHOLDER/>
      <GSTBANKBRANCHADDRESS/>
      <GSTBANKIFSCCODE/>
      <GSTBANKMICRCODE/>
      <GSTDEBITDOCNUMBER/>
      <GSTPYMTMODEOFDEPOSIT/>
      <GSTBANKBRANCHNAME/>
      <GSTINSTRUMENTNUMBER/>
      <GSTCHALLANNUMBER/>
      <GSTCPINNUMBER/>
      <GSTCINNUMBER/>
      <ADJPARTYGSTIN/>
      <ADJPARTYINVOICENO/>
      <GSTMERCHANTID/>
      <PARTYORDERNO/>
      <GSTITCREVERSALDETAILS/>
      <GSTNATUREOFRETURN/>
      <GSTITCDOCUMENTTYPE/>
      <GSTADDITIONALDETAILS/>
      <GSTACTIVITYSTATUS/>
      <GSTRECONSTATUS/>
      <GSTREASONFORREJECTION/>
      <PLACEOFSUPPLY>Maharashtra</PLACEOFSUPPLY>
      <ADVANCERECEIPTNUMBER/>
      <REFUNDVOUCHERNUMBER/>
      <URDORIGINALSALEVALUE/>
      <VCHTAXUNIT/>
      <CONSIGNEEGSTIN>27AADCD1301K1Z1</CONSIGNEEGSTIN>
      <BASICSHIPPEDBY/>
      <BASICDESTINATIONCOUNTRY/>
      <BASICBUYERNAME>DARUKHANA STEEL PVT. LTD. (C)</BASICBUYERNAME>
      <BASICPLACEOFRECEIPT/>
      <BASICSHIPDOCUMENTNO/>
      <BASICPORTOFLOADING/>
      <BASICPORTOFDISCHARGE/>
      <BASICFINALDESTINATION/>
      <BASICORDERREF>SANJAY PANCHAMIA-.50</BASICORDERREF>
      <BASICSHIPVESSELNO/>
      <BASICBUYERSSALESTAXNO/>
      <BASICDUEDATEOFPYMT>1</BASICDUEDATEOFPYMT>
      <BASICSERIALNUMINPLA/>
      <BASICDATETIMEOFINVOICE/>
      <BASICDATETIMEOFREMOVAL/>
      <BUYERADDRESSTYPE/>
      <PARTYADDRESSTYPE/>
      <MFGRADDRESSTYPE/>
      <TRANSPORTERADDRROOM/>
      <TRANSPORTERADDRBLDG/>
      <TRANSPORTERADDRROAD/>
      <TRANSPORTERADDRAREA/>
      <TRANSPORTERADDRTOWN/>
      <TRANSPORTERADDRDIST/>
      <TRANSPORTERADDRSTATE/>
      <TRANSPORTERADDRPINCODE/>
      <TRANSPORTERADDRPHONE/>
      <TRANSPORTERADDRFAX/>
      <TRANSPORTERVEHICLE2/>
      <TRANSPORTLOCALTIN/>
      <VATCFORMISSUESTATE/>
      <PLACEOFSUPPLYSTATE/>
      <PLACEOFSUPPLYCOUNTRY/>
      <EMIRATEPOS/>
      <VCHGSTCLASS/>
      <COSTCENTRENAME/>
      <ADDITIONALNARRATION/>
      <PARTYINVNO/>
      <INSPDOCNO/>
      <SETTLEMENTTYPE/>
      <VOUCHERTIME/>
      <HOLDREFERENCE/>
      <VATCANCINVNO/>
      <VATCANCPURCTIN/>
      <VATCANCPURCNAME/>
      <VATTYPEOFDEVICE/>
      <VATBRIEFDESCRIPTION/>
      <VATDEVICENO/>
      <BUYERPINNUMBER/>
      <VATEXPORTENTRYNO/>
      <CONSIGNEEPINNUMBER/>
      <VATEXEMPTCERTIFICATENO/>
      <VATVEHICLENUMBER/>
      <VATGOODSRECEIPTNUMBER/>
      <VATMOBILENUMBER/>
      <VATCSTE1SERIESNO/>
      <VATCSTE1SERIALNO/>
      <VATPERMITFORM/>
      <VATCERTIFICATENO/>
      <CONSIGNEECIRCLE/>
      <CONSIGNEECITY/>
      <CONSIGNEESTATENAME>Maharashtra</CONSIGNEESTATENAME>
      <CONSIGNEEPINCODE/>
      <CONSIGNEEMOBILENUMBER/>
      <CONSIGNEEOTHERS/>
      <CONSIGNEEMAIL/>
      <DELIVERYCITY/>
      <DELIVERYPINCODE/>
      <DELIVERYOTHERS/>
      <DELIVERYSTATE/>
      <DISPATCHCITY/>
      <DISPATCHPINCODE/>
      <DESTINATIONPERMITNUMBER/>
      <ENTRYCHECKPOSTLOCATION/>
      <EXITCHECKPOSTLOCATION/>
      <VATTDSDEDUCTORNAME/>
      <ENTEREDBY>TEJASHRI</ENTEREDBY>
      <VOUCHERTYPEORIGNAME/>
      <DIFFACTUALQTY>No</DIFFACTUALQTY>
      <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
      <ASORIGINAL>No</ASORIGINAL>
      <AUDITED>No</AUDITED>
      <FORJOBCOSTING>No</FORJOBCOSTING>
      <ISOPTIONAL>No</ISOPTIONAL>
      <EFFECTIVEDATE>20190424</EFFECTIVEDATE>
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
      <ECFEERATE>0</ECFEERATE>
      <VATCONSIGNMENTNO>0</VATCONSIGNMENTNO>
      <VATTDSRATE>0</VATTDSRATE>
      <BONDAMOUNT/>
      <POSCASHRECEIVED/>
      <CUSTOMDUTYPAID/>
      <VATPARTYITCCLAIMED/>
      <VATPARTYTAXLIABILITY/>
      <VALUEOFWORKSCONTRACT/>
      <ECFEEAMOUNT/>
      <ECFEEDEPOSITBYAWARDER/>
      <ECFEEDEPOSITBYCONTRACTOR/>
      <TDSDEDUCTED/>
      <VALUEOFSUBWORKSCONT/>
      <VATTDSAMT/>
      <ADJPARTYINVOICEVALUE/>
      <GSTINVASSESSABLEVALUE/>
      <VATGOODSVALUE/>
      <EXCHGRATE/>
      <PROCESSINGDURATION/>
      <TEMPGSTEWAYCONSIGNORADDRESSTYPE/>
      <TEMPGSTEWAYCONSIGNEEADDRESSTYPE/>
      <EIDEFAULTLED_ISZRBASICSERVICE/>
      <PREVBOMNAME/>
      <CURBOMNAME/>
      <COMMONNATUREOFPAYMENT/>
      <TEMPISVCHSUPPLFILLED/>
      <CURRPARTYLEDGERNAME/>
      <CURRBASICBUYERNAME/>
      <CURRPARTYNAME/>
      <CURRBUYERADDRESSTYPE/>
      <CURRPARTYADDRESSTYPE/>
      <CURRSTATENAME/>
      <CURRBASICPURCHASEORDERNO/>
      <CURRBASICSHIPDELIVERYNOTE/>
      <POSSTAXPARTYLEDGERNAME/>
      <ISCSTAGTFORMC/>
      <EIDEFAULTLED_ISUSERDEFINED/>
      <EIDEFAULTLED_CLASSNAME/>
      <EIDEFAULTLED_CLASSRATE>0</EIDEFAULTLED_CLASSRATE>
      <EIDEFAULTLED_CLASSADDLRATE>0</EIDEFAULTLED_CLASSADDLRATE>
      <TEMPVATCLASSIFICATION/>
      <ISATTDDATAPRESERVED/>
      <TEMPGSTEWAYBILLNUMBER/>
      <TEMPGSTEWAYBILLDATE/>
      <TEMPGSTEWAYSUBTYPE/>
      <TEMPGSTEWAYDOCUMENTTYPE/>
      <TEMPGSTEWAYSTATUS/>
      <TEMPGSTEWAYCONSIGNOR/>
      <TEMPGSTEWAYCONSIGNORADDRESS/>
      <TEMPGSTEWAYFROMPLACE/>
      <TEMPCONSIGNORPINCODENUMBER>0</TEMPCONSIGNORPINCODENUMBER>
      <TEMPGSTEWAYPINCODENUMBER>0</TEMPGSTEWAYPINCODENUMBER>
      <TEMPGSTEWAYPINCODE/>
      <TEMPGSTEWAYCONSIGNORTIN/>
      <TEMPGSTEWAYCONSIGNORSTATE/>
      <TEMPGSTEWAYCONSSHIPFROMSTATE/>
      <TEMPGSTEWAYCONSIGNEE/>
      <TEMPGSTEWAYCONSADDRESS/>
      <TEMPGSTEWAYCONSFROMPLACE/>
      <TEMPGSTEWAYCONSPINCODENUMBER>0</TEMPGSTEWAYCONSPINCODENUMBER>
      <TEMPCONSIGNEEPINCODENUMBER>0</TEMPCONSIGNEEPINCODENUMBER>
      <TEMPGSTEWAYCONSPINCODE/>
      <TEMPGSTEWAYCONSTIN/>
      <TEMPGSTEWAYCONSSTATE/>
      <TEMPGSTEWAYCONSSHIPTOSTATE/>
      <TEMPGSTEWAYTRANSPORTMODE/>
      <TEMPGSTEWAYDISTANCE>0</TEMPGSTEWAYDISTANCE>
      <TEMPGSTEWAYTRANSPORTERNAME/>
      <TEMPGSTEWAYVEHICLENUMBER/>
      <TEMPGSTEWAYTRANSPORTERID/>
      <TEMPGSTEWAYTRANSPORTERDOCNO/>
      <TEMPGSTEWAYTRANSPORTERDOCDATE/>
      <TEMPGSTEWAYVEHICLETYPE/>
      <TEMPGSTEWAYCONSBILLNUMBER/>
      <TEMPGSTEWAYCONSBILLDATE/>
      <TEMPISUSERDEFINEDCLASS/>
      <TEMPCLASSNATURE/>
      <TEMPGSTOVRDNINELIGIBLEITC/>
      <TEMPGSTOVRDNISREVCHARGEAPPL/>
      <TEMPGSTOVRDNTAXABILITY/>
      <TEMPGSTOVRDNISREVCHARGEAPPLSTR/>
      <TEMPGSTOVRDNINELIGIBLEITCSTR/>
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
       <NARRATION/>
       <ADDLALLOCTYPE/>
       <TAXCLASSIFICATIONNAME/>
       <NOTIFICATIONSLNO/>
       <ROUNDTYPE/>
       <LEDGERNAME>DARUKHANA  STEEL PVT LTD</LEDGERNAME>
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
       <CAPVATTAXRATE>0</CAPVATTAXRATE>
       <AMOUNT>-350637.00</AMOUNT>
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
       <ORIGPURCHINVDATE/>
       <NARRATION/>
       <ADDLALLOCTYPE/>
       <TAXCLASSIFICATIONNAME/>
       <NOTIFICATIONSLNO/>
       <ROUNDTYPE/>
       <LEDGERNAME>LOADING CHARGES (S)</LEDGERNAME>
       <TAXUNITNAME/>
       <STATNATURENAME/>
       <GOODSTYPE/>
       <METHODTYPE>As User Defined Value</METHODTYPE>
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
       <AMOUNT>1400.00</AMOUNT>
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
       <VATEXPAMOUNT>1400.00</VATEXPAMOUNT>
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
       <ORIGPURCHINVDATE/>
       <NARRATION/>
       <ADDLALLOCTYPE/>
       <TAXCLASSIFICATIONNAME/>
       <NOTIFICATIONSLNO/>
       <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
       <LEDGERNAME>SGST OUTPUT @ 9%</LEDGERNAME>
       <TAXUNITNAME/>
       <STATNATURENAME/>
       <GOODSTYPE/>
       <METHODTYPE>GST</METHODTYPE>
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
       <AMOUNT>26743.50</AMOUNT>
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
       <VATEXPAMOUNT>26743.50</VATEXPAMOUNT>
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
       <ORIGPURCHINVDATE/>
       <NARRATION/>
       <ADDLALLOCTYPE/>
       <TAXCLASSIFICATIONNAME/>
       <NOTIFICATIONSLNO/>
       <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
       <LEDGERNAME>CGST OUTPUT @ 9%</LEDGERNAME>
       <TAXUNITNAME/>
       <STATNATURENAME/>
       <GOODSTYPE/>
       <METHODTYPE>GST</METHODTYPE>
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
       <AMOUNT>26743.50</AMOUNT>
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
       <VATEXPAMOUNT>26743.50</VATEXPAMOUNT>
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
      <ALLINVENTORYENTRIES.LIST>
       <BASICUSERDESCRIPTION.LIST TYPE="String">
        <BASICUSERDESCRIPTION>10MMX2000</BASICUSERDESCRIPTION>
        <BASICUSERDESCRIPTION>JSW</BASICUSERDESCRIPTION>
       </BASICUSERDESCRIPTION.LIST>
       <ORIGINVOICEDATE/>
       <ORIGSALESINVDATE/>
       <DESCRIPTION/>
       <STOCKITEMNAME>H.R. COIL/SHEET/PLATE</STOCKITEMNAME>
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
       <RATE>42250.00/M.T.</RATE>
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
       <AMOUNT>295750.00</AMOUNT>
       <EXCISEASSESSABLEVALUE/>
       <VATASSESSABLEVALUE/>
       <ORIGINVGOODSVALUE/>
       <GSTASSBLVALUE/>
       <ORIGINVGOODSTAXVALUE/>
       <VATASSBLVALUE/>
       <VATACCEPTEDTAXAMT/>
       <VATACCEPTEDADDLTAXAMT/>
       <GVATEXCISEAMT/>
       <ACTUALQTY> 7.000 M.T.</ACTUALQTY>
       <BILLEDQTY> 7.000 M.T.</BILLEDQTY>
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
        <GODOWNNAME/>
        <BATCHNAME>Any</BATCHNAME>
        <DESTINATIONGODOWNNAME/>
        <INDENTNO/>
        <ORDERTYPE/>
        <PARENTITEM/>
        <ORDERCLOSUREREASON/>
        <ORDERNO>AJ/SO-0328/19-20</ORDERNO>
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
        <AMOUNT>295750.00</AMOUNT>
        <ADDLEXPENSEAMOUNT/>
        <BATCHDIFFVAL/>
        <ACTUALQTY> 7.000 M.T.</ACTUALQTY>
        <BILLEDQTY> 7.000 M.T.</BILLEDQTY>
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
        <ORDERDUEDATE JD="43578" P="23-Apr-2019">23-Apr-2019</ORDERDUEDATE>
        <INCLUSIVETAXVALUE/>
        <ISPRECLOSED/>
        <BATCHALLOCBOMNAME/>
        <PREVBATCHALLOCBOMNAME/>
        <BOMALLOCACCEPTED/>
        <BATCHALLOCBOMBASEQTY/>
        <COSTTRACKID>0</COSTTRACKID>
        <ISINCLTAXRATEFIELDEDITED/>
        <ADDITIONALDETAILS.LIST>        </ADDITIONALDETAILS.LIST>
        <VOUCHERCOMPONENTLIST.LIST>        </VOUCHERCOMPONENTLIST.LIST>
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
        <LEDGERNAME>SALES @18% GST</LEDGERNAME>
        <TAXUNITNAME/>
        <STATNATURENAME/>
        <GOODSTYPE/>
        <METHODTYPE/>
        <CLASSRATE>100.00000</CLASSRATE>
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
        <GSTOVRDNNATURE>Sales Taxable</GSTOVRDNNATURE>
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
        <AMOUNT>295750.00</AMOUNT>
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
        <RATEDETAILS.LIST>        </RATEDETAILS.LIST>
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
      <PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
      <ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
      <GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>
      <GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>
      <TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>
     </VOUCHER>
    </TALLYMESSAGE>
</REQUESTDATA>
  </IMPORTDATA>
 </BODY>
</ENVELOPE>
';
    echo '<pre>';print_r($xml);
    exit;
    $res = $this->curl_handling($xml);
    print_r($res);

    return $res;
  }


}