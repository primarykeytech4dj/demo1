<section class="content">
  <?php if(($this->session->flashdata('error'))) {
    $csv = $this->session->flashdata('error');?>
    <div class = "alert alert-<?php echo isset($csv['class'])?$csv['class']:'danger';?>">
      <?php foreach ($csv['error'] as $key => $value) {
        echo $value.'<br>';
      }
      ?>
    </div>
  <?php } elseif ($this->session->flashdata('message')) {
    $csv = $this->session->flashdata('message');?>
    <div class = "alert alert-<?php echo isset($csv['class'])?$csv['class']:'success';?>">
       <?php echo $csv['message'];?>
     </div>
  <?php } ?>

  <?php //echo form_open_multipart(custom_constants::upload_used_car_csv_file); 
    echo form_open_multipart('products/productmasters');

  ?>
    <div class= "row">
      <div class="col-md-6">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Upload Product Masters</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <div class="box-body">
              <div class="row">
                
                <div class="form-group">   
                  <br>
                  <?php echo nbs(4);?>
                  <input type="file" name='sel_file' style="margin-left: 15px">
                </div>

                <div class = "col-sm-2"> 
                  <div class="form-group">
                    <input type="submit" class="btn btn-info" value="upload"  name="Upload">
                  </div> 
               </div> 
              </div>
              <?php echo nbs(10); ?>
          </div>
          <h4 class= "text-danger"></h4>
        </div>
      </div>
    </div><!--End of Row-->
  <?php echo form_close();?>

</section>
