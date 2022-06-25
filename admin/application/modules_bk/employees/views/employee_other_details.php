
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-user margin-r-5"></i> Other Details</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Start date</th>
              <th>End Date</th>
              <th>Salary</th>
              <th>Provident Fund</th>
              <th>ESIC</th>
              <th>Professional Tax</th>
              <th>Active/Inactive</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($otherDetails as $otherDetailKey=> $otherDetail) {?>
            <tr>
              <td><?php echo $otherDetailKey+1 ;?></td>
              <td><?php echo date('d F Y', strtotime($otherDetail['employment_start_date'])); ?></td>
              <td><?php echo ($otherDetail['employment_end_date']!='0000-00-00 00:00:00')?date('d F Y', strtotime($otherDetail['employment_end_date'])):''; ?></td>
              <td><?php echo $otherDetail['salary'] ;?></td>
              <td><?php echo $otherDetail['provident_fund'];?></td>
              <td><?php echo $otherDetail['esic']; ?></td>
              <td><?php echo $otherDetail['professional_tax'];?></td>
              <td><i class="<?php echo ($otherDetail['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               <!-- <?= anchor("Colleges/admin_Edit/".$otherDetail['id']);?>  -->
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                   <li><?php echo anchor("employees/admin_employee_details/".$otherDetail['employee_id'], 'View', ['class'=>'']);  ?></li>
                   <li><?php echo anchor(custom_constants::edit_employee_url."/".$otherDetail['employee_id'].'?tab=other', 'Edit', ['class'=>'']);  ?></li>
                   <!-- <li><?php echo anchor(custom_constants::edit_employee_url."/".$otherDetail['employee_id'].'?tab=other', 'Add Address', ['class'=>'']);  ?></li>  -->
                  </ul>
                </div>
              </td>  
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>Start date</th>
                <th>End Date</th>
                <th>Salary</th>
                <th>Provident Fund</th>
                <th>ESIC</th>
                <th>Professional Tax</th>
                <th>Active/Inactive</th>
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

