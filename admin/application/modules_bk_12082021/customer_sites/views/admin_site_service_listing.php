<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Customer Site Services / Work Order</h3>
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
              <th>Site Name</th>
              <th>Delivery Person</th>
              <th>Email ID's</th>
              <th>contact</th>
              <th>Date</th>
              <th>Product/Service</th>
              <th>Tracking No.</th>
              <th>Weight</th>
              <th>Mode</th>
              <th>Rate</th>
              <th>Service Charge</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
              <?php 
              /*echo '<pre>';
              print_r($customerSiteServices);
              echo '</pre>';*/
              //$url = $module;
              foreach($customerSiteServices as $key=> $service) { //print_r($v); ?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo anchor(custom_constants::admin_customer_view.'/'.$service['customer_id'], $service['client_company']." (".$service['client']." - ".$service['client_contact'].")") ;?></td>
              <td><?php echo $service['site_name'] ;?></td>
              <td><?php echo $service['delivery_person'] ;?></td>
              <td><?php 
                echo $service['primary_email'].(!empty($service['secondary_email'])?" / ".$service['secondary_email']:'');?></td>
              <td><?php echo $service['contact_1'].(!empty($service['contact_2'])?' / '.$service['contact_2']:'') ;?></td>
              <td><?php echo date('d/m/Y', strtotime($service['start_date']));?></td>
              <td><?php echo $service['product'] ;?></td>
              <td><?php echo $service['consign_no'] ;?></td>
              <td><?php echo $service['weight'] ;?></td>
              <td><?php echo $service['mode'] ;?></td>
              <td align="right"><?php echo number_format($service['cost'], 2) ;?></td>
              <td><?php $unit = ($service['service_charge_type']=='PERCENT')?'%':'Rs.'; echo $service['service_charge'].' '.$unit ;?></td>
              <td>
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                  <span class="fa fa-caret-down" ></span></button>
                  <ul class="dropdown-menu">
                    <!-- <li><?php echo anchor("customers/admin_customer_details/".$service['id'], 'View', ['class'=>'']);  ?></li> -->
                     <li><?php echo anchor(custom_constants::edit_site_service_url."/".$service['id'], 'Edit Service', ['class'=>'']);  ?></li>
                    <li><?php echo anchor(custom_constants::edit_customer_url."/".$service['customer_id'].'?tab=sites&site_id='.$service['customer_site_id'], 'Add Service', ['class'=>'']);  ?></li>
                    <li><?php echo anchor(custom_constants::edit_customer_url."/".$service['customer_id'].'?tab=sites', 'Add New Site', ['class'=>'']);  ?></li>
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
                <th>Site Name</th>
                <th>Delivery Person</th>
                <th>Email ID's</th>
                <th>contact</th>
                <th>Date</th>
                <th>Product/Service</th>
                <th>Tracking No.</th>
                <th>Weight</th>
                <th>Mode</th>
                <th>Rate</th>
                <th>Service Charge</th>
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

