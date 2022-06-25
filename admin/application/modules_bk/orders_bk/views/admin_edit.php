
<?php
//echo "<pre>";print_r($orderDetails);//exit;
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$input['order_date'] =  array(		
							"name" => "data[orders][date]",
							"placeholder" => "Order Date *",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "order_date",
							"value" => date('d/m/Y')
							 );
//echo "<div class = "for-group" >";
/*$input['order_code'] = array(
						"name" => "data[orders][order_code]",
						"placeholder" => "Order Code",
						"class"=> "form-control",
						"id" => "order_code",
					);*/

/*$input['po_no'] = array(
						"name" => "data[stocks][po_no]",
						"placeholder" => "PO No *",
						"required" => "required",
						"class" => "form-control checkPo",
						'id' => "po_no"
					);*/
$input['sale_by'] = array(
							"required" =>"required",
							"class" =>"form-control select2",
							"id" => "sale_by",
							"style"=>"width:80%"
						);
$input['customer_id'] = array(
							"required" =>"required",
							"class" =>"form-control select2 required",
							"id" => "customer_id",
							
						);
$input['order_status_id'] = array(
							"required" =>"required",
							"class" =>"form-control select2 required",
							"id" => "order_status_id",
							
						);
/*$input['invoice_address_id'] = array(
						"name" => "data[orders][invoice_address_id]",
						"required" => "required",
						"class" => "form-control select2 addqc",
						'id' => "invoice_address_id"
					);*/
$input['delivery_address_id'] = array(
						"name" => "data[orders][shipping_address_id]",
						"required" => "required",
						"class" => "form-control select2 addqc",
						'id' => "delivery_address_id"
					);

$input['amount_before_tax'] =  array(
							"name" => "data[orders][amount_before_tax]",
							"placeholder" => "Amount Before Tax",
							//"required" => "required",
							"class" => "form-control",
							"id" => "amount_before_tax",
							"readonly"=>"readonly"
							 );

$input['amount_after_tax'] =  array(
							"name" => "data[orders][amount_after_tax]",
							"placeholder" => "Amount After Tax",
							//"required" => "required",
							"class" => "form-control",
							"id" => "amount_after_tax",
							"readonly"=>"readonly"
							 );

$input['other_charges'] =  array(
							"name" => "data[orders][shipping_charge]",
							"placeholder" => "Other Charges",
							"class" => "form-control calculate",
							"id" => "other_charges",
							"value"=>'0.00'
							 );
$input['discount'] =  array(
							"name" => "data[orders][discount]",
							"placeholder" => "discount",
							"class" => "form-control calculate",
							"id" => "discount",
							"value"=>"0.00",
							"readonly"=>'readonly'
							 );
$input['discountamt'] =  array(
							"name" => "discountamt",
							"class" => "form-control calculate",
							"id" => "discountamt",
							"value"=>"0.00"
							 );
$input['field2'] =  array(
							"name" => "data[stocks][field2]",
							"placeholder" => "Field 2",
							"class" => "form-control",
							"id" => "field2",
							 );
$input['message'] =  array(
							"name" => "data[orders][message]",
							"placeholder" => "Message",
							"class" => "form-control",
							"id" => "message",
							"rows"=>5
							 );
$product =  array(
				'id' =>	'product_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter',
				/*'data-link' => 'states/getCountrywiseStates',
				'data-target' =>'state_id',*/
				'data-link' => 'products/getProductwisePackProduct',
				'data-target' =>'pack_product_id_0',
				'style' => 'width:100%',
			 );

// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ //print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{
		foreach ($post_value as $field_key => $field_value) {
			# code...
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
	<h1><?=$title?></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><?php echo anchor(custom_constants::admin_order_listing_url, '<i class="fa fa-shopping-bag"></i> View Orders'); ?></li>
		<li><a href="#"><i class="fa fa-plus-square"></i>Edit Order</a></li>
	</ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<div class="tab-content">
					<div class="tab-pane active" id="basic_detail"> 
						<?php 
						/*echo '<pre>';
						print_r($order);
						echo '</pre>';*/
						echo form_open_multipart(custom_constants::edit_order_url.'/'.$order[0]['id'], ['class'=>'form-horizontal', 'id'=>'register_order']); 
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><?=$heading?></h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputVendor_id" class="col-sm-2 control-label">Customer</label>
												<div class="col-sm-10">
													<?php //echo form_input($input['vendor_id']);
													echo form_dropdown('data[orders][customer_id]', $option['customer'], $order[0]['customer_id'], $input['customer_id']);
													 ?>
													<?php echo form_error('data[orders][customer_id]'); ?>
												</div>
											</div>
										</div>
										<!--<div class="col-md-4">
											<div class="form-group">
												<label for="inputInvoice_address_id" class="col-sm-2 control-label">Invoice Address</label>
												<div class="col-sm-10">
													<?php 
													echo form_dropdown('data[orders][invoice_address_id]', '', set_value('data[orders][invoice_address_id]'), $input['invoice_address_id']);
													 ?>
													<button class="btn dynamic-modal load-ajax" type="button" data-path="address/admin_add_form" data-refill-target="invoice_address_id" data-modal-title="New Customer Address" data-model-size="modal-lg"><i class="fa fa-plus"></i></button>
													<?php echo form_error('data[orders][invoice_address_id]'); ?>
												</div>
											</div>
										</div>-->
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputInvoice_address_id" class="col-sm-2 control-label">Delivery Address</label>
												<div class="col-sm-10">
													<?php 
													echo form_dropdown('data[orders][shipping_address_id]', $option['address'],$values_posted['order']['shipping_address_id'], $input['delivery_address_id']);
													 ?>
												
													<?php echo form_error('data[orders][shipping_address_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputSale_by" class="col-sm-2 control-label">Sales Person</label>
												<div class="col-sm-10">
													<?php 
													echo form_dropdown('data[orders][sale_by]', $option['sold_by'], $values_posted['order']['sale_by'], $input['sale_by']);
													 ?>
													
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputVendor_id" class="col-sm-2 control-label">Order Status</label>
												<div class="col-sm-10">
													<?php //echo form_input($input['vendor_id']);
													echo form_dropdown('data[orders][order_status_id]', $option['status'], $order[0]['order_status_id'], $input['order_status_id']);
													 ?>
													<?php echo form_error('data[orders][order_status_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputMessage" class="col-sm-2 control-label">Message</label>
												<div class="col-sm-10">
													<?php echo form_textarea($input['message']); ?>
													<?php echo form_error('data[orders][message]'); ?>
												</div>
											</div>
										</div>
									</div>
									
						<!-- /box-body -->
							 		<div class="box-header with-border">
										<h2 class="box-title">Order Details</h2>
									</div>
									<div class="box-body" style="overflow-x:scroll">
										<table class="table details">
											<thead>
												<tr>
													<th>Product</th>
													<th>Variants</th>
													<!--<th>Batch No</th>
													<th>HSN/SAC</th>
													<th>MFG Date</th>
													<th>Expiry Date</th>-->
													<th>Rate</th>
													<th>Qty</th>
													<th>Tax(%)</th>
													<th>Total</th>
													<th>Active</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php $i=0; 
												if(count($orderDetails)>0){
												foreach($orderDetails as $oKey=>$oDetail): 
												    //echo '<pre>';print_r($oDetail);echo '</pre>';
												    $variant = json_decode($oDetail['variation'], true);
												//print_r($oDetail);?>
												<tr id="<?= $i?>">
													
													<td ><?php 
													echo form_input(["name"=>'order_details['.$oKey.'][id]', 'value'=>$oDetail['id'], 'id'=>'id_'.$oKey, 'type'=>'hidden']);
													echo form_dropdown("order_details[$oKey][product_id]",$option['product'],$oDetail['product_id'],["id" =>'product_id_'.$oKey, 'required'=>'required', 'class'=>'form-control select2 filter required product_id', 'data-link' => 'products/productwise_attributes', "data-target" => 'productattributeid_'.$oKey, "style" => 'width:100% !important']);?></td>
													<td ><?php echo form_dropdown("order_details[$oKey][product_attribute_id]", $oDetail['attributeList'], $variant['attribute']['product_attribute_id'],["id" =>'productattributeid_'.$oKey, 'required'=>'required', 'class'=>'form-control select2 attribute', "style" => 'width:100% !important']);?></td>
													
													<td>
														<input type="text" name="order_details[<?=$oKey?>][unit_price]" class="form-control calculate2" id="unit_price_<?=$oKey?>" value="<?=$oDetail['unit_price']?>" readonly>
													</td>
													<td>
														<input type="text" name="order_details[<?=$oKey?>][qty]" class="form-control required calculate2" id="qty_<?=$oKey?>" value="<?=$oDetail['qty']?>" required="required"></td>
													<td>
														<input type="text" name="order_details[<?=$oKey?>][tax]" class="form-control calculate2 nochange" id="tax_<?=$oKey?>" value="<?=$oDetail['tax']?>">
														
													</td>
													
													<td><input type="text" disabled="disabled" id="total_<?=$oKey?>" value="<?=$oDetail['unit_price']*$oDetail['qty']?>" name="order_detail[<?=$oKey?>][total]" class="producttotal" style="text-align: right"></td>
													<td><input type="checkbox" name="order_details[<?=$oKey?>][is_active]" id="is_active_<?=$oKey?>" class="calculate2" <?=($oDetail['is_active'])?'checked="checked"':''?>></td>
													<td></td>
												</tr>
												<?php $i++; endforeach;
												}else{
												    ?>
												<tr id="<?= $i?>">
													
													<td ><?php 
													echo form_dropdown("order_details[0][product_id]",$option['product'], '',["id" =>'product_id_0', 'required'=>'required', 'class'=>'form-control select2 filter required product_id', 'data-link' => 'products/productwise_attributes', "data-target" => 'productattributeid_0', "style" => 'width:100% !important']);?></td>
													<td ><?php echo form_dropdown("order_details[0][product_attribute_id]", [''=>'Select Attribute'], '',["id" =>'productattributeid_0', 'required'=>'required', 'class'=>'form-control select2 attribute', "style" => 'width:100% !important']);?></td>
													
													<td>
														<input type="text" name="order_details[0][unit_price]" class="form-control calculate2" id="unit_price_0" value="0.00" readonly>
													</td>
													<td>
														<input type="text" name="order_details[0][qty]" class="form-control required calculate2" id="qty_0" value="0.00" required="required"></td>
													<td>
														<input type="text" name="order_details[0][tax]" class="form-control calculate2 nochange" id="tax_0" value="0.00">
														
													</td>
													
													<td><input type="text" disabled="disabled" id="total_0" value="0.00" name="order_detail[0][total]" class="producttotal" style="text-align: right"></td>
													<td><input type="checkbox" name="order_details[0][is_active]" id="is_active_0" class="calculate2" checked="checked"></td>
													<td></td>
												</tr>
												    <?php
												}
												?>
											</tbody>
											<tfoot>
												
												<tr>
											   		<td colspan="11"><button type="button" id="AddMorestocks" class="btn btn-info pull-right AddMoreRow">Add More</button>
											   		</td>
											   	</tr>
											</tfoot>
										</table>
									</div>
									<div class="row">
										<div class="col-md-6 pull-right">
											<div class="form-group">
												<label for="inputAmount_before_tax" class="col-sm-6 control-label">Amount Before Tax</label>
												<div class="col-sm-6">
													<?php echo form_input($input['amount_before_tax']); ?>
													<?php echo form_error('data[orders][amount_before_tax]'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 pull-right">
											<div class="form-group">
												<label for="inputAmount_after_tax" class="col-sm-6 control-label">Amount After Tax</label>
												<div class="col-sm-6">
													<?php echo form_input($input['amount_after_tax']); ?>
													<?php echo form_error('data[orders][amount_after_tax]'); ?>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row">
									    <div class="col-md-4">
											<div class="form-group">                          
											<label for="inputDiscountType" class="col-sm-6 control-label">Discount Type</label>
												<div class="col-sm-6">
													<input type="radio" id="dicounttype" value="percentage" name="discountType" class="calculate"> in Percent<br>
													<input type="radio" id="dicounttype" value="value" name="discountType" checked  class="calculate"> in Amount
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">                          
											<label for="inputDiscount" class="col-sm-6 control-label">Discount(%)</label>
												<div class="col-sm-6">
													<?php echo form_input($input['discount']); ?>
													<?php echo form_error('discount'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">                          
											<label for="inputDiscountAmount" class="col-sm-6 control-label">Discount(Amt)</label>
												<div class="col-sm-6">
													<?php echo form_input($input['discountamt']); ?>
													<?php echo form_error('discountamt'); ?>
												</div>
											</div>
										</div>
										
										
									</div><!-- /row -->	
									<div class="row">
										<div class="col-md-12 pull-right">
											<div class="form-group">
								                <label for="inputOther_charges" class="col-sm-9 control-label">Other Charges (i.e. loading, shipping, brokerage etc)</label>
								                <div class="col-sm-3">
								                  	<?php echo form_input($input['other_charges']);?>
													<?php echo form_error('data[stocks][other_charges]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6 pull-right">
											<div class="form-group">                          
											<label for="inputGrandTotal" class="col-sm-6 control-label">Final Purchase Price</label>
												<div class="col-sm-6">
													<label id="grand_total" class="pull-right">0.00</label>
												</div>
											</div>
										</div>
									</div>
									<button class="btn btn-info pull-left">Submit</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
						</div>
						<?php echo form_close(); ?> 
					</div><!-- /tab-pane -->
					
					
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->
<script type="text/javascript">
    $(document).on('change', '.product_id', function(){
        //alert("hii");
        trId = $(this).closest('tr').attr('id');
        /*$('#unit_price_'+trId).val('');
        $('#qty_'+trId).val('');*/
        var flag = true;
        $(document).ajaxComplete(function() {
            if(!flag){
                return;
            }
            $('#productattributeid_'+trId).trigger('change');
            flag = false;
            //alert($('#productattributeid_'+trId+' option:selected').val());
        })
        
        
    })
    
    
</script>
