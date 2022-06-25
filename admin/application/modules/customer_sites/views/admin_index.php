<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Customer Sites</h3>
          <?php if(!isset($module)){ ?>
            <?php echo anchor(custom_constants::new_courier_site_url, 'Add New Site', ['class'=>'btn btn-primary pull-right']); ?>
             <!--  <a href="<?php //echo custom_constants::new_user_address_url; ?>" title="" class="btn btn-primary pull-right">New Address</a> -->
          <?php } ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2" style="overflow-x: scroll;">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Customer Name</th>
              <th>Company Name</th>
              <th>Site Code</th>
              <th>Contact Person</th>
              <th>Primary / Secondary Email</th>
              <th>contact 1 / Contact 2</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
              <?php 
              /*echo '<pre>';
              print_r($address);
              echo '</pre>';*/
              //$url = $module;
              foreach($customerSites as $key=> $customerSite) { //print_r($v); ?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo $customerSite['fullname'] ;?></td>
              <td><?php echo $customerSite['company_name'] ;?></td>
              <td><?php echo $customerSite['site_name'] ;?></td>
              <td><?php echo $customerSite['contactfullname'] ;?></td>
              <td><?php 
                echo $customerSite['primary_email'];
                echo (!empty($customerSite['secondary_email']))?" / ".$customerSite['secondary_email']:'' ;?></td>
              <td><?php echo $customerSite['contact_1'].' / '.$customerSite['contact_2'] ;?></td>
              <td>
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                  <span class="fa fa-caret-down" ></span></button>
                  <ul class="dropdown-menu">
                    <!-- <li><?php echo anchor("customers/admin_customer_details/".$customerSite['id'], 'View', ['class'=>'']);  ?></li> -->
                    <li><?php echo anchor(custom_constants::edit_customer_url."/".$customerSite['customer_id'].'?tab=sites&site_id='.$customerSite['id'], 'Edit Site', ['class'=>'']);  ?></li>
                  </ul>
                </div>
              </td>  
            
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>Customer Name</th>
                <th>Company Name</th>
                <th>Site Code</th>
                <th>Contact Person</th>
                <th>Primary / Secondary Email</th>
                <th>contact 1 / Contact 2</th>
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

