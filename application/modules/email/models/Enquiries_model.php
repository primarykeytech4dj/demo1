<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquiries_model extends CI_Model
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
        //print_r($table);//exit;
        $db->insert($table, $data);
        $insert_id = $db->insert_id();
        //print_r($insert_id);exit;
        //return  $insert_id;
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

    // Get where column id is ... return query
    function get_where($id) {
        //echo "hello";
        /*print
        exit;*/
        $db = $this->database;
        $table = $this->get_table();
        $db->where('id', $id);
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

}