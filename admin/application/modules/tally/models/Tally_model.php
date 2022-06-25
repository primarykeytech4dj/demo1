<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tally_model extends CI_Model {
	function __construct() {
		parent:: __construct();
		$this->database = $this->load->database('login', TRUE);
		//$this->load->model('address_model');
	}

	function check_table($table=''){
    	if(empty($table))
    		return FALSE;

    	$query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
    	$res = $query->row_array();

    	return $res;
    }

    function tbl_tally_ledger(){
    	$check = $this->check_table('tally_ledger');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE `tally_ledger` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `ledger_name` varchar(255) NOT NULL,
            `account_type` varchar(255) NOT NULL,
            `user_id` int(11) NOT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT '0',
            `created` datetime NOT NULL,
            `modified` datetime NOT NULL,
            PRIMARY KEY(`id`),
            UNIQUE KEY(`ledger_name`)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
        );

	    	if($query){
          $adminMenus = $this->db->query("INSERT INTO `temp_menu` (`menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_custom_constant`, `priority`, `module`, `is_active`, `created`, `modified`) VALUES(2, 0, '_self', 'Tally Setup', '#', 'fa-user', 0, 3, 'tally', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
            $menuId = $this->db->insert_id();
            if($menuId){
              $arr = [
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'Tally Ledgers', 'slug'=>'tally/adminindex', 'class'=>'fa-user', 'is_custom_constant'=>0, 'priority'=>1, 'module'=>'tally', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
                ['menu_id'=>2, 'parent_id'=>$menuId, 'target'=>'_self', 'name'=>'Ledger Mapping', 'slug'=>'tally/ledgerMapping', 'class'=>'fa-plus-square', 'is_custom_constant'=>0, 'priority'=>2, 'module'=>'tally', 'is_active'=>1, 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')],
              ];

              $ins = $this->db->insert_batch('temp_menu', $arr);
              $menuRolesArray[0] = ['menu_detail_id'=>$menuId, 'role_id'=>1, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];

              $menus = $this->db->query('Select id from temp_menu where parent_id="'.$menuId.'"'); 
              foreach ($menus->result_array() as $key => $menu) {
                $menuRolesArray[count($menuRolesArray)] = ['menu_detail_id'=>$menu['id'], 'role_id'=>1, 'created'=>date('Y-m-d H:i:d'), 'modified'=>date('Y-m-d H:i:s')];
              }

              $ins = $this->db->insert_batch('menu_roles', $menuRolesArray);
				}
    		return $query;
    	}
    	else
    		return FALSE;
    }
  }
}
?>