<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$value='';
// /echo $values_posted['products']['dob'];exit;
$input['category_name'] = array(
						"name" => "data[products][parent]",
						"placeholder" => "Category name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "category_name",
					);

$input['product_type'] = array(
						"name" => "data[products][product_type]",
						"placeholder" => "Product Type (s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "product_type",
						'width'=>'100%'
					);


$input['product'] = array(
						"name" => "data[products][product]",
						"placeholder" => "Product (s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "product",
					);
$input['priority'] = array(
						"name" => "data[products][priority]",
						"required" => "required",
						"class"=> "form-control",
						"id" => "priority",
						"type"=>'number'
					);
if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==true){
	//echo "hii";
	$input['tally_name'] = array(
							"name" => "data[products][tally_name]",
							"placeholder" => "Name as per tally",
							"max_length" => "255",
							"required" => "required",
							"class"=> "form-control",
							"id" => "tally_name"
						);
}


$input['product_code'] = array(
						"name" => "data[products][product_code]",
						"placeholder" => "Product Code (s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "product_code",
					);


/*$input['slug'] = array(
							'name' => "data[products][slug]",
							'placeholder'=> "Slug(s) *",
							"max_length" =>"64",
							"required" =>"required",
							"class" =>"form-control",
							"id" => "slug",
							 );*/

$input['base_price'] = array(
						"name" => "data[products][base_price]",
						"placeholder" => "Base Price (s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "base_price",
					);


$input['description'] = array(
						"name" => "data[products][description]",
						"placeholder" => "Description (s) *",
						"required" => "required",
						"class"=> "form-control textarea",
						"id" => "description",
					);


$input['meta_title'] =  array(
							"name" => "data[products][meta_title]",
							"placeholder" => "Meta Title",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_title",
							 );

$input['meta_keyword'] =  array(
							"name" => "data[products][meta_keyword]",
							"placeholder" => "Meta Keyword",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_keyword",
							 );							

$input['meta_description'] =  array(
							"name" => "data[products][meta_description]",
							"placeholder" => "Meta Description",
							"max_length" => "100",
							"class" => "form-control",
							"id" => "meta_description",
							 );

/*$input['image_name_1'] =  array(
							"name" => "image_name_1",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "image_name_1",
							"value" =>	set_value('image_name_1'),
							 );
	*/
/*$input['image_name_1_2'] =  array(
							"data[product_images][image_name_1_2]" => $values_posted['product_images']['image_name_1'],
							"data[product_images][id]" => $id,
							//"value" =>	$values_posted['product_images']['logo_image'],
							 );*/


$input['is_sale'] = array(
					"name" => "data[products][is_sale]",
					"class" => "flat-red",
					"id" => "is_sale",
					"type"=> "checkbox",
					/*"checked" => (True == $values_posted['products']['is_sale'])?"checked":'',*/
					"value" => true,
					);

$input['is_gift'] = array(
					"name" => "data[products][is_gift]",
					"class" => "flat-red",
					"id" => "is_gift",
					"type"=> "checkbox",
					/*"checked" => (True == $values_posted['products']['is_gift'])?"checked":'',*/
					"value" => true,
					);

$input['is_new'] = array(
					"name" => "data[products][is_new]",
					"class" => "flat-red",
					"id" => "is_new",
					"type"=> "checkbox",
					"value" => true,
					);
$input['overall_stock_mgmt'] = array(
					"name" => "data[products][overall_stock_mgmt]",
					"class" => "flat-red",
					"id" => "overall_stock_mgmt",
					"type"=> "checkbox",
					"value" => true,
					);
$input['is_pack']  = array(
					"name" => "data[products][is_pack]",
					"class" => "flat-red showdiv",
					"id" => "is_pack",
					"type" => "checkbox",
					"value" => true,
					'data-show'=>'packproducts'
				);
				
$input['is_active']  = array(
					"name" => "data[products][is_active]",
					"class" => "flat-red",
					"id" => "is_pack",
					"type" => "checkbox",
					"value" => true,
				);

$input['show_on_website'] = array(
					"name" => "data[products][show_on_website]",
					"class" => "flat-red",
					"id" => "show_on_website",
					"type"=> "checkbox",
					"value" => true,
					);

$input['company'] = [
	'id'=>'company_id', 
	'required'=>'required', 
	'class'=>'form-control select2 filter', 
	'data-link'=>'products/getCompanyWiseProductCategories',
	'data-target'=>'category',
	'style'=>'width:100%'
];

if($_SESSION['application']['share_products']){
	$input['company']['multiple'] = 'multiple';
}

$input['brand'] = [
	'id'		=>		'brand_id', 
	/*'required'	=>		'required', */
	'class'		=>		'form-control select2',
	'style'=>'width:100%'
];

$input['base_uom'] = [
	'id'=>'base_uom', 
	'required'=>'required', 
	'class'=>'form-control select2',
	'style'=>'width:100%'
];
if($_SESSION['application']['product_has_multiple_brand']){
	$input['brand']['multiple'] = 'multiple';
}
//echo '<pre>';print_r($_SESSION['application']);exit;
//print_r($input['company']);exit;

// If form has been submitted with errors populate fields that were already filled
//print_r($values_posted);
if(isset($values_posted))
{ //print_r($values_posted);
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
		Module :: Products
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="<?php echo base_url().'products'; ?>" title="products">products</a></li>
		
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
        	<?php /*echo '<pre>';
        	print_r($values_posted);*/
        	?>
        	<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="<?php if($tab=="product_details"){echo "active";} ?>"><a href="#product_details" data-toggle="tab">Product Details</a></li>
					<?php if($values_posted['products']['is_pack']){?>
					<li class="<?php if($tab=="pack_products"){echo "active";} ?>"><a href="#pack_products" data-toggle="tab">Pack(Basket) Products Setup</a></li>
					<?php } ?>
					<li class="<?php if($tab=="product_images"){echo "active";} ?>"><a href="#product_images" data-toggle="tab">Product Images</a></li>
					<?php if($values_posted['products']['overall_stock_mgmt']){?>
					<li class="<?php if($tab=="other_details"){echo "active";} ?>"><a href="#other_details" data-toggle="tab">Other Details</a></li>
					<?php } ?>
					<!-- <li class="<?php if($tab=="product_variation"){echo "active";} ?>"><a href="#product_variation" data-toggle="tab">Product Variation</a></li> -->
					<li class="<?php if($tab=="product_attribute"){echo "active";} ?>"><a href="#product_attribute" data-toggle="tab">Product Attribute</a></li>
				</ul> 
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="product_details"){echo "active";} ?>" id="product_details"> 
						<?php //echo form_open_multipart(custom_constants::edit_employee_url ."/".$id, ['class'=>'form-horizontal', 'id'=>'register_user']); 
						echo form_open_multipart(custom_constants::edit_product_url."/".$id, ['class'=>'form-horizontal', 'id'=>'edit_products'])
						?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Existing Product</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<?php $counter=1;?>
								<div class="box-body">
									<div class="row">
									<?php if($_SESSION['application']['multiple_company']){
										?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputCompanyProduct" class="col-sm-2 control-label">Company</label>
												<div class="col-sm-10">
													<?php //echo $company_id;?>
													<?php echo form_dropdown('data[companies_products][company_id][]',$option['company'], isset($company_id)?$company_id:'', $input['company']);?>
													<?php echo form_error('data[companies_products][company_id]');?>
												</div>
											</div>
										</div>
									<?php $counter=$counter+1; } ?>
									<?php if($_SESSION['application']['product_has_multiple_brand']){?>
									<div class="col-md-4">
											<div class="form-group">
												<label for="inputBrandProduct" class="col-sm-2 control-label">Brand</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[brand_products][brand_id][]',$option['brand'], isset($brand_id)?$brand_id:'', $input['brand']);?>
													<?php echo form_error('data[brand_products][brand_id]');?>
												</div>
											</div>
										</div>
										<?php $counter=$counter+1; ?>
									<?php } ?>
									
										<!-- <div class="col-md-4">
											<div class="form-group">
												<label for="inputParent" class="col-sm-2 control-label">Product Category</label>
												<div class="col-sm-10">
													
													<select name="data[products][product_category_id]" id='product_category_id' class="form-control select2" style="width: 100%">
														
														<?php foreach($productCategories as $productKey => $productCategory) {?>
														<option value="<?php echo $productCategory['id'];?>" <?php if($productCategory['id'] == $values_posted['products']['product_category_id']){echo " selected='selected'";}?>><?php echo $productCategory['category_name'];?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div> -->
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputCategory" class="col-sm-2 control-label">Category</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[products][product_category_id]', $option['category'], isset($values_posted)?$values_posted['products']['product_category_id']:'',"id='category' required='required' class='form-control select2' style='width:100%'"); ?>
													<?php echo form_error('data[products][category]'); ?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputProductType" class="col-sm-2 control-label">Product Type</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[products][product_type]', $product_type, isset($values_posted['products']['product_type'])?$values_posted['products']['product_type']:'','id="product_type" required="required" class="form-control select2" style="width:100%"');?>
													
												      <?php echo form_error('product_type'); ?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputProduct" class="col-sm-2 control-label">Product</label>
												<div class="col-sm-10">
													<?php echo form_input($input['product']); ?>
													
												      <?php echo form_error('product'); ?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<?php if(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==1){ ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputTallyname" class="col-sm-2 control-label">Tally Name</label>
												<div class="col-sm-10">
							                      <?php echo form_input($input['tally_name']);?>
													<?php echo form_error('data[products][tally_name]');?>
							                  </div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
									<?php } ?>
										<div class="col-md-4">
											<div class="form-group">  
											 <label for="inputProductCode" class="col-sm-2 control-label">Product Code</label>
												<div class="col-sm-10">
													<?php echo form_input($input['product_code']); ?>
													<?php echo form_error('product_code'); ?>
												</div>                       
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<!-- <div class="col-md-4">
											<div class="form-group">                         
												<label for="inputSlug" class="col-sm-2 control-label">Slug / url</label>
												<div class="col-sm-10">
													<?php echo form_input($input['slug']); ?>
													<?php echo form_error('slug'); ?>
												</div>
											</div>
										</div> -->
										<div class="col-md-4">
											<div class="form-group">   
											 <label for="inputBasePrice" class="col-sm-2 control-label">Base Price</label>
												<div class="col-sm-10">
													<?php echo form_input($input['base_price']); ?>
													<?php echo form_error('base_price'); ?>
												</div>                      
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputbaseUom" class="col-sm-2 control-label">Base Uom</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[products][base_uom]',$option['attributes'], (!set_value('data[products][base_uom]'))?$values_posted['products']['base_uom']:set_value('data[products][base_uom]'), $input['base_uom']);?>
													<?php echo form_error('data[products][base_uom]');?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputMetaTitle" class="col-sm-2 control-label">Meta Title</label>
												<div class="col-sm-10">
													<?php echo form_input($input['meta_title']); ?>
													<?php echo form_error('data[products][meta_title]'); ?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputMetaKeyWord" class="col-sm-2 control-label">Meta KeyWord</label>
												<div class="col-sm-10">
													<?php echo form_input($input['meta_keyword']); ?>
													<?php echo form_error('data[products][meta_keyword]'); ?>
												</div>
											</div>
										</div>
									
										
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputMetaDesciption" class="col-sm-2 control-label">Meta Desciption</label>
												<div class="col-sm-10">
													<?php echo form_input($input['meta_description']); ?>
													<?php echo form_error('data[products][meta_description]'); ?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsSale" class="col-sm-2 control-label">Is Sale</label>
												<div class="col-sm-10">
													<?php echo form_input($input['is_sale']); ?>
													<?php echo form_error('data[products][is_sale]'); ?>
												</div>
											</div>
										</div>
									
										
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsNew" class="col-sm-2 control-label">Is New</label>
												<div class="col-sm-10">
													<?php echo form_input($input['is_new']); ?>
													<?php echo form_error('data[products][is_new]'); ?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsGift" class="col-sm-2 control-label">Is Gift</label>
												<div class="col-sm-10">
													<?php echo form_input($input['is_gift']); ?>
													<?php echo form_error('data[products][is_gift]'); ?>
												</div>
											</div>
										</div>
									
										
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsPack" class="col-sm-2 control-label">Is Pack Product</label>
												<div class="col-sm-10">
													<?php echo form_input($input['is_pack']);?>
													<?php echo form_error('data[products][is_pack]');?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsPack" class="col-sm-2 control-label">Show on Website</label>
												<div class="col-sm-10">
													<?php echo form_input($input['show_on_website']);?>
													<?php echo form_error('data[products][show_on_website]');?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIs_active" class="col-sm-2 control-label">Active</label>
												<div class="col-sm-10">
													<?php echo form_input($input['is_active']);?>
													<?php echo form_error('data[products][is_active]');?>
												</div>
											</div>
										</div>
										<?=($counter%3==0)?'</div><div class="row">':''?>
										<?php $counter=$counter+1; ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputOverall_stock_mgmt" class="col-sm-2 control-label">Overall Stock Mgmt</label>
												<div class="col-sm-10">
													<?php echo form_input($input['overall_stock_mgmt']);?>
													<?php echo form_error('data[products][overall_stock_mgmt]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputPriority" class="col-sm-2 control-label">Priority</label>
												<div class="col-sm-10">
													<?php echo form_input($input['priority']);?>
													<?php echo form_error('data[products][priority]');?>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputDescription" class="col-sm-1 control-label">Description</label>
												<div class="col-sm-11">
													<?php echo form_textarea($input['description']); ?>
													<?php echo form_error('data[products][description]'); ?>
												</div>
											</div>
										</div>
									</div>
									
								</div>
								<div class="box-footer">  
									<button type="new_product" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
						<?php echo form_close(); ?> 
					</div>
					<div class="tab-pane <?php if($tab=="pack_products"){echo "active";} ?>" id="pack_products">
						<?php echo $packProducts; ?>

					</div>
					<div class="tab-pane <?php if($tab=="product_images"){echo "active";} ?>" id="product_images"> 
						<?php echo $productImages; ?>
					</div>
					
					<div class="tab-pane <?php if($tab=="other_details"){echo "active";} ?>" id="other_details"> 
						<?php echo $productDetails; ?>
					</div>
					<div class="tab-pane <?php if($tab=="product_variation"){echo "active";} ?>" id="product_variation">
						<?php echo $variation; ?>

					</div>
					<div class="tab-pane <?php if($tab=="product_attribute"){echo "active";} ?>" id="product_attribute">
						<?php echo $attribute; ?>
					</div><!-- /tab-pane -->
				</div><!-- /tab-content-->
			</div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

