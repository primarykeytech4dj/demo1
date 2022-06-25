<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$value='';
// /echo $values_posted['employees']['dob'];exit;
if(!empty($values_posted['employees']['dob']) && $values_posted['employees']['dob']!='0000-00-00'){ 
	$value = date('d/m/Y',strtotime($values_posted['employees']['dob']));
}

$employmentStartDate = date('d/m/Y');
if($values_posted['employees']['start_date']!='0000-00-00'){ 
	$employmentStartDate = date('d/m/Y',strtotime($values_posted['employees']['start_date']));
}

$input['first_name'] = array(
						"name" => "data[employees][first_name]",
						"placeholder" => "first name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "first_name",
						//'value' => set_values('first_name', $employees['first_name']),
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
							'placeholder'=> "Middle Name(s)",
							"max_length" =>"64",
							"class" =>"form-control",
							"id" => "middle_name",
							 );
$input['contact_1'] =  array(
							"name" => "data[employees][contact_1]",
							"placeholder" => "contact 1 *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "contact_1",
							 );

$input['contact_2'] =  array(
							"name" => "data[employees][contact_2]",
							"placeholder" => "contact 2",
							"max_length" => "12",
							"class" => "form-control",
							"id" => "contact_2",
							 );

$input['pan_no'] = array(
						'name' => 'data[employees][pan_no]',
						'placeholder' => 'Pan No',
						'class' => 'form-control',
						'id' => 'pan_no',
						);

$input['adhaar_no'] = array(
						'name' => 'data[employees][adhaar_no]',
						'placeholder' => 'Adhaar Number',
						'class' => 'form-control',
						'id' => 'adhaar_no',
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
							"id"	=> "dob",
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

$input['is_default'] = array(
						"name" => "data[employees][is_default]",
						"class" => "flat-red",
						"id" => "allow_login",
						"type" => "checkbox",
						//"checked" => (TRUE == $address['is_default'])?"checked":'',
						"value" => true,
					);
$type  = array(
				'id' =>	'type',
				'required'	=>	'required',
				'class'	=>	'form-control'
			 );

$input['emp_code'] =  array(
							"name" => "data[employees][emp_code]",
							"placeholder" => "emp_code *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "emp_code",
						);

$input['licence_no'] =  array(
							"name" => "data[employees][licence_no]",
							"placeholder" => "Licence No *",
							"max_length" => "100",
							//"required" => "required",
							"class" => "form-control",
							"id" => "licence_no",
						);

$input['expiry_date'] =  array(
							"name" => "data[employees][expiry_date]",
							"placeholder" => "Expiry Date *",
							"max_length" => "12",
							//"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "expiry_date"
							 );

$state 	=	array(
				'id'	=>	'licence_state_id',
				//'required'	=>	'required',
				'class'	=>	'form-control select2 filter',
				/*'data-link' => 'cities/getStateWiseCities',*/
				'style' => 'width:100%',
				);

$input['profile_img'] =  array(
							"name" => "profile_img",
							"placeholder" => "profile_img *",
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
					

/*$option['city'] 	= 	array(
							'1' => 'Mumbai',
					        '2'  	=> 'Delhi',
					        '3' => 'Chennai',
					        '4'=> 'Bhopal',
						);*/

/*$blood_group	= 	array(
						'id' => 'blood_group',
						'required' => 'required',
						'class' => 'form-control'
					);*/


// If form has been submitted with errors populate fields that were already filled
unset($values_posted['employees']['dob']);
unset($values_posted['employees']['start_date']);
if(isset($values_posted))
{ 	//print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{
		foreach ($post_value as $field_key => $field_value) {
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
		<li><a href="<?php echo base_url().'Employees'; ?>" title="Employees">Other Staff</a></li>
		<li class="active">Edit Other Staff: <b><?php echo $values_posted['employees']['emp_code']; ?></b></li>
	</ol>
</section>
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
					<li class="<?php if($tab=="personal_info"){echo "active";} ?>"><a href="#personal_info" data-toggle="tab">Personal Information</a></li>
					<li class="<?php if($tab=="address"){echo "active";} ?>"><a href="#address" data-toggle="tab">Address</a></li>
					<li class="<?php if($tab=="bank_account"){echo "active";} ?>"><a href="#bank_account" data-toggle="tab">Bank Account</a></li>
					<li class="<?php if($tab=="document"){echo "active";} ?>"><a href="#document" data-toggle="tab">Document</a></li>
					<li class="<?php if($tab=="other"){echo "active";} ?>"><a href="#other" data-toggle="tab">Other Details</a></li>
					<!-- <li class="<?php if($tab=="followup"){echo "active";} ?>"><a href="#followup" data-toggle="tab">Follow Ups</a></li>
					<li class="<?php if($tab=="meeting"){echo "active";} ?>"><a href="#meeting" data-toggle="tab">Schedule Meeting</a></li> -->
					<!-- <li class="<?php if($tab=="login"){echo "active";} ?>"><a href="#login" data-toggle="tab">Login</a></li> -->
					<li class="pull-right">
						<?php echo anchor(custom_constants::admin_employee_view.'/'.$id, '<i class="fa fa-sticky-note"></i>', ['class'=>'text-muted', 'title'=>'View Details']);  ?>
					</li>
				</ul> 
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="personal_info"){echo "active";} ?>" id="personal_info">
						<?php echo form_open_multipart(custom_constants::edit_other_staff_url ."/".$id, ['class'=>'form-horizontal', 'id'=>'admin_edit']); ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Existing Staff</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="first_name" class="col-sm-2 control-label">FirstName</label>
												<div class="col-sm-10">
													<?php echo form_input($input['first_name']); ?>
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
											<label for="profile_img" class="col-sm-2 control-label">Profile Picture</label>
												<div class="col-sm-10">
													<?php echo form_upload($input['profile_img']); ?>
													<?php echo form_hidden($input['profile_img2']); ?>
													<img src="<?php echo !empty($values_posted['employees']['profile_img'])?content_url().'uploads/profile_images/'.$values_posted['employees']['profile_img']:content_url().'uploads/profile_images/default.jpg'; ?>" height="80px" width="80px">
													<?php echo form_error('data[employees][profile_img]'); ?>
												</div>
											</div>
										</div>
<!-- 										<div class="col-md-6">
										<div class="form-group">
											<label for="blood_group" class="col-sm-2 control-label">Blood Group</label>
											<div class="col-sm-10">
												<?php //echo form_dropdown('data[employees][blood_group]', $option['blood_group'], $values_posted['employees']['blood_group'], "id='blood_group'  required='required' class='form-control'"); ?>
												<?php //echo form_error('data[employees][blood_group]'); ?>
											</div>
										</div>
									</div>
									 -->									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
							               <label for="emp_code"  class="col-sm-2 control-label">Employee Code:</label>
							               <div class="col-sm-10">
													<?php echo '<h3>'.$values_posted['employees']['emp_code'].'</h3>'; ?>
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
												<label for="pan_no" class="col-sm-2 control-label">Pan No.</label>
												<div class="col-sm-10">
													<?php echo form_input($input['pan_no']); ?>
													<?php echo form_error('data[employees][pan_no]'); ?>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputAdhaar_no" class="col-sm-2 control-label">Adhaar Number</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['adhaar_no']);?>
													<?php echo form_error('data[employees][adhaar_no]'); ?>
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
								                  	<?php echo form_input($input['start_date']);?>
													<?php echo form_error('data[employees][start_date]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="licence_no" class="col-sm-2 control-label">Driving Licence No</label>
												<div class="col-sm-10">
													<?php echo form_input($input['licence_no']); ?>
													<?php echo form_error('data[employees][licence_no]'); ?>
												</div>
											</div>
										</div>
										
									</div>
									<div class="row">
										
										
										<div class="col-md-6">
											<div class="form-group">
												<label for="expiry_date"  class="col-sm-2 control-label">Licence Expiry Date:</label>

								                <div class="input-group date">
									                <div class="input-group-addon">
									                	<i class="fa fa-calendar"></i>
									                </div>
								                  	<?php echo form_input($input['expiry_date']);?>
													<?php echo form_error('data[employees][expiry_date]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                 <label for="licence_state" class="col-sm-2 control-label">Licence State</label>
								                <div class="col-sm-10">
								                  	<?php echo form_dropdown('data[employees][licence_state]',$option['states'],'', $state);?>
													<?php echo form_error('licence_state'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>

									</div><!-- /row -->


								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
							</div>
						<?php echo form_close(); ?> 
					</div>
					<div class="tab-pane <?php if($tab=="address"){echo "active";} ?>" id="address">
						<?php 
						echo $address;
						echo $addressList; ?> 
					</div>
					<div class="tab-pane <?php if($tab=="bank_account"){echo "active";} ?>" id="bank_account">
						<?php 
						echo $bank_account;
						echo $bankAccountList; ?> 
					</div>
					<div class="tab-pane <?php if($tab=="document"){echo "active";} ?>" id="document">
						<?php 
						echo $document;
						echo $documentList; ?> 
					</div>
					<div class="tab-pane <?php if($tab=="other"){echo "active";} ?>" id="other">
						<?php 
						echo $otherDetailForm;
						echo $otherDetailsList;
						//echo $documentList; ?> 
					</div>

					<div class="tab-pane <?php if($tab=="followup"){echo "active";} ?>" id="followup">
						<?php 
						echo $followupForm;
						echo $followupList;
						//echo $documentList; ?> 
					</div>
					<div class="tab-pane <?php if($tab=="meeting"){echo "active";} ?>" id="meeting">
						<?php 
						echo $meetingForm;
						echo $meetingList;
						//echo $documentList; ?> 
					</div>
					<!-- <div class="tab-pane <?php if($tab=="login"){echo "active";} ?>" id="login">
					
					</div> -->
				</div>
			</div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

