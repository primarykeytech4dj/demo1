<?php 

class Products extends MY_Controller {
	function __construct() {
		parent::__construct();

		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(FALSE);
			}
		}
		$this->load->library('pktdblib');
		$this->load->model('products/product_model');
		$this->load->library('ajax_pagination');
		$this->perPage = 10;
	}

	function get_service_list_dropdown(){
		$this->load->model('products/product_model');	
		$serviceList = $this->product_model->get_active_service_list();
		$products = [''=>'Select Service'];
		//$serviceList = ['1'=> 'Security Guards', 2 => 'Security Supervisor', 3 => 'Bouncer/Bodyguard'];
		foreach ($serviceList as $key => $service) {
			$products[$service['id']] = $service['product'];
		}
		return $products;
	}

	function get_product_category_details($id) {
		// echo "reached get_product_category_details";
		$this->product_model->set_table('product_categories');
		$productCategoryDetail = $this->product_model->get_where($id);
		//print_r($productCategoryDetail);
		return $productCategoryDetail;
	}
	
	function get_product_details($id) {
		$this->product_model->set_table('products');
		$product = $this->product_model->get_where($id);
		//print_r($product);
		return $product;
	}

	function get_product_details_ajax($id) {
		$this->product_model->set_table('products');
		$product = $this->product_model->get_where($id);
		//print_r($product);
		echo json_encode($product);
		exit;
	}

	function product_wise_product_image_listing($data = []){
		//print_r($data);
		$condition = '';
			$condition['product_images.product_id'] = $data['product_images.productid'];

		if(isset($data['condition']))
			$condition = $data['condition'];
		//print_r($condition);exit;
		$this->product_model->set_table('product_images');
		$productImage = $this->product_model->get_product_image_list($condition);
		//print_r($productImage);exit;
		return $productImage;
	}

	function get_product_image_details($productId) {

		$this->product_model->set_table('product_images');
		$productImage = $this->product_model->get_where_custom('product_id', $productId);
		/*echo '<pre>';
		print_r($productImage->result_array());*/
		$img = $productImage->result_array();
		$this->pktlib->parseOutput($this->config->item('response_format'), $img);
		return $img;
	}


	function get_product_categories_list() {
		$this->product_model->set_table('product_categories');
		$productCategories = $this->product_model->get_product_category_list();
		$this->pktlib->parseOutput($this->config->item('response_format'), $productCategories);
		return $productCategories;
	}

	function get_product_category_list() {
		$this->product_model->set_table('products');
		$query = $this->product_model->get_product_category_list();
		//print_r($query);
		return $query;
	}

	function get_categorywise_product($slug = '') {
		if('' === $slug){
			show_404();
			exit;
		}

		$category = $this->get_slugwise_category($slug);
		$this->product_model->set_table('products');
		$data['categoryWiseProducts'] = $this->product_model->get_categorywise_product($category['id']);
		//print_r($data['categoryWiseProducts']);exit;
		$data['content'] = 'products/venedor/categorywise_product_list';
		//$data['content'] = 'products/add_products';
		$data['title'] = $category['category_name'];
		$data['meta_title'] = $category['meta_title'];
		$data['meta_description'] = $category['meta_description'];
		$data['meta_keyword'] = $category['meta_keyword'];
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'Category : : '.ucfirst($category['category_name'])]
		];

		$data['category'] = $category;
		echo Modules::run('templates/default_template', $data);
	}

	function get_slugwise_category($slug = ''){
		if(''===$slug){
			show_404();
			exit;
		}
		$this->product_model->set_table('product_categories');
		$category = $this->product_model->get_slugwise_category($slug);
		return $category;
	}

	function id_wise_category($id = ''){
		//echo $id;exit;
		if(''==$id){
			show_404();
			exit;
		}
		//echo "reached here";
		$this->product_model->set_table('product_categories');
		$product = $this->product_model->get_list($id);
		//print_r($product);exit;
		return $product;
	}

	function get_single_product($slug = ''){
		//echo $slug;//exit;
		if(''===$slug){
			show_404();
			exit;
		}
		//print_r($slug);exit;
		$product = $this->get_slugwise_product($slug);
		//print_r($product);exit;
		$data['productVariation'] = [];
		$variations = $this->pktdblib->custom_query("select variations.name, variations.value from product_variations inner join variations on variations.id = product_variations.variation_id where product_id = ".$product['id']);
        $data['productVariation'] = [];
    		foreach ($variations as $itemKey => $itemValue) {
    			$data['productVariation'][$itemValue['name']][]= $itemValue['value'];
    		}
    
    	$attributes = $this->pktdblib->custom_query("select * from product_attributes where product_id = ".$product['id']);
        $data['productAttribute'] = [];
		foreach ($attributes as $attKey => $attValue) {
			$data['productAttribute'][]= $attValue;
		}
		$data['product'] = $product;
		$data['productImages'] = $this->get_productwise_images($product['id']);
		$category = $this->id_wise_category($product['product_category_id']);
		$data['relatedProducts'] = $this->get_related_products($category['id'], $product['id']);
		
		$this->pktlib->parseOutput($this->config->item('response_format'), $data);
		
		$data['title'] = $product['product'];
		$data['meta_title'] = $product['meta_title'];
		$data['meta_description'] = $product['meta_description'];
		$data['meta_keyword'] = $product['meta_keyword'];
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'product-category/'.$category['slug'], 'title'=>'Category : : '.ucfirst($category['category_name'])],
			['url'=>'#', 'title'=>'Product : : '.ucfirst($product['product'])]
		];
		
		//echo '<pre>';print_r($data['productImages']);exit;
		
		
		$data['category'] = $category;
		
		$data['content'] = 'products/venedor/product_detail';
		$data['js'][] = '';
		echo Modules::run('templates/default_template', $data);
	}

	function get_productwise_images($productId){
		$this->product_model->set_table('product_images');
		$images = $this->product_model->get_where_custom('product_id', $productId);
		return $images->result_array();
	}

	function get_slugwise_product($slug = ''){
		//echo "reached in get_slugwise_product";
		//print_r($slug);exit;
		if(''===$slug){
			//echo "hiii";exit;
			show_404();
			exit;
		}

		$this->product_model->set_table('products');
		$product = $this->product_model->get_slugwise_product($slug);
		//print_r($product);exit;
		return $product;
	}

	function left_get_categorywise_product($slug = '') {
		//echo $slug;
		if('' === $slug){
			show_404();
			exit;
		}

		$category = $this->get_slugwise_category($slug);
		$this->product_model->set_table('products');
		$data['categoryWiseProducts'] = $this->product_model->get_categorywise_product($category['id']);
		//print_r($data['categoryWiseProducts']);exit;
		//$data['content'] = 'products/add_products';
		$data['title'] = $category['category_name'];
		$data['meta_title'] = $category['meta_title'];
		$data['meta_description'] = $category['meta_description'];
		$data['meta_keyword'] = $category['meta_keyword'];
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'Category : : '.ucfirst($category['category_name'])]
		];

		$data['category'] = $category;
		$data['content'] = 'products/categorywise_product_list2';
		echo Modules::run('templates/default_template', $data);
	}

	function right_get_categorywise_product($slug = '') {
		if('' === $slug){
			show_404();
			exit;
		}

		$category = $this->get_slugwise_category($slug);
		$this->product_model->set_table('products');
		$data['categoryWiseProducts'] = $this->product_model->get_categorywise_product($category['id']);
		//print_r($categoryWiseProducts);exit;
		$data['content'] = 'products/categorywise_product_list3';
		//$data['content'] = 'products/add_products';
		$data['title'] = $category['category_name'];
		$data['meta_title'] = $category['meta_title'];
		$data['meta_description'] = $category['meta_description'];
		$data['meta_keyword'] = $category['meta_keyword'];
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>'Category : : '.ucfirst($category['category_name'])]
		];

		$data['category'] = $category;
		echo Modules::run('templates/default_template', $data);
	}

	function get_related_products($categoryId, $productId)
	{
		$this->product_model->set_table('products');
		return $this->product_model->get_related_products($categoryId, $productId);
	}

	function backTraverse_category($parentId, $traverseResult = []) {
		$this->product_model->set_table('product_categories');
		$categories = $this->product_model->get_where_custom('id',$parentId);
		$category = $categories->row_array();
		/*echo '<pre>';
		print_r($category);
		echo '</pre>';*/
		$traverseResult[$category['id']] = $category;
		if($category['parent_id']!=0){
			$this->backTraverse_category($category['parent_id'], $traverseResult);
		}
		return $traverseResult;
	}

	function checkCategory_is_parent($categoryId){
		//print_r($categoryId);echo '<br>';
		$this->product_model->set_table('product_categories');
		$categories = $this->product_model->get_where_custom('parent_id',$categoryId);
		$category = $categories->result_array();
		/*echo '<pre>';
		print_r($category);*/
		//not used so far. remove this line if used anywhere
		return count($category);
	}

	function get_categorylist($parentSlug = '') {
		
		$parentId = 1;
		$category = $this->id_wise_category($parentId);
		$breadCrumb[0] = ['url'=>'/', 'title'=>'Home'];
		if('' !== $parentSlug){ 
			//echo "hii";exit;
			$category = $this->get_slugwise_category($parentSlug);
			$parentId = $category['id'];
			$backTraverseCategory = $this->backTraverse_category($category['parent_id']);
			
		//print_r($parentId);exit;
		}
        //echo $parentId;
		$this->pktdblib->set_table('product_categories');
		$query = $this->pktdblib->get_where_custom('parent_id', $parentId);
		//echo $this->db->last_query();
		$data['categories'] = $query->result_array();

		//print_r($data['categories']);exit;

		foreach ($data['categories'] as $catKey => $cat) {
			$childCategory = $this->product_model->get_where_custom('parent_id', $cat['id']);
			$childCategory = $childCategory->result_array();
			if(count($childCategory)>0)
				$data['categories'][$catKey]['is_parent'] = true;
			else
				$data['categories'][$catKey]['is_parent'] = false;

		}
		//print_r($data['categories']);exit;
		$data['category'] = $category;
		$this->pktlib->parseOutput($this->config->item('response_format'), $data);
		$data['content'] = 'products/categorylist2';
		$data['title'] = $category['category_name'];
		$data['meta_title'] = $category['meta_title'];
		$data['meta_description'] = $category['meta_description'];
		$data['meta_keyword'] = $category['meta_keyword'];
		$data['breadCrumbs'] = [
			['url'=>'/', 'title'=>'Home'],
			['url'=>'#', 'title'=>ucfirst($category['category_name'])]
		];
		//echo $category['slug'];exit;
		$data['products'] = Modules::run("products/left_get_categorywise_product", $category['slug']);
		
		echo Modules::run('templates/default_template', $data);

	}

	

	function category_wise_product_listing($data = []){
		//echo "giii";exit;
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		$this->product_model->set_table('products');
		$res = $this->product_model->get_product_list($condition);
		//print_r($res);exit;
		return $res;
	}

	function get_product_type(){
		$query = ['Select Product Type','Product', '	Service', 'Product & Services']; 
		//print_r($query);
		return $query;
	}

	function get_product_list($data = []) {
		//print_r($data);
		$condition = [];
		if(isset($data['condition']))
			$condition = $data['condition'];
		$this->product_model->set_table('products');
		$res = $this->product_model->get_product_details($condition);
		/*echo '<pre>';
		print_r($res);exit;*/
		return $res;
	}

	function getProductWisePackProduct() {
		//$_POST['params'] = 1;
		if(!$this->input->post('params'))
			return;

		$condition = [];
		$condition['pack_products.is_active'] = TRUE;
		$basketId = $this->input->post('params');
		if(!empty($basketId)) {
			$condition['pack_products.basket_id'] = $basketId;
		}
		$this->product_model->set_table("pack_products");
		$productWisePackProducts = $this->product_model->get_product_wise_pack_product($condition);
		$packProductList = [0=>['id' => 0, 'text' => 'Select ']];
		foreach ($productWisePackProducts as $key => $packProduct) {
			$packProductList[$key+1]['id'] = $packProduct['id'];
			$packProductList[$key+1]['text'] = $packProduct['product'];
		}
		/*echo '<pre>';
		print_r($packProductList);
		exit;*/
		echo json_encode($packProductList);
		//print_r($stateList);exit;
		exit;

	}

	function get_product_detail_ajax($productId) {
		$this->product_model->set_table('products');
		$product = $this->product_model->get_product_details(['products.id'=>$productId]);
		echo json_encode($product);
		exit;
	}

	function create_product_code($productId) {
		
		$productCode = "P";
		//print_r($companyDetails['short_code']."/"."Driver");exit;
		if($productId>0 && $productId<=9)
			$productCode .= '000000';
			
		elseif($productId>=10 && $productId<=99)
			$productCode .= '00000';
		elseif($productId>=100 && $productId<=999)
			$productCode .= '0000';
		elseif($productId>=1000 && $productId<=9999)
			$productCode .= '000';
		elseif($productId>=10000 && $productId<=99999)
			$productCode .= '00';
		elseif($productId>=100000 && $productId<=999999)
			$productCode .= '0';

		$productCode .= $productId;
		return $productCode;
	}

	function index() {
		$data['title'] = 'Products';
		$data['meta_title'] = 'Product listing';
		$data['meta_description'] = 'Products Listing';
		$data['meta_keyword'] = 'Products Listing';
		$data['modules'][] = 'products';
		$data['methods'][] = 'product_listing';
		echo Modules::run("templates/default_template", $data);
	}

	function product_listing($data = []) {
		//$condition = ['products.is_active'=>true, 'products.show_on_website'=>true];
		$condition = ['products.is_active'=>true, 'products.show_on_website'=>true];

		if(NULL !==(custom_constants::company_id)){
			$condition['companies_products.company_id'] =custom_constants::company_id;
		}
		//print_r($data);
		$this->product_model->set_table('products');
		if(isset($data['conditions'])){
			$condition = $data['conditions'];
		}
		//print_r($condition);
		$data['products'] = $this->product_model->get_product_list($condition);
		//print_r($data);exit;
		$this->load->view("products/venedor/index", $data);
	}

	function product_listing_home($data = []) { 
		$condition = ['products.is_active'=>true, 'products.show_on_website'=>true];

		//print_r($data);
		$this->product_model->set_table('products');
		if(isset($data['conditions'])){
			$condition = $data['conditions'];
		}
		$data['products'] = $this->product_model->get_product_list($condition);
		//print_r($data);exit;
		$this->load->view("products/index_home", $data);
	}

	function get_category_list(){
		//echo "hiii";exit;
		$this->pktdblib->set_table('product_categories');
		$category = $this->pktdblib->get_where_custom('is_active', true);
		$data['categories'] = $category->result_array();
		$this->pktlib->parseOutput($this->config->item('response_format'), $data);
		return $data;
	}

	function product_list() {

		$data['title'] = 'Products';
		$data['meta_title'] = 'Product listing';
		$data['meta_description'] = 'Products Listing';
		$data['meta_keyword'] = 'Products Listing';
		$data['modules'][] = 'products';
		$data['methods'][] = 'get_service_list';
		/*echo '<pre>';
		print_r($data['product_category']);
		exit;*/
		echo Modules::run("templates/default_template", $data);
	}

	function get_service_list(){
		$condition = '';

		if(isset($data['condition']))
			$condition = $data['condition'];
		else{
			$condition = ['product_images.featured_image'=>true];
		}
		$this->product_model->set_table("products");
		$data['product_category'] = $this->product_model->product_wise_product_images($condition);
		//$data['content'] = 'products/product_list';
		$this->load->view("products/product_list", $data);
	}

	function featured_product($data = []) {
        $this->product_model->set_table("products");
    	/*$sql = "select products.*, product_images.image_name_1, product_images.image_name_2 from products left join product_images on product_images.product_id=products.id where products.is_featured = 1 and products.is_active=true ORDER BY modified DESC limit 4";*/
    	$sql = "select products.*, product_images.image_name_1, product_images.image_name_2, product_details.discount_type, product_details.discount, product_details.in_stock_qty, (CASE WHEN product_details.discount_type='percentage' THEN products.base_price+(products.base_price*(product_details.discount/100)) WHEN product_details.discount_type='value' THEN products.base_price+product_details.discount ELSE products.base_price END) as actual_price from products left join product_images on product_images.product_id=products.id and product_images.featured_image=true left join product_details on product_details.product_id=products.id AND product_details.in_stock_qty>0 where products.show_on_website=true AND is_gift=true";

    	if(isset($data['condition'])){
    		foreach ($data['condition'] as $key => $condition) {
    			# code...
    			$sql.=" AND ".$key."='".$condition."'";
    		}
    	}

    	$sql.= " AND products.is_active=true";

    	if(isset($data['order']))
    		$sql.=" ORDER BY ".$data['order'];
    	else
    		$sql.=" ORDER BY products.modified DESC";

    	if(isset($data['limit']))
    		$sql.=" limit ".$data['limit'];
    	else
    		$sql.=" limit 21";

    	/*"SELECT Orders.OrderID, Customers.CustomerName, Shippers.ShipperName
FROM ((Orders
INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID)
INNER JOIN Shippers ON Orders.ShipperID = Shippers.ShipperID)";*/
    	$data['featuredProduct'] = $this->pktlib->custom_query($sql);
    	foreach($data['featuredProduct'] as $key=>$product){
    	    $attribute = $this->product_default_attribute($product['id']);
    	    if($attribute){
    	        foreach($attribute as $attKey=>$att)
    	        {
    	            $data['featuredProduct'][$key][$attKey] = $att;
    	        }
    	    }
    	    
    	    $data['featuredProduct'][$key]['attributes'] = $this->product_attributes($product['id']);
    	}
        $this->pktlib->parseOutput($this->config->item('response_format'), ['featured_product'=>$data]);
        //echo "<pre>";
        //print_r($featuredProduct);exit;
        $this->load->view("products/venedor/featured_products", $data);
    }

    function all_product($page=0) {
        $params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            //print_r($params);exit;
            $_POST = $params;
        }
        
        $conditions = [];
		if($_SERVER['REQUEST_METHOD']=='POST' || NULL!==$params){
			$conditions['condition'] = $this->input->post('condition');
		}
		//$conditions['condition'] = $this->input->post();
		if(NULL===$this->input->post('page')){
			$_POST['page'] = $page;
		}
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        } 
         
        // Get record count 
        $conditions['returnType'] = 'count'; 
        $totalRec = $this->product_model->_get_all_products($conditions); 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('products/all_product/'.$page); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
        $config['link_func']   = 'searchFilter';
        $config['uri_segment'] = 2;
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions2 = array( 
            'start' => $offset, 
            'limit' => $this->perPage, 
        ); 

        if(isset($conditions['condition'])){
	        $conditions2['condition'] = $conditions['condition'];
	    } 
        $data['allProduct'] = $this->product_model->_get_all_products($conditions2);
        //echo '<pre>';
        //print_r($data['allProduct']);
        
        foreach($data['allProduct'] as $key=>$product){
    	    $attribute = $this->product_default_attribute($product['id']);
    	    if($attribute){
    	        foreach($attribute as $attKey=>$att)
    	        {
    	            $data['allProduct'][$key][$attKey] = $att;
    	        }
    	    }
    	    
    	    $data['allProduct'][$key]['attributes'] = $this->product_attributes($product['id']);
    	}
    	
    	$data['total_rows'] = $totalRec;
    	$data['page'] = (int)$page;
        	
    	$this->pktlib->parseOutput($this->config->item('response_format'), ['all_product'=>$data]);
        
        $this->load->view("products/all_products", $data);
    }

    function latest_product_slider($data = []) {
    	//echo "reached here";
    	//print_r($data);exit;
        $this->product_model->set_table("products");
    	/*$sql = "select products.*, product_images.image_name_1, product_images.image_name_2 from products left join product_images on product_images.product_id=products.id where products.is_featured = 1 and products.is_active=true ORDER BY modified DESC limit 4";*/
    	//$data['condition'] = 'products.is_new' => TRUE;
    	$sql = "select products.*, product_images.image_name_1, product_images.image_name_2, product_details.discount_type, product_details.discount, product_details.in_stock_qty, (CASE WHEN product_details.discount_type='percentage' THEN products.base_price+(products.base_price*(product_details.discount/100)) WHEN product_details.discount_type='value' THEN products.base_price+product_details.discount ELSE products.base_price END) as actual_price from products left join product_images on product_images.product_id=products.id and product_images.featured_image=true left join product_details on product_details.product_id=products.id where products.show_on_website=true AND products.is_new=1";

    	if(isset($data['condition'])){
    		foreach ($data['condition'] as $key => $condition) {
    			$sql.=" AND ".$key."='".$condition."'";
    		}
    	}

    	$sql.= " AND products.is_active=true";

    	if(isset($data['order']))
    		$sql.=" ORDER BY ".$data['order'];
    	else
    		$sql.=" ORDER BY products.modified DESC";

    	if(isset($data['limit']))
    		$sql.=" limit ".$data['limit'];
    	else
    		$sql.=" limit 21";
    		
        //echo $sql;
        //$data['latestProduct'] = $sql->result_array();
    	$data['latestProduct'] = $this->pktdblib->custom_query($sql);
    	//print_r($data['latestProduct']);exit;
    	foreach($data['latestProduct'] as $key=>$product){
    	    $attribute = $this->product_default_attribute($product['id']);
    	    if($attribute){
    	        foreach($attribute as $attKey=>$att)
    	        {
    	            $data['latestProduct'][$key][$attKey] = $att;
    	        }
    	    }
    	    
    	    $data['latestProduct'][$key]['attributes'] = $this->product_attributes($product['id']);
    	}
        //echo "<pre>";
        //print_r($data['latestProduct']);exit;
        $this->pktlib->parseOutput($this->config->item('response_format'), ['latest_product'=>$data]);
        $this->load->view("products/venedor/latest_products_slider", $data);
    }

    function sale_product($data = []) {
    	//echo "reached here";exit;
        $this->product_model->set_table("products");
    	/*$sql = "select products.*, product_images.image_name_1, product_images.image_name_2 from products left join product_images on product_images.product_id=products.id where products.is_featured = 1 and products.is_active=true ORDER BY modified DESC limit 4";*/
    	//$data['condition'] = 'products.is_new' => TRUE;
    	$sql = "select products.*, product_images.image_name_1, product_images.image_name_2, product_details.discount_type, product_details.discount, product_details.in_stock_qty, (CASE WHEN product_details.discount_type='percentage' THEN products.base_price+(products.base_price*(product_details.discount/100)) WHEN product_details.discount_type='value' THEN products.base_price+product_details.discount ELSE products.base_price END) as actual_price from products left join product_images on product_images.product_id=products.id and product_images.featured_image=true left join product_details on product_details.product_id=products.id where products.show_on_website=true and products.is_active=true and products.is_sale=1";

    	if(isset($data['condition'])){
    		foreach ($data['condition'] as $key => $condition) {
    			# code...
    			$sql.=" AND ".$key."='".$condition."'";
    		}
    	}

    	$sql.= " AND products.is_active=true";

    	if(isset($data['order']))
    		$sql.=" ORDER BY ".$data['order'];
    	else
    		$sql.=" ORDER BY products.modified DESC";

    	if(isset($data['limit']))
    		$sql.=" limit ".$data['limit'];
    	else
    		$sql.=" limit 21";

    	//echo $sql;exit;
        //$data['latestProduct'] = $sql->result_array();
    	$data['saleProduct'] = $this->pktdblib->custom_query($sql);
        foreach($data['saleProduct'] as $key=>$product){
    	    $attribute = $this->product_default_attribute($product['id']);
    	    if($attribute){
    	        foreach($attribute as $attKey=>$att)
    	        {
    	            $data['saleProduct'][$key][$attKey] = $att;
    	        }
    	    }
    	    
    	    $data['saleProduct'][$key]['attributes'] = $this->product_attributes($product['id']);
    	}
        $this->pktlib->parseOutput($this->config->item('response_format'), ['sale_product'=>$data]);
        $this->load->view("products/venedor/sale_products", $data);
    }

    function projects($parentSlug = NULL)
	{
		$parentId = 1;
		$data['parentSlug'] = $parentSlug;
		$this->pktdblib->set_table('product_categories');
		$query = $this->pktdblib->get_where_custom('product_categories.slug', $parentSlug);
		$parentCategory = $query->row_array();
		//print_r($parentCategory);exit;
		$data['title'] = ($parentCategory['category_name'])?$parentCategory['category_name']:'Projects';
		$data['meta_title'] = ($parentCategory['meta_title'])?$parentCategory['meta_title']:'Projects';
		$data['meta_description'] = ($parentCategory['meta_description'])?$parentCategory['meta_description']:'Projects';
		$data['meta_keyword'] = ($parentCategory['meta_keyword'])?$parentCategory['meta_keyword']:'Projects';
		/*$data['modules'][] = "services";
		$data['methods'][] = "category_list";*/
		$data['parentCategory'] = $parentCategory;
		$sql = 'Select product_categories.*, (Select count(pc.id) from product_categories pc where pc.parent_id in (product_categories.id)) as child from product_categories where 1=1';

		if(NULL !== $parentSlug)
			$sql.=' AND product_categories.parent_id in (Select id from product_categories where slug like "'.$parentSlug.'")';
		else
			$sql.=' AND product_categories.parent_id='.$parentId;
			
		$sql.=' AND product_categories.is_active=true';

		$sql.=' order by product_categories.id asc';

		//echo $sql;
		//$this->pktdblib->set_table('product_categories');
		//echo '<pre>';
		$category = $this->pktdblib->custom_query($sql);
		/*echo '<pre>';
		print_r($category);exit;*/
		$this->pktlib->parseOutput($this->config->item('response_format'), $category);
		$data['category'] = [];
		foreach ($category as $key => $childCategory) {
		    //print_r($childCategory);
		    if(NULL===$parentSlug){ //echo "hii";
		        $data['heading'] = $childCategory['description'];
		    	$data['category'] = array_merge($data['category'], $this->get_child_category($childCategory['id']));
		    }else{ //echo "hello";
		        $data['heading'] = '';
			    $data['category'][] = $childCategory;
		    }
			
			//print_r($child_category);
		}
		//echo '<pre>';
		//print_r($data['category']);
		//exit;
		$data['content'] = 'products/projects';
		echo Modules::run('templates/default_template', $data);

		
	}

	function category($categorySlug = NULL)
	{
		//echo $categorySlug; exit;
		$parentId = 1;
		$data['parentSlug'] = $categorySlug;
		$this->pktdblib->set_table('product_categories');
		$query = $this->pktdblib->get_where_custom('product_categories.slug', $categorySlug);
		$parentCategory = $query->row_array();
		//print_r($parentCategory);exit;
		$data['title'] = ($parentCategory['category_name'])?$parentCategory['category_name']:'Projects';
		$data['meta_title'] = ($parentCategory['meta_title'])?$parentCategory['meta_title']:'Projects';
		$data['meta_description'] = ($parentCategory['meta_description'])?$parentCategory['meta_description']:'Projects';
		$data['meta_keyword'] = ($parentCategory['meta_keyword'])?$parentCategory['meta_keyword']:'Projects';
		/*$data['modules'][] = "services";
		$data['methods'][] = "category_list";*/
		$data['parentCategory'] = $parentCategory;
		//print_r($data['parentCategory']);exit;
		/*$sql = 'Select products.*, (Select pi.image_name_1 from product_images pi where pi.product_id in (products.id) and pi.featured_image=true and pi.is_active=true) as image_name_1 from products where 1=1';*/

		$sql = "select products.*, product_images.image_name_1, product_images.image_name_2, product_categories.category_name, product_details.discount_type, product_details.discount, product_details.in_stock_qty, (CASE WHEN product_details.discount_type='percentage' THEN products.base_price+(products.base_price*(product_details.discount/100)) WHEN product_details.discount_type='value' THEN products.base_price+product_details.discount ELSE products.base_price END) as actual_price from products left join product_details on product_details.product_id=products.id left join product_images on product_images.product_id=products.id left join product_categories on product_categories.id=products.product_category_id where 1=1 and product_images.featured_image=true";

		if(NULL !== $categorySlug)
			$sql.=' AND products.product_category_id in (Select id from product_categories where slug like "'.$categorySlug.'")';
		/*else
			$sql.=' AND product_categories.parent_id='.$parentId;*/
			
		$sql.=' AND products.is_active=true';

		$sql.=' order by products.id asc';
		$data['products'] = $this->pktdblib->custom_query($sql);
		foreach($data['products'] as $key=>$product){
    	    $attribute = $this->product_default_attribute($product['id']);
    	    if($attribute){
    	        foreach($attribute as $attKey=>$att)
    	        {
    	            $data['products'][$key][$attKey] = $att;
    	        }
    	    }
    	    
    	    $data['products'][$key]['attributes'] = $this->product_attributes($product['id']);
    	}
		//echo '<pre>';print_r($data['products']);exit;
		$this->pktlib->parseOutput($this->config->item('response_format'), $data);
		$data['content'] = 'products/venedor/categorywise_product_list';
		echo Modules::run('templates/default_template', $data);
	}

	function get_productwise_category_list(){
	    $sql='SELECT product_categories.*, (select count(products.id) from products inner join product_details on product_details.product_id=products.id and product_details.in_stock_qty>0 where product_category_id=product_categories.id) as product_count FROM `product_categories`';
	    if(NULL!==custom_constants::company_id){
	           $sql.=' inner join companies_product_categories on companies_product_categories.product_category_id=product_categories.id and companies_product_categories.company_id='.custom_constants::company_id;
	    }
	   
	    $sql.=" where id not in (select parent_id FROM product_categories)";
		$categoryList = $this->pktdblib->custom_query($sql);
		return $categoryList;
	}

	function get_brand_list(){
		$categoryList = $this->pktdblib->custom_query('Select product_categories.*, (select count(products.id) from products where products.product_category_id=product_categories.id and products.is_active=true) as product_count from product_categories where id in (Select product_category_id from products where products.is_active=true) and product_categories.is_active=true');
		return $categoryList;
	}

	function latest_products()
	{
		
		$data['title'] = 'Latest Products';
		$data['meta_title'] = 'Latest Products';
		$data['meta_description'] = 'Latest products collection';
		$data['meta_keyword'] = 'Blooming Collection, Garments, clothes, online shopping';
		
		
		$data['modules'][] = 'products';
		$data['methods'][] = 'get_latest_products';
		echo Modules::run('templates/default_template', $data);
	}

	function get_latest_products($data = []){
		$sql = "select products.*, product_images.image_name_1, product_images.image_name_2, product_details.discount_type, product_details.discount, product_details.in_stock_qty, (CASE WHEN product_details.discount_type='percentage' THEN products.base_price+(products.base_price*(product_details.discount/100)) WHEN product_details.discount_type='value' THEN products.base_price+product_details.discount ELSE products.base_price END) as actual_price from products left join product_images on product_images.product_id=products.id and product_images.featured_image=true left join product_details on product_details.product_id=products.id where products.show_on_website=true AND products.is_new=true";

    	if(isset($data['condition'])){
    		foreach ($data['condition'] as $key => $condition) {
    			$sql.=" AND ".$key."='".$condition."'";
    		}
    	}

    	$sql.= " AND products.is_active=true";

    	if(isset($data['order']))
    		$sql.=" ORDER BY ".$data['order'];
    	else
    		$sql.=" ORDER BY products.modified DESC";

    	if(isset($data['limit']))
    		$sql.=" limit ".$data['limit'];
    	else
    		$sql.=" limit 21";

    	$data['products'] = $this->pktdblib->custom_query($sql);
    	foreach($data['products'] as $key=>$product){
    	    $attribute = $this->product_default_attribute($product['id']);
    	    if($attribute){
    	        foreach($attribute as $attKey=>$att)
    	        {
    	            $data['products'][$key][$attKey] = $att;
    	        }
    	    }
    	    
    	    $data['products'][$key]['attributes'] = $this->product_attributes($product['id']);
    	}
    	$this->load->view('products/venedor/latest_products', $data);

	}

	function product_price($productId){

		//echo $productId;
		$sql = 'Select products.base_price, product_details.discount_type, product_details.discount from products left join product_details on product_details.product_id=products.id where products.id='.$productId;
		$query = $this->pktdblib->custom_query($sql);
		$product = $query[0];
		$price=0;
		if(!empty($product['discount_type']) && $product['discount_type'] == 'percentage')
			$price = $product['base_price']+($product['base_price']*($product['discount']/100));
		elseif(!empty($product['discount_type']) && $product['discount_type'] == 'value')
			$price = $product['base_price']+($product['base_price']*($product['discount']/100));
		else
			$price = $product['base_price'];

		echo $price;exit; 
		//return $price;
	}
	
	function categories($parentId = 0){
	    $category = $this->pktdblib->custom_query('select * from product_categories where parent_id='.$parentId.' and is_active=true');
	    $params = json_decode(file_get_contents('php://input'), TRUE);
	    if(!empty($params))
		    $parentId = $params['params'];
	    
	    $child = [];
	    foreach($category as $key=>$value){
	        $isParent = $this->pktdblib->custom_query('select count(parent_id) as child from product_categories where parent_id='.$value['id']);
	        $category[$key]['is_parent'] = $isParent[0]['child']; 
	    }
	    $this->pktlib->parseOutput($this->config->item('response_format'), $category);
	    return $category;
	    
	}
	
	function attribute_wise_mrp($data=[]){
	    /*if($data['productId']=='')
    	print_r($data);exit;*/
		$productId = '';
		$attribute = '';
		$unit = '';
		//echo "reached here";exit;
		/*if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//echo "reached here";exit;
			$productId = $this->input->post('productId');
			$attribute = $this->input->post('attribute');
			$unit = $this->input->post('unit');
		}else{*/
			$productId = $data['productId'];
			$attribute = $data['attribute'];
			$unit = $data['unit'];
		//}
		
		$requireUOM = explode(" ", $attribute);
		//print_r($requireUOM);exit;
    	$basePrice = $this->pktdblib->custom_query('select base_uom, base_price from products where id='.$productId);
    	$requireUOM[1] = trim(strtoupper($requireUOM[1]));
    	$baseUom = explode(" ", trim(strtoupper($basePrice[0]['base_uom'])));
    	if(count($requireUOM)<2 || count($baseUom)<2)
    	    return false;
    	//print_r($baseUom);exit;
    	$basePrice[0]['base_uom'] = trim(strtoupper($basePrice[0]['base_uom']));
    	$uom = $attribute;
    	$finalPrice = $basePrice[0]['base_price'];
    	//echo $attribute." ".$basePrice[0]['base_uom'].'<br>';
    	//echo $requireUOM[1]." ".$basePrice[0]['base_uom']." <br>";
    	if(($requireUOM[1])!=$baseUom[1]){ //echo "hii";
    	    //$finalPrice = ($finalPrice*$requireUOM[0])/$baseUom[0];
    	    
    		$unit = $this->pktlib->unit_convertion(trim(strtoupper($requireUOM[1])),trim(strtoupper($baseUom[1])));
    		//echo $finalPrice." ".$requireUOM[0]." ".$baseUom[0]." ".$unit.'<br>';exit;
    		$finalPrice = $finalPrice*$requireUOM[0]/trim($baseUom[0])*$unit;
    	    //print_r($unit);exit;
    	}else{
    	    $finalPrice = ($finalPrice*$requireUOM[0])/$baseUom[0];
    	}
    	//echo $finalPrice." ".$uom."<br>";exit;
    	$priceList = ['final_price' => $finalPrice, 'unit'=>$uom];
    	
         //$myJSON = JSON.stringify($basePrice);
        //$this->pktlib->parseOutput($this->config->item('response_format'), $priceList);
    	return $priceList;
    }

	
	function product_default_attribute($productId){
	    
	    $defaultAttr = $this->pktdblib->custom_query('Select p.base_price, p.base_uom, pa.id,pa.attribute_id, a.uom,pa.increased_percentage,pa.mrp,pa.discount,pa.price, pa.stock_qty, concat(a.unit, " ",a.uom) as uom2 from products p inner join product_attributes pa on pa.product_id=p.id inner join attributes a on a.id=pa.attribute_id where pa.product_id="'.$productId.'" and pa.is_default=1 and pa.is_active=true order by pa.id asc limit 1');
	    if(empty($defaultAttr)){
	         $defaultAttr = $this->pktdblib->custom_query('Select p.base_price, p.base_uom, pa.id, pa.attribute_id, a.uom,pa.increased_percentage,pa.mrp,pa.discount,pa.price, pa.stock_qty, concat(a.unit, " ",a.uom) as uom2 from products p inner join product_attributes pa on pa.product_id=p.id inner join attributes a on a.id=pa.attribute_id where pa.product_id="'.$productId.'" and pa.is_active=true order by pa.id asc limit 1');
	    }
	    //print_r($defaultAttr);//exit;
	    if(!empty($defaultAttr)){
	       $priceList =  $this->attribute_wise_mrp(['productId'=>$productId, 'attribute'=>$defaultAttr[0]['uom2'], 'unit'=>$defaultAttr[0]['uom2']]);
	       $response = false;
	       //if($priceList){
	           $response = ['product_attribute_id'=>$defaultAttr[0]['id'], 'discount'=>$defaultAttr[0]['discount'], 'default_uom'=>$defaultAttr[0]['uom2'], 'actual_price'=>$defaultAttr[0]['price']-(($defaultAttr[0]['discount']/100.00)*$defaultAttr[0]['price']), 'mrp'=>$defaultAttr[0]['mrp'], 'stock_qty'=>$defaultAttr[0]['stock_qty']];
	       //}
	       
	      // $this->pktlib->parseOutput($this->config->item('response_format'), $response);
	       return $response;
	       //print_r($priceList);exit;
	    }else{
	        return false;
	    }
	}
	
	function product_attributes($productId){
	    $productAttr = $this->pktdblib->custom_query('Select p.base_price, p.base_uom, pa.id, pa.attribute_id, a.uom,pa.increased_percentage,pa.mrp,pa.discount,pa.price, pa.stock_qty, concat(a.unit, " ",a.uom) as uom2, a.unit from products p inner join product_attributes pa on pa.product_id=p.id inner join attributes a on a.id=pa.attribute_id where pa.product_id="'.$productId.'" and pa.is_active=true order by pa.id asc');
	    //print_r($productAttr);
	    $response = [];
	    foreach($productAttr as $attKey=>$att){
	       $priceList =  $this->attribute_wise_mrp(['productId'=>$productId, 'attribute'=>$att['uom2'], 'unit'=>$att['uom2']]);
	       
	       //if($priceList){
	           $actualPrice = number_format($att['price']-(($att['discount']/100.00)*$att['price']), 2);
	           $response[] = ['product_attribute_id'=>$att['id'], 'discount'=>$att['discount'], 'default_uom'=>$att['uom2'], 'actual_price'=>$actualPrice, 'mrp'=>$att['mrp'], 'unit'=>$att['unit'], 'uom'=>$att['uom'], 'display_unit'=>$att['uom2'], 'stock_qty'=>$att['stock_qty']];
	       //}
	    }
	    //$this->pktlib->parseOutput($this->config->item('response_format'), $response);
	    
	    //print_r($response);exit;
	    return $response;
	}
	
	function product_attribute_list($arr=[]){
	    //print_r($arr);
	    //method was created for Android Application
	    $query = $this->pktdblib->custom_query('SELECT product_attributes.id, CONCAT(attributes.unit, " ",attributes.uom) as uom FROM `product_attributes` INNER JOIN attributes on attributes.id=product_attributes.attribute_id WHERE product_attributes.is_active=true');
	    $productAttribute = [];
	    foreach($query as $key=>$attribute){
	        $productAttribute[$attribute['id']] = strtoupper($attribute['uom']);
	    }
	    
	    if(isset($arr['responsetype']) && $arr['responsetype']=="web"){
		    
		}else{
		    $this->pktlib->parseOutput($this->config->item('response_format'), $productAttribute);
		}
	    
	    return $productAttribute;
	}
	
	function ordered_product_attribute_list($arr=[]){
	    //print_r($arr);
	    //method was created for Android Application
	    $query = $this->pktdblib->custom_query('SELECT product_attributes.id, CONCAT(attributes.unit, " ",attributes.uom) as uom FROM `product_attributes` INNER JOIN attributes on attributes.id=product_attributes.attribute_id WHERE 1=1');
	    $productAttribute = [];
	    foreach($query as $key=>$attribute){
	        $productAttribute[$attribute['id']] = strtoupper($attribute['uom']);
	    }
	    
	    return $productAttribute;
	}

	function product_attribute_details(){
	    $params = json_decode(file_get_contents('php://input'), TRUE);
	    //print_r($params);exit;
	    if(empty($params))
		    return false;

	    $productAttribute = $this->pktdblib->custom_query('SELECT product_attributes.*, CONCAT(attributes.unit, " ",attributes.uom) as uom FROM `product_attributes` INNER JOIN attributes on attributes.id=product_attributes.attribute_id WHERE product_attributes.id="'.$params['product_attribute_id'].'"');
	    
	    if(isset($arr['responsetype']) && $arr['responsetype']=="web"){
	    	return $productAttribute;
		}else{
		    $this->pktlib->parseOutput($this->config->item('response_format'), $productAttribute);
		}
	    
	}
	
}
