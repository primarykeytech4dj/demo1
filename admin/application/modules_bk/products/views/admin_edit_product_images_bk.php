<div class="box box-info">
<?php echo form_open_multipart('products/admin_edit_product_images/'.$id, ['class'=>'form-horizontal', 'id'=>'edit_product_images']);?>
		<!-- <input type="hidden" name="product_id" value="<?php echo $id; ?>"> -->
		<div class="box-header with-border">
			<h3 class="box-title">Existing Product Images</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body">
			
			<div class="box-haeder with-border">
				<h2 class="box-title">Product Images</h2>
			</div>
			<div class="box-body" style="overflow-x:scroll">
				<table class="table" id="target">
					<thead>
						<tr>
							<th>Product Image 1</th>
							<th>Product Image 2</th>
							<th>Featured Image</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php //print_r($values_posted['product_images']);?>
							<?php 
					if(count($product_images)>0){
						foreach($product_images as $productImageKey =>$product_image) {
								//print_r($product_image);
								//print_r($product_image['image_name_1']);
								//print_r($product_image['image_name_2']);
								//print_r($product_image['featured_image']);
								?>

						<tr id="<?php echo $productImageKey; ?>">
							<td>
								<input type="hidden" name="product_images[<?php echo $productImageKey;?>][id]" class="form-control" id="id_<?php echo $productImageKey;?>" value="<?php echo $product_image['id']; ?>">
								<input type="file" name="product_images[<?php echo $productImageKey; ?>][image_name_1]", id="image_name_1_<?php echo $productImageKey; ?>", class="form required">
								<input type="hidden" name="product_images[<?php echo $productImageKey; ?>][image_name_1_2]"  id="image_name_1_<?php echo $productImageKey; ?>"  value= "<?php echo $product_image['image_name_1']; ?>" class="form required">
								
							<img src="<?php echo content_url().'uploads/'.(!empty($product_image['image_name_1'])?'products/'.$product_image['image_name_1']:'no_image.jpg'); ?>" height="80px" width="80px">
							
							</td>
							<td>
								<input type="file" name="product_images[<?php echo $productImageKey; ?>][image_name_2]", id="image_name_1_<?php echo $productImageKey; ?>", class="form required">
								<input type="hidden" name=product_images[<?php echo $productImageKey; ?>][image_name_2_2]" id="image_name_1_<?php echo $productImageKey; ?>", value= "<?php echo $product_image['image_name_2'];?>"class="form required">
								
							<img src="<?php echo content_url().'uploads/'.(!empty($product_image['image_name_2'])?'products/'.$product_image['image_name_2']:'no_image.jpg'); ?>" 	height="80px" width="80px">
								<?php //echo form_input('data[product_image']);?>
							</td>
							
							<td><input type="checkbox" name="product_images[<?php echo $productImageKey; ?>][featured_image]" id="featured_image_<?php echo $productImageKey; ?>", <?php if($product_image['featured_image']){echo "checked='checked'";}?> class="form required SingleCheck"></td>
							<td></td>
						</tr>
							<?php }
						}else{
								?>
						<tr id="0">
							<td>
								
								<input type="file" name="product_images[0][image_name_1]", id="image_name_1_0", class="form required">
							
							</td>
							<td>
								<input type="file" name="product_images[0][image_name_2]", id="image_name_1_0", class="form required">
								
							</td>
							
							<td><input type="checkbox" name="product_images[0][featured_image]" id="featured_image_0" class="form required SingleCheck"></td>
							<td></td>
						</tr>
								<?php
							} ?>
					</tbody>
					<tfoot>
						<tr>
					   		<td colspan="9"><button type="button" id="AddMoreProductImages" class="btn btn-info pull-right AddMoreRow">Add More</button>
					   		</td>
					   	</tr>
					</tfoot>
				</table>
			</div>
		</div><!-- /box body-->
		<div class="box-footer">  
			<button type="new_product" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
<?php echo form_close(); ?> 
</div>