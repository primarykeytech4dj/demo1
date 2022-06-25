<?php 
$company = array(
                  'id'=>'company_id',
                  'class'=>'form-control',
                  'style'=>'width:100%;'
                );
?>
<section class="content">
<?php if(($this->session->flashdata('error'))) {
                    $csv = $this->session->flashdata('error');?>
                  <div class = "alert alert-<?php echo isset($csv['class'])?$csv['class']:'danger';?>">
                        <?php echo $csv['error'];?>
                  </div>
                  <?php } ?>
<?php //echo $id;?>
<?php echo form_open_multipart('customers/upload_stocks2', 'id="upload_stocks"');
  if(isset($form_error))
  {
                echo "<div class='alert alert-danger'>";
                echo $form_error;
                echo "</div>";
              }
              if($this->session->flashdata('message') !== FALSE) {
                $msg = $this->session->flashdata('message');?>
                <div class = "<?php echo  isset($msg['class'])?$msg['class']:'';?>">
                  <?php echo isset($msg['class'])?$msg['class']:'';?>
                </div>
                <?php } ?>
                <?php //echo form_open_multipart(custom_constants::upload_site_url);?> 
                <?php //echo form_hidden(['data[company_customers][company_id]']);?>
<div class= "row">
  <!-- <div class="row"> -->
    <!-- <div class="col-md-6">
      
      </div> -->
      <!-- </div> -->
      <div class="col-md-6">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Upload Stocks</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <!-- <form > -->
              <div class="box-body">
                <!-- <div class="row">
                  <div class = "col-md-12"> 
                    <div class="form-group">
                        <label class="col-sm-2">Company</label>
                         <div class="col-sm-10">
                          <?php echo form_dropdown('data[company_customers][company_id]',$option['company'], isset($company_id)?$company_id:'',$company);?>
                          <?php echo form_error('customer_id'); ?>
                        </div>
                    </div>
                  </div>
                </div> -->
                
                <div class="row">
                  
                  <div class="form-group">   
                     <br>
                    <?php echo nbs(4);?>
                    <input type="file" name='sel_file' id="sel_file" style="margin-left: 15px">
                  </div>

                 <div class = "col-sm-2"> 
                  <div class="form-group">
                    <input type="button" class="btn btn-info" value="upload" id="upload"  name="Upload">
                  </div> 
                 </div> 
                </div>
               <?php echo nbs(10); ?>

              <table class="table table-bordered table-striped mytable" style="display:none">
               <thead>
                 <tr>
                   <td>Rows</td>
                   <td>Products</td>
                   <td>Error</td>
                  </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
              </table>
            </div>
             <h4 class= "text-danger"> <?php //echo  $this->session->flashdata('error');?></h4>
             </div>
             </div>
             
             <?php echo form_close();?>
         
             

</div><!--End of Row-->
</section>

<script type="text/javascript">

$(document).on('click', '#upload', function(e){
		e.preventDefault();
// alert('hi');
var file_data = $("#sel_file").prop("files")[0]; // Getting the properties of file from file field
  var form_data = new FormData(); // Creating object of FormData class
  form_data.append("file", file_data)
  
		$.ajax({
            type: 'POST',
            // enctype: 'multipart/form-data',
            url: '<?php echo base_url('customers/upload_stocks2'); ?>',
            data: form_data,
            dataType: 'script',
            // contentType:  'application/json',
            // data: { username : username, 
            //         password : password, 
            //         work_dt : work_dt, },
            // cache: false,
            cache: false,
            contentType: false,
            processData: false,
            success:function(res) {
              var response = jQuery.parseJSON(res);
              // console.log(response.status);
              // console.log(response.data);
              if(response.status === "success"){
                swal.fire({
                  html: '<h5>'+response.message+'</h5>',
                  showConfirmButton: true,
                  onRender: function() {
                    // there will only ever be one sweet alert open.
                    $('.swal2-content').prepend(sweet_loader);
                    }
                  }).then((result) => {
                    
                  // $('tbody').html(response.data);
                 
                  });
              }else{
                Swal.fire(response.message);
                if(response.data)
                {

                  $('.mytable').attr('style', 'display:block');
                  $.each(response.data, function (index, itemData){
                      console.log(itemData);
                      $('#tbody').append('<tr><td>'+index+'<td>'+itemData.product+'</td><td>'+itemData.message+'</td></tr>');
                    })
                }
              }
              return false;
            } 
          });
        
		return false;
	})
</script>


