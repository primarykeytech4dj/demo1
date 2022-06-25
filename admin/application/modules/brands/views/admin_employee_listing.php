
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Employees
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-user margin-r-5"></i> Employees</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-user margin-r-5"></i> Employees</h3>
          <?php //echo anchor(custom_constants::new_employee_url, 'New Employee', 'class="btn btn-primary pull-right"'); ?>
         
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Profile Image</th>
              <th>Full Name</th>
              <th>Employee Code</th>
              <th>Date Of Birth</th>
              <th>Contact</th>
              <th>Blood Group</th>
              <th>Primary / Secondary Email</th>
              <th>Is Active</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($employees as $key=> $v) {?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo !empty($v['profile_img'])?'<img src="'.content_url().'uploads/profile_images/'.$v['profile_img'].'" alt="'.$v['first_name'].'" height="80px" width="80px">':'';?></td>
              <td><?php echo $v['first_name']." ".$v['middle_name']." ".$v['surname'] ;?></td>
              <td><?php echo $v['emp_code'] ;?></td>
              <td><?php echo ($v['dob']!='0000-00-00')?date('d F, Y',strtotime($v['dob'])):'NA'; ?></td>
              <td><?php echo $v['contact_1'].' / '.$v['contact_2'];?></td>
              <td><?php echo $v['blood_group'] ;?></td>
              <td><?php echo $v['primary_email'].' / '.$v['secondary_email'];?></td>
              <td><i class="<?php echo ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               <!-- <?= anchor("Colleges/admin_Edit/".$v['id']);?>  -->
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                   <li><?php echo anchor(custom_constants::admin_employee_view."/".$v['id'], 'View', ['class'=>'']);  ?></li>
                   <li><?php echo anchor(custom_constants::edit_employee_url."/".$v['id'], 'Edit', ['class'=>'']);  ?></li>
                   <li><?php echo anchor(custom_constants::edit_employee_url."/".$v['id'].'?tab=address', 'Add Address', ['class'=>'']);  ?></li> 
                  </ul>
                </div>
              </td>  
             <!--  <td>
              <?= anchor("Colleges/admin_Edit/".$v['id']);?>
             
             </td> -->

            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>Profile Image</th>
                <th>Full Name</th>
                <th>Employee Code</th>
                <th>Date Of Birth</th>
                <th>Contact</th>
                <th>Blood Group</th>
                <th>Primary / Secondary Email</th>
                <th>Is Active</th>
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

