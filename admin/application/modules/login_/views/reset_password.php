<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$input['email'] = array(
						"name" => "email",
						"placeholder" => "email address",
						"maxlength" => "320",
						"required" => "required"
					);
					
$input['password'] = array(
						"name" => "password",
						"placeholder" => "password",
						"maxlength" => "32",
						"required" => "required"
					);
					
$input['password_confirmation'] = array(
						"name" => "password_confirmation",
						"placeholder" => "confirm password",
						"maxlength" => "32",
						"required" => "required"
					);
	
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{
	foreach($values_posted as $post_name => $post_value)
	{
		$input[$post_name]['value'] = $post_value;
	}
}

?>

<?php
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="row content login-box-body">
<div class="col-md-4 ">
  <div class="login-box">
    <div class="m-title"><h4><b>Reset Password</b></h4></div>
    <div class="login-body">
        <?php 
       echo "<div id='login-container'>";

if($new_link_sent === TRUE)
{
	header('Refresh: 5; URL=' . base_url() . custom_constants::login_page_url);
	echo "<p>This link has expired. A new reset password link has been sent to {$email}. Please check your spam inbox
	as well. You will now be redirected to the login page.</p>
	<p>Please <a href='" . base_url() . custom_constants::login_page_url . "' class='blue_anchor'><u>Click here</u></a> if you are not redirected.</p>";
}
else
{
	if($success_reset === TRUE)
	{
		header('Refresh: 5; URL=' . base_url() . custom_constants::login_page_url);
		echo "<p>Thanks. Your password has been successfully reset.
		You will now be redirected to the login page.</p>
		<p>Please <a href='" . base_url() . custom_constants::login_page_url . "' class='blue_anchor'><u>Click here</u></a> if you are not redirected.</p>";
	}
	else
	{
		echo form_open(custom_constants::reset_password_url . "/{$verification_string}");
		?>
		<div class="form-group row">
	        <label class="control-label col-sm-3">Email</label>
	        <div class="col-sm-9">
	          <?php echo form_input($input['email']); ?>
	          <span class="text-danger"><?php echo form_error('email'); ?></span>
	        </div>
	    </div>
	    <div class="form-group row">
	        <label class="control-label col-sm-3">Password</label>
	        <div class="col-sm-9">
	          <?php echo form_password($input['password']); ?>
	          <span class="text-danger"><?php echo form_error('password'); ?></span>
	        </div>
	    </div>
	    <div class="form-group row">
	        <label class="control-label col-sm-3">Confirm Password</label>
	        <div class="col-sm-9">
	          <?php echo form_password($input['password_confirmation']); ?>
	          <span class="text-danger"><?php echo form_error('email'); ?></span>
	        </div>
	    </div>
		<?php
		
		if(isset($form_errors))
		{
			echo "<div class='form-errors'>";
			echo "<p>" . $form_errors . "</p>";
			echo "</div>";
		}
		
		if(validation_errors())
		{
			echo "<div class='form-errors'>";
			echo validation_errors();
			echo "</div>";
		}
		
		echo "<div class='form-options'>";
		echo form_submit("submit", "reset password");
		echo "</div>";
		
		echo "<p class='register-link'><a href='" . base_url() . custom_constants::login_page_url . "'>cancel</a></p>";
		
		echo form_close();
	}
}

echo "</div>";

if(defined('custom_constants::main_site_url'))
{
	echo "<div class='login-options'>";
	echo "<p>";
	if(defined('custom_constants::main_site_display'))
	{
		// Link to the main site with full access
		echo "<a href='" . custom_constants::main_site_url . "'>" . custom_constants::main_site_display . "</a>";
	}
	else
	{
		// Link to the main site with full access
		echo "<a href='" . custom_constants::main_site_url . "'>" . custom_constants::main_site_display . "</a>";
	}
	echo "</p>";
	echo "</div>";
}
         ?>
      
    </div>
  </div><!-- end login-box-->

  </div>

  <!-- <div class="col-md-8">
    <div id="login-banner"  class="owl-carousel" style="margin-top:2%;"> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/tree.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/group.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/run.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/road.jpg" class="img-responsive" /> 
      <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/mobile.jpg" class="img-responsive" /> 
    </div>

  </div> -->

</div><!--end content-->
<?php //} ?>

