<div class="tab-content">
    <div class="tab-pane <?php /*if($tab=="login"){echo "active";}*/ ?>" id="login"> 
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <?php echo form_open_multipart(custom_constants::login_page_url, ['class'=>'form-horizontal', 'id'=>'login_user']); ?>
          <?php 
          //print_r($this->session);
            if($this->session->flashdata('message') !== FALSE) {
            $msg = $this->session->flashdata('message');?>
            <div class = "<?php echo $msg['class'];?>">
              <?php echo $msg['message'];?>
            </div>
            <?php } ?>
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Login</h3>
              </div><!-- /box-header -->
              <!-- form start -->
              <div class="box-body">
                <?php if(isset($err)){ ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $this->session->flashdata('err'); ?>
                </div>
                <?php } ?>

                
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">                          
                            <label for="inputuserName" class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10">
                           <?php echo form_input($input['username']); ?>
                          <?php echo form_error('username'); ?>
                            </div>
                          </div>
                        </div>
                        
                      </div><!-- /row -->

                      <div class="row">
                        <!-- <div class="col-md-6">
                          
                        </div> -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="inputPassword" class="col-sm-2 control-label">Password.</label>
                            <div class="col-sm-10">
                               <?php echo form_input($input['password']);?>
                              <?php echo form_error('password'); ?>
                            </div>
                          </div>
                        </div>
                      </div><!-- /row -->

                      
                     

                        </div>     
                      </div><!-- /row -->                       <!-- s -->                  
                      <div class="box-footer">  
                    <button type="new_college" class="btn btn-info pull-left">Edit</button>
                    <?php //echo nbs(3); ?>
                    <button type="submit" class="btn btn-info">cancel</button>
                  </div>
                  <!-- /.box-footer -->
                </div><!-- /box -->
                <?php echo form_close(); ?>
              <!-- <hr>      -->   
                
              <!-- </div>
            </div> -->
          </div> <!-- /tab-pane-->
         
           
          </div> <!-- /tab-pane-->
          </div> <!-- /tab-pane -->