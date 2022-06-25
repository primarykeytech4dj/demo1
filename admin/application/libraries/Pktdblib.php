<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author      PKT
 * @copyright   Copyright (c) 2017, Primary Key Technologies.
 * @license     
 * @link        http://www.primarykey.in
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Primary Key Technologies core CodeIgniter class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    PKT's Database Library
 * @author      Deepak Jha
 * @link        http://www.primarykey.in
 */

class Pktdblib {
    protected $CI;
    private $database;
    private $table;
    public function __construct($params = array())
    {
        $this->CI =& get_instance();

        $this->CI->load->helper('url');
        $this->CI->config->item('base_url');
        $this->database = $this->CI->load->database();
    }

    // Unique to models with multiple tables
    function set_table($table) {
        $this->CI->table = $table;
    }
    
    // Get table from table property
    function get_table() {
        //echo "hii";
        $table = $this->CI->table;
        return $table;
    }

    // Retrieve all data from database and order by column return query
    function get($order_by) {
        $db = $this->database;
        $table = $this->get_table();
        $this->CI->db->order_by($order_by);
        $query=$this->CI->db->get($table);
        return $query;
    }

    // Limit results, then offset and order by column return query
    function get_with_limit($limit, $offset, $order_by) {
        $db = $this->database;
        $table = $this->get_table();
        $db->limit($limit, $offset);
        $db->order_by($order_by);
        $query=$db->get($table);
        return $query;
    }

    // Get where column id is ... return query
    function get_where($id) {
        //$db = $this->database;
        $table = $this->get_table();
        $this->CI->db->where('id', $id);
        $query=$this->CI->db->get($table);
        return $query->row_array();
    }

    // Get where custom column is .... return query
    function get_where_custom($col, $value) {
        $db = $this->database;
        $table = $this->get_table();
        $this->CI->db->where($col, $value);
        $query=$this->CI->db->get($table);
        return $query;
    }

    function get_list($id = NULL) {
        $db = $this->database;
        $table = $this->get_table();
        if (empty($id)) {
            $query = $db->get($table);
            return $query->result_array();
        }

        $query = $db->get_where($table, array('id' => $id));
        return $query->row_array();
    }

    function _insert($data) {
        $response['status'] = 'failed';
        // print_r($data);exit;
        $db = $this->database;
        $table = $this->get_table();
        $res = $this->CI->db->insert($table, $data);
        //print_r($this->CI->db->last_query());
        if($res){
            $response['status'] = 'success';
            $response['id'] = $this->CI->db->insert_id();
        }else{
            $response['status'] = 'error';
            $response['message'] = $this->CI->db->_error_message();
        }
        //print_r($response);exit;
        return  $response;
    }

    function _insert_multiple($data) {
        $db = $this->database;
        $table = $this->get_table();
         /*echo "<pre>";print_r($data);
         print_r($table);
         exit;*/
        $num_rows = $this->CI->db->insert_batch($table, $data);
        return $num_rows;
    }

    function _update($id, $data) {
        //print_r($data);
       // $db = $this->database;
        $table = $this->get_table();
        //echo $id;exit;
        //print_r($data);exit;
        $this->CI->db->where('id', $id);
        $update = $this->CI->db->update($table, $data);
        //print_r($this->CI->db->last_query());
        return $update;
    } 

    function update_multiple($field, $data) {
        /*print_r($field);
        print_r($data);exit;*/
        $db = $this->database;
        $table = $this->get_table();
        $updt = $this->CI->db->update_batch($table, $data, $field);
        return $updt;
    }

    function get_active_list($id = NULL) {
        $db = $this->database;
        $table = $this->get_table();
        $condition['is_active'] = TRUE;
        
        if (empty($id))
        {
            $query = $this->CI->db->get_where($table, $condition);
            return $query->result_array();
        }
        $condition['id'] = $id;
        $query = $this->CI->db->get_where($table, $condition);
       //print_r($db->last_query());
        return $query->row_array();
    }

    function custom_query($sql) {
        $db = $this->database;
        
        $query = $this->CI->db->query($sql);
        //print_r($query);
        if(is_object($query))
            return $query->result_array();
        else
            return $query;
    }

    function custom_query_independent_of_id($sql) {
        $db = $this->database;
        //print_r($db);
        //$table = $this->get_table();
       // $db->query($sql);
        //print_r($sql);
        $query = $this->CI->db->query($sql);
        //print_r($this->CI->db->last_query());
        return $query;
    }

    function createquery($params){
        //print_r($params);
        if(isset($params['fields'])){
            $fields = implode(",", $params['fields']);
            $this->CI->db->select($fields);
        }else{
            $this->CI->db->select('*');
        }

        if($params['table']) {
            $this->CI->db->from($params['table']);
        }else{
            return false;
        }

        if(isset($params['join'])){
            foreach ($params['join'] as $key => $join) {
                foreach ($join as $joinkey => $jointype) {
                    if(!($jointype['table'])){ //echo "hello";
                        return false;
                    }
                    $innerConditions = implode(",", $jointype['innercondition']);
                    $this->CI->db->join($jointype['table'], $innerConditions, $key);
                }
            }
        }
        
        if(isset($params['conditions'])){
            $this->CI->db->where($params['conditions']);
        }//exit;

        if(isset($params['group'])){
            $this->CI->db->group_by($params['group']);
        }//exit;

        if(isset($params['order'])){
            $this->CI->db->order_by($params['order']);
        }

        if(isset($params['limit'])){
            $this->CI->db->limit($params['limit']);
        }
        $query = $this->CI->db->get();
        //print_r($this->CI->db->last_query());
        if ($query->num_rows() > 0 )
        {
            return $query->result_array();
        }
        else
        {
            return FALSE;
        }
    }

    function _delete($id) {
        $db = $this->database;
        $table = $this->get_table();
        $query = $this->CI->db->delete($table, array('id' => $id));
        return $query;
    }

    function _delete_by_column($key, $value) {
        $db = $this->database;
        $table = $this->get_table();
        $query = $this->CI->db->delete($table, array($key => $value));
        return $query;
    }

    // Count results where column = value and return integer
    function count_where($column, $value) {
        $db = $this->database;
        $table = $this->get_table();
        $this->CI->db->where($column, $value);
        $query=$this->CI->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    // Count all the rows in a table and return integer
    function count_all() {
        $db = $this->database;
        $table = $this->get_table();
        $query=$this->CI->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    // Find the highest value in id then return id
    function get_max() {
        $db = $this->database;
        $table = $this->get_table();
        $this->CI->db->select_max('id');
        $query = $this->CI->db->get($table);
        $row=$query->row();
        $id=$row->id;
        return $id;
    }

}
?>
