<?php
//$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//print_r($values_posted);
$value = DateTime::createFromFormat('Y-m-d', $values_posted['orders']['date']);
$value = $value->format('d/m/Y');

$input['project_name'] = array(
						"name" => "data[orders][project_name]",
						"placeholder" => "Project name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "project_name",
						//'value' => set_values('project_name', $orders['project_name']),
					);
									    

$input['date'] =  array(		
							"name" => "data[orders][date]",
							"placeholder" => "date *",
							"max_length" => "12",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "date",
							"value" => $value,
							 );
$input['order_code'] =  array(
							"name" => "data[orders][order_code]",
							"placeholder" => "Invoice Number *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "order_code",
						);



$input['message'] =  array(
							"name" => "data[orders][message]",
							"placeholder" => "Enquiry",
							"class" => "form-control",
							"id" => "message",
							'rows'=>5
							 );


// If form has been submitted with errors populate fields that were already filled
unset($values_posted['orders']['date']);
//unset($values_posted['orders']['start_date']);
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
					<li class="<?php if($tab=="personal_info"){echo "active";} ?>"><a href="#personal_info" data-toggle="tab">Order</a></li>
					
					<li class="<?php if($tab=="document"){echo "active";} ?>"><a href="#document" data-toggle="tab">Project Images</a></li>
					
					<!-- <li class="<?php if($tab=="login"){echo "active";} ?>"><a href="#login" data-toggle="tab">Login</a></li> -->
					<li class="pull-right"><?php echo anchor(custom_constants::new_project_url, 'New Order (Project)', ['class'=>'btn btn-info']); ?></li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="personal_info"){echo "active";} ?>" id="personal_info"> 
						<?php 
						echo form_open_multipart(custom_constants::edit_project_url."/".$id, ['class'=>'form-horizontal', 'id'=>'update_project']); 
						 ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Existing Order (Project)</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="type" class="col-sm-2 control-label">Select Customer</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[orders][customer_id]', $customers, $values_posted['orders']['customer_id'], "id='customer_id' required='required' class='form-control select2' style='width:100%'");?>
													<?php echo form_error('data[orders][customer_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputProject_name" class="col-sm-2 control-label">Project Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['project_name']); ?>
													<?php //echo form_error('data[orders][project_name]'); ?>
													<?php //print_r($Colleges);
												      ?>
												      <?php echo form_error('project_name'); ?>
												</div>
											</div>
										</div>
										
									</div><!-- /row -->
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputOrder_code" class="col-sm-2 control-label">Invoice Number</label>
												<div class="col-sm-10">
													<?php echo form_input($input['order_code']); ?>
													<?php //echo form_error('data[orders][project_name]'); ?>
													<?php //print_r($Colleges);
												      ?>
												      <?php echo form_error('data[orders][order_code]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="inputDate"  class="col-sm-2 control-label">Invoice Date :</label>

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

								</div><!-- /row -->
								
								
								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputMessage" class="col-sm-2 control-label">Enquiry Details</label>
											<div class="col-sm-10">
												<?php //print_r($option['referred_by']); ?>
												<?php echo form_textarea($input['message']); ?>
												<?php echo form_error('data[orders][message]'); ?>
											</div>
										</div>
									</div>
									
								</div>
								<!-- s --> <!-- /box-body -->  
						    	<div class="box-footer">  
									<button type="submit" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
						</div>
						<?php echo form_close(); ?> 
					</div><!-- /tab-pane -->
					
					<div class="tab-pane <?php if($tab=="document"){echo "active";} ?>" id="document" >
						<?php 
						echo $document;
						echo $documentList; 
						?>
					</div><!-- /tab-pane -->
					
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

