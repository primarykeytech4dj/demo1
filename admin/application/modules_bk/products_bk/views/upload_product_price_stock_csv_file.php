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
    echo form_open_multipart('products/updateproductstockprice');

  ?>
    <div class= "row">
      <!-- <div class="col-md-6">
        <div class="form-group">
          <div class="col-sm-2"><label>Type</label></div>
          <div class="col-sm-10"></div>
        </div>
      </div> -->
      <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Upload Product Stock / Price</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                <br>
                  <?php echo nbs(4);?>
                <div class="form-group">
                  <label>Type</label>
                  <br>
                  <?php echo nbs(4);?>
                  <?=form_dropdown('data[product][type]',$type, '', ['id'=>'product', 'class'=>'form-control select2']);?>
                </div>
                <div class="form-group">
                  <!-- <input type="file" id="sel_file"> -->
                  <input type="file" name='sel_file' style="margin-left: 15px">
                   
                </div>
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" value="upload" name="Upload">Upload</button>
                <!-- <input type="submit" class="btn btn-info" value="upload"  name="Upload"> -->
              </div>
            </form>
          </div>
          <!-- /.box -->

          <!-- Form Element sizes -->
          
          <!-- /.box -->

          

        </div>
      
    </div><!--End of Row-->
  <?php echo form_close();?>

</section>
