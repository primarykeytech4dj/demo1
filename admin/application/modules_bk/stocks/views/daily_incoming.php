<?php
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$input['inward_date'] =  array(		
							"name" => "inward_date",
							"placeholder" => "Inward Date *",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "inward_date",
							"value" => date('d/m/Y')
							 );
$input['vendor'] = [
  'id'=>'vendor_id', 
  'class'=>'form-control select2', 
];

$input['warehouse'] = [
  'id'=>'warehouse_id', 
  'class'=>'form-control select2', 
];

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

<section class="content-header">
	<h1 class="text-center"><?=$title?></h1>
	<ol class="breadcrumb pull-right">
		<li><?php echo anchor('#', '<i class="fa fa-dashboard"></i> Dashboard'); ?></li>
		<li><?php echo anchor(custom_constants::admin_stock_listing_url, '<i class="fa fa-list"></i> View Inward'); ?></li>
		<li><a href="#"><i class="fa fa-list"></i> Report</a></li>
	</ol>
</section>
<section class="content-body">
<?php echo form_open_multipart('stocks/stock_report', ['class'=>'form-horizontal', 'id' => 'stock_inward_report']);
        if($this->session->flashdata('message')!== FALSE) {
          $msg = $this->session->flashdata('message');?>
          <div class="<?php echo $msg['class'];?>">
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
					  <label for="inputVendor" class="col-sm-2 control-label">Vendor</label>
					  <div class="col-sm-9">
					   <?php echo form_dropdown('vendor_id', $option['vendors'], set_value('vendor_id'), $input['vendor']);?>
					   <?php echo form_error('data[stocks][vendor_id]');?>
					  </div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="inputWarehouse" class="col-sm-2 control-label">Cutter</label>
					  <div class="col-sm-9">
					   <?php echo form_dropdown('warehouse_id', $option['warehouse'], !set_value('warehouse_id')?0:set_value('warehouse_id'), $input['warehouse']);?>
					   <?php echo form_error('data[stocks][company_warehouse_id]');?>
					  </div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <label for="inputInward_date" class="col-sm-2 control-label">Inward Date</label>
					  <div class="col-sm-10">
					   <?php echo form_input($input['inward_date']);?>
					   <?php echo form_error('inward_date');?>
					  </div>
					</div>
				</div>
				
              	<div class="col-md-4">
					
					  <button type="view_report" class="btn btn-info pull-left" id="View_Report">Filter</button>&nbsp;&nbsp;
					  <input type="button" value="Export" class="btn btn-info text-center" onclick="exportToExcel('report', 'delivery_report_<?php echo date('dmyhis'); ?>.xls');">
					
				</div>
            </div><!-- /row -->
            <hr>
            <div class="row">
				<div class="col-md-12" id="dvData">
					<table id="report" class="table table-bordered table-striped report" style="table-layout:fixed">
					    <?php $sum = []; ?>
			            <thead>
			            <tr>
			              <!-- <th>Sr No</th> -->
			              <th>Inward Date</th>
			              <th>Stock Code</th>
			              <th>Vendor</th>
			              <th>Warehouse</th>
			              <th>Product</th>
			              <th>Grade</th>
			              <th>lot No</th>
			              <th>Coil No</th>
			              <th>thickness</th>
							<th>width</th>
							<th>length</th>
			              <th>Pcs</th>
			              <!-- <th>Ordered Qty</th>
			              <th>Received Qty</th> -->
			            
			              <th>Balance Qty</th>
			            </tr>
			            <tr id="search">
			                
			                <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
						  <th></th>
						  <th></th>
			              <th></th>
			              <th></th>
			              
			            </tr>
			            </thead>
			            <tbody>
			                <?php foreach($stocks as $key=> $v) { 			  $sum[] = $v['balance_qty'];?>

			               
			            <tr>
			              <!-- <td><?php echo $key+1 ;?></td> -->
			              <td><?=date('d/m/Y', strtotime($v['inward_date']))?></td>
			              <td><?=$v['stock_code']?></td>
			              <td><?php echo $v['company_name'] ;?></td>
			              <td><?=$v['warehouse']?></td>
			              <td><?=$v['product']?></td>
			              <td><?=($v['type']=='new')?$v['grade']:$v['stock_grade']?></td>
			              <td><?=($v['type']=='new')?$v['lot_no']:$v['stock_lot']?></td>
			              <td><?=$v['coil_no']?></td>
			              <td><?=(is_numeric($v['thickness']))?number_format($v['thickness'],2):$v['thickness']?></td>
			              <td><?=$v['width']?></td>
			              <td><?=$v['length']?></td>
			              <td><?=$v['piece']?></td>
			              <!-- <td><?=$v['order_qty']." ".$v['uom']?></td>
			              <td><?=$v['qty']." ".$v['uom']?></td> -->
			              <td><?=$v['balance_qty']?></td>
			            </tr>
			            <?php }?>
			            </tbody>
			            <tfoot>
			                <tr><th colspan="12" class="text-center">Total Qty</th><td><?=array_sum($sum)?></td></tr>
			              <tr>
			              <!-- <th>Sr No</th> -->
			              <th>Inward Date</th>
			              <th>Stock Code</th>
			              <th>Vendor</th>
			              <th>Warehouse</th>
			              <th>Product</th>
			              <th>Grade</th>
			              <th>lot No</th>
			              <th>Coil No</th>
			              <th>thickness</th>
						  <th>width</th>
						  <th>length</th>
			              <th>Pcs</th>
			              <!-- <th>Ordered Qty</th>
			              <th>Received Qty</th> -->
			            
			              <th>Balance Qty</th>
			            </tr>
			            </tfoot>
			          </table>

				</div>
            </div><!-- /row -->
        </div><!-- /box-body -->
        
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
  </section>
