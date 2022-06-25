<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 |	CONTROLLER SUMMARY AND DATABASE TABLES
 |--------------------------------------------------------------------------
 | 
 |	This is where you can start your admin/manage/password protected stuff.
 | 
 |	Database table structure
 |
 |	Table name(s) - no tables
 |
 |
 */
 
 
class Fieldmember_Panel extends MY_Controller {
    
	public $accesRoles = [];
	function __construct() {
		parent::__construct();
		$this->accessRoles=array('1','2','6');
		$this->load->model('fieldmember_panel/fieldmember_panel_model');
		// Check login and make sure email has been verified
		check_user_login(FALSE);
		$roles=$this->session->userdata('roles');
		$access = FALSE;
		foreach($roles as $rKey=>$role){
			if(in_array($role, $this->accessRoles)){
				$access = TRUE;
				break;
			}
		}
		//echo $access;exit;
		if($access===FALSE){
			redirect('/');
		}
		
	}

    // function admin_index() {
	// 	$data['meta_title'] = "Fieldmember Panel";
	// 	$data['meta_description'] = "Welcome to the FieldMember panel";	
		
	// 	/*if((NULL!==$this->session->userdata('roles')) && in_array(1, $this->session->userdata('roles'))){
    //         	redirect('#');
    //     }*/
	// 	$data['modules'][] = "fieldmember_panel";
	// 	$data['methods'][] = "view_admin_panel_default";
		
	// 	echo Modules::run("templates/fieldmember_template", $data);
    // }
	
    function admin_assign(){
		//echo "reached here";exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['values_posted'] = $_POST['data'];
            
			$this->form_validation->set_rules('data[deliveryboy_order][employee_id]', 'Delivery Boy', 'required');
			$this->form_validation->set_rules('data[orders]', 'Orders', 'required');
			if($this->form_validation->run('employees') !== FALSE) {
				$orderArray=$this->input->post('data[orders]');
				$updateOrder=[];
				$log = [];
				foreach($orderArray as $oKey=>$order):
					$post_data[$oKey]['employee_id'] = $this->input->post('data[deliveryboy_order][employee_id]');
					$log[$oKey]['created'] = $post_data[$oKey]['created'] = $updateOrder[$oKey]['modified'] = date('Y-m-d H:i:s');
					$log[$oKey]['order_id'] = $post_data[$oKey]['order_id'] = $updateOrder[$oKey]['id'] = $order;
					$log[$oKey]['order_status_id'] = $updateOrder[$oKey]['order_status_id'] = 9;
					$log[$oKey]['user_id'] = $this->session->userdata('user_id');
					
				endforeach;
				
				$this->pktdblib->set_table("deliveryboy_order");
				$count = $this->pktdblib->_insert_multiple($post_data);

				if($count>0){
					$this->pktdblib->set_table('orders');
					$updateData = $this->pktdblib->update_multiple('id', $updateOrder);
					
                    //echo '<pre>';print_r($insert);exit;
                    $this->pktdblib->set_table('order_logs');
                    $insLog = $this->pktdblib->_insert_multiple($log);
					$msg = array('message'=>$count.'  Orders Assigned Successfully', 'class'=>'alert alert-success');
					$this->session->set_flashdata('message', $msg);
					
				}else{
					$msg = array('message'=>'Some Error Occured', 'class'=>'alert alert-danger');
					$this->session->set_flashdata('message', $msg);
				}
				
			}else{
				$msg = array('message' => 'Some Validation Error Occurred'.validation_errors(),'class' => 'alert alert-danger fade in');
                $this->session->set_flashdata('error', $msg);
			}
			redirect(custom_constants::assign_deliveryboy_url);
		}
		//echo "hello";exit;
		$data['meta_title'] = 'Fieldmember Panel';
		$data['meta_description'] = 'Welcome to the FieldMember panel';
		$data['modules'][] = "fieldmember_panel";
        $data['methods'][] = "fieldmember_panel_assign_fieldmember";
        echo modules::run('templates/admin_template', $data);
	}

	// function view_admin_panel_default() {
	//     $data['status'] = 'in process';
	// 	$data['pendingOrders'] = Modules::run('orders/admin_order_listing', $data);	
	// 	$this->load->view("admin_panel/admin_panel_default", $data);
	// }

	function fieldmember_panel_assign_fieldmember($data =[]) {
		// $this->fieldmember_panel_model->set_table('deliveryboy');
		// $deliveryBoy = $this->fieldmember_panel_model->get_where_custom('is_active',true);
		// $data['deliveryboy'] = $deliveryBoy->result_array();
		$orderslist = $this->pktdblib->custom_query('Select o.id, o. order_code, ar.area_name, o.date from orders o inner join address a on a.id=o.shipping_address_id inner join areas ar on ar.id=a.area_id where o.is_active=true and o.order_status_id=3 order by o.id DESC');
		$userlist = $this->pktdblib->custom_query('Select u.id, u.first_name, u.surname, ur.user_id, ur.role_id from employees u inner join user_roles ur on u.id=ur.user_id where u.is_active=true');
		//echo '<pre>'; print_r($userlist);exit;
		$data['option']['employees'] = [''=>'Select Delivery Boy'];
		$data['option']['orders'] = [''=>'Select Order'];
		foreach ($userlist as $userKey => $user) {
			$data['option']['employees'][$user['id']] = $user['first_name']." ".$user['surname'];
		}
		foreach ($orderslist as $orderKey => $order) {
			$data['option']['orders'][$order['id']] = $order['order_code']." ".$order['area_name']." (".date('d/m/Y', strtotime($order['date'])).")";
		}
		//echo '<pre>';print_r($data['deliveryboy']);echo '</pre>';
		//echo var_dump($data);exit;
		$this->load->view("fieldmember_panel/fieldmember_panel_assign_fieldmember", $data);
	}

	function fieldmember_panel_order_listing(){
		$data['modules'][] = "orders";
		$data['methods'][] = "admin_index";
		
		
		$this->load->view("fieldmember_panel/admin_order_detail_list", $data);
		//echo Modules::run("orders/admin_order_detail_list", $data);
	}

	function admin_index($data=[]){
		if($this->input->is_ajax_request()){  
			 
			$postData = $this->input->post();
			$postData['condition'] = [];
			if(in_array(1, $this->session->userdata('roles')) || in_array(2, $this->session->userdata('roles'))){

			}else{
				$postData['condition']['dbo.employee_id'] = $this->session->userdata('employees')['id'];
			}
			 //echo "<pre>"; print_r($postData);echo "</pre>";//exit;
			$data = $this->fieldmember_panel_model->orderList($postData);
			//echo "<pre>"; print_r($data);exit;
			foreach($data['aaData'] as $key=>$v){
				$action = '';
				if($v['status']=='Delivered'){
					$action = '<i class="fa fa-check alert-success"></i>';
				}elseif($v['status']=='Cancel'){
					$action = '<i class="fa fa-times alert-danger"></i>';
				}elseif($v['status']=='Assigned Delivery Boy'){
					$action = anchor('fieldmember_panel/view_OrderDetail/'.$v['id'], '<i class="fa fa-truck"></i> Update', ['class'=>'btn btn-success']);
				}
				$data['aaData'][$key]['amount'] = $data['aaData'][$key]['amount_after_tax'].'<br>'.$action;
				//$action.=$action;exit;
			}	
			echo json_encode($data);
			exit;
			 
		 }
		
		 $data['meta_title'] = "ERP";
		 
		 $data['title'] = "ERP : Orders";
		 $data['meta_description'] = "Order panel";
		 
		 $data['modules'][] = "fieldmember_panel";
		 $data['methods'][] = "admin_order_listing";
		 
		 echo Modules::run("templates/fieldmember_template", $data);
	 }
 
	 function admin_order_listing($data=[]){
		 $data['formTitle'] = "Orders";
		 $this->load->view("fieldmember_panel/admin_index", $data);
	 }
	 
	 function viewOrderDetail(){
		$this->load->view("fieldmember_panel/admin_order_detail");
	 }
	 
	 function view_OrderDetail($id=NULL){
	     if(NULL==$id){
	         echo "Invalid Access";
	         exit;
	     }
	     
	     if($_SERVER['REQUEST_METHOD'] == 'POST') {
	         /*echo '<pre>';
	         print_r($this->input->post());*/
	         //print_r($this->session->userdata());
	         $orderDetailArray = $this->input->post('order_details');
	         foreach($orderDetailArray as $odKey=>$detail){
	             $orderDetailArray[$odKey]['modified'] = date('Y-m-d H:i:s');
	         }
	         $this->pktdblib->set_table('order_details');
	         $updDetails = $this->pktdblib->update_multiple('id', $orderDetailArray);
	         if($updDetails){
	            $this->pktdblib->set_table('orders');
	            $updOrder = $this->pktdblib->_update($id, $this->input->post('data[orders]'));
	            $log['order_id'] = $id;
                $log['user_id'] = $this->session->userdata('user_id');
                $log['order_status_id'] =  $this->input->post('data[orders][order_status_id]');
                $log['is_active'] = true;
                $log['created'] = $insert['modified'] = date('Y-m-d H:i:s');
                //echo '<pre>';print_r($insert);exit;
                $this->pktdblib->set_table('order_logs');
                $insLog = $this->pktdblib->_insert($log);
	            if($this->input->post('data[orders][order_status_id]')==5){
    	            $insArray = ['order_id'=>$id, 'created_at'=>date('Y-m-d H:i:s'), 'created_by'=>$this->session->userdata('user_id'), 'payment_mode'=>$this->input->post('data[order_payments][payment_mode]')];
    	            if($this->input->post('data[order_payments][payment_mode]')=='cheque'){
    	                $insArray['tracker'] = $this->input->post('data[order_payments][cheque_no]');
    	                $insArray['date'] = $this->pktlib->dmYtoYmd($this->input->post('data[order_payments][cheque_date]'));
    	            }elseif($this->input->post('data[order_payments][payment_mode]')=='online'){
    	                $insArray['tracker'] = $this->input->post('data[order_payments][tracker]');
    	                $insArray['date'] = $this->pktlib->dmYtoYmd($this->input->post('data[order_payments][online_payment_date]'));
    	            }else{
    	                $insArray['date'] = $this->pktlib->dmYtoYmd($this->input->post('data[order_payments][cash_paid_on]'));
    	            }
    	            
    	            $this->pktdblib->set_table('order_payments');
    	            $ins = $this->pktdblib->_insert($insArray);
	            
    	            if($ins['status']=='success'){
    	                $msg = array('message' => 'Order Updated Successfully','class' => 'alert alert-success');
    	                $this->session->set_flashdata('message',$msg);
    	            }else{
    	                $msg = array('message' => 'Some Error Occurred','class' => 'alert alert-danger');
    	                $this->session->set_flashdata('message',$msg);
    	            }
	            }else{
	                $msg = array('message' => 'Order Updated Successfully','class' => 'alert alert-success');
    	            $this->session->set_flashdata('message',$msg);
    	            
    	            redirect(custom_constants::fieldmember_url);
	            }
	            
	         }
	         redirect(custom_constants::fieldmember_url);
	     }
		//echo var_dump($data);exit;
		$data['meta_title'] = "ERP";
		
		$data['title'] = "ERP : Orders Detail";
		$data['meta_description'] = "Field Member panel";
		$data['modules'][] = "fieldmember_panel";
		$data['methods'][] = "viewOrderDetail";
		$data['orderId'] = $id;
		
		$data['order'] = Modules::run('orders/getOrderData', $id);
		
		echo Modules::run("templates/fieldmember_template", $data);
	}
	
	function viewOrderPaymentDetail(){
		$this->load->view("fieldmember_panel/payment_mode_wise_collection_report");
	 }
	 
	function view_PaymentReport($condition=[]){
		
		$date = date('Y-m-d');
		$empId = 0;
		if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $empId = $this->session->userdata('employees')['id'];
        }
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		    $date = $this->pktlib->dmYtoYmd($this->input->post('selected_date'));
		    if($empId==0){
		        $empId = $this->input->post('employee_id');
		    }
		}
		
		$sql = "select * from (SELECT op.payment_mode, o.amount_after_tax as amount, o.order_code, op.tracker, op.created_at, c.first_name, c.middle_name, c.surname, op.date, do.employee_id, concat(e.first_name,' ',e.middle_name,' ',e.surname,' | ',e.emp_code) as delivery_person, o.id as order_id FROM orders o inner join order_payments op ON o.id=op.order_id inner join deliveryboy_order do on do.order_id=o.id inner join employees e on e.id=do.employee_id INNER JOIN customers c ON c.id=o.customer_id WHERE o.order_status_id=5 and op.payment_mode<>'online' UNION SELECT op.tracker as payment_mode, o.amount_after_tax as amount, o.order_code, op.tracker, op.created_at, c.first_name, c.middle_name, c.surname,op.date, do.employee_id, concat(e.first_name,' ',e.middle_name,' ',e.surname,' | ',e.emp_code) as delivery_person, o.id as order_id FROM orders o inner join order_payments op ON o.id=op.order_id inner join deliveryboy_order do on do.order_id=o.id inner join employees e on e.id=do.employee_id INNER JOIN customers c ON c.id=o.customer_id WHERE o.order_status_id=5 and op.payment_mode='online') t where 1=1 ";
		$sql.=" AND t.date like '".$date."'";
		if($empId!=0){
		    $sql.=" AND t.employee_id = '".$empId."'";
		}
		//echo $sql;
		$paymentMode = [];
		$report = [];
		$paymentTotal = [];
		
		$paymentDetail = $this->pktdblib->custom_query($sql);
		if(count($paymentDetail)>0){
    		foreach($paymentDetail as $key=>$paymentDetailmode){
    		    if(!in_array($paymentDetailmode['payment_mode'], $paymentMode)){
    		        $paymentMode[] = $paymentDetailmode['payment_mode'];
    		        $paymentTotal[$paymentDetailmode['payment_mode']] = 0;
    		    }
    		    
    		}
    		
    		foreach($paymentDetail as $key=>$paymentDetailmode){
    		    foreach($paymentMode as $mode){
    		        if($paymentDetailmode['payment_mode'] == $mode){
    		            $paymentDetail[$key][$mode] = $paymentDetailmode['amount'];
    		        }else{
    		            $paymentDetail[$key][$mode] = 0;
    		        }
    		        
    		        $paymentTotal[$paymentDetailmode['payment_mode']] = $paymentTotal[$paymentDetailmode['payment_mode']]+$paymentDetail[$key][$mode];
    		    }
    		}
		}

        $deliveryBoys['options'] = [0=>'Select Delivery Boy'];
        $sql2 = 'Select concat(e.first_name, " ", e.middle_name, " ", e.surname," | ",e.emp_code) as employee, e.id, e.is_active from employees e where 1=1';
        //print_r($this->session->userdata('roles'));
        if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $sql2.=' and e.id='.$this->session->userdata('employees')['id'];
        }
        
        //echo $sql2;
		$employees = $this->pktdblib->custom_query($sql2);
		foreach($employees as $eKey=>$employee){
		    $active = ($employee['is_active'])?'Active':'Inactive';
            $deliveryBoys['options'][$employee['id']] = ucfirst($employee['employee'])." (".$active.")";
		    
		}
		$data['deliveryBoys'] = $deliveryBoys;
		$data['paymentDetail'] = $paymentDetail;
		$data['paymentMode'] = $paymentMode;
		$data['paymentTotal'] = $paymentTotal;
		$data['meta_title'] = "ERP";
		
		$data['title'] = "ERP : Orders Detail";
		$data['meta_description'] = "Field Member panel";
		$data['modules'][] = "fieldmember_panel";
		$data['methods'][] = "viewOrderPaymentDetail";
		echo Modules::run("templates/admin_template", $data);
	}
	
	function morning_stock_report(){
	    $today = date('Y-m-d');
	    $yesterday = date('Y-m-d', strtotime('-1 day'));
	    $empId = 0;
        $productsArray = $this->pktdblib->custom_query('Select * from products where is_active=true and show_on_website=true');
        
        $orderedProducts = [];
        $productwiseOrder = ['old'=>[], 'new'=>[]];
        if(count($productsArray)>0){
            if(in_array(6, $this->session->userdata('roles'))){
            
                foreach($productsArray as $k=>$product){
                $orderedProducts[$product['id']] = $product;
                $delSql = 'Select * from daily_user_stocks where type="in" and created like "'.$today.'%" and product_id='.$product['id'].' and employee_id='.$this->session->userdata('employees')['id'];
                //echo $delSql.'<br>';
                $todaysDelivery = $this->pktdblib->custom_query($delSql);
                if(count($todaysDelivery)>0){
                    $productwiseOrder['new'][$product['id']] = $todaysDelivery[0]['qty'];
                }else{
                    $productwiseOrder['new'][$product['id']] = 0;
                }
                
                
                $inStock = $this->pktdblib->custom_query('Select sum(qty) as qty from daily_user_stocks where type="in" and created < "'.$today.'" and product_id='.$product['id'].' and employee_id='.$this->session->userdata('employees')['id']);
                if(count($inStock)>0){
                    $productwiseOrder['old'][$product['id']] = $inStock[0]['qty'];
                }else{
                    $productwiseOrder['old'][$product['id']] = 0;
                }
                $outStock = $this->pktdblib->custom_query('Select sum(qty) as qty from daily_user_stocks where type="out" and created < "'.$today.'" and product_id='.$product['id'].' and employee_id='.$this->session->userdata('employees')['id']);
                if(count($outStock)>0){
                    $productwiseOrder['old'][$product['id']] = $productwiseOrder['old'][$product['id']]-$outStock[0]['qty'];
                }else{
                    $productwiseOrder['old'][$product['id']] = $productwiseOrder['old'][$product['id']];
                }
            }
            }else{
                if($_SERVER['REQUEST_METHOD']=='POST' && ($this->input->post('employee_id')!=0 || $this->input->post('employee_id')!='')){
                    foreach($productsArray as $k=>$product){
                $orderedProducts[$product['id']] = $product;
                $delSql = 'Select * from daily_user_stocks where type="in" and created like "'.$today.'%" and product_id='.$product['id'].' and employee_id='.$this->input->post('employee_id');
                
                $todaysDelivery = $this->pktdblib->custom_query($delSql);
                if(count($todaysDelivery)>0){
                    $productwiseOrder['new'][$product['id']] = $todaysDelivery[0]['qty'];
                }else{
                    $productwiseOrder['new'][$product['id']] = 0;
                }
                
                
                $inStock = $this->pktdblib->custom_query('Select sum(qty) as qty from daily_user_stocks where type="in" and created < "'.$today.'" and product_id='.$product['id'].' and employee_id='.$this->input->post('employee_id'));
                if(count($inStock)>0){
                    $productwiseOrder['old'][$product['id']] = $inStock[0]['qty'];
                }else{
                    $productwiseOrder['old'][$product['id']] = 0;
                }
                $outStock = $this->pktdblib->custom_query('Select sum(qty) as qty from daily_user_stocks where type="out" and created < "'.$today.'" and product_id='.$product['id'].' and employee_id='.$this->input->post('employee_id'));
                if(count($outStock)>0){
                    $productwiseOrder['old'][$product['id']] = $productwiseOrder['old'][$product['id']]-$outStock[0]['qty'];
                }else{
                    $productwiseOrder['old'][$product['id']] = $productwiseOrder['old'][$product['id']];
                }
            }
                }
            }
        }
        
        /*echo '<pre>';//print_r($inStock);
        print_r($productwiseOrder);
        echo '</pre>';*/
        //exit;
        $deliveryBoys['options'] = [0=>'Select Delivery Boy'];
        $sql2 = 'Select concat(e.first_name, " ", e.middle_name, " ", e.surname," | ",e.emp_code) as employee, e.id, e.is_active from employees e where 1=1';
        //print_r($this->session->userdata('roles'));
        if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $sql2.=' and e.id='.$this->session->userdata('employees')['id'];
        }
        
        //echo $sql2;
		$employees = $this->pktdblib->custom_query($sql2);
		foreach($employees as $eKey=>$employee){
		    $active = ($employee['is_active'])?'Active':'Inactive';
            $deliveryBoys['options'][$employee['id']] = ucfirst($employee['employee'])." (".$active.")";
		    
		}
		$data['deliveryBoys'] = $deliveryBoys;
        $data['date'] = $today;
        $data['productwiseOrder'] = $productwiseOrder;
        //exit;
        $data['products'] = $orderedProducts;
        //print_r($productwiseOrder);
        $data['meta_title'] = "ERP";
		
		$data['title'] = "ERP : Moring Stock Report";
		$data['meta_description'] = "Field Member panel";
		$data['modules'][] = "fieldmember_panel";
		$data['methods'][] = "morning_stock_report_view";
		
		
		echo Modules::run("templates/fieldmember_template", $data); 
	}
	
	function morning_stock_report_view(){
	    
	    
	    //$sql = 'Select count(od.qty) as qty, od.variation, od.product_id, p.base_uom, p.is_pack from deliveryboy_order dbo inner join orders o on o.id=dbo.order_id inner join order_details od on od.order_id=o.id inner join products p on p.id=od.product_id where 1=1 and o.order_status_id in (9) and o.date like "'.date('Y-m-d', strtotime($today.' -1 day')).'%"';
	    /*$sql = 'Select sum(od.qty-od.return_quantity) as qty, od.variation, od.product_id, p.base_uom, p.is_pack from deliveryboy_order dbo inner join orders o on o.id=dbo.order_id inner join order_details od on od.order_id=o.id inner join products p on p.id=od.product_id where 1=1 and o.order_status_id in (9,5) and o.date < "'.$today.'"';
	    if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $sql.=' and dbo.employee_id='.$this->session->userdata('employees')['id'];
        }
        
        $sql.=' group by od.product_id, od.variation';
        
        //echo $sql;
        $oldOrders = $this->pktdblib->custom_query($sql);
        $productwiseOrder = ['old'=>[], 'new'=>[], 'return'=>[]];
        
        foreach($oldOrders as $oldKey => $oldOrder){
            $products[] = $oldOrder['product_id'];
            $attributeId = json_decode($oldOrder['variation'], true);
            if((NULL!==$attributeId['attribute']['product_attribute_id'] || $attributeId['attribute']['product_attribute_id']!==0) && $oldOrder['base_uom']>0){
                $attribute = $this->pktdblib->custom_query('Select a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$attributeId['attribute']['product_attribute_id'].'"');
                $baseUom = explode(" ", $oldOrder['base_uom']);
                
                $conversion = $this->pktlib->unit_convertion($attribute[0]['uom'], trim($baseUom[1]));
                //echo $oldOrder['product_id']." ".$oldOrder['qty']." ".$attribute[0]['uom']." ".$baseUom[1]." ".$conversion."<br>";
                if(array_key_exists($oldOrder['product_id'], $productwiseOrder['old'])){
                    $productwiseOrder['old'][$oldOrder['product_id']] = $productwiseOrder['old'][$oldOrder['product_id']]+($oldOrder['qty']*$conversion*$attribute[0]['unit']);
                }else{
                    $productwiseOrder['old'][$oldOrder['product_id']] = $oldOrder['qty']*$conversion*$attribute[0]['unit'];   
                }
            }else{
                if(array_key_exists($oldOrder['product_id'], $productwiseOrder['old'])){
                    
                    $productwiseOrder['old'][$oldOrder['product_id']] = $productwiseOrder['old'][$oldOrder['product_id']]+($oldOrder['qty']);
                }else{
                    $productwiseOrder['old'][$oldOrder['product_id']] = $oldOrder['qty'];
                }
                
            }
        }
        
        $sql2 = 'Select count(od.qty) as qty, od.variation, od.product_id, p.base_uom, p.is_pack from deliveryboy_order dbo inner join orders o on o.id=dbo.order_id inner join order_details od on od.order_id=o.id inner join products p on p.id=od.product_id where 1=1 and o.order_status_id=9 and o.date like "'.$today.'%"';
	    if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $sql2.=' and dbo.employee_id='.$this->session->userdata('employees')['id'];
        }
        
        $sql2.=' group by od.product_id, od.variation';
        //echo $sql2;
        $newOrders = $this->pktdblib->custom_query($sql2);
        
        foreach($newOrders as $oldKey => $newOrder){
            $products[] = $newOrder['product_id'];
            $attributeId = json_decode($newOrder['variation'], true);
            if((NULL!==$attributeId['attribute']['product_attribute_id'] || $attributeId['attribute']['product_attribute_id']!==0) && $newOrder['base_uom']>0){
                $attribute = $this->pktdblib->custom_query('Select a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$attributeId['attribute']['product_attribute_id'].'"');
                $baseUom = explode(" ", $newOrder['base_uom']);
                $conversion = $this->pktlib->unit_convertion($attribute[0]['uom'], trim($baseUom[1]));
                if(array_key_exists($newOrder['product_id'], $productwiseOrder['new'])){
                    $productwiseOrder['new'][$newOrder['product_id']] = $productwiseOrder['new'][$newOrder['product_id']]+($newOrder['qty']*$conversion*$attribute[0]['unit']);
                }else{
                    $productwiseOrder['new'][$newOrder['product_id']] = $newOrder['qty']*$conversion*$attribute[0]['unit'];   
                }
            }else{
                if(array_key_exists($newOrder['product_id'], $productwiseOrder['new'])){
                    
                    $productwiseOrder['new'][$newOrder['product_id']] = $productwiseOrder['new'][$newOrder['product_id']]+($newOrder['qty']);
                }else{
                    $productwiseOrder['new'][$newOrder['product_id']] = $newOrder['qty'];
                }
                
            }
        }
        
        
        
        
        $sql3 = 'Select count(od.qty) as qty, od.variation, od.product_id, p.base_uom, p.is_pack from deliveryboy_order dbo inner join orders o on o.id=dbo.order_id inner join order_details od on od.order_id=o.id inner join products p on p.id=od.product_id where 1=1 and o.order_status_id in (2) and o.date LIKE "'.date('Y-m-d', strtotime($today.' - 1 day')).'"';
	    if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $sql3.=' and dbo.employee_id='.$this->session->userdata('employees')['id'];
        }
        
        $sql3.=' group by od.product_id, od.variation';
        $sql3.=' UNION';
        $sql3.= ' Select count(od.return_quantity) as qty, od.variation, od.product_id, p.base_uom, p.is_pack from deliveryboy_order dbo inner join orders o on o.id=dbo.order_id inner join order_details od on od.order_id=o.id inner join products p on p.id=od.product_id where 1=1 and o.order_status_id in (5) and o.date  LIKE "'.date('Y-m-d', strtotime($today.' - 1 day')).'"';
	    if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $sql3.=' and dbo.employee_id='.$this->session->userdata('employees')['id'];
        }
        
        $sql3.=' group by od.product_id, od.variation';
        //echo $sql3;exit;
        $returnOrders = $this->pktdblib->custom_query($sql3);
        foreach($returnOrders as $returnKey => $returnOrder){
            $products[] = $returnOrder['product_id'];
            $attributeId = json_decode($returnOrder['variation'], true);
            if((NULL!==$attributeId['attribute']['product_attribute_id'] || $attributeId['attribute']['product_attribute_id']!==0) && $returnOrder['base_uom']>0){
                $attribute = $this->pktdblib->custom_query('Select a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$attributeId['attribute']['product_attribute_id'].'"');
                $baseUom = explode(" ", $returnOrder['base_uom']);
                $conversion = $this->pktlib->unit_convertion($attribute[0]['uom'], trim($baseUom[1]));
                if(array_key_exists($returnOrder['product_id'], $productwiseOrder['return'])){
                    $productwiseOrder['return'][$returnOrder['product_id']] = $productwiseOrder['return'][$returnOrder['product_id']]+($returnOrder['qty']*$conversion*$attribute[0]['unit']);
                }else{
                    $productwiseOrder['return'][$returnOrder['product_id']] = $returnOrder['qty']*$conversion*$attribute[0]['unit'];   
                }
            }else{
                if(array_key_exists($returnOrder['product_id'], $productwiseOrder['return'])){
                    
                    $productwiseOrder['return'][$returnOrder['product_id']] = $productwiseOrder['return'][$returnOrder['product_id']]+($returnOrder['qty']);
                }else{
                    $productwiseOrder['return'][$returnOrder['product_id']] = $returnOrder['qty'];
                }
                
            }
        }
        
        $productsArray = $this->pktdblib->custom_query('Select * from products where id in ("'.implode('","', array_unique($products)).'") order by product ASC');
        $orderedProducts = [];
        if(count($productsArray)>0){
            foreach($productsArray as $k=>$product){
                $orderedProducts[$product['id']] = $product;
            }
        }
        $data['productwiseOrder'] = $productwiseOrder;*/
        //echo 'Select * from daily_user_stocks where type="in" and created like "'.$today.'%"';
        
        $this->load->view("fieldmember_panel/morning_stock_report");
	}
	
	function evening_stock_report(){
	    $today = date('Y-m-d');
	    $yesterday = date('Y-m-d', strtotime('-1 day'));
	    $empId = 0;
        $productsArray = $this->pktdblib->custom_query('Select * from products where is_active=true and show_on_website=true');
        
        $orderedProducts = [];
        $productwiseOrder = ['old'=>[], 'new'=>[]];
        if(count($productsArray)>0){
            if(in_array(6, $this->session->userdata('roles'))){
            
                foreach($productsArray as $k=>$product){
                $orderedProducts[$product['id']] = $product;
                $delSql = 'Select * from daily_user_stocks where type="in" and created like "'.$today.'%" and product_id='.$product['id'].' and employee_id='.$this->session->userdata('employees')['id'];
                
                $todaysDelivery = $this->pktdblib->custom_query($delSql);
                if(count($todaysDelivery)>0){
                    $productwiseOrder['new'][$product['id']] = $todaysDelivery[0]['qty'];
                }else{
                    $productwiseOrder['new'][$product['id']] = 0;
                }
                
                
                $inStock = $this->pktdblib->custom_query('Select sum(qty) as qty from daily_user_stocks where type="in" and created < "'.$today.'" and product_id='.$product['id'].' and employee_id='.$this->session->userdata('employees')['id']);
                if(count($inStock)>0){
                    $productwiseOrder['old'][$product['id']] = $inStock[0]['qty'];
                }else{
                    $productwiseOrder['old'][$product['id']] = 0;
                }
                $outStock = $this->pktdblib->custom_query('Select sum(qty) as qty from daily_user_stocks where type="out" and created < "'.$today.'" and product_id='.$product['id'].' and employee_id='.$this->session->userdata('employees')['id']);
                if(count($outStock)>0){
                    $productwiseOrder['old'][$product['id']] = $productwiseOrder['old'][$product['id']]-$outStock[0]['qty'];
                }else{
                    $productwiseOrder['old'][$product['id']] = $productwiseOrder['old'][$product['id']];
                }
            }
            }else{
                if($_SERVER['REQUEST_METHOD']=='POST' && ($this->input->post('employee_id')!=0 || $this->input->post('employee_id')!='')){
                    foreach($productsArray as $k=>$product){
                        $orderedProducts[$product['id']] = $product;
                        $delSql = 'Select * from daily_user_stocks where type="in" and created like "'.$today.'%" and product_id='.$product['id'].' and employee_id='.$this->input->post('employee_id');
                        
                        $todaysDelivery = $this->pktdblib->custom_query($delSql);
                        if(count($todaysDelivery)>0){
                            $productwiseOrder['new'][$product['id']] = $todaysDelivery[0]['qty'];
                        }else{
                            $productwiseOrder['new'][$product['id']] = 0;
                        }
                        $inStock = $this->pktdblib->custom_query('Select sum(qty) as qty from daily_user_stocks where type="in" and created < "'.$today.'" and product_id='.$product['id'].' and employee_id='.$this->input->post('employee_id'));
                        if(count($inStock)>0){
                            $productwiseOrder['old'][$product['id']] = $inStock[0]['qty'];
                        }else{
                            $productwiseOrder['old'][$product['id']] = 0;
                        }
                        $outStock = $this->pktdblib->custom_query('Select sum(qty) as qty from daily_user_stocks where type="out" and created < "'.$today.'" and product_id='.$product['id'].' and employee_id='.$this->input->post('employee_id'));
                        if(count($outStock)>0){
                            $productwiseOrder['old'][$product['id']] = $productwiseOrder['old'][$product['id']]-$outStock[0]['qty'];
                        }else{
                            $productwiseOrder['old'][$product['id']] = $productwiseOrder['old'][$product['id']];
                        }
                    }
                }
            }
        }
        
        /*echo '<pre>';//print_r($inStock);
        print_r($productwiseOrder);
        echo '</pre>';*/
        //exit;
        $deliveryBoys['options'] = [0=>'Select Delivery Boy'];
        $sql2 = 'Select concat(e.first_name, " ", e.middle_name, " ", e.surname," | ",e.emp_code) as employee, e.id, e.is_active from employees e where 1=1';
        //print_r($this->session->userdata('roles'));
        if(!in_array(1, $this->session->userdata('roles')) && !in_array(2, $this->session->userdata('roles'))){
            $sql2.=' and e.id='.$this->session->userdata('employees')['id'];
        }
        
        //echo $sql2;
		$employees = $this->pktdblib->custom_query($sql2);
		foreach($employees as $eKey=>$employee){
		    $active = ($employee['is_active'])?'Active':'Inactive';
            $deliveryBoys['options'][$employee['id']] = ucfirst($employee['employee'])." (".$active.")";
		    
		}
		$data['deliveryBoys'] = $deliveryBoys;
        $data['date'] = $today;
        $data['productwiseOrder'] = $productwiseOrder;
        //exit;
        $data['products'] = $orderedProducts;
        //print_r($productwiseOrder);
        $data['meta_title'] = "ERP";
		
		$data['title'] = "ERP : Evening Stock Report";
		$data['meta_description'] = "Field Member panel";
		$data['modules'][] = "fieldmember_panel";
		$data['methods'][] = "evening_stock_report_view";
		
		
		echo Modules::run("templates/fieldmember_template", $data); 
	}
	
	function evening_stock_report_view(){
	    
        $this->load->view("fieldmember_panel/evening_stock_report");
	}
	

}
