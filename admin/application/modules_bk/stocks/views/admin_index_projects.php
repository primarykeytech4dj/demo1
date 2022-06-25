
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
   <span class="glyphicon glyphicon-shopping-cart"></span> Module :: Orders (Projects)
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-shopping-cart margin-r-5"></i> Orders (Projects)</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-shopping-cart margin-r-5"></i> Orders</h3>
          <?php echo anchor(custom_constants::new_project_url, 'New Order (Project)', 'class="btn btn-primary pull-right"'); ?>
          
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
              <th>Invoice Number</th>
              <th>Invoice Date</th>
             <!--  <th>Contact</th> -->
              <!-- <th>Blood Group</th> -->
              <th>Description</th>
              <!-- <th>Primary / Secondary Email</th> -->
              <th>Amount Before Tax</th>
              <th>Amount After Tax</th>
              <th>Is Active</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $key=> $v) {?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo !empty($v['profile_img'])?'<img src="'.base_url().'assets/uploads/profile_images/'.$v['profile_img'].'" alt="'.$v['first_name'].'" height="80px" width="80px">':'';?></td>
              <td><?php echo $v['first_name']." ".$v['middle_name']." ".$v['surname'] ;?></td>
              <td><?php echo $v['order_code'] ;?></td>
              <td><?php echo ($v['date']!='0000-00-00')?date('d F, Y',strtotime($v['date'])):'NA'; ?></td>
              <!-- <td><?php echo $v['contact_1'];
              
                echo !empty($v['contact_2'])?' / '.$v['contact_2']:'';?></td> -->
              <!-- <td><?php echo $v['blood_group'] ;?></td> -->
              <td><?php echo $v['message'] ;?></td>
              <!-- <td><?php echo $v['primary_email'];
                 echo !empty($v['secondary_email'])?' / '.$v['secondary_email']:'';
              ?></td> -->
              <td><?php echo $v['amount_before_tax']; ?></td>
              <td><?php echo $v['amount_after_tax']; ?></td>
              <td><i class="<?php echo ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               <!-- <?= anchor("Colleges/admin_Edit/".$v['id']);?>  -->
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                   
                   <li><?php echo anchor(custom_constants::edit_project_url."/".$v['id'], 'Edit', ['class'=>'']);  ?></li>
                   <li><?php echo anchor(custom_constants::edit_project_url."/".$v['id'].'?tab=document', 'Add Images', ['class'=>'']);  ?></li> 
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
                <th>Invoice Number</th>
                <th>Invoice Date</th>
                <!-- <th>Contact</th> -->
                <!-- <th>Blood Group</th> -->
                <th>Requirement</th>
                <!-- <th>Primary / Secondary Email</th> -->
                <th>Amount Before Tax</th>
                <th>Amount After Tax</th>
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

