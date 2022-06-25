<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_model extends CI_Model
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

    function tbl_stocks(){
        $check = $this->check_table('stocks');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `stocks` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `inward_date` date NOT NULL,
                `stock_code` varchar(255) NULL,
                `lot_no` varchar(255) NOT NULL,
                `vendor_id` int(11) NOT NULL,
                `grade` varchar(255) NOT NULL,
                `company_warehouse_id` int(11) NOT NULL,
                `amount_before_tax` float(10,2) NOT NULL,
                `amount_after_tax` float(10,2) NOT NULL,
                `other_charges` float(10,2) NOT NULL,
                `field1` varchar(255) NULL,
                `field2` varchar(255) NOT NULL,
                `invoice` varchar(255) NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY(stock_code)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

        if($query){
            $stockDetails = $this->db->query("CREATE TABLE IF NOT EXISTS `stock_details` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `stock_id` int(11) NOT NULL,
                `product_id` int(11) NOT NULL,
                `coil_no` varchar(255) NOT NULL,
                `thickness` float(10,2) NOT NULL,
                `width` float(10,2) NOT NULL,
                `length` float(10,2) NOT NULL,
                `piece` int(11) NOT NULL,
                `order_qty` float(10,3) NOT NULL,
                `qty` float(10,3) NOT NULL,
                `uom` varchar(255) NOT NULL,
                `balance_qty` FLOAT(10,3) NOT NULL,
                `unit_price` float(10,2) NOT NULL,
                `tax` float(10,2) NOT NULL,
                `remark` text NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

            $warehouse = $this->db->query("CREATE TABLE IF NOT EXISTS `company_warehouse` (
                `id` int(11) NOT NULL,
                `company_id` int(11) NOT NULL DEFAULT '1',
                `warehouse` int(11) NOT NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT '1',
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY (warehouse)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );
            
            $adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'stocks', '#', 'fa-user', 0, 3, 'stocks', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
            $menuId = $this->db->insert_id();
            if($menuId){
              $arr = [
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'Inward Listing', 'slug'=>'stocks/adminindex', 'class'=>'fa-user', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'stocks', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Inward', 'slug'=>'stocks/newstock', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'stocks', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],

              ];

              $ins = $this->db->insert_batch('temp_menu', $arr);

              $roleId = 1;
              $menuRolesArray[0] = ['menu_detail_id'=>$menuId, 'role_id'=>$roleId, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];

              $menus = $this->db->query('Select id from temp_menu where parent_id="'.$menuId.'"'); 
              foreach ($menus->result_array() as $key => $menu) {
                $menuRolesArray[count($menuRolesArray)] = ['menu_detail_id'=>$menu['id'], 'role_id'=>$roleId, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];
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

    function tbl_stockout(){
        $check = $this->check_table('stockout');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `stockout` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `outward_date` date NOT NULL,
                `order_code` varchar(255) NULL,
                `lorry_no` varchar(255) NOT NULL,
                `broker_id` int(11) NOT NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

        if($query){
            $stockoutDetails = $this->db->query("CREATE TABLE IF NOT EXISTS `stockout_details` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `stockout_id` int(11) NOT NULL,
                `product_id` int(11) NOT NULL,
                `coil_no` varchar(255) NOT NULL,
                `stock_detail_id` int(11) NOT NULL,
                `company_warehouse_id` int(11) NOT NULL,
                `thickness` float(10,2) NOT NULL,
                `width` float(10,2) NOT NULL,
                `length` float(10,2) NOT NULL,
                `piece` int(11) NOT NULL,
                `qty` float(10,2) NOT NULL,
                `uom` varchar(255) NOT NULL,
                `remark` text NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );
            
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

    function count_where($column, $value) {
        //print_r($column." ".$value);//exit;
        $db = $this->database;
        $table = $this->get_table();
        //print_r($table);/*exit;*/
        $db->where($column, $value);
        $query=$db->get($table);
        /*echo "<pre>";
        print_r($query);exit;*/
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

    function _insert_multiple($data) {
        $db = $this->database;
        $table = $this->get_table();
        $num_rows = $db->insert_batch($table,$data);
        return $num_rows;
    }

    // Get where custom column is .... return query
    function get_where_custom($col, $value) {
        $db = $this->database;
        $table = $this->get_table();
        $db->where($col, $value);
        $query=$db->get($table);
        return $query;
    }

    // Get where column id is ... return query
    function get_where($conditions = []) {
        
        $db = $this->database;
        $table = $this->get_table();
        if(is_array($conditions)){
            foreach ($conditions as $key => $condition) {
                $db->where($key, $condition);
            }
        }else{
            $db->where('id', $conditions);
        }
        $query=$db->get($table);
        //print_r($db->last_query());exit;
        return $query;
    }

    // Retrieve all data from database and order by column return query
    function get($order_by) {
        $db = $this->database;
        $table = $this->get_table();
        $db->order_by($order_by);
        $query=$db->get($table);
        return $query;
    }


    function _update($id,$data) {
        $db = $this->database;
        $table = $this->get_table();
        $this->db->where('id',$id);
        $updt = $this->db->update($table,$data);
        return $updt;
    }

    function _update_multiple($field, $data) {
        $db = $this->database;
        $table = $this->get_table();
        $updt = $db->update_batch($table, $data, $field);
        return $updt;
    }


    function stockList($postData=null) {
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
           $searchQuery = " AND (t.stock_code like '%".$searchValue."%' or t.po_no like '%".$searchValue."%' or t.warehouse like'%".$searchValue."%' or t.company_name like'%".$searchValue."%')";
        }
        ## Total number of records without filtering
        
        $this->db->select(['s.*', 'Concat(v.first_name," ", v.middle_name," ", v.surname, "-", v.company_name) as vendor', '(select count(distinct product_attribute_id) from stock_details where stock_details.stock_id=s.id and stock_details.is_active=true) as purchase_count', 'w.warehouse', 'l.username as created_by_user', 'l2.username as modified_by_user']);
        $this->db->join('vendors v', 'v.id=s.vendor_id', 'left');
        $this->db->join('login l', 'l.id=s.created_by', 'left');
        $this->db->join('login l2', 'l2.id=s.modified_by', 'left');
        $this->db->join('company_warehouse w', 'w.id=s.company_warehouse_id', 'left');
        $sql = $this->db->get_compiled_select('stocks s');
        $sql2 = 'Select count(*) as allcount from ('.$sql.') t';
        $records = $this->db->query($sql2)->result();
        $totalRecords = $records[0]->allcount;
   

        $sql2 = 'Select count(*) as allcount from ('.$sql.') t where 1=1'.$searchQuery;
        $records = $this->db->query($sql2)->result();
        $totalRecordwithFilter = $records[0]->allcount;
        
        
        $sql2 = 'Select * from ('.$sql.') t where 1=1'.$searchQuery.' order by '.$columnName.' '.$columnSortOrder;
        if ($rowperpage!='-1') {
            $sql2.=' LIMIT '.$start.', '.$rowperpage;
        }
        $records = $this->db->query($sql2)->result();
        //print_r($records);
        //echo $this->db->last_query();exit;
        $data = array();
        foreach($records as $recordKey => $record ){
            
            $active = ($record->is_active==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';
            $data[] = array(
            "sr_no" => $recordKey+1,
            "id"=>$record->id,
            "stock_code"=>$record->stock_code,
            "inward_date"=>date('d F,y', strtotime($record->inward_date)),
            "po_no"=>$record->po_no,
            "vendor"=>$record->vendor,
            "warehouse"=>$record->warehouse,
            "invoice"=>$record->invoice,
            "purchase_count"=>$record->purchase_count,
            "created"=>date('d F,y', strtotime($record->created)),
            "created_by_user"=>$record->created_by_user,
            "modified"=>date('d F,y', strtotime($record->modified)),
            "modified_by_user"=>$record->modified_by_user,
            "is_active"=>"<i class='".$active."'></i>",
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

    function stockDetailsList() {
        $db = $this->database;
        //$db->reset_query();
        $table = $this->get_table();
        //print_r($table);exit;
        $db->select([$table.'.*', '(select GROUP_CONCAT(coil_no SEPARATOR ", ") from stock_details where stock_details.stock_id=stocks.id and stock_details.is_active=true) as coil_no']);
        $db->join('stocks', 'stocks.id='.$table.'.stock_id', 'left');
        $db->order_by($table.'.stock_id DESC');
        $query = $db->get($table);
        //print_r($this->db->last_query());exit;
        return $query;
    }



    function stockOutList() {
        $db = $this->database;
        //$db->reset_query();
        $table = $this->get_table();
        //echo $table;exit;
        $db->select([$table.'.*', 'brokers.first_name',  'brokers.middle_name',  'brokers.surname', 'brokers.emp_code', 'brokers.contact_1', 'brokers.primary_email', 'brokers.company_name', 'brokers.profile_img', '(select GROUP_CONCAT(coil_no SEPARATOR ", ") from stockout_details where stockout_details.stockout_id=stockout.id and stockout_details.is_active=true) as coil_no']);
        $db->join('brokers', 'brokers.id='.$table.'.broker_id', 'LEFT');
        $db->order_by($table.'.outward_date DESC');
        $query = $db->get($table);
        //print_r($this->db->last_query());exit;
        return $query;
    }

    function orderCoverImage() {
        $db = $this->database;
        /*//$table = $this->get_table();
        $table = 'user_documents';
        //$db->select($table.'.*, customers.first_name,  customers.middle_name,  customers.surname, customers.emp_code, customers.contact_1, customers.primary_email, customers.profile_img, distinct(user_documents.file)');
        //$db->select('DISTINCT(user_documents.user_id), orders.*, customers.first_name,  customers.middle_name,  customers.surname, customers.emp_code, customers.contact_1, customers.primary_email, customers.profile_img');
        $db->join('orders', 'orders.id='.$table.'.user_id And orders.is_active=1');
        $db->join('customers', 'customers.id=orders.customer_id');
        $db->order_by('orders.date DESC');
        $query = $db->get_where($table, [$table.'.is_active'=>1, $table.'.user_type'=>'orders']);
        print_r($db->last_query());exit;*/
        $sql = 'SELECT `orders`.*, `customers`.`first_name`, `customers`.`middle_name`, `customers`.`surname`, `customers`.`emp_code`, `customers`.`contact_1`, `customers`.`primary_email`, `customers`.`profile_img`, (select file from user_documents where user_type="orders" AND user_id=orders.id order by id ASC limit 1) as coverimage

FROM `orders` JOIN `customers` ON `customers`.`id`=`orders`.`customer_id`

WHERE `orders`.`is_active` = 1
AND orders.id in (select user_id from user_documents where user_type="orders" AND user_id=orders.id)
ORDER BY `orders`.`date` DESC';

        $query = $db->query($sql);
        return $query;
    }

    // Get where custom column is .... return query
    function get_order_images($col, $value) {
        $db = $this->database;
        $table = $this->get_table();
        $db->select($table.'.*, user_documents.file');
        $db->join('user_documents', 'user_documents.user_id='.$table.'.id AND user_documents.user_type="orders"');
        $db->where($col, $value);
        $query=$db->get($table);
        return $query;
    }

}