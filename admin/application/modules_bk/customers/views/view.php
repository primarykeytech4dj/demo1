<style>
/*.table{
overflow-x:auto;
padding:0;
}*/
@media only screen and (max-width:500px)
{
  /*.table_set>thead>tr>th,td{
    font-size: 11px;
    padding:0;
    
  }
  .table_set{
    margin-left: -27px;
  }*/
}
</style>


<hr class="">
<div class="container target">
  <div class="row">
    <!-- <div class="col-sm-3">
      <?php //echo anchor(custom_constants::customer_page_url, !empty($user['profile_img'])?img(['src'=>'assets/uploads/profile_images/'.$user['profile_img'], 'class'=>'img-circle img-responsive', 'alt'=>$user['first_name']]):img(['src'=>'assets/uploads/profile_images/no-user.png', 'class'=>'img-circle img-responsive', 'alt'=>$user['first_name']])); ?>
    </div> -->
    
    
  </div>
  <!-- <br> -->
  <div class="row">
    <div class="col-sm-12">
      <div class="alert alert-success bg_navy_blue text_white fw600 dashbord_top_message">
        <?php echo isset($message[0]['subject'])?anchor(custom_constants::customer_message_view.'/'.$message[0]['id'],$message[0]['subject'].'- ON '.date('d F, Y', strtotime($message[0]['date']))):'<h2>No Message for you</h2>' ?>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
      <!--left col--><?php //print_r($this->session->userdata('logged_in_since')); ?>
      <ul class="list-group">
          <li class="list-group-item text-muted m-title" contenteditable="false">Profile</li>
          <li class="list-group-item text-right"><span class="pull-left"><strong class="">Joined</strong></span> <?php if($user['joining_date']!=='0000-00-00'){ echo date('d F, Y', strtotime($user['joining_date']));}else{ echo 'NA';} ?></li>
          <li class="list-group-item text-right"><span class="pull-left"><strong class="">Last seen</strong></span> <?php echo $this->session->userdata('logged_in_since'); ?></li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong class="">Real name</strong></span> <?php echo $user['first_name']." ".$user['middle_name']." ".$user['surname']; ?></li>
              <li class="list-group-item text-right"><span class="pull-left"><strong class="">Role: </strong></span> <?php echo implode(', ',$roles); ?>
               
                      </li>
            </ul>

           <div class="panel panel-default">
             <div class="panel-heading m-title">Address</div>
                <div class="panel-body">
                  <?php 
                  //print_r($address);
                  if(count($address)>0){
                    foreach ($address as $addressKey => $location) {
                      echo anchor('customers/edit/'.$user['id']."?tab=address&address_id=".$location['id'], 'Update', ['class'=>'btn btn-default pull-right']).'<br>'.$location['address_1']."<br>".$location['address_2']."<br>".$location['city_name'].", ".$location['state_name']." - ".$location['pincode']."<br>".$location['name'];
                    }
                  }else{
                    echo anchor('customers/edit/'.$user['id']."?tab=address", 'Add New', ['class'=>'btn btn-default']);
                  } ?>
                  
                </div>
            </div>
            <!-- <div class="panel panel-default">
                <div class="panel-heading">Website <i class="fa fa-link fa-1x"></i>
            
                </div>
                <div class="panel-body"><a href="http://bootply.com" class="">bootply.com</a>
            
                </div>
            </div> -->
          
            <ul class="list-group">
                <li class="list-group-item text-muted m-title">Personal Details <i class="fa fa-dashboard fa-1x"></i>

                </li>
                <li class="list-group-item text-right">
                  <span class="pull-left">
                    <strong class="">Gender</strong>
                  </span> 
                  <?php echo !empty($user['gender'])?ucfirst($user['gender']):'NA'; ?>
                </li>
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Email ID</strong></span> <?php echo $user['primary_email'];
                  echo !empty($user['secondary_email'])?'<br>'.$user['secondary_email']:'';
                 ?></li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong class="">Contact No.</strong></span> 
                      <?php echo $user['contact_1']; ?>
                      <?php echo !empty($user['contact_2'])?'<br>'.$user['contact_2']:'' ?>
                    </li>
                    <li class="list-group-item text-right">
                      <span class="pull-left">
                        <strong class="">DOB</strong>
                      </span> 
                      <?php echo ($user['dob']!='0000-00-00')?date('d F, Y', strtotime($user['dob'])):'NA'; ?>
                    </li>
            </ul>
            <!-- <div class="panel panel-default">
                <div class="panel-heading">Social Media</div>
                <div class="panel-body">  <i class="fa fa-facebook fa-2x"></i>  <i class="fa fa-github fa-2x"></i> 
                    <i class="fa fa-twitter fa-2x"></i> <i class="fa fa-pinterest fa-2x"></i>  <i class="fa fa-google-plus fa-2x"></i>
            
                </div>
            </div> -->
        </div>
        <!--/col-3-->
        <div class="col-sm-9" style="" contenteditable="false">
          <?php //print_r($placement); ?>
            <div class="panel panel-default">
                <div class="panel-heading"><strong class="fw600 text_green">Placement Details</strong></div>
                <div class="panel-body"> 
                  <?php //print_r($placement); ?>
                  <table id="" class="table table-bordered table-striped">
                    <thead>
                      <tr class="bg-1">
                        <th class="text-center bg_red text_white fw600">Placed Under - ID</th>
                        <th class="text-center bg_yellow text_white fw600">Placement Node</th>
                        <th class="text-center bg_green text_white fw600">Proposer Name - ID</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php //if(!empty($placement['node'])){ ?>
                      <tr>
                        <td class="text-center fw600 text_green">
                          <?php echo (!empty($placement['node']))?/*img(['src'=>'assets/uploads/profile_images/'.$placement['node']['profile_img'], 'class'=>'img-circle img-responsive']).*/$placement['node']['fullname'].' - '.$placement['node']['username']:'NA' ?>
                        </td>
                        <td class="text-center fw600 text_green"><?php echo (!empty($placement['node']))?$placement['node']['placement']:''; ?></td>
                        <td class="text-center fw600 text_green"><?php echo (!empty($placement['sponsor']))?/*img(['src'=>'assets/uploads/profile_images/'.$placement['sponsor']['profile_img'], 'class'=>'img-circle img-responsive']).*/$placement['sponsor']['fullname']." - ".$placement['sponsor']['username']:'NA'; ?></td>
                      </tr>
                      <?php //} ?>
                    </tbody>
                  </table>
                </div>
            </div>
            <div class="panel panel-default target">
              <div class="panel-heading" contenteditable="false">
                <strong class="fw600 text_green">Commission Details</strong>
              </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12">
                        <div class="table_responsive">
 <table class="table table-bordered table-striped table_set">
                        <thead>
                          <tr class="bg-2">
                            <?php foreach ($mlmIncomes as $key => $income) {
                              if($income['income'] == 'Direct Income')
                              {
                                $class = "bg_blue";
                              }
                              else if($income['income'] == 'Pair Matching Income')
                              {
                                $class = "bg_red";
                              }
                              else if($income['income'] == 'Repurchase Income')
                              {
                                $class = "bg_green";
                              }
                              else if($income['income'] == 'Digital Income')
                              {
                                $class = "bg_yellow";
                              }
                              echo '<th align="center" class="'.$class.' text_white fw600"><center>'.$income['income'].'<hr> Withdrawl | Shopping</center></th>';
                            } ?>
                            <th class="bg_purple text_white fw600"><center>Total Wallet Income<hr>Withdrawl | Shopping</center></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <?php 
                            //print_r($wallets);
                            $reedemable = 0;
                            $nonReedemable = 0;
                            foreach ($mlmIncomes as $key => $income) {

                              echo '<td class="text-center fw600 text_green">';
                              echo $total1 = isset($wallets[$income['id']][1]['amount'])?$wallets[$income['id']][1]['amount']:'0.00';
                              echo ' | ';
                              echo $total2 = isset($wallets[$income['id']][0]['amount'])?$wallets[$income['id']][0]['amount']:'0.00';
                              echo '</td>';
                              $reedemable = $reedemable + $total1;
                              $nonReedemable = $nonReedemable + $total2;

                            } ?>
                            <td class="text-center fw600 text_green"><?php echo number_format($reedemable, 2)." | ".number_format($nonReedemable, 2); ?></td>
                          </tr>
                        </tbody>
                      </table>
</div>
                    </div>                     
                  </div>
                 
                </div>
              
              </div>
           <div class="panel panel-default">
                <div class="panel-heading m-title2"><strong class="fw600 text_green">Member Statistics</strong></div>
                <div class="panel-body"> 
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr class="bg-3 bg_navy_blue text_white fw600">
                        <th></th>
                        <th class="fw600">Left</th>
                        <th class="fw600">Right</th>
                        <th class="fw600">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th class="bg-3 bg_green text_white fw600">Total Joining</th>
                        <td class="fw600 text_green">
                          <?php //print_r($memberStatistics['placementCount']); ?>
                          <?php echo $left = (!empty($memberStatistics) && NULL !== $memberStatistics['placementCount']['left'])?$memberStatistics['placementCount']['left']:0; ?>
                        </td>
                        <td class="fw600 text_green">
                          <?php echo $right = (NULL !== $memberStatistics['placementCount']['right'])?$memberStatistics['placementCount']['right']:0; ?>
                        </td>
                        <td class="fw600 text_green"><?php echo $left+$right; ?></td>
                      </tr>
                      <tr>
                        <th class="bg-3 bg_yellow text_white fw600">Today's Joining</th>
                        <td class="fw600 text_green"><?php echo $left = (NULL !== $memberStatistics['todayJoining']['left'])?$memberStatistics['todayJoining']['left']:0; ?></td>
                        <td class="fw600 text_green"><?php echo $right = (NULL !== $memberStatistics['todayJoining']['right'])?$memberStatistics['todayJoining']['right']:0; ?></td>
                        <td class="fw600 text_green"><?php echo $left+$right; ?></td>
                      </tr>
                      <tr>
                        <th class="bg-3 bg_purple text_white fw600">Total Directs</th>
                        <td class="fw600 text_green"><?php echo $directCount['left']; ?></td>
                        <td class="fw600 text_green"><?php echo $directCount['right']; ?></td>
                        <td class="fw600 text_green"><?php echo $directCount['left']+$directCount['right']; ?></td>
                      </tr>
                      
                      <tr>
                        <th class="bg-3 bg_red text_white fw600">Total Commission Paid</th>
                        <td class="fw600 text_green"><?php 
                          $left = 0;
                          $right = 0;
                          if(isset($busCount['left']))
                            $left = $busCount['left'];

                          if(isset($busCount['right']))
                            $right = $busCount['right'];

                          echo $left2 = ($left<$right)?$left*0.15:$right*0.15; ?></td>
                        <td class="fw600 text_green"><?php echo $right2 = ($left<$right)?$left*0.15:$right*0.15; ?></td>
                        <td class="fw600 text_green"><?php echo $left2+$right2; ?></td>
                      </tr>
                      <tr>
                        <th class="bg-3 bg_blue text_white fw600">Total Commission Count</th>
                        <td class="fw600 text_green"><?php 
                          $left = 0;
                          $right = 0;
                          if(isset($busCount['left']))
                            $left = $busCount['left']*0.15;

                          if(isset($busCount['right']))
                            $right = $busCount['right']*0.15;

                          echo $left2 = ($left<$right)?0:($left-$right); ?></td>
                        <td class="fw600 text_green"><?php echo $right2 = ($right<$left)?0:($right-$left); ?></td>
                        <td class="fw600 text_green"><?php echo $left2+$right2; ?></td>
                      </tr>

                      <tr>
                        <th class="bg-3 bg_dark_green text_white fw600">Total Business Turnover</th>
                        <td class="fw600 text_green"><?php 
                          $left = 0;
                          $right = 0;
                          if(isset($busCount['left']))
                            $left = $busCount['left'];

                          if(isset($busCount['right']))
                            $right = $busCount['right'];

                          echo $left; ?></td>
                        <td class="fw600 text_green"><?php echo $right; ?></td>
                        <td class="fw600 text_green"><?php echo $left+$right; ?></td>
                      </tr>
                    </tbody>
                  </table>

                </div>
</div></div>

        </div>
    
      
        <!-- End Quantcast tag -->
        
        
        
  
  
</div>

