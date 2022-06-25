<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<style>
		th {
			text-align: right;
			background-color: #F6F6F6;
		}
	</style>
</head>
<body>
	<?php echo $this->load->view($content); ?>
</body>
</html>