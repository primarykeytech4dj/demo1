<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$input['email'] = array(
						"name" => "email",
						"placeholder" => "email address",
						"maxlength" => "320",
						"required" => "required",
						"class"=>'form-control'
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
<div class="container">
<div class="row">
<div class="col-md-4">
  <div class="login-box">
    <div class="m-title">Email verification resent</div>
    <div class="login-body">
        <?php 
        echo "<div id='login-container'>";

if(isset($email_errors))
{
	if($email_errors === FALSE)
	{
		header('Refresh: 10; URL=' . base_url() . custom_constants::login_page_url);
		echo "<div class='login-message'>";
		echo "<p>Thanks. You should receive and email shortly with a link to reset your password.
		Remember to check your spam inbox. You will now be redirected back to the login page.</p>
		<p>Please <a href='" . base_url() . custom_constants::login_page_url . "' class='blue_anchor'><u>Click here</u></a> if you are not redirected.</p>";
		echo "</div>";
		
		// Don't display the form
		$show_form = FALSE;
		$no_errors = TRUE;
	}
	else
	{
		// Display the form
		$show_form = TRUE;
		$no_errors = FALSE;
		unset($input['email']['value']);
	}
}
else
{
	// Display the form
	$show_form = TRUE;
	$no_errors = TRUE;
}

if($show_form === TRUE)
{
	echo form_open(custom_constants::reset_password_form_url);
	echo form_input($input['email']);
	
	if($no_errors === FALSE)
	{
		echo "<div class='form-errors'>";
		echo "<p>" . $email_errors . "</p>";
		echo "</div>";
	}
	
	if(validation_errors())
	{
		echo "<div class='form-errors'>";
		echo validation_errors();
		echo "</div>";
	}
	
	echo "<br><div class='row'><div class='form-options col-md-4 col-sm-6 col-xs-12 pl0 pr0'>";
	echo form_submit("submit", "Reset Password", ['class'=>'btn btn-info']);
	echo "</div>";
	
	echo "&nbsp;&nbsp;<div class='form-option register-link form-options col-md-6 col-sm-6 col-xs-12 pl0'><a href='" . base_url() . custom_constants::login_page_url . "' class='btn btn-info pull-right'>cancel</a></div></div><div class='clearfix'></div>";
	echo form_close();
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
</div>
<?php //} ?>



