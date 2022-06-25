<?php 
if(!defined('BASEPATH'))exit('No direct script access allowed');

class Notify_model extends CI_Model{
	private $database;
	private $table;

    function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
	}

	function set_table($table) {
		$this->table = $table;
	}

	function get_table() {
		$table = $this->table ;
		return $table;
	}

	function generateOtp($authData)
    {
        // $otp = random_string('numeric', 6);
        $otp = mt_rand(100000, 999999);
        $date = date("Y-m-d H:i:s");

        $this->db->select('*');
        $this->db->from('otp');

        if(isset($authData['mobile']) && !empty($authData['mobile']))
        {
            $this->db->where('mobile', $authData['mobile']);
        }
        if(isset($authData['email']) && !empty($authData['email']))
        {
            $this->db->where('email', $authData['email']);
        }

        $query = $this->db->get();
        if($query->num_rows())
        {
            $updateotp = array('otp' => $otp, 'created' => $date, 'modified' => $date);
            $this->db->where('mobile', $authData['mobile']);
            $this->db->update('otp', $updateotp);
            $otp = ($this->db->affected_rows()>0) ? $otp : '';
        }
        else
        {
            $insertotp = array('mobile' => $authData['mobile'], 'email' => $authData['email'], 'otp' => $otp, 'created' => $date, 'modified' => $date);
            $otp = ($this->db->insert('otp', $insertotp)) ? $otp : '';
        }
        return $otp;
    }
}