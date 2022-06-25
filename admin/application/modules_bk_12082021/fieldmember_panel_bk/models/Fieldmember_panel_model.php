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

class Fieldmember_panel_model extends CI_Model
{

	// Declare table variable for multiple tables
	public $table;

    function __construct() {
		parent::__construct();
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
		$table = $this->get_table();
		$this->db->order_by($order_by);
		$query=$this->db->get($table);
		return $query;
    }

	// Limit results, then offset and order by column return query
    function get_with_limit($limit, $offset, $order_by) {
		$table = $this->get_table();
		$this->db->limit($limit, $offset);
		$this->db->order_by($order_by);
		$query=$this->db->get($table);
		return $query;
    }

	// Get where column id is ... return query
    function get_where($id) {
		$table = $this->get_table();
		$this->db->where('id', $id);
		$query=$this->db->get($table);
		return $query;
    }

	// Get where custom column is .... return query
    function get_where_custom($col, $value) {
		$table = $this->get_table();
		$this->db->where($col, $value);
		$query=$this->db->get($table);
		return $query;
    }
	
	// Get where with multiple where conditions $data contains conditions as associative
	// array column=>condition
    function get_multiple_where($data) {
		$table = $this->get_table();
		$this->db->where($data);
		$query=$this->db->get($table);
		return $query;
    }
	
	// Get where column like %match% for single where condition
    function get_where_like($column, $match) {
		$table = $this->get_table();
		$this->db->like($column, $match);
		$query=$this->db->get($table);
		return $query;
    }
	
	// Get where column like %match% for each $data. $data is associative array column=>match
    function get_where_like_multiple($data) {
		$table = $this->get_table();
		$this->db->like($data);
		$query=$this->db->get($table);
		return $query;
    }
	
	// Get where column not like %match% for single where condition
    function get_where_not_like($column, $match) {
		$table = $this->get_table();
		$this->db->not_like($column, $match);
		$query=$this->db->get($table);
		return $query;
    }

	// Insert data into table $data is an associative array column=>value
    function _insert($data) {
		$table = $this->get_table();
		$this->db->insert($table, $data);
    }
	
	// Insert data into table $data is an associative array column=>value
    function insert_batch($data) {
		$table = $this->get_table();
		$this->db->insert_batch($table, $data);
    }

	// Update existing row where id = $id and data is an associative array column=>value
    function _update($id, $data) {
		$table = $this->get_table();
		$this->db->where('id', $id);
		$this->db->update($table, $data);
    }

	// Delete a row where id = $id
    function _delete($id) {
		$table = $this->get_table();
		$this->db->where('id', $id);
		$this->db->delete($table);
    }

	// Delete a row where $column = $value
    function delete_where($column, $value) {
		$table = $this->get_table();
		$this->db->where($column, $value);
		$this->db->delete($table);
    }
	
	// Count results where column = value and return integer
    function count_where($column, $value) {
		$table = $this->get_table();
		$this->db->where($column, $value);
		$query=$this->db->get($table);
		$num_rows = $query->num_rows();
		return $num_rows;
    }

	// Count all the rows in a table and return integer
    function count_all() {
		$table = $this->get_table();
		$query=$this->db->get($table);
		$num_rows = $query->num_rows();
		return $num_rows;
    }

	// Find the highest value in id then return id
    function get_max() {
		$table = $this->get_table();
		$this->db->select_max('id');
		$query = $this->db->get($table);
		$row=$query->row();
		$id=$row->id;
		return $id;
    }

	// Specify a custom query then return query
		function _custom_query($mysql_query) {
		$query = $this->db->query($mysql_query);
		return $query;
	}
	
	function orderList($postData=null){
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
           $searchQuery = " AND (t.order_code like '%".$searchValue."%' or t.customer_name like '%".$searchValue."%' or t.date like'%".$searchValue."%' or t.invoice_address like'%".$searchValue."%' or t.delivery_address like'%".$searchValue."%')";
        }
        ## Total number of records without filtering
        
        $this->db->select(['o.*', 'concat(e.first_name, " ", e.middle_name, " ", e.surname) as employee_name', 'concat(c.first_name, " ", c.middle_name, " ", c.surname) as customer_name', 'ar.area_name', 'concat(a.address_1," ", a.address_2," ",cn.name," ",st.state_name," ",ct.city_name," ",ar.area_name) as invoice_address', 'concat(a.address_1," ", a.address_2," ",cn.name," ",st.state_name," ",ct.city_name," ",ar.area_name) as delivery_address', 'a.remark as address_remark', 'o.created as time']);
        //$this->db->join('customers c', 'c.id=orders.customer_id', 'left');
		$this->db->join('orders o', 'o.id=dbo.order_id');
		$this->db->join('order_status os', 'os.id=o.order_status_id', 'left');
        $this->db->join('customers c', 'c.id=o.customer_id', 'left');
        $this->db->join('address a', 'a.id=o.shipping_address_id', 'left');
        $this->db->join('countries cn', 'cn.id=a.country_id', 'left');
        $this->db->join('states st', 'st.id=a.state_id', 'left');
        $this->db->join('cities ct', 'ct.id=a.city_id', 'left');
		$this->db->join('areas ar', 'ar.id=a.area_id', 'left');
		$this->db->join('employees e', 'e.id=dbo.employee_id');
		
		if(count($postData['condition'])>0){
			foreach($postData['condition'] as $conKey=>$condition){
				$this->db->where($conKey, $condition);
			}
		}
		
        $sql = $this->db->get_compiled_select('deliveryboy_order dbo');
		$sql2 = 'Select count(*) as allcount from ('.$sql.') t';
		//echo $sql2;exit;
        $records = $this->db->query($sql2)->result();
        $totalRecords = $records[0]->allcount;
   

		$sql2 = 'Select count(*) as allcount from ('.$sql.') t where 1=1'.$searchQuery;
		//echo $sql2;exit;
        $records = $this->db->query($sql2)->result();
        $totalRecordwithFilter = $records[0]->allcount;
   		
   		
        $sql2 = 'Select * from ('.$sql.') t where 1=1'.$searchQuery.' order by '.$columnName.' '.$columnSortOrder;
        if ($rowperpage!='-1') {
            $sql2.=' LIMIT '.$start.', '.$rowperpage;
        }
        $records = $this->db->query($sql2)->result();
        //echo $this->db->last_query();exit;
        $data = array();
        foreach($records as $recordKey => $record ){
   
           $data[] = array(
            "sr_no" => $recordKey+1,
            "id"=>$record->id,
           // "project_name"=>$record->project_name,
            "customer_name"=>$record->customer_name,
            "order_code"=>$record->order_code,
            //"date" => date('d-F-Y', strtotime($record->date)),
            //"time"=>date('h:i a', strtotime($record->created)),
            //"area_name"=>$record->area_name,
            //"invoice_address"=>$record->invoice_address,
            "delivery_address"=>$record->delivery_address,
            //"shipping_charge"=>$record->shipping_charge,
            //"amount_before_tax"=>$record->amount_before_tax,
            "amount_after_tax"=>$record->amount_after_tax,
			"message"=>$record->address_remark,
			"employee_name"=>$record->employee_name,
            //"status"=>$record->status,
            "modified"=>date('d-m-Y', strtotime($record->modified)),
            //"is_active"=>$record->is_active,
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

   function admin_order_details($orderId){
	   //echo $orderId;exit;
	$sql = 'select od.unit_price, od.qty, od.uom, od.variation, p.product, o.amount_before_tax, o.shipping_charge, o.amount_after_tax, p.base_uom, concat(a.unit, " ",a.uom) as attribute from order_details od left join orders o on o.id=od.order_id left join products p on p.id=od.product_id left join product_attributes pa on pa.id=od.product_attribute_id left join attributes a on a.id=pa.attribute_id where od.order_id='.$orderId;
	$data['id'] = $orderId;
	
	$data['orderDetails'] = $this->pktdblib->custom_query($sql);
	$data['attribute'] = Modules::run('products/ordered_product_attribute_list');
	$variation = json_decode($data['orderDetails'][0]['variation']);
	$this->load->view('orders/admin_order_detail_list', $data);

}
    function admin_orders_details($orderId){
    	//$sql = 'select od.unit_price, od.qty, od.uom, od.variation, p.product, o.amount_before_tax, o.shipping_charge, o.amount_after_tax, a.unit, a.uom, pa.price, concat(a.unit, " ",a.uom) as attribute from order_details od left join orders o on o.id=od.order_id left join products p on p.id=od.product_id left join product_attributes pa on pa.id=od.product_attribute_id left join attributes a on a.id=pa.attribute_id where od.order_id='.$orderId;
    	$sql = 'select od.unit_price, od.qty, od.variation, p.product, o.shipping_charge, o.amount_after_tax, a.unit, a.uom, pa.price, concat(a.unit, " ",a.uom) as attribute from order_details od left join orders o on o.id=od.order_id left join products p on p.id=od.product_id left join product_attributes pa on pa.id=od.product_attribute_id left join attributes a on a.id=pa.attribute_id where od.order_id='.$orderId;
    	
    	$data['id'] = $orderId;
    	
    	$orderDetails = $this->pktdblib->custom_query($sql);
    	$ord_data=[];
    	foreach($orderDetails as $orderDetailsKey=>$orderDetailsValue){
    		//echo "<pre>";print_r($orderDetailsKey);echo "</pre>";
    		$ord_data['unit_price']=$orderDetails[$orderDetailsKey]['unit_price'];
    		$variations=json_decode($orderDetails[$orderDetailsKey]['variation'],true);
    		$price=$this->pktdblib->custom_query('select price, attribute_id from product_attributes where id='.$variations['attribute']['product_attribute_id']);
    		$ord_data['price']=$price[0]['price'];
    		$uom_unit=$this->pktdblib->custom_query('select concat(unit, " ",uom) as attribute from attributes where id='.$price[0]['attribute_id']);
    		$ord_data['attribute']=$uom_unit[0]['attribute'];
    		$ord_data['product']=$orderDetails[$orderDetailsKey]['product'];
    		$ord_data['amount_after_tax']=$orderDetails[$orderDetailsKey]['amount_after_tax'];
    		$ord_data['qty']=$orderDetails[$orderDetailsKey]['qty'];
    		$ord[]=$ord_data;
    		//echo "<pre>";print_r($price[0]['price']);print_r($uom_unit);echo "</pre>";}
    		unset($ord_data);
    	}
    
    	//echo "<pre>";print_r($ord);echo "</pre>";exit;
    	
    	//echo "<pre>";print_r($orderDetails);echo "</pre>";exit;
    	return $ord;
    }
    function admin_orders_payment_details($field,$value){
        echo $field; echo $value;//exit;
        $payment_order=$this->pktdblib->custom_query('select * from order_details where'.$field.'='.$value);
        echo "<pre>";print_r($payment_order);echo "</pre>";exit;
    }

}