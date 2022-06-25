<?php 
$input['dashboard_date'] =  array(
							"name" => "dashboard_date",
							"placeholder" => "dd/mm/YYYY",
							"class" => "form-control datepicker datemask",
							"id" => "dashboard_date",
							'autocomplete'=>'nope',
				// 			'value'=>!set_value('dashboard_date')?$dashboard_date:set_value('dashboard_date')
				'value'=>!set_value('dashboard_date')?date('d-m-Y'):set_value('dashboard_date')
						);

$formClass = ($this->input->is_ajax_request())?"form-horizontal submit-ajax":"form-horizontal";
	echo form_open_multipart('#', ['class'=>$formClass, 'id'=>'address_'.time(), 'autocomplete'=>'nope']);  ?>
<section class="content" id="user" style="min-height:20px;">
	<div class="row">
	  <div class="col-xs-12">
	    <div class="box box-solid">
                <div class="box-body">
        	      	<div class="row">
        	        	<div class="col-md-6">
        					<div class="form-group">
        						<label for="gst_no" class="col-sm-4 control-label">Select Date :</label>
    							<div class="col-sm-8">
    								
    								<?php echo form_input($input['dashboard_date']); ?>
    								<?php echo form_error('dashboard_date'); ?>
    							</div>
        					</div>
        				</div>
        				<div class="col-md-6">
        					<div class="form-group">
        						<button type="submit" class="btn btn-info pull-left">Apply</button>
        					</div>
        				</div>
        				
        			</div>
			    </div>
	        </div>
	    </div>
    </div>
</section>
<?php echo form_close();?>
<?php foreach ($reports as $key => $report) {
	     if(count($report['data'])>0){   		?>
<!-- Main content -->

<section class="content" id="user">
	<div class="row">
	  <div class="col-xs-12">
	    <div class="box box-solid">
	      <div class="box-header">
	        <i class="fa <?=$report['icon']?>"></i>

	        <h3 class="box-title"><?=ucfirst($key)?></h3>

	        <div class="box-tools pull-right">
	          <button type="button" class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
	          </button>
	          <button type="button" class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i>
	          </button>
	        </div>
	      </div>
	      <!-- /.box-header -->
	      <div class="box-body">
	      	<?php //echo '<pre>';print_r($reports);echo '</pre>'; ?>
	        <div class="row">
	        	<?php foreach ($report['data'] as $dkey => $data) {
	        		?>
					<div class="col-lg-3 col-xs-6">
						<!-- small box -->
						<div class="small-box <?=$data['background']?>">
							<div class="inner">
								<h3><?=$data['count']?></h3>

								<p><?=$data['title']?></p>
							</div>
						<div class="icon">
							<i class="fa <?=$data['icon']?>"></i>
							</div>
							<?=$data['url']?>
						</div>
					</div>
					<!-- ./col -->

	        		<?php } ?>
	        </div>
	        <!-- /.row -->
	      </div>
	      <!-- /.box-body -->
	    </div>
	    <!-- /.box -->
	  </div>
	  <!-- /.col -->
	</div>
	<!-- /.row -->
</section>
<!-- /.content -->
<?php
}
} ?>