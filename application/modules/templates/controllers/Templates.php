<?php



// If access is requested from anywhere other than index.php then exit

if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/*

 |--------------------------------------------------------------------------

 |	CONTROLLER SUMMARY AND DATABASE TABLES

 |--------------------------------------------------------------------------

 | 

 |	Templates is used to put together the main structure of the HTML view. It

 |	calls head, header, content and footer in most cases. Other items can been

 |	called and used. Each part can be dynamic but content is loaded through

 |	modules and methods.

 |

 |	Database table structure

 |

 |	No table

 |

 */



class Templates extends MY_Controller

{

	private $meta_module;

	private $id;

	private $templateType;



	function __construct() {

		parent::__construct();

		$this->id = custom_constants::company_id;

		$this->templateType = 'login';
		$this->load->library('pktlib');

	}

	

	function default_template($data) {
		//print_r($data);exit;
		$template = $this->pktdblib->custom_query("select template from templates where type='frontend' and is_active=true");
		$meta['meta_title'] = $data['meta_title'];

		$meta['meta_description'] = $data['meta_description'];
		$meta['meta_keyword'] = $data['meta_keyword'];

		$companyData = ['id'=>$this->id];

		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);
		//$data['title'] = $data['websiteInfo']['company_name'];
		//$data['categories'] = Modules::run('products/get_category_list');
			//echo '<pre>';print_r($data['categories']);exit;

		/*$data['template'] = $template[0];
		print_r($data['template']);exit;*/
		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		$data['usefullink'] = $this->pktlib->custom_query("select * from temp_menu where menu_id=1 and parent_id=0");
                             

		$this->load->view('templates/'.$template[0]['template'].'/head', $data);

		$this->load->view('templates/'.$template[0]['template'].'/header', $data);
			//echo "header";exit;
		$this->load->view('templates/'.$template[0]['template'].'/content', $data);
		/*echo '<pre>';
		print_r($data);
		echo '</pre>';exit;*/
		$this->load->view('templates/'.$template[0]['template'].'/footer', $data);

	}

	function default_breadcrumb($data) {
		$this->load->view('templates/default/breadcrumb', $data);
	}

	function default_pagination($data) {
		$template = $this->pktdblib->custom_query("select template from templates where type='frontend' and is_active=true");
		//print_r($data);
		$this->load->view('templates/'.$template[0]['template'].'/pagination', $data);
	}

	function default_inner_sidebar_right($data) {
		$this->load->view('templates/default/inner_sidebar_right', $data);
	}



	function home_template($data) {

		$meta['meta_title'] = $data['meta_title'];

		$meta['meta_description'] = $data['meta_description'];

		$companyData = ['id'=>$this->id];

		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);

		//print_r($data['websiteInfo']);

		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);

		$this->load->view('templates/home/head', $data);

		$this->load->view('templates/home/header', $data);

		$this->load->view('templates/home/content', $data);

		$this->load->view('templates/home/footer', $data);

	}

	

	function error_template($data) {

		$data['meta_title'] = $data['meta_title'];

		$data['meta_description'] = $data['meta_description'];

		$companyData = ['id'=>$this->id];

		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);

		//print_r($data['websiteInfo']);

		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);

		$this->load->view('templates/error/head', $data);

		$this->load->view('templates/error/header', $data);

		$this->load->view('templates/error/content', $data);

		$this->load->view('templates/error/footer', $data);

	}



	function templateAddress($companyId = custom_constants::company_id, $type = 'companies'){
		$this->load->model('address_model');
		$this->address_model->set_table('address');
		$address = $this->address_model->userBasedDefaultAddress($companyId, $type);
		return $address;

	}



	function email_frontTemplate($data = []) {

		$companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);
		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		$this->load->view('templates/email/template_1', $data);
		$this->load->view('templates/email/footer', $data);
	}

	function obaju_inner_template($data = []) {
		//echo '<pre>';print_r($data);echo '</pre>';
		$companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);

		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		//print_r($data['websiteAddress']);
		$this->load->view('templates/obaju/head',$data);
		$this->load->view('templates/obaju/header',$data);
		$this->load->view('templates/obaju/content_inner',$data);
		//$this->load->view('templates/obaju/inner_template_content',$data);

		$this->load->view('templates/obaju/footer',$data);
	}

	function humanity_master_template(/*$data = []*/) {
		$template[0]['template'] = 'humanity_master';
		//echo '<pre>';print_r($data);echo '</pre>';
		/*$companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);

		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);*/
		//print_r($data['websiteAddress']);
		$this->load->view('templates/'.$template[0]['template'].'/head'/*,$data*/);
		$this->load->view('templates/'.$template[0]['template'].'/header'/*,$data*/);
		//$this->load->view('templates/.$template[0]['template']./content_inner',$data);
		//$this->load->view('templates/.$template[0]['template']./inner_template_content'/*,$data*/);

		$this->load->view('templates/'.$template[0]['template'].'/footer'/*,$data*/);
	}

	function memberpanel_template($data) {
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		$meta['meta_keyword'] = $data['meta_keyword'];
		$companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);
		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		$this->load->view('templates/memberpanel/head', $data);
		$this->load->view('templates/memberpanel/header', $data);
		$this->load->view('templates/memberpanel/content', $data);
		$this->load->view('templates/memberpanel/footer', $data);
	}

	function venedor_template($data) {
		$meta['meta_title'] = $data['meta_title'];
		$meta['meta_description'] = $data['meta_description'];
		$meta['meta_keyword'] = $data['meta_keyword'];
		$companyData = ['id'=>$this->id];
		$data['websiteInfo'] = Modules::run('companies/get_company_details', $companyData);
		$data['websiteAddress'] = $this->templateAddress($this->id, $this->templateType);
		$this->load->view('templates/venedor/head', $data);
		$this->load->view('templates/venedor/header', $data);
		$this->load->view('templates/venedor/content', $data);
		$this->load->view('templates/venedor/footer', $data);
	}

}

