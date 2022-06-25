<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="row content email-verification">
    <div class="col-md-4">
        <div class="login-box">
            <div class="m-title">Email Verification</div>
            <div class="login-body">
                <div id="login-container">
                    <?php
                        if($email_already_verified === TRUE)
                        {
                            echo "<p>You have already verified your account.</p>";
                            echo "<p><a href='" . base_url() . custom_constants::customer_page_url . "' class='blue_anchor'><u>Click here</u></a>
                            to go to the My Account.</p>";
                            echo "<p><a href='" . base_url() . custom_constants::logout_url . "'>logout</a></p>";
                        }
                        else
                        {
                            if($string_entered === FALSE)
                            {
                                echo "<p>Please check your email for the verification link you were sent.</p>";
                                echo "<p>If you need a new verification email sent then please <a href='" . base_url() . custom_constants::new_email_ver_link_url . "' class='blue_anchor'><u>Click here</u></a>.</p>";
                                echo "<p>To change your email address <a href='". base_url() . custom_constants::change_email_before_ver_url . "' class='blue_anchor'><u>Click here</u></a>.</p>";
                                echo "<p><a href='" . base_url() . custom_constants::customer_page_url . "'>My Account</a></p>";
                                echo "<p><a href='" . base_url() . custom_constants::logout_url . "'>logout</a></p>";
                            }
                            else
                            {
                                if($email_verified === TRUE)
                                {
                                    header('Refresh: 5; URL=' . base_url() . custom_constants::customer_page_url);
                                    echo "<p>Thank you. You have successfully verified your account. You now have access to the site.
                                    You will now be redirected to the admin panel.</p>";
                                    echo "<p>Please <a href='" . base_url() . custom_constants::customer_page_url . "' class='blue_anchor'><u>Click here</u></a> if you are not redirected.";
                                }
                                else
                                {
                                    if(isset($new_verify_link) && !empty($new_verify_link))
                                    {
                                        echo $new_verify_link;
                                    }
                                    echo "<p>" . $email_ver_error . "</p>";
                                }
                            }
                        }
                    ?>
                </div>
            </div>

            <?php
                if(!isset($module) && $this->session->flashdata('message') !== FALSE)
                {
                    $msg = $this->session->flashdata('message');
            ?>
                <div class="mt20 <?php echo $msg['class'];?>">
                    <?php echo $msg['message'];?>
                </div>
            <?php } ?>

            <?php
                if(isset($logged_in) && $logged_in == 'yes'){
                    if($this->session->userdata('email_verified') !== TRUE && $this->session->userdata('mobile_verified') !== TRUE){
            ?>
                <div class="login-body">
                    <form class="form-horizontal" action="<?php echo base_url(custom_constants::email_verification_url); ?>" method="post">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Otp</label>
                            <div class="col-sm-9">
                                <input type="text" name="otp" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">        
                            <div class="col-sm-offset-3 col-sm-4"><button type="submit" class="btn btn-default">Submit</button></div>
                            <div class="col-sm-5">
                                <label><a href="<?php echo base_url('login/generateOtp'); ?>">Resend OTP</a></label>
                            </div>
                        </div>
                    </form>
                </div>
            <?php }} ?>
        </div>
    </div>

        <?php /* ?>
        <div class="login-box">
        <div class="c-title">Franchise Login</div>
        <div class="login-body">
        <form class="form-horizontal">
        <div class="form-group">
        <label class="control-label col-sm-3">User Name</label>
        <div class="col-sm-9">
        <input type="text" class="form-control">
        </div>
        </div>
        <div class="form-group">
        <label class="control-label col-sm-3">Password</label>
        <div class="col-sm-9">          
        <input type="password" class="form-control">
        </div>
        </div>
        <div class="form-group">        
        <div class="col-sm-offset-3 col-sm-9">
        <button type="submit" class="btn btn-default">Login</button>
        </div>
        </div>
        <div class="form-group">        
        <div class="col-sm-offset-3 col-sm-9">
        <label><a href="#"> Forgot Password</a></label>
        </div>
        </div>
        </form>
        </div>
        </div><!-- end login-box-->
        <?php */ ?>

</div><!--end content-->
<?php //} ?>