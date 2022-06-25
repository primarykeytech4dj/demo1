<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees_model extends CI_Model
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

    function tbl_employees(){
        $check = $this->check_table('employees');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `contact_1` varchar(15) NOT NULL,
  `contact_2` varchar(15) NOT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `primary_email` varchar(255) DEFAULT NULL,
  `secondary_email` varchar(255) NOT NULL,
  `profile_img` varchar(255) NOT NULL DEFAULT 'default.png',
  `emp_code` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `allow_login` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `pan_no` varchar(255) NOT NULL,
  `adhaar_no` varchar(255) NOT NULL,
  `licence_no` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `licence_state` int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (emp_code),
  UNIQUE KEY (primary_email)

) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

        if($query){
            $otherDetails = $this->db->query("CREATE TABLE IF NOT EXISTS `employees_salaries` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `employee_id` int(11) NOT NULL,
              `employment_start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `employment_end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `salary` int(11) NOT NULL,
              `provident_fund` int(11) NOT NULL,
              `esic` int(11) NOT NULL,
              `professional_tax` int(11) NOT NULL,
              `is_active` tinyint(1) NOT NULL DEFAULT '1',
              `created` datetime NOT NULL,
              `modified` datetime NOT NULL,
              PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

            $adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'Employees', '#', 'fa-user', 0, 2, 'employees', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
            $menuId = $this->db->insert_id();
            if($menuId){
              $arr = [
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'View Employees', 'slug'=>'employees/adminindex', 'class'=>'fa-user', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'employees', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Employee', 'slug'=>'employees/newemployee', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'employees', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],

              ];

              $ins = $this->db->insert_batch('temp_menu', $arr);

            }
            //$insert = $this->db->query("");
            
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


    // Count results where column = value and return integer
    function count_where($column, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($column, $value);
		$query=$db->get($table);
		$num_rows = $query->num_rows();
		return $num_rows;
    }

    // Insert data into table $data is an associative array column=>value
    function _insert($data) {
		$db = $this->database;
		$table = $this->get_table();
		$db->insert($table, $data);
		$insert_id = $db->insert_id();
   		return  $insert_id;
    }

    // Get where custom column is .... return query
    function get_where_custom($col, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($col, $value);
		$query=$db->get($table);
		return $query;
    }

    function get_dropdown_list() {
		//debug("reached here");exit;
		$condition['is_active'] = TRUE;
		$this->db->select('id, blood_group');
        $query = $this->db->get_where('blood_group', $condition);
        return $query->result_array();
	}

	// Get where column id is ... return query
    function get_where($id) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where('id', $id);
		$query=$db->get($table);
		return $query;
    }
    
    function get_employees_details($orderby = '') {

     	$db = $this->database;
     	$table = $this->get_table();
        if($orderby=='')
     	  $db->order_by('is_active desc, modified DESC, id DESC');
        else
            $db->order_by($orderby);

        $db->select('employees.*, states.state_name as licence_state');
        $db->join('states', 'states.id=employees.licence_state', 'left');
     	$query = $db->get('employees');
     	return $query->result_array();
    }

    function update_employees($id,$data) {
        $this->db->where('id',$id);
        $updt = $this->db->update('employees',$data);
        return $updt;
    }

    function get_employee_other_details($empId){
    	$db = $this->database;
    	$table = $this->get_table();
    	$db->order_by('is_active desc, employment_start_date desc');
    	$query = $db->get_where($table, ['employee_id'=>$empId]);
    	return $query->result_array();
    }

    function get_employee_active_other_details($empId){
    	$db = $this->database;
    	$table = $this->get_table();
    	$query = $db->get_where($table, ['employee_id'=>$empId, 'is_active'=>true]);
    	return $query->row_array();
    }

    function insert_other_details($data){
    	$db = $this->database;
		$table = $this->get_table();
		$db->insert($table, $data);
		$insert_id = $db->insert_id();
   		return  $insert_id;
    }

    function update_other_details($data, $updField = []){
    	$db = $this->database;
		$table = $this->get_table();
		foreach ($updField as $key => $value) {
			$db->where($key, $value);
		}
		$query = $db->update($table, $data);
   		return  $query;
    }

    function employee_list() {
        $condition['is_active'] = true;
        $db = $this->database;
        $table = $this->get_table();
        $this->db->select('id,emp_code, first_name, middle_name, surname, is_active');
        $query = $this->db->get_where('employees', $condition);
        return $query->result_array();
    }

}

?>