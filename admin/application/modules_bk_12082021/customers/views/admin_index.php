
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
      "order": [[2, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>customers/adminindex'
      },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'category_name' },
         { data: 'referral_code' },
         { data: 'name' },
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
</script>