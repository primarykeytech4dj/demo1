<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider_model extends CI_Model
{

	private $database;
	private $table;

    function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
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
    
    function update_slider($id,$data) {
        $this->db->where('id',$id);
        $updt = $this->db->update('slider',$data);
        return $updt;
    }
}
