<?php
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
							"style"=>"width:100%"
						);
$input['customer_id'] = array(
							"required" =>"required",
							"class" =>"form-control select2 required addqc",
							"id" => "customer_id",
							
						);
$input['invoice_address_id'] = array(
						"name" => "data[orders][invoice_address_id]",
						"required" => "required",
						"class" => "form-control select2 addqc",
						'id' => "invoice_address_id"
					);
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
							"style"=>"text-align:right",
							"readonly"=>"readonly",
							"value"=>"0.00"
							 );

$input['amount_after_tax'] =  array(
							"name" => "data[orders][amount_after_tax]",
							"placeholder" => "Amount After Tax",
							//"required" => "required",
							"class" => "form-control",
							"id" => "amount_after_tax",
							"readonly"=>"readonly",
							"style"=>"text-align:right",
							"value"=>"0.00"
							 );

$input['other_charges'] =  array(
							"name" => "data[orders][shipping_charge]",
							"placeholder" => "Other Charges",
							"class" => "form-control calculate",
							"id" => "other_charges",
							"value"=>'0.00',
							"readonly"=>'readonly',
							"style"=>"text-align:right"
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
		<?php if(in_array(1, $this->session->userdata('roles')) ||  in_array(2, $this->session->userdata('roles')) ||  in_array(7, $this->session->userdata('roles'))){ ?>
		<li><?php echo anchor(custom_constants::admin_order_listing_url, '<i class="fa fa-shopping-bag"></i> View Orders'); ?></li>
		<?php }elseif(in_array(6, $this->session->userdata('roles'))){ ?>
		<li><?php echo anchor(custom_constants::fieldmember_url, '<i class="fa fa-shopping-bag"></i> View Assigned Orders'); ?></li>
		<?php } ?>
	</ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12" style="padding-right: 5px;padding-left: 5px;">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<div class="tab-content" style="padding: 0">
					<div class="tab-pane active" id="basic_detail"> 
						<?php echo form_open_multipart(custom_constants::new_order_url2, ['class'=>'form-horizontal submit-ajax', 'id'=>'register_order', 'onsubmit'=>"myButtonValue.disabled = true; return true;"]); 
							echo form_hidden(['data[orders][sale_by]'=>$this->session->userdata('user_id')]);
							if(NULL!==$this->session->flashdata('message')) {
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
								<div class="box-body" style="padding: 5px;">
									
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputVendor_id" class="col-sm-2 control-label">Customer</label>
												<div class="col-sm-10">
													<?php //echo form_input($input['vendor_id']);
													echo form_dropdown('data[orders][customer_id]', $option['customer'], set_value('data[orders][customer_id]'), $input['customer_id']);
													 ?>
													<?php echo form_error('data[orders][customer_id]'); ?>
													<button class="btn dynamic-modal load-ajax" type="button" data-path="customers/admin_add_customer_new" data-refill-target="customer_id" data-modal-title="New Customer" data-model-size="modal-lg"><i class="fa fa-plus"></i></button>
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
													echo form_dropdown('data[orders][shipping_address_id]', '', set_value('data[orders][shipping_address_id]'), $input['delivery_address_id']);
													 ?>
													<button class="btn dynamic-modal load-ajax" type="button" data-path="address/admin_add_form" data-refill-target="delivery_address_id" data-modal-title="New Customer Address" data-model-size="modal-lg"><i class="fa fa-plus"></i></button>
													<?php echo form_error('data[orders][shipping_address_id]'); ?>
												</div>
											</div>
										</div>
										<!-- <div class="col-md-4">
											<div class="form-group">
												<label for="inputSale_by" class="col-sm-2 control-label">Sales Person</label>
												<div class="col-sm-10">
													<?php 
													echo form_dropdown('data[orders][sale_by]', $option['sold_by'], (!set_value('data[orders][sale_by]'))?$this->session->userdata('user_id'):set_value('data[orders][sale_by]'), $input['sale_by']);
													 ?>
													
												</div>
											</div>
										</div> -->
										
									</div>
									
						<!-- /box-body -->
							 		<div class="box-header with-border">
										<h2 class="box-title text-center">Item Details</h2>
									</div>
									<div class="box-body" style="overflow-x:scroll">
										<table class="table details">
											<!--<thead>
												<tr>
													<th style="width: 40%">Product</th>
													<th style="width: 30%">Variants</th>
													<th style="width: 30%">Qty</th>
													<th></th>
												</tr>
											</thead>-->
											<tbody>
												<tr id="0">
													<?php //print_r($option['product']);?>
													<td >
													<div class="row">
													    <div class="col-md-12">
											                <div class="form-group">
        													    <div class="col-xs-2">
        													        Product
        													    </div>
        													    <div class="col-xs-10">
        													        <?php echo form_dropdown("order_details[0][product_id]",$option['product'],'',["id" =>'product_id_0', 'required'=>'required', 'class'=>'form-control select2 filter required product_id', 'data-link' => 'products/productwise_attributes', "data-target" => 'productattributeid_0', "style" => 'width:100% !important']);?>
        													    </div>
        													</div>
        												</div>
													</div>
													<div class="row">
													    <div class="col-md-12">
											                <div class="form-group">
        													    <div class="col-xs-2">
        													        Variants
        													    </div>
        													    <div class="col-xs-10">
        													        <?php echo form_dropdown("order_details[0][product_attribute_id]", ['select Attributes'],'',["id" =>'productattributeid_0', 'required'=>'required', 'class'=>'form-control select2 attribute', "style" => 'width:100% !important']); ?>
        													    </div>
        													</div>
        												</div>
													</div>
													<div class="row">
													    <div class="col-md-12">
											                <div class="form-group">
        													    <div class="col-xs-2">
        													        Rate
        													    </div>
        													    <div class="col-xs-10">
        													        <input type="text" name="order_details[0][unit_price]" class="form-control calculate" id="unit_price_0" value="0.00" readonly>
        													    </div>
        													</div>
        												</div>
													</div>
													<div class="row">
													    <div class="col-md-12">
											                <div class="form-group">
        													    <div class="col-xs-2">
        													        GST
        													    </div>
        													    <div class="col-xs-10">
        													        <input type="text" name="order_details[0][tax]" class="form-control calculate nochange" id="tax_0" value="0.00" readonly>
        													    </div>
        													</div>
        												</div>
													</div>
													<div class="row">
													    <div class="col-md-12">
											                <div class="form-group">
        													    <div class="col-xs-2">
        													        Qty
        													    </div>
        													    <div class="col-xs-10">
        													        <input type="number" name="order_details[0][qty]" class="form-control required calculate" id="qty_0" value="1" min="1" required="required" style="min-width: 60px">
        													        <input type="hidden" name="order_details[0][is_active]" value="true" id="is_active_0">
                													
                													<input type="hidden" disabled="disabled" id="total_0" value="0.00" name="order_details[0][total_0]" class="producttotal" style="text-align: right">
        													    </div>
        													    
        													</div>
        												</div>
													</div>
													<div class="row">
													    <div class="col-md-12">
													        <hr>
													    </div>
												    </div>
													
													
													
													
													</td>
													
													<td></td>
												</tr>
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
										<div class="col-md-12 col-xs-12 pull-right">
											<div class="form-group">
												<label for="inputAmount_before_tax" class="col-sm-4 control-label">Amount Before Tax</label>
												<div class="col-sm-8">
													<?php echo form_input($input['amount_before_tax']); ?>
													<?php echo form_error('data[orders][amount_before_tax]'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 col-xs-12 pull-right">
											<div class="form-group">
												<label for="inputAmount_after_tax" class="col-sm-4 control-label">Order Amount</label>
												<div class="col-sm-8">
													<?php echo form_input($input['amount_after_tax']); ?>
													<?php echo form_error('data[orders][amount_after_tax]'); ?>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row hideIt">
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
										<div class="col-md-12  col-xs-12 pull-right">
											<div class="form-group">
								                <label for="inputOther_charges" class="col-sm-6 control-label">Other Charges (i.e. loading, shipping, brokerage etc)</label>
								                <div class="col-sm-6">
								                  	<?php echo form_input($input['other_charges']);?>
													<?php echo form_error('data[stocks][other_charges]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6  col-xs-12">
											<div class="form-group">                          
											<label for="inputGrandTotal" class="col-sm-4 control-label text-center">Final Amount</label>
												<div class="col-sm-8">
													<label id="grand_total" class="pull-right" style="padding-right: 10px">0.00</label>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputMessage" class="col-sm-2 control-label">Message</label>
												<div class="col-sm-10">
													<?php echo form_textarea($input['message']); ?>
													<?php echo form_error('data[orders][message]'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
									    <div class="col-md-12 col-xs-12 response"></div>
									</div>
									<div class="row">
										<div class="col-md-6  col-xs-6 text-center">
											<button class="btn btn-info" id="myButtonValue">Submit</button>
										</div>
										<div class="col-md-6  col-xs-6 text-center">
											<button type="reset" class="btn btn-info">Reset</button>
										</div>
									</div>
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
    
    
    $(document).ajaxComplete(function() {
        //alert("hii");
            $('#myButtonValue').removeAttr('disabled');
            //alert($('#productattributeid_'+trId+' option:selected').val());
        });
    
    
    
</script>
