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

$input['lorry_no'] = array(
						"name" => "data[stocks][lorry_no]",
						"placeholder" => "Lorry no",
						"class"=> "form-control",
						"id" => "lorry_no",
					);

$input['invoice_no'] = array(
						"name" => "data[stocks][invoice_no]",
						"placeholder" => "Invoice no",
						"class"=> "form-control",
						"id" => "invoice_no",
					);


$input['lot_no'] = array(
						"name" => "data[stocks][lot_no]",
						"placeholder" => "Lot No *",
						"required" => "required",
						"class" => "form-control",
						'id' => "lot_no"
					);

$input['vendor_id'] = array(
							"required" =>"required",
							"class" =>"form-control required",
							"id" => "vendor_id",
							'data-link' => 'vendors/get_vendorwise_site',
							'data-target' =>'grade',
						);

$input['grade'] = array(
							'name' => "data[stocks][grade]",
							'placeholder'=> "Grade *",
							"class" =>"form-control",
							"id" => "grade",
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
							 );

$input['amount_after_tax'] =  array(
							"name" => "data[stocks][amount_after_tax]",
							"placeholder" => "Amount After Tax",
							//"required" => "required",
							"class" => "form-control",
							"id" => "amount_after_tax",
							 );

$input['other_charges'] =  array(
							"name" => "data[stocks][other_charges]",
							"placeholder" => "Other Charges",
							"class" => "form-control",
							"id" => "other_charges",
							 );
$input['field1'] =  array(
							"name" => "data[stocks][field1]",
							"placeholder" => "Field 1",
							"class" => "form-control",
							"id" => "field1",
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
										<div class="col-md-3">
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
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputStock_code" class="col-sm-2 control-label">Stock Code</label>
												<div class="col-sm-10">
													<?php echo form_input($input['stock_code']); ?>
													<?php echo form_error('data[stocks][stock_code]'); ?>
												</div>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputLorry_no" class="col-sm-2 control-label">Lorry No</label>
												<div class="col-sm-10">
													<?php echo form_input($input['lorry_no']); ?>
													<?php echo form_error('data[stocks][lorry_no]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputInvoice_no" class="col-sm-2 control-label">Invoice No</label>
												<div class="col-sm-10">
													<?php echo form_input($input['invoice_no']); ?>
													<?php echo form_error('data[stocks][invoice_no]'); ?>
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
											<label for="inputGrade" class="col-sm-2 control-label">Product</label>
											<div class="col-sm-10">
												<?php echo form_dropdown("product_id",$option['product'],'',["id" =>'product_id', 'required'=>'required', 'class'=>'form-control select2 required', "style" => 'width:100% !important']);?>
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
									</div>
									
									<div class="row hideIt">
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputAmount_before_tax" class="col-sm-2 control-label">Amount Before Tax</label>
												<div class="col-sm-10">
													<?php echo form_input($input['amount_before_tax']); ?>
													<?php echo form_error('data[stocks][amount_before_tax]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputAmount_after_tax" class="col-sm-2 control-label">Amount After Tax</label>
												<div class="col-sm-10">
													<?php echo form_input($input['amount_after_tax']); ?>
													<?php echo form_error('data[stocks][amount_after_tax]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
								                <label for="inputOther_charges" class="col-sm-2 control-label">Other Charges</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['other_charges']);?>
													<?php echo form_error('data[stocks][other_charges]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									
									<div class="row hideIt">
										<div class="col-md-4">
											<div class="form-group">                          
											<label for="inputField1" class="col-sm-2 control-label">Field 1</label>
												<div class="col-sm-10">
													<?php echo form_input($input['field1']); ?>
													<?php echo form_error('field1'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
								                <label for="inputField2" class="col-sm-2 control-label">Field 2</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['field2']);?>
													<?php echo form_error('data[stocks][field2]'); ?>
								                </div>
								                <!-- /.input group -->
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
									</div><!-- /row -->
									
						<!-- /box-body -->
							 		<div class="box-header with-border">
											<h2 class="box-title">Inward Details</h2>
										</div>
										<div class="box-body" style="overflow-x:scroll">
											<table class="table">
												<thead>
													<tr>
														<!-- <th>Product</th> -->
														<th>Lot No</th>
														<th>Coil No</th>
														<th>Grade</th>
														<!-- <th>Tax (GST %)</th> -->
														<th>thickness</th>
														<th>width</th>
														<th>length</th>
														<th>Piece</th>
														<th>Net Wt</th>
														<th>Kaata Wt</th>
														<th>UOM</th>
														<th class="hideIt">Price</th>
														<th class="hideIt">Total</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<tr id="0">
														<?php //print_r($option['product']);?>
														<!-- <td ><?php echo form_dropdown("stock_details[0][product_id]",$option['product'],'',["id" =>'product_id_0', 'required'=>'required', 'class'=>'form-control select2 filter prodDetail required', 'data-link' => 'products/getProductwisePackProduct', "data-target" => 'pack_product_id_0', "style" => 'width:100% !important']);?></td> -->
														<td>
															<input type="text" name="stock_details[0][lot_no]" class="form-control required" id="lot_no_0" required="required">
														</td>
														<td>
															<input type="text" name="stock_details[0][coil_no]" class="form-control required" id="coil_no_0" required="required">
														</td>
														<td><input type="text" name="stock_details[0][grade]" class="form-control required" id="grade_0" required="required"></td>
														<!-- <td>
															
														</td> -->
														<!-- <td><?php echo form_dropdown("stock_details[0][pack_product_id]",'','',"id = 'pack_product_id_0' required	='required' class =	'form-control select2 prodDetail'  style => 'width:100%'"); ?>
														</td> -->
														<td>
															<input type="hidden" name="stock_details[0][tax]" class="form-control calculate" id="tax_0" value="0.00">
															<input type="text" name="stock_details[0][thickness]" class="form-control required" id="thickness_0" value="" required="">
														</td>
														<td>
															<input type="text" name="stock_details[0][width]" class="form-control required" id="width_0" value="" required="required">
														</td>
														<td>
															<input type="text" name="stock_details[0][length]" class="form-control required" id="length_0" value="" required="required">
														</td>
														<td>
															<input type="text" name="stock_details[0][piece]" class="form-control required" id="piece_0" value="" required="required">
														</td>
														<td>
															<input type="text" name="stock_details[0][order_qty]" class="form-control required" id="order_qty_0" value="" required="required">
														</td>
														
														<td>
															<input type="text" name="stock_details[0][qty]" class="form-control required calculate" id="qty_0" value="" required="required"></td>
														
														<td>
															
															<?php echo form_dropdown("stock_details[0][uom]", $option['uom'],'', ["id" =>'uom_0', "class" =>'form-control select2', "style" => 'width:100%']); ?>
														</td>
														<td class="hideIt">
															<input type="text" name="stock_details[0][unit_price]" class="form-control calculate" id="unit_price_0" readonly="readonly">
														</td>
														<td class="hideIt"><input type="text" disabled="disabled" id="total_0" value="0.00" name="stock_details[0][total_0]" class="producttotal" style="text-align: right"></td>
														<td></td>
													</tr>
												</tbody>
												<tfoot>
													<tr class="hideIt">
														<th colspan="10" align="right" style="text-align: right">Grand Total</th>
														<td colspan="1" class="grandTotal" style="text-align: right">0.00</td>
													</tr>
													<tr>
												   		<td colspan="11"><button type="button" id="AddMorestocks" class="btn btn-info pull-right AddMoreRow">Add More</button>
												   		</td>
												   	</tr>
												</tfoot>
											</table>
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

