<?php 

class Orders extends MY_Controller {
	function __construct() {
		parent::__construct();
		//echo "reached here";exit;
        $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            $_POST = $params;
            /*check_user_login(FALSE);
            print_r($this->session->userdata());exit;*/
             
        }
		check_user_login(TRUE);
		$this->load->model('checkout/checkout_model');
	}

    function index()
	{
	    //echo "reached here";exit;
	    $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        /*if(!empty($params)){
            $_POST = $params;
            check_user_login(FALSE);
            print_r($this->session->userdata());exit;
             
        }*/
		$this->pktdblib->set_table('orders');
		//echo $this->session->userdata('customers')['id'];
		$data['orders'] = $this->pktdblib->custom_query('Select o.*, s.status from orders o inner join order_status s on s.id=o.order_status_id where o.customer_id='.$this->session->userdata('customers')['id']);
		//$data['orders'] = $query->result_array();
		//print_r($data['orders']);exit;
		$this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'orders'=>$data['orders']]);
		
	    $data['content'] = 'orders/index'; 
    	$data['title'] = 'Order History';
		$data['meta_title'] = 'Order History';
		$data['meta_description'] = 'Order History';
		$data['meta_keyword'] = 'Order History';
		echo Modules::run('templates/default_template', $data);
    	
	}
	
	function view($orderId = NULL){
	    $params = json_decode(file_get_contents('php://input'), TRUE);
	    if(!empty($params)){
	        $orderId = $params['order_id'];
	    }
	    if(NULL===$orderId){
	        redirect('home');
	    }
	    
	    $this->pktdblib->set_table('orders');
	    $data['order'] = $this->pktdblib->get_where($orderId);
	    $this->pktdblib->set_table('order_details');
	    //$query = $this->pktdblib->get_where_custom('order_id', $orderId);
	    //$data['orderDetail'] = $query->result_array();
	    $data['orderDetail'] = $this->pktdblib->custom_query('Select od.*, p.product from order_details od inner join products p on p.id=od.product_id where order_id='.$orderId);
	    /*foreach($data['orderDetail'] as $detailKey=>$orderDetail){
	        $variation = json_decode($orderDetail['variation'], true);
	        if(isset($variation['attribute']['product_attribute_id'])){
	            
	        }
	    }*/
	    $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'orders'=>$data['order'], 'orderDetail'=>$data['orderDetail']]);
	}

	function add($cart = []){
		$data['orderData']['project_name'] = 'Order Through Website';
		$params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            $_POST = $params;
            $data['orderData']['project_name'] = 'Order Through App';
            if(isset($_POST['cart']) && !empty($_POST['cart'])){
                $cart = $_POST['cart'];
                $this->cart->insert($_POST['cart']);
                if(NULL!==$_POST['user_id'] && NULL!==$_POST['login_id'] && NULL!==$_POST['user_type']){
                    check_user_login(FALSE);
                    if(isset($_POST['order']) && $_POST['order']!='')
                        $this->session->set_userdata(['order'=>$_POST['order']]);
                    if(isset($_POST['shipping_address_id']) && $_POST['shipping_address_id']!='')
                        $this->session->set_userdata(['shipping_address_id'=>$_POST['shipping_address_id']]);
                    if(isset($_POST['billing_address_id']) && $_POST['billing_address_id']!=''){
                        $this->session->set_userdata(['billing_address_id'=>$_POST['billing_address_id']]);
                    }else{
                        $this->session->set_userdata(['billing_address_id'=>$_POST['shipping_address_id']]); 
                    }
                }
            }
        }
        //print_r($cart);exit;
       // print_r($this->session->userdata('cart_contents'));exit;
		$data['orderData']['amount_before_tax'] = $cart['cart_total'];
		$arr['responsetype'] = 'web';
		$otherCharges = Modules::run('cart/get_other_charges', $arr);
		//print_r($otherCharges);exit;
		foreach ($otherCharges as $key => $charge) {
			//echo $key;
			if($key=='shipping'){
				$data['orderData']['shipping_charge'] = $charge['cost'];
			}
			if($key=='tax'){
				$data['orderData']['amount_after_tax'] = $cart['cart_total']+$charge['cost'];
			}
		}

		if(!isset($data['orderData']['amount_after_tax'])){
			$data['orderData']['amount_after_tax'] = $cart['cart_total'];
		}

        //print_r($_SESSION['customers']['id']);exit;
		//print_r($this->session->userdata('customers.id'));
		$data['orderData']['customer_id'] = $_SESSION['customers']['id'];
		
		$data['orderData']['date'] = date('Y-m-d');
		$data['orderData']['fiscal_yr'] = $this->pktlib->get_fiscal_year();
		$data['orderData']['order_status_id'] = 1;
		$data['orderData']['shipping_address_id'] = $this->session->userdata('shipping_address_id');
		$data['orderData']['billing_address_id'] = $this->session->userdata('billing_address_id');
		$data['orderData']['order_code'] = $this->create_order_code(NULL);
		$data['orderData']['created'] = $data['orderData']['modified'] = date('Y-m-d H:i:s');
		//print_r($data['orderData']);exit;
		$this->pktdblib->set_table('orders');
		$order = $this->pktdblib->_insert($data['orderData']);
		//print_r($order);
		if(NULL!==$order['id']){
			
			
			/*$data['orderData']['id'] = $order['id'];
			$this->pktdblib->set_table('orders');*/
			$upd = true;//$this->pktdblib->_update($order['id'], $data['orderData']);
			if($upd){
				unset($cart['cart_total']);
				unset($cart['total_items']);
				$data['orderDetail'] = [];
				$counter = 0;
				foreach ($cart as $key => $detail) {
					$data['orderDetail'][$counter]['product_id'] = $detail['id'];  
					$data['orderDetail'][$counter]['order_id'] = $order['id'];  
					$data['orderDetail'][$counter]['pack_product_id'] = 0;  
					$data['orderDetail'][$counter]['unit_price'] = $detail['price'];  
					$data['orderDetail'][$counter]['qty'] = $detail['qty'];
					$data['orderDetail'][$counter]['rowid'] = $detail['rowid'];   
					$data['orderDetail'][$counter]['variation'] = json_encode($detail['options']);  
					$data['orderDetail'][$counter]['coupon'] = isset($detail['coupon'])?json_encode($detail['coupon']):NULL;  
					$data['orderDetail'][$counter]['tax'] = 0;  
					$data['orderDetail'][$counter]['created'] = $data['orderDetail'][$counter]['modified'] = date('Y-m-d H:i:s');  

					$counter++;
				}
				$this->pktdblib->set_table('order_details');
				$orderDetail = $this->pktdblib->_insert_multiple($data['orderDetail']);
				if($orderDetail){
				    
					$this->session->set_userdata('order', $order['id']);
					if(!empty($params)){
				        $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'cart'=>$this->session->userdata('cart_contents'), 'order'=>$order['id']]);
				    }
					return TRUE;
				}else
					return FALSE;

			}else{
				$data['cancelOrder']['id'] = $order['id'];
				$data['cancelOrder']['order_status_id'] = 0;
				$data['cancelOrder']['is_active'] = 0;
				$this->pktdblib->set_table('orders');
				$upd = $this->pktdblib->_update($order['id'], $data['cancelOrder']);
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	function create_order_code($orderId = NULL)
	{
		/*if(NULL===$orderId)
			return FALSE;*/


		$companyId = custom_constants::company_id;
        $orderYear = $this->pktlib->get_fiscal_year();
       
        $this->pktdblib->set_table("companies");
        $companyDetails = $this->pktdblib->get_where($companyId);
        $sql = 'Select count(id) as count from orders where fiscal_yr like "'.$orderYear.'" and order_code like "'.$companyDetails['short_code'].'%"';
        //echo $sql;
		$orderCount = $this->pktdblib->custom_query($sql);
		//print_r($orderCount);exit;
		$orderId = $orderCount[0]['count']+1;
        $orderCode = $companyDetails['short_code']."/O/".$orderYear.'/';
        if($orderId>0 && $orderId<=9)
            $orderCode .= '000000';
            
        elseif($orderId>=10 && $orderId<=99)
            $orderCode .= '00000';
        elseif($orderId>=100 && $orderId<=999)
            $orderCode .= '0000';
        elseif($orderId>=1000 && $orderId<=9999)
            $orderCode .= '000';
        elseif($orderId>=10000 && $orderId<=99999)
            $orderCode .= '00';
        elseif($orderId>=100000 && $orderId<=999999)
            $orderCode .= '0';

        $orderCode .= $orderId;


        //echo "reached in create order code method"; print_r($orderCode);exit;
        return $orderCode;
	}

	function edit($cart = []){
		//print_r($cart);exit;
		if(!$cart){
			$data['orderData']['order_status_id'] = 0;
			$data['orderData']['modified'] = date('Y-m-d H:i:s');
			$data['orderData']['id'] = $this->session->userdata('order');
			$data['orderData']['shipping_address_id'] = $this->session->userdata('shipping_address_id');
		    $data['orderData']['billing_address_id'] = $this->session->userdata('billing_address_id');
			$this->pktdblib->set_table('orders');
			$upd = $this->pktdblib->_update($order['id'], $data['orderData']);
			return true;
		}
		$this->pktdblib->set_table('orders');
		$orders = $this->pktdblib->get_where($this->session->userdata('order'));
		/*echo $orders['amount_before_tax']." ".$cart['cart_total'];
		exit;*/
		if((int)$orders['amount_before_tax']==(int)$cart['cart_total']){
			return TRUE;
		}else{
			$this->pktdblib->set_table('order_details');
			$this->pktdblib->_delete_by_column('order_id', $this->session->userdata('order'));
		}
		
		$data['orderData']['amount_before_tax'] = $data['orderData']['amount_after_tax'] = $cart['cart_total'];
		$otherCharges = Modules::run('cart/get_other_charges');
		//print_r($otherCharges);exit;
		foreach ($otherCharges as $key => $charge) {
			//echo $key;
			if($key=='shipping'){
				$data['orderData']['shipping_charge'] = $charge['cost'];
			}
			if($key=='tax'){
				$data['orderData']['amount_after_tax'] = $cart['cart_total']+$charge['cost'];
			}
		}
		//print_r($this->session->userdata('customers.id'));
		$data['orderData']['customer_id'] = $_SESSION['customers']['id'];
		$data['orderData']['project_name'] = 'Order Through Website';
		$data['orderData']['date'] = date('Y-m-d');
		$data['orderData']['fiscal_yr'] = $this->pktlib->get_fiscal_year();
		$data['orderData']['order_status_id'] = 0;
		//$data['orderData']['id'] = $this->session->userdata('order');
		//echo '<pre>';print_r($data['orderData']);exit;
		$this->pktdblib->set_table('orders');
		$order = $this->pktdblib->_update($this->session->userdata('order'), $data['orderData']);
		///print_r($order);exit;
		if($order){
			
			unset($cart['cart_total']);
			unset($cart['total_items']);
			$data['orderDetail'] = [];
			$counter = 0;
			
			//echo '<pre>';
			foreach ($cart as $key => $detail) {
				//print_r($detail);
				$data['orderDetail'][$counter]['product_id'] = $detail['id'];  
				$data['orderDetail'][$counter]['order_id'] = $this->session->userdata('order');  
				$data['orderDetail'][$counter]['pack_product_id'] = 0;  
				$data['orderDetail'][$counter]['unit_price'] = $detail['price'];  
				$data['orderDetail'][$counter]['qty'] = $detail['qty'];
				$data['orderDetail'][$counter]['rowid'] = $detail['rowid'];  
				$data['orderDetail'][$counter]['variation'] = json_encode($detail['options']);  
				$data['orderDetail'][$counter]['coupon'] = json_encode($detail['coupon']);  
				$data['orderDetail'][$counter]['tax'] = 0;  
				$data['orderDetail'][$counter]['created'] = $data['orderDetail'][$counter]['modified'] = date('Y-m-d H:i:s');  
				$counter++;
			}
			$this->pktdblib->set_table('order_details');
			$orderDetail = $this->pktdblib->_insert_multiple($data['orderDetail']);
			//print_r($data['orderDetail']);exit;
			if($orderDetail){
				//$this->session->set_userdata('order', $order['id']);
				return TRUE;
			}else
				return FALSE;			
		}else{
			return FALSE;
		}
		
	}

	function confirmation()	{
	    $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            
            if(isset($params['order']) && !empty($params['order'])){
                $this->session->set_userdata('order', $params['order']);
            }
        }
	    //print_r($this->session->userdata('order'));exit;
		if(NULL===$this->session->userdata('order'))
		{
			$msg = array('message'=>'Invalid Access', 'class'=>'alert alert-danger');
			$this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'failed', 'error'=>$msg['message']]);
			$this->session->set_flashdata('message', $msg);
			redirect('cart');
		}
		$orderArray = ['order_status_id'=>3, 'date'=>date('Y-m-d H:i:s')];
		$this->update_order($this->session->userdata('order'), $orderArray);
		    //$data['order']['id'] = $this->session->userdata('order');
    		$this->pktdblib->set_table('orders');
    		$order = $this->pktdblib->get_where($this->session->userdata('order'));
    
    		$this->pktdblib->set_table('order_details');
    		$query = $this->pktdblib->get_where_custom('order_id', $this->session->userdata('order'));
    		$orderDetails = $query->result_array();
    		//print_r($orderDetails);exit;
    
    		foreach ($orderDetails as $key => $detail) {
    		    //print_r($detail);
    			$this->pktdblib->set_table('product_details');
    			$query = $this->pktdblib->get_where_custom('product_id', $detail['product_id']);
    			$stock = $query->row_array();
    			$this->pktdblib->set_table('products');
                $product = $this->pktdblib->get_where($detail['product_id']);
                //$product = $query2->row_array();
                $attr = json_decode($detail['variation'], true);
                //print_r($attr);exit;
                if(isset($attr['attribute']['product_attribute_id'])){
                    $sql = 'Select a.unit,a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id='.$attr['attribute']['product_attribute_id'];
                    $query3 = $this->pktdblib->custom_query($sql);
                    $productAttribute = $query3[0];
                    //print_r($productAttribute);
                    $unit = 1;
                    //echo $productAttribute['unit']." ".$productAttribute['uom']." - ".$product['base_uom'];
                    if($productAttribute['unit']." ".$productAttribute['uom']!=$product['base_uom']){ 
                        $param1 = $productAttribute['uom'];
                        $param2 = explode(' ',$product['base_uom']);
                        $unit = $this->pktlib->unit_convertion($productAttribute['uom'], (count($param2)>1)?$param2[1]:$param2[0]);
                        $detail['qty'] = $unit*$productAttribute['unit']*$detail['qty'];
                    }
                }
                //print_r($detail);//exit;
    			$updStock['in_stock_qty'] = $stock['in_stock_qty']-$detail['qty'];
    			$updStock['modified'] = date('Y-m-d H:i:s');
    			//print_r($updStock);//exit;
    			$this->pktdblib->set_table('product_details');
    			$this->pktdblib->_update($stock['id'], $updStock);
    		}
		
		
		
		//print_r($order);exit;
		//$mailAdmin = Modules::run('orders/mail_invoice_admin', $order['order_code']);
		$mailAdmin = Modules::run('orders/mail_invoice_admin', $order['id']);
		$mailCustomer = Modules::run('orders/mail_invoice_customer', $order['id']);
		
		$msg = array('message' => 'Order Placed Successfully', 'class'=>'alert alert-success');
		$this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'msg'=>$msg['message']]);
		$this->session->set_flashdata('message', $msg);
		unset($_SESSION['order']);
		redirect('cart/empty_cart');

		//$emailOrder = Modules::run('orders')
	}
	
	function cancellation()	{
	    $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            
            if(isset($params['order']) && !empty($params['order'])){
                $this->session->set_userdata('order', $params['order']);
            }
        }
	    //print_r($this->session->userdata('order'));exit;
		if(NULL===$this->session->userdata('order'))
		{
			$msg = array('message'=>'Invalid Access', 'class'=>'alert alert-danger');
			$this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'failed', 'error'=>$msg['message']]);
			$this->session->set_flashdata('message', $msg);
			redirect('cart');
		}
		$orderArray = ['order_status_id'=>2];
		$this->update_order($this->session->userdata('order'), $orderArray);
		    //$data['order']['id'] = $this->session->userdata('order');
    	$msg = array('message' => 'Order Cancelled Successfully', 'class'=>'alert alert-success');
		$this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'msg'=>$msg['message']]);
		$this->session->set_flashdata('message', $msg);
		unset($_SESSION['order']);
		redirect('cart/empty_cart');

		//$emailOrder = Modules::run('orders')
	}

	function mail_invoice_admin($invoiceId){
		$sql = 'Select c.* from companies c where c.id='.custom_constants::company_id;
		//echo $sql;
		$company = $this->pktdblib->custom_query($sql);
		//echo $this->db->last_query();exit;
		//echo '<pre>';print_r($company);exit;
		$company = $company[0];
		$this->pktdblib->set_table('orders');
		$order = $this->pktdblib->get_where($invoiceId);
		$this->load->library('email');
			
			$config = Array(
			    'protocol' => 'smtp',
			    'smtp_host' => 'mail.primarykey.in',
			    'smtp_port' => 25,
			    'smtp_user' => 'thegrocerystore@primarykey.in',
	            'smtp_pass' => 'grocery@123*#',
			    'charset'   => 'utf-8'
			);
		$this->email->initialize($config);
		//$html = Modules::run('orders/load_invoice_pdf', base64_encode($order['order_code']));
		$html = Modules::run('orders/load_invoice_pdf', $invoiceId);
		//echo $html;exit;
		$email = $company['primary_email'];
		//$email = 'orders.thegrocerystore@gmail.com, primarykeytech@gmail.com';
		
		$this->email->from('thegrocerystore@primarykey.in', 'Grocer Green');
		//$this->email->from($this->config->item('smtp_user'), custom_constants::main_site_display);
		$this->email->to($email);
		//$this->email->to('primarykeytech@gmail.com');
		$this->email->set_mailtype("html");
		$this->email->subject("Online Order Received. Order Number : ".$order['order_code']);
		$this->email->attach($html);
		
		$this->email->message($html);
		$mail = $this->email->send();
		/*print_r($mail);//exit;
		echo $this->email->print_debugger();
		exit;*/
		//print_r($mail);exit;
		return $mail;
		
	}

	function mail_invoice_customer($invoiceId){
		//echo $invoiceId;
		$this->pktdblib->set_table('orders');
		$order = $this->pktdblib->get_where($invoiceId);
		//print_r($order);exit;
		$sql = 'Select c.* from customers c left join user_roles ur on ur.user_id=c.id and ur.account_type="customers" and ur.is_active=true where c.id='.$order['customer_id'];
		//echo $sql;
		$query = $this->pktdblib->custom_query($sql);
		//echo '<pre>';print_r($query);exit;
		$customer = $query[0];
		$this->load->library('email');
			
			$config = Array(
			    'protocol' => 'smtp',
			    'smtp_host' => 'mail.primarykey.in',
			    'smtp_port' => 25,
			    'smtp_user' => 'thegrocerystore@primarykey.in',
	            'smtp_pass' => 'grocery@123*#',
			    'charset'   => 'utf-8'
			);
		$this->email->initialize($config);
		//$html = Modules::run('orders/load_invoice_pdf', base64_encode($order['order_code']));
		$html = Modules::run('orders/load_invoice_pdf', $invoiceId);
		//echo $html;exit;
		
		$email = $customer['primary_email'];
		//echo $email;exit;
		//$email = 'primarykeytech@gmail.com';
		
		$this->email->from('thegrocerystore@primarykey.in', 'Grocer Green');
		$this->email->to($email);
		$this->email->set_mailtype("html");
		$this->email->subject("Online Order Received. Order Number : ".$order['order_code']);
		$this->email->attach($html);
		
		$this->email->message($html);
		$mail = $this->email->send();
		//echo "hello"; print_r($mail);
		//echo $this->email->print_debugger();
		//exit;
		return $mail;
	}

	function load_invoice_pdf($invoiceId){
	   // print_r($invoiceId);exit;
		//$invoiceId = base64_decode($invoiceId);
		$invoiceId = $invoiceId;
		//print_r($invoiceId);exit;
		$this->pktdblib->set_table('orders');
		$data['order'] = $this->pktdblib->get_where($invoiceId);
		//$data['order'] = $query->row_array();
		//echo '<pre>';print_r($data['order']);exit;
		/*
		exit;*/
		$sql = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode, ba.bank_name, ba.account_type, ba.account_number, ba.ifsc_code, ba.branch from companies c inner join user_roles ur on ur.user_id=c.id and ur.account_type="companies" and ur.is_active=true LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id left join bank_accounts ba on ba.user_id=ur.login_id and ba.is_default=true where c.id='.custom_constants::company_id;
		//echo $sql;
		$company = $this->pktdblib->custom_query($sql);
		//print_r($company);exit;
		$data['company'] = $company[0];

		/*$sql2 = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode from customers c left join user_roles ur on ur.user_id=c.id and ur.account_type="customers" and ur.is_active=true LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where c.id='.$data['order']['customer_id'];*/
		$sql2 = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode from customers c left join user_roles ur on ur.user_id=c.id and ur.account_type="customers" and ur.is_active=true LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where a.id='.$data['order']['shipping_address_id'];
				//echo $sql2; exit;
		$customer = $this->pktdblib->custom_query($sql2);
		//print_r($customer);exit;
		$data['customer'] = $customer[0];

		$data['orderDetails'] = $this->pktdblib->custom_query('select products.product, order_details.*, products.base_uom from order_details inner join products on products.id=order_details.product_id where order_details.order_id='.$data['order']['id']);
		$arr['responsetype'] = 'web';
		$data['attribute'] = Modules::run('products/ordered_product_attribute_list', $arr);

		/*echo '<pre>';
		print_r($data);
		exit;*/

		$this->load->view('orders/email/order', $data);
	}
	
	function update_order($orderId, $status){
		//$table = $this->get_table();
        //echo $orderId;//exit;
        //print_r($status);//exit;
        $this->db->where('id', $orderId);
        $update = $this->db->update('orders', $status);
        //print_r($this->db->last_query());exit;
        return $update;
	}
}
	