<?php
//print_r($value_posted);
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
$input['featured_image2'] = array(
								'data[pages][featured_image2]' => $value_posted['pages']['featured_image'],
								'data[pages][id]' => $id,
							);	
/*echo '<pre>';
print_r($value_posted);
echo '</pre>';	*/		

if(isset($value_posted)) 
	foreach ($value_posted as $post_name => $post_value) {
		foreach ($post_value as $fieldkey => $fieldvalue) {
			$input[$fieldkey]['value'] = $fieldvalue;
		}
	}


?>
<section class="content-header">
	<h1>Module :: Pages</h1>
	<ol class="breadcrumb"> 
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><?php echo anchor(custom_constants::admin_page_listing_url, 'Pages'); ?></li>
	</ol>
</section>
<section class="content">
<div class="row">
	<div class="col-md-12">
		<?php if(isset($error)) {
			echo "<div class='alert alert-danger'>";
			echo "<p>" .$form_error."</p>";
			echo "</div>";
		}
		if($this->session->flashdata('message')!== FALSE) {
			$msg = $this->session->flashdata('message');?>
			<div class="<?php echo $msg['class'];?>">
				<?php echo $msg['message'];?>
			</div>
		<?php }
		?>
		<div class="nav-tabs-custom">
			<!-- <ul class="nav nav-tabs">
				<li class="<?php if($tab=="personal_info"){echo "active";} ?>"><a href="#personal_info" data-toggle="tab">Edit Page</a></li>
				
			</ul> -->
			<div class="tab-content">
				<div class="tab-pane <?php if($tab == 'personal_info'){ echo "active";}?>" id="personal_info">
					<?php echo form_open_multipart(custom_constants::edit_page_url."/".$id, ['class' =>'form-horizontal', 'id' => 'pages']); ?>
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Existing Page</h3>
						</div><!-- /box-header -->
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
				                  <?php echo form_input($input['content']);?>
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
				                      <?php echo form_hidden($input['featured_image2']); ?>
				                      <?php echo form_error('featured_image'); ?>
				                      <img src="<?php echo !empty($value_posted['pages']['featured_image'])?base_url().'/assets/uploads/featured_images/'. $value_posted['pages']['featured_image']:base_url().'assets/uploads/profile_images/defaultm.jpg'; ?>" height="100px" width="100px">
				                  </div>
				                </div>
				              </div>     
				            </div><!-- /row -->
						</div><!-- /box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-info pull-left">Update</button>
							<?php echo nbs(3); ?>
							<button type="reset" class="btn btn-info">Reset</button>
						</div><!-- /box-footer -->
					</div><!-- /box box-info -->
					<?php echo form_close(); ?>
				</div> 
				
			</div>
		</div><!-- /nav-tabs-custom -->
	</div><!-- /col-md-12 -->
</div>
</section>