
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-credit-card margin-r-5"></i> Print Bills</h3>
          <?php //echo anchor('billings/areaWiseBill', 'Generate New Bill', ['class'=>"btn btn-primary pull-right", ]); ?>
        </div>
        <!-- /.box-header -->
        <?php echo form_open('billings/print_multiple_bills'); ?>
        <div class="box-body" style="overflow-x: scroll;">
		<?php 
			if(isset($print_content)){
				foreach ($print_content as $key => $content) {
					echo $content."<hr>";
				}
			}
		 ?>
		</div>
	</div>
</div></div></section>