<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setups extends MY_Controller {

	// Configuration properties used in blacklisting
	
	function __construct() {
		parent:: __construct();
	
		foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
				check_user_login(TRUE);
			}
		}
		
		$this->load->model('setups/setups_model');
		
	}

    function setups_listing()
    {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        
			// print_r($this->input->post());exit;
	        $this->form_validation->set_rules('module_name', 'Module Name', 'required');
			$this->form_validation->set_rules('parameter', 'Parameter', 'required');
			$this->form_validation->set_rules('value', 'Value', 'required');
			$this->form_validation->set_rules('datatype', 'Data Type', 'required');
			$this->form_validation->set_rules('priority', 'Priority', 'required');
			
			if($this->form_validation->run()!==FALSE)
			{
			    $postData = $this->input->post();
			    $this->pktdblib->set_table('setup');
		
					
					if($postData['datatype'] == "boolean")
					{
						if($postData['value'] == 1)
						{
							$postData['value'] = "true";
						}
						else{
							$postData['value'] = "false";
						}
					}
					
					// print_r($postData);exit;
					if(isset($postData['id']) && NULL!==$postData['id'] && $postData['id']!=''){
						// $postData['modified_by'] = $this->session->userdata('user_id');
						$postData['modified'] = date('Y-m-d H:i:s');
						try{
							$upd = $this->pktdblib->_update($postData['id'], $postData);
							if($upd){
								echo json_encode(['status'=>'success', 'message'=>'Routes Updates']);
								exit;
							}
						}catch(Exception $e){
							// echo json_encode(['status'=>'error', 'message'=>'Failed to update route '.$e->message()]);
							echo json_encode(['status'=>'error', 'message'=>'Failed to update route ']);
							exit;
						}
						//$upd = $this->pktdblib->_update($postData['id'], $postData);
						
					}else{
						// print_r($postData);exit;
						// $postData['created_by'] = $this->session->userdata('user_id');
						$postData['created'] = date('Y-m-d H:i:s');
						try{
					
							$id = $this->pktdblib->_insert($postData);
							// print_r($id);
							if($id){
								echo json_encode(['status'=>'success', 'message'=>'Routes created']);
								exit;
							}else{
								echo json_encode(['status'=>'error', 'message'=>'Failed to create route']);
								exit;
							}
						}catch(Exception $e){
							// echo json_encode(['status'=>'error', 'message'=>'Failed to update route '.$e->message()]);
							echo json_encode(['status'=>'error', 'message'=>'Failed to update route ']);
							exit;
						}
					 
					}

			}else{
				// print_r($this->input->post());exit;
			    echo json_encode(['status'=>'error', 'message'=>'Following Validation Error: '.validation_errors()]);
			    exit;
			}
			
	    }
		
        

		$data['meta_title'] = "ERP";
        
        $data['title'] = "ERP : Setups";
        $data['meta_description'] = "setup panel";
        
        $data['modules'][] = "setups";
        $data['methods'][] = "setup_list";
        
        echo Modules::run("templates/admin_template", $data);
    }



	function setup_list($data=[]){
		
		$sql = 'SELECT * FROM `setup` WHERE 1=1';
		
		if(isset($_GET['name']) && $_GET['name'] !== "")
		{
			
			$sql .= ' AND module_name = "'.$_GET['name'].'"';
		}
		else{
			$sql .= ' AND module_name = "application"';
		}
		$sql .= ' ORDER BY priority ASC';
		// print_r($sql);exit;
	
		$data['setups'] = $this->pktdblib->custom_query($sql);
		$data['option'] = $this->setups_model->setupFilter();
        $this->load->view("setups/setup_list", $data);
    }

	public function area_priority(){
    
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $postData = $this->input->post();
            $response = array();
            ## Read value
            $draw = $postData['draw'];
            $start = $postData['start'];
            $rowperpage = $postData['length']; // Rows display per page
            // $columnIndex = $postData['order'][0]['column']; // Column index
            // $columnName = $postData['columns'][$columnIndex]['data']; // Column name
            // $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
            $searchValue = $postData['search']['value']; // Search value
            // echo "<pre>"; print_r($postData);exit;
       
            ## Search 
            $searchQuery = "";
            if($searchValue != ''){
               $searchQuery = " AND (t.city_name like '%".$searchValue."%' or t.area_name like '%".$searchValue."%' or t.state_name like '%".$searchValue."%')";
            }
            $this->db->select(['a.area_name', 'a.id', 'a.priority', 'c.city_name', 's.state_name', 'a.is_active']);
            $this->db->join('cities c', 'c.id=a.city_id', 'left');
            $this->db->join('states s', 's.id=c.state_id', 'left');

            $this->db->where('a.is_active', $postData['is_active']);
            if($postData['is_active'] == 1)
            {
                
                $this->db->order_by('a.priority', 'asc');
            }
            $sql = $this->db->get_compiled_select('areas a');

            $sql2 = 'Select count(*) as allcount from ('.$sql.') t';
            //echo $sql2;exit;
            $records = $this->db->query($sql2)->result();
            // echo '<pre>';print_r($records);exit;
            $totalRecords = $records[0]->allcount;
       
            $sql2 = 'Select count(*) as allcount from ('.$sql.') t where 1=1'.$searchQuery;
            //echo $sql2;exit;
            $records = $this->db->query($sql2)->result();
            $totalRecordwithFilter = $records[0]->allcount;
               
               
            $sql2 = 'Select * from ('.$sql.') t where 1=1'.$searchQuery.' order by priority ASC';
            if ($rowperpage!='-1') {
                $sql2.=' LIMIT '.$start.', '.$rowperpage;
            }
            //echo $sql2;exit;
            $records = $this->db->query($sql2)->result();
            //echo $this->db->last_query();exit;
            $data = array();
            foreach($records as $recordKey => $record){
				$action = '<input type="checkbox" class=" update-status" '.(($record->is_active)?"checked":"").'  id="'.$record->id.'" name="status">';

               $data[] = array(
                "sr_no" => $recordKey+1,
                "id"=>$record->id,
                "area_name"=>$record->area_name,
                "priority"=>$record->priority,
                "city_name"=>$record->city_name,
                "state_name"=>$record->state_name,
                "is_active"=>$action,
         
               ); 
            }
            // echo "<pre>"; print_r($totalRecords);exit;
            ## Response
            $response = array(
               "draw" => intval($draw),
               "iTotalRecords" => $totalRecordwithFilter,
               "iTotalDisplayRecords" => $totalRecords,
               "aaData" => $data
            );
    
            echo json_encode($response);
            exit;
    
        }
        // $data['areas'] = $query->result();
        // print_r($data['area']);exit;
        $zones =$this->pktdblib->custom_query('SELECT DISTINCT zone_no FROM routes');
        foreach($zones as $eKey=>$zone){
           $data['option']['zone'][$zone['zone_no']] = $zone['zone_no'];
        }
        $data['option']['is_active'] = array('1' => 'Active', '0' => 'In-Active');
        $data['meta_title'] = "ERP";
        $data['meta_description'] = "Area Priority";
        $data['meta_title'] = 'Area Priority';
        $data['meta_keyword'] = 'Area Priority';
        $data['content'] = 'setups/admin_area_priority';
        $data['js'][] = '<script type="text/javascript">
        $(document).on("change", ".zone",function(){
    
            var trId = $(this).closest("tr").attr("id");
            var zone = $(this).val();
            
            $.ajax({
                type: "POST",
                dataType: "json",
                // data: "product_id="+productId,
                url :  base_url+"orders/get_routes/"+zone,
                success: function(response) {
                    console.log(response);
    
            
                    $("#routes").select2("destroy").empty().select2({data : response}).trigger("change");
              }
            
            });
            
        })
        </script>';
        // echo Modules::run('templates/admin_template', $data);
        echo Modules::run("templates/admin_template", $data);

    }

    public function update_area_priority()
    {
        $postData = $this->input->post();
        // print_r($postData);exit;
        $i =1;
        foreach($postData['allData'] as $pdkey => $value)
        {
            $sql = 'UPDATE areas SET priority = '.($pdkey+1).' WHERE id = '.$value.' AND is_active = 1';
            $response = $this->pktdblib->custom_query($sql);
            
            $i++;
        }
        if(!empty($response))
        {
            $msg = array('message'=>'Area Priority Updated!', 'status'=>'success');
            echo json_encode($msg);
           exit;
        }else{
            $msg = array('message'=>'Something went wrong!', 'status'=>'error');
           echo json_encode($msg);
           exit;
        }

    }

    public function update_area_status()
    {
        $postData = $this->input->post();
        // print_r($postData);exit;
        if(!empty($postData)){
            if($postData['status'] == 0)
            {
                $sql = 'UPDATE areas SET is_active = '.$postData['status'].', priority = 0 WHERE id = '.$postData['id'].'';

            }else{
				
				$sql1 = 'SELECT MAX(priority) as priority FROM areas';
				$priority = $this->pktdblib->custom_query($sql1);
				// print_r($priority[0]['priority']);exit;
                $sql = 'UPDATE areas SET is_active = '.$postData['status'].', priority = '.($priority[0]['priority']+1).' WHERE id = '.$postData['id'].'';
				// echo $sql;exit;
            }
            $response = $this->pktdblib->custom_query($sql);
            if(!empty($response))
            {
                $msg = array('message'=>'Area Priority Updated!', 'status'=>'success');
                echo json_encode($msg);
               exit;
            }else{
                $msg = array('message'=>'Something went wrong!', 'status'=>'error');
               echo json_encode($msg);
               exit;
            }
        }
    }


}