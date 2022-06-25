
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=$title?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="admin"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active"><?=$heading?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?=$heading?></h3>
              <?php echo anchor(custom_constants::new_vendor_url, 'New Vendor', 'class="btn btn-primary pull-right"'); ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php //print_r($this->session->userdata); ?>
              <table id="example2" class="table table-bordered table-striped example2">
                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>Profile Image</th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Primary / Secondary Email</th>
                    <th>contact 1 / Contact 2</th>
                    <th>Blood Group</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  //print_r($vendors);
                  foreach($vendors as $key => $vendor) {?>
                <tr>
                  <td><?php echo $vendor['id'] ;?></td>
                  <td><img src="<?php echo content_url(); ?>uploads/profile_images/<?php echo (!empty($vendor['profile_img']))?$vendor['profile_img']:'default.png' ;?>" alt="<?php echo $vendor['first_name']." ".$vendor['middle_name']." ".$vendor['surname'] ;?>" height="80px" width="80px"></td>
                  <td><?php echo $vendor['first_name']." ".$vendor['middle_name']." ".$vendor['surname'] ;?></td>
                  <td><?php echo $vendor['company_name'] ;?></td>
                  <td><?php 
                    echo $vendor['primary_email'];
                    echo (!empty($vendor['secondary_email']))?" / ".$vendor['secondary_email']:'' ;?></td>
                  <td><?php echo $vendor['contact_1'].' / '.$vendor['contact_2'] ;?></td>
                  <td><?php echo $vendor['blood_group'] ;?></td>
                  <td>
                    <!-- <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                        <span class="fa fa-caret-down" ></span></button>
                        <ul class="dropdown-menu">
                          <li><?php //echo anchor(custom_constants::edit_vendor.$vendor['id'], 'Edit', ['class'=>'']);  ?>
                        </ul>
                    </div> -->
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                      <span class="fa fa-caret-down" ></span></button>
                      <ul class="dropdown-menu">
                        <li><?php echo anchor(custom_constants::admin_vendor_view."/".$vendor['id'], 'View', ['class'=>'']);  ?></li>
                        <li><?php echo anchor(custom_constants::edit_vendor_url."/".$vendor['id'], 'Edit', ['class'=>'']);  ?></li>
                        <li><?php echo anchor(custom_constants::edit_vendor_url."/".$vendor['id'].'?tab=address', 'Add Address', ['class'=>'']);  ?></li> 
                        <li><?php echo anchor(custom_constants::new_enquiry_url."?vendorId=".$vendor['emp_code'], 'New Order', ['class'=>'']);  ?></li>
                        <?php if(isset($vendor['login_id'])){ ?> 
                                <li><?php echo anchor(custom_constants::new_social_url."?user_id=".$vendor['login_id'], 'SMS', ['class'=>'']);  ?></li>
                        <?php } ?>
                      </ul>
                    </div>
                  </td>  
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Primary / Secondary Email</th>
                    <th>contact 1 / Contact 2</th>
                    <th>Blood Group</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->