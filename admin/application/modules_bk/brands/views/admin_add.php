<?php
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['brand'] = array(
						"name" => "data[manufacturing_brands][brand]",
						"placeholder" => "Brand Name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "brand",
					);

$input['text'] = array(
						"name" => "data[manufacturing_brands][text]",
						"placeholder" => "Text *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "text",
					);



$input['brand_logo'] =  array(
							"name" => "brand_logo",
							"placeholder" => "brand_logo *",
							//"required" => "required",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "brand_logo",
							"value" =>	set_value('brand_logo'),
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
  <h1>
    Module :: Brands
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li>
    	<?php //echo anchor(custom_constants::admin_manufacturing_brands_listing_url, 'manufacturing_brands'); 
    	echo anchor(custom_constants::admin_brand_listing_url, 'manufacturing_brands');?>
    		
    </li>
    <li class="active">New Brand</li>
  </ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<div class="tab-content">
					
						<?php echo form_open_multipart(custom_constants::new_brand_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
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
									<h3 class="box-title">New Brand</h3>
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
												<label for="brand" class="col-sm-2 control-label">Brand Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['brand']); ?>
													<?php echo form_error('data[manufacturing_brands][brand]'); ?>
												</div>
											</div>
										</div>
										
										
										<div class="col-md-6">
											<div class="form-group">                          
											<label for="brand_logo" class="col-sm-2 control-label">Logo Image</label>
												<div class="col-sm-10">
													<?php echo form_upload($input['brand_logo']); ?>
													<?php echo form_error('brand_logo'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="text" class="col-sm-2 control-label">Text</label>
												<div class="col-sm-10">
													<?php echo form_input($input['text']); ?>
													<?php echo form_error('data[manufacturing_brands][text]'); ?>
												</div>
											</div>
										</div>
									</div>
									<!-- s --> <!-- /box-body -->  
							                   
								</div><!-- /box -->
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Register</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div>
						<?php echo form_close(); ?> 
					
					
					
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

