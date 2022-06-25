<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['first_name'] = array(
						"name" => "data[enquiries][first_name]",
						"placeholder" => "Full name *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "first_name",
					);

$input['surname'] = array(
						"name" => "data[enquiries][surname]",
						"placeholder" => "surname *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "surname"
					);





$input['primary_email'] =  array(
							"type" => "email",
							"name" => "data[enquiries][primary_email]",
							"placeholder" => "Email ID *",
							"max_length" => "100",
							"required" => "required",
							"class" => "form-control",
							"id" => "primary_email",
						);
$input['contact_1'] = array(
						"name" => "data[enquiries][contact_1]",
						"placeholder" => "contact no. *",
						"max_length" => "15",
						"class"=> "form-control",
						"id" => "contact_1"
					);
$input['message'] = array(
						"name" => "data[enquiries][message]",
						"placeholder" => "message *",
						"max_length" => "100",
						"required" => "required",
						"class"=> "form-control",
						"rows" =>5,
						"id" => "message"
					);
$input['captcha'] = array(
						"name" => "data[captcha]",
						"placeholder" => "Your Answer *",
						'required' => 'required',
						"class"=> "form-control",
						"id" => "captcha"
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
  <h2>
    Contact Form
  </h2>
  
</section> -->
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">

				<div class="tab-content contact-form">
					
						<?php echo form_open_multipart('enquiries/process_form', ['class'=>'form-horizontal', 'id'=>'contact-us']); 
							//print_r($this->session);
						//echo form_open_multipart('enquiries/process_form', ['class' => 'form-horizontal', 'id' => 'register_enquiry']);
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
										<div class="col-md-12">
											<div class="form-group">
												<!-- <label for="first_name" class="col-sm-2 control-label">Full Name </label> -->
												<?php echo form_input($input['first_name']); ?>
												<?php echo form_error('data[enquiries][first_name]'); ?>
												<!-- <div class="col-sm-10">
												</div> -->
											</div>
										</div>
										<!-- <div class="col-md-6">
											<div class="form-group">
												<label for="lastname" class="col-sm-2 control-label">Last Name </label>
												<div class="col-sm-10">
													<?php echo form_input($input['surname']); ?>
													<?php echo form_error('data[enquiries][surname]'); ?>
												</div>
											</div>
										</div> -->
										
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<!-- <label for="email" class="col-sm-2 control-label">email</label> -->
												<?php echo form_input($input['primary_email']); ?>
												<?php echo form_error('data[enquiries][primary_email]'); ?>
												<!-- <div class="col-sm-10">
												</div> -->
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<!-- <label for="inputContact" class="col-sm-2 control-label">Contact No.</label> -->
												<?php echo form_input($input['contact_1']); ?>
												<?php echo form_error('data[enquiries][subject]'); ?>
												<!-- <div class="col-sm-10">
												</div> -->
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<!-- <label for="inputMessage" class="control-label">Message</label> -->
												
													<?php echo form_textarea($input['message']); ?>
													<?php echo form_error('message'); ?>
												
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputcaptch" class="control-label">Total of <?php echo $num1; ?> + <?php echo $num2; ?> is : </label>
												
													<?php echo form_input($input['captcha']); ?>
													<?php echo form_error('captcha'); ?>
												
											</div>
										</div>
									</div><!-- /row -->
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

