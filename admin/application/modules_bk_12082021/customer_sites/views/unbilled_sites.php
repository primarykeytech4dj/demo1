<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Unbilled Workorders</h3>
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
              <th>Site Name</th>
              <th>Contact 1</th>
              <th>Date</th>
              <th>Consignment No</th>
              <th>Weight</th>
              <th>Cost</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
              <?php 
              /*echo '<pre>';
              print_r($address);
              echo '</pre>';*/
              //$url = $module;
              foreach($site_services as $key=> $service) { //print_r($v); ?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo $service['fullname'] ;?></td>
              <td><?php echo $service['company_name'] ;?></td>
              <td><?php echo $service['site_name'] ;?></td>
             
              <td><?php echo $service['contact_1'] ;?></td>
              <td><?php echo date('d, F, y', strtotime($service['start_date'])) ;?></td>
              <td><?php echo $service['consign_no'] ;?></td>
              <td><?php echo $service['weight'] ;?></td>
              <td><?php echo $service['cost'] ;?></td>
              <td>
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                  <span class="fa fa-caret-down" ></span></button>
                  <ul class="dropdown-menu">
                   <li><?php echo anchor(custom_constants::edit_site_service_url."/".$service['id'], 'Edit Service', ['class'=>'']);  ?></li>
                  </ul>
                </div>
              </td>  
            
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Customer Name</th>
              <th>Company Name</th>
              <th>Site Name</th>
              <th>Contact 1</th>
              <th>Date</th>
              <th>Consignment No</th>
              <th>Weight</th>
              <th>Cost</th>
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

