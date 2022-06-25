<?php
$customer  = array(
              'id' => 'customer_id',
              'required'  =>  'required',
              'class' =>  'form-control select2 filter',
              "tab-index" => 5,
              'style' => 'width:100%',
            );
            ?>

<section class="content">
<?php /*print_r($_SESSION);exit;*/ 
if(($this->session->flashdata('error'))) {
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

<?php 
      //echo form_open_multipart('customer_sites/upload_sites2');
      /*echo form_open_multipart(custom_constants::upload_site_url);*/
      echo form_open_multipart(custom_constants::upload_stock_csv_file); ?>
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
              <div class="box-body">
                <!-- <div class="row">
                  <div class = "col-md-12"> 
                    <div class="form-group">
                        <label class="col-sm-2">Customer</label>
                         <div class="col-sm-10">
                          <?php echo form_dropdown('data[customer_sites][customer_id]',$option['customers'], isset($customer_id)?$customer_id:'',$customer);?>
                          <?php echo form_error('customer_id'); ?>
                        </div>
                    </div>
                  </div>
                </div> -->
                
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
             <h4 class= "text-danger"> <?php //echo  $this->session->flashdata('error');?></h4>
             </div>
             </div>
             
             <?php echo form_close();?>

</div><!--End of Row-->
</section>
