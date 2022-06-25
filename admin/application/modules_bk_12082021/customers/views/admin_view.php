 <?php $view = 'view'; ?> 
<section class="content-header">
  <h1>
    Customer Profile
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><?php echo anchor(custom_constants::admin_customer_listing_url,'Customers');?></li>
    <li class="active">Details</li>
  </ol>
</section>

<section class="content">

  <div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-primary">
        <?php echo anchor(custom_constants::edit_customer_url.'/'.$user['id'], '<i class="fa fa-edit pull-right"></i>'); ?>
        <div class="box-body box-profile">
          <?php //print_r($user); ?>
          <?php echo img(['src'=>content_url().'uploads/profile_images/'.((!empty($user['profile_img']))?$user['profile_img']:'default.png'), 'class'=>'profile-user-img img-responsive img-circle', 'alt'=>$user['first_name']]); ?>

          <h3 class="profile-username text-center"><?php echo $user['first_name']." ".$user['middle_name']." ".$user['surname']; ?></h3>

        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <!-- About Me Box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">About Me</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <strong><i class="fa fa-ambulance margin-r-5"></i> Blood Group</strong>

          <p class="text-muted">
            <?php echo "<i class='fa fa-heartbeat'></i> ".$user['blood_group']; ?>
          </p>
          <hr>
          <strong><i class="fa fa-fw fa-mobile margin-r-5"></i> Contact Details</strong>

          <p class="text-muted">
            <?php echo "<i class='fa fa-fw fa-phone-square'></i> ".$user['contact_1']." / ".$user['contact_2']; ?>
          </p>
          <p class="text-muted">
            <?php echo "<i class='fa fa-envelope'></i> ".$user['primary_email']." <br /> <i class='fa fa-envelope'></i> ".$user['secondary_email']; ?>
          </p>
          <hr>
          <strong><i class="glyphicon glyphicon-file"></i> Pan No</strong>

          <p class="text-muted">
            <?php echo "<i class='glyphicon glyphicon-pencil'></i> ".$user['pan_no']; ?>
          </p>
          <hr>
          <strong><i class="glyphicon glyphicon-file"></i> GST No.</strong>

          <p class="text-muted">
            <?php echo "<i class='glyphicon glyphicon-pencil'></i> ".$user['gst_no']; 
            ?>
          </p>
          <hr>

        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="<?php if($tab=="address"){echo "active";} ?>"><a href="#address" data-url="address/addressTab/customers/<?=$user['id']?>/<?=$view?>" class = "sitetab" data-target="address" data-toggle="tab">Address</a></li>
          <!-- <?php if($user['has_multiple_sites']){ ?>
          <li><a href="#sites" data-toggle="tab">Sites</a></li>
          <?php } ?> -->
          <?php if(isset($user['has_multiple_sites'])){
          ?>
          <li class="<?php if($tab=="sites"){echo "active";} ?>"><a href="#sites" class = "sitetab" data-toggle="tab" data-url="customers/siteTab/<?=$user['id']?>/<?=$view?>" data-target="sites">Sites</a></li>
          <?php } ?>
          <li class="<?php if($tab=="bankaccount"){echo "active";} ?>"><a href="#bankaccount" class = "sitetab" data-toggle="tab" data-url="customers/admin_bank_view/<?=$user['id']?>/<?=$view?>" data-target="bankaccount">Bank Account</a></li>
          <li class=""><a href="#serviceVisits" data-toggle="tab">Service Visits</a></li>
          <li class="<?php if($tab=="orders"){echo "active";} ?>"><a class="sitetab" href="#orders" data-url="customers/admin_order_view/<?=$user['id']?>" data-target="orders" data-toggle="tab">Orders</a></li>
          <!-- <li><a href="#other_details" data-toggle="tab">Other Details</a></li>
          <li><a href="#activity" data-toggle="tab">Activity</a></li>
          <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
          <li><a href="#settings" data-toggle="tab">Settings</a></li> -->
        </ul>
        <div class="tab-content">
          <div class="active tab-pane" id="address">
            <!-- <?php echo $addressList; ?> -->
          </div>
          <div class="tab-pane" id="orders">
          </div>
          <div class="tab-pane" id="bankaccount">
          </div>
          <?php if($user['has_multiple_sites']){ ?>
            <div class="tab-pane" id="sites">
              <!-- <?php echo $sites; ?> -->
            </div>
          <?php } ?>  
          <div class="tab-pane" id="other_details">
            <?php echo $otherDetailsList; ?>
          </div>
          <div class="tab-pane" id="activity">
            <!-- Post -->
            <div class="post">
              <div class="user-block">
                <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                    <span class="username">
                      <a href="#">Jonathan Burke Jr.</a>
                      <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                    </span>
                <span class="description">Shared publicly - 7:30 PM today</span>
              </div>
              <!-- /.user-block -->
              <p>
                Lorem ipsum represents a long-held tradition for designers,
                typographers and the like. Some people hate it and argue for
                its demise, but others ignore the hate as they create awesome
                tools to help create filler text for everyone from bacon lovers
                to Charlie Sheen fans.
              </p>
              <ul class="list-inline">
                <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                </li>
                <li class="pull-right">
                  <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                    (5)</a></li>
              </ul>

              <input class="form-control input-sm" type="text" placeholder="Type a comment">
            </div>
            <!-- /.post -->

            <!-- Post -->
            <div class="post clearfix">
              <div class="user-block">
                <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                    <span class="username">
                      <a href="#">Sarah Ross</a>
                      <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                    </span>
                <span class="description">Sent you a message - 3 days ago</span>
              </div>
              <!-- /.user-block -->
              <p>
                Lorem ipsum represents a long-held tradition for designers,
                typographers and the like. Some people hate it and argue for
                its demise, but others ignore the hate as they create awesome
                tools to help create filler text for everyone from bacon lovers
                to Charlie Sheen fans.
              </p>

              <form class="form-horizontal">
                <div class="form-group margin-bottom-none">
                  <div class="col-sm-9">
                    <input class="form-control input-sm" placeholder="Response">
                  </div>
                  <div class="col-sm-3">
                    <button type="submit" class="btn btn-danger pull-right btn-block btn-sm">Send</button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.post -->

            <!-- Post -->
            <div class="post">
              <div class="user-block">
                <img class="img-circle img-bordered-sm" src="../../dist/img/user6-128x128.jpg" alt="User Image">
                    <span class="username">
                      <a href="#">Adam Jones</a>
                      <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                    </span>
                <span class="description">Posted 5 photos - 5 days ago</span>
              </div>
              <!-- /.user-block -->
              <div class="row margin-bottom">
                <div class="col-sm-6">
                  <img class="img-responsive" src="../../dist/img/photo1.png" alt="Photo">
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                  <div class="row">
                    <div class="col-sm-6">
                      <img class="img-responsive" src="../../dist/img/photo2.png" alt="Photo">
                      <br>
                      <img class="img-responsive" src="../../dist/img/photo3.jpg" alt="Photo">
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <img class="img-responsive" src="../../dist/img/photo4.jpg" alt="Photo">
                      <br>
                      <img class="img-responsive" src="../../dist/img/photo1.png" alt="Photo">
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <ul class="list-inline">
                <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                </li>
                <li class="pull-right">
                  <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                    (5)</a></li>
              </ul>

              <input class="form-control input-sm" type="text" placeholder="Type a comment">
            </div>
            <!-- /.post -->
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="timeline">
            <!-- The timeline -->
            <ul class="timeline timeline-inverse">
              <!-- timeline time label -->
              <li class="time-label">
                    <span class="bg-red">
                      10 Feb. 2014
                    </span>
              </li>
              <!-- /.timeline-label -->
              <!-- timeline item -->
              <li>
                <i class="fa fa-envelope bg-blue"></i>

                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                  <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                  <div class="timeline-body">
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                    weebly ning heekya handango imeem plugg dopplr jibjab, movity
                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                    quora plaxo ideeli hulu weebly balihoo...
                  </div>
                  <div class="timeline-footer">
                    <a class="btn btn-primary btn-xs">Read more</a>
                    <a class="btn btn-danger btn-xs">Delete</a>
                  </div>
                </div>
              </li>
              <!-- END timeline item -->
              <!-- timeline item -->
              <li>
                <i class="fa fa-user bg-aqua"></i>

                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                  <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                  </h3>
                </div>
              </li>
              <!-- END timeline item -->
              <!-- timeline item -->
              <li>
                <i class="fa fa-comments bg-yellow"></i>

                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                  <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                  <div class="timeline-body">
                    Take me to your leader!
                    Switzerland is small and neutral!
                    We are more like Germany, ambitious and misunderstood!
                  </div>
                  <div class="timeline-footer">
                    <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                  </div>
                </div>
              </li>
              <!-- END timeline item -->
              <!-- timeline time label -->
              <li class="time-label">
                    <span class="bg-green">
                      3 Jan. 2014
                    </span>
              </li>
              <!-- /.timeline-label -->
              <!-- timeline item -->
              <li>
                <i class="fa fa-camera bg-purple"></i>

                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                  <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                  <div class="timeline-body">
                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                  </div>
                </div>
              </li>
              <!-- END timeline item -->
              <li>
                <i class="fa fa-clock-o bg-gray"></i>
              </li>
            </ul>
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="settings">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">Name</label>

                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputName" placeholder="Name">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                </div>
              </div>
              <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">Name</label>

                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputName" placeholder="Name">
                </div>
              </div>
              <div class="form-group">
                <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                <div class="col-sm-10">
                  <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-danger">Submit</button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

</section>
<!-- /.content-->