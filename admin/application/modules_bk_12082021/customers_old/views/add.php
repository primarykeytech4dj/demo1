<?php
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
                          "placeholder" => "middle name(s)",
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

$input['username'] = array(
                      "name" => "data[login][username]",
                      "placeholder" => "User Name *",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "username",
                    );

$input['primary_email'] = array(
                      "name" => "data[customers][primary_email]",
                      "placeholder" => "Email ID(s) *",
                      "max_length" => "100",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "primary_email",
                    );

$input['contact_1'] = array(
                      "name" => "data[customers][contact_1]",
                      "placeholder" => "Mobile Number *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "contact_1",
                    );
$input['password'] = array(
                      "name" => "data[login][password]",
                      "placeholder" => "Password *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "password",
                      'type' => 'password'
                    );

$input['repassword'] = array(
                      "name" => "data[repassword][repassword]",
                      "placeholder" => "Password *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "repassword",
                      'type' => 'password'
                    );

$input['profile_img'] =  array(
              "name" => "profile_img",
              "placeholder" => "profile_img *",
              "class" => "form-control",
              "id" => "profile_img",
              "value" =>  set_value('profile_img'),
               );
$country  = array(
        'id' => 'country_id',
        'required'  =>  'required',
        'class' =>  'form-control select2 filter',
        'data-link' => 'states/getCountrywiseStates',
        'data-target' =>'state_id',
        'style' => 'width:100%',
       );

$state  = array(
        'id'  =>  'state_id',
        'required'  =>  'required',
        'class' =>  'form-control select2 filter',
        'data-link' => 'cities/getStateWiseCities',
        'data-target' => 'city_id',
        'style' => 'width:100%',
        );

$city =   array(
        'id' => 'city_id',
        'required' => 'required',
        'class' => 'form-control select2 filter',
        'data-link' => 'customers/getCityWiseCustomers',
        'data-target' => 'introducer_id',
        'style' => 'width:100%',
      );
if(isset($_GET['sponsor_id']) && !empty($_GET['sponsor_id']))
{
  $introducer  = array(
          'id' => 'introducer_id',
          'required'  =>  'required',
          'class' =>  'form-control select2 viewInput',
          'style' => 'width:100%;pointer-events:none;',
          'readonly' => 'readonly',
          'data-target' => 'proposer_id'
         );
}
else
{
  $introducer  = array(
        'id' => 'introducer_id',
        'required'  =>  'required',
        'class' =>  'form-control select2 viewInput',
        'style' => 'width:100%',
        'data-target' => 'proposer_id'
       );
}
$input['captcha'] = array(
            "name" => "data[captcha]",
            "placeholder" => "Your Answer *",
            'required' => 'required',
            "class"=> "form-control",
            "id" => "captcha"
          );
/*echo '<pre>';
print_r($values_posted);
echo '</pre>';*/
if(isset($values_posted)) {
  foreach ($values_posted as $post_name => $post_value) {
    //print_r($post_value);
    if(is_array($post_value))
    foreach ($post_value as $field_key => $field_value) {
      //print_r($field_key);
      $input[$field_key]['value'] = $field_value;
    }else { //echo $post_value;
      $input[$post_name]['value'] = $post_value;

    }
  }
}

?>
<!-- <div class="col-md-12">

    <ul class="breadcrumb">
        <li><a href="#">Home</a>
        </li>
        <li>New account / Sign up</li>
    </ul>

</div> -->
<div class="container">
  <div class="col-sm-12">
    
    <div class="box">
        <?php echo form_open_multipart(custom_constants::register_url, ['class'=>'form-horizontal', 'id' => 'customers']);
        //print_r($this->session->flashdata('message'));
        if($this->session->flashdata('message')!== FALSE) {
          $msg = $this->session->flashdata('message');?>
          <div class="<?php echo $msg['class'];?>">
            <?php echo $msg['message'];?>
          </div>
        <?php } ?>

        <div class="box box-info">
          <div class="box-header with-border">
          	<h2 style="text-align: center;font-size:30px;padding:2%; color:red;">Registration</h2>

          </div><!-- /box-header -->
          <div class="errormessage">
        
          </div>
          <!-- form start -->
          <div class="box-body">
            <div class="form-group row">
              <label for="country_id" class="col-sm-2 col-form-label">Country:</label>
               <div class="col-sm-10">
                <?php 
                $countryId = 0;
                if(set_value('data[address][country_id]'))
                  $countryId = $values_posted['address']['country_id'];
                echo form_dropdown('data[address][country_id]', $option['countries'], $countryId, $country); ?>
                <?php echo form_error('data[address][country_id]'); ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="state_id" class="col-sm-2 col-form-label">State:</label>
               <div class="col-sm-10">
                <?php 
                $stateId = 0;
                if(set_value('data[address][state_id]'))
                  $stateId = $values_posted['address']['state_id'];
                echo form_dropdown('data[address][state_id]', $option['states'], $stateId, $state); ?>
                <?php echo form_error('data[address][state_id]'); ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="city_id" class="col-sm-2 col-form-label">City:</label>
               <div class="col-sm-10">
                <?php 
                $cityId = 0;
                if(set_value('data[address][city_id]'))
                  $cityId = $values_posted['address']['city_id'];
                echo form_dropdown('data[address][city_id]', $option['cities'], $cityId, $city); ?>
                <?php echo form_error('data[address][city_id]'); ?>
              </div>
            </div>
            <div class="form-group ">
              <label for="introducer_id" class="col-sm-2 col-form-label">Proposer Id:</label>
              <div class="col-sm-4">
              <?php 
              $introducerId = 0;
              // print_r($option['introducers']);
              if(set_value('data[customer_references][introducer_id]'))
                $introducerId = $values_posted['customer_references']['introducer_id'];
              echo form_dropdown('data[customer_references][introducer_id]', $option['introducers'], $introducerId, $introducer); ?>

              <input type="text" name="data[proposer_id]" class="form-control" id="proposer_id" <?php if(isset($introducerId) && $introducerId > 0){ echo 'style="display:none;"'; }else{ echo 'style="display:block;"'; } ?> placeholder="Proposer Not found? Enter Proposer ID Here" value="<?php if(isset($_POST['data']['proposer_id']) && !empty($_POST['data']['proposer_id'])){ echo $_POST['data']['proposer_id']; }else if(isset($_GET['sponsor_id']) && !empty($_GET['sponsor_id'])){ echo $_GET['sponsor_id']; } ?>" <?php if(isset($_GET['sponsor_id']) && !empty($_GET['sponsor_id'])){ echo 'readonly="readonly"'; } ?>>
              <?php echo form_error('data[customer_references][introducer_id]'); ?>
            </div>
                 <!--  </div>
            <div class="form-group row"> -->
              <!-- <label for="example-search-input" class="col-sm-2 col-form-label">Enter New Id:</label>
              <div class="col-sm-4">
                <input class="form-control" type="search" value="" id="example-search-input">
              </div> -->
              <label for="proposer_name" class="col-sm-2 col-form-label">Proposer Name:</label>
              <div class="col-sm-4">
                <input class="form-control" type="text" name="data[proposer_name]" placeholder="Proposer name will be displayed here" id="proposer_name" readonly="readonly" value="<?php if(isset($_POST['data']['proposer_name']) && !empty($_POST['data']['proposer_name'])){ echo $_POST['data']['proposer_name']; } ?>">
                
              </div>
            </div>
            
            <!-- <div class="form-group row">
              <label for="first_name" class="col-sm-2 col-form-label">Proposer Name:</label>
              <div class="col-sm-10">
                <?php echo form_input($input['first_name']);?>
                <?php echo form_error('data[customers][first_name]');?>
              </div>
            </div> -->
            <div class="form-group row">
              <label for="username" class="col-sm-2 col-form-label">e-Panelist User Id:</label>
              <div class="col-sm-10">
                <?php echo form_input($input['username']);?>
                <?php echo form_error('data[login][username]');?>
              </div>
            </div>
            <div class="form-group row">
              <label for="example-tel-input" class="col-sm-2 col-form-label">e-Panelist Name:</label>
              <div class="col-sm-3">
                <?php echo form_input($input['first_name']);?>
                <?php echo form_error('data[customers][first_name]');?>
              </div>
              <div class="col-sm-3">
                <?php echo form_input($input['middle_name']);?>
                <?php echo form_error('data[customers][middle_name]');?>
              </div>
              <div class="col-sm-4">
                <?php echo form_input($input['surname']);?>
                <?php echo form_error('data[customers][surname]');?>
              </div>
            </div>
            <div class="form-group row">
              <label for="gender" class="col-sm-2 col-form-label">Gender:</label>
              <div class="col-sm-10">
                <?php 
                $gender = '';
                if(set_value('data[customers][gender]'))
                  $gender = $values_posted['customers']['gender'];
                 ?>
                 <select name="data[customers][gender]" id="gender" class="form-control" required="required">
                  <option value="" <?php if($gender==''){ echo 'selected="selected"';} ?>>Select Gender</option>
                  <option value="male" <?php if($gender=='male'){ echo 'selected="selected"';} ?>>Male</option>
                  <option value="female" <?php if($gender=='female'){ echo 'selected="selected"';} ?>>Female</option>
                  <option value="transgender" <?php if($gender=='transgender'){ echo 'selected="selected"';} ?>>Transgender</option>
                 </select>
                 <?php echo form_error('data[customers][gender]');?>
                <!-- <label class="radio-inline"><input type="radio" name="data[customers][gender]" value="male" <?php //echo  set_radio('data[customers][gender]', 'male', TRUE); ?>>Male</label>
                <label class="radio-inline"><input type="radio" name="data[customers][gender]" value="female" <?php //echo  set_radio('data[customers][gender]', 'female', TRUE); ?>>Female</label> -->
              </div>
            </div>

            <div class="form-group row">
              <label for="placement" class="col-sm-2 col-form-label">Your Position:</label>
              <div class="col-sm-10">
                <?php 
                $placement = '';
                if(set_value('data[person_under][placement]'))
                  $placement = $values_posted['person_under']['placement'];
                 ?>
               <!-- <label class="radio-inline">
                <input type="radio" name="data[person_under][placement]" value="left" <?php //echo  set_radio('data[person_under][placement]', 'left', TRUE); ?> required>Left
               </label>
               <label class="radio-inline">
                <input type="radio" name="data[person_under][placement]" value="right" <?php //echo  set_radio('data[person_under][placement]', 'right', TRUE); ?> required>Right
               </label> -->
                <select name="data[person_under][placement]" id="placement" class="form-control" required="required" <?php if(isset($_GET['position']) && !empty($_GET['position'])){ echo 'readonly'.' '.'style="pointer-events: none"'; } ?>>
                 <option value="" <?php if($placement==''){ echo 'selected="selected"';} ?>>Select Position</option>
                 <option value="left" <?php if($placement=='left'){ echo 'selected="selected"';}else if(isset($_GET['position']) && $_GET['position'] == "left"){ echo 'selected="selected"'; } ?>>Left</option>
                 <option value="right" <?php if($placement=='right'){ echo 'selected="selected"';}else if(isset($_GET['position']) && $_GET['position'] == "right"){ echo 'selected="selected"'; } ?>>Right</option>
               </select>
               <?php echo form_error('data[person_under][placement]');?>
              </div>
            </div>
            <div class="form-group row">
              <label for="contact_1" class="col-sm-2 col-form-label">e-Panelist Mobile No:</label>
              <div class="col-sm-10">
                <?php echo form_input($input['contact_1']);?>
                <?php echo form_error('data[customers][contact_1]');?>
              </div>
            </div>
            <div class="form-group row">
              <label for="primary_email" class="col-sm-2 col-form-label">e-Panelist Email ID:</label>
              <div class="col-sm-10">
                <?php echo form_input($input['primary_email']);?>
                <?php echo form_error('data[customers][primary_email]');?>
              </div>
            </div>

            <div class="form-group row">
              <label for="example-datetime-local-input" class="col-sm-2 col-form-label">Password :</label>
              <div class="col-sm-10">
                <?php echo form_input($input['password']);?>
                <?php echo form_error('data[login][password]');?>
              </div>
            </div>

            <div class="form-group row">
              <label for="example-datetime-local-input" class="col-sm-2 col-form-label">Confirm-Password:</label>
              <div class="col-sm-10">
                <?php echo form_input($input['repassword']);?>
                <?php echo form_error('data[repassword][repassword]');?>
              </div>
            </div>                        
        </div><!-- /box-body -->
        <div class="form-group row">
          <label for="inputcaptcha" class="col-sm-2 col-form-label">Total of <?php echo $num1; ?> + <?php echo $num2; ?> is :</label>
          
          
          <div class="col-sm-10">
            <?php echo form_input($input['captcha']); ?>
            <?php echo form_error('data[captcha]'); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="example-week-input" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            <label class="checkbox-inline">
              <input type="checkbox" value="1" required="required" name="terms" <?php if(isset($_POST['terms']) && $_POST['terms'] == 1){ echo "checked"; } ?>>I agree term of services, Please Register.<a href='#' style='color:black;text-decoration: none;'>Terms & Condition</a>
            </label>
          </div>
        </div>
       
        <div class="box-footer">
          <div class="form-group row">
          <label for="example-color-input" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            
            <button type="new_customer" class="btn" id="Save" style="background-color:#3E4095; color:white;" >Submit</button>
            <?php echo anchor('login', 'Login', ['class'=>'btn', 'style'=>'background-color:#3E4095; color:white;']) ?>
            
          </div>
        </div>  
          
        </div>
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

