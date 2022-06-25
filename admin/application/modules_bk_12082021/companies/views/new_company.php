<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
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
						"placeholder" => "First Name *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "first_name"
					);


$input['middle_name'] = array(
						"name" => "data[companies][middle_name]",
						"placeholder" => "Middle Name *",
						"max_length" => "64",
						"class"=> "form-control",
						"id" => "middle_name",
						
					);

$input['surname'] = array(
						"name" => "data[companies][surname]",
						"placeholder" => "surname *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "surname",
					);

$input['primary_email'] = array(
						"name" => "data[companies][primary_email]",
						"placeholder" => "primary_email *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "primary_email",
					);
$input['secondary_email'] =array(
						"name" => "data[companies][secondary_email]",
						"placeholder" => "secodary email *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "secondary_email",
					);




$input['logo'] =  array(
							"name" => "logo",
							"placeholder" => "logo *",
							//"required" => "required",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "logo",
							"value" =>	set_value('logo'),
							 );

$input['product_categories'] =  array(
							"name" => "product_categories",
							"placeholder" => "product_categories *",
							//"required" => "required",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "product_categories",
							"value" =>	set_value('product_categories'),
							 );
					

$input['contact_1'] = array(
						"name" => "data[companies][contact_1]",
						"placeholder" => "Contact 1 *",
						"max_length" => "100",
						"required" => "required",
						"class"=> "form-control",
						"id" => "contact_1"
					);

$input['contact_2'] = array(
						"name" => "data[companies][contact_2]",
						"placeholder" => "Contact 2 ",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "contact_2"
					);

$input['short_code'] = array(
						"name" => "data[companies][short_code]",
						"placeholder" => "Short Code ",
						"max_length" => "100",
						"required" => "required",
						"class"=> "form-control",
						"id" => "short_code"
					);


$input['meta_keyword'] = array(
						"name" => "data[companies][meta_keyword]",
						"placeholder" => "meta_keyword *",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "meta_keyword"
					);

$input['meta_title'] = array(
						"name" => "data[companies][meta_title]",
						"placeholder" => "meta_title *",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "meta_title"
					);

$input['meta_description'] = array(
						"name" => "data[companies][meta_description]",
						"placeholder" => "meta_description *",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "meta_description"
					);

$input['website'] = array(
						"name" => "data[companies][website]",
						"placeholder" => "website *",
						"max_length" => "100",
						"required" =>"required",
						"class"=> "form-control",
						"id" => "website"
					);

$input['about_company'] =  array(
							"name" => "data[companies][about_company]",
							"placeholder" => "About Company *",
							"required" => "required",
							"class" => "form-control textarea",
							"id" => "about_company",
							"rows" => "5",
							"tab-index" => 3,
						);

// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{	
//print_r($values_posted);

	foreach($values_posted['data'] as $post_name => $post_value)
	{ 
		foreach ($post_value as $field_key => $field_value) {
		//print_r($field_value);
			# code...
			$input[$field_key]['value'] = $field_value;
		}
	}
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Add Company
  </h1>
  
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">

				<div class="tab-content">
					
						<?php //echo form_open_multipart(custom_constants::new_user_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
							//print_r($this->session);
						echo form_open_multipart(custom_constants::new_company_url, ['class'=>'form-horizontal', 'id'=>'new_company']);
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">New Company</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputCategory" class="col-sm-2 control-label">Company Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['company_name']); ?>
													<?php echo form_error('data[companies][category]'); ?>
												</div>
											</div>
										</div>
									<div class="col-md-6">
											<div class="form-group">
												<label for="logo" class="col-sm-2 control-label">Logo</label>
												<div class="col-sm-10">
													<input type="file" name="logo">
													
													<?php echo form_error('data[companies][logo]'); ?>
												</div>
											</div>
										</div>
										
									</div><!-- /row -->
									<div class="row">
									<div class="col-md-6">
											<div class="form-group">
												<label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['first_name']); ?>
													<?php echo form_error('data[companies][first_name]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputMiddleName" class="col-sm-2 control-label">Middle Name</label>
												<div class="col-sm-10">
							                      <?php echo form_input($input['middle_name']);?>
													<?php echo form_error('data[companies][middle_name]');?>
							                  </div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputSurname" class="col-sm-2 control-label">Surname</label>
												<div class="col-sm-10">
													<?php echo form_input($input['surname']);?>
													<?php echo form_error('data[companies][surname]');?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputPrimaryEmail" class="col-sm-2 control-label">Primary Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['primary_email']);?>
													<?php echo form_error('data[companies][primary_email]');?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputSecondaryEmail" class="col-sm-2 control-label">Secondary Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['secondary_email']);?>
													<?php echo form_error('data[companies][secondary_email]');?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputContact_1" class="col-sm-2 control-label">Contact 1</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_1']);?>
													<?php echo form_error('data[companies][contact_1]');?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputGst" class="col-sm-2 control-label">Contact 2</label>
												<div class="col-sm-10">
													<?php echo form_input($input['contact_2']);?>
													<?php echo form_error('data[companies][contact_2]');?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputMetaTitle" class="col-sm-2 control-label">Meta Title</label>
												<div class="col-sm-10">
													<?php echo form_input($input['meta_title']);?>
													<?php echo form_error('data[companies][meta_title]');?>
												</div>
											</div>
										</div>
									</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="inputMetaKeyword" class="col-sm-2 control-label">Meta Keyword</label>
													<div class="col-sm-10">
														<?php echo form_input($input['meta_keyword']);?>
														<?php echo form_error('data[companies][meta_keyword]');?>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="inputMetaDescription" class="col-sm-2 control-label">Meta Description</label>
													<div class="col-sm-10">
														<?php echo form_input($input['meta_description']);?>
														<?php echo form_error('data[companies][meta_description]');?>
													</div>
												</div>
											</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputWebsite" class="col-sm-2 control-label">Website</label>
												<div class="col-sm-10">
													<?php echo form_input($input['website']);?>
													<?php echo form_error('data[companies][website]');?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputShortCode" class="col-sm-2 control-label">Short Code</label>
												<div class="col-sm-10">
													<?php echo form_input($input['short_code']);?>
													<?php echo form_error('data[companies][short_code]');?>
												</div>
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
									<button type="new_company" class="btn btn-info pull-left">New Company</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
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

