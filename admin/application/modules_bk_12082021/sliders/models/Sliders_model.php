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
			  	  `link` varchar(255) NOT NULL DEFAULT '#',
				  `image` varchar(255) NOT NULL,
				  `priority` int(11) NOT NULL,
				  /*`link` varchar(255) NOT NULL,*/
				  `is_active` tinyint(1) NOT NULL DEFAULT '1',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL,
			  	  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1"
				);

				$adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'Sliders', '#', 'fa-list', 0, 10, 'sliders', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
            $menuId = $this->db->insert_id();
            if($menuId){
              $arr = [
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'Slider Listing', 'slug'=>'sliders/adminindex', 'class'=>'fa-list', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'sliders', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'New Slider', 'slug'=>'sliders/newslider', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'sliders', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],

              ];

              $ins = $this->db->insert_batch('temp_menu', $arr);

              $menuRolesArray[0] = ['menu_detail_id'=>$menuId, 'role_id'=>1, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];

              $menus = $this->db->query('Select id from temp_menu where parent_id="'.$menuId.'"'); 
              foreach ($menus->result_array() as $key => $menu) {
                $menuRolesArray[count($menuRolesArray)] = ['menu_detail_id'=>$menu['id'], 'role_id'=>1, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];
              }

              $ins = $this->db->insert_batch('menu_roles', $menuRolesArray);

            }
			}
    		return $query;
    	}
    	else
    		return TRUE;
    	
    }

	function get_slider($orderBy){

		$this->db->select(['sliders.*', '(Select count(id) from slider_details where slider_details.slider_id=sliders.id and slider_details.is_active=true) as slide_count']);
		//$this->db->join('slider_details', 'sliders.id=slider_details.slider_id', 'left');
		/*if($sliderCode!='')
			$this->db->where('sliders.slider_code',$sliderCode);*/

		$this->db->order_by($orderBy);

		$slider = $this->db->get_where('sliders', ['sliders.is_active'=>true]);
		return $slider->result_array();
		
	}
}
