<div class="box-header with-border">
	<h2 class="box-title">Enquiry Details</h2>
</div>
<div class="box-body" style="overflow-x:scroll">
	<table class="table" id="target">
		<thead>
			<tr>
				<th>Product </th>
				<th>Pack Product</th>
				<th>Quantity</th>
				<th>Unit</th>
				<th>Price</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr id="0">
				<?php //print_r($values_posted['products']);?>
				<td><?php echo form_dropdown("enquiries_details[0][product_id]",$option['product'],''/*isset($values_posted['products'])?$values_posted['enquiries_details']['product_id']:''*/,  "id='product_id_0' class='form-control select2' required='required'");?></td>
				<td><?php echo form_dropdown();?></td>
				<td>
					<input type="text" name="enquiries_details[0][quantity]" class="form-control" id="quantity_0" >
				</td>
				
				<td>
					<input type="text" name="enquiries_details[0][unit]" class="form-control" id="unit_0" >
				</td>
				<td><input type="text" name="enquiries_details[0][price]" class="form-control" id="price_0"></td>
				<td></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
		   		<td colspan="9"><button type="button" id="AddMoreProductEnquiries" class="btn btn-info pull-right AddMoreRow">Add More</button>
		   		</td>
		   	</tr>
		</tfoot>
	</table>
</div>