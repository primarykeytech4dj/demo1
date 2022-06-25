<?php 
if(!defined('BASEPATH'))exit('No direct script access allowed');

class Page_model extends CI_Model{
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

    function tbl_pages(){
    	$check = $this->check_table('pages');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `pages` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`title` varchar(255) NOT NULL,
				`content` longtext NOT NULL,
				`slug` varchar(255) NOT NULL,
				`featured_image` varchar(255) NOT NULL,
				`is_active` int(1) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				PRIMARY KEY (id),
				UNIQUE KEY(slug)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);
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

    function get_activePages($order_by) {
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

	function _insert_multiple($data) {
        $db = $this->database;
        $table = $this->get_table();
        $num_rows = $db->insert_batch($table,$data);
        return $num_rows;
    }

    function _update_multiple($field, $data) {
        $db = $this->database;
        $table = $this->get_table();
        $updt = $db->update_batch($table, $data, $field);
        return $updt;
    }

} 
?>