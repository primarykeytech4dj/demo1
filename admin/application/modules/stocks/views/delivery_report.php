<?php
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$input['from_date'] =  array(		
							"name" => "data[stocks][from_date]",
							"placeholder" => "From Date *",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "from_date",
							"value" => date('d/m/Y')
							 );
//echo "<div class = "for-group" >";
$input['to_date'] =  array(		
							"name" => "data[stocks][to_date]",
							"placeholder" => "To Date *",
							"required" => "required",
							"class" => "col-xs-3 form-control datepicker datemask",
							"id"	=> "to_date",
							"value" => date('d/m/Y')
							 );
$input['coil_no'] =  array(		
							"name" => "data[stocks][coil_no]",
							"placeholder" => "Coil No",
							"class" => "col-xs-3 form-control",
							"id"	=> "coil_no" 
							);

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
<?php echo form_open_multipart('stocks/delivery_report', ['class'=>'form-horizontal', 'id' => 'stock_inward_report']);
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
					  <label for="inputFromdate" class="col-sm-2 control-label">From Date</label>
					  <div class="col-sm-10">
					   <?php echo form_input($input['from_date']);?>
					   <?php echo form_error('data[stocks][from_date]');?>
					  </div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="inputFromdate" class="col-sm-2 control-label">To Date</label>
					  <div class="col-sm-10">
					   <?php echo form_input($input['to_date']);?>
					   <?php echo form_error('data[stocks][to_date]');?>
					  </div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="inputCoil_no" class="col-sm-2 control-label">Coil No</label>
					  <div class="col-sm-10">
					   <?php echo form_input($input['coil_no']);?>
					   <?php echo form_error('data[stocks][coil_no]');?>
					  </div>
					</div>
				</div>
              	<div class="col-md-3">
					<div class="form-group">
					  <button type="view_report" class="btn btn-info pull-left" id="View_Report">View Report</button>&nbsp;&nbsp;
					  <input type="button" value="Export To Excel" class="btn btn-info text-center" onclick="exportToExcel('report', 'delivery_report_<?php echo date('dmyhis'); ?>.xls');">
					</div>
				</div>
            </div><!-- /row -->
            <hr>
            <div class="row">
				<div class="col-md-12" id="dvData">
					<table id="report" class="table table-bordered table-striped report">
			            <thead>
			            <tr>
			              <!-- <th>Sr No</th> -->
			              <th>Outward Date</th>
			              <th>So No</th>
			              <th>Broker</th>
			              <th>Lorry No</th>
			              <th>Product</th>
			              <th>Warehouse/Cutter</th>
			              <th>Size (thickness x Width x length)</th>
			              <th>Coil No</th>
			              <th>Grade</th>
			              <th>Pcs</th>
			              <th>Qty</th>
			            </tr>
			            </thead>
			            <tbody>
			                <?php 
			                $totalQty = [];
			                foreach($reports as $key=> $v) {
			                    $totalQty[] = $v['qty'];
			                ?>
			            <tr>
			              <!-- <td><?php echo $key+1 ;?></td> -->
			              <td><?=date('d F,Y', strtotime($v['outward_date']))?></td>
			              <td><?=$v['order_code']?></td>
			              <td><?php echo $v['broker']." (".$v['company_name'].")" ;?></td>
			              <td><?=$v['lorry_no']?></td>
			              <td><?=$v['product']?></td>
			              <td><?=$v['warehouse']?></td>
			              <td><?=$v['thickness'].' x '.$v['width'].' x '.$v['length']?></td>
			              <td><?=$v['coil_no']?></td>
			              <td><?=$v['grade']?></td>
			              <td><?=$v['piece']?></td>
			              <td><?=$v['qty']." ".$v['uom']?></td>
			            </tr>
			            <?php }?>
			            </tbody>
			            <tfoot>
			              <tr>
			                <th colspan="10" style="text-align:center">Total</th>
							
							<th><?=array_sum($totalQty)?> MT</th>
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
