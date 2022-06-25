<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$input['outward_date'] =  array(		
							"name" => "data[stockout][outward_date]",
							"placeholder" => "Stock Out Date *",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "outward_date",
							"value" => date('d/m/Y')
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
					
$input['order_id'] = array(
						"name" => "data[stockout][order_id]",
						"placeholder" => "Order No *",
						"required" => "required",
						"class" => "form-control",
						'id' => "order_id"
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

$input['company_warehouse_id'] = array(
						"required" =>"required",
						"class" => "form-control",
						'id' => "company_warehouse_id"
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
if(isset($values_posted['stockout']['outward_date'])){
	unset($values_posted['stockout']['outward_date']);
}
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
						<?php echo form_open_multipart(custom_constants::multiple_stockout_url, ['class'=>'form-horizontal', 'id'=>'multiple_stockout']);
						
							
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
										<div class="box-body" style="overflow-x:scroll">
											<table class="table">
												<thead>
													<tr>
														<th style="width: 250px">Date</th>
														<th style="width: 250px">Lorry No</th>
														<th style="width: 250px">SO No</th>
														<th style="width: 250px">Broker</th>
														<th style="width: 250px">Product</th>
														<th>Coil No</th>
														<!-- <th>Tax (GST %)</th> -->
														<th>Warehouse</th>
														<th>thickness</th>
														<th>width</th>
														<th>length</th>
														<th>Piece</th>
														<th>Qty</th>
														<th>UOM</th>
														<!-- <th>Price</th> -->
														<!-- <th>Total</th> -->
														<th></th>
													</tr>
												</thead>
												<tbody>
													
												
													<tr id="0">
														<td><?php echo form_input([
																"name" => "stockout[0][outward_date]",
																"placeholder" => "Stock Out Date *",
																"required" => "required",
																"class" => "form-control datepicker datemask nochange",
																"id"	=> "outward_date",
																"value" => date('d/m/Y'),
																'style'=>"width: 200px"
															]);?></td>
														<td>
															<?php echo form_input([
																"name" => "stockout[0][lorry_no]",
																"placeholder" => "Lorry No *",
																"required" => "required",
																"class" => "form-control nochange",
																'id' => "lorry_no_0",
																'style'=>"width: 100px"
															]); ?>
																
														</td>
														<td>
															<?=form_input(array(
																"name" => "stockout[0][order_code]",
																"placeholder" => "Order No *",
																"required" => "required",
																"class" => "form-control nochange",
																'style'=>"width: 100px",
																'id' => "order_code_0"
															))?>
															 
														</td>
														<td><?php //echo form_input($input['vendor_id']);
													echo form_dropdown('stockout[0][broker_id]', $option['broker'], isset($values_posted['stockout']['broker_id'])?$values_posted['stockout']['broker_id']:'', [
															"required" =>"required",
															"class" =>"form-control select2 nochange",
															"id" => "broker_id",
															'style'=>"width: 150px"
														]);
													 ?></td>
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
															<input type="text" name="stockout_details[0][length]" class="form-control required" style="width: 100px;" id="length_0" value="" required="required">
														</td>
														<td>
															<input type="text" name="stockout_details[0][piece]" class="form-control required" style="width: 100px;" id="piece_0" value="" required="required">
														</td>
														<td>
															<input type="text" name="stockout_details[0][qty]" style="width: 100px;" class="form-control dispatchqty calculate required" id="qty_0" value="" required="required"></td>
														
														<td>
															
															<?php echo form_dropdown("stockout_details[0][uom]", $option['uom'],'', ["id" =>'uom_0', "required"=>'required', "class" =>'form-control select2 nochange', "style" => 'width:100%']); ?>
														</td>						
														<td></td>
													</tr>
													
													
												</tbody>
												<tfoot>
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
									<button type="reset" class="btn btn-info">Reset</button>
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

