<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address_model extends CI_Model {
	function __construct() {
		parent:: __construct();
		$this->database = $this->load->database('login', TRUE);
		//$this->load->model('address_model');
	}

	function check_table($table=''){
    	if(empty($table))
    		return FALSE;

    	$query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
    	$res = $query->row_array();

    	return $res;
    }

    function tbl_address(){
    	$check = $this->check_table('address');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address_1` text NOT NULL,
  `address_2` text NOT NULL,
  `landmark` varchar(255) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `type` enum('employees','customers','suppliers','companies','enquiries', 'login') NOT NULL,
  `city_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `pincode` int(11) NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `lat` varchar(255) NOT NULL,
  `lng` varchar(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

	    	if($query){
				
			}
    		return $query;
    	}
    	else
    		return FALSE;
    }

    function set_table($table) {
    $this->table = $table;
  }

   function get_table() {
    $table = $this->table;
    return $table;
    }

    function userBasedAddress($userId, $userType){
        //echo "reached here";exit;
      /*echo $userId;
      echo $userType;exit;*/
    	$db = $this->database;
  		$table = $this->get_table();
  		$db->select(['address.*', 'areas.area_name', 'cities.city_name', 'states.state_name', 'countries.name']);

  		$db->where('user_id', $userId);
  		$db->where('address.type', $userType);
  		$db->join('areas', 'areas.id = address.area_id', 'left');
  		$db->join('cities', 'cities.id = address.city_id', 'left');
  		$db->join('states', 'states.id = address.state_id', 'left');
  		$db->join('countries', 'countries.id = address.country_id', 'left');
  		$db->where('address.is_active', true);
  		$query = $db->get($table);
  		//print_r($db->last_query());exit;
  		return $query->result_array();
    }

    function userBasedDefaultAddress($userId, $userType){
    	$db = $this->database;
		//$table = $this->get_table();
		//print_r($table);
		$db->select(['address.*', 'areas.area_name', 'cities.city_name', 'states.state_name', 'countries.name']);
		$db->join('areas', 'areas.id = address.area_id', 'left');
		$db->join('cities', 'cities.id = address.city_id', 'left');
		$db->join('states', 'states.id = address.state_id', 'left');
		$db->join('countries', 'countries.id = address.country_id', 'left');
		$db->where('user_id', $userId);
		$db->where('address.type', $userType);
		$db->where('is_default', true);
		$db->where('address.is_active', true);
		$query=$db->get('address');
		//print_r($db->last_query());
		return $query->row_array();
    }

    function get_address_list($conditions){
    	$db = $this->database;
  		//print_r($table);
  		$db->select('address.id, address.address_1, address.address_2, address.landmark, address.type, address.site_name, address.pincode, address.is_default, address.is_active, address.city_id, cities.city_name, address.state_id, states.state_name, address.country_id, countries.name, address.area_id, areas.area_name, address.type as address_type, address.user_id');
  		$db->join('cities','cities.id = address.city_id', 'left');
  		$db->join('states','states.id = address.state_id', 'left');
  		$db->join('areas','areas.id = address.area_id', 'left');
  		$db->join('countries','countries.id = address.country_id', 'left');
  		$db->where('address.is_active', true);
  		$db->order_by('is_default desc, is_active desc');
  		//$db->where('address.type', $userType);
  		$query=$db->get_where('address', $conditions);
  		//print_r($db->last_query());
  		return $query->result_array();
    }

    function get_custom_address_type_users($addressBelongsTo){
    	$db = $this->database;
    	//$table = $this->get_table();
    	$sql = "Select ".$addressBelongsTo.".id, Concat(".$addressBelongsTo.".first_name, ' ', ".$addressBelongsTo.".middle_name, ' ', ".$addressBelongsTo.".surname) as fullname, ".$addressBelongsTo.".profile_img, ".$addressBelongsTo.".emp_code  from ".$addressBelongsTo." WHERE ".$addressBelongsTo.".is_active=true order by fullname asc";
    	$query = $db->query($sql);
		//print_r($db->last_query());
		return $query->result_array();
    }
}
?>