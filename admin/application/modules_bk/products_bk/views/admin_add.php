<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['product'] = array(
						"name" => "data[products][product]",
						"placeholder" => "product name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "product",
					);
$input['priority'] = array(
						"name" => "data[products][priority]",
						"placeholder" => "Priority",
						"required" => "required",
						"class"=> "form-control",
						"id" => "priority",
						"type"=>'number',
						"min" => 1
					);

$input['product_code'] = array(
						"name" => "data[products][product_code]",
						"placeholder" => "product code *",
						"max_length" => "64",
						"class" => "form-control",
						'id' => "product_code"
					);


$input['profile_image'] = array(
						"name" => "product_categories",
						"placeholder" => "image *",
						"class"=> "form-control",
						"id" => "image",
						"value" => set_value('image'),
					);

$input['product_image'] =  array(
							"name" => "product_image",
							"placeholder" => "product image *",
							//"required" => "required",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "product_image",
							"value" =>	set_value('product_image'),
							 );

$input['product_categories'] =  array(
							"name" => "product_categories",
							"placeholder" => "product_categories *",
							//"required" => "required",
							"class" => "form-control",
							//"type"	=> "file",
							"id" => "product_categories",
							"value" =>	set_value('product_categories'),
							 );

/*$input['slug'] = array(
						"name" => "data[products][slug]",
						"placeholder" => "Slug *",
						"max_length" => "100",
						"required" => "required",
						"class"=> "form-control slugify",
						"id" => "slug",
					);*/					

$input['base_price'] = array(
						"name" => "data[products][base_price]",
						"placeholder" => "base price *",
						"max_length" => "100",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "base_price"
					);
$input['base_uom'] = [
	'id'=>'base_uom', 
	'required'=>'required', 
	'class'=>'form-control select2',
	'style'=>'width:100%'
];

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

$input['description'] = array(
						"name" => "data[products][description]",
						"placeholder" => "Description *",
						"required" => "required",
						"class"=> "form-control editor1",
						"id" => "description"
					);

$input['meta_title'] = array(
						"name" => "data[products][meta_title]",
						"placeholder" => "Meta Title ",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "meta_title"
					);

$input['meta_description'] = array(
						"name" => "data[products][meta_description]",
						"placeholder" => "Meta Description ",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "meta_description"
					);

$input['meta_keyword'] = array(
						"name" => "data[products][meta_keyword]",
						"placeholder" => "Meta Keyword",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "meta_keyword"
					);

$input['is_sale'] = array(
					"name" => "data[products][is_sale]",
					"class" => "flat-red",
					"id" => "is_sale",
					"type"=> "checkbox",
					"value" => true,
					);

$input['is_gift']  = array(
					"name" => "data[products][is_gift]",
					"class" => "flat-red",
					"id" => "is_gift",
					"type" => "checkbox",
					"value" => true,
				);

$input['is_new']  = array(
					"name" => "data[products][is_new]",
					"class" => "flat-red",
					"id" => "is_new",
					"type" => "checkbox",
					"value" => true,
				);

$input['is_pack']  = array(
					"name" => "data[products][is_pack]",
					"class" => "showdiv",
					"id" => "is_pack",
					"type" => "checkbox",
					"value" => true,
					'data-show'=>'packproducts'
				);
$input['overall_stock_mgmt'] = array(
					"name" => "data[products][overall_stock_mgmt]",
					"class" => "flat-red",
					"id" => "overall_stock_mgmt",
					"type"=> "checkbox",
					"value" => true,
					);
$input['unit'] = array(
						"name" => "pack_products[unit]",
						"placeholder" => "Unit",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "unit"
					);

$input['quantity'] = array(
						"name" => "pack_products[quantity]",
						"placeholder" => "Quantity",
						"max_length" => "100",
						"class"=> "form-control",
						"id" => "quantity"
					);

$input['show_on_website'] = array(
					"name" => "data[products][show_on_website]",
					"class" => "flat-red",
					"id" => "show_on_website",
					"type"=> "checkbox",
					"value" => true,
					);
$input['company'] = [
	'id'		=>		'company_id', 
	'required'	=>		'required', 
	'class'		=>		'form-control select2 filter multiple', 
	'data-link'	=>		'products/getCompanyWiseProductCategories',
	'data-target'=>		'category',
	"multiple"	=>	'multiple'
	
];
if($_SESSION['application']['share_products']){
	$input['company']['multiple'] = 'multiple';
}
//print_r($_SESSION);exit;
$input['brand'] = [
	'id'		=>		'brand_id', 
	/*'required'	=>		'required', */
	'class'		=>		'form-control select2'
];
if($_SESSION['application']['product_has_multiple_brand']){
	$input['brand']['multiple'] = 'multiple';
}

$type 	=	array(
				'id'	=>	'type_0',
				//'required'	=>	'required',
				'class'	=>	'form-control select2 showInput type',
				'style' => 'width:100%',
                'tab-index'=>'2'
				);

//print_r($_SESSION);exit;
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
	    Module :: Products
	</h1>
	<ol class="breadcrumb">
	    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
	    <li>
	      <?php echo anchor(custom_constants::admin_product_listing_url, 'Products', 'title="products"'); ?>
	    </li>
	    <li>
	      <?php echo anchor(custom_constants::new_product_url, 'New Product'); ?>
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
					
						<?php //echo form_open_multipart(custom_constants::new_user_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
							//print_r($this->session);
						echo form_open_multipart(custom_constants::new_product_url, ['class'=>'form-horizontal', 'id'=>'new_product']);
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> New Product</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<?php $counter = 1;?>
								<div class="box-body">

									<div class="row">
									<?php if(isset($_SESSION['application']['multiple_company']) && $_SESSION['application']['multiple_company']==true){?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputCompanyProduct" class="col-sm-2 control-label">Company</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[companies_products][company_id][]',$option['company'], !(set_value('data[companies_products][company_id]'))?'0':set_value('data[companies_products][company_id]'), $input['company']);?>
													<?php echo form_error('data[companies_products][company_id]');?>
												</div>
											</div>
										</div>
									<?php $counter = $counter+1;} ?>
									<?php if($_SESSION['application']['product_has_multiple_brand']){?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputCompanyProduct" class="col-sm-2 control-label">Grade/ Brand</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[brand_products][brand_id][]',$option['brand'], set_value('data[brand_products][brand_id]'), $input['brand']);?>
													<?php echo form_error('data[brand_products][brand_id]');?>
												</div>
											</div>
										</div>
										<?php $counter = $counter+1;} ?>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputCategory" class="col-sm-2 control-label">Category</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[products][product_category_id]', $option['category'], isset($values_posted)?$values_posted['products']['product_category_id']:'',"id='category' required='required' class='form-control select2'"); ?>
													<?php echo form_error('data[products][category]'); ?>
												</div>
											</div>
										</div>
									<?php if($counter==3){
										echo '</div><div class="row">';
									} ?>
									<div class="col-md-4">
											<div class="form-group">
												<label for="inputProduct_type" class="col-sm-2 control-label">Product Type</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[products][product_type]',$product_type, isset($values_posted['products']['product_type'])?$values_posted['products']['product_type']:1,"id='product_type' required='required' class='form-control select2' ");?>
													<?php echo form_error('data[products][product_code]'); ?>
												</div>
											</div>
										</div>
										
									
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputProduct" class="col-sm-2 control-label">Product</label>
												<div class="col-sm-10">
													<?php echo form_input($input['product']); ?>
													<?php echo form_error('data[products][product]'); ?>
												</div>
											</div>
										</div>
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
									<?php } ?>
									
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputBaseprice" class="col-sm-2 control-label">Base Price</label>
												<div class="col-sm-10">
													<?php echo form_input($input['base_price']);?>
													<?php echo form_error('data[products][base_price]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputbaseUom" class="col-sm-2 control-label">Base Uom</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[products][base_uom]',$option['attributes'], (!set_value('data[products][base_uom]'))?'':set_value('data[products][base_uom]'), $input['base_uom']);?>
													<?php echo form_error('data[products][base_uom]');?>
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsSale" class="col-sm-6 control-label">Is Sale</label>
												<div class="col-sm-6">
													<?php echo form_input($input['is_sale']);?>
													<?php echo form_error('data[products][is_sale]');?>
												</div>
											</div>
										</div>
									
										
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsNew" class="col-sm-6 control-label">Is New</label>
												<div class="col-sm-6">
													<?php echo form_input($input['is_new']);?>
													<?php echo form_error('data[products][is_new]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsGift" class="col-sm-6 control-label">Is Gift</label>
												<div class="col-sm-6">
													<?php echo form_input($input['is_gift']);?>
													<?php echo form_error('data[products][is_gift]');?>
												</div>
											</div>
										</div>
									
										
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsPack" class="col-sm-6 control-label">Show on Website</label>
												<div class="col-sm-6">
													<?php echo form_input($input['show_on_website']);?>
													<?php echo form_error('data[products][show_on_website]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputIsPack" class="col-sm-6 control-label">Is Pack Product</label>
												<div class="col-sm-6">
													<?php echo form_input($input['is_pack']);?>
													<?php echo form_error('data[products][is_pack]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputOverall_stock_mgmt" class="col-sm-6 control-label">Overall Stock Mgmt</label>
												<div class="col-sm-6">
													<?php echo form_input($input['overall_stock_mgmt']);?>
													<?php echo form_error('data[products][overall_stock_mgmt]');?>
												</div>
											</div>
										</div>
									
										
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputDesciption" class="col-sm-1 control-label">Desciption</label>
												<div class="col-sm-11">
													<?php echo form_input($input['description']);?>
													<?php echo form_error('data[products][description]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputMetaTitle" class="col-sm-2 control-label">Meta Title</label>
												<div class="col-sm-10">
													<?php echo form_input($input['meta_title']);?>
													<?php echo form_error('data[products][meta_title]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputMetaDesciption" class="col-sm-2 control-label">Meta Desciption</label>
												<div class="col-sm-10">
													<?php echo form_input($input['meta_description']);?>
													<?php echo form_error('data[products][meta_description]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputMetaKeyword" class="col-sm-2 control-label">Meta Keyword</label>
												<div class="col-sm-10">
													<?php echo form_input($input['meta_keyword']);?>
													<?php echo form_error('data[products][meta_keyword]');?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputPriority" class="col-sm-2 control-label">Priority</label>
												<div class="col-sm-10">
													<?php echo form_input($input['priority']); ?>
													<?php echo form_error('data[products][priority]'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="packproducts hideIt" id="packproducts" >

										<div class="box-header with-border">
											<h2 class="box-title">Pack Products</h2>
										</div>
										<div class="box-body" style="overflow-x:scroll">
											<table class="table" id="target">
												<thead>
													<tr>
														<th>Product </th>
														<th>Attributes</th>
														<th>Qty</th>
														<th>Display Priority</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<tr id="0">
														<?php //print_r($values_posted['products']);?>
														<td><?php echo form_dropdown("pack_products[0][product_id]",$option['product'],'',  ['id'=>'product_id_0', 'class'=>'form-control select2 filter', 'required'=>'required', 'style'=>'width:100%', 'data-link'=>"products/productwise_attributes", "data-target"=>"productattributeid_0" ]);?></td>
														<td>
														    <?php echo form_dropdown("pack_products[0][product_attribute_id]", ['0'=>'Select Atrribute'],'',  "id='productattributeid_0' class='form-control select2' style='width:100%'");?>
															<!--<input type="text" name="pack_products[0][unit]" class="form-control" id="unit_0" >-->
														</td>
														<td>
															<input type="text" name="pack_products[0][quantity]" class="form-control" id="quantity_0" >
														</td>
														<td><input type="text" name="pack_products[0][priority]" id="priority_0" class="form-control"></td>
														<td></td>
													</tr>
												</tbody>
												<tfoot>
													<tr>
												   		<td colspan="9"><button type="button" id="AddMorePackProducts" class="btn btn-info pull-right AddMoreRow">Add More</button>
												   		</td>
												   	</tr>
												</tfoot>
											</table>
										</div>
									</div>
								<!-- <div class="box-footer">  --> 


									<div class="box-haeder with-border">
										<h2 class="box-title">Product Images</h2>
									</div>
																		<div class="box-body" style="overflow-x:scroll">
										<table class="table" id="target">
											<thead>
												<tr>
													<th>Type</th>
													<th>Product Image 1</th>
													<!--<th>Product Image 2</th>-->
													<th>Title</th>
													<th>Priority</th>
													<th>Featured Image</th>
													<th>Is Active</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr id="0">
													<td><?php echo form_dropdown('product_images[0][type]',$option['type'], set_value('product_images[0][type]'),$type); ?></td>
													<td><!-- <div id="image_0" class="image"> --><input type="file" name="product_images[0][image_name_1]" id="image_name_1_0" class="form-control image"><!-- </div> -->
														<!-- <div id="video_0" class="video" style="display: none"> --><input type="text" name="product_images[0][video]" id="featured_image_video_0" class="form-control video" placeholder="https://www.youtube.com/embed/fEYx8dQr_cQ" value="<?php echo set_value('product_images[0][video]'); ?>" style="display: none"><!-- </div> -->
													</td>
													<!-- <td id="video" style="display: block"><input type="text" name="product_images[0][video]" id="featured_image_video_0" class="form-control" placeholder="https://www.youtube.com/embed/fEYx8dQr_cQ" value="<?php echo set_value('product_images[0][video]'); ?>">
													</td> -->
													<td><input type="text" name="product_images[0][title]" id="title_0" class="form-control"></td>
													<td><input type="text" name="product_images[0][priority]" id="priority_0" class="form-control"></td>
													<td><input type="checkbox" name="product_images[0][featured_image]" id="featured_image_0" class="form required SingleCheck"></td>
													<td><input type="checkbox" name="product_images[0][is_active]" id="is_active_0" class="form required"></td>
													<td></td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
											   		<td colspan="9"><button type="button" id="AddMoreProductImages" class="btn btn-info pull-right AddMoreRow">Add More</button>
											   		</td>
											   	</tr>
											</tfoot>
										</table>
									</div>

									
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Add Product</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
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

