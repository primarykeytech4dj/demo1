<?php
class Areas_model extends CI_Model {
	function construct() {
		parent::__construct();
	}

    function check_table($table=''){
        if(empty($table))
            return FALSE;

        $query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
        $res = $query->row_array();

        return $res;
    }

    function tbl_areas(){
        $check = $this->check_table('areas');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

        if($query){
            $insert = $this->db->query("INSERT INTO `areas` (`id`, `city_id`, `area_name`, `is_active`, `created`, `modified`) VALUES
(1, 1, 'Kandivali East', 1, '2017-03-03 00:00:00', '".date('Y-m-d H:i:s')."'),
(2, 1, 'Kandivali(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(3, 1, 'Dahanu(East)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(4, 1, 'Dahanu(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(5, 1, 'Palghar(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(6, 1, 'Palghar(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(7, 1, 'Virar(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(8, 1, 'Virar(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(9, 1, 'Nalasopara(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(10, 1, 'Nalasopara(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(11, 1, 'Vasai(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(12, 1, 'Vasai(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(13, 1, 'Naigaon(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(14, 1, 'Naigaon(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(15, 1, 'Bhayandar(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(16, 1, 'Bhayander(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(17, 1, 'Mira Road(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(18, 1, 'Mira Road(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(19, 1, 'Dahisar(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(20, 1, 'Dahisar(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(21, 1, 'Borivali(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(22, 1, 'Borivali(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(23, 1, 'Malad(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(24, 1, 'Malad(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(25, 1, 'Goregaon(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(26, 1, 'Goregaon(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(27, 1, 'Jogeshwari(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(28, 1, 'Jogeshwari(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(29, 1, 'Andheri(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(30, 1, 'Andheri(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(31, 1, 'Vile Parle(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(32, 1, 'Vile Parle(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(33, 1, 'Santacruz(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(34, 1, 'Santacruz(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(35, 1, 'Khar Road(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(36, 1, 'Khar Road(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(37, 1, 'Bandra(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(38, 1, 'Bandra(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(39, 1, 'Mahim(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(40, 1, 'Mahim(W)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(41, 1, 'Matunga(E)', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(42, 1, 'Cotton Green West', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(43, 1, 'Kalyan East', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(44, 16, 'Thane West', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
        }
            return $query;
        }
        else
            return TRUE;
        
    }

    function get_dropdown_list($id = NULL) {
        //debug("reached here");exit;
        $condition['is_active'] = TRUE;
        $this->db->select('id, area_name');
        $query = $this->db->get_where('areas', $condition);
        return $query->result_array();
    }

    function get_city_wise_areas($condition = []) {
        $condition['is_active'] = TRUE;
        $this->db->select('id,area_name');
        $query = $this->db->get_where('areas', $condition);
        return $query->result_array();
    }

    function get_list($id = NULL) {
        /*$query = $this->db->get_where('cities',array('is_active' =>1));
        return $query->result();*/
        $this->db->select('areas.*, states.state_name, countries.name as country_name, cities.city_name');
        $this->db->join('cities', 'cities.id=areas.city_id');
        $this->db->join('states', 'states.id=cities.state_id');
        $this->db->join('countries', 'countries.id=states.country_id');
        if (empty($id)) {
            $query = $this->db->get('areas');
            return $query->result_array();
        }

        $query = $this->db->get_where('areas', array('areas.id' => $id));
        return $query->row_array();
    }

}
?>