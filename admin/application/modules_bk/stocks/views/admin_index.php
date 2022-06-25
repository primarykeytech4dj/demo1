
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
   <?=$title?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><?=$heading?></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?=$heading?></h3>
          <?php echo anchor(custom_constants::new_stock_url, 'New Stock', 'class="btn btn-primary pull-right"'); ?>
          
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          
          <table id="ajaxLoader" class="table table-bordered table-striped">
            <thead>
              <tr>
                <tr>
                <th>Sr No</th>
                <th class="hideIt">ID</th>
                <th>Inward Date</th>
                <th>Stock Code</th>
                <th>Vendor</th>
                <th>PO No</th>
                <th>Purchase Count</th>
                <th>Invoice</th>
                <th>Warehouse/Cutter</th>
                <th>Created On</th>
                <th>Created By</th>
                <th>Last Modified On</th>
                <th>Last Modified By</th>
                <th>Is Active</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                  
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th class="hideIt">ID</th>
                <th>Inward Date</th>
                <th>Stock Code</th>
                <th>Vendor</th>
                <th>PO No</th>
                <th>Purchase Count</th>
                <th>Invoice</th>
                <th>Warehouse/Cutter</th>
                <th>Created On</th>
                <th>Created By</th>
                <th>Last Modified On</th>
                <th>Last Modified By</th>
                <th>Is Active</th>
                <th>Action</th>
              </tr>
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
         'url':'<?=base_url()?>stocks/adminindex'
      },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'inward_date' },
         { data: 'stock_code' },
         { data: 'vendor' },
         { data: 'po_no' },
         { data: 'purchase_count' },
         { data: 'invoice' },
         { data: 'warehouse' },
         { data: 'created' },
         { data: 'created_by_user' },
         { data: 'modified' },
         { data: 'modified_by_user' },
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


