<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies_Model extends CI_Model {

	private $database;
	private $table;

	function __construct(){
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

    function tbl_companies(){
    	$check = $this->check_table('companies');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `companies` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`company_name` varchar(255) NOT NULL,
				`short_code` char(10) NOT NULL,
				`slug` varchar(255) DEFAULT NULL,
				`logo` varchar(255) NOT NULL,
				`first_name` varchar(255) NOT NULL,
				`middle_name` varchar(255) NOT NULL,
				`surname` varchar(255) NOT NULL,
				`primary_email` varchar(255) NOT NULL,
				`secondary_email` varchar(255) NOT NULL,
				`contact_1` varchar(255) NOT NULL,
				`contact_2` varchar(255) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				`meta_keyword` varchar(160) NOT NULL,
				`meta_title` varchar(160) NOT NULL,
				`meta_description` varchar(160) NOT NULL,
				`website` varchar(255) NOT NULL,
				`pan_no` varchar(255) NOT NULL,
				`gst_no` varchar(255) NOT NULL,
				`about_company` longtext NOT NULL,
				PRIMARY KEY (id),
				UNIQUE KEY (slug),
				UNIQUE KEY (website)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

			$tbl = $this->db->query("CREATE TABLE IF NOT EXISTS `companies_companies` (
				`parent_company_id` int(11) NOT NULL,
				`company_id` int(11) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

			$insert = $this->db->query("INSERT INTO `companies` (`id`, `company_name`, `short_code`, `slug`, `logo`, `first_name`, `middle_name`, `surname`, `primary_email`, `secondary_email`, `contact_1`, `contact_2`, `is_active`, `created`, `modified`, `meta_keyword`, `meta_title`, `meta_description`, `website`, `about_company`) VALUES
(1, 'Primary Key Technologies', 'pkt', 'primary-key-technologies', 'default-user.png', 'Primary', 'Key', 'Technologies', 'primarykeytech@gmail.com', 'support@primarykey.in', '9920758818', '9920758818', 1, '".date('Y-md H:i:s')."', '".date('Y-md H:i:s')."', 'Software, website, Customized erp solutions, CRM solutions', 'Primary Key Technologies', 'Software and website development company located in Mumbai India', 'www.primarykey.in', '<h2 style=\"text-align:center\">Coming Soon</h2>\r\n')");

			$companyCompanies = $this->db->query("Insert into companies_companies (parent_company_id, company_id, created, modified) VALUES(1,1,'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");
    		return $query;
    	}
    	else
    		return TRUE;	
    }
}