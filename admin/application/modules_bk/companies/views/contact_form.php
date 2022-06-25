<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['first_name'] = array(
						"name" => "data[companies][first_name]",
						"placeholder" => "first name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "first_name",
					);

$input['surname'] = array(
						"name" => "data[companies][surname]",
						"placeholder" => "surname *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "surname"
					);





$input['primary_email'] =  array(
							"type" => "email",
							"name" => "data[companies][primary_email]",
							"placeholder" => "primary_email *",
							"max_length" => "100",
							"required" => "required",
							"class" => "form-control",
							"id" => "primary_email",
						);



$input['subject'] = array(
						"name" => "data[companies][subject]",
						"placeholder" => "subject *",
						"max_length" => "100",
						"required" => "required",
						"class"=> "form-control",
						"id" => "subject"
					);

$input['message'] = array(
						"name" => "data[companies][message]",
						"placeholder" => "message *",
						"max_length" => "100",
						"required" => "required",
						"class"=> "form-control",
						"id" => "message"
					);


// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ //print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{
		foreach ($post_value as $field_key => $field_value) {
			# code...
			$input[$field_key]['value'] = $field_value;
		}
	}
}

?>
<!-- Content Header (Page header) -->
<!-- <section class="content-header">
  <h1>
    Contact Form
  </h1>
  
</section> -->
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">

				<div class="tab-content">
					
						<?php echo form_open_multipart(custom_constants::new_user_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
							//print_r($this->session);
							if(isset($form_error))
							{
								echo "<div class='alert alert-danger'>";
								echo $form_error;
								echo "</div>";
							}
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">New User</h3>
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
												<label for="first_name" class="col-sm-2 control-label">FirstName</label>
												<div class="col-sm-10">
													<?php echo form_input($input['first_name']); ?>
													<?php echo form_error('data[employees][first_name]'); ?>
												</div>
											</div>
										</div>
									<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">LastName</label>
												<div class="col-sm-10">
													<?php echo form_input($input['surname']); ?>
													<?php echo form_error('data[employees][surname]'); ?>
												</div>
											</div>
										</div>
										
									</div><!-- /row -->
									<div class="row">
									<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Surname</label>
												<div class="col-sm-10">
													<?php echo form_input($input['surname']); ?>
													<?php echo form_error('data[employees][surname]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="emp_code" class="col-sm-2 control-label">Employee Code</label>
												<div class="col-sm-10">
													<?php echo form_input($input['emp_code']); ?>
													<?php echo form_error('data[employees][emp_code]'); ?>
												</div>
											</div>
										</div>
									</div>
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Send Message</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
							</div>
						<?php echo form_close(); ?> 
					
					
					
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

