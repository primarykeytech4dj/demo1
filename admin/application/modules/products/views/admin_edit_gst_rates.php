<div class="box box-info"><!---reached--->
	<?php echo form_open('products/admin_edit_gst_rates/'.$id, ['class'=>'form-horizontal', 'id'=>'edit_gst_rates']);?>
	
	 <input type="hidden" name="product_id" value="<?php echo $id; ?>"> 
	<div class="box-header with-border">
			<h3 class="box-title">GST RATES SETUP</h3>
	</div><!-- /box-header -->
	<!-- form start -->
		<div class="box-body" style="overflow-x:scroll">
			<?php //echo '<pre>';print_r($product);?>
			<table class="table" id="target">
				<thead>
					<tr>
						<th>GST Rate</th>
						<th>HSN/SAC Code</th>
						<th>Effective From</th>
						<th>Effective Till</th>
						<th>Is Active</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php //print_r($values_posted['product_attributes']);?>
						<?php 
				if(count($product_gst_rates)>0){
				    /*echo '<pre>';
				    print_r($product);*/
					foreach($product_gst_rates as $key =>$value) {
					    //print_r($value);
					?>

					<tr id="<?php echo $key; ?>" data-id="<?=$id?>">
						
						<td>
						    <input type="hidden" name="product_gst_rates[<?php echo $key;?>][id]" class="form-control" id="id_<?php echo $key;?>" value="<?php echo $value['id']; ?>">
							<input type="text" name="product_gst_rates[<?=$key?>][gst_rate]" id="gst_rate_<?=$key?>" value="<?=$value['gst_rate']?>" class="form-control required" data-id="<?=$id?>">
						</td>
						<td>
							<input type="text" name="product_gst_rates[<?=$key?>][hsn_sac_code]" id="hsnsac_code_<?=$key?>" value="<?=$value['hsn_sac_code']?>" class="form-control required">
						</td>
						<td>
							<input type="text" name="product_gst_rates[<?=$key?>][effective_from]" value="<?=date('d/m/Y', strtotime($value['effective_from']))?>" id="effective_from_<?=$key?>" class="form-control required datepicker">
						</td>
						<td>
							<input type="text" name="product_gst_rates[<?=$key?>][effective_till]" id="effective_till_<?=$key?>" value="<?=($value['effective_till']!=NULL)?date('d/m/Y', strtotime($value['effective_till'])):''?>" class="form-control datepicker">
						</td>
						<td><input type="checkbox" name="product_gst_rates[<?=$key?>][is_active]" id="is_active_<?=$key?>" <?=($value['is_active']==1)?'checked=checked':''?> class="SingleCheck"></td>
						<td></td>
					</tr>
						<?php }
					}else{
							?>
					<tr id="0">
						<td>
						    <input type="hidden" name="product_gst_rates[0][id]" class="form-control" id="id_0" value="<?php echo $value['id']; ?>">
							<input type="text" name="product_gst_rates[0][gst_rate]" id="gst_rate_0" value="0" class="form-control required" data-id="<?=$id?>">
						</td>
						<td>
							<input type="text" name="product_gst_rates[0][hsn_sac_code]" id="hsnsac_code_0" value="<?=$value['hsn_sac_code']?>" class="form-control required">
						</td>
						<td>
							<input type="text" name="product_gst_rates[0][effective_from]" value="<?=date('d/m/Y', strtotime($value['effective_from']))?>" id="effective_from_0" class="form-control required datepicker">
						</td>
						<td>
							<input type="text" name="product_gst_rates[0][effective_till]" id="effective_till_0" value="<?=($value['effective_till']!="0000-00-00")?date('d/m/Y', strtotime($value['effective_from'])):''?>" class="form-control datepicker">
						</td>
						<td><input type="checkbox" name="product_gst_rates[0][is_active]" id="is_active_0" <?=($value['is_active']==1)?'checked=checked':''?> class=""></td>
						<td></td>
					</tr>
							<?php
						} ?>
				</tbody>
				<tfoot>
					<tr>
				   		<td colspan="9"><button type="button" id="AddMoreProductGST" class="btn btn-info pull-right AddMoreRow">Add More</button>
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