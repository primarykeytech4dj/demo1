<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$outwardDate = '';
if(!empty($values_posted['stockout']['outward_date']) && $values_posted['stockout']['outward_date']!='0000-00-00'){ 
  $outwardDate = date('d-m-Y',strtotime($values_posted['stockout']['outward_date']));
  
}else{
  $outwardDate = date('d-m-Y');
}

$input['outward_date'] =  array(		
							"name" => "data[stockout][outward_date]",
							"placeholder" => "Stock Out Date *",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "outward_date",
							"value" => $outwardDate
							 );
//echo "<div class = "for-group" >";
$input['stockout_code'] = array(
						"name" => "data[stockout][stockout_code]",
						"placeholder" => "Stock Out Code",
						"class"=> "form-control",
						"id" => "stockout_code",
					);

$input['lorry_no'] = array(
						"name" => "data[stockout][lorry_no]",
						"placeholder" => "Lorry No *",
						"required" => "required",
						"class" => "form-control",
						'id' => "lorry_no"
					);

$input['broker_id'] = array(
							"required" =>"required",
							"class" =>"form-control select2",
							"id" => "broker_id",
						);

$input['order_code'] = array(
							'name' => "data[stockout][order_code]",
							'placeholder'=> "SO No *",
							"class" =>"form-control",
							"required" =>"required",
							"id" => "order_code",
							 );
$input['remark'] = array(
							'name' => "data[stockout][remark]",
							'placeholder'=> "Remark",
							"class" =>"form-control",
							"required" =>"required",
							"id" => "remark",
							 );

$input['company_warehouse_id'] = array(
						"required" =>"required",
						"class" => "form-control",
						'id' => "company_warehouse_id"
					);

/*$input['amount_before_tax'] =  array(
							"name" => "data[stockout][amount_before_tax]",
							"placeholder" => "Amount Before Tax",
							//"required" => "required",
							"class" => "form-control",
							"id" => "amount_before_tax",
							 );

$input['amount_after_tax'] =  array(
							"name" => "data[stockout][amount_after_tax]",
							"placeholder" => "Amount After Tax",
							//"required" => "required",
							"class" => "form-control",
							"id" => "amount_after_tax",
							 );

$input['other_charges'] =  array(
							"name" => "data[stockout][other_charges]",
							"placeholder" => "Other Charges",
							"class" => "form-control",
							"id" => "other_charges",
							 );
$input['field1'] =  array(
							"name" => "data[stockout][field1]",
							"placeholder" => "Field 1",
							"class" => "form-control",
							"id" => "field1",
							 );
$input['field2'] =  array(
							"name" => "data[stockout][field2]",
							"placeholder" => "Field 2",
							"class" => "form-control",
							"id" => "field2",
							 );
$input['invoice'] = array(
							'name' => "invoice",
							'placeholder'=> "Invoice",
							"class" =>"form-control",
							"id" => "invoice",
							 );*/


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
if(isset($values_posted['stockout']['outward_date'])){
	unset($values_posted['stockout']['outward_date']);
}
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ //echo '<pre>';print_r($values_posted);echo "</pre>";
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
		<li><a href="#"><i class="fa fa-plus-square"></i>Stock Out</a></li>
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
						<?php //echo form_open_multipart(custom_constants::new_stockout_url, ['class'=>'form-horizontal', 'id'=>'register_order']); 
						echo form_open_multipart('stocks/admin_edit_stock_out/'.$id, ['class'=>'form-horizontal', 'id'=>'register_order']); 
							
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
												<label for="inputStockout_date"  class="col-sm-2 control-label">Stock Out Date:</label>
												<div class="input-group date">
													<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
													</div>
													<?php echo form_input($input['outward_date']);?>
													<?php echo form_error('data[stockout][outward_date]'); ?>
												</div>
												<!-- /.input group -->
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputLorry_no" class="col-sm-2 control-label">Lorry No</label>
												<div class="col-sm-10">
													<?php echo form_input($input['lorry_no']); ?>
													<?php echo form_error('lorry_no'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputOrder_code" class="col-sm-2 control-label">SO No</label>
												<div class="col-sm-10">
													<?//=$values_posted['stockout']['order_code']?>
													<?=form_dropdown('data[stockout][order_code]', $option['orderCodes'],  isset($values_posted['stockout']['order_code'])?$values_posted['stockout']['order_code']:'', ['class'=>'form-control select2 filter', 'data-link'=>"brokers/getorderCodeWisebrokers", 'data-target'=>'broker_id', 'id'=>'order_code'])?>
													<?php echo form_error('data[stockout][order_code]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputBroker_id" class="col-sm-2 control-label">Broker</label>
												<div class="col-sm-10">
													<?php //echo form_input($input['vendor_id']);
													echo form_dropdown('data[stockout][broker_id]', $option['broker'], isset($values_posted['stockout']['broker_id'])?$values_posted['stockout']['broker_id']:'', $input['broker_id']);
													 ?>
													<?php echo form_error('data[stockout][broker_id]'); ?>
												</div>
											</div>
										</div>
									</div>
									
									
						<!-- /box-body -->
							 		<div class="box-header with-border">
											<h2 class="box-title">Outward Details</h2>
										</div>
										<div class="box-body" style="overflow-x:scroll">
											<table class="table">
												<thead>
													<tr>
														<th>Product</th>
														<th>Coil No</th>
														<!-- <th>Tax (GST %)</th> -->
														<th>Warehouse</th>
														<th>thickness</th>
														<th>width</th>
														<th>length</th>
														<th>Piece</th>
														<th>Qty</th>
														<th>UOM</th>
														<th>Remark</th>
														<!-- <th>Price</th> -->
														<!-- <th>Total</th> -->
														<th></th>
													</tr>
												</thead>
												<tbody>
												<?php
												if(isset($values_posted['stockout_details'])){


													foreach ($values_posted['stockout_details'] as $key => $detail) { 
														/*echo '<pre>';
														print_r($option);
														echo '</pre>';*/
														?>
													<tr id="<?=$key?>">
														<?php //print_r($option['product']);?>
														<td ><input type="hidden" name="stockout_details[<?=$key?>][id]" id="id_<?=$key?>" value="<?=$detail['id']?>">
															<?php echo form_dropdown("stockout_details[".$key."][product_id]",$option['product'], $detail['product_id'],["id" =>'product_id_'.$key, 'required'=>'required', 'class'=>'form-control select2 filter stock', 'data-link' => 'stocks/getProductwiseDetail', "data-target" => 'stock_detail_id_'.$key, "style" => 'width:100% !important']);?>
															
															
														</td>
														<td ><?//=$detail['coil_no']?>
															<?php echo form_dropdown("stockout_details[".$key."][stock_detail_id]",$option['coil_no'][$detail['product_id']], $detail['stock_detail_id'],["id" =>'stock_detail_id_'.$key, 'required'=>'required', 'class'=>'form-control select2 stockDetail', "style" => 'width:100% !important']);?>
															  <input type="text" readonly="readonly" id="balance_qty_<?=$key?>" value="Bal : 0 MT" name="bal[<?=$key?>][bal]" class="form-control input"> 
														</td>
														<td>
															<?php echo form_dropdown("stockout_details[".$key."][company_warehouse_id]",$option['companyWarehouse'], $detail['company_warehouse_id'],["id" =>'company_warehouse_id_'.$key, 'required'=>'required', 'class'=>'form-control select2', "style" => 'width:100% !important']);?>
														</td>
														<td>
															<input type="text" name="stockout_details[<?=$key?>][thickness]" class="form-control" id="thickness_<?=$key?>" value="<?=$detail['thickness']?>" readonly="readonly">
														</td>
														<td>
															<input type="text" name="stockout_details[<?=$key?>][width]" class="form-control" id="width_<?=$key?>" value="<?=$detail['width']?>" readonly="readonly">
														</td>
														
														<td>
															<input type="text" name="stockout_details[<?=$key?>][length]" class="form-control" id="length_<?=$key?>" value="<?=$detail['length']?>">
															<!-- <label><?=$detail['length']?></label> -->
														</td>
														<td>
															<input type="text" name="stockout_details[<?=$key?>][piece]" class="form-control" id="piece_<?=$key?>" value="<?=$detail['piece']?>">
															
															<!-- <label><?=isset($detail['piece'])?$detail['piece']:0?></label> -->
														</td>
														<td>
															<input type="text" name="stockout_details[<?=$key?>][qty]" class="form-control calculate" id="qty_<?=$key?>" value="<?=$detail['qty']?>">
															
															<!-- <label><?=$detail['qty']?></label> -->
														</td>
														
														<td>
															
															<?php echo form_dropdown("stockout_details[".$key."][uom]", $option['uom'],'', ["id" =>'uom_'.$key, "required"=>'required', "class" =>'form-control select2', "style" => 'width:100%']); ?>
														</td>
														<td>
															<input type="text" name="stockout_details[<?=$key?>][remark]" class="form-control calculate" id="remark_<?=$key?>" value="<?=$detail['remark']?>">
															
															<!-- <label><?=$detail['qty']?></label> -->
														</td>
														<td><a href="#" data-tr="<?=$key?>" class="removebutton"><span class="glyphicon glyphicon-remove"></span></a></td>
													</tr>
														<?php
													}
												}else{
													?>
													<tr id="0">
														<?php //print_r($option['product']);?>
														<td ><?php echo form_dropdown("stockout_details[0][product_id]",$option['product'],'',["id" =>'product_id_0', 'required'=>'required', 'class'=>'form-control select2 filter stock', 'data-link' => 'stocks/getProductwiseDetail', "data-target" => 'stock_detail_id_0', "style" => 'width:100% !important']);?></td>
														<td ><?php echo form_dropdown("stockout_details[0][stock_detail_id]",$option['stock_detail'],'',["id" =>'stock_detail_id_0', 'required'=>'required', 'class'=>'form-control select2 stockDetail', "style" => 'width:100% !important']);?>
															 <input type="text" readonly="readonly" id="balance_qty_0" value="Bal : 0 MT" name="bal[0][bal]" class="form-control input">
														</td>
														<td>
															<?php echo form_dropdown("stockout_details[0][company_warehouse_id]",$option['companyWarehouse'],'',["id" =>'company_warehouse_id_0', 'required'=>'required', 'class'=>'form-control select2', "style" => 'width:100% !important']);?>
														</td>
														<td>
															<input type="text" name="stockout_details[0][thickness]" class="form-control" id="thickness_0" value="" readonly="readonly">
														</td>
														<td>
															<input type="text" name="stockout_details[0][width]" class="form-control" id="width_0" value="" readonly="readonly">
														</td>
														
														<td>
															<input type="text" name="stockout_details[0][length]" class="form-control" id="length_0" value="">
														</td>
														<td>
															<input type="text" name="stockout_details[0][piece]" class="form-control" id="piece_0" value="">
														</td>
														<td>
															<input type="text" name="stockout_details[0][qty]" class="form-control calculate" id="qty_0" value=""></td>
														
														<td>
															
															<?php echo form_dropdown("stockout_details[0][uom]", $option['uom'],'', ["id" =>'uom_0', "required"=>'required', "class" =>'form-control select2', "style" => 'width:100%']); ?>
														</td>						
														<td>
															<input type="text" name="stockout_details[0][remark]" class="form-control" id="remark_0" value="" readonly="readonly">
														</td>
													</tr>
													<?php
												}
												?>
													
												</tbody>
												<tfoot>
													<!-- <tr>
														<th colspan="10" align="right" style="text-align: right">Grand Total</th>
														<td colspan="1" class="grandTotal" style="text-align: right">0.00</td>
													</tr> -->
													<?php if(!isset($values_posted['stockout_details'])){ ?>
													<tr>
												   		<td colspan="11"><button type="button" id="AddMorestockout" class="btn btn-info pull-right AddMoreRow">Add More</button>
												   		</td>
												   	</tr>
												   <?php } ?>
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

