<?php 
class Menus_model extends CI_Model {
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

    function tbl_temp_menu(){
    	$check = $this->check_table('temp_menu');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `temp_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `target` enum('_self','_blank','_new','') NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(100) NOT NULL,
  `is_custom_constant` tinyint(1) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL,
  `module` varchar(255)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;"
			);

    	if($query){
			$insert = $this->db->query("INSERT INTO `temp_menu` (`id`, `menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(1, 2, 0, '_self', 'Company', '#', 'fa-bank', 0, 0, 'companies', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),(2, 2, 1, '_self', 'View Companies', 'companies/adminindex', 'fa-th', 0, 1, 'companies', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),(3, 2, 1, '_self', 'New Company', 'companies/newcompany', 'fa-plus-square', 0, 2, 'companies', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),(4, 2, 0, '_self', 'Navigation Menus Settings', '#', 'fa-book', 0, 100, 'menus', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),(5, 2, 4, '_self', 'Menu List', 'menus/adminindex', 'fa-book', 0, 1, 'menus', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),(6, 2, 4, '_self', 'New Menu', 'menus/newmenu', 'fa-plus-square', 0, 2, 'menus', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),(7, 2, 4, '_self', 'Roles List', 'roles/adminindex', 'fa-book', 0, 1, 'menus', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),(8, 2, 4, '_self', 'New Role', 'roles/newrole', 'fa-plus-square', 0, 2, 'menus', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."');
");
		}
    		return $query;
    	}
    	else
    		return FALSE;
    }

    function tbl_menus(){
    	$check = $this->check_table('menus');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
"
			);

    	if($query){
			$insert = $this->db->query("INSERT INTO `menus` (`id`, `name`, `is_active`, `created`, `modified`) VALUES(1, 'Frontend Main Menu', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(2, 'Backend Left Menu', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(3, 'Frontend-footer 1', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(4, 'Frontend-footer 2', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(5, 'Frontend-footer 3', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(6, 'Frontend-footer 4', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')"
			);
		}
    		return $query;
    	}
    	else
    		return TRUE;
    	
    }


    function tbl_menu_roles(){
      $check = $this->check_table('menu_roles');
      //print_r($check);exit;
      if(!$check){
        //echo "table does not exists<br>";
        $query = $this->db->query("CREATE TABLE `menu_roles` (
          `menu_detail_id` int(11) NOT NULL COMMENT 'temp_menu_id',
          `role_id` int(11) NOT NULL,
          `is_active` tinyint(1) NOT NULL DEFAULT '1',
          `created` datetime NOT NULL,
          `modified` datetime NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

      if($query){
        $menus = 
      $insert = $this->db->query("INSERT INTO `menu_roles` (`menu_detail_id`, `role_id`, `is_active`, `created`, `modified`) VALUES
(1, 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(2, 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(3, 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(4, 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(5, 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(6, 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(7, 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(8, 1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
    }
        return $query;
      }
      else
        return TRUE;
      
    }

	function get_menu_category_list($id=null) {
		$db = $this->database;
		$condition['is_active'] = true;
		
		$db->select('id,name')->where('`id` NOT IN (SELECT id from `temp_menu`)');
		$query = $db->get_where('temp_menu', $condition);
		//print_r($db->last_query());
		return $query->result_array();
	}

	function get_category_list($condition = []){
    $db = $this->database;
		
		$db->select(['temp_menu.*', '(select name from temp_menu nc where nc.id=temp_menu.parent_id) as parent', 'menus.name as menu', '(select GROUP_CONCAT(roles.role_name SEPARATOR ", ") from menu_roles inner join roles on roles.id=menu_roles.role_id where temp_menu.id=menu_roles.menu_detail_id) as roles']);
		$db->join('menus', 'menus.id=temp_menu.menu_id', 'left');
		$db->order_by('temp_menu.priority asc');
		$query = $db->get_where('temp_menu', $condition);
		//print_r($db->last_query());
		return $query->result_array();
    }

}
