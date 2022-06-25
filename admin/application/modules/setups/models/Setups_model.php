<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*====================================================================================================
 |	ORIGINAL TEMPLATE BY DAVID CONNELLY @ INSIDERCLUB.COM MODIFIED BY LEWIS NELSON @ LEWNELSON.COM
 |====================================================================================================
 |
 |	This is the basic model template where only the table name changes and the
 |	class name changes. It was originally put together by David Connelly from
 |	http://insiderclub.com and the original template can be found at
 |
 |	http://www.insiderclub.org/perfectcontroller
 |
 |	It has been modified by Lewis Nelson from http://lewnelson.com to include
 |	comments and add a few more functions for some additional common queries.
 |
 ====================================================================================================
 |
 |	Template designed to run with CodeIgniter & wiredesignz Modular Extensions - HMVC
 |
 |====================================================================================================
 */

class Setups_model extends CI_Model
{


    function setupFilter()
    {
        $sql2 = 'SELECT DISTINCT module_name FROM `setup`';
        $response = $this->db->query($sql2)->result();
        return $response;
    }
}