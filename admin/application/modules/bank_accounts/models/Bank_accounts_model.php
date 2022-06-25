<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_accounts_model extends CI_Model {
	function __construct() {
		parent:: __construct();
		$this->database = $this->load->database('login', TRUE);
	}

	function check_table($table=''){
    	if(empty($table))
    		return FALSE;

    	$query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
    	$res = $query->row_array();

    	return $res;
    }

    function tbl_bank_accounts(){
    	$check = $this->check_table('bank_accounts');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `bank_accounts` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL,
			  `user_type` enum('employees','customers','suppliers','companies', 'login') NOT NULL,
			  `bank_name` varchar(255) NOT NULL,
			  `account_name` varchar(255) NOT NULL,
			  `account_type` enum('Saving Account','Current Account') NOT NULL,
			  `account_number` varchar(20) NOT NULL,
			  `ifsc_code` varchar(20) NOT NULL,
			  `branch` varchar(255) NOT NULL,
			  `is_default` tinyint(1) NOT NULL DEFAULT '0',
			  `is_active` tinyint(1) NOT NULL DEFAULT '1',
			  `created` datetime NOT NULL,
			  `modified` datetime NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
			");

	    	if($query){
				
			}
    		return $query;
    	}
    	else
    		return FALSE;
    }

    function userBasedAccount($userId, $userType){
    	$db = $this->database;
		//$table = $this->get_table();
		$db->where('user_id', $userId);
		$db->where('type', $userType);
		$query=$db->get('bank_accounts');
		return $query->result_array();
    }

    function userBasedDefaultBankAccount($userId, $userType){
    	$db = $this->database;
		//$table = $this->get_table();
		//print_r($table);
		$db->where('user_id', $userId);
		$db->where('user_type', $userType);
		$db->where('is_default', true);
		$query=$db->get('bank_accounts');
		//print_r($db->last_query());
		return $query->row_array();
    }

    function get_accounts_list($conditions){
    	$db = $this->database;
		//$table = $this->get_table();
		//print_r($table);
		$db->select('*');
		
		foreach ($conditions as $key => $condition) {
			$db->where($key, $condition);
		}

		$db->order_by('is_default desc, is_active desc');
		//$db->where('address.type', $userType);
		$query=$db->get('bank_accounts');
		//print_r($db->last_query());
		return $query->result_array();
    }

    function get_custom_account_type_users($accountBelongsTo){
    	$db = $this->database;
    	//$table = $this->get_table();
    	$sql = "Select ".$accountBelongsTo.".id, Concat(".$accountBelongsTo.".first_name, ' ', ".$accountBelongsTo.".middle_name, ' ', ".$accountBelongsTo.".surname) as fullname, ".$accountBelongsTo.".profile_img, ".$accountBelongsTo.".emp_code  from ".$accountBelongsTo." WHERE ".$accountBelongsTo.".is_active=true order by fullname asc";
    	$query = $db->query($sql);
		//print_r($db->last_query());
		return $query->result_array();
    }

}
?>