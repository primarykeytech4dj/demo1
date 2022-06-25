<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-12-26 12:31:56 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `shipping_charge`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 1255, '0.00', 1255, 1922, '2021-12-26', '21-22', 1, NULL, 0, 'E/O/21-22/0019949', '2021-12-26 12:31:56', '2021-12-26 12:31:56')
ERROR - 2021-12-26 12:31:56 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
