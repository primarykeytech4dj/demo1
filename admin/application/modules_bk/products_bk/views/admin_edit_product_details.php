<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$input['discount_type'] = array(
						"name" => "data[product_details][discount_type]",
						"placeholder" => "discount_type",
						"required" => "required",
						"class"=> "form-control",
						"id" => "discount_type",
					);

$input['discount'] = array(
						"name" => "data[product_details][discount]",
						"placeholder" => "Discount",
						"class"=> "form-control",
						"id" => "discount",
					);
$input['in_stock_qty'] = array(
						"name" => "data[product_details][in_stock_qty]",
						"placeholder" => "In stock Qty",
						"class"=> "form-control",
						"id" => "in_stock_qty",
					);
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted['product_details']) && !empty($values_posted['product_details']))
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
<div class="box box-info">
<?php echo form_open_multipart('products/admin_edit_product_details/'.$id, ['class'=>'form-horizontal', 'id'=>'edit_product_details']);?>
		<!-- <input type="hidden" name="product_id" value="<?php echo $id; ?>"> -->

		<?=form_hidden(['data[product_details][id]'=>$values_posted['product_details']['id']])?>
		<?=form_hidden(['data[product_details][product_id]'=>$id])?>
		<div class="box-header with-border">
			<h3 class="box-title">Other Product Details</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body">
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="inputInStockQty" class="col-sm-2 control-label">In Stock Qty</label>
							<div class="col-sm-10">
								<?php echo form_input($input['in_stock_qty']);?>
								<?php echo form_error('data[product_details][in_stock_qty]');?>
							</div>
						</div>
					</div>
					<!--<div class="col-md-6">
						<div class="form-group">
							<label for="inputArea" class="col-sm-2 control-label">Discount</label>
							<div class="col-sm-10">
								<?php echo form_input($input['discount']);?>
								<?php echo form_error('data[product_details][discount]');?>
							</div>
						</div>
					</div>-->
				</div>

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