<?php 
$input['email'] = array(
						"name" => "email",
						"placeholder" => "email address",
						"maxlength" => "320",
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

echo "<div id='login-container'>";

if(isset($email_errors))
{
	if($email_errors === FALSE)
	{
		header('Refresh: 10; URL=' . base_url() . custom_constants::login_page_url);
		echo "<div class='login-message'>";
		echo "<p>Thanks. You should receive and email shortly with a link to reset your password.
		Remember to check your spam inbox. You will now be redirected back to the login page.</p>
		<p>Please <a href='" . base_url() . custom_constants::login_page_url . "'>click here</a> if you are not redirected.</p>";
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
	?>
<div>
						<?php echo form_open_multipart(custom_constants::reset_password_form_url, ['class'=>'form-horizontal', 'id'=>'login_user']); ?>
							<?php 
							//print_r($this->session);
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Reset Password</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<?php if(isset($err)){ ?>
									<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<h4><i class="icon fa fa-ban"></i> Alert!</h4>
										<?php echo $this->session->flashdata('err'); ?>
									</div>
									<?php } ?>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="inputemail" class="col-sm-4 control-label">Email</label>
												<div class="col-sm-8">
													<?php echo form_input($input['email']); ?>
													<?php echo form_error('email'); ?>
												</div>
											</div>
										</div>
										
									</div><!-- /row -->
									
									
								</div>     
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Reset Password</button>
									
								</div><!-- /.box-footer -->
							</div><!-- /box -->
						<?php echo form_close(); ?> 
					</div><!-- /tab-pane -->
					<?php } ?>