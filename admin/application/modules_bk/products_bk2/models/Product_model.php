<?php 

class Product_model extends CI_Model {

	private $database;
	private $table;
    function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
    }

    function check_table($table=''){
        if(empty($table))
            return FALSE;

        $query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
        $res = $query->row_array();

        return $res;
    }

    function tbl_product_categories(){
        $check = $this->check_table('product_categories');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `product_categories` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `parent_id` int(11) NOT NULL,
				  `category_name` varchar(255) NOT NULL,
				  `description` longtext NOT NULL,
				  `slug` varchar(255) NOT NULL,
				  `gst` varchar(255) NOT NULL,
				  `image_name_1` varchar(255) NOT NULL,
				  `image_name_2` varchar(255) NOT NULL,
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL,
				  `meta_keyword` varchar(160) NOT NULL,
				  `meta_title` varchar(160) NOT NULL,
				  `meta_description` varchar(160) NOT NULL,
				  PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

	        if($query){
	            
				$product = $this->db->query("CREATE TABLE IF NOT EXISTS `products` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `product_category_id` int(11) NOT NULL,
					  `product_type` int(11) NOT NULL,
					  `product` varchar(255) NOT NULL,
					  `product_code` varchar(255) NOT NULL,
					  `slug` varchar(255) DEFAULT NULL,
					  `base_price` float(10,2) NOT NULL,
					  `is_active` tinyint(1) NOT NULL DEFAULT '1',
					  `created` datetime NOT NULL,
					  `modified` datetime NOT NULL,
					  `description` longtext NOT NULL,
					  `meta_title` varchar(160) NOT NULL,
					  `meta_description` varchar(160) NOT NULL,
					  `meta_keyword` varchar(160) NOT NULL,
					  `is_pack` tinyint(1) NOT NULL DEFAULT '0',
					  `show_on_website` tinyint(1) NOT NULL DEFAULT '1',
					  `is_sale` tinyint(1) NOT NULL DEFAULT '0',
					  `is_new` tinyint(1) NOT NULL DEFAULT '0',
					  `is_gift` tinyint(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (id),
					  UNIQUE KEY (product_code)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
				);

				$packProducts = $this->db->query("CREATE TABLE IF NOT EXISTS `pack_products` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`basket_id` int(11) NOT NULL,
					`product_id` int(11) NOT NULL,
					`quantity` varchar(255) NOT NULL,
					`unit` varchar(255) NOT NULL,
					`base_price` float(10,2) NOT NULL,
					`priority` int(11) NOT NULL,
					`is_active` tinyint(1) NOT NULL DEFAULT '1',
					`created` datetime NOT NULL,
					`modified` datetime NOT NULL,
					PRIMARY KEY (id)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
				);

				$productDocument = $this->db->query("CREATE TABLE `product_documents` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`product_id` int(11) NOT NULL,
					`document_id` int(11) NOT NULL,
					`type` varchar(255) NOT NULL,
					`is_active` tinyint(1) NOT NULL DEFAULT '1',
					`created` datetime NOT NULL,
					`modified` datetime NOT NULL,
					PRIMARY KEY (id)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
				);

				$productImages = $this->db->query("CREATE TABLE `product_images` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`image_name_1` varchar(255) NOT NULL,
					`image_name_2` varchar(255) NOT NULL,
					`product_id` int(11) NOT NULL,
					`featured_image` tinyint(1) NOT NULL DEFAULT '0',
					`priority` int(11) NOT NULL DEFAULT '0',
					`title` varchar(255) NULL,
					`type` varchar(255) NOT NULL DEFAULT 'image',
					`is_active` tinyint(1) NOT NULL DEFAULT '1',
					`created` datetime NOT NULL,
					`modified` datetime NOT NULL,
					PRIMARY KEY (id)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
				);

				$productDetails = $this->db->query("CREATE TABLE `product_details` (`id` int(11) NOT NULL AUTO_INCREMENT,
					  `product_id` int(11) NOT NULL,
					  `location` varchar(255) NOT NULL,
					  `area` varchar(255) NOT NULL,
					  `landarea` varchar(255) NOT NULL,
					  `status` varchar(255) NOT NULL,
					  `short_name` varchar(255) NOT NULL,
					  `bedroom` int(11) NOT NULL,
					  `bathroom` int(11) NOT NULL,
					  `is_active` tinyint(1) NOT NULL DEFAULT '1',
					  `created` datetime NOT NULL,
					  `modified` datetime NOT NULL,
					  PRIMARY KEY (id)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
				); 

				$companyProduct = $this->db->query("CREATE TABLE IF NOT EXISTS `companies_products` (
				  `company_id` int(11) NOT NULL,
				  `product_id` int(11) NOT NULL,
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

				$variation = $this->db->query("CREATE TABLE IF NOT EXISTS`variations` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) NOT NULL,
				  `value` varchar(255) NOT NULL,
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL,
				  PRIMARY KEY(`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

				$productVariation = $this->db->query("CREATE TABLE IF NOT EXISTS`product_variations` (
				  `product_id` int(11) NOT NULL,
				  `variation_id` int(11) NOT NULL,
				  `is_active` tinyint(4) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

				$brandProducts = $this->db->query("CREATE TABLE IF NOT EXISTS`brand_products` (
				  `brand_id` int(11) NOT NULL,
				  `product_id` int(11) NOT NULL,
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

				$companyProductCategories = $this->db->query("CREATE TABLE IF NOT EXISTS `companies_product_categories` (
				  `company_id` int(11) NOT NULL,
				  `product_category_id` int(11) NOT NULL,
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

				$adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'Products', '#', 'fa-shopping-bag', 0, 3, 'products', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
	            $menuId = $this->db->insert_id();
	            if($menuId){
					$arr = [
						['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'View Product Categories', 'slug'=>'products/adminindexcategory', 'class'=>'fa-shopping-bag', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'products', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
						['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Product Category', 'slug'=>'products/newcategory', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'products', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
					['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'View Products', 'slug'=>'products/adminindex', 'class'=>'fa-shopping-bag', 'is_custom_constant'=>0, 'priority'=>3, 'module'=>'products', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
					['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Product', 'slug'=>'products/newproduct', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>4, 'module'=>'products', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],

					];

					$ins = $this->db->insert_batch('temp_menu', $arr);

					$menuRolesArray[0] = ['menu_detail_id'=>$menuId, 'role_id'=>1, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];

					$menus = $this->db->query('Select id from temp_menu where parent_id="'.$menuId.'"'); 
					foreach ($menus->result_array() as $key => $menu) {
						$menuRolesArray[count($menuRolesArray)] = ['menu_detail_id'=>$menu['id'], 'role_id'=>1, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];
					}

					$ins = $this->db->insert_batch('menu_roles', $menuRolesArray);			
	        	}
	            return $query;
	        }
	        else
	            return TRUE;
	        
	    }
	}

	 function tbl_product_details(){
        $check = $this->check_table('product_details');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE `product_details` (`id` int(11) NOT NULL,
					  `product_id` int(11) NOT NULL,
					  `location` varchar(255) NOT NULL,
					  `area` varchar(255) NOT NULL,
					  `landarea` varchar(255) NOT NULL,
					  `status` varchar(255) NOT NULL,
					  `short_name` varchar(255) NOT NULL,
					  `bedroom` int(11) NOT NULL,
					  `bathroom` int(11) NOT NULL,
					  `is_active` tinyint(1) NOT NULL DEFAULT '1',
					  `created` datetime NOT NULL,
					  `modified` datetime NOT NULL,
					  PRIMARY KEY (id)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
				);

	        if($query){
	            
	            return $query;
	        }
	        else
	            return TRUE;
	        
	    }
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
		$db = $this->database;
		$table = $this->get_table();
		$db->select($table.'.*, product_images.image_name_1, product_images.image_name_2');
		$db->join('product_images', 'product_images.product_id='.$table.'.id AND product_images.featured_image = 1', 'left');
		$query = $db->get_where($table, ['slug'=>$slug]);
		return $query->row_array();
	}

	function get_related_products($categoryId, $productId){
		$db = $this->database;
		$table = $this->get_table();
		$condition['is_active'] = true;
		$db->join('product_images', 'product_images.product_id='.$table.'.id');
		$db->select($table.'.*, product_images.image_name_1, product_images.image_name_2')->where($table.'.id != "'.$productId.'" AND product_category_id='.$categoryId.' AND '.$table.'.is_active=1');
		$db->order_by($table.'.id DESC');
		$db->limit(3, 0);
		$query = $db->get($table);
		//print_r($db->last_query());
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
		$db->select(['products.*', 'product_categories.category_name', 'product_images.image_name_1', 'product_images.image_name_2']);
		//$db->select('products.id, products.product_category_id, products.product_type, products.product_code, products.slug, products.base_price, products.is_active, products.meta_title, products.meta_description,products.meta_keyword, products.is_sale, products.is_gift, products.is_new, product_categories.category_name');
		$db->join('product_categories', 'product_categories.id = products.product_category_id');
		$db->join('product_images', 'product_images.product_id='.$table.'.id AND product_images.featured_image = 1', 'left');
		foreach ($conditions as $key => $condition) {
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

    
    function get_stock_details($params){
    	$db =  $this->database;
    	$table = $this->get_table();
    	$db->select(['products.id', 'products.product', 'products.base_price', 'products.show_on_website', 'products.is_sale', 'product_details.id as detail_id', 'product_details.discount_type', 'product_details.discount', 'product_details.in_stock_qty', 'products.base_price', 'products.base_uom']);
    	if(TRUE == $this->session->userdata('application')['multiple_company']){
    	    $db->join('companies_products', 'companies_products.product_id=products.id', 'inner');
    	}
    	$db->join('product_categories', 'product_categories.id=products.product_category_id', 'inner');
    	
    	$db->join('product_details', 'product_details.product_id=products.id', 'left');
    	if(isset($params['condition'])){
    		foreach ($params['condition'] as $key => $condition) {
    			$db->where($key, $condition);
    		}
    	}
    	$db->where('product_categories.is_active', true);
    	$db->order_by('product_details.in_stock_qty desc, products.show_on_website desc');
    	$query = $db->get_where('products', ['products.is_active'=>true]);
    	//print_r($db->last_query());
    	return $query->result_array();
    }

    function productList($postData=null){
        //echo "<pre>";print_r($postData);exit;
        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        //echo "<pre>"; print_r($postData);exit;
   
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " AND (t.product_code like '%".$searchValue."%' or t.product like '%".$searchValue."%' or t.category_name like'%".$searchValue."%' or t.image_name_1 like'%".$searchValue."%')";
        }
        ## Total number of records without filtering
        
        $this->db->select(['p.*', 'c.category_name', 'i.image_name_1', '(select count(distinct attribute_id) from product_attributes where product_attributes.product_id=p.id and product_attributes.is_active=true) as variant_count']);
        $this->db->join('product_images i', 'i.product_id=p.id AND i.featured_image=1 and i.type like "image" AND i.is_active=1', 'left');
        $this->db->join('product_categories c', 'c.id=p.product_category_id', 'left');
        $sql = $this->db->get_compiled_select('products p');
        $sql2 = 'Select count(*) as allcount from ('.$sql.') t';
        $records = $this->db->query($sql2)->result();
        $totalRecords = $records[0]->allcount;
   

        $sql2 = 'Select count(*) as allcount from ('.$sql.') t where 1=1'.$searchQuery;
        $records = $this->db->query($sql2)->result();
        $totalRecordwithFilter = $records[0]->allcount;
   		
   		
        $sql2 = 'Select * from ('.$sql.') t where 1=1'.$searchQuery.' order by '.$columnName.' '.$columnSortOrder;
        if ($rowperpage!='-1') {
            $sql2.=' LIMIT '.$start.', '.$rowperpage;
        }
        $records = $this->db->query($sql2)->result();
        //print_r($records);
        //echo $this->db->last_query();exit;
        $data = array();
        foreach($records as $recordKey => $record ){
        	//print_r($record);
        	$isSale = ($record->is_sale==true)?"alert-success fa fa-check-circle":"alert-danger fa fa-remove";
        	$isGift = ($record->is_gift==true)?"alert-success fa fa-check-circle":"alert-danger fa fa-remove";
        	$isNew = ($record->is_new==true)?"alert-success fa fa-check-circle":"alert-danger fa fa-remove";
   			$active = ($record->is_active==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';
   			$image = ($record->image_name_1!=null)?$record->image_name_1:'no-image.png';
           	$data[] = array(
            "sr_no" => $recordKey+1,
            "id"=>$record->id,
            "category_name"=>$record->category_name,
            "image"=>$record->image_name_1,
            "image_name_1"=>"<img src='".content_url('uploads/products/'.$image)."' class='img-responsive'></i>",
            "product"=>$record->product,
            "product_code"=>$record->product_code,
            "slug"=>$record->slug,
            "base_price"=>$record->base_price,
            "base_uom"=>$record->base_uom,
            "variant_count"=>$record->variant_count,
            "description"=>$record->description,
            "meta_title"=>$record->meta_title,
            "meta_keyword"=>$record->meta_keyword,
            "meta_description"=>$record->meta_description,
            "is_sale"=>'<i class="'.$isSale.'"></i> | <i class="'.$isGift.'"></i> | <i class="'.$isNew.'"></i>',
            "is_active"=>"<i class='".$active."'></i>",
            'action'=>'Action'
           ); 
        }
        //echo "<pre>"; print_r($data);exit;
        ## Response
        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecordwithFilter,
           "iTotalDisplayRecords" => $totalRecords,
           "aaData" => $data
        );

        return $response;


    }
}
?>