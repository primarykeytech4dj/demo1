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

if(!function_exists('activity_log'))
{
	// Checks if user has verified email and is logged in by default. If email_verification is TRUE then it will also check if user has verified email address
	function log_que($sql) {
		echo "hii";
		print_r($sql);
		exit;
        $user = $CI->session->userdata();
        print_r($user);
        $filepath = APPPATH . 'logs/Query-log-' . date('Y-m-d') . '.php'. $user;   
            $handle = fopen($filepath, "a+");
            if(preg_match('/INSERT/',$sql) || preg_match('/UPDATE/',$sql) || preg_match('/DELETE/',$sql)) {
				fwrite($handle, $sql." \n Execution Time: ".date("Y-m-d H:i:s")."\n\n");  
			}

            //fwrite($handle, $sql." \n Execution Time: ".date("Y-m-d H:i:s")."\n\n");  
            fclose($handle);   
	}
	//print_r($CI->session->userdata());
}

?>