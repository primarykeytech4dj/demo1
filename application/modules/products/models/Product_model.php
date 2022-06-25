<?php 

class Product_model extends CI_Model {

	private $database;
	private $table;
    function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
    }

    // Unique to models with multiple tables
	function set_table($table) {
		$this->table = $table;
	}
	
	// Get table from table property
    function get_table() {
		$table = $this->table;
		return $table;
    }

    // Retrieve all data from database and order by column return query
    function get($order_by) {
		$db = $this->database;
		$table = $this->get_table();
		$db->order_by($order_by);
		$query=$db->get($table);
		return $query;
    }

	// Limit results, then offset and order by column return query
    function get_with_limit($limit, $offset, $order_by) {
		$db = $this->database;
		$table = $this->get_table();
		$db->limit($limit, $offset);
		$db->order_by($order_by);
		$query=$db->get($table);
		return $query;
    }

	// Get where column id is ... return query
    function get_where($id) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where('id', $id);
		$query=$db->get($table);
		return $query->row_array();
    }

	// Get where custom column is .... return query
    function get_where_custom($col, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($col, $value);
		$query=$db->get($table);
		return $query;
    }

	function get_list($id = NULL) {
		$db = $this->database;
		$table = $this->get_table();
		if (empty($id)) {
            $query = $db->get($table);
            return $query->result_array();
        }

        $query = $db->get_where($table, array('id' => $id));
        return $query->row_array();
	}


	function get_active_service_list($id = NULL) {
		$condition['is_active'] = TRUE;
		$condition['product_type'] = 2;
		if (empty($id))
        {
            $query = $this->db->get_where('products', $condition);
            return $query->result_array();
        }
        $condition['id'] = $id;
        $query = $this->db->get_where('products', $condition);
        return $query->row_array();
	}

	function get_category_dropdown_list($id = NULL) {
		$db = $this->database;
		$condition['is_active'] = TRUE;
		$db->select('id,category_name');
        $query = $db->get_where('product_categories', $condition);
        return $query->result_array();
	}

	function _insert($data) {
		$response['status'] = 'failed';
		$db = $this->database;
		$table = $this->get_table();
		$res = $db->insert($table, $data);
		//print_r($res);
		if($res){
			$response['status'] = 'success';
			$response['id'] = $db->insert_id();
		}else{
			echo $db->_error_message();
		}
   		return  $response;
    }

    function _insert_multiple($data) {
    	$db = $this->database;
    	$table = $this->get_table();
    	$num_rows = $db->insert_batch($table,$data);
    	return $num_rows;
    }

    function update_product_categories($id, $data) {
    	$db = $this->database;
    	$table = $this->get_table();
    	$db->where('id', $id);
    	$update = $db->update('product_categories', $data);
    	//print_r($db->last_query());
    	return $update;
    }

   	function _update($id, $data) {
    	$db = $this->database;
    	$table = $this->get_table();
    	$db->where('id', $id);
    	$update = $db->update($table, $data);
    	return $update;
    } 

	function get_product_category_list($id=null) {
		$db = $this->database;
		$condition['is_active'] = true;
		
		$db->select('id,category_name')->where('`id` NOT IN (SELECT product_category_id from `products`)');
		$query = $db->get_where('product_categories', $condition);
		//print_r($db->last_query());
		return $query->result_array();
	}		

	function get_all_product_categories() {
		$db = $this->database;
    	$table = $this->get_table();
    	$condition['is_active'] = true;
    	$db->select('id, category_name, parent_id');
    	$query = $db->get_where($table, $condition);
    	return $query->result_array();
	}

	function get_slugwise_category($slug){
		$db = $this->database;
		$table = $this->get_table();
		$query = $db->get_where($table, ['slug'=>$slug]);
		return $query->row_array();
	}

	function get_categorywise_product($categoryId){
		$db = $this->database;
		$table = $this->get_table();
		$db->select($table.'.*, product_images.image_name_1, product_images.image_name_2');

		$db->join('product_images', 'product_images.product_id='.$table.'.id AND product_images.featured_image = 1', 'left');
		$query = $db->get_where($table, ['product_category_id'=>$categoryId, $table.'.is_active'=>true]);
		return $query->result_array();
	}

	function get_slugwise_product($slug){
		//echo "reached in product_model";exit;
		$db = $this->database;
		$table = $this->get_table();
		$db->select([$table.'.*', 'product_images.image_name_1', 'product_images.image_name_2', 'product_details.discount_type', 'product_details.discount', 'product_details.in_stock_qty','(CASE WHEN product_details.discount_type="percentage" THEN products.base_price+(products.base_price*(product_details.discount/100)) WHEN product_details.discount_type="value" THEN products.base_price+product_details.discount ELSE products.base_price END) as actual_price']);
		$db->join('product_images', 'product_images.product_id='.$table.'.id AND product_images.featured_image = 1', 'left');
		$db->join('product_details', 'product_details.product_id='.$table.'.id', 'left');
		$query = $db->get_where($table, ['products.slug'=>$slug]);
		/*print_r($query->row_array());*/
		//print_r($db->last_query());exit;
		return $query->row_array();
	}

	function get_related_products($categoryId, $productId){
		$db = $this->database;
		$table = $this->get_table();
		$condition['is_active'] = true;
		$db->join('product_images', 'product_images.product_id='.$table.'.id');
		$db->select($table.'.*, product_images.image_name_1, product_images.image_name_2')->where($table.'.id != "'.$productId.'" AND product_category_id='.$categoryId.' AND '.$table.'.is_active=1 and product_images.featured_image=true and products.show_on_website=true');
		$db->order_by($table.'.id DESC');
		$db->limit(3, 0);
		$query = $db->get($table);
		//print_r($db->last_query());exit;
		return $query->result_array();
	}

	function update_multiple($field, $data) {
		$db = $this->database;
		$table = $this->get_table();
		$updt = $db->update_batch($table, $data, $field);
		return $updt;
	}

	function get_active_list($id = NULL) {
		$db = $this->database;
		$table = $this->get_table();
		$condition['is_active'] = TRUE;
		//$db->select(['products.*', 'product_categories.category_name']);
		//$db->join('product_categories', 'product_categories.id = products.product_category_id');
		
		if (empty($id))
        {
			//print_r("reached here");exit;
            $query = $this->db->get_where('products', $condition);
            return $query->result_array();
        }
        $condition['id'] = $id;
        $query = $this->db->get_where('products', $condition);
       //print_r($db->last_query());
        return $query->row_array();
	}

	function get_product_list($conditions){
		//print_r($conditions);exit;
		$db = $this->database;
		$table = $this->get_table();
		//echo $conditons;exit;
		$db->select(['products.*', 'product_categories.category_name', 'product_images.image_name_1', 'product_images.image_name_2', 'product_details.discount_type', 'product_details.discount', 'product_details.in_stock_qty', '(CASE WHEN product_details.discount_type="percentage" THEN products.base_price+(products.base_price*(product_details.discount/100)) WHEN product_details.discount_type="value" THEN products.base_price+product_details.discount ELSE products.base_price END) as actual_price']);
		
		$db->join('product_categories', 'product_categories.id = products.product_category_id');
		$db->join('product_images', 'product_images.product_id='.$table.'.id AND product_images.featured_image = 1', 'left');
		$db->join('product_details', 'product_details.product_id='.$table.'.id AND product_details.in_stock_qty>0', 'inner');
		foreach ($conditions as $key => $condition) {
			if($key=='companies_products.company_id'){
				$db->join('companies_products', 'companies_products.product_id='.$table.'.id', 'left');
			}
			$db->where($key, $condition);

		}

		//$db->order_by('is_active desc');
		$db->order_by('created DESC');
		$query = $db->get($table);
		//print_r($db->last_query());
		/*echo '<pre>';
		print_r($query->result_array());exit;*/
		return $query->result_array(); 
	}
	function get_product_image_list($conditions){

		//echo "model";print_r($conditions);exit;
		$db = $this->database;
		$table = $this->get_table();
		$db->select(['product_images.*','products.product']);
		$db->join('products', 'products.id = product_images.product_id');
		foreach($conditions as $key => $condition) {
			$db->where($key, $condition);
		}
		$query = $db->get($table);
		//print_r($query->result_array());exit;
		return $query->result_array();
	}

	function get_category_list($condition = []){
    	$db = $this->database;
		$table = $this->get_table();
		//$condition[$table.'.is_active'] = true;
//db->join($table.' c2', 'c2.parent_id='.$table.'.id', 'left');
		$db->select($table.'.*, (select category_name from product_categories pc where pc.id='.$table.'.parent_id) as parent');
		$db->order_by($table.'.is_active desc, '.$table.'.id DESC');
		//$db->limit(3, 0);
		$query = $db->get_where($table, $condition);
		//print_r($db->last_query());
		return $query->result_array();
    }

    function get_categorylist_for_product() {
		$db = $this->database;
		$condition['is_active'] = true;
		
		$db->select('id,category_name')->where('`id` NOT IN(SELECT distinct `parent_id` from `product_categories`)');
		$query = $db->get_where('product_categories', $condition);
		return $query->result_array();
	}

		function get_where_product($id) {
		$db = $this->database;
		$table = $this->get_table();
		//echo $id;
		$db->where('products.id', $id);
		$db->select(['products.*', 'product_categories.category_name']);
		$db->join('product_categories', 'product_categories.id = products.product_category_id','left');
		/*foreach ($conditions as $key => $condition) {
			$db->where($key, $condition);

		}*/
		$query = $db->get_where($table);
		/*echo '<pre>';
		print_r($db->last_query());
		print_r($query->row_array());*/
		return $query->row_array();
    }

	function get_product_details($conditions){
		$db = $this->database;
		$table = $this->get_table();
		//$conditon = [];
		//$condition['id'] = $id;
		$db->select(['products.*', 'product_categories.category_name', 'product_categories.gst']);
		$db->join('product_categories', 'product_categories.id = products.product_category_id', 'left');
		foreach ($conditions as $key => $condition) {
			$db->where($key, $condition);

		}
		$query = $db->get($table);
		 
		return $query->row_array(); 

	}

	function get_product_wise_pack_product($conditions = []) {
    	$db =  $this->database;
    	$table = $this->get_table();
        $this->db->select(['pack_products.*', 'products.product']);
        $db->join('products', 'products.id = pack_products.product_id');
        $query = $db->get_where($table, $conditions);
        //print_r($db->last_query());
        return $query->result_array();
    }

    function product_wise_product_images($conditions = []) {
    	$db =  $this->database;
        $this->db->select(['products.*', 'product_images.image_name_1', 'product_images.image_name_2']);
        $db->join('product_images', 'products.id = product_images.product_id');
        $query = $db->get_where('products', $conditions);
        //print_r($db->last_query());exit;
        return $query->result_array();
    }

    function _get_all_products($params = array()){
        $this->db->select(["products.*", "product_images.image_name_1", "manufacturing_brands.brand", "product_images.image_name_2", "product_details.discount_type", "product_details.discount", "product_details.in_stock_qty", "ROUND((CASE WHEN product_details.discount_type='percentage' THEN products.base_price-(products.base_price*(product_details.discount/100.00)) WHEN product_details.discount_type='value' THEN products.base_price-product_details.discount ELSE products.base_price END), 2) as actual_price", "base_uom as default_uom", "base_price as mrp"]);
        $this->db->from('products');
        $this->db->join('product_categories', 'product_categories.id=products.product_category_id', 'inner');
        $this->db->join('product_images', 'product_images.product_id=products.id', 'left');
        $this->db->join('product_details', 'product_details.product_id=products.id', 'left');
        $this->db->join('brand_products', 'brand_products.product_id=products.id', 'left');
        $this->db->join('manufacturing_brands', 'manufacturing_brands.id=brand_products.brand_id', 'left');
        $this->db->where(['products.is_active' => 1, 'products.show_on_website'=>1, 'product_categories.is_active'=>true]);
        //print_r($params);
        if(isset($params['condition'])){

            if(isset($params['condition']['product_category_id'])){
                $this->db->where(['products.product_category_id'=>$params['condition']['product_category_id']]);
            }

            if(isset($params['condition']['productslug'])){
                $this->db->where(['products.slug'=>$params['condition']['productslug']]);
            }
            
            if(isset($params['condition']['brand'])){
                $brands = implode('","', $params['condition']['brand']);
                $this->db->where('bp.brand_id in ("'.$brands.'")');
            }
            
            if(isset($params['condition']['search']) && !empty(trim($params['condition']['search']))){
                $this->db->where('products.product like "%'.$params['condition']['search'].'%"');
                //unset($params['condition']['available_at']);
            }
            
        }
        $this->db->order_by('products.priority ASC, products.created asc');
        //set start and limit
        /*if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }*/
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            /*if($params['start']==0){
                $this->db->limit($params['limit'],$params['start']);
            }else*/
            $this->db->limit($params['limit'],$params['start']*$params['limit']);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            return $query->num_rows(); 
        }else{
            return $query->result_array();
        }
    }

}
?>