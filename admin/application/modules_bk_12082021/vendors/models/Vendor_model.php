<?php 
if(!defined('BASEPATH'))exit('No direct script access allowed');

class Vendor_model extends CI_Model{
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

    function tbl_vendor_categories(){
        $check = $this->check_table('vendor_categories');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
        	$query = $this->db->query("CREATE TABLE IF NOT EXISTS `vendor_categories` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `parent_id` int(11) NOT NULL,
				  `category_name` varchar(255) NOT NULL,
				  `description` longtext NOT NULL,
				  `slug` varchar(255) NOT NULL,
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL,
				  `meta_keyword` varchar(160) NOT NULL,
				  `meta_title` varchar(160) NOT NULL,
				  `meta_description` varchar(160) NOT NULL,
				  PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );
            if($query){
	            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `vendors` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`vendor_category_id` int(11) NOT NULL,
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
					`pan_no` varchar(255) NOT NULL,
					`gst_no` varchar(255) DEFAULT NULL,
					`adhaar_no` int(11) DEFAULT NULL,
					`fuel_surcharge` float(10,2) NOT NULL,
					`allow_login` tinyint(1) NOT NULL default 0,
					`is_active` tinyint(1) NOT NULL DEFAULT '1',
					`has_multiple_sites` tinyint(1) NOT NULL DEFAULT '0',
					`created` datetime NOT NULL,
					`modified` datetime NOT NULL,
					PRIMARY KEY (id),
					UNIQUE KEY (primary_email),
					UNIQUE KEY (emp_code)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
	            );

		        if($query){
		            $companyVendors = $this->db->query("CREATE TABLE IF NOT EXISTS `vendor_wise_categories` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`vendor_id` int(11) NOT NULL,
						`vendor_category_id` int(11) NOT NULL,
						`is_active` tinyint(4) NOT NULL DEFAULT '1',
						`created` datetime NOT NULL,
						`modified` datetime NOT NULL,
						PRIMARY KEY (id)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
					);
					
		            $adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'vendors', '#', 'fa-user', 0, 3, 'vendors', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
		            $menuId = $this->db->insert_id();
		            if($menuId){
		              $arr = [
		              	['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'Vendor Categories', 'slug'=>'vendors/adminindexcategory', 'class'=>'fa-user', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'vendors', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
						['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Vendor Category', 'slug'=>'vendors/newcategory', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'vendors', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
		                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'Vendors List', 'slug'=>'vendors/adminindex', 'class'=>'fa-user', 'is_custom_constant'=>0, 'priority'=>3, 'module'=>'vendors', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
		                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Vendor', 'slug'=>'vendors/newvendor', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>4, 'module'=>'vendors', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],

		              ];

		              $ins = $this->db->insert_batch('temp_menu', $arr);

		              $roles = $this->db->insert('roles', ['module'=>'vendors', 'role_name'=>'Vendor', 'role_code'=>'sup', 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')]);
		              if($roles){
		              	$roleId =  $this->db->insert_id();
		              	$rolesDetails = $this->db->insert('role_details', ['role_id'=>$roleId, 'is_view'=>1, 'is_add'=>1, 'is_update'=>1, 'is_delete'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')]);
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

	function get_category_list($condition = []){
    	$db = $this->database;
		$db->select('vendor_categories.*, (select category_name from vendor_categories pc where pc.id=vendor_categories.parent_id) as parent');
		$db->order_by('vendor_categories.is_active desc, vendor_categories.id DESC');
		//$db->limit(3, 0);
		$query = $db->get_where('vendor_categories', $condition);
		//print_r($db->last_query());
		return $query->result_array();
    }

    function get_category_dropdown_list($id = NULL) {
		$db = $this->database;
		$condition['is_active'] = TRUE;
		$db->select('id,category_name');
        $query = $db->get_where('vendor_categories', $condition);
        return $query->result_array();
	}

	function get_vendor_category_list($id=null) {
		$db = $this->database;
		$condition['is_active'] = true;
		
		$db->select('id,category_name')->where('`id` NOT IN (SELECT vendor_category_id from `vendors`)');
		$query = $db->get_where('vendor_categories', $condition);
		//print_r($db->last_query());
		return $query->result_array();
	}

} 
?>