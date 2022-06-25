<?php 
$input['broker_id'] = array(
              "required" =>"required",
              "class" =>"form-control select2 addqc",
              "id" => "broker_id",
              "style"=>"width:80%"
            ); 
?>
<style>
                .amt{
                  text-align:right; padding:15px;
                }
            </style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
   <span class="glyphicon glyphicon-user"></span> <?=$title?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-user margin-r-5"></i>Broker Outstanding</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?=$heading?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="inputBroker_id" class="col-sm-2 control-label">Broker</label>
                <div class="col-sm-10">
                  <?php //echo $this->uri->segment(0);
                  echo form_dropdown('data[orders][broker_id]', $option['broker'], $this->uri->segment(3), $input['broker_id']);
                   ?>
                  <?php echo form_error('data[orders][broker_id]'); ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2">
            <thead>
              <tr>
        				<th>Broker Name</th>
        				<th>Bill Number</th>
        				<th>Bill Date</th>
        				<th>Ledger Name</th>
        				<th>Outstanding</th>
        			</tr>
            </thead>
            <tbody>
			<?php foreach ($outstanding as $key => $value) {

				/*echo '<pre>';
				print_r($value);exit;*/
				$outstanding = [];
				$count = count($value);?>
					<tr>
						
				<?php foreach ($value as $Okey => $Ovalue) {
					$outstanding[$Okey] = $Ovalue['outstanding'];
					?>
            <td><?php echo $key;?></td>
						<td><?php echo $Ovalue['bill_no'];?></td>
						<td><?php echo $Ovalue['bill_date'];?></td>
						<td><?php echo $Ovalue['bill_party'];?></td>
						<td class="amt"><?php echo number_format(abs($Ovalue['outstanding']), 2);?></td>
					</tr>
			<?php } ?> <tr style="color: red; font-size: 12px; font-weight: bold;"><td colspan="2"><b class="pull-right">Total Oustanding : <?=$key?></b></td>
            <td colspan="2"><?=strtoupper($this->pktlib->getIndianCurrency(abs(array_sum($outstanding))))?></td>
						   <td class="amt"><b style="color: red;  font-size: 12px;"><?php echo number_format(abs(array_sum($outstanding)),2);?></b></td></tr> <?php }  ?>
            </tbody>
          </table>
          </div></div>
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
<script type="text/javascript">
  $(document).on('change', '#broker_id', function(){
      val = $(this).val();
      //alert(val);
      if(val==0)
          window.location.href=base_url+'tally/broker-outstanding';
        else
      window.location.href=base_url+'tally/broker-outstanding/'+val;
  });
</script>