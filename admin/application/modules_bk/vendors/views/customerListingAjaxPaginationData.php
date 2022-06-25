<div class="box-body" id="customerpaging">
    <table class="table table-bordered">
        <tr>
          <th style="width: 10px">id</th>
          <th>Name</th>
          <th>Email</th>
          <th>contact 1</th>
          <th>Contact 2</th>
          <th>Blood Group</th>
          <!-- <th>Contact Number</th>
          <th>primary Email</th>
          <th>college code</th>
          <th>crowd</th> -->

          <th style="width: 40px">Action</th>
        </tr>

        <?php foreach($customers as $key => $customer) {?>
        <tr>
          <td><?php echo $customer['id'] ;?></td>
          <td><?php echo $customer['fname']." ".$customer['surname'] ;?></td>
          <td><?php echo $customer['email'] ;?></td>
          <td><?php echo $customer['contact_no_1'] ;?></td>
          <td><?php echo $customer['contact_no_2'] ;?></td>
          <td><?php echo $customer['blood_group'] ;?></td>
          <!-- <td><?php echo $customer['contact_1'] ;?></td>
          <td><?php echo $customer['primary_email'] ;?></td>
          <td><?php echo $customer['college_code'] ;?></td>
          <td><?php echo $customer['crowd'] ;?></td> -->
          <td>
           <!-- <?= anchor("Colleges/admin_Edit/".$customer['id']);?>  -->
           <div class="input-group-btn">
               <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
               <span class="fa fa-caret-down" ></span></button>
             <ul class="dropdown-menu">
               <li><?php echo anchor('customer/admin_edit_college/'.$customer['id'], 'Edit', ['class'=>'']);  ?><!--a href="<?php echo base_url();?>Colleges/admin_edit/".echo $customer[id]>edit</a--></li> 
            <li><a href="">Another action</a></li>
               <li><a href="#">Something else here</a></li>
               <li class="divider"></li>
               <li><a href="#">Separated link</a></li>
              </ul>
            </div>
          </td>  
        </tr>
        <?php }?>
   </table>
   <div class="pull-right">
    <?php echo isset($pagination)?$pagination:''; ?>
   </div>
</div>