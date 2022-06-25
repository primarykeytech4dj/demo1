<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$input['from_invoice'] =  array(		
							"name" => "from_invoice",
							"placeholder" => "From Invoice *",
							"required" => "required",
							"class" => "col-xs-3 form-control",
							"id"	=> "from_invoice",
							"type"=>'number',
							"min"=>1
							 );

$input['to_invoice'] = array(
                            "name" => "to_invoice",
							"placeholder" => "To Invoice *",
							"required" =>"required",
							"class" =>"form-control",
							"id" => "to_invoice",
							"type"  =>  "number",
							"min"=>1
						);
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ //print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{
		foreach ($post_value as $field_key => $field_value) {
			# code...
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
	<h1><?=$title?></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<?php if(in_array(1, $this->session->userdata('roles')) ||  in_array(2, $this->session->userdata('roles')) ||  in_array(7, $this->session->userdata('roles'))){ ?>
		<li><?php echo anchor(custom_constants::admin_order_listing_url, '<i class="fa fa-shopping-bag"></i> View Orders'); ?></li>
		<?php }elseif(in_array(6, $this->session->userdata('roles'))){ ?>
		<li><?php echo anchor(custom_constants::fieldmember_url, '<i class="fa fa-shopping-bag"></i> View Assigned Orders'); ?></li>
		<?php } ?>
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
						<?php echo form_open_multipart("orders/exportxml", ['class'=>'form-vertical', 'id'=>'export_order', 'onsubmit'=>"myButtonValue.disabled = true; return true;"]); 
							
							if(NULL!==$this->session->flashdata('message')) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><?=$heading?></h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									
									<div class="row">
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputFrom_invoice" class="control-label">From Invoice</label>
												<?php echo form_input($input['from_invoice']); ?>
												<?php echo form_error('from_invoice'); ?>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputTo_invoice" class="control-label">To Invoice</label>
												<?php echo form_input($input['to_invoice']); ?>
												<?php echo form_error('to_invoice'); ?>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputExport" class="control-label">Export</label><br>
												<input type="radio" name="export" value="customer" required="required"> Customers<br>
												<input type="radio" name="export" value="order" required="required"> Orders
											</div>
										</div>
									</div>
									
									
						<!-- /box-body -->
							 	
									<div class="row">
									    <div class="col-md-12 col-xs-12 response"></div>
									</div>
									<div class="row">
										<div class="col-md-6  col-xs-6 text-center">
											<button class="btn btn-info" id="Exportbtn">Export</button>
										</div>
										<div class="col-md-6  col-xs-6 text-center">
											<button type="reset" class="btn btn-info">Reset</button>
										</div>
									</div>
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
<script type="text/javascript">
    
    
    /*$(document).ajaxComplete(function() {
        //alert("hii");
        $('#myButtonValue').removeAttr('disabled');
        //alert($('#productattributeid_'+trId+' option:selected').val());
    });*/
    
    
</script>