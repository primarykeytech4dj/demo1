<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-01-07 14:37:44 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `shipping_charge`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 1235, '0.00', 1235, 1674, '2022-01-07', '21-22', 1, NULL, 0, 'E/O/21-22/0021388', '2022-01-07 14:37:44', '2022-01-07 14:37:44')
ERROR - 2022-01-07 14:37:44 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
