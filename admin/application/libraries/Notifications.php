<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notifications
{
	private $error = array();
	function __construct()
	{
		$this->ci =& get_instance();
	}

	public function sendSms($smsData=[])
	{
		$response = array();

		if(isset($smsData['mobile']) && !empty($smsData['mobile']))
		{
			$user = "expedeglobal";
			$password = "patanjali";
			$senderId = "EMARKI";
			$channel = "Trans";
			$dcs = "0";
			$flashsms = "1";
			$route = "6";
			$mobile = $smsData['mobile'];
			$sms = urlencode($smsData['sms']);

			$smsurl = 'http://103.233.76.120/api/mt/SendSMS?user='.$user.'&password='.$password.'&senderid='.$senderId.'&channel='.$channel.'&DCS='.$dcs.'&flashsms='.$flashsms.'&number=91'.$mobile.'&text='.$sms.'&route='.$route.'';

			try  
	        {
	            $ch = curl_init();
	            curl_setopt($ch, CURLOPT_HEADER, 0);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	            curl_setopt($ch, CURLOPT_URL, $smsurl);
	            $data = curl_exec($ch);
	            curl_close($ch);
	            $response = $data;
	        }  
	        catch (Exception $e)  
	        {  
	            $response = $e->getMessage();  
	        }
		}
		return $response;
	}

	public function sendEmail($emailData=[])
	{
		$this->ci->load->library('email');
		if(isset($emailData['email']) && !empty($emailData['email']))
		{
			$this->ci->email->from("no-reply@primarykey.in", "Emarkit");
			$this->ci->email->to($emailData['email']);
			$this->ci->email->subject($emailData['subject']);
			$this->ci->email->message($emailData['message']);
			$this->ci->email->send();
		}
		return true;
	}
}