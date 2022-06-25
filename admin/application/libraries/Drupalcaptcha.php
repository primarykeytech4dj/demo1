<?php
/**
* Library for Image captcha
* Code Courtesy: Drupal Captcha Module
* @author Sastha L
*/

//session_start();

define('IMAGE_CAPTCHA_ALLOWED_CHARACTERS', 'aAbBCdEeFfGHhijKLMmNPQRrSTtWXYZ23456789123456789@#$%&*');
//define('IMAGE_CAPTCHA_ALLOWED_CHARACTERS', 'abcdefghijklmnopqrstuvwxyz');
class drupalcaptcha
{
    /*
    * _Constructor
    */
    protected $CI;
    public function __construct($params = array())
    {
        $this->CI =& get_instance();

        $this->CI->load->helper('url');
        $this->CI->config->item('base_url');
        $this->CI->load->database();
        
    }
    /**
    * Function for initiate image creation process
    */
    function createCaptcha()
    {
        if(isset($_SESSION['captcha']['solution']))
            unset($_SESSION['captcha']['solution']);
        $seed    = $this->image_captcha_captcha();
        $this->image_captcha_image($seed['seed']);
    }
    /*
    * Function to find the seed value for image creation
    *
    */
    function image_captcha_captcha()
    {
        echo "hello";
        list($font, $errmsg) = $this->_image_captcha_get_font();
        $allowed_chars = $this->_image_captcha_utf8_split(IMAGE_CAPTCHA_ALLOWED_CHARACTERS);
        $code_length = 5;
        $code = '';
        for ($i = 0; $i < $code_length; $i++) {
          $code .= $allowed_chars[array_rand($allowed_chars)];
        }
        // store the answer in $_SESSION for the image generator function (which happens in another http request)
        $seed = mt_rand();
        $_SESSION['image_captcha'][$seed] = $code;

        $_SESSION['captcha']['solution'] = $code;   
        // build the result to return
        $result = array();
        $result['seed']     = $seed;
        $result['solution'] = $code;
        return $result;
    }
    /**
    * Main image creation function
    * 
    */
    function image_captcha_image($seed=NULL) 
    {
        if(!$seed)
        {
            echo 'exit';
            return;
        }
        // get the code to draw from $_SESSION
        $code = $_SESSION['image_captcha'][$seed];
        // unset the code from $_SESSION to prevent rerendering the CAPTCHA
        unset($_SESSION['image_captcha'][$seed]);
        // only generate an image if there is an code
        if ($code) {
            // generate the image
            $image = $this->_image_captcha_generate_image($code);
            // check of generation was successful
            if (!$image) {
              echo 'Generation of image CAPTCHA failed. Check your image CAPTCHA configuration and especially the used font.';
              exit();
            }
            //return $image;
            // Send the image resource as an image to the client
            header("Content-type: image/jpeg");
            // Following header is needed for Konqueror, which would re-request the image
            // on a mouseover event, which failes because the image can only be generated
            // once. This cache directive forces Konqueror to use cache instead of
            // re-requesting
            //header("Cache-Control: max-age=3600, must-revalidate");
            // print the image as jpg to the client
            imagejpeg($image);
            // Clean up
            imagedestroy($image);
            exit();
        }
    }
    /**
 * base function for generating a image CAPTCHA
 */
    function _image_captcha_generate_image($code) {
        echo "hii";
        // get font
        list($font, $errmsg) = $this->_image_captcha_get_font();
        if (!$font) 
        {
            echo 'Font is not available';
            exit();
        }
        // get other settings
        $font_size = 30;
        $character_spacing = '1.2';
        $characters = $this->_image_captcha_utf8_split($code);
        $character_quantity = count($characters);
        $width = 163; //$character_spacing * $font_size * $character_quantity;
        $height = 2 * $font_size;

        // create image resource
        $image = imagecreatetruecolor($width, $height);
        if (!$image) 
        {
            return FALSE;
        }

        // background
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, $width, $height, $background_color);
        $doubleVision = false;
        // draw text
        //if (variable_get('image_captcha_double_vision', 0)) {
        if ($doubleVision) {
            $result = $this->_image_captcha_image_generator_print_string($image, $width, $height, $font, $font_size, $code, TRUE);
            if (!$result) 
            {
              return FALSE;
            }
        }
        $result = $this->_image_captcha_image_generator_print_string($image, $width, $height, $font, $font_size, $code, FALSE);
        if (!$result) 
        {
            return FALSE;
        }

        // add noise
        $noise_colors = array();
        for ($i = 0; $i < 20; $i++) {
            $noise_colors[] = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        }
          
        // Add additional noise.
        $dotNoise    = 1;
        //if (variable_get('image_captcha_dot_noise', 0)) {
        if ($dotNoise) {
            $this->_image_captcha_image_generator_add_dots($image, $width, $height, $noise_colors);
          }
          $lineNoise    = 0;
          //if (variable_get('image_captcha_line_noise', 0)) {
          if ($lineNoise) {
            $this->_image_captcha_image_generator_add_lines($image, $width, $height, $noise_colors);
          }

          // Distort the image.
          $distortion_amplitude = .25 * $font_size * 1 / 10.0;
          if ($distortion_amplitude > 1) 
          {
            // distortion parameters
            $wavelength_xr = (2+3*lcg_value())*$font_size;
            $wavelength_yr = (2+3*lcg_value())*$font_size;
            $freq_xr = 2 * M_PI / $wavelength_xr;
            $freq_yr = 2 * M_PI / $wavelength_yr;
            $wavelength_xt = (2+3*lcg_value())*$font_size;
            $wavelength_yt = (2+3*lcg_value())*$font_size;
            $freq_xt = 2 * M_PI / $wavelength_xt;
            $freq_yt = 2 * M_PI / $wavelength_yt;

            $distorted_image = imagecreatetruecolor($width, $height);
            if (!$distorted_image) {
              return FALSE;
            }
            $bilinairInterpolation    = true;
           //if (variable_get('image_captcha_bilinair_interpolation', FALSE)) {
           if ($bilinairInterpolation) {
                // distortion with bilineair interpolation
                for ($x = 0; $x < $width; $x++) {
                    for ($y = 0; $y < $height; $y++) {
                        // get distorted sample point in source image
                        $r = $distortion_amplitude * sin($x * $freq_xr + $y * $freq_yr);
                        $theta = $x * $freq_xt + $y * $freq_yt;
                        $sx = $x + $r * cos($theta);
                        $sy = $y + $r * sin($theta);
                        $sxf = (int)floor($sx);
                        $syf = (int)floor($sy);
                        if ($sxf < 0 || $syf < 0 || $sxf >= $width - 1 || $syf >= $height - 1) {
                            $color = $background_color;
                        }else {
                            // bilineair interpolation: sample at four corners
                            $color_00 = imagecolorat($image, $sxf  , $syf  );
                            $color_00_r = ($color_00 >> 16) & 0xFF;
                            $color_00_g = ($color_00 >> 8) & 0xFF;
                            $color_00_b = $color_00 & 0xFF;
                            $color_10 = imagecolorat($image, $sxf+1, $syf  );
                            $color_10_r = ($color_10 >> 16) & 0xFF;
                            $color_10_g = ($color_10 >> 8) & 0xFF;
                            $color_10_b = $color_10 & 0xFF;
                            $color_01 = imagecolorat($image, $sxf  , $syf+1);
                            $color_01_r = ($color_01 >> 16) & 0xFF;
                            $color_01_g = ($color_01 >> 8) & 0xFF;
                            $color_01_b = $color_01 & 0xFF;
                            $color_11 = imagecolorat($image, $sxf+1, $syf+1);
                            $color_11_r = ($color_11 >> 16) & 0xFF;
                            $color_11_g = ($color_11 >> 8) & 0xFF;
                            $color_11_b = $color_11 & 0xFF;
                            // interpolation factors
                            $u  = $sx - $sxf;
                            $v  = $sy - $syf;
                            // interpolate
                            $r = (int)((1-$v)*((1-$u)*$color_00_r + $u*$color_10_r) + $v*((1-$u)*$color_01_r + $u*$color_11_r));
                            $g = (int)((1-$v)*((1-$u)*$color_00_g + $u*$color_10_g) + $v*((1-$u)*$color_01_g + $u*$color_11_g));
                            $b = (int)((1-$v)*((1-$u)*$color_00_b + $u*$color_10_b) + $v*((1-$u)*$color_01_b + $u*$color_11_b));
                            // build color
                            $color = ($r<<16) + ($g<<8) + $b;
                        }
                        imagesetpixel($distorted_image, $x, $y, $color);
                    }
                }
            }else {
                // distortion with nearest neighbor interpolation
                for ($x = 0; $x < $width; $x++) {
                    for ($y = 0; $y < $height; $y++) {
                        // get distorted sample point in source image
                        $r = $distortion_amplitude * sin($x * $freq_xr + $y * $freq_yr);
                        $theta = $x * $freq_xt + $y * $freq_yt;
                        $sx = $x + $r * cos($theta);
                        $sy = $y + $r * sin($theta);
                        $sxf = (int)floor($sx);
                        $syf = (int)floor($sy);
                        if ($sxf < 0 || $syf < 0 || $sxf >= $width - 1 || $syf >= $height - 1) {
                            $color = $background_color;
                        }else {
                            $color = imagecolorat($image, $sxf, $syf);
                        }
                        imagesetpixel($distorted_image, $x, $y, $color);
                    }
                }
            }
            // release undistorted image
            imagedestroy($image);
            //echo 'asdf';print_r($image);exit;
            // return distorted image
            return $distorted_image;
            }else{
                return $image;
            }
    }
/**
 * Returns:
 *  - the path to the image CAPTCHA font or FALSE when an error occured
 *  - error message
*/
    function _image_captcha_get_font() {
        $font = './assets/font-awesome-4.5.0/fonts/times.ttf';
        //$font    = realpath(dirname(__FILE__)).'/fonts/arial.ttf';
        $errmsg = FALSE;
        if ($font != 'BUILTIN' && (!is_file($font) || !is_readable($font))) {
            echo $errmsg = 'Could not find or read the configured font "'.$font.'" for the image captcha.';
            $font = FALSE;
        }
        return array($font, $errmsg);
    }
/**
 * Helper function for splitting an utf8 string correctly in characters.
 * Assumes the given utf8 string is well formed.
 * See http://en.wikipedia.org/wiki/Utf8 for more info
 */
    function _image_captcha_utf8_split($str) {
        $characters = array();
        $len = strlen($str);
        for ($i=0; $i < $len; ) 
        {
            $chr = ord($str[$i]);
            if (($chr & 0x80) == 0x00) { // one byte character (0zzzzzzz)
                $width = 1;
            }else {
                if (($chr & 0xE0) == 0xC0) { // two byte character (first byte: 110yyyyy)
                    $width = 2;
                }
                elseif (($chr & 0xF0) == 0xE0) { // three byte character (first byte: 1110xxxx)
                    $width = 3;
                }
                elseif (($chr & 0xF8) == 0xF0) { // four byte character (first byte: 11110www)
                    $width = 4;
                }
                else {
                    echo 'Encountered an illegal byte while splitting an utf8 string in characters.';
                    return $characters;
                }
            }
            $characters[] = substr($str, $i, $width);
            $i += $width;
        }
        return $characters;
    }
/**
* Function to print the seed over image
*/
    function _image_captcha_image_generator_print_string(&$image, $width, $height, $font, $font_size, $text, $light_colors=FALSE) {
        // get characters
        $characters = $this->_image_captcha_utf8_split($text);
        $character_quantity = count($characters);
        // get total width
        if ($font == 'BUILTIN') {
            $character_width = imagefontwidth(5);
            $character_height = imagefontheight(5);
            $textwidth = $character_quantity * $character_width;
        }
        else {
            $bbox = imagettfbbox($font_size, 0, realpath($font), $text);
            if (!$bbox) {
            return FALSE;
            }
            $textwidth = $bbox[2] - $bbox[0];
        }
        // calculate spacing
        $spacing = ($width - $textwidth) / ($character_quantity + 1);
        // character jittering
        $jittering_x = .3 * $font_size;
        $jittering_y = .3 * $font_size;
        // start cursor
        $x = $spacing;
        foreach ($characters as $character) {
            // get character dimensions
            if ($font != 'BUILTIN') {
              $bbox = imagettfbbox($font_size, 0, realpath($font), $character);
              $character_width = $bbox[2] - $bbox[0];
              $character_height = $bbox[5] - $bbox[3];
            }
            // calculate y position
            $y = .5 * ($height - $character_height);
            // generate random color
            if ($light_colors) {
              $color = imagecolorallocate($image, mt_rand(128, 255), mt_rand(128, 255), mt_rand(128, 255));
            }
            else {
              $color = imagecolorallocate($image, mt_rand(0, 127), mt_rand(0, 127), mt_rand(0, 127));
            }
            // add jitter to position
            $pos_x = $x + mt_rand(-$jittering_x, $jittering_x);
            $pos_y = $y + mt_rand(-$jittering_y, $jittering_y);
            // draw character
            if ($font == 'BUILTIN') {
              imagestring($image, 5, $pos_x, $pos_y, $character, $color);
            } else {
              imagettftext($image, $font_size, 0, $pos_x, $pos_y, $color, realpath($font), $character);
            }
            // shift cursor
            $x += $character_width + $spacing;
        }
        // return a sign of success
        return TRUE;
    }    
/**
* Function place lines in the image background
*/
    function _image_captcha_image_generator_add_lines(&$image, $width, $height, $colors) {
        $line_quantity = $width * $height/200.0 * (5) / 10.0;
        for ($i = 0; $i <  $line_quantity; $i++) 
        {
            imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $colors[array_rand($colors)]);
        }
    }
/**
* Function place dots in the image background
*/
    function _image_captcha_image_generator_add_dots(&$image, $width, $height, $colors) {
        $noise_quantity = ($width * $height)/2.0 * (1) / 10.0;
        for ($i = 0; $i < $noise_quantity; $i++ ) 
        {
            imagesetpixel($image, mt_rand(0, $width), mt_rand(0, $height), $colors[array_rand($colors)]);
        }
    }
/**
* Create a new image
*/
    function get_captcha_image($seedInput = '')
    {
        if($seedInput == "")
            exit;
        else
        {
            /*
            $seed = $seedInput;
            // get the code to draw from $_SESSION
            $code = $_SESSION['image_captcha'][$seed];
            // unset the code from $_SESSION to prevent rerendering the CAPTCHA
            unset($_SESSION['image_captcha'][$seed]);
            // only generate an image if there is an code
            if ($code) 
            {
                // generate the image
                $image = $this->_image_captcha_generate_image($code);
                // check of generation was successful
                if (!$image) {
                    echo 'Generation of image CAPTCHA failed. Check your image CAPTCHA configuration and especially the used font.';
                    exit();
                }
                // Send the image resource as an image to the client
                header("Content-type: image/jpeg");
                // Following header is needed for Konqueror, which would re-request the image
                // on a mouseover event, which failes because the image can only be generated
                // once. This cache directive forces Konqueror to use cache instead of
                // re-requesting
                header("Cache-Control: max-age=3600, must-revalidate");
                // print the image as jpg to the client
                imagejpeg($image);
                // Clean up
                imagedestroy($image);
                exit();
            }
            */
            $this->createCaptcha();
        }
        exit;
    }
/*
set new Image
*/
    function set_captcha_image()
    {
        // generate a CAPTCHA code
        $allowed_chars = $this->_image_captcha_utf8_split(IMAGE_CAPTCHA_ALLOWED_CHARACTERS);
        $code_length = 5;
        $code = '';
        for ($i = 0; $i < $code_length; $i++) {
          $code .= $allowed_chars[array_rand($allowed_chars)];
        }
        $seed = mt_rand();
        $_SESSION['image_captcha'][$seed] = $code;
        $captcha_token=md5(mt_rand());
        $_SESSION['captcha']['solution']=$code;
        print $seed."^".$captcha_token;
        exit;
    }
}
?>