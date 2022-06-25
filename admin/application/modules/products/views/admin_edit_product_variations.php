<?php 
$input['show_on_website'] = array(
					"name" => "data[products][show_on_website]",
					"class" => "flat-red",
					"id" => "show_on_website",
					"type"=> "checkbox",
					"value" => true,
					);
?>

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
<section class="content">
	<div class="row">
		<div class="col-md-12">
		<?php 
		echo form_open_multipart('products/product_variation/'.$id, ['class'=>'form-horizontal', 'id'=>'new_product']);?>
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i>Product Variation</h3>
				</div><!-- /box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="inputCompanyProduct" class="col-sm-2 control-label">Variation</label>
								<div class="col-sm-10">
									<?php 
									foreach ($nameVariation as $key => $value) {?>
									<table class="table" id="target">
										<tbody>
											<tr>
												<td>
											<?=$key?>
										</td>
										<td>
										<?php foreach ($value as $varkey => $variationVal) {
											//print_r($variationVal);
											?>

												<input type="checkbox" name="data[product_variations][variation_id][]" id="variation_id_<?=$variationVal['id']?>" class="flat-red" type="checkbox" value="<?=$variationVal['id']?>" <?=(in_array($variationVal['id'], $productVariations))?'checked="checked"':""?>>
												<span style="background: <?=$variationVal['value']?>;width: 20px"><?=$variationVal['value']?></span>
										<br>		
										<?php } ?>
											</td>
										</tr>
									</tbody>
								</table>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">  
							<button type="new_college" class="btn btn-info pull-left">Add Variation</button> &nbsp;&nbsp;&nbsp;&nbsp;
							
						</div>
				</div>
			</div>
		<?=form_close();?>
		</div>
	</div>
</section>