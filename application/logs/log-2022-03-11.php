<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-11 19:12:02 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `shipping_charge`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', '998.0', '0.00', '998.0', 393, '2022-03-11', '21-22', 1, NULL, 0, 'E/O/21-22/0029803', '2022-03-11 19:12:02', '2022-03-11 19:12:02')
ERROR - 2022-03-11 19:12:03 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
