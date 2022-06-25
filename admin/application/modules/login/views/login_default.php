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
  <div class="login-box-body">
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
    <?php //echo validation_errors(); ?>
    <p class="login-box-msg">Sign in to start your session</p>
  <!--   <?php $attribute = ['autocomplete'=>'off']; ?>
    <?php //echo form_open("login/login", $attribute) ?> -->
    <?php echo form_open(custom_constants::admin_login_url); ?>
    <!-- <font></font>rm action="../../index2.html" method="post" autocomplete="off"> -->
    
    <?php if(NULL !== $this->session->flashdata('message')) {
      $msg = $this->session->flashdata('message');?> 
      <div class="alert<?php echo $msg['class']; ?>">
      <?php echo $msg['message'];?>
      </div>
    <?php } ?>
      <br>
      <div class="form-group has-feedback">
        <!-- <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo set_value('email'); ?>" size="50" > -->
        <?php echo form_input($username_email_input); ?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <span class="text-danger"><?php echo form_error('email'); ?></span>
      </div>
      <div class="form-group has-feedback">
        <!-- <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo set_value('password'); ?>"> -->
        <?php echo form_password($password_input); ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span class="text-danger"><?php echo form_error('password'); ?></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <!--div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div-->
        </div>
       
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <?=anchor(custom_constants::reset_password_url, 'I forgot my password')?>
        <!-- <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a> -->
        <!-- /.col -->
      </div>
    

<?php echo form_close(); ?>
	<?php } ?>
  </div>
  
   <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
      <script type="text/javascript">
        $(document).ready(function(){
           
            checkCookie();
            /*console.log('<?=$_COOKIE['username']?>');
          $("#username").val("<?=$_COOKIE['username']?>");
          $("#password").val("<?=$_COOKIE['password']?>");
          $("#submit").trigger('click');*/
        });
        
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkCookie() {
            var user=getCookie("username");
            var pass=getCookie("password");
            if (user != "") {
                console.log("Welcome again " + user);
                $("#username").val(user);
                $("#password").val(pass);
                $("#submit").trigger('click')
            } /*else {
             user = prompt("Please enter your name:","");
                if (user != "" && user != null) {
                    setCookie("username", user, 30);
                }
            }*/
        }
      </script>


  
 

