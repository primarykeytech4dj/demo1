<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*====================================================================================================
 |	ORIGINAL TEMPLATE BY DAVID CONNELLY @ INSIDERCLUB.COM MODIFIED BY LEWIS NELSON @ LEWNELSON.COM
 |====================================================================================================
 |
 |	This is the basic model template where only the table name changes and the
 |	class name changes. It was originally put together by David Connelly from
 |	http://insiderclub.com and the original template can be found at
 |
 |	http://www.insiderclub.org/perfectcontroller
 |
 |	It has been modified by Lewis Nelson from http://lewnelson.com to include
 |	comments and add a few more functions for some additional common queries.
 |
 ====================================================================================================
 |
 |	Template designed to run with CodeIgniter & wiredesignz Modular Extensions - HMVC
 |
 |====================================================================================================
 */

class Mdl_Login extends CI_Model
{

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

    function tbl_ip_blacklist(){
    	$check = $this->check_table('ip_blacklist');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `ip_blacklist` (
	 				`id` int(11) NOT NULL AUTO_INCREMENT,
					`ip_address` varchar(15) NOT NULL,
					`failed_attempts` int(11) NOT NULL,
					`lock_time` varchar(15) DEFAULT NULL,
					`last_login_attempt` varchar(15) NOT NULL,
					PRIMARY KEY (id),
					UNIQUE KEY (ip_address)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);
    		return $query;
    	}
    	else
    		return TRUE;
    	
    }

    function tbl_login(){
    	$check = $this->check_table('login');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `login`(
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `first_name` varchar(64) NOT NULL,
			  `surname` varchar(64) NOT NULL,
			  `username` varchar(24) DEFAULT NULL,
			  `password_hash` varchar(255) DEFAULT NULL,
			  `employee_id` int(11) NOT NULL,
			  `account_type` varchar(32) NOT NULL,
			  `email` varchar(320) NOT NULL,
			  `email_verification_link` varchar(64) DEFAULT NULL,
			  `mobile_verified` varchar(3) NOT NULL,
			  `email_ver_time` varchar(15) DEFAULT NULL,
			  `email_verified` varchar(3) DEFAULT NULL,
			  `accnt_create_time` varchar(15) NOT NULL,
			  `passwd_reset_str` varchar(64) DEFAULT NULL,
			  `passwd_reset_time` varchar(15) DEFAULT NULL,
			  `is_active` tinyint(1) NOT NULL DEFAULT '1',
			  `created` datetime NOT NULL,
			  `modified` datetime NOT NULL,
			  PRIMARY KEY (id),
			  UNIQUE KEY (username),
			  UNIQUE KEY (email)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

			if($query){
				$insert = $this->db->query("INSERT INTO `login` (`first_name`, `surname`, `username`, `password_hash`, `employee_id`, `account_type`, `email`, `email_verification_link`, `mobile_verified`, `email_ver_time`, `email_verified`, `accnt_create_time`, `passwd_reset_str`, `passwd_reset_time`, `is_active`, `created`, `modified`) VALUES('Primary', 'Technologies', 'primarykeytech', '".password_hash('12345', PASSWORD_DEFAULT)."', 1, 'companies', 'primarykeytech@gmail.com', NULL, '', NULL, 'yes', '1499587599', NULL, NULL, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");

				$adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'Users', '#', 'fa-user', 0, 11, 'login', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
	            $menuId = $this->db->insert_id();
	            if($menuId){
	              $arr = [
	                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'View User', 'slug'=>'login/adminindex2', 'class'=>'fa-user', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'login', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
	                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New User', 'slug'=>'login/createuser', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'login', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],

	              ];

	              $ins = $this->db->insert_batch('temp_menu', $arr);

	            }
			}
    		return $query;
    	}
    	else
    		return TRUE;
    }

    function tbl_blood_group(){
    	$check = $this->check_table('blood_group');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE `blood_group` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `blood_group` varchar(5) NOT NULL,
				  `desciption` varchar(255) NOT NULL,
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL,
				  PRIMARY KEY (id),
				  UNIQUE KEY (blood_group)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

			if($query){
				$insert = $this->db->query("INSERT INTO `blood_group` (`id`, `blood_group`, `desciption`, `is_active`, `created`, `modified`) VALUES
(1, 'A+ve', '', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(2, 'B+ve', '', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(3, 'AB+', 'AB+', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(4, 'O+ve', '', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."');");
			}
    		return $query;
    	}
    	else
    		return TRUE;
    }

    function tbl_user_roles()
    {
    	$check = $this->check_table('user_roles');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `user_roles` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`user_id` int(11) NOT NULL,
				`role_id` int(11) NOT NULL,
				`account_type` varchar(255) NOT NULL,
				`login_id` int(11) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

			if($query){
				$insert = $this->db->query("INSERT INTO `user_roles` (`user_id`, `role_id`, `account_type`, `login_id`, `is_active`, `created`, `modified`) VALUES (1, 1, 'companies', 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
			}
    		return $query;
    	}
    	else
    		return TRUE;
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
		return $query;
    }

	// Get where custom column is .... return query
    function get_where_custom($col, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($col, $value);
		$query=$db->get($table);
		return $query;
    }

    // Get where custom column is .... return query
    function get_where_custom_login($col, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->select($table.'.*, concat(employees.first_name," ", employees.middle_name," ", employees.surname) as emp_name, employees.profile_img, employees.id as emp_id, employees.emp_code');
		$db->join('employees', 'employees.id='.$table.'.employee_id');
		$db->where($col, $value);
		$query=$db->get($table);
		return $query;
    }
	
	// Get where with multiple where conditions $data contains conditions as associative
	// array column=>condition
    function get_multiple_where($data) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($data);
		$query=$db->get($table);
		return $query;
    }
	
	// Get where column like %match% for single where condition
    function get_where_like($column, $match) {
		$db = $this->database;
		$table = $this->get_table();
		$db->like($column, $match);
		$query=$db->get($table);
		return $query;
    }
	
	// Get where column like %match% for each $data. $data is associative array column=>match
    function get_where_like_multiple($data) {
		$db = $this->database;
		$table = $this->get_table();
		$db->like($data);
		$query=$db->get($table);
		return $query;
    }
	
	// Get where column not like %match% for single where condition
    function get_where_not_like($column, $match) {
		$db = $this->database;
		$table = $this->get_table();
		$db->not_like($column, $match);
		$query=$db->get($table);
		return $query;
    }

	// Insert data into table $data is an associative array column=>value
    function _insert($data) {
		$db = $this->database;
		$table = $this->get_table();
		$db->insert($table, $data);
		$insert_id = $db->insert_id();
   		return  $insert_id;
    }
	
	// Insert data into table $data is an associative array [0=>['column'=>'value'], 1=>['column2'=>'value2']]
    function insert_batch($data) {
		$db = $this->database;
		$table = $this->get_table();
		$db->insert_batch($table, $data);
    }

	// Update existing row where id = $id and data is an associative array column=>value
    function _update($id, $data) {
    	//echo '<prev>';echo $id;print_r($data);exit;
		$db = $this->database;
		$table = $this->get_table();
		$db->where('id', $id);
		$update = $db->update($table, $data);
		return $update;
    }

	// Delete a row where id = $id
    function _delete($id) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where('id', $id);
		$db->delete($table);
    }

	// Delete a row where $column = $value
    function delete_where($column, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($column, $value);
		$db->delete($table);
    }
	
	
    function get_dropdown_list() {
		//debug("reached here");exit;
		$condition['is_active'] = TRUE;
		$this->db->select('id, blood_group');
        $query = $this->db->get_where('blood_group', $condition);
        return $query->result_array();
	}

	// Get where custom column is .... return query
    function generic_login($column, $value) {
		$db = $this->database;
		$table = $this->get_table();
		
		//$sql = "Select login.*, employees.id as emp_id, concat(employees.first_name,' ', employees.middle_name,' ', employees.surname) as emp_name, employees.emp_code, employees.profile_img from login inner join employees on employees.id=login.employee_id and login.account_type='employees' where ".$column."='".$value."' UNION Select login.*, customers.id as emp_id, concat(customers.first_name,' ', customers.middle_name,' ', customers.surname) as emp_name, customers.emp_code, customers.profile_img from login inner join customers on customers.id=login.employee_id and login.account_type='customers' where ".$column."='".$value."'";
		//echo $sql;
		//$db->select($table.'.*');
		$sql = "Select login.*, concat(login.first_name, ' ', login.surname) as emp_name  from login where ".$column."='".$value."'";
		//$db->join('user_roles', 'user_roles.login_id='.$table.'.id');
		//$db->join('employees', 'employees.id='.$table.'.employee_id');
		$query = $db->query($sql);
		//$query=$db->get($table);
		return $query;
    }

    function get_user_details($conditions) {
    	$db = $this->database;
		//$table = $this->get_table();
		$conditions['is_active'] = TRUE;
		//$condition['login_id']
		$db->select('*');
        $query = $db->get_where('user_roles', $conditions);
        return $query->row_array();
	}

	function login_listing($orderby = '') {

     	$db = $this->database;
     	$db->select(['login.*', '(select GROUP_CONCAT(roles.role_name SEPARATOR ", ") from roles inner join user_roles on user_roles.role_id=roles.id where user_roles.login_id=login.id) as roles']);
     	//$db->join('user_roles', 'user_roles');
     	$db->order_by($orderby);
     	$query = $db->get('login');
     	return $query->result_array();
    }

}