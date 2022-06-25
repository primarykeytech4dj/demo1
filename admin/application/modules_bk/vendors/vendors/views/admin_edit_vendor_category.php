<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$value='';
// /echo $values_posted['vendor_categories']['dob'];exit;
$input['category_name'] = array(
					"name" => "data[vendor_categories][category_name]",
					"placeholder" => "Category name(s) *",
					"max_length" => "64",
					"required" => "required",
					"class"=> "form-control",
					"id" => "category_name",
					);


$input['description'] = array(
						"name" => "data[vendor_categories][description]",
						"placeholder" => "Description (s) *",
						"required" => "required",
						"class"=> "form-control textarea",
						"id" => "description",
					);

$input['slug'] = array(
						'name' => "data[vendor_categories][slug]",
						'placeholder'=> "Slug(s) *",
						"max_length" =>"64",
						"required" =>"required",
						"class" =>"form-control slugify",
						"id" => "slug",
							 );

$input['meta_title'] =  array(
							"name" => "data[vendor_categories][meta_title]",
							"placeholder" => "Meta Title",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_title",
							 );

$input['meta_keyword'] =  array(
							"name" => "data[vendor_categories][meta_keyword]",
							"placeholder" => "Meta Keyword",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_keyword",
							 );							

$input['meta_description'] =  array(
							"name" => "data[vendor_categories][meta_description]",
							"placeholder" => "Meta Description",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_description",
							 );
$input['is_active'] =  array(
							"name" => "data[vendor_categories][is_active]",
							"type" => "checkbox",
							"class" => "flat-red",
							"id" => "is_active",
							"value" => true
						);

// If form has been submitted with errors populate fields that were already filled
//print_r($values_posted);
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
	<h1>
		<?=$title?>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li>
			<?php echo anchor(custom_constants::admin_vendor_category_listing_url, 'vendor Categories', 'title="vendor_categories" id="vendor_categories"'); ?>
		</li>
		
	</ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<?php 
			
           	if($this->session->flashdata('message') !== FALSE) {
	            $msg = $this->session->flashdata('message');
	            //print_r($msg);?>
	          	<div class = "<?php echo $msg['class'];?>">
	                <?php echo $msg['message'];?>
	          	</div>
        	<?php } ?>
        	<div class="nav-tabs-custom">
				
				<div class="tab-content">
				
					<?php  
					echo form_open_multipart(custom_constants::edit_vendor_category_url."/".$id, ['class'=>'form-horizontal', 'id'=>'vendor_categories'])
					?>
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title"><?=$heading?>
							</div><!-- /box-header -->
							<!-- form start -->
							<div class="box-body">
								<div class ="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputParent_id" class="col-sm-2 control-label">Parent</label>
											<div class="col-sm-10">
												<?php 
												echo form_dropdown('data[vendor_categories][parent_id]', $option['parent'], $values_posted['vendor_categories']['parent_id'], 'class="form-control select2" id="parent_id" style="width:100%"');
												?>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputCategoryName" class="col-sm-2 control-label">Category Name</label>
											<div class="col-sm-10">
												<?php echo form_input($input['category_name']); ?>
												
											      <?php echo form_error('category_name'); ?>
											</div>
										</div>
									</div>
								</div><!-- /row -->
								<div class="row">
									
									<div class="col-md-6">
										<div class="form-group">                          
											<label for="inputSlug" class="col-sm-2 control-label">Slug</label>
											<div class="col-sm-10">
												<?php echo form_input($input['slug']); ?>
												<?php echo form_error('slug'); ?>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputIs_active" class="col-sm-2 control-label">Is Active</label>
											<div class="col-sm-10">
												<?php echo form_input($input['is_active']);?>
												<?php echo form_error('data[vendor_categories][is_active]');?>
											</div>
										</div>
									</div>
								</div><!-- /row -->
									
								<div class="row">
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputMetaTitle" class="col-sm-2 control-label">Meta Title</label>
											<div class="col-sm-10">
												<?php echo form_input($input['meta_title']); ?>
												<?php echo form_error('data[vendor_categories][meta_title]'); ?>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputMetaKeyWord" class="col-sm-2 control-label">Meta KeyWord</label>
											<div class="col-sm-10">
												<?php echo form_input($input['meta_keyword']); ?>
												<?php echo form_error('data[vendor_categories][meta_keyword]'); ?>
											</div>
										</div>
									</div>
								</div><!-- /row -->
								<div class="row">
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputMetaDesciption" class="col-sm-2 control-label">Meta Desciption</label>
											<div class="col-sm-10">
												<?php echo form_input($input['meta_description']); ?>
												<?php echo form_error('data[vendor_categories][meta_description]'); ?>
											</div>
										</div>
									</div>
								</div><!-- /row -->
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputDescription" class="col-sm-1 control-label">Description</label>
											<div class="col-sm-11">
												<?php echo form_input($input['description']); ?>
												
											      <?php echo form_error('description'); ?>
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
					<!-- </div> -->
				</div>
			</div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

