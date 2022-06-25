<?php
//print_r($this->session->flashdata());
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$username_email_input = array(
                        "name" => "username/email",
                        "placeholder" => "Your Email",
                        "maxlength" => "320",
                        "required" => "required",
                        "class" => "form-control input-lg",
                    );


if(custom_constants::email_login_allowed === TRUE)
{
  $username_email_input['placeholder'] = "username/email";
}
          
$password_input = array(
            "name" => "password",
            "placeholder" => "Your Password",
            "maxlength" => "32",
            "required" => "required",
            "class" => "form-control input-lg",
          );
          
$username_email_input['value'] = $this->session->flashdata("username");
?>


  <!-- /.login-logo -->
  <!-- <div class="login-box-body"> -->

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
    }
    else
    {
      echo "<p>If you have forgot your username or password then click on the forgot username or reset
      password link below.</p>";
    }
    echo "</div>";
  }
?>

<div id="breadcrumb-container">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.html">Home</a></li>
                        <li class="active">Login</li>
                    </ul>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <header class="content-title">
                            <h1 class="title">Login or Create An Account</h1>
                            <div class="md-margin"></div>
                        </header>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h2>New Customer</h2>
                                <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
                                <div class="md-margin"></div><!-- <a href="register-account.html" class="btn btn-custom-2">Create An Account</a> -->
                                 <?=anchor(custom_constants::register_url, 'Create An Account', ['class'=>"btn btn-custom-2"])?>
                                <div class="lg-margin"></div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h2>Registered Customers</h2>
                                <p>If you have an account with us, please log in.</p>
                                <div class="xs-margin"></div>
                                <?php echo form_open_multipart(custom_constants::login_page_url, ['class'=>'form-vertical', 'id' => 'register_customer']);
                                  if(isset($form_error)) 
                                    {
                                      echo "<div class='alert alert-danger'>";
                                      echo $form_error;
                                      echo "</div>";
                                    }
                                    //print_r($_SESSION);

                                    if($this->session->flashdata('message')!== FALSE) {
                                      $msg = $this->session->flashdata('message');?>
                                      <div class="<?php echo $msg['class'];?>">
                                        <?php echo $msg['message'];?>
                                      </div>
                                    <?php } ?>
                                <!-- <form id="login-form" method="get" action="#"> -->
                                    <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-email"></span><span class="input-text">Email&#42;</span></span>
                                      <?php echo form_input($username_email_input); ?>
                                      <?php echo form_error('email'); ?>
                                        <!-- <input type="text" required class="form-control input-lg" placeholder="Your Email" name="<?php echo $username_email_input;?>"> -->
                                    </div>
                                    <div class="input-group xs-margin"><span class="input-group-addon"><span class="input-icon input-icon-password"></span><span class="input-text">Password&#42;</span></span>
                                        <?php echo form_password($password_input); ?>
                                         <?php echo form_error('password'); ?>
                                    </div>
                                    <span class="help-block text-right">
                                      <?=anchor(custom_constants::reset_password_form_url, 'Forgot your password?')?>
                                      <!-- <a href="#">Forgot your password?</a> -->
                                    </span>
                                    <button class="btn btn-custom-2">LOGIN</button>
                                </form>
                                <div class="sm-margin"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
