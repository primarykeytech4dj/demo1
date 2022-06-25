<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redirect extends MY_Controller {
	function __construct() {
		parent::__construct();
		redirect(base_url());
	}
}