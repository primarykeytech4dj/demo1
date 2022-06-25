<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="row content">
    <div class="col-md-4">
        <div class="login-box">
            <div class="m-title">Email verification resent</div>
            <div class="login-body">
                <?php 
                    echo "<div id='login-container'>";
                    echo "<p>New email verifification link sent to {$email}.</p>";
                    echo "<p>If this email address is incorrect please change it by
                    <a href='". base_url() . custom_constants::change_email_before_ver_url . "' class='blue_anchor'><u>Clicking here</u></a>.</p>";
                    echo "<p><a href='" . base_url() . custom_constants::logout_url . "'>logout</a></p>";
                    echo "</div>";

                    if(defined('custom_constants::main_site_url'))
                    {
                        echo "<div class='login-options'>";
                        echo "<p>";
                        if(defined('custom_constants::main_site_display'))
                        {
                            // Link to the main site with full access
                            echo "<a href='" . custom_constants::main_site_url . "'>" . custom_constants::main_site_display . "</a>";
                        }
                        else
                        {
                            // Link to the main site with full access
                            echo "<a href='" . custom_constants::main_site_url . "'>" . custom_constants::main_site_display . "</a>";
                        }
                        echo "</p>";
                        echo "</div>";
                    }
                ?>
            </div>

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
        </div><!-- end login-box-->
    </div>

    <div class="col-md-8">
        <div id="login-banner"  class="owl-carousel" style="margin-top:2%;"> 
            <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/tree.jpg" class="img-responsive" /> 
            <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/group.jpg" class="img-responsive" /> 
            <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/run.jpg" class="img-responsive" /> 
            <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/road.jpg" class="img-responsive" /> 
            <img src="<?php echo base_url(); ?>assets/oxiinc/img/banner/mobile.jpg" class="img-responsive" /> 
        </div>
    </div>
</div><!--end content-->
<?php //} ?>

