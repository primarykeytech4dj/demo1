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
                        </li>
                        <li>
                          <a class="" href="editorder/'.$v['id'].'">Edit Order</a> 
                        </li>
                        <li>';

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
        $sql = 'select od.unit_price, od.qty, od.uom, od.variation, p.product, o.amount_before_tax, o.shipping_charge, o.amount_after_tax, p.base_uom, concat(a.unit, " ",a.uom) as attribute from order_details od left join orders o on o.id=od.order_id left join products p on p.id=od.product_id left join product_attributes pa on pa.id=od.product_attribute_id left join attributes a on a.id=pa.attribute_id where od.order_id='.$orderId;
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
        //echo $sql;exit;
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
		
		echo $html;exit;
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
        //echo '<pre>';print_r($data['customer']);exit;
		$data['orderDetails'] = $this->pktdblib->custom_query('select products.product, order_details.*, products.base_uom from order_details inner join products on products.id=order_details.product_id where order_details.order_id='.$data['order']['id']);
		$arr['responsetype'] = 'web';
		$data['attribute'] = Modules::run('products/ordered_product_attribute_list', $arr);

		/*echo '<pre>';
		print_r($data);
		exit;
*/
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
		//echo '<pre>';print_r($data);exit;
		foreach ($data as $key => $orderDetail) {
		    if(isset($orderDetail['is_active'])){
		        $orderDetail['is_active'] = true;
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
                    this.submit();
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
        $sql='Select o.*, concat(l.first_name," ",l.surname) as sales_person, concat(l2.first_name," ",l2.surname) as created_person, c.company_name, concat(c.first_name," ",c.middle_name, " ", c.surname) as contact_person, concat(a.address_1,", ", a.address_2, ", ", areas.area_name,", ", cities.city_name,"-",a.pincode, " \nFSSI:", a.fssi, " \nGST NO:",a.gst_no) as address, areas.area_name, os.status, c.contact_1 from orders o inner join customers c on c.id=o.customer_id inner join address a on a.id=o.shipping_address_id left join countries ct on ct.name left join states s on s.id=a.state_id left join cities on cities.id=a.city_id left join areas ON areas.id=a.area_id INNER join order_status os on os.id=o.order_status_id left join login l on l.id=o.sale_by left join login l2 on l2.id=o.created_by where o.date>"'.date('Y-m-d', strtotime('-60 days')).'" and o.order_status_id not in (0,1,2,8) order by date DESC, c.first_name ASC';
        $query = $this->pktdblib->custom_query($sql);
        $fileName = 'order-'.date('dmY');
        $this->load->library('excel');
        //$objPHPExcel = PHPExcel_IOFactory::load($filename);
        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $tableColumns = ['Order Number', 'Shop Name', 'Ordered Through', 'Contact Number', 'Order Date', 'Order Time', 'Address', 'Teritorry', 'Product specification', 'Sales Person', 'Entered By', 'Invoice Amount', 'Order Status'];
        $column = 0;
        foreach($tableColumns as $field){
            $excel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);//->getStyle( $column )->getFont()->setBold( true );;
            $column++;
        }
        $excel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
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
            //$excel->getActiveSheet()->setCellValueByColumnAndRow(++$col, $excelRow, $order['company_name']);
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
            $excelRow++;
        }
        //exit;
        /*echo '<pre>';
        print_r($excel);
        exit;*/
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        //header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
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
}
?>