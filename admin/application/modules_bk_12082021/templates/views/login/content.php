<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<!-- <div id="content">
	<?php
	
	/*foreach($modules as $index=>$value)
	{
		echo Modules::run($modules[$index]."/".$methods[$index]);
	}*/
	
	?>
</div>  -->
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo base_url(); ?>"><b>ERP </b>Version <?php echo custom_constants::current_version; ?></a>
  </div>
<!-- <?php //$this->load->view($content_view);?> -->
<?php
foreach($modules as $index=>$value)
	{
		echo Modules::run($modules[$index]."/".$methods[$index]);
	}
	?>
</div>