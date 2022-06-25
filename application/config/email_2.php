<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    //'mailpath' => '/usr/sbin/sendmail',
    'smtp_host' => 'smtp.primarykey.in', 
    'smtp_port' => 465,
    'smtp_user' => 'thegrocerystore@primarykey.in',
    'smtp_pass' => 'grocery@123*#',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'iso-8859-1',//'utf-8',
    'wordwrap' => TRUE
);
