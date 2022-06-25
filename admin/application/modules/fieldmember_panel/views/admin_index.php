    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-12">
      <h1>
        Module :: Field Sale
      </h1>
      <ol class="breadcrumb pull-right">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Field Sale</li>
      </ol>
      </div>
      </div>
    </section>
    <style>
      td.details-control {
          background: url('<?=assets_url('admin_lte/plugins/datatables/images/')?>details_open.png') no-repeat center center;
          cursor: pointer;
      }
      tr.shown td.details-control {
          background: url('<?=assets_url('admin_lte/plugins/datatables/images/')?>details_close.png') no-repeat center center;
      }
    </style>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!--<div class="box-header">
              <h3 class="box-title"><?=$formTitle?></h3>
              <?php //echo anchor(custom_constants::new_role_url, 'New Role', 'class="btn btn-primary pull-right"'); ?>
            </div>-->
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x:scroll">
              <?php 
                if(NULL!==$this->session->flashdata('message')) {
                  $msg = $this->session->flashdata('message');?>
                  <div class = "<?php echo $msg['class'];?>">
                      <?php echo $msg['message'];?>
                  </div>
              <?php } ?>
              <form class="form-vertical">
              <div class="row">
                  
                    <!--<div class="col-md-3">-->
                    <!--    <label>Select Delivery Person</label>-->
                    <!--    <?php echo form_dropdown('employee_id[]', $deliveryBoys['options'], '', ['class'=>'form-control select2', 'multiple'=>'multiple', 'id'=>'employee_id']); ?>-->
                    <!--</div>-->
                     <?php if(!in_array(6, $this->session->userdata('roles'))){?>
                    <div class="col-md-3">
                        <label>Select Delivery Person</label>
                        <?php echo form_dropdown('employee_id[]', $deliveryBoys['options'], '', ['class'=>'form-control select2', 'multiple'=>'multiple', 'id'=>'employee_id']); ?>
                    </div>
                    <?php }?>
                    <div class="col-md-3">
                        <label>Select Status</label>
                        <?php echo form_dropdown('order_status_id', $option['status'], '9', ['class'=>'form-control select2', 'id'=>'order_status_id']); ?>
                    </div>
              </div>
              </form>
              <table id="ajaxLoader" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th></th>
                    <!-- <th>Sr No</th> -->
                    <th class="hideIt">ID</th>
                    <th>Inv No.</th>
                    <th>Customer Name</th>
                    <!-- <th>Order Code</th> -->
                    <!--<th>Delivery Address</th>-->
                    <th>Amount After Tax</th>
                    <!-- <th>Message</th> -->
                    <!-- <th>Action</th> -->
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
  /* Formatting function for row details - modify as you need */
  function format ( d ) {
    //alert(d);
    //console.log(d);
    // `d` is the original data object for the row
    var table = '';
    table = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        
        '<tr>'+
            '<th>Delivery Boy:</th>'+
            '<td>'+d.delivery_boy+'</td>'+
        '</tr>';
        table+='<tr>'+
              '<th>Delivery Address:</th>'+
            '<td>'+d.delivery_address+'</td>'+
          '</tr>';
        if(d.order_status_id==9){
          table+='<tr>'+
              '<th colspan="2"> <a class="btn btn-md btn-danger" href="'+base_url+'fieldmember_panel/view_OrderDetail2/'+btoa(d.company_id+"-"+d.bill_type+"-"+d.id)+'"><i class="fa fa-truck alert-danger"></i></a></th>'+
          '</tr>';
        }else if(d.order_status_id==5){
          table+='<tr>'+
              '<th colspan="2"><i class="fa fa-check alert-success"></i> <b>Delivered</b></th>'+
          '</tr>';
        }else if(d.order_status_id==2){
          table+='<tr>'+
              '<th colspan="2">Order Status: <i class="fa fa-times alert-danger"></i> <b>Cancelled</b></th>'+
          '</tr>';
        }
    table+='</table>';
    return table;

  }
  $(document).ready(function(){
    var table = $('#ajaxLoader').DataTable({
     
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      "pageLength": 50,
      "order": [[1, "asc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>fieldmember_panel/adminindex',
         "data": function (d) {
            d.employee_id = $('#employee_id').val(),
            d.order_status_id = $('#order_status_id').val()
        }
      },
      'columns': [
          {
              "className":      'details-control',
              "orderable":      false,
              "data":           null,
              "defaultContent": ''
          },
        //  { data: 'sr_no' },
        { data: 'id' },
        { data: 'invoice_no' },
        { data: 'customer_name' },
        //  { data: 'order_code' },
        //{ data: 'delivery_address' },
        { data: 'amount' },
         //{ data: 'message' },
        //{ data: 'action' },
         
      ],
      "columnDefs": [
            {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            },
        ],
       
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false 
    });

    $(document).on('change', "#order_status_id, #employee_id", function(){
        console.log("hii")
        $('#ajaxLoader').DataTable().ajax.reload();
        //table.ajax.reload().draw();
    });
    // Add event listener for opening and closing details
    $('#ajaxLoader tbody').on('click', 'td.details-control', function () {
      //alert('kdjshfks');
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        
        //alert(row.data());
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
  });
   
</script>