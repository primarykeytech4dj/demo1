
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
   <?=$title?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><?=$heading?></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?=$heading?></h3>
          <?php echo anchor(custom_constants::new_stockout_url, 'New Stock', 'class="btn btn-primary pull-right"'); ?>
          
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="datatable" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Outward Date</th>
              <th>Broker</th>
              <th>Lorry no</th>
              
              <th>Coil No</th>
              
              <th>Is Active</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($stockout as $key=> $v) {?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?=date('d F,Y', strtotime($v['outward_date']))?></td>
              <td><?=$v['first_name']." ".$v['middle_name']." ".$v['surname']." (".$v['company_name'].")"?></td>
              <td><?=$v['lorry_no']?></td>
              
              <td><?=$v['coil_no']?></td>
              
              <td><i id="status" class="<?php echo (true==$v['is_active'])?'fa fa-check-circle alert-success':'fa fa-remove alert-danger';?>"></i></td>
              <td>
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                   <li>
                    <li><?php echo anchor("#", '<i class="fa fa-eye"></i> View', ['class'=>'dynamic-modal load-ajax', 'data-path'=>"stocks/admin_stockout_view/".$v['id'], 'data-modal-title'=>"Stockout Details", 'data-model-size'=>"modal-lg"]);  ?>
                    
                  </li>
                   <li><?php echo anchor(custom_constants::edit_stockout_url."/".$v['id'], '<i class="fa fa-edit"></i> Edit', ['class'=>'']);  ?></li>
                   <li >
                      <?php echo anchor("#", '<i class="fa fa-trash"></i> Delete', ['class'=>'removebutton alert-danger', 'data-link'=>"stocks/custom_admin_delete", 'data-id' => $v['id'], 'data-table'=>"stockout"]);  ?>
                    

                  </ul>
                </div>
              </td>  
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>Outward Date</th>
                <th>Broker</th>
                <th>Lorry no</th>
                
                <th>Coil No</th>
                
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



