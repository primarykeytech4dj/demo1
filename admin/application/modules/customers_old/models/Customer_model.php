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

        $query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
        $res = $query->row_array();

        return $res;
    }

    function tbl_customers(){
        $check = $this->check_table('customers');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `customers` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`first_name` varchar(255) NOT NULL,
				`middle_name` varchar(255) NOT NULL,
				`surname` varchar(255) NOT NULL,
				`gender` varchar(255) NOT NULL,
				`company_name` varchar(255) NOT NULL,
				`primary_email` varchar(255) NULL,
				`secondary_email` varchar(255) NOT NULL,
				`contact_1` varchar(12) NOT NULL,
				`contact_2` varchar(12) NOT NULL,
				`dob` date NOT NULL,
				`joining_date` date NOT NULL,
				`blood_group` varchar(3) NOT NULL,
				`profile_img` varchar(255) NOT NULL DEFAULT 'defaultm.png',
				`emp_code` varchar(250) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`has_multiple_sites` tinyint(1) NOT NULL DEFAULT '0',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				`pan_no` varchar(255) NOT NULL,
				`gst_no` varchar(255) DEFAULT NULL,
				`adhaar_no` int(11) DEFAULT NULL,
				`fuel_surcharge` float(10,2) NOT NULL,
				PRIMARY KEY (id),
				UNIQUE KEY (primary_email),
				UNIQUE KEY (emp_code)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

        if($query){
            $companyCustomers = $this->db->query("CREATE TABLE IF NOT EXISTS `companies_customers` (
				`customer_id` int(11) NOT NULL,
				`company_id` int(11) NOT NULL,
				`is_active` tinyint(4) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

			$customerReference = $this->db->query("CREATE TABLE IF NOT EXISTS `customer_references` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_id` int(11) NOT NULL,
				`package_id` int(11) NOT NULL DEFAULT '1',
				`introducer_id` int(11) NOT NULL,
				`introducer_type` varchar(255) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
			");

			
            $adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'customers', '#', 'fa-user', 0, 3, 'customers', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
            $menuId = $this->db->insert_id();
            if($menuId){
              $arr = [
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'View customers', 'slug'=>'customers/adminindex', 'class'=>'fa-user', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'customers', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Customer', 'slug'=>'customers/newcustomer', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'customers', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],

              ];

              $ins = $this->db->insert_batch('temp_menu', $arr);

              $roles = $this->db->insert('roles', ['module'=>'customers', 'role_name'=>'Customer', 'role_code'=>'cst', 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')]);
              if($roles){
              	$roleId =  $this->db->insert_id();
              	$rolesDetails = $this->db->insert('role_details', ['role_id'=>$roleId, 'is_view'=>0, 'is_add'=>0, 'is_update'=>1, 'is_delete'=>0, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')]);
              }

              $menuRolesArray[0] = ['menu_detail_id'=>$menuId, 'role_id'=>1, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];

              $menus = $this->db->query('Select id from temp_menu where parent_id="'.$menuId.'"'); 
              foreach ($menus->result_array() as $key => $menu) {
              	$menuRolesArray[count($menuRolesArray)] = ['menu_detail_id'=>$menu['id'], 'role_id'=>1, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];
              }

              $ins = $this->db->insert_batch('menu_roles', $menuRolesArray);


            }
            //$insert = $this->db->query("");
            
        }
            return $query;
        }
        else
            return TRUE;
        
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