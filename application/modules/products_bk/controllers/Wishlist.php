<?php 

class Wishlist extends MY_Controller {
	function __construct() {
		parent::__construct();

		/*foreach(custom_constants::$protected_pages as $page)
		{	
			if(strpos($this->uri->uri_string, $page) === 0)
			{ 	
			}
		}*/
		check_user_login(TRUE);
		$this->load->model('products/wishlist_model');
		$this->load->library('ajax_pagination');
		$this->perPage = 10;
	}

    function index($page=0)
	{
		$params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            //print_r($params);exit;
            $_POST = $params;
        }
        
        $conditions = [];
		if($_SERVER['REQUEST_METHOD']=='POST' || NULL!==$params){
			$conditions['condition'] = $this->input->post('condition');
		}
		//$conditions['condition'] = $this->input->post();
		if(NULL===$this->input->post('page')){
			$_POST['page'] = $page;
		}else
        $page = $this->input->post('page'); 
        
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        } 
         
        // Get record count 
        $conditions['returnType'] = 'count'; 
        $totalRec = $this->wishlist_model->_get_all_products($conditions); 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('wishlist/index/'.$page); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
        $config['link_func']   = 'searchFilter';
        $config['uri_segment'] = 2;
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions2 = array( 
            'start' => $offset, 
            'limit' => $this->perPage, 
        ); 

        if(isset($conditions['condition'])){
	        $conditions2['condition'] = $conditions['condition'];
	    } 
        $data['allProduct'] = $this->wishlist_model->_get_all_products($conditions2);
        //echo '<pre>';
        //print_r($data['allProduct']);
        
        foreach($data['allProduct'] as $key=>$product){
    	    $attribute = Modules::run('products/product_default_attribute',$product['id']);
    	    //print_r($attribute);exit;
    	    if($attribute){
    	        foreach($attribute as $attKey=>$att)
    	        {
    	            $data['allProduct'][$key][$attKey] = $att;
    	        }
    	    }
    	    
    	    $data['allProduct'][$key]['attributes'] = Modules::run('products/product_attributes',$product['id']);
    	}
    	
    	$data['total_rows'] = $totalRec;
    	$data['page'] = $page;
        	
    	$this->pktlib->parseOutput($this->config->item('response_format'), ['all_product'=>$data]);
        
        $this->load->view("products/all_products", $data);
    	
	}
	
	function add()
	{
		$params = json_decode(file_get_contents('php://input'), TRUE);
        //print_r($params);exit;
        if(!empty($params)){
            $_POST = $params;
        }
        
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $this->form_validation->set_rules('wishlist[product_id]', 'product_id', 'required|numeric');
            if($this->form_validation->run()!==FALSE){
                $postData = $this->input->post();
                //echo 'Select * from wishlist where user_id="'.$postData['user_id'].'" and product_id="'.$postData['wishlist']['product_id'].'"';exit;
                $chk = $this->pktdblib->custom_query('Select * from wishlist where user_id="'.$postData['user_id'].'" and product_id="'.$postData['wishlist']['product_id'].'"');
                if(count($chk)>0){
                    $upArray['is_active'] = true;
                    $upArray['modified'] = date('Y-m-d H:i:s');
                    $upArray['modified_by'] = $postData['login_id'];
                    $this->db->where('product_id', $postData['wishlist']['product_id']);
                    $this->db->where('user_id', $postData['user_id']);
                    $upd = $this->db->update('wishlist', $upArray);
                    if($upd){
                        if($this->input->is_ajax_request() || !empty($params)){
                            echo json_encode(['status'=>'success', 'msg'=>'Product already added to wishlist']);
                            exit;
                        }else{
                            $msg = array('message' => "Product already added to wishlist", 'class' => 'alert alert-success');
               		        $this->session->set_flashdata('message',$msg);
                        }
                    }
                    
                }else{
                    $ins['user_id'] =$postData['user_id'];
                    $ins['product_id'] = $postData['wishlist']['product_id'];
                    $ins['created_by'] = $postData['login_id'];
                    $upArray['created'] = date('Y-m-d H:i:s');
                    $this->pktdblib->set_table('wishlist');
                    $id = $this->pktdblib->_insert($ins);
                    if($id['status']='success'){
                        if($this->input->is_ajax_request() || !empty($params)){
                            echo json_encode(['status'=>'success', 'msg'=>'Product added to wishlist']);
                            exit;
                        }else{
                            $msg = array('message' => "Product added to wishlist", 'class' => 'alert alert-success');
               		        $this->session->set_flashdata('message',$msg);
                        }
                    }
                    else{
                        if($this->input->is_ajax_request() || !empty($params)){
                            echo json_encode(['status'=>'failed', 'msg'=>'Failed to add in wishlist']);
                            exit;
                        }else{
                            $msg = array('message' => "Failed to add in wishlist", 'class' => 'alert alert-danger');
               		        $this->session->set_flashdata('message',$msg);
                        }
                    }
                    
                }
                $insArr['user_id'] = $this->input->post('user_id'); 
                
			}else{
			    if($this->input->is_ajax_request() || !empty($params)){
			        echo json_encode(['status'=>'failed', 'msg'=>'validation Error Occured '.validation_errors()]);
                    exit;
                    
                }else{
                    $msg = array('message' => "Following Validation Error Occurred.".validation_errors(), 'class' => 'alert alert-danger');
               		$this->session->set_flashdata('message',$msg);
                }
			}
        }
    	
	}

}
	