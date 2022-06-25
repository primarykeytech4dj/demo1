
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
            <h1 class="page-header">Registration</h1>
            <div class="login-register">
    <div class="row">
        <div class="span4">
            <ul class="tabs nav nav-tabs">
                <li class="<?php if($tab=="login"){echo "active";} ?>"><a href="#login">Login</a></li>
                <li class="<?php if($tab=="register"){echo "active";} ?>"><a href="#register">Register</a></li>
            </ul>
            <!-- /.nav -->

            <div class="tab-content">
                <div class="tab-pane" id="login">
                    <?php $this->load->view('login/login'); ?>
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane active" id="register">
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
                            <!-- /.controls -->
                        </div>
                        <!-- /.control-group -->

                        <div class="control-group">
                            <label class="control-label" for="inputRegisterSurname">
                                Surname
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="text" id="inputRegisterSurname" name="data[vendors][surname]">
                            </div>
                            <!-- /.controls -->
                        </div>
                        <!-- /.control-group -->

                        <div class="control-group">
                            <label class="control-label" for="inputRegisterEmail">
                                E-mail
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="text" id="inputRegisterEmail" name="data[vendors][primary_email]">
                            </div>
                            <!-- /.controls -->
                        </div>
                        <!-- /.control-group -->

                        <div class="control-group">
                            <label class="control-label" for="inputRegisterPassword">
                                Password
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="password" id="inputRegisterPassword" name="data[login][password]">
                            </div>
                            <!-- /.controls -->
                        </div>
                        <!-- /.control-group -->

                        <div class="control-group">
                            <label class="control-label" for="inputRegisterRetype">
                                Retype
                                <span class="form-required" title="This field is required.">*</span>
                            </label>

                            <div class="controls">
                                <input type="password" id="inputRegisterRetype" name="data[vendor][password2]">
                            </div>
                            <!-- /.controls -->
                        </div>
                        <!-- /.control-group -->

                        <div class="form-actions">
                            <input type="submit" value="Register" class="btn btn-primary arrow-right">
                        </div>
                        <!-- /.form-actions -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.span4-->
        <?php $this->load->view('login/login_right_content');?>
        <!-- <div class="span8">
            <h2 class="page-header">Why to work with us?</h2>

            <div class="images row">
                <div class="item span2">
                    <img src="assets/img/icons/circle-dollar.png" alt="">

                    <h3>Cheap services</h3>
                </div>
                <div class="item span2">
                    <img src="assets/img/icons/circle-search.png" alt="">

                    <h3>Easy to find you</h3>
                </div>
                <div class="item span2">
                    <img src="assets/img/icons/circle-global.png" alt="">

                    <h3>Act global or local</h3>
                </div>
                <div class="item span2">
                    <img src="assets/img/icons/circle-package.png" alt="">

                    <h3>All in one package</h3>
                </div>
            </div>

            <hr class="dotted">

            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ullamcorper libero sed ante auctor vel
                gravida nunc placerat. Suspendisse molestie posuere sem, in viverra dolor venenatis sit amet. Aliquam
                gravida nibh quis justo pulvinar luctus. Phasellus a malesuada massa.
            </p>

            <ul class="unstyled dotted">
                <li>
                                        <span class="inner">
                                            <strong>Lorem ipsum dolor sit amet</strong><br>
                                                Consectetur adipiscing elit. Proin aliquam lorem sed urna viverra
                                                accumsan. Aliquam sit amet dui et diam rutrum aliquet. Sed vulputate,
                                                arcu vitae aliquet facilisis, ligula sem posuere nisl, sit amet pretium
                                                ligula dolor
                                        </span>
                </li>

                <li>
                                        <span class="inner">
                                            <strong>Lorem ipsum dolor sit amet</strong><br>
                                                Consectetur adipiscing elit. Proin aliquam lorem sed urna viverra
                                                accumsan. Aliquam sit amet dui et diam rutrum aliquet. Sed vulputate,
                                                arcu vitae aliquet facilisis, ligula sem posuere nisl, sit amet pretium
                                                ligula dolor
                                        </span>
                </li>

                <li>
                                        <span class="inner">
                                            <strong>Lorem ipsum dolor sit amet</strong><br>
                                                Consectetur adipiscing elit. Proin aliquam lorem sed urna viverra
                                                accumsan. Aliquam sit amet dui et diam rutrum aliquet. Sed vulputate,
                                                arcu vitae aliquet facilisis, ligula sem posuere nisl, sit amet pretium
                                                ligula dolor
                                        </span>
                </li>
            </ul>
        </div> -->
    </div>
</div><!-- /.login-register -->        </div>
    </div>
</div>