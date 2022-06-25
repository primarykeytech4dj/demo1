<?php
if(isset($values_posted))
{ //print_r($values_posted);
    foreach($values_posted as $post_name => $post_value)
    {
        foreach ($post_value as $field_key => $field_value) {
            # code...
            $input[$field_key]['value'] = $field_value;
        }
    }
}
 ?>
<div class="container">
    <div>
        <div id="main">
            <!-- <h1 class="page-header">Login</h1> -->
            <div class="login-register">
    <div class="row">
        <div class="span4">
            <ul class="tabs nav nav-tabs">
                <li class="<?php if($tab=="login"){echo "active";} ?>"><a href="#login">Login</a></li>
                <!-- <li ><a href="#register">Register</a></li> -->
            </ul>
            <!-- /.nav -->

            <div class="tab-content">
                <div class="tab-pane <?php if($tab=="login"){echo "active";} ?>" id="login">
<?php //echo "hiiiooo";exit;?>
                    <?php $this->load->view('login/login'); 
                    //echo modules::run('login');?>
                </div>
                <!-- /.tab-pane -->

                <!-- <div class="tab-pane <?php if($tab=="register"){echo "active";} ?>" id="register">
                        <?php echo form_open_multipart('vendors/register2', ['class'=>'form-vertical', 'id' => 'register_customer']);
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
                        <div class="control-group">
                            <label class="control-label" for="inputRegisterFirstName">
                                First name
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="text" id="inputRegisterFirstName" name="data[vendors][first_name]">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="inputRegisterSurname">
                                Surname
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="text" id="inputRegisterSurname" name="data[vendors][surname]">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="inputRegisterEmail">
                                E-mail
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="text" id="inputRegisterEmail" name="data[vendors][primary_email]">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="inputRegisterPassword">
                                Password
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="password" id="inputRegisterPassword" name="data[login][password]">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="inputRegisterRetype">
                                Retype
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="password" id="inputRegisterRetype" name="data[vendor][password2]">
                            </div>
                        </div>

                        <div class="form-actions">
                            <input type="submit" value="Register" class="btn btn-primary arrow-right">
                        </div>
                    </form>
                </div> -->
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.span4-->
        <?php //$this->load->view('login/login_right_content');?>
        
    </div>
</div><!-- /.login-register -->        </div>
    </div>
</div>