<?php 

if(!defined('BASEPATH')) exit('No direct script access allowed ');

$billingAreas  = array(
      				'id' =>	'billing_area',
      				'required'	=>	'required',
      				'class'	=>	'form-control select2',
      				"tab-index" => 1,
              "multiple" => "multiple",
      			);

$input['billing_date'] = array(
                          "name" => "data[customer_site_bills][billing_date]",
                          "placeholder" => "Date *",
                          "required" => "required",
                          "class" => "form-control pull-right",
                          "id" =>"reservation",
                          );

if(isset($value_posted)) {
  //print_r($value_posted);
  foreach ($value_posted['data'] as $post_name => $post_value) {
    foreach ($post_value as $field_key => $field_value) { //echo $field_key.'<br>';
      $input[$field_key]['value'] = $field_value;
    }
  }
}
//print_r($input);
?>
<!-- Content Header (Page header) -->
<!-- <section class="content-header">
  <h1>
    Module :: Customer Sites
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li><a href="<?php echo custom_constants::site_url; ?>"> Sites List</a></li>
    <li class="active"><a href="#">New Site</a></li>
  </ol> 
</section> -->


<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <?php echo form_open_multipart('billings/areaWiseBill', ['class'=>'form-horizontal', 'id' => 'register_customer']);
      if(isset($form_error)) 
      {
        echo "<div class='alert alert-danger'>";
        echo $form_error;
        echo "</div>";
      }

      //print_r($this->session->flashdata('message'));
      if($this->session->flashdata('message')!== FALSE) {
        $msg = $this->session->flashdata('message');?>
        <div class="<?php echo $msg['class'];?>">
          <?php echo $msg['message'];?>
        </div>
      <?php } ?>
        <input type="hidden" name="url" value="<?php echo !isset($url)?'customer_sites/edit_site/':$url; ?>">
        <input type="hidden" name="module" value="<?php echo !isset($module)?'customer_sites':$module; ?>">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Generate Bill</h3>
            <?php echo anchor('billings', 'View Bills', ['class'=>"btn btn-primary pull-right", ]); ?>
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
          	<div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputcustomer_id" class="col-sm-2 control-label">Select Areas For Billing</label>
                    <div class="col-sm-10">
                      
                      <?php echo form_dropdown('data[customer_site_bills][area_id][]',$option['billingAreas'], isset($value_posted['data']['customer_site_bills']['area_id'])?$value_posted['data']['customer_site_bills']['area_id']:'',$billingAreas);?>
                      <?php echo form_error('data[customer_site_bills][area_id][]'); ?>
                    </div>
                </div>
              </div>
            
          		<div class="col-md-6">
                <!-- <div class="form-group">
                  <label>Date range button:</label>
                  <div class="input-group">
                    <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                      <span>
                        <i class="fa fa-calendar"></i> Date range picker
                      </span>
                      <i class="fa fa-caret-down"></i>
                    </button>
                  </div>
                </div> -->
                <label>Date range:</label>
                <div class="form-group">
                  <div class="col-sm-10">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <!-- <input type="text" name="data[customer_site_bills][billing_date]" class="form-control pull-right" id="reservation"> -->
                      <?php echo form_input($input['billing_date']);?>
                      <?php echo form_error('data[customer_sites][billing_date]');?>
                    </div>
                    <!-- /.input group -->
                  </div>
                </div>
              </div>
          	</div>
          </div><!-- /box-body -->
        <div class="box-footer">  
          <button type="new_college" class="btn btn-info pull-left" id="view_bill">View Bill</button>
          <?php echo nbs(3); ?>
          <button type="reset" class="btn btn-info">cancel</button>
        </div>
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
      <?php echo nbs(3); ?>
    </div>
  </div>

  <?php if(isset($billAnnexure)){
    echo $billAnnexure;
  } ?>
</section><!-- /.content -->
