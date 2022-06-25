<?php 
class Sliders_model extends CI_Model {
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

    function tbl_slider(){
    	$check = $this->check_table('sliders');
    	//print_r($check);exit;
    	if(!$check){
    		
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `sliders` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(255) NOT NULL,
			  `js` varchar(255) NOT NULL,
			  `slider_code` varchar(255) NOT NULL,
			  `css` varchar(255) NOT NULL,
			  `is_active` tinyint(1) NOT NULL DEFAULT '1',
			  `created` datetime NOT NULL,
			  `modified` datetime NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

    		if($query){
				$query2 = $this->db->query("CREATE TABLE IF NOT EXISTS `slider_details` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `slider_id` int(11) NOT NULL,
				  `type` varchar(255) NULL,
			  	  `title_1` varchar(255) NOT NULL,
			  	  `title_2` varchar(255) NOT NULL,
				  `image` varchar(255) NOT NULL,
				  `priority` int(11) NOT NULL,
				  /*`link` varchar(255) NOT NULL,*/
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL,
			  	  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1"
				);
			}
    		return $query;
    	}
    	else
    		return TRUE;
    	
    }

    function get_slider($params){

		$this->db->select(['sliders.*', '(Select count(id) from slider_details where slider_details.slider_id=sliders.id and slider_details.is_active=true) as slide_count']);
		
		if(isset($params['condition']))
			$this->db->where($params['condition']);

		if(isset($params['order']))
				$this->db->order_by($params['order']);

		$slider = $this->db->get('sliders');
		return $slider->result_array();
		
	}

	function get_slider_detail($params){
		$this->db->reset_query();
		//echo "reached here";
		$this->db->select(['slider_details.*', 'sliders.*']);
		$this->db->join('slider_details', 'slider_details.slider_id=sliders.id', 'left');
		
		if(isset($params['condition']))
			$this->db->where($params['condition']);

		if(isset($params['order']))
				$this->db->order_by($params['order']);

		$slider = $this->db->get('sliders');
		//echo $this->db->last_query();exit;
		//print_r($slider->result_array());exit;
		return $slider->result_array();
	}
}
