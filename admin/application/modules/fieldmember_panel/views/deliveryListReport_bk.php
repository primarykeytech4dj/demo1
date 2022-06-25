    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-12">
      <h1>
        <?=$title?>
      </h1>
      <ol class="breadcrumb pull-right">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-truck"></i> Delivery Modules</a></li>
        <li class="active">Delivery List</li>
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
                  
                    <div class="col-md-3">
                        <label>Select Delivery Person</label>
                        <?php echo form_dropdown('employee_id[]', $deliveryBoys['options'], '', ['class'=>'form-control select2', 'multiple'=>'multiple', 'id'=>'employee_id']); ?>
                    </div>
                    <div class="col-md-3">
                        <label>Select Status</label>
                        <?php echo form_dropdown('order_status_id', $option['status'], '', ['class'=>'form-control select2', 'id'=>'order_status_id']); ?>
                    </div>
                    <div class="col-md-3">
                        <label>Delivery No</label>
                        <?php echo form_dropdown('delivery_no', $option['deliveryNo'], '', ['class'=>'form-control select2', 'id'=>'delivery_no']); ?>
                    </div>
                    <div class="col-md-3">
                        <label>From Date</label>
                        <?php echo form_input(['name'=>'from_date', 'type'=>'text', 'class'=>'form-control datepicker', 'id'=>'from_date', 'value'=>date('d/m/Y')]); ?>
                    </div>
                    <div class="col-md-3">
                        <label>To Date</label>
                        <?php echo form_input(['name'=>'to_date', 'type'=>'text', 'class'=>'form-control datepicker', 'id'=>'to_date', 'value'=>date('d/m/Y')]); ?>
                    </div>
              </div>
              </form>
              <hr>
              <table id="ajaxLoader" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th></th>
                    <th>Delivery No</th>
                    <th>Invoice No.</th>
                    <th>Delivery Boy</th>
                    <th>Customer Name</th>
                    <th>Territory</th>
                    <!--<th>Total Qty</th>-->
                    <th>Total Amount</th>
                    <th>Assigned On</th>
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
        if(d.order_status_id==9){
          table+='<tr>'+
              '<th colspan="2"> <a class="btn btn-md btn-danger" href="'+base_url+'fieldmember_panel/view_OrderDetail/'+d.id+'"><i class="fa fa-truck alert-danger"></i> Update</a></th>'+
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
      "pageLength": 15,
      "order": [[2, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>fieldmember_panel/deliveryList',
         "data": function (d) {
            d.employee_id = $('#employee_id').val(),
            d.order_status_id = $('#order_status_id').val(),
            d.delivery_no = $('#delivery_no').val()
            d.from_date = $('#from_date').val()
            d.to_date = $('#to_date').val()
        }
      },
      'columns': [
          {
              "className":      'details-control',
              "orderable":      false,
              "data":           null,
              "defaultContent": ''
          },
        { data: 'delivery_no' },
        { data: 'invoice_no' },
        { data: 'delivery_boy' },
        { data: 'customer_name' },
        { data: 'area_name' },
        /*{ data: 'total_qty' },*/
        { data: 'amount', 'className': "pull-right" },
        { data: 'created' },
         //{ data: 'message' },
        //{ data: 'action' },
         
      ],
      "columnDefs": [
            {
                "targets": [ 1 ],
                "visible": true,
                "searchable": true
            }
        ],
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false 
    });

    $(document).on('change', "#order_status_id, #employee_id, #delivery_no, #from_date, #to_date", function(){
        //console.log("hii")
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