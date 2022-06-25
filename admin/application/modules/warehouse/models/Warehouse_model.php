<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_model extends CI_Model {
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

    function warehouseList($postData=null){
        //echo "<pre>";print_r($postData);exit;
        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        //echo "<pre>"; print_r($postData);exit;
   
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " AND (t.warehouse like '%".$searchValue."%' or t.balance_qty like '%".$searchValue."%' or t.dump_qty like'%".$searchValue."%')";
        }
        ## Total number of records without filtering
        $balanceQtySql = '(SELECT sum(sd.balance_qty) as balance_qty FROM `stock_details` sd INNER JOIN stocks s on s.id=sd.stock_id INNER JOIN company_warehouse cw on cw.id=s.company_warehouse_id WHERE cw.id=cw2.id and s.is_active=true and sd.is_active=true and sd.balance_qty>0.5)';

        $dumpQtySql = '(SELECT sum(sd.balance_qty) as balance_qty FROM `stock_details` sd INNER JOIN stocks s on s.id=sd.stock_id INNER JOIN company_warehouse cw on cw.id=s.company_warehouse_id WHERE cw.id=cw2.id and s.is_active=true and sd.is_active=true and sd.balance_qty>0 AND sd.balance_qty<=0.5)';
        $this->db->select(['cw2.*', $balanceQtySql.' as balance_qty', $dumpQtySql.' as dump_qty']);
        

        $sql = $this->db->get_compiled_select('company_warehouse cw2');
        $sql2 = 'Select count(*) as allcount from ('.$sql.') t';
        $records = $this->db->query($sql2)->result();
        $totalRecords = $records[0]->allcount;
        //echo $this->db->last_query();exit();
        
        $sql2 = 'Select count(*) as allcount from ('.$sql.') t where 1=1'.$searchQuery;
        $records = $this->db->query($sql2)->result();
        $totalRecordwithFilter = $records[0]->allcount;
   
        
        $sql2 = 'Select * from ('.$sql.') t where 1=1'.$searchQuery.' order by '.$columnName.' '.$columnSortOrder;
        if ($rowperpage!='-1') {
            $sql2.=' LIMIT '.$start.', '.$rowperpage;
        }
        $records = $this->db->query($sql2)->result();
        $data = array();
        foreach($records as $recordKey => $record ){
   
           $data[] = array(
            "sr_no" => $recordKey+1,
            "id"=>$record->id,
            "warehouse"=>$record->warehouse,
            "balance_qty"=>(NULL===$record->balance_qty)?'0.000':number_format($record->balance_qty, 3),
            "dump_qty" => (NULL===$record->dump_qty)?'0.000':number_format($record->dump_qty, 3),
            "is_active"=>$record->is_active,
            'action'=>'Action'
           ); 
        }
        //echo "<pre>"; print_r($data);exit;
        ## Response
        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecordwithFilter,
           "iTotalDisplayRecords" => $totalRecords,
           "aaData" => $data
        );

        return $response;
    }
}
?>