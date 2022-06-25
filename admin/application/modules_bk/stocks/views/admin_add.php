<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$input['inward_date'] =  array(		
							"name" => "data[stocks][inward_date]",
							"placeholder" => "Inward Date *",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "inward_date",
							"value" => date('d/m/Y')
							 );
//echo "<div class = "for-group" >";
$input['stock_code'] = array(
						"name" => "data[stocks][stock_code]",
						"placeholder" => "Stock Code",
						"class"=> "form-control",
						"id" => "stock_code",
					);

$input['po_no'] = array(
						"name" => "data[stocks][po_no]",
						"placeholder" => "PO No *",
						"required" => "required",
						"class" => "form-control checkPo",
						'id' => "po_no"
					);

$input['vendor_id'] = array(
							"required" =>"required",
							"class" =>"form-control required",
							"id" => "vendor_id",
							'data-link' => 'vendors/get_vendorwise_site',
							'data-target' =>'grade',
						);

$input['company_warehouse_id'] = array(
						"required" =>"required",
						"class" => "form-control",
						'id' => "company_warehouse_id"
					);

$input['amount_before_tax'] =  array(
							"name" => "data[stocks][amount_before_tax]",
							"placeholder" => "Amount Before Tax",
							//"required" => "required",
							"class" => "form-control",
							"id" => "amount_before_tax",
							"readonly"=>"readonly"
							 );

$input['amount_after_tax'] =  array(
							"name" => "data[stocks][amount_after_tax]",
							"placeholder" => "Amount After Tax",
							//"required" => "required",
							"class" => "form-control",
							"id" => "amount_after_tax",
							"readonly"=>"readonly"
							 );

$input['other_charges'] =  array(
							"name" => "data[stocks][other_charges]",
							"placeholder" => "Other Charges",
							"class" => "form-control calculate",
							"id" => "other_charges",
							"value"=>'0.00'
							 );
$input['discount'] =  array(
							"name" => "data[stocks][discount]",
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
$input['invoice'] = array(
							'name' => "invoice",
							'placeholder'=> "Invoice",
							"class" =>"form-control",
							"id" => "invoice",
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
		<li><?php echo anchor(custom_constants::admin_stock_listing_url, '<i class="fa fa-shopping-bag"></i> View Inward'); ?></li>
		<li><a href="#"><i class="fa fa-plus-square"></i>New Inward</a></li>
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
						<?php echo form_open_multipart(custom_constants::new_stock_url, ['class'=>'form-horizontal', 'id'=>'register_order']); 
							
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
												<label for="inputPo_no" class="col-sm-2 control-label">Po No</label>
												<div class="col-sm-10">
													<?php echo form_input($input['po_no']); ?>
													<?php echo form_error('po_no'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputInward_date"  class="col-sm-2 control-label">Inward Date:</label>
												<div class="input-group date">
													<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
													</div>
													<?php echo form_input($input['inward_date']);?>
													<?php echo form_error('data[stocks][inward_date]'); ?>
												</div>
												<!-- /.input group -->
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputStock_code" class="col-sm-2 control-label">Stock Code</label>
												<div class="col-sm-10">
													<?php echo form_input($input['stock_code']); ?>
													<?php echo form_error('data[stocks][stock_code]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputVendor_id" class="col-sm-2 control-label">Vendor</label>
												<div class="col-sm-10">
													<?php //echo form_input($input['vendor_id']);
													echo form_dropdown('data[stocks][vendor_id]', $option['vendor'], set_value('data[stocks][vendor_id]'), $input['vendor_id']);
													 ?>
													<?php echo form_error('data[stocks][vendor_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">                          
												<label for="inputWarehouse" class="col-sm-2 control-label">Godown</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[stocks][company_warehouse_id]', $option['companyWarehouse'], set_value('data[stocks][company_warehouse_id]'), $input['company_warehouse_id']); ?>
													<?php echo form_error('data[stocks][company_warehouse_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
								                <label for="inputInvoice" class="col-sm-2 control-label">Upload Invoice Copy</label>
								                <div class="col-sm-10">
								                  	<?php echo form_upload($input['invoice']);?>
													<?php echo form_error('data[stocks][invoice]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div>
									
									
									
									
									
						<!-- /box-body -->
							 		<div class="box-header with-border">
										<h2 class="box-title">Inward Details</h2>
									</div>
									<div class="box-body" style="overflow-x:scroll">
										<table class="table details">
											<thead>
												<tr>
													<th>Product</th>
													<th>Variants</th>
													<th>Batch No</th>
													<th>HSN/SAC</th>
													<th>MFG Date</th>
													<th>Expiry Date</th>
													<th>Rate</th>
													<th>Qty</th>
													<th>Tax(%)</th>
													<th>Total</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr id="0">
													<?php //print_r($option['product']);?>
													<td ><?php echo form_dropdown("stock_details[0][product_id]",$option['product'],'',["id" =>'product_id_0', 'required'=>'required', 'class'=>'form-control select2 filter required', 'data-link' => 'products/productwise_attributes', "data-target" => 'productattributeid_0', "style" => 'width:100% !important']);?></td>
													<td ><?php echo form_dropdown("stock_details[0][product_attribute_id]", ['select Attributes'],'',["id" =>'productattributeid_0', 'required'=>'required', 'class'=>'form-control select2', "style" => 'width:100% !important']);?></td>
													<td>
														<input type="text" name="stock_details[0][lot_no]" class="form-control calculate" id="lot_no_0">
													</td>
													<td>
														<input type="text" name="stock_details[0][hsn_sac_code]" class="form-control calculate" id="hsn_sac_code_0">
													</td>
													<td>
														<input type="text" name="stock_details[0][mfg_date]" class="form-control datepicker datemask" id="mfg_date_0" value="<?=date('d/m/Y')?>">
													</td>
													<td>
														<input type="text" name="stock_details[0][exp_date]" class="form-control datepicker datemask" id="exp_date_0" value="<?=date('d/m/Y', strtotime('+1 year'))?>">
													</td>
													
													<td>
														<input type="text" name="stock_details[0][unit_price]" class="form-control calculate" id="unit_price_0" value="0.00">
													</td>
													<td>
														<input type="text" name="stock_details[0][qty]" class="form-control required calculate" id="qty_0" value="" required="required"></td>
													<td>
														<input type="text" name="stock_details[0][tax]" class="form-control calculate" id="tax_0" value="0.00">
														
													</td>
													
													<td><input type="text" disabled="disabled" id="total_0" value="0.00" name="stock_details[0][total_0]" class="producttotal" style="text-align: right"></td>
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
										<div class="col-md-6 pull-right">
											<div class="form-group">
												<label for="inputAmount_before_tax" class="col-sm-6 control-label">Amount Before Tax</label>
												<div class="col-sm-6">
													<?php echo form_input($input['amount_before_tax']); ?>
													<?php echo form_error('data[stocks][amount_before_tax]'); ?>
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
													<?php echo form_error('data[stocks][amount_after_tax]'); ?>
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
										<div class="col-md-4 hideIt">
											<div class="form-group">
								                <label for="inputField2" class="col-sm-2 control-label">Field 2</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['field2']);?>
													<?php echo form_error('data[stocks][field2]'); ?>
								                </div>
								                <!-- /.input group -->
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
	$(".checkPo").on('blur', function(){
		var pono = $(this).val();
		var id = this.id;
		//alert(id);
    	$.ajax({
          type: 'POST',
          dataType: 'json',
          url : base_url+'stocks/checkPo/',
          data: {'po_no':pono},
          success: function(response) {
            console.log(response);
            //alert(response.status);
            if(response.status!=true){
            	alert("PO number already exists");
            	$("#"+id).val('');
            }
            
          }
        
        });
	});
</script>
