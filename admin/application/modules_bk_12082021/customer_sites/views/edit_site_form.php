
<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed ');

$input['customer_sites']['first_name'] = array(
                          "name" => "data[customer_sites][first_name]",
                          "placeholder" => "first name *",
                          "max_length" => "64",
                          "required" => "required",
                          "class" => "form-control",
                          "id" =>"first_name",
                          );

$input['customer_sites']['middle_name'] = array(
                          "name" => "data[customer_sites][middle_name]",
                          "placeholder" => "middle_name",
                          "max_length" => "255",
                           "class" => "form-control",
                           "id" => "middle_name",
                           );

$input['customer_sites']['surname'] = array(
                      "name" => "data[customer_sites][surname]",
                      "placeholder" => "surname *",
                      "max_length" => "64",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "surname",
                    );
$input['customer_sites']['service_charge'] = array(
                      "name" => "data[customer_sites][service_charge]",
                      "placeholder" => "service_charge *",
                      "max_length" => "64",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "service_charge",
                      'value' => '18',
                    );

$input['customer_sites']['primary_email'] = array(
                      "name" => "data[customer_sites][primary_email]",
                      "placeholder" => "primary email",
                      "max_length" => "100",
                      "class" => "form-control",
                      "id" => "primary_email",
                    );

$input['customer_sites']['contact_1'] = array(
                      "name" => "data[customer_sites][contact_1]",
                      "placeholder" => "contact 1",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "contact_1",
                    );

$input['customer_sites']['contact_2'] = array(
                      "name" => "data[customer_sites][contact_2]",
                      "placeholder" => "contact 2",
                      "max_length" => "12",
                      "class" => "form-control",
                      "id" => "contact_2",
                    );

$input['address']['address_1'] =  array(
              "name" => "data[address][address_1]",
              "placeholder" => "address 1",
              "class" => "form-control",
              "id" => "address_1",
              "rows" => "5",
              "tab-index" => 3,
            );

$input['address']['site_name'] =  array(
              "name" => "data[address][site_name]",
              "placeholder" => "Site Name*",
              "required" => "required",
              "class" => "form-control",
              "id" => "site_name",
              "rows" => "5",
            );

$input['address']['address_2'] =  array(
              "name" => "data[address][address_2]",
              "placeholder" => "address 2",
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

$customer  = array(
              'id' => 'country_id',
              'required'  =>  'required',
              'class' =>  'form-control select2',
              "tab-index" => 5,
              'style' => 'width:100%',
            );

$country  = array(
              'id' => 'country_id',
              'required'  =>  'required',
              'class' =>  'form-control select2 filter viewInput',
              'data-link' => 'states/getCountrywiseStates',
              'data-target' =>'state_id',
              "tab-index" => 5,
              'input-data-target' => 'country',
              'style' => 'width:100%',

            );
$state  = array(
            'id'  =>  'state_id',
            'required'  =>  'required',
            'class' =>  'form-control select2 filter viewInput',
            'data-link' => 'cities/getStateWiseCities',
            'data-target' => 'city_id',
            "tab-index" => 6,
            'input-data-target' => 'state',
            'style' => 'width:100%',

          );

$city =   array(
        'id' => 'city_id',
        'required' => 'required',
        'class' => 'form-control select2 filter viewInput',
        'data-link' => 'areas/getCityWiseAreas',
        'data-target' => 'area_id',
        "tab-index" => 7,
        'input-data-target' => 'city',
        'style' => 'width:100%',

      );

$area  = array(
        'id' => 'area_id',
        'required'  =>  'required',
        'class' =>  'form-control select2 filter viewInput',
        "tab-index" => 8,
        'input-data-target' => 'area',
        'style' => 'width:100%',

       );

$input['address']['pincode'] = array(
            "name" => "data[address][pincode]",
            "placeholder" => "pincode*",
            "max_length" => "6",
            "required" => "required",
            "class" => "form-control",
            "id" => "pincode",
            "tab-index" => 9,
          );
/*echo '<pre>';
print_r($values_posted);
echo '</pre>';*/
if(isset($values_posted)) {
  foreach ($values_posted as $post_name => $post_value) {
    //echo $post_name.'<br>';
    foreach ($post_value as $field_key => $field_value) {
      //echo $field_key."<br>";
      $input[$post_name][$field_key]['value'] = $field_value;
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
  <?php
  if($this->session->flashdata('message')!== FALSE) {
        $msg = $this->session->flashdata('message');?>
        <div class="<?php echo $msg['class'];?>">
          <?php echo $msg['message'];?>
        </div>
      <?php } ?>
  <div class="row">
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <?php echo form_open_multipart(custom_constants::edit_site_service_url.'/'.$id, ['class'=>'form-horizontal', 'id' => 'register_customer']);
     ?>
        <input type="hidden" name="formtype" value="courier">
        <input type="hidden" name="url" value="<?php echo !isset($url)?custom_constants::new_courier_site_url:$url; ?>">
        <input type="hidden" name="module" value="<?php echo !isset($module)?'customer_sites':$module; ?>">
        <?php //print_r($values_posted);?>
        <input type="hidden" data[customer_sites][id]" value="<?php echo isset($values_posted['customer_sites']['id'])?$values_posted['customer_sites']['id']:0; ?>">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title text-center">Edit Site Details</h3>
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <?php if(isset($values_posted['customer_sites']['id'])){
                      echo '<input type="hidden" name="data[customer_sites][id]" value="'.$values_posted['customer_sites']['id'].'">';
                    } ?>
                  <label for="inputSite_name" class="col-sm-2 control-label">Site Name</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['address']['site_name']);?>
                  <?php echo form_error('data[address][site_name]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputFirstName" class="col-sm-2 control-label">Receiver First Name</label>
                  <div class="col-sm-10">
                   <?php echo form_input($input['customer_sites']['first_name']);?>
                   <?php echo form_error('data[customer_sites][first_name]');?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputMiddleName" class="col-sm-2 control-label">Receiver Middle Name</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['customer_sites']['middle_name']);?>
                  <?php echo form_error('data[customer_sites][middle_name]'); ?>
                  </div>
                </div>
              </div>
                <div class="col-md-6">
                <div class="form-group">
                  <label for="inputSurName" class="col-sm-2 control-label">Receiver Last Name</label>
                  <div class="col-sm-10">
                <?php echo form_input($input['customer_sites']['surname']);?>
                <?php echo form_error('data[customer_sites][surname]'); ?>
                  </div>
                </div>
              </div>

            </div><!-- /row -->

            <div class="row">
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputAddress" class="col-sm-2 control-label">Receiver Email ID</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['customer_sites']['primary_email']);?>
                  <?php echo form_error('data[customer_sites][primary_email]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputContact1" class="col-sm-2 control-label">Receiver Contact 1</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['customer_sites']['contact_1']);?>
                    <?php echo form_error('data[customer_sites][contact_1]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                   <label for="inputContact2" class="col-sm-2 control-label">Receiver Contact 2</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['customer_sites']['contact_2']);?>
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
                    <?php echo form_input($input['customer_sites']['service_charge']);?>
                    <?php echo form_error('data[customer_sites][service_charge]'); ?>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <h3 align="center"><b>Courier Delivery Address</b></h3>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address_1" class="col-sm-2 control-label">Address 1</label>
                  <div class="col-sm-10">
                    <?php if(isset($values_posted['address']['id'])){
                      echo '<input type="hidden" name="data[address][id]" value="'.$values_posted['address']['id'].'">';
                    } ?>
                    <?php echo form_textarea($input['address']['address_1']); ?>
                    <?php echo form_error('data[address][address_1]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address_2" class="col-sm-2 control-label">Address 2</label>
                  <div class="col-sm-10">
                    <?php echo form_textarea($input['address']['address_2']); ?>
                    <?php echo form_error('data[address][address_2]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="country_id" class="col-sm-2 control-label">Country</label>
                  <div class="col-sm-10">
                      <?php echo form_dropdown('data[address][country_id]', $option['countries'], isset($values_posted['address']['country_id'])?$values_posted['address']['country_id']:'', $country); ?>
                      <input type="text" name="data[countries][name]" class="form-control input" id="country" placeholder="Country Not found? Enter here" value="<?php isset($values_posted['address']['country_id'])?$values_posted['address']['country_id']:'';?>" style="display: <?php echo (empty($values_posted['address']['country_id']) || $values_posted['address']['country_id']==0)?'block':'none'; ?>">
                      <?php echo form_error('data[address][country_id]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="state_id" class="col-sm-2 control-label">State</label>
                    <div class="col-sm-10">
                      
                      <?php echo form_dropdown('data[address][state_id]',$option['states'], isset($values_posted['address']['state_id'])?$values_posted['address']['state_id']:'',$state);?>
                      <input type="text" name="data[states][state_name]" class="form-control input" id="state" style="display: <?php echo (empty($values_posted['address']['state_id']) || $values_posted['address']['state_id']==0)?'block':'none'; ?>" placeholder="State Is Not Listed. Enter here" value="<?php isset($values_posted['address']['state_id'])?$values_posted['address']['state_id']:'';?>">
                        <?php echo form_error('data[address][state_id]'); ?>
                    </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="city_id" class="col-sm-2 control-label">City</label>
                  <div class="col-sm-10">
                    <?php echo form_dropdown('data[address][city_id]', $option['cities'], isset($values_posted['address']['city_id'])?$values_posted['address']['city_id']:'', $city); ?>
                    <input type="text" name="data[cities][city_name]" class="form-control input" id="city" style="display: <?php echo (empty($values_posted['address']['city_id']) || $values_posted['address']['city_id']==0)?'block':'none'; ?>" placeholder="City Is Not Listed. Enter here" value="<?php isset($values_posted['address']['city_id'])?$values_posted['address']['city_id']:'';?>">
                    <?php echo form_error('data[address][city_id]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="area_id" class="col-sm-2 control-label">Area</label>
                  <div class="col-sm-10">
                    <?php echo form_dropdown('data[address][area_id]', $option['areas'], isset($values_posted['address']['area_id'])?$values_posted['address']['area_id']:'', $area); ?>
                    <input type="text" name="data[areas][area_name]" class="form-control input" id="area" style="display: <?php echo (empty($values_posted['address']['area_id']) || $values_posted['address']['area_id']==0)?'block':'none'; ?>" placeholder="State Is Not Listed. Enter here" value="<?php isset($values_posted['address']['area_id'])?$values_posted['address']['area_id']:'';?>">
                    <?php echo form_error('data[address][area_id]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="pincode" class="col-sm-2 control-label">Pincode</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['address']['pincode']); ?>
                    <?php echo form_error('data[address][pincode]'); ?>
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
                    <th>Date</th>
                    <th>Consignment No /Tracking No</th>
                    <th>Weight(kg)</th>
                    <th>Mode</th>
                    <th>Reference No</th>
                    <th>Rate</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr id="0">   
                    
                    <td><input type="hidden" name="customer_site_services[0][id]" value="<?php echo $id;?>" id="id_0">
                      <?php echo form_dropdown('customer_site_services[0][product_id]', $option['services'], isset($values_posted['siteServiceDetails']['product_id'])?$values_posted['siteServiceDetails']['product_id']:'', ['id'=>'product_id_0', 'class'=>'form-control select2', 'required'=>'required']); ?>
                    </td>
                    <td>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      <?php echo form_input(['name'=>'customer_site_services[0][start_date]', 'class'=>'col-xs-3 form-control datepicker datemask maxCurrentDate', 'id'=>'start_date_0', 'value'=> date('d/m/Y', strtotime($values_posted['siteServiceDetails']['start_date'])),
                        'required'=>'required'
                        //'max'=>date('d/m/Y')
                    ]);?>
                      <?php echo form_error('customer_site_services[0][start_date]'); ?>
                      </div>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_site_services[0][consign_no]', 'class'=>'form-control', 'type'=>'number', 'id'=>'consign_no_0', 'required'=>'required','value'=>isset($values_posted['siteServiceDetails']['consign_no'])?$values_posted['siteServiceDetails']['consign_no']:'']);?>
                      <?php echo form_error('customer_site_services[0][consign_no]'); ?>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_site_services[0][weight]', 'class'=>'form-control', 'type'=>'text', 'id'=>'weight_0', 'required'=>'required'], isset($values_posted['siteServiceDetails']['weight'])?$values_posted['siteServiceDetails']['weight']:'');?>
                      <?php echo form_error('customer_site_services[0][weight]'); ?>
                    </td>
                    <td>
                      <?php echo form_dropdown('customer_site_services[0][mode]', [''=>'Select Mode', 'surface'=>'Surface', 'air'=>'Air'], /*set_value('customer_site_services[0][mode]')*/isset($values_posted['siteServiceDetails']['mode'])?$values_posted['siteServiceDetails']['mode']:'', ['class'=>'form-control select2', 'type'=>'number', 'id'=>'no_of_shift_0', 'required'=>'required']);?>
                      <?php echo form_error('customer_site_services[0][mode]'); ?>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_site_services[0][ref_no]', 'class'=>'form-control', 'id'=>'ref_no_0', 'value'=>isset($values_posted['siteServiceDetails']['ref_no'])?$values_posted['siteServiceDetails']['ref_no']:'']);?>
                      <?php echo form_error('customer_site_services[0][ref_no]'); ?>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_site_services[0][cost]', 'class'=>'form-control', 'value'=>isset($values_posted['siteServiceDetails']['cost'])?$values_posted['siteServiceDetails']['cost']:'', 'id'=>'cost_0', 'required'=>'required']);?>
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
