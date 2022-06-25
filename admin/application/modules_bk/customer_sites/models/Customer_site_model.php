<?php 
if(!defined('BASEPATH'))exit('No direct script access allowed');

class Customer_site_model extends CI_Model{
	private $database;
	private $table;

	function __constuct() {
		parent:: __constuct();
		$this->database = $this->load->database('login', TRUE);
	}

	function check_table($table=''){
        if(empty($table))
            return FALSE;

        $query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
        $res = $query->row_array();

        return $res;
    }

    function tbl_customer_sites(){
        $check = $this->check_table('customer_sites');
        //print_r($check);exit;
        if(!$check){
            //echo "table does not exists<br>";
            $query = $this->db->query("CREATE TABLE IF NOT EXISTS `customer_sites` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_id` int(11) NOT NULL,
				`address_id` int(11) NOT NULL,
				`first_name` varchar(255) NOT NULL,
				`middle_name` varchar(255) NOT NULL,
				`surname` varchar(255) NOT NULL,
				`primary_email` varchar(255) NULL,
				`secondary_email` varchar(255) NOT NULL,
				`contact_1` varchar(12) NOT NULL,
				`contact_2` varchar(12) NOT NULL,
				`blood_group` varchar(3) NOT NULL,
				`profile_img` varchar(255) NOT NULL,
				`site_code` varchar(255) NOT NULL,
				`service_charge_type` enum('PERCENT','VALUE','','') NOT NULL DEFAULT 'PERCENT',
				`service_charge` float(10,2) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
            );

        if($query){
            
			$customerSiteBills = $this->db->query("CREATE TABLE IF NOT EXISTS `customer_site_bills` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`invoice_no` varchar(60) DEFAULT NULL,
				`bill_from_date` datetime NOT NULL,
				`bill_to_date` datetime NOT NULL,
				`customer_site_id` int(11) NOT NULL,
				`area_id` int(11) NOT NULL,
				`amount_before_tax` float(10,2) NOT NULL,
				`amount_after_tax` float(10,2) NOT NULL,
				`service_charge_type` enum('PERCENT','VALUE','','') NOT NULL DEFAULT 'PERCENT',
				`service_charge` float(10,2) NOT NULL,
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id),
				UNIQUE KEY `invoice_no` (`invoice_no`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);

			$customerSiteBillDetails = $this->db->query("CREATE TABLE IF NOT EXISTS `customer_site_bill_details` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_site_bill_id` int(11) NOT NULL,
				`bill_from_date` date NOT NULL,
				`bill_to_date` date NOT NULL,
				`product_id` int(11) NOT NULL,
				`cost` int(11) NOT NULL,
				`no_of_labour` int(11) NOT NULL,
				`night_shift_labour_count` int(11) NOT NULL,
				`no_of_shift` int(11) NOT NULL,
				`no_of_days` int(11) NOT NULL,
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
			");

			$customerSiteServices = $this->db->query("CREATE TABLE IF NOT EXISTS `customer_site_services` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_site_id` int(11) NOT NULL,
				`product_id` int(11) NOT NULL,
				`start_date` date NOT NULL,
				`end_date` date NOT NULL,
				`no_of_labour` int(11) NOT NULL,
				`night_shift_labour_count` int(11) NOT NULL,
				`no_of_shift` int(11) NOT NULL,
				`cost` int(11) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`created` datetime NOT NULL,
				`modified` datetime NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
			);
        }
            return $query;
        }
        else
            return TRUE;
        
    }

	function get_dropdown_list() {
		$condition['is_active'] = true;
		$this->db->select('id', 'blood_group');
		$query = $this->db->get_where('blood_group', $condition);
		return $query->result_array();
	}

	function get_sites_list($conditions){
		//print_r($conditions);
    	$db = $this->database;
		//$table = $this->get_table();
		//print_r($table);
		$this->db->select("customer_sites.id, customer_sites.customer_id, Concat(customers.first_name, ' ', customers.middle_name, ' ', customers.surname) as fullname, customer_sites.first_name, customers.company_name, Concat(customer_sites.first_name, ' ', customer_sites.middle_name, ' ', customer_sites.surname) as contactfullname, customer_sites.primary_email, customer_sites.secondary_email, customer_sites.contact_1, customer_sites.contact_2, address.site_name, customer_sites.profile_img, customer_sites.site_code, customer_sites.is_active, areas.area_name");
		$this->db->join('customers','customers.id = customer_sites.customer_id');
		$this->db->join('address','address.id = customer_sites.address_id');
		$this->db->join('cities','cities.id = address.city_id', 'left');
		$this->db->join('states','states.id = address.state_id', 'left');
		$this->db->join('areas','areas.id = address.area_id', 'left');
		$this->db->join('countries','countries.id = address.country_id', 'left');
		foreach ($conditions as $key => $condition) {
			$this->db->where($key, $condition);
		}

		$this->db->order_by('is_active desc, id desc');
		//$this->db->where('address.type', $userType);
		$query=$this->db->get('customer_sites');
		//print_r($db->last_query());
		return $query->result_array();
    }

    function get_site_service_list(){
    	$this->db->select(['css.*', 'concat(cs.first_name," ", cs.middle_name, " ", cs.surname) as delivery_person', 'cs.primary_email', 'cs.contact_1', 'cs.secondary_email', 'cs.contact_2', 'cs.service_charge', 'cs.service_charge_type', 'concat(c.first_name, " ", c.middle_name, " ", c.surname) as client', 'c.contact_1 as client_contact','c.company_name as client_company', 'a.site_name', 'cs.customer_id', 'p.product']);
    	$this->db->join('customer_sites cs', 'cs.id=css.customer_site_id');
    	$this->db->join('customers c', 'c.id=cs.customer_id');
    	$this->db->join('address a', 'a.id=cs.address_id');
    	$this->db->join('products p', 'p.id=css.product_id');
    	$this->db->order_by('css.is_active desc, css.modified desc');
    	$query = $this->db->get('customer_site_services css');
    	return $query->result_array();
    }

}
