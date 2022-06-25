<?php 

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billings extends MY_Controller {
	function __construct() {
		parent::__construct();
		check_user_login(FALSE);
		$this->load->model('billings/billing_model');	
	}

	function index($id = NULL) {
		$data['meta_title'] = 'Bills';
		$data['meta_description'] = 'Bills';
		$data['modules'][] = 'billings';
		$data['methods'][] = 'bill_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function bill_listing($data = []) {
		$this->load->model('billings/billing_model');	
		//echo "string"; 
		$this->billing_model->set_table('customer_site_bills');
		$data['bills'] = $this->billing_model->get_bills($data);
		//print_r($data);exit;
		$this->load->view("billings/bill_listing", $data);
	}

	function areaWiseBill() {
		$data['billDetails'] = '';
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			//print_r($_POST);//exit;
			
			$data['value_posted'] = $_POST;

			$bill['area_id'] = $this->input->post('data[customer_site_bills][area_id]');
			$billDates = explode(' - ',$this->input->post('data[customer_site_bills][billing_date]'));
			//print_r($billDates);
			$bill['start_date'] = $this->pktlib->mdYtoYmd($billDates[0]);
			$bill['end_date'] = $this->pktlib->mdYtoYmd($billDates[1]);
			/*echo '<pre>';
			print_r($bill);
			echo '</pre>';*/
			$data['billAnnexure'] = Modules::run('billings/billDetails', $bill);
		}else{
			//$data['value_posted'] = [];
		}
		$data['meta_title'] = 'Customer Billing Type 1';
		$data['meta_description'] = 'Customer Billing Type 1';
		//$data['module'] = 'address';
		//$data['content'] = 'address/address_listing';
		/*$data['modules'][] = 'billings';
		$data['methods'][] = 'generateBill';*/
		$this->load->model('billings/billing_model');	
		$billingAreas = $this->billing_model->getBillingAreas();
		foreach ($billingAreas as $key => $billingArea) {
			$data['option']['billingAreas'][$billingArea['area_id']] = $billingArea['area_name'];
		}
		$data['content'] = 'billings/generateBill1';
		//print_r($data['option']['billingAreas']);
		//$this->load->view("billings/generateBill1", $data);
		echo Modules::run("templates/admin_template", $data);
		

	}

	function generateBill($data = []){
		$this->load->model('billings/billing_model');	
		$billingAreas = $this->billing_model->getBillingAreas();
		foreach ($billingAreas as $key => $billingArea) {
			$data['option']['billingAreas'][$billingArea['area_id']] = $billingArea['area_name'];
		}
		//print_r($data['option']['billingAreas']);
		$this->load->view("billings/view_unprocessed_bill1", $data);
	}

	function billDetails($data = []){
		$this->load->model('billings/billing_model');	
		$this->load->model('products/product_model');	

		//print_r($data);
		$billings = $this->billing_model->custom_billing($data);
		$data['products'] = $this->product_model->get_active_list();
		/*echo '<pre>';
		//print_r($products);
		print_r($billings);exit;*/
		$siteBills = [];
		foreach ($billings as $key => $billing) {
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['customer_name'] = $billing['full_name'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['site_name'] = $billing['site_name'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['site_id'] = $billing['customer_site_id'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service_charge_type'] = $billing['service_charge_type'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service_charge'] = $billing['service_charge'];
			
			$startDate = $data['start_date'];
			$endDate = $data['end_date'];
			if($billing['start_date']>$startDate)
				$startDate = $billing['start_date'];

			if($billing['end_date'] != '0000-00-00' && $billing['end_date']<$endDate)
				$endDate = $billing['end_date'];

			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['start_date'] = $startDate;
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['end_date'] = $endDate;
			$now = strtotime($endDate); // or your date as well
			$your_date = strtotime($startDate);
			$datediff = $now - $your_date;
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['no_of_days'] = (floor($datediff / (60 * 60 * 24)))+1;
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['no_of_labour'] = $billing['no_of_labour'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['night_shift_labour_count'] = $billing['night_shift_labour_count'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['no_of_shift'] = $billing['no_of_shift'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['cost'] = $billing['cost'];
		}
		/*echo '<pre>';
		print_r($siteBills);
		exit;*/
		$data['site_bills'] = $siteBills;
		$this->load->view("billings/view_unprocessed_bill1", $data);

	}

	function process_bill(){
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->load->model('billings/billing_model');	
			$this->load->model('products/product_model');
			/*echo '<pre>';
			print_r($_POST);*/
			$billCondition['area_id'] = explode(',', $this->input->post('data[area_id]'));
			$billCondition['start_date'] = $this->input->post('data[start_date]');
			$billCondition['end_date'] = $this->input->post('data[end_date]');
			$billCondition['area_id'] = explode(',', $this->input->post('data[area_id]'));
			$billCondition['customer_site_id'] = $this->input->post('data[customer_site_id]');
			//print_r($billCondition);
			$billings = $this->billing_model->custom_billing($billCondition);
			$data['products'] = $this->product_model->get_active_list();
			/*echo '<pre>';
			print_r($billings);
			exit;*/
			$this->billing_model->set_table('customer_site_bills');
			$siteBillDetails = [];
			$billId = [];
			$updateBill = [];
			foreach ($billings as $key => $billing) {
				$siteBills = [];

				$siteBills['customer_site_id'] = $billing['customer_site_id'];
				$startDate = $this->input->post('data[start_date]');
				$endDate = $this->input->post('data[end_date]');
				if($billing['start_date']>$startDate)
					$startDate = $billing['start_date'];

				if($billing['end_date'] != '0000-00-00' && $billing['end_date']<$endDate)
					$endDate = $billing['end_date'];

				$siteBills['bill_from_date'] = $this->input->post('data[start_date]');
				$siteBills['bill_to_date'] = $this->input->post('data[end_date]');
				$now = strtotime($endDate); // or your date as well
				$your_date = strtotime($startDate);
				$datediff = $now - $your_date;
				$no_of_days = (floor($datediff / (60 * 60 * 24)))+1;
				//$amount = (($billing['cost']/$no_of_days)*$billing['no_of_shift'])*$no_of_days*($billing['no_of_labour']+$billing['night_shift_labour_count']);
				$amount = (($billing['cost']/$no_of_days))*$no_of_days*($billing['no_of_labour']+$billing['night_shift_labour_count']);

				if($billing['service_charge_type']=='PERCENT'){
					$amount = $amount+(($billing['service_charge']/100)*$amount);
				}else{
					$amount = $amount+$billing['service_charge'];

				}
				$siteBills['area_id'] = $billing['area_id'];
				$siteBills['service_charge_type'] = $billing['service_charge_type'];
				$siteBills['service_charge'] = $billing['service_charge'];
				$siteBills['created'] = date('Y-m-d H:i:s');
				$siteBills['modified'] = date('Y-m-d H:i:s');
				$siteBillDetails[$key]['product_id'] = $billing['product_id'];
				$siteBillDetails[$key]['cost'] = $billing['cost'];
				$siteBillDetails[$key]['no_of_labour'] = $billing['no_of_labour'];
				$siteBillDetails[$key]['night_shift_labour_count'] = $billing['night_shift_labour_count'];
				$siteBillDetails[$key]['no_of_shift'] = $billing['no_of_shift'];
				$siteBillDetails[$key]['no_of_days'] = $no_of_days;
				$siteBillDetails[$key]['bill_from_date'] = $startDate;
				$siteBillDetails[$key]['bill_to_date'] = $endDate;
				$siteBillDetails[$key]['created'] = date('Y-m-d H:i:s');
				$siteBillDetails[$key]['bill_to_date'] = date('Y-m-d H:i:s');
				if(!array_key_exists($billings[$key]['customer_site_id'], $billId)){
					$billings[$key]['customer_site_bill_id'] = $this->billing_model->_insert_site_bill($siteBills);
					$billId[$billing['customer_site_id']] = $billings[$key]['customer_site_bill_id'];
					$updateBill[$billing['customer_site_id']]['amount_before_tax'] = $amount;
					$updateBill[$billing['customer_site_id']]['invoice_no'] = $this->create_invoice_no($billings[$key]['customer_site_bill_id']);
					//$updateBill[$billing['customer_site_id']]['amount_after_tax'] = $amount+($amount*1.8);
					$updateBill[$billing['customer_site_id']]['id'] = $billings[$key]['customer_site_bill_id'];
				}else{
					$billings[$key]['customer_site_bill_id'] = $billId[$billing['customer_site_id']];
					$updateBill[$billing['customer_site_id']]['amount_before_tax'] = $updateBill[$billing['customer_site_id']]['amount_before_tax']+$amount;
					//$updateBill[$billing['customer_site_id']]['amount_after_tax'] = $updateBill[$billing['customer_site_id']]['amount_before_tax']+$amount+($amount*1.8);

				}

				$siteBillDetails[$key]['customer_site_bill_id'] = $billings[$key]['customer_site_bill_id'];

				//print_r($siteBills);
			}
			//print_r($updateBill);
			if(!empty($updateBill)){
				foreach ($updateBill as $key => $bill) {
					//print_r($bill);
					$updateBill[$key]['amount_after_tax'] = $bill['amount_before_tax']+($bill['amount_before_tax']*0.18);
				}

				//print_r($updateBill);exit;
				$updBill = $this->billing_model->update_multiple_bill($updateBill, 'id');
				//print_r($updBill);
			}

			if(!empty($siteBillDetails)){
				$this->billing_model->set_table('customer_site_bill_details');
				$insertDetails = $this->billing_model->_insert_multiple_bill_details($siteBillDetails);
				//print_r($insertDetails);
			}
			/*print_r($updateBill);
			print_r($siteBillDetails);exit;*/
			$msg = array('message'=>'Bill generated Successfully', 'class'=>'alert alert-success');
            $this->session->set_flashdata('message',$msg);
			redirect('billings/areaWiseBill');
		}

		$msg = array('message'=>'Invalid access', 'class'=>'alert alert-success');
        $this->session->set_flashdata('message',$msg);
	}

	function create_invoice_no($billId = 0){
		$fiscalYr = $this->pktlib->get_fiscal_year();
		if($billId==0){
			$maxBillId = $this->pktdblib->custom_query('select max(id) as id from customer_bills where fiscal_year="'.$fiscalYr.'"');
			$billId = $maxBillId[0]['id']+1;
		}
		
		$invoice_no = 'BE/'.$fiscalYr.'/';
		if($billId>0 && $billId<=9)
			$invoice_no .= '000000';
		elseif($billId>=10 && $billId<=99)
			$invoice_no .= '00000';
		elseif($billId>=100 && $billId<=999)
			$invoice_no .= '0000';
		elseif($billId>=1000 && $billId<=9999)
			$invoice_no .= '000';
		elseif($billId>=10000 && $billId<=99999)
			$invoice_no .= '00';
		elseif($billId>=100000 && $billId<=999999)
			$invoice_no .= '0';

		$invoice_no .= $billId;
		return $invoice_no;
	}

	function print_bill($billId){
		//echo $billId;
		$this->load->model('billings/billing_model');
		$this->billing_model->set_table('customer_site_bills');
		//echo "hii";
		$data['bill'] = $this->billing_model->get_where($billId);
		$this->billing_model->set_table('customer_site_bill_details');
		$data['billDetails'] = $this->billing_model->get_bill_details($billId);
		$defaultAddressCondition['type'] = 'customers';
		$defaultAddressCondition['user_id'] = $data['bill']['customer_id'];
		$data['billingAddress'] = Modules::run('address/_get_default_address', $defaultAddressCondition);
		$data['billId'] = $billId;
		//print_r($data['billingAddress']);exit;
		$this->load->view('print_bill', $data);
	}


	function print_multiple_bills($data = []){
		//echo $billId;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//print_r($_POST);//exit;

			foreach ($this->input->post('data[bills]') as $key => $value) {
				$data['print_content'][] = Modules::run('billings/print_bill',$value);
			}

			//print_r($data['print_content']);
			$data['content'] = 'billings/show_print';
		
			$data['meta_title'] = 'Print Multiple Bills';
			$data['meta_description'] = 'Print Bills';
			
			echo Modules::run("templates/admin_template", $data);
		}
	}

	function show_bills(){
		$this->load->view('show_print');
	}

	function clientWiseBill() {
		$data['billDetails'] = '';
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			//print_r($_POST);//exit;
			
			$data['value_posted'] = $_POST;

			$bill['customer_id'] = $this->input->post('data[customer_site_bills][customer_id]');
			$billDates = explode(' - ',$this->input->post('data[customer_site_bills][billing_date]'));
			//print_r($billDates);
			$bill['start_date'] = $this->pktlib->mdYtoYmd($billDates[0]);
			$bill['end_date'] = $this->pktlib->mdYtoYmd($billDates[1]);
			/*echo '<pre>';
			print_r($bill);
			echo '</pre>';*/
			$data['billAnnexure'] = Modules::run('billings/billDetailsClientwise', $bill);
		}else{
			//$data['value_posted'] = [];
		}
		$data['meta_title'] = 'Customer Billing Type 2';
		$data['meta_description'] = 'Customer Billing Type 2';
		//$data['module'] = 'address';
		//$data['content'] = 'address/address_listing';
		/*$data['modules'][] = 'billings';
		$data['methods'][] = 'generateBill';*/
		$this->load->model('billings/billing_model');	
		$billingClients = $this->billing_model->get_site_customers();
		foreach ($billingClients as $key => $billingClient) {
			$data['option']['billingClients'][$billingClient['customer_id']] = $billingClient['client'];
		}
		/*echo '<pre>';
		print_r($data['option']['billingClients']);
		exit;*/

		$data['content'] = 'billings/generateBill2';
		//print_r($data['option']['billingAreas']);
		//$this->load->view("billings/generateBill1", $data);
		echo Modules::run("templates/admin_template", $data);
	}

	function billDetailsClientwise($data = []){
		$this->load->model('billings/billing_model');	
		$this->load->model('products/product_model');	

		//print_r($data);
		$billings = $this->billing_model->custom_billing_clientwise($data);
		$data['products'] = $this->product_model->get_active_list();
		/*echo '<pre>';
		//print_r($products);
		print_r($billings);exit;*/
		$siteBills = [];
		foreach ($billings as $key => $billing) {
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['customer_name'] = $billing['full_name'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['site_name'] = $billing['site_name'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['site_id'] = $billing['customer_site_id'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service_charge_type'] = $billing['service_charge_type'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service_charge'] = $billing['service_charge'];
			
			$startDate = $data['start_date'];
			$endDate = $data['end_date'];
			if($billing['start_date']>$startDate)
				$startDate = $billing['start_date'];

			if($billing['end_date'] != '0000-00-00' && $billing['end_date']<$endDate)
				$endDate = $billing['end_date'];

			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['start_date'] = $startDate;
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['end_date'] = $endDate;
			$now = strtotime($endDate); // or your date as well
			$your_date = strtotime($startDate);
			$datediff = $now - $your_date;
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['no_of_days'] = (floor($datediff / (60 * 60 * 24)))+1;
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['consign_no'] = $billing['consign_no'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['weight'] = $billing['weight'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['mode'] = $billing['mode'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['ref_no'] = $billing['ref_no'];
			$siteBills[$billing['customer_id']][$billing['customer_site_id']]['service'][$billing['product_id']]['cost'] = $billing['cost'];
		}
		/*echo '<pre>';
		print_r($siteBills);
		exit;*/
		$data['site_bills'] = $siteBills;
		$this->load->view("billings/view_unprocessed_bill2", $data);

	}

	function process_courier_bill(){
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->load->model('billings/billing_model');	
			$this->load->model('products/product_model');
			/*echo '<pre>';
			print_r($_POST);exit;*/
			$billCondition['customer_id'] = explode(',', $this->input->post('data[customer_id]'));
			$billCondition['start_date'] = $this->input->post('data[start_date]');
			$billCondition['end_date'] = $this->input->post('data[end_date]');
			$billCondition['customer_id'] = explode(',', $this->input->post('data[customer_id]'));
			$billCondition['customer_site_id'] = $this->input->post('data[customer_site_id]');
			//print_r($billCondition);
			$billings = $this->billing_model->custom_billing_clientwise($billCondition);
			$data['products'] = $this->product_model->get_active_list();
			/*echo '<pre>';
			print_r($billings);
			exit;*/
			$this->billing_model->set_table('customer_site_bills');
			$siteBillDetails = [];
			$billId = [];
			$updateBill = [];
			foreach ($billings as $key => $billing) {
				$siteBills = [];

				$siteBills['customer_site_id'] = $billing['customer_site_id'];
				$startDate = $this->input->post('data[start_date]');
				$endDate = $this->input->post('data[end_date]');
				if($billing['start_date']>$startDate)
					$startDate = $billing['start_date'];

				if($billing['end_date'] != '0000-00-00' && $billing['end_date']<$endDate)
					$endDate = $billing['end_date'];

				$siteBills['bill_from_date'] = $this->input->post('data[start_date]');
				$siteBills['bill_to_date'] = $this->input->post('data[end_date]');
				$now = strtotime($endDate); // or your date as well
				$your_date = strtotime($startDate);
				$datediff = $now - $your_date;
				$no_of_days = (floor($datediff / (60 * 60 * 24)))+1;
				//$amount = (($billing['cost']/$no_of_days)*$billing['no_of_shift'])*$no_of_days*($billing['no_of_labour']+$billing['night_shift_labour_count']);
				$amount = $billing['cost'];//(($billing['cost']/$no_of_days))*$no_of_days*($billing['no_of_labour']+$billing['night_shift_labour_count']);

				if($billing['service_charge_type']=='PERCENT'){
					$amount = $amount+(($billing['service_charge']/100)*$amount);
				}else{
					$amount = $amount+$billing['service_charge'];

				}
				$siteBills['area_id'] = $billing['area_id'];
				$siteBills['service_charge_type'] = $billing['service_charge_type'];
				$siteBills['service_charge'] = $billing['service_charge'];
				$siteBills['created'] = date('Y-m-d H:i:s');
				$siteBills['modified'] = date('Y-m-d H:i:s');
				$siteBillDetails[$key]['product_id'] = $billing['product_id'];
				$siteBillDetails[$key]['cost'] = $billing['cost'];
				$siteBillDetails[$key]['consign_no'] = $billing['consign_no'];
				$siteBillDetails[$key]['weight'] = $billing['weight'];
				$siteBillDetails[$key]['mode'] = $billing['no_of_shift'];
				$siteBillDetails[$key]['ref_no'] = $billing['ref_no'];
				$siteBillDetails[$key]['bill_from_date'] = $startDate;
				$siteBillDetails[$key]['bill_to_date'] = $endDate;
				$siteBillDetails[$key]['created'] = date('Y-m-d H:i:s');
				$siteBillDetails[$key]['bill_to_date'] = date('Y-m-d H:i:s');
				if(!array_key_exists($billings[$key]['customer_site_id'], $billId)){
					$billings[$key]['customer_site_bill_id'] = $this->billing_model->_insert_site_bill($siteBills);
					$billId[$billing['customer_site_id']] = $billings[$key]['customer_site_bill_id'];
					$updateBill[$billing['customer_site_id']]['amount_before_tax'] = $amount;
					$updateBill[$billing['customer_site_id']]['invoice_no'] = $this->create_invoice_no($billings[$key]['customer_site_bill_id']);
					//$updateBill[$billing['customer_site_id']]['amount_after_tax'] = $amount+($amount*1.8);
					$updateBill[$billing['customer_site_id']]['id'] = $billings[$key]['customer_site_bill_id'];
				}else{
					$billings[$key]['customer_site_bill_id'] = $billId[$billing['customer_site_id']];
					$updateBill[$billing['customer_site_id']]['amount_before_tax'] = $updateBill[$billing['customer_site_id']]['amount_before_tax']+$amount;
					//$updateBill[$billing['customer_site_id']]['amount_after_tax'] = $updateBill[$billing['customer_site_id']]['amount_before_tax']+$amount+($amount*1.8);

				}

				$siteBillDetails[$key]['customer_site_bill_id'] = $billings[$key]['customer_site_bill_id'];

				//print_r($siteBills);
			}
			//print_r($updateBill);
			if(!empty($updateBill)){
				foreach ($updateBill as $key => $bill) {
					//print_r($bill);
					$updateBill[$key]['amount_after_tax'] = $bill['amount_before_tax']+($bill['amount_before_tax']*0.18);
				}

				//print_r($updateBill);exit;
				$updBill = $this->billing_model->update_multiple_bill($updateBill, 'id');
				//print_r($updBill);
			}

			if(!empty($siteBillDetails)){
				$this->billing_model->set_table('customer_site_bill_details');
				$insertDetails = $this->billing_model->_insert_multiple_bill_details($siteBillDetails);
				//print_r($insertDetails);
			}
			/*print_r($updateBill);
			print_r($siteBillDetails);exit;*/
			$msg = array('message'=>'Bill generated Successfully', 'class'=>'alert alert-success');
            $this->session->set_flashdata('message',$msg);
			redirect('billings/clientWiseBill');
		}

		$msg = array('message'=>'Invalid access', 'class'=>'alert alert-success');
        $this->session->set_flashdata('message',$msg);
	}

	function cron_courier_billing_end_of_month(){
		$firstdate = '2018-05-01';//date('Y-m-01');
		$lastdate = '2018-05-31';//date('Y-m-t',strtotime('today'));
		$curdate = '2018-05-31';//date('Y-m-d');
		if($lastdate == $curdate){
		    $this->load->library('email');
			
			$config = Array(
			    'protocol' => 'smtp',
			    'smtp_host' => 'mail.primarykey.in',
			    'smtp_port' => 25,
			    'smtp_user' => 'bloomingenterprises@primarykey.in',
	            'smtp_pass' => 'bloom@123*#',
			    'charset'   => 'utf-8'
			);
			$this->email->initialize($config);
			$billingData = [];
			//echo '<pre>';
			$company = Modules::run('companies/get_company_details', ['id'=>1]);
			$userRoles = Modules::run('login/get_typewise_user_role', ['user_id'=>$company['id'], 'account_type'=>'companies']);
			$loginId = $userRoles[0]['login_id'];
			$roleId = $userRoles[0]['role_id'];

			//print_r($query);exit;
			$sql = 'Select distinct cs.customer_id from customer_site_services css inner join customer_sites cs on cs.id=css.customer_site_id where css.start_date between "'.$firstdate.'" AND "'.$lastdate.'" and css.is_active=true';
			$query = $this->pktdblib->custom_query($sql);
			foreach ($query as $key => $customer) {
				$sql2 = 'Select css.*, concat(cs.first_name," ", cs.middle_name," ", cs.surname) as delivery_person, cs.service_charge_type, cs.service_charge, cs.customer_id, cs.id as customer_site, c.fuel_surcharge, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.site_name from customer_site_services css inner join customer_sites cs on css.customer_site_id=cs.id and cs.customer_id = "'.$customer['customer_id'].'" inner join customers c on c.id=cs.customer_id  LEFT JOIN address a on a.id=cs.address_id and a.is_active=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where css.start_date between "'.$firstdate.'" AND "'.$lastdate.'" and css.is_active=true';
				//echo $sql2;
				$customerSiteServices = $this->pktdblib->custom_query($sql2);
				/*print_r($customerSiteServices);exit;*/
				$customerwiseBill = [];

				foreach ($customerSiteServices as $key => $service) {
					//echo $key;
					$customerwiseBill['customer_bills']['customer_id'] = $service['customer_id'];
					$customerwiseBill['customer_bills']['bill_month'] = date('m', strtotime($lastdate));
					$customerwiseBill['customer_bills']['bill_from_date'] = $firstdate;
					$customerwiseBill['customer_bills']['bill_to_date'] = $lastdate;
					$customerwiseBill['customer_bills']['service_charge_type'] = $service['service_charge_type'];
					$customerwiseBill['customer_bills']['service_charge'] = $service['service_charge'];
					$customerwiseBill['customer_bills']['fiscal_year'] = $this->pktlib->get_fiscal_year();
					$customerwiseBill['customer_bills']['invoice_no'] = $this->create_invoice_no();
					$customerwiseBill['customer_bills']['fuel_surcharge'] = $service['fuel_surcharge'];
					$customerwiseBill['customer_bills']['created'] = date('Y-m-d H:i:s');
					$customerwiseBill['customer_bills']['modified'] = date('Y-m-d H:i:s');

					
					$customerwiseBill['customer_bill_details'][$key]['customer_site_id'] = $service['customer_site'];
					$customerwiseBill['customer_bill_details'][$key]['customer_site_id'] = $service['customer_site'];
					$customerwiseBill['customer_bill_details'][$key]['product_id'] = $service['product_id'];
					$customerwiseBill['customer_bill_details'][$key]['delivery_person'] = str_replace('  ', ' ', $service['delivery_person']);
					$customerwiseBill['customer_bill_details'][$key]['date'] = $service['start_date'];
					$customerwiseBill['customer_bill_details'][$key]['consign_no'] = $service['consign_no'];
					$customerwiseBill['customer_bill_details'][$key]['weight'] = $service['weight'];
					$customerwiseBill['customer_bill_details'][$key]['mode'] = $service['mode'];
					$customerwiseBill['customer_bill_details'][$key]['ref_no'] = $service['ref_no'];
					$customerwiseBill['customer_bill_details'][$key]['cost'] = $service['cost'];
					$customerwiseBill['customer_bill_details'][$key]['created'] = date('Y-m-d H:i:s');
					$customerwiseBill['customer_bill_details'][$key]['modified'] = date('Y-m-d H:i:s');
					$customerwiseBill['customer_bill_details'][$key]['site_name'] = $service['site_name'];
					//break;
				}

				$this->pktdblib->set_table('customer_bills');
				$bill = $this->pktdblib->_insert($customerwiseBill['customer_bills']);
				//print_r($bill);
				if($bill['status']=='success'){
					$totalWithTax = 0;
					$totalWithoutTax = 0;
					
					foreach ($customerwiseBill['customer_bill_details'] as $key => $value) {
						$serviceCharge = ($customerwiseBill['customer_bills']['service_charge_type']=='PERCENT')?($value['cost']*($customerwiseBill['customer_bills']['service_charge']/100)):$customerwiseBill['customer_bills']['service_charge'];
						$fuelSurcharge = $value['cost']*($customerwiseBill['customer_bills']['fuel_surcharge']/100);
						$value['customer_bill_id'] = $bill['id'];
						//print_r($value);
						$customerwiseBill['customer_bill_details'][$key] = $value;
						$totalWithoutTax = $totalWithoutTax+$value['cost'];
						$totalWithTax = $totalWithTax+$value['cost']+$serviceCharge+$fuelSurcharge;
					}

					$customerwiseBill['customer_bills']['amount_before_tax'] = $totalWithoutTax;
					$customerwiseBill['customer_bills']['amount_after_tax'] = $totalWithTax;

					//echo "With tax ".$totalWithTax." Without tax ".$totalWithoutTax.'<br><br>';
					$updateBill = ['amount_before_tax'=>$totalWithoutTax, 'amount_after_tax'=>$totalWithTax];
					$update = $this->pktdblib->_update($bill['id'], $updateBill);
					//print_r($customerwiseBill['customer_bill_details']);
					if($update){

						$this->pktdblib->set_table('customer_bill_details');
						$billDetails = $this->pktdblib->_insert_multiple($customerwiseBill['customer_bill_details']);
						$mail = Modules::run('billings/mail_courier_invoice', $customerwiseBill['customer_bills']['invoice_no']);
					}
				}
			}

		}

	}

	function mail_courier_invoice($invoiceId){
		$html = Modules::run('billings/load_courier_bill_pdf', base64_encode($invoiceId));
		//echo $html;exit;
		$email = 'mailme.deepakjha@gmail.com';
		//$email = 'mailme.deepakjha@gmail.com, mr.goyalmanish@gmail.com';
		
		$this->email->from('bloomingenterprises@primarykey.in', 'PKT ERP');
		$this->email->to($email);
		$this->email->set_mailtype("html");
		$this->email->subject("YOUR COURIER BILL HAS BEEN GENERATED FROM ".date('d F, y', strtotime($firstdate)). ' TO '.date('d F, y', strtotime($lastdate)));
		$this->email->attach($html);
		
		$this->email->message($html);
		$mail = $this->email->send();
		return $mail;
		//echo $this->email->print_debugger();
	}

	function load_courier_bill_pdf($invoiceId){
		$invoiceId = base64_decode($invoiceId);
		//print_r($invoiceId);
		$this->pktdblib->set_table('customer_bills');
		$query = $this->pktdblib->get_where_custom('invoice_no', $invoiceId);
		$data['customer_bills'] = $query->row_array();
		//print_r($invoice);
		/*
		exit;*/
		$sql = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode, ba.bank_name, ba.account_type, ba.account_number, ba.ifsc_code, ba.branch from companies c inner join user_roles ur on ur.user_id=c.id and ur.account_type="companies" and ur.is_active=true LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id left join bank_accounts ba on ba.user_id=ur.login_id and ba.is_default=true where c.id=1';
		$company = $this->pktdblib->custom_query($sql);
		$data['company'] = $company[0];

		$sql = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode from customers c inner join user_roles ur on ur.user_id=c.id and ur.account_type="customers" and ur.is_active=true LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where c.id='.$data['customer_bills']['customer_id'];
				//echo $sql;
		$query = $this->pktdblib->custom_query($sql);
		$data['customer'] = $query[0];

		$this->pktdblib->set_table('customer_bill_details');
		$query = $this->pktdblib->get_where_custom('customer_bill_id', $data['customer_bills']['id']);
		$data['customer_bill_details'] = $query->result_array();
		/*echo '<pre>';
		print_r($customerBillDetails);
		exit;*/

		$this->load->view('billings/courier_bill_format1', $data);
	}

	function index_courier($id = NULL) {
		$data['meta_title'] = 'Bills';
		$data['meta_description'] = 'Bills';
		$data['modules'][] = 'billings';
		$data['methods'][] = 'courier_bill_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function courier_bill_listing($data = []) {
		$this->load->model('billings/billing_model');	
		//echo "string"; 
		//$this->billing_model->set_table('customer_bills');
		$data['bills'] = $this->billing_model->get_courier_bills($data);
		/*echo '<pre>';
		print_r($data);exit;*/
		$this->load->view("billings/courier_bill_listing", $data);
	}

	function print_multiple_courier_bills($data = []){
		//echo $billId;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//print_r($_POST);//exit;
			foreach ($this->input->post('data[bills]') as $key => $value) {
				$this->pktdblib->set_table('customer_bills');
				$bill = $this->pktdblib->get_where($value);
				//print_r($bill);
				$data['print_content'][] = Modules::run('billings/load_courier_bill_pdf', base64_encode($bill['invoice_no']));
			}

			//print_r($data['print_content']);
			$data['content'] = 'billings/show_print';
		
			$data['meta_title'] = 'Print Multiple Bills';
			$data['meta_description'] = 'Print Bills';
			
			echo Modules::run("templates/admin_template", $data);
		}
	}
}
	