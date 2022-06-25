<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="">
<?php 

//echo "hello";
/*print_r($modules);
print_r($methods);
exit;*/
if(isset($modules)){
	foreach($modules as $index=>$value)
	{ 
		//print_r($modules);exit;
		echo Modules::run($modules[$index]."/".$methods[$index]);
	}
}else{
	echo $this->load->view($content);
}
    ?>
</div> 
