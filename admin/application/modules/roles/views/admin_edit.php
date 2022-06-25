<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
//echo $values_posted['role_details']['id'];
$input['role_name'] = array(
            "name" => "data[roles][role_name]",
            "placeholder" => "Name(s) *",
            "max_length" => "64",
            "required" => "required",
            "class"=> "form-control",
            "id" => "role_name",
          );

$input['role_code'] = array(
            "name" => "data[roles][role_code]",
            "placeholder" => "Role Code *",
            "max_length" => "64",
            "class" => "form-control",
            'id' => "role_code"
          );

$input['is_view'] = array(
          "name" => "data[role_details][is_view]",
          "class" => "flat-red",
          "id" => "is_view",
          "type"=> "checkbox",
          "value" => true,
          );

$input['is_add']  = array(
          "name" => "data[role_details][is_add]",
          "class" => "flat-red",
          "id" => "is_add",
          "type" => "checkbox",
          "value" => true,
        );

$input['is_update']  = array(
          "name" => "data[role_details][is_update]",
          "class" => "flat-red",
          "id" => "is_update",
          "type" => "checkbox",
          "value" => true,
        );

$input['is_delete']  = array(
          "name" => "data[role_details][is_delete]",
          "class" => "flat-red",
          "id" => "is_delete",
          "type" => "checkbox",
          "value" => true,
        );


// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ 
  //print_r($values_posted);

  foreach($values_posted as $post_name => $post_value)
  { //echo '</pre>';print_r($post_value);echo '</pre>';
    foreach ($post_value as $field_key => $field_value) {
      if(isset($input[$field_key]['type']) && $input[$field_key]['type']=="checkbox" && $field_value==true){
        $input[$field_key]['checked'] = "checked";
      }else{
        $input[$field_key]['value'] = $field_value;
      }
    }
  }
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
      Module :: Roles
  </h1>
  <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li>
        <?php echo anchor(custom_constants::admin_role_listing_url, 'Role', ['title'=>'Role']); ?>
      </li>
      <li>
        <?php echo anchor(custom_constants::new_role_url, 'New Role'); ?>
      </li>
  </ol>
</section>
<!--Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="<?php if($tab=="role"){echo "active";} ?>"><a href="#role" data-toggle="tab">Role Info</a></li>
          <li class="<?php if($tab=="menus"){echo "active";} ?>"><a href="#menus" data-toggle="tab">Menu Allocation</a></li>
        </ul> 
        <div class="tab-content">
          <div class="tab-pane <?php if($tab=="role"){echo "active";} ?>" id="role">
            <?php //echo form_open_multipart(custom_constants::new_user_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
              //print_r($this->session);
            echo form_open_multipart(custom_constants::edit_role_url."/".$id, ['class'=>'form-horizontal', 'id'=>'new_role']);
              
              if($this->session->flashdata('message') !== FALSE) {
                $msg = $this->session->flashdata('message');?>
                <div class = "<?php echo $msg['class'];?>">
                  <?php echo $msg['message'];?>
                </div>
              <?php } ?>
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i>Existing Role</h3>
                </div><!-- /box-header -->
                <!-- form start -->
                <div class="box-body">
                  
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputRole" class="col-sm-2 control-label">Module</label>
                        <div class="col-sm-10">
                          <?php echo form_dropdown("data[roles][module]", $option['module'], isset($values_posted['roles']['module'])?$values_posted['roles']['module']:'',"id='module' required='required' class='form-control select2'"); ?>
                          <?php echo form_error('data[roles][module]'); ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputRole" class="col-sm-2 control-label">Role</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['role_name']); ?>
                          <?php echo form_error('data[roles][role_name]'); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputCode" class="col-sm-2 control-label">Code</label>
                        <div class="col-sm-10">
                                    <?php echo form_input($input['role_code']);?>
                          <?php echo form_error('data[roles][role_code]');?>
                                </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="box-header with-border">
                     <h3 class="box-title"></i>Role Details</h3>
                  </div><!-- /box-header -->
                  <div class="row">
                  	<div class="col-md-6">
                      <div class="form-group">
                      	<input type="hidden" name="data[role_details][id]" id="role_detail_0" class="form-control" value="<?php echo $values_posted['role_details']['id'];?>">
                      </div>
                  </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputIsView" class="col-sm-2 control-label">View</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['is_view']);?>
                          <?php echo form_error('data[role_details][is_view]');?>
                        </div>
                      </div>
                    </div>
                   <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputIsAdd" class="col-sm-2 control-label">Add</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['is_add']);?>
                          <?php echo form_error('data[role_details][is_add]');?>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                  <div class="row">
                    
                   <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputIsUpdate" class="col-sm-2 control-label">Update</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['is_update']);?>
                          <?php echo form_error('data[role_details][is_update]');?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputIsDelete" class="col-sm-2 control-label">Delete</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['is_delete']);?>
                          <?php echo form_error('data[role_details][is_delete]');?>
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="box-footer">  
                  <button type="new_college" class="btn btn-info pull-left">Update Role</button> &nbsp;&nbsp;&nbsp;&nbsp;
                  
                </div>
                <!-- /.box-footer -->
              </div><!-- /box -->
              </div>
            <?php echo form_close(); ?> 
          </div>
          <div class="tab-pane <?php if($tab=="menus"){echo "active";} ?>" id="menus">
            
          </div>
          
          
        </div><!-- /tab-content -->
      </div><!-- end of nav tab -->
    </div><!-- col-md-12 -->
  </div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

