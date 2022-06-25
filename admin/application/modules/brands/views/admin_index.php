    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Module :: Roles
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Role</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Role</h3>
              <?php echo anchor(custom_constants::new_role_url, 'New Role', 'class="btn btn-primary pull-right"'); ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php //print_r($this->session->userdata); ?>
              <table id="example2" class="table table-bordered table-striped example2">
                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>Nme</th>
                    <th>Code</th>
                    <th>Active</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  foreach($roles as $key => $role) {?>
                <tr>
                  <td><?php echo $role['id'] ;?></td>
                  <td><?php echo $role['role_name'] ;?></td>
                  <td><?php echo $role['role_code'] ;?></td>
                  <td><i class="<?php echo ($role['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>

                  <td>
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                      <span class="fa fa-caret-down" ></span></button>
                      <ul class="dropdown-menu">
                        <!-- <li><?php echo anchor(custom_constants::admin_role_view."/".$role['id'], 'View', ['class'=>'']);  ?></li> -->
                        <li><?php echo anchor(custom_constants::edit_role_url."/".$role['id'], 'Edit', ['class'=>'']);  ?></li>
                        <!-- <li><?php echo anchor(custom_constants::edit_role_url."/".$role['id'].'?tab=address', 'Add Address', ['class'=>'']);  ?></li>  -->
                      </ul>
                    </div>
                  </td>  
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Name</th>
                    <th>Code</th>
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