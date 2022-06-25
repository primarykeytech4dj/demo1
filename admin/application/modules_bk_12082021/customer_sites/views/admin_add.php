<?php 

if(!defined('BASEPATH')) exit('No direct script access allowed ');

$input['first_name'] = array(
                          "name" => "data[customer_sites][first_name]",
                          "placeholder" => "first name *",
                          "max_length" => "64",
                          "required" => "required",
                          "class" => "form-control",
                          "id" =>"first_name",
                          );

$input['middle_name'] = array(
                          "name" => "data[customer_sites][middle_name]",
                          "placeholder" => "middle_name *",
                          "max_length" => "64",
                          "required" => "required",
                           "class" => "form-control",
                           "id" => "middle_name",
                           );

$input['surname'] = array(
                      "name" => "data[customer_sites][surname]",
                      "placeholder" => "surname *",
                      "max_length" => "64",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "surname",
                    );
$input['service_charge'] = array(
                      "name" => "data[customer_sites][service_charge]",
                      "placeholder" => "service_charge *",
                      "max_length" => "64",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "service_charge",
                      'value' => '0',
                    );

/*$input['company_name'] = array(
                      "name" => "data[customer_sites][company_name]",
                      "placeholder" => "company name *",
                      "max_length" => "64",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "company_name",
                    );*/

$input['primary_email'] = array(
                      "name" => "data[customer_sites][primary_email]",
                      "placeholder" => "primary email *",
                      "max_length" => "100",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "primary_email",
                    );

$input['secondary_email'] = array(
                      "name" => "data[customer_sites][secondary_email]",
                      "placeholder" => "secondary email",
                      "max_length" => "100",
                      "class" => "form-control",
                      "id" => "secondary_email",
                    );


$input['contact_1'] = array(
                      "name" => "data[customer_sites][contact_1]",
                      "placeholder" => "contact 1",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "contact_1",
                    );

$input['contact_2'] = array(
                      "name" => "data[customer_sites][contact_2]",
                      "placeholder" => "contact 2",
                      "max_length" => "12",
                      "class" => "form-control",
                      "id" => "contact_2",
                    );

$input['address_1'] =  array(
              "name" => "data[address][address_1]",
              "placeholder" => "address 1*",
              "required" => "required",
              "class" => "form-control",
              "id" => "address_1",
              "rows" => "5",
              "tab-index" => 3,
            );

$input['site_name'] =  array(
              "name" => "data[address][site_name]",
              "placeholder" => "Site Name*",
              "required" => "required",
              "class" => "form-control",
              "id" => "site_name",
              "rows" => "5",
            );

$input['address_2'] =  array(
              "name" => "data[address][address_2]",
              "placeholder" => "address 2*",
              "required" => "required",
              "class" => "form-control",
              "id" => "address_2",
              "tab-index" => 4,
              "rows" => "5",
            );

$serviveChargeType  = array(
            'id'  =>  'service_charge_type',
            'required'  =>  'required',
            'class' =>  'form-control select2',
            'style' => 'width:100%',
          );
$country  = array(
              'id' => 'country_id',
              'required'  =>  'required',
              'class' =>  'form-control select2 filter',
              'data-link' => 'states/getCountrywiseStates',
              'data-target' =>'state_id',
              "tab-index" => 5,
              'style' => 'width:100%',

            );

$customer  = array(
              'id' => 'country_id',
              'required'  =>  'required',
              'class' =>  'form-control select2',
              "tab-index" => 5,
              'style' => 'width:100%',
            );

$state  = array(
            'id'  =>  'state_id',
            'required'  =>  'required',
            'class' =>  'form-control select2 filter',
            'data-link' => 'cities/getStateWiseCities',
            'data-target' => 'city_id',
            "tab-index" => 6,
            'style' => 'width:100%',

          );

$city =   array(
        'id' => 'city_id',
        'required' => 'required',
        'class' => 'form-control select2 filter',
        'data-link' => 'areas/getCityWiseAreas',
        'data-target' => 'area_id',
        "tab-index" => 7,
        'style' => 'width:100%',

      );

$area  = array(
        'id' => 'area_id',
        'required'  =>  'required',
        'class' =>  'form-control select2 filter',
        "tab-index" => 8,
        'style' => 'width:100%',

       );

$input['pincode'] = array(
            "name" => "data[address][pincode]",
            "placeholder" => "pincode*",
            "max_length" => "6",
            "required" => "required",
            "class" => "form-control",
            "id" => "pincode",
            "tab-index" => 9,
          );
if(isset($value_posted)) {
  foreach ($value_posted as $post_name => $post_value) {
    foreach ($post_value as $field_key => $field_value) {
      $input[$field_key]['value'] = $field_value;
    }
  }
}
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
      <?php echo form_open_multipart(custom_constants::new_site_url, ['class'=>'form-horizontal', 'id' => 'register_customer']);
     /* if(isset($form_error)) 
      {
        echo "<div class='alert alert-danger'>";
        echo $form_error;
        echo "</div>";
      }*/

      //print_r($this->session->flashdata('message'));
     /* if($this->session->flashdata('message')!== FALSE) {
        $msg = $this->session->flashdata('message');?>
        <!-- <div class="<?php echo $msg['class'];?>">
          <?php echo $msg['message'];?>
        </div> -->
      <?php }*/ ?>
        <input type="hidden" name="url" value="<?php echo !isset($url)?'customer_sites/edit_site/':$url; ?>">
        <input type="hidden" name="module" value="<?php echo !isset($module)?'customer_sites':$module; ?>">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">New Site</h3>
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputcustomer_id" class="col-sm-2 control-label">Customer</label>
                    <div class="col-sm-10">
                      
                      <?php echo form_dropdown('data[customer_sites][customer_id]',$option['customers'], isset($customer_id)?$customer_id:'',$customer);?>
                      <?php echo form_error('customer_id'); ?>
                    </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputMiddleName" class="col-sm-2 control-label">Site Name</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['site_name']);?>
                  <?php echo form_error('data[address][site_name]'); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
                  <div class="col-sm-10">
                   <?php echo form_input($input['first_name']);?>
                   <?php echo form_error('data[customer_sites][first_name]');?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputMiddleName" class="col-sm-2 control-label">Middle Name</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['middle_name']);?>
                  <?php echo form_error('data[customer_sites][middle_name]'); ?>
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
                <?php echo form_error('data[customer_sites][surname]'); ?>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-6">
                <div class="form-group">
                  <label for="inputCompanyName" class="col-sm-2 control-label">Company Name</label>
              
                  <div class="col-sm-10">
                  <?php echo form_input($input['company_name']);?>
                  <?php echo form_error('data[customer_sites][company_name]'); ?>
                  </div>
                </div>
              </div> -->
            
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputAddress" class="col-sm-2 control-label">Primary Email</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['primary_email']);?>
                  <?php echo form_error('data[customer_sites][primary_email]'); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputSecondaryEmail" class="col-sm-2 control-label">Secondary Email</label>

                  <div class="col-sm-10">
                    <?php echo form_input($input['secondary_email']);?>
                    <?php echo form_error('data[customer_sites][secondary_email]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputContact1" class="col-sm-2 control-label">Contact 1</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['contact_1']);?>
                    <?php echo form_error('data[customer_sites][contact_1]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                   <label for="inputContact2" class="col-sm-2 control-label">Contact 2</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['contact_2']);?>
                    <?php echo form_error('data[customer_sites][contact_2]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                   <label for="inputservice_charge_type" class="col-sm-2 control-label">Service Charge Type</label>
                  <div class="col-sm-10">
                    <?php echo form_dropdown('data[customer_sites][service_charge_type]', $option['service_charge_tye'], '', $serviveChargeType); ?>
                    <?php echo form_error('data[customer_sites][service_charge_tye]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                   <label for="inputservice_charge" class="col-sm-2 control-label">Service Charge</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['service_charge']);?>
                    <?php echo form_error('data[customer_sites][service_charge]'); ?>
                  </div>
                </div>
              </div>
            </div>
            
            <h3>Site Address</h3>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address_1" class="col-sm-2 control-label">Address 1</label>
                  <div class="col-sm-10">
                    <?php if(isset($value_posted['address']['id'])){
                      echo form_input(['name'=>'data[address][id]', 'id'=>'address_id', 'value'=>$value_posted['address']['id']]);
                    } ?>
                    <?php echo form_textarea($input['address_1']); ?>
                    <?php echo form_error('address_1'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address_2" class="col-sm-2 control-label">Address 2</label>
                  <div class="col-sm-10">
                    <?php echo form_textarea($input['address_2']); ?>
                    <?php echo form_error('address_2'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="country_id" class="col-sm-2 control-label">Country</label>
                  <div class="col-sm-10">
                      
                      <?php echo form_dropdown('data[address][country_id]', $option['countries'], '', $country); ?>
                      <?php echo form_error('country'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="state_id" class="col-sm-2 control-label">State</label>
                    <div class="col-sm-10">
                      
                      <?php echo form_dropdown('data[address][state_id]',$option['states'], '',$state);?>
                      <?php echo form_error('state'); ?>
                    </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="city_id" class="col-sm-2 control-label">City</label>
                  <div class="col-sm-10">
                    <?php echo form_dropdown('data[address][city_id]', $option['cities'], '', $city); ?>
                    <?php echo form_error('city'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="area_id" class="col-sm-2 control-label">Area</label>
                  <div class="col-sm-10">
                    <?php echo form_dropdown('data[address][area_id]', $option['areas'], '', $area); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="pincode" class="col-sm-2 control-label">Pincode</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['pincode']); ?>
                    <?php echo form_error('pincode'); ?>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-6">
                <div class="form-group">
                  <label for="is_default" class="col-sm-2 control-label">Set as Default Address</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['is_default']); ?>
                    <?php echo form_error('is_default'); ?>
                  </div>
                </div>
              </div> -->
            </div><!-- /row -->
            <hr><h2>Site Services</h2><hr>
            <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered table-striped" style="overflow-x: scroll;">
                <thead>
                  <tr>
                    <th>Service</th>
                    <th>Start Date</th>
                    <th>Day Shift Man Power</th>
                    <th>Night Shift Man Power</th>
                    <th>Shift</th>
                    <th>Cost Per Labour</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr id="0">
                    <td>
                      <?php echo form_dropdown('customer_site_services[0][product_id]', $option['services'], '', ['id'=>'product_id_0', 'class'=>'form-control select2', 'required'=>'required']); ?>
                    </td>
                    <td>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      <?php echo form_input(['name'=>'customer_site_services[0][start_date]', 'class'=>'col-xs-3 form-control datepicker datemask', 'id'=>'start_date_0']);?>
                      <?php echo form_error('customer_site_services[0][start_date]'); ?>
                      </div>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_site_services[0][no_of_labour]', 'class'=>'form-control', 'value'=>1, 'type'=>'number', 'id'=>'no_of_labour_0']);?>
                      <?php echo form_error('customer_site_services[0][no_of_labour]'); ?>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_site_services[0][night_shift_labour_count]', 'class'=>'form-control', 'value'=>1, 'type'=>'number', 'id'=>'night_shift_labour_count_0']);?>
                      <?php echo form_error('customer_site_services[0][night_shift_labour_count]'); ?>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_site_services[0][no_of_shift]', 'class'=>'form-control', 'value'=>1, 'type'=>'number', 'id'=>'no_of_shift_0']);?>
                      <?php echo form_error('customer_site_services[0][no_of_shift]'); ?>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_site_services[0][cost]', 'class'=>'form-control', 'value'=>0, 'type'=>'number', 'id'=>'cost_0']);?>
                      <?php echo form_error('customer_site_services[0][cost]'); ?>
                    </td>
                    <td></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="7"><button type="button" id="AddMoreService" class="btn btn-info pull-right AddMoreRow">Add More</button>
                    </td>
                  </tr>
                </tfoot>
              </table>
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
