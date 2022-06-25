
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Module :: Customers
      </h1>
      <ol class="breadcrumb">
        
        <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Customers</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">

          <div class="box-header">
              <h3 class="box-title">Customer</h3>
              <?php echo anchor((in_array(7, $this->session->userdata('roles')) || in_array(6, $this->session->userdata('roles')))?custom_constants::new_customer_url_new:custom_constants::new_customer_url, 'New Customer', 'class="btn btn-primary pull-right"'); ?>
          </div>
          <div class="box-header">
            <form class="form-vertical" autocomplete="off">
              <div class="row">
                  
                    <!-- <div class="col-md-3">
                        <label>Select Delivery Status</label>
                        <?php //echo form_dropdown('order_status_id', $option['status'], '', ['class'=>'form-control select2', 'id'=>'order_status_id']); ?>
                    </div> -->
                    <div class="col-md-3">
                        <label>Name</label>
                        <?php echo form_input(['name'=>'name', 'type'=>'text', 'class'=>'form-control ', 'id'=>'name']); ?>
                    </div>
                    <!-- <div class="col-md-3">
                        <label>Email</label>
                        <?php //echo form_input(['name'=>'email', 'type'=>'text', 'class'=>'form-control ', 'id'=>'email']); ?>
                    </div> -->
                    <div class="col-md-3">
                        <label>Mobile</label>
                        <?php echo form_input(['name'=>'mobile', 'type'=>'text', 'class'=>'form-control ', 'id'=>'mobile']); ?>
                    </div>
                  <!-- </div>
                  <div class="row"> -->
                    <div class="col-md-2">
                        <label>From Date</label>
                        <?php echo form_input(['name'=>'from_date', 'type'=>'text', 'class'=>'form-control datepicker', 'id'=>'from_date', 'value'=>date('d/m/Y', strtotime('-7 days'))]); ?>
                    </div>
                    <div class="col-md-2">
                        <label>To Date</label>
                        <?php echo form_input(['name'=>'to_date', 'type'=>'text', 'class'=>'form-control datepicker', 'id'=>'to_date', 'value'=>date('d/m/Y')]); ?>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-primary" onclick="filter();">Filter</button>
                    </div>
              </div>
              </form>
              </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php //print_r($this->session->userdata); ?>
              <table id="ajaxLoader" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Referral Code</th>
                    <th>Name</th>
                    <th>App Order Count</th>
                    <th>Area</th>
                    <th>Tally Ledger Name</th>
                    <th>GST No</th>
                    <th>Primary / Secondary Email</th>
                    <th>contact 1 / Contact 2</th>
                    <!--<th>Blood Group</th>-->
                    <th>Created</th>
                    <!--<th>Modified</th>
                    <th>Multiple Site</th>-->
                    <th>Active</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Referral Code</th>
                    <th>Name</th>
                    <th>App Order Count</th>
                    <th>Primary / Secondary Email</th>
                    <th>contact 1 / Contact 2</th>
                    <!--<th>Blood Group</th>-->
                    <th>Created</th>
                    <!--<th>Modified</th>
                    <th>Multiple Site</th>-->
                    <th>Active</th>
                    <th>Action</th>
                </tfoot>
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
      search: {
            return: true
        },
      "order": [[10, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>customers/adminindex',
         "data": function (d) {
                
                d.name = $('#name').val(),
                d.email = $('#email').val(),
                d.mobile = $('#mobile').val(),

                d.from_date = ($('#name').val() == "" && $('#email').val() == "" && $('#mobile').val()) ? $('#from_date').val() : "",
                d.to_date = ($('#name').val() == "" && $('#email').val() == "" && $('#mobile').val()) ? $('#to_date').val() : ""
            }
      },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'category_name' },
         { data: 'referral_code' },
         { data: 'name' },
         { data: 'app_order_count' },
         { data: 'area_name' },
         { data: 'company_name' },
         { data: 'gst_no' },
         { data: 'email' },
         { data: 'contact' },
         //{ data: 'blood_group' },
         { data: 'created' },
         /*{ data: 'modified' },
         { data: 'has_multiple_sites' },*/
         { data: 'is_active' },
         { data: 'action' },
         
      ],
      "columnDefs": [
        {
            "targets": [ 1 ],
            "visible": false,
            "searchable": false
        }
    ]
    });
  });
  function filter(){
        
        $('#ajaxLoader').DataTable().ajax.reload();
    }
</script>