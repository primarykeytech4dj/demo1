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

    function userBasedAddress($userId, $userType){
    	$db = $this->database;
		//$table = $this->get_table();
		$db->select(['address.*', 'areas.area_name', 'cities.city_name', 'states.state_name', 'countries.name']);

		$db->where('user_id', $userId);
		$db->where('address.type', $userType);
		$db->join('areas', 'areas.id = address.area_id', 'left');
		$db->join('cities', 'cities.id = address.city_id', 'left');
		$db->join('states', 'states.id = address.state_id', 'left');
		$db->join('countries', 'countries.id = address.country_id', 'left');
		$query = $db->get($table);
		//print_r($db->last_query());
		return $query->result_array();
    }

    function userBasedDefaultAddress($userId, $userType){
    	$db = $this->database;
		//$table = $this->get_table();
		//print_r($table);
		$db->select(['address.*', 'areas.area_name', 'cities.city_name', 'states.state_name', 'states.gst_state_code', 'countries.name']);
		$db->join('areas', 'areas.id = address.area_id', 'left');
		$db->join('cities', 'cities.id = address.city_id', 'left');
		$db->join('states', 'states.id = address.state_id', 'left');
		$db->join('countries', 'countries.id = address.country_id', 'left');
		$db->where('user_id', $userId);
		$db->where('address.type', $userType);
		$db->where('is_default', true);
		$query=$db->get('address');
		//print_r($db->last_query());
		return $query->row_array();
    }

    function get_address_list($conditions){
    	$db = $this->database;
  		//print_r($table);
  		$db->select('address.id, address.address_1, address.address_2, address.landmark, address.type, address.site_name, address.pincode, address.is_default, address.is_active, address.city_id, cities.city_name, address.state_id, states.state_name, address.country_id, countries.name, address.area_id, areas.area_name, address.type as address_type, address.user_id, address.fssi, address.gst_no, address.remark');
  		$db->join('cities','cities.id = address.city_id', 'left');
  		$db->join('states','states.id = address.state_id', 'left');
  		$db->join('areas','areas.id = address.area_id', 'left');
  		$db->join('countries','countries.id = address.country_id', 'left');
  		
  		$db->order_by('is_default desc, is_active desc');
  		//$db->where('address.type', $userType);
  		$query=$db->get_where('address', $conditions);
  		//print_r($db->last_query());
  		return $query->result_array();
    }

    function get_custom_address_type_users($addressBelongsTo){

      $db = $this->database;
      //$table = $this->get_table();
      $sql = "Select t.id, Concat(t.first_name, ' ', t.middle_name, ' ', t.surname) as fullname, t.profile_img, t.emp_code  from ".$addressBelongsTo." t WHERE t.is_active=true and id not in (Select user_id from user_roles where account_type='".$addressBelongsTo."')";

      $check = $this->check_table($addressBelongsTo);
      if($check){
        $sql.=' UNION';
        $sql.=" Select concat(ur.login_id,'-','login') as id, Concat(t.first_name, ' ', t.middle_name, ' ', t.surname) as fullname, t.profile_img, t.emp_code from user_roles ur inner join ".$addressBelongsTo." t on t.id=ur.user_id where ur.account_type='".$addressBelongsTo."' group by t.emp_code";
      }
      //echo $sql;
    	$query = $db->query($sql);
		//print_r($db->last_query());
		return $query->result_array();
  }

  function useraddresslist($params){
    $db = $this->database;
    $sql = 'Select a.id, concat(a.site_name," - ", a.pincode) as address from address a where a.type="'.$params['type'].'" and a.user_id='.$params['user_id'].' And a.user_id not in (Select ur.user_id from user_roles ur where ur.account_type="'.$params['type'].'") UNION Select a.id, concat(a.site_name," - ", a.pincode) as address from address a inner join user_roles ur on ur.login_id=a.user_id and ur.account_type="'.$params['type'].'" where ur.user_id='.$params['user_id'];
    //echo $sql;
    $query = $db->query($sql);
    //print_r($db->last_query());
    return $query->result_array();
  }
}
?>