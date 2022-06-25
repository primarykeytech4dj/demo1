<?php 
class Cart_model extends CI_Model {
	private $database;
	private $table;
	function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
	}

	// Function to retrieve an array with all product information
    function retrieve_products(){
        $query = $this->db->get('products'); // Select the table products
        return $query->result_array(); // Return the results in a array.
    }   

	    // Add an item to the cart
	function validate_add_cart_item(){
	     
	    $id = $this->input->post('product_id'); // Assign posted product_id to $id
	    $qty = $this->input->post('quantity'); // Assign posted quantity to $qty
	    $sql = '';
	    //print_r($_POST);
	    $sql ='Select p.*, pi.image_name_1';
	    if(isset($_POST['options']['attribute']['product_attribute_id']) && $_POST['options']['attribute']['product_attribute_id']!=0){
	    	$sql.=', pa.mrp, pa.discount, pa.increased_percentage, concat(a.unit, " ", a.uom) as attribute, pa.price';
	    }else{ //echo "hii";
	    	$sql.=', p.base_price as mrp, "0" as discount, "0" as increased_percentage, base_uom as attribute, p.base_price as price';
	    }

	    $sql.=" from products p left join product_images pi on pi.product_id=p.id AND pi.featured_image=1";

	    if(isset($_POST['options']['attribute']['product_attribute_id']) && $_POST['options']['attribute']['product_attribute_id']!=0){
	    	$sql.=' inner join product_attributes pa on pa.product_id=p.id and pa.product_id='.$id.' AND pa.id='.$_POST['options']['attribute']['product_attribute_id'];
	    	$sql.=' inner join attributes a on a.id=pa.attribute_id';
	    }
	    $sql.=' where p.is_active=true and p.show_on_website=true AND p.id='.$id;
	    $query = $this->db->query($sql);
    	// Check if a row has matched our product id
	    if($query->num_rows() > 0){
	    	// We have a match!
	        foreach ($query->result() as $row)
	        {
	        	$price = 0;
	        	if(isset($_POST['options']['attribute']['product_attribute_id']) && $_POST['options']['attribute']['product_attribute_id']!=0){
	        	    
	        		//$calc = Modules::run('products/attribute_wise_mrp', ['productId'=>$row->id, 'attribute'=>$row->attribute, 'unit'=>1]);
	        		//print_r($calc);exit;
	        		//$price = $calc['final_price']-($calc['final_price']*$row->discount)/100.00;;
	        		$price = $row->price;//$row->mrp-($row->mrp*$row->discount)/100.00;
	        	}else{
	        		$price = $row->base_price;
	        	}
	            // Create an array with product information
	            $data = array(
	                'id'      => $id,
	                'coupon'=>(NULL!==$this->input->post('coupon'))?$this->input->post('coupon'):NULL,
	                'qty'     => $qty,
	                'price'   => number_format($price, 2, '.', ''),
	                'name'    => $row->product,
	                'image'	=> $row->image_name_1,
	                'slug' => $row->slug,
	                'product_code'=>$row->product_code,
	                'options'=>(NULL!==$this->input->post('options'))?$this->input->post('options'):NULL
	                
	            );
	            /*echo '<pre>';
	            print_r($data);
	            exit;*/
	            
	            $this->cart->insert($data); 
	            return TRUE; // Finally return TRUE
	        }
	     
	    }else{
	        // Nothing found! Return FALSE! 
	        return FALSE;
	    }
	}

	function validate_update_cart(){
		$total = $this->cart->total_items();
		/*echo '<pre>';
		print_r($this->input->post());
		exit;*/
		$item = $this->input->post('rowid');
	    $qty = $this->input->post('qty');
	    $option = $this->input->post('options');
		$counter = 0;
		foreach ($this->cart->contents() as $key => $content) 
		{ //echo "hii";
			// Create an array with the products rowid's and quantities. 
			$data = array(
               'rowid' => $item[$counter],
               'qty'   => $qty[$counter],
               'options'=>$option[$counter]
            );
            
            // Update the cart with the new information
            //print_r($data);
			$this->cart->update($data);
			$counter++;
		}
		
	}
}