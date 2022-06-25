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
              <?php //echo anchor(custom_constants::new_role_url, 'New Role', 'class="btn btn-primary pull-right"'); ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x:scroll">
              <?php 
                if($this->session->flashdata('message') !== FALSE) {
                  $msg = $this->session->flashdata('message');?>
                  <div class = "<?php echo $msg['class'];?>">
                      <?php echo $msg['message'];?>
                  </div>
              <?php } ?>
              
              <table id="ajaxLoader" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>ID</th>
                    <th>Ordered Through</th>
                    <th>Customer Name</th>
                    <th>Order Code</th>
                    <th>Order Date</th>
                    <th>Order Time</th>
                    <th>Area</th>
                    <th>Invoice Address</th>
                    <!--<th>Delivery Address</th>-->
                    <th class="hideIt">Shipping Charge</th>
                    <th class="hideIt">Amount Before Tax</th>
                    <th>Amount</th>
                    <th>Message</th>
                    <th>Order Status</th>
                    <th>Sale By</th>
                    <th class="hideIt">Active</th>
                    <th>Action</th>
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
    $('#ajaxLoader').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      "pageLength": 25,
      "order": [[1, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>orders/adminindex'
      },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'project_name' },
         { data: 'customer_name' },
         { data: 'order_code' },
         { data: 'date' },
         { data: 'time' },
         { data: 'area_name' },
         { data: 'invoice_address' },
         /*{ data: 'delivery_address' },*/
         { data: 'shipping_charge' },
         { data: 'amount_before_tax' },
         { data: 'amount_after_tax' },
         { data: 'message' },
         { data: 'status' },
         { data: 'sale_by' },
         { data: 'is_active' },
         { data: 'action' },
         
      ],
      "columnDefs": [
        {
            "targets": [ 1 ],
            "visible": false,
            "searchable": false
        },
        {
            "targets": [ 9 ],
            "visible": false,
            "searchable": false
        },
        {
            "targets": [ 10 ],
            "visible": false,
            "searchable": false
        },
        {
            "targets": [ 15 ],
            "visible": false,
            "searchable": false
        }
        
    ]
    });
  });
</script>