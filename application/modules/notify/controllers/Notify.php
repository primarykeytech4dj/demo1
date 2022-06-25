<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Notify extends MY_Controller {

        function __construct() {
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
            header("Access-Control-Allow-Headers: *");
            parent::__construct();
            
            // Check login and make sure email has been verified
            foreach(custom_constants::$protected_pages as $page)
            {   
                if(strpos($this->uri->uri_string, $page) === 0)
                {   
                    check_user_login(FALSE);
                }
            }
            $this->load->model('notify/notify_model');
        }

        function index() {
            check_user_login(FALSE);
            redirect('');
        }

        public function accountVerification($authData=[])
        {
            if(isset($authData) && !empty($authData))
            {
                $site_cn = base_url();
                $username = $authData['username'];
                $hash_string = $authData['hash_string'];
                $subject = "Verify account for {$username}";
                $message = "Congratulations, {$username}.\n You are qualified to take part in our Oxiinc Group! Simply tell your friends, partners and family about your “Oxiinc Digital Business” and invite them to join Your community.";
                $message .= "Paste this link in your browser to verify your account. {$site_cn}" . custom_constants::email_verification_url . "/{$hash_string}";
                $message .= "\nThank you. \nTeam Oxiinc Group";

                $emailResponse = $this->sendEmail(array('email' => $authData['email'], 'subject' => $subject, 'message' => $message));

                if(isset($authData['sendotp']) && $authData['sendotp'] === TRUE)
                {
                    if($otp = $this->generateOtp($authData))
                    {
                        $sms = "One Time Password for Oxiinc Digital Account verification is {$otp}.\n Please use the password to complete the verification.\n\n Team Oxiinc Group.";
                        $smsResponse = $this->sendSms(array('mobile' => $authData['mobile'], 'sms' => $sms));
                    }
                }
            }
            return true;
        }

        public function generateOtp($authData=[])
        {
            $otp = '';
            if(isset($authData) && !empty($authData))
            {
                $otp = $this->notify_model->generateOtp($authData);
            }
            return $otp;
        }

        public function sendSms($smsData=[])
        {
            $smsResponse = array();
            if(isset($smsData) && !empty($smsData))
            {
                $smsResponse = $this->notifications->sendSms(array('mobile' => $smsData['mobile'], 'sms' => $smsData['sms']));
            }
            return $smsResponse;
        }

        public function sendEmail($emailData=[])
        {
            $emailResponse = array();
            if(isset($emailData) && !empty($emailData))
            {
                $emailResponse = $this->notifications->sendEmail($emailData);
            }
            return $emailResponse;
        }

        public function testEmail($emailData=[])
        {
            $emailResponse = $this->notifications->sendEmail(array('email' => 'kiranj1992@gmail.com', 'subject' => 'Test Email', 'message' => 'Welcome To Web World'));
            echo "<pre>";print_r($emailResponse);exit;
        }
    }