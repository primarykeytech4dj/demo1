<?php 
$tab = "basic details";
if(!defined('BASEPATH')) exit('No direct script access allowed ');

$input['title'] = array(
                          "name" => "data[pages][title]",
                          "placeholder" => "Title(s) *",
                          "max_length" => "255",
                          "required" => "required",
                          "class" => "form-control",
                          "id" =>"title",
                          );

$input['content'] = array(
                          "name" => "data[pages][content]",
                          "placeholder" => "Content(s) *",
                          "required" => "required",
                          "class" => "form-control editor1",
                          "id" => "editor1",
                           );

$input['slug'] = array(
                      "name" => "data[pages][slug]",
                      "placeholder" => "slug(s) *",
                      "max_length" => "255",
                      "required" => "required",
                      "class" => "form-control slugify",
                      "slug-content" => 'title',
                      "id" => "slug",
                    );

$input['featured_image'] =  array(
              "name" => "featured_image",
              "class" => "form-control",
              "id" => "featured_image",
              "value" =>  set_value('featured_image'),
               );

if(isset($value_posted)) {
  foreach ($value_posted as $post_name => $post_value) {
    foreach ($post_value as $field_key => $field_value) {
      $input[$field_key]['value'] = $field_value;
    }
  }
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Pages
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li><?php echo anchor(custom_constants::admin_page_listing_url, ' Pages'); ?></li>
    <li class="active"><?php echo anchor(custom_constants::new_page_url, 'New Page'); ?></li>
  </ol> 
</section>


<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <?php echo form_open_multipart(custom_constants::new_page_url, ['class'=>'form-horizontal', 'id' => 'pages']);
      if(isset($form_error)) 
        {
          echo "<div class='alert alert-danger'>";
          echo $form_error;
          echo "</div>";
        }
        if($this->session->flashdata('message')!== FALSE) {
          $msg = $this->session->flashdata('message');?>
          <div class="<?php echo $msg['class'];?>">
            <?php echo $msg['message'];?>
          </div>
        <?php } ?>

        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">New Page</h3>
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="inputtitle" class="col-sm-2 control-label">Page Title</label>
                  <div class="col-sm-10">
                   <?php echo form_input($input['title']);?>
                   <?php echo form_error('data[pages][title]');?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="inputslug" class="col-sm-2 control-label">Slug</label>
                  <div class="col-sm-10">
                   <?php echo form_input($input['slug']);?>
                   <?php echo form_error('data[pages][slug]');?>
                  </div>
                </div>
              </div>
            </div>
             <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="inputcontent" class="col-sm-2 control-label">Content</label>
                  <div class="col-sm-10">
                  <?php echo form_textarea($input['content']);?>
                  <?php echo form_error('data[pages][content]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="inputCity" class="col-sm-2 control-label">Featured Image</label>
                  <div class="col-sm-10">
                      <?php echo form_upload($input['featured_image']); ?>
                      <?php echo form_error('profile_img'); ?>
                  </div>
                </div>
              </div>     
            </div><!-- /row -->
        </div><!-- /box-body -->
        <div class="box-footer">  
          <button type="submit" class="btn btn-info pull-left" id="Save">Save</button>
          <?php echo nbs(3); ?>
          <button type="reset" class="btn btn-info">Reset</button>
        </div>
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
      <?php echo nbs(3); ?>
    </div>
  </div>
</section><!-- /.content -->
