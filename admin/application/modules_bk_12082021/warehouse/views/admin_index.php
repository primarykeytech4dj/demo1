
<!-- Content Header (Page header) -->
<?php if(isset($title)){ ?>
<section class="content-header">
  <h1>
   <span class="glyphicon glyphicon-shopping-cart"></span> <?=$title?>
  </h1>
  <ol class="breadcrumb">
    <li><?=anchor('#', '<i class="fa fa-dashboard"></i>Dashboard')?></li>
    <li class="active"><i class="fa fa-shopping-cart margin-r-5"></i> Warehouse</li>
  </ol>
</section>
<?php } ?>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        
        <div class="box-header">
          <h3 class="box-title">Warehouse List</h3>
         
        </div>
        <!-- /.box-header -->
      
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="warehouseList" class="table table-bordered table-striped ajaxLoader">
            <thead>
            <tr>
              <th>Sr No</th>
              <th class="hide">Id</th>
              <th>Warehouse</th>
              <th>Balance Qty (MT)</th>
              <th>Dump Qty (MT)</th>
              <th>Is Active</th>
              <!-- <th style="width:100px !important">Required Action</th> -->
            </tr>
            </thead>
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
    $('#warehouseList').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      "pageLength": 25,
      "order": [[3, "desc" ]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>warehouse/admin_index'
      },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'warehouse' },
         { data: 'balance_qty' },
         { data: 'dump_qty' },
         { data: 'is_active' },
         //{ data: 'action'},
         
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