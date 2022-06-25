<?php
$tab = "basic_detail";
//If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


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
  <h1>
    Module :: Fieldmember
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li><a href="<?php echo custom_constants::fieldmember_url; ?>">Delivery Section</a></li>
    <li class="active">Assign Deliveryboys</li>
  </ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<!-- <ul class="nav nav-tabs">
					<li class="<?php if($tab=="basic_detail"){echo "active";} ?>"><a href="#basic_detail" data-toggle="tab">User</a></li>
					<li class="<?php if($tab=="address"){echo "active";} ?>"><a href="#address" data-toggle="tab">Address</a></li>
					<li class="<?php if($tab=="login"){echo "active";} ?>"><a href="#login" data-toggle="tab">Login</a></li>
				</ul>  -->
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="basic_detail"){echo "active";} ?>" id="basic_detail"> 
						<?php echo form_open_multipart(custom_constants::assign_deliveryboy_url, ['class'=>'form-horizontal', 'id'=>'assign']); 
							//print_r($this->session);
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
									<h3 class="box-title">Assign Order</h3>
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
												<label for="surname" class="col-sm-2 control-label">Delivery Boys</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[deliveryboy_order][employee_id]',$option['employees'],'',"id='employee_id' required='required' class='form-control select2 filter'   tab-index=1 style='width:100%'");?>
													<?php echo form_error('data[deliveryboy_order][employee_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Orders</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[orders][]',$option['orders'],set_value('data[orders][]'),["id"=>'order_id', "required"=>'required', "class"=>'form-control select2 filter', "multiple"=>'multiple', 'tab-index'=>2, 'style'=>'width:100%']);?>
													<?php //echo form_error('data[orders][id]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									
	
									<!-- s --> <!-- /box-body -->  
							                   
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Assign</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
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

