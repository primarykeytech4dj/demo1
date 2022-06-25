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
				`customer_category_id` int(11) NOT NULL DEFAULT 0,
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
				`emp_code` varchar(250) DEFAULT NULL,
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
				`id` int(11) NOT NULL AUTO_INCREMENT,
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

			$customerCategories = $this->db->query("CREATE TABLE IF NOT EXISTS `customer_categories` (
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
				PRIMARY KEY(`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);
			
            $adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'customers', '#', 'fa-user', 0, 3, 'customers', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
            $menuId = $this->db->insert_id();
            if($menuId){
              $arr = [
              	['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'View Customer Categories', 'slug'=>'customers/adminindexcategory', 'class'=>'fa-shopping-bag', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'customers', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
				['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Customer Category', 'slug'=>'customers/newcategory', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'customers', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
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

	function get_category_list($condition = []){
    	$db = $this->database;
		$db->select('customer_categories.*, (select category_name from customer_categories pc where pc.id=customer_categories.parent_id) as parent');
		$db->order_by('customer_categories.is_active desc, customer_categories.id DESC');
		//$db->limit(3, 0);
		$query = $db->get_where('customer_categories', $condition);
		//print_r($db->last_query());
		return $query->result_array();
    }

    function get_category_dropdown_list($id = NULL) {
		$db = $this->database;
		$condition['is_active'] = TRUE;
		$db->select('id,category_name');
        $query = $db->get_where('customer_categories', $condition);
        return $query->result_array();
	}

	function get_customer_category_list($id=null) {
		$db = $this->database;
		$condition['is_active'] = true;
		
		$db->select('id,category_name')->where('`id` NOT IN (SELECT customer_category_id from `customers`)');
		$query = $db->get_where('customer_categories', $condition);
		//print_r($db->last_query());
		return $query->result_array();
	}

	function get_categorylist_for_customer() {
		$db = $this->database;
		$condition['is_active'] = true;
		
		$db->select('id,category_name')->where('`id` NOT IN(SELECT distinct `parent_id` from `customer_categories`)');
		$query = $db->get_where('customer_categories', $condition);
		return $query->result_array();
	}

	function customerList($postData=null){
        //echo "<pre>";print_r($postData);exit;
        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        //echo "<pre>"; print_r($postData);exit;
   
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " AND (company_name like '%".$searchValue."%' or category_name like '%".$searchValue."%' or customer_name like'%".$searchValue."%' or email like'%".$searchValue."%' or contact like '%".$searchValue."%' or gst_no like '%".$searchValue."%')";
        }
        ## Total number of records without filtering
        
        $this->db->select(['c.id','cz.zone_no', 'cz.route_no', 'c.profile_img', 'concat(a.address_1," ", a.address_2) as address','c.referral_code','c.company_name','c.created','c.modified','c.is_active','c.created_by','c.modified_by','c.gst_no','c.referral_code as ref_code','c.blood_group','c.has_multiple_sites','c.contact_1','c.contact_2', 'cc.category_name', 'concat(c.first_name," ", c.middle_name, " ", c.surname) as customer_name', 'concat(c.primary_email," / ", c.secondary_email) as email', 'concat(contact_1," / ", contact_2) as contact','ur.id as ur_id','ur.role_id as ur_rid','ur.account_type as ur_at', 'ar.area_name']);
        //$this->db->join('customers c', 'c.id=orders.customer_id', 'left');
        $this->db->join('user_roles ur', 'ur.user_id=c.id AND ur.account_type="customers"', 'left');
        $this->db->join('address a', 'a.user_id=ur.login_id AND a.type="login" and a.is_default=1', 'left');
        $this->db->join('customer_categories cc', 'cc.id=c.customer_category_id', 'left');
        $this->db->join('areas ar', 'ar.id=a.area_id', 'left');
        $this->db->join('customer_zones cz', 'cz.customer_id=c.id', 'left');
        if(isset($postData['customers']['referral_code'])){
            $this->db->where('c.referral_code', $postData['customers']['referral_code']);
        }
        
        if(isset($postData['area_id']) && !empty($postData['area_id'])){
            $this->db->where('a.area_id', $postData['area_id']);
        }
        if(isset($postData['is_active']) && !empty($postData['is_active'])){
            $this->db->where('c.is_active', $postData['is_active']);
        }
		if(isset($postData['name']) && !empty($postData['name']))
		{
			$this->db->like('concat(c.first_name," ", c.middle_name," ", c.surname)',$postData['name']);
			$this->db->or_like(' c.company_name',$postData['name']);
		}
		if(isset($postData['email']) && !empty($postData['email']))
		{
			$this->db->like('concat(c.primary_email," ", c.secondary_email)', $postData['email']);
		}
		if(isset($postData['mobile']) && !empty($postData['mobile']))
		{
			$this->db->like('concat(contact_1," ", contact_2)', $postData['mobile']);
		}

		if(isset($postData['from_date']) && !empty(trim($postData['from_date'])) && isset($postData['to_date']) && !empty(trim($postData['to_date']))){
            $fromdate = str_replace('/', '-', $postData['from_date']);
            $todate = str_replace('/', '-', $postData['to_date']);
            if($fromdate==$todate){
            //echo date('Y-m-d', strtotime($postData['from_date']));
            $this->db->like('c.created', date("Y-m-d", strtotime($fromdate)), 'after');
            }else{
                $this->db->where('c.created >=', date('Y-m-d 00:00:00', strtotime($fromdate)));
                $this->db->where('c.created <=', date('Y-m-d 23:59:00', strtotime($todate)));
            }
        }

        $sql = $this->db->get_compiled_select('customers c');

		// print_r($sql);exit;
		
        //$this->db->_reset_select();
        
        /*$this->db->select(['c.id', 'c.profile_img', 'concat(a.address_1," ", a.address_2) as address','c.referral_code','c.company_name','c.created','c.modified','c.is_active','c.created_by','c.modified_by','c.gst_no','c.referral_code as ref_code','c.blood_group','c.has_multiple_sites','c.contact_1','c.contact_2', 'cc.category_name', 'concat(c.first_name," ", c.middle_name, " ", c.surname) as customer_name', 'concat(c.primary_email," / ", c.secondary_email) as email', 'concat(contact_1," / ", contact_2) as contact','0 as ur_id','3 as ur_rid','"customers" as ur_at', 'ar.area_name']);
        //$this->db->join('customers c', 'c.id=orders.customer_id', 'left');
        $this->db->join('user_roles ur', 'ur.user_id=c.id AND ur.account_type="customers"', 'left');
        $this->db->join('address a', 'a.user_id=ur.login_id AND a.type="login" and a.is_default=1', 'left');
        $this->db->join('customer_categories cc', 'cc.id=c.customer_category_id', 'left');
        $this->db->join('areas ar', 'ar.id=a.area_id', 'left');
        //$this->db->join('customer_zones cz', 'cz.customer_id=c.id', 'left');
        if(isset($postData['customers']['referral_code'])){
            $this->db->where('c.referral_code', $postData['customers']['referral_code']);
        }
        
        if(isset($postData['area_id']) && !empty($postData['area_id'])){
            $this->db->where('a.area_id', $postData['area_id']);
        }
        if(isset($postData['is_active']) && !empty($postData['is_active'])){
            $this->db->where('c.is_active', $postData['is_active']);
        }
        $sql3 = $this->db->get_compiled_select('customers c');*/
        //echo $sql." UNION ".$sql3;exit;
        $sql2 = 'Select count(*) as allcount from ('.$sql.') t';
        // echo $sql2;exit;
        $records = $this->db->query($sql2)->result();
        $totalRecords = $records[0]->allcount;
   

        $sql2 = 'Select count(*) as allcount from ('.$sql.') t where 1=1'.$searchQuery;
        $records = $this->db->query($sql2)->result();
        $totalRecordwithFilter = $records[0]->allcount;
   		
		// echo $sql2;exit;
        $sql2 = 'Select * from ('.$sql.') t where 1=1 '. $searchQuery.' GROUP BY id order by '.$columnName.' '.$columnSortOrder;
        if ($rowperpage!='-1') {
			$sql2.=' LIMIT '.$start.', '.$rowperpage;
        }
        // echo $sql2; exit;
        $records = $this->db->query($sql2)->result();
        $data = array();
        foreach($records as $recordKey => $record ){
   
			$orderSql =  'select count(id)  as app_order_count FROM orders where order_status_id NOT IN (1,2,8) AND customer_id = '.$record->id.'';
			$countResult = $this->db->query($orderSql)->row_array();
			// print_r($countResult);exit;
           $data[] = array(
            "sr_no" => $start+$recordKey+1,
            "id"=>$record->id,
            "category_name"=>(NULL===$record->category_name)?'default':$record->category_name,
            "referral_code"=>$record->referral_code,
            "name" => $record->customer_name,
            "company_name"=>$record->company_name,
            "gst_no"=>$record->gst_no,
            "email"=>$record->email,
            "zone_no"=>$record->zone_no,
            "route_no"=>$record->route_no,
            "area_name"=>$record->area_name,
            "address"=>$record->address,
            "contact"=>$record->contact_1." / ".$record->contact_2,
            "blood_group"=>$record->blood_group,
            "created"=>date('d-m-Y', strtotime($record->created)),
            "modified"=>date('d-m-Y', strtotime($record->modified)),
            "is_active"=>$record->is_active,
            "has_multiple_sites" => $record->has_multiple_sites,
			"app_order_count" => $countResult['app_order_count'],
            'action'=>'Action'
           ); 
        }
        //echo "<pre>"; print_r($data);exit;
        ## Response
        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecordwithFilter,
           "iTotalDisplayRecords" => $totalRecords,
           "aaData" => $data
        );

        return $response;


    }

} 
?>