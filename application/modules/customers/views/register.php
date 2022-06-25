
<?php

$input['primary_email'] = [
    'name'      =>  'data[customers][primary_email]',
    'id'        =>  'primary_email',
    'class'     =>  'form-control input-lg check_email',
    'data-link' =>  'login/check_email',
    'required'  =>  'required',
    'type'      =>  'email',
    'placeholder'=> 'Your Email'

];

$input['first_name'] = [
    'name'      =>  'data[customers][first_name]',
    'id'        =>  'first_name',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Your First Name'

];

$input['surname'] = [
    'name'      =>  'data[customers][surname]',
    'id'        =>  'surname',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Your Last Name'

];

$input['contact_1'] = [
    'name'      =>  'data[customers][contact_1]',
    'id'        =>  'contact_1',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Your Contact Number',
    'maxlength' =>  15    
];

$input['password'] = [
    'name'      =>  'data[login][password]',
    'id'        =>  'password',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Your Password',
    'type'      =>  'password'
];

$input['password2'] = [
    'name'      =>  'data[pwd][password2]',
    'id'        =>  'password2',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Confirm Password',
    'type'      =>  'password'
];

$input['company_name'] = [
    'name'      =>  'data[customers][company_name]',
    'id'        =>  'company_name',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Your Company Name',
];

$input['address_1'] = [
    'name'      =>  'data[address][address_1]',
    'id'        =>  'address_1',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Address Line 1',
];

$input['address_2'] = [
    'name'      =>  'data[address][address_2]',
    'id'        =>  'address_2',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Address Line 2',
];

$input['pincode'] = [
    'name'      =>  'data[address][pincode]',
    'id'        =>  'pincode',
    'class'     =>  'form-control input-lg',
    'required'  =>  'required',
    'placeholder'=> 'Your Pincode',
];
$input['captcha'] = array(
                        "name" => "data[captcha]",
                        "placeholder" => "Your Answer *",
                        'required' => 'required',
                        "class"=> "form-control input-lg",
                        "id" => "captcha"
                    );

$country  = array(
                'id' => 'country_id',
                'required'  =>  'required',
                'class' =>  'form-control required select2 filter',
                'data-link' => 'states/getCountrywiseStates',
                'data-target' =>'state_id',
                'input-data-target' =>'country',
                'style' => 'width:100%',
                 /*data-target='faq_category_".$faquesKey."'*/
             );

$state  =   array(
                'id'    =>  'state_id',
                'required'  =>  'required',
                'class' =>  'form-control required select2 input-lg filter',
                'data-link' => 'cities/getStateWiseCities',
                'data-target' => 'city_id',
                'style' => 'width:100%',
                );

$city   =   array(
                'id' => 'city_id',
                'required' => 'required',
                'class' => 'form-control required select2 input-lg filter',
                'data-link' => 'areas/getCityWiseAreas',
                'data-target' => 'area_id',
                'style' => 'width:100%',
            );
$area   =   array(
                'id' => 'area_id',
                'required' => 'required',
                'class' => 'form-control required select2 input-lg',
                'data-target' => 'area_id',
                'style' => 'width:100%',
            );
//echo '<pre>';
if(isset($values_posted))
{ //print_r($values_posted);
    foreach($values_posted['data'] as $post_name => $post_value)
    {
        //print_r($post_value);
        if(is_array($post_value))
        foreach ($post_value as $field_key => $field_value) {
            # code...
            $input[$field_key]['value'] = $field_value;
        }
    }
}
 ?>
<div id="breadcrumb-container">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.html">Home</a></li>
                        <li class="active">Register Account</li>
                    </ul>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <header class="content-title">
                            <h1 class="title">Register Account</h1>
                            <p class="title-desc">If you already have an account, please login at <?=anchor('login', 'Login Page')?></p>
                        </header>
                        <div class="xs-margin"></div>
                        <!-- <form action="#" id="register-form"> -->
                            <?php echo form_open_multipart(custom_constants::register_url, ['id' => 'register_customer']);
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
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <fieldset>
                                        <h2 class="sub-title">YOUR PERSONAL DETAILS</h2>
                                        <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-email"></span><span class="input-text">Email&#42;</span></span>
                                            <?=form_input($input['primary_email'])?>
                                            <?php echo form_error('data[customers][primary_email]'); ?>
                                        </div>
                                        <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text">First Name&#42;</span></span>
                                            <?=form_input($input['first_name'])?>
                                            <?php echo form_error('data[customers][first_name]'); ?>
                                        </div>
                                        <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text">Last Name&#42;</span></span>
                                            <?=form_input($input['surname'])?>
                                            <?php echo form_error('data[customers][surname]'); ?>
                                        </div>
                                        
                                        <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-phone"></span><span class="input-text">Mobile No&#42;</span></span>
                                           <?=form_input($input['contact_1'])?>
                                            <?php echo form_error('data[customers][contact_1]'); ?>
                                        </div>
                                        <!-- <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-fax"></span><span class="input-text">Fax</span></span>
                                            <input type="text" class="form-control input-lg" placeholder="Your Fax">
                                        </div> -->
                                    </fieldset>
                                    <div class="show_password" id="show_password">
                                        <fieldset>
                                            <h2 class="sub-title">YOUR PASSWORD</h2>
                                            <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-password"></span><span class="input-text">Password&#42;</span></span>
                                                <?=form_input($input['password'])?>
                                            <?php echo form_error('data[login][password]'); ?>
                                            </div>
                                            <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-password"></span><span class="input-text">Confirm Password&#42;</span></span>
                                                <?=form_input($input['password2'])?>
                                                <?php echo form_error('data[pwd][password2]'); ?>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <fieldset>
                                        <h2 class="sub-title">YOUR DELIVERY ADDRESS</h2>
                                        <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-company"></span><span class="input-text">Company&#42;</span></span>
                                            <?=form_input($input['company_name'])?>
                                            <?=form_error('data[customers][company_name]')?>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="input-icon input-icon-address"></span>
                                                <span class="input-text">Address 1&#42;</span>
                                            </span>
                                            <?=form_input($input['address_1'])?>
                                            <?=form_error('data[address][address_1]')?>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="input-icon input-icon-address"></span>
                                                <span class="input-text">Address 2&#42;</span>
                                            </span>
                                            <?=form_input($input['address_2'])?>
                                            <?=form_error('data[address][address_2]')?>
                                        </div>
                                        <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-country"></span><span class="input-text">Country*</span></span>
                                            <div class="large-selectbox clearfix">
                                                <?php echo form_dropdown('data[address][country_id]', $option['countries'], set_value('data[address][country_id]'), $country); ?>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="input-icon input-icon-region"></span>
                                                <span class="input-text">Region / State&#42;</span>
                                            </span>
                                            <div class="large-selectbox clearfix">
                                                <?php echo form_dropdown('data[address][state_id]', $option['states'], set_value('data[address][state_id]'), $state); ?>
                                            </div>
                                        </div>
                                        <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-city"></span><span class="input-text">City*</span></span>
                                            <div class="large-selectbox clearfix">
                                                <?php 

                                                echo form_dropdown('data[address][city_id]', $option['cities'], set_value('data[address][city_id]'), $city); ?>
                                            </div>
                                        </div>
                                        <div class="input-group"><span class="input-group-addon"><span class="input-icon input-icon-city"></span><span class="input-text">Area*</span></span>
                                            <div class="large-selectbox clearfix">
                                                <?php echo form_dropdown('data[address][area_id]', $option['areas'], set_value('data[address][area_id]'), $area); ?>
                                                <?=form_error('data[address][area_id]')?>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="input-icon input-icon-postcode"></span>
                                                <span class="input-text">Post Code&#42;</span>
                                            </span>
                                            <?=form_input($input['pincode'])?>
                                            <?=form_error('data[address][pincode]')?>
                                        </div>
                                        
                                        
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <fieldset class="half-margin">
                                        <h2 class="sub-title">NEWSLETTER</h2>
                                        <div class="input-desc-box"><span class="separator icon-box">&plus;</span>I wish to subscribe to the newsletter.</div>
                                        <div class="input-group custom-checkbox">
                                            <input type="checkbox" id="terms-n-condition" name="data['terms']" class="required" required="required"><span class="checbox-container"><i class="fa fa-check"></i></span> I have reed and agree to the <?=anchor('privacy-policy', 'Privacy Policy')?>.</div>
                                    </fieldset>
                                    <input type="submit" value="CREATE MY ACCCOUNT" class="btn btn-custom-2 md-margin">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <fieldset class="half-margin">
                                    <div class="form-group">
                                        <label for="inputcaptcha" class="control-label">Total of <?php echo $customerNum1; ?> + <?php echo $customerNum2; ?> is : </label>
                                        <?php echo form_input($input['captcha']); ?>
                                        <?php echo form_error('data[captcha]'); ?>
                                    </div>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                $('#register_customer').on('submit', function(e){
                    //e.preventDefault();
                    /*$('#terms-n-condition').on('click', function(){
                        if($(this).)
                    })
                    return false;*/
                })
            </script>