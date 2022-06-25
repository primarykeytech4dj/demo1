
<!-- Content Header (Page header) -->
<!-- <section class="content-header">
  <h1>
    Module :: Products
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-user margin-r-5"></i> Products</li>
  </ol>
</section> -->

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> Customer List</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <table id="ajaxLoader" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Sr No</th>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Latitude</th>
                <th>Longitude</th>
              </tr>
            </thead>
            <tbody>
                  
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Latitude</th>
                <th>Longitude</th>
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
         'url':'<?=base_url()?>products/adminindex'
      },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'category_name' },
         { data: 'image_name_1' },
         { data: 'product' },
         { data: 'product_code' },
         { data: 'slug' },
         { data: 'base_price' },
         { data: 'base_uom' },
         { data: 'variant_count' },
         { data: 'description' },
         { data: 'priority' },
         { data: 'meta_title' },
         { data: 'meta_description' },
         { data: 'meta_keyword' },
         { data: 'is_sale' },
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