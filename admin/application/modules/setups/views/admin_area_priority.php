    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Module :: Setups
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Order</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            <div class="row ">
              <div class="col-md-3">
                <h2 class="box-title "><?=$meta_title?></h2>
              </div>
            </div>
             <!-- <br> -->
                <div class="row " style ="margin-top:10px">
                  <!-- <div class="col-md-3">
                    <label>Zone no</label>
                      <?php 
                      echo form_dropdown('zone[]', $option['zone'],'',"id='zone' class='form-control select2 zone' multiple='multiple'"); 
                      echo form_error('zone');  ?>
                    </div>
                    <div class="col-md-3">
                    <label>Routes </label>
                    <?php 

                    echo form_dropdown('routes[]', ['select Attributes'],'',["id"=>'routes', 'class'=>'form-control select2', 'multiple'=>'multiple']); 
                    echo form_error('routes'); 

                    ?>
                  </div> -->
                  <div class="col-md-3">
                    <label>Is Active</label>
                    <?php 

                    echo form_dropdown('is_active',$option['is_active'],'',["id"=>'is_active', 'class'=>'form-control select2']); 
                    echo form_error('is_active'); 

                    ?>
                  </div>
                  <div class="col-md-3 " >
                    <div class="form-group">
                      <button type="button" style ="margin-top:25px" class="btn btn-primary" onclick="filter();">Filter</button>
                    </div>
                  </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x:scroll">
              <?php //
                // if($this->session->flashdata('message') !== FALSE) {
                //   $msg = $this->session->flashdata('message');?>
                  <!-- <div class = "<?php // echo $msg['class'];?>">
                      <?php //echo $msg['message'];?> -->
                  </div>
              <?php // } ?>
              <?php echo form_open_multipart('setups/update_area_status', ['class'=>'form-horizontal', 'id'=>'new_setup']);  ?>
              <table id="ajaxLoader" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>ID</th>
                    <th>Priority</th>
                    <th>Area Name</th>
                    <th>City Name</th>
                    <th>State Name</th>
                    <th>Is Active</th>                  
                  </tr>
                </thead>
                <tbody class="row_postion"> 

                </tbody>
              </table>
              <?=form_close()?>
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
    var table = $('#ajaxLoader').DataTable({

        'rowId': 'id',
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      "pageLength": 100,
      'sort':false,
      'paging':false,
      "ordering": false,
      'searching':false,
      // "order": [[]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
            'url':'<?=base_url()?>setups/area_priority',
                "data": function (d) {
                  // d.routes = $('#routes').val(),
                  // d.zone= $("#zone").val(),
                d.is_active = $('#is_active').val()
            }
        },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'priority' },
         { data: 'area_name' },
         { data: 'city_name' },
         { data: 'state_name' },
         { data: 'is_active' }
  
      ],
        
      "columnDefs": [
        {
            "targets": [ 1 ],
            "visible": false,
            "searchable": false
        }
        // ,
        // {
        //     "targets": [ 2 ],
        //     "visible": false,
        //     "searchable": false
        // }   
        ]
    });
    
  });
  
  function filter(){
        
        $('#ajaxLoader').DataTable().ajax.reload();
    }
  
  $(".row_postion").sortable({
    delay:150,
    stop:function(){
        Swal.fire({  
          title: 'Do you want to save the changes?',  
          showDenyButton: true,  showCancelButton: true,  
          confirmButtonText: `Save`,  
          denyButtonText: `Don't save`,
        }).then((result) => {  
          console.log(result);
          /* Read more about isConfirmed, isDenied below */  
            if (result.isConfirmed) { 
              var selectedData = new Array();
              $(".row_postion>tr").each(function(){
                  selectedData.push($(this).attr("id"));
              })   
              UpdateOrder(selectedData); 
            } else if (result.isDenied) {    
              filter();
            }else if (result.isDismissed) {    
              filter();
            }
        });
        
        // alert(selectedData);
    }
  })


  $(document).on('click', '.update-status', function(e){
        e.preventDefault();
        var formId = $(this).closest('form').attr('id');
        var trId = $(this).closest('tr').attr('id');
        // alert(trId);
        // alert($(this).attr('id'));
        var formData = new FormData();
        formData.append('id', $(this).attr('id'));
        $("#"+trId+" *").filter(':input').each(function(){
			// alert('sas');
            if(typeof $(this).attr('name')!='undefined'){
                if($(this).attr('type')=="checkbox"){
                    var val = 0;
                    if($(this).is(":checked")){
                       val = 1;
                    }
                    //alert($(this).attr('name')+" "+val);
                  
                    formData.append($(this).attr('name'), val);
                   
                }else if($(this).attr('type')=="button"){
                    
                }else{
                    
                    // alert(this.id+" "+$(this).attr('name')+" "+$(this).val());
                    formData.append($(this).attr('name'), $(this).val());
                }
            }
        });
        
        var action = $("#"+formId).attr('action');
        
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: action,
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
    
                console.log(data);
                var data = $.parseJSON(data);
                Toast.fire({
                    icon: data.status,
                    title: data.message
                })
                filter();
            }
        });
        console.log(formData);
        return false;
    });

  function UpdateOrder(aData)
  {
    $.ajax({
        url:'<?=base_url()?>setups/update_area_priority',
        type: 'POST',
        data: { allData: aData },
        success: function(data){
        var result = jQuery.parseJSON(data);
           
          Swal.fire({
            position: 'top-end',
            icon: result.status,
            title: result.message,
            showConfirmButton: false,
            timer: 1500
          })
      }
    });
  }
    
  function filter(){
        
        $('#ajaxLoader').DataTable().ajax.reload();
    }
</script>