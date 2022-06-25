<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$input['first_name'] = array(
						"name" => "data[login][first_name]",
						"placeholder" => "first name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "first_name",
					);

$input['surname'] = array(
						"name" => "data[login][surname]",
						"placeholder" => "surname *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "surname"
					);

$input['middle_name'] = array(
							'name' => "data[login][middle_name]",
							'placeholder'=> "Middle Name(s) *",
							"max_length" =>"64",
							"required" =>"required",
							"class" =>"form-control",
							"id" => "middle_name",
							 );
$input['username'] =  array(
							"name" => "data[login][username]",
							"placeholder" => "Username *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "username",
							 );



$input['email'] =  array(
							"type" => "email",
							"name" => "data[login][email]",
							"placeholder" => "Primary Email *",
							"max_length" => "100",
							"required" => "required",
							"class" => "form-control",
							"id" => "primary_email",
						);


// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ 	//print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{ //print_r($post_value);
		foreach ($post_value as $field_key => $field_value) {
			$input[$field_key]['value'] = $field_value;
		}
	}
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Module :: login
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><?php echo anchor('login/admin_index_2', 'login', ['title'=>'login']) ?></li>
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
					<li class="<?php if($tab=="roles"){echo "active";} ?>"><a href="#roles" data-toggle="tab">Role</a></li>
					<li class="pull-right">
						<?php echo anchor(custom_constants::admin_employee_view.'/'.$id, '<i class="fa fa-sticky-note"></i>', ['class'=>'text-muted', 'title'=>'View Details']);  ?>
					</li>
				</ul> 
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="personal_info"){echo "active";} ?>" id="personal_info">
						<?php echo form_open_multipart(custom_constants::edit_employee_url ."/".$id, ['class'=>'form-horizontal', 'id'=>'admin_edit']); ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Existing User</h3>
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
												<label for="surname" class="col-sm-2 control-label">Surname</label>
												<div class="col-sm-10">
													<?php echo form_input($input['surname']); ?>
													<?php echo form_error('data[login][surname]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_1" class="col-sm-2 control-label">Username</label>
												<div class="col-sm-10">
													<?php echo form_input($input['username']); ?>
													<?php echo form_error('data[login][username]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="primary_email" class="col-sm-2 control-label">Primary Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['email']); ?>
													<?php echo form_error('data[login][email]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
										
										<div class="col-md-6">
										</div>

									</div><!-- /row -->
									<div class="row">
										

									</div><!-- /row -->
									<div class="row">
										
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
					<div class="tab-pane <?php if($tab=="roles"){echo "active";} ?>" id="roles">
					</div>
					
				</div>
			</div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

