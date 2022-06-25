<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 |	CONTROLLER SUMMARY AND DATABASE TABLES
 |--------------------------------------------------------------------------
 | 
 |	Used to authenticate user logins. Features IP blacklisting, email address
 |	whitelisting, register user, reset password, email verification and
 |	username reminder emails.
 |
 |	Configuration can be found inside the library called custom_constants.php
 |
 |	Module can be downloaded from GitHub at https://github.com/lewnelson/CI-hmvc-login-module
 |
 |
 |	Default database is called login. This can be changed in mdl_login.php
 |
 |	Database table structure
 |
 |	Table name(s) - login, ip_blacklist, email_whitelist
 |
 |	ai = auto_increment
 |	pk = primary_key
 |	null = value can be set to null if not set assume not null
 |
 |	Table - login
 |
 |	||==================================================================================================||
 |	|| column						| type (flags)			| description								||
 |	||==================================================================================================||
 |	|| id							| int (ai, pk)			| id primary_key							||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| first_name					| varchar(64)			| Users first name							||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| surname						| varchar(64)			| Users surname								||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| username						| varchar(24)			| Users username							||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| password_hash				| varchar(256)			| Users password hash						||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| account_type					| varchar(32)			| Account type (custom string)				||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| email						| varchar(320)			| User email address						||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| email_verification_link		| varchar(64) null		| sha1 email verification link				||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| email_ver_time				| varchar(15) null		| Time email verification link was created	||
 |	||								|						| unix timestamp format						||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| email_verified				| varchar(3) null		| Whether user has verified email. NULL or	||
 |	||								|						| yes										||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| accnt_create_time			| varchar(15)			| When the account was registered			||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| passwd_reset_str				| varchar(64) null		| sha1 email password reset link			||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| passwd_reset_time			| varchar (15) null		| Time password reset link was created		||
 |	||								|						| unix timestamp format						||
 |	||==================================================================================================||
 |
 |
 |
 |	Table - ip_blacklist
 |
 |	||==================================================================================================||
 |	|| column						| type (flags)			| description								||
 |	||==================================================================================================||
 |	|| id							| int (ai, pk)			| id primary_key							||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| ip_address					| varchar(15)			| Users IP address							||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| failed_attempts				| int					| Number of failed attempts from IP address	||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| lock_time					| varchar(15)			| Time when IP was locked out. Unix			||
 |	||								|						| timestamp									||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| last_login_attempt			| varchar(15)			| Time when user last attempted login. Unix	||
 |	||								|						| timestamp									||
 |	||==================================================================================================||
 |
 |
 |
 |	Table - email_whitelist
 |
 |	||==================================================================================================||
 |	|| column						| type (flags)			| description								||
 |	||==================================================================================================||
 |	|| id							| int (ai, pk)			| id primary_key							||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| email						| varchar(320)			| User email address						||
 |	||------------------------------+-----------------------+-------------------------------------------||
 |	|| account_type					| varchar (32)			| Account type (custom string)				||
 |	||==================================================================================================||
 |
 */
 
 
class Login extends MY_Controller {

	// Configuration properties used in blacklisting
	private $num_login_attempts;
	private $ip_address;
	private $logged_in;
	
			//print_r($ip_address); exit;
	function __construct() {
		parent::__construct();
		$this->ip_address = (NULL!==$this->session->userdata("ip_address"))?$this->session->userdata("ip_address"):$_SERVER['SERVER_ADDR'];
		// If logged in then set logged_in to TRUE otherwise set to FALSE
		if($this->session->userdata('logged_in'))
		{
			$this->logged_in = TRUE;
		}
		else
		{
			$this->logged_in = FALSE;
		}
		//$this->load->model('mdl_login');
		/*$this->load->model('countries/countries_model');
		$this->load->model('states/states_model');
		$this->load->model('cities/cities_model');
		$this->load->model('areas/areas_model');*/
		$this->load->model('mdl_login');
		//echo "reached here";exit;
		$setup = $this->setup();
		//$menuSetup = Modules::run('menus/setup');
	}

	function setup(){
		//echo 'reached in setup';exit;
		$ip_blacklist = $this->mdl_login->tbl_ip_blacklist();
		$bloodGroup = $this->mdl_login->tbl_blood_group();
		$companies = Modules::run('companies/setup');
		$login = $this->mdl_login->tbl_login();
		$setup = $this->mdl_login->tbl_setup();
		$roles = Modules::run('roles/setup');
		$country = Modules::run('countries/setup');
		$states = Modules::run('states/setup');
		$cities = Modules::run('cities/setup');
		$areas = Modules::run('areas/setup');
		$userRoles = $this->mdl_login->tbl_user_roles();
		return TRUE;
	}

    function admin_index() {
		$data = array();
		// Check if currently timed out
		$data['timeout_left'] = $this->_check_blacklist($this->ip_address);
		//print_r($this->logged_in);exit;
		if($this->logged_in === TRUE)
		{
			redirect(base_url() . custom_constants::admin_page_url);
		}
		
		if($this->input->post('username/email') or $this->input->post('password'))
		{
			$this->load->library("form_validation");
			
			if(custom_constants::email_login_allowed === TRUE)
			{				
				// Check if user has entered email or username
				if(strpos($this->input->post('username/email'), '@'))
				{
				
					// Email was entered
					$type = 'email';
					$this->form_validation->set_rules('username/email', 'email', 'required|max_length[320]|valid_email');
				}
				else
				{
					// Username was entered
					$type = 'username';
					$this->form_validation->set_rules('username/email', 'username', 'required|max_length[24]|alpha_dash');
				}
			}
			else
			{
				
				// Treat input as username
				$type = 'username';
				$this->form_validation->set_rules('username/email', 'username', 'required|max_length[24]|alpha_dash');
			}
			
			$this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
			/*print_r($_POST);
			echo $this->form_validation->run();
			exit;*/
			if($this->form_validation->run())
			{
				
				$username = strtolower($this->input->post("username/email"));
				$password = $this->input->post("password");
				
				if($this->_validate_login($username, $password, $type))
				{  
					$uncookie = array(
                        'name'   => 'username',
                        'value'  => $username,                            
                        'expire' => time()+(12*60*60),                                                                                   
                        'secure' => TRUE
                    );
                    $pwdcookie = array(
                        'name'   => 'password',
                        'value'  => $password,                            
                        'expire' => time()+(12*60*60),                                                                                   
                        'secure' => TRUE
                    );
                    
                    $this->input->set_cookie($uncookie);
                    $this->input->set_cookie($pwdcookie);
                        
					if(NULL !== ($this->session->userdata('requested_url')))
					{
						// Get requested URL, remove it from the session and redirect to it 302
						$req_url = $this->session->userdata('requested_url');
						$this->session->unset_userdata('requested_url');
						redirect($req_url);
					}
					else
					{
						redirect(base_url() . custom_constants::admin_page_url);
					}
				}
				else
				{
					//echo "hello"; exit;
					// Authentication failed so we update our blacklist
					if($this->_update_blacklist() === FALSE)
					{ 
						// If max login attempts reached then reload the login page
						redirect(base_url() . custom_constants::admin_page_url);
					}

					// Set data auth_failed to let view know authentication failed
					$data['auth_failed'] = TRUE;
					$msg = array('message'=>'Invalid Login', 'class'=>'alert alert-danger');
					$this->session->set_flashdata('message', $msg);
				}
			}else { 
				//echo validation_errors();exit;
			}
		}
		/*$data['id'] = 1;
		$home = Modules::run('companies/get_company_details', $data);*/
		//print_r($home);exit;
		$data['title'] = $data['meta_title'] = 'ERP Login';
		$data['meta_description'] = 'ERP Login';
		$data['meta_keyword'] = 'Login';
		
		$data['modules'][] = "login";
		$data['methods'][] = "admin_login";
		
		echo Modules::run("templates/login_template", $data);
    }

    function index() {
    	//echo 'reached here';exit;
		$data = array();
		// Check if currently timed out
		$data['timeout_left'] = $this->_check_blacklist($this->ip_address);
		//print_r($this->session->userdata());exit;
		if($this->logged_in === TRUE)
		{
			redirect(base_url() . custom_constants::admin_page_url);
		}
		if($this->input->post('username/email') or $this->input->post('password'))
		{
			$this->load->library("form_validation");
			
			if(custom_constants::email_login_allowed === TRUE)
			{				
				// Check if user has entered email or username
				if(strpos($this->input->post('username/email'), '@'))
				{
				
					// Email was entered
					$type = 'email';
					$this->form_validation->set_rules('username/email', 'email', 'required|max_length[320]|valid_email');
				}
				else
				{
					// Username was entered
					$type = 'username';
					$this->form_validation->set_rules('username/email', 'username', 'required|max_length[255]');
				}
			}
			else
			{
				
				// Treat input as username
				$type = 'username';
				$this->form_validation->set_rules('username/email', 'username', 'required|max_length[24]|alpha_dash');
			}
			
			$this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
			
			if($this->form_validation->run())
			{
				$username = strtolower($this->input->post("username/email"));
				$password = $this->input->post("password");
				if($this->_validate_login($username, $password, $type))
				{
					if(NULL !== ($this->session->userdata('requested_url')))
					{
						// Get requested URL, remove it from the session and redirect to it 302
						$req_url = $this->session->userdata('requested_url');
						$this->session->unset_userdata('requested_url');
						redirect($req_url);
					}
					else
					{
						redirect(base_url() . custom_constants::customer_page_url);
					}
				}
				else
				{
					$msg = array('type' => 'login', 'message'=> "Invalid Login", 'class' => 'alert alert-danger');
                	$this->session->set_flashdata('message', $msg);
					// Authentication failed so we update our blacklist
					if($this->_update_blacklist() === FALSE)
					{
						// If max login attempts reached then reload the login page
                    	//print_r($this->session->flashdata('message'));exit;
						redirect(base_url() . custom_constants::login_page_url);
					}
					else
					{
						redirect(base_url() . custom_constants::login_page_url);
					}
					
					// Set data auth_failed to let view know authentication failed
					$data['auth_failed'] = TRUE;
				}
			}else { 
				$msg = array('message'=> validation_errors(), 'class' => 'alert alert-danger');
                $this->session->set_flashdata('message', $msg);
				//echo validation_errors();exit;
			}
		}
		
		$data['title'] = 'Login Module';
		$data['meta_keyword'] = "Login module in Codeigniter";
		//echo "hello";exit;
		$data['meta_title'] = "Login Module";
		$data['meta_description'] = "Login Module";
		
		$data['modules'][] = "login";
		//$data['methods'][] = "front_login";
		$data['methods'][] = "admin_login";

		$data['js'][] = '<script>
        $(document).ready(function() {
          $("#login-banner").owlCarousel({
            // navigation : false, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,autoPlay:3000,
          });
          
        });
      </script>';
		echo Modules::run("templates/login_template", $data);
    }
	
	private function _check_blacklist() {
		if(custom_constants::num_login_attempts === FALSE)
		{
			return FALSE;
		}
		
		// See if the IP address is on the blacklist
		$this->pktdblib->set_table("ip_blacklist");
		if($this->pktdblib->count_where("ip_address", $this->ip_address) > 0)
		{			
			$timeout = custom_constants::black_list_timeout;
			$reset_time = custom_constants::black_list_reset_time;
			
			$current_time = time();
			
			$query = $this->pktdblib->get_where_custom("ip_address", $this->ip_address);
		//print_r($query);exit;
			foreach($query->result() as $row)
			{
				$login_attempts = $row->failed_attempts;
				if(!$row->lock_time !== NULL)
				{
					$locked_out_since = $row->lock_time;
				}
				$time_last_attempt = $row->last_login_attempt;
			}
			
			$time_since_last_attempt = ($current_time - $time_last_attempt) / 60;
			if($time_since_last_attempt > $reset_time)
			{
				$this->_remove_ip_blacklist();
			}
			
			if($login_attempts > custom_constants::num_login_attempts)
			{
				$time_waited = ($current_time - $locked_out_since) / 60;
				
				if($time_waited < $timeout)
				{
					$timeout_left = $timeout - $time_waited;
					return $timeout_left;
				}
				else
				{
					$this->_remove_ip_blacklist();
					return FALSE;
				}
			}
			else
			{				
				// IP address is not blacklisted.
				return FALSE;
			}
		}
		else
		{
			// IP address is not blacklisted.
			return FALSE;
		}
	}
	
	private function _remove_ip_blacklist() {
		$this->pktdblib->set_table("ip_blacklist");
		$this->pktdblib->_delete_by_column("ip_address", $this->ip_address);
	}
	
	private function _update_blacklist() {
		if(custom_constants::num_login_attempts === FALSE)
		{
			return TRUE;
		}
		
		$last_login_attempt = time();
		
		$this->pktdblib->set_table("ip_blacklist");
		//print_r($this->ip_address);exit;
		/*print_r($this->pktdblib->count_where("ip_address", $this->ip_address));
		exit;*/
		if($this->pktdblib->count_where("ip_address", $this->ip_address) > 0)
		{
			$query = $this->pktdblib->get_where_custom("ip_address", $this->ip_address);
			foreach($query->result() as $row)
			{
				$id = $row->id;
				$failed_attempts = $row->failed_attempts;
			}
			
			if($failed_attempts == custom_constants::num_login_attempts)
			{
				$update_data['lock_time'] = time();
			}
			
			$update_data['failed_attempts'] = $failed_attempts + 1;
			$update_data['last_login_attempt'] = $last_login_attempt + 1;
			$this->pktdblib->_update($id, $update_data);
			if(isset($update_data['lock_time']))
			{ 
				// User locked out
				return FALSE;
			}

			return TRUE;
		}
		else
		{
			$failed_attempts = 1;
			$insert_data = array(
								"ip_address" => $this->ip_address,
								"failed_attempts" => $failed_attempts,
								"last_login_attempt" => $last_login_attempt
							);
			
			$this->pktdblib->_insert($insert_data);
		}
	}
	
	private function _validate_login($username, $password, $type) {
		// type is username or email
		$this->pktdblib->set_table("login");
		//echo $username." ".$type;exit;
		
		if($this->pktdblib->count_where($type, $username) > 0)
		{	
			$this->pktdblib->set_table('setup');
				$query = $this->pktdblib->get('setup.priority asc');
				$setupArray = [];
				foreach ($query->result_array() as $key => $setup) {
					$setupArray['application'][$setup['parameter']] = $setup['value'];
				}
			$this->load->model("login/mdl_login");
			//$query = $this->mdl_login->get_where_custom_login($type, $username);
			//$loginData = [$type=>$username]
			$session = [];
			$query = $this->mdl_login->generic_login($type, $username);
			/*echo '<pre>';
			print_r($query->result());exit;*/
			foreach($query->result() as $row)
			{
				$user_id = $row->id;
				$account_type = $row->account_type;
				$user_username = $row->username;
				$hashed_pass = $row->password_hash;
				$passwd_reset_str = $row->passwd_reset_str;
				$profileImage = '';
				$name = $row->emp_name;
				//$employeeId = $row->emp_id;
				//$employeeCode = $row->emp_code;
				
				if($row->email_verified === "yes")
				{
					$email_verified = TRUE;
				}
				else
				{
					$email_verified = FALSE;
				}
				if($row->mobile_verified === "yes")
				{
					$mobile_verified = TRUE;
				}
				else
				{
					$mobile_verified = FALSE;
				}
				$this->pktdblib->set_table('user_roles');
				$userRoles = $this->pktdblib->get_where_custom('login_id', $row->id);
				//echo '<pre>';
				//print_r($userRoles->result_array());exit;
				$accessCompanies = [];
				$companyKeyMapping = ['employees'=>'employee_id', 'customers'=>'customer_id', 'vendors'=>'vendor_id', 'companies'=>'parent_company_id', ];
				/*echo '<pre>';
				print_r($setupArray['application']['multiple_company']);*/
				foreach ($userRoles->result_array() as $key => $userRole) {
					//print_r($role);
					$this->pktdblib->set_table($userRole['account_type']);
					$session_data[$userRole['account_type']] = $this->pktdblib->get_where($userRole['user_id']);
					if(empty($profileImage)){
						if($userRole['account_type']=='companies'){
							$profileImage = $session_data[$userRole['account_type']]['logo'];
						}else{
							$profileImage = $session_data[$userRole['account_type']]['profile_img'];

						}
					}
					$roles[$userRole['role_id']] = $userRole['role_id'];
					//echo 'reached here';
					/*echo '<pre>';
					echo ($setupArray['application']['multiple_company']);*/
					if(isset($setupArray['application']['multiple_company']) && $setupArray['application']['multiple_company']){
						//echo 'SHOW TABLES LIKE "companies_'.$userRole['account_type'].'"';
						$tbl = $this->pktdblib->custom_query('SHOW TABLES LIKE "companies_'.$userRole['account_type'].'"');
						if(!empty($tbl)){

							$this->pktdblib->set_table('companies_'.$userRole['account_type']);
							$query = $this->pktdblib->get_where_custom($companyKeyMapping[$userRole['account_type']], $userRole['user_id']);
							foreach ($query->result_array() as $qkey => $qval) {
								# code...
								$accessCompanies[] = $qval['company_id'];
							}
						}
						/*if($userRole['account_type']=='companies'){

							$accessCompanies
						}*/
					}
				}
				//print_r($session);exit;
			}
			/*echo '<pre>';
			print_r($accessCompanies);
			exit;*/
			if(empty($roles))
				return FALSE;
			//echo "hii";exit;
			//print_r($roles);exit;
			//echo $profileImage;exit;
			if(password_verify($password, $hashed_pass) === TRUE)
			{
				$session_data['user_id'] = $user_id;
				$session_data['username'] = $user_username;
				$session_data['name'] = $name;
				$session_data['logged_in'] = TRUE;
				$session_data['last_activity'] = time();
				$session_data['email_verified'] = $email_verified;
				$session_data['mobile_verified'] = $mobile_verified;
				$session_data['account_type'] = $account_type;
				$session_data['logged_in_since'] = date('d F,y H:i:s');
				$session_data['roles'] = $roles;
				$session_data['access_to_company'] = array_unique($accessCompanies);
				$session_data['profileImage'] = $profileImage;
				$session_data['current_fiscal_yr'] = $this->pktlib->get_fiscal_year();
				$this->session->set_userdata($setupArray);
				$this->session->set_userdata($session_data);
				$data['new_expiration'] = 60*60*24*30;//30 days
                $this->session->sess_expiration = $data['new_expiration'];
				$this->session->set_userdata(['access_menu' => Modules::run('menus/get_rolewise_menus')]);
				/*echo '<pre>';
				print_r($_SESSION);
				exit;*/
				$this->_remove_ip_blacklist();
				
				if($passwd_reset_str !== NULL)
				{
					$this->pktdblib->set_table("login");
					
					$update_data['passwd_reset_str'] = NULL;
					$update_data['passwd_reset_time'] = NULL;
					
					$this->pktdblib->_update($user_id, $update_data);
				}
				
				// Successful login
				return TRUE;
			}
			else
			{ //echo "invalid password";exit;
				// Invalid password
				return FALSE;
			}
		}
		else
		{
			// Invalid username/email
			return FALSE;
		}
	}
	
	function register_user_form() {
		//check_user_login(FALSE);
		if(custom_constants::registration_disable === TRUE)
		{
			redirect(base_url() . custom_constants::login_page_url);
		}
		
		if($this->logged_in === TRUE)
		{
			$data['logged_in'] = TRUE;
		}
		else
		{
			$data['logged_in'] = FALSE;
		}
		
		$data['registered'] = FALSE;
		
		if($this->input->post('first_name'))
		{
			$data['values_posted']['first_name'] = $this->input->post('first_name');
			$data['values_posted']['surname'] = $this->input->post('surname');
			$data['values_posted']['profile_img'] = $this->input->post('profile_img');
			$data['values_posted']['username'] = str_replace(' ', '', $this->input->post('username'));
			$data['values_posted']['email'] = $this->input->post('email');
			
			$this->load->library("form_validation");
			
			$this->form_validation->set_rules('first_name', 'first name', 'required|max_length[64]|alpha_dash');
			$this->form_validation->set_rules('surname', 'surname', 'required|max_length[64]|min_length[2]|alpha_dash');
			$this->form_validation->set_rules('username', 'username', 'required|max_length[32]|min_length[3]|alpha_dash');
			$this->form_validation->set_rules('email', 'email', 'required|max_length[320]|valid_email|matches[email_confirmation]');
			$this->form_validation->set_rules('email_confirmation', 'confirm email', 'required|max_length[320]|valid_email');
			$this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[32]|matches[password_confirmation]');
			$this->form_validation->set_rules('password_confirmation', 'confirm password', 'required|min_length[8]|max_length[32]');
			
			if($this->form_validation->run())
			{
				$post_data['first_name'] = $this->input->post('first_name');
				$post_data['surname'] = $this->input->post('surname');
				$post_data['username'] = str_replace(' ', '', $this->input->post('username'));
				$post_data['password'] = $this->input->post('password');
				$post_data['email'] = $this->input->post('email');
				//echo "register user form"; exit;
				
				$reg_user = $this->_register_user($post_data);
				/*echo $reg_user;
				exit;*/
				
				if($reg_user === FALSE)
				{
					// Successfully registered
					$data['email_verified'] = $this->session->userdata('email_verified');
					$data['registered'] = TRUE;
				}
				else
				{
					// Registration error
					$data['form_error'] = $reg_user;
				}
			}
		}

		$blood_groups = $this->mdl_login->get_dropdown_list();
		
		foreach($blood_groups as $blood_groupKey => $blood_group) {
			$data['option']['blood_groups'][$blood_group['id']] = $blood_group['blood_group'];
		}

		//echo"hello";exit;
			//echo '<pre>';
		$data['option']['cities'] = Modules::run('cities/get_dropdown_list');
		/*$this->cities_model->get_dropdown_list();
		//print_r($cities);
		foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}*/

		$data['option']['states'] = Modules::run('states/get_dropdown_list');
		/*$this->states_model->get_dropdown_list();
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}*/
		/*print_r($data['option']['states']);
		exit;*/
		//echo "hii";exit;
		$data['option']['countries'] = Modules::run('countries/get_dropdown_list');

		/*$countries =$this->countries_model->get_dropdown_list();
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}*/
		$data['option']['areas'] = Modules::run('areas/get_dropdown_list');
		/*$areas =$this->areas_model->get_dropdown_list();
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}*/

		/*$this->pktdblib->set_table('areas');
		$data['areas'] = $this->pktdblib->get_list();
		$this->pktdblib->set_table('states');
		$data['states'] = $this->pktdblib->get_list();
		$this->pktdblib->set_table('countries');
		$data['countries'] = $this->pktdblib->get_list();*/
		$data['meta_title'] = "Register";
		$data['meta_description'] = "New user registration";
		
		$data['modules'][] = "login";
		$data['methods'][] = "view_login_register";
		
		//echo Modules::run("templates/login_template", $data);
		echo Modules::run("templates/admin_template", $data);
	}
	
	function _register_user($data) {
		//print_r($data);exit;
		/*echo "register user";
		exit;*/
		//$this->load->model("login/mdl_login");
		$this->pktdblib->set_table("login");
		
		if($this->pktdblib->count_where("username", $data['username']) > 0)
		{
			return json_encode(["msg"=>"Username has been taken", "status"=>"error"]);
		}
		
		if($this->pktdblib->count_where("email", $data['email']) > 0)
		{
			return json_encode(["msg"=>"Email is already in use", "status"=>"error"]);
			//return "email is already in use";
		}
		
		$insert_data['first_name'] = $data['first_name'];
		$insert_data['surname'] = $data['surname'];
		$insert_data['username'] = strtolower($data['username']);	// Usernames are case insensitive so always make them lower case
		$insert_data['email'] =  $data['email'];
		$insert_data['employee_id'] =  $data['employee_id'];
		$insert_data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
		$insert_data['accnt_create_time'] = time();
		$white_list_account = $this->_email_whitelisted($data['email']);
		//echo "reached here";
		//print_r($white_list_account);exit;
		if($white_list_account === FALSE)
		{ //echo custom_constants::white_list." whitelist";exit;
			if(custom_constants::white_list === FALSE)
			{
				$insert_data['account_type'] = (NULL ===$data['account_type'])? custom_constants::default_account_type:$data['account_type'];
				
				$insert_data['email_verification_link'] = $this->_create_email_ver_string($data['username'], $data['email'], $data['contact_1'], TRUE);
				$insert_data['email_ver_time'] = time();
			}
			else
			{
				return "Your email address is not on the white list for registration. If you
				believe this information is wrong please contact the site administrator.";
			}
		}
		else
		{
			$insert_data['email_verified'] = "yes";
			$insert_data['account_type'] = $white_list_account;
		}
		/*echo '<pre>';
		print_r($insert_data);
		exit;*/
		$this->pktdblib->set_table("login");
		//print_r($insert_data);exit;
		$id = $this->pktdblib->_insert($insert_data);
		//print_r($login);exit;
		if($id){
			if(!isset($data['username']) && empty($data['username'])){
				$username = $this->create_username($id);
	            $updArr['id'] = $id;
	            $updArr['username'] = $username;
	            $updLogin = $this->edit_login($id, $updArr);
	        }

			$roleArray['login_id'] = $id;
			$roleArray['account_type'] = $insert_data['account_type'];
			$roleArray['user_id'] = $insert_data['employee_id'];
			$this->pktdblib->set_table('roles');
			$rolequery = $this->pktdblib->get_where_custom('role_name', $insert_data['account_type']);
			$role = $rolequery->row_array();

			$roleArray['role_id'] = ($rolequery->num_rows()>0)?$role['id']:1;
			//print_r($roleArray);exit;
			$userRole = $this->createUserRole($roleArray);
			//exit;
		}

		if(isset($data['is_new']) && $data['is_new']==true){
			return TRUE;
		}
		$this->mdl_login->set_table('login');

		$query = $this->mdl_login->generic_login("email", $data['email']);
		/*echo '<pre>';
		print_r($query->row());
		exit;*/
		$roles = [];
		foreach($query->result() as $row)
		{
			$user_id = $row->id;
			$username = $row->username;
			$account_type = $row->account_type;
			if($row->email_verified === "yes")
			{
				$email_verified = TRUE;
			}
			else
			{
				$email_verified = FALSE;
			}
			if($row->mobile_verified === "yes")
			{
				$mobile_verified = TRUE;
			}
			else
			{
				$mobile_verified = FALSE;
			}
			$this->pktdblib->set_table('user_roles');
			$userRoles = $this->pktdblib->get_where_custom('login_id', $row->id);
			foreach ($userRoles->result_array() as $key => $role) {
				$roles[$role['role_id']] = $role['role_id'];
			}
		}

		if(empty($roles))
			return FALSE;
		
		$session_data = array(
							'user_id' => $user_id,
							'username' => $username,
							'logged_in' => TRUE,
							'account_type' => $account_type,
							'last_activity' => time(),
							'email_verified' => $email_verified,
							'mobile_verified' => $mobile_verified,
							'logged_in_since' => time(),
							'roles' => $roles
						);
		
		$this->session->set_userdata($session_data);
		
		// Successful registration. User is also logged in
		return FALSE;
	}
	
	private function _email_whitelisted($email) {
		//$this->load->model("mdl_login");
		$this->pktdblib->set_table("email_whitelist");
		
		if($this->pktdblib->count_where("email", $email) > 0)
		{
			$query = $this->pktdblib->get_where_custom("email", $email);
			foreach($query->result() as $row)
			{
				$account_type = $row->account_type;
			}
		}
		else
		{
			// Email address is not on whitelist
			return FALSE;
		}
		
		return $account_type;
	}
	
	function email_verification($ver_string = FALSE) {
		//check_user_login(TRUE);
		//echo "hii";exit;
		$data['logged_in'] = 'no';
		if($this->logged_in !== FALSE)
		{
			$data['logged_in'] = 'yes';
		}
		
		if($this->session->userdata('email_verified') === TRUE)
		{
			$data['email_already_verified'] = TRUE;
		}
		else
		{
			$data['email_already_verified'] = FALSE;
			
			if($ver_string === FALSE && !isset($_POST['otp']))
			{
			    if(!$this->session->userdata("otpsent"))
			    {
			        $this->session->set_userdata('otpsent', TRUE);
			        $this->generateOtp();
			    }
				$data['string_entered'] = FALSE;
			}
			else
			{
				$data['string_entered'] = TRUE;

				if(isset($_POST['otp']))
				{
					$string_check = $this->_verify_mobile($_POST['otp']);
					if($string_check !== FALSE)
					{
						$data['new_verify_link'] = "<p>If you need a new verification email sent then please <a href='" . base_url() . custom_constants::new_email_ver_link_url . "' class='blue_anchor'><u>Click here</u></a>.</p>";
					}
				}
				else
				{
					$string_check = $this->_check_email_ver_string($ver_string);
				}

				if($string_check === FALSE)
				{
					$data['email_verified'] = TRUE;
				}
				else
				{
					$data['email_verified'] = FALSE;
					$data['email_ver_error'] = $string_check;
				}
			}
		}
		$data['title'] = "Verify Email";
        $data['meta_keyword'] = "Verify Email";
		$data['meta_title'] = "Verify Email";
		$data['meta_description'] = "Email verification";
		$data['js'][] = '<script>
        $(document).ready(function() {
          $("#login-banner").owlCarousel({
            // navigation : false, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,autoPlay:3000,
          });
          
        });
      </script>';
		$data['modules'][] = "login";
		$data['methods'][] = "view_verify_email";
		
		echo Modules::run("templates/login_template", $data);
	}

	public function _verify_mobile($otp='')
	{
		if($this->logged_in === FALSE)
		{
			redirect(base_url() . custom_constants::login_page_url);
		}
		
		$return = '';
		if(isset($otp) && !empty($otp))
		{
			$username = $this->session->userdata("username");
			//$this->load->model("mdl_login");
			$this->pktdblib->set_table("login");
			$query = $this->pktdblib->get_where_custom("username", $username);
			$mobile = '';
			$actualOtp = '';
			$modified = '';

			foreach($query->result() as $row)
			{
				$user_id = $row->id;
				$email = $row->email;
				$employee_id = $row->employee_id;
				$account_type = $row->account_type;
				$mobile = $this->getMobileNumber($account_type, $employee_id);
			}

			//$this->load->model("login/mdl_login");
			$this->pktdblib->set_table("otp");
			$query = $this->pktdblib->get_where_custom("mobile", $mobile);

			if($query->result())
			{
				foreach($query->result() as $row)
				{
					$actualOtp = $row->otp;
					$modified = $row->modified;
				}

				if($otp === $actualOtp)
				{
					$mydate = date("Y-m-d H:i:s");
				  	$theDiff = "";
				  	$datetime1 = date_create($modified);
				  	$datetime2 = date_create($mydate);
				  	$interval = date_diff($datetime1, $datetime2);
				  	// echo "<pre>";print_r($interval);exit;
				  	
				  	if($interval->h > 0 || $interval->i > custom_constants::mobile_ver_string_time)
				  	{
				  		$return = "<p class='alert alert-danger text-center'>OTP expired.</p>";
				  	}
				  	else
				  	{
						$this->session->set_userdata('mobile_verified', TRUE);
						$this->pktdblib->set_table("login");
						$this->pktdblib->_update($user_id, array('mobile_verified' => 'yes'));
				  		$return = FALSE;
				  	}
				}
				else
				{
					$return = "<p class='alert alert-danger text-center'>Invalid OTP.</p>";
				}
			}
			else
			{
				$return = "<p class='alert alert-danger text-center'>Invalid OTP.</p>";
			}
		}
		else
		{
			$return = "<p class='alert alert-danger text-center'>Please enter an OTP.</p>";
		}

		return $return;
	}

	public function generateOtp()
	{
		if($this->logged_in === FALSE)
		{
			redirect(base_url() . custom_constants::login_page_url);
		}
		
		$return = '';
		$username = $this->session->userdata("username");
		//$this->load->model("mdl_login");
		$this->pktdblib->set_table("login");
		$query = $this->pktdblib->get_where_custom("username", $username);
		$mobile = '';
		$email = '';

		foreach($query->result() as $row)
		{
			$id = $row->id;
			$email = $row->email;
			$employee_id = $row->employee_id;
			$account_type = $row->account_type;
			$mobile = $this->getMobileNumber($account_type, $employee_id);
		}

		if($otp = Modules::run("notify/generateOtp", array('mobile' => $mobile, 'email' => $email)))
		{
            // $subject = "Verify account for {$username}";
            // $message = "Use below OTP code to verify your account : <br> {$otp}";
            $sms = "One Time Password for Account verification is {$otp}. Please use the password to complete the verification. Team Oxiinc Group.";

            // $emailResponse = Modules::run("notify/sendEmail", (array('email' => $email, 'subject' => $subject, 'message' => $message)));
            $smsResponse = Modules::run("notify/sendSms", (array('mobile' => $mobile, 'sms' => $sms)));

            $msg = array('message'=> 'Otp sent to your mobile number', 'class' => 'alert alert-success');
            $this->session->set_flashdata('message', $msg);
		}

		redirect('login/email_verification');
	}
	
	private function _check_email_ver_string($ver_string)
	{
		//$this->load->model("login/mdl_login");
		$this->pktdblib->set_table("login");
		//$query = $this->pktdblib->get_where_custom("username", $this->session->userdata("username"));
		$query = $this->pktdblib->get_where_custom("email_verification_link", $ver_string);
		$mobile = "";
		$email_address = "";
		$email_verification_link = "";
		$response = array();
		foreach($query->result() as $row)
		{
			$user_id = $row->id;
			$employee_id = $row->employee_id;
			$account_type = $row->account_type;
			$username = $row->username;
			$email_address = $row->email;
			$email_verification_link = $row->email_verification_link;
			$email_ver_time = $row->email_ver_time;

			$mobile = $this->getMobileNumber($account_type, $employee_id);
		}
		
		if($ver_string === $email_verification_link)
		{
			$time_elapsed = ((time() - $email_ver_time) / 60) / 60;		// In hours
			
			$valid_time = custom_constants::email_ver_string_time;
			if($time_elapsed < $valid_time)
			{
				$update_data['email_verification_link'] = NULL;
				$update_data['email_ver_time'] = NULL;
				$update_data['email_verified'] = "yes";
				$this->session->set_userdata('email_verified', TRUE);
				$this->pktdblib->set_table("login");
				$this->pktdblib->_update($user_id, $update_data);
				
				// Email verified
				return FALSE;
			}
			else
			{
				// String expired create a new one and email
				$new_email_hash_string = $this->_create_email_ver_string($username, $email_address, $mobile, TRUE);
				
				$update_data['email_verification_link'] = $new_email_hash_string;
				$update_data['email_ver_time'] = time();
				$this->pktdblib->set_table("login");
				$this->pktdblib->_update($user_id, $update_data);
				
				return "Verification link expired. New link was sent to {$email_address}. Don't forget
				to check your spam folder.";
			}
		}
		else
		{
			return "Verification link does not match. Please check the link or request a new
			link to be emailed by clicking the link below.";
		}
	}
	
	function send_new_verification_email() {
		if($this->logged_in === FALSE)
		{
			redirect(base_url() . custom_constants::login_page_url);
		}
		else
		{
			$data['logged_in'] = 'yes';
		}
		
		$username = $this->session->userdata("username");
		//$this->load->model("mdl_login");
		$this->pktdblib->set_table("login");
		$query = $this->pktdblib->get_where_custom("username", $username);
		$mobile = '';
		foreach($query->result() as $row)
		{
			$id = $row->id;
			$email = $row->email;
			$employee_id = $row->employee_id;
			$account_type = $row->account_type;

			$mobile = $this->getMobileNumber($account_type, $employee_id);
		}
		
		$new_link = $this->_create_email_ver_string($username, $email, $mobile, FALSE);
		$update_data['email_verification_link'] = $new_link;
		$update_data['email_ver_time'] = time();
		$this->pktdblib->set_table("login");
		$this->pktdblib->_update($id, $update_data);
		
		$data['email'] = $email;
		$data['meta_keyword'] = "New Email Verification Sent";
		$data['title'] = "Email verification resent";
		$data['meta_title'] = "New Email Verification Sent";
		$data['meta_description'] = "Email verification resent";
		$data['js'][] = '<script>
	        $(document).ready(function() {
	          $("#login-banner").owlCarousel({
	            // navigation : false, // Show next and prev buttons
	            slideSpeed : 300,
	            paginationSpeed : 400,
	            singleItem:true,autoPlay:3000,
	          });
	          
	        });
	    </script>';
		
		$data['modules'][] = "login";
		$data['methods'][] = "view_new_verify_email";
		
		echo Modules::run("templates/oxiinc_template", $data);
	}
	
	function change_email_address() {
		if($this->logged_in === FALSE)
		{
			redirect(base_url() . custom_constants::login_page_url);
		}
		
		$mobile = '';
		$username = $this->session->userdata("username");
		//$this->load->model("mdl_login");
		$this->pktdblib->set_table("login");
		$query = $this->pktdblib->get_where_custom("username", $username);
		foreach($query->result() as $row)
		{
			$id = $row->id;
			$email_verified = $row->email_verified;
			$data['old_email'] = $row->email;
			$employee_id = $row->employee_id;
			$account_type = $row->account_type;

			$mobile = $this->getMobileNumber($account_type, $employee_id);
		}
		
		if($email_verified === "yes")
		{
			redirect(base_url() . custom_constants::admin_page_url);
		}
		
		$data['new_email_successful'] = FALSE;
		$data['email_exists'] = FALSE;
		
		if($this->input->post('email'))
		{   //print_r($_POST);//exit;
			$this->load->library("form_validation");
			
			$data['values_posted']['email'] = $this->input->post('email');
			
			$this->form_validation->set_rules('email', 'email', 'required|max_length[320]|valid_email|matches[email_confirmation]');
			$this->form_validation->set_rules('email_confirmation', 'confirm email', 'required|max_length[320]|valid_email');
			
			if($this->form_validation->run())
			{   
			    $this->pktdblib->set_table('login');
			    //echo "raeched Here";exit;
			    //echo $this->pktdblib->count_where("email", $this->input->post("email"));exit;
				if($this->pktdblib->count_where("email", $this->input->post("email")) > 0)
				{
					$data['email_exists'] = TRUE;
					$data['values_posted']['email'] = '';
				}
				else
				{
					$update_data['email'] = $this->input->post('email');
					$data['new_email_successful'] = TRUE;
					$data['new_email'] = $this->input->post('email');
				
					$new_link = $this->_create_email_ver_string($username, $data['new_email'], $mobile, FALSE);
					$update_data['email_verification_link'] = $new_link;
					$update_data['email_ver_time'] = time();
					$this->pktdblib->set_table("login");
					$this->pktdblib->_update($id, $update_data);
				}
			}
		}
		
		$data['meta_keyword'] = "Change Email Address";
		$data['title'] = "Change Email Address";
		$data['meta_title'] = "Change Email Address";
		$data['meta_description'] = "Change your email address";
		$data['js'][] = '<script>
        $(document).ready(function() {
          $("#login-banner").owlCarousel({
            // navigation : false, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,autoPlay:3000,
          });
          
        });
      </script>';
		$data['modules'][] = "login";
		$data['methods'][] = "view_change_email_address";

		
		echo Modules::run("templates/oxiinc_template", $data);
	}
	
	private function _create_email_ver_string($username, $email, $mobile, $sendOtp='') {
		$string_pt1 = $username;
		$string_pt2 = rand(100000, 999999);

		$hash_string = sha1($string_pt1 . $string_pt2);
		
		$response = Modules::run("notify/accountVerification", array('username' => $username, 'mobile' => $mobile, 'email' => $email, 'sendotp' =>$sendOtp, 'hash_string' => $hash_string));
		
		return $hash_string;
	}
	
	function forgot_username() {
		if(custom_constants::email_login_allowed === TRUE)
		{
			redirect(base_url() . custom_constants::login_page_url);
		}
		
		if($this->logged_in === TRUE)
		{
			$data['logged_in'] = TRUE;
		}
		else
		{
			$data['logged_in'] = FALSE;
		}
		
		if($this->input->post('email'))
		{
			$data['values_posted']['email'] = $this->input->post('email');
			
			$this->load->library("form_validation");
			$this->form_validation->set_rules('email', 'email', 'required|max_length[320]|valid_email');
			
			if($this->form_validation->run())
			{
				$email = $this->input->post('email');
				$data['email_errors'] = $this->_email_credentials($email, "username");
			}
		}
		
		$data['meta_title'] = "Username Recovery";
		$data['meta_description'] = "Enter email to recover username";
		
		$data['modules'][] = "login";
		$data['methods'][] = "view_forgot_username";
		
		echo Modules::run("templates/oxiinc_template", $data);
	}
	
	private function _email_credentials($email, $type) {
		//$this->load->model("login/mdl_login");
		$this->pktdblib->set_table("login");
		
		if($this->pktdblib->count_where("email", $email) > 0)
		{
			$query = $this->pktdblib->get_where_custom("email", $email);
			foreach($query->result() as $row)
			{
				$id = $row->id;
				$first_name = $row->first_name;
				$username = $row->username;
			}
		}
		else
		{
			return "{$email} is not registered.";
		}
		
		$this->load->library('email');
			
		$config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => 'mail.expedeglobal.com',
		    'smtp_port' => 587,
		    'smtp_user' => 'emarkit@expedeglobal.com',
            'smtp_pass' => 'Mum@400064',
            'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->from(custom_constants::mailer_address, custom_constants::mailer_name);
		//$html = Modules::run('billings/load_courier_bill_pdf', base64_encode($invoiceId));
		//echo $html;exit;
		//$email = 'mailme.deepakjha@gmail.com';
		//$email = 'mailme.deepakjha@gmail.com, mr.goyalmanish@gmail.com';
		
		$this->email->to($email);
		$this->email->set_mailtype("html");
		
		$site_cn = base_url();
		
		if($type === "username")
		{			
			$this->email->subject("Forgot username request for {$username}");
			$this->email->message("Hi {$first_name}, your username is {$username}. To login paste this link into your browser {$site_cn}" . custom_constants::login_page_url);
			$this->email->send();
		}
		else if($type === "password")
		{
			$string_pt1 = $username;
			$string_pt2 = rand(100000, 999999);
			$hash_string = sha1($string_pt1 . $string_pt2);
			
			$update_data['passwd_reset_str'] = $hash_string;
			$update_data['passwd_reset_time'] = time();
			
			$this->pktdblib->_update($id, $update_data);
			/*$this->load->library('email');
			$this->email->from('primarykeytech@gmail.com', 'Primary Key Technologies');
			$this->email->to($email);*/
			$this->email->subject("Reset password request for {$username}");
			$this->email->message("Hi {$first_name}, this is an automated email sent because you requested a password reset.
			To reset your password paste this link into your browser {$site_cn}" . custom_constants::reset_password_url . "/{$hash_string}");
			$this->email->send();
 			/*echo $this->email->print_debugger();
 			exit;*/
		}
		else
		{
			echo "email_credentials type is invalid";
		}
		
		// Email successfully sent
		return FALSE;
	}
	
	function reset_password_form() {
		if($this->logged_in === TRUE)
		{
			redirect(base_url() . custom_constants::admin_page_url);
		}
		
		$data = array();
		
		if($this->input->post('email'))
		{
			$data['values_posted']['email'] = $this->input->post('email');
			
			$this->load->library("form_validation");
			$this->form_validation->set_rules('email', 'email', 'required|max_length[320]|valid_email');
			
			if($this->form_validation->run())
			{
				$email = $this->input->post('email');
				$data['email_errors'] = $this->_email_credentials($email, "password");
			}
		}
		
		$data['meta_keyword'] = "Reset password";
		$data['title'] = "Request a password reset";
		$data['meta_title'] = "Reset password";
		$data['meta_description'] = "Request a password reset";
		$data['js'][] = '<script>
        $(document).ready(function() {
          $("#login-banner").owlCarousel({
            // navigation : false, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,autoPlay:3000,
          });
          
        });
      </script>';

		$data['modules'][] = "login";
		$data['methods'][] = "view_reset_password_form";
		
		echo Modules::run("templates/login_template", $data);
	}
	
	function reset_password($verification_string = FALSE) {		
		if($this->logged_in === TRUE)
		{
			redirect(base_url() . custom_constants::admin_page_url);
		}
		
		$reset_password_form_url = base_url() . custom_constants::reset_password_form_url;
		
		if($verification_string === FALSE)
		{
			redirect($reset_password_form_url);
		}
		
		$data['new_link_sent'] = FALSE;
		$data['success_reset'] = FALSE;
		
		//$this->load->model("login/mdl_login");
		$this->pktdblib->set_table("login");
		//print_r($_POST);exit;
		if($this->pktdblib->count_where("passwd_reset_str", $verification_string) > 0)
		{
			$query = $this->pktdblib->get_where_custom("passwd_reset_str", $verification_string);
			//print_r($query->result());exit;
			foreach($query->result() as $row)
			{
				$id = $row->id;
				$username = $row->username;
				$email = $row->email;
				$passwd_reset_time = $row->passwd_reset_time;
			}
			
			$current_time = time();
			
			$time_dif = (($current_time - $passwd_reset_time) / 60) / 60;	// In hours
			$valid_time = custom_constants::passwd_reset_valid_time;
			
			if($time_dif > $valid_time)
			{
				$this->_email_credentials($email, "password");
				$data['new_link_sent'] = TRUE;
				$data['email'] = $email;
			}
		}
		else
		{
			redirect($reset_password_form_url);
		}
		
		if($this->input->post('password'))
		{ //print_r($_POST);exit;
			$data['values_posted']['email'] = $this->input->post("email");
			
			$this->load->library("form_validation");
			$this->form_validation->set_rules('email', 'email', 'required|max_length[320]|valid_email');
			$this->form_validation->set_rules('password', 'password', 'required|min_length[5]|max_length[32]|matches[password_confirmation]');
			$this->form_validation->set_rules('password_confirmation', 'confirm password', 'required|min_length[5]|max_length[32]');
			
			if($this->form_validation->run())
			{
				if($this->input->post("email") !== $email)
				{
					$data['form_errors'] = "email address does not match the address attached to this link";
				}
				else
				{					
					$password = $this->input->post("password");
					$update_data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
					$update_data['passwd_reset_str'] = NULL;
					$update_data['passwd_reset_time'] = NULL;
					
					$this->pktdblib->_update($id, $update_data);
					
					$data['success_reset'] = TRUE;
				}
			}else{
				echo validation_errors();exit;
			}
		}
		
		$data['verification_string'] = $verification_string;
		$data['meta_keyword'] = "Reset your password";
		$data['title'] = "Reset your password";
		$data['meta_title'] = "Reset your password";
		$data['meta_description'] = "Enter a new password for your account";
		$data['js'][] = '<script>
	        $(document).ready(function() {
	          $("#login-banner").owlCarousel({
	            // navigation : false, // Show next and prev buttons
	            slideSpeed : 300,
	            paginationSpeed : 400,
	            singleItem:true,autoPlay:3000,
	          });
	          
	        });
	    </script>';

		$data['modules'][] = "login";
		$data['methods'][] = "view_reset_password";
		//echo "reached here";
		echo Modules::run("templates/login_template", $data);
	}
	
	function user_logout() {
		// Destroy the session and redirect the user to the login page
		//$this->session->sess_destroy();
		session_destroy();
		redirect(base_url() . custom_constants::login_page_url);
	}
	
	function new_user() {
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data['values_posted'] = $_POST['data'];
			/*echo "<pre>";
			print_r($data['values_posted']);
			exit;*/
			if(!empty($_FILES['employee']['profile_img'])) {
			}
			//$this->load->library("form_validation");
			 $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[employees][first_name]', 'first name', 'required|max_length[255]|alpha_dash');
			$this->form_validation->set_rules('data[employees][surname]', 'surname', 'required|max_length[255]|min_length[2]|alpha_dash');
			//$this->form_validation->set_rules('data[employees][profile_img]', 'profile_img');
			$this->form_validation->set_rules('data[employees][dob]', 'dob', 'required');
			$this->form_validation->set_rules('data[employees][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][contact_2]', 'contact_2', 'required|max_length[15]|min_length[10]|numeric');
			$this->form_validation->set_rules('data[employees][primary_email]', 'primary email', 'required|max_length[255]|valid_email|is_unique[login.email]');
			$this->form_validation->set_rules('data[employees][secondary_email]', 'secondary email', 'required|max_length[255]|valid_email');
			

			if($this->form_validation->run('login')!==FALSE)
			{
				$data = $_POST;
				$err = [];
				//echo '<pre>';
				//print_r($_FILES);exit;
				if(!empty($_FILES['profile_img']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'./assets/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$post_data['profile_img'] = $profileImg['filename'];
					}
					else {
						$error['profile_img'] = $profileImg['error'];
					}
				}
				$post_data['first_name'] = $this->input->post('data[employees][first_name]');
				$post_data['surname'] = $this->input->post('data[employees][surname]');
				//$post_data['profile_img'] = $this->input->post('data[employees][profile_img]');
				$post_data['dob'] = $this->input->post('data[employees][dob]');
				$post_data['contact_1'] = $this->input->post('data[employees][contact_1]');
				$post_data['contact_2'] = $this->input->post('data[employees][contact_2]');
				$post_data['primary_email'] = $this->input->post('data[employees][primary_email]');
				$post_data['secondary_email'] = $this->input->post('data[employees][secondary_email]');
				
				$reg_user = $this->_register_new_user($post_data);
				/*echo $reg_user;
				exit;*/
				
				if($reg_user === FALSE)
				{
					// Successfully registered
					$data['primary_email'] = $this->session->userdata('primary_email');
					$data['registered'] = TRUE;
				}
				else
				{
					// Registration error
					$data['form_error'] = $reg_user;
				}
			}else {
				echo validation_errors();
			}
			/*print_r($_FILES);
			echo "<pre>";*/
			//print_r($data);exit;
		 	/*if($this->form_validation->run('login')!==FALSE){
				
			} */
		}
		/*else {
			show_404();
			exit;
		}*/
		
		$cities = $this->cities_model->get_dropdown_list();
		//print_r($cities);
		foreach ($cities as $cityKey => $city) {
			//print_r($city);
			$data['option']['cities'][$city['id']] = $city['city_name'];
		}

		$states = $this->states_model->get_dropdown_list();
		foreach($states as $stateKey => $state) {
			$data['option']['states'][$state['id']] = $state['state_name'];
		}
		/*print_r($data['option']['states']);
		exit;*/

		$countries =$this->countries_model->get_dropdown_list();
		foreach ($countries as $countryKey => $country) {
			$data['option']['countries'][$country['id']] = $country['name'];
		}
                                   
		$areas =$this->areas_model->get_dropdown_list();
		foreach ($areas as $areaKey => $area) {
			$data['option']['areas'][$area['id']] = $area['area_name'];
		}


		$data['areas'] = $this->areas_model->get_list();
		$data['states'] = $this->states_model->get_list();
		$data['countries'] = $this->countries_model->get_list();
		$data['meta_title'] = "New User";
		$data['meta_description'] = "New user registration";
		$data['modules'][] = "login";
		$data['methods'][] = "view_login_register";
		
		//echo Modules::run("templates/login_template", $data);
		echo Modules::run("templates/admin_template", $data);
			/*$data['content'] = 'login/new_user';
			$this->templates->employee_template($data);*/
	}

	private function _register_new_user($data) {
		/*echo "register user";
		exit;*/
		$this->load->model("login/mdl_login");
		$this->pktdblib->set_table("employees");
		
		if($this->pktdblib->count_where("primary_email", $data['primary_email']) > 0)
		{
			return "email is already in use";
		}
		$insert_data['first_name'] = $data['first_name'];
		$insert_data['surname'] = $data['surname'];
		$insert_data['profile_img'] = $data['profile_img'];
		$insert_data['dob'] = $data['dob'];
		$insert_data['contact_1'] = $data['contact_1'];
		$insert_data['contact_2'] = $data['contact_2'];
		$insert_data['primary_email'] = $data['primary_email'];
		$insert_data['secondary_email'] = $data['secondary_email']; 

		$this->pktdblib->set_table("employees");
		$id = $this->pktdblib->_insert($insert_data);
		//print_r($id);exit;
		$query = $this->pktdblib->get_where_custom("primary_email", $data['primary_email']);

		foreach($query->result() as $row)
		{
			$user_id = $row->id;
			$username = $row->username;
			$account_type = $row->account_type;
			if($row->email_verified === "yes")
			{
				$email_verified = TRUE;
			}
			else
			{
				$email_verified = FALSE;
			}
			if($row->mobile_verified === "yes")
			{
				$mobile_verified = TRUE;
			}
			else
			{
				$mobile_verified = FALSE;
			}
		}
		
		$session_data = array(
							'user_id' => $user_id,
							'username' => $username,
							'logged_in' => TRUE,
							'account_type' => $account_type,
							'last_activity' => time(),
							'email_verified' => $email_verified,
							'mobile_verified' => $mobile_verified,
							'logged_in_since' => time()
						);
		
		$this->session->set_userdata($session_data);
		
		// Successful registration. User is also logged in
		return FALSE;
		
		
	}

	function admin_login() {
		$this->load->view("login/login_default");
	}

	function front_login() {
		$this->load->view("login/index");
	}
	
	function view_login_register() {
		$this->load->view("login/login_register");
	}

	/*function view_login_register() {
		$this->load->view("login/admin_inner");
	}*/
	
	function view_forgot_username() {
		$this->load->view("login/forgot_username");
	}
	
	function view_reset_password_form() {
		$this->load->view("login/reset_password_form");
	}
	
	function view_reset_password() {
		$this->load->view("login/reset_password");
	}
	
	function view_verify_email() {

		$this->load->view("login/verify_email");
	}
	
	function view_new_verify_email() {
		$this->load->view("login/new_verify_email");
	}
	
	function view_change_email_address() {
		$this->load->view("login/change_email_address");
	}

	function create_username($loginId) {
        $companyId = '';
        //$this->load->model('companies/companies_model');
        $this->pktdblib->set_table("companies");
        $companyDetails = $this->pktdblib->get_where(1);
        //$username = Modules::run("companies/company_details/1");
        //$username = 'MISS';
        $username = $companyDetails['short_code']."/Cl/";
        //print_r($companyDetails['short_code']."/"."Driver");exit;
        if($loginId>0 && $loginId<=9)
            $username .= '000000';
            
        elseif($loginId>=10 && $loginId<=99)
            $username .= '00000';
        elseif($loginId>=100 && $loginId<=999)
            $username .= '0000';
        elseif($loginId>=1000 && $loginId<=9999)
            $username .= '000';
        elseif($loginId>=10000 && $loginId<=99999)
            $username .= '00';
        elseif($loginId>=100000 && $loginId<=999999)
            $username .= '0';

        $username .= $loginId;


        //echo "reached in create emp code method"; print_r($username);exit;
        return $username;
    }

    function edit_login($id=NULL, $post_data = []) {
        if(NULL == $id)
            return false;

        if($this->pktdblib->_update($id,$post_data))
            return true;
        else
            return false;
    }

    function createUserRole($data){
    	$data['created'] = date('Y-m-d H:i:s');
    	$data['modified'] = date('Y-m-d H:i:s');
    	$this->pktdblib->set_table('user_roles');
    	$roles = $this->pktdblib->_insert($data);
    	return $roles;
    }

/*    function login(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //print_r($_POST);exit;
            $data['values_posted'] = $_POST['data'];
            
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->form_validation->set_rules('data[customers][first_name]', 'first name', 'required|max_length[255]');
            $this->form_validation->set_rules('data[customers][surname]', 'surname', 'required|max_length[255]|min_length[2]');
            
            /*$this->form_validation->set_rules('data[customers][dob]', 'dob', 'required');*/
           /* $this->form_validation->set_rules('data[customers][contact_1]', 'contact_1', 'required|max_length[15]|min_length[10]|numeric');
           /* $this->form_validation->set_rules('data[customers][contact_2]', 'contact_2', 'required|max_length[15]|min_length[10]|numeric');*/
           /* $this->form_validation->set_rules('data[customers][primary_email]', 'primary email', 'required|max_length[255]|valid_email|is_unique[login.email]');
            /*$this->form_validation->set_rules('data[customers][secondary_email]', 'secondary email', 'required|max_length[255]|valid_email');*/
            /*$this->form_validation->set_rules('data[login][password]', 'Password', 'required|max_length[255]|alpha_numeric');
            $this->form_validation->set_rules('data[repassword][repassword]', 'Re-Enter Password', 'required|max_length[255]|alpha_numeric|matches[data[login][password]]');
            

            if($this->form_validation->run('login')!==FALSE)
            {
                $post_data = $_POST['data'];
                $err = [];
                //echo '<pre>';
                //print_r($_FILES);exit;
                if(!empty($_FILES['profile_img']['name'])) {
                    //echo "hii";
                    $profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'./assets/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
                    $profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
                    //print_r($profileImg);exit;
                    if(empty($profileImg['error'])) {
                        $post_data['customers']['profile_img'] = $profileImg['filename'];
                    }
                    else {
                        $error['profile_img'] = $profileImg['error'];
                    }
                }
                if(empty($error)){
                    //print_r($post_data);exit;
                    $reg_user = json_decode($this->_register_admin_add($post_data['customers']), true);
                    /*echo '<pre>';
                    print_r($reg_user);exit;*/
                    /*if($reg_user['status'] === 'success')
                    {
                        $post_data['login']['employee_id'] = $reg_user['id'];
                        $post_data['login']['account_type'] = 'Customer';
                        $post_data['login']['email'] = $post_data['customers']['primary_email'];
                        $post_data['login']['username'] = $reg_user['customers']['emp_code'];
                        $post_data['login']['first_name'] = $reg_user['customers']['first_name'];
                        $post_data['login']['surname'] = $reg_user['customers']['surname'];
                        //echo '<pre>';
                        //print_r($post_data['login']);
                        $login = Modules::run("login/_register_user", $post_data['login']);
                        //print_r($login);
                        //exit;
                        // Successfully registered
                        $data['primary_email'] = $this->session->userdata('primary_email');
                        $data['registered'] = TRUE;
                        $msg = array('message'=> 'Logged In Successfully', 'class' => 'alert alert-success');
                        $this->session->set_flashdata('message', $msg);

                        //print_r($this->session->userdata('roles'));exit;
                        if(array_key_exists('Customer', $this->session->userdata('roles'))){
                            redirect(custom_constants::customer_account);
                        }
                    }
                    else
                    {
                        $msg = array('message'=> $reg_user['msg'], 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                    }
                }else{
                    $msg = array('message'=> 'Some Error Occurred with Profile Image', 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);

                }
            }
            
        }
        
        $data['title'] = "New Customer";
        
        $data['meta_title'] = "New User";
        $data['meta_keyword'] = "Oxiinc New Customer";
        $data['meta_description'] = "New user registration";
        $data['modules'][] = "customers";
        $data['methods'][] = "add_customer";
        
        echo Modules::run("templates/obaju_inner_template", $data);
    }
*/ 

    function getMobileNumber($account_type='', $employee_id='')
    {
    	$mobile = '';
    	if($account_type == 'customers')
		{
			$this->pktdblib->set_table("customers");
			$customerQuery = $this->pktdblib->get_where_custom("id", $employee_id);

			foreach($customerQuery->result() as $customerRow)
			{
				$mobile = $customerRow->contact_1;
			}
		}
		else
		{
			if($account_type == 'employees')
			{
				$this->pktdblib->set_table("employees");
				$employeeQuery = $this->pktdblib->get_where_custom("id", $employee_id);

				foreach($employeeQuery->result() as $employeeRow)
				{
					$mobile = $employeeRow->contact_1;
				}
			}
		}

		return $mobile;
    }

    function get_username_based_data(){
        $sql = "Select concat(first_name, ' ', surname) as fullname from login where is_active=true";
        if(NULL!==$this->input->post('username'))
            $sql.=" AND username LIKE '".$this->input->post('username')."'";

        if(NULL!==$this->input->post('email'))
            $sql.=" AND email LIKE '".$this->input->post('email')."'";

        $query = $this->pktlib->custom_query($sql);
        if(!empty($query))
        	echo json_encode($query[0]['fullname']);
        else
        	echo json_encode('');
        exit;
    }

    function emailtest(){
    	//echo php_info();
    	
        $this->load->library('email');
        $config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => 'mail.expedeglobal.com',
		    'smtp_port' => 587,
		    'smtp_user' => 'emarkit@expedeglobal.com',
            'smtp_pass' => 'Mum@400064',
            'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->from(custom_constants::mailer_address, custom_constants::mailer_name);
        $this->email->set_newline("\r\n");
        
        $this->email->to('mailme.deepakjha@gmail.com');
        $this->email->subject('Test Mail');
        $this->email->message('Test');

        $this->email->send();
        //echo $this->email->print_debugger();
    }

    function register_employee_to_login($data){
    	//echo "hii i m in employee";
		$this->pktdblib->set_table("login");
		
		if($this->pktdblib->count_where("email", $data['primary_email']) > 0)
		{
			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], 'employees');
			$this->pktdblib->set_table('login');
			$login = $this->pktdblib->get_where_custom('email', $data['primary_email']);
            $loginDetails = $login->row_array();
			return json_encode(["msg"=>"Login Mapped Successfully", "status"=>"success", 'id'=>$loginDetails['id']]);
			
			//return "email is already in use";
		}

		$password = time();
		$insert_data['first_name'] = $data['first_name'];
		$insert_data['surname'] = isset($data['surname'])?$data['surname']:'';
		$insert_data['username'] = $data['primary_email'];
		$insert_data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
		$insert_data['employee_id'] = $data['id'];
		$insert_data['account_type'] = 'employees';
		$insert_data['email'] = $data['primary_email'];
		$insert_data['email_verified'] = 'yes';

		$this->pktdblib->set_table("login");
		$id = $this->pktdblib->_insert($insert_data);
		if($id['id']){
			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], 'employees');
			return json_encode(["msg"=>"Login Successfully Inserted", "status"=>"success", 'id'=>$id]);
		}else{
			return false;
		}
	}

	function mapUserRole($emailId, $userId, $loginType){
		/*echo '<pre>';
		print_r($_SESSION);
		echo '</pre>';
		echo $emailId." ".$userId." ".$loginType;*/
		$this->pktdblib->set_table('login');
		$sql = $this->pktdblib->get_where_custom('email', $emailId);
		$login = $sql->row_array();
		//print_r($login);//exit;

		$this->pktdblib->set_table('roles');
		$query = $this->pktdblib->get_where_custom('module', $loginType);
		$roles = $query->row_array();

		return $this->assignUserRole($userId, $roles['id'], $loginType, $login['id']);
	}

	function register_company_to_login($data){
		$this->pktdblib->set_table("login");
		
		if($this->pktdblib->count_where("email", $data['primary_email']) > 0)
		{
			$userroles = $this->mapUserRole($data['primary_email'], $data['id'], 'companies');
			$this->pktdblib->set_table('login');
			$login = $this->pktdblib->get_where_custom('email', $data['primary_email']);
            $loginDetails = $login->row_array();
			return json_encode(["msg"=>"Login Mapped Successfully", "status"=>"success", 'id'=>$loginDetails['id']]);
			//return "email is already in use";
		}

		$password = time();
		$insert_data['first_name'] = $data['first_name'];
		$insert_data['surname'] = $data['surname'];
		$insert_data['username'] = $data['primary_email'];
		$insert_data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
		$insert_data['employee_id'] = $data['id'];
		$insert_data['account_type'] = 'companies';
		$insert_data['email'] = $data['primary_email'];
		$insert_data['email_verified'] = 'yes';

		$this->pktdblib->set_table("login");
		$id = $this->pktdblib->_insert($insert_data);
		if($id['id']){
			
			$assignCompanyCompanies = $this->assignCompanyCompanies($data['id'], $data['id']);

			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], 'companies');
			return json_encode(["msg"=>"Login Successfully Inserted", "status"=>"success", 'id'=>$id]);
		}else{
			return false;
		}
		
	}

	function assignCompanyCompanies($parentCompanyId=NULL, $companyId=NULL){
		if($parentCompanyId==NULL || $companyId==NULL){
			return false;
		}

		$ins['parent_company_id'] = $parentCompanyId;
		$ins['company_id'] = $companyId;
		$ins['created'] = $ins['modified'] = date('Y-m-d H:i:s');
		$this->pktdblib->set_table("companies_companies");
		$query = $this->pktdblib->_insert($ins);
		if(isset($_SESSION['companies']['id'])){
			//$assignCompanyCompanies = $this->assignCompanyCompanies($_SESSION['companies']['id'], $data['id']);
			$ins['parent_company_id'] = $_SESSION['companies']['id'];
			$companyCompanies = $this->pktdblib->_insert($ins);

			if($_SESSION['companies']['id']!=1){
				$ins['parent_company_id'] = 1;
				$companyCompanies = $this->pktdblib->_insert($ins);
			}
		}
		return $query;
	}

	function assignUserRole($userId, $roleId, $userType, $loginId){
		$insert['user_id'] = $userId;
		$insert['role_id'] = $roleId;
		$insert['account_type'] = $userType;
		$insert['login_id'] = $loginId;
		$insert['created'] = date('Y-m-d H:i:s');
		$insert['modified'] = date('Y-m-d H:i:s');
		//echo 'user roles mapped<br>';
		$this->pktdblib->set_table("user_roles");
		$userRoleId = $this->pktdblib->_insert($insert);
		//print_r($userRoleId);
		return $userRoleId;
	}

	function edit_employee_login($employeeeId = ''){
		//echo $employeeId;exit;

	}

	function admin_edit_login($empId, $post_data = []) {
		$condition = [];
		$this->mdl_login->set_table("user_roles");
		$condition['user_id'] = $empId;
		$condition['role_id'] = 5; 
		$user = $this->mdl_login->get_user_details($condition);
		if(NULL == $empId)
			return false;
		$update['first_name'] = $post_data['first_name'];
		$update['surname'] = $post_data['surname'];
		$update['email'] = $post_data['primary_email'];
  		$this->pktdblib->set_table("login");
		if($this->pktdblib->_update($user['login_id'],$update))
			return true;
		else
			return false;
	}

	function view_edit_login($data = NULL) {
		/*echo '<pre>';
		print_r($data);
		exit;*/
		//echo "in login";exit;
		//echo '<pre>';
		//print_r($data);exit;
		//print_r($data['login']['id']);exit;
		//echo $id;
		/*$data['id'] = $data['login']['id'];
		//print_r($data['id']);exit;
		$data['login'] = $this->login_details($data['id']);*/
		$data['values_posted']['login'] = $data['login'];
		//print_r($data['values_posted']['login']);exit;
		$this->load->view('login/admin_edit', $data);
	}

	function admin_edit($id = NULL) {
		//echo "in Admin Edit";
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			/*echo '<pre>';
			print_r($this->input->post());exit;*/
			$data['values_posted'] = $_POST['data'];
			//print_r($data['values_posted']);
			$this->form_validation->set_rules('data[login][password_hash]', 'Password', 'required');
			$this->form_validation->set_rules('data[login][confirm_password]', 'Password Confirmation', 'required|matches[data[login][password_hash]]');


			if($this->form_validation->run('login')!== FALSE){
				$postData = $_POST['data']['login'];
				//print_r($postData['password_hash']);exit;
				$error=[];
					unset($postData['confirm_password']);
					//print_r($postData['password_hash']);exit;
					$postData['password_hash'] = password_hash($postData['password_hash'], PASSWORD_DEFAULT);
					$postData['email_verified'] = 'yes';
					//print_r($postData);exit;
				if(empty($error)) {
					$this->pktdblib->set_table("login");
					if($this->pktdblib->_update($id,$postData))
					{	//echo "update";exit;
				//echo exit;
						$msg = array('message' => 'Data Updated Successfully', 'class' => ' alert alert-success');
						$this->session->set_flashdata('message', $msg);
					} 
					else {
						$msg = array('message' => 'Some error occured while updating','class'=>'alert alert-danger');
						$this->session->set_flashdata('message', $msg);
					}
					//print_r(custom_constants::edit_product_url."/".$id);exit;
					//redirect('login/admin_edit'."/".$id);

				}
				else {
					//echo "hiii";
					$msg = array('message' => 'Some error occured','class'=>'alert alert-danger');
					$this->session->set_flashdata('message', $msg);	
				}
			} 
			else{
				$msg = array('message' => 'validation error'.validation_errors() ,'class'=>'alert alert-danger');
				$this->session->set_flashdata('message', $msg);
			}
			redirect($this->input->post('url').'?tab=login');

		}
		else {
			//$this->mdl_login->set_table("login");
			$data['login'] = $this->login_details($id);
			$data['values_posted']['login'] = $data['login'];
		}
		/*$this->mdl_login->set_table("login");
		$data['login'] = $this->login_details($id);
		$data['values_posted']['login'] = $data['login'];*/
		$data['meta_title'] = 'Login Edit';
		$data['meta_description'] = 'Edit Login';
		$data['meta_keyword'] = 'Edit Login';
		$data['modules'][] = 'login';
		$data['methods'][] = 'edit_login_details';
		//print_r($data);exit;
		//$data['content'] = 'login/admin_edit';
		echo Modules::run("templates/admin_template", $data);
		/*$data['id'] = $id;
		$this->load->view('login/admin_edit',$data);*/
	}

	function edit_login_details($data = NULL) {
		//echo '<pre>';print_r($data);exit;
		$data['id'] = $this->uri->segment(3);
		/*print_r($data['id']);
		exit;*/
		/*$this->mdl_login->set_table("login");

			$data['login'] = $this->login_details($data['user']['login_id']);
			$data['values_posted']['login'] = $data['login'];*/
		//echo '<pre>';print_r($data);exit;
		//$data['values_posted']['login'] = $data['login'];

		$this->load->view('login/admin_edit', $data);

		//$this->load->view('login/admin_edit', $data);
	}

	function login_details($id) {
		$this->pktdblib->set_table('login');
		$loginDetails = $this->pktdblib->get_where($id);
		return $loginDetails/*->row_array()*/;
		
	}

	function register_customer_to_login($data){
		//echo "hii i m in customer";
		//echo '<pre>';print_r($data);exit;
	    $this->pktdblib->set_table("login");
		
		if($this->pktdblib->count_where("email", $data['primary_email']) > 0)
		{ //echo "hello";
			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], 'customers');
			$this->pktdblib->set_table('login');
			$login = $this->pktdblib->get_where_custom('email', $data['primary_email']);
            $loginDetails = $login->row_array();
			return json_encode(["msg"=>"Login Mapped Successfully", "status"=>"success", 'id'=>$loginDetails['id']]);
			//return "email is already in use";
		}

		$password = time();
		$insert_data['first_name'] = $data['first_name'];
		$insert_data['surname'] = isset($data['surname'])?$data['surname']:'';
		$insert_data['username'] = $data['contact_1'];
		$insert_data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
		$insert_data['employee_id'] = $data['id'];
		$insert_data['account_type'] = 'customers';
		$insert_data['email'] = $data['primary_email'];
		$insert_data['email_verified'] = 'yes';

		$this->pktdblib->set_table("login");
		$id = $this->pktdblib->_insert($insert_data);
		if($id['id']){
			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], 'customers');
			return json_encode(["msg"=>"Login Successfully Inserted", "status"=>"success", 'id'=>$id]);
		}else{
			return false;
		}
	}

	function get_typewise_user_role($data = []){
		//print_r($data);exit;
		$userRoles = $this->pktdblib->createquery(['conditions'=>['user_id' => $data['user_id'], 'account_type'=>$data['account_type'], 'is_active'=>true], 'table'=>'user_roles']);
		return $userRoles;
	}

	function admin_index_2() {
		$data['meta_title'] = 'login listing';
		$data['meta_description'] = 'login Details';
		$data['modules'][] = 'login';
		$data['methods'][] = 'admin_login_listing';
		echo Modules::run("templates/admin_template", $data);
	}

	function admin_login_listing($data = []) {
		$data['login'] = $this->mdl_login->login_listing('modified desc, is_active desc');
		$this->load->view("login/admin_login_listing", $data);
	}

	function admin_edit_user($id){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			// print_r($this->input->post('data[user_roles][id]'));exit;
			$data['values_posted'] = $this->input->post('data');
			
			$this->form_validation->set_rules('data[login][first_name]', 'First Name', 'required');
			$this->form_validation->set_rules('data[login][surname]', 'Last Name', 'required');
			$this->form_validation->set_rules('data[login][username]', 'Username', 'required');
			$this->form_validation->set_rules('data[login][password]', 'Password', 'required');
			$this->form_validation->set_rules('data[password]', 'Re-Password', 'required|matches[data[login][password]]');

			if($this->form_validation->run('login')!==FALSE)
			{
				$update_data['first_name'] = $this->input->post()['data']['login']['first_name'];
				$update_data['surname'] = isset($this->input->post()['data']['login']['surname'])?$this->input->post()['data']['login']['surname']:'';
				$update_data['username'] = $this->input->post()['data']['login']['username'];
				$update_data['is_active'] = (NULL !== $this->input->post('data[login][is_active]')) ? 1 : 0;
				if(isset($this->input->post()['data']['login']['password'])){
					$password = (string)$this->input->post()['data']['login']['password'];
					$update_data['password_hash'] = password_hash($password,  PASSWORD_DEFAULT);//password_hash($password, PASSWORD_DEFAULT);
				}
				$update_data['employee_id'] = $id;
				$update_data['email'] = $this->input->post()['data']['login']['email'];
				$update_data['email_verified'] = 'yes';
				$this->pktdblib->set_table("login");
				// print_r($update_data);exit;
				$this->pktdblib->_update($id, $update_data);
				if(NULL !== $this->input->post('data[user_roles]'))
				{
					$role_data	= $this->input->post('data[user_roles]');
					
					$this->pktdblib->set_table("user_roles");
					$this->pktdblib->_update($this->input->post('data[user_roles][id]'), $role_data);
				}
				redirect('login/adminindex2');

			}else {
				if(!$this->input->is_ajax_request()){
                    $msg = array('message'=> 'validation Error Occured '.validation_errors(), 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                }else{
                    echo json_encode(['status'=>0, 'msg'=>'validation Error Occured '.validation_errors()]);
                    exit;
                }
    			//exit;
			}
		
		}else{
			$this->pktdblib->set_table("login");
			$data['login'] = $this->login_details($id);
			//print_r($data['login']);
			$data['values_posted']['login'] = $data['login'];
		}

		$data['user_roles'] = $this->pktdblib->custom_query('SELECT id, role_id FROM user_roles WHERE login_id = '.$id.' AND account_type = "employees"');
		
		$this->pktdblib->set_table("roles");
        $data['role'] = $this->pktdblib->custom_query('Select * from roles where module="employees" and is_active=true');
        $data['option']['role'] = [];
        $data['option']['role'] = [''=>'Select Role'];
        foreach ($data['role'] as $roleKey => $role) {
        	if($role['id']!=1)
        	$data['option']['role'][$role['id']] = $role['role_name'];
        }
		$data['id'] = $id;
		if(!($this->input->get('tab')))
			$data['tab'] = 'personal_info';
		else
			$data['tab'] = $this->input->get('tab');
		$data['meta_title'] = 'login listing';
		$data['meta_description'] = 'login Details';
		$data['modules'][] = 'login';
		$data['methods'][] = 'edit_login_view';
		echo Modules::run("templates/admin_template", $data);
	}

	function edit_login_view(){
		$this->load->view("login/edit_login");
	}

	function register_user() {
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data['values_posted'] = $_POST['data'];
			
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
			$this->form_validation->set_rules('data[login][first_name]', 'first name', 'required|max_length[255]|alpha_dash');
			$this->form_validation->set_rules('data[login][surname]', 'surname', 'required|max_length[255]|min_length[2]|alpha_dash');
			$this->form_validation->set_rules('data[login][email]', 'primary email', 'required|max_length[255]|valid_email|is_unique[login.email]');
			

			if($this->form_validation->run('login')!==FALSE)
			{
				$data = $_POST;
				$err = [];
				if(!empty($_FILES['profile_img']['name'])) {
					$profileFileValidationParams = ['file' =>$_FILES['profile_img'], 'path'=>'..content/uploads/profile_images/','ext'=>'gif|jpg|png|jpeg', 'fieldname'=>'profile_img', 'arrindex'=>'profile_img'];
					$profileImg = $this->pktlib->upload_single_file($profileFileValidationParams);
					//print_r($profileImg);exit;
					if(empty($profileImg['error'])) {
						$post_data['profile_img'] = $profileImg['filename'];
					}
					else {
						$error['profile_img'] = $profileImg['error'];
					}
				}
				else{
					$post_data['profile_img'] = 'default-user.png';
				}
				
				$post_data = $this->input->post('data');
				$reg_user = json_decode($this->_register_new_user_role_wise($post_data), true);
				//print_r($reg_user);exit;
				if($reg_user['status'] === 'success') {
                    if(!$this->input->is_ajax_request()){
                    		$msg = array('message'=> $reg_user['msg'], 'class' => 'alert alert-success', 'employees'=>$reg_user['employees']);
                            $this->session->set_flashdata('message',$msg);
                            redirect(custom_constants::new_login_url);
                        }else{
                            echo json_encode(['status'=>1, 'value'=>[0=>['id'=>$reg_user['employees']['id'], 'text'=>$reg_user['employees']['first_name']." ".$reg_user['employees']['middle_name']." ".$reg_user['employees']['surname']]], 'msg'=>'User Created Successfully']);
                            exit;
                        }
                }
                else {
                    $data['form_error'] = $reg_user['msg'];
                    if(!$this->input->is_ajax_request()){
                        $msg = array('message'=> 'Following Error Occurred '.$reg_user['msg'], 'class' => 'alert alert-danger');
                        $this->session->set_flashdata('message', $msg);
                    }else{
                        echo json_encode(['status'=>0, 'msg'=>'Error while uploading file'.$reg_user['msg']]);
                        exit;
                    }
                }
			}else {
				if(!$this->input->is_ajax_request()){
                    $msg = array('message'=> 'validation Error Occured '.validation_errors(), 'class' => 'alert alert-danger');
                    $this->session->set_flashdata('message', $msg);
                }else{
                    echo json_encode(['status'=>0, 'msg'=>'validation Error Occured '.validation_errors()]);
                    exit;
                }
    			//exit;
			}
		}
        
		$data['meta_title'] = "New User";
		$data['meta_description'] = "New user registration";
		$data['modules'][] = "login";
		$data['methods'][] = "register_new_user";
		
		echo Modules::run("templates/admin_template", $data);
	}

	function register_new_user() {
		$data['option']['company'] = json_decode(Modules::run('companies/get_dropdown_list'), true);
		$this->pktdblib->set_table("roles");
        $data['role'] = $this->pktdblib->custom_query('Select * from roles where module="employees" and is_active=true');
        $data['option']['role'] = [];
        $data['option']['role'] = [''=>'Select Role'];
        foreach ($data['role'] as $roleKey => $role) {
        	if($role['id']!=1)
        	$data['option']['role'][$role['id']] = $role['role_name'];
        }
		$this->load->view('login/register_backend_user', $data);
	}

	private function _register_new_user_role_wise($data) {
		$insertLogin = [];
		
		$insertUserRole = []; 
		$this->pktdblib->set_table("login");
		if($this->pktdblib->count_where("email", $data['login']['email']) > 0)
		{
			//return "email is already in use";
			echo json_encode(["msg" => "Email is already in use", "status" => "failed"]);
			exit;
		}
		$type = [];
		$insertLogin['first_name'] = $data['login']['first_name'];
		$insertLogin['surname'] = $data['login']['surname'];
		$insertLogin['email'] = $data['login']['email'];
		$insertLogin['username'] = $data['login']['email'];
		$insertLogin['created'] = date('Y-m-d H:i:s');
		$insertLogin['modified'] = date('Y-m-d H:i:s');
		$insertLogin['email_verified'] = 'yes';
		$insertLogin['password_hash'] = password_hash($data['login']['password'], PASSWORD_DEFAULT);
		$newLogin = $this->pktdblib->_insert($insertLogin);
		$userType = [];
		$id=0;
		
		foreach ($data['user_roles']['role_id'] as $roleKey => $role) {
			//print_r($userType);
			//echo array_key_exists($role, $userType);
			$this->pktdblib->set_table("roles");
			$type = $this->pktdblib->get_where($role);
			if(FALSE === array_key_exists($type['module'], $userType)){
				$insert = [];
				//print_r($userType);
				$insert['first_name'] = $data['login']['first_name'];
				$insert['middle_name'] = $data['login']['middle_name'];
				$insert['surname'] = $data['login']['surname'];
				$insert['contact_1'] = $data['employees']['contact_1'];
				$insert['primary_email'] = $data['login']['email'];
				$insert['created'] = date('Y-m-d H:i:s');
				$insert['modified'] = date('Y-m-d H:i:s');
				
				$insert['emp_code'] = !empty($data['emp_code'])?$data['emp_code']:NULL;
				$insert['allow_login'] = TRUE;
				$this->pktdblib->set_table($type['module']);
				$new = $this->pktdblib->_insert($insert);
				$id = $new['id'];
				//print_r($new);exit;
    			if(empty($data['emp_code'])){
    				$code = Modules::run($type['module'].'/create_code', $new['id']);
    				//print_r($code);exit;
    				$this->pktdblib->set_table($type['module']);
    				$this->pktdblib->_update($new['id'], ['emp_code'=>$code]);
    			}
				if($_SESSION['application']['multiple_company']){

					foreach ($this->input->post('data[companies_employees][company_id]') as $key => $company) {
						if($type['module']=='companies'){
							//$
						}elseif($type['module']=='employees'){
							$query = $this->pktdblib->custom_query('Insert into companies_'.$type['module'].' (employee_id, company_id, created, modified) VALUES ("'.$new['id'].'", "'.$company.'", "'.date('Y-m-d H:i:s').'", "'.date('Y-m-d H:i:s').'")');
						}elseif($type['module']=='customers'){
							$query = $this->pktdblib->custom_query('Insert into companies_'.$type['module'].' (customer_id, company_id, created, modified) VALUES ("'.$new['id'].'", "'.$company.'", "'.date('Y-m-d H:i:s').'", "'.date('Y-m-d H:i:s').'")');
						}

					}
				}
				$userType[$type['module']]['module'] = $type['module'];
				$userType[$type['module']]['user_id'] = $new['id'];
			}
			//print_r($userType);exit;
			$insertUserRole[$roleKey]['user_id'] = $userType[$type['module']]['user_id'];
			$insertUserRole[$roleKey]['role_id'] = $role;
			$insertUserRole[$roleKey]['account_type'] = $userType[$type['module']]['module'];
			$insertUserRole[$roleKey]['login_id'] = $newLogin['id'];
			$insertUserRole[$roleKey]['created'] = date('Y-m-d H:i:s');
			$insertUserRole[$roleKey]['modified'] = date('Y-m-d H:i:s');
		}
		//print_r($insertUserRole);exit;
		$this->pktdblib->set_table('employees');
		$employees = $this->pktdblib->get_where($id);

		if(!empty($insertUserRole)){
			$this->pktdblib->set_table("user_roles");
			$query = $this->pktdblib->_insert_multiple($insertUserRole);
			if($query){
				return json_encode(["msg" => "User has been created and Role has been allocated successfully", "status" => "success", 'employees'=>$employees]);
			}
		}
		// Successful registration. User is also logged in
		return json_encode(["msg" => "User Created Successfully but failed to assign role", "status" => "success", 'employees'=>$employees]);
	}

	function register_vendor_to_login($data){
		//echo "hii i m in vendor";
		$this->pktdblib->set_table("login");
		
		if($this->pktdblib->count_where("email", $data['primary_email']) > 0)
		{
			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], $data['account_type']);
			$this->pktdblib->set_table('login');
			$login = $this->pktdblib->get_where_custom('email', $data['primary_email']);
            $loginDetails = $login->row_array();
			return json_encode(["msg"=>"Login Mapped Successfully", "status"=>"success", 'id'=>$loginDetails['id']]);
			//return "email is already in use";
		}

		$password = time();
		$insert_data['first_name'] = $data['first_name'];
		$insert_data['surname'] = isset($data['surname'])?$data['surname']:'';
		$insert_data['username'] = $data['primary_email'];
		$insert_data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
		$insert_data['employee_id'] = $data['id'];
		$insert_data['account_type'] = $data['account_type'];
		$insert_data['email'] = $data['primary_email'];
		$insert_data['email_verified'] = 'no';

		$this->pktdblib->set_table("login");
		$id = $this->pktdblib->_insert($insert_data);
		if($id['id']){
			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], $data['account_type']);
			return json_encode(["msg"=>"Login Successfully Inserted", "status"=>"success", 'id'=>$id]);
		}else{
			return false;
		}
	}

	function register_broker_to_login($data){
		//echo "hii i m in broker";
		$this->pktdblib->set_table("login");
		
		if($this->pktdblib->count_where("email", $data['primary_email']) > 0)
		{
			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], $data['account_type']);
			$this->pktdblib->set_table('login');
			$login = $this->pktdblib->get_where_custom('email', $data['primary_email']);
            $loginDetails = $login->row_array();
			return json_encode(["msg"=>"Login Mapped Successfully", "status"=>"success", 'id'=>$loginDetails['id']]);
			//return "email is already in use";
		}

		$password = time();
		$insert_data['first_name'] = $data['first_name'];
		$insert_data['surname'] = isset($data['surname'])?$data['surname']:'';
		$insert_data['username'] = $data['primary_email'];
		$insert_data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
		$insert_data['employee_id'] = $data['id'];
		$insert_data['account_type'] = $data['account_type'];
		$insert_data['email'] = $data['primary_email'];
		$insert_data['email_verified'] = 'no';

		$this->pktdblib->set_table("login");
		$id = $this->pktdblib->_insert($insert_data);
		if($id['id']){
			$userRoles = $this->mapUserRole($data['primary_email'], $data['id'], $data['account_type']);
			return json_encode(["msg"=>"Login Successfully Inserted", "status"=>"success", 'id'=>$id]);
		}else{
			return false;
		}
	}
	
	function persistSession(){
	    
	    check_user_login(FALSE);
	    return json_encode(['msg'=>'reached in persist session', 'status'=>'success']);
	}
}
