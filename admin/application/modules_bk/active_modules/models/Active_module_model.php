<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Active_module_model extends CI_Model {
	function __construct() {
		parent:: __construct();
		$this->database = $this->load->database('login', TRUE);
		//$this->load->model('address_model');
	}

	function set_table($table) {
		$this->table = $table;
	}

	function get_table() {
		$table = $this->table;
		return $table;
    }
}
?>