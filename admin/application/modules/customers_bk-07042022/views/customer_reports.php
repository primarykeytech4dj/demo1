
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Module :: Customers
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url()?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Customers</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customer</h3>
              <?php //echo anchor(custom_constants::new_customer_url, 'New Customer', 'class="btn btn-primary pull-right"'); ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php //print_r($this->session->userdata); ?>
              <table id="example2" class="table table-bordered table-striped example2">

                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>Company</th>
                    <th>Name</th>
                    <th>Primary / Secondary Email</th>
                    <th>contact 1 / Contact 2</th>                   <th>Customer Code</th>
                    <th>Site Name</th>
                    <th>Address_1 </th>
                    <th>Address_2</th>
                    <th>Area</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Pincode</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  //print_r($customers);
                  //echo '<pre>';
                  foreach($customers as $key => $customer) {
                    //print_r($customer);?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $customer['company'] ;?></td>
                  <td><?php echo $customer['first_name']." ".$customer['middle_name']." ".$customer['surname']." | ".$customer['company_name'] ;?></td>
                  <td><?php 
                    echo $customer['primary_email'];
                    echo (!empty($customer['secondary_email']))?" / ".$customer['secondary_email']:'' ;?></td>
                  <td><?php echo $customer['contact_1'].' / '.$customer['contact_2'] ;?></td>
                  <td><?php echo $customer['emp_code'];?></td>
                  <td><?php echo $customer['site_name'];?></td>
                  <td><?php echo $customer['address_1'];?></td>
                  <td><?php echo $customer['address_2'];?></td>
                  <td><?php echo $customer['area_name'];?></td>
                  <td><?php echo $customer['city_name'];?></td>
                  <td><?php echo $customer['state_name'];?></td>
                  <td><?php echo $customer['name'];?></td>
                  <td><?php echo $customer['pincode'];?></td>
                  <td><div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                      <span class="fa fa-caret-down" ></span></button>
                      <ul class="dropdown-menu">
                        <li><?php echo anchor('customers/editcustomer'."/".$customer['id'], 'Edit', ['class'=>'']);  ?></li>
                      </ul>
                    </div></td>  

                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Company</th>
                    <th>Name</th>
                    <th>Primary / Secondary Email</th>
                    <th>contact 1 / Contact 2</th>                   <th>Customer Code</th>
                    <th>Site Name</th>
                    <th>Address_1 </th>
                    <th> Address_2</th>
                    <th>Area</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Pincode</th>
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