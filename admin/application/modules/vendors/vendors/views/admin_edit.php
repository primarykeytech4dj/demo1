<?php

$input['vendor_category_id'] = array(
                      "name" => "data[vendors][vendor_category_id]",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "vendor_category_id",
                    );
$input['first_name'] = array(
							"name" => "data[vendors][first_name]",
							"placeholder" => "First Name *",
							"class" => "form-control",
							"required" => "required",
							"id" => "first_name",
						);

$input['middle_name'] = array(
						'name' => 'data[vendors][middle_name]',
						'placeholder' => 'Middle Name *',
/*						'required' => 'required',
*/						'class' => 'form-control',
						'id' => 'middle_name',
						);
$input['emp_code'] = array(
                      "name" => "data[vendors][emp_code]",
                      "placeholder" => "Client Code",
                      "class" => "form-control",
                      "id" => "emp_code",
                    );

$input['surname'] = array(
						'name' => 'data[vendors][surname]',
						'placeholder' => 'Surname *',
/*						'required' => 'required',
*/						'class' => 'form-control',
						'id' => 'surname',
						);
$input['adhaar_no'] = array(
                      "name" => "data[vendors][adhaar_no]",
                      "placeholder" => "Adhaar Number",
                      
                      "class" => "form-control",
                      "id" => "adhaar_no",
                    );

$input['company_name'] = array(
						'name' => 'data[vendors][company_name]',
						'placeholder' => 'Company Name *',
						'required' => 'required',
						'class' => 'form-control',
						'id' => 'company_name',
						);

$input['primary_email'] = array(
						'name' => 'data[vendors][primary_email]',
						'placeholder' => 'Primary email *',
						'required' => 'required',
						'class' => 'form-control',
						'id' => 'primary_email',
						);

$input['secondary_email'] = array(
						'name' => 'data[vendors][secondary_email]',
						'placeholder' => 'Secondary email',
						'class' => 'form-control',
						'id' => 'secondary_email',
						);

$input['contact_1'] = array(
						'name' => 'data[vendors][contact_1]',
						'placeholder' => 'Contact 1',
						'class' => 'form-control',
						'required' => 'required',
						'id' => 'contact_1',
						);

$input['contact_2'] = array(
						'name' => 'data[vendors][contact_2]',
						'placeholder' => 'Contact 2',
						'class' => 'form-control',
						'id' => 'contact_2',
						);

$input['pan_no'] = array(
						'name' => 'data[vendors][pan_no]',
						'placeholder' => 'Pan No',
						'class' => 'form-control',
						'id' => 'pan_no',
						);

$input['gst_no'] = array(
						'name' => 'data[vendors][gst_no]',
						'placeholder' => 'GST No',
						'class' => 'form-control',
						'id' => 'gst_no',
						);	
						
$input['profile_img'] = array(
							'name' => 'profile_img',
							'placeholder' => 'Profile Image',
							'class' => 'form-control',
							'id' => 'profile_img',
							'value' => set_value('profile_img'),
						);		

$input['profile_img2'] = array(
								'data[vendors][profile_img2]' => $values_posted['vendors']['profile_img'],
								'data[vendors][id]' => $id,
							);	

$input['has_multiple_sites'] = array(
                      "name" => "data[vendors][has_multiple_sites]",
                      "type" => 'checkbox',
                      "class" => "form-control flat-red",
                      "id" => "has_multiple_sites",
                    );	

$input['is_active'] = array(
                      "name" => "data[vendors][is_active]",
                      "type" => 'checkbox',
                      "class" => "form-control flat-red",
                      "id" => "is_active",
                    );		

$blood_group = array(
					'class' => 'form-control',
					'id' => 'blood_group',
				);
//echo '<pre>';
//print_r($values_posted);
if(isset($values_posted)) 
	foreach ($values_posted as $post_name => $post_value) {
		foreach ($post_value as $fieldkey => $fieldvalue) {
		    //echo $fieldkey." ".$fieldvalue."<br>";
			if(isset($input[$fieldkey]['type']) && $input[$fieldkey]['type']=='checkbox' && $fieldvalue==1){
		        $input[$fieldkey]['checked'] = "checked";
			}else
				$input[$fieldkey]['value'] = $fieldvalue;
		}
	}


?>
<section class="content-header">
	<h1><?=$title?></h1>
	<ol class="breadcrumb"> 
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><?php echo anchor(custom_constants::admin_vendor_listing_url, 'Vendor'); ?></li>
	</ol>
</section>
<section class="content">
<div class="row">
	<div class="col-md-12">
		<?php if(isset($error)) {
			echo "<div class='alert alert-danger'>";
			echo "<p>" .$form_error."</p>";
			echo "</div>";
		}
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
				<li class="<?php if($tab=="personal_info"){echo "active";} ?>"><a href="#personal_info" data-toggle="tab">Personal Information</a></li>
				<li class="<?php if($tab=="address"){echo "active";} ?>"><a href="#address" data-toggle="tab">Address</a></li>
				<li class="<?php if($tab=="bank_account"){echo "active";} ?>"><a href="#bank_account" data-toggle="tab">Bank Account</a></li>
				<li class="<?php if($tab=="document"){echo "active";} ?>"><a href="#document" data-toggle="tab">Document</a></li>
				<?php if($values_posted['vendors']['has_multiple_sites']){
					?>
					<li class="<?php if($tab=="sites"){echo "active";} ?>"><a href="#sites" data-toggle="tab">Sites</a></li>
					<?php
				} ?>
				
			</ul>
			<div class="tab-content">
				<div class="tab-pane <?php if($tab == 'personal_info'){ echo "active";}?>" id="personal_info">
					<?php echo form_open_multipart(custom_constants::edit_vendor_url."/".$id, ['class' =>'form-horizontal', 'id' => 'register_vendor']); ?>
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title"><?=$heading?></h3>
						</div><!-- /box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-4">
					                <div class="form-group">
					                  <label for="inputCompanyName" class="col-sm-2 control-label">Category</label>
					                  <div class="col-sm-10">
					                  <?php echo form_dropdown('data[vendors][vendor_category_id]', $option['vendor_categories'], (!set_value('data[vendors][vendor_category_id]'))?$values_posted['vendors']['vendor_category_id']:set_value('data[vendors][vendor_category_id]'), $input['vendor_category_id']);?>
					                  <?php echo form_error('data[vendors][vendor_category_id]'); ?>
					                  </div>
					                </div>
					            </div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
										<div class="col-sm-10">
											<?php echo form_input($input['first_name']); ?>
											<?php echo form_error('first_name');?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputMiddleName" class="col-sm-2 control-label">Middle Name</label>
										<div class="col-sm-10">
											<?php echo form_input($input['middle_name']);?>
											<?php echo form_error('middle_name'); ?>
										</div>
									</div>
								</div>
							</div><!-- /row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputFirstName" class="col-sm-2 control-label">Surname</label>
										<div class="col-sm-10">
											<?php echo form_input($input['surname']); ?>
											<?php echo form_error('surname');?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputCompanyName" class="col-sm-2 control-label">Company Name</label>
										<div class="col-sm-10">
											<?php echo form_input($input['company_name']);?>
											<?php echo form_error('company_name'); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
					                <div class="form-group">
					                  <label for="inputEmp_code" class="col-sm-2 control-label">vendor Code</label>
					                  <div class="col-sm-10">
					                    <?php echo form_input($input['emp_code']);?>
					                    <?php echo form_error('data[vendors][code]'); ?>
					                  </div>
					                </div>
					            </div>
							</div><!-- /row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputPrimaryEmail" class="col-sm-2 control-label">Primary Email</label>
										<div class="col-sm-10">
											<?php echo form_input($input['primary_email']); ?>
											<?php echo form_error('primary_email');?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputSecondaryEmail" class="col-sm-2 control-label">Secondary Email</label>
										<div class="col-sm-10">
											<?php echo form_input($input['secondary_email']);?>
											<?php echo form_error('secondary_email'); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputContact_1" class="col-sm-2 control-label">Contact 1</label>
										<div class="col-sm-10">
											<?php echo form_input($input['contact_1']); ?>
											<?php echo form_error('contact_1');?>
										</div>
									</div>
								</div>
							</div><!-- /row -->
							
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputContact_2" class="col-sm-2 control-label">Contact 2</label>
										<div class="col-sm-10">
											<?php echo form_input($input['contact_2']);?>
											<?php echo form_error('contact_2'); ?>
										</div>
									</div>
								</div>
								<!-- <div class="col-md-4">
									<div class="form-group">
										<label for="inputBloodgroup" class="col-sm-2 control-label">Blood Group</label>
										<div class="col-sm-10">
											
											<?php 
											//print_r($option);
											echo form_dropdown('data[vendors][blood_group]', $option['blood_group'], $values_posted['vendors']['blood_group'], "id= 'blood_group', required='required', class='form-control select2'"); ?>
											<?php echo form_error('blood_group');?>
										</div>
									</div>
								</div> -->
								<div class="col-md-4">
					                <div class="form-group">
					                   <label for="inputAdhaar_no" class="col-sm-2 control-label">Adhaar Number</label>
					                  <div class="col-sm-10">
					                    <?php echo form_input($input['adhaar_no']);?>
					                    <?php echo form_error('data[vendors][adhaar_no]'); ?>
					                  </div>
					                </div>
					            </div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputPanNo" class="col-sm-2 control-label">Pan No</label>
										<div class="col-sm-10">
											<?php echo form_input($input['pan_no']); ?>
											<?php echo form_error('pan_no');?>
										</div>
									</div>
								</div>
							</div><!-- /row -->
							
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputGstNo" class="col-sm-2 control-label">GST No</label>
										<div class="col-sm-10">
											<?php echo form_input($input['gst_no']);?>
											<?php echo form_error('gst_no'); ?>
										</div>
									</div>
								</div>
								<!-- <div class="col-md-6">
									<div class="form-group">
										<label for="inputHas_multiple_sites" class="col-sm-2 control-label">Has Multiple Sites</label>
										<div class="col-sm-10">
											<?php echo form_input($input['has_multiple_sites']);?>
											<?php echo form_error('has_multiple_sites'); ?>
										</div>
									</div>
								</div> -->
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputIs_active" class="col-sm-2 control-label">Is Active</label>
										<div class="col-sm-10">
											<?php echo form_input($input['is_active']); ?>
											<?php echo form_error('is_active');?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputProfileImage" class="col-sm-2 control-label">Profile Image</label>
										<div class="col-sm-10">
											<?php echo form_upload($input['profile_img']);?>
											<?php echo form_hidden($input['profile_img2']); ?>
											<img src="<?php echo !empty($values_posted['vendors']['profile_img'])?content_url().'/uploads/profile_images/'. $values_posted['vendors']['profile_img']:content_url().'uploads/profile_images/default.png'; ?>" height="100px" width="100px">
											<?php echo form_error('data[vendors][profile_img]'); ?>
										</div>
									</div>
								</div>
							</div><!-- /row -->
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
					echo $addressList;
				?>
				</div>
				<div class="tab-pane <?php if($tab == 'bank_account'){ echo "active";}?>" id="bank_account">
				<?php
					echo $bank_account;
					echo $bankAccountList;
				?>
				</div>
				<div class="tab-pane <?php if($tab == 'document'){ echo "active";}?>" id="document">
				<?php
					echo $document;
					echo $documentList;
				?>
				</div>
				<?php if($values_posted['vendors']['has_multiple_sites']){ ?>
					<div class="tab-pane <?php if($tab == 'sites'){ echo "active";}?>" id="sites">
					<?php
						echo $vendor_sites;
						echo $siteList;
					?>
					</div>
				<?php } ?>
			</div>
		</div><!-- /nav-tabs-custom -->
	</div><!-- /col-md-12 -->
</div>
</section>