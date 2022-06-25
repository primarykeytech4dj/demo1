
<div class="box box-info">
<?php echo form_open_multipart('products/admin_edit_product_images/'.$id, ['class'=>'form-horizontal', 'id'=>'edit_product_images']);?>
		<!-- <input type="hidden" name="product_id" value="<?php echo $id; ?>"> -->
		<div class="box-header with-border">
			<h3 class="box-title">Existing Product Images</h3>
		</div><!-- /box-header -->
		<h4 class="text-blue">Note: First image will be Thumbnail and Second Image will be Banner Image</h4>
		<!-- form start -->
		<div class="box-body">
			
			<div class="box-haeder with-border">
				<h2 class="box-title">Product Images</h2>
			</div>
			<div class="box-body" style="overflow-x:scroll">
				<table class="table" id="target">
					<thead>
						<tr>
							<th>Type</th>
							<th>Product Image 1</th>
							<!-- <th>Product Image 2</th> -->
							<th>Title</th>
							<th>Priority</th>
							<th>Thumbnail Image</th>
							<th>Is Active</th>
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
								<?php echo form_dropdown('product_images['.$productImageKey.'][type]',$option['type'], isset($product_image['type'])?$product_image['type']:'', ['id'=>'type_'.$productImageKey, 'class'=>'form-control select2 showInput type']); ?>
							</td>
							<td>

								<input type="file" name="product_images[<?php echo $productImageKey; ?>][image_name_1]", id="image_name_1_<?php echo $productImageKey; ?>", class="form required" style="display: <?php echo ($product_image['type']=='image')?'block':'none'; ?>">
								<input type="hidden" name="product_images[<?php echo $productImageKey; ?>][image_name_1_2]"  id="image_name_1_<?php echo $productImageKey; ?>"  value= "<?php echo $product_image['image_name_1']; ?>" class="form required">
								
							<?php //print_r($product_image['image_name_1']);?>
							<input type="text" name="product_images[<?php echo $productImageKey; ?>][video]" id="featured_image_video_<?php echo $productImageKey; ?>" class="form-control video" placeholder="https://www.youtube.com/embed/fEYx8dQr_cQ" value="<?php echo $product_image['image_name_1']; ?>" style="display: <?php echo ($product_image['type']=='video')?'block':'none'; ?>">
							<img src="<?php echo content_url().'uploads/'.(!empty($product_image['image_name_1'])?'products/'.$product_image['image_name_1']:'no_image.jpg'); ?>" height="80px" width="80px" style="display: <?php echo ($product_image['type']=='image')?'block':'none'; ?>" >
							
							</td>
							<td>
								<input type="text" name="product_images[<?php echo $productImageKey;?>][title]" id="title_<?=$productImageKey?>" value="<?=$product_image['title']?>">
								<!-- <input type="file" name="product_images[<?php echo $productImageKey; ?>][image_name_2]", id="image_name_1_<?php echo $productImageKey; ?>", class="form required"> -->
								<input type="hidden" name="product_images[<?php echo $productImageKey; ?>][image_name_2_2]" id="image_name_1_<?php echo $productImageKey; ?>" value= "<?php echo $product_image['image_name_2'];?>" class="form required">
							</td>

							<td>
								<input type="text" name="product_images[<?php echo $productImageKey;?>][priority]" id="priority_<?=$productImageKey?>" value="<?=$product_image['priority']?>" placeholder="Priority *" type="number">
							</td>
							
							<td><input type="checkbox" name="product_images[<?php echo $productImageKey; ?>][featured_image]" id="featured_image_<?php echo $productImageKey; ?>", <?php if($product_image['featured_image']){echo "checked='checked'";}?> class="form required SingleCheck"></td>
							<td><input type="checkbox" name="product_images[<?php echo $productImageKey; ?>][is_active]" id="is_active<?php echo $productImageKey; ?>", <?php if($product_image['is_active']){echo "checked='checked'";}?> class="form required flat-red"></td>
							<td></td>
						</tr>
							<?php }
						}else{
								?>
						<tr id="0">
							<td>
								
								<?php echo form_dropdown('product_images[0][type]',$option['type'], '', ['id'=>'type_0', 'class'=>'form-control select2 showInput type']); ?>
							</td>
							<td>

								<input type="file" name="product_images[0][image_name_1]", id="image_name_1_0", class="form required" style="display: block; ?>">
								
							<?php //print_r($product_image['image_name_1']);?>
							<input type="text" name="product_images[0][video]" id="featured_image_video_0" class="form-control video" placeholder="https://www.youtube.com/embed/fEYx8dQr_cQ" value="" style="display: none; ?>">
							
							</td>
							<td>
								<input type="text" name="product_images[0][title]" id="title_0">
								<input type="hidden" name="product_images[0][image_name_2_2]" id="image_name_1_0"  class="form required">
							</td>

							<td>
								<input type="text" name="product_images[0][priority]" id="priority_0" placeholder="Priority *" type="number">
							</td>
							
							<td><input type="checkbox" name="product_images[0][featured_image]" id="featured_image_0" class="form required SingleCheck"></td>
							<td><input type="checkbox" name="product_images[0][is_active]" id="is_active_0" class="form required flat-red"></td>
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