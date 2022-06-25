
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Login
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-user margin-r-5"></i> Login</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-user margin-r-5"></i> Login</h3>
          <?php //echo anchor(custom_constants::new_user_url, 'New User', 'class="btn btn-primary pull-right"'); ?>
         
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
         
          <table id="ajaxLoader" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Full Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Roles</th>
              <th>Is Active</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            
            </tbody>
            <tfoot>
            <tr>
              <th>Sr No</th>
              <th>Full Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Roles</th>
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
      "order": [[1, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>login/admin_index_2'
        
      },
      'columns': [
         { data: 'sr_no' },

         { data: 'full_name' },
         { data: 'username' },
         { data: 'email' },
         { data: 'roles' },
         { data: 'is_active' },

         { data: 'action' }
         
      ],
      "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false
    //   "columnDefs": [
    //     {
    //         "targets": [ 1 ],
    //         "visible": false,
    //         "searchable": false
    //     },
    //     {
    //         "targets": [ 9 ],
    //         "visible": false,
    //         "searchable": false
    //     },
    //     {
    //         "targets": [ 10 ],
    //         "visible": false,
    //         "searchable": false
    //     },
    //     {
    //         "targets": [ 15 ],
    //         "visible": false,
    //         "searchable": false
    //     }
        
    // ]
    });
  });

  function filter(){
        
        $('#ajaxLoader').DataTable().ajax.reload();
    }
</script>
