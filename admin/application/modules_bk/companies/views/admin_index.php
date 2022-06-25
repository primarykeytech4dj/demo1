
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Companies
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-user margin-r-5"></i> Companies</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-user margin-r-5"></i> Companies</h3>
          <?php echo anchor(custom_constants::new_company_url, 'New Companies', ['class'=>"btn btn-primary pull-right"]) ?>
          <!-- <a href="<?php echo custom_constants::new_company_url; ?>" title="" class="btn btn-primary pull-right">New Companies</a> -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Company Name</th>
              <th>Slug</th>
              <th>Logo</th>
              <th>Full Name</th>
              <th>Primary /Secondary Email</th>
              <th>Contact 1/Contact 2</th>
              <th>Meta Keyword</th>
              <th>Meta Title</th>
              <th>Meta Description</th>
              <th>Website</th>
              <th>Pan No.</th>
              <th>GST</th>
              <th>Is Active</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($companies as $key=> $v) {
                  //print_r($v);?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo $v['company_name'];?></td>
              <td><?php echo $v['slug'];?></td>
              <td><?php echo !empty($v['logo'])?'<img src="'.content_url().'uploads/profile_images/'.$v['logo'].'" alt="'.$v['first_name'].'" height="80px" width="80px">':'';?></td>
              <td><?php echo $v['first_name']." ".$v['middle_name']." ".$v['surname'] ;?></td>
              <td><?php echo $v['primary_email']."/". $v['secondary_email'] ;?></td>
              <td><?php echo $v['contact_1'].' / '.$v['contact_2'];?></td>
              <td><?php echo $v['meta_keyword'] ;?></td>
              <td><?php echo $v['meta_title'];?></td>
              <td><?php echo $v['meta_description'];?></td>
              <td><?php echo $v['website'];?></td>
              <td><?php echo $v['pan_no'];?></td>
              <td><?php echo $v['gst_no'];?></td>
              <td><i class="<?php echo ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               <!-- <?= anchor("Colleges/admin_Edit/".$v['id']);?>  -->
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                  <li><?php echo anchor(custom_constants::admin_company_view."/".$v['id'], 'View', ['class'=>'']);?></li>
                   <li><?php echo anchor(custom_constants::edit_company_url."/".$v['id'], 'Edit', ['class'=>'']);?></li>
                  <li><?php echo anchor(custom_constants::edit_company_url."/".$v['id'].'?tab=address', 'Add Address', ['class'=>'']);?></li>
                  <li><?php echo anchor(custom_constants::new_infra_url."?company_id=".$v['id'], 'Add Infrastructure', ['class'=>'']);?></li>
                  </ul>
                </div>
              </td>  
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
              <th>Company Name</th>
              <th>Slug</th>
              <th>Logo</th>
              <th>Full Name</th>
              <th>Primary /Secondary Email</th>
              <th>Contact 1/Contact 2</th>
              <th>Meta Keyword</th>
              <th>Meta Title</th>
              <th>Meta Description</th>
              <th>Website</th>
              <th>Pan No.</th>
              <th>GST</th>
              <!-- <th>About Company</th> -->
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

