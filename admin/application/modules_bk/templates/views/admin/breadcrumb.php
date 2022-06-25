<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1><?php echo (isset($module) ? $module : 'Module'); ?> :: <?php echo (isset($meta_title) ? $meta_title : ''); ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active"><i class="fa fa-tags m-r-5"></i><?php echo (isset($meta_title) ? $meta_title : ''); ?></li>
    </ol>
</section>