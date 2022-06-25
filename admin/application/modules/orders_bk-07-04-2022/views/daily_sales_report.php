    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Module :: Orders
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Order</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?=$formTitle?></h3>
              <?php echo form_open_multipart('#', ['class'=>'form-vertical', 'id'=>'sales_filter']); ?>
                <div class="row">
                <div class="col-md-3">
                        <label>Zone no</label>
                        <?php echo form_input(['name'=>'zone', 'type'=>'text', 'class'=>'form-control', 'id'=>'zone']); ?>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="inputSalesPerson" class="col-sm-6 control-label">Sales Person</label>
                        
                          <select name="sale_by" class="form-control select2" id="sale_by">
                              <option value="">-select-</option>
                              <?php foreach($salePersons as $sKey=>$sales){
                              echo '<option value="'.$sales['id'].'">'.$sales['person_name'].' | '.$sales['username'].'</option>';
                              }?>
                          </select>
                        
                      </div>
                    </div>
                    <div class="col-md-3">
                        <label>From Date</label>
                        <?php echo form_input(['name'=>'from_date', 'type'=>'text', 'class'=>'form-control datepicker', 'id'=>'from_date', 'value'=>date('d/m/Y')]); ?>
                    </div>
                    <div class="col-md-3">
                        <label>To Date</label>
                        <?php echo form_input(['name'=>'to_date', 'type'=>'text', 'class'=>'form-control datepicker', 'id'=>'to_date', 'value'=>date('d/m/Y')]); ?>
                    </div>
					<!--<div class="col-md-3">
						<div class="form-group">
							<label for="inputZone" class="col-sm-6 control-label">Zone No</label>
							
								<input type="text" name="zone" id="zone" class="form-control">
							
						</div>
					</div>-->
					<div class="col-md-3">
						<div class="form-group">
							<button type="button" class="btn btn-primary" onclick="filter();">Filter</button>
						</div>
					</div>
				</div>
                
              <?php echo form_close(); ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x:scroll">
              <?php //
                // if($this->session->flashdata('message') !== FALSE) {
                //   $msg = $this->session->flashdata('message');?>
                  <!-- <div class = "<?php // echo $msg['class'];?>">
                      <?php //echo $msg['message'];?> -->
                  </div>
              <?php // } ?>
              
              <table id="ajaxLoader" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>Salesman</th>
                    <th>Orders (nos)</th>
                    <th>App %</th>
                    <th>customers</th>
                    <th>Items</th>
                    <th>Total amt</th>
                    <th>average</th>
                    <th>credit note Nos</th>
                    <th>Credit note amt</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
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
  $(document).ready(function(){
    var table = $('#ajaxLoader').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      //"pageLength": 25,
      'sort':false,
      'paging':false,
      "ordering": false,
      searching:false,
        "info":     false,
      "order": [[2, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
            'url':'<?=base_url()?>orders/daily_sales_report',
                "data": function (d) {
                d.sale_by = $('#sale_by').val(),
                d.from_date = $('#from_date').val(),
                d.to_date = $('#to_date').val(),
                d.zone= $("#zone").val()
            }
        },
      'columns': [
         { data: 'sr_no' },
         { data: 'sale_person' },
         { data: 'order_count' },
         { data: 'app_percent' },
         { data: 'customer_count' },
         { data: 'item_count' },
         { data: 'total_amt' },
         { data: 'average' },
         { data: 'credit_note_count' },
         { data: 'credit_note_amt' },
      ],
      columnDefs: [
            { targets: [6], className: "pull-right" },
        ]
    });
  });
  
  function filter(){
        
        $('#ajaxLoader').DataTable().ajax.reload();
    }
</script>