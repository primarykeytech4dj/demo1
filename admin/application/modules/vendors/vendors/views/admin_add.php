<?php 
$tab = "basic details";
if(!defined('BASEPATH')) exit('No direct script access allowed ');

$input['first_name'] = array(
                          "name" => "data[vendors][first_name]",
                          "placeholder" => "first name(s) *",
                          "max_length" => "64",
                          "required" => "required",
                          "class" => "form-control",
                          "id" =>"first_name",
                          );

$input['middle_name'] = array(
                          "name" => "data[vendors][middle_name]",
                          "placeholder" => "middle name(s)",
                          "max_length" => "64",
/*                          "required" => "required",
*/                           "class" => "form-control",
                           "id" => "middle_name",
                           );

$input['surname'] = array(
                      "name" => "data[vendors][surname]",
                      "placeholder" => "surname(s)",
                      "max_length" => "64",
/*                      "required" => "required",
*/                      "class" => "form-control",
                      "id" => "surname",
                    );

$input['vendor_category_id'] = array(
                      "name" => "data[vendors][vendor_category_id]",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "vendor_category_id",
                    );

$input['company_name'] = array(
                      "name" => "data[vendors][company_name]",
                      "placeholder" => "company name(s) *",
                      "max_length" => "255",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "company_name",
                    );

$input['primary_email'] = array(
                      "name" => "data[vendors][primary_email]",
                      "placeholder" => "primary email *",
                      "max_length" => "100",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "primary_email",
                    );

$input['secondary_email'] = array(
                      "name" => "data[vendors][secondary_email]",
                      "placeholder" => "secondary email",
                      "class" => "form-control",
                      "id" => "secondary_email",
                    );


$input['contact_1'] = array(
                      "name" => "data[vendors][contact_1]",
                      "placeholder" => "contact 1 *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "contact_1",
                    );

$input['contact_2'] = array(
                      "name" => "data[vendors][contact_2]",
                      "placeholder" => "contact 2 *",
                      "class" => "form-control",
                      "id" => "contact_2",
                    );

$input['pan_no'] = array(
                      "name" => "data[vendors][pan_no]",
                      "placeholder" => "Pan No",
                      "class" => "form-control",
                      "id" => "pan_no",
                    );

$input['emp_code'] = array(
                      "name" => "data[vendors][emp_code]",
                      "placeholder" => "Client Code",
                      "class" => "form-control",
                      "id" => "emp_code",
                    );

$input['adhaar_no'] = array(
                      "name" => "data[vendors][adhaar_no]",
                      "placeholder" => "Adhaar Number",
                      
                      "class" => "form-control",
                      "id" => "adhaar_no",
                    );

$input['has_multiple_sites'] = array(
                      "name" => "data[vendors][has_multiple_sites]",
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
              "name" => "data[vendors][joining_date]",
              "placeholder" => "Joining Date *",
              "max_length" => "12",
              "required" => "required",
              "class" => "col-xs-3 form-control datepicker datemask",
              "id"  => "joining_date",
              'value'=>date('d/m/Y')
               );
$input['gst_no'] = array(
            'name' => 'data[vendors][gst_no]',
            'placeholder' => 'GST No',
            'class' => 'form-control',
            'id' => 'gst_no',
            );  

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
<?php if(!$this->input->is_ajax_request()){ ?>
  <section class="content-header">
    <h1>
      <?=$title?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><?php echo anchor(custom_constants::admin_vendor_listing_url, ' vendor'); ?></li>
      <li class="active"><?php echo anchor(custom_constants::new_vendor_url, 'New vendor'); ?></li>
    </ol> 
  </section>
<?php } ?>


<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <?php 
       $formClass = ($this->input->is_ajax_request())?"form-horizontal submit-ajax":"form-horizontal";
       echo form_open_multipart(custom_constants::new_vendor_url, ['class'=>$formClass, 'id' => 'register_vendor']);
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
            <h3 class="box-title"><?=$heading?></h3>
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputCompanyName" class="col-sm-2 control-label">Category</label>
                  <div class="col-sm-10">
                  <?php echo form_dropdown('data[vendors][vendor_category_id]', $option['vendor_categories'], set_value('data[vendors][vendor_category_id]'), $input['vendor_category_id']);?>
                  <?php echo form_error('data[vendors][vendor_category_id]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
                  <div class="col-sm-10">
                   <?php echo form_input($input['first_name']);?>
                   <?php echo form_error('data[vendors][first_name]');?>
                  </div>
                </div>
                </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputMiddleName" class="col-sm-2 control-label">Middle Name</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['middle_name']);?>
                  <?php echo form_error('data[vendors][middle_name]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputSurName" class="col-sm-2 control-label">Surname</label>
                  <div class="col-sm-10">
                <?php echo form_input($input['surname']);?>
                <?php echo form_error('data[vendors][surname]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputCompanyName" class="col-sm-2 control-label">Company Name</label>

                  <div class="col-sm-10">
                  <?php echo form_input($input['company_name']);?>
                  <?php echo form_error('data[vendors][company_name]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputAddress" class="col-sm-2 control-label">Primary Email</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['primary_email']);?>
                  <?php echo form_error('data[vendors][primary_email]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputSecondaryEmail" class="col-sm-2 control-label">Other Email </label>

                  <div class="col-sm-10">
                    <?php echo form_input($input['secondary_email']);?>
                    <?php echo form_error('data[vendors][secondary_email]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputContact1" class="col-sm-2 control-label">Contact 1</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['contact_1']);?>
                    <?php echo form_error('data[vendors][contact_1]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                   <label for="inputContact2" class="col-sm-2 control-label">Contact 2</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['contact_2']);?>
                    <?php echo form_error('data[vendors][contact_2]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputProfile_image" class="col-sm-2 control-label">Profile Image</label>
                  <div class="col-sm-10">
                      <?php echo form_upload($input['profile_img']); ?>
                          <?php echo form_error('profile_img'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputEmp_code" class="col-sm-2 control-label">vendor Code</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['emp_code']);?>
                    <?php echo form_error('data[vendors][code]'); ?>
                  </div>
                </div>
              </div>     
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputPan_no" class="col-sm-2 control-label">Pan No.</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['pan_no']);?>
                    <?php echo form_error('data[vendors][pan_no]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                   <label for="inputAdhaar_no" class="col-sm-2 control-label">Adhaar Number</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['adhaar_no']);?>
                    <?php echo form_error('data[vendors][adhaar_no]'); ?>
                  </div>
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputGstNo" class="col-sm-2 control-label">GST No</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['gst_no']);?>
                    <?php echo form_error('gst_no'); ?>
                  </div>
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <label for="inputJoining_date" class="col-sm-2 control-label">Join Date</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['joining_date']);?>
                    <?php echo form_error('data[vendors][joining_date]'); ?>
                  </div>
                </div>
              </div>
            </div>
        </div><!-- /box-body -->
        <div class="box-footer">  
          <div class="response"></div>
          <button type="submit" class="btn btn-info pull-left" id="Save">Save</button>
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
