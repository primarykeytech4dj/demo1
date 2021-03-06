<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['pre_system'] = array(
    'class'     => 'Router',
    'function'  => 'route',
    'filename'  => 'router.php',
    'filepath'  => 'hooks'
);

$hook['post_controller_constructor'] = array(
    'class'     => 'Router',
    'function'  => 'config',
    'filename'  => 'router.php',
    'filepath'  => 'hooks'                      
);

/*$hook['post_system'] = array(
    'class'     => 'Router',
    'function'  => 'parseRoute',
    'filename'  => 'router.php',
    'filepath'  => 'hooks',
    //'params' => $this->CI->config->item('response_format')                        
);*/
