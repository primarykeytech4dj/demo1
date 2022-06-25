
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
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> Customer List</h3>
        </div>
        <div class="row ">
    
            <div class="col-md-3">
            <label>Routes </label>
            <?php 

            echo form_dropdown('routes[]', ['select Attributes'],'',["id"=>'routes', 'class'=>'form-control select2', 'multiple'=>'multiple']); 
            echo form_error('routes'); 

            ?>
          </div>
          <div class="col-md-3">
						<div class="form-group">
							<button type="button" class="btn btn-primary " style="margin-top:25px;" onclick="filter();">Filter</button>
						</div>
					</div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <table id="ajaxLoader" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th></th>
                <th>Sr No</th>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Distance</th>
                <!-- <th>Latitude</th>
                <th>Longitude</th> -->
                <th>Update Location</th>
              </tr>
            </thead>
            <tbody>
                  
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th>Sr No</th>
                <th>ID</th>
                <th>Customer Name</th>
                <!-- <th>Latitude</th>
                <th>Longitude</th> -->
                <th>Update Location</th>
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
   /* Formatting function for row details - modify as you need */
   function format ( d ) {
    //alert(d);
    //console.log(d);
    // `d` is the original data object for the row
    var table = '';
    table = ' <table id="ajaxLoaderOrders'+d+'"  class="table table-bordered table-striped">'+
        '<thead>'+
        '<tr>'+
            '<th>ID</th>'+
            '<th>Delivery Boy:</th>'+
            '<th>Customer Name</th>'+
            '<th>Area Name</th>'+
            '<th>Amount</th>'+
            '<th>Date</th>'+
            '<th>Status</th>'+
            '<th>Salesman</th>'+
            '<th>Is Active</th>'+
        '</tr>'
        +'</thead>';
        table+='<tbody></tbody>'
      //  table+= '<tfoot>'+
      //   '<tr>'+
      //       '<th>Delivery Boy:</th>'+
      //       '<th>Delivery Boy:</th>'+
      //       '<th>Delivery Boy:</th>'+
      //       '<th>Delivery Boy:</th>'+
      //       '<th>Delivery Boy:</th>'+
      //       '<th>Delivery Boy:</th>'+
      //       '<th>Delivery Boy:</th>'+
      //       '<th>Delivery Boy:</th>'+
      //       '<th>Delivery Boy:</th>'+
      //   '</tr>'
      //   +'</tfoot>';
       
    table+='</table>';
    return table;

  }


  $(document).ready(function(){
    var table = $('#ajaxLoader').DataTable({
      'rowId': 'id',
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      "pageLength": 25,
      "order": [[4, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>customers/visit_customer_list',
         "data": function (d) {
            d.routes= $("#routes").val()
          }
      },
      'columns': [
        {
          "className":      'details-control',
          "orderable":      false,
          "data":           null,
          "defaultContent": ''
        },
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'first_name'},
         { data: 'distance'},
         { data: 'action' },
         
      ],
      "columnDefs": [
        {
            "targets": [ 2 ],
            "visible": false,
            "searchable": false
        },
        {
            "targets": [ 1 ],
            "visible": false,
            "searchable": false
        }
    ]
    });

    $('#ajaxLoader tbody').on('click', 'td.details-control', function () {
      // alert('kdjshfks');
        var trId = $(this).closest('tr').attr('id');
        // alert(trId);
        var tr = $(this).closest('tr');
        var row = table.row( tr );
            if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
          row.child( format(trId) ).show();
            tr.addClass('shown');
                $('#ajaxLoaderOrders'+trId).DataTable({
                  
                  'processing': true,
                  'serverSide': true,
                  'serverMethod': 'post',
                  "pageLength": 5,
                  "order": [[1, "desc"]],
                  "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
                  'ajax': {
                    'url':'<?=base_url()?>customers/get_customers_order/'+trId
                   
                  },
                  'columns': [
                    { data: 'sr_no' },
                    { data: 'id' },
                    { data: 'customer_name' },
                    { data: 'area_name' },
                    { data: 'amount_after_tax' },
                    { data: 'date' },
                    { data: 'status' },
                    { data: 'sale_by' },
                    { data: 'is_active' },
         
                  ],
                  "columnDefs": [
                    {
                        "targets": [ 1 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 2 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 7 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 8 ],
                        "visible": false,
                        "searchable": false
                    }
                  ],
                  'sDom': 't',
                  "bPaginate": false,
                  "bLengthChange": true,
                  "bFilter": true,
                  "bInfo": false,
                  "bAutoWidth": false 
                });
        }
      
       
    } );


  });
  $(document).ready(function(){
    $.ajax({
      type: "POST",
      dataType: "json",
      url :  base_url+"customers/get_salesman_route",
      success: function(response) {
        console.log(response);

        // alert('hii');
        $("#routes").select2("destroy").empty().select2({data : response}).trigger("change");
      }
    
    });
    
  });
  $(document).on('click', '.customer_name', function(){
    var trId = $(this).closest('tr').attr('id');
    
    Swal.fire({
      title: 'Customers Update',
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: '<i class="fa fa-shopping-cart"></i> Place Order',
      denyButtonText: `No Order`,
      cancelButtonText: `Cancel`,
      
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        window.location.href = "http://localhost/patanjali/admin/orders/neworder2?customer_id="+trId;
      } else if (result.isDenied) {
        var trIds = $(this).closest('tr').attr('id');
        Swal.fire({
          title: 'Enter reason for no order',
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Update',
   
          preConfirm: (inputData) => {
            
            $.ajax({
              type: "POST",
              dataType: "json",
              url :  base_url+"customers/get_salesman_route",
              success: function(response) {
                console.log(response);

                // alert('hii');
                $("#routes").select2("destroy").empty().select2({data : response}).trigger("change");
              }
            
            });
          //   return fetch(`//api.github.com/users/${login}`)
          //     .then(response => {
          //       if (!response.ok) {
          //         throw new Error(response.statusText)
          //       }
          //       return response.json()
          //     })
          //     .catch(error => {
          //       Swal.showValidationMessage(
          //         `Request failed: ${error}`
          //       )
          //     })
          return true;
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          // if (result.isConfirmed) {
          //   Swal.fire({
          //     title: `${result.value.login}'s avatar`,
          //     imageUrl: result.value.avatar_url
          //   })
          // }
          // if (result.isConfirmed) {
          //   Swal.fire('Saved!', '', 'success')
          // }
        });
      }
      else if(result.isDismissed){
        Swal.close();
      }
      // else{
      //   alert('else statement')
      //   Swal.close();
      // }
    })
    $(".swal2-actions").append('<a role="button" tabindex="0" class="swal2-cancel feedback swal2-styled load-ajax" data-path="orders/admin_order_details/42186" data-model-size="modal-lg" data-modal-title="Order Detail" aria-label  style="display: inline-block;">Feedback</a>');
  })

$(document).on('click', '.swal2-cancel', function(){
  Swal.close();
})
  function filter(){
        
        $('#ajaxLoader').DataTable().ajax.reload();
    }
  
</script>