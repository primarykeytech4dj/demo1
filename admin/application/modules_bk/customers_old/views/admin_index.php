
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Module :: Customers
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Customers</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customer</h3>
              <?php echo anchor(custom_constants::new_customer_url, 'New Customer', 'class="btn btn-primary pull-right"'); ?>
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
                  //print_r($customers);
                  foreach($customers as $key => $customer) {?>
                <tr>
                  <td><?php echo $customer['id'] ;?></td>
                  <td><img src="<?php echo content_url(); ?>uploads/profile_images/<?php echo (!empty($customer['profile_img']))?$customer['profile_img']:'default.png' ;?>" alt="<?php echo $customer['first_name']." ".$customer['middle_name']." ".$customer['surname'] ;?>" height="80px" width="80px"></td>
                  <td><?php echo $customer['first_name']." ".$customer['middle_name']." ".$customer['surname'] ;?></td>
                  <td><?php echo $customer['company_name'] ;?></td>
                  <td><?php 
                    echo $customer['primary_email'];
                    echo (!empty($customer['secondary_email']))?" / ".$customer['secondary_email']:'' ;?></td>
                  <td><?php echo $customer['contact_1'].' / '.$customer['contact_2'] ;?></td>
                  <td><?php echo $customer['blood_group'] ;?></td>
                  <td>
                    <!-- <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                        <span class="fa fa-caret-down" ></span></button>
                        <ul class="dropdown-menu">
                          <li><?php //echo anchor(custom_constants::edit_customer.$customer['id'], 'Edit', ['class'=>'']);  ?>
                        </ul>
                    </div> -->
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                      <span class="fa fa-caret-down" ></span></button>
                      <ul class="dropdown-menu">
                        <li><?php echo anchor(custom_constants::admin_customer_view."/".$customer['id'], 'View', ['class'=>'']);  ?></li>
                        <li><?php echo anchor(custom_constants::edit_customer_url."/".$customer['id'], 'Edit', ['class'=>'']);  ?></li>
                        <li><?php echo anchor(custom_constants::edit_customer_url."/".$customer['id'].'?tab=address', 'Add Address', ['class'=>'']);  ?></li> 
                        <li><?php echo anchor(custom_constants::new_enquiry_url."?customerId=".$customer['emp_code'], 'New Order', ['class'=>'']);  ?></li>
                        <?php if(isset($customer['login_id'])){ ?> 
                                <li><?php echo anchor(custom_constants::new_social_url."?user_id=".$customer['login_id'], 'SMS', ['class'=>'']);  ?></li>
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