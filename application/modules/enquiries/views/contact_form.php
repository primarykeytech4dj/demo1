<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['first_name'] = array(
						"name" => "data[enquiries][first_name]",
						"placeholder" => "first name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
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
							"placeholder" => "primary email *",
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

						<?php echo form_open_multipart('enquiries/process_form', ['class'=>'form-vertical', 'id'=>'register_enquiry']); 
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
							
									<?php if(isset($err)){ ?>
									<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<h4><i class="icon fa fa-ban"></i> Alert!</h4>
										<?php echo $this->session->flashdata('err'); ?>
									</div>
									<?php } ?>
									<div class="control-group">
                                        <label class="control-label" for="first_name">
                                            Name
                                            <span class="form-required" title="This field is required.">*</span>
                                        </label>
                                        <div class="controls">
                                            <?php echo form_input($input['first_name']); ?>
											<?php echo form_error('data[enquiries][first_name]'); ?>
                                        </div><!-- /.controls -->
                                    </div><!-- /.control-group -->
                                    <div class="control-group">
                                        <label class="control-label" for="primary_email">Email ID
                                            <span class="form-required" title="This field is required.">*</span>
                                        </label>
                                        <div class="controls">
                                            <?php echo form_input($input['primary_email']); ?>
											<?php echo form_error('data[enquiries][primary_email]'); ?>
                                        </div><!-- /.controls -->
                                    </div><!-- /.control-group -->
                                    <div class="control-group">
                                        <label class="control-label" for="contact_1">Contact
                                            <span class="form-required" title="This field is required.">*</span>
                                        </label>
                                        <div class="controls">
                                            <?php echo form_input($input['contact_1']); ?>
											<?php echo form_error('data[enquiries][contact_1]'); ?>
                                        </div><!-- /.controls -->
                                    </div><!-- /.control-group -->
                                    <div class="control-group">
                                        <label class="control-label" for="inputMessage">
                                            Message
                                            <span class="form-required" title="This field is required.">*</span>
                                        </label>

                                        <div class="controls">
                                            <?php echo form_textarea($input['message']); ?>
											<?php echo form_error('data[enquiries][message]'); ?>
                                        </div><!-- /.controls -->
                                    </div><!-- /.control-group -->
                                    <div class="control-group">
                                        <label class="control-label" for="captcha">Total of <?php echo $num1; ?> + <?php echo $num2; ?> is : 
                                            <span class="form-required" title="This field is required.">*</span>
                                        </label>
                                        <div class="controls">
                                            <?php echo form_input($input['captcha']); ?>
											<?php echo form_error('captcha'); ?>
                                        </div><!-- /.controls -->
                                    </div><!-- /.control-group -->
									
									<div class="form-actions">
                                        <input type="submit" class="btn btn-primary arrow-right" value="Send">
                                    </div><!-- /.form-actions -->
									
						<?php echo form_close(); ?> 
				
