<?php
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*echo '<pre>';
print_r($_SESSION);
exit;*/
?>

<header class="main-header">
    <!-- Logo -->
    <?php echo anchor('admin', '<span class="logo-mini">'.img(assets_url().'favicon.png').'</span><span class="logo-lg">'.img(content_url('uploads/profile_images/'). $logo).'</span>', ['class'=>'logo', 'style'=>'240px']) ?>
    
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo content_url();?>uploads/profile_images/<?php echo  $this->session->userdata('profileImage'); ?>" class="user-image" alt="Logout">
              <span class="hidden-xs"><?php echo $this->session->userdata('name'); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo content_url();?>uploads/profile_images/<?php echo  $this->session->userdata('profileImage'); ?>" class="img-circle" alt="<?php echo $this->session->userdata('name'); ?>">

                <p>
                  <?php echo $this->session->userdata('name'); ?>
                  <small><?php echo $this->session->userdata('logged_in_since'); ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <!--<div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>-->
                <div class="pull-right">
                  <?php echo anchor(custom_constants::logout_url, 'Sign Out', ['class'=>'btn btn-default btn-flat']);  ?>
                  <!-- <a href="<?php //echo custom_constants::logout_url; ?>" class="btn btn-default btn-flat">Sign out</a> -->
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo content_url();?>uploads/profile_images/<?php echo  $this->session->userdata('profileImage'); ?>" class="img-circle" alt="<?php echo $this->session->userdata('name'); ?>">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('name'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php //  
      //$current_url =& get_instance(); //  get a reference to CodeIgniter
      /*echo $this->router->fetch_class().'<br>'; // for Class name or controller
      echo $this->router->fetch_method().'<br>';
      echo $this->router->fetch_module();exit;*/ ?>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <?php 
        //print_r($this->session->userdata('roles'));exit;
        /*if((NULL!==$this->session->userdata('roles')) && in_array(1, $this->session->userdata('roles'))){
            echo '<li>'.anchor('#', 'Customer Panel').'</li>';
        }else{*/
          //print_r($this->session->userdata('access_menu'));exit;
          //print_r($this->session->userdata('access_menu'));
          $menus = $this->pktlib->create_nested_menu(0, 2, $this->session->userdata('access_menu'));
          /*echo '<pre>';
          print_r($menus);
          exit;*/

          
          echo $this->pktlib->createMenu($menus);
          
        ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <?php 
/*echo '<pre>';
  print_r($this->session->userdata());exit;*/ ?>