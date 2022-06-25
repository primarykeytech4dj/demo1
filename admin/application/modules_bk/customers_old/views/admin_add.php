<?php 
$tab = "basic details";
if(!defined('BASEPATH')) exit('No direct script access allowed ');

$input['first_name'] = array(
                          "name" => "data[customers][first_name]",
                          "placeholder" => "first name(s) *",
                          "max_length" => "64",
                          "required" => "required",
                          "class" => "form-control",
                          "id" =>"first_name",
                          );

$input['middle_name'] = array(
                          "name" => "data[customers][middle_name]",
                          "placeholder" => "middle_name(s)",
                          "max_length" => "64",
/*                          "required" => "required",
*/                           "class" => "form-control",
                           "id" => "middle_name",
                           );

$input['surname'] = array(
                      "name" => "data[customers][surname]",
                      "placeholder" => "surname(s)",
                      "max_length" => "64",
/*                      "required" => "required",
*/                      "class" => "form-control",
                      "id" => "surname",
                    );

$input['company_name'] = array(
                      "name" => "data[customers][company_name]",
                      "placeholder" => "company_name(s) *",
                      "max_length" => "64",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "company_name",
                    );

$input['primary_email'] = array(
                      "name" => "data[customers][primary_email]",
                      "placeholder" => "primary_email(s) *",
                      "max_length" => "100",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "primary_email",
                    );

$input['secondary_email'] = array(
                      "name" => "data[customers][secondary_email]",
                      "placeholder" => "secondary_email",
                      "max_length" => "100",
                      "class" => "form-control",
                      "id" => "secondary_email",
                    );


$input['contact_1'] = array(
                      "name" => "data[customers][contact_1]",
                      "placeholder" => "contact_1 *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "contact_1",
                    );

$input['contact_2'] = array(
                      "name" => "data[customers][contact_2]",
                      "placeholder" => "contact_2 *",
                      "max_length" => "12",
                      "class" => "form-control",
                      "id" => "contact_2",
                    );

$input['pan_no'] = array(
                      "name" => "data[customers][pan_no]",
                      "placeholder" => "Pan No",
                      "class" => "form-control",
                      "id" => "pan_no",
                    );

$input['emp_code'] = array(
                      "name" => "data[customers][emp_code]",
                      "placeholder" => "Client Code",
                      "class" => "form-control",
                      "id" => "emp_code",
                    );

$input['adhaar_no'] = array(
                      "name" => "data[customers][adhaar_no]",
                      "placeholder" => "Adhaar Number",
                      
                      "class" => "form-control",
                      "id" => "adhaar_no",
                    );

$input['has_multiple_sites'] = array(
                      "name" => "data[customers][has_multiple_sites]",
                      "type" => 'checkbox',
                      "class" => "form-control flat-red",
                      "id" => "has_multiple_sites",
                    );

$blood_group = array(
                    "id" => "blood_group",
                    "class" => "form-control",
                    );


$input['profile_img'] =  array(
              "name" => "profile_img",
              "placeholder" => "profile_img *",
              "class" => "form-control",
              "id" => "profile_img",
              "value" =>  set_value('profile_img'),
               );

$input['joining_date'] =  array(   
              "name" => "data[customers][joining_date]",
              "placeholder" => "Joining Date *",
              "max_length" => "12",
              "required" => "required",
              "class" => "col-xs-3 form-control datepicker datemask",
              "id"  => "joining_date"
               );
$input['gst_no'] = array(
            'name' => 'data[customers][gst_no]',
            'placeholder' => 'GST No',
            'class' => 'form-control',
            'id' => 'gst_no',
            );  
$input['fuel_surcharge'] = array(
            'name' => 'data[customers][fuel_surcharge]',
            'placeholder' => 'Fuel Surcharge',
            'class' => 'form-control',
            'id' => 'fuel_surcharge',
            );  
$input['company'] = [
  'id'=>'company_id', 
  'required'=>'required', 
  'class'=>'form-control select2', 
  
];
//print_r($_SESSION);exit;
if($_SESSION['application']['share_products']){
  $input['company']['multiple'] = 'multiple';
}
if(isset($values_posted)) {
  foreach ($values_posted as $post_name => $post_value) {
    foreach ($post_value as $fieldkey => $fieldvalue) {
      if(isset($input[$fieldkey]['type']) && $input[$fieldkey]['type']=='checkbox'){
        $input[$fieldkey]['checked'] = "checked";
      }else
      $input[$fieldkey]['value'] = $fieldvalue;
      //$input[$fieldkey]['value'] = $fieldvalue;
    }
  }
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Customer Module
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li><?php echo anchor(custom_constants::admin_customer_listing_url, ' Customer'); ?></li>
    <li class="active"><?php echo anchor(custom_constants::new_customer_url, 'New Customer'); ?></li>
  </ol> 
</section>


<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <?php echo form_open_multipart(custom_constants::new_customer_url, ['class'=>'form-horizontal', 'id' => 'register_customer']);
      if(isset($form_error)) 
        {
          echo "<div class='alert alert-danger'>";
          echo $form_error;
          echo "</div>";
        }
        if($this->session->flashdata('message')!== FALSE) {
          $msg = $this->session->flashdata('message');?>
          <div class="<?php echo $msg['class'];?>">
            <?php echo $msg['message'];?>
          </div>
        <?php } ?>

        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">New Customer</h3>
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
            <?php if($_SESSION['application']['multiple_company']){?>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputCompanyProduct" class="col-sm-2 control-label">Company</label>
                        <div class="col-sm-10">
                          <?php echo form_dropdown('data[companies_customers][company_id][]',$option['company'], set_value('data[companies_customers][company_id]'), $input['company']);?>
                          <?php echo form_error('data[companies_customers][company_id]');?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
                  <div class="col-sm-10">
                   <?php echo form_input($input['first_name']);?>
                   <?php echo form_error('data[customers][first_name]');?>
                  </div>
                </div>
                </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputMiddleName" class="col-sm-2 control-label">Middle Name</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['middle_name']);?>
                  <?php echo form_error('data[customers][middle_name]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputSurName" class="col-sm-2 control-label">Surname</label>
                  <div class="col-sm-10">
                <?php echo form_input($input['surname']);?>
                <?php echo form_error('data[customers][surname]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputCompanyName" class="col-sm-2 control-label">Company Name</label>

                  <div class="col-sm-10">
                  <?php echo form_input($input['company_name']);?>
                  <?php echo form_error('data[customers][company_name]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputAddress" class="col-sm-2 control-label">Primary Email</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['primary_email']);?>
                  <?php echo form_error('data[customers][primary_email]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputSecondaryEmail" class="col-sm-2 control-label">Secondary Email</label>

                  <div class="col-sm-10">
                    <?php echo form_input($input['secondary_email']);?>
                    <?php echo form_error('data[customers][secondary_email]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputContact1" class="col-sm-2 control-label">Contact_1</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['contact_1']);?>
                    <?php echo form_error('data[customers][contact_1]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                   <label for="inputContact2" class="col-sm-2 control-label">Contact_2</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['contact_2']);?>
                    <?php echo form_error('data[customers][contact_2]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-6" >
                <div class="form-group">
                  <label  class="col-sm-2 control-label">Blood Group</label>
                  <div class="col-sm-10 col-md-10">
                     <!--  <?php echo form_dropdown('data[customers][blood_group]', $option['blood_group'],'',"id = 'blood_group' class = 'form-control'");?> -->
                      <?php echo form_dropdown('data[customers][blood_group]', $option['blood_group'],set_value('data[customers][blood_group]'),"id = 'blood_group' class = 'form-control'");?>
                      <?php echo form_error('data[customers][blood_group]');?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputCity" class="col-sm-2 control-label">Profile Image</label>
                  <div class="col-sm-10">
                      <?php echo form_upload($input['profile_img']); ?>
                          <?php echo form_error('profile_img'); ?>
                  </div>
                </div>
              </div>     
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputPan_no" class="col-sm-2 control-label">Pan No.</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['pan_no']);?>
                    <?php echo form_error('data[customers][pan_no]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                   <label for="inputAdhaar_no" class="col-sm-2 control-label">Adhaar Number</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['adhaar_no']);?>
                    <?php echo form_error('data[customers][adhaar_no]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputEmp_code" class="col-sm-2 control-label">Customer Code</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['emp_code']);?>
                    <?php echo form_error('data[customers][code]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputFuelSurcharge" class="col-sm-2 control-label">Fuel Surcharge</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['fuel_surcharge']);?>
                    <?php echo form_error('data[customers][fuel_surcharge]'); ?>
                  </div>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputJoining_date" class="col-sm-2 control-label">Bill Cycle Start Date/ Contract Date</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['joining_date']);?>
                    <?php echo form_error('data[customers][joining_date]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="inputGstNo" class="col-sm-2 control-label">GST No</label>
                    <div class="col-sm-10">
                      <?php echo form_input($input['gst_no']);?>
                      <?php echo form_error('gst_no'); ?>
                    </div>
                  </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputhas_multiple_sites" class="col-sm-2 control-label">Has Multiple Sites</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['has_multiple_sites']);?>
                    <?php echo form_error('data[customers][has_multiple_sites]'); ?>
                  </div>
                </div>
              </div>
            </div>
        </div><!-- /box-body -->
        <div class="box-footer">  
          <button type="new_college" class="btn btn-info pull-left" id="Save">Save</button>
          <?php echo nbs(3); ?>
          <button type="reset" class="btn btn-info">cancel</button>
        </div>
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
      <?php echo nbs(3); ?>
    </div>
  </div>
</section><!-- /.content -->
