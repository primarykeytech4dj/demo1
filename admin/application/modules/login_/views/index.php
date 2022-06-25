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

<!-- <div class="banner_home">
  <div class="line hidden-md-down" style="background-color:#3e4095;"></div>
    <div id="login-banner"  class="owl-carousel" style="margin-top:0%;">
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/tree.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/group.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/run.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/road.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/mobile.jpg" class="img-responsive" />
  </div>
</div> -->

<?php /* ?>  
<div class="row content banner_home">
<div class="col-md-4 hidden">
  <div class="login-box">
    <div class="m-title">Member Login</div>
    <div class="login-body">
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
  else
  {
     ?>
     <!--  <form class="form-horizontal" method="post" action="<?php //echo custom::constant(); ?>"> -->
      <?php //echo form_open("login", ['class'=>'form-horizontal']); ?>
      <?php echo form_open("login", ['class'=>'form-horizontal']) ?>
    <!-- <font></font>rm action="../../index2.html" method="post" autocomplete="off"> -->
    
    <?php if(NULL !== $this->session->flashdata('message')) {
      $msg = $this->session->flashdata('message');?> 
      <div class="alert<?php echo $msg['class']; ?>">
      <?php echo $msg['message'];?>
      </div>
      <br>
    <?php } ?>
      <div class="form-group">
        <label class="control-label col-sm-3">User Name</label>
        <div class="col-sm-9">
          <?php echo form_input($username_email_input); ?>
          <span class="text-danger"><?php echo form_error('email'); ?></span>
        </div>
      </div>
      
      <div class="form-group">
        <label class="control-label col-sm-3">Password</label>
        <div class="col-sm-9">          
          <?php echo form_password($password_input); ?>
          <span class="text-danger"><?php echo form_error('password'); ?></span>
        </div>
      </div>
      
      <div class="form-group">        
          <div class="col-sm-offset-3 col-sm-9">
               
            <button type="submit" class="btn btn-default">Login</button>
            
          </div>
        </div>
        <div class="form-group">        
          <div class="col-sm-offset-3 col-sm-9">
           
            <label><?php echo anchor('login/reset-password-form', 'Forgot Password?? Click Here'); ?></label><br>
            <label><?php echo anchor('customers/register', 'New User?? Click Here'); ?></label>
          </div>
        </div>
    

<?php echo form_close(); ?>
        <!-- <div class="form-group">
          <label class="control-label col-sm-3">User Name</label>
          <div class="col-sm-9">
            <input name="data[]" type="text" maxlength="20" id="txtUserID" class="form-control" />
            <span id="rfvLoginUserID" style="color:Red;display:none;">*</span>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3">Password</label>
          <div class="col-sm-9">          
            <input name="txtPassword" type="password" maxlength="30" id="txtPassword" oncopy="return false;" class="form-control" oncut="return false;" onpaste="return false;" />
            <span id="rfvLoginPassword" style="color:Red;display:none;">*</span>
          </div>
        </div>
              
        <div class="form-group">        
          <div class="col-sm-offset-3 col-sm-9">
               
            <input type="submit" name="btnLogin" value="Login" id="btnLogin" class="btn btn-default" style="color:White;background-color:#3E4095;" />
            <span id="lblValidationMessage" style="color:Red;"></span>
          </div>
        </div>
        <div class="form-group">        
          <div class="col-sm-offset-3 col-sm-9">
           
            <label><a href=""> Forgot Password</a></label>
          </div>
        </div>
              <?php echo form_close(); ?> -->
    <?php } ?>
    </div>
  </div><!-- end login-box-->

  <div class="login-box">
    <div class="c-title">Franchise Login</div>
      <div class="login-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="control-label col-sm-3">User Name</label>
            <div class="col-sm-9">
              <input type="text" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3">Password</label>
            <div class="col-sm-9">          
              <input type="password" class="form-control">
            </div>
          </div>
          <div class="form-group">        
            <div class="col-sm-offset-3 col-sm-9">
              <button type="submit" class="btn btn-default">Login</button>
            </div>
          </div>
          <div class="form-group">        
            <div class="col-sm-offset-3 col-sm-9">
                <label><a href="#"> Forgot Password</a></label>
            </div>
          </div>
        </form>
      </div>
    </div><!-- end login-box-->
  </div>

  <div class="col-md-12">
    <div id="login-banner"  class="owl-carousel" style="margin-top:2%;"> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/1.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/4.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/5.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/6.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/7.jpg" class="img-responsive" /> 
    </div>

  </div>

</div><!--end content-->
<?php //} ?>
<?php */ ?>
