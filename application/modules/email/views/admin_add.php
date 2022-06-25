<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['first_name'] = array(
						"name" => "data[enquiries][first_name]",
						"placeholder" => "first name(s) *",
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

$input['middle_name'] = array(
							'name' => "data[enquiries][middle_name]",
							'placeholder'=> "Middle Name(s) *",
							"max_length" =>"64",
							"required" =>"required",
							"class" =>"form-control",
							"id" => "middle_name",
							 );

$input['company_name'] = array(
							'name' => "data[enquiries][company_name]",
							'placeholder'=> "Company Name(s) *",
							"max_length" =>"64",
							"class" =>"form-control",
							"id" => "middle_name",
							 );

$input['emp_code'] = array(
						"name" => "data[enquiries][emp_code]",
						"placeholder" => "Enquiry Code",
						"class" => "form-control",
						'id' => "emp_code"
					);

$input['contact_1'] =  array(
							"name" => "data[enquiries][contact_1]",
							"placeholder" => "contact 1 *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "contact_1",
							 );

$input['contact_2'] =  array(
							"name" => "data[enquiries][contact_2]",
							"placeholder" => "contact 2",
							"max_length" => "12",
							"class" => "form-control",
							"id" => "contact_2",
							 );

$input['dob'] =  array(		
							"name" => "data[enquiries][dob]",
							"placeholder" => "dob *",
							"max_length" => "12",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "dob"
							 );

$input['primary_email'] =  array(
							"type" => "email",
							"name" => "data[enquiries][primary_email]",
							"placeholder" => "Primary Email *",
							"max_length" => "100",
							"required" => "required",
							"class" => "form-control",
							"id" => "primary_email",
						);

$input['secondary_email'] =  array(
							"type" => "email",
							"name" => "data[enquiries][secondary_email]",
							"placeholder" => "Secondary Email",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "secondary_email",
							 );

$input['message'] =  array(
							"name" => "data[enquiries][message]",
							"placeholder" => "Enquiry Details",
							"class" => "form-control",
							"id" => "message",
							 );

$input['profile_img'] =  array(
							"name" => "profile_img",
							//"required" => "required",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "profile_img",
							"value" =>	set_value('profile_img'),
							 );

$referredby  = array(
				'id' =>	'referred_by',
				'required'	=>	'required',
				'class'	=>	'form-control'
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
<section class="content-header">
  <h1>
    Module :: Lead
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li>
    	<?php echo anchor(custom_constants::admin_enquiries_listing_url, '<i class="fa fa-users"></i> View Leads'); ?></li>
    <li><a href="#"><i class="fa fa-plus-square"></i>New Lead</a></li>

  </ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<div class="tab-content">
					<div class="tab-pane active" id="basic_detail"> 
						<?php echo form_open_multipart(custom_constants::new_enquiry_url, ['class'=>'form-horizontal', 'id'=>'register_enquiry']); 
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">New Enquiry</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="first_name" class="col-sm-2 control-label">FirstName</label>
												<div class="col-sm-10">
													<?php echo form_input($input['first_name']); ?>
													<?php echo form_error('data[enquiries][first_name]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="middle_name" class="col-sm-2 control-label">Middle Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['middle_name']); ?>
													<?php echo form_error('middle_name'); ?>
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
													<?php echo form_error('data[enquiries][surname]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputCompany_name" class="col-sm-2 control-label">Company Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['company_name']); ?>
													<?php echo form_error('data[enquiries][company_name]'); ?>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="contact_1" class="col-sm-2 control-label">Contact 1</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_1']); ?>
													<?php echo form_error('data[enquiries][contact_1]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_2" class="col-sm-2 control-label">Contact 2</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_2']); ?>
													<?php echo form_error('data[enquiries][contact_2]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="primary_email" class="col-sm-2 control-label">Primary Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['primary_email']); ?>
													<?php echo form_error('data[enquiries][primary_email]'); ?>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
								                <label for="secondary_email" class="col-sm-2 control-label">Secondary Email</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['secondary_email']);?>
													<?php echo form_error('data[enquiries][secondary_email]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
            <label for="dob"  class="col-sm-2 control-label">DOB:</label>
            <div class="input-group date">
             <div class="input-group-addon">
             	<i class="fa fa-calendar"></i>
             </div>
             	<!-- <input type="text" class="form-control pull-right" id="datepicker"> -->
             	<?php echo form_input($input['dob']);?>
														<?php echo form_error('data[enquiries][dob]'); ?>
								    </div>
           <!-- /.input group -->
           </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">                          
											<label for="profile_img" class="col-sm-2 control-label">Profile Picture</label>
												<div class="col-sm-10">
													<?php echo form_upload($input['profile_img']); ?>
													<?php echo form_error('profile_img'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="Referred By" class="col-sm-2 control-label">Referred By</label>
												<div class="col-sm-10">
													<?php //print_r($option['referred_by']); ?>
													<?php echo form_dropdown('data[enquiries][referred_by]',$option['referred_by'], '',$referredby);?>
													<?php echo form_error('data[enquiries][referred_by]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputEmp_code" class="col-sm-2 control-label">Enquiry Number</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['emp_code']);?>
													<?php echo form_error('data[enquiries][emp_code]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputMessage" class="col-sm-2 control-label">Enquiry Details</label>
												<div class="col-sm-10">
													<?php //print_r($option['referred_by']); ?>
													<?php echo form_textarea($input['message']); ?>
													<?php echo form_error('data[enquiries][message]'); ?>
												</div>
											</div>
										</div>
										
									</div>
						<!-- /box-body -->  
							                   
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Register</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
							</div>
						<?php echo form_close(); ?> 
					</div><!-- /tab-pane -->
					
					
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

