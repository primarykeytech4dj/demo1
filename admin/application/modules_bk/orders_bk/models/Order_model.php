<?php 
class Order_model extends CI_Model {
	private $database;
	private $table;
	function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
	}

	function orderList($postData=null){
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
           $searchQuery = " AND (t.order_code like '%".$searchValue."%' or t.customer_name like '%".$searchValue."%' or t.date like'%".$searchValue."%' or t.invoice_address like'%".$searchValue."%' or t.delivery_address like'%".$searchValue."%' or t.status like '%".$searchValue."%')";
        }
        ## Total number of records without filtering
        
        $this->db->select(['o.*','concat(l.first_name, " ",  l.surname) as sales_person', 'os.status', 'concat(c.first_name, " ", c.middle_name, " ", c.surname) as customer_name', 'ar.area_name', 'concat(a.address_1," ", a.address_2," ",cn.name," ",st.state_name," ",ct.city_name," ",ar.area_name, " ", "<br><b>Fssi no:</b> ", a.fssi, "<br><b>GST No:</b> ", a.gst_no, "<br>Remark: ", a.remark) as invoice_address', 'concat(a.address_1," ", a.address_2," ",cn.name," ",st.state_name," ",ct.city_name," ",ar.area_name, " ", "<br><b>Fssi no: </b>", a.fssi, "<br><b>GST No:</b> ", a.gst_no, "<br>Remark: ", a.remark) as delivery_address', 'o.created as time']);
        //$this->db->join('customers c', 'c.id=orders.customer_id', 'left');
        $this->db->join('order_status os', 'os.id=o.order_status_id', 'left');
        $this->db->join('customers c', 'c.id=o.customer_id', 'left');
        $this->db->join('address a', 'a.id=o.shipping_address_id', 'left');
        $this->db->join('countries cn', 'cn.id=a.country_id', 'left');
        $this->db->join('states st', 'st.id=a.state_id', 'left');
        $this->db->join('cities ct', 'ct.id=a.city_id', 'left');
        $this->db->join('areas ar', 'ar.id=a.area_id', 'left');
        $this->db->join('login l', 'l.id=o.sale_by', 'left');
        if(isset($postData['orders']['sale_by'])){
            $this->db->where('o.sale_by', $postData['orders']['sale_by']);
        }
        $sql = $this->db->get_compiled_select('orders o');
        $sql2 = 'Select count(*) as allcount from ('.$sql.') t';
        //echo $sql2;exit;
        $records = $this->db->query($sql2)->result();
       // echo '<pre>';print_r($records);exit;
        $totalRecords = $records[0]->allcount;
   

        $sql2 = 'Select count(*) as allcount from ('.$sql.') t where 1=1'.$searchQuery;
        $records = $this->db->query($sql2)->result();
        $totalRecordwithFilter = $records[0]->allcount;
   		
   		
        $sql2 = 'Select * from ('.$sql.') t where 1=1'.$searchQuery.' order by '.$columnName.' '.$columnSortOrder;
        if ($rowperpage!='-1') {
            $sql2.=' LIMIT '.$start.', '.$rowperpage;
        }
        $records = $this->db->query($sql2)->result();
        //echo $this->db->last_query();exit;
        $data = array();
        foreach($records as $recordKey => $record ){
   
           $data[] = array(
            "sr_no" => $recordKey+1,
            "id"=>$record->id,
            "project_name"=>$record->project_name,
            "customer_name"=>$record->customer_name,
            "order_code"=>$record->order_code,
            "date" => date('d-F-Y', strtotime($record->date)),
            "time"=>date('h:i a', strtotime($record->created)),
            "area_name"=>$record->area_name,
            "invoice_address"=>$record->invoice_address,
            "delivery_address"=>$record->delivery_address,
            "shipping_charge"=>$record->shipping_charge,
            "amount_before_tax"=>$record->amount_before_tax,
            "amount_after_tax"=>$record->amount_after_tax,
            "message"=>$record->message,
            "status"=>$record->status,
            "modified"=>date('d-m-Y', strtotime($record->modified)),
            "is_active"=>$record->is_active,
            "sale_by"=>$record->sales_person,
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