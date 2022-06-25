<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blogs extends MY_Controller {

	function __construct() {
		parent::__construct();
		
		// Check login and make sure email has been verified
		//check_user_login(TRUE);
		$this->load->model('blogs/blogs_model');
		$this->load->library('pktlib');
        //$this->session->set_userdata(array('menu' => 'Blogs'));
	}

    function index($data = NULL) {
        
        $data['meta_title'] = "blogs";
        $data['title'] = "Blogs";
        $data['meta_description'] = "Property Related latest updates";
        $data['meta_keyword'] = "Property, Property in Nalasopara and Virar";
        $data['modules'][] = "blogs";
        $data['methods'][] = "blog_listing";
        
        echo Modules::run("templates/default_template", $data);
    }

    function blog_listing($data=[]){
        /*$condition = '';
        //print_r($data);
        if(isset($data['condition']))
            $condition[] = $data['condition'];*/
    	//$this->pktdblib->set_table("blogs");
        //$blog = $this->blogs_model->get_blogs_list('is_active',true);
        //$data['blogs'] = $blog->result_array();
        $data['blogs'] = $this->category_wise_blogs_listing($data);
       // print_r($data['blogs']);exit;
        $this->load->view('blogs/index', $data);
    }

    function category_wise_blogs_listing($data = []){
        //print_r($data);
        /*$condition = [];
        if(isset($data['condition']))
            $condition = $data['condition'];*/


        $this->blogs_model->set_table('blogs');
        $res = $this->blogs_model->get_blogs_list($data);
        //print_r($res);exit;
        return $res;
    }

    function view($slug = NULL) {
       //print_r($slug);exit;
       if(NULL===$slug){
        redirect(custom_constants::not_found);
       }
       $slug = rawurldecode($slug);
        $this->blogs_model->set_table('blogs');
        //$blogs = $this->blogs_model->get_where_custom('slug', $slug);
        $blogs = $this->blogs_model->get_categories_wise_blogs('blogs.slug', $slug);
        //print_r($blogs);
        if(empty($blogs)){
            redirect(custom_constants::not_found);
        }
        $data['blogs'] = (isset($blogs))?$blogs[0]:[];

        $previous = $this->blogs_model->get_section_wise_blogs(['condition'=>['blogs.id<'=>$data['blogs']['id']], 'limit'=>1, 'order'=>'id desc']);
        $data['previous'] = isset($previous[0])?$previous[0]:[];

        $next = $this->blogs_model->get_section_wise_blogs(['condition'=>['blogs.id>'=>$data['blogs']['id']], 'limit'=>1, 'order'=>'id asc']);
        $data['next'] = isset($next[0])?$next[0]:[];
        //print_r($next);exit;
        $data['meta_title'] = $data['blogs']['meta_title'];
        $data['meta_keyword'] = $data['blogs']['meta_keyword'];
        $data['title'] = $data['blogs']['title'];
        $data['meta_description'] = $data['blogs']['meta_description'];
        $data['meta_image'] = 'uploads/blogs/'.$data['blogs']['featured_image'];
        $data['relatedBlogs'] = Modules::run('blogs/blog_listing', ['condition'=>['blogs.blogs_category_id'=>$data['blogs']['blogs_category_id'], 'blogs.id <>'=>$data['blogs']['id']], 'limit'=>6]);
        //print_r($data['relatedBlogs']);exit;
        $data['modules'][] = "blogs";
        $data['methods'][] = "blogs_listing";
        $updNews = $this->pktlib->custom_query('UPDATE blogs set read_count=read_count+1 where slug="'.$slug.'"');
        $format = $this->config->item('response_format' );
        //print_r($format);
        echo Modules::run("templates/default_template", $data);
    }


    function blogs_listing() {
        
        $this->load->view("blogs/view");
        //$this->load->view("admin_panel/login_register");
    }

    function get_blogs_details($id) {
        $this->pktdblib->set_table('blogs_categories');
        $data['blogs_category'] = $this->pktdblib->get_where($id);
        return $data;
    }

    function related_blogs($slug='')
    {
        $slug = rawurldecode($slug);
        if($slug)
        {
            $limit = 15;
            $offset = 0;
            $this->blogs_model->set_table('blogs_categories');
            $blogs = $this->blogs_model->get_category_wise_blogs(['condition'=>['blogs_categories.slug'=>$slug],'limit'=>$limit, 'offset'=>$offset]);
            $this->blogs_model->set_table("blogs");
            $section1Blogs = $this->blogs_model->get_categories_wise_blogs('blogs_categories.slug', $slug);
            $category = [];
            foreach ($section1Blogs as $blogsKey => $blogsValue) {
                $category[$blogsValue['category_name']][] = $blogsValue;
            }
            $data['category'] = $category;
            $data['blogs'] = $blogs;
            
            $this->pktlib->parseOutput($this->config->item('response_format'), $data);
            $this->load->view('blogs/related_blogs', $data);
        }
    }
    
}
