<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-11-09 17:50:12 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 315, 315, 640, '2021-11-09', '21-22', 1, NULL, 0, 'E/O/21-22/0015261', '2021-11-09 17:50:12', '2021-11-09 17:50:12')
ERROR - 2021-11-09 17:50:12 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
