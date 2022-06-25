<?php 
if(!defined('BASEPATH'))exit('No direct script access allowed');

class Customer_model extends CI_Model{
	private $database;
	private $table;

    function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
	}

	function check_table($table=''){
		if(empty($table))
			return FALSE;
		$query = $this->db->query('Show tables like "'.$table.'"');
		$res= $query->row_array();
		return $res;
	}

	function tbl_customer(){
		$check = $this->check_table('customers');
		if(!$check){
			$query = $this->db->query("CREATE TABLE IF NOT EXISTS `customers` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `first_name` varchar(255) NOT NULL,
			  `middle_name` varchar(255) NOT NULL,
			  `surname` varchar(255) NOT NULL,
			  `gender` varchar(255) NOT NULL,
			  `company_name` varchar(255) NOT NULL,
			  `primary_email` varchar(255) NOT NULL,
			  `secondary_email` varchar(255) NOT NULL,
			  `contact_1` varchar(12) NOT NULL,
			  `contact_2` varchar(12) NOT NULL,
			  `dob` date NOT NULL,
			  `joining_date` date NOT NULL,
			  `blood_group` varchar(3) NOT NULL,
			  `profile_img` varchar(255) NOT NULL DEFAULT 'defaultm.jpg',
			  `emp_code` varchar(250) NOT NULL,
			  `is_active` tinyint(1) NOT NULL DEFAULT '1',
			  `created` datetime NOT NULL,
			  `modified` datetime NOT NULL,
			  `pan_no` varchar(255) NOT NULL,
			  `gst_no` varchar(255) DEFAULT NULL,
			  `adhaar_no` int(11) DEFAULT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);
		}else{
			return TRUE;
		}
	}

	function set_table($table) {
		$this->table = $table;
	}

	function get_table() {
		$table = $this->table ;
		return $table;
	}

	function get($order_by) {
		$db = $this->database;
		$table = $this->get_table();
		$db->order_by($order_by);
		$query=$db->get($table);
		return $query;
    }

    // Get where custom column is .... return query
    function get_where_custom($col, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($col, $value);
		$query=$db->get($table);
		//echo $db->last_query();
		return $query;
    }

    function get_activeCustomers($order_by) {
    	$db = $this->database;
		$table = $this->get_table();
		$db->where('is_active', true);
		$db->order_by($order_by);
		$query=$db->get($table);
		return $query->result_array();
    }

	function get_where($id){
		//echo $id;
		$db = $this->database;
		$table = $this->get_table();
		//print_r($table);
		$db->where('id', $id);
		$query = $db->get($table);
		return $query;
	}


	// Count results where column = value and return integer
    function count_where($column, $value) {
    	//print_r($column." ".$value);//exit;
		$db = $this->database;
		$table = $this->get_table();
		$query = $db->get_where($table, [$column=>$value]);
		//print($db->last_query());
		$num_rows = $query->num_rows();
		return $num_rows;
    }

	function _insert($data) {
		//$this->set_table("customers");
		$db = $this->database;
		$table = $this->get_table();
		$query = $db->insert($table, $data);
		$insert_id = $db->insert_id();
		return $insert_id;

	}

	function _update($id, $data) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where('id',$id);
		$update = $db->update($table, $data);
		return $update;
	}

	function get_dropdown_list() {
		$db = $this->database;
		$condition['is_active'] = TRUE;
		$db->select('id, blood_group');
		$query = $db->get_where('blood_group', $condition);
		/*echo "<pre>";
		print_r($query);*///exit;
		return $query->result_array();
	}

	function getCityWiseCustomers($condition){
		/*print_r($condition);
		exit;*/
		$db = $this->database;
		$table = $this->get_table();
		//print_r($table);
		$db->select('customers.id, customers.first_name, customers.middle_name, customers.surname, customers.emp_code');
		$db->join('address', 'address.user_id = customers.id AND address.type="customers" AND address.is_default=1', 'inner');
		foreach ($condition as $key => $value) {
			$db->where($key, $value);
		}
		$query = $db->get($table);
		//echo $db->last_query();
		return $query->result_array();
	}

} 
?>