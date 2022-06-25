<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['first_name'] = array(
						"name" => "data[employees][first_name]",
						"placeholder" => "first name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "first_name",
					);

$input['surname'] = array(
						"name" => "data[employees][surname]",
						"placeholder" => "surname *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "surname"
					);

$input['middle_name'] = array(
							'name' => "data[employees][middle_name]",
							'placeholder'=> "Middle Name(s) *",
							"max_length" =>"64",
							"required" =>"required",
							"class" =>"form-control",
							"id" => "middle_name",
							 );

$input['emp_code'] = array(
						"name" => "data[employees][emp_code]",
						"placeholder" => "Employee Code *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "emp_code"
					);

$input['contact_1'] =  array(
							"name" => "data[employees][contact_1]",
							"placeholder" => "contact_1 *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "contact_1",
							 );

$input['contact_2'] =  array(
							"name" => "data[employees][contact_2]",
							"placeholder" => "contact_2",
							"max_length" => "12",
							"class" => "form-control",
							"id" => "contact_2",
							 );

$input['dob'] =  array(		
							"name" => "data[employees][dob]",
							"placeholder" => "dob *",
							"max_length" => "12",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "dob"
							 );

$input['pan_no'] = array(
	                      "name" => "data[employees][pan_no]",
	                      "placeholder" => "Pan No",
	                      "class" => "form-control",
	                      "id" => "pan_no",
                    	);
$input['adhaar_no'] = array(
                      "name" => "data[employees][adhaar_no]",
                      "placeholder" => "Aadhar Number *",
                      
                      "class" => "form-control",
                      "id" => "adhaar_no",
                    );

$input['start_date'] =  array(		
							"name" => "data[employees][start_date]",
							"placeholder" => "Employee Start Date *",
							"max_length" => "12",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "start_date"
							 );

$input['emp_code'] =  array(
							"name" => "data[employees][emp_code]",
							"placeholder" => "Employee Code",
							"max_length" => "12",
							"class" => "form-control",
							"id" => "emp_code",
						);

$input['primary_email'] =  array(
							"type" => "email",
							"name" => "data[employees][primary_email]",
							"placeholder" => "primary_email *",
							"max_length" => "100",
							"required" => "required",
							"class" => "form-control",
							"id" => "primary_email",
						);

$input['secondary_email'] =  array(
							"type" => "email",
							"name" => "data[employees][secondary_email]",
							"placeholder" => "secondary_email",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "secondary_email",
							 );

$input['username'] = array(
						"name" => "data[employees][username]",
						"placeholder" => "username *",
						"max_length" => "24",
						"required" => "required",
						"class"=> "form-control",
						"id" => "username"
					);

$input['profile_img'] =  array(
							"name" => "profile_img",
							"placeholder" => "profile_img *",
							//"required" => "required",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "profile_img",
							"value" =>	set_value('profile_img'),
							 );
$input['allow_login'] = array(
						"name" => "data[employees][allow_login]",
						"class" => "flat-red",
						"id" => "allow_login",
						"type" => "checkbox",
						"value" => true,
					);

$blood_group	= 	array(
						'id' => 'blood_group',
						/*'required' => 'required',*/
						'class' => 'form-control'
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
    Module :: Employees
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li>
    	<?php echo anchor(custom_constants::admin_employees_listing_url, 'Employees'); ?>
    		
    </li>
    <li class="active">New Employees</li>
  </ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="basic_detail"){echo "active";} ?>" id="basic_detail"> 
						<?php echo form_open_multipart(custom_constants::new_employee_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
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
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="blood_group" class="col-sm-2 control-label">Blood Group</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[employees][blood_group]', $option['blood_group'], '', "id='blood_group' class='form-control'"); ?>
													<?php echo form_error('data[employees][blood_group]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="dob"  class="col-sm-2 control-label">DOB:</label>

								                <div class="input-group date">
									                <div class="input-group-addon">
									                	<i class="fa fa-calendar"></i>
									                </div>
								                  	<!-- <input type="text" class="form-control pull-right" id="datepicker"> -->
								                  	<?php echo form_input($input['dob']);?>
													<?php echo form_error('data[employees][dob]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="contact_1" class="col-sm-2 control-label">Contact 1</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_1']); ?>
													<?php echo form_error('data[employees][contact_1]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_2" class="col-sm-2 control-label">Contact 2</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_2']); ?>
													<?php echo form_error('data[employees][contact_2]'); ?>
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
													<?php echo form_error('data[employees][primary_email]'); ?>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
								                <label for="secondary_email" class="col-sm-2 control-label">Secondary Email</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['secondary_email']);?>
													<?php echo form_error('data[employees][secondary_email]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									<div class="row">
						              <div class="col-md-6">
						                <div class="form-group">
						                  <label for="inputPan_no" class="col-sm-2 control-label">Pan No.</label>
						                  <div class="col-sm-10">
						                    <?php echo form_input($input['pan_no']);?>
						                    <?php echo form_error('$data[employees][pan_no]'); ?>
						                  </div>
						                </div>
						              </div>
						              <div class="col-md-6">
						                <div class="form-group">
						                   <label for="inputAadharNo" class="col-sm-2 control-label">Adhaar Number</label>
						                  <div class="col-sm-10">
						                    <?php echo form_input($input['adhaar_no']);?>
						                    <?php echo form_error('$data[employees][adhaar_no]'); ?>
						                  </div>
						                </div>
						              </div>
						            </div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
								                <label for="dob"  class="col-sm-2 control-label">Joining Date:</label>

								                <div class="input-group date">
									                <div class="input-group-addon">
									                	<i class="fa fa-calendar"></i>
									                </div>
								                  	<!-- <input type="text" class="form-control pull-right" id="datepicker"> -->
								                  	<?php echo form_input($input['start_date']);?>
													<?php echo form_error('data[employees][start_date]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="allow_login" class="col-sm-2 control-label">Allow Login</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['allow_login']);?>
													<?php echo form_error('data[employees][allow_login]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div>
									<div class="row">
										
										<div class="col-md-6">
											<div class="form-group">                          
											<label for="profile_img" class="col-sm-2 control-label">Profile Picture</label>
												<div class="col-sm-10">
													<?php echo form_upload($input['profile_img']); ?>
													<?php echo form_error('profile_img'); ?>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">                          
											<label for="profile_img" class="col-sm-2 control-label">Select Role</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[user_roles][][role_id]', $option['roles'], '', "id='role_id' class='form-control select2' multiple"); ?>
													<?php echo form_error('data[user_roles][role_id]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->

									<!-- s --> <!-- /box-body -->  
							                   
								</div><!-- /box -->
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Register</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div>
						<?php echo form_close(); ?> 
					</div><!-- /tab-pane -->
					
					
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

