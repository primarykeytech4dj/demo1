
<div class="box box-info"><!---reached--->
	<?php echo form_open('products/admin_edit_product_attributes/'.$id, ['class'=>'form-horizontal', 'id'=>'edit_attribute']);?>
	
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
						<th>Attribute</th>
						<th>Margin(%)</th>
						<th>Our Price</th>
						<th>MRP</th>
						<th>Discount on Mrp</th>
						<th>Selling Price</th>
						<?php if(!$product['overall_stock_mgmt']){ ?>
							<th>In Stock Qty</th>
						<?php } ?>
						<th>Remark</th>
						<th>Is Default</th>
						<th>Is Active</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php //print_r($values_posted['product_attributes']);?>
						<?php 
				if(count($product_attributes)>0){
				    /*echo '<pre>';
				    print_r($product);*/
					foreach($product_attributes as $key =>$value) {
					    //print_r($value);
					?>

					<tr id="<?php echo $key; ?>" data-id="<?=$id?>">
						<td>
							<input type="hidden" name="product_attributes[<?php echo $key;?>][id]" class="form-control" id="id_<?php echo $key;?>" value="<?php echo $value['id']; ?>">
							<?=form_dropdown('product_attributes['.$key.'][attribute_id]', $option['attribute'], $value['attribute_id'],['id'=>'attribute_id_'.$key.'', 'class'=>'form-control attribute','required'=>'required','data-id'=>$id]);?>
							
						</td>
						<td>
							<input type="number" step='0.01' name="product_attributes[<?=$key?>][increased_percentage]" id="increased_percentage_<?=$key?>" value="<?=$value['increased_percentage']?>" class="increased_price form-control required" data-id="<?=$id?>">
						</td>
						<td>
							<input type="text" name="product_attributes[<?=$key?>][price]" id="price_<?=$key?>" value="<?=isset($value['price'])?$value['price']:0?>" class="form-control required">
						</td>
						<td>
							<input type="text" name="product_attributes[<?=$key?>][mrp]" value="<?=$value['mrp']?>" id="mrp_<?=$key?>" class="form-control required">
						</td>
						<td>
							<input type="text" name="product_attributes[<?=$key?>][discount]" id="discount_<?=$key?>" value="<?=$value['discount']?>" class="product_discount form-control required">
						</td>
						<td><input type="text" id="final_price_<?=$key?>"  class="form-control required finalprice" value="<?=($value['mrp']-(($value['discount']/100)*$value['mrp']))?>" name="attributes[<?=$key?>][final_price]"></td>
						<?php if(!$product['overall_stock_mgmt']){ ?>
							<td><input type="text" id="stockqty_<?=$key?>"  class="form-control required" value="<?=$value['stock_qty']?>" name="product_attributes[<?=$key?>][stock_qty]"></td>
						<?php } ?>
						<td>
							<input type="text" name="product_attributes[<?=$key?>][remarks]" id="remarks_<?=$key?>" value="<?=$value['remarks']?>" class="form-control">
						</td>
						<td><input type="checkbox" name="product_attributes[<?=$key?>][is_default]" id="is_default_<?=$key?>" <?=($value['is_default']==1)?'checked=checked':''?> class="SingleCheck"></td>
						<td><input type="checkbox" name="product_attributes[<?=$key?>][is_active]" id="is_active_<?=$key?>" <?=($value['is_active']==1)?'checked=checked':''?> class="flat-red form-control required"></td>
						<td></td>
					</tr>
						<?php }
					}else{
							?>
					<tr id="0">
						<td>
							<?=form_dropdown('product_attributes[0][attribute_id]', $option['attribute'],'',['id'=>'attribute_id_0', 'class'=>'attribute form-control','required'=>'required','data-id'=>$id]);?>
						
						</td>
						<td>
							<input type="number" step='0.01' name="product_attributes[0][increased_percentage]" id="increased_percentage_0" class="increased_price form-control required" data-id="<?=$id?>">
						</td>
						<td>
							<input type="text" name="product_attributes[0][price]" id="price_0" class="form-control required">
						</td>
						<td>
							<input type="text" name="product_attributes[0][mrp]" id="mrp_0" class="form-control required">
						</td>
						<td>
							<input type="text" name="product_attributes[0][discount]" id="discount_0" class="product_discount form-control required">
						</td>
						<td><input id="final_price_0" class="form-control required finalprice" name="attributes[0][final_price]" id="is_default_0"></td>
						<?php if(!$product['overall_stock_mgmt']){ ?>
							<td><input type="text" id="stockqty_0"  class="form-control required" value="0" name="product_attributes[0][stock_qty]"></td>
						<?php } ?>
						<td>
							<input type="text" name="product_attributes[0][remarks]" id="remarks_0" value="" class="form-control">
						</td>
						<td><input type="checkbox" name="product_attributes[0][is_default]" id="is_default_0"  class="SingleCheck"></td>
						<td><input type="checkbox" name="product_attributes[0][is_active]" id="is_active_0"  class="flat-red form-control required"></td>
						<td></td>
					</tr>
							<?php
						} ?>
				</tbody>
				<tfoot>
					<tr>
				   		<td colspan="9"><button type="button" id="AddMoreProductAttributes" class="btn btn-info pull-right AddMoreRow">Add More</button>
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