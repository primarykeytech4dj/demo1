<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$username_email_input = array(
                        "name" => "username/email",
                        "placeholder" => "username",
                        "maxlength" => "320",
                        "required" => "required",
                        "class" => "form-control",
                    );

if(custom_constants::email_login_allowed === TRUE)
{
    $username_email_input['placeholder'] = "username/email";
}
                    
$password_input = array(
                        "name" => "password",
                        "placeholder" => "password",
                        "maxlength" => "32",
                        "required" => "required",
                        "class" => "form-control",
                    );
                    
$username_email_input['value'] = $this->session->flashdata("username");
?>
<?php 

    if($timeout_left !== FALSE)
    {
        echo "<div class='form-errors'>";
        echo "<p>You have been locked out for too many incorrect login attempts. Please
        wait " . ceil($timeout_left) . " minutes before attempting to login again.</p>";
        
        if(custom_constants::email_login_allowed === TRUE)
        {
            echo "<p>If you have forgot your password then click on the reset
            password link below.</p>";
      echo anchor(custom_constants::reset_password_url, 'Forgot Password');
        }
        else
        {
            echo "<p>If you have forgot your username or password then click on the forgot username or reset
            password link below.</p>";

      echo anchor(custom_constants::forgot_username, 'Forgot username');

        }
        echo "</div>";
    }
    else
    {
     ?>
            <?php echo form_open_multipart(custom_constants::login_url, ['class'=>'form-vertical', 'id' => 'register_customer']);?>
                <?php if(NULL !== $this->session->flashdata('message')) {
                      $msg = $this->session->flashdata('message');?> 
                      <div class="alert<?php echo $msg['class']; ?>">
                      <?php echo $msg['message'];?>
                      </div>
                    <?php } ?>    
                
            </form>
 <?php } ?>
        