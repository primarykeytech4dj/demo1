<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Check login and make sure email has been verified
		foreach(custom_constants::$protected_pages as $page)
        {   
            if(strpos($this->uri->uri_string, $page) === 0)
            {   
            }
        }
        check_user_login(TRUE);
        //check_user_login(TRUE);
		//$this->load->model("customer/mdl_admin_customer");
        $this->load->model("orders/order_model");
	}
	
	function _register_new_order($data) {
		if(!isset($data['order_code'])){
		    $data['order_code'] = NULL;
		}
		$this->pktdblib->set_table("orders");
		$id = $this->pktdblib->_insert($data);
		if(!isset($data['order_code']) || empty($data['order_code'])){
			$data['order_code'] = $orderCode = $this->create_order_code($id['id']);
			$updArr['id'] = $id['id'];
			$updArr['order_code'] = $orderCode;
			/*//update sale by
			$updArr['sale_by'] = $this->session->userdata('user_id');*/
			
			$updCode = $this->edit_order($id['id'], $updArr);
		}
		$data = $this->order_details($id['id']);
		return json_encode(['message' =>'Order Created Successfully', "status"=>"success", 'id'=> $id['id'], 'orders'=>$data]);
	}
	
	function order_details($id, $table = 'orders') {
		$this->pktdblib->set_table($table);
		$orderDetails = $this->pktdblib->get_where($id);
		return $orderDetails;
		
	}
	
	function edit_order($id=NULL, $post_data = []){
		//check_user_login(FALSE);
		if(NULL == $id)
			return false;

		$this->pktdblib->set_table('orders');
		if($this->pktdblib->_update($id,$post_data))
			return true;
		else
			return false;
	}

    function admin_index($data=[]){
        if($this->input->is_ajax_request()){  
            
            $postData = $this->input->post();
            $postData['orders'] = [];
            if(in_array(7, $this->session->userdata('roles'))){
               $postData['orders']['sale_by'] = $this->session->userdata('user_id');
            }
            //echo "<pre>"; print_r($postData);
            $data = $this->order_model->orderList($postData);
            //echo "<pre>"; print_r($data);//exit;
            foreach($data['aaData'] as $key=>$v){
                //echo "<pre>"; print_r($v);exit;
                $active = ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';
                $data['aaData'][$key]['is_active'] = "<i class='".$active."'></i>";
                $action = '
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                        <span class="fa fa-caret-down" ></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                          <a class="load-ajax" data-path="orders/admin_order_details/'.$v['id'].'" data-model-size="modal-lg" data-modal-title="Order Detail">View Detail</a> 
                        </li>';
                        if(in_array(6, $this->session->userdata('roles')) || in_array(7, $this->session->userdata('roles'))){
                           // echo 'hii';exit;
                            $action.= '<li>
                          <a class="" href="editorderfieldpos/'.$v['id'].'">Edit Order</a> 
                        </li>';
                        }else{
                            //echo 'hello';exit;
                            $action.= '<li>
                          <a class="" href="editorder/'.$v['id'].'">Edit Order</a> 
                        </li>';
                        }
                        
                        $action.= '<li>';

                        if($v['status']=='By Mistake'){
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=In Process', 'In Process',[]);
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Cancel', 'Cancel',[]);
                         }else if($v['status']=='In Process'){
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Confirmed', 'Confirm',[]);
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Cancel', 'Cancel',[]);
                        }else if($v['status']=='Confirmed'){
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Cancel', 'Cancel',[]);
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Packing', 'Packing',[]);
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Delivered', 'Delivered',[]);
                        }else if($v['status']=='Packing'){
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Cancel', 'Cancel',[]);
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Dispatched', 'Dispatched',[]);
                        }else if($v['status']=='Dispatched'){
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Delivered', 'Delivered',[]);
                          $action.=anchor('orders/admin_edit_status/'.$v['id'].'?status=Cancel', 'Cancel',[]);
                        }
                    $action.='</li>
                        <li>'.anchor('orders/mail_invoice_customer/'.$v['id'], 'Mail Order',['target'=>'_new']).'</li>';
                     
                    $action.='</ul>
                </div>';
                $data['aaData'][$key]['action'] = $action;
                //$action.=$action;exit;
            }
            echo json_encode($data);
            exit;
            
        }
       
        $data['meta_title'] = "ERP";
        
        $data['title'] = "ERP : Orders";
        $data['meta_description'] = "Order panel";
        
        $data['modules'][] = "orders";
        $data['methods'][] = "admin_order_listing";
        
        echo Modules::run("templates/admin_template", $data);
    }

    function admin_order_listing($data=[]){
        $data['formTitle'] = "Orders";
        $this->load->view("orders/admin_index", $data);
    }
    
    
   


    function admin_order_details($orderId){
        $sql = 'select od.unit_price, od.qty, od.uom, od.variation, p.product, o.amount_before_tax, o.shipping_charge, o.amount_after_tax, p.base_uom, concat(a.unit, " ",a.uom) as attribute from order_details od left join orders o on o.id=od.order_id left join products p on p.id=od.product_id left join product_attributes pa on pa.id=od.product_attribute_id left join attributes a on a.id=pa.attribute_id where od.order_id='.$orderId.' and od.is_active=true';
        //echo $sql;
        $data['id'] = $orderId;
        
        $data['orderDetails'] = $this->pktdblib->custom_query($sql);
        $data['attribute'] = Modules::run('products/ordered_product_attribute_list');
        $variation = json_decode($data['orderDetails'][0]['variation']);
        $this->load->view('orders/admin_order_detail_list', $data);

    }

    function admin_edit_status($id){
        //echo $id;//exit;
        if(isset($_GET['status']) && !empty($_GET['status'])){$this->pktdblib->set_table('order_status');
            $statusId = $this->pktdblib->get_where_custom('status', $_GET['status']);
            $orderStatusId = $statusId->result_array();
            $this->pktdblib->set_table('orders');
            $postData['order_status_id'] = $orderStatusId[0]['id'];
            $postData['modified'] = date('Y-m-d H:i:s');
            if($this->pktdblib->_update($id, $postData)){
                $insert['order_id'] = $id;
                $insert['user_id'] = $this->session->userdata('user_id');
                $insert['order_status_id'] =  $postData['order_status_id'];
                $insert['is_active'] = true;
                $insert['created'] = $insert['modified'] = date('Y-m-d H:i:s');
                //echo '<pre>';print_r($insert);exit;
                $this->pktdblib->set_table('order_logs');
                $this->pktdblib->_insert($insert);
                $msg = array('message'=> 'Status Updated Successfully', 'class' => 'alert alert-success');
                $this->session->set_flashdata('message', $msg);
                redirect('orders/admin_index');
            }
        }
    }
    
    function mail_invoice_admin($invoiceId){
        $comp = 'Select c.* from companies c where c.id='.custom_constants::company_id;
        //echo $sql;
        $company = $this->pktdblib->custom_query($comp);
        $company = $company[0];
        /*$this->pktdblib->set_table('orders');
        $data['order'] = $this->pktdblib->get_where($invoiceId);*/
        
        $sql = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode, ba.bank_name, ba.account_type, ba.account_number, ba.ifsc_code, ba.branch from companies c inner join user_roles ur on ur.user_id=c.id and ur.account_type="companies" and ur.is_active=true LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id left join bank_accounts ba on ba.user_id=ur.login_id and ba.is_default=true where c.id='.custom_constants::company_id;
        //echo $sql;
        $company = $this->pktdblib->custom_query($sql);
        //print_r($company);exit;
        $data['company'] = $company[0];

        /*$sql2 = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode from customers c left join user_roles ur on ur.user_id=c.id and ur.account_type="customers" and ur.is_active=true LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where c.id='.$data['order']['customer_id'];
                //echo $sql2; exit;
        $customer = $this->pktdblib->custom_query($sql2);
        //print_r($query);exit;
        $data['customer'] = $customer[0];

        $data['orderDetails'] = $this->pktdblib->custom_query('select products.product, order_details.* from order_details inner join products on products.id=order_details.product_id where order_details.order_id='.$data['order']['id']);
        $data['attribute'] = Modules::run('products/product_attribute_list');
        //echo '<pre>';print_r($data);exit;
        $this->load->view('orders/email/order', $data);*/
        $html = Modules::run('orders/load_invoice_pdf', $invoiceId);
        echo $html;
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
		
			
		$config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => 'mail.expedeglobal.com',
		    'smtp_port' => 587,
		    'smtp_user' => 'emarkit@expedeglobal.com',
            'smtp_pass' => 'Mum@400064',
            'charset'   => 'utf-8'
		);
		$this->load->library('email', $config);
		$this->email->initialize($config);
		//$html = Modules::run('orders/load_invoice_pdf', base64_encode($order['order_code']));
		$html = Modules::run('orders/load_invoice_pdf', $invoiceId);
		
		//echo $html;exit;
		$email = $customer['primary_email'];
		//echo $email;exit;
		//$email = 'primarykeytech@gmail.com';
		
		$this->email->from('emarkit@expedeglobal.com', 'Emarkit');
		//$this->email->to($email);
		$this->email->to('primarykeytech@gmail.com');
		//$this->email->cc('mailme.deepakjha@gmail.com');
		$this->email->set_mailtype("html");
		$this->email->subject("Online Order Received. Order Number : ".$order['order_code']);
		$this->email->attach($html);
		
		$this->email->message($html);
		$mail = $this->email->send();
		//echo "hello"; print_r($mail);
		if(TRUE!==$mail){
		    echo $this->email->print_debugger();
		}else{
		    echo "Mail Sent";
		}
		echo $html;
		exit;
		//return $mail;
	}
	
	function load_invoice_pdf($invoiceId){
	    //print_r($invoiceId);exit;
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
		$sql2 = 'Select c.* from customers c  where c.id='.$data['order']['customer_id'];
		//echo $sql2; //exit;
		$customer = $this->pktdblib->custom_query($sql2);
		//print_r($customer);exit;
		$data['customer'] = $customer[0];
		$sql3 = 'Select a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode from address a inner join countries cn on cn.id=a.country_id inner join states s on s.id=a.state_id inner join cities ct on ct.id=a.city_id inner join areas ar on ar.id=a.area_id where a.id='.$data['order']['shipping_address_id'];
        $address = $this->pktdblib->custom_query($sql3);
        /*echo '<pre>';
        print_r($address);
        exit;*/
        $data['customer'] = array_merge($data['customer'],$address[0]);

		$data['orderDetails'] = $this->pktdblib->custom_query('select products.product, order_details.*, products.base_uom from order_details inner join products on products.id=order_details.product_id where order_details.order_id='.$data['order']['id']);
		$arr['responsetype'] = 'web';
		$data['attribute'] = Modules::run('products/ordered_product_attribute_list', $arr);

		/*echo '<pre>';
		print_r($data);
		exit;*/

		$this->load->view('orders/email/order', $data);
	}

    function product_wise_orders(){
        $orderSql = 'Select o.order_code, o.id, o.date, concat(c.first_name," ",c.middle_name," ", c.surname) as customer_name from orders o inner join order_status os on os.id=o.order_status_id left join customers c on c.id=o.customer_id where o.order_status_id not in (2,8)';
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            /*echo '<pre>';
            print_r($this->input->post());exit;
            echo '</pre>';*/
            $data['values_posted'] = $this->input->post();
            $fromDate = $this->pktlib->dmYtoYmd($this->input->post('from_date'));
            $toDate = $this->pktlib->dmYtoYmd($this->input->post('to_date'));
            if($fromDate==$toDate){
                $orderSql.=' And o.date like "'.$fromDate.'%"';
            }else{
                $orderSql.=' And o.date between "'.$fromDate." ".$this->input->post('from_time').'" AND "'.$toDate." ".$this->input->post('to_time').'"';
            }
        }else{
            $maxDate = $this->pktdblib->custom_query('Select max(date) as date from orders where order_status_id not in (2,8)');
            //print_r($maxDate);
            $fromDate = date('Y-m-d', strtotime($maxDate[0]['date']));
            $orderSql.=' And o.date like "'.$fromDate.'%"';
        }

        $orderSql.=' order by o.id DESC';
        //echo $orderSql;//exit;
        $orders = $this->pktdblib->custom_query($orderSql);
        $orderDetails = [];
        //echo '<pre>';
        foreach ($orders as $okey => $order) {
            $ods = $this->pktdblib->custom_query('Select od.*, p.product, p.base_uom, p.base_price from order_details od inner join products p on p.id=od.product_id where od.is_active=true and od.order_id='.$order['id']);
            foreach ($ods as $odkey => $od) {
                //print_r($od);
                $variant = json_decode($od['variation'], true);
                $productAttribute = $this->pktdblib->custom_query('Select pa.*, a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$variant['attribute']['product_attribute_id'].'"');
                $od['base_uom'] = trim($od['base_uom']);
                if($od['base_uom']==0 || empty($productAttribute)){
                    $orderDetails[$order['order_code']][$od['product_id']]['qty'] = $od['qty'];
                    //$orderDetails[$order['order_code']][$od['product_id']]['default_uom'] = $od['base_uom'];
                }else{
                    //echo $od['base_uom'].' ';
                    $baseuom = explode(" ", $od['base_uom']);
                   /* echo ($productAttribute[0]['unit']." ".$productAttribute[0]['uom']);
                    print_r($od['base_uom']);
                    echo '<br>';*/
                    //print_r($baseuom);
                    $conversion = $this->pktlib->unit_convertion(strtoupper($productAttribute[0]['uom']), strtoupper($baseuom[1]));
                    $afterConversion = $productAttribute[0]['unit']*$conversion;
                    //echo $afterConversion." <br>";
                    //echo $conversion.'<br>';
                    $orderDetails[$order['order_code']][$od['product_id']]['qty'] = $od['qty']*$afterConversion;
                    $orderDetails[$order['order_code']][$od['product_id']]['qty_count'] = $od['qty'];
                    $orderDetails[$order['order_code']][$od['product_id']]['product'] = $od['product'];
                    $orderDetails[$order['order_code']][$od['product_id']]['uom'] = $productAttribute[0]['unit']." ".$productAttribute[0]['uom'];
                    $orderDetails[$order['order_code']][$od['product_id']]['price'] = $od['unit_price'];
                }
                //$orderDetails[$order['order_code']][$od['product_id']]['variation'] = $od['variation'];
            }
        }
        //exit;
        $products = $this->pktdblib->custom_query('Select p.id, p.product, p.base_uom, pc.category_name from products p inner join product_categories pc on pc.id=p.product_category_id order by p.product_category_id ASC, p.show_on_website DESC');
        /*echo '<pre>';
        //print_r($orders);
        print_r($products);
        exit;*/
        $data['orderDetails'] = $orderDetails;
        $data['orders'] = $orders;
        $data['products'] = $products;
        //$data['attribute'] = Modules::run('products/product_attribute_list');
        //$variation = json_decode($data['orderDetails'][0]['variation']);
        $data['meta_description'] = 'Product Wise Orders';
        $data['meta_title'] = 'Product Wise Orders';
        $data['meta_keyword'] = 'Product Wise Orders';
        
        $data['content'] = 'orders/admin_product_wise_order_report';
        echo Modules::run('templates/admin_template', $data);
    }

    function orderwisereport(){
        $orderSql = 'Select o.order_code, o.id, o.date, concat(c.first_name," ",c.middle_name," ", c.surname) as customer_name from orders o inner join order_status os on os.id=o.order_status_id left join customers c on c.id=o.customer_id where o.order_status_id not in (1,2,8)';
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            /*echo '<pre>';
            print_r($this->input->post());exit;
            echo '</pre>';*/
            $data['values_posted'] = $this->input->post();
            $fromDate = $this->pktlib->dmYtoYmd($this->input->post('from_date'));
            $toDate = $this->pktlib->dmYtoYmd($this->input->post('to_date'));
            if($fromDate==$toDate){
                $orderSql.=' And o.date like "'.$fromDate.'%"';
            }else{
                $orderSql.=' And o.date between "'.$fromDate." ".$this->input->post('from_time').'" AND "'.$toDate." ".$this->input->post('to_time').'"';
            }
        }else{
            $maxDate = $this->pktdblib->custom_query('Select max(date) as date from orders where order_status_id not in (2,8)');
            //print_r($maxDate);
            $fromDate = date('Y-m-d', strtotime($maxDate[0]['date']));
            $orderSql.=' And o.date like "'.$fromDate.'%"';
        }

        $orderSql.=' order by o.id DESC';
        //echo $orderSql;//exit;
        $orders = $this->pktdblib->custom_query($orderSql);
        /*echo '<pre>';
        print_r($orders);
        echo '</pre>';*/
        $orderDetails = [];
        //echo '<pre>';
        foreach ($orders as $okey => $order) {
            //echo 'Select od.*, p.product, p.base_uom, p.base_price from order_details od inner join products p on p.id=od.product_id where od.is_active=true and od.order_id='.$order['id'].'<br>';
            $ods = $this->pktdblib->custom_query('Select od.*, p.product, p.base_uom, p.base_price from order_details od inner join products p on p.id=od.product_id where od.is_active=true and od.order_id='.$order['id']);
            //print_r($ods);
            foreach ($ods as $odkey => $od) {
                //print_r($od);
                $variant = json_decode($od['variation'], true);
                $productAttribute = $this->pktdblib->custom_query('Select pa.*, a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$variant['attribute']['product_attribute_id'].'"');
                $od['base_uom'] = trim($od['base_uom']);
                if($od['base_uom']==0 || empty($productAttribute)){
                    $orderDetails[$order['order_code']][$od['product_id']]['qty'] = $od['qty'];
                    //$orderDetails[$order['order_code']][$od['product_id']]['default_uom'] = $od['base_uom'];
                }else{
                    //echo $od['base_uom'].' ';
                    $baseuom = explode(" ", $od['base_uom']);
                   /* echo ($productAttribute[0]['unit']." ".$productAttribute[0]['uom']);
                    print_r($od['base_uom']);
                    echo '<br>';*/
                    //print_r($baseuom);
                    $conversion = $this->pktlib->unit_convertion(strtoupper($productAttribute[0]['uom']), strtoupper($baseuom[1]));
                    $afterConversion = $productAttribute[0]['unit']*$conversion;
                    //echo $afterConversion." <br>";
                    //echo $conversion.'<br>';
                    $orderDetails[$order['order_code']][$od['product_id']]['qty'] = $od['qty']*$afterConversion;
                    $orderDetails[$order['order_code']][$od['product_id']]['product'] = $od['product'];
                    $orderDetails[$order['order_code']][$od['product_id']]['uom'] = $productAttribute[0]['unit']." ".$productAttribute[0]['uom'];
                }
                //$orderDetails[$order['order_code']][$od['product_id']]['variation'] = $od['variation'];
            }
        }
        //exit;
        $products = $this->pktdblib->custom_query('Select p.id, p.product, p.base_uom, pc.category_name from products p inner join product_categories pc on pc.id=p.product_category_id order by p.product_category_id ASC, p.show_on_website DESC');
        //echo '<pre>';
        /*print_r($orders);
        print_r($orderDetails);
        exit;*/
        $data['orderDetails'] = $orderDetails;
        $data['orders'] = $orders;
        $data['products'] = $products;
        //$data['attribute'] = Modules::run('products/product_attribute_list');
        //$variation = json_decode($data['orderDetails'][0]['variation']);
        $data['meta_description'] = 'Product Wise Orders';
        $data['meta_title'] = 'Product Wise Orders';
        $data['meta_keyword'] = 'Product Wise Orders';
        
        $data['content'] = 'orders/product_wise_order_count';
        echo Modules::run('templates/admin_template', $data);
    }
    
    function getOrderData($orderId){
        $sql = 'Select o.*, concat(c.first_name," ", c.middle_name, " ", c.surname) as customer_name, concat(i.address_1, ", ", i.address_2, ", ", ct.city_name, "-", i.pincode, ", ",s.state_name) as delivery_address, i.remark as delivery_address_remark, os.status from orders o left join order_status os on os.id=o.order_status_id inner join customers c on c.id=o.customer_id inner join address i on i.id=o.shipping_address_id inner join cities ct on ct.id=i.city_id inner join states s on s.id=i.state_id where o.id='.$orderId;
        //echo $sql;//exit;
        $query = $this->pktdblib->custom_query($sql);
        $order = $query[0];
        $orderDetails = Modules::run('orders/getOrderDetailData', $order['id'], $order['order_code']);
        $result = compact("order", "orderDetails");
        return $result;
    }
    
    function getOrderDetailData($orderId = NULL, $orderCode = NULL){
        
        if(NULL===$orderCode || NULL===$orderCode){
            return false;
        }
        $order['order_code'] = $orderCode;
        $orderDetails = [];
        $sql = 'Select od.*, p.product, p.base_uom, p.base_price, pi.image_name_1, b.brand from order_details od inner join products p on p.id=od.product_id left join brand_products bp on bp.product_id=p.id left join manufacturing_brands b on b.id=bp.brand_id inner join product_images pi on pi.product_id=p.id and pi.featured_image=true where od.is_active=true and od.order_id='.$orderId;
        //echo $sql;
        $ods = $this->pktdblib->custom_query($sql);
        foreach ($ods as $odkey => $od) {
            //print_r($od);
            $variant = json_decode($od['variation'], true);
            $productAttribute = $this->pktdblib->custom_query('Select pa.*, a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$variant['attribute']['product_attribute_id'].'"');
            $od['base_uom'] = trim($od['base_uom']);
            if($od['base_uom']==0 || empty($productAttribute)){
                $ods[$odkey]['qty'] = $od['qty'];
                //$orderDetails[$order['order_code']][$od['product_id']]['default_uom'] = $od['base_uom'];
            }else{
                //echo $od['base_uom'].' ';
                $baseuom = explode(" ", $od['base_uom']);
               /* echo ($productAttribute[0]['unit']." ".$productAttribute[0]['uom']);
                print_r($od['base_uom']);
                echo '<br>';*/
                //print_r($baseuom);
                $conversion = $this->pktlib->unit_convertion(strtoupper($productAttribute[0]['uom']), strtoupper($baseuom[1]));
                $afterConversion = $productAttribute[0]['unit']*$conversion;
                //echo $afterConversion." <br>";
                //echo $conversion.'<br>';
                $ods[$odkey]['qty'] = $od['qty']*$afterConversion;
                $ods[$odkey]['qty_count'] = $od['qty'];
                //$ods[$odkey]['product'] = $od['product'];
                $ods[$odkey]['uom'] = $productAttribute[0]['unit']." ".$productAttribute[0]['uom'];
                //$ods[$odkey]['price'] = $od['unit_price'];
            }
            //$orderDetails[$odkey]['variation'] = $od['variation'];
        }
        
        return $ods;
    }
    
    
    function admin_add() {
		//check_user_login(FALSE);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			/*echo '<pre>';
			print_r($this->input->post());
			exit;*/
			$data['values_posted'] = $this->input->post();
			//echo '<pre>';print_r($_POST);exit;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[orders][customer_id]', 'Customer', 'required');
			$this->form_validation->set_rules('data[orders][shipping_address_id]', 'Address', 'required');
			$this->form_validation->set_rules('data[orders][sale_by]', 'Sale Person', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$post_data = $this->input->post('data[orders]');//$_POST['data']['stocks'];
				$post_data['date'] = date('Y-m-d H:i:s');
				$post_data['created'] = date('Y-m-d H:i:s');
				$post_data['billing_address_id'] = $post_data['shipping_address_id'];
				$post_data['created_by'] = $this->session->userdata('user_id');
				$post_data['project_name'] = 'POS';
				$post_data['order_status_id'] = 3;
				$post_data['fiscal_yr'] = $this->pktlib->get_fiscal_year();
				$reg_order = json_decode($this->_register_new_order($post_data), true);
				
				if($reg_order['status'] === "success")
				{
					$_POST['data']['orders'] = $reg_order['orders'];
					$_POST['data']['orders']['id'] = $reg_order['id'];
					
					if(NULL !== $this->input->post('order_details')){
						$stockDetails = $this->admin_add_multiple_order_details($this->input->post('order_details'), $reg_order['id']);
						$_POST['data']['order_details'] = $this->input->post('order_details');
					}
					
                    if($this->input->is_ajax_request()){  
                        $msg = array('msg'=>'Order Added Successfully', 'class'=>'alert alert-success');
                        echo json_encode($msg);
                        exit;
                    }
					$msg = array('message'=>'Order Added Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
					redirect(custom_constants::new_order_url);
				}
				else
				{
					// Registration error
					$data['form_error'] = $reg_enq['msg'];
				}
				
			}else{
				
				if($this->input->is_ajax_request()){  
                    $msg = array('msg'=>'Error Occured'.validation_errors(), 'class'=>'alert alert-danger'); 
                    echo json_encode($msg);
                    exit;
                }
                $msg = array('message'=>'Error Occured'.validation_errors(), 'class'=>'alert alert-danger');
	            $this->session->set_flashdata('message',$msg);
			}
		}
		
		$data['meta_title'] = "POS";
		$data['meta_description'] = "POS";
		$data['title'] = "Module :: Orders";
		$data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> New Order';
		//echo "reached here"; exit;
		$data['modules'][] = "orders";
		$data['methods'][] = "admin_order_form";
		$data['js'][] = '<script type="text/javascript">
            $(".product").on("change", function(){
            	var id = this.id;
            	alert($(this).val());
            	var productId = $(this).val();
            	$.ajax({
		          type: "POST",
		          dataType: "html",
		          url : "products/get_product_details/"+productId,
		          data: "product_id="+productId,
		          success: function(response) {
		            console.log(response);
		            //$("#"+datatarget).select2("destroy").empty().select2({data : response});
		          }
		        
		        });
				
            })

            $("#customer_id").on("change", function(){
            	var val = $(this).val();
            	var type = "customers";
            	console.log(val);
            	console.log(type);
            	$.ajax({
					type: "POST",
					dataType: "json",
					data:{"type":type, "id":val},
					url : base_url+"address/type_wise_user2/",
					success: function(response) {
						console.log(response);
						$("#invoice_address_id").select2("destroy").empty().select2({data : response});
						$("#delivery_address_id").select2("destroy").empty().select2({data : response});

					}
		        
		        });
            });
            
            $("#customer_id").on("change", function(){
            	var val = $(this).val();
            	var type = "customers";
            	console.log(val);
            	console.log(type);
            	$.ajax({
					type: "POST",
					dataType: "json",
					data:{"type":type, "id":val},
					url : base_url+"address/type_wise_user2/",
					success: function(response) {
						console.log(response);
						$("#invoice_address_id").select2("destroy").empty().select2({data : response});
						$("#delivery_address_id").select2("destroy").empty().select2({data : response});

					}
		        
		        });
            });
            
            $(document).on("change", ".attribute", function(){
        		var attribute = $(this).val();
        		//alert(attribute);
        		var trId = $(this).closest("tr").attr("id");
        		var id = this.id;
        		$.ajax({
                  type: "POST",
                  dataType: "json",
                  url : base_url+"products/idWiseProductAttribute/"+attribute,
                  success: function(response) {
                    if(response.status == "success"){
                        $("#unit_price_"+trId).val(response.data.price);
                    } 
                  }
                });
        	});

            $("form").on("submit", function(e){
            	e.preventDefault();
            	if($("#customer_id").val()==0){
            		alert("Please select Customer");
            		$("#customer_id").focus();
            		return false;
            	}

            	if($("#delivery_address_id").val()==0){
            		alert("Please select Addres");
            		$("#delivery_address_id").focus();
            		return false;
            	}
            	var error = false;
            	$(".select2").each(function(){
            		
            		if($(this).hasClass("required")){
            			var id = this.id;
            			
            			if($("#"+id).val()=="" || $("#"+id).val()=="0"){

	            			alert("Selected Field is missing");
	            			this.focus();
	            			error = true;
	            			return false;
            			}
            			//return false;
            		}
            	})

            	if(error == false){
            		//this.submit();
            	}
            	
            	
            })
		</script>';
		echo Modules::run("templates/admin_template", $data);
		
	}
	
	function admin_edit($orderId) {
	    
	    if($_SERVER['REQUEST_METHOD'] == 'POST'){
			/*echo '<pre>';
			print_r($this->input->post());
			exit;*/
			$data['values_posted'] = $this->input->post();
			//echo '<pre>';print_r($_POST);exit;
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[orders][customer_id]', 'Customer', 'required');
			$this->form_validation->set_rules('data[orders][shipping_address_id]', 'Address', 'required');
			//$this->form_validation->set_rules('data[orders][sale_by]', 'Sale Person', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
				$post_data = $this->input->post('data[orders]');//$_POST['data']['stocks'];
				$post_data['modified'] = date('Y-m-d H:i:s');
				$post_data['modified_by'] = $this->session->userdata('user_id');
				$reg_order = json_decode($this->edit_order($orderId, $post_data), true);
				
				if($reg_order)
				{
					
					if(NULL !== $this->input->post('order_details')){
						$stockDetails = $this->admin_add_multiple_order_details($this->input->post('order_details'), $orderId);
						//echo '<pre>';print_r($stockDetails);exit;
						$_POST['data']['order_details'] = $this->input->post('order_details');
					}

					
					$msg = array('message'=>'Order Update Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
					redirect(custom_constants::admin_order_listing_url);
				}
				else
				{
					// Registration error
					$msg = array('message'=>'Error Occured while updating', 'class'=>'alert alert-danger');
	                $this->session->set_flashdata('message',$msg);
				}
				
			}else{
				$msg = array('message'=>'Error Occured'.validation_errors(), 'class'=>'alert alert-danger');
	                $this->session->set_flashdata('message',$msg);
			}
			redirect(custom_constants::edit_order_url.'/'.$orderId);
		}else{
		    $this->pktdblib->set_table("orders");
    		$data['values_posted']['order'] = $this->pktdblib->get_where($orderId);
    		
    		$this->pktdblib->set_table('order_details');
    		$orderDetails = $this->pktdblib->get_where_custom('order_id', $orderId);
    		$data['values_posted']['orderDetails'] = $orderDetails->result_array();
		}
		
		
		//echo $address;exit;
		/*echo '<pre>';
		print_r($data['values_posted']);
		exit;*/
		$data['meta_title'] = "POS";
		$data['meta_description'] = "POS";
		$data['title'] = "Module :: Orders";
		$data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> Edit Order';
		//echo "reached here"; exit;
		$data['modules'][] = "orders";
		$data['methods'][] = "admin_order_form_edit";
		$data['js'][] = '<script type="text/javascript">
            $(".product").on("change", function(){
            	var id = this.id;
            	alert($(this).val());
            	var productId = $(this).val();
            	$.ajax({
		          type: "POST",
		          dataType: "html",
		          url : "products/get_product_details/"+productId,
		          data: "product_id="+productId,
		          success: function(response) {
		            console.log(response);
		            //$("#"+datatarget).select2("destroy").empty().select2({data : response});
		          }
		        
		        });
				
            })

            $("#customer_id").on("change", function(){
            	var val = $(this).val();
            	var type = "customers";
            	console.log(val);
            	console.log(type);
            	$.ajax({
					type: "POST",
					dataType: "json",
					data:{"type":type, "id":val},
					url : base_url+"address/type_wise_user2/",
					success: function(response) {
						console.log(response);
						$("#invoice_address_id").select2("destroy").empty().select2({data : response});
						$("#delivery_address_id").select2("destroy").empty().select2({data : response});

					}
		        
		        });
            });
            
            $("#customer_id").on("change", function(){
            	var val = $(this).val();
            	var type = "customers";
            	console.log(val);
            	console.log(type);
            	$.ajax({
					type: "POST",
					dataType: "json",
					data:{"type":type, "id":val},
					url : base_url+"address/type_wise_user2/",
					success: function(response) {
						console.log(response);
						$("#invoice_address_id").select2("destroy").empty().select2({data : response});
						$("#delivery_address_id").select2("destroy").empty().select2({data : response});

					}
		        
		        });
            });
            
            $(document).on("change", ".attribute", function(){
        		var attribute = $(this).val();
        		var trId = $(this).closest("tr").attr("id");
        		var id = this.id;
        		$.ajax({
                  type: "POST",
                  dataType: "json",
                  url : base_url+"products/idWiseProductAttribute/"+attribute,
                  success: function(response) {
                    if(response.status == "success"){
                        $("#unit_price_"+trId).val(response.data.price);
                    } 
                  }
                });
        	});

            $("form").on("submit", function(e){
            	e.preventDefault();
            	if($("#customer_id").val()==0){
            		alert("Please select Customer");
            		$("#customer_id").focus();
            		return false;
            	}

            	if($("#delivery_address_id").val()==0){
            		alert("Please select Addres");
            		$("#delivery_address_id").focus();
            		return false;
            	}
            	var error = false;
            	$(".select2").each(function(){
            		
            		if($(this).hasClass("required")){
            			var id = this.id;
            			
            			if($("#"+id).val()=="" || $("#"+id).val()=="0"){

	            			alert("Selected Field is missing");
	            			this.focus();
	            			error = true;
	            			return false;
            			}
            			//return false;
            		}
            	})

            	if(error == false){
            		this.submit();
            	}
            	
            	
            })
		</script>';
		echo Modules::run("templates/admin_template", $data);
		
	}
	
	function admin_order_form_edit($data = []) {
        $this->pktdblib->set_table('products');
		$data['products'] = $this->pktdblib->custom_query('Select * from products where is_active=true and show_on_website=true');
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}
		
		//$this->pktdblib->set_table('order_status');
		$orderStatus = $this->pktdblib->custom_query('Select * from order_status where is_active=true');
		$data['option']['status'][0] = 'Select Status';
		foreach ($orderStatus as $statusKey => $status) {
			$data['option']['status'][$status['id']] = $status['status'];
		}
		//echo "<pre>"; print_r($data['option']['product']);exit;
		
		$this->pktdblib->set_table('customers');
		$customers = $this->pktdblib->get('created asc');
		$data['option']['customer'][0] = 'Select Customer';
		foreach ($customers->result_array() as $customerKey => $customer) {
			$data['option']['customer'][$customer['id']] = $customer['company_name']." (".$customer['first_name']." ".$customer['middle_name']." ".$customer['surname'].")";
		}
		
		$this->pktdblib->set_table('login');
		$logins = $this->pktdblib->custom_query('Select id,first_name,surname from login where is_active=true and id in (select login_id from user_roles where account_type in ("companies", "employees"))');
		$data['option']['sold_by'][] = 'Select Sales Person';
		foreach ($logins as $loginKey => $login) {
			$data['option']['sold_by'][$login['id']] = $login['first_name']." ".$login['surname'];
		}

		$data['option']['uom'] = $this->pktlib->uom();
		//print_r($this->uri->segment(3));exit;
		
		$sql2 = 'Select o.*, concat(c.first_name, " ",c.middle_name, " ",c.surname) as customername from orders o inner join customers c on c.id = o.customer_id where o.id='.$this->uri->segment(3);
		//echo $sql2;
		$data['order'] = $this->pktdblib->custom_query($sql2);
		/*echo '<pre>';
		print_r($data['order']);
		echo '</pre>';*/
		//echo var_dump($data['order']);exit;
		$data['option']['address'][] = 'Select Address';
		$sql = 'Select a.id,a.site_name, a.pincode, a.tally_address, a.address_1, a.address_2 from customers c inner join address a on a.user_id=c.id and a.type="customers" where a.user_id='.$data['order'][0]['customer_id'].' and a.is_active=true';
		$sql2 = $this->pktdblib->custom_query('select * from user_roles where user_id='.$data['order'][0]['customer_id'].' and account_type="customers"');
        if(count($sql2)>0){
            //$sql2 = $sql2[0];
            $role = [];
            foreach($sql2 as $roleAddress){
                //print_r($roleAddress);
                $role[] = $roleAddress['login_id'];
            }
            $sql.= ' UNION Select a.id,a.site_name, a.pincode, a.tally_address, a.address_1, a.address_2 from address a where a.is_active=true and a.user_id in ('.implode(",", $role).') and a.type="login"';
        }
        //echo json_encode($sql).'<br/>';exit;
		$typeWiseUsers = $this->pktdblib->custom_query($sql);//exit;
        
        foreach($typeWiseUsers as $addkey=>$addvalue){
           $data['option']['address'][$addvalue['id']] = $addvalue['address_1'].' '.$addvalue['address_2'].' '.$addvalue['pincode'];
        }
        
        $orderDetails = $this->pktdblib->custom_query('Select * from order_details where order_id='.$this->uri->segment(3));
        if(count($orderDetails)>0){
            foreach($orderDetails as $odKey=>$orderDetail){
                //$variant = json_decode($orderDetail['variation'], true);
                $productAttributes = $this->pktdblib->custom_query('Select pa.id, concat(a.unit, " ", a.uom) as uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.product_id='.$orderDetail['product_id'].' and pa.is_active=true');
                foreach($productAttributes as $paKey=>$productAttribute){
                    $orderDetails[$odKey]['attributeList'][$productAttribute['id']] = $productAttribute['uom'];
                }
            }
        }
        
        $data['orderDetails'] = $orderDetails;
        /*echo '<pre>';
        print_r($orderDetails);
        exit;*/
		$this->load->view("orders/admin_edit", $data);
	}

	function admin_order_form($data = []) {


		$this->pktdblib->set_table('products');
		$data['products'] = $this->pktdblib->custom_query('Select * from products where is_active=true and show_on_website=true');
		$data['option']['product'][0] = 'Select Product';
		foreach ($data['products'] as $productKey => $product) {
			$data['option']['product'][$product['id']] = $product['product'];
		}
		/*$this->pktdblib->set_table('customers');
		$customers = $this->pktdblib->get_where_custom('is_active', true);*/
		$customers = Modules::run('customers/customer_with_default_address');
		$data['option']['customer'][0] = 'Select Customer';
		foreach ($customers as $customerKey => $customer) {
			$data['option']['customer'][$customer['id']] = $customer['first_name']." ".$customer['middle_name']." ".$customer['surname']." - ".$customer['area_name']." - ".$customer['contact_1'];
		}
		
		$this->pktdblib->set_table('login');
		$logins = $this->pktdblib->custom_query('Select id,first_name,surname from login where is_active=true and id in (select login_id from user_roles where account_type in ("companies", "employees"))');
		$data['option']['sold_by'][] = 'Select Sales Person';
		foreach ($logins as $loginKey => $login) {
			$data['option']['sold_by'][$login['id']] = $login['first_name']." ".$login['surname'];
		}

		$data['option']['uom'] = $this->pktlib->uom();
		//print_r($data);exit;

		$this->load->view("orders/admin_add", $data);
	}
	
	
	
	function create_order_code($enqId){
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
	
	function admin_add_multiple_order_details($data, $orderId, $productId=0) {
	    $oldOrderDetails = $this->pktdblib->custom_query('Select * from order_details where order_id='.$orderId);
		$orderDetails = [];
		$insert = [];
		$update = [];
		$totalAmt = 0;
		//echo '<pre>';print_r($data);exit;
		foreach ($data as $key => $orderDetail) {
		    if(isset($orderDetail['is_active'])){
		        $orderDetail['is_active'] = true;
		        $totalAmt = $totalAmt+($orderDetail['qty']*$orderDetail['unit_price']);
		    }else{
		        $orderDetail['is_active'] = false;
		    }
			$orderDetails[$key] = $orderDetail;
			$orderDetails[$key]['variation'] = json_encode(['attribute'=>['product_attribute_id'=>$orderDetail['product_attribute_id']]]);
			$orderDetails[$key]['order_id'] = $orderId;
			if(isset($orderDetail['id']) && $orderDetail['id']!=''){
				$orderDetails[$key]['modified'] = date('Y-m-d H:i:s');
				$orderDetails[$key]['modified_by'] = $this->session->userdata('user_id');
				$update[] = $orderDetails[$key];
			}else{
				$orderDetails[$key]['created'] = date('Y-m-d H:i:s');
				$orderDetails[$key]['created_by'] = $this->session->userdata('user_id');
				if(isset($orderDetails[$key]['id'])){
				    unset($orderDetails[$key]['id']);
				}
				$insert[] = $orderDetails[$key];

			}
		}
		/*echo '<pre>';print_r($insert);
		print_r($update);
		exit;*/
		$detailCount = 0;
		if(!empty($insert)){
    		$this->pktdblib->set_table('order_details');
    		$detailCount = $this->pktdblib->_insert_multiple($insert);
		}
		
		if(!empty($update)){
    		$detailCount = $this->admin_edit_multiple_order_details($update);
		}
		$updateOrder['amount_before_tax'] = $totalAmt;
        $updateOrder['amount_after_tax'] = $totalAmt;
        $this->pktdblib->set_table('orders');
        $order = $this->pktdblib->_update($orderId,$updateOrder);
		$updStock = $this->updateStock($orderId, $oldOrderDetails);
		if($detailCount){
		    
			return $detailCount;
		}
		else
			return false;
	}
	
	function admin_edit_multiple_order_details($data) {
	    //echo '<pre>';print_r($data);exit;
		$this->pktdblib->set_table('order_details');
		$upd = $this->pktdblib->update_multiple('id', $data);
		//print_r($upd);exit;
		return $upd;
	}
	
	function admin_add_2() {
        //check_user_login(FALSE);
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            /*echo '<pre>';
            print_r($this->input->post());
            exit;*/
            $data['values_posted'] = $this->input->post();
            //echo '<pre>';print_r($_POST);exit;
            
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->form_validation->set_rules('data[orders][customer_id]', 'Customer', 'required');
            $this->form_validation->set_rules('data[orders][shipping_address_id]', 'Address', 'required');
            $this->form_validation->set_rules('data[orders][sale_by]', 'Sale Person', 'required');
            
            if($this->form_validation->run()!==FALSE)
            {
                $post_data = $this->input->post('data[orders]');//$_POST['data']['stocks'];
                $post_data['date'] = date('Y-m-d H:i:s');
                $post_data['created'] = date('Y-m-d H:i:s');
                $post_data['billing_address_id'] = $post_data['shipping_address_id'];
                $post_data['created_by'] = $this->session->userdata('user_id');
                
                $post_data['sale_by'] = $this->session->userdata('user_id');
                
                $post_data['project_name'] = 'Salesman POS';
                $post_data['order_status_id'] = 3;
                $post_data['fiscal_yr'] = $this->pktlib->get_fiscal_year();
                $reg_order = json_decode($this->_register_new_order($post_data), true);
                
                if($reg_order['status'] === "success")
                {
                    $_POST['data']['orders'] = $reg_order['orders'];
                    $_POST['data']['orders']['id'] = $reg_order['id'];
                    
                    if(NULL !== $this->input->post('order_details')){
                        $stockDetails = $this->admin_add_multiple_order_details($this->input->post('order_details'), $reg_order['id']);
                        $_POST['data']['order_details'] = $this->input->post('order_details');
                    }

                    
                    
                    if($this->input->is_ajax_request()){  
                        $msg = array('msg'=>'Order Added Successfully', 'class'=>'alert alert-success');
                        echo json_encode($msg);
                        exit;
                    }
                    $msg = array('message'=>'Order Added Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
                    redirect(custom_constants::new_order_url2);
                }
                else
                {
                    // Registration error
                    $data['form_error'] = $reg_enq['msg'];
                }
                
            }else{
                $msg = array('message'=>'Error Occured'.validation_errors(), 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
            }
        }
        
        $data['meta_title'] = "POS";
        $data['meta_description'] = "POS";
        $data['title'] = "Module :: Orders";
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> New Order';
        //echo "reached here"; exit;
        $data['modules'][] = "orders";
        $data['methods'][] = "admin_order_form2";
        $data['js'][] = '<script type="text/javascript">
            $(".product").on("change", function(){
                var id = this.id;
                alert($(this).val());
                var productId = $(this).val();
                $.ajax({
                  type: "POST",
                  dataType: "html",
                  url : "products/get_product_details/"+productId,
                  data: "product_id="+productId,
                  success: function(response) {
                    console.log(response);
                    //$("#"+datatarget).select2("destroy").empty().select2({data : response});
                  }
                
                });
                
            })

            $("#customer_id").on("change", function(){
                var val = $(this).val();
                var type = "customers";
                console.log(val);
                console.log(type);
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data:{"type":type, "id":val},
                    url : base_url+"address/type_wise_user2/",
                    success: function(response) {
                        console.log(response);
                        $("#invoice_address_id").select2("destroy").empty().select2({data : response});
                        $("#delivery_address_id").select2("destroy").empty().select2({data : response});

                    }
                
                });
            });
            
            $("#customer_id").on("change", function(){
                var val = $(this).val();
                var type = "customers";
                console.log(val);
                console.log(type);
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data:{"type":type, "id":val},
                    url : base_url+"address/type_wise_user2/",
                    success: function(response) {
                        console.log(response);
                        $("#invoice_address_id").select2("destroy").empty().select2({data : response});
                        $("#delivery_address_id").select2("destroy").empty().select2({data : response});

                    }
                
                });
            });
            
            $(document).on("change", ".attribute", function(){
                var attribute = $(this).val();
                var trId = $(this).closest("tr").attr("id");
                var id = this.id;
                $.ajax({
                  type: "POST",
                  dataType: "json",
                  url : base_url+"products/idWiseProductAttribute/"+attribute,
                  success: function(response) {
                    if(response.status == "success"){
                        $("#unit_price_"+trId).val(response.data.price);
                        $("#qty_"+trId).val(1).keyup();
                    } 
                  }
                });
            });

            $("form").on("submit", function(e){
                e.preventDefault();
                if($("#customer_id").val()==0){
                    alert("Please select Customer");
                    $("#customer_id").focus();
                    return false;
                }

                if($("#delivery_address_id").val()==0){
                    alert("Please select Addres");
                    $("#delivery_address_id").focus();
                    return false;
                }
                var error = false;
                $(".select2").each(function(){
                    
                    if($(this).hasClass("required")){
                        var id = this.id;
                        
                        if($("#"+id).val()=="" || $("#"+id).val()=="0"){

                            alert("Selected Field is missing");
                            this.focus();
                            error = true;
                            return false;
                        }
                        //return false;
                    }
                })

                if(error == false){
                    //this.submit();
                    
                }
                
                
            })
        </script>';
        //echo '<pre>';print_r($data['option']['product']);
        //echo Modules::run("templates/login_template", $data);
        echo Modules::run("templates/admin_template", $data);
        
    }

    function admin_order_form2($data = []) {
        $this->pktdblib->set_table('products');
        $data['products'] = $this->pktdblib->custom_query('Select * from products where is_active=true and show_on_website=true');
        $data['option']['product'][0] = 'Select Product';
        foreach ($data['products'] as $productKey => $product) {
            $data['option']['product'][$product['id']] = $product['product'];
        }
        /*$this->pktdblib->set_table('customers');
        $customers = $this->pktdblib->get_where_custom('is_active', true);*/
        
        $customers = Modules::run('customers/customer_with_default_address');
        
		$data['option']['customer'][0] = 'Select Customer';
		foreach ($customers as $customerKey => $customer) {
			$data['option']['customer'][$customer['id']] = $customer['first_name']." ".$customer['middle_name']." ".$customer['surname']." - ".$customer['area_name']." - ".$customer['contact_1'];;
		}
        
        $this->pktdblib->set_table('login');
        $logins = $this->pktdblib->custom_query('Select id,first_name,surname from login where is_active=true and id in (select login_id from user_roles where account_type in ("companies", "employees"))');
        $data['option']['sold_by'][] = 'Select Sales Person';
        foreach ($logins as $loginKey => $login) {
            $data['option']['sold_by'][$login['id']] = $login['first_name']." ".$login['surname'];
        }

        $data['option']['uom'] = $this->pktlib->uom();
        //print_r($data);exit;

        $this->load->view("orders/admin_add2", $data);
    }
    
    function export(){
        $sql='Select o.*, concat(l.first_name," ",l.surname) as sales_person, concat(l2.first_name," ",l2.surname) as created_person, c.company_name, concat(c.first_name," ",c.middle_name, " ", c.surname) as contact_person, concat(a.address_1,", ", a.address_2, ", ", areas.area_name,", ", cities.city_name,"-",a.pincode, " \nFSSI:", a.fssi, " \nGST NO:",a.gst_no) as address, areas.area_name, os.status, c.contact_1, o.message from orders o inner join customers c on c.id=o.customer_id inner join address a on a.id=o.shipping_address_id left join countries ct on ct.name left join states s on s.id=a.state_id left join cities on cities.id=a.city_id left join areas ON areas.id=a.area_id INNER join order_status os on os.id=o.order_status_id left join login l on l.id=o.sale_by left join login l2 on l2.id=o.created_by where 1=1';
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //print_r($_POST);exit;
            $sql.=' AND DATE_FORMAT(o.date, "%m/%d/%Y") BETWEEN "'.str_replace(' - ', '" AND "', $this->input->post('order_date')).'"';
        }else{
            $sql.=' AND o.date>="'.date('Y-m-d', strtotime('-1 days')).'"';
        }
        
        $sql.='  and o.order_status_id not in (1,2,8) order by date DESC, c.first_name ASC';
        //echo $sql;exit;
        $query = $this->pktdblib->custom_query($sql);
        $fileName = 'order-'.date('dmY');
        $this->load->library('excel');
        //$objPHPExcel = PHPExcel_IOFactory::load($filename);
        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $tableColumns = ['Order Number', 'ledger name / business name', 'Contact Person', 'Ordered Through', 'Contact Number', 'Order Date', 'Order Time', 'Address', 'Teritorry', 'Product specification', 'Sales Person', 'Entered By', 'Invoice Amount', 'Order Status', 'Remark'];
        $column = 0;
        foreach($tableColumns as $field){
            $excel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);//->getStyle( $column )->getFont()->setBold( true );;
            $column++;
        }
        $excel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(38);
        $excelRow = 2;
        //echo '<pre>';
        
        foreach($query as $order){
            $orderDetails = $this->pktdblib->custom_query('Select od.*, p.product,p.base_uom, p.base_price from order_details od inner join products p on p.id=od.product_id where od.order_id='.$order['id'].' and od.is_active=true');
            $str = '';
            foreach($orderDetails as $detail){
                $spec = json_decode($detail['variation'], true);
                $attribute = $this->pktdblib->custom_query('select a.unit, a.uom, concat(a.unit," ",a.uom) as attribute, pa.per_unit_margin from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$spec['attribute']['product_attribute_id'].'"');
                if(count($attribute)>0){
                    $requireUOM = explode(" ", $attribute[0]['attribute']);
                    $baseUOM = explode(" ", $detail['base_uom']);
                    $unit = $this->pktlib->unit_convertion($attribute[0]['uom'], $baseUOM[1]);
                    $qt = $requireUOM[0]*$unit*($detail['qty']-$detail['return_quantity']);
                    //echo $unit." <br>";
                    //$price = ceil(($detail['base_price']+$attribute[0]['per_unit_margin'])*$requireUOM[0]*$unit);
                    //$perKgPrice = ($detail['base_price']+$attribute[0]['per_unit_margin']);
                    $rateperkg = $detail['unit_price']/($requireUOM[0]*$unit);
                    $str.=$detail['product']." (".$attribute[0]['unit']." ".$attribute[0]['uom'].") pcs:".($detail['qty']-$detail['return_quantity'])." rate/pc:".$detail['unit_price'];
                    $str.=' QTY:'.$qt." ".$baseUOM[1]."  RATE PER".$baseUOM[1].":".$rateperkg."\n";
                }else{
                    $str.=$detail['product']." pcs:".($detail['qty']-$detail['return_quantity'])." rate/pc:".$detail['unit_price']."\n";
                }
                
                
            }
            $col = 0;
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $excelRow, $order['order_code']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, !empty($order['company_name'])?$order['company_name']:$order['contact_person']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['contact_person'])->getStyle('B')->getAlignment()->setWrapText(true);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['project_name']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['contact_1']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, date('d/m/Y', strtotime($order['date'])));
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, date('h:i:s a', strtotime($order['date'])));
            
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, str_replace(", , ",", ", str_replace(",, ",", ", $order['address']." \nRemark:".$order['delivery_remark'])))->getStyle('D')->getAlignment()->setWrapText(true);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['area_name']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $str)->getStyle('F')->getAlignment()->setWrapText(true);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['sales_person']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['created_person']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['amount_after_tax']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['status']);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['message']);
            $excelRow++;
        }
        //exit;
        /*echo '<pre>';
        print_r($excel);
        exit;*/
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    
    function updateStock($orderId=NULL, $oldData=[]){
        /*print_r($orderId);
        exit;*/
	    if(NULL===$orderId){
	        return false;
	    }
	    
		$this->pktdblib->set_table('orders');
		$order = $this->pktdblib->get_where($orderId);

		$this->pktdblib->set_table('order_details');
		$query = $this->pktdblib->get_where_custom('order_id', $orderId);
		$orderDetails = $query->result_array();
    		//print_r($orderDetails);exit;
        /*echo '<pre>';
        print_r($oldData);
        print_r($orderDetails);
        exit;*/
		foreach ($orderDetails as $key => $detail) {
		    //print_r($detail);
		    if(isset($oldData[$key])){
		        if($oldData[$key]['is_active']==$detail['is_active'] && $oldData[$key]['product_id']==$detail['product_id']){
		            //product not changed
		            $attr = json_decode($oldData[$key]['variation'], true);
        			$this->pktdblib->set_table('products');
                    $product = $this->pktdblib->get_where($oldData[$key]['product_id']);
                    if($product['is_pack']){ //echo "hii";
                    	$packProducts = $this->pktdblib->custom_query('Select * from pack_products where basket_id = "'.$oldData[$key]['product_id'].'"');
                    	foreach ($packProducts as $pkey => $pack) {
                    		$stock = [];
                    		$this->pktdblib->set_table('products');
                    		$pcproduct = $this->pktdblib->get_where($pack['product_id']);
    
    		                if($pcproduct['overall_stock_mgmt']){
    			    			$this->pktdblib->set_table('product_details');
    			    			$query = $this->pktdblib->get_where_custom('product_id', $oldData[$key]['product_id']);
    			    			$stock = $query->row_array();
    		                }else{ 
    		                	$this->pktdblib->set_table('product_attributes');
    			    			$query = $this->pktdblib->get_where_custom('id', $pack['product_attribute_id']);
    			    			//echo $this->db->last_query();
    			    			$stock = $query->row_array();
    		                }
    
    		                $productAtt = [];
    		                if(isset($pack['product_attribute_id'])){
    		                    $sql = 'Select a.unit,a.uom, pa.stock_qty, pa.id from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id='.$pack['product_attribute_id'];
    		                    $query3 = $this->pktdblib->custom_query($sql);
    		                    $productAttribute = $query3[0];
    		                    $productAtt['id'] = $productAttribute['id'];
    		                    //print_r($productAttribute);
    		                    $unit = 1;
    		                    //echo $productAttribute['unit']." ".$productAttribute['uom']." - ".$product['base_uom'];exit;
    		                    if($productAttribute['unit']." ".$productAttribute['uom']!=$pcproduct['base_uom']){ 
    		                        $param1 = $productAttribute['uom'];
    		                        $param2 = explode(' ',$pcproduct['base_uom']);
    		                        $unit = $this->pktlib->unit_convertion($productAttribute['uom'], (count($param2)>1)?$param2[1]:$param2[0]);
    		                        $oldData[$key]['qty'] = $unit*$productAttribute['unit']*$oldData[$key]['qty'];
    		                    }
    		                }
    		                $updStock = [];
    		                //print_r($oldData[$key]);//exit;
    		                if($pcproduct['overall_stock_mgmt']){ //echo "hii";
    			    			$updStock['in_stock_qty'] = $stock['in_stock_qty']+$oldData[$key]['qty'];
    			    			$updStock['modified'] = date('Y-m-d H:i:s');
    			    			//prin
    			    			$this->pktdblib->set_table('product_details');
    			    			$this->pktdblib->_update($stock['id'], $updStock);
    		                }else{ //echo "hello";
    		                	//print_r($productAtt);
    		                	$updStock['stock_qty'] = $stock['stock_qty']+$oldData[$key]['qty'];
    		                	$updStock['modified'] = date('Y-m-d H:i:s');
    		                	$this->pktdblib->set_table('product_attributes');
    			    			$this->pktdblib->_update($productAtt['id'], $updStock);	
    		                }
    		                
                    	}
    
                    }else{ //echo "hello";
                    	$stock = [];
                    	//echo $product['overall_stock_mgmt'];
    	                if($product['overall_stock_mgmt']){ 
    		    			$this->pktdblib->set_table('product_details');
    		    			$query = $this->pktdblib->get_where_custom('product_id', $oldData[$key]['product_id']);
    		    			$stock = $query->row_array();
    	                }else{ //echo "hello";
    	                	$this->pktdblib->set_table('product_attributes');
    		    			$query = $this->pktdblib->get_where_custom('id', $attr['attribute']['product_attribute_id']);
    		    			//echo $this->db->last_query();
    		    			$stock = $query->row_array();
    	                }
    	                //print_r($stock);exit;
    	                $productAtt = [];
    	                if(isset($attr['attribute']['product_attribute_id'])){
    	                    $sql = 'Select a.unit,a.uom, pa.stock_qty, pa.id from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id='.$attr['attribute']['product_attribute_id'];
    
    	                    $query3 = $this->pktdblib->custom_query($sql);
    	                    $productAttribute = $query3[0];
    	                    $productAtt['id'] = $productAttribute['id'];
    	                    //print_r($productAttribute);
    	                    $unit = 1;
    	                    //echo $productAttribute['unit']." ".$productAttribute['uom']." - ".$product['base_uom'].'<br>';exit;
    	                    if($productAttribute['unit']." ".$productAttribute['uom']!=$product['base_uom']){ //echo "hello";
    	                        $param1 = $productAttribute['uom'];
    	                        $param2 = explode(' ',$product['base_uom']);
    	                        $unit = $this->pktlib->unit_convertion($productAttribute['uom'], (count($param2)>1)?$param2[1]:$param2[0]);
    	                        $oldData[$key]['qty'] = $unit*$productAttribute['unit']*$oldData[$key]['qty'];
    	                    }
    	                }
    	                
    	                $updStock = [];
    	                //print_r($product);
    	                if($product['overall_stock_mgmt']){ 
    	                	
    		    			$updStock['in_stock_qty'] = $stock['in_stock_qty']+$oldData[$key]['qty'];
    		    			$updStock['modified'] = date('Y-m-d H:i:s');
    		    			
    		    			$this->pktdblib->set_table('product_details');
    		    			$this->pktdblib->_update($stock['id'], $updStock);
    	                }else{ 
    	                	$updStock['stock_qty'] = $stock['stock_qty']+$oldData[$key]['qty'];
    	                	$updStock['modified'] = date('Y-m-d H:i:s');
    	                	$this->pktdblib->set_table('product_attributes');
    		    			$this->pktdblib->_update($productAtt['id'], $updStock);	
    	                }
                    }
		        }else{
		            //product changed
		        }
		    }
		    //echo $this->db->last_query();exit;
		    
		    if($detail['is_active']===FALSE){
		        continue;
		    }
		    //print_r($detail);//exit;
    		//if(count($oldData)==0){
    		    $attr = json_decode($detail['variation'], true);
    			$this->pktdblib->set_table('products');
                $product = $this->pktdblib->get_where($detail['product_id']);
                if($product['is_pack']){ //echo "hii";
                	$packProducts = $this->pktdblib->custom_query('Select * from pack_products where basket_id = "'.$detail['product_id'].'"');
                	foreach ($packProducts as $pkey => $pack) {
                		$stock = [];
                		$this->pktdblib->set_table('products');
                		$pcproduct = $this->pktdblib->get_where($pack['product_id']);

		                if($pcproduct['overall_stock_mgmt']){
			    			$this->pktdblib->set_table('product_details');
			    			$query = $this->pktdblib->get_where_custom('product_id', $detail['product_id']);
			    			$stock = $query->row_array();
		                }else{ 
		                	$this->pktdblib->set_table('product_attributes');
			    			$query = $this->pktdblib->get_where_custom('id', $pack['product_attribute_id']);
			    			//echo $this->db->last_query();
			    			$stock = $query->row_array();
		                }

		                $productAtt = [];
		                if(isset($pack['product_attribute_id'])){
		                    $sql = 'Select a.unit,a.uom, pa.stock_qty, pa.id from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id='.$pack['product_attribute_id'];
		                    $query3 = $this->pktdblib->custom_query($sql);
		                    $productAttribute = $query3[0];
		                    $productAtt['id'] = $productAttribute['id'];
		                    //print_r($productAttribute);
		                    $unit = 1;
		                    //echo $productAttribute['unit']." ".$productAttribute['uom']." - ".$product['base_uom'];exit;
		                    if($productAttribute['unit']." ".$productAttribute['uom']!=$pcproduct['base_uom']){ 
		                        $param1 = $productAttribute['uom'];
		                        $param2 = explode(' ',$pcproduct['base_uom']);
		                        $unit = $this->pktlib->unit_convertion($productAttribute['uom'], (count($param2)>1)?$param2[1]:$param2[0]);
		                        $detail['qty'] = $unit*$productAttribute['unit']*$detail['qty'];
		                    }
		                }
		                $updStock = [];
		                //print_r($detail);//exit;
		                if($pcproduct['overall_stock_mgmt']){ //echo "hii";
			    			$updStock['in_stock_qty'] = $stock['in_stock_qty']-$detail['qty'];
			    			$updStock['modified'] = date('Y-m-d H:i:s');
			    			//prin
			    			$this->pktdblib->set_table('product_details');
			    			$this->pktdblib->_update($stock['id'], $updStock);
		                }else{ //echo "hello";
		                	//print_r($productAtt);
		                	$updStock['stock_qty'] = $stock['stock_qty']-$detail['qty'];
		                	$updStock['modified'] = date('Y-m-d H:i:s');
		                	$this->pktdblib->set_table('product_attributes');
			    			$this->pktdblib->_update($productAtt['id'], $updStock);	
		                }
		                
                	}

                }else{ //echo "hello";
                	$stock = [];
                	//echo $product['overall_stock_mgmt'];
	                if($product['overall_stock_mgmt']){ 
		    			$this->pktdblib->set_table('product_details');
		    			$query = $this->pktdblib->get_where_custom('product_id', $detail['product_id']);
		    			$stock = $query->row_array();
	                }else{ //echo "hello";
	                	$this->pktdblib->set_table('product_attributes');
		    			$query = $this->pktdblib->get_where_custom('id', $attr['attribute']['product_attribute_id']);
		    			//echo $this->db->last_query();
		    			$stock = $query->row_array();
	                }
	                //print_r($stock);exit;
	                $productAtt = [];
	                if(isset($attr['attribute']['product_attribute_id'])){
	                    $sql = 'Select a.unit,a.uom, pa.stock_qty, pa.id from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id='.$attr['attribute']['product_attribute_id'];

	                    $query3 = $this->pktdblib->custom_query($sql);
	                    $productAttribute = $query3[0];
	                    $productAtt['id'] = $productAttribute['id'];
	                    //print_r($productAttribute);
	                    $unit = 1;
	                    //echo $productAttribute['unit']." ".$productAttribute['uom']." - ".$product['base_uom'].'<br>';exit;
	                    if($productAttribute['unit']." ".$productAttribute['uom']!=$product['base_uom']){ //echo "hello";
	                        $param1 = $productAttribute['uom'];
	                        $param2 = explode(' ',$product['base_uom']);
	                        $unit = $this->pktlib->unit_convertion($productAttribute['uom'], (count($param2)>1)?$param2[1]:$param2[0]);
	                        $detail['qty'] = $unit*$productAttribute['unit']*$detail['qty'];
	                    }
	                }
	                
	                $updStock = [];
	                //print_r($product);
	                if($product['overall_stock_mgmt']){ 
	                	
		    			$updStock['in_stock_qty'] = $stock['in_stock_qty']-$detail['qty'];
		    			$updStock['modified'] = date('Y-m-d H:i:s');
		    			
		    			$this->pktdblib->set_table('product_details');
		    			$this->pktdblib->_update($stock['id'], $updStock);
	                }else{ 
	                	$updStock['stock_qty'] = $stock['stock_qty']-$detail['qty'];
	                	$updStock['modified'] = date('Y-m-d H:i:s');
	                	$this->pktdblib->set_table('product_attributes');
		    			$this->pktdblib->_update($productAtt['id'], $updStock);	
	                }
	                //echo $this->db->last_query();
                }
                /*echo $this->db->last_query();
                echo '<br>';*/
    		    /*}else{
		        
		    }*/
		    
            
		}
		//exit;
		return TRUE;
	}
	
	function admin_edit_field_pos($orderId) {
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            /*echo '<pre>';
            print_r($this->input->post());
            exit;*/
            $data['values_posted'] = $this->input->post();
            //echo '<pre>';print_r($_POST);exit;
            
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->form_validation->set_rules('data[orders][customer_id]', 'Customer', 'required');
            $this->form_validation->set_rules('data[orders][shipping_address_id]', 'Address', 'required');
            //$this->form_validation->set_rules('data[orders][sale_by]', 'Sale Person', 'required');
            
            if($this->form_validation->run()!==FALSE)
            {
                $post_data = $this->input->post('data[orders]');//$_POST['data']['stocks'];
                //echo '<pre>';print_r($post_data);exit;
                $post_data['modified'] = date('Y-m-d H:i:s');
                $post_data['modified_by'] = $this->session->userdata('user_id');
                $reg_order = json_decode($this->edit_order($orderId, $post_data), true);
                
                if($reg_order)
                {
                    
                    if(NULL !== $this->input->post('order_details')){
                        $stockDetails = $this->admin_add_multiple_order_details($this->input->post('order_details'), $orderId);
                        //echo '<pre>';print_r($stockDetails);exit;
                        $_POST['data']['order_details'] = $this->input->post('order_details');
                    }

                    
                    $msg = array('message'=>'Order Update Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
                    redirect(custom_constants::admin_order_listing_url);
                }
                else
                {
                    // Registration error
                    $msg = array('message'=>'Error Occured while updating', 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
                }
                
            }else{
                $msg = array('message'=>'Error Occured'.validation_errors(), 'class'=>'alert alert-danger');
                    $this->session->set_flashdata('message',$msg);
            }
            redirect(custom_constants::edit_order_url.'/'.$orderId);
        }else{
            $this->pktdblib->set_table("orders");
            $data['values_posted']['orders'] = $this->pktdblib->get_where($orderId);
            
            $this->pktdblib->set_table('order_details');
            $orderDetails = $this->pktdblib->get_where_custom('order_id', $orderId);
            $data['values_posted']['orderDetails'] = $orderDetails->result_array();
            //echo '<pre>';print_r($data['values_posted']['orderDetails'][0]);exit;
        }
        //echo '<pre>';print_r($data);exit;
        $data['id'] = $orderId;
        $data['meta_title'] = "POS";
        $data['meta_description'] = "POS";
        $data['title'] = "Module :: Orders";
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> Edit Order';
        //echo '<pre>';print_r($data);exit;
        //echo "reached here"; exit;
        $data['modules'][] = "orders";
        $data['methods'][] = "admin_order_form_edit_field_pos";
        $data['js'][] = '<script type="text/javascript">
            $(".product").on("change", function(){
                var id = this.id;
                alert($(this).val());
                var productId = $(this).val();
                $.ajax({
                  type: "POST",
                  dataType: "html",
                  url : "products/get_product_details/"+productId,
                  data: "product_id="+productId,
                  success: function(response) {
                    console.log(response);
                    //$("#"+datatarget).select2("destroy").empty().select2({data : response});
                  }
                
                });
                
            })

            $("#customer_id").on("change", function(){
                var val = $(this).val();
                var type = "customers";
                console.log(val);
                console.log(type);
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data:{"type":type, "id":val},
                    url : base_url+"address/type_wise_user2/",
                    success: function(response) {
                        console.log(response);
                        $("#invoice_address_id").select2("destroy").empty().select2({data : response});
                        $("#delivery_address_id").select2("destroy").empty().select2({data : response});

                    }
                
                });
            });
            
            $("#customer_id").on("change", function(){
                var val = $(this).val();
                var type = "customers";
                console.log(val);
                console.log(type);
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data:{"type":type, "id":val},
                    url : base_url+"address/type_wise_user2/",
                    success: function(response) {
                        console.log(response);
                        $("#invoice_address_id").select2("destroy").empty().select2({data : response});
                        $("#delivery_address_id").select2("destroy").empty().select2({data : response});

                    }
                
                });
            });
            
            $(document).on("change", ".attribute", function(){
                var attribute = $(this).val();
                var trId = $(this).closest("tr").attr("id");
                var id = this.id;
                $.ajax({
                  type: "POST",
                  dataType: "json",
                  url : base_url+"products/idWiseProductAttribute/"+attribute,
                  success: function(response) {
                    if(response.status == "success"){
                        $("#unit_price_"+trId).val(response.data.price);
                    } 
                  }
                });
            });

            $("form").on("submit", function(e){
                e.preventDefault();
                if($("#customer_id").val()==0){
                    alert("Please select Customer");
                    $("#customer_id").focus();
                    return false;
                }

                if($("#delivery_address_id").val()==0){
                    alert("Please select Addres");
                    $("#delivery_address_id").focus();
                    return false;
                }
                var error = false;
                $(".select2").each(function(){
                    
                    if($(this).hasClass("required")){
                        var id = this.id;
                        
                        if($("#"+id).val()=="" || $("#"+id).val()=="0"){

                            alert("Selected Field is missing");
                            this.focus();
                            error = true;
                            return false;
                        }
                        //return false;
                    }
                })

                if(error == false){
                    this.submit();
                }
                
                
            })
        </script>';
        echo Modules::run("templates/admin_template", $data);
        
    }

    function admin_order_form_edit_field_pos($data = []) {

        //echo '<pre>';print_r($data);exit;
        $this->pktdblib->set_table('products');
        $data['products'] = $this->pktdblib->custom_query('Select * from products where is_active=true and show_on_website=true');
        $data['option']['product'][0] = 'Select Product';
        foreach ($data['products'] as $productKey => $product) {
            $data['option']['product'][$product['id']] = $product['product'];
        }
        
        //$this->pktdblib->set_table('order_status');
        $orderStatus = $this->pktdblib->custom_query('Select * from order_status where is_active=true');
        $data['option']['status'][0] = 'Select Status';
        foreach ($orderStatus as $statusKey => $status) {
            $data['option']['status'][$status['id']] = $status['status'];
        }
        //echo "<pre>"; print_r($data['option']['product']);exit;
        
        $this->pktdblib->set_table('customers');
        $customers = $this->pktdblib->get('created asc');
        $data['option']['customer'][0] = 'Select Customer';
        foreach ($customers->result_array() as $customerKey => $customer) {
            $data['option']['customer'][$customer['id']] = $customer['company_name']." (".$customer['first_name']." ".$customer['middle_name']." ".$customer['surname'].")";
        }
        
        $this->pktdblib->set_table('login');
        $logins = $this->pktdblib->custom_query('Select id,first_name,surname from login where is_active=true and id in (select login_id from user_roles where account_type in ("companies", "employees"))');
        $data['option']['sold_by'][] = 'Select Sales Person';
        foreach ($logins as $loginKey => $login) {
            $data['option']['sold_by'][$login['id']] = $login['first_name']." ".$login['surname'];
        }

        $data['option']['uom'] = $this->pktlib->uom();
        //print_r($this->uri->segment(3));exit;
        
        $sql2 = 'Select o.*, concat(c.first_name, " ",c.middle_name, " ",c.surname) as customername from orders o inner join customers c on c.id = o.customer_id where o.id='.$this->uri->segment(3);
        //echo $sql2;
        $data['order'] = $this->pktdblib->custom_query($sql2);
        /*echo '<pre>';
        print_r($data['order']);
        echo '</pre>';*/
        //echo var_dump($data['order']);exit;
        $data['option']['address'][] = 'Select Address';
        $sql = 'Select a.id,a.site_name, a.pincode, a.tally_address, a.address_1, a.address_2 from customers c inner join address a on a.user_id=c.id and a.type="customers" where a.user_id='.$data['order'][0]['customer_id'].' and a.is_active=true';
        $sql2 = $this->pktdblib->custom_query('select * from user_roles where user_id='.$data['order'][0]['customer_id'].' and account_type="customers"');
        if(count($sql2)>0){
            //$sql2 = $sql2[0];
            $role = [];
            foreach($sql2 as $roleAddress){
                //print_r($roleAddress);
                $role[] = $roleAddress['login_id'];
            }
            $sql.= ' UNION Select a.id,a.site_name, a.pincode, a.tally_address, a.address_1, a.address_2 from address a where a.is_active=true and a.user_id in ('.implode(",", $role).') and a.type="login"';
        }
        //echo json_encode($sql).'<br/>';exit;
        $typeWiseUsers = $this->pktdblib->custom_query($sql);//exit;
        
        foreach($typeWiseUsers as $addkey=>$addvalue){
           $data['option']['address'][$addvalue['id']] = $addvalue['address_1'].' '.$addvalue['address_2'].' '.$addvalue['pincode'];
        }
        
        $orderDetails = $this->pktdblib->custom_query('Select * from order_details where order_id='.$this->uri->segment(3));
        if(count($orderDetails)>0){
            foreach($orderDetails as $odKey=>$orderDetail){
                //$variant = json_decode($orderDetail['variation'], true);
                $productAttributes = $this->pktdblib->custom_query('Select pa.id, concat(a.unit, " ", a.uom) as uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.product_id='.$orderDetail['product_id'].' and pa.is_active=true');
                foreach($productAttributes as $paKey=>$productAttribute){
                    $orderDetails[$odKey]['attributeList'][$productAttribute['id']] = $productAttribute['uom'];
                }
            }
        }


        
        $data['orderDetails'] = $orderDetails;
        /*echo '<pre>';
        print_r($data['orderDetails']);
        exit;*/
        $this->load->view("orders/admin_edit_field_pos", $data);
    }
    
    
    public function admin_invoice_orders(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            /*echo '<pre>';
            print_r($this->input->post());exit;*/
            $postData = $this->input->post('invoice_orders');
            $insert = [];
            $error = false;
            //echo "<pre>";
            $counter = 0;
            foreach ($postData as $key => $value) {
                //echo 'post key '.$key.'<br>';
                if(empty(trim($value['invoice_no']))){
                    continue;
                }
                //echo 'invoice no = at key = '.$key.' '.$value['invoice_no'].'<br>';
                foreach ($value['order_code'] as $orderKey => $order) {
                   // echo 'order code = '.$order.'<br>';
                    $insert[$counter]['order_code'] = $order;
                    $insert[$counter]['invoice_no'] = $value['invoice_no'];
                    $insert[$counter]['fiscal_yr'] = $this->input->post('fiscal_yr');
                    $insert[$counter]['created'] = $insert[$counter]['modified'] = date('Y-m-d H:i:s'); 
                    $insert[$counter]['created_by'] = $insert[$counter]['modified_by'] = $this->session->userdata('user_id');
                    $insert[$counter]['is_active'] = true;
                    $counter = $counter+1;
                }
                
            }

            if($error){
                $msg = array('message'=>'Invoice Already Exist', 'class'=>'alert alert-danger');
                $this->session->set_flashdata('message',$msg);
                return redirect('orders/invoiceorders');
            }else{
                if(!empty($insert)){
                    //print_r($insert);exit;
                    $this->pktdblib->set_table('invoice_orders');
                    $ins = $this->pktdblib->_insert_multiple($insert);
                    $msg = array('message'=>'Invoice Mapped Successfully', 'class'=>'alert alert-success');
                    $this->session->set_flashdata('message',$msg);
                    return redirect('orders/invoiceorders');
                }
            }
        }else{

        }
        $data['meta_title'] = "POS";
        $data['meta_description'] = "POS";
        $data['title'] = "Module :: Orders";
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> Invoice Orders';
        $data['modules'][] = "orders";
        $data['methods'][] = "admin_invoice_order_form";
        echo Modules::run("templates/admin_template", $data);
    }


    public function admin_invoice_order_form($data = []){
        $date = '2021-12-06';//date('Y-m-d', strtotime('-50 day'));
        $custQuery = $this->pktdblib->custom_query('Select distinct customer_id from orders where order_status_id=3 and order_code not in (Select order_code from invoice_orders) and date>"'.$date.'" ');
        $customers = [];
        //print_r($custQuery);
        foreach($custQuery as $cust){
            $customers[] = $cust['customer_id'];
        }
        //print_r($customers);exit;
        //echo $date;exit;
        $sql = 'select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id inner join address a on a.id=o.shipping_address_id inner join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"'.$date.'" and o.customer_id in ('.implode(',', $customers).') order by o.id ASC';
        //echo $sql;exit;
        $orders = $this->pktdblib->custom_query($sql);
        /*echo count($orders);
        echo '<pre>';print_r($orders);exit;*/
        $invoice = [];
        if($orders){
            foreach ($orders as $key => $value) {
                $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['customer'] = '<b>'.anchor("customers/editcustomer/".$value['customer_id'], '<i class="fa fa-edit"></i> '.$value['company_name'].'('.$value['customer_id'].')', ['target'=>'_new']).'</b><br>Address: '.$value['site_name'].'<br>Contact Person: '.$value['customer'];
                $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['date'] = $value['date'];
                $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['area'] = $value['area'];
                //$invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['orders']['order_code'] = $value['order_code'];
                $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['orders'][$value['order_code']]['id'] = $value['id'];
                $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['orders'][$value['order_code']]['orderdetail'] = $this->getOrderDetails($value['id']);
                $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['orders'][$value['order_code']]['order_amount'] = $value['amount_after_tax'];
                if(array_key_exists('invoice_amt', $invoice[$value['customer_id'].'.'.$value['shipping_address_id']])){
                    $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['invoice_amt']= $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['invoice_amt']+$value['amount_after_tax'];
                }else{
                    $invoice[$value['customer_id'].'.'.$value['shipping_address_id']]['invoice_amt']= $value['amount_after_tax'];
                }
            }
        }
        $data['invoiceorders'] = $invoice;
        $month = date('y');
        $data['option']['fiscal_yr'][$this->pktlib->get_fiscal_year()] = $this->pktlib->get_fiscal_year();
        //echo $fiscal_yr;exit;
        //echo $date;exit;
        if($month != '04'){
            $date = date('Y-m-d', strtotime('- 2 month'));
            $data['option']['fiscal_yr'][$this->pktlib->get_fiscal_year($date)] = $this->pktlib->get_fiscal_year($date);
        }
        /*echo '<pre>';
        print_r($data['option']);
        exit;*/
        $data['last_invoice'] = $this->pktdblib->custom_query('Select max(invoice_no) as invoice_no from invoice_orders where fiscal_yr like "'.$this->pktlib->get_fiscal_year().'"');
        //print_r($data['last_invoice']);
        $this->load->view('orders/admin_invoice_orders', $data);
    }

    

    public function getOrderDetails($orderId){
    
        //echo 'Select od.*, p.product from order_details od inner join products p on p.id=od.product_id where od.order_id='.$orderId.' and od.is_active=true<br>';
        $orderDetails = $this->pktdblib->custom_query('Select od.*, p.product from order_details od inner join products p on p.id=od.product_id where od.order_id='.$orderId.' and od.is_active=true');
            $str = '';
            foreach($orderDetails as $detail){
                $spec = json_decode($detail['variation'], true);
                //echo 'select a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$spec['attribute']['product_attribute_id'].'"<br>';
                $attribute = $this->pktdblib->custom_query('select a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$spec['attribute']['product_attribute_id'].'"');
                //print_r($attribute);
                if(count($attribute)>0)
                $str.=$detail['product']." (".$attribute[0]['unit']." ".$attribute[0]['uom'].") qty:".($detail['qty']-$detail['return_quantity'])." Rate:".$detail['unit_price']."\n";
            }
            return $str;
            //echo $str;exit;
            //echo '<pre>';print_r($orderDetails);exit;
            
    }
    
    public function admin_assign_delivery(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //echo '<pre>';
            //print_r($this->input->post());//exit;
            $insert = [];
            $fiscalYr = $this->pktlib->get_fiscal_year();
            $query = $this->pktdblib->custom_query('select max(delivery_no) as delivery_no from deliveryboy_order where fiscal_yr like "'.$fiscalYr.'"');
            
            $transNo = 1;
            if(count($query)>0){
                $transNo = $query[0]['delivery_no']+1;
            }
            foreach ($this->input->post('deliveryboy_orders') as $key => $value) {
                
                $value['delivery_no'] = $transNo;
                $value['fiscal_yr'] = $fiscalYr;
                /*echo $key."<br>";
                print_r($value);*/
                //$value['is_active'] = true;
                $value['created'] = $value['modified'] = date('Y-m-d H:i:s');
                //$value['created_by'] = $value['modified_by'] = $this->session->userdata('user_id');
                if(isset($value['employee_id'])){
                    $insert[] = $value;
                }
            }
            //echo '<pre>';print_r($insert);exit;
            if(!empty($insert)){
                //echo 'reached heer';exit;
                $this->pktdblib->set_table('deliveryboy_order');
                
                $this->pktdblib->_insert_multiple($insert);
                //echo 'inserted';exit;
                $msg = array('message'=>'Invoice Mapped Successfully', 'class'=>'alert alert-success');
                $this->session->set_flashdata('message',$msg);
                return redirect('orders/assigndelivery');
            }

        }
        $data['meta_title'] = "Assign Delivery";
        $data['meta_description'] = "Assign Delivery";
        $data['title'] = "Module :: Orders";
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> Assign Delivery';
        $data['modules'][] = "orders";
        $data['methods'][] = "admin_assign_delivery_form";
        echo Modules::run("templates/admin_template", $data);
    }

    public function admin_assign_delivery_form($data = []){
        $sql = 'SELECT i.id, i.invoice_no, i.order_code, o.id as order_id,o.date, o.customer_id, concat(c.first_name," ", c.surname) as customer, o.shipping_address_id, o.amount_after_tax, ar.area_name  FROM `invoice_orders` i join orders o on o.order_code=i.order_code join customers c on c.id=o.customer_id join address a on a.id=o.shipping_address_id join areas ar on ar.id=a.area_id WHERE i.fiscal_yr like "'.$this->pktlib->get_fiscal_year().'" AND i.invoice_no not in (select invoice_no from deliveryboy_order where invoice_no is not NULL)';
        //echo $sql;
        //$sql.=' and i.invoice_no=22' ;
        $sql.=' order by i.invoice_no ASC,ar.area_name ASC';
        $invoice = $this->pktdblib->custom_query($sql);
        $invoiceArray = [];
        /*echo '<pre>';
        print_r($invoice);exit;*/
        $orderTotalQty = [];
        foreach ($invoice as $key => $order) {
            $ods = $this->pktdblib->custom_query('Select od.*, p.product, p.base_uom, p.base_price from order_details od inner join products p on p.id=od.product_id where od.is_active=true and od.order_id='.$order['order_id']);
            foreach ($ods as $odkey => $od) {
                if(!array_key_exists($order['invoice_no'], $orderTotalQty)){
                    $orderTotalQty[$order['invoice_no']]['qty'] = 0;
                }
                $variant = json_decode($od['variation'], true);
                $productAttribute = $this->pktdblib->custom_query('Select pa.*, a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$variant['attribute']['product_attribute_id'].'"');
                $od['base_uom'] = trim($od['base_uom']);
                $str="invoice no: ".$order['invoice_no']." ** order code: ".$order['order_code']." ** ";
                if($od['base_uom']==0 || empty($productAttribute)){
                    $orderTotalQty[$order['invoice_no']]['qty'] = $orderTotalQty[$order['invoice_no']]['qty']+$od['qty'];
                    $str.="Qty: ".$od['qty'];
                }else{
                    $baseuom = explode(" ", $od['base_uom']);
                    $conversion = $this->pktlib->unit_convertion(strtoupper($productAttribute[0]['uom']), strtoupper($baseuom[1]));
                    $afterConversion = $productAttribute[0]['unit']*$conversion;
                    $str.="Qty: ".$od['qty']." ** After Conversion:".$afterConversion.' ** ';
                    $orderTotalQty[$order['invoice_no']]['qty'] = $orderTotalQty[$order['invoice_no']]['qty']+($od['qty']*$afterConversion);
                }
                
                $str.='<br>';
                //echo $str;
                //$orderDetails[$order['order_code']][$od['product_id']]['variation'] = $od['variation'];
            }
            
            if(isset($invoiceArray[$order['area_name']][$order['invoice_no']]['amount_after_tax'])){
                $order['amount_after_tax'] = $invoiceArray[$order['area_name']][$order['invoice_no']]['amount_after_tax']+$order['amount_after_tax'];
            }
            $invoiceArray[$order['area_name']][$order['invoice_no']] = $order;
            if(array_key_exists('qty', $invoiceArray[$order['area_name']][$order['invoice_no']])){
                $invoiceArray[$order['area_name']][$order['invoice_no']]['qty'] = $invoiceArray[$order['area_name']][$order['invoice_no']]['qty']+$orderTotalQty[$order['invoice_no']]['qty'];
            }else{
                $invoiceArray[$order['area_name']][$order['invoice_no']]['qty'] = $orderTotalQty[$order['invoice_no']]['qty'];
            }
            
        }
        
        $data['invoiceArray'] = $invoiceArray;
        //echo '<pre>';print_r($invoiceArray);exit;
        $data['users'] = $this->pktdblib->custom_query('SELECT l.id, concat(l.first_name," ", l.surname) as name FROM `login` l join user_roles ur on ur.login_id=l.id join roles r on r.id=ur.role_id where ur.role_id in (6)');
        $this->load->view('orders/admin_assign_delivery', $data);
    }
    
    public function updateOrderIdWise($orderId, $status=1){
        //echo 'order id = '.$orderId.' status = '.$status.'<br>';
        $orderDetails = $this->pktdblib->custom_query('select * from order_details where order_id='.$orderId.' and is_active = true');
        //echo '<pre>';print_r($orderDetails);//exit;
        $result = [];
        if(count($orderDetails)>0){
            $amtbeforetax = 0;
            $amtaftertax = 0;
            foreach ($orderDetails as $key => $value) {
                $qty = $value['qty']-$value['return_quantity'];
                $amtbeforetax = $amtbeforetax+$qty*$value['unit_price'];
                $amtaftertax = $amtaftertax+$qty*$value['unit_price'] + (($value['tax']/100.00)*$qty*$value['unit_price']);
            }
            //echo 'amt before tax ='.$amtbeforetax.' amt after tax '.$amtaftertax;//exit;
            $orderArray['order_status_id'] = $status;
            $orderArray['amount_before_tax'] = $amtbeforetax;
            $orderArray['amount_after_tax'] = $amtaftertax;
            $orderArray['modified'] = date('Y-m-d H:i:s');
            //echo '<pre>';print_r($orderArray);exit;
            $this->pktdblib->set_table('orders');
            $updateOrder = $this->pktdblib->_update($orderId, $orderArray);
            //print_r($updateOrder);exit;
            return $updateOrder;
            
        }
    }

    public function getInvoiceWiseOrderId($invno){
        $sql = 'select o.id from deliveryboy_order dbo join invoice_orders inv on inv.invoice_no=dbo.invoice_no join orders o on o.order_code=inv.order_code where dbo.invoice_no = '.$invno;
        //echo $sql;exit;
        $orders = $this->pktdblib->custom_query($sql);
        //echo '<pre>';print_r($orders);exit;
        return $orders;
        
    }
    
    public function issue_material(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            /*print_r($this->input->post());
            exit;*/
            $postData = $this->input->post('daily_user_stocks');
            foreach($postData as $key=>$post){
                if($post['qty']>0){
                    $postData[$key]['created_by'] = $this->session->userdata('user_id');
                    $postData[$key]['created'] = date('Y-m-d H:i:s');
                    $postData[$key]['employee_id'] = $this->input->post('delivery_boy');
                }else{
                    unset($postData[$key]);
                }
            }
            
            $postData = array_values($postData);
            $this->pktdblib->set_table('daily_user_stocks');
    		$userStock = $this->pktdblib->_insert_multiple($postData);
    		if($userStock){
        		 echo json_encode(['status'=>'success', 'msg'=>'Material Issued Successfully']);
        		 exit;
    		}else{
    		    echo json_encode(['status'=>'fail', 'msg'=>'Some Error Occurred']);
        		 exit;
    		}
        }
        $empId = (NULL!==$this->input->get('employee_id'))?$this->input->get('employee_id'):0;
        $sql = 'SELECT sum(od.qty) as qty, od.product_id, od.variation, p.product, p.base_uom FROM `deliveryboy_order` dbo INNER JOIN invoice_orders inv on inv.invoice_no=`dbo`.invoice_no INNER JOIN orders o on o.order_code=inv.order_code INNER JOIN order_details od on od.order_id=o.id and od.is_active!=0 inner join products p on p.id=od.product_id WHERE dbo.employee_id='.$empId.' and dbo.order_status_id=9 GROUP BY od.product_id, od.variation';
        $productwiseOrder = $this->pktdblib->custom_query($sql);
        $orderTotalQty = [];
        foreach($productwiseOrder as $oKey=>$order){
            if(!array_key_exists($order['product_id'], $orderTotalQty)){
                    $orderTotalQty[$order['product_id']] = $order;
                    $orderTotalQty[$order['product_id']]['qty'] = 0;
                }
                $variant = json_decode($order['variation'], true);
                $productAttribute = $this->pktdblib->custom_query('Select pa.*, a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$variant['attribute']['product_attribute_id'].'"');
                $order['base_uom'] = trim($order['base_uom']);
                $str="product id: ".$order['product_id']." ** ";
                if($order['base_uom']==0 || empty($productAttribute)){
                    $orderTotalQty[$order['product_id']]['qty'] = $orderTotalQty[$order['product_id']]['qty']+$order['qty'];
                    $orderTotalQty[$order['product_id']]['uom'] = "Nos.";
                    $str.="Qty: ".$order['qty'];
                    
                }else{
                    $baseuom = explode(" ", $order['base_uom']);
                    $conversion = $this->pktlib->unit_convertion(strtoupper($productAttribute[0]['uom']), strtoupper($baseuom[1]));
                    
                    $afterConversion = $productAttribute[0]['unit']*$conversion;
                    $str.="Qty: ".$order['qty']." ** After Conversion:".$afterConversion.' ** ';
                    $orderTotalQty[$order['product_id']]['qty'] = $orderTotalQty[$order['product_id']]['qty']+($order['qty']*$afterConversion);
                    $orderTotalQty[$order['product_id']]['uom'] = strtoupper($baseuom[1]);
                }
                /*$productwiseOrder[$oKey]['qty'] = $orderTotalQty[$order['product_id']]['qty'];
                $productwiseOrder[$oKey]['uom'] = $orderTotalQty[$order['product_id']]['uom'];*/
                $str.='<br>';
        }
        /*echo '<pre>';
        print_r($orderTotalQty);
        exit;*/
        $oldStock = [];
        $inwardProduct = $this->pktdblib->custom_query('Select sum(qty) as total_inward, product_id from daily_user_stocks where employee_id='.$empId.' and type="in" and is_active=true group by product_id');
        //print_r($inwardProduct);exit;
        if($inwardProduct){
            foreach($inwardProduct as $inKey=>$inward){
                $oldStock[$inward['product_id']]['in'] = $inward['total_inward'];
            }
        }
        /*echo '<pre>';
        print_r($oldStock);
        exit;*/
        $outwardProduct = $this->pktdblib->custom_query('Select sum(qty) as total_outward, product_id from daily_user_stocks where employee_id='.$empId.' and type="out" and is_active=true group by product_id');
        if($outwardProduct){
            foreach($outwardProduct as $outKey=>$outward){
                $oldStock[$outward['product_id']]['out'] = $inward['total_outward'];
            }
        }
        $data['oldStock'] = $oldStock;
        $data['productwiseOrder'] = $orderTotalQty;
        $data['meta_title'] = "Issue Material";
        $data['meta_description'] = "Issue Material";
        $data['title'] = "Module :: Orders";
        $data['heading'] = '<i class="fa fa-plus-square margin-r-5"></i> Assign Delivery';
        $data['modules'][] = "orders";
        $data['methods'][] = "admin_issue_material_form";
        $data['empId'] = $empId;
        echo Modules::run("templates/admin_template", $data);
        
    }
    
    function admin_issue_material_form($data=[]){
        $deliveryBoys['options'] = [0=>'Select Delivery Boy'];
        $sql2 = 'select ur.login_id, concat(e.first_name," ",e.middle_name," ", e.surname," (", e.emp_code,")") as employee, e.is_active from employees e INNER JOIN user_roles ur on ur.user_id=e.id and ur.account_type="employees" and ur.role_id=6';
        //print_r($this->session->userdata('roles'));
        if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $sql2.=' and ur.login_id='.$this->session->userdata('user_id');
        }
        
        //echo $sql2;
		$employees = $this->pktdblib->custom_query($sql2);
		foreach($employees as $eKey=>$employee){
		    $active = ($employee['is_active'])?'Active':'Inactive';
            $deliveryBoys['options'][$employee['login_id']] = ucfirst($employee['employee'])." (".$active.")";
		    
		}
		$data['deliveryBoys'] = $deliveryBoys;
		
        $this->load->view('orders/issue_material_form', $data);
    }
    
    function check_invoice(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //print_r($this->input->post());
            //echo 'Select * from invoice_orders where invoice_no = '.trim($this->input->post('invoice')).' and fiscal_yr like "'.$this->input->post('fiscalyr').'"';
            $checkInvoice = $this->pktdblib->custom_query('Select * from invoice_orders where invoice_no = '.trim($this->input->post('invoice')).' and fiscal_yr like "'.$this->input->post('fiscalyr').'"');
            if($checkInvoice){
                echo json_encode(['status'=>'success', 'msg'=>'Invoice Already Exist in Database']);
                exit;
            }else{
                echo json_encode(['status'=>'fail', 'msg'=>'No Invoice Found']);
                exit;
            }
        }else{
            echo json_encode(['status'=>'fail', 'msg'=>'Invalid Request']);
            exit;
        }
    }
    
    function invoice_list(){
        if($this->input->is_ajax_request()){  
			 
			$postData = $this->input->post();
			$postData['condition'] = [];
			if(in_array(1, $this->session->userdata('roles')) || in_array(2, $this->session->userdata('roles'))){

			}else{
				$postData['condition']['dbo.employee_id'] = $this->session->userdata('user_id');
			}
			//echo "<pre>"; print_r($postData);echo "</pre>";exit;
			$data = $this->order_model->invoiceList($postData);
			//echo "<pre>"; print_r($data);exit;
			foreach($data['aaData'] as $key=>$v){
			    //$data['aaData'][$key]['invoice_no'] = anchor('#', $v['invoice_no'], ['class'=>'load-ajax', 'data-path'=>"orders/invoice_details/".$v['invoice_no'].'?fiscal_yr='.$v['fiscal_yr']])." ".anchor('#', '<i class="fa fa-print"></i>'); 
			    $data['aaData'][$key]['invoice_no'] = anchor('#', $v['invoice_no'], ['class'=>'load-ajax', 'data-path'=>"orders/invoice_details/".$v['invoice_no'].'?fiscal_yr='.$v['fiscal_yr']])." ".anchor('orders/print_invoice/'.base64_encode($v['company_id']."-".$v['bill_type']."-".$v['invoice_no']).'?fiscal_yr='.$v['fiscal_yr'], '<i class="fa fa-print"></i>'); 
			    $data['aaData'][$key]['amount'] = '<p style="text-align:right"><span class="pull-left">(AMT)</span>'.$v['amount_before_tax'].'</p>
			        <p style="text-align:right"><span class="pull-left">(TAX)</span>+'.number_format($v['amount_after_tax']-$v['amount_before_tax'],2).'</p>
			        <p style="text-align:right"><span class="pull-left">(SHP CHG)</span>+'.number_format($v['shipping_charge'],2).'</p><p style="text-align:right"><hr></p>
			        <p style="text-align:right"><span class="pull-left">(INV AMT)</span><b>'.number_format($v['amount_after_tax']+$v['shipping_charge'], 2).'</b></p>';
			        
			    $data['aaData'][$key]['status'] = '<b>'.$v['delivery_boy'].'</b><small class="fa '.$v['status_bg_color'].'">'.$data['aaData'][$key]['status'].'</small>';
				$action = '';
				if($v['status']=='Delivered'){
					$action = '<i class="fa fa-check alert-success"></i>';
				}elseif($v['status']=='Cancel'){
					$action = '<i class="fa fa-times alert-danger"></i>';
				/*}
				elseif($v['status']=='Assigned Delivery Boy'){
					$action = anchor('fieldmember_panel/view_OrderDetail/'.$v['id'], '<i class="fa fa-truck"></i> Update', ['class'=>'btn btn-success']);
				}*/
				}elseif($v['status']=='Assigned Delivery Boy'){
					$action = anchor('fieldmember_panel/view_OrderDetail2/'.$v['invoice_no'], '<i class="fa fa-truck"></i> Update', ['class'=>'btn btn-danger']);
				}
				//$amount = $this->getInvoiceWiseOrders($v['invoice_no']);
				$data['aaData'][$key]['action'] = $action;
				//$action.=$action;exit;
			}	
			echo json_encode($data);
			exit;
			 
		 }
		
        $data['meta_title'] = "ERP";
        
        $data['title'] = "ERP : Orders";
        $data['meta_description'] = "Order panel";
        
        $data['modules'][] = 'orders';
        $data['methods'][] = 'admin_invoice_listing';
        echo Modules::run("templates/admin_template", $data);
    }
    
    public function admin_invoice_listing(){
        //$data['content'] = "orders/admin_invoice_index";
        
        $data['formTitle'] = "Invoice";
        //$data['deliveryBoys'] = Modules::run("Fieldmember_panel/getDeliveryBoy");
        //print_r($data['deliveryBoys']);exit;
        $data['option']['status'] = [''=>'Select Status', '9'=>'Waiting To Be Delivered', '5'=>'Delivered', 2=>'Cancelled'];
        $this->load->view("orders/admin_invoice_index", $data);
    }
    
    function invoice_details($invno){
        //echo $invno;
        $fiscalYr = $this->pktlib->get_fiscal_year();
	    if(NULL!==$this->input->get('fiscal_yr')){
	        $fiscalYr = $this->input->get('fiscal_yr');
	    }
		$sql = 'select distinct inv.invoice_no, GROUP_CONCAT(DISTINCT CONCAT(inv.order_code) ORDER BY inv.created SEPARATOR "\",\"") as order_codes, inv.created, GROUP_CONCAT(DISTINCT CONCAT(o.id) ORDER BY o.created SEPARATOR "\",\"") as order_id from invoice_orders inv inner join orders o on o.order_code=inv.order_code where inv.invoice_no = '.$invno.' and inv.fiscal_yr like "'.$fiscalYr.'" and inv.is_active=true';
		//echo $sql;//exit;
		$invoice = $this->pktdblib->custom_query($sql);
		if(empty($invoice)){
		    echo 'Invalid Invoice Number';
		    exit;
		}
		echo '<pre>';print_r($invoice);exit;
		//$response = [];
		$orderCode = $orderId = [];
		foreach ($invoice as $key => $value) {
			$orderId[] = $value['order_id'];
			$orderCode[] = $value['order_code'];
		}

		$id = implode(",",$orderId);
		$data['order'] = $this->pktdblib->custom_query('select sum(o.amount_after_tax) as amount_after_tax, sum(o.amount_before_tax) as amount_before_tax, o.customer_id  from orders o  join customers c on c.id=o.customer_id where o.order_code in ("'.$id.'") and o.is_active!=0');
		//print_r($order);exit;
		$this->pktdblib->set_table('customers');
		$data['customer'] = $this->pktdblib->get_where($data['order'][0]['customer_id']);
		
		/*$sql2 = 'select od.id as order_detail_id, od.order_id, p.product, od.unit_price, od.qty, od.return_quantity, od.variation from order_details od join products p on p.id=od.product_id where od.order_id in ('.$id.') and od.is_active!=0';
		$orderDetail = $this->pktdblib->custom_query($sql2);
		$data['orderDetail'] = $orderDetail;*/
		$sql2 = 'Select od.*, p.product, p.base_uom, p.base_price, pi.image_name_1, b.brand from order_details od inner join products p on p.id=od.product_id left join brand_products bp on bp.product_id=p.id left join manufacturing_brands b on b.id=bp.brand_id inner join product_images pi on pi.product_id=p.id and pi.featured_image=true where od.is_active=true and od.order_id in ('.$id.')';
		$ods = $this->pktdblib->custom_query($sql2);
        foreach ($ods as $odkey => $od) {
            //print_r($od);
            $variant = json_decode($od['variation'], true);
            $productAttribute = $this->pktdblib->custom_query('Select pa.*, a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$variant['attribute']['product_attribute_id'].'"');
            $od['base_uom'] = trim($od['base_uom']);
            if($od['base_uom']==0 || empty($productAttribute)){
                $ods[$odkey]['qty'] = $od['qty'];
            }else{
                $baseuom = explode(" ", $od['base_uom']);
                $conversion = $this->pktlib->unit_convertion(strtoupper($productAttribute[0]['uom']), strtoupper($baseuom[1]));
                $qty = $od['qty']-$od['return_quantity'];
                $afterConversion = $productAttribute[0]['unit']*$conversion;
                /*$ods[$odkey]['qty'] = $od['qty']*$afterConversion;
                $ods[$odkey]['qty_count'] = $od['qty'];*/
                $ods[$odkey]['qty'] = $qty*$afterConversion;
                $ods[$odkey]['qty_count'] = $qty;
                $ods[$odkey]['uom'] = $productAttribute[0]['unit']." ".$productAttribute[0]['uom'];
            }
        }
        $data['orderDetail'] =$ods;
        /*echo '<pre>';
        print_r($data);
        exit;*/
        $sql = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state,s.gst_state_code, ct.city_name as city, ar.area_name as area, a.pincode, ba.bank_name, ba.account_type, ba.account_number, ba.ifsc_code, ba.branch 
		from companies c 
		inner join user_roles ur on ur.user_id=c.id and ur.account_type="companies" and ur.is_active=true 
		LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true 
		left join countries cn on cn.id=a.country_id 
		left join states s on s.id=a.state_id 
		left join cities ct on ct.id=a.city_id 
		left join areas ar on ar.id=a.area_id 
		left join bank_accounts ba on ba.user_id=ur.login_id and ba.is_default=true 
		where 1=1';
		if($this->session->userdata('application')['multiple_company']){
		    if($invoice[0]['company_id']!=0){
		        $sql.=' AND c.id='.$invoice[0]['company_id'];
		    }else{
		        $sql.=' AND c.id='.custom_constants::company_id;
		    }
		}else{
		    $sql.=' AND c.id='.custom_constants::company_id;
		}
		
		//echo $sql;
		$company = $this->pktdblib->custom_query($sql);
		$data['company'] = $company[0];
		$this->load->view("orders/invoice_view", $data);
    }
    
    function exportxml(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            /*echo '<pre>';
            print_r($this->input->post());
            exit;*/
            
            $postData['condition'] = [];
            $postData['from_invoice'] = $this->input->post('from_invoice');
			$postData['to_invoice'] = $this->input->post('to_invoice');
			$fileName = $postData['from_invoice']."-to-".$postData['to_invoice'].'.xml';
			//echo "<pre>"; print_r($postData);echo "</pre>";exit;
			//echo json_encode($this->order_model->exportInvoiceList($postData));
			$resultArray = json_decode(json_encode($this->order_model->exportInvoiceList($postData)), true);
			/*echo '<pre>';
			print_r($resultArray);exit;*/
			$customerIds = [];
			if($this->input->post('export')=='customer'){
    			foreach($resultArray as $cKey=>$customer){
    			    $customerIds[$cKey] = $customer['customer_id'];
    			}
    			$params['account'] = 'customers';
    			
    			$params['ids'] = $customerIds;
    			$params['requestType'] = 'export';
    			$customerxml = Modules::run('tally/export_ledgerxml', $params);
			}elseif($this->input->post('export')=='order'){
    			/*echo '<pre>';
    			print_r($resultArray);
    			exit;*/
    			$salesXml = '<ENVELOPE>
                  <HEADER>
                    <TALLYREQUEST>Import Data</TALLYREQUEST>
                  </HEADER>
                  <BODY>
                    <IMPORTDATA>
                      <REQUESTDESC>
                        <REPORTNAME>Vouchers</REPORTNAME>
                        <STATICVARIABLES>
                          <SVCURRENTCOMPANY>Expede Global - (21-22)</SVCURRENTCOMPANY>
                        </STATICVARIABLES>
                      </REQUESTDESC>
                      <REQUESTDATA>
                        <TALLYMESSAGE xmlns:UDF="TallyUDF">';
                        $gstType = 'Regular';
                        
                        foreach($resultArray as $invKey=>$invoice){
                            if(empty(trim($invoice['gst_no']))){
                                $gstType = 'Unregistered';   
                            }
                            $custCode = $invoice['customer_id'];
                          $salesXml.='<VOUCHER REMOTEID="" VCHKEY="" VCHTYPE="Sales Dry Fruit" ACTION="Create" OBJVIEW="Invoice Voucher View">
                            <ADDRESS.LIST TYPE="String"><ADDRESS>
                              '.str_replace(', ', '</ADDRESS><ADDRESS>', htmlspecialchars($invoice['delivery_address'], ENT_QUOTES)).'</ADDRESS>
                            </ADDRESS.LIST>
                            <BASICBUYERADDRESS.LIST TYPE="String">
                            <BASICBUYERADDRESS>
                              '.str_replace(', ', '</BASICBUYERADDRESS><BASICBUYERADDRESS>', htmlspecialchars($invoice['delivery_address'], ENT_QUOTES)).'</BASICBUYERADDRESS>
                              <BASICBUYERADDRESS></BASICBUYERADDRESS>
                            </BASICBUYERADDRESS.LIST>
                            <BASICORDERTERMS.LIST TYPE="String">
                              <BASICORDERTERMS>
                              </BASICORDERTERMS>
                            </BASICORDERTERMS.LIST>
                            <OLDAUDITENTRYIDS.LIST TYPE="Number">
                              <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                            </OLDAUDITENTRYIDS.LIST>
                            <DATE>'.date('Ymd', strtotime($invoice['created'])).'</DATE>
                            <GUID />
                            <STATENAME>'.$invoice['state_name'].'</STATENAME>
                            <GSTREGISTRATIONTYPE>'.$gstType.'</GSTREGISTRATIONTYPE>
                            <NARRATION>
                            </NARRATION>
                            <COUNTRYOFRESIDENCE>'.$invoice['country_name'].'</COUNTRYOFRESIDENCE>
                            <PARTYGSTIN>'.$invoice['gst_no'].'</PARTYGSTIN>
                            <TAXUNITNAME />
                            <PARTYNAME>'.htmlspecialchars(trim((empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name']))).' ('.$custCode.')</PARTYNAME>
                            <VOUCHERTYPENAME>Dry Fruit Sales</VOUCHERTYPENAME>
                            <REFERENCE>'.$invoice['order_code'].'</REFERENCE>
                            <VOUCHERNUMBER>D'.str_replace('-','',$invoice['fiscal_yr']).'-'.$invoice['invoice_no'].'</VOUCHERNUMBER>
                            <PARTYLEDGERNAME>'.htmlspecialchars(trim(empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name'])).' ('.$custCode.')</PARTYLEDGERNAME>
                            <BASICBASEPARTYNAME>'.htmlspecialchars(trim(empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name'])).' ('.$custCode.')</BASICBASEPARTYNAME>
                            <CSTFORMISSUETYPE />
                            <CSTFORMRECVTYPE />
                            <BUYERSCSTNUMBER>
                            </BUYERSCSTNUMBER>
                            <FBTPAYMENTTYPE />
                            <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
                            <PLACEOFSUPPLY>'.$invoice['state_name'].'</PLACEOFSUPPLY>
                            <CONSIGNEEGSTIN>'.$invoice['gst_no'].'</CONSIGNEEGSTIN>
                            <BASICSHIPPEDBY>
                            </BASICSHIPPEDBY>
                            <BASICBUYERNAME>'.htmlspecialchars(trim(empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name'])).' ('.$custCode.')</BASICBUYERNAME>
                            <BASICSHIPDOCUMENTNO>
                            </BASICSHIPDOCUMENTNO>
                            <BASICORDERREF>
                            </BASICORDERREF>
                            <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
                            <BILLOFLADINGNO>
                            </BILLOFLADINGNO>
                            <BILLOFLADINGDATE>
                            </BILLOFLADINGDATE>
                            <BASICSHIPVESSELNO>
                            </BASICSHIPVESSELNO>
                            <BASICFINALDESTINATION>
                            </BASICFINALDESTINATION>
                            <BASICBUYERSSALESTAXNO>
                            </BASICBUYERSSALESTAXNO>
                            <BASICDUEDATEOFPYMT>
                            </BASICDUEDATEOFPYMT>
                            <BASICDATETIMEOFINVOICE>'.date('d M y', strtotime($invoice['created'])).'</BASICDATETIMEOFINVOICE>
                            <BASICDATETIMEOFREMOVAL>'.date('d M y', strtotime($invoice['created'])).'</BASICDATETIMEOFREMOVAL>
                            <VCHGSTCLASS />
                            <CONSIGNEESTATENAME>
                            </CONSIGNEESTATENAME>
                            <COSTCENTRENAME>
                            </COSTCENTRENAME>
                            <DIFFACTUALQTY>No</DIFFACTUALQTY>
                            <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
                            <ASORIGINAL>No</ASORIGINAL>
                            <AUDITED>No</AUDITED>
                            <FORJOBCOSTING>No</FORJOBCOSTING>
                            <ISOPTIONAL>No</ISOPTIONAL>
                            <EFFECTIVEDATE>'.date('Ymd', strtotime($invoice['created'])).'</EFFECTIVEDATE>
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
                            <EXCISEOPENING>No</EXCISEOPENING>
                            <USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>
                            <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
                            <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
                            <ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>
                            <INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>
                            <ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>
                            <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
                            <IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>
                            <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
                            <ISISDVOUCHER>No</ISISDVOUCHER>
                            <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
                            <ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>
                            <ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>
                            <GSTNOTEXPORTED>No</GSTNOTEXPORTED>
                            <ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>
                            <ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>
                            <ISCANCELLED>No</ISCANCELLED>
                            <HASCASHFLOW>No</HASCASHFLOW>
                            <ISPOSTDATED>No</ISPOSTDATED>
                            <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
                            <ISINVOICE>Yes</ISINVOICE>
                            <MFGJOURNAL>No</MFGJOURNAL>
                            <HASDISCOUNTS>No</HASDISCOUNTS>
                            <ISCOSTCENTRE> No </ISCOSTCENTRE>
                            <ASPAYSLIP>No</ASPAYSLIP>
                            <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
                            <ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>
                            <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
                            <ISVOID>No</ISVOID>
                            <ISONHOLD>No</ISONHOLD>
                            <ORDERLINESTATUS>No</ORDERLINESTATUS>
                            <VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>
                            <VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>
                            <ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>
                            <VATISASSESABLECALCVCH>Yes</VATISASSESABLECALCVCH>
                            <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                            <ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>
                            <ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>
                            <ISDELETED>No</ISDELETED>
                            <CHANGEVCHMODE>No</CHANGEVCHMODE>
                            <ALTERID />
                            <MASTERID />
                            <VOUCHERKEY />
                            <EXCLUDEDTAXATIONS.LIST />
                            <OLDAUDITENTRIES.LIST />
                            <ACCOUNTAUDITENTRIES.LIST />
                            <AUDITENTRIES.LIST />
                            <DUTYHEADDETAILS.LIST />
                            <SUPPLEMENTARYDUTYHEADDETAILS.LIST />
                            <INVOICEDELNOTES.LIST>
                              <BASICSHIPPINGDATE>
                              </BASICSHIPPINGDATE>
                              <BASICSHIPDELIVERYNOTE>
                              </BASICSHIPDELIVERYNOTE>
                            </INVOICEDELNOTES.LIST>
                            <INVOICEINDENTLIST.LIST />
                            <ATTENDANCEENTRIES.LIST />
                            <ORIGINVOICEDETAILS.LIST />
                            <INVOICEEXPORTLIST.LIST />
                            <INVOICEORDERLIST.LIST>
                              <BASICORDERDATE>
                              </BASICORDERDATE>
                              <BASICPURCHASEORDERNO>
                              </BASICPURCHASEORDERNO>
                            </INVOICEORDERLIST.LIST>
                            <LEDGERENTRIES.LIST>
                              <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                              </OLDAUDITENTRYIDS.LIST>
                              <LEDGERNAME>'.htmlspecialchars(trim(empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name'])).'('.$custCode.')</LEDGERNAME>
                              <GSTCLASS />
                              <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                              <LEDGERFROMITEM>No</LEDGERFROMITEM>
                              <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                              <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                              <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
                              <AMOUNT> -'.$invoice['amount_after_tax'].'</AMOUNT>
                              <SERVICETAXDETAILS.LIST />
                              <BANKALLOCATIONS.LIST />
                              <BILLALLOCATIONS.LIST>
                                <NAME>order no D'.str_replace('-','',$invoice['fiscal_yr']).'-'.$invoice['invoice_no'].' </NAME>
                                <BILLTYPE>New Ref</BILLTYPE>
                                <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                                <AMOUNT> -'.$invoice['amount_after_tax'].'</AMOUNT>
                                <INTERESTCOLLECTION.LIST />
                                <STBILLCATEGORIES.LIST />
                              </BILLALLOCATIONS.LIST>
                              <INTERESTCOLLECTION.LIST />
                              <OLDAUDITENTRIES.LIST />
                              <ACCOUNTAUDITENTRIES.LIST />
                              <AUDITENTRIES.LIST />
                              <INPUTCRALLOCS.LIST />
                              <DUTYHEADDETAILS.LIST />
                              <EXCISEDUTYHEADDETAILS.LIST />
                              <RATEDETAILS.LIST />
                              <SUMMARYALLOCS.LIST />
                              <STPYMTDETAILS.LIST />
                              <EXCISEPAYMENTALLOCATIONS.LIST />
                              <TAXBILLALLOCATIONS.LIST />
                              <TAXOBJECTALLOCATIONS.LIST />
                              <TDSEXPENSEALLOCATIONS.LIST />
                              <VATSTATUTORYDETAILS.LIST />
                              <COSTTRACKALLOCATIONS.LIST />
                              <REFVOUCHERDETAILS.LIST />
                              <INVOICEWISEDETAILS.LIST />
                              <VATITCDETAILS.LIST />
                              <ADVANCETAXDETAILS.LIST />
                            </LEDGERENTRIES.LIST>
                            ';
                            //echo 'Select od.* from order_details od where od.order_id in ('.$invoice['order_ids'].') AND od.is_active=true<br>';
                            $sgst = 0;
                            $cgst = 0;
                            $igst = 0;
                            $gst = 0;
                            $finalAmt = 0;
                            $orderedProducts = $this->pktdblib->custom_query('Select od.*, p.product, p.tally_name, p.base_uom, p.base_price from order_details od inner join products p on p.id=od.product_id where od.order_id in ('.$invoice['order_ids'].') AND od.is_active=true');
                            //print_r($orderedProducts);
                            if(count($orderedProducts)>0){
                                foreach($orderedProducts as $odKey=>$detail){
                                    $gcalc = 0;
                                    $spec = json_decode($detail['variation'], true);
                                    //print_r($spec);exit;
                                    $str = '';
                                    $bill['qty'] = 0;
                                    $bill['rate'] = 0;
                                    $baseUOM = explode(" ", $detail['base_uom']);
                                    $attribute = $this->pktdblib->custom_query('select a.unit, a.uom, concat(a.unit," ",a.uom) as attribute, pa.per_unit_margin from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$spec['attribute']['product_attribute_id'].'"');
                                    if(count($attribute)>0){
                                        $requireUOM = explode(" ", $attribute[0]['attribute']);
                                        
                                        $unit = $this->pktlib->unit_convertion($attribute[0]['uom'], $baseUOM[1]);
                                        $qt = $requireUOM[0]*$unit*($detail['qty']-$detail['return_quantity']);
                                        $rateperkg = $detail['unit_price']/($requireUOM[0]*$unit);
                                        //$str.=$detail['product']." (".$attribute[0]['unit']." ".$attribute[0]['uom'].") pcs:".($detail['qty']-$detail['return_quantity'])." rate/pc:".$detail['unit_price'];
                                        //echo ' QTY:'.$qt." ".$baseUOM[1]."  RATE PER".$baseUOM[1].":".$rateperkg."<br>";
                                        $bill['qty'] = $qt;
                                        $bill['rate'] = $rateperkg;
                                    }else{
                                        $bill['qty'] = ($detail['qty']-$detail['return_quantity']);
                                        $bill['rate'] = $detail['unit_price'];
                                        //$str.=$detail['product']." pcs:".($detail['qty']-$detail['return_quantity'])." rate/pc:".$detail['unit_price']."\n";
                                    }
                                    //echo $bill['qty']." ".$bill['rate'];exit;
                                    $gstSql = 'Select * from product_gst_rates where product_id='.$detail['product_id'].' and effective_from<="'.$invoice['created'].'" and (effective_till is null or effective_till>="'.$invoice['created'].'") and is_active=true';
                                    $gstRates = $this->pktdblib->custom_query($gstSql);
                                    //print_r($gstRates);
                                    if(count($gstRates)>0){
                                        //$gst = $gst+($gstRates[0]['gst_rate']/100)*$bill['qty']*$bill['rate'];
                                        $gcalc2 = round($bill['rate']*$gstRates[0]['gst_rate']/(100+$gstRates[0]['gst_rate']), 2);
                                        $gcalc = $bill['qty']*$gcalc2;
                                        $bill['perKgRateWithoutGst'] = $bill['rate']-round($bill['rate']*$gstRates[0]['gst_rate']/(100+$gstRates[0]['gst_rate']), 2);
                                        //$bill['rate']*$bill['qty']*100/(100+$gstRates[0]['gst_rate']);
                                        $gst = $gst+$gcalc;
                                        
                                    }
                                    //echo $bill['rate']." ".$gcalc." ".$bill['qty']."<br>";
                                    
                                    $bill['rate'] = round($bill['rate'], 2);
                                    $wgstValue2 = round($bill['rate']*100/(100+$gstRates[0]['gst_rate']),2);
                                    $wgstValue = round($bill['qty']*$wgstValue2, 2);
                                    $gstValue = round(($bill['rate'])*$bill['qty'], 2);
                                    $finalAmt = $finalAmt+($wgstValue);
                                    //echo $bill['perKgRateWithoutGst']." ".$detail['product']." including price: ".$gstValue." gcalc: ".$gcalc." ".$bill['qty']." gst rate: ".$gstRates[0]['gst_rate']." excluding price:".$wgstValue."<br><br>";
                                    //echo $wgstValue.'<br>';//exit;
                                    $salesXml.='<ALLINVENTORYENTRIES.LIST>
                                      <BASICUSERDESCRIPTION.LIST TYPE="String">
                                        <BASICUSERDESCRIPTION>
                                        </BASICUSERDESCRIPTION>
                                      </BASICUSERDESCRIPTION.LIST>
                                      <STOCKITEMNAME>'.(empty($detail['tally_name'])?$detail['product']:$detail['tally_name']).'</STOCKITEMNAME>
                                      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                      <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                      <ISAUTONEGATE>No</ISAUTONEGATE>
                                      <ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
                                      <ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
                                      <ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
                                      <ISPRIMARYITEM>No</ISPRIMARYITEM>
                                      <ISSCRAP>No</ISSCRAP>
                                      <RATE>'.$bill['perKgRateWithoutGst'].'/'.$baseUOM[1].'</RATE>
                                      <DISCOUNT>
                                      </DISCOUNT>
                                      <AMOUNT>'.$wgstValue.'</AMOUNT>
                                      <VATASSBLVALUE>'.$wgstValue.'</VATASSBLVALUE>
                                      <ACTUALQTY>'.$bill['qty'].'</ACTUALQTY>
                                      <BILLEDQTY>'.$bill['qty'].'</BILLEDQTY>
                                      <BATCHALLOCATIONS.LIST>
                                        <MFDON>
                                        </MFDON>
                                        <GODOWNNAME>Vasai - Godown</GODOWNNAME>
                                        <BATCHNAME> Primary Batch</BATCHNAME>
                                        <DESTINATIONGODOWNNAME>Vasai - Godown</DESTINATIONGODOWNNAME>
                                        <INDENTNO />
                                        <ORDERNO />
                                        <TRACKINGNUMBER />
                                        <DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED>
                                        <AMOUNT>'.$wgstValue.'</AMOUNT>
                                        <ACTUALQTY>'.$bill['qty'].' '.$baseUOM[1].'</ACTUALQTY>
                                        <BILLEDQTY>'.$bill['qty'].' '.$baseUOM[1].'</BILLEDQTY>
                                        <ADDITIONALDETAILS.LIST />
                                        <VOUCHERCOMPONENTLIST.LIST />
                                      </BATCHALLOCATIONS.LIST>
                                      <ACCOUNTINGALLOCATIONS.LIST>
                                        <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                          <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                        </OLDAUDITENTRYIDS.LIST>
                                        <LEDGERNAME>Sale - Vasai</LEDGERNAME>
                                        <GSTCLASS />
                                        <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                        <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                        <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                        <ISPARTYLEDGER>No</ISPARTYLEDGER>
                                        <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                        <AMOUNT>'.$wgstValue.'</AMOUNT>
                                        <SERVICETAXDETAILS.LIST />
                                        <BANKALLOCATIONS.LIST />
                                        <BILLALLOCATIONS.LIST />
                                        <INTERESTCOLLECTION.LIST />
                                        <OLDAUDITENTRIES.LIST />
                                        <ACCOUNTAUDITENTRIES.LIST />
                                        <AUDITENTRIES.LIST />
                                        <INPUTCRALLOCS.LIST />
                                        <DUTYHEADDETAILS.LIST />
                                        <EXCISEDUTYHEADDETAILS.LIST />
                                        <RATEDETAILS.LIST />
                                        <SUMMARYALLOCS.LIST />
                                        <STPYMTDETAILS.LIST />
                                        <EXCISEPAYMENTALLOCATIONS.LIST />
                                        <TAXBILLALLOCATIONS.LIST />
                                        <TAXOBJECTALLOCATIONS.LIST />
                                        <TDSEXPENSEALLOCATIONS.LIST />
                                        <VATSTATUTORYDETAILS.LIST />
                                        <COSTTRACKALLOCATIONS.LIST />
                                        <REFVOUCHERDETAILS.LIST />
                                        <INVOICEWISEDETAILS.LIST />
                                        <VATITCDETAILS.LIST />
                                        <ADVANCETAXDETAILS.LIST />
                                      </ACCOUNTINGALLOCATIONS.LIST>
                                      <DUTYHEADDETAILS.LIST />
                                      <SUPPLEMENTARYDUTYHEADDETAILS.LIST />
                                      <TAXOBJECTALLOCATIONS.LIST />
                                      <REFVOUCHERDETAILS.LIST />
                                      <EXCISEALLOCATIONS.LIST />
                                      <EXPENSEALLOCATIONS.LIST />
                                    </ALLINVENTORYENTRIES.LIST>';
                                    
                                }
                            }
                            //$finalAmt = number_format($finalAmt, 2, '.', '');
                            $remainder = 0;
                            //echo "gst=".$gst;
                            $decimal = explode('.', $gst);
                            if(count($decimal)>1){
                                $remainder = ($decimal[1] % 2);
                            }
                            //$sgst = $cgst = $gst/2;
                            if($remainder==0){
                            $sgst = $cgst = $gst/2;
                            //echo "even decimal";
                            }
                            else{
                                //echo "odd decimal";
                                $sgst = $cgst = ($gst+0.01)/2;
                            }
                            
                            $sgst = round($sgst, 2);
                            $cgst = round($cgst, 2);
                            //echo " sgst: ".$sgst." cgst: ".$cgst;//exit;
                            //echo "amount withoutgst:".$finalAmt." gst amount:".$gst;
                            $finalAmt = round($finalAmt+$cgst+$sgst , 2);
                            //echo " finalamt with gst:".$finalAmt;
                            $roundOff = round($invoice['amount_after_tax']-$finalAmt, 2);
                            //echo "gst: ".$gst." finalamt: ".$finalAmt." roundoff: ".$roundOff;
                            $salesXml.='
                            <LEDGERENTRIES.LIST>
                              <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                              </OLDAUDITENTRYIDS.LIST>
                              <BASICRATEOFINVOICETAX.LIST TYPE="Number">
                                <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
                              </BASICRATEOFINVOICETAX.LIST>
                              <ROUNDTYPE />
                              <LEDGERNAME>CGST</LEDGERNAME>
                              <GSTCLASS />
                              <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                              <LEDGERFROMITEM>No</LEDGERFROMITEM>
                              <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                              <ISPARTYLEDGER>No</ISPARTYLEDGER>
                              <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                              <AMOUNT> '.$sgst.'</AMOUNT>
                              <VATEXPAMOUNT>'.$cgst.'</VATEXPAMOUNT>
                              <SERVICETAXDETAILS.LIST />
                              <BANKALLOCATIONS.LIST />
                              <SERVICETAXDETAILS.LIST>
                              </SERVICETAXDETAILS.LIST>
                              <CATEGORYALLOCATIONS.LIST>
                              </CATEGORYALLOCATIONS.LIST>
                              <BANKALLOCATIONS.LIST>
                              </BANKALLOCATIONS.LIST>
                              <BILLALLOCATIONS.LIST>
                              </BILLALLOCATIONS.LIST>
                              <INTERESTCOLLECTION.LIST>
                              </INTERESTCOLLECTION.LIST>
                              <OLDAUDITENTRIES.LIST>
                              </OLDAUDITENTRIES.LIST>
                              <ACCOUNTAUDITENTRIES.LIST>
                              </ACCOUNTAUDITENTRIES.LIST>
                              <AUDITENTRIES.LIST>
                              </AUDITENTRIES.LIST>
                              <INPUTCRALLOCS.LIST>
                              </INPUTCRALLOCS.LIST>
                              <DUTYHEADDETAILS.LIST>
                              </DUTYHEADDETAILS.LIST>
                              <EXCISEDUTYHEADDETAILS.LIST>
                              </EXCISEDUTYHEADDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Integrated Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Central Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>State Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Cess</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <SUMMARYALLOCS.LIST>
                              </SUMMARYALLOCS.LIST>
                              <STPYMTDETAILS.LIST>
                              </STPYMTDETAILS.LIST>
                              <EXCISEPAYMENTALLOCATIONS.LIST>
                              </EXCISEPAYMENTALLOCATIONS.LIST>
                              <TAXBILLALLOCATIONS.LIST>
                              </TAXBILLALLOCATIONS.LIST>
                              <TAXOBJECTALLOCATIONS.LIST>
                              </TAXOBJECTALLOCATIONS.LIST>
                              <TDSEXPENSEALLOCATIONS.LIST>
                              </TDSEXPENSEALLOCATIONS.LIST>
                              <VATSTATUTORYDETAILS.LIST>
                              </VATSTATUTORYDETAILS.LIST>
                              <COSTTRACKALLOCATIONS.LIST>
                              </COSTTRACKALLOCATIONS.LIST>
                              <REFVOUCHERDETAILS.LIST>
                              </REFVOUCHERDETAILS.LIST>
                              <INVOICEWISEDETAILS.LIST>
                              </INVOICEWISEDETAILS.LIST>
                              <VATITCDETAILS.LIST>
                              </VATITCDETAILS.LIST>
                              <ADVANCETAXDETAILS.LIST>
                              </ADVANCETAXDETAILS.LIST>
                            </LEDGERENTRIES.LIST>
                            <LEDGERENTRIES.LIST>
                              <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                              </OLDAUDITENTRYIDS.LIST>
                              <BASICRATEOFINVOICETAX.LIST TYPE="Number">
                                <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
                              </BASICRATEOFINVOICETAX.LIST>
                              <ROUNDTYPE />
                              <LEDGERNAME>SGST</LEDGERNAME>
                              <GSTCLASS />
                              <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                              <LEDGERFROMITEM>No</LEDGERFROMITEM>
                              <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                              <ISPARTYLEDGER>No</ISPARTYLEDGER>
                              <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                              <AMOUNT>'.round($sgst, 2).'</AMOUNT>
                              <VATEXPAMOUNT>'.round($sgst, 2).'</VATEXPAMOUNT>
                              <SERVICETAXDETAILS.LIST />
                              <BANKALLOCATIONS.LIST />
                              <SERVICETAXDETAILS.LIST>
                              </SERVICETAXDETAILS.LIST>
                              <CATEGORYALLOCATIONS.LIST>
                              </CATEGORYALLOCATIONS.LIST>
                              <BANKALLOCATIONS.LIST>
                              </BANKALLOCATIONS.LIST>
                              <BILLALLOCATIONS.LIST>
                              </BILLALLOCATIONS.LIST>
                              <INTERESTCOLLECTION.LIST>
                              </INTERESTCOLLECTION.LIST>
                              <OLDAUDITENTRIES.LIST>
                              </OLDAUDITENTRIES.LIST>
                              <ACCOUNTAUDITENTRIES.LIST>
                              </ACCOUNTAUDITENTRIES.LIST>
                              <AUDITENTRIES.LIST>
                              </AUDITENTRIES.LIST>
                              <INPUTCRALLOCS.LIST>
                              </INPUTCRALLOCS.LIST>
                              <DUTYHEADDETAILS.LIST>
                              </DUTYHEADDETAILS.LIST>
                              <EXCISEDUTYHEADDETAILS.LIST>
                              </EXCISEDUTYHEADDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Integrated Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Central Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>State Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Cess</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <SUMMARYALLOCS.LIST>
                              </SUMMARYALLOCS.LIST>
                              <STPYMTDETAILS.LIST>
                              </STPYMTDETAILS.LIST>
                              <EXCISEPAYMENTALLOCATIONS.LIST>
                              </EXCISEPAYMENTALLOCATIONS.LIST>
                              <TAXBILLALLOCATIONS.LIST>
                              </TAXBILLALLOCATIONS.LIST>
                              <TAXOBJECTALLOCATIONS.LIST>
                              </TAXOBJECTALLOCATIONS.LIST>
                              <TDSEXPENSEALLOCATIONS.LIST>
                              </TDSEXPENSEALLOCATIONS.LIST>
                              <VATSTATUTORYDETAILS.LIST>
                              </VATSTATUTORYDETAILS.LIST>
                              <COSTTRACKALLOCATIONS.LIST>
                              </COSTTRACKALLOCATIONS.LIST>
                              <REFVOUCHERDETAILS.LIST>
                              </REFVOUCHERDETAILS.LIST>
                              <INVOICEWISEDETAILS.LIST>
                              </INVOICEWISEDETAILS.LIST>
                              <VATITCDETAILS.LIST>
                              </VATITCDETAILS.LIST>
                              <ADVANCETAXDETAILS.LIST>
                              </ADVANCETAXDETAILS.LIST>
                            </LEDGERENTRIES.LIST>
                            <PAYROLLMODEOFPAYMENT.LIST />
                            <ATTDRECORDS.LIST />
                            <TEMPGSTRATEDETAILS.LIST />
                          ';
                          //echo "software total:".$invoice['amount_after_tax']." calculated total: ".$finalAmt.'<br>';
                          //echo ($invoice['amount_after_tax']-$finalAmt);
                          //echo $roundOff;exit;
                          if($roundOff>0 || $roundOff<0){
                          $salesXml.='<LEDGERENTRIES.LIST>
                               <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                               </OLDAUDITENTRYIDS.LIST>
                               <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                               <LEDGERNAME>Round Off</LEDGERNAME>
                               <GSTCLASS/>
                               <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                               <LEDGERFROMITEM>No</LEDGERFROMITEM>
                               <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                               <ISPARTYLEDGER>No</ISPARTYLEDGER>
                               <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                               <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                               <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                               <ROUNDLIMIT> 1</ROUNDLIMIT>
                               <AMOUNT>'.number_format(($invoice['amount_after_tax']-$finalAmt), 2).'</AMOUNT>
                               <VATEXPAMOUNT>'.number_format(($invoice['amount_after_tax']-$finalAmt), 2).'</VATEXPAMOUNT>
                               <INVOICEROUNDINGDIFFVAL>'.number_format(($invoice['amount_after_tax']-$finalAmt), 2).'</INVOICEROUNDINGDIFFVAL>
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
                          ';
                          }
                          $salesXml.='</VOUCHER>';
                        }
                        $salesXml.='</TALLYMESSAGE>
                        <TALLYMESSAGE xmlns:UDF="TallyUDF">
                         <COMPANY>
                          <REMOTECMPINFO.LIST MERGE="Yes">
                           <NAME>508ae971-4004-433e-8332-aa88752545a2</NAME>
                           <REMOTECMPNAME>Expede Global - (21-22)</REMOTECMPNAME>
                           <REMOTECMPSTATE>Maharashtra</REMOTECMPSTATE>
                          </REMOTECMPINFO.LIST>
                         </COMPANY>
                        </TALLYMESSAGE>
                        <TALLYMESSAGE xmlns:UDF="TallyUDF">
                         <COMPANY>
                          <REMOTECMPINFO.LIST MERGE="Yes">
                           <NAME>508ae971-4004-433e-8332-aa88752545a2</NAME>
                           <REMOTECMPNAME>Expede Global - (21-22)</REMOTECMPNAME>
                           <REMOTECMPSTATE>Maharashtra</REMOTECMPSTATE>
                          </REMOTECMPINFO.LIST>
                         </COMPANY>
                        </TALLYMESSAGE>
                      </REQUESTDATA>
                    </IMPORTDATA>
                  </BODY>
                </ENVELOPE>';
    			//exit;
    			/*echo '<pre>';
    			echo htmlspecialchars($salesXml);exit;*/
    			$ledgerContent = file_put_contents($fileName, $salesXml);
                force_download($fileName, NULL);
                
			}
        }
        
        $data['meta_title'] = "ERP";
        
        $data['title'] = "ERP : Orders";
        $data['meta_description'] = "Order panel";
        
        $data['modules'][] = "orders";
        $data['methods'][] = "export_form";
        
        echo Modules::run("templates/admin_template", $data);
    }
    
    function export_form($data=[]){
        $data['heading'] = '<i class="fa fa-download margin-r-5"></i> Export Order';
        $this->load->view("orders/export_form", $data);
    }
    
    function generatenmapinvoice(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            /*echo '<pre>';
            print_r($this->input->post());*/
            $currentFiscalYr = $this->pktlib->get_fiscal_year();
            $insertData = [];
            $data['last_invoice'] = $this->pktdblib->custom_query('Select max(invoice_no) as invoice_no from invoice_orders where fiscal_yr like "'.$currentFiscalYr.'"');
            foreach($this->input->post('order_code') as $key=>$orderCode){
                $insertData[$key]['order_code'] = $orderCode;
                $insertData[$key]['invoice_no'] = $data['last_invoice'][0]['invoice_no']+1;
                $insertData[$key]['fiscal_yr'] = $currentFiscalYr;
                $insertData[$key]['created'] = date('Y-m-d H:i:s');
                $insertData[$key]['created_by'] = $this->session->userdata('user_id');
            }
            
            if(!empty($insertData)){
        		$this->pktdblib->set_table('invoice_orders');
        		$insert = $this->pktdblib->_insert_multiple($insertData);
        		
        		if($insert){
        		    echo json_encode(['status'=>'success', 'invoice_no'=>$data['last_invoice'][0]['invoice_no']+1]);
        		}else{
        		    echo json_encode(['status'=>'fail']);
        		}
    		}else{
    		    echo json_encode(['status'=>'fail']);
    		}
        }
    }
    
    public function collection_report(){
        if($this->input->is_ajax_request()){  
			 
			$postData = $this->input->post();
			$postData['condition'] = [];
			if(in_array(1, $this->session->userdata('roles')) || in_array(2, $this->session->userdata('roles'))){

			}else{
				$postData['condition']['dbo.employee_id'] = $this->session->userdata('user_id');
			}
			//echo "<pre>"; print_r($postData);echo "</pre>";exit;
			$data = $this->order_model->invoiceList($postData);
			//echo "<pre>"; print_r($data);exit;
            //echo '<pre>'print_r($v);exit;;
			foreach($data['aaData'] as $key=>$v){
			    
			}	
			echo json_encode($data);
			exit;
			 
		 }
        
        $data['meta_title'] = "Collection Report";
        
        $data['title'] = "Collection Report : Payment Method";
        $data['meta_description'] = "Payment Method panel";
        
        $data['modules'][] = 'orders';
        $data['methods'][] = 'collection_report_view';
        echo Modules::run("templates/admin_template", $data);
    }
    
    public function collection_report_view(){
        //echo "hii";exit;
         $data['formTitle'] = "Invoice";
        $data['option']['status'] = [''=>'Select Status', '9'=>'Waiting To Be Delivered', '5'=>'Delivered', 2=>'Cancelled'];
        $data['paymentModes'] = $this->pktdblib->custom_query('SELECT DISTINCT payment_mode FROM `order_payments` ORDER BY payment_mode');
        
        $this->load->view("orders/collection_report", $data);
    }
    
    public function print_invoice($inv){
        $companyId=1; $billType = 1;
        $invoiceId = 0;
        $inv = base64_decode($inv);
        //print_r($inv);
        $sp = explode('-',$inv);
        //print_r($sp);
        //echo count($sp);exit;
        if(count($sp)==3){ 
            $companyId = $sp[0];
            $billType = $sp[1];
            $invoiceId = $sp[2];
        }
        //echo $invoiceId;
        $invSql = 'select o.*, inv.invoice_no from orders o inner join invoice_orders inv on inv.order_code=o.order_code where inv.invoice_no='.$invoiceId.' AND inv.company_id='.$companyId.' AND bill_type='.$billType;
        //echo $invSql;
        $order = $this->pktdblib->custom_query($invSql);
		$data['order'] = $order[0];
		$data['bill_type'] = $billType;
		$sql = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state,s.gst_state_code, ct.city_name as city, ar.area_name as area, a.pincode, ba.bank_name, ba.account_type, ba.account_number, ba.ifsc_code, ba.branch 
		from companies c 
		inner join user_roles ur on ur.user_id=c.id and ur.account_type="companies" and ur.is_active=true 
		LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true 
		left join countries cn on cn.id=a.country_id 
		left join states s on s.id=a.state_id 
		left join cities ct on ct.id=a.city_id 
		left join areas ar on ar.id=a.area_id 
		left join bank_accounts ba on ba.user_id=ur.login_id and ba.is_default=true 
		where 1=1';
		if($this->session->userdata('application')['multiple_company']){
		    if($data['order']['company_id']!=0){
		        $sql.=' AND c.id='.$data['order']['company_id'];
		    }else{
		        $sql.=' AND c.id='.$companyId;
		    }
		}else{
		    $sql.=' AND c.id='.$companyId;
		}
		
		//echo $sql;
		$company = $this->pktdblib->custom_query($sql);
		//print_r($company);exit;
		$data['company'] = $company[0];
        $sqlBank = 'SELECT * FROM `user_roles` ur INNER JOIN bank_accounts b on b.user_id=ur.login_id and b.user_type LIKE "login" WHERE ur.account_type="companies" AND b.is_active=true';
		//$sqlBank.= ' where 1=1';
		if($this->session->userdata('application')['multiple_company']){
		    if($data['order']['company_id']!=0){
		        $sqlBank.=' AND ur.user_id='.$data['order']['company_id'];
		    }else{
		        $sqlBank.=' AND ur.user_id='.$companyId;
		    }
		}else{
		    $sqlBank.=' AND ur.user_id='.$companyId;
		}
		//echo $sqlBank;
        $data['banks'] = $this->pktdblib->custom_query($sqlBank);
        //print_r($banks);exit;
		/*$sql2 = 'Select c.*, a.address_1, a.address_2, cn.name as country, s.state_name as state, ct.city_name as city, ar.area_name as area, a.pincode from customers c left join user_roles ur on ur.user_id=c.id and ur.account_type="customers" and ur.is_active=true LEFT JOIN address a on a.user_id=ur.login_id and a.is_active=true and a.is_default=true left join countries cn on cn.id=a.country_id left join states s on s.id=a.state_id left join cities ct on ct.id=a.city_id left join areas ar on ar.id=a.area_id where c.id='.$data['order']['customer_id'];*/
		$sql2 = 'Select c.* from customers c  where c.id='.$data['order']['customer_id'];
		//echo $sql2; //exit;
		$customer = $this->pktdblib->custom_query($sql2);
		//print_r($customer);exit;
		$data['customer'] = $customer[0];
		$sql3 = 'Select a.address_1, a.address_2, cn.name as country, s.state_name as state,s.gst_state_code, ct.city_name as city, ar.area_name as area, a.pincode from address a inner join countries cn on cn.id=a.country_id inner join states s on s.id=a.state_id inner join cities ct on ct.id=a.city_id inner join areas ar on ar.id=a.area_id where a.id='.$data['order']['shipping_address_id'];
        $address = $this->pktdblib->custom_query($sql3);
        /*echo '<pre>';
        print_r($address);
        exit;*/
        $data['customer'] = array_merge($data['customer'],$address[0]);
        $detQuery = 'select b.brand, products.product_type, products.product, order_details.*, products.base_uom, pgr.gst_rate, pgr.hsn_sac_code';
        if(isset($this->session->userdata('application')['product_has_multiple_models']) && $this->session->userdata('application')['product_has_multiple_models']){
            $detQuery.=', pa.model';
        }
        if(isset($this->session->userdata('application')['enable_purchase']) && $this->session->userdata('application')['enable_purchase']){
            $detQuery.=', sd.mrp';
        }
        
        $detQuery.=' from order_details 
            inner join products on products.id=order_details.product_id 
            inner join product_attributes pa on pa.id=order_details.product_attribute_id left 
            join brand_products bp on bp.product_id=products.id 
            inner join manufacturing_brands b on b.id=bp.brand_id
            LEFT JOIN product_gst_rates pgr on pgr.product_id=products.id and pgr.effective_till is null and pgr.is_active=true';
            
        if(isset($this->session->userdata('application')['enable_purchase']) && $this->session->userdata('application')['enable_purchase']){
            $detQuery.=' left join stock_logs sl on sl.order_detail_id=order_details.id left join stock_details sd on sd.id=sl.stock_detail_id';
        }
        
        $detQuery.=' where order_details.order_id='.$data['order']['id'];
		//echo $detQuery;
		$data['orderDetails'] = $this->pktdblib->custom_query($detQuery);
		/*echo '<pre>';
		print_r($data['orderDetails']);
		exit;*/
		$arr['responsetype'] = 'web';
		$data['attribute'] = Modules::run('products/ordered_product_attribute_list', $arr);
		
		$jobWorkQuery = 'select b.brand, products.product_type, products.product, order_details.*, products.base_uom';
		if(isset($this->session->userdata('application')['product_has_multiple_models']) && $this->session->userdata('application')['product_has_multiple_models']){
		    $jobWorkQuery.= ', pa.model';
		}
		$jobWorkQuery.= ' from order_details inner join products on products.id=order_details.product_id inner join product_attributes pa on pa.id=order_details.product_attribute_id left join brand_products bp on bp.product_id=products.id inner join manufacturing_brands b on b.id=bp.brand_id where order_details.order_id='.$data['order']['id'].' and products.product_type in (2,3)';
        $data['jobWork'] = $this->pktdblib->custom_query($jobWorkQuery);
		/*echo '<pre>';
		print_r($data);
		exit;*/

		$this->load->view('orders/email/invoice_format_2', $data);
    }
    
    function exportxml2($test=false){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            /*echo '<pre>';
            print_r($this->input->post());
            exit;*/
            $postData['condition'] = [];
            $postData['from_invoice'] = $this->input->post('from_invoice');
			$postData['to_invoice'] = $this->input->post('to_invoice');
			$fileName = $postData['from_invoice']."-to-".$postData['to_invoice'].'.xml';
			//echo "<pre>"; print_r($postData);echo "</pre>";exit;
			//echo json_encode($this->order_model->exportInvoiceList($postData));
			$resultArray = json_decode(json_encode($this->order_model->exportInvoiceList($postData)), true);
			/*echo '<pre>';
			print_r($resultArray);exit;*/
			$customerIds = [];
			if($this->input->post('export')=='customer'){
    			foreach($resultArray as $cKey=>$customer){
    			    $customerIds[$cKey] = $customer['customer_id'];
    			}
    			$params['account'] = 'customers';
    			
    			$params['ids'] = $customerIds;
    			$params['requestType'] = 'export';
    			$customerxml = Modules::run('tally/export_ledgerxml', $params);
			}elseif($this->input->post('export')=='order'){
    			/*echo '<pre>';
    			print_r($resultArray);
    			exit;*/
    			$salesXml = '<ENVELOPE>
                  <HEADER>
                    <TALLYREQUEST>Import Data</TALLYREQUEST>
                  </HEADER>
                  <BODY>
                    <IMPORTDATA>
                      <REQUESTDESC>
                        <REPORTNAME>Vouchers</REPORTNAME>
                        <STATICVARIABLES>
                          <SVCURRENTCOMPANY>Expede Global - (21-22)</SVCURRENTCOMPANY>
                        </STATICVARIABLES>
                      </REQUESTDESC>
                      <REQUESTDATA>
                        <TALLYMESSAGE xmlns:UDF="TallyUDF">';
                        $gstType = 'Regular';
                        
                        foreach($resultArray as $invKey=>$invoice){
                            if(empty(trim($invoice['gst_no']))){
                                $gstType = 'Unregistered';   
                            }
                            $custCode = $invoice['customer_id'];
                          $salesXml.='<VOUCHER REMOTEID="" VCHKEY="" VCHTYPE="Sales Dry Fruit" ACTION="Create" OBJVIEW="Invoice Voucher View">
                            <ADDRESS.LIST TYPE="String"><ADDRESS>
                              '.str_replace(', ', '</ADDRESS><ADDRESS>', htmlspecialchars($invoice['delivery_address'], ENT_QUOTES)).'</ADDRESS>
                            </ADDRESS.LIST>
                            <BASICBUYERADDRESS.LIST TYPE="String">
                            <BASICBUYERADDRESS>
                              '.str_replace(', ', '</BASICBUYERADDRESS><BASICBUYERADDRESS>', htmlspecialchars($invoice['delivery_address'], ENT_QUOTES)).'</BASICBUYERADDRESS>
                              <BASICBUYERADDRESS></BASICBUYERADDRESS>
                            </BASICBUYERADDRESS.LIST>
                            <BASICORDERTERMS.LIST TYPE="String">
                              <BASICORDERTERMS>
                              </BASICORDERTERMS>
                            </BASICORDERTERMS.LIST>
                            <OLDAUDITENTRYIDS.LIST TYPE="Number">
                              <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                            </OLDAUDITENTRYIDS.LIST>
                            <DATE>'.date('Ymd', strtotime($invoice['created'])).'</DATE>
                            <GUID />
                            <STATENAME>'.$invoice['state_name'].'</STATENAME>
                            <GSTREGISTRATIONTYPE>'.$gstType.'</GSTREGISTRATIONTYPE>
                            <NARRATION>
                            </NARRATION>
                            <COUNTRYOFRESIDENCE>'.$invoice['country_name'].'</COUNTRYOFRESIDENCE>
                            <PARTYGSTIN>'.$invoice['gst_no'].'</PARTYGSTIN>
                            <TAXUNITNAME />
                            <PARTYNAME>'.htmlspecialchars(trim((empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name']))).' ('.$custCode.')</PARTYNAME>
                            <VOUCHERTYPENAME>Dry Fruit Sales</VOUCHERTYPENAME>
                            <REFERENCE>'.$invoice['order_code'].'</REFERENCE>
                            <VOUCHERNUMBER>D'.str_replace('-','',$invoice['fiscal_yr']).'-'.$invoice['invoice_no'].'</VOUCHERNUMBER>
                            <PARTYLEDGERNAME>'.htmlspecialchars(trim(empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name'])).' ('.$custCode.')</PARTYLEDGERNAME>
                            <BASICBASEPARTYNAME>'.htmlspecialchars(trim(empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name'])).' ('.$custCode.')</BASICBASEPARTYNAME>
                            <CSTFORMISSUETYPE />
                            <CSTFORMRECVTYPE />
                            <BUYERSCSTNUMBER>
                            </BUYERSCSTNUMBER>
                            <FBTPAYMENTTYPE />
                            <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
                            <PLACEOFSUPPLY>'.$invoice['state_name'].'</PLACEOFSUPPLY>
                            <CONSIGNEEGSTIN>'.$invoice['gst_no'].'</CONSIGNEEGSTIN>
                            <BASICSHIPPEDBY>
                            </BASICSHIPPEDBY>
                            <BASICBUYERNAME>'.htmlspecialchars(trim(empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name'])).' ('.$custCode.')</BASICBUYERNAME>
                            <BASICSHIPDOCUMENTNO>
                            </BASICSHIPDOCUMENTNO>
                            <BASICORDERREF>
                            </BASICORDERREF>
                            <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
                            <BILLOFLADINGNO>
                            </BILLOFLADINGNO>
                            <BILLOFLADINGDATE>
                            </BILLOFLADINGDATE>
                            <BASICSHIPVESSELNO>
                            </BASICSHIPVESSELNO>
                            <BASICFINALDESTINATION>
                            </BASICFINALDESTINATION>
                            <BASICBUYERSSALESTAXNO>
                            </BASICBUYERSSALESTAXNO>
                            <BASICDUEDATEOFPYMT>
                            </BASICDUEDATEOFPYMT>
                            <BASICDATETIMEOFINVOICE>'.date('d M y', strtotime($invoice['created'])).'</BASICDATETIMEOFINVOICE>
                            <BASICDATETIMEOFREMOVAL>'.date('d M y', strtotime($invoice['created'])).'</BASICDATETIMEOFREMOVAL>
                            <VCHGSTCLASS />
                            <CONSIGNEESTATENAME>
                            </CONSIGNEESTATENAME>
                            <COSTCENTRENAME>
                            </COSTCENTRENAME>
                            <DIFFACTUALQTY>No</DIFFACTUALQTY>
                            <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
                            <ASORIGINAL>No</ASORIGINAL>
                            <AUDITED>No</AUDITED>
                            <FORJOBCOSTING>No</FORJOBCOSTING>
                            <ISOPTIONAL>No</ISOPTIONAL>
                            <EFFECTIVEDATE>'.date('Ymd', strtotime($invoice['created'])).'</EFFECTIVEDATE>
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
                            <EXCISEOPENING>No</EXCISEOPENING>
                            <USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>
                            <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
                            <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
                            <ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>
                            <INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>
                            <ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>
                            <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
                            <IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>
                            <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
                            <ISISDVOUCHER>No</ISISDVOUCHER>
                            <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
                            <ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>
                            <ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>
                            <GSTNOTEXPORTED>No</GSTNOTEXPORTED>
                            <ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>
                            <ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>
                            <ISCANCELLED>No</ISCANCELLED>
                            <HASCASHFLOW>No</HASCASHFLOW>
                            <ISPOSTDATED>No</ISPOSTDATED>
                            <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
                            <ISINVOICE>Yes</ISINVOICE>
                            <MFGJOURNAL>No</MFGJOURNAL>
                            <HASDISCOUNTS>No</HASDISCOUNTS>
                            <ISCOSTCENTRE> No </ISCOSTCENTRE>
                            <ASPAYSLIP>No</ASPAYSLIP>
                            <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
                            <ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>
                            <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
                            <ISVOID>No</ISVOID>
                            <ISONHOLD>No</ISONHOLD>
                            <ORDERLINESTATUS>No</ORDERLINESTATUS>
                            <VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>
                            <VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>
                            <ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>
                            <VATISASSESABLECALCVCH>Yes</VATISASSESABLECALCVCH>
                            <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                            <ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>
                            <ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>
                            <ISDELETED>No</ISDELETED>
                            <CHANGEVCHMODE>No</CHANGEVCHMODE>
                            <ALTERID />
                            <MASTERID />
                            <VOUCHERKEY />
                            <EXCLUDEDTAXATIONS.LIST />
                            <OLDAUDITENTRIES.LIST />
                            <ACCOUNTAUDITENTRIES.LIST />
                            <AUDITENTRIES.LIST />
                            <DUTYHEADDETAILS.LIST />
                            <SUPPLEMENTARYDUTYHEADDETAILS.LIST />
                            <INVOICEDELNOTES.LIST>
                              <BASICSHIPPINGDATE>
                              </BASICSHIPPINGDATE>
                              <BASICSHIPDELIVERYNOTE>
                              </BASICSHIPDELIVERYNOTE>
                            </INVOICEDELNOTES.LIST>
                            <INVOICEINDENTLIST.LIST />
                            <ATTENDANCEENTRIES.LIST />
                            <ORIGINVOICEDETAILS.LIST />
                            <INVOICEEXPORTLIST.LIST />
                            <INVOICEORDERLIST.LIST>
                              <BASICORDERDATE>
                              </BASICORDERDATE>
                              <BASICPURCHASEORDERNO>
                              </BASICPURCHASEORDERNO>
                            </INVOICEORDERLIST.LIST>
                            
                            ';
                            //echo 'Select od.* from order_details od where od.order_id in ('.$invoice['order_ids'].') AND od.is_active=true<br>';
                            $sgst = 0;
                            $cgst = 0;
                            $igst = 0;
                            $gst = 0;
                            $finalAmt = 0;
                            $calcAmt = [];
                            $calcGst = [];
                            $orderedProducts = $this->pktdblib->custom_query('Select od.*, p.product, p.tally_name, p.base_uom, p.base_price from order_details od inner join products p on p.id=od.product_id where od.order_id in ('.$invoice['order_ids'].') AND od.is_active=true');
                            //print_r($orderedProducts);
                            if(count($orderedProducts)>0){
                                foreach($orderedProducts as $odKey=>$detail){
                                    $gcalc = 0;
                                    $spec = json_decode($detail['variation'], true);
                                    //print_r($spec);exit;
                                    $str = '';
                                    $bill['qty'] = 0;
                                    $bill['rate'] = 0;
                                    $baseUOM = explode(" ", $detail['base_uom']);
                                    $attribute = $this->pktdblib->custom_query('select a.unit, a.uom, concat(a.unit," ",a.uom) as attribute, pa.per_unit_margin from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$spec['attribute']['product_attribute_id'].'"');
                                    if(count($attribute)>0){
                                        $requireUOM = explode(" ", $attribute[0]['attribute']);
                                        
                                        $unit = $this->pktlib->unit_convertion($attribute[0]['uom'], $baseUOM[1]);
                                        $qt = $requireUOM[0]*$unit*($detail['qty']-$detail['return_quantity']);
                                        $rateperkg = $detail['unit_price']/($requireUOM[0]*$unit);
                                        //$str.=$detail['product']." (".$attribute[0]['unit']." ".$attribute[0]['uom'].") pcs:".($detail['qty']-$detail['return_quantity'])." rate/pc:".$detail['unit_price'];
                                        //echo ' QTY:'.$qt." ".$baseUOM[1]."  RATE PER".$baseUOM[1].":".$rateperkg."<br>";
                                        $bill['qty'] = $qt;
                                        $bill['rate'] = $rateperkg;
                                    }else{
                                        $bill['qty'] = ($detail['qty']-$detail['return_quantity']);
                                        $bill['rate'] = $detail['unit_price'];
                                        //$str.=$detail['product']." pcs:".($detail['qty']-$detail['return_quantity'])." rate/pc:".$detail['unit_price']."\n";
                                    }
                                    //echo $bill['qty']." ".$bill['rate'];exit;
                                    $gstSql = 'Select * from product_gst_rates where product_id='.$detail['product_id'].' and effective_from<="'.$invoice['created'].'" and (effective_till is null or effective_till>="'.$invoice['created'].'") and is_active=true';
                                    $gstRates = $this->pktdblib->custom_query($gstSql);
                                    //print_r($gstRates);
                                    
                                    if(count($gstRates)>0){
                                        $calcGst[] = $gcalc = $bill['rate']*$bill['qty']*$gstRates[0]['gst_rate']/(100+$gstRates[0]['gst_rate']);
                                        $bill['perKgRateWithoutGst'] = round($bill['rate']-$gcalc,2);
                                        $gst = $gst+$gcalc;
                                        $calcAmt[] = $bill['rate']-$gcalc;
                                    }
                                    echo '<pre>';
                                    print_r($bill);
                                    echo '</pre>';
                                    //echo $bill['rate']." gcalc=".$gcalc." ".$bill['qty']."<br>";
                                    
                                    $bill['rate'] = round($bill['rate'], 2);
                                    $wgstValue = round($bill['perKgRateWithoutGst'],2);//round($bill['rate']*$bill['qty']*100/(100+$gstRates[0]['gst_rate']),2);
                                    $gstValue = $wgstValue+$gcalc;
                                    $finalAmt = $finalAmt+$gstValue;
                                    //echo $finalAmt." ".$gstValue.'<br>';
                                    //echo $bill['perKgRateWithoutGst']." ".$detail['product']." including price: ".($wgstValue+$gcalc)." gcalc: ".$gcalc." ".$bill['qty']." gst rate: ".$gstRates[0]['gst_rate']." excluding price:".$wgstValue."<br><br>";
                                    //echo $str;exit;
                                    $salesXml.='<ALLINVENTORYENTRIES.LIST>
                                      <BASICUSERDESCRIPTION.LIST TYPE="String">
                                        <BASICUSERDESCRIPTION>
                                        </BASICUSERDESCRIPTION>
                                      </BASICUSERDESCRIPTION.LIST>
                                      <STOCKITEMNAME>'.(empty($detail['tally_name'])?$detail['product']:$detail['tally_name']).'</STOCKITEMNAME>
                                      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                      <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                      <ISAUTONEGATE>No</ISAUTONEGATE>
                                      <ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
                                      <ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
                                      <ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
                                      <ISPRIMARYITEM>No</ISPRIMARYITEM>
                                      <ISSCRAP>No</ISSCRAP>
                                      <RATE>'.$bill['perKgRateWithoutGst'].'/'.$baseUOM[1].'</RATE>
                                      <DISCOUNT>
                                      </DISCOUNT>
                                      <AMOUNT>'.$wgstValue.'</AMOUNT>
                                      <VATASSBLVALUE>'.$wgstValue.'</VATASSBLVALUE>
                                      <ACTUALQTY>'.$bill['qty'].'</ACTUALQTY>
                                      <BILLEDQTY>'.$bill['qty'].'</BILLEDQTY>
                                      <BATCHALLOCATIONS.LIST>
                                        <MFDON>
                                        </MFDON>
                                        <GODOWNNAME>Vasai - Godown</GODOWNNAME>
                                        <BATCHNAME> Primary Batch</BATCHNAME>
                                        <DESTINATIONGODOWNNAME>Vasai - Godown</DESTINATIONGODOWNNAME>
                                        <INDENTNO />
                                        <ORDERNO />
                                        <TRACKINGNUMBER />
                                        <DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED>
                                        <AMOUNT>'.$wgstValue.'</AMOUNT>
                                        <ACTUALQTY>'.$bill['qty'].' '.$baseUOM[1].'</ACTUALQTY>
                                        <BILLEDQTY>'.$bill['qty'].' '.$baseUOM[1].'</BILLEDQTY>
                                        <ADDITIONALDETAILS.LIST />
                                        <VOUCHERCOMPONENTLIST.LIST />
                                      </BATCHALLOCATIONS.LIST>
                                      <ACCOUNTINGALLOCATIONS.LIST>
                                        <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                          <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                        </OLDAUDITENTRYIDS.LIST>
                                        <LEDGERNAME>Sale - Vasai</LEDGERNAME>
                                        <GSTCLASS />
                                        <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                        <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                        <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                        <ISPARTYLEDGER>No</ISPARTYLEDGER>
                                        <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                        <AMOUNT>'.$wgstValue.'</AMOUNT>
                                        <SERVICETAXDETAILS.LIST />
                                        <BANKALLOCATIONS.LIST />
                                        <BILLALLOCATIONS.LIST />
                                        <INTERESTCOLLECTION.LIST />
                                        <OLDAUDITENTRIES.LIST />
                                        <ACCOUNTAUDITENTRIES.LIST />
                                        <AUDITENTRIES.LIST />
                                        <INPUTCRALLOCS.LIST />
                                        <DUTYHEADDETAILS.LIST />
                                        <EXCISEDUTYHEADDETAILS.LIST />
                                        <RATEDETAILS.LIST />
                                        <SUMMARYALLOCS.LIST />
                                        <STPYMTDETAILS.LIST />
                                        <EXCISEPAYMENTALLOCATIONS.LIST />
                                        <TAXBILLALLOCATIONS.LIST />
                                        <TAXOBJECTALLOCATIONS.LIST />
                                        <TDSEXPENSEALLOCATIONS.LIST />
                                        <VATSTATUTORYDETAILS.LIST />
                                        <COSTTRACKALLOCATIONS.LIST />
                                        <REFVOUCHERDETAILS.LIST />
                                        <INVOICEWISEDETAILS.LIST />
                                        <VATITCDETAILS.LIST />
                                        <ADVANCETAXDETAILS.LIST />
                                      </ACCOUNTINGALLOCATIONS.LIST>
                                      <DUTYHEADDETAILS.LIST />
                                      <SUPPLEMENTARYDUTYHEADDETAILS.LIST />
                                      <TAXOBJECTALLOCATIONS.LIST />
                                      <REFVOUCHERDETAILS.LIST />
                                      <EXCISEALLOCATIONS.LIST />
                                      <EXPENSEALLOCATIONS.LIST />
                                    </ALLINVENTORYENTRIES.LIST>';
                                    
                                }
                            }
                            
                            $salesXml.='<LEDGERENTRIES.LIST>
                              <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                              </OLDAUDITENTRYIDS.LIST>
                              <LEDGERNAME>'.htmlspecialchars(trim(empty($invoice['company_name'])?$invoice['customer_name']:$invoice['company_name'])).'('.$custCode.')</LEDGERNAME>
                              <GSTCLASS />
                              <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                              <LEDGERFROMITEM>No</LEDGERFROMITEM>
                              <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                              <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                              <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
                              <AMOUNT> -'.$finalAmt.'</AMOUNT>
                              <SERVICETAXDETAILS.LIST />
                              <BANKALLOCATIONS.LIST />
                              <BILLALLOCATIONS.LIST>
                                <NAME>order no D'.str_replace('-','',$invoice['fiscal_yr']).'-'.$invoice['invoice_no'].' </NAME>
                                <BILLTYPE>New Ref</BILLTYPE>
                                <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                                <AMOUNT> -'.$invoice['amount_after_tax'].'</AMOUNT>
                                <INTERESTCOLLECTION.LIST />
                                <STBILLCATEGORIES.LIST />
                              </BILLALLOCATIONS.LIST>
                              <INTERESTCOLLECTION.LIST />
                              <OLDAUDITENTRIES.LIST />
                              <ACCOUNTAUDITENTRIES.LIST />
                              <AUDITENTRIES.LIST />
                              <INPUTCRALLOCS.LIST />
                              <DUTYHEADDETAILS.LIST />
                              <EXCISEDUTYHEADDETAILS.LIST />
                              <RATEDETAILS.LIST />
                              <SUMMARYALLOCS.LIST />
                              <STPYMTDETAILS.LIST />
                              <EXCISEPAYMENTALLOCATIONS.LIST />
                              <TAXBILLALLOCATIONS.LIST />
                              <TAXOBJECTALLOCATIONS.LIST />
                              <TDSEXPENSEALLOCATIONS.LIST />
                              <VATSTATUTORYDETAILS.LIST />
                              <COSTTRACKALLOCATIONS.LIST />
                              <REFVOUCHERDETAILS.LIST />
                              <INVOICEWISEDETAILS.LIST />
                              <VATITCDETAILS.LIST />
                              <ADVANCETAXDETAILS.LIST />
                            </LEDGERENTRIES.LIST>';
                            //$finalAmt = number_format($finalAmt, 2, '.', '');
                            $remainder = 0;
                            $decimal = explode('.', $gst);
                            if(count($decimal)>1){
                                $remainder = ($decimal[1] % 2);
                            }
                            
                            if($remainder==0){
                            $sgst = $cgst = $gst/2;
                            //echo "even decimal";
                            }
                            else{
                                //echo "odd decimal";
                                $sgst = $cgst = ($gst+0.01)/2;
                            }
                            //echo $sgst." ".$cgst;exit;
                            //$finalAmt = round($finalAmt+$cgst+$sgst , 2);
                            $roundOff = round($invoice['amount_after_tax']-$finalAmt, 2);
                            //echo "gst: ".$gst." finalamt: ".$finalAmt." roundoff: ".$roundOff;
                            $salesXml.='
                            <LEDGERENTRIES.LIST>
                              <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                              </OLDAUDITENTRYIDS.LIST>
                              <BASICRATEOFINVOICETAX.LIST TYPE="Number">
                                <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
                              </BASICRATEOFINVOICETAX.LIST>
                              <ROUNDTYPE />
                              <LEDGERNAME>CGST</LEDGERNAME>
                              <GSTCLASS />
                              <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                              <LEDGERFROMITEM>No</LEDGERFROMITEM>
                              <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                              <ISPARTYLEDGER>No</ISPARTYLEDGER>
                              <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                              <AMOUNT> '.round($cgst, 2).'</AMOUNT>
                              <VATEXPAMOUNT>'.round($cgst, 2).'</VATEXPAMOUNT>
                              <SERVICETAXDETAILS.LIST />
                              <BANKALLOCATIONS.LIST />
                              <SERVICETAXDETAILS.LIST>
                              </SERVICETAXDETAILS.LIST>
                              <CATEGORYALLOCATIONS.LIST>
                              </CATEGORYALLOCATIONS.LIST>
                              <BANKALLOCATIONS.LIST>
                              </BANKALLOCATIONS.LIST>
                              <BILLALLOCATIONS.LIST>
                              </BILLALLOCATIONS.LIST>
                              <INTERESTCOLLECTION.LIST>
                              </INTERESTCOLLECTION.LIST>
                              <OLDAUDITENTRIES.LIST>
                              </OLDAUDITENTRIES.LIST>
                              <ACCOUNTAUDITENTRIES.LIST>
                              </ACCOUNTAUDITENTRIES.LIST>
                              <AUDITENTRIES.LIST>
                              </AUDITENTRIES.LIST>
                              <INPUTCRALLOCS.LIST>
                              </INPUTCRALLOCS.LIST>
                              <DUTYHEADDETAILS.LIST>
                              </DUTYHEADDETAILS.LIST>
                              <EXCISEDUTYHEADDETAILS.LIST>
                              </EXCISEDUTYHEADDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Integrated Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Central Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>State Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Cess</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <SUMMARYALLOCS.LIST>
                              </SUMMARYALLOCS.LIST>
                              <STPYMTDETAILS.LIST>
                              </STPYMTDETAILS.LIST>
                              <EXCISEPAYMENTALLOCATIONS.LIST>
                              </EXCISEPAYMENTALLOCATIONS.LIST>
                              <TAXBILLALLOCATIONS.LIST>
                              </TAXBILLALLOCATIONS.LIST>
                              <TAXOBJECTALLOCATIONS.LIST>
                              </TAXOBJECTALLOCATIONS.LIST>
                              <TDSEXPENSEALLOCATIONS.LIST>
                              </TDSEXPENSEALLOCATIONS.LIST>
                              <VATSTATUTORYDETAILS.LIST>
                              </VATSTATUTORYDETAILS.LIST>
                              <COSTTRACKALLOCATIONS.LIST>
                              </COSTTRACKALLOCATIONS.LIST>
                              <REFVOUCHERDETAILS.LIST>
                              </REFVOUCHERDETAILS.LIST>
                              <INVOICEWISEDETAILS.LIST>
                              </INVOICEWISEDETAILS.LIST>
                              <VATITCDETAILS.LIST>
                              </VATITCDETAILS.LIST>
                              <ADVANCETAXDETAILS.LIST>
                              </ADVANCETAXDETAILS.LIST>
                            </LEDGERENTRIES.LIST>
                            <LEDGERENTRIES.LIST>
                              <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                              </OLDAUDITENTRYIDS.LIST>
                              <BASICRATEOFINVOICETAX.LIST TYPE="Number">
                                <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
                              </BASICRATEOFINVOICETAX.LIST>
                              <ROUNDTYPE />
                              <LEDGERNAME>SGST</LEDGERNAME>
                              <GSTCLASS />
                              <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                              <LEDGERFROMITEM>No</LEDGERFROMITEM>
                              <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                              <ISPARTYLEDGER>No</ISPARTYLEDGER>
                              <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                              <AMOUNT>'.round($sgst, 2).'</AMOUNT>
                              <VATEXPAMOUNT>'.round($sgst, 2).'</VATEXPAMOUNT>
                              <SERVICETAXDETAILS.LIST />
                              <BANKALLOCATIONS.LIST />
                              <SERVICETAXDETAILS.LIST>
                              </SERVICETAXDETAILS.LIST>
                              <CATEGORYALLOCATIONS.LIST>
                              </CATEGORYALLOCATIONS.LIST>
                              <BANKALLOCATIONS.LIST>
                              </BANKALLOCATIONS.LIST>
                              <BILLALLOCATIONS.LIST>
                              </BILLALLOCATIONS.LIST>
                              <INTERESTCOLLECTION.LIST>
                              </INTERESTCOLLECTION.LIST>
                              <OLDAUDITENTRIES.LIST>
                              </OLDAUDITENTRIES.LIST>
                              <ACCOUNTAUDITENTRIES.LIST>
                              </ACCOUNTAUDITENTRIES.LIST>
                              <AUDITENTRIES.LIST>
                              </AUDITENTRIES.LIST>
                              <INPUTCRALLOCS.LIST>
                              </INPUTCRALLOCS.LIST>
                              <DUTYHEADDETAILS.LIST>
                              </DUTYHEADDETAILS.LIST>
                              <EXCISEDUTYHEADDETAILS.LIST>
                              </EXCISEDUTYHEADDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Integrated Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Central Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>State Tax</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <RATEDETAILS.LIST>
                                <GSTRATEDUTYHEAD>Cess</GSTRATEDUTYHEAD>
                                <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                              </RATEDETAILS.LIST>
                              <SUMMARYALLOCS.LIST>
                              </SUMMARYALLOCS.LIST>
                              <STPYMTDETAILS.LIST>
                              </STPYMTDETAILS.LIST>
                              <EXCISEPAYMENTALLOCATIONS.LIST>
                              </EXCISEPAYMENTALLOCATIONS.LIST>
                              <TAXBILLALLOCATIONS.LIST>
                              </TAXBILLALLOCATIONS.LIST>
                              <TAXOBJECTALLOCATIONS.LIST>
                              </TAXOBJECTALLOCATIONS.LIST>
                              <TDSEXPENSEALLOCATIONS.LIST>
                              </TDSEXPENSEALLOCATIONS.LIST>
                              <VATSTATUTORYDETAILS.LIST>
                              </VATSTATUTORYDETAILS.LIST>
                              <COSTTRACKALLOCATIONS.LIST>
                              </COSTTRACKALLOCATIONS.LIST>
                              <REFVOUCHERDETAILS.LIST>
                              </REFVOUCHERDETAILS.LIST>
                              <INVOICEWISEDETAILS.LIST>
                              </INVOICEWISEDETAILS.LIST>
                              <VATITCDETAILS.LIST>
                              </VATITCDETAILS.LIST>
                              <ADVANCETAXDETAILS.LIST>
                              </ADVANCETAXDETAILS.LIST>
                            </LEDGERENTRIES.LIST>
                            <PAYROLLMODEOFPAYMENT.LIST />
                            <ATTDRECORDS.LIST />
                            <TEMPGSTRATEDETAILS.LIST />
                          ';
                          echo $invoice['amount_after_tax']." ".$finalAmt.'<br>';
                          //echo ($invoice['amount_after_tax']-$finalAmt);
                          //echo $roundOff;exit;
                          if($roundOff>0 || $roundOff<0){
                          $salesXml.='<LEDGERENTRIES.LIST>
                               <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                               </OLDAUDITENTRYIDS.LIST>
                               <ROUNDTYPE>Normal Rounding</ROUNDTYPE>
                               <LEDGERNAME>Round Off</LEDGERNAME>
                               <GSTCLASS/>
                               <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                               <LEDGERFROMITEM>No</LEDGERFROMITEM>
                               <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                               <ISPARTYLEDGER>No</ISPARTYLEDGER>
                               <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                               <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                               <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                               <ROUNDLIMIT> 1</ROUNDLIMIT>
                               <AMOUNT>'.number_format(($invoice['amount_after_tax']-$finalAmt), 2).'</AMOUNT>
                               <VATEXPAMOUNT>'.number_format(($invoice['amount_after_tax']-$finalAmt), 2).'</VATEXPAMOUNT>
                               <INVOICEROUNDINGDIFFVAL>'.number_format(($invoice['amount_after_tax']-$finalAmt), 2).'</INVOICEROUNDINGDIFFVAL>
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
                          ';
                          }
                          $salesXml.='</VOUCHER>';
                        }
                        $salesXml.='</TALLYMESSAGE>
                        <TALLYMESSAGE xmlns:UDF="TallyUDF">
                         <COMPANY>
                          <REMOTECMPINFO.LIST MERGE="Yes">
                           <NAME>508ae971-4004-433e-8332-aa88752545a2</NAME>
                           <REMOTECMPNAME>Expede Global - (21-22)</REMOTECMPNAME>
                           <REMOTECMPSTATE>Maharashtra</REMOTECMPSTATE>
                          </REMOTECMPINFO.LIST>
                         </COMPANY>
                        </TALLYMESSAGE>
                        <TALLYMESSAGE xmlns:UDF="TallyUDF">
                         <COMPANY>
                          <REMOTECMPINFO.LIST MERGE="Yes">
                           <NAME>508ae971-4004-433e-8332-aa88752545a2</NAME>
                           <REMOTECMPNAME>Expede Global - (21-22)</REMOTECMPNAME>
                           <REMOTECMPSTATE>Maharashtra</REMOTECMPSTATE>
                          </REMOTECMPINFO.LIST>
                         </COMPANY>
                        </TALLYMESSAGE>
                      </REQUESTDATA>
                    </IMPORTDATA>
                  </BODY>
                </ENVELOPE>';
    			//exit;
    			echo '<pre>';
    			echo htmlspecialchars($salesXml);exit;
    			$ledgerContent = file_put_contents($fileName, $salesXml);
                force_download($fileName, NULL);
                
			}
        }
        
        $data['meta_title'] = "ERP";
        
        $data['title'] = "ERP : Orders";
        $data['meta_description'] = "Order panel";
        
        $data['modules'][] = "orders";
        $data['methods'][] = "export_form";
        $data['test'] = true;
        
        echo Modules::run("templates/admin_template", $data);
    }
}
?>