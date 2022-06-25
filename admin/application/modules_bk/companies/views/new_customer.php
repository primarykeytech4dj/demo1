<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['company_name'] = array(
						"name" => "data[companies][company_name]",
						"placeholder" => "company_name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "company_name",
					);

$input['first_name'] = array(
						"name" => "data[companies][first_name]",
						"placeholder" => "first_name *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "first_name"
					);


$input['middle_name'] = array(
						"name" => "product_categories",
						"placeholder" => "image *",
						"class"=> "form-control",
						"id" => "image",
						"value" => set_value('image'),
					);

$input['surname'] = array(
						"name" => "product_categories",
						"placeholder" => "image *",
						"class"=> "form-control",
						"id" => "image",
						"value" => set_value('image'),
					);

$input['primary_email'] = array(
						"name" => "product_categories",
						"placeholder" => "image *",
						"class"=> "form-control",
						"id" => "image",
						"value" => set_value('image'),
					);
$input['secondary_email'] = array(
						"name" => "product_categories",
						"placeholder" => "image *",
						"class"=> "form-control",
						"id" => "image",
						"value" => set_value('image'),
					);




$input['logo_image'] =  array(
							"name" => "logo_image",
							"placeholder" => "logo_image *",
							//"required" => "required",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "logo_image",
							"value" =>	set_value('logo_image'),
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
						"name" => "data[companies][base_price]",
						"placeholder" => "base_price *",
						"max_length" => "100",
						"required" => "required",
						"class"=> "form-control",
						"id" => "base_price"
					);

$input['contact_2'] = array(
						"name" => "product_categories",
						"placeholder" => "image *",
						"class"=> "form-control",
						"id" => "image",
						"value" => set_value('image'),
					);



// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{	
//print_r($values_posted);

	foreach($values_posted['data'] as $post_name => $post_value)
	{ 
		foreach ($post_value as $field_key => $field_value) {
		print_r($field_value);
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
						echo form_open_multipart('companies/new_company', ['class'=>'form-horizontal', 'id'=>'new_company']);
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
									<h3 class="box-title">New Company</h3>
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
												<label for="inputCategory" class="col-sm-2 control-label">Company Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['company_name']); ?>
													<?php echo form_error('data[companies][category]'); ?>
												</div>
											</div>
										</div>
									<div class="col-md-6">
											<div class="form-group">
												<label for="first_name" class="col-sm-2 control-label">Logo Image</label>
												<div class="col-sm-10">
													<?php echo form_input($input['first_name']); ?>
													<?php echo form_error('data[companies][first_name]'); ?>
												</div>
											</div>
										</div>
										
									</div><!-- /row -->
									<div class="row">
									<div class="col-md-6">
											<div class="form-group">
												<label for="inputSlug" class="col-sm-2 control-label">First Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['product']); ?>
													<?php echo form_error('data[companies][product]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputProfileImage" class="col-sm-2 control-label">Middle Name</label>
												<div class="col-sm-10">
							                      <?php echo form_input($input['first_name']);?>
													<?php echo form_error('data[companies][first_name]');?>
							                  </div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputGst" class="col-sm-2 control-label">Surname</label>
												<div class="col-sm-10">
													<?php echo form_input($input['base_price']);?>
													<?php echo form_error('data[companies][base_price]');?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputGst" class="col-sm-2 control-label">Primary Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['base_price']);?>
													<?php echo form_error('data[companies][base_price]');?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputGst" class="col-sm-2 control-label">Secondary Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['base_price']);?>
													<?php echo form_error('data[companies][base_price]');?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputGst" class="col-sm-2 control-label">Contact 1</label>
												<div class="col-sm-10">
													<?php echo form_input($input['base_price']);?>
													<?php echo form_error('data[companies][base_price]');?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputGst" class="col-sm-2 control-label">Contact 2</label>
												<div class="col-sm-10">
													<?php echo form_input($input['base_price']);?>
													<?php echo form_error('data[companies][base_price]');?>
												</div>
											</div>
										</div>
									</div>
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">New Company</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
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

