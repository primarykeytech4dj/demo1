<?php 

class Checkout extends MY_Controller {
	function __construct() {
		parent::__construct();

		/*foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
			}
		}*/
        
		check_user_login(TRUE);
		$this->load->model('checkout/checkout_model');
	}

    function index()
	{
		/*echo '<pre>';
		print_r($this->session->userdata());exit;*/
        
		if(NULL===$this->session->userdata('customers')){
			redirect('login');
		}
		$productVariation = [];
		$inStockQty = [];
        $this->pktdblib->set_table('product_details');
    	foreach ($this->cart->contents() as $key => $item){
            $query = $this->pktdblib->custom_query('Select in_stock_qty from product_details where product_id='.$item['id']);
            //print_r($query);exit;
            $stock = $query[0];
            $inStockQty[$item['id']] = $stock['in_stock_qty'];
    	    //print_r($item);
    		$cartItems = $this->pktdblib->custom_query("select variations.name, variations.value from product_variations inner join variations on variations.id = product_variations.variation_id where product_id = ".$item['id']);

    		foreach ($cartItems as $itemKey => $itemValue) {
    			$productVariation[$item['rowid']][$itemValue['name']][]= $itemValue['value'];
    		}
    	}
        $data['stock'] = $inStockQty;
    	//exit;
    	/*echo '<pre>';
    	print_r($_SESSION);
    	exit;*/
    	if(NULL===$this->session->userdata('order') && NULL!==$this->session->userdata('cart_contents')){
    	    $deliveryAddress = $this->session->userdata('address');
            if(count($deliveryAddress)>1){
                 foreach($deliveryAddress as $key=> $address){
                    if($address['is_default']){
                        $_SESSION['shipping_address_id'] = $_SESSION['billing_address_id'] = $address['id'];
                        break;
                    }
                    
                    if(NULL===$_SESSION['shipping_address_id']){
                        $_SESSION['shipping_address_id'] = $_SESSION['billing_address_id'] = $deliveryAddress[0]['id'];
                    }
                 }
            }else{
                $_SESSION['shipping_address_id'] = $_SESSION['billing_address_id'] = $deliveryAddress[0]['id'];
            }
    	    //print_r($this->session->userdata('cart_contents'));exit;
    	    //$_SESSION['cart_contents']['delivery_address_id'] = 
    		$order = Modules::run('orders/add', $this->session->userdata('cart_contents'));
    		//print_r($this->session->userdata('cart_contents'));
    		//exit;
    	}else{
    		$order = Modules::run('orders/edit', $this->session->userdata('cart_contents'));
    		/*echo "updtae";
    		exit;*/
    	}
    	
    	$data['products'] = $productVariation;
	    $data['content'] = 'checkout/index'; 
    	$data['title'] = 'Cart';
		$data['meta_title'] = 'Cart';
		$data['meta_description'] = 'Cart';
		$data['meta_keyword'] = 'Cart';
		$data['otherCharges'] = Modules::run('cart/get_other_charges');
		echo Modules::run('templates/default_template', $data);
    	
	}

	
}
	