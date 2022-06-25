<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class upload_documents_model extends CI_Model {
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

    function tbl_user_documents(){
    	$check = $this->check_table('user_documents');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `user_documents` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL,
			  `user_type` varchar(255) NOT NULL,
			  `document_id` int(11) NOT NULL,
			  `file` varchar(255) NOT NULL,
			  `is_active` tinyint(4) NOT NULL DEFAULT '1',
			  `created` datetime NOT NULL,
			  `modified` datetime NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");

    		return $query;
    	}
    	else
    		return FALSE;
    }

    function tbl_documents(){
    	$check = $this->check_table('documents');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE `documents` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `doc_type` varchar(255) NOT NULL,
			  `name` varchar(200) NOT NULL,
			  `is_active` int(11) NOT NULL,
			  `created` datetime NOT NULL,
			  `modified` datetime NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

			if($query){
				$insert = $this->db->query("INSERT INTO `documents` (`doc_type`, `name`, `is_active`, `created`, `modified`) VALUES
				('ID AND Address Proof', 'Adhaar Card', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
				('ID Proof', 'Pan Card', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
				('Address Proof', 'Ration Card', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
				('ID And Address Proof', 'Driving Licence', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
				('Address Proof', 'Electricity Bill', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
				('Snaps', 'Snaps', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
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
		//echo $db->last_query();
		return $query;
    }

	function _insert($data) {
		$db = $this->database;
		$table = $this->get_table();
		$db->insert($table, $data);
		$insert_id = $db->insert_id();
   		//return  $insert_id;
   		return  $insert_id;
    }

    function _insert_multiple($data) {
		$db = $this->database;
		$table = $this->get_table();
		$db->insert_batch($table, $data);
		$insert_id = $db->insert_id();
   		//return  $insert_id;
   		return  $insert_id;
    }

    function _update_multiple($data, $field) {
		$db = $this->database;
		$table = $this->get_table();
		$query = $db->update_batch($table, $data, $field);
		//$insert_id = $db->insert_id();
   		//return  $insert_id;
   		return  $query;
    }

    function update_document($id, $data) {
        $this->db->where('id',$id);
        $updt = $this->db->update('bank_accounts',$data);
        return $updt;
    }

    function userBasedDocument($userId, $userType){
    	$db = $this->database;
		//$table = $this->get_table();
		$db->where('user_id', $userId);
		$db->where('type', $userType);
		$query=$db->get('user_documents');
		return $query->result_array();
    }

    function userBasedDefaultDocument($userId, $userType){
    	$db = $this->database;
		//$table = $this->get_table();
		//print_r($table);
		$db->where('user_id', $userId);
		$db->where('user_type', $userType);
		//$db->where('is_default', true);
		$db->order_by('is_active desc');
		$query=$db->get('user_documents');
		//print_r($db->last_query());
		return $query->result_array();
    }

    function get_document_list($conditions){
    	$db = $this->database;
		//$table = $this->get_table();
		//print_r($table);
		$db->select('user_documents.*, documents.name as document');
		$db->join('documents', 'documents.id=user_documents.document_id');
		foreach ($conditions as $key => $condition) {
			$db->where($key, $condition);
		}

		$db->order_by('is_active desc');
		//$db->where('address.type', $userType);
		$query=$db->get('user_documents');
		//print_r($db->last_query());
		return $query->result_array();
    }

    function get_custom_document_type_users($accountBelongsTo){
    	$db = $this->database;
    	//$table = $this->get_table();
    	$sql = "Select ".$accountBelongsTo.".id, Concat(".$accountBelongsTo.".first_name, ' ', ".$accountBelongsTo.".middle_name, ' ', ".$accountBelongsTo.".surname) as fullname, ".$accountBelongsTo.".profile_img, ".$accountBelongsTo.".emp_code  from ".$accountBelongsTo." WHERE ".$accountBelongsTo.".is_active=true order by fullname asc";
    	$query = $db->query($sql);
		//print_r($db->last_query());
		return $query->result_array();
    }

    function get_document_type_list(){
    	$query = $this->db->get_where('documents', ['is_active'=>true]);
    	return $query->result_array();
    }
}
?>