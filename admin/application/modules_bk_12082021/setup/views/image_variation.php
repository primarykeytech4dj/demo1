<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$name  = array(
				'id' =>	'name',
				'required'	=>	'required',
				'class'	=>	'form-control select2 viewInput',
				'data-target' => 'name_input_0',
				'input-data-target' =>'name_input_0',
				'style' => 'width:100%',
				 /*data-target='faq_category_".$faquesKey."'*/
			 );

$input['value'] =  array(
							"name" => "data[variations][value]",
							"placeholder" => "Value *",
							"required" => "required",
							"class" => "form-control",
							"id" => "value",
							"tab-index" => 2,
						);




//echo "<div class = "for-group" >";
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{	
	//print_r($values_posted);

	foreach($values_posted as $post_name => $post_value)
	{ 
		foreach ($post_value as $field_key => $field_value) {
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
	    Module :: Variations
	</h1>
	<ol class="breadcrumb">
	    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
	    <li>
	      <?php echo anchor(custom_constants::admin_product_listing_url, 'Variation', 'title="products"'); ?>
	    </li>
	    <li>
	      <?php echo anchor(custom_constants::new_product_url, 'New Variation'); ?>
	    </li>
	</ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">

				<div class="tab-content">
					<?php echo form_open_multipart('setup/imagesetup', ['class'=>'form-horizontal', 'id'=>'new_product']);
						
						if($this->session->flashdata('message') !== FALSE) {
							$msg = $this->session->flashdata('message');?>
							<div class = "<?php echo $msg['class'];?>">
								<?php echo $msg['message'];?>
							</div>
						<?php } ?>
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i>Image Setup</h3>
							</div><!-- /box-header -->
							<!-- form start -->
							<div class="box-body">
								

								<div class="box-body" style="overflow-x:scroll">
									<table class="table" id="target">
										<thead>
											<tr>
												<th>Image Path</th>
												<th>Width</th>
												<th>Height</th>
												<th>Active</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php if(count($values_posted)>0){
											foreach ($values_posted as $key => $image) {
		$name  = array(
				'id' =>	'name_'.$key,
				'required'	=>	'required',
				'class'	=>	'form-control select2 viewInput',
				'data-target' => 'name_input_'.$key.'',
				'input-data-target' =>'name_input_'.$key.'',
				'style' => 'width:100%',
				 /*data-target='faq_category_".$faquesKey."'*/
			 );

		
														?>
													<tr id="<?=$key?>">
													
													<td><input type="hidden" name="image_setup[<?=$key?>][id]" value="<?=isset($image['id'])?$image['id']:''?>" id="id_<?=$key?>" class="form-control"><?php echo form_dropdown("image_setup[".$key."][image_folder_name]", $option['image'],isset($image['image_folder_name'])?$image['image_folder_name']:'' ,$name); ?>
														<input type="text" name="image_setup[<?=$key?>][name_input]" class="form-control input" style="display: none;" id="name_input_<?=$key?>" placeholder='Folder Name Not found? Enter here' ">
													</td>
													<td><input type="text" name="image_setup[<?=$key?>][width]" class="form-control" value="<?=$image['width']?>" required id="width<?=$key?>" ></td>
													<td><input type="text" name="image_setup[<?=$key?>][height]" class="form-control" value="<?=$image['height']?>" required id="height_<?=$key?>" ></td>
													<td><input type="checkbox" name="image_setup[<?=$key?>][is_active]" class="flat-red" id="is_active_<?=$key?>" 
														<?=($image['is_active'])?'checked="checked"':''?>></td>
													<td></td>
												</tr>	
													<?php }

												}else{?>
												<tr id="0">
													
													<td><?php echo form_dropdown("image_setup[0][image_folder_name]", $option['image'],'',$name); ?>
														<input type="text" name="image_setup[0][name_input]" class="form-control input" id="name_input_0" placeholder='Folder Not found? Enter here'>
													</td>
													<td><input type="text" name="image_setup[0][width]" class="form-control" id="width_0"></td>
													<td><input type="text" name="image_setup[0][height]" class="form-control"  required id="height_0" ></td>
													<td><input type="checkbox" name="image_setup[0][is_active]" class="flat-red" id="is_active_0"></td>
													<td></td>
												</tr>
												<?php } ?>
											</tbody>
											<tfoot>
												<tr>
											   		<td colspan="9"><button type="button" id="AddMoreVariation" class="btn btn-info pull-right AddMoreRow">Add More</button>
											   		</td>
											   	</tr>
											</tfoot>
										</table>
									</div>
									
								<div class="box-footer">  
									<button type="new_variation" class="btn btn-info pull-left"><?=(count($images)>0)?'Update':'Add'?> Image Variation</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
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

