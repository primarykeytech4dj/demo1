<?php 

class Cart extends MY_Controller {
	function __construct() {
		parent::__construct();

		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->model('cart/cart_model');
	}

    function index()
	{
    	$productVariation = [];
    	$inStockQty = [];
    	$this->pktdblib->set_table('product_details');
    	foreach ($this->cart->contents() as $key => $item){
    		//echo $item['id'];
    		$query = $this->pktdblib->custom_query('Select in_stock_qty from product_details where product_id='.$item['id']);
    		//print_r($query);exit;
    		$stock = $query[0];
    		//print_r($stock);
    		$inStockQty[$item['id']] = $stock['in_stock_qty'];
    		$cartItems = $this->pktdblib->custom_query("select variations.name, variations.value from product_variations inner join variations on variations.id = product_variations.variation_id where product_id = ".$item['id']);

    		foreach ($cartItems as $itemKey => $itemValue) {
    			$productVariation[$item['rowid']][$itemValue['name']][] = $itemValue['value'];
    		}
    	}
    	//exit;
    	$data['stock'] = $inStockQty;
    	//print_r($data['stock']);exit;
    	$data['products'] = $productVariation;
	    $data['content'] = 'cart/index'; 
    	$data['title'] = 'Cart';
		$data['meta_title'] = 'Cart';
		$data['meta_description'] = 'Cart';
		$data['meta_keyword'] = 'Cart';
		$data['otherCharges'] = $this->get_other_charges();
		echo Modules::run('templates/default_template', $data);
    	
	}

	function add_cart_item(){
	    $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            $_POST = $params;
            if(isset($_POST['cart']) && !empty($_POST['cart'])){
                $this->cart->insert($_POST['cart']);
                
                
                if(NULL!==$_POST['user_id'] && NULL!==$_POST['login_id'] && NULL!==$_POST['user_type']){
                    check_user_login(FALSE);
                    if(isset($_POST['order']) && $_POST['order']!='')
                        $this->session->set_userdata(['order'=>$_POST['order']]);
                    if(isset($_POST['shipping_address_id']) && $_POST['shipping_address_id']!='')
                        $this->session->set_userdata(['shipping_address_id'=>$_POST['shipping_address_id']]);
                    if(isset($_POST['billing_address_id']) && $_POST['billing_address_id']!='')
                        $this->session->set_userdata(['billing_address_id'=>$_POST['billing_address_id']]);
                }
                
                if(NULL===($this->input->post('product_id')) && (isset($_POST['cart']) && !empty($_POST['cart']))){
                    $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'cart'=>$this->session->userdata('cart_contents')]);
                }
                
                //print_r($this->session->userdata('cart_contents'));exit;
            }
            
        }
        //print_r($_POST);exit;
     	if(NULL===$this->input->post('options') && NULL!==($this->input->post('product_id'))){ 
			$productId = $this->input->post('product_id');
			$productVariations = $this->pktdblib->custom_query('Select variations.name, variations.value from variations inner join product_variations on product_variations.variation_id=variations.id and product_variations.is_active=true where product_id='.$productId.' and variations.is_active=true group by name');
			//print_r($productVariations);exit;
			if(count($productVariations)>0){
				foreach ($productVariations as $key => $variation) {
					//print_r($variation);exit;
					$_POST['options'][$variation['name']] = $variation['value'];
				}
			}
		}
		//print_r($_POST);exit;
	    if($this->cart_model->validate_add_cart_item() == TRUE){
	        
	    	if(NULL!==$this->session->userdata('order')){
				//print_r($this->session->userdata('order'));exit;
				$updateOrder = Modules::run('orders/edit', $this->session->userdata('cart_contents'));
			}
	        
	        // Check if user has javascript enabled
	        if($this->input->post('ajax') != '1'){
	           /*print_r($this->session->userdata('cart_contents'));
	           exit;*/
	           $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'cart'=>$this->session->userdata('cart_contents')]);
	            
	            redirect('cart'); // If javascript is not enabled, reload the page with new data
	        }else{
	            echo 'true'; // If javascript is enabled, return true, so the cart gets updated
	        }
	    }else{
	         $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'failure', 'msg'=>'Invalid Product Details']);
	    }
	}

	function show_cart(){
		

		$data['otherCharges'] = $this->get_other_charges();
		$this->load->view('cart/header_cart', $data);
	}

	function get_other_charges($arr=[]){
	    //print_r($arr);exit;
	   // echo "reached here";exit;
	    $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            $this->cart->destroy();
            $this->cart->insert($params['cart']);
            
        }
		$this->pktdblib->set_table('other_charges');
		$sql = 'Select * from other_charges where is_active=true and "'.date('Y-m-d').'" between valid_from and valid_till';
		if(NULL!==$this->cart->total()){
			$sql.=' and '.$this->cart->total().' between min_value and max_value';
		}
		//echo $sql;exit;
		$otherCharges = $this->pktdblib->custom_query($sql);
		$charges = [];
		foreach ($otherCharges as $key => $value) {
			$value['cost'] = ($value['charge_in']=='percent')?(($value['value']/100.00)*$this->cart->total()):$value['value'];
			$charges[$value['type']] = $value;
		}
		
		if(isset($arr['responsetype']) && $arr['responsetype']=="web"){
		    //echo "hii";
		}else{ //echo "hello";
		    $this->pktlib->parseOutput($this->config->item('response_format'), ['status'=>'success', 'other_charges'=>$charges]);
		}
        
		return $charges;
	}

	function update_cart(){
	    $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            
            $_POST = $params;
            if(isset($_POST['cart']) && !empty($_POST['cart'])){
                $this->cart->insert($_POST['cart']);
                
                
                if(NULL!==$_POST['user_id'] && NULL!==$_POST['login_id'] && NULL!==$_POST['user_type']){
                    check_user_login(FALSE);
                    if(isset($_POST['order']) && $_POST['order']!='')
                        $this->session->set_userdata(['order'=>$_POST['order']]);
                }
                
                //print_r($this->session->userdata('cart_contents'));exit;
            }
            
        }
        
		$updateCart = $this->cart_model->validate_update_cart();
		/*echo '<pre>';
        print_r($_POST);
        print_r($_SESSION);
        exit;*/
		//print_r($this->session->userdata('order'));
		if(NULL!==$this->session->userdata('order')){
			//print_r($this->session->userdata('order'));exit;
			$updateOrder = Modules::run('orders/edit', $this->session->userdata('cart_contents'));
			//print_r($updateOrder);
			//exit;
		}
		if($this->input->post('ajax') != '1'){
			if(NULL!==$this->input->post('request_url'))
				redirect($this->input->post('request_url'));
			else
            	redirect('cart'); // If javascript is not enabled, reload the page with new data
        }else{
            echo 'true'; // If javascript is enabled, return true, so the cart gets updated
        }
	}

	function empty_cart(){
		$this->cart->destroy(); // Destroy all cart data
		redirect('cart'); // Refresh te page
	}
     
}
	