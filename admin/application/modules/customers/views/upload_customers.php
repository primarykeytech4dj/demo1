<?php 
$company = array(
                  'id'=>'company_id',
                  'class'=>'form-control',
                  'style'=>'width:100%;'
                );
?>
<section class="content">
<?php if(NULL !== $this->session->flashdata('error')) {
                    $csv = $this->session->flashdata('error');?>
                  <div class = "alert alert-<?php echo isset($csv['class'])?$csv['class']:'danger';?>">
                        <?php echo $csv['error'];?>
                  </div>
                  <?php } ?>
<?php //echo $id;?>
<?php echo form_open_multipart('customers/upload_customer2');
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
              <h3 class="box-title">Upload Customers</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">
                <div class="row">
                  <div class = "col-md-12"> 
                    <div class="form-group">
                        <label class="col-sm-2">Company</label>
                         <div class="col-sm-10">
                          <?php echo form_dropdown('data[company_customers][company_id]',$option['company'], isset($company_id)?$company_id:'',$company);?>
                          <?php echo form_error('customer_id'); ?>
                        </div>
                    </div>
                  </div>
                </div>
                
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

