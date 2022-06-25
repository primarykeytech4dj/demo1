<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |------------------------------------------------------------------------------
 |	USED ON ADMIN PAGES TO CHECK USER IS LOGGED IN
 |------------------------------------------------------------------------------
 |
 |	Checks that the user is logged in. This function should be called in every
 |	class __construct where the page requires user to be logged in.
 |
*/

if(!function_exists('check_user_login'))
{

	// Checks if user has verified email and is logged in by default. If email_verification is TRUE then it will also check if user has verified email address
	function check_user_login($email_verification = TRUE) {
		$CI =& get_instance();
		$params = json_decode(file_get_contents('php://input'), TRUE);
		if(!empty($params)){
            //print_r($params);exit;
		    //for API related setup
		    //echo 'Select * from user_roles where user_id='.$params['user_id'].' and login_id='.$params['login_id'].' and account_type like "'.$params['user_type'].'"';
		    $sql = $CI->db->query('Select * from user_roles where user_id='.$params['user_id'].' and login_id='.$params['login_id'].' and account_type like "'.$params['user_type'].'"');
		    if($sql->num_rows()>0){
		        $session_data = [];
		        $login = $CI->db->query('Select * from login where id='.$params['login_id']);
		        if($login->num_rows()==0){
		            $CI->pktlib->parseOutput($CI->config->item('response_format'), ['status'=>'failure', 'msg'=>'Invalid Login']);
		        }
		        foreach($login->result() as $row)
			    {
			        $CI->pktdblib->set_table('user_roles');
    				$userRoles = $CI->pktdblib->get_where_custom('login_id', $row->id);
    				//echo '<pre>';
    				//print_r($userRoles->result_array());exit;
                    $roles = [];
                    $profileImage='';
    				foreach ($userRoles->result_array() as $key => $userRole) {
    					//print_r($role);
    					$CI->pktdblib->set_table($userRole['account_type']);
    					$session_data[$userRole['account_type']] = $CI->pktdblib->get_where($userRole['user_id']);
    					if(empty($profileImage)){
    						if($userRole['account_type']=='companies'){
    							$profileImage = $session_data[$userRole['account_type']]['logo'];
    						}else{
    							$profileImage = $session_data[$userRole['account_type']]['profile_img'];
    
    						}
    					}
    					$roles[$key][$userRole['account_type']] = $userRole['role_id'];
    				}
    		        $addressQuery = $CI->db->query('Select a.*, c.name as country, s.state_name, ct.city_name, ar.area_name  from address a left join areas ar on ar.id=a.area_id left join cities ct on ct.id=a.city_id  left join countries c on c.id=a.country_id left join states s on s.id=a.state_id where a.user_id='.$params['login_id'].' and a.user_id in (select login_id from user_roles where login_id='.$params['login_id'].' and account_type="'.$params['user_type'].'")');
    			    $address = $addressQuery->result_array();
    				$session_data['user_id'] = $row->id;
    				$session_data['username'] = $row->username;
    				$session_data['name'] = $row->first_name." ".$row->surname;
    				$session_data['logged_in'] = TRUE;
    				$session_data['last_activity'] = time();
    				$session_data['email_verified'] = $row->email_verified;
    				$session_data['mobile_verified'] = $row->mobile_verified;
    				$session_data['account_type'] = $params['user_type'];
    				$session_data['logged_in_since'] = date('d F,y H:i:s');
    				$session_data['roles'] = $roles;
                    $session_data['address'] = $address;
    				$session_data['profileImage'] = $profileImage;
			    }
			    //print_r($session_data);exit;
			    $CI->session->set_userdata($session_data);
			    
			    
		        if($email_verification === TRUE)
    			{
    				// Check that user has verified their email address
    				if($CI->session->userdata('email_verified') === FALSE && $CI->session->userdata('mobile_verified') === FALSE)
    				{
    					$CI->load->model("login/mdl_login");
    					$CI->mdl_login->set_table("login");
    					$user_id = $params['login_id'];
    					$query = $CI->mdl_login->get_where($user_id);
    					foreach($query->result() as $row)
    					{
    						$db_email_ver = $row->email_verified;
    						$db_mobile_ver = $row->mobile_verified;
    					}
    					
    					if($db_email_ver !== "yes" && $db_mobile_ver !== "yes")
    					{
    						// If they have not verified their email address direct them to the email verification page
    						$CI->pktlib->parseOutput($CI->config->item('response_format'), ['status'=>'failure', 'msg'=>'Mobile or Email not verified']);
    					}
    				}
    			}
			
			$last_activity = $CI->session->userdata('last_activity');
			$current_time = time();
			$time_since_last_activity = ($current_time - $last_activity) / 60;
			
			if($time_since_last_activity > custom_constants::user_timeout)
			{
				$username = $CI->session->userdata('username');
				$CI->session->set_userdata('user_id', '');
				$CI->session->set_userdata('roles', array());
				$CI->session->set_userdata('username', '');
				$CI->session->set_userdata('logged_in', '');
				$CI->session->set_userdata('account_type', '');
				$CI->session->set_userdata('email_verified', '');
				$CI->session->set_userdata('mobile_verified', '');
				$CI->session->set_userdata('logged_in_since', '');
				
				$CI->session->set_flashdata('timed_out', 'TRUE');
				$CI->session->set_flashdata('username', $username);
				
				$requested_url = current_url();
				
				$CI->pktlib->parseOutput($CI->config->item('response_format'), ['status'=>'failure', 'msg'=>'Please login Again to continue']);
			}
			else
			{
				$CI->session->unset_userdata('last_activity');
				$CI->session->set_userdata('last_activity', time());
			}
		    }else{
		        $CI->pktlib->parseOutput($CI->config->item('response_format'), ['status'=>'failed', 'msg'=>'Invalid User']);
	            
		    }
		}else{
		//echo "reached in check login helper";
        //print_r($params);exit;
    		if($CI->session->userdata('logged_in'))
    		{ 
    			if($email_verification === TRUE)
    			{
    				// Check that user has verified their email address
    				if($CI->session->userdata('email_verified') === FALSE && $CI->session->userdata('mobile_verified') === FALSE)
    				{
    					$CI->load->model("login/mdl_login");
    					$CI->mdl_login->set_table("login");
    					$user_id = $CI->session->userdata('user_id');
    					$query = $CI->mdl_login->get_where($user_id);
    					foreach($query->result() as $row)
    					{
    						$db_email_ver = $row->email_verified;
    						$db_mobile_ver = $row->mobile_verified;
    					}
    					
    					if($db_email_ver !== "yes" && $db_mobile_ver !== "yes")
    					{
    						// If they have not verified their email address direct them to the email verification page
    						redirect(base_url() . custom_constants::email_verification_url);
    					}
    				}
    			}
    			
    			$last_activity = $CI->session->userdata('last_activity');
    			$current_time = time();
    			$time_since_last_activity = ($current_time - $last_activity) / 60;
    			
    			if($time_since_last_activity > custom_constants::user_timeout)
    			{
    				$username = $CI->session->userdata('username');
    				$CI->session->set_userdata('user_id', '');
    				$CI->session->set_userdata('roles', array());
    				$CI->session->set_userdata('username', '');
    				$CI->session->set_userdata('logged_in', '');
    				$CI->session->set_userdata('account_type', '');
    				$CI->session->set_userdata('email_verified', '');
    				$CI->session->set_userdata('mobile_verified', '');
    				$CI->session->set_userdata('logged_in_since', '');
    				
    				$CI->session->set_flashdata('timed_out', 'TRUE');
    				$CI->session->set_flashdata('username', $username);
    				
    				$requested_url = current_url();
    				$CI->session->set_userdata('requested_url', $requested_url);
    				
    				redirect(base_url() . custom_constants::login_page_url);
    			}
    			else
    			{
    				$CI->session->unset_userdata('last_activity');
    				$CI->session->set_userdata('last_activity', time());
    			}
    		}
    		else
    		{ 
    			$requested_url = current_url();
    			$CI->session->set_userdata('requested_url', $requested_url);
    			//echo base_url() . custom_constants::login_page_url;
    			//echo "hello";exit;
    			redirect(base_url() . custom_constants::login_page_url);
    		}
    	}
	}
}

?>