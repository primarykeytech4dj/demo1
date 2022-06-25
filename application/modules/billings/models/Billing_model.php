<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing_model extends CI_Model {
	private $database;
	private $table;
	function construct() {
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

    // Get where column id is ... return query
    function get_where($id) {
		//echo "hello";
		/*print
		exit;*/
		//$db = $this->database;
		$table = $this->get_table();
		//echo $table;
		$this->db->select($table.'.*, address.site_name, address.address_1, address.address_2, concat(customers.first_name, " ", customers.middle_name, " ", customers.surname) as client_name, customer_sites.customer_id, customers.pan_no, customers.gst_no');
		$this->db->join('customer_sites', 'customer_sites.id='.$table.'.customer_site_id');
		$this->db->join('address', 'address.id=customer_sites.address_id');
		$this->db->join('customers', 'customers.id=customer_sites.customer_id');
		$this->db->where($table.'.id', $id);
		$query=$this->db->get($table);
		//print_r($query->row_array());exit;
		return $query->row_array();
    }

	function getBillingAreas(){
		$this->db->select('distinct(address.area_id) as area_id, areas.area_name');
		$this->db->join('address', 'address.id=customer_sites.address_id');
		$this->db->join('areas', 'areas.id=address.area_id');
		$this->db->order_by('areas.area_name asc');
		$query = $this->db->get_where('customer_sites');

		return $query->result_array();
	}

	function custom_billing($condition = []){
		//print_r($condition);
		$str = 'SELECT css.id, css.customer_site_id, css.product_id, css.start_date, css.end_date, css.no_of_labour, css.no_of_shift, css.night_shift_labour_count, css.cost, cs.customer_id, cs.address_id, cs.site_code, a.address_1, a.address_2, a.landmark, a.site_name, a.pincode, a.area_id, areas.area_name, cities.city_name, states.state_name, countries.name, concat(c.first_name," ", c.middle_name, " ", c.surname) as full_name, cs.service_charge_type, cs.service_charge FROM `customer_site_services` css inner join customer_sites cs on cs.id=css.customer_site_id inner join address a on a.id=cs.address_id inner join areas on areas.id=a.area_id inner join cities on cities.id = a.city_id inner join states on states.id=a.state_id inner join countries on countries.id=a.country_id inner join customers c on cs.customer_id=c.id where css.start_date<="'.$condition['start_date'].'" and (css.end_date>="'.$condition['end_date'].'" or css.end_date="0000-00-00") AND css.is_active=true';
		if(isset($condition['area_id']))
			$str.= ' AND a.area_id IN ('.implode(", ", $condition['area_id']).')';

		if(isset($condition['customer_site_id']))
			$str.= ' AND cs.id IN ('.implode(',',$condition['customer_site_id']).')';

		$str.= ' order by css.product_id asc, cs.customer_id asc';
		$query = $this->db->query($str);
		//$this->db->query($query);
		//echo $this->db->last_query();
		return $query->result_array();
	}

	function _insert_site_bill($data = []){
		//print_r($data);
		$db = $this->database;
		$table = $this->get_table();
		//print_r($table);//exit;
		$this->db->insert($table, $data);
		$this->db->last_query();
		$insert_id = $this->db->insert_id();
		//print_r($insert_id);exit;
   		//return  $insert_id;
   		return  $insert_id;
	}

	function update_multiple_bill($data, $updField){
		$table = $this->get_table();
		$query = $this->db->update_batch($table, $data, $updField);
		//print_r($this->db->last_query());
		return $query;
	}

	function _insert_multiple_bill_details($data){
		$table = $this->get_table();
		$query = $this->db->insert_batch($table, $data);
		//print_r($this->db->last_query());
		return $query;
	}

	function get_bills($condition=[])
	{
		$table = $this->get_table();
		$this->db->select('concat(customers.first_name, " ", customers.middle_name, " ", customers.surname) as full_name, customers.company_name, areas.area_name, '.$table.'.*, address.site_name');
		//$this->db->join('customer_sites', 'customer_sites.id='.$table.'.customer_site_id');
		$this->db->join('areas', 'areas.id='.$table.'.area_id', 'left');
		$this->db->join('address', 'address.id=customer_sites.address_id', 'left');
		$this->db->join('customers', 'customers.id=customer_sites.customer_id', 'left');
		$this->db->order_by($table.'.created desc');
		$query = $this->db->get($table);
		return $query->result_array();
	}

	function get_courier_bills($condition=[])
	{
		$table = $this->get_table();
		$this->db->select('concat(customers.first_name, " ", customers.middle_name, " ", customers.surname) as full_name, customers.company_name, customer_bills.*, (select count(customer_bill_details.id) from customer_bill_details where customer_bill_details.customer_bill_id = customer_bills.id) as annexure_count');
		$this->db->join('customers', 'customers.id=customer_bills.customer_id', 'left');
		//$this->db->join('customer_bill_details', 'customer_sites.customer_id=customer.id');
		/*$this->db->join('areas', 'areas.id='.$table.'.area_id', 'left');
		$this->db->join('address', 'address.id=customer_sites.address_id', 'left');*/
		$this->db->order_by('customer_bills.created desc');
		$query = $this->db->get('customer_bills');
		return $query->result_array();
	}

	function get_bill_details($billId) {
		$table = $this->get_table();
		$this->db->select($table.'.*, products.product, products.product_code');
		$this->db->join('products', 'products.id='.$table.'.product_id');
		$query = $this->db->get_where($table, ['customer_site_bill_id'=>$billId]);
		return $query->result_array();
	}

	function get_site_customers(){
		$this->db->select(['distinct(customers.id) as customer_id', 'concat(customers.first_name, " ", customers.middle_name, " ", customers.surname) as client']);
		$this->db->join('customers', 'customer_sites.customer_id=customers.id');
		$query = $this->db->get('customer_sites');
		return $query->result_array();
	}

	function custom_billing_clientwise($condition = []){
		//print_r($condition);
		$str = 'SELECT css.id, css.customer_site_id, css.product_id, css.start_date, css.end_date, css.consign_no, css.weight, css.mode, css.ref_no, css.cost, cs.customer_id, cs.address_id, cs.site_code, a.address_1, a.address_2, a.landmark, a.site_name, a.pincode, a.area_id, areas.area_name, cities.city_name, states.state_name, countries.name, concat(c.first_name," ", c.middle_name, " ", c.surname) as full_name, cs.service_charge_type, cs.service_charge FROM `customer_site_services` css inner join customer_sites cs on cs.id=css.customer_site_id inner join address a on a.id=cs.address_id inner join areas on areas.id=a.area_id inner join cities on cities.id = a.city_id inner join states on states.id=a.state_id inner join countries on countries.id=a.country_id inner join customers c on cs.customer_id=c.id where css.start_date<="'.$condition['start_date'].'" and (css.end_date>="'.$condition['start_date'].'" or css.end_date="0000-00-00") AND css.is_active=true';
		if(isset($condition['area_id']))
			$str.= ' AND a.area_id IN ('.implode(", ", $condition['area_id']).')';

		if(isset($condition['customer_site_id']))
			$str.= ' AND cs.id IN ('.implode(',',$condition['customer_site_id']).')';

		if(isset($condition['customer_id']))
			$str.= ' AND cs.customer_id IN ('.implode(',',$condition['customer_id']).')';

		$str.= ' order by css.product_id asc, cs.customer_id asc';
		//echo $str;
		$query = $this->db->query($str);
		//$this->db->query($query);
		//echo $this->db->last_query();
		return $query->result_array();
	}

}
?>