<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$value='';
// /echo $values_posted['companies']['dob'];exit;
$input['company_name'] = array(
						"name" => "data[companies][company_name]",
						"placeholder" => "Company Name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "company_name",
					);


$input['first_name'] = array(
						"name" => "data[companies][first_name]",
						"placeholder" => "First Name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "first_name",
					);

$input['middle_name'] = array(
							'name' => "data[companies][middle_name]",
							'placeholder'=> "Middle Name(s) ",
							"max_length" =>"64",
							"class" =>"form-control",
							"id" => "middle_name",
							 );

$input['surname'] = array(
						"name" => "data[companies][surname]",
						"placeholder" => "Surname *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "surname"
					);
$input['contact_1'] =  array(
							"name" => "data[companies][contact_1]",
							"placeholder" => "Contact 1 *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "contact_1",
							 );

$input['contact_2'] =  array(
							"name" => "data[companies][contact_2]",
							"placeholder" => "Contact 2",
							"max_length" => "12",
							"class" => "form-control",
							"id" => "contact_2",
							 );

$input['short_code'] =  array(
							"name" => "data[companies][short_code]",
							"placeholder" => "Short Code",
							"max_length" => "12",
							"class" => "form-control",
							"id" => "short_code",
							 );

$input['primary_email'] =  array(
							"type" => "email",
							"name" => "data[companies][primary_email]",
							"placeholder" => "Primary Email *",
							"max_length" => "100",
							"required" => "required",
							"class" => "form-control",
							"id" => "primary_email",
						);

$input['secondary_email'] =  array(
							"type" => "email",
							"name" => "data[companies][secondary_email]",
							"placeholder" => "Secondary Email",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "secondary_email",
							 );

$input['pan_no'] = array(
	                      "name" => "data[companies][pan_no]",
	                      "placeholder" => "Pan No",
	                      "class" => "form-control",
	                      "id" => "pan_no",
                    	);

$input['gst_no'] = array(
	                      "name" => "data[companies][gst_no]",
	                      "placeholder" => "GST",
	                      "class" => "form-control",
	                      "id" => "gst_no",
                    	);

$input['meta_title'] =  array(
							"name" => "data[companies][meta_title]",
							"placeholder" => "Meta Title",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_title",
							 );

$input['meta_keyword'] =  array(
							"name" => "data[companies][meta_keyword]",
							"placeholder" => "Meta Keyword",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_keyword",
							 );							

$input['meta_description'] =  array(
							"name" => "data[companies][meta_description]",
							"placeholder" => "Meta Description",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_description",
							 );

$input['website'] =  array(
							"name" => "data[companies][website]",
							"placeholder" => "Website",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "website",
							 );

$input['logo'] =  array(
							"name" => "logo",
							"placeholder" => "logo *",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "logo",
							"value" =>	set_value('logo'),
							 );
/*echo '<pre>';
print_r($values_posted);
echo '</pre>';*/
$input['logo2'] =  array(
							"data[companies][logo2]" => $values_posted['companies']['logo'],
							"data[companies][id]" => $id,
							//"value" =>	$values_posted['companies']['logo'],
							 );
					
$input['about_company'] =  array(
							"name" => "data[companies][about_company]",
							"placeholder" => "About Company *",
							"required" => "required",
							"class" => "form-control editor1",
							"id" => "about_company",
							"rows" => "5",
							"tab-index" => 3,
						);
$input['is_active'] =  array(
							"name" => "data[companies][is_active]",
							"class" => "flat-red",
							"id" => "is_active",
							"type" => "checkbox",
						);

// If form has been submitted with errors populate fields that were already filled
//print_r($values_posted);
if(isset($values_posted))
{ //print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{
		foreach ($post_value as $field_key => $field_value) {
			if(isset($input[$field_key]['type']) && $input[$field_key]['type']=="checkbox" && $field_value==true){
				$input[$field_key]['checked'] = "checked";
			}else{
				$input[$field_key]['value'] = $field_value;
			}
		}
	}
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Module :: companies
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="<?php echo base_url().'companies'; ?>" title="companies">companies</a></li>
		
	</ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<?php 
			if(isset($form_error))
			{
				echo "<div class='alert alert-danger'>";
				echo "<p>" . $form_error . "</p>";
				echo "</div>";
			}
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
					<!-- <li class="<?php if($tab=="infrastructure"){echo "active";} ?>"><a href="#infrastructure" data-toggle="tab">Infrastructure</a></li> -->
					<li class="<?php if($tab=="login"){echo "active";} ?>"><a href="#login" data-toggle="tab">Login</a></li> 
					<li class="pull-right">
						<?php echo anchor(custom_constants::admin_company_view."/".$id, '<i class="fa fa-sticky-note"></i>', ['class'=>'text-muted', 'title'=>'View Details']);  ?>
					</li>
				</ul> 
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="personal_info"){echo "active";} ?>" id="personal_info">
						<?php //echo form_open_multipart(custom_constants::edit_employee_url ."/".$id, ['class'=>'form-horizontal', 'id'=>'register_user']); 
						echo form_open_multipart(custom_constants::edit_company_url."/".$id, ['class'=>'form-horizontal', 'id'=>'companies'])
						?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Existing Company</h3>
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
												<label for="first_name" class="col-sm-2 control-label">Company Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['company_name']); ?>
													
												      <?php echo form_error('company_name'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">                          
											<label for="logo" class="col-sm-2 control-label">Logo Image</label>
												<div class="col-sm-10">
													<?php echo form_upload($input['logo']); ?>
													<?php echo form_hidden($input['logo2']); ?>
													<img src="<?php echo !empty($values_posted['companies']['logo'])?content_url().'uploads/profile_images/'.$values_posted['companies']['logo']:base_url().'assets/uploads/profile_images/default.jpg'; ?>" height="80px" width="80px">
													<?php echo form_error('data[companies][logo]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
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
													<?php echo form_error('data[companies][surname]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_1" class="col-sm-2 control-label">Contact 1</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_1']); ?>
													<?php echo form_error('data[companies][contact_1]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_2" class="col-sm-2 control-label">Contact 2</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_2']); ?>
													<?php echo form_error('data[companies][contact_2]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="primary_email" class="col-sm-2 control-label">Primary Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['primary_email']); ?>
													<?php echo form_error('data[companies][primary_email]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
								                <label for="secondary_email" class="col-sm-2 control-label">Secondary Email</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['secondary_email']);?>
													<?php echo form_error('data[companies][secondary_email]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputMetaTitle" class="col-sm-2 control-label">Meta Title</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['meta_title']);?>
													<?php echo form_error('data[companies][meta_title]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputMetaKeyword" class="col-sm-2 control-label">Meta Keyword</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['meta_keyword']);?>
													<?php echo form_error('data[companies][meta_keyword]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputMetaDesciption" class="col-sm-2 control-label">Meta Description</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['meta_description']);?>
													<?php echo form_error('data[companies][meta_decription]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputWebsite" class="col-sm-2 control-label">Website</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['website']);?>
													<?php echo form_error('data[companies][website]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputAbout_company" class="col-sm-2 control-label">Short Code</label>
												<div class="col-sm-10">
													<?php echo form_input($input['short_code']); ?>
													<?php echo form_error('short_code'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputPanNo" class="col-sm-2 control-label">Pan No</label>
												<div class="col-sm-10">
													<?php echo form_input($input['pan_no']);?>
													<?php echo form_error('data[companies][pan_no]');?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputGST" class="col-sm-2 control-label">GST</label>
												<div class="col-sm-10">
													<?php echo form_input($input['gst_no']);?>
													<?php echo form_error('data[companies][gst_no]');?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputIs_active" class="col-sm-2 control-label">Active / Inactive</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['is_active']);?>
													<?php echo form_error('data[companies][is_active]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputAbout_company" class="col-sm-1 control-label">About Company</label>
												<div class="col-sm-11">
													<?php echo form_textarea($input['about_company']); ?>
													<?php echo form_error('about_company'); ?>
												</div>
											</div>
										</div>
									</div>
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
					<!-- <div class="tab-pane <?php if($tab=="infrastructure"){echo "active";} ?>" id="infrastructure">
						<?php 
						echo $infrastructures;
						?> 
					</div> -->
					<div class="tab-pane <?php if($tab=="login"){echo "active";} ?>" id="login"> 
						<?php echo $login;
						//echo $loginList; ?>
					</div>
					
				</div>
			</div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

