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
 * Anil Labs core CodeIgniter class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    PKT's Library
 * @author      Deepak Jha
 * @link        http://www.primarykey.in
 */

class Pktlib {
    protected $CI;
    private $database;
    private $table;
    public function __construct($params = array())
    {
        $this->CI =& get_instance();

        $this->CI->load->helper('url');
        $this->CI->load->library('pktdblib');
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

    function get_list($params = []) {
        //$db = $this->database;
        $table = $this->get_table();
        if(isset($params['order']))
            $this->CI->db->order_by($params['order']);

        if(!isset($params['condition'])) {
            //echo $table;exit;
            $query = $this->CI->db->get($table);
            return $query->result_array();
        }

        $query = $this->CI->db->get_where($table, $params['condition']);
        return $query->row_array();
    }


    function get_active_service_list($id = NULL) {
        $condition['is_active'] = TRUE;
        $condition['product_type'] = 2;
        if (empty($id))
        {
            $query = $this->CI->db->get_where('products', $condition);
            return $query->result_array();
        }
        $condition['id'] = $id;
        $query = $this->CI->db->get_where('products', $condition);
        return $query->row_array();
    }

    function get_category_dropdown_list($id = NULL) {
        $db = $this->database;
        $condition['is_active'] = TRUE;
        $db->select('id,category_name');
        $query = $db->get_where('product_categories', $condition);
        return $query->result_array();
    }

    function _insert($data) {
        //echo "hii";
        $response['status'] = 'failed';
        $db = $this->database;
        $table = $this->get_table();
        /*print_r($db);
        print_r($table);
        print_r($data);*/
        $res = $this->CI->db->insert($table, $data);
        //print_r($res);
        if($res){
            $response['status'] = 'success';
            $response['id'] = $this->CI->db->insert_id();
        }else{
            $response['status'] = 'error';
            $response['message'] = $this->CI->db->_error_message();
        }
        return  $response;
    }

    function _insert_multiple($data) {
        $db = $this->database;
        $table = $this->get_table();
        // echo "<pre>";print_r($table);exit;
        $num_rows = $this->CI->db->insert_batch($table, $data);
        return $num_rows;
    }

    function _update($id, $data) {
        //print_r($data);
       // $db = $this->database;
        $table = $this->get_table();
        $this->CI->db->where('id', $id);
        $update = $this->CI->db->update($table, $data);
        //print_r($this->CI->db->last_query());
        return $update;
    } 

    function update_multiple($field, $data) {
        $db = $this->database;
        $table = $this->get_table();
        $updt = $this->CI->db->update_batch($table, $data, $field);
        return $updt;
    }

    function get_active_list($id = NULL) {
        $db = $this->database;
        $table = $this->get_table();
        $condition['is_active'] = TRUE;
        //$db->select(['products.*', 'product_categories.category_name']);
        //$db->join('product_categories', 'product_categories.id = products.product_category_id');
        
        if (empty($id))
        {
            //print_r("reached here");exit;
            $query = $this->CI->db->get_where('products', $condition);
            return $query->result_array();
        }
        $condition['id'] = $id;
        $query = $this->CI->db->get_where('products', $condition);
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

    public function getMetadata($pageid){
        $sql = "SELECT * FROM pages WHERE pageid = ?"; 
        $results = $this->CI->db->query($sql, array($pageid));
        $pageMetadata = $results->row(); 
        $data['keywords'] = $pageMetadata->keywords;
        $data['description'] = $pageMetadata->description;
        $CI->load->view('templates/header',$data); 
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
                //print_r($join);
                //echo '<br>';
                foreach ($join as $joinkey => $jointype) {
                   // echo $joinkey."<br>";
                   // print_r($jointype);
                    //echo $jointype['table'];
                    # code...
                    if(!($jointype['table'])){ //echo "hello";
                        return false;
                    }

                    $innerConditions = implode(",", $jointype['innercondition']);
                    //echo $innerConditions;
                    $this->CI->db->join($jointype['table'], $innerConditions, $key);
                }

                /*if(!($join['table'])){ 
                    return false;
                }

                $innerConditions = implode(",", $join['innercondition']);
                //echo $innerConditions;
                $this->CI->db->join($join['table'], $innerConditions, $key);*/
            }
        }
        
        //print_r($params['conditions']);
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

    function upload_multiple_file($arr = []){
        //echo "<pre> pkt lib";
        //echo "<pre> pkt lib";
        //print_r($arr);//exit;
        $countfile = count($arr['file']['name']);
        //echo $countfile;exit;
        $error = [];
        $fileName = [];
        if($countfile >= 1){
            $files = $_FILES;
            //print_r($_FILES);
            $number_of_files_uploaded = count($files[$arr['arrindex']]['name']);
            //echo $number_of_files_uploaded;
            // Faking upload calls to $_FILE
            //print_r($files[$arr['arrindex']]);
            for ($i = 0; $i < $number_of_files_uploaded; $i++) :
                //echo $arr['arrindex']." ".$arr['fieldname'];
                //print_r($files[$arr['arrindex']]['name'][$i]);
                if(empty($files[$arr['arrindex']]['name'][$i][$arr['fieldname']])){
                    $fileName[$i] = false;
                    continue;
                }
                //echo "hello";
                $_FILES['userfile']['name']     = $files[$arr['arrindex']]['name'][$i][$arr['fieldname']];
                $_FILES['userfile']['type']     = $files[$arr['arrindex']]['type'][$i][$arr['fieldname']];
                $_FILES['userfile']['tmp_name'] = $files[$arr['arrindex']]['tmp_name'][$i][$arr['fieldname']];
                $_FILES['userfile']['error']    = $files[$arr['arrindex']]['error'][$i][$arr['fieldname']];
                $_FILES['userfile']['size']     = $files[$arr['arrindex']]['size'][$i][$arr['fieldname']];
                //echo $i;
               // echo "hii";
                //print_r($_FILES['userfile']);
                $config = array(
                    //'file_name'     => '',
                    'allowed_types' => $arr['ext'],
                    //'max_size'      => 3000,
                    'overwrite'     => FALSE,
                    
                    /* real path to upload folder ALWAYS */
                    'upload_path'   => $arr['path']
                );
                //print_r($config);
                if(isset($arr['max_size']) && in_array('max_size', $arr))
                    $config['max_size'] = $arr['max_size']; 

                if(isset($arr['max_width']) && in_array('max_width', $arr))
                    $config['max_width']     = $arr['max_width'];

                if(isset($arr['max_height']) && in_array('max_height', $arr)) 
                    $config['max_height']    = $arr['max_height'];

                $this->CI->load->library('upload', $config);
                $this->CI->upload->initialize($config);
                if ( ! $this->CI->upload->do_upload('userfile')) :
                    $error[$arr['arrindex']][] = array('error' => $this->CI->upload->display_errors());
                    continue;
                else :
                    $fileName[$i] = $this->CI->upload->data('file_name');
                    if(isset($arr['thumb'])){
                        $this->createMultipleImageThumbs($config['upload_path'], $arr['thumb']['path'], $fileName, $arr['thumb']);
                    }
                    // Continue processing the uploaded data
                endif;
            endfor;

            $array['error'] = $error;
            $array['filename'] = $fileName;
            return $array;
            //exit;

        }else{
          //echo 'in else';
          return $this->upload_single_file($arr);
        }
        //exit;
    }

    function upload_single_file($arr = []){
        /*echo "<pre>";
        print_r($arr);exit;*/
        $error = [];
        $fileName = '';
        $config['upload_path']   = $arr['path']; 
        $config['allowed_types'] = $arr['ext']; 
        if(isset($arr['max_size']) && in_array('max_size', $arr))
            $config['max_size'] = $arr['max_size']; 

        if(isset($arr['max_width']) && in_array('max_width', $arr))
            $config['max_width']     = $arr['max_width'];

        if(isset($arr['max_height']) && in_array('max_height', $arr)) 
            $config['max_height']    = $arr['max_height'];

        $this->CI->load->library('upload', $config);
        $this->CI->upload->initialize($config);
        //print_r($arr['fieldname']);exit;
        if ( ! $this->CI->upload->do_upload($arr['fieldname'])) {
            $error[$arr['arrindex']] = array($arr['fieldname'] => $this->CI->upload->display_errors()); 
        }
        else { 
            $fileName = $this->CI->upload->data('file_name');
            if(isset($arr['thumb'])){
                $this->createSingleImageThumbs($config['upload_path'], $fileName, $arr['thumb']);
            } 
        }
        $array['error'] = $error;
        $array['filename'] = $fileName;
        return $array;
    }

    function file_unlink($arr = []){

    }

    function dmYtoYmd($date){
        $str = explode('/', $date);
        return $str[2].'-'.$str[1].'-'.$str[0];
    }

    function YmdtodmY($date){
        $str = explode('-', $date);
        return $str[2].'/'.$str[1].'/'.$str[0];
    }

    function mdYtoYmd($date){
        $str = explode('/', $date);
        return $str[2].'-'.$str[0].'-'.$str[1];
    }

    function generate_captcha() {
        $vals = array(
            'img_path' => './assets/uploads/capchaImage/',
            'img_url' => site_url('assets/uploads/capchaImage/'),
            'img_width' => 150,
            'img_height' => 25
            );
        $captchaVal = create_captcha($vals);
        $this->session->set_userdata('captchaword', $captchaVal['word']);
        return $captchaVal;
    }

    function referalList() {
        $referralArray = [
            '' => 'Referred By',
            'companies' => 'Walk In',
            'just_dial' => 'JustDial',
            'customers' => 'Client',
            'employees' => 'Employee',
            'website' => 'Online',
        ];
        return $referralArray;
    }

    function meetingList() {
        $meetingArray = [
            '' => 'Referred By',
            'enquiries' => 'Lead',
            'customers' => 'Client',
            'employees' => 'Employee',
        ];
        return $meetingArray;
    }

    function meetingPlace() {
        $meetingArray = [
            '' => 'Meeting Place',
            'Office' => 'Office',
            'Client Place' => 'Client Place',
            'Outside' => 'Outside',
        ];
        return $meetingArray;
    }

    function email($emailConfig = []) {
        echo "reached in Email";
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl:\\smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'primarykeytech@gmail.com',
            'smtp_pass' => 'primary5848',

        ];
        $str = str_replace('www.', '', $_SERVER['HTTP_HOST']);
        $this->CI->load->library('email', $config);
        $this->CI->email->set_newline("\r\n");
        $emailConfig['charset'] = 'iso-8859-1';
        $emailConfig['wordwrap'] = TRUE;
        $emailConfig['mailtype'] = 'html'; // Append This Line
        $this->CI->email->initialize($emailConfig);  
        $this->CI->email->from('noreply@'.$str, ucfirst(str_replace('.com', '', $str)));
        $this->CI->email->to($emailConfig['to']);
        $this->CI->email->subject($emailConfig['subject']);
        $this->CI->email->message($emailConfig['message']);
        if($this->CI->email->send()){
            return json_encode(['status'=>'success', 'message'=>'Email Sent Successfully']);
        }else{
            return json_encode(['status'=>'fail', 'message'=>$this->CI->email->print_debugger()]);

            show_error($this->CI->email->print_debugger()); 
            exit;
        }
    }

    function read_GmailEmail() {
        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
        $username = 'primarykeytech@gmail.com';
        $password = 'primary5848';

        /* try to connect */
        $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

        /* grab emails */
        $emails = imap_search($inbox,'ALL');
        /*echo '<pre>';
        print_r($emails);
        exit;*/
        /* if emails are returned, cycle through each... */
        if($emails) {
            
            /* begin output var */
            $output = '';
            
            /* put the newest emails on top */
            rsort($emails);
            
            /* for every email... */
            foreach($emails as $email_number) {
                
                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox,$email_number,0);
                $message = imap_fetchbody($inbox,$email_number,2);
                
                /* output the email header information */
                $output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
                $output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
                $output.= '<span class="from">'.$overview[0]->from.'</span>';
                $output.= '<span class="date">on '.$overview[0]->date.'</span>';
                $output.= '</div>';
                
                /* output the email body */
                $output.= '<div class="body">'.$message.'</div>';
                echo $output;
                exit;
            }
            
            echo $output;
        } 

        /* close the connection */
        imap_close($inbox);
    }

    function smsTypes(){
        return [''=>'Select SMS Types', 'link'=>'link', 'video'=>'video'];
    }
    
    function setInput($values_posted) {
        foreach ($values_posted as $post_name => $post_value) {
        //print_r($post_name);
        //print_r($post_value);
            foreach ($post_value as $field_key => $field_value) {
                if(isset($input[$field_key]['type']) && $input[$field_key]['type']=="checkbox" && $field_value==true){
                    $input[$field_key]['checked'] = "checked";
                }else{
                    $input[$field_key]['value'] = $field_value;
                }
            }
        }
    }

    function createMultipleImageThumbs( $pathToImages, $pathToThumbs, $filename, $thumbArray ){
        if (!is_dir($pathToThumbs)) {
            mkdir($pathToThumbs, 0777, TRUE);
        }

        if(is_array($filename)){
            foreach ($filename as $key => $file) {
                $this->createSingleImageThumbs($pathToImages, $file, $thumbArray);
            }
        }

    }

    function createSingleImageThumbs($pathToImages, $filename, $thumbArray ){
        // continue only if this is a JPEG image
        //echo $filename.'<br>';
        $info = pathinfo($pathToImages . $filename);
        if (!is_dir($thumbArray['path'])) {
            mkdir($thumbArray['path'], 0777, TRUE);
        }
        $img = '';
        if(isset($info['extension'])){
            //echo $info['extension']."<br>";
            switch($info['extension']) {
                case 'gif':
                    $img = imagecreatefromgif("{$pathToImages}{$filename}");
                    break;
                case 'jpg':
                case 'JPG':
                case 'JPEG':
                case 'jpeg':
                    $img = imagecreatefromjpeg("{$pathToImages}{$filename}");
                    break;
                case 'png':
                    $img = imagecreatefrompng("{$pathToImages}{$filename}");
                    break;
            }

            if(empty($img))
                return true;
                /*if (strtolower($info['extension']) == 'jpg' && $info['filename']!=='thumbs') 
                {*/
                  //echo "Creating thumbnail for {$filename} <br />";

                  // load image and get image size
                  //$img = imagecreatefromjpeg( "{$pathToImages}{$filename}" );
                  $width = imagesx( $img );
                  $height = imagesy( $img );

                  // calculate thumbnail size
                  $new_width = $thumbArray['width'];
                  $new_height = isset($thumbArray['height'])?$thumbArray['height']:floor( $height * ( $thumbArray['width'] / $width ) );

                  // create a new temporary image
                  $tmp_img = imagecreatetruecolor( $new_width, $new_height );

                  // copy and resize old image into new image 
                  imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

                  // save thumbnail into a file
                  imagejpeg( $tmp_img, "{$thumbArray['path']}{$filename}" );
                //}
            
            }
        
        return true;
    }

    function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth ) 
    {
      // open the directory
        $dir = opendir( $pathToImages );
      //print_r($dir);
        //$fname = readdir( $dir );
        if (!is_dir($pathToThumbs)) {
            mkdir($pathToThumbs, 0777, TRUE);
        }
      /*while (false !== ($fname = readdir( $dir ))) {
       echo $fname.'<br>';
      }
      exit;*/
      // loop through it, looking for any/all JPG files:
      while (false !== ($fname = readdir( $dir ))) {
        // parse path for the extension
        $info = pathinfo($pathToImages . $fname);
        
        // continue only if this is a JPEG image
        //echo $fname.'<br>';
        $img = '';
        if(isset($info['extension'])){
            //echo $info['extension']." ".$fname."<br>";
            switch($info['extension']) {
                case 'gif':
                    $img = imagecreatefromgif("{$pathToImages}{$fname}");
                    break;
                case 'jpg':
                case 'JPG':
                case 'JPEG':
                case 'jpeg':
                    $img = imagecreatefromjpeg("{$pathToImages}{$fname}");
                    break;
                case 'png':
                    $img = imagecreatefrompng("{$pathToImages}{$fname}");
                    break;
            }

            if(empty($img))
                continue;
                /*if (strtolower($info['extension']) == 'jpg' && $info['filename']!=='thumbs') 
                {*/
                 // echo "Creating thumbnail for {$fname} <br />";

                  // load image and get image size
                  //$img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
                  $width = imagesx( $img );
                  $height = imagesy( $img );

                  // calculate thumbnail size
                  $new_width = $thumbWidth;
                  $new_height = floor( $height * ( $thumbWidth / $width ) );

                  // create a new temporary image
                  $tmp_img = imagecreatetruecolor( $new_width, $new_height );

                  // copy and resize old image into new image 
                  imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

                  // save thumbnail into a file
                  imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
                //}
            
          }
        }
        // close the directory
        closedir( $dir );
    }
    // call createThumb function and pass to it as parameters the path 
    // to the directory that contains images, the path to the directory
    // in which thumbnails will be placed and the thumbnail's width. 
    // We are assuming that the path will be a relative path working 
    // both in the filesystem, and through the web for links
    //createThumbs("upload/","upload/thumbs/",100);



    function cropImage($nw, $nh, $source, $stype, $dest) {
     $size = getimagesize($source);
     $w = $size[0];
      $h = $size[1];

      switch($stype) {
          case 'gif':
          $simg = imagecreatefromgif($source);
          break;
          case 'jpg':
          $simg = imagecreatefromjpeg($source);
          break;
          case 'png':
          $simg = imagecreatefrompng($source);
          break;
      }

      $dimg = imagecreatetruecolor($nw, $nh);
      $wm = $w/$nw;
      $hm = $h/$nh;
      $h_height = $nh/2;
      $w_height = $nw/2;

      if($w> $h) {
          $adjusted_width = $w / $hm;
          $half_width = $adjusted_width / 2;
          $int_width = $half_width - $w_height;
          imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
      } elseif(($w <$h) || ($w == $h)) {
          $adjusted_height = $h / $wm;
          $half_height = $adjusted_height / 2;
          $int_height = $half_height - $h_height;

          imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
      } else {
          imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
      }

      imagejpeg($dimg,$dest,100);
    }

    function create_nested_menu($parent = 0, $menuId = 1, $session_menu = []) {
        //print_r($session_menu);exit;
        $items = array();
        $str = 'Select * from temp_menu where parent_id="'.$parent.'" and menu_id='.$menuId.' and is_active=true';
        if(!empty($session_menu)){
            $str.=' AND id in ("'.implode('","', $session_menu).'")';
        }
        $str.=' order by priority ASC';
        //echo $str;
        $results = $this->CI->pktdblib->custom_query($str);
        foreach($results as $result) {
            $child_array = $this->create_nested_menu($result['id'], $menuId);
            if(sizeof($child_array) == 0) {
                $result['sub_menu'] = [];
                array_push($items, $result);
            } else {
                $result['sub_menu'] = $child_array;
                array_push($items, $result);
            }
        }
        return $items;
    }

    function get_target(){
        $query = ['_self'=>'Self', '_new'=>'New', '_blank'=>'Blank']; 
        //print_r($query);
        return $query;
    }

    function get_fiscal_year(){
        $billYear = date('y').'-'.date('y', strtotime('+1 year'));
        if(date('m')<=3)
            $billYear = date('y', strtotime('-1 year')).'-'.date('y');

        return $billYear;
    }

    function social_share_button($post, $path = NULL){
        //print_r($post['product']);exit;
        //$post = $trend;
        $post_id = $post['id'];
        $post_title = $post['product'];
        if(NULL!==$path){
            $post_url = $path;
        }
        else{
            $post_url = base_url();
            //$post_url = base_url().'news/view/'.$post['slug'];
        }
        $post_author = 'Anonymous';
        $post_content = $post['description'];
        if(strlen($post['description'])<=200)
        {
        $short_desc = $post_content;
        }
        else
        {
        $short_desc = substr($post_content,0,200).'...';
        }
        $baseurl = base_url();
        $gmail_body = $post_url.'<br><br>'.$short_desc;
        $link_href = $post_url;
        $gmail_href = "https://mail.google.com/mail/u/0/?view=cm&fs=1&to&su=".$post_title."&body=".$link_href."&ui=2&tf=1&pli=1?";
        $email_href = "mailto:?subject=".$post_title."&amp;body=".$link_href."";
        $facebook_href = "http://www.facebook.com/sharer.php?u=".$post_url;
        $twitter_href = "https://twitter.com/share?url=".$post_url."&text=".$post_title;
        $linkedin_href = "https://www.linkedin.com/shareArticle?mini=true&url=".$post_url."&title=".$post_title."&summary=".$short_desc."&source=bloomingcollection.com";
        $whatsappURL = 'whatsapp://send?text='.$post_url;
        
        ?>
        <a href="<?php echo $whatsappURL; ?>" id="whatsapp1" class="hidden-lg hidden-md"><i class="fa fa-whatsapp custom-icon mesg_options_icon p4" id="watsaapLink1"></i></a>
        <a href="<?php echo $email_href; ?>" id="emailLink1" target="_blank"><i class="fa fa-envelope-o custom-icon mail_options_icon p4"></i></a>
        <a href="<?php echo $facebook_href; ?>" id="facebookLink1" target="_blank"><i class="fa fa-facebook-f custom-icon fb_options_icon p4"></i></a>
        <a href="<?php echo $twitter_href; ?>" id="twitterLink1" target="_blank"><i class="fa fa-twitter custom-icon twit_options_icon p4"></i></a>
        <a href="<?php echo $linkedin_href; ?>" id="linkedinLink1" target="_blank"><i class="fa fa-linkedin custom-icon linkedin_options_icon p4"></i></a>
        <?php
    }

    function createMegaMenu($menuArray = []){
        $string ='<div class="mega-menu clearfix" style="display: none;">
            ';

        foreach ($menuArray as $key => $menu) {
            $string.='<div class="col-5">'.anchor($menu['slug'], $menu['name'], ['class'=>'mega-menu-title', 'target'=>$menu['target']]);
            if(!empty($menu['sub_menu'])){
                $string.=$this->createulli($menu['sub_menu'], 'mega-menu-list clearfix');
            }
            $string.='</div>';
        }
        $string.='</div>'; 

        return $string;         
    }

    function createulli($menuArray = [], $ulClass=''){
        /*echo "hiii";print_r($menuArray);
        print_r($ulClass);exit;*/
        $string = '<ul class="'.$ulClass.'">';
        foreach ($menuArray as $key => $menu) {
            if($menu['is_custom_constant']){
                $string.= '<li class="menuitem-'.$key.' mega-menu-container"> '.anchor($menu['slug'], $menu['name'], ['class'=>'fa '.$menu['class'], 'target'=>$menu['target']]);

                
                if(!empty($menu['sub_menu'])){ //echo "hhi";
                    $string.=$this->createMegaMenu($menu['sub_menu']);
                }
                $string.= '</li>';
                
            }else{

                $string.= '<li class="menuitem-'.$key.'"> '.anchor($menu['slug'], $menu['name'], ['class'=>'fa '.$menu['class']]);
                if(!empty($menu['sub_menu'])){
                    $string.=$this->createulli($menu['sub_menu'], '');
                }
                $string.= '</li>';
            }

        }
        $string.='</ul>';
        return $string;
    }

    function parseOutput($format, $content){
        //print_r($content);exit;
        if($format == 'json'){
            //header('Content-type: application/json;charset=utf-8'); //Setting the page Content-type
            //echo "hii";
            //print_r($content);
            echo json_encode($content);
            exit;
        }elseif($format=='xml'){
            $xml = new SimpleXMLElement('<root/>');
            array_walk_recursive($content, array ($xml, 'news'));
            return $xml->asXML();
        }
    }
    
    function unit_convertion($unit1, $baseUOM){
        /*echo $unit1;
        echo $baseUOM;exit;*/
        switch(strtoupper(trim($unit1))){
            case "GM":
                switch (strtoupper(trim($baseUOM))) {
                    case 'KG':
                    //echo "reached";exit;
                        return 0.001;
                        break;
                    
                    default:
                        "invalid";
                        break;
                }
            break;
            case "KG":
                switch (strtoupper(trim($baseUOM))) {
                    case 'GM':
                    //echo "gm found";exit;
                        return 1000;
                        break;
                    
                    default:
                        # code...
                        break;
                }
            break;
            case "Nos":
                return 1;
            break;
            case "Bun":
                return 1;
            break;
        }
          
    }
}
?>
