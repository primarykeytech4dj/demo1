   
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-12">
      <h1>
        Module :: Order
      </h1>
      <ol class="breadcrumb pull-right">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Order</li>
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
              <form class="form-vertical" autocomplete="off">
              <div class="row">
                  
                    <div class="col-md-3">
                        <label>Select Delivery Status</label>
                        <?php echo form_dropdown('order_status_id', $option['status'], '', ['class'=>'form-control select2', 'id'=>'order_status_id']); ?>
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
              <table id="ajaxLoader" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th></th>
                    <!-- <th>Sr No</th> -->
                    <th class="hideIt">ID</th>
                    <th>Date</th>
                    <th>Party Name</th>
                    <th>Territory</th>
                    <th>Route</th>
                    <th>Invoice No.</th>
                    <th>Qty in Kgs</th>
                    <th>Pcs</th>
                    <th style="width:150px">Amount</th>
                    <th>Delivery Boy</th>
                    <th>Status</th>
                    <?php foreach($paymentModes as $ky=>$paymentMode){
                        echo '<th>'.ucfirst($paymentMode['payment_mode']).'</th>';
                    }
                    ?>
                    <th>Pending</th>
                    
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
    console.log(d);
    // `d` is the original data object for the row
    var table = '';
    table = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
        
        /*'<tr>'+
            '<th>Delivery Boy:</th>'+
            '<td>'+d.delivery_boy+'</td>'+
        '</tr>';*/
        /*'<tr>'+
            '<th>Status:</th>'+
            '<td>'+d.status+'</td>'+
        '</tr>';*/
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
      "pageLength": 25,
      "order": [[6, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>orders/collection_report',
         "data": function (d) {
            //d.employee_id = $('#employee_id').val(),
            d.order_status_id = $('#order_status_id').val(),
            d.from_date = $('#from_date').val(),
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
        //{ data: 'invoice_no' },
        { data: 'sr_no' },
        { data: 'date' },
        { data: 'customer_name' },
        { data: 'area_name' },
        { data: 'route_no' },
        { data: 'invoice_no' },
        { data: 'total_in_kgs' },
        { data: 'delivery_address' },
        { data: 'amount' },
        /*{ data: 'shipping_charge' },
        { data: 'amount_after_tax' },*/
        { data: 'message' },
        { data: 'status' },
        /*{ data: 'delivery_boy' },*/
         
      ],
      "columnDefs": [
            {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            }
        ],
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false 
    });

    $(document).on('change', "#order_status_id, #employee_id, #from_date, #to_date", function(){
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