<?php 
class Checkout_model extends CI_Model {
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
	    $this->db->select(['products.*', 'product_images.image_name_1']);
	    $this->db->join('product_images', 'product_images.product_id=products.id AND product_images.featured_image=1', 'left');
	    $this->db->where('products.id', $id); // Select where id matches the posted id
	    $this->db->where('products.is_active', true); // Select where id matches the posted id
	    $this->db->where('products.show_on_website', true); // Select where id matches the posted id
    	$query = $this->db->get('products', 1); 
    	//echo $this->db->last_query();
	    // Check if a row has matched our product id
	    if($query->num_rows() > 0){
	    	//echo "hello";exit;
	   // print_r($query->result_array());exit;
	     
		    // We have a match!
	        foreach ($query->result() as $row)
	        {
	        	//print_r($row);exit;
	            // Create an array with product information
	            $data = array(
	                'id'      => $id,
	                'qty'     => $qty,
	                'price'   => $row->base_price,
	                'name'    => $row->product,
	                'image'	=> $row->image_name_1,
	                'slug' => $row->slug,
	                'product_code'=>$row->product_code
	            );
	 
	            // Add the data to the cart using the insert function that is available because we loaded the cart library
	            $this->cart->insert($data); 
	             
	            return TRUE; // Finally return TRUE
	        }
	     
	    }else{
	        // Nothing found! Return FALSE! 
	        return FALSE;
	    }
	}

	function validate_update_cart(){
		
		// Get the total number of items in cart
		$total = $this->cart->total_items();
		//echo $total;
		// Retrieve the posted information
		$item = $this->input->post('rowid');
	    $qty = $this->input->post('qty');
	    //print_r($qty);
		// Cycle true all items and update them
		$counter = 0;
		foreach ($this->cart->contents()as $key => $content) 
		{ //echo "hii";
			// Create an array with the products rowid's and quantities. 
			$data = array(
               'rowid' => $item[$counter],
               'qty'   => $qty[$counter]
            );
            
            // Update the cart with the new information
            //print_r($data);
			$this->cart->update($data);
			$counter++;
		}
		/*echo '<pre>';
		print_r($this->cart->contents());
		exit;*/

	}
}