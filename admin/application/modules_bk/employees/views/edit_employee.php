<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";

$value = DateTime::createFromFormat('Y-m-d', $values_posted['employees']['dob']);
$value = $value->format('d/m/Y');

$employmentStartDate = DateTime::createFromFormat('Y-m-d', $values_posted['employees']['start_date']);
$employmentStartDate = $employmentStartDate->format('d/m/Y');

$input['first_name'] = array(
						"name" => "data[employees][first_name]",
						"placeholder" => "first name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "first_name",
						//'value' => set_values('first_name', $employees['first_name']),
					);

/*$input['first_name'] = array(
							'value' => set_values('first_name')?set_values('first_name'):$employees['first_name'],
							'id'
						);
*/
					//<?php print_r($Colleges);
												    
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
							"placeholder" => "contact_2 *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "contact_2",
							 );

$input['dob'] =  array(		
							"name" => "data[employees][dob]",
							"placeholder" => "dob *",
							"max_length" => "12",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "dob",
							"value" => $value,
							 );
$input['start_date'] =  array(		
							"name" => "data[employees][start_date]",
							"placeholder" => "Employment Start Date *",
							"max_length" => "12",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "start_date",
							"value" => $employmentStartDate,
							 );

$input['address_1'] =  array(
							"name" => "data[address][address_1]",
							"placeholder" => "address 1*",
							"required" => "required",
							"class" => "form-control",
							"id" => "address_1",
							"rows" => "5",
						);

$input['address_2'] =  array(
							"name" => "data[address][address_2]",
							"placeholder" => "address 2*",
							"required" => "required",
							"class" => "form-control",
							"id" => "address_2",
							"rows" => "5",
						);

$input['emp_code'] =  array(
							"name" => "data[employees][emp_code]",
							"placeholder" => "emp_code *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "emp_code",
						);

$input['primary_email'] =  array(
							"type" => "email",
							"name" => "data[employees][primary_email]",
							"placeholder" => "primary_email *",
							"max_length" => "50",
							"required" => "required",
							"class" => "form-control",
							"id" => "primary_email",
						);

$input['secondary_email'] =  array(
							"type" => "email",
							"name" => "data[employees][secondary_email]",
							"placeholder" => "secondary_email",
							"max_length" => "50",
							"required" => "required",
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

$input['profile_img2'] =  array(
							"data[employees][profile_img2]" => $values_posted['employees']['profile_img'],
							"data[employees][id]" => $id
							//"value" =>	$values_posted['employees']['profile_img'],
							 );
					
$input['email'] = $input['primary_email'];

$input['email_confirmation'] = array(
						"type" => "email",
						"name" => "data[employees][email_confirmation]",
						"placeholder" => "confirm email address *",
						"max_length" => "120",
						"required" => "required",
						"class"=> "form-control",
						"id" => "email_confirmation"
					);
					
$input['password'] = array(
						"name" => "data[employees][password]",
						"placeholder" => "password *",
						"max_length" => "32",
						"required" => "required",
						"class"=> "form-control",
						"id" => "password"
					);
					
$input['password_confirmation'] = array(
						"name" => "data[employees][password_confirmation]",
						"placeholder" => "confirm password *",
						"max_length" => "32",
						"required" => "required",
						"class" => "form-control",
						"id" => "password_confirmation"
					);

/*$option['city'] 	= 	array(
							'1' => 'Mumbai',
					        '2'  	=> 'Delhi',
					        '3' => 'Chennai',
					        '4'=> 'Bhopal',
						);*/

$blood_group	= 	array(
						'id' => 'blood_group_id',
						'required' => 'required',
						'class' => 'form-control'
					);

$city	= 	array(
				'id' => 'city_id',
				'required' => 'required',
				'class' => 'form-control'
			);

$state 	=	array(
				'id'	=>	'state_id',
				'required'	=>	'required',
				'class'	=>	'form-control'
				);
/*$option['country']	=	array(
								'india' => 'India'

						);*/

$country  = array(
				'id' =>	'country_id',
				'required'	=>	'required',
				'class'	=>	'form-control'
			 );

$area  = array(
				'id' =>	'area_id',
				'required'	=>	'required',
				'class'	=>	'form-control'
			 );

$input['pincode'] = array(
						"name" => "data[address][pincode]",
						"placeholder" => "pincode*",
						"max_length" => "6",
						"required" => "required",
						"class" => "form-control",
						"id" => "pincode"
					);
// If form has been submitted with errors populate fields that were already filled
unset($values_posted['employees']['dob']);
unset($values_posted['employees']['start_date']);
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
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<?php 
	           	if($this->session->flashdata('message') !== FALSE) {
		            $msg = $this->session->flashdata('message');?>
		          	<div class = "<?php echo $msg['class'];?>">
		                <?php echo $msg['message'];?>
		          	</div>
	        <?php } ?>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="<?php if($tab=="basic_detail"){echo "active";} ?>"><a href="#basic_detail" data-toggle="tab">User</a></li>
					<li class="<?php if($tab=="address"){echo "active";} ?>"><a href="#address" data-toggle="tab">Address</a></li>
					<li class="<?php if($tab=="salary"){echo "active";} ?>"><a href="#salary" data-toggle="tab">Salary & Other Deductions</a></li>
					<!-- <li class="<?php if($tab=="login"){echo "active";} ?>"><a href="#login" data-toggle="tab">Login</a></li> -->
				</ul> 
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="basic_detail"){echo "active";} ?>" id="basic_detail"> 
						<?php echo form_open_multipart(custom_constants::edit_employee_url ."/".$id, ['class'=>'form-horizontal', 'id'=>'register_user']); 
							//print_r($this->session);
						//echo form_hidden($input['id']); 
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Existing User</h3>
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
													<?php //echo form_error('data[employees][first_name]'); ?>
													<?php //print_r($Colleges);
												      ?>
												      <?php echo form_error('first_name'); ?>
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
												<label for="blood_group_id" class="col-sm-2 control-label">Blood_Group</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[employees][blood_group_id]', $option['blood_group'], '', "id='blood_group_id'  required='required' class='form-control'"); ?>
													<?php echo form_error('data[employees][blood_group_id]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">                          
												<label for="profile_img" class="col-sm-2 control-label">Profile Picture</label>
													<div class="col-sm-10">
														<?php echo form_upload($input['profile_img']); ?>
														<img src="<?php echo !empty($values_posted['employees']['profile_img'])?base_url().'assets/uploads/profile_images/'.$values_posted['employees']['profile_img']:base_url().'assets/uploads/profile_images/defaultm.jpg'; ?>" height="80px" width="80px">
														<?php echo form_error('data[employees][profile_img]'); ?>
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
												<div class="col-sm-10">
													<?php echo form_hidden($input['profile_img2']); ?>
													<?php echo form_error('data[employees][profile_img2]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="contact_1" class="col-sm-2 control-label">Contact_1</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_1']); ?>
													<?php echo form_error('data[employees][contact_1]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_2" class="col-sm-2 control-label">Contact_2</label>
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
								                <label for="start_date"  class="col-sm-2 control-label">Employment Start Date:</label>

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
									</div>
	<?php
	if(isset($form_error))
		{
			echo "<div class='form-errors alert-danger'>";
			echo "<p>" . $form_error . "</p>";
			echo "</div>";
		}
		
		/*if(validation_errors())
		{
			echo "<div class='form-errors'>";
			echo validation_errors();
			echo "</div>";
		}*/
?>
									<!-- <div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="blood_group_id" class="col-sm-2 control-label">Blood_Group</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[blood_group][blood_group_id]', $option['blood_group'], '', "id='blood_group_id'  required='required' class='form-control'"); ?>
												</div>
											</div>
										</div>
									</div> -->
									<!-- <div class="box-header with-border">
										<h3 class="box-title">Address</h3>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="address_1" class="col-sm-2 control-label">Address_1</label>
												<div class="col-sm-10">
													<?php echo form_textarea($input['address_1']); ?>
													<?php echo form_error('address_1'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="address_2" class="col-sm-2 control-label">Address_2</label>
												<div class="col-sm-10">
													<?php echo form_textarea($input['address_2']); ?>
													<?php echo form_error('address_2'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="country_id" class="col-sm-2 control-label">Country</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[address][country_id]', $option['countries'], '', "id='country_id'  required='required' class='form-control'"); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="state_id" class="col-sm-2 control-label">State.</label>
													<div class="col-sm-10">
														<?php echo form_dropdown('data[address][state_id]',$option['states'], '', "id='state_id' required='required' class='form-control'");
														?>
														<?php //echo form_error('state'); ?>
													</div>
											</div>
										</div>
									</div><!-- /row -->
									<!-- <div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="city_id" class="col-sm-2 control-label">City</label>
												<div class="col-sm-10">
													<?php //$city = array('mumbai' ,'delhi' ); ?>
													<?php //$js = 'id = "city" class ="form-control"'; ?>
													<?php echo form_dropdown('data[address][city_id]', $option['cities'], '', "id='city_id'  required='required' class='form-control'"); ?>
													<?php //echo form_error('city'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="area_id" class="col-sm-2 control-label">Area</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[address][area_id]', $option['areas'], '', "id='area_id'  required='required' class='form-control'"); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<!-- <div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="pincode" class="col-sm-2 control-label">Pincode</label>
												<div class="col-sm-10">
													<?php echo form_input($input['pincode']); ?>
													<?php echo form_error('pincode'); ?>
												</div>
											</div>
										</div>
									</div>  --><!-- /row --> 


									<!-- s --> <!-- /box-body -->  
							                   
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
							</div>
						<?php echo form_close(); ?> 
					</div><!-- /tab-pane -->
					<div class="tab-pane <?php if($tab=="address"){echo "active";} ?>" id="address"> 
						<?php echo form_open_multipart(custom_constants::address_url, ['class'=>'form-horizontal', 'id'=>'address']); 
							//print_r($this->session);
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Address</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<!-- <?php if(isset($err)){ ?>
									<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<h4><i class="icon fa fa-ban"></i> Alert!</h4>
										<?php echo $this->session->flashdata('err'); ?>
									</div>
									<?php } ?>
									<div class="box-header with-border">
										<h3 class="box-title">Address</h3>
									</div> -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="address_1" class="col-sm-2 control-label">Address_1</label>
												<div class="col-sm-10">
													<?php echo form_textarea($input['address_1']); ?>
													<?php echo form_error('address_1'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="address_2" class="col-sm-2 control-label">Address_2</label>
												<div class="col-sm-10">
													<?php echo form_textarea($input['address_2']); ?>
													<?php echo form_error('address_2'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="country_id" class="col-sm-2 control-label">Country</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[address][country_id]', $option['countries'], '', "id='country_id'  required='required' class='form-control'"); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="state_id" class="col-sm-2 control-label">State.</label>
													<div class="col-sm-10">
														<?php echo form_dropdown('data[address][state_id]',$option['states'], '', "id='state_id' required='required' class='form-control'");
														?>
														<?php //echo form_error('state'); ?>
													</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="city_id" class="col-sm-2 control-label">City</label>
												<div class="col-sm-10">
													<?php //$city = array('mumbai' ,'delhi' ); ?>
													<?php //$js = 'id = "city" class ="form-control"'; ?>
													<?php echo form_dropdown('data[address][city_id]', $option['cities'], '', "id='city_id'  required='required' class='form-control'"); ?>
													<?php //echo form_error('city'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="area_id" class="col-sm-2 control-label">Area</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[address][area_id]', $option['areas'], '', "id='area_id'  required='required' class='form-control'"); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="pincode" class="col-sm-2 control-label">Pincode</label>
												<div class="col-sm-10">
													<?php echo form_input($input['pincode']); ?>
													<?php echo form_error('pincode'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->


									<!-- s --> <!-- /box-body -->  
							                  
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
					<div class="tab-pane <?php if($tab=="login"){echo "active";} ?>" id="login" >
						<?php echo form_open_multipart(custom_constants::login_page_url, ['class'=>'form-horizontal', 'id'=>'login_user']); ?>
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
									<h3 class="box-title">Login</h3>
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
												<label for="inputuserName" class="col-sm-2 control-label">Username</label>
												<div class="col-sm-10">
													<?php echo form_input($input['username']); ?>
													<?php echo form_error('username'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="inputemail" class="col-sm-2 control-label">Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['email']); ?>
													<?php echo form_error('email'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputPassword" class="col-sm-2 control-label">Password.</label>
												<div class="col-sm-10">
													<?php echo form_input($input['password']);?>
													<?php echo form_error('password'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="for-group">
												<label for="inputConfirmEmail" class="col-sm-2">Confirm Password</label>
												<div class="col-sm-10">
												<?php echo form_input($input['password_confirmation']);?>
												<?php echo form_error('password_confirmation');?>

												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="inputConfirmEmail" class="col-sm-2 control-label">Email Confirm</label>
												<div class="col-sm-10">
													<?php echo form_input($input['email_confirmation']); ?>
													<?php echo form_error('email_confirmation'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="inputemail" class="col-sm-2 control-label">Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['email']); ?>
													<?php echo form_error('email'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
								</div>     
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Update</button>&nbsp;&nbsp;&nbsp;&nbsp;
									<?php //echo nbs(3); ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div><!-- /.box-footer -->
							</div><!-- /box -->
						<?php echo form_close(); ?> 
					</div><!-- /tab-pane -->
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

