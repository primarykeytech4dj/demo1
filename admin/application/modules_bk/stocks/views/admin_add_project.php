<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['project_name'] = array(
						"name" => "data[orders][project_name]",
						"placeholder" => "Project name(s) *",
						"max_length" => "255",
						"required" => "required",
						"class"=> "form-control",
						"id" => "project_name",
					);



$input['order_code'] = array(
						"name" => "data[orders][order_code]",
						"placeholder" => "Invoice Number",
						"class" => "form-control",
						'id' => "order_code"
					);

$input['date'] =  array(		
							"name" => "data[orders][date]",
							"placeholder" => "Date *",
							"max_length" => "12",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "dob"
							 );
$input['message'] =  array(
							"name" => "data[orders][message]",
							"placeholder" => "Enquiry Details",
							"class" => "form-control",
							"id" => "message",
							"rows" => 4
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
	<h1><span class="glyphicon glyphicon-shopping-cart"></span> Module :: Orders (Projects)</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><?php echo anchor(custom_constants::admin_project_listing_url, '<i class="fa fa-shopping-bag"></i> View Orders (Projects)'); ?></li>
		<li><a href="#"><i class="fa fa-plus-square"></i>New Order (Project)</a></li>
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
						<?php echo form_open_multipart(custom_constants::new_project_url, ['class'=>'form-horizontal', 'id'=>'register_order']); 
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">New Order (Project)</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="type" class="col-sm-2 control-label">Select Customer</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[orders][customer_id]', $customers, '', "id='customer_id' required='required' class='form-control select2' style='width:100%'");?>
													<?php echo form_error('data[orders][customer_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputOrder_code" class="col-sm-2 control-label">Project Name</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['project_name']);?>
													<?php echo form_error('data[orders][project_name]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputOrder_code" class="col-sm-2 control-label">Invoice Number</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['order_code']);?>
													<?php echo form_error('data[orders][order_code]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputDate"  class="col-sm-2 control-label">Invoice Date:</label>
												<div class="input-group date">
													<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
													</div>
													<?php echo form_input($input['date']);?>
													<?php echo form_error('data[orders][date]'); ?>
												</div>
												<!-- /.input group -->
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputMessage" class="col-sm-2 control-label">Description</label>
												<div class="col-sm-10">
													<?php echo form_textarea($input['message']); ?>
													<?php echo form_error('data[orders][message]'); ?>
												</div>
											</div>
										</div>
									</div>
						<!-- /box-body -->
								</div>
								<button type="new_college" class="btn btn-info pull-left">Add Order (Project)</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
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

