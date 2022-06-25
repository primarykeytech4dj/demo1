<?php
$dob = '';
//print_r($value_posted);
if($value_posted['customers']['dob']!="0000-00-00")
	$dob = date('d/m/Y', strtotime($value_posted['customers']['dob']));
	
$profileImage = 'defaultm.png';
if(isset($value_posted['customers']['profile_img']))
    $profileImage = $value_posted['customers']['profile_img'];

$input['first_name'] = array(
							"name" => "data[customers][first_name]",
							"placeholder" => "First Name *",
							"class" => "form-control",
							"required" => "required",
							"id" => "first_name",
						);

$input['middle_name'] = array(
						'name' => 'data[customers][middle_name]',
						'placeholder' => 'Middle Name *',
/*						'required' => 'required',
*/						'class' => 'form-control',
						'id' => 'middle_name',
						);

$input['surname'] = array(
						'name' => 'data[customers][surname]',
						'placeholder' => 'Surname *',
/*						'required' => 'required',
*/						'class' => 'form-control',
						'id' => 'surname',
						);

$input['company_name'] = array(
						'name' => 'data[customers][company_name]',
						'placeholder' => 'Company Name',
/*						'required' => 'required',
*/						'class' => 'form-control',
						'id' => 'company_name',
						);

$input['primary_email'] = array(
						'name' => 'data[customers][primary_email]',
						'placeholder' => 'Primary email *',
						'required' => 'required',
						'class' => 'form-control',
						'id' => 'primary_email',
						);

$input['secondary_email'] = array(
						'name' => 'data[customers][secondary_email]',
						'placeholder' => 'Secondary email',
						'class' => 'form-control',
						'id' => 'secondary_email',
						);

$input['contact_1'] = array(
						'name' => 'data[customers][contact_1]',
						'placeholder' => 'Contact 1',
						'class' => 'form-control',
						'required' => 'required',
						'id' => 'contact_1',
						);

$input['contact_2'] = array(
						'name' => 'data[customers][contact_2]',
						'placeholder' => 'Contact 2',
						'class' => 'form-control',
						'id' => 'contact_2',
						);

$input['pan_no'] = array(
						'name' => 'data[customers][pan_no]',
						'placeholder' => 'Pan No',
						'class' => 'form-control',
						'id' => 'pan_no',
						);

$input['dob'] = array(
						'name' => 'data[customers][dob]',
						'placeholder' => 'dd/mm/yyyy',
						'class' => 'form-control datemask',
						'id' => 'dob',
						'required' => 'required',
						'value' => $dob,
						);

$input['gst_no'] = array(
						'name' => 'data[customers][gst_no]',
						'placeholder' => 'GST No',
						'class' => 'form-control',
						'id' => 'gst_no',
						);	
$input['adhaar_no'] = array(
						'name' => 'data[customers][adhaar_no]',
						'placeholder' => 'Adhaar No',
						'class' => 'form-control',
						'id' => 'adhaar_no',
						);
						
$input['profile_img'] = array(
							'name' => 'profile_img',
							'placeholder' => 'Profile Image',
							'class' => 'form-control',
							'id' => 'profile_img',
							'value' => '',
						);		

$input['profile_img2'] = array(
								'data[customers][profile_img2]' => !set_value('data[customers][profile_img2]')?$value_posted['customers']['profile_img']:'',
								'data[customers][id]' => $id,
							);				

$blood_group = array(
					'class' => 'form-control',
					'id' => 'blood_group',
				);
unset($value_posted['customers']['dob']);
if(isset($value_posted)) 
	foreach ($value_posted as $post_name => $post_value) {
		foreach ($post_value as $fieldkey => $fieldvalue) {
			$input[$fieldkey]['value'] = $fieldvalue;
		}
	}


?>
<section class="content-header">
	<!-- <h1>Module :: Customer</h1> -->
	<ol class="breadcrumb"> 
		<li><?php echo anchor(custom_constants::customer_page_url, '<i class="fa fa-dashboard"></i>Dashboard') ?></li>
		<li><?php echo anchor(custom_constants::edit_personal_info_url, ' Edit My Profile'); ?></li>
	</ol>
</section>
<!-- <section class="content"> -->
<div class="row content">
	<div class="col-md-1">
	</div>
	<div class="col-md-10">
		<?php 
		if($this->session->flashdata('message')!== FALSE) {
			$msg = $this->session->flashdata('message');?>
			<div class="<?php echo $msg['class'];?>">
				<?php echo $msg['message'];?>
			</div>
		<?php }
		?>
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<!-- <li class="<?php if($tab == "personal_info"){echo "active";}?>">
					<a href="#personal_info" data-toggle="tabs">Personal Information</a></li>
				<li class="<?php if($tab == "address"){echo "active";} ?>">
					<a href="#address" data-toggle="tabs">Address</a></li> -->
				<!-- <li class=""><a href="#login" data-toggle="tabs">Login</a></li> -->
				<li class="nav-item"><a href="#personal_info" data-toggle="tab" class="nav-link <?php if($tab=="personal_info"){echo "active";} ?>">Personal Information</a></li>
				<li class="nav-item"><a href="#address" data-toggle="tab" class="nav-link <?php if($tab=="address"){echo "active";} ?>">Address</a></li>
				<li class="nav-item"><a href="#bank_account" data-toggle="tab" class="nav-link <?php if($tab=="bank_account"){echo "active";} ?>">Bank Account</a></li>
				<li class="nav-item"><a href="#document" data-toggle="tab" class="nav-link <?php if($tab=="document"){echo "active";} ?>">Document</a></li>
				
			</ul>
			<div class="tab-content container" style="padding-top: 50px">
				<div class="tab-pane <?php if($tab == 'personal_info'){ echo "active";}?>" id="personal_info">
					<?php 
					//echo custom_constants::edit_personal_info_url;
					echo form_open_multipart(custom_constants::edit_personal_info_url, ['class' =>'form-horizontal', 'id' => 'register_customer']); ?>
					<div class="box box-info">
						<!-- <div class="box-header with-border">
							<h3 class="box-title">Edit My Profile</h3>
						</div ><!-- /box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
										<div class="col-sm-10">
											<?php echo form_input($input['first_name']); ?>
											<?php echo form_error('first_name');?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputMiddleName" class="col-sm-2 control-label">Middle Name</label>
										<div class="col-sm-10">
											<?php echo form_input($input['middle_name']);?>
											<?php echo form_error('middle_name'); ?>
										</div>
									</div>
								</div>
							</div><!-- /row -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputFirstName" class="col-sm-2 control-label">Surname</label>
										<div class="col-sm-10">
											<?php echo form_input($input['surname']); ?>
											<?php echo form_error('surname');?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputCompanyName" class="col-sm-2 control-label">Company Name</label>
										<div class="col-sm-10">
											<?php echo form_input($input['company_name']);?>
											<?php echo form_error('company_name'); ?>
										</div>
									</div>
								</div>
							</div><!-- /row -->
							<div class="row">
                                <div class="col-md-6">
									<div class="form-group row">
										<label for="inputPrimaryEmail" class="col-sm-2 control-label">Primary Email</label>
										<div class="col-sm-10">
											<?php echo form_input($input['primary_email']);?>
											<?php echo form_error('data[customers][primary_email]'); ?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputSecondaryEmail" class="col-sm-2 control-label">Secondary Email</label>
										<div class="col-sm-10">
											<?php echo form_input($input['secondary_email']);?>
											<?php echo form_error('data[customers][secondary_email]'); ?>
										</div>
									</div>
								</div>
								
							</div><!-- /row -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputContact_1" class="col-sm-2 control-label">Contact 1</label>
										<div class="col-sm-10">
											<?php echo form_input($input['contact_1']); ?>
											<?php echo form_error('contact_1');?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputContact_2" class="col-sm-2 control-label">Contact 2</label>
										<div class="col-sm-10">
											<?php echo form_input($input['contact_2']);?>
											<?php echo form_error('contact_2'); ?>
										</div>
									</div>
								</div>
								
								
							</div><!-- /row -->
							<div class="row">
                                <div class="col-md-6">
									<div class="form-group row">
										<label for="inputDob" class="col-sm-2 control-label">Dob</label>
										<div class="col-sm-10">
											<?php echo form_input($input['dob']);?>
											<?php echo form_error('data[customers][dob]'); ?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputPanNo" class="col-sm-2 control-label">Pan No</label>
										<div class="col-sm-10">
											<?php echo form_input($input['pan_no']); ?>
											<?php echo form_error('data[customers][pan_no]');?>
										</div>
									</div>
								</div>

							</div><!-- /row -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputPanNo" class="col-sm-2 control-label">Adhaar No</label>
										<div class="col-sm-10">
											<?php echo form_input($input['adhaar_no']); ?>
											<?php echo form_error('data[customers][adhaar_no]');?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputGstNo" class="col-sm-2 control-label">GST No</label>
										<div class="col-sm-10">
											<?php echo form_input($input['gst_no']);?>
											<?php echo form_error('data[customers][gst_no]'); ?>
										</div>
									</div>
								</div>

								
							</div><!-- /row -->
							<div class="row">
							    <div class="col-md-6">
									<div class="form-group row">
										<label for="inputBloodgroup" class="col-sm-2 control-label">Blood Group</label>
										<div class="col-sm-10">
											<?php 
											//print_r($option);
											echo form_dropdown('data[customers][blood_group]', $option['blood_group'], $value_posted['customers']['blood_group'], "id= 'blood_group', required='required', class='form-control select2'"); ?>
											<?php echo form_error('blood_group');?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label for="inputProfileImage" class="col-sm-2 control-label">Profile Image</label>
										<div class="col-sm-10">
											<?php echo form_upload($input['profile_img']);?>
											<?php echo form_hidden($input['profile_img2']); ?>
											<img src="<?php echo !empty($value_posted['customers']['profile_img'])?base_url().'/assets/uploads/profile_images/'. $value_posted['customers']['profile_img']:base_url().'assets/uploads/profile_images/defaultm.jpg'; ?>" height="100px" width="100px">
											<?php echo form_error('data[customers][profile_img]'); ?>
										</div>
									</div>
									
								</div>
							</div>
						</div><!-- /box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-info pull-left">Update</button>
							<?php echo nbs(3); ?>
							<button type="submit" class="btn btn-info">Cancel</button>
						</div><!-- /box-footer -->
					</div><!-- /box box-info -->
					<?php echo form_close(); ?>
				</div> 
				<div class="tab-pane <?php if($tab == 'address'){ echo "active";}?>" id="address">
				<?php
					echo $address;
					//echo $addressList;
				?>
				</div>
				<div class="tab-pane <?php if($tab == 'bank_account'){ echo "active";}?>" id="bank_account">
				<?php
					echo $bank_account;
					//echo $bankAccountList;
				?>
				</div>
				<div class="tab-pane <?php if($tab == 'document'){ echo "active";}?>" id="document">
				<?php
					echo $document;
					echo $documentList;
				?>
				</div>
				
			</div>
		</div><!-- /nav-tabs-custom -->
	</div><!-- /col-md-12 -->
	<div class="col-md-1">
	</div>
</div>
<!-- </section> -->