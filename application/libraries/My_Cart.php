<?php if (!defined('BASEPATH')) exit('No direct access allowed.');
class MY_Cart extends CI_Cart {
  var $CI;
  
  function __construct()
  {
    parent::__construct(); //this may not be required

    $this->CI =& get_instance(); //this may not be required

    $this->product_name_rules    = '^.';
  }

}