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
    
    function invoiceList($postData=null){
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
        
        $this->db->select(['oi.*', 'cz.route_no', 'cz.zone_no', 'concat(l.first_name, " ", l.surname) as delivery_boy', 'sum(shipping_charge) as shipping_charge', 'bd.order_status_id',
        'GROUP_CONCAT(DISTINCT CONCAT(o.message) ORDER BY o.id SEPARATOR " | ") as message', 'sum(amount_before_tax) as amount_before_tax', 'sum(amount_after_tax) as amount_after_tax', 
        'GROUP_CONCAT( o.order_code ) as order_codes', 'os.status', 'os.bg_color as status_bg_color','concat(c.first_name, " ", c.middle_name, " ", c.surname) as customer_name', 
        'ar.area_name', 'concat(a.address_1," ", a.address_2," ",cn.name," ",st.state_name," ",ct.city_name," ",ar.area_name, " ", "<br><b>Fssi no:</b> ", a.fssi, "<br><b>GST No:</b> ", a.gst_no, "<br>Remark: ", a.remark) as invoice_address', 
        'concat(a.address_1," ", a.address_2," ",cn.name," ",st.state_name," ",ct.city_name," ",ar.area_name, " ", "<br><b>Fssi no: </b>", a.fssi, "<br><b>GST No:</b> ", a.gst_no, "<br>Remark: ", a.remark) as delivery_address', 'o.created as time']);
        //$this->db->join('customers c', 'c.id=orders.customer_id', 'left');
        
        $this->db->join('orders o', 'o.order_code=oi.order_code');
        $this->db->join('customers c', 'c.id=o.customer_id', 'left');
        $this->db->join('address a', 'a.id=o.shipping_address_id', 'left');
        $this->db->join('countries cn', 'cn.id=a.country_id', 'left');
        $this->db->join('states st', 'st.id=a.state_id', 'left');
        $this->db->join('cities ct', 'ct.id=a.city_id', 'left');
        $this->db->join('areas ar', 'ar.id=a.area_id', 'left');
        $this->db->join('deliveryboy_order bd', 'bd.invoice_no=oi.invoice_no', 'left');
        $this->db->join('login l', 'l.id=bd.employee_id', 'left');
        $this->db->join('customer_zones cz', 'cz.customer_id=c.id', 'left');
        $this->db->join('order_status os', 'os.id=bd.order_status_id', 'left');
        if(isset($postData['orders']['delivery_by'])){
            $this->db->where('bd.employee_id', $postData['orders']['delivery_by']);
        }
        if(isset($postData['order_status_id']) && !empty(trim($postData['order_status_id']))){
            $this->db->where('bd.order_status_id', $postData['order_status_id']);
        }
        
        if(isset($postData['from_date']) && !empty(trim($postData['from_date'])) && isset($postData['to_date']) && !empty(trim($postData['to_date']))){
            $fromdate = str_replace('/', '-', $postData['from_date']);
            $todate = str_replace('/', '-', $postData['to_date']);
            if($fromdate==$todate){
            //echo date('Y-m-d', strtotime($postData['from_date']));
            $this->db->like('oi.created', date("Y-m-d", strtotime($fromdate)), 'after');
            }else{
                $this->db->where('oi.created >=', date('Y-m-d 00:00:00', strtotime($fromdate)));
                $this->db->where('oi.created <=', date('Y-m-d 23:59:00', strtotime($todate)));
            }
        }
        $this->db->group_by('oi.invoice_no');
        $sql = $this->db->get_compiled_select('invoice_orders oi');
        $sql2 = 'Select count(*) as allcount from ('.$sql.') t';
        //echo $sql2;exit;
        $records = $this->db->query($sql2)->result();
        //echo '<pre>';print_r($records);exit;
        $totalRecords = $records[0]->allcount;
   
        $sql2 = 'Select count(*) as allcount from ('.$sql.') t where 1=1'.$searchQuery;
        $records = $this->db->query($sql2)->result();
        $totalRecordwithFilter = $records[0]->allcount;
   		
   		
        $sql2 = 'Select * from ('.$sql.') t where 1=1'.$searchQuery.' order by '.$columnName.' '.$columnSortOrder;
        if ($rowperpage!='-1') {
            $sql2.=' LIMIT '.$start.', '.$rowperpage;
        }
        //echo $sql2;exit;
        $records = $this->db->query($sql2)->result();
        //echo $this->db->last_query();exit;
        $data = array();
        foreach($records as $recordKey => $record ){
   
           $data[] = array(
            "sr_no" => $recordKey+1,
            "id"=>$record->invoice_no,
            "invoice_no"=>$record->invoice_no,
            "fiscal_yr"=>$record->fiscal_yr,
            "customer_name"=>$record->customer_name,
            "order_codes"=>$record->order_codes,
            "date" => date('d-F-Y', strtotime($record->created)),
            "time"=>date('h:i a', strtotime($record->created)),
            "route_no"=>$record->route_no,
            "zone_no"=>$record->zone_no,
            "area_name"=>$record->area_name,
            "order_status_id"=>$record->order_status_id,
            "status_bg_color"=>$record->status_bg_color,
            "invoice_address"=>$record->invoice_address,
            "delivery_address"=>$record->delivery_address,
            "shipping_charge"=>$record->shipping_charge,
            "amount_before_tax"=>$record->amount_before_tax,
            "amount_after_tax"=>$record->amount_after_tax,
            "message"=>$record->message,
            "status"=>$record->status,
            "modified"=>date('d-m-Y', strtotime($record->modified)),
            "is_active"=>$record->is_active,
            "delivery_boy"=>$record->delivery_boy,
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
    
    function exportInvoiceList($postData=null){
        //echo "<pre>";print_r($postData);exit;
        $response = array();
        ## Read value
        $this->db->select(['oi.*', 'concat(l.first_name, " ", l.surname) as delivery_boy', 'sum(shipping_charge) as shipping_charge', 'bd.order_status_id',
        'GROUP_CONCAT(DISTINCT CONCAT(o.message) ORDER BY o.id SEPARATOR " | ") as message', 'sum(amount_before_tax) as amount_before_tax', 'sum(amount_after_tax) as amount_after_tax', 
        'GROUP_CONCAT( o.order_code ) as order_codes', 'GROUP_CONCAT( o.id ) as order_ids', 'os.status', 'os.bg_color as status_bg_color','concat(c.first_name, " ", c.middle_name, " ", c.surname) as customer_name', 'c.company_name', 
        'ar.area_name', 'concat(a.address_1,", ", a.address_2,",",ar.area_name,", ",ct.city_name, " - ", a.pincode,", Tel: ",c.contact_1) as invoice_address', 
        'CONCAT(a.address_1,", ", a.address_2,",*",ar.area_name,"*, ",ct.city_name, " - ", a.pincode,", Tel: ",c.contact_1) as delivery_address', 'o.created as time', 'st.state_name', 'a.gst_no', 'cn.name as country_name', 'o.customer_id', 'a.pincode']);
        //$this->db->join('customers c', 'c.id=orders.customer_id', 'left');
        
        $this->db->join('orders o', 'o.order_code=oi.order_code');
        $this->db->join('customers c', 'c.id=o.customer_id', 'left');
        $this->db->join('address a', 'a.id=o.shipping_address_id', 'left');
        $this->db->join('countries cn', 'cn.id=a.country_id', 'left');
        $this->db->join('states st', 'st.id=a.state_id', 'left');
        $this->db->join('cities ct', 'ct.id=a.city_id', 'left');
        $this->db->join('areas ar', 'ar.id=a.area_id', 'left');
        $this->db->join('deliveryboy_order bd', 'bd.invoice_no=oi.invoice_no', 'left');
        $this->db->join('login l', 'l.id=bd.employee_id', 'left');
        $this->db->join('order_status os', 'os.id=bd.order_status_id', 'left');
        
        if(isset($postData['from_invoice']) && isset($postData['to_invoice'])){
            if($postData['from_invoice']==$postData['to_invoice']){
                $this->db->where('oi.invoice_no', $postData['from_invoice']);
            }else{
                $this->db->where('oi.invoice_no BETWEEN '.$postData['from_invoice'].' AND '.$postData['to_invoice']);
            }
        }
        $this->db->where_not_in('o.order_status_id', [1,2,8]);
        $this->db->group_by('oi.invoice_no');
        $sql = $this->db->get_compiled_select('invoice_orders oi');
        
        $sql2 = 'Select * from ('.$sql.') t where 1=1 order by invoice_no ASC';
        //echo $sql2;exit;
        $records = $this->db->query($sql2)->result();
        //echo $this->db->last_query();exit;
        /*echo '<pre>';
        print_r($records);exit;*/
        ## Response
        $response = $records;
        return $response;


    }
}
?>