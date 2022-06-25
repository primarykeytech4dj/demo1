<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies_Model extends CI_Model {

	private $database;
	private $table;

	function __construct(){
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);

	}

	// Unique to models with multiple tables
	function set_table($table) {
		//echo "reached in set table";

		$this->table = $table;
		//echo $table;
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

	function get_where($id) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where('id', $id);
		$query=$db->get($table);
		return $query->row_array();
    }

    // Get where custom column is .... return query
    function get_where_custom($col, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($col, $value);
		$query=$db->get($table);
		return $query;
    }

    function _insert($data) {
    	$db = $this->database;
    	$table = $this->get_table();
    	$db->insert($table, $data);
    	$insert_id = $db->insert_id();
    	//print_r($db->last_query());exit;
    	return $insert_id; 
    }

    function _insert_multiple($data) {
    	//echo '<pre>';print_r($data);//exit;
    	$db = $this->database;
    	$table = $this->get_table();
    	echo "hello";
    	$num_rows = $db->insert_batch($table,$data);
    	//echo "inserted";
    	//print_r($db->last_query());exit;
    	return $num_rows;
    }

    function update($id, $data) {
    	//print_r($data);
    	$db = $this->database;
    	$table = $this->get_table();
    	$db->where('id', $id);
    	$update = $db->update('companies', $data);
    	//print_r($db->last_query());
    	return $update;
    }

    function _update_multiple($field, $data) {
		$db = $this->database;
		$table = $this->get_table();
		$updt = $db->update_batch($table, $data, $field);
		return $updt;
	}

}