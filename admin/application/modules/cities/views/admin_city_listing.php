<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Cities</h3>
          <?php if(!isset($module)){ ?>
            <?php echo anchor('cities/add_new_city', 'Add New City', ['class'=>'btn btn-primary pull-right']); ?>
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
              <th>City</th>
              <th>Short Name</th>
              <th>Type</th>
              <th>Population</th>
              <th>Population Class</th>
              <th>State</th>
              <th>Country</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
              <?php 
              /*echo '<pre>';
              print_r($address);
              echo '</pre>';*/
              //$url = $module;
              foreach($cities as $key=> $city) { //print_r($v); ?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo $city['city_name'] ;?></td>
              <td><?php echo $city['short_name'] ;?></td>
              <td><?php echo $city['type'] ;?></td>
              <td><?php echo $city['population'] ;?></td>
              <td><?php echo $city['population_class'] ;?></td>
              <td><?php echo $city['state_name'] ;?></td>
              <td><?php echo $city['country_name'] ;?></td>
              <td><i class="<?php echo ($city['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                  <span class="fa fa-caret-down" ></span></button>
                  <ul class="dropdown-menu">
                    <!-- <li><?php echo anchor("customers/admin_customer_details/".$city['id'], 'View', ['class'=>'']);  ?></li> -->
                    <li><?php echo anchor(custom_constants::edit_customer_url."/".$city['id'].'?tab=sites&site_id='.$city['id'], 'Edit Site', ['class'=>'']);  ?></li>
                  </ul>
                </div>
              </td>  
            
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>City</th>
                <th>Short Name</th>
                <th>Type</th>
                <th>Population</th>
                <th>Population Class</th>
                <th>State</th>
                <th>Country</th>
                <th>Active</th>
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

