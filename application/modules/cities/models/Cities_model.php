<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cities_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function check_table($table=''){
    	if(empty($table))
    		return FALSE;

    	$query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
    	$res = $query->row_array();

    	return $res;
    }

    function tbl_cities(){
    	$check = $this->check_table('cities');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `cities`(
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				`city_name` varchar(255) NOT NULL,
				`short_name` varchar(25) NULL,
				`country_id` int(11) NOT NULL,
				`state_id` int(11) NOT NULL,
				`type` varchar(255) NOT NULL,
				`population` varchar(255) NOT NULL,
				`population_class` char(10) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

			if($query){
				
				$insert = $this->db->insert_batch('cities', [
					[
						'city_name'=>'Mumbai', 
						'short_name'=>'mumbai', 
						'country_id'=>'1', 
						'state_id'=>'1', 
						'type'=>'Municipal Corporation / Corporation', 
						'population'=>'1,35,97,924', 
						'population_class'=>'Class I', 
						'is_active'=>'1', 
						'created'=>date('Y-m-d H:i:s'), 
						'modified'=>date('Y-m-d H:i:s')
					],
					[
						'city_name'=>'Delhi', 
						'short_name'=>'delhi', 
						'country_id'=>'1', 
						'state_id'=>'2', 
						'type'=>'Municipal Corporation / Corporation', 
						'population'=>'1,10,07,835', 
						'population_class'=>'Class I', 
						'is_active'=>'1', 
						'created'=>date('Y-m-d H:i:s'), 
						'modified'=>date('Y-m-d H:i:s')
					],
				]
			);
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

	function get_list($id = NULL) {
		/*$query = $this->db->get_where('cities',array('is_active' =>1));
		return $query->result();*/
		$this->db->select('cities.*, states.state_name, countries.name as country_name');
		$this->db->join('states', 'states.id=cities.state_id');
		$this->db->join('countries', 'countries.id=states.country_id');
		if (empty($id)) {
            $query = $this->db->get('cities');
            return $query->result_array();
        }

        $query = $this->db->get_where('cities', array('id' => $id));
        return $query->row_array();
	}

	function get_active_list($id = NULL) {
		//debug("reached here");exit;
		$condition['is_active'] = TRUE;
		if (empty($id))
        {
			//print_r("reached here");exit;
            $query = $this->db->get_where('cities', $condition);
            return $query->result_array();
        }
        $condition['id'] = $id;
        $query = $this->db->get_where('cities', $condition);
        return $query->row_array();
	}

	function get_dropdown_list($id = NULL) {
		//debug("reached here");exit;
		$condition['is_active'] = TRUE;
		$this->db->select('id, city_name');
        $query = $this->db->get_where('cities', $condition);
        return $query->result_array();
	}

	function get_state_wise_cities($condition = []) {
        $condition['is_active'] = TRUE;
        $this->db->select('id,city_name');
        $query = $this->db->get_where('cities', $condition);
        return $query->result_array();
    }
}
?>