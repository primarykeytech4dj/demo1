<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-institution margin-r-5"></i> Banks Accounts</h3>
              <?php if(!isset($module)){ echo anchor('bank_accounts/user_new_accounts', 'New Account', ['title'=>"", 'class'=>"btn btn-primary pull-right"]); ?>
                  <!-- <a href="<?php echo custom_constants::new_user_address_url; ?>" title="" class="btn btn-primary pull-right">New Account</a> -->
              <?php } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php //print_r($this->session->userdata); ?>
              <table id="example2" class="table table-bordered table-striped example2" style="overflow-x: scroll;">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>User Type</th>
                  <th>User</th>
                  <th>Bank Name</th>
                  <th>Account Name</th>
                  <th>Account Number</th>
                  <th>Account Type</th>
                  <th>IFSC Code</th>
                  <th>Branch</th>
                  <th>Is Default</th>
                  <th>Is Active</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                  /*echo '<pre>';
                  print_r($address);
                  echo '</pre>';*/
                  //$url = $module;
                  if($accounts)
                  foreach($accounts as $key=> $account) {  ?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $account['user_type'] ;?></td>
                  <td><?php echo $account['user_id'];?></td>
                  <td><?php echo $account['bank_name'] ;?></td>
                  <td><?php echo $account['account_name'] ;?></td>
                  <td><?php echo $account['account_number']; ?></td>
                  <td><?php echo $account['account_type']; ?></td>
                  <td><?php echo $account['ifsc_code'];?></td>
                  <td><?php echo $account['branch'] ;?></td>
                  <td>
                      <i class="<?php echo (true==$account['is_default'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                      <i class="<?php echo (true==$account['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                   <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                       <span class="fa fa-caret-down" ></span>
                      </button>
                      <ul class="dropdown-menu">
                       <li>
                        <?php 
                         //$url = $this->router->fetch_class().'/'.$this->router->fetch_method().'/'.$account['user_id'].'?tab=bank_account&bank_account_id='.$account['id'];
                         $url = $url.'&bank_account_id='.$account['id'];
                        if($this->router->fetch_class()=='address')
                          $url = '#';
                        /*$url = '#';
                        if($account['user_type']=='employees'){
                          $url = custom_constants::edit_employee_url."/".$account['user_id']."?tab=bank_account&bank_account_id=".$account['id'];
                        }elseif ($account['user_type']=='customers') {
                          $url = custom_constants::edit_customer_url."/".$account['user_id']."?tab=bank_account&bank_account_id=".$account['id'];
                          # code...
                        }elseif ($account['user_type']=='companies') {
                          $url = custom_constants::edit_company_url."/".$account['user_id']."?tab=bank_account&bank_account_id=".$account['id'];
                          # code...
                        }elseif ($account['user_type']=='enquiries') {
                          $url = custom_constants::edit_enquiry_url."/".$account['user_id']."?tab=bank_account&bank_account_id=".$account['id'];
                          # code...
                        }*/
                        echo anchor($url, 'Edit', ['class'=>'']);  ?></li> 
                      </ul>
                    </div>
                  </td>  
                
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>User Type</th>
                    <th>User</th>
                    <th>Bank Name</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Account Type</th>
                    <th>IFSC Code</th>
                    <th>Branch</th>
                    <th>Is Default</th>
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
  
