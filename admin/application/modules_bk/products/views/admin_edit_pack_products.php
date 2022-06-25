
<div class="box box-info"><!---reached--->
	<?php echo form_open('products/admin_edit_pack_products/'.$id, ['class'=>'form-horizontal', 'id'=>'edit_attribute']);?>
	
	 <input type="hidden" name="product_id" value="<?php echo $id; ?>"> 
	<div class="box-header with-border">
			<h3 class="box-title">Product Attributes</h3>
	</div><!-- /box-header -->
	<!-- form start -->
		<div class="box-body" style="overflow-x:scroll">
			<?php //echo '<pre>';print_r($product);?>
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
					<?php if(count($packProducts)>0){
						foreach ($packProducts as $key => $pack) {
							?>
							<tr id="<?=$key?>">
							<?php //print_r($values_posted['products']);?>
								<td><?php echo form_dropdown("pack_products[".$key."][product_id]",$option['product'], $pack['product_id'],  ['id'=>'product_id_'.$key, 'class'=>'form-control select2 filter', 'required'=>'required', 'style'=>'width:100%', 'data-link'=>"products/productwise_attributes", "data-target"=>"productattributeid_".$key ]);?></td>
								<td>
								    <?php echo form_dropdown("pack_products[".$key."][product_attribute_id]", $option['productAttribute'][$pack['product_id']], $pack['product_attribute_id'],  "id='productattributeid_".$key."' class='form-control select2' style='width:100%'");?>
								</td>
								<td>
									<input type="text" name="pack_products[<?=$key?>][quantity]" class="form-control" id="quantity_<?=$key?>" value="<?=$pack['quantity']?>">
								</td>
								<td><input type="text" name="pack_products[<?=$key?>][priority]" id="priority_<?=$key?>" class="form-control" value="<?=$pack['priority']?>"></td>
								<td></td>
							</tr>
							<?php
						}
					}else{ ?>
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
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
				   		<td colspan="9"><button type="button" id="AddMorePackProducts" class="btn btn-info pull-right AddMoreRow">Add More</button>
				   		</td>
				   	</tr>
				</tfoot>
			</table>
		</div>
		<div class="box-footer">  
			<button type="new_product" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
	<?=form_close();?>
</div>
<script type="text/javascript">
   
</script>