<?php if($this->session->flashdata('message') !== FALSE) {
    $msg = $this->session->flashdata('message');?>
    <div class = "<?php echo $msg['class'];?>">
      <?php echo $msg['message'];?>
    </div>
  <?php } ?> 
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-book margin-r-5"></i> Documents</h3>
              <?php if(!isset($module)){ echo anchor('upload_documents/new_document', 'New Document', ['title'=>"", 'class'=>"btn btn-primary pull-right"]); ?>
                  <!-- <a href="<?php echo custom_constants::new_user_address_url; ?>" title="" class="btn btn-primary pull-right">New document</a> -->
              <?php } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php //print_r($this->session->userdata); ?>
              <table id="example2" class="table table-bordered table-striped example2">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th class="<?php if(in_array(1, $this->session->userdata('roles'))){ echo 'hideIt';} ?>">User Type</th>
                  <th class="<?php if(in_array(1, $this->session->userdata('roles'))){ echo 'hideIt';} ?>">User</th>
                  <th>Document</th>
                  <th>File</th>
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
                  foreach($documents as $key=> $document) { //print_r($document); ?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td class="<?php if(in_array(1, $this->session->userdata('roles'))){ echo 'hideIt';} ?>"><?php echo $document['user_type'] ;?></td>
                  <td class="<?php if(in_array(1, $this->session->userdata('roles'))){ echo 'hideIt';} ?>"><?php echo $document['user_id'];?></td>
                  <td><?php echo $document['document'] ;?></td>
                  <td><?php echo anchor('upload_documents/download_document/'.$document['id'], $document['file'], ['title'=>'Click To download']); ?></td>
                  <td>
                      <i class="<?php echo (true==$document['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                   <!-- <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                       <span class="fa fa-caret-down" ></span>
                      </button>
                      <ul class="dropdown-menu">
                       <li>
                        <?php 
                        $url = ($document['user_type']=='employees')?custom_constants::edit_employee_url:'upload_documents/new_document';
                        echo anchor($url."/".$document['user_id']."?tab=document", 'Edit', ['class'=>'']);  ?></li> 
                      </ul>
                    </div> -->
                  </td>  
                
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th class="<?php if(in_array(1, $this->session->userdata('roles'))){ echo 'hideIt';} ?>">User Type</th>
                    <th class="<?php if(in_array(1, $this->session->userdata('roles'))){ echo 'hideIt';} ?>">User</th>
                    <th>Document</th>
                    <th>File</th>
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
  
