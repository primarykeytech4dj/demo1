<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 |	CONTROLLER SUMMARY AND DATABASE TABLES
 |--------------------------------------------------------------------------
 | 
 |	This is where you can start your admin/manage/password protected stuff.
 | 
 |	Database table structure
 |
 |	Table name(s) - no tables
 |
 |
 */
 
 
class Search extends MY_Controller {
	function __construct() {

		parent::__construct();

        
        // Check login and make sure email has been verified
        foreach(custom_constants::$protected_pages as $page)
        {   
            if(strpos($this->uri->uri_string, $page) === 0)
            {   
                check_user_login(FALSE);
            }
        }
        
	}

    function index_listing() {
        
        $this->load->view("search/index");
        //$this->load->view("admin_panel/login_register");
    }

    function index() {

        //print_r($this->input->post());
        $sql = 'Select distinct products.id, products.product as title, (select product_images.image_name_1 from product_images where product_id=products.id and product_images.featured_image=true limit 1) as thumbnail_image, products.slug, products.meta_title, products.meta_keyword, products.meta_description, "products" as section from products inner join product_details on product_details.product_id=products.id and product_details.in_stock_qty>0';
        $chkTbl = $this->pktdblib->custom_query('SHOW TABLES LIKE "companies_products"');
        //print_r($chkTbl);exit;
        if(count($chkTbl)>0){
            $sql.=' inner join companies_products on companies_products.product_id=products.id AND companies_products.company_id='.custom_constants::company_id;
        }

        if(NULL!==$this->input->post('search')){
            $sql.=" AND products.product like '%".$this->input->post('search')."%' OR products.meta_keyword like '%".$this->input->post('search')."%' OR products.meta_description like '%".$this->input->post('search')."%'";
        }

        $sql.=' where products.is_active=true and products.show_on_website=true';

        /*$sql.=' UNION Select product_categories.category_name, product_categories.image_name_1 as thumbnail_image, product_categories.slug, product_categories.meta_title, product_categories.meta_keyword, product_categories.meta_description, "product_categories" as section from product_categories';
        $chkTbl = $this->pktdblib->custom_query('SHOW TABLES LIKE "companies_product_categories"');
        //print_r($chkTbl);exit;
        if(count($chkTbl)>0){
            $sql.=' inner join companies_product_categories on companies_product_categories.product_category_id=product_categories.id AND companies_product_categories.company_id='.custom_constants::company_id;
        }
        $sql.=' where product_categories.is_active=true';
        if(NULL!==$this->input->post('search')){
            $sql.=" AND product_categories.category_name like '%".$this->input->post('search')."%' OR product_categories.meta_keyword like '%".$this->input->post('search')."%' OR product_categories.meta_description like '%".$this->input->post('search')."%'";
        }*/
        //echo $sql;
        $query = $this->pktdblib->custom_query($sql);
        
        $data['search'] = $query;
        $data['meta_title'] = "Search";
        $data['meta_keyword'] = "Search";
        $data['title'] = "Search";
        $data['meta_description'] = "Search";
        
        $data['modules'][] = "search";
        $data['methods'][] = "index_listing";
        $data['param'] = (NULL!==$this->input->post('search'))?$this->input->post('search'):'';
        
        echo Modules::run("templates/default_template", $data);
    }

}
