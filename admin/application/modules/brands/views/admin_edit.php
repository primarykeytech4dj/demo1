<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";


$input['brand'] = array(
						"name" => "data[manufacturing_brands][brand]",
						"placeholder" => "Brand *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "brand",
					);

												    
$input['text'] = array(
						"name" => "data[manufacturing_brands][text]",
						"placeholder" => "text *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "text"
					);

$input['brand_logo'] =  array(
							"name" => "brand_logo",
							"placeholder" => "brand_logo *",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "brand_logo",
							"value" =>	set_value('brand_logo'),
							 );

$input['brand_logo2'] =  array(
							"data[manufacturing_brands][brand_logo2]" => $values_posted['manufacturing_brands']['brand_logo'],
							"data[manufacturing_brands][id]" => $id
							//"value" =>	$values_posted['manufacturing_brands']['brand_logo'],
							 );
					
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
		Module :: Brands
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><?php echo anchor(custom_constants::admin_brand_listing_url, 'Brand', ['title'=>'manufacturing_brands']) ?></li>
		<li class="active"><?php echo anchor(custom_constants::edit_brand_url."/".$id, 'Edit Brand', ['title'=>'Edit Brand']) ?></li>
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
				<div class="tab-content">
						<?php echo form_open_multipart(custom_constants::edit_brand_url ."/".$id, ['class'=>'form-horizontal', 'id'=>'admin_edit']); ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Existing User</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="brand" class="col-sm-2 control-label">Brand Name</label>
												<div class="col-sm-10">
													<?php echo form_input($input['brand']); ?>
												      <?php echo form_error('brand'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">                          
											<label for="brand_logo" class="col-sm-2 control-label">Brand Logo</label>
												<div class="col-sm-10">
													<?php echo form_upload($input['brand_logo']); ?>
													<?php echo form_hidden($input['brand_logo2']); ?>
													<img src="<?php echo !empty($values_posted['manufacturing_brands']['brand_logo'])?content_url().'uploads/brands/'.$values_posted['manufacturing_brands']['brand_logo']:content_url().'uploads/brands/defaultm.jpg'; ?>" height="80px" width="80px">
													<?php echo form_error('data[manufacturing_brands][brand_logo]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
										
										<div class="col-md-6">
											<div class="form-group">
												<label for="brand" class="col-sm-2 control-label">Text</label>
												<div class="col-sm-10">
													<?php echo form_input($input['text']); ?>
												      <?php echo form_error('text'); ?>
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
			</div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

