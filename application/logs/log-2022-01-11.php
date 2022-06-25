<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-01-11 12:47:52 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `shipping_charge`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 1600, '0.00', 1600, 556, '2022-01-11', '21-22', 1, NULL, 0, 'E/O/21-22/0021802', '2022-01-11 12:47:52', '2022-01-11 12:47:52')
ERROR - 2022-01-11 12:47:52 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
