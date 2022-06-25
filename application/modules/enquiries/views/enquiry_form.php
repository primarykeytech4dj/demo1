<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['first_name'] = array(
						"name" => "data[enquiries][first_name]",
						"placeholder" => "Full name *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control input-lg",
						"id" => "first_name",
					);
$input['primary_email'] =  array(
							"type" => "email",
							"name" => "data[enquiries][primary_email]",
							"placeholder" => "Email ID *",
							"max_length" => "100",
							"required" => "required",
							"class" => "form-control input-lg",
							"id" => "primary_email",
						);
$input['contact_1'] = array(
						"name" => "data[enquiries][contact_1]",
						"placeholder" => "contact no. *",
						"max_length" => "15",
						"required"=>'required',
						"class"=> "form-control input-lg",
						"id" => "contact_1"
					);
$input['message'] = array(
						"name" => "data[enquiries][message]",
						"placeholder" => "message *",
						"required" => "required",
						"class"=> "form-control input-lg",
						"rows" =>5,
						"id" => "message"
					);
$input['captcha'] = array(
						"name" => "data[captcha]",
						"placeholder" => "Your Answer *",
						'required' => 'required',
						"class"=> "form-control input-lg",
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

<!--Main content -->
<div class="basic-contact-form ptb-20">
	<div class="container container2">
		<div class="area-title text-center">
			<h2>Enquiry For <?=$product['product']?></h2>
		</div>
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
			<?php echo form_open_multipart('enquiries/process_form', ['id'=>'contact-form', 'class'=>'form-vertical']); 
			 	echo form_hidden(['data[enquiry_details][product_id]'=>$product['id']]);
			 	echo form_hidden(['data[redirectUrl]'=>(NULL!==$params['redirectUrl'])?$params['redirectUrl']:$this->router->fetch_class().'/'.$this->router->fetch_method()]);
				if($this->session->flashdata('message') !== FALSE) {
					$msg = $this->session->flashdata('message');?>
					<div class = "<?php echo $msg['class'];?>">
						<?php echo $msg['message'];?>
					</div>
				<?php } ?>
					<div class="row">
						<div class="col-md-12 form-group">
							<div class="form-group">
								<label for="inputFirst_name" class="control-label">Full Name</label>
								<?php echo form_input($input['first_name']); ?>
								<p class="help-block text-danger">
									<?php echo form_error('data[enquiries][first_name]'); ?>
								</p>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<label for="inputPrimary_email" class="control-label">Email</label>
							<?php echo form_input($input['primary_email']); ?>
							<p class="help-block text-danger">
								<?php echo form_error('data[enquiries][primary_email]'); ?>
							</p>
						</div>
						<div class="col-md-12 form-group">
							<label for="inputContact_1" class="control-label">Contact No</label>
							<?php echo form_input($input['contact_1']); ?>
							<p class="help-block text-danger">
								<?php echo form_error('data[enquiries][contact_1]'); ?>
							</p>
						</div>
						<div class="col-md-12 form-group">
							<label for="inputMessage" class="control-label">Message</label>
							<?php echo form_textarea($input['message']); ?>
							<p class="help-block text-danger"><?php echo form_error('data[enquiries][message]'); ?></p>
						</div>
						<div class="col-md-12 form-group">
							<div class="form-group">
								<label for="inputcaptcha" class="control-label">Total of <?php echo $enquiryNum1; ?> + <?php echo $enquiryNum2; ?> is : </label>
								<?php echo form_input($input['captcha']); ?>
								<?php echo form_error('captcha'); ?>
							</div>
						</div>
						<div class="col-md-12 text-center">
							<button type="submit" class="btn btn-lg btn-round btn-dark">Post Enquiry</button>
							<button type="submit" class="btn btn-lg btn-round btn-dark popup-modal-dismiss">Cancel</button>
						</div>
					</div><!-- .row -->
				</form>
			<!-- Ajax response -->
				<div class="ajax-response text-center"></div>
			</div>
		</div>
	</div>
</div>
