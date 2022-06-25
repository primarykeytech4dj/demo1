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
<div class="register-box">
  <div class="register-logo">
    <a href="<?php echo base_url(); ?>"><b>ERP </b>Version 1.0</a>
  </div>
<!-- <?php //$this->load->view($content_view);?> -->
<?php
foreach($modules as $index=>$value)
	{
		echo Modules::run($modules[$index]."/".$methods[$index]);
	}
	?>
</div>