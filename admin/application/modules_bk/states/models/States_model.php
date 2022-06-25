<?php
class States_model extends CI_Model {
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

    function tbl_states(){
        $check = $this->check_table('states');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `state_name` varchar(255) NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

        if($query){
            $insert = $this->db->query("INSERT INTO `states` (`id`, `country_id`, `state_name`, `slug`, `created`, `modified`, `is_active`) VALUES
(1, 1, 'Maharashtra', 'महाराष्ट्र', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(2, 1, 'Delhi', 'दिल्ली', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(3, 1, 'Karnataka', 'कर्नाटक', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(4, 1, 'Gujarat', 'गुजरात', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(5, 1, 'Telangana', 'तेलंगाना', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(6, 1, 'Tamil Nadu', 'तमिल-नाडु', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(7, 1, 'West Bengal', 'वेस्ट-बंगाल', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(8, 1, 'Rajasthan', 'राजस्थान', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(9, 1, 'Uttar Pradesh', 'उत्तर-प्रदेश', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(10, 1, 'Bihar', 'बिहार', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(11, 1, 'Madhya Pradesh', 'मध्य-प्रदेश', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(12, 1, 'Andhra Pradesh', 'आंध्र-प्रदेश', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(13, 1, 'Punjab', 'पंजाब', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(14, 1, 'Haryana', 'हरयाणा', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(15, 1, 'Jammu and Kashmir', 'जम्मू-एंड-कश्मीर', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(16, 1, 'Jharkhand', 'झारखण्ड', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(17, 1, 'Chhattisgarh', 'छत्तीसगढ़', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(18, 1, 'Assam', 'असम', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(19, 1, 'Chandigarh', 'चंडीगढ़', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(20, 1, 'Odisha', 'ओडिशा', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(21, 1, 'Kerala', 'केरला', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(22, 1, 'Uttarakhand', 'उत्तराखंड', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(23, 1, 'Puducherry', 'पुडुचेर्री', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(24, 1, 'Tripura', 'त्रिपुरा', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(26, 1, 'Mizoram', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(27, 1, 'Meghalaya', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(28, 1, 'Manipur', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(29, 1, 'Himachal Pradesh', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(30, 1, 'Nagaland', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(31, 1, 'Goa', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(32, 1, 'Andaman and Nicobar Islands', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(33, 1, 'Arunachal Pradesh', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1),
(34, 1, 'Dadra and Nagar Haveli', '', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1)");
        }
            return $query;
        }
        else
            return TRUE;
        
    }

    function get_country_wise_state($condition = []) {
        $condition['is_active'] = TRUE;
        $this->db->select('id,state_name');
        $query = $this->db->get_where('states', $condition);
        return $query->result_array();
    }

    function get_dropdown_list($id = NULL) {
        //debug("reached here");exit;
        $condition['is_active'] = TRUE;
        $this->db->select('id, state_name');
        $query = $this->db->get_where('states', $condition);
        return $query->result_array();
    }

    function get_list($id = NULL) {
        /*$query = $this->db->get_where('cities',array('is_active' =>1));
        return $query->result();*/
        $this->db->select('states.*, countries.name as country_name');
        $this->db->join('countries', 'countries.id=states.country_id');
        if (empty($id)) {
            $query = $this->db->get('states');
            return $query->result_array();
        }

        $query = $this->db->get_where('states', array('states.id' => $id));
        return $query->row_array();
    }
}
?>