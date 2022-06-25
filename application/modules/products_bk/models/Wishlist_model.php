<?php 
class Wishlist_model extends CI_Model {
	private $database;
	private $table;
	function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
	}

	function _get_all_products($params = array()){
        $this->db->select(["products.*", "product_images.image_name_1", "product_images.image_name_2", "product_details.discount_type", "product_details.discount", "product_details.in_stock_qty", "(CASE WHEN product_details.discount_type='percentage' THEN products.base_price+(products.base_price*(product_details.discount/100)) WHEN product_details.discount_type='value' THEN products.base_price+product_details.discount ELSE products.base_price END) as actual_price"]);
        $this->db->from('products');
        $this->db->join('wishlist', 'wishlist.product_id=products.id ', 'inner');
        $this->db->join('product_images', 'product_images.product_id=products.id', 'left');
        $this->db->join('product_details', 'product_details.product_id=products.id', 'left');
        
        $this->db->where(['products.is_active' => 1, 'products.show_on_website'=>1]);
        //print_r($params);
        if(isset($params['condition'])){

            if(isset($params['condition']['product_category_id'])){
                $this->db->where(['products.product_category_id'=>$params['condition']['product_category_id']]);
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
        $this->db->order_by('products.created asc');
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();//exit;
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            return $query->num_rows(); 
        }else{
            return $query->result_array();
        }
    }
}