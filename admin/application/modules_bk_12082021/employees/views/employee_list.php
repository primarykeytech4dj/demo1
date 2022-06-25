<?php echo form_open();?>
<div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Employees</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 10px">id</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Surname</th>
                  <th>DOB</th>
                  <th>Contact_1</th>
                  <th>Contact_2</th>
                  <th>Blood Group</th>
                  <th>Primary Email</th>
                  <th>Secondary Email</th>
                  <th>Profile Image</th>
                  <th>Employee Code</th>

                  <th style="width: 40px">Action</th>
                </tr>

                <?php foreach($employees as $v) {?>
                <tr>
                  <td><?php echo $v['id'] ;?></td>
                  <td><?php echo $v['first_name'] ;?></td>
                  <td><?php echo $v['middle_name'] ;?></td>
                  <td><?php echo $v['surname'] ;?></td>
                  <td><?php echo $v['dob'] ;?></td>
                  <td><?php echo $v['contact_1'] ;?></td>
                  <td><?php echo $v['contact_2'] ;?></td>
                  <td><?php echo $v['blood_group_id'] ;?></td>
                  <td><?php echo $v['primary_email'] ;?></td>
                  <td><?php echo $v['secondary_email'] ;?></td>
                  <td><?php echo $v['profile_img'] ;?></td>
                  <td><?php echo $v['emp_code'] ;?></td>
                  
                  <td>
                   <!-- <?= anchor("Colleges/admin_Edit/".$v['id']);?>  -->
                  
                  </td>  
                </tr>
                <?php }?>
           </table>
           
        </div>
    </div>
    </div>


<?php echo form_close();?>