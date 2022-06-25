
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
                <h3 class="box-title">Filter</h3>
                <?php echo form_open("#", ['class'=>'form-vertical']); ?>
                <div class=-"row">
                    <div class="col-md-3">
                        <label>Select Area:</label>
                        <?php echo form_dropdown('area_name', $options['areas'], '0', ['class'=>'form-control select2', 'id'=>'area_id']); ?>
                    </div>
                    <div class="col-md-3">
                        <label>Select Status:</label>
                        <?php echo form_dropdown('is_active', [1=>'Active', 0=>'Inactive', ''=>'All'], 1, ['class'=>'form-control select2', 'id'=>'is_active']); ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php //print_r($this->session->userdata); ?>
              <table id="ajaxLoader" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>ID</th>
                    <th>Name</th>
                    
                    <th>Address</th>
                    <th>Tally Ledger Name</th>
                    <th>Referral Code</th>
                    
                    <th>Zone No</th>
                    <th>Route No</th>
                    <th>Active</th>
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
      "order": [[1, "ASC"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
            'url':'<?=base_url()?>customers/bulk_update',
            "data": function (d) {
                //d.employee_id = $('#employee_id').val(),
                d.area_id = $('#area_id').val(),
                d.is_active = $('#is_active').val()
            }
      },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'name' },
         { data: 'address' },
         { data: 'company_name' },
         { data: 'referral_code' },
         { data: 'zone_no' },
         { data: 'route_no' },
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
  
  $(document).on('change', "#area_id, #is_active", function(){
        console.log("hii")
        $('#ajaxLoader').DataTable().ajax.reload();
        //table.ajax.reload().draw();
    });
    
    function update(key){
        //alert(key);
        var referralCode = $("#referral_code_"+key).val();
        var zoneNo = $("#zone_no_"+key).val();
        var routeNo = $("#route_no_"+key).val();
        var customerId = $("#id_"+key).val();
        var is_active = $("#is_active_"+key+':checked').val();
        //alert(is_active);
        $.ajax({
            type: "POST",
            dataType: "json",
            url : base_url+"customers/updateZones",
            data: {"referral_code":referralCode, "zone_no":zoneNo, "route_no":routeNo, "customer_id":customerId},
            success: function(response) {
                //alert(response);
                if(response.status=="success"){
                    //alert(response.data.gst);
                    alert(response.msg);
                }
                //$("#"+datatarget).select2("destroy").empty().select2({data : response});
            }
        
        });
    }
</script>