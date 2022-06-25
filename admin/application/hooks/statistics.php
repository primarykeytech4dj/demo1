<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Statistics {
    /*public function log_activity() {
        // We need an instance of CI as we will be using some CI classes
        $CI =& get_instance();
 
        // Start off with the session stuff we know
        $data = array();
        //$data['account_id'] = $CI->session->userdata('account_id');
        //$data['project_id'] = $CI->session->userdata('project_id');
        print_r($CI->session->userdata());

        $data['user_id'] = (NULL!==$CI->session->userdata('user_id'))?$CI->session->userdata('user_id'):0;
 
        // Next up, we want to know what page we're on, use the router class
        $data['section'] = $CI->router->class;
        $data['action'] = $CI->router->method;
 
        // Lastly, we need to know when this is happening
        $data['when'] = time();

        //which query has been executed
        $data['query'] = $this->log_queries();
        print_r($data['query']);exit;
 
        // We don't need it, but we'll log the URI just in case
        $data['uri'] = uri_string();
 
        // And write it to the database
        $CI->db->insert('statistics', $data);
    }

    function log_queries() {    
        $CI =& get_instance();
        $times = $CI->db->query_times;
        $dbs    = array();
        $output = NULL;     
        $queries = $CI->db->queries;

        if (count($queries) == 0){
            $output .= "no queries\n";
        }else{
            foreach ($queries as $key=>$query){
                $output .= $query . "\n";
            }
            $took = round(doubleval($times[$key]), 3);
            $output .= "===[took:{$took}]\n\n";
        }
        echo '<pre>';
        print_r($output);
        $CI->load->helper('file');
        if ( ! write_file(APPPATH  . "/logs/queries.log.txt", $output, 'a+')){
             log_message('debug','Unable to write query the file');
        }   
    }

    function logQueries() 
    {
        $CI = & get_instance();

        $filepath = APPPATH . 'logs/Query-log-' . date('Y-m-d') . '.php'; 
        $handle = fopen($filepath, "a+");                        
        $log = [];
        $times = $CI->db->query_times;
        print_r($CI->db->queries);exit;
        foreach ($CI->db->queries as $key => $query) 
        { 
            $sql = $query . " \n Execution Time:" . $times[$key]; 
            $log[$key] = ['query'=>$query, 'time'=>$times[$key]];
            fwrite($handle, $sql . "\n\n");    
        }

        fclose($handle);  
        $log = json_encode($log);
        return $log;
    }*/
}
