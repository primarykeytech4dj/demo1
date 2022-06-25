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
    //private $database;
    private $table;
    public function __construct($params = array())
    {
        $this->CI =& get_instance();

        $this->CI->load->helper('url');
        $this->CI->config->item('base_url');
    }

    function upload_multiple_file($arr = []){
        //echo "<pre> pkt lib";
        //echo "<pre> pkt lib";
        //print_r($arr);//exit;
        $countfile = count($arr['file']['name']);
        //echo $countfile;exit;
        $error = [];
        $fileName = [];
        $fileType = [];
        if($countfile >= 1){
            $files = $_FILES;
            //print_r($_FILES);
            //echo '<pre>';
            $number_of_files_uploaded = count($files[$arr['arrindex']]['name']);
            for ($i = 0; $i < $number_of_files_uploaded; $i++) :
                //echo $arr['arrindex']." ".$arr['fieldname'];
                //print_r($files[$arr['arrindex']]['name'][$i]);
                if(empty($files[$arr['arrindex']]['name'][$i][$arr['fieldname']])){
                    $fileName[$i] = false;
                    continue;
                }
                
                //echo "file type= ".get_file_extension($files[$arr['arrindex']]['name'][$i])."<br>";
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
                //print_r($config);
                $this->CI->load->library('upload', $config);
                $this->CI->upload->initialize($config);
                if ( ! $this->CI->upload->do_upload('userfile')) :
                    //echo  $this->CI->upload->display_errors();
                    $error[$arr['arrindex']][] = array('error' => $this->CI->upload->display_errors());
                    continue;
                else :
                    $fileName[$i] = $this->CI->upload->data('file_name');
                    $ext = explode('.', $fileName[$i]);
                    $fileType[$i] = $this->rectify_file_type($ext[count($ext)-1]);
                    if(isset($arr['thumb'])){
                        $this->createMultipleImageThumbs($config['upload_path'], $arr['thumb']['path'], $fileName, $arr['thumb']);
                    }
                    // Continue processing the uploaded data
                endif;
            endfor;

            $array['error'] = $error;
            $array['filename'] = $fileName;
            $array['type'] = $fileType;
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
        $fileType = '';
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
            $ext = explode('.', $fileName);
            $fileType = $this->rectify_file_type($ext[count($ext)-1]);
            if(isset($arr['thumb'])){
                $this->createSingleImageThumbs($config['upload_path'], $fileName, $arr['thumb']);
            } 
        }
        $array['error'] = $error;
        $array['filename'] = $fileName;
        $array['type'] = $fileType;
        return $array;
    }

    function file_unlink($arr = []){
        if(empty($arr))
            return FALSE;

        $str = 'Select '.$arr['field'].' from '.$arr['table'];
        //$this->CI->pktdblib->custom_query($str);
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
            '' => 'Select',
            'na' => 'NA',
            'customers' => 'Client',
            'employees' => 'Employee',
            'vendors' => 'Vendor',
            'brokers' => 'Broker',
            'warehouse' => 'Warehouse/Cutter',
            'enquiries' => 'Lead',
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
        //echo "reached in Email";
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
                  $thumbArray['width'] = $new_width = (!isset($thumbArray['width']))?imagesx( $img ):$thumbArray['width'];
                  $new_height = isset($thumbArray['height'])?$thumbArray['height']:floor( $height * ( $thumbArray['width'] / $width ) );
                  $new_width = isset($thumbArray['width'])?$thumbArray['width']:floor( $width * ( $thumbArray['height'] / $height ) );

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
        if(empty($session_menu)){
            return false;
        }
        $items = array();
        $str = 'Select * from temp_menu where parent_id="'.$parent.'" and menu_id='.$menuId.' and is_active=true AND id in ("'.implode('","', $session_menu).'")';
        /*if(!empty($session_menu)){
            $str.=' AND id in ("'.implode('","', $session_menu).'")';
        }*/
        $str.=' order by priority ASC';
        $results = $this->CI->pktdblib->custom_query($str);
            //echo '<pre>';
        foreach($results as $result) {
            //print_r($results);
            $child_array = $this->create_nested_menu($result['id'], $menuId, $session_menu);
            if(sizeof($child_array) == 0) {
                $result['sub_menu'] = [];
                array_push($items, $result);
            } else {
                $result['sub_menu'] = $child_array;
                array_push($items, $result);
            }
        }
        //exit;
        return $items;
    }

    function get_target(){
        $query = ['_self'=>'Self', '_new'=>'New', '_blank'=>'Blank']; 
        //print_r($query);
        return $query;
    }

    function get_fiscal_year($date = NULL){
        if(NULL===$date){
            $date = date('Y-m-d');
        }
        $billYear = date('y', strtotime($date)).'-'.date('y', strtotime($date.' +1 year'));
        if(date('m', strtotime($date))<=3)
            $billYear = date('y', strtotime($date.' -1 year')).'-'.date('y', strtotime($date));

        return $billYear;
    }


    function social_share_button($post, $path = NULL){
    //print_r($post);
        //$post = $trend;
        $post_id = $post['id'];
        $post_title = $post['title'];
        if(NULL!==$path){
            $post_url = $path;
        }
        else{
            $post_url = base_url().'news/view/'.$post['slug'];
        }
        $post_author = 'Anonymous';
        $post_content = $post['short_description'];
        if(strlen($post['short_description'])<=200)
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
        $linkedin_href = "https://www.linkedin.com/shareArticle?mini=true&url=".$post_url."&title=".$post_title."&summary=".$short_desc."&source=abhitakk.com";
        $whatsappURL = 'whatsapp://send?text='.$post_url;
        
        ?>
        <a href="<?php echo $whatsappURL; ?>" id="whatsapp1" class="hidden-lg hidden-md"><i class="fa fa-whatsapp custom-icon mesg_options_icon p4" id="watsaapLink1"></i></a>
        <a href="<?php echo $email_href; ?>" id="emailLink1" target="_blank"><i class="fa fa-envelope-o custom-icon mail_options_icon p4"></i></a>
        <a href="<?php echo $facebook_href; ?>" id="facebookLink1" target="_blank"><i class="fa fa-facebook-f custom-icon fb_options_icon p4"></i></a>
        <a href="<?php echo $twitter_href; ?>" id="twitterLink1" target="_blank"><i class="fa fa-twitter custom-icon twit_options_icon p4"></i></a>
        <a href="<?php echo $linkedin_href; ?>" id="linkedinLink1" target="_blank"><i class="fa fa-linkedin custom-icon linkedin_options_icon p4"></i></a>
        <?php
    }

    function createulli($menuArray = [], $ulClass=''){
        $string = '<ul class="'.$ulClass.'">';
        foreach ($menuArray as $key => $menu) {
            $string.= '<li class="menuitem-'.$key.'"> '.anchor($menu['slug'], $menu['name'], ['class'=>'fa '.$menu['class']]);
            if(!empty($menu['sub_menu'])){
                $string.=$this->createulli($menu['sub_menu'], '');
            }

            $string.= '</li>';
        }
        $string.='</li></ul>';
        return $string;
    }

    function curl_request($url, $params = NULL){
        //echo "reached in curl";exit;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if(NULL!==$params)
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        // execute!
        $response = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);
        /*echo '<pre>';
        var_dump($response);
        exit;*/
        return $response;
        // do anything you want with your response
    }

    function rectify_file_type($ext){
        //echo $ext;//exit;
        switch ($ext) {
            case 'gif':
            case 'jpg':
            case 'png':
            case 'jpeg':
            case 'JPG':

                return 'image';
            // do img config setup
            break;
            case 'avi':
            case 'flv':
            case 'wmv':
            case 'mp3':
            case 'wma':
            case 'mov':
            case 'mp4':
            case 'mpg':
            case '3gp':
            case 'asf':
            case 'rm':
            case 'swf':
            case 'amr':
                return 'video';
            // do video config
            break;
        }
    }

    function get_month_count($startDate, $endDate){
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        
        $numberOfMonths = abs((date('Y', $endDate) - date('Y', $startDate))*12 + (date('m', $endDate) - date('m', $startDate)))+1;
        return $numberOfMonths;
    }

    function excel_to_php_date($date){
        //echo $date;exit;
        $UNIX_DATE = ($date - 25569) * 86400;
       // echo gmdate("Y-m-d", $UNIX_DATE);exit;
        return gmdate("Y-m-d", $UNIX_DATE);
    }

    function multipleFiscalyr($num){
        $fiscalyr = [];
        for($i=0;$i<=$num;$i++){
            //echo $i."<br>";
            $date = date('Y-m-d', strtotime('-'.$i.' year'));
            $fiscalyr[$this->get_fiscal_year($date)] = $this->get_fiscal_year($date);
            //echo $fiscalyr."<br>";
        }
        return $fiscalyr;
    }

    function get_datatype(){
        $type = [0=>'Select Datatype','text'=>'Text', 'textarea'=>'Textarea', 'hidden'=>'Hidden', 'password'=>'Password', 'checkbox'=>'Checkbox', 'longtext'=>'Long Text'];
        return $type;
    }

    function uom(){
        return ['MT'=>'M.T.'];
    }

    function order_status(){
        $status = $this->CI->pktdblib->custom_query('Select * from order_status where is_active=true');
        $stat[0]='Deleted';
        foreach($status as $keyStat=>$statValue){
            $stat[$statValue['id']] = $statValue['status'];
        }
        return $stat;
        //return ['1'=>'Pending', '2'=>'Ready To Dispatch', '3'=>'Dispatched', '4'=>'Delivered', '5'=>'Cancelled' ];
    }
    
    function convertNumberToWord($num = false) {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }
    
    function getIndianCurrency($number)
	{
	    $decimal = round($number - ($no = floor($number)), 2) * 100;
	    $hundred = null;
	    $digits_length = strlen($no);
	    $i = 0;
	    $str = array();
	    $words = array(0 => '', 1 => 'one', 2 => 'two',
	        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
	        7 => 'seven', 8 => 'eight', 9 => 'nine',
	        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
	        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
	        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
	        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
	        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
	        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
	    $digits = array('', 'hundred','thousand','lakh', 'crore', 'hundred');
	    while( $i < $digits_length ) {
	        $divider = ($i == 2) ? 10 : 100;
	        $number = floor($no % $divider);
	        $no = floor($no / $divider);
	        $i += $divider == 10 ? 1 : 2;
	        if ($number) {
	            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
	            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
	            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
	        } else $str[] = null;
	    }
	    $Rupees = implode('', array_reverse($str));
	    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise .' only';
	}
	function createMenu($menus){
        if(!$menus){
          return false;
        }
        $current_url =& get_instance();
        $str = '';
        $class = $current_url->router->fetch_class();
        //echo $class;
        foreach ($menus as $key => $menu) {
          $active = "";
          if($menu['module']==$class){
            $active = " active";
          }
          //echo "-- ".$active.' --';
          $str.= '<li class="'.(!empty($menu['sub_menu'])?'treeview':'').$active.'">';
          $str.='<a href="'.(!empty($menu['sub_menu'])?'#':base_url($menu['slug'])).'">';
          $cls = (empty($menu['class']) || trim($menu['class'])=='fa')?'fa-list':$menu['class'];
          $str.='<i class="fa '.$cls.'"></i> <span>'.$menu['name'].'</span>';
          $str.= !empty($menu['sub_menu'])?'<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>':'';;
          $str.='</a>';

          if(!empty($menu['sub_menu'])){
            $str.='<ul class="treeview-menu">';
            $str.=$this->createMenu($menu['sub_menu']);
            $str.='</ul>';
          }
          $str.='</li>';

        }
        return $str;
    }

    function unit_convertion($unit1, $baseUOM){
        /*echo "unit = ".$unit1.'<br/>';
        echo "base uom =".$baseUOM.'<br/>';*/
        switch(strtoupper(trim($unit1))){
            case "GM":
                switch (strtoupper(trim($baseUOM))) {
                    case "KG":
                        return 0.001;
                    break;
                    case "GM":
                        return 1;
                    break;
                    default:
                        return 0;
                        break;
                }
            break;
            case "KG":
                switch (strtoupper(trim($baseUOM))) {
                    case "GM":
                        return 1000;
                        break;
                    case "KG":
                        //echo "reached here";exit;
                        //echo "kg found"."<br/>";//exit;
                        return 1;
                        break;
                    
                    default:
                        # code...
                        break;
                }
            break;
            case "NOS":
                return 1;
            break;
            case "BUN":
                return 1;
            break;
        }
          
    }
}
?>
