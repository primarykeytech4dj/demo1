<?php 
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$input['product'] = [
  'id'=>'product_id', 
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
<?php echo form_open_multipart('stocks/gradewise_report', ['class'=>'form-horizontal', 'id' => 'gradewise_report']);
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
            	<div class="col-md-4">
            		<div class="form-group">
					  <label for="inputProduct" class="col-sm-2 control-label">Product</label>
					  <div class="col-sm-9">
					   <!-- <?php echo form_dropdown('product_id', $option['product'], set_value('product_id'), $input['product']);?>
					   <?php echo form_error('data[stocks][product_id]');?> -->

					    <?php echo form_dropdown('product_id', $option['product'], !set_value('product_id')?0:set_value('product_id'), $input['product']);?>
					   <?php echo form_error('data[stocks][product_id]');?>
					  </div>
					</div>
            	</div>

            	<div class="col-md-4">
					
					  <button type="view_report" class="btn btn-info pull-left" id="View_Report">Filter</button>&nbsp;&nbsp;
					  <input type="button" value="Export" class="btn btn-info text-center" onclick="exportToExcel('report', 'gradewise_report_<?php echo date('dmyhis'); ?>.xls');">
					
				</div>
            </div>
            <hr>
            <div class="row">
				<div class="col-md-12" id="dvData">
				    <?php if(isset($reports)){ ?>
					<table id="report" class="table table-bordered table-striped report" style="table-layout:fixed">
					    <?php $sum = []; ?>
			            <thead>
			            <tr>
			              <!-- <th>Sr No</th> -->
			              
			              <th rowspan=2>Product</th>
			              <th colspan="<?=count($grades)?>">Grade</th>
			              <th rowspan=2>Total</th>
			            </tr>
			            <tr>
			                <?php 
			                foreach($grades as $gradeKey=>$grade){
			                    echo '<th>'.$grade.'</th>';
			                }
			                ?>
			            </tr>
			            </thead>
			            <tbody>
			                <?php 
			                foreach($reports as $key=> $v) {
			                    $total = 0;
			                    echo '<tr>';
			                    //print_r($v);
			                    echo '<td>'.$key.'</td>';
			                    foreach($grades as $gradeKey=>$grade){
    			                    echo '<td>'.((isset($v[$grade]))?number_format($v[$grade], 3):0).'</td>';
    			                    $total = $total+((isset($v[$grade]))?$v[$grade]:0);
    			                }
    			                echo '<td>'.number_format($total, 3).'</td>';
    			                echo '</tr>';
			                }?>
			            </tbody>
			            
			          </table>
                    <?php } ?>
				</div>
            </div><!-- /row -->
        </div><!-- /box-body -->
        
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
  </section>
