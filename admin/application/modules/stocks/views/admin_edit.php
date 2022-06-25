<?php
//$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//echo $values_posted['stocks']['inward_date'];
$value = $this->pktlib->YmdtodmY($values_posted['stocks']['inward_date']);//DateTime::createFromFormat('Y-m-d', 
$input['inward_date'] =  array(		
							"name" => "data[stocks][inward_date]",
							"placeholder" => "Inward Date *",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "inward_date",
							"value" => (!set_value('data[stocks][inward_date]')?$value:set_value('data[stocks][inward_date]'))
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
						"class" => "form-control",
						'id' => "po_no",
						'readonly'=>'readonly'
					);
$input['is_active'] = array(
						"name" => "data[stocks][is_active]",
						"class" => "form-control flat-red",
						'id' => "is_active",
						'type'=>'checkbox',
						'value'=>true
					);

$input['vendor_id'] = array(
							"required" =>"required",
							"class" =>"form-control",
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

// If form has been submitted with errors populate fields that were already filled
unset($values_posted['stocks']['inward_date']);
//unset($values_posted['stocks']['start_date']);
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

<!--Main content -->
<section class="content">
	<h1><?=$title?></h1>
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<?php 
	           	if($this->session->flashdata('message') !== FALSE) {
		            $msg = $this->session->flashdata('message');?>
		          	<div class = "<?php echo $msg['class'];?>">
		                <?php echo $msg['message'];?>
		          	</div>
	        <?php } ?>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="<?php if($tab=="personal_info"){echo "active";} ?>"><a href="#personal_info" data-toggle="tab">Stock</a></li>
					<li class="pull-right"><?php echo anchor(custom_constants::new_stock_url, '<i class="fa fa-plus-circle" style="color:green; font-size:22px"></i>', ['class'=>'btn btn-info']); ?></li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="personal_info"){echo "active";} ?>" id="personal_info"> 
						<?php 
						echo form_open_multipart(custom_constants::edit_stock_url."/".$id, ['class'=>'form-horizontal', 'id'=>'update_stock']); 
						echo form_hidden(['data[stocks][invoice2]'=>$values_posted['stocks']['invoice']] );
						 ?>
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
													echo form_dropdown('data[stocks][vendor_id]', $option['vendor'], (set_value('data[stocks][vendor_id]'))?set_value('data[stocks][vendor_id]'):$values_posted['stocks']['vendor_id'], $input['vendor_id']);
													 ?>
													<?php echo form_error('data[stocks][vendor_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">                          
												<label for="inputWarehouse" class="col-sm-2 control-label">Godown</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[stocks][company_warehouse_id]', $option['companyWarehouse'], (set_value('data[stocks][company_warehouse_id]'))?set_value('data[stocks][company_warehouse_id]'):$values_posted['stocks']['company_warehouse_id'], $input['company_warehouse_id']); ?>
													<?php echo form_error('data[stocks][company_warehouse_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
								                <label for="inputInvoice" class="col-sm-2 control-label">Upload Invoice Copy</label>
								                <div class="col-sm-10">
								                  	<?php echo form_upload($input['invoice']);?>
								                  	<?=anchor(content_url().'uploads/stocks/'.$values_posted['stocks']['invoice'], 'View File', ['target'=>'_new'])?>
													<?php echo form_error('data[stocks][invoice]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputAmount_before_tax" class="col-sm-2 control-label">Active</label>
												<div class="col-sm-10">
													<?php echo form_input($input['is_active']); ?>
													<?php echo form_error('data[stocks][is_active]'); ?>
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
										
									</div><!-- /row -->
									
						<!-- /box-body -->
							 		<div class="box-header with-border">
										<h2 class="box-title">Inward Details</h2>
									</div>
									<div class="box-body" style="overflow-x:scroll">
											<table class="table">
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
												<?php if(count($stockDetails)>0){
													foreach ($stockDetails as $key => $detail) {
												 ?>
													<tr id="<?=$key?>">
														<?php //print_r($option['product']);?>
														<td >
															<input type="hidden" name="stock_details[<?=$key?>][id]" id="id=<?=$key?>" value="<?=$detail['id']?>">
															<?php echo form_dropdown("stock_details[".$key."][product_id]",$option['product'], !set_value("stock_details[".$key."][product_id]")?$detail['product_id']:set_value("stock_details[".$key."][product_id]"),["id" =>'product_id_'.$key, 'required'=>'required', 'class'=>'form-control select2 filter required', 'data-link' => 'products/productwise_attributes', "data-target" => 'productattributeid_'.$key, "style" => 'width:100% !important']);?></td>
														<td ><?php echo form_dropdown("stock_details[".$key."][product_attribute_id]", $option['attribute'][$detail['product_id']], !set_value("stock_details[".$key."][product_attribute_id]")?$detail['product_attribute_id']:set_value("stock_details[".$key."][product_attribute_id]"),["id" =>'productattributeid_'.$key, 'required'=>'required', 'class'=>'form-control select2', "style" => 'width:100% !important']);?></td>
														<td>
															<input type="text" name="stock_details[<?=$key?>][lot_no]" class="form-control calculate" id="lot_no_<?=$key?>" value="<?=!(set_value("stock_details[".$key."][lot_no]"))?$detail['lot_no']:set_value("stock_details[".$key."][lot_no]")?>">
														</td>
														<td>
															<input type="text" name="stock_details[<?=$key?>][hsn_sac_code]" class="form-control calculate" id="hsn_sac_code_<?=$key?>" value="<?=!(set_value("stock_details[".$key."][hsn_sac_code]"))?$detail['hsn_sac_code']:set_value("stock_details[".$key."][hsn_sac_code]")?>">
														</td>
														<td>
															<input type="text" name="stock_details[<?=$key?>][mfg_date]" class="form-control datepicker datemask" id="mfg_date_<?=$key?>" value="<?=!(set_value("stock_details[".$key."][mfg_date]"))?date('d/m/Y', strtotime($detail['mfg_date'])):set_value("stock_details[".$key."][mfg_date]")?>">
														</td>
														<td>
															<input type="text" name="stock_details[<?=$key?>][exp_date]" class="form-control datepicker datemask" id="exp_date_<?=$key?>" value="<?=!(set_value("stock_details[".$key."][exp_date]"))?date('d/m/Y', strtotime($detail['exp_date'])):set_value("stock_details[".$key."][exp_date]")?>">
														</td>
														<td>
															<input type="text" name="stock_details[<?=$key?>][unit_price]" class="form-control calculate" id="unit_price_<?=$key?>" value="<?=!(set_value("stock_details[".$key."][unit_price]"))?$detail['unit_price']:set_value("stock_details[".$key."][unit_price]")?>">
														</td>
														<td>
															<input type="text" name="stock_details[<?=$key?>][qty]" class="form-control required calculate" id="qty_<?=$key?>" required="required" value="<?=!(set_value("stock_details[".$key."][qty]"))?$detail['qty']:set_value("stock_details[".$key."][qty]")?>"></td>
														<td>
															<input type="text" name="stock_details[0][tax]" class="form-control calculate" id="tax_<?=$key?>" value="<?=!(set_value("stock_details[".$key."][tax]"))?$detail['tax']:set_value("stock_details[".$key."][tax]")?>">
															
														</td>
														
														<td><input type="text" disabled="disabled" id="total_0" value="<?=($detail['qty']*$detail['unit_price'])+(($detail['tax']/100.00)*($detail['qty']*$detail['unit_price']))?>" name="stock_details[0][total_0]" class="producttotal" style="text-align: right"></td>
														<td></td>
													</tr>
												<?php }
												}else{
													?>
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
															<input type="text" name="stock_details[0][mfg_date]" class="form-control datepicker datemask" id="mfg_date_0" value="<?=date('Y-m-d')?>">
														</td>
														<td>
															<input type="text" name="stock_details[0][exp_date]" class="form-control datepicker datemask" id="exp_date_0" value="<?=date('Y-m-d', strtotime('+1 year'))?>">
														</td>
														
														<td>
															<input type="text" name="stock_details[0][unit_price]" class="form-control calculate" id="unit_price_0">
														</td>
														<td>
															<input type="text" name="stock_details[0][qty]" class="form-control required calculate" id="qty_0" value="" required="required"></td>
														<td>
															<input type="text" name="stock_details[0][tax]" class="form-control calculate" id="tax_0" value="0.00">
															
														</td>
														
														<td><input type="text" disabled="disabled" id="total_0" value="0.00" name="stock_details[0][total_0]" class="producttotal" style="text-align: right"></td>
														<td></td>
													</tr>
												<?php
												} ?>
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
									

									<div class="box-footer">  
										<button type="submit" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
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

